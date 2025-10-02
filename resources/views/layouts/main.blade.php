<!doctype html>
<html lang="en">
    <head>
        <title>Giftory</title>
        <link rel="icon" href="">

        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">

        <link href="{{ asset('css/font-googleapis-poppins.css') }}" rel="stylesheet">

        <link rel="stylesheet" href="{{ asset('css/fontawesome-min.css') }}">
        <link rel="stylesheet" href="{{ asset('css/style.css') }}">
        <link rel="stylesheet" href="{{ asset('css/universal.css') }}">

        {{-- Sweet Alert --}}
        <script src="{{ asset('js/sweetalert.js') }}"></script>
        <script src="{{ asset('js/sweetalert-min.js') }}" crossorigin="anonymous"></script>

        {{-- DATATABLES --}}
        <link rel="stylesheet" href="{{ asset('css/datatable-min.css') }}">
        <script src="{{ asset('js/datatable.js') }}" crossorigin="anonymous"></script>
        <script src="{{ asset('js/datatable-min.js') }}"></script>

        {{-- Autocomplete --}}
        <link href="{{ asset('css/select2-min.css') }}" rel="stylesheet" />
        <script src="{{ asset('js/select2-min.js') }}"></script>

        {{-- Datepicker --}}
        <link rel="stylesheet" href="{{ asset('css/bootstrap-datepicker.css') }}">

        <link rel="stylesheet" href="{{ asset('css/jquery-ui.css') }}">
        <script src="{{ asset('js/jquery-ui.js') }}"></script>

    </head>
    <body>

		<div class="wrapper d-flex align-items-stretch">

            @include("partials.sidebar")

                    <!-- Page Content  -->
            <div id="content" class="p-4">

                {{-- @include("partials.navbar") --}}

                @yield('containter')

            </div>
		</div>

        {{-- <script src="{{ asset('js/jquery.min.js') }}"></script> --}}
        <script src="{{ asset('js/popper.js') }}"></script>
        <script src="{{ asset('js/bootstrap.min.js') }}"></script>
        <script src="{{ asset('js/main.js') }}"></script>

        <!-- Datetimepicker -->
        <script src="{{ asset('js/bootstrap-datepicker.js') }}"></script>
        <script src="{{ asset('js/moment.js') }}"></script>
        <script src="{{ asset('js/datetime-moment.js') }}"></script>
        <script>
            $(document).ready(function () {
                setActiveMenu();
            });
            
            $('.datepicker').datepicker({
                format: 'dd/mm/yyyy',
                autoclose: true,
                todayHighlight: true,
                orientation: "bottom",
            }).on('keypress', function(e) {
                e.preventDefault();
            });

            function setActiveMenu(){
                var menuId = $("li.active").parent().attr('id');

                if (menuId != undefined) {
                    document.getElementById(menuId).classList.add('show');
                }
            }
        </script>
    </body>
</html>
