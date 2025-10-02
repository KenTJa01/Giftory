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

        <h4 style="margin-left: 15px; margin-top: 10px; font-weight: 500; color: #424976">List Transfer</h4>

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
        <h2 style="font-weight: 500">List of Transfer</h2>
    </div>
    <img src="{{ asset('svg/circleLeft.svg') }}" id="circleLeft" alt="">
    <div class="container mt-3 text-center " align="center" style="max-width:100%;">
        <img src="{{ asset('svg/circleRight.svg') }}" id="circleRight" alt="">
        <div class="container" id="divTable" style="max-width:100%;">
            <div class="container" id="divTable-white" style="max-width:100%;">
                <div class="row">
                    <div class="col">
                        <div class="container" style="margin-top: 10px">
                            <table>
                                <tr>
                                    <td style="width: 14%">
                                        <input type="text" class="form-control inputFilter" id="trfNumber" name="trfNumber" placeholder="Transfer No">
                                    </td>
                                    <td style="width: 14%">
                                        <input type="text" class="form-control inputFilter datepicker" id="dateFrom" name="dateFrom" placeholder="From Date" style="border-radius: 10px">
                                        <input type="hidden" id="dateFromData" name="dateFromData" />
                                    </td>
                                    <td style="width: 14%">
                                        <input type="text" class="form-control inputFilter datepicker" id="dateTo" name="dateTo" placeholder="To Date" style="border-radius: 10px">
                                        <input type="hidden" id="dateToData" name="dateToData" />
                                    </td>
                                    <td style="width: 14%">
                                        <select name="siteFrom" id="siteFrom" class="form-control inputFilter siteList">
                                            <option value="">All from site</option>
                                        </select>
                                    </td>
                                    <td style="width: 14%">
                                        <select name="siteTo" id="siteTo" class="form-control inputFilter siteList">
                                            <option value="">All to site</option>
                                        </select>
                                    </td>
                                    <td style="width: 14%">
                                        <select name="" class="form-control inputFilter" id="status">
                                            <option value="">All status</option>
                                        </select>
                                    </td>
                                    <td>
                                        <button type="submit" class="btn" id="buttonSearch">
                                            Search
                                        </button>
                                    </td>
                                    <td>
                                        <button class="btn btn_export btn-secondary" style="height: 40px !important; margin-left: -5px;" id="buttonExport">
                                            Export
                                        </button>
                                    </td>
                                </tr>
                            </table>
                            <!-- <input type="text" class="form-control inputFilter" id="siteFrom" name="siteFrom" placeholder="From Site Code">
                            <input type="text" class="form-control inputFilter" id="siteTo" name="siteTo" placeholder="To Site Code"> -->
                        </div>
                    </div>
                </div>
                <table class="table table-bordered border-dark align-middle mt-2" id="tableData" style="width:100%;">
                    <thead class="thead-dark">
                        <tr class="text-center" style="width: 100%; background-color: #35384B; color: white;">
                            <td>Transfer No</td>
                            <td>Transfer Date</td>
                            <td>From Site</td>
                            <td>To Site</td>
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
        getListTransfer();

        /** Set max date for datepicker */
        $('.datepicker').datepicker('setEndDate', new Date());

        /** Disabled date to */
        $('#dateTo').prop('disabled', true);

        /** Use select2 for dropdown sites */
        $(".siteList").select2();
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

    function getListSite() {
        $('.siteList').prop('disabled', true);
        $.ajax({
            type: 'GET',
            url: "{{ url('/get-all-user-site-permission') }}",
            dataType: 'json',
            data: {},
            success: function(response) {
                var data = response;

                /** Set dropdown list */
                $('.siteList').find('option').remove().end().append();
                $('#siteFrom').append('<option value="" selected>All from site</option>');
                $('#siteTo').append('<option value="" selected>All to site</option>');

                for (var i = 0; i < data.length; i++) {
                    text = data[i].store_code+' - '+data[i].site_description;
                    value = data[i].site_id;
                    $('.siteList').append($("<option></option>").attr("value", value).text(text));
                }

                /** Enabled dropdown */
                $('.siteList').prop('disabled', false);
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
            url: "{{ url('/get-list-status-for-trf') }}",
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

    $('#buttonSearch').on('click', function() {
        getListTransfer();
    });

    function getListTransfer() {
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
            url: `{{ route("get-trf-list-datatable") }}`,
            data: function (d) {
                d.from_date = $('#dateFromData').val();
                d.to_date = $('#dateToData').val();
                d.trf_no = $('#trfNumber').val();
                d.from_site = $('#siteFrom').val();
                d.to_site = $('#siteTo').val();
                d.status_id = $('#status').val();
            },
        },
        columns: [
            { data: 'trf_no', name: 'trf_no', },
            {
                data: "trf_date",
                render: function(data, type) {
                    return type === 'sort' ? data : new Date(data).toLocaleDateString('id-ID');
                }
            },
            {
                data: null,
                render: function(data, type, row) {
                    return row.store_code_orig;
                }
            },
            {
                data: null,
                render: function(data, type, row) {
                    return row.store_code_dest;
                }
            },
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

    $('#button-new').click(function() {
        window.location.href = `{{ route('form-transfer') }}`;
    });

    $('#buttonExport').on('click', function() {
        var dateFromData = $('#dateFromData').val();
        var dateToData = $('#dateToData').val();
        var trfNumber = $('#trfNumber').val();
        var siteFrom = $('#siteFrom').val();
        var siteTo = $('#siteTo').val();
        var status = $('#status').val();

        // Create a form and submit it to download the file
        var form = $('<form>', {
            'action': "{{ url('/export-excel-list-transfer') }}",
            'method': 'GET',
        });

        // Add CSRF Token
        var csrfToken = $('meta[name="csrf-token"]').attr('content');
        form.append($('<input>', { 'type': 'hidden', 'name': '_token', 'value': csrfToken }));

        // Append the data
        form.append($('<input>', { 'type': 'hidden', 'name': 'dateFromData', 'value': dateFromData }));
        form.append($('<input>', { 'type': 'hidden', 'name': 'dateToData', 'value': dateToData }));
        form.append($('<input>', { 'type': 'hidden', 'name': 'trfNumber', 'value': trfNumber }));
        form.append($('<input>', { 'type': 'hidden', 'name': 'siteFrom', 'value': siteFrom }));
        form.append($('<input>', { 'type': 'hidden', 'name': 'siteTo', 'value': siteTo }));
        form.append($('<input>', { 'type': 'hidden', 'name': 'status', 'value': status }));

        // Append the form to the body and submit
        $('body').append(form);
        form.submit();
        form.remove();
    });
</script>
@endsection
