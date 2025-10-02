@extends('layouts.main')

@section('containter')

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
        <h2 style="font-weight: 500">List of Location</h2>
    </div>

    <img src="svg/circleLeft.svg" id="circleLeft" alt="">

    <div class="container mt-3 text-center " align="center" style="max-width:100%;">

        <img src="svg/circleRight.svg" id="circleRight" alt="">

        <div class="container" id="divTable" style="max-width:100%;">

            @if ($is_req_loc_allowed)
                <div class="row">

                    {{-- BUTTON ADD --}}
                    <div class="col">
                        <button class="CartBtn" onclick="window.dialog_add.showModal();">
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
                        <td>Location Code</td>
                        <td>Location Name</td>
                        <td>Active</td>
                        <td>Action</td>
                    </tr>
                </thead>
                <tbody style="background-color: white">
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Add New Location -->
<dialog id="dialog_add">
    <h4>Add New Location</h4>

    <div id="formAddModal">
        <table align="center">
            <tr>
                <td style="width: 130px">Location Code</td>
                <td><input type="text" class="form-control inputAdd" id="location_code" name="location_code" placeholder="Enter location code" autocomplete="off" required></td>
            </tr>
            <tr>
                <td>Location Name</td>
                <td><input type="text" class="form-control inputAdd" id="location_name" name="location_name" placeholder="Enter location name" autocomplete="off" required></td>
            </tr>
            <tr>
                <td>Flag Active</td>
                <td>
                    <div class="checkbox-wrapper-51">
                        <input id="cbx-51" name="flag" type="checkbox" checked>
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
            <button class="btn" id="btn-clear-modal">Clear</button>
            <button type="submit" class="btn" id="btn-submit-modal">Submit</button>
        </div>

    </div>

    <button onclick="window.dialog_add.close();" aria-label="close" class="x">❌</button>
</dialog>

<!-- Modal Edit Location -->
<dialog id="dialog_edit">
    <h4>Edit Location</h4>

    <input type="hidden" name="id" id="location_id">
    <div id="formAddModal">
        <table align="center">
            <input type="hidden" name="location_id" id="location_id">
            <tr>
                <td style="width: 130px">Location Code</td>
                <td><input type="text" class="form-control inputAdd" name="location_code" id="location_code_edit" placeholder="Enter location code" autocomplete="off"></td>
            </tr>
            <tr>
                <td>Location Name</td>
                <td><input type="text" class="form-control inputAdd" name="location_name" id="location_name_edit" placeholder="Enter location name" autocomplete="off"></td>
            </tr>
            <tr>
                <td>Flag Active</td>
                <td>
                    <div class="checkbox-wrapper-51">
                        <input class="flag_edit" id="cbx-52" name="flag_edit" type="checkbox">
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
            <button class="btn" id="btn-clear-modal">Clear</button>
            <button type="submit" class="btn" id="btn-edit-submit-modal">Submit</button>
        </div>

    </div>

    <button onclick="window.dialog_edit.close();" aria-label="close" class="x">❌</button>
</dialog>

{{-- Delete Unit --}}
<form action="/delete-location" method="post" id="deleteForm">
    @csrf
    <input type="hidden" name="id" id="locationID">
</form>

<script>
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
            url: `{{ route("get-location-list-datatable") }}`,
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
                data: 'location_code',
                name: 'location_code',
            },
            {
                data: 'location_name',
                name: 'location_name',
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
            { className: "dt-center", targets: [0,1,2,3,4] }
        ],
        language: {
            loadingRecords: '&nbsp;',
            processing: '<div class="spinner" style="z-index: 1;"></div>',
            zeroRecords: "No data found",
        },
    });

         // Submit Add Request Location
    $('#btn-submit-modal').click(function(){
        event.preventDefault();

        var locationCode = $('#location_code').val();
        var locationName = $('#location_name').val();
        var status = $('input[name="flag"]:checked').val();
        if (status == 'on'){
            status = 1;
        }else{
            status = 0;
        }

        $.ajax({
            type: 'POST',
            url: "{{ url('/post-location-req-submit') }}",
            dataType: 'json',
            data: {
                location_code: locationCode,
                location_name: locationName,
                flag: status,
            },
            success: function(response){

                // console.log(response);

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

    $('.x').on('click', function(){
        $("#location_code").val("");
        $("#location_name").val("");
        $("#location_code_edit").val("");
        $("#location_name_name").val("");
    });

    // Get Old data edit
    $(document).on('click', '#btnEditLocation', function(event) {
        event.preventDefault();

        const data = $(this).data('l');
        // console.log(data);

        $.ajax({
            type: 'GET',
            url: "{{ url('/get-old-data-location-edit') }}",
            dataType: 'json',
            data: {
                location_id: data,
            },
            success: function(response) {

                $('#location_id').val(response.id);
                $('#location_code_edit').val(response.location_code);
                $('#location_name_edit').val(response.location_name);
                if (response.flag == 1){
                    $('.flag_edit').attr('checked', true);
                } else if (response.flag == 0){
                    $('.flag_edit').attr('checked', false);
                }
            },
        });
        window.dialog_edit.showModal();
    });

    // Submit Edit Location
    $('#btn-edit-submit-modal').click(function(){
        event.preventDefault();

        var locationId = $('#location_id').val();
        var locationCode = $('#location_code_edit').val();
        var locationName = $('#location_name_edit').val();

        var status = $('input[name="flag_edit"]:checked').val();

        if (status == 'on'){
            status = 1;
        }else{
            status = 0;
        }


        $.ajax({
            type: 'POST',
            url: "{{ url('/post-location-update-submit') }}",
            dataType: 'json',
            data: {
                location_id: locationId,
                location_code: locationCode,
                location_name: locationName,
                flag: status,
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
            },

        });

    });

</script>

@endsection
