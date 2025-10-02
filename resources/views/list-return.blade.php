@extends('layouts.main')

@section('containter')

<nav class="navbar navbar-expand-lg navbar-light" id="navbar-partial">
    <div class="container-fluid">

        <button type="button" id="sidebarCollapse" class="btn btn-primary">
            <i class="fa fa-bars"></i>
        </button>

        <h4 style="margin-left: 15px; margin-top: 10px; font-weight: 500; color: #424976">Return</h4>

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
        <h2 style="font-weight: 500">List of Return</h2>
    </div>

    <img src="svg/circleLeft.svg" id="circleLeft" alt="">

    <div class="container mt-3 text-center " align="center" style="max-width:100%;">

        <img src="svg/circleRight.svg" id="circleRight" alt="">

        <div class="container" id="divTable" style="max-width:100%;">

            <div class="container" id="divTable-white" style="max-width:100%;">

                <div class="row">
                    <div class="col">
                        <div class="container d-flex" style="margin-top: 10px">

                            <input type="text" class="form-control input-filter" name="retNumber" id="retNumber" placeholder="Return No">

                            <input type="text" class="form-control input-filter datepicker" style="border-radius: 10px" id="dateRec" name="dateRec" placeholder="Return Date">

                            <input type="hidden" id="dateRetData" name="dateRetData" />


                            <input type="text" class="form-control input-filter" name="suppSite" id="suppSite" placeholder="Location / Supp">

                            {{-- <select class="form-control input-filter" name="" id="status">
                                <option value="">All Status</option>
                            </select> --}}

                            <button type="submit" class="btn" id="buttonSearch">
                                Search
                            </button>

                            <button class="btn btn_export btn-secondary" id="buttonExport">
                                Export
                            </button>

                        </div>

                    </div>

                </div>

                <div style="margin: 15px">
                    <table class="table table-bordered mt-2 border-dark align-middle" id="tableData" style="width:100%;">
                        <thead class="thead-dark">
                            <tr class="text-center" style="width: 100%; background-color: #35384B; color: white;">
                                <td>Ret No</td>
                                <td>Ret Date</td>
                                <td>Site</td>
                                <td>To Location / Supplier</td>
                                <td>Status</td>
                                <td>Action</td>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr> --}}
                        </tbody>

                    </table>
                </div>
            </div>
        </div>
    </div>

</div>

<script>

    $(document).ready(function () {
        getListReturn();

        /** Disabled date to */
        $('#dateTo').prop('disabled', true);
    });


    // GLOBAL SETUP CSRF
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('#dateRec').on('changeDate', function(selected) {
        document.getElementById("dateRetData").value = new Date(selected.date.valueOf()).toLocaleDateString('en-US');

    });

    $('#dateRec').change(function(selected) {
        if (!$('#dateRec').val()) {
            /** Reset date from data */
            document.getElementById("dateRetData").value = null;
        }
    });


    $('#buttonSearch').on('click', function() {
        getListReturn();
    });

    function getListReturn() {
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
            type: 'GET',
            url: `{{ route("get-ret-list-datatable") }}`,
            data: function (d) {
                d.ret_date = $('#dateRetData').val();
                d.ret_no = $('#retNumber').val();
                d.supp_site = $('#suppSite').val();
            },
        },
        columns: [
            {
                data: 'ret_no',
                name: 'ret_no',
            },
            {
                data: "ret_date",
                render: function(data, type) {
                    return type === 'sort' ? data : new Date(data).toLocaleDateString('id-ID');
                }
            },
            {
                data: 'store_code',
                name: 'store_code',
            },
            {
                data: 'location',
                name: 'location',
            },
            {
                data: 'status',
                name: 'status',
            },
            {
                data: 'actions',
                name: 'actions',
                orderable: false,
                searchable: false,
                className: 'text-center',
            },
        ],
        order: [[1, 'desc'], [0, 'desc']],
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
        var retNumber = $('#retNumber').val();
        var dateRet = $('#dateRetData').val();
        var suppSite = $('#suppSite').val();

        // Create a form and submit it to download the file
        var form = $('<form>', {
            'action': "{{ url('/export-excel-list-return') }}",
            'method': 'GET',
        });

        // Add CSRF Token
        var csrfToken = $('meta[name="csrf-token"]').attr('content');
        form.append($('<input>', { 'type': 'hidden', 'name': '_token', 'value': csrfToken }));

        // Append the data
        form.append($('<input>', { 'type': 'hidden', 'name': 'retNumber', 'value': retNumber }));
        form.append($('<input>', { 'type': 'hidden', 'name': 'dateRet', 'value': dateRet }));
        form.append($('<input>', { 'type': 'hidden', 'name': 'suppSite', 'value': suppSite }));

        // Append the form to the body and submit
        $('body').append(form);
        form.submit();
        form.remove();
    });



</script>

@endsection
