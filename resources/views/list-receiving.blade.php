@extends('layouts.main')

@section('containter')

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
        <h2 style="font-weight: 500">List of Receiving</h2>
    </div>

    <img src="svg/circleLeft.svg" id="circleLeft" alt="">

    <div class="container mt-3 text-center " align="center" style="max-width:100%;">

        <img src="svg/circleRight.svg" id="circleRight" alt="">

        <div class="container" id="divTable" style="max-width:100%;">

            <div align="left">
                {{-- BUTTON CREATE NEW RECEIVING --}}
                {{-- <a href="/form-receiving">
                <button class="continue-application">
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
                    Create new Receiving
                </button>
                </a> --}}
            </div>

            <div class="container" id="divTable-white" style="max-width:100%;">

                <div class="row">
                    {{-- <div class="col"></div> --}}
                    <div class="col">
                        <div class="container d-flex" style="margin-top: 10px">

                            {{-- BUTTON NEW --}}
                            {{-- <button id="button-new">
                                <span>
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"></path><path fill="currentColor" d="M11 11V5h2v6h6v2h-6v6h-2v-6H5v-2z"></path></svg> New
                                </span>
                            </button> --}}
                            <input type="text" class="form-control input-filter" name="recNumber" id="recNumber" placeholder="Receiving No">

                            <input type="text" class="form-control input-filter datepicker" style="border-radius: 10px" id="dateRec" name="dateRec" placeholder="Receiving Date">

                            <input type="hidden" id="dateRecData" name="dateRecData" />


                            <input type="text" class="form-control input-filter" name="suppSite" id="suppSite" placeholder="From Supp Code / Site">

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
                                <td>Rec No</td>
                                <td>Rec Date</td>
                                <td>From Site / Supplier</td>
                                <td>To Site</td>
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

    $(document).ready(function () {
        getListTransfer();

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
        document.getElementById("dateRecData").value = new Date(selected.date.valueOf()).toLocaleDateString('en-US');

    });

    $('#dateRec').change(function(selected) {
        if (!$('#dateRec').val()) {
            /** Reset date from data */
            document.getElementById("dateRecData").value = null;
        }
    });


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
            url: `{{ route("get-rec-list-datatable") }}`,
            data: function (d) {
                d.rec_date = $('#dateRecData').val();
                d.rec_no = $('#recNumber').val();
                d.supp_site = $('#suppSite').val();
            },
        },
        columns: [
            {
                data: 'rec_no',
                name: 'rec_no',
            },
            {
                data: "rec_date",
                render: function(data, type) {
                    return type === 'sort' ? data : new Date(data).toLocaleDateString('id-ID');
                }
            },
            {
                data: 'origin',
                name: 'origin',
            },
            {
                data: null,
                render: function(data, type, row) {
                    return row.to_store_code;
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
        order: [[0, 'desc']],
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
        var recNumber = $('#recNumber').val();
        var dateRec = $('#dateRecData').val();
        var suppSite = $('#suppSite').val();

        // Create a form and submit it to download the file
        var form = $('<form>', {
            'action': "{{ url('/export-excel-list-receiving') }}",
            'method': 'GET',
        });

        // Add CSRF Token
        var csrfToken = $('meta[name="csrf-token"]').attr('content');
        form.append($('<input>', { 'type': 'hidden', 'name': '_token', 'value': csrfToken }));

        // Append the data
        form.append($('<input>', { 'type': 'hidden', 'name': 'recNumber', 'value': recNumber }));
        form.append($('<input>', { 'type': 'hidden', 'name': 'dateRec', 'value': dateRec }));
        form.append($('<input>', { 'type': 'hidden', 'name': 'suppSite', 'value': suppSite }));

        // Append the form to the body and submit
        $('body').append(form);
        form.submit();
        form.remove();
    });



</script>

@endsection
