@extends('layouts.main')

@section('containter')

<style>
       .plus, .minus {
            display: inline-block;
            background-repeat: no-repeat;
            background-size: 16px 16px !important;
            width: 16px;
            height: 16px;
            /*vertical-align: middle;*/
        }

        .plus {
            background-image: url(https://img.icons8.com/color/48/000000/plus.png);
        }

        .minus {
            background-image: url(https://img.icons8.com/color/48/000000/minus.png);
        }

        ul {
            list-style: none;
            padding: 0px 0px 0px 20px;
        }

            ul.inner_ul li:before {
                /* content: "├"; */
                font-size: 18px;
                margin-left: -11px;
                margin-top: -5px;
                vertical-align: middle;
                float: left;
                width: 8px;
                color: #41424e;
            }

            ul.inner_ul li:last-child:before {
                /* content: "└"; */
            }

        .inner_ul {
            padding: 0px 0px 0px 35px;
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
        <h2 style="font-weight: 500">List of Profile</h2>
    </div>

    <img src="svg/circleLeft.svg" id="circleLeft" alt="">

    <div class="container mt-3 text-center " align="center" style="max-width:100%;">

        <img src="svg/circleRight.svg" id="circleRight" alt="">

        <div class="container" id="divTable" style="max-width:100%;">

            @if ($is_req_prof_allowed)
                <div class="row">

                    {{-- BUTTON ADD --}}
                    <div class="col">
                        <button class="CartBtn" id="addButton" onclick="window.dialog_add.showModal();">
                            <span class="IconContainer">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-circle-fill" viewBox="0 0 16 16">
                                    <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8.5 4.5a.5.5 0 0 0-1 0v3h-3a.5.5 0 0 0 0 1h3v3a.5.5 0 0 0 1 0v-3h3a.5.5 0 0 0 0-1h-3v-3z"/>
                                </svg>
                            </span>
                            <p class="text mt-3">Add</p>
                        </button>
                    </div>

                </div>
            @endif

            <table class="table table-bordered border-dark align-middle" id="tableData" style="width:100%;">
                <thead class="thead-dark">
                    <tr class="text-center" style="width: 100%; background-color: #35384B; color: white;">
                        <td>No</td>
                        <td>Profile Code</td>
                        <td>Profile Name</td>
                        <td>Active</td>
                        <td>Action</td>
                    </tr>
                </thead>
                <tbody style="background-color: white">
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Add New Profile -->
<dialog id="dialog_add">
    <h4>Add New Profile</h4>

    <div id="formAddModal">
        <table align="center">
            <tr>
                <td>Code</td>
                <td><input type="text" class="form-control inputAdd" name="profile_code" id="profile_code" placeholder="Enter code" autocomplete="off" required></td>
            </tr>
            <tr>
                <td>Name</td>
                <td><input type="text" class="form-control inputAdd" name="profile_name" id="profile_name" placeholder="Enter name" required></td>
            </tr>
            <tr>
                <td>Location</td>
                <td>
                    <select name="location[]" id="location" class="form-control inputAdd" placeholder="Select Location">
                    </select>
                </td>
            </tr>
            <tr>
                <td>Status</td>
                <td>
                    {{-- <div class="row inputAdd">
                        <input type="radio" name="flag" class="flag" value="1" style="margin-right: 10px; margin-left:20px;" checked>Active
                        <input type="radio" name="flag" class="flag" value="2" style="margin-right: 10px; margin-left: 20px">Non-Active
                    </div> --}}

                    <div class="checkbox-wrapper-51" style="margin-left: -20px">
                        <input id="cbx-51" type="checkbox" name="flag" class="flag" checked>
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
            <tr>
                <td style="height: 40px;">Choose Menu</td>
                <td></td>
            </tr>
        </table>

        <div class="TreeViewProfile">
            <input type="checkbox" id="selectAll" style="margin-left: 20px" class="parent"> Select All Menu

            <div class="tree_main">
                <ul id="bs_main" class="main_ul">

                    {{-- LEVEL 1 --}}
                    @foreach ($menus as $menu)
                        <li id="bs_{{ $menu->id }}">
                            <span class="plus">&nbsp;</span>
                            <input type="checkbox" id="c_bs_{{ $menu->id }}" class="parent"/>
                            <span>{{ $menu->menu_name }}</span>
                            <ul id="bs_l_{{ $menu->id }}" style="display: none" class="sub_ul">

                                {{-- LEVEL 2 --}}
                                @foreach ($submenus as $sub)
                                    @if ($sub->menu_id == $menu->id)
                                        <li id="bf_{{ $sub->id }}">
                                            <span class="plus">&nbsp;</span>
                                            <input type="checkbox" id="c_bf_{{ $sub->id }}" name="menu[]" value="{{ $sub->id }}" class="submenu" />
                                            <span>{{ $sub->sub_menu_name }}</span>

                                            {{-- LEVEL 3 --}}
                                            <ul id="bf_l_{{ $sub->id }}" style="display: none" class="inner_ul viewtree">
                                                @foreach ($permissions as $p)
                                                    @if ($sub->id == $p->sub_menu_id)
                                                            <li id="io_{{ $p->id }}">
                                                            <input type="checkbox" id="c_io_{{ $p->id }}" value="{{ $p->id }}" class="profilePermission"/>
                                                            <span>{{ $p->key }}</span></li>
                                                    @endif
                                                @endforeach
                                            </ul>
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>

    <div class="row text-right">
        <div class="col">
            <button class="btn clear" id="btn-clear-modal">Clear</button>
            <button class="btn" id="btn-submit-modal">Submit</button>
        </div>

    </div>

    <a onclick="window.dialog_add.close();" aria-label="close" class="x">❌</a>
</dialog>

<!-- Modal Edit Profile -->
<dialog id="dialog_edit">
    <h4>Edit Profile</h4>

    <div id="formAddModal">
        <table align="center">
            <input type="hidden" name="profile_id" id="profile_id">
            <tr>
                <td>Code</td>
                <td><input type="text" class="form-control inputAdd" name="profile_code" id="profile_code_edit" placeholder="Enter name"  autocomplete="off" required></td>
            </tr>
            <tr>
                <td>Name</td>
                <td><input type="text" class="form-control inputAdd" name="profile_name" id="profile_name_edit" placeholder="Enter name" required></td>
            </tr>
            <tr>
                <td>Location</td>
                <td>
                    <select name="location[]" id="location_edit" class="inputAdd" disabled>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Status</td>
                <td>
                    {{-- <div class="row inputAdd">
                        <input type="radio" name="flag" class="flag_edit" value="1" style="margin-right: 10px">Active
                        <input type="radio" name="flag" class="flag_edit2" value="2" style="margin-right: 10px; margin-left: 20px">Non-Active
                    </div> --}}
                    <div class="checkbox-wrapper-51" style="margin-left: -20px;">
                        <input id="cbx-52" type="checkbox" name="flag_edit" class="flag_edit">
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
            <tr>
                <td>Choose Menu</td>
            </tr>
        </table>

        <div class="TreeViewProfile">
            <input type="checkbox" id="selectAllEdit" style="margin-left: 20px" class="parent_edit"> Select All Menu
            <div class="tree_main">
                <ul id="bs_main" class="main_ul">
                    @foreach ($menus as $menu)
                        {{-- Level 1 --}}
                        <li id="bs_{{ $menu->id }}e">
                            <span class="plus">&nbsp;</span>
                            <input type="checkbox" id="c_bs_{{ $menu->id }}e" class="parent_edit"/>
                            <span>{{ $menu->menu_name }}</span>
                            <ul id="bs_l_{{ $menu->id }}e" style="display: block" class="sub_ul">

                                {{-- Level 2 --}}
                                @foreach ($submenus as $sub)
                                    @if ($sub->menu_id == $menu->id)
                                        <li id="bf_{{ $sub->id }}e">
                                            <span class="plus">&nbsp;</span>
                                            <input type="checkbox" id="c_bf_{{ $sub->id }}e" name="menu[]" value="{{ $sub->id }}" class="submenu_edit" />
                                            <span>{{ $sub->sub_menu_name }}</span>

                                            {{-- Level 3 --}}
                                            <ul id="bf_l_{{ $sub->id }}e" style="display: block" class="inner_ul">
                                                @foreach ($permissions as $p)
                                                    @if ($sub->id == $p->sub_menu_id)
                                                            <li id="io_{{ $p->id }}e">
                                                            <input type="checkbox" id="c_io_{{ $p->id }}e" value="{{ $p->id }}" class="profilePermission_edit"/>
                                                            <span>{{ $p->key }}</span></li>
                                                    @endif
                                                @endforeach
                                            </ul>
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>

    <div class="row text-right">
        <div class="col">
            <button class="btn" id="btn-edit-submit-modal" disabled>Submit</button>
        </div>

    </div>

    <a onclick="window.dialog_edit.close();" id="close" aria-label="close" class="x">❌</a>
</dialog>


<script>
    $(document).ready(function(){
        $('#location').select2({
            dropdownParent: $('#dialog_add'),
            placeholder: "Select Location",
            multiple: true
        });

        getLocationData();
        // getListProfile();
    });

    $('#addButton').click(function(){
        $("#profile_code").val("");
        $("#profile_name").val("");
        $("#location").val("");
        $("#location").trigger("change");
        $("input[class=parent]").prop("checked", false);
        $("input[class=submenu]").prop("checked", false);
        $("input[class=profilePermission]").prop("checked", false);

    });

    // GLOBAL SETUP CSRF
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
        }
    });

    var tableData = $("#tableData").DataTable({
        serverSide: true,
        processing: true,
        paginate: true,
        autoWidth: true,
        scrollCollapse: true,
        dom: 'rtip',
        ajax: {
            type: 'POST',
            url: `{{ route("get-profile-list-datatable") }}`,
            data: {
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
                data: 'profile_code',
                name: 'profile_code',
            },
            {
                data: 'profile_name',
                name: 'profile_name',
            },
            {
                data: null,
                render: function(data, type, row) {
                    if (row.flag == 1){
                        return 'Active';
                    }else{
                        return 'Non-Active';
                    }
                }
            },
            {
                data: 'actions',
                name: 'actions',
                orderable: false,
                searchable: false,
                className: 'text-center',
            },
        ],
        order: [[0, 'asc']],
        columnDefs: [
            { className: "dt-center", targets: [0,1,2,3] }
        ],
        language: {
            loadingRecords: '&nbsp;',
            processing: '<div class="spinner" style="z-index: 1;"></div>',
            zeroRecords: "No data found",
        },
    });

    function getLocationData(){
        $.ajax({
            type: 'GET',
            url: "{{ url('/get-list-locations') }}",
            dataType: 'json',
            data: {
            },
			success: function(response) {
                $.each(response,function(key, value) {
                    $("#location").append('<option value="'+value.id+'">'+value.location_code+" - "+value.location_name+'</option>');
                });
			}
		});
    }

    $("#selectAll").click(function() {
        $("input[class=parent]").prop("checked", $(this).prop("checked"));
        $("input[class=submenu]").prop("checked", $(this).prop("checked"));
        $("input[class=profilePermission]").prop("checked", $(this).prop("checked"));
    });

    $("input[class=parent]").click(function() {
        if (!$(this).prop("checked")) {
            $("#selectAll").prop("checked", false);
        }
    });

    $("input[class=submenu]").click(function() {
        if (!$(this).prop("checked")) {
            $("#selectAll").prop("checked", false);
        }
    });

    $("input[class=profilePermission]").click(function() {
        if (!$(this).prop("checked")) {
            $("#selectAll").prop("checked", false);
        }
    });

    // Edit
    $("#selectAllEdit").click(function() {
        $("input[class=parent_edit]").prop("checked", $(this).prop("checked"));
        $("input[class=submenu_edit]").prop("checked", $(this).prop("checked"));
        $("input[class=profilePermission_edit]").prop("checked", $(this).prop("checked"));
    });

    $("input[class=parent_edit]").click(function() {
        if (!$(this).prop("checked")) {
            $("#selectAllEdit").prop("checked", false);
        }
    });

    $("input[class=submenu_edit]").click(function() {
        if (!$(this).prop("checked")) {
            $("#selectAllEdit").prop("checked", false);
        }
    });

    $("input[class=profilePermission_edit]").click(function() {
        if (!$(this).prop("checked")) {
            $("#selectAllEdit").prop("checked", false);
        }
    });

    $('.clear').click(function(){
        document.getElementById("profile_code").value = '';
        document.getElementById("profile_name").value = '';
        // document.getElementById("location").value = '';
        $("#location").val("");
        $("#location").trigger("change");
        $("input[class=parent]").prop("checked", false);
        $("input[class=submenu]").prop("checked", false);
        $("input[class=profilePermission]").prop("checked", false);
    });

    function uncheckAllEdit(){
        $("input[class=parent_edit]").prop("checked", false);
        $("input[class=submenu_edit]").prop("checked", false);
        $("input[class=profilePermission_edit]").prop("checked", false);
        $("#location_edit").val("");
        $("#location_edit").trigger("change");
    }

    $(document).on('click', '#btnEditProfile', function(event) {
        event.preventDefault();

        uncheckAllEdit();

        $('#location_edit').select2({
            dropdownParent: $('#dialog_edit'),
            placeholder: "Select Location",
            multiple: true
        });

        const data = $(this).data('p');

        $.ajax({
            type: 'GET',
            url: "{{ url('/get-old-data-profile-edit') }}",
            dataType: 'json',
            data: {
                profile_id: data,
            },
            success: function(response) {
                // console.log(response.profile[0].flag);

                $('#profile_id').val(response.profile[0].id);
                $('#profile_code_edit').val(response.profile[0].profile_code);
                $('#profile_name_edit').val(response.profile[0].profile_name);

                getLocationById(response.profile[0].id);

                if (response.profile[0].flag == 1){
                    $('.flag_edit').prop('checked', true);
                } else {
                    $('.flag_edit').prop('checked', false);
                }

                // Total menu array
                menuArray = [];
                var x=0;
                $.each(response.menu, function(index, value) {
                    $.each(response.submenu, function(index2, value2) {
                        if (response.menu[index].id == response.submenu[index2].menu_id){
                            x += 1;
                            menuArray[index] = x;
                        }
                    });
                    x=0;
                });

                // Total menu array yang checklist
                menuCheckArray = [];
                var y=0
                $.each(response.menu, function(index, value) {
                    $.each(response.profSubmenu, function(index2, value2) {
                        if (response.menu[index].id == response.profSubmenu[index2].menu_id){
                            y += 1;
                            menuCheckArray[index] = y;
                        }
                    });
                    y=0;
                });

                // Master menu checklist
                $.each(response.menu, function(index, value) {
                    if (menuArray[index] == menuCheckArray[index]){
                        $('#c_bs_'+response.menu[index].id+'e').prop('checked', true);
                    }
                });


                // Checklist submenu
                $.each(response.profileMenu, function(index, value) {
                    $('#c_bf_'+(response.profileMenu[index].sub_menu_id)+'e').prop('checked', true);
                });

                // Checklist profile permission
                $.each(response.profilePermission, function(index, value) {
                    $('#c_io_'+response.profilePermission[index].permission_id+'e').prop('checked', true);
                });
                // getListProfileEdit(response.profile_id);
                // getListSiteEdit(response.id);

                $('#location_edit').prop('disabled', false);
                $('#btn-edit-submit-modal').prop('disabled', false);
            },
        });
        window.dialog_edit.showModal();
    });


    function getLocationById(id){
        $.ajax({
            type: 'GET',
            url: "{{ url('/get-location-by-id') }}",
            dataType: 'json',
            data: {
                profileId: id,
            },
			success: function(response) {
                $('#location_edit').find('option').remove().end().append();
                getProfileLocation(response);
			}

		});
    }

    function getProfileLocation(data){
        $.ajax({
            type: 'GET',
            url: "{{ url('/get-profile-location') }}",
            dataType: 'json',
            data: {
            },
			success: function(response) {
                // console.log(response);
                $.each(response,function(key, value) {
                    // console.log(value.id);
                    if ( data.includes(value.id) ) {
                        $("#location_edit").append('<option value="'+value.id+'" selected>'+value.location_code+" - "+value.location_name+'</option>');
                    } else {
                        $("#location_edit").append('<option value="'+value.id+'">'+value.location_code+" - "+value.location_name+'</option>');
                    }
                });
			}

		});
    }

        // Submit Add Request Profile
    $('#btn-submit-modal').click(function(){
        event.preventDefault();

        var profileCode = $('#profile_code').val();
        var profileName = $('#profile_name').val();
        var location = $('#location').val();
        if (location.length == 0) {
            location = null;
        }

        var status = $('input[name="flag"]:checked').val();
        if (status == 'on'){
            status = 1;
        }else{
            status = 0;
        }
        console.log(status);

        // Profile Permission
        var profilePermission = [];
        var j = 0;
        $('input[class="profilePermission"]:checked').each(function() {
            profilePermission[j++] = this.value;
        });



        $.ajax({
            type: 'POST',
            url: "{{ url('/post-profile-req-submit') }}",
            dataType: 'json',
            data: {
                profile_code: profileCode,
                profile_name: profileName,
                location_id: location,
                flag: status,
                // submenu: submenu,
                profile_permission: profilePermission
            },
            success: function(response){

                console.log(response);

                return Swal.fire({
                    title: response.title,
                    text: response.message,
                    timer: 5000,
                    icon: "success",
                    timerProgressBar: true,
                    showConfirmButton: true,
                    target: document.getElementById('dialog_add'),
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
                    text: error.responseJSON.message ?? 'Failed submit profile request',
                    target: document.getElementById('dialog_add'),
                });
            },

        });

    });

    // Submit Update Profile Request
    $('#btn-edit-submit-modal').click(function(){
        event.preventDefault();

        var profileId = $('#profile_id').val();
        var profileCode = $('#profile_code_edit').val();
        var profileName = $('#profile_name_edit').val();
        var location = $('#location_edit').val();
        if (location.length == 0) {
            location = null;
        }

        var status = $('input[name="flag_edit"]:checked').val();
        // console.log(status);
        if (status == 'on'){
            status = 1;
        }else{
            status = 0;
        }
        // console.log(status)

        // Profile Permission
        var profilePermission = [];
        var j = 0;
        $('input[class="profilePermission_edit"]:checked').each(function() {
            profilePermission[j++] = this.value;
        });

        $.ajax({
            type: 'POST',
            url: "{{ url('/post-profile-update-submit') }}",
            dataType: 'json',
            data: {
                profile_id: profileId,
                profile_code: profileCode,
                profile_name: profileName,
                location_id: location,
                flag: status,
                profile_permission: profilePermission
            },
            success: function(response){

                console.log(response);

                return Swal.fire({
                    title: response.title,
                    text: response.message,
                    timer: 5000,
                    icon: "success",
                    timerProgressBar: true,
                    showConfirmButton: true,
                    target: document.getElementById('dialog_edit'),
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
                    text: error.responseJSON.message ?? 'Failed submit profile request',
                    target: document.getElementById('dialog_edit'),
                });
                $("#btn-submit").prop('disabled', false);
            },

        });

    });

    // VIEW TREE - PLUG IN
    $(document).ready(function () {
        $(".plus").click(function () {
            $(this).toggleClass("minus").siblings("ul").toggle();
        })

        $("input[type=checkbox]").click(function () {
            //alert($(this).attr("id"));
            //var sp = $(this).attr("id");
            //if (sp.substring(0, 4) === "c_bs" || sp.substring(0, 4) === "c_bf") {
                $(this).siblings("ul").find("input[type=checkbox]").prop('checked', $(this).prop('checked'));
            //}
        })

        $("input[type=checkbox]").change(function () {
            var sp = $(this).attr("id");
            if (sp.substring(0, 4) === "c_io") {
                var ff = $(this).parents("ul[id^=bf_l]").attr("id");
                if ($('#' + ff + ' > li input[type=checkbox]:checked').length == $('#' + ff + ' > li input[type=checkbox]').length) {
                    $('#' + ff).siblings("input[type=checkbox]").prop('checked', true);
                    check_fst_lvl(ff);
                }
                else {
                    $('#' + ff).siblings("input[type=checkbox]").prop('checked', false);
                    check_fst_lvl(ff);
                }
            }

            if (sp.substring(0, 4) === "c_bf") {
                var ss = $(this).parents("ul[id^=bs_l]").attr("id");
                if ($('#' + ss + ' > li input[type=checkbox]:checked').length == $('#' + ss + ' > li input[type=checkbox]').length) {
                    $('#' + ss).siblings("input[type=checkbox]").prop('checked', true);
                    check_fst_lvl(ss);
                }
                else {
                    $('#' + ss).siblings("input[type=checkbox]").prop('checked', false);
                    check_fst_lvl(ss);
                }
            }
        });

    })

    function check_fst_lvl(dd) {
        //var ss = $('#' + dd).parents("ul[id^=bs_l]").attr("id");
        var ss = $('#' + dd).parent().closest("ul").attr("id");
        if ($('#' + ss + ' > li input[type=checkbox]:checked').length == $('#' + ss + ' > li input[type=checkbox]').length) {
            //$('#' + ss).siblings("input[id^=c_bs]").prop('checked', true);
            $('#' + ss).siblings("input[type=checkbox]").prop('checked', true);
        }
        else {
            //$('#' + ss).siblings("input[id^=c_bs]").prop('checked', false);
            $('#' + ss).siblings("input[type=checkbox]").prop('checked', false);
        }

    }

    function pageLoad() {
        $(".plus").click(function () {
            $(this).toggleClass("minus").siblings("ul").toggle();
        })
    }



// });
</script>

@endsection
