@extends('layouts.main')

@section('containter')

<style>

    .select2-container .select2-selection--single{
        text-align: left;
        border-radius: 10px !important;
        font-size: 12px !important;
        color: lightgrey !important;
        height: 40px !important;
        border: 1px solid #ced4da !important;
        padding-left: 8px !important;
        /* margin-right: 10px !important; */
    }

    .select2-selection__rendered{
        line-height: 40px !important;
        font-size: 12px !important;
    }

    .select2-selection_arror {
        height: 39px !important;
        font-size: 12px !important;
    }

    .select2-dropdown.select2-dropdown--below{
        font-size: 12px !important;
    }
</style>

<nav class="navbar navbar-expand-lg navbar-light" id="navbar-partial">
    <div class="container-fluid">

        <button type="button" id="sidebarCollapse" class="btn btn-primary">
            <i class="fa fa-bars"></i>
        </button>

        <h4 style="margin-left: 15px; margin-top: 10px; font-weight: 500; color: #424976">Adjustments</h4>

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
        <h2 style="font-weight: 500">List of Adjustments</h2>
    </div>

    <img src="svg/circleLeft.svg" id="circleLeft" alt="">

    <div class="container mt-3 text-center " align="center" style="max-width:100%;">

        <img src="svg/circleRight.svg" id="circleRight" alt="">

        <div class="container" id="divTable" style="max-width:100%;">

            {{-- <div align="left">
            </div> --}}

            <div class="container" id="divTable-white" style="max-width:100%;">

                <div class="row">

                    <div class="col">

                        <div class="container d-flex" style="margin-top: 10px">

                            {{-- BUTTON NEW --}}
                            {{-- <button id="button-new">
                                <span>
                                  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"></path><path fill="currentColor" d="M11 11V5h2v6h6v2h-6v6h-2v-6H5v-2z"></path></svg> New
                                </span>
                            </button> --}}

                            <input type="text" class="form-control input-filter" id="adjNumber" name="adjNumber" placeholder="Adjustment No">


                            <input type="text" class="form-control input-filter datepicker" id="dateFrom" name="dateFrom" placeholder="From Date" style="border-radius: 10px; ">
                            <input type="text" class="form-control input-filter datepicker" id="dateTo" name="dateTo" placeholder="To Date" style="border-radius: 10px">

                            <select name="site" class="form-control input-filter" id="selectSite" style="width: 80%; border-radius: 20px">
                                <option value="">All site</option>
                            </select>

                            <input type="hidden" id="dateFromData" name="dateFromData" />
                            <input type="hidden" id="dateToData" name="dateToData" />

                            <button type="submit" class="btn" id="buttonSearch" style="margin-left: 10px">
                                Search
                            </button>

                            <button class="btn btn_export btn-secondary" id="buttonExport">
                                Export
                            </button>
                        </div>

                    </div>

                </div>

                <table class="table table-bordered mt-2 border-dark align-middle" id="tableData" style="width:100%;">
                    <thead class="thead-dark">
                        <tr class="text-center" style="width: 100%; background-color: #35384B; color: white;">
                            <td>Adjustment No</td>
                            <td>Adjustment Date</td>
                            <td>Site Code</td>
                            {{-- <td>Status</td> --}}
                            <td>Action</td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr></tr>
                        <tr></tr>
                        <tr></tr>
                        <tr></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<script>

    $(document).ready(function(){
        $("#selectSite").select2();

        getListSite();
        getListAdjustment();

        $('#dateTo').prop('disabled', true);
    });

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
        }
    });

    // ===== GET LIST OF SITE ================================================================================
    function getListSite() {
        $.ajax({
            type: 'GET',
            url: "{{ url('/get-all-user-site-permission') }}",
            dataType: 'json',
            data: {},
            success: function(response) {
                $.each(response,function(key, value)
                {
                    $("#selectSite").append('<option value="' + value.site_code + '">' + value.store_code + ' - ' + value.site_description + '</option>');
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

    $('#buttonSearch').on('click', function() {
        getListAdjustment();
    });

    function getListAdjustment() {
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
            url: `{{ route("get-adj-list-datatable") }}`,
            data: function (d) {
                d.from_date = $('#dateFromData').val();
                d.to_date = $('#dateToData').val();
                d.adj_no = $('#adjNumber').val();
                d.site = $('#selectSite').val();
            },
        },
        columns: [
            { data: 'adj_no', name: 'adj_no', },
            {
                data: "adj_date",
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
                data: 'actions',
                name: 'actions',
                orderable: false,
                searchable: false,
                className: 'text-center',
            },
        ],
        order: [[1, 'desc']],
        columnDefs: [
            { className: "dt-center", targets: [0,1,2,3] }
        ],
        language: {
            loadingRecords: '&nbsp;',
            processing: '<div class="spinner" style="z-index: 1;"></div>',
            zeroRecords: "No data found",
        },
    });

    $('#buttonExport').on('click', function() {
        var from_date = $('#dateFromData').val();
        var to_date = $('#dateToData').val();
        var adj_no = $('#adjNumber').val();
        var site = $('#selectSite').val();

        // Create a form and submit it to download the file
        var form = $('<form>', {
            'action': "{{ url('/export-excel-list-adjustment') }}",
            'method': 'GET',
        });

        // Add CSRF Token
        var csrfToken = $('meta[name="csrf-token"]').attr('content');
        form.append($('<input>', { 'type': 'hidden', 'name': '_token', 'value': csrfToken }));

        // Append the data
        form.append($('<input>', { 'type': 'hidden', 'name': 'from_date', 'value': from_date }));
        form.append($('<input>', { 'type': 'hidden', 'name': 'to_date', 'value': to_date }));
        form.append($('<input>', { 'type': 'hidden', 'name': 'adj_no', 'value': adj_no }));
        form.append($('<input>', { 'type': 'hidden', 'name': 'site', 'value': site }));

        // Append the form to the body and submit
        $('body').append(form);
        form.submit();
        form.remove();
    });

</script>

@endsection
