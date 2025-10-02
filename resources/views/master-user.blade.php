@extends('layouts.main')

@section('containter')

<style>
.select2-container .select2-selection {
    height: 200px;
    overflow: scroll;
}

</style>

<nav class="navbar navbar-expand-lg navbar-light" id="navbar-partial">
    <div class="container-fluid">

        <button type="button" id="sidebarCollapse" class="btn btn-primary">
            <i class="fa fa-bars"></i>
        </button>

        <h4 style="margin-left: 15px; margin-top: 10px; font-weight: 500; color: #424976">Data Master</h4>

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
        <h2 style="font-weight: 500">User Management</h2>
    </div>

    <img src="svg/circleLeft.svg" id="circleLeft" alt="">

    <div class="container mt-3 text-center " align="center" style="max-width:100%;">

        <img src="svg/circleRight.svg" id="circleRight" alt="">

        <div class="container" id="divTable" style="max-width:100%;">

            <div class="row">

                @if ( $add_user_allowed )
                    {{-- BUTTON ADD --}}
                    <div class="col">
                        <button class="CartBtn" id="btnAddUser">
                            <span class="IconContainer">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-circle-fill" viewBox="0 0 16 16">
                                    <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8.5 4.5a.5.5 0 0 0-1 0v3h-3a.5.5 0 0 0 0 1h3v3a.5.5 0 0 0 1 0v-3h3a.5.5 0 0 0 0-1h-3v-3z"/>
                                </svg>
                            </span>
                            <p class="text mt-3">Add</p>
                        </button>
                    </div>
                @else
                    <div class="col" style="height: 60px"></div>
                @endif

                <div class="col">

                    <div class="container d-flex" style="margin-top: 10px">

                        <input type="text" class="form-control input-filter" name="usernameFilter" id="usernameFilter" placeholder="Username">

                        <input type="text" class="form-control input-filter" name="nameFilter" id="nameFilter" placeholder="Name">

                        <button type="submit" class="btn" id="buttonSearch">
                            Search
                        </button>

                    </div>

                </div>

            </div>


            <table class="table myTable table-bordered border-dark align-middle" id="tableData" style="width:100%; background-color:white;">
                <thead class="thead-dark">
                    <tr class="text-center" style="width: 100%; background-color: #35384B; color: white;">
                        <td>No</td>
                        <td>Username</td>
                        <td>Name</td>
                        <td>Active</td>
                        <td>Action</td>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>

    </div>

</div>

<!-- Modal Add New User -->
<dialog id="dialog_add">
    <h4>Add New User</h4>

    <div id="formAddModal">
        <table align="center">
            <tr>
                <td style="width: 100px">Username</td>
                <td><input type="text" class="form-control inputAdd" name="username" id="username" placeholder="Enter username" required></td>
            </tr>
            <tr>
                <td>Password</td>
                <td><input type="password" class="form-control inputAdd" name="password" id="password" placeholder="Enter password" required></td>
            </tr>
            <tr>
                <td>Name</td>
                <td><input type="text" class="form-control inputAdd" name="name" id="name" placeholder="Enter name" required></td>
            </tr>
            <tr>
                <td>Profile</td>
                <td>
                    <select class="form-control inputAdd" name="select_profile" id="select_profile" required>
                        <option value="">Select profile</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td>List Site</td>
                <td>
                    <select class="form-control inputAdd" name="select_site[]" id="select_site" placeholder="true" required></select><br>
                    <input style="width: 15px; margin-top: 0px;" type="checkbox" id="select-all-stores">
                    <div style="margin-left: 25px; margin-top: -25px; margin-bottom: 10px; font-size: 13px;">
                        Select all store
                    </div>
                </td>
            </tr>
            <tr>
                <td>Flag Active</td>
                <td>
                    <div class="checkbox-wrapper-51">
                        <input id="cbx-51" type="checkbox" name="is_active" value="1" checked>
                        <label class="toggle" for="cbx-51">
                            <span>
                                <svg viewBox="0 0 10 10" height="10px" width="10px">
                                    <path d="M5,1 L5,1 C2.790861,1 1,2.790861 1,5 L1,5 C1,7.209139 2.790861,9 5,9 L5,9 C7.209139,9 9,7.209139 9,5 L9,5 C9,2.790861 7.209139,1 5,1 L5,9 L5,1 Z"></path>
                                </svg>
                            </span>
                        </label>
                    </div>
                </td>
            </tr>
        </table>

    </div>

    <div class="row text-right">
        <div class="col">
            <button class="btn clearAdd" id="btn-clear-modal">Clear</button>
            <button class="btn submitAdd" id="btn-submit-modal">Submit</button>
        </div>

    </div>

    <button onclick="window.dialog_add.close();" aria-label="close" class="x">❌</button>
