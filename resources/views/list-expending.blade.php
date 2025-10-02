@extends('layouts.main')

@section('containter')

<style>
    .select2-container .select2-selection--single{
        text-align: left;
        border-radius: 10px !important;
        font-size: 12px !important;
        height: 40px !important;
        border: 1px solid #ced4da !important;
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
        font-size: 12px;
    }
</style>

<nav class="navbar navbar-expand-lg navbar-light" id="navbar-partial">
    <div class="container-fluid">

        <button type="button" id="sidebarCollapse" class="btn btn-primary">
            <i class="fa fa-bars"></i>
        </button>

        <h4 style="margin-left: 15px; margin-top: 10px; font-weight: 500; color: #424976">Expendings</h4>

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
        <h2 style="font-weight: 500">List of Expendings</h2>
    </div>

    <img src="svg/circleLeft.svg" id="circleLeft" alt="">

    <div class="container mt-3 text-center " align="center" style="max-width:100%;">

        <img src="svg/circleRight.svg" id="circleRight" alt="">

        <div class="container" id="divTable" style="max-width:100%;">

            <div align="left">
                {{-- BUTTON CREATE NEW ADJUSTMENT --}}
                {{-- <a href="/form-expending">
                <button type="submit" class="continue-application">
                    <div>
                        <div class="pencil"></div>
                        <div class="folder">
                            <div class="top">
                                <svg viewBox="0 0 24 27">
                                    <path d="M1,0 L23,0 C23.5522847,-1.01453063e-16 24,0.44771525 24,1 L24,8.17157288 C24,8.70200585 23.7892863,9.21071368 23.4142136,9.58578644 L20.5857864,12.4142136 C20.2107137,12.7892863 20,13.2979941 20,13.8284271 L20,26 C20,26.5522847 19.5522847,27 19,27 L1,27 C0.44771525,27 6.76353751e-17,26.5522847 0,26 L0,1 C-6.76353751e-17,0.44771525 0.44771525,1.01453063e-16 1,0 Z"></path>
                                </svg>
                            </div>
                            <div class="paper"></div>
                        </div>
                    </div>
                    Create new Expending
                </button>
                </a> --}}
            </div>

            <div class="container" id="divTable-white" style="max-width:100%;">

                <div class="row">
                    {{-- <div class="col"></div> --}}
                    <div class="col">
                        <div class="container d-flex" style="margin-top: 10px">
                            <input type="text" class="form-control inputFilter" id="expNumber" name="expNumber" placeholder="Expending No">

                            <input type="text" class="form-control inputFilter datepicker" id="dateFrom" name="dateFrom" placeholder="From Date" style="border-radius: 10px">
                            <input type="text" class="form-control inputFilter datepicker" id="dateTo" name="dateTo" placeholder="To Date" style=" border-radius: 10px">
                            <input type="hidden" id="dateFromData" name="dateFromData" />
                            <input type="hidden" id="dateToData" name="dateToData" />

                            <select name="site" class="form-control  inputFilter site" id="site" style="width: 100%; padding:20px;">
                                <option value="">All site</option>
                            </select>
                            <button type="submit" class="btn" id="buttonSearch" style="margin-left: 10px">
                                Search
                            </button>

                            <button class="btn btn_export btn-secondary" id="buttonExport">
                                Export
                            </button>
                        </div>

                    </div>

                </div>

                <div style="margin: 15px;">
                    <table class="table table-bordered mt-2 border-dark align-middle myTable" id="tableData" style="width:100%;">
                        <thead class="thead-dark">
                            <tr class="text-center" style="width: 100%; height:100%; background-color: #35384B; color: white;">
                                <td>Expending No</td>
                                <td>Expending Date</td>
                                <td>Site</td>
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
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>



