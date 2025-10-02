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
        <h2 style="font-weight: 500">List of Product Category</h2>
    </div>

    <img src="svg/circleLeft.svg" id="circleLeft" alt="">

    <div class="container mt-3 text-center " align="center" style="max-width:100%;">

        <img src="svg/circleRight.svg" id="circleRight" alt="">

        <div class="container" id="divTable" style="max-width:100%;">

            <div class="row">

                @if ( $add_product_allowed )
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

                </div>

                <div class="col">

                    <div class="container d-flex" style="margin-top: 10px">
                        <input type="text" class="form-control input-filter" name="catg_name_filter" id="catg_name_filter" placeholder="Catg Name" style="width: 300px">

                        <button type="submit" class="btn" id="buttonSearch">
                            Search
                        </button>
                    </div>
                </div>
            </div>

            <table class="table table-bordered border-dark align-middle" id="tableData" style="width:100%;">
                <thead class="thead-dark">
                    <tr class="text-center" style="width: 100%; background-color: #35384B; color: white;">
                        <td>No</td>
                        <td>Catg Code</td>
                        <td>Catg Name</td>
                        <td>Unit</td>
                        <td>Active</td>
                        <td>Action</td>
                    </tr>
                </thead>
                <tbody style="background-color: white"></tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Add New Product Category -->
<dialog id="dialog_add">
    <h4>Add New Product Category</h4>

    <div id="formAddModal">
        <table align="center">
            <tr>
                <td width="150px">Category Name</td>
                <td><input type="text" class="form-control inputAdd" name="catg_name_add" id="catg_name_add" placeholder="Enter category name" required></td>
            </tr>
            <tr>
                <td>Flag Active</td>
                <td>
                    <div class="checkbox-wrapper-51" style="margin-left: -20px">
                        <input id="cbx-51" type="checkbox" name="flag" class="flag" value="1" checked>
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
            <a class="btn clearAdd" id="btn-clear-modal">Clear</a>
            <button class="btn submitAdd" id="btn-submit-modal">Submit</button>
        </div>
    </div>

    <a onclick="window.dialog_add.close();" aria-label="close" class="x">❌</a>
</dialog>

<!-- Modal Edit Product Category -->
<dialog id="dialog_edit">
    <h4>Edit Product Category</h4>

    <div id="formAddModal">
        <table align="center">
            <input type="hidden" name="catg_id_edit" id="catg_id_edit">
            <tr>
                <td width>Category Code</td>
                <td><input type="text" class="form-control inputAdd" name="catg_code_edit" id="catg_code_edit" placeholder="Enter category name" disabled></td>
            </tr>
            <tr>
                <td width="150px">Category Name</td>
                <td><input type="text" class="form-control inputAdd" name="catg_name_edit" id="catg_name_edit" placeholder="Enter category name" required></td>
            </tr>
            <tr>
                <td>Unit</td>
                <td id="catg_unit_edit" style="color: black">
                </td>
            </tr>
            <tr>
                <td>Flag Active</td>
                <td>
                    <div class="checkbox-wrapper-51" style="margin-left: -20px">
                        <input class="flag_edit" id="cbx-52" type="checkbox" name="flag_edit" value="1">
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

