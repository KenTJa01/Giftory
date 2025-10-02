@extends('layouts.main')

@section('containter')
<style>
    .d-none{
        display: none;
    }
    .read-only{
        pointer-events: none;
        background-color: yellow;
    }
    .field-icon {
        float: right;
        margin-right: 10px;
        margin-top: -25px;
        position: relative;
        z-index: 2;
    }
</style>

<nav class="navbar navbar-expand-lg navbar-light" id="navbar-partial">
    <div class="container-fluid">

        <button type="button" id="sidebarCollapse" class="btn btn-primary">
            <i class="fa fa-bars"></i>
        </button>

        <h4 style="margin-left: 15px; margin-top: 10px; font-weight: 500; color: #424976">Receiving</h4>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="nav navbar-nav ml-auto">
                <li class="nav-item">
                    <font style="font-weight: 500; font-size: 14px; color: #424976">
                      <svg xmlns="http://www.w3.org/2000/svg" width="19" height="19" fill="currentColor" class="bi bi-person-circle" viewBox="0 0 16 16" style="margin-top: -5px; margin-right: 7px">
                          <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0z"/>
                          <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1z"/>
                      </svg>
                      {{ auth()->user()->name }}
                  </font>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="row">

    <div class="col text-center" id="title">
        <h2 style="font-weight: 500">Change Password</h2>
    </div>

    <img src="svg/circleLeft.svg" id="circleLeft" alt="">

    <div class="container text-center mt-3" align="center" style="max-width:100%; margin-top: -20px">

        <form id="main_form">

            <img src="svg/circleRight.svg" id="circleRight" alt="">

            <div class="container" id="divTable" style="max-width:70%;">

                <div class="container" id="divTable-white" style="max-width:100%;">

                    <div class="row" style="margin: 5px">
                        <div class="col">
                            <table align="center" style="color: black">
                                <tr>
                                    <td align="left" width="150px">Current Password</td>
                                    <td>
                                        <input type="password" class="form-control" name="current_pw" id="current_pw" style="width: 350px">
                                        <span toggle="#current_pw" class="fa fa-fw fa-eye field-icon toggle-password"></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="left" width="150px">New Password</td>
                                    <td>
                                        <input type="password" class="form-control" name="new_pw" id="new_pw" style="width: 350px">
                                        <span toggle="#new_pw" class="fa fa-fw fa-eye field-icon toggle-password"></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="left" width="150px">Confirm Password</td>
                                    <td>
                                        <input type="password" class="form-control" name="confirm_pw" id="confirm_pw" style="width: 350px">
                                        <span toggle="#confirm_pw" class="fa fa-fw fa-eye field-icon toggle-password"></span>
                                    </td>
                                </tr>
                            </table>

                        </div>
                    </div>

                </div>
            </div>

            <button type="button" class="btn btn-primary" id="btn-submit">Submit</button>

        </form>

    </div>

</div>

<script>
    $(".toggle-password").click(function() {

        $(this).toggleClass("fa-eye fa-eye-slash");
        var input = $($(this).attr("toggle"));
        if (input.attr("type") == "password") {
            input.attr("type", "text");
        } else {
            input.attr("type", "password");
        }
    });

    $(document).on('click', '#btn-submit', function(event) {

        var currPw = $('#current_pw').val();
        var newPw = $('#new_pw').val();
        var confirmPw = $('#confirm_pw').val();

        $.ajax({
            type: 'POST',
            url: "{{ url('/post-change-pass-submit') }}",
            dataType: 'json',
            data: {
                current_pw: currPw,
                new_pw: newPw,
                confirm_pw: confirmPw,
            },
            success: function(response) {
                console.log(response);
                // return;
                // window.dialog_add.close();
                return Swal.fire({
                    title: response.title,
                    text: response.message,
                    timer: 5000,
                    icon: "success",
                    timerProgressBar: true,
                    showConfirmButton: true,
                    willClose: () => {
                        if (typeof response.route !== "undefined") {
                            window.location.href = response.route;
                        }
                    },
                });
            },
            error: function(error) {

                console.log(error.responseJSON);
                Swal.fire({
                    icon: 'error',
                    title: "Error",
                    text: error.responseJSON.message ?? 'Failed submit receiving request',
                    target: document.getElementById('dialog_add'),
                });
                $(".submitAdd").prop('disabled', false);
            },
        });
        event.preventDefault();

    });

</script>

@endsection