<script>
    $(document).ready(function(){
        $('.site').select2();
        getListSite();
        getListExpending();

        $('.datepicker').datepicker('setEndDate', new Date());
        $('#dateTo').prop('disabled', true);
    });

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
        }
    });

    function getListSite(){
        $('#site').prop('disabled', true);
        $.ajax({
            type: 'GET',
            url: "{{ url('/get-all-user-site-permission') }}",
            dataType: 'json',
            data: {},
            success: function(response){
                var data = response;

                /** Set dropdown list */
                $('#site').find('option').remove().end().append();
                $('#site').append('<option value="" selected>All sites</option>');
                for (var i = 0; i < data.length; i++) {
                    text = data[i].store_code+' - '+data[i].site_description;
                    value = data[i].site_code;
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
                    text: error.responseJSON.message ?? 'Failed get list site for expending',
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

    $('#dateFrom').change(function(){
        if (!$('#dateFrom').val()){
            document.getElementById("dateFromData").value = null;
            document.getElementById("dateTo").value = null;
            document.getElementById("dateToData").value = null;
            $('#dateTo').prop('disabled', true);
        }
    });

    $('#dateTo').change(function(){
        if (!$('#dateTo').val()){
            document.getElementById("dateToData").value = null;
        }
    });

    $('#buttonSearch').on('click', function() {
        getListExpending();
    });

    function getListExpending(){
        $('#buttonSearch').prop('disabled', true);
        tableData.ajax.reload();
        $('#buttonSearch').prop('disabled', false);
    }

    var tableData = $('#tableData').DataTable({
        serverSide: true,
        processing: true,
        paginate: true,
        autoWidth: true,
        scrollCollapse: true,
        dom: 'rtip',
        ajax: {
            type: 'POST',
            url: `{{ route("get-exp-list-datatable") }}`,
            data: function (d) {
                d.exp_no = $('#expNumber').val();
                d.site = $('#site').val();
                d.from_date = $('#dateFromData').val();
                d.to_date = $('#dateToData').val();
            },
        },
        columns:[
            {data: 'req_no', name: 'req_no',},
            {
                data: 'req_date',
                render: function(data, type) {
                    return type === 'sort' ? data: new Date(data).toLocaleDateString('id-ID');
                }
            },
            {
                data: null,
                render: function(data, type, row){
                    return row.store_code;
                }
            },
            {
                data: 'status', name: 'status'
            },
            {
                data: 'actions',
                name: 'actions',
                orderable: false,
                searchable: false,
                className: 'text-center',
            },
        ],
        order: [[0, 'desc'], [1, 'desc']],
        columnDefs: [
            { className: "dt-center", targets: [0,1,2,3,4] }
        ],
        language: {
            loadingRecords: '&nbsp;',
            processing: '<div class="spinner" style="z-index: 1;"></div>',
            zeroRecords: "No data found",
        },
    });

    $('#buttonExport').on('click', function() {
        var expNumber = $('#expNumber').val();
        var site = $('#site').val();
        var dateFromData = $('#dateFromData').val();
        var dateToData = $('#dateToData').val();

        // Create a form and submit it to download the file
        var form = $('<form>', {
            'action': "{{ url('/export-excel-list-expending') }}",
            'method': 'GET',
        });

        // Add CSRF Token
        var csrfToken = $('meta[name="csrf-token"]').attr('content');
        form.append($('<input>', { 'type': 'hidden', 'name': '_token', 'value': csrfToken }));

        // Append the data
        form.append($('<input>', { 'type': 'hidden', 'name': 'expNumber', 'value': expNumber }));
        form.append($('<input>', { 'type': 'hidden', 'name': 'site', 'value': site }));
        form.append($('<input>', { 'type': 'hidden', 'name': 'dateFromData', 'value': dateFromData }));
        form.append($('<input>', { 'type': 'hidden', 'name': 'dateToData', 'value': dateToData }));

        // Append the form to the body and submit
        $('body').append(form);
        form.submit();
        form.remove();
    });

</script>

@endsection
