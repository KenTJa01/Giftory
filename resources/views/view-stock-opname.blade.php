@extends('layouts.main')
@section('containter')
<nav class="navbar navbar-expand-lg navbar-light" id="navbar-partial">
    <div class="container-fluid">
        <button type="button" id="sidebarCollapse" class="btn btn-primary">
            <i class="fa fa-bars"></i>
        </button>
        <h4 style="margin-left: 15px; margin-top: 10px; font-weight: 500; color: #424976">Stock Opname</h4>
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
        <h2 style="font-weight: 500">Stock Opname Details</h2>
    </div>
    <img src="{{ asset('svg/circleLeft.svg') }}" id="circleLeft" alt="">
    <div class="container text-center mt-3" align="center" style="max-width:100%; margin-top: -10px">
        <img src="{{ asset('svg/circleRight.svg') }}" id="circleRight" alt="">
        <div class="container" id="divTable" style="max-width:100%;">
            <div class="container" id="divTable-white" style="max-width:100%;">
                <div class="container" id="divTable-scrollable" style="max-width:100%;">
                    <div class="row" style="margin: 5px">
                        <div class="col">
                            <table align="left" style="color: black; width: 100%;">
                                <tr>
                                    <td align="left" style="width: 15%; font-size: 16px;">SO No</td>
                                    <td style="width: 30%;">
                                        <input type="text" class="form-control inputAdd" name="soNum" id="soNum"
                                            value="{{ $so_header_data?->so_no }}" style="width: 350px" disabled
                                        >
                                    </td>
                                    <td style="width: 10%;"></td>
                                    <td align="left" style="width: 15%; font-size: 16px;">Site</td>
                                    <td style="width: 30%;">
                                        <input type="text" class="form-control inputAdd" name="site" id="site"
                                            value="{{ $so_header_data?->store_code.' - '.$so_header_data?->site_description }}"
                                            style="width: 350px" disabled
                                        >
                                    </td>
                                </tr>
                                <tr>
                                    <td align="left" style="width: 15%; font-size: 16px;">SO Date</td>
                                    <td style="width: 30%;">
                                        <input type="text" class="form-control inputAdd" name="soDate" id="soDate"
                                            value="{{ date_format(new DateTime($so_header_data?->so_date), 'd/m/Y') }}" style="width: 350px" disabled
                                        >
                                    </td>
                                    <td style="width: 10%;"></td>
                                    <td align="left" style="width: 15%; font-size: 16px;">Location</td>
                                    <td style="width: 30%;">
                                        <input type="text" class="form-control inputAdd" name="location" id="location"
                                            value="{{ $so_header_data?->location_code.' - '.$so_header_data?->location_name }}"
                                            style="width: 350px" disabled
                                        >
                                    </td>
                                </tr>
                                <tr>
                                    <!-- <td align="left" style="width: 15%; font-size: 16px;">SO Type</td> -->
                                    <td align="left" style="width: 15%; font-size: 16px;"></td>
                                    <td style="width: 30%;">
                                        <!-- <input type="text" class="form-control inputAdd" name="soType" id="soType"
                                            value="{{ $so_header_data?->so_type }}"
                                            style="width: 350px" disabled
                                        > -->
                                    </td>
                                    <td style="width: 10%;"></td>
                                    <td style="width: 15%;"></td>
                                    <td style="width: 30%;"></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <img src="{{ asset('svg/line.svg') }}" alt="" style="width: 100%">
                    <h5 style="font-weight: 500">List Item</h5>
                    <table class="table table-bordered border-dark align-middle" id="tableData" style="width:100%;">
                        <thead class="thead-dark">
                            <tr class="text-center" style="width: 100%; background-color: #35384B; color: white;">
                                <td>Products</td>
                                <td>Before Qty</td>
                                <td>After Qty</td>
                                <td>Var. Qty</td>
                                <td>Unit</td>
                            </tr>
                        </thead>
                        <tbody style="background-color: white">
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td class="font-weight-bold">Total</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
        <a target="_blank" href="{{ '/document-stock-opname/'.$so_id }}" title="Print">
            <button class="btn" id="btn-generate">Print</button>
        </a>
    </div>
</div>

<script>
    $(document).ready(function () {
        document.getElementById('list-stock-opname').classList.add('active');
    });

    var tableData = $("#tableData").DataTable({
        data: {!! json_encode($so_detail_data) !!},
        processing: true,
        paginate: false,
        ordering: false,
        autoWidth: true,
        scrollCollapse: true,
        dom: 't',
        columns: [
            {
                data: null,
                render: function(data, type, row) {
                    return row.catg_code+' - '+row.catg_desc;
                }
            },
            { data: 'before_quantity', name: 'before_quantity', },
            { data: 'after_quantity', name: 'after_quantity', },
            { data: 'variance_qty', name: 'variance_qty', },
            { data: 'unit', name: 'unit', },
        ],
        columnDefs: [
            { className: "dt-center", targets: [0,1,2,3,4] },
            { render: $.fn.dataTable.render.number(',', '.', 0, ''), targets: [1,2,3] },
        ],
        footerCallback: function (row, data, start, end, display) {
            var api = this.api();
            var numFormat = $.fn.dataTable.render.number( ',', '.', 0, '' ).display;

            var i = 1;
            var columnNum = 3;
            while (i <= columnNum) {
                var totalRow = api.column(i, {page: 'current'}).data().reduce(function (a,b) {
                    return Number(a) + Number(b);
                }, 0);

                $(api.column(i).footer()).html(numFormat(totalRow));
                i++;
            }
        },
        language: {
            loadingRecords: '&nbsp;',
            processing: '<div class="spinner" style="z-index: 1;"></div>',
            zeroRecords: "No data found",
        },
    });
</script>
@endsection
