<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">

    {{-- Google font --}}
    <link href="{{ asset('css/font-googleapis-poppins.css') }}" rel="stylesheet">

    {{-- bootstrap --}}
    <link href="{{ asset('css/bootstrap.css') }}" rel="stylesheet" crossorigin="anonymous">
    <script src="{{ asset('js/bootstrap.js') }}" crossorigin="anonymous"></script>
</head>
<body class="body">

    <div class="d-flex flex-column justify-content-center w-100 h-100">

        <div class="d-flex flex-column justify-content-center align-items-center">

            <div class="row" id="divLogin" style="margin-top: 80px">

                <div class="col text-center">
                    <img id="imgLogin" src="{{ asset('images/imgLogin.jpg') }}" alt="">
                </div>

                <div class="col text-center" style="margin-top: 25px">
                    <img id="giftoryLogin" src="{{ asset('svg/giftoryLogin.svg') }}" alt="">
                    <form action="/" method="post">
                        @csrf
                        <div>
                            <h5>Welcome! please Login first</h5>
                        </div>
                        <div class="form-group" style="margin-top: 20px">
                            <input type="text" class="form-control inputLogin" id="username" name="username" placeholder="Enter Username" required value="{{ old('username') }}">
                        </div>
                        <div class="form-group">
                            <input type="password" class="form-control inputLogin" id="password" name="password" placeholder="Enter password" required>
                        </div>
                        @if (session()->has('error'))
                            <div class="notif">
                                {{ session('error') }}
                            </div>
                        @endif

                        <button type="submit" class="btn" id="btnLogin"><b>LOGIN</b></button>
                        <div class="row mt-2">
                            <div class="col" style="color: rgb(184, 184, 184); font-size: 12px;">
                                <font>v 1.0</font>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <img src="{{ asset('svg/logoYogya.svg') }}" id="logoYogya" alt=""></div>
                        </div>
                    </form>

                </div>

            </div>

        </div>

    </div>

</body>
</html>