</dialog>

<!-- Modal Edit User -->
<dialog id="dialog_edit">
    <h4>Edit User</h4>

    <div id="formAddModal">
        <table align="center">
            <input type="hidden" name="id" id="user_id_edit">
            <tr>
                <td>Username</td>
                <td><input type="text" class="form-control inputAdd" name="username_edit" id="username_edit" readonly></td>
            </tr>
            {{-- <tr>
                <td>New Password</td>
                <td><input type="password" class="form-control inputAdd" name="password_edit" id="password_edit" placeholder="New password"></td>
            </tr> --}}
            <tr>
                <td>Name</td>
                <td><input type="text" class="form-control inputAdd" name="name_edit" id="name_edit" placeholder="Enter name" required></td>
            </tr>
            <tr>
                <td>Profile</td>
                <td>
                    <select class="form-control inputAdd" name="select_profile_edit" id="select_profile_edit" required>
                        <option value="">Select profile</option>
                    </select>
                </td>
            </tr>
            {{-- <tr>
                <td>Home Site</td>
                <td>
                    <select class="form-control inputAdd" name="select_hm_site_edit" id="select_hm_site_edit" required>
                        <option value="">Select site</option>
                    </select>
                </td>
            </tr> --}}
            <tr>
                <td>List Site</td>
                <td>
                    <select class="form-control inputAdd select_site_edit" name="select_site_edit[]" id="select_site_edit" required>
                        {{-- <option value="">Select site</option> --}}
                    </select><br>
                    <input style="width: 15px; margin-top: 0px;" type="checkbox" id="select-all-stores-edit">
                    <div style="margin-left: 25px; margin-top: -25px; margin-bottom: 10px; font-size: 13px;">
                        Select all store
                    </div>
                </td>
            </tr>
            <tr>
                <td>Flag Active</td>
                <td>
                    <div class="checkbox-wrapper-51">
                        <input name="is_active" id="cbx-52" type="checkbox" class="flag_edit" value="1">
                        <label class="toggle" for="cbx-52">
                            <span>
                                <svg viewBox="0 0 10 10" height="10px" width="10px">
                                    <path d="M5,1 L5,1 C2.790861,1 1,2.790861 1,5 L1,5 C1,7.209139 2.790861,9 5,9 L5,9 C7.209139,9 9,7.209139 9,5 L9,5 C9,2.790861 7.209139,1 5,1 L5,9 L5,1 Z"></path>
                                </svg>
                            </span>
                        </label>
                    </div>
                </td>
            </tr>
        </table>

    </div>

    <div class="row text-right">
        <div class="col">
            <button class="btn submitEdit" id="btn-submit-modal">Submit</button>
        </div>

    </div>

    <a onclick="window.dialog_edit.close();" aria-label="close" class="x">❌</a>
</dialog>

{{-- Delete User --}}
{{-- <form action="/delete-user" method="post" id="deleteForm">
    @csrf
    <input type="hidden" name="id" id="userID">
</form> --}}

{{-- <link rel="stylesheet" href="//cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
<script src="//cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
 --}}