<script>
    $(document).ready(function () {
        getListProduct();
    });

    // GLOBAL SETUP CSRF
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('#buttonSearch').on('click', function() {
        getListProduct();
    });

    function getListProduct() {
        $('#buttonSearch').prop('disabled', true);

        // tableData.ajax.reload();
        $("#tableData").DataTable({
            serverSide: true,
            processing: true,
            paginate: true,
            autoWidth: true,
            destroy: true,
            scrollCollapse: true,
            dom: 'rtip',
            ajax: {
                type: 'POST',
                url: `{{ route("get-product-list-datatable") }}`,
                data: function (d) {
                    d.name = $('#catg_name_filter').val();
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
                    data: 'catg_code',
                    name: 'catg_code',
                },
                {
                    data: 'catg_name',
                    name: 'catg_name',
                },
                {
                    data: 'unit',
                    name: 'unit',
                },
                {
                    data: 'null',
                    render: function(data, type, row) {
                        if (row.flag == 1) {
                            return "Active";
                        } else {
                            return "Non-Active";
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
            order: [[1, 'asc']],
            columnDefs: [
                { className: "dt-center", targets: [0,1,2,3,4,5] }
            ],
            language: {
                loadingRecords: '&nbsp;',
                processing: '<div class="spinner" style="z-index: 1;"></div>',
                zeroRecords: "No data found",
            },
        });

        $('#buttonSearch').prop('disabled', false);
    }

    // var tableData = $("#tableData").DataTable({
    //     serverSide: true,
    //     processing: true,
    //     paginate: true,
    //     autoWidth: true,
    //     scrollCollapse: true,
    //     dom: 'rtip',
    //     ajax: {
    //         type: 'POST',
    //         url: `{{ route("get-product-list-datatable") }}`,
    //         data: function (d) {
    //             d.name = $('#catg_name_filter').val();
    //         },
    //     },
    //     columns: [
    //         {
    //             data:'DT_RowIndex',
    //             name:'DT_RowIndex',
    //             orderable:false,
    //             searchable:false
    //         },
    //         {
    //             data: 'catg_code',
    //             name: 'catg_code',
    //         },
    //         {
    //             data: 'catg_name',
    //             name: 'catg_name',
    //         },
    //         {
    //             data: 'unit',
    //             name: 'unit',
    //         },
    //         {
    //             data: 'null',
    //             render: function(data, type, row) {
    //                 if (row.flag == 1) {
    //                     return "Active";
    //                 } else {
    //                     return "Non-Active";
    //                 }
    //             }
    //         },
    //         {
    //             data: 'actions',
    //             name: 'actions',
    //             orderable: false,
    //             searchable: false,
    //             className: 'text-center',
    //         },
    //     ],
    //     order: [[1, 'asc']],
    //     columnDefs: [
    //         { className: "dt-center", targets: [0,1,2,3,4,5] }
    //     ],
    //     language: {
    //         loadingRecords: '&nbsp;',
    //         processing: '<div class="spinner" style="z-index: 1;"></div>',
    //         zeroRecords: "No data found",
    //     },
    // });

    $(document).on('click', '#btnAddUser', function(event) {
        window.dialog_add.showModal();
    });

    $(document).on('click', '#btnEditUser', function(event) {
        event.preventDefault();
        const data = $(this).data('pc');

        $.ajax({
            type: 'GET',
            url: "{{ url('/get-old-data-product-edit') }}",
            dataType: 'json',
            data: {
                catgId: data,
            },
            success: function(response) {
                // console.log(response);
                $('#catg_id_edit').val(response.id);
                $('#catg_code_edit').val(response.catg_code);
                $('#catg_name_edit').val(response.catg_name);
                $('#catg_unit_edit').html(response.unit);
                if (response.flag == 1){
                    $('.flag_edit').attr('checked', true);
                } else if (response.flag == 0){
                    $('.flag_edit').attr('checked', false);
                }
                // getListProfileEdit(response.profile_id);
                // getListSiteEdit(response.id);
            },
        });
        window.dialog_edit.showModal();
    });

    $(document).on('click', '.clearAdd', function(event) {
        $('#catg_name_add').val("");
    });

    $(document).on('click', '.submitAdd', function(event) {
        var catgName = $('#catg_name_add').val();
        var toggle = $('#cbx-51:checked').val();
        if ( toggle == 1 ) {
            var flag = 1;
        } else {
            var flag = 0;
        }

        $.ajax({
            type: 'POST',
            url: "{{ url('/post-product-req-submit') }}",
            dataType: 'json',
            data: {
                catg_name: catgName,
                flag_active: flag
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
        var idVal = $('#catg_id_edit').val();
        var nameVal = $('#catg_name_edit').val();
        var toggle = $('#cbx-52:checked').val();
        if ( toggle == 1 ) {
            var flag = 1;
        } else {
            var flag = 0;
        }

        $.ajax({
            type: 'POST',
            url: "{{ url('/post-product-req-submit-edit') }}",
            dataType: 'json',
            data: {
                catg_id_edit: idVal,
                catg_name_edit: nameVal,
                flag_active: flag
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

                console.log(error.responseJSON);
                Swal.fire({
                    icon: 'error',
                    title: "Error",
                    text: error.responseJSON.message ?? 'Failed submit receiving request',
                    target: document.getElementById('dialog_add'),
                });
                $(".submitEdit").prop('disabled', false);
            },
        });
        event.preventDefault();
    });
</script>
@endsection
