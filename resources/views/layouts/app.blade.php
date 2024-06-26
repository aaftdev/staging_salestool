<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="{{ asset('favicon.png') }}" rel="icon">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    @yield('meta')

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <style>
        .table-container {
            overflow-x: auto;
            width: 100%;
        }
        .btn-group{
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            grid-gap: 5px;
        }
    </style>

    @yield('styles')

    @livewireStyles
</head>

<body>
    <div id="app">
        <x-partials.alert />
        <x-include.navbar />
        <x-include.sidebar />
        <main class="py-4">
            @yield('content')
        </main>
    </div>

    @yield('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.sidebar-list-dropdown').click(function(){
                $('.sidebar-list-dropdown-title').each(function(){
                    if($(this).hasClass('active')){
                        $(this).removeClass('active');
                    }
                });
                $('.sidebar-list-dropdown-content').each(function(){
                    if(!$(this).hasClass('d-none')){
                        $(this).addClass('d-none');
                    }
                });
                $(this).children('.sidebar-list-dropdown-title').addClass('active');
                $(this).children('.sidebar-list-dropdown-content').removeClass('d-none');
            });

            $('#navbar .btn-menu-top').click(function(){
                if($(this).children('.bx-x').hasClass('d-none')){
                    $(this).children('.bx-x').removeClass('d-none');
                    $(this).children('.bx-menu-alt-left').addClass('d-none');
                    $('#sidebar').css({"width": "50px"});
                    $('#sidebar .sidebar-list-title').css({'display': 'none'});
                    $('#sidebar .sidebar-list span').css({'display': 'none'});
                    $('#sidebar .sidebar-list-dropdown-content').css({
                        'position': 'absolute',
                        'left':' 50px',
                        'width':' 200px',
                        'background-color':' #252b3b',
                        'top':' 0',
                        'left':' 40px',
                    });
                    $('#navbar').css({'width': 'calc(100% - 50px)'});
                    $('main').css({'width': 'calc(100% - 50px)', 'left': '50px'});
                }else{
                    $(this).children('.bx-x').addClass('d-none');
                    $(this).children('.bx-menu-alt-left').removeClass('d-none');
                    $('#sidebar').css({"width": "200px"});
                    $('#sidebar .sidebar-list-title').css({'display': 'block'});
                    $('#sidebar .sidebar-list span').css({'display': 'inline-block'});
                    $('#sidebar .sidebar-list-dropdown-content').css({
                        'position': 'relative',
                        'left':' 0px',
                        'width':' 100%',
                        'background-color':'transparent',
                        'top':' 0',
                        'left':' 0px',
                    });
                    $('#navbar').css({'width': 'calc(100% - 200px)'});
                    $('main').css({'width': 'calc(100% - 200px)', 'left' : '200px'});
                }
            });
            // $('.table-show').DataTable();
            $('[data-toggle="tooltip"]').tooltip();

        });
    </script>

    @livewireScripts
</body>

</html>