<script>

    $(document).ready(function () {
        getListUser();
    });

    // GLOBAL SETUP CSRF
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('#buttonSearch').on('click', function() {
        getListUser();
    });

    function getListUser() {
        $('#buttonSearch').prop('disabled', true);
        tableData.ajax.reload();
        $('#buttonSearch').prop('disabled', false);
    }

    var tableData = $("#tableData").DataTable({
        serverSide: true,
        processing: true,
        paginate: true,
        autoWidth: true,
        scrollCollapse: true,
        dom: 'rtip',
        ajax: {
            type: 'POST',
            url: `{{ route("get-user-list-datatable") }}`,
            data: function (d) {
                d.username = $('#usernameFilter').val();
                d.name = $('#nameFilter').val();
            },
        },
        columns: [
            {
                data:'DT_RowIndex',
                name:'DT_RowIndex',
                orderable:false,
                searchable:false
            },
            {
                data: 'username',
                name: 'username',
            },
            {
                data: 'name',
                name: 'name',
            },
            {
                data: 'null',
                render: function(data, type, row) {
                    if (row.is_active == 1) {
                        return "Active";
                    } else {
                        return "Non-Active";
                    }
                }
            },
            // {
            //     data: null,
            //     render: function(data, type, row) {
            //         return row.to_site_code+' - '+row.to_store_code;
            //     }
            // },
            {
                data: 'actions',
                name: 'actions',
                orderable: false,
                searchable: false,
                className: 'text-center',
            },
        ],
        order: [[1, 'asc']],
        columnDefs: [
            { className: "dt-center", targets: [0,1,2,3,4] }
        ],
        language: {
            loadingRecords: '&nbsp;',
            processing: '<div class="spinner" style="z-index: 1;"></div>',
            zeroRecords: "No data found",
        },
    });


    $(document).on('click', '#btnAddUser', function(event) {
        getListSite();
        getListProfile();

        $('#select_site').select2({
            dropdownParent: $("#dialog_add"),
            // placeholder: "Select site",
            placeholder: {
                id: '-1', // the value of the option
                textw: 'Select an option'
            },
            // allowClear: true,
            multiple: true
        });

        $("#username").val("");
        $("#password").val("");
        $("#name").val("");

        window.dialog_add.showModal();

    });


    $(document).on('click', '#btnEditUser', function(event) {
        event.preventDefault();

        $('#select_site_edit').select2({
            dropdownParent: $("#dialog_edit"),
            // placeholder: "Select site",
            // allowClear: true,
            multiple: true
        });

        const data = $(this).data('u');

        $.ajax({
            type: 'GET',
            url: "{{ url('/get-old-data-user-edit') }}",
            dataType: 'json',
            data: {
                user_id: data,
            },
            success: function(response) {
                // console.log(response);
                $('#user_id_edit').val(response.id);
                $('#username_edit').val(response.username);
                $('#password_edit').val(response.password);
                $('#name_edit').val(response.name);
                if (response.is_active == 1){
                    $('.flag_edit').attr('checked', true);
                } else if (response.is_active == 0){
                    $('.flag_edit').attr('checked', false);
                }
                getListProfileEdit(response.profile_id);
                getListSiteEdit(response.id);
            },
        });

        window.dialog_edit.showModal();

    });

    $('#select-all-stores').change(function() {
        if ($(this).is(':checked')) {
            console.log("kajshdkj");
            $('#select_site option').prop('selected', true);
            $('#select_site').trigger('change');
        } else {
            $('#select_site option').prop('selected', false);
            $('#select_site').trigger('change');
        }
    });

    $('#select-all-stores-edit').change(function() {
        if ($(this).is(':checked')) {
            console.log("kajshdkj");
            $('#select_site_edit option').prop('selected', true);
            $('#select_site_edit').trigger('change');
        } else {
            $('#select_site_edit option').prop('selected', false);
            $('#select_site_edit').trigger('change');
        }
    });


    function getListSite() {
        $("#select_site").html("");
        $.ajax({
            type: 'GET',
            url: "{{ url('/get-all-user-site-permission') }}",
            dataType: 'json',
            data: {},
            success: function(response) {
                $.each(response,function(key, value)
                {
                    $("#select_site").append('<option value="' + value.site_id + '">' + value.store_code + ' - ' + value.site_description + '</option>');
                    // $("#select_hm_site").append('<option value="' + value.site_id + '">' + value.store_code + ' - ' + value.site_description + '</option>');
                });
            },
            error: function(error) {
                console.log(error.responseJSON);
                Swal.fire({
                    icon: 'error',
                    title: "Error",
                    text: error.responseJSON.message ?? 'Failed get list site from',
                });
            },
        });
    }


    function getListSiteEdit(siteId) {
        // $("#select_site_edit").html('<option value="" disabled>Select site</option>');
        $.ajax({
            type: 'GET',
            url: "{{ url('/get-all-site-master-user-edit') }}",
            dataType: 'json',
            data: {
                site_id: siteId,
            },
            success: function(response) {
                // console.log(response);
                getUserSite(response);
            },
        });
    }


    function getUserSite(data) {
        // console.log(data);
        $.ajax({
            type: 'GET',
            url: "{{ url('/get-all-user-site-permission') }}",
            dataType: 'json',
            data: {},
            success: function(response) {
                // console.log(response);
                $.each(response,function(key, value)
                {
                    // console.log(data[id])
                    // console.log(value.site_id+"|"+data.includes(value.site_id));
                    if ( data.includes(value.site_id) ) {
                        $("#select_site_edit").append('<option value="' + value.site_id + '" selected>' + value.store_code + ' - ' + value.site_description + '</option>');
                    } else {
                        $("#select_site_edit").append('<option value="' + value.site_id + '">' + value.store_code + ' - ' + value.site_description + '</option>');
                    }

                });
            },
            error: function(error) {
                console.log(error.responseJSON);
                Swal.fire({
                    icon: 'error',
                    title: "Error",
                    text: error.responseJSON.message ?? 'Failed get list site from',
                });
            },
        });
    }


    function getListProfile() {
        $("#select_profile").html('<option value="">Select profile</option>');
        $.ajax({
            type: 'GET',
            url: "{{ url('/get-all-profile-master-user') }}",
            dataType: 'json',
            data: {},
            success: function(response) {
                $.each(response,function(key, value)
                {
                        $("#select_profile").append('<option value="' + value.id + '">' + value.profile_code + ' - ' + value.profile_name + '</option>');
                });
            },
            error: function(error) {
                console.log(error.responseJSON);
                Swal.fire({
                    icon: 'error',
                    title: "Error",
                    text: error.responseJSON.message ?? 'Failed get list profile from',
                });
            },
        });
    }

    function getListProfileEdit(profileId) {
        $("#select_profile_edit").html('<option value="">Select profile</option>')
        $.ajax({
            type: 'GET',
            url: "{{ url('/get-all-profile-master-user') }}",
            dataType: 'json',
            data: {},
            success: function(response) {
                $.each(response,function(key, value)
                {
                    if ( value.id == profileId ) {
                        $("#select_profile_edit").append('<option value="' + value.id + '" selected>' + value.profile_code + ' - ' + value.profile_name + '</option>');
                    } else {
                        $("#select_profile_edit").append('<option value="' + value.id + '">' + value.profile_code + ' - ' + value.profile_name + '</option>');
                    }
                });
            },
            error: function(error) {
                console.log(error.responseJSON);
                Swal.fire({
                    icon: 'error',
                    title: "Error",
                    text: error.responseJSON.message ?? 'Failed get list profile from',
                });
            },
        });
    }


    $(document).on('click', '.clearAdd', function(event) {
        $('#username').val("");
        $('#password').val("");
        $('#name').val("");
        $('#select_profile').val("");
        $('#select_site').val("");
        $('#select_site').trigger("change");
    });


    $(document).on('click', '.submitAdd', function(event) {

        var usernameVal = $('#username').val();
        var passwordVal = $('#password').val();
        var nameVal = $('#name').val();
        var profile_id = $('#select_profile').val();
        // var home_site_id = $('#select_hm_site').val();
        var site_data = $('#select_site').val();
        var toggle = $('#cbx-51:checked').val();
        if ( toggle == 1 ) {
            var is_active = 1;
        } else {
            var is_active = 0;
        }

        // document.write(profile_id);

        $.ajax({
            type: 'POST',
            url: "{{ url('/post-user-req-submit') }}",
            dataType: 'json',
            data: {
                username: usernameVal,
                password: passwordVal,
                name: nameVal,
                profileId: profile_id,
                siteData: site_data,
                isActive: is_active
            },
            success: function(response) {
                window.dialog_add.close();
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


    $(document).on('click', '.submitEdit', function(event) {
        event.preventDefault();

        var idVal = $('#user_id_edit').val();
        var usernameVal = $('#username_edit').val();
        var nameVal = $('#name_edit').val();
        var profile_id = $('#select_profile_edit').val();
        var site_data = $('#select_site_edit').val();
        var toggle = $('#cbx-52:checked').val();
        if ( toggle == 1 ) {
            var is_active = 1;
        } else {
            var is_active = 0;
        }

        $.ajax({
            type: 'POST',
            url: "{{ url('/post-user-req-submit-edit') }}",
            dataType: 'json',
            data: {
                id_user: idVal,
                username: usernameVal,
                name: nameVal,
                profileId: profile_id,
                siteData: site_data,
                isActive: is_active
            },
            success: function(response) {
                window.dialog_edit.close();
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

                // console.log(error.responseJSON);
                Swal.fire({
                    icon: 'error',
                    title: "Error",
                    text: error.responseJSON.message ?? 'Failed submit receiving request',
                    target: document.getElementById('dialog_edit'),
                });
                $(".submitEdit").prop('disabled', false);
            },
        });
    });


    $(document).on('click', '.changePw', function(event) {
        const data = $(this).data('id');

        Swal.fire({
            icon: "warning",
            title: "Reset Password",
            text: "Are you sure want to reset the password?",
            showCancelButton: true,
            confirmButtonText: "Reset",
            confirmButtonColor: "#d33",
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: 'POST',
                    url: "{{ url('/post-user-req-reset-pw') }}",
                    dataType: 'json',
                    data: {
                        id_user: data,
                    },
                    success: function(response) {
                        // console.log(response);
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
                            text: error.responseJSON.message ?? 'Failed submit change password request',
                            target: document.getElementById('dialog_add'),
                        });
                        $(".submitAdd").prop('disabled', false);
                    },
                });
            }
        });


        event.preventDefault();
    });


</script>


@endsection
