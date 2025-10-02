@extends('layouts.main')

@section('containter')
<style>
    .select2-container .select2-selection--single {
        text-align: left;
        border-radius: 10px !important;
        font-size: 12px !important;
        height: 40px !important;
        border: 1px solid #ced4da !important;
    }

    .select2-selection__rendered {
        line-height: 40px !important;
        font-size: 12px !important;
    }

    .select2-selection__arrow {
        height: 39px !important;
        font-size: 12px !important;
    }

    .select2-dropdown.select2-dropdown--below{
        font-size: 12px !important;
    }

    .btnNew {
        margin-right: 0px !important;
    }
</style>
<nav class="navbar navbar-expand-lg navbar-light" id="navbar-partial">
    <div class="container-fluid">
        <button type="button" id="sidebarCollapse" class="btn btn-primary">
            <i class="fa fa-bars"></i>
        </button>

        <h4 style="margin-left: 15px; margin-top: 10px; font-weight: 500; color: #424976">List Stock Opname</h4>

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
        <h2 style="font-weight: 500">List of Stock Opname</h2>
    </div>
    <img src="{{ asset('svg/circleLeft.svg') }}" id="circleLeft" alt="">
    <div class="container mt-3 text-center " align="center" style="max-width:100%;">
        <img src="{{ asset('svg/circleRight.svg') }}" id="circleRight" alt="">
        <div class="container" id="divTable" style="max-width:100%;">
            <div class="container" id="divTable-white" style="max-width:100%;">
                <div class="row">
                    <div class="col">
                        <div class="container" style="margin-top: 10px; width:100%;">
                            <table>
                                <tr>
                                    <td style="width: 17%">
                                        <input type="text" class="form-control inputFilter" id="soNumber" name="soNumber" placeholder="SO No" style="margin-left: 5px;">
                                    </td>
                                    <td style="width: 17%">
                                        <input type="text" class="form-control inputFilter datepicker" id="dateFrom" name="dateFrom" placeholder="From Date" style="border-radius: 10px; margin-left:4px;">
                                        <input type="hidden" id="dateFromData" name="dateFromData" />
                                    </td>
                                    <td style="width: 17%">
                                        <input type="text" class="form-control inputFilter datepicker" id="dateTo" name="dateTo" placeholder="To Date" style="border-radius: 10px; margin-left:3px">
                                        <input type="hidden" id="dateToData" name="dateToData" />
                                    </td>
                                    <td style="width: 17%">
                                        <select name="site" id="site" class="form-control inputFilter">
                                            <option value="">All site</option>
                                        </select>
                                    </td>
                                    <td style="width: 17%">
                                        <select name="location" id="location" class="form-control inputFilter">
                                            <option value="">All Location</option>
                                        </select>
                                    </td>
                                    <td style="width: 17%; margin-left: 10px">
                                        <select name="" class="form-control inputFilter" style="margin-left: 1px" id="status">
                                            <option value="">All status</option>
                                        </select>
                                    </td>
                                    <td>
                                        <button type="submit" class="btn" id="buttonSearch" style="margin-left: 5px">
                                            Search
                                        </button>
                                    </td>
                                    <td>
                                        <button class="btn btn_export btn-secondary" style="height: 40px !important; margin-left: 0px;" id="buttonExport">
                                            Export
                                        </button>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                <table class="table table-bordered border-dark align-middle mt-2" id="tableData" style="width:100%;">
                    <thead class="thead-dark">
                        <tr class="text-center" style="width: 100%; background-color: #35384B; color: white;">
                            <td>SO No</td>
                            <td>SO Date</td>
                            <td>Site</td>
                            <td>Location</td>
                            <!-- <td>SO Type</td> -->
                            <td>Status</td>
                            <td>Action</td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <!-- <td></td> -->
                            <td></td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        getListSite();
        getListStatus();
        getListLocation();
        getListStockOpname();

        /** Set max date for datepicker */
        $('.datepicker').datepicker('setEndDate', new Date());

        /** Disabled date to */
        $('#dateTo').prop('disabled', true);

        /** Use select2 for dropdown sites */
        $("#site").select2();
    });

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('#dateFrom').on('changeDate', function(selected) {
        document.getElementById("dateFromData").value = new Date(selected.date.valueOf()).toLocaleDateString('en-US');

        /** Reset filter date to */
        document.getElementById("dateTo").value = null;

        /** Set filter date to min date */
        var minDate = new Date(selected.date.valueOf());
        $('#dateTo').datepicker('setStartDate', minDate);

        /** Enable date to */
        $('#dateTo').prop('disabled', false);
    });

    $('#dateTo').on('changeDate', function(selected) {
        document.getElementById("dateToData").value = new Date(selected.date.valueOf()).toLocaleDateString('en-US');
    });

    $('#dateFrom').change(function() {
        /** Check text input null or not */
        if (!$('#dateFrom').val()) {
            /** Reset date from data */
            document.getElementById("dateFromData").value = null;

            /** Reset date to data */
            document.getElementById("dateTo").value = null;
            document.getElementById("dateToData").value = null;
            $('#dateTo').prop('disabled', true);
        }
	});

    $('#dateTo').change(function() {
        /** Check text input null or not */
        if (!$('#dateTo').val()) {
            document.getElementById("dateToData").value = null;
        }
	});

    $('#buttonSearch').on('click', function() {
        getListStockOpname();
    });

    function getListLocation(){
        $.ajax({
            type: 'GET',
            url: "{{ url('/get-list-locations') }}",
            dataType: 'json',
            data: {},
            success: function(response){
                var data = response;

                $('#location').find('option').remove().end().append();
                if (data.length != 1) {
                    $('#location').append('<option value="" selected>All Location</option>');
                }
                for (var i = 0; i < data.length; i++){
                    text = data[i].location_code;
                    value = data[i].location_code;
                    $('#location').append($('<option></option>').attr('value', value).text(text));
                }
            },
            error: function(error) {
                console.log(error.responseJSON);
                Swal.fire({
                    icon: 'error',
                    title: "Error",
                    text: error.responseJSON.message ?? 'Failed get list location for movement stock',
                });
            },
        });
    }

    function getListStockOpname() {
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
            url: `{{ route("get-stock-opname-list-datatable") }}`,
            data: function (d) {
                d.from_date = $('#dateFromData').val();
                d.to_date = $('#dateToData').val();
                d.so_no = $('#soNumber').val();
                d.site = $('#site').val();
                d.location = $('#location').val();
                d.status = $('#status').val();
            },
        },
        columns: [
            { data: 'so_no', name: 'so_no', },
            {
                data: "so_date",
                render: function(data, type) {
                    return type === 'sort' ? data : new Date(data).toLocaleDateString('id-ID');
                }
            },
            {
                data: null,
                render: function(data, type, row) {
                    return row.store_code;
                }
            },
            {
                data: null,
                render: function(data, type, row) {
                    return row.location_code;
                }
            },
            // { data: 'so_type', name: 'so_type', },
            { data: 'status', name: 'status', },
            {
                data: 'actions',
                name: 'actions',
                orderable: false,
                searchable: false,
                className: 'text-center',
            },
        ],
        order: [[1, 'desc']],
        columnDefs: [
            { className: "dt-center", targets: [0,1,2,3,4,5] }
        ],
        language: {
            loadingRecords: '&nbsp;',
            processing: '<div class="spinner" style="z-index: 1;"></div>',
            zeroRecords: "No data found",
        },
    });

    function getListSite() {
        $('#site').prop('disabled', true);
        $.ajax({
            type: 'GET',
            url: "{{ url('/get-all-user-site-permission') }}",
            dataType: 'json',
            data: {},
            success: function(response) {
                var data = response;

                /** Set dropdown list */
                $('#site').find('option').remove().end().append();
                if (data.length != 1) {
                    $('#site').append('<option value="" selected>All site</option>');
                }
                for (var i = 0; i < data.length; i++) {
                    text = data[i].store_code+' - '+data[i].site_description;
                    value = data[i].site_id;
                    $('#site').append($("<option></option>").attr("value", value).text(text));
                }

                /** Enabled dropdown */
                $('#site').prop('disabled', false);
            },
            error: function(error) {
                console.log(error.responseJSON);
                Swal.fire({
                    icon: 'error',
                    title: "Error",
                    text: error.responseJSON.message ?? 'Failed get list site',
                });
            },
        });
    }

    function getListStatus() {
        $('#status').prop('disabled', true);
        $.ajax({
            type: 'GET',
            url: "{{ url('/get-list-status-for-stock-opname') }}",
            dataType: 'json',
            data: {},
            success: function(response) {
                var data = response;

                /** Set dropdown list */
                $('#status').find('option').remove().end().append();
                $('#status').append('<option value="" selected>All status</option>');
                for (var i = 0; i < data.length; i++) {
                    text = data[i].flag_desc;
                    value = data[i].flag_value;
                    $('#status').append($("<option></option>").attr("value", value).text(text));
                }

                /** Enabled dropdown */
                $('#status').prop('disabled', false);
            },
            error: function(error) {
                console.log(error.responseJSON);
                Swal.fire({
                    icon: 'error',
                    title: "Error",
                    text: error.responseJSON.message ?? 'Failed get list status for transfer',
                });
            },
        });
    }

    function processData(data) {
        Swal.fire({
            icon: 'warning',
            title: "Are you sure?",
            text: "Process this transaction",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes",
            showLoaderOnConfirm: true,
            preConfirm: () => {
                processDataStockOpname(data)
            }
        });
    }

    function processDataStockOpname(data) {
        $.ajax({
            type: 'POST',
            url: "{{ url('/post-stock-opname-process-data') }}",
            dataType: 'json',
            data: {
                header_id: data,
            },
            success: function(response) {
                return Swal.fire({
                    title: response.title,
                    text: response.message,
                    timer: 5000,
                    icon: "success",
                    timerProgressBar: true,
                    showConfirmButton: true,
                    willClose: () => {
                        tableData.ajax.reload();
                    },
                });
            },
            error: function(error) {
                console.log(error.responseJSON);
                Swal.fire({
                    icon: 'error',
                    title: "Error",
                    text: error.responseJSON.message ?? 'Failed process stock opname data',
                });
            },
        });
    }

    $('#buttonExport').on('click', function() {
        var from_date = $('#dateFromData').val();
        var to_date = $('#dateToData').val();
        var so_no = $('#soNumber').val();
        var site = $('#site').val();
        var location = $('#location').val();
        var status = $('#status').val();

        // Create a form and submit it to download the file
        var form = $('<form>', {
            'action': "{{ url('/export-excel-list-stock-opname') }}",
            'method': 'GET',
        });

        // Add CSRF Token
        var csrfToken = $('meta[name="csrf-token"]').attr('content');
        form.append($('<input>', { 'type': 'hidden', 'name': '_token', 'value': csrfToken }));

        // Append the data
        form.append($('<input>', { 'type': 'hidden', 'name': 'from_date', 'value': from_date }));
        form.append($('<input>', { 'type': 'hidden', 'name': 'to_date', 'value': to_date }));
        form.append($('<input>', { 'type': 'hidden', 'name': 'so_no', 'value': so_no }));
        form.append($('<input>', { 'type': 'hidden', 'name': 'site', 'value': site }));
        form.append($('<input>', { 'type': 'hidden', 'name': 'location', 'value': location }));
        form.append($('<input>', { 'type': 'hidden', 'name': 'status', 'value': status }));

        // Append the form to the body and submit
        $('body').append(form);
        form.submit();
        form.remove();
    });
</script>
@endsection
