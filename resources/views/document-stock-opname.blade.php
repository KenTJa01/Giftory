@extends('layouts.document')
@section('containter')
<style>
    /* * {
        font-family: "Poppins", Arial, sans-serif;
    } */
    .table-data {
        width: 100%;
        text-align: center;
    }
    .table-data tr td {
        border: 1px solid black;
    }
    .table-data td {
        padding-top: 10px;
        padding-bottom: 10px;
    }
    .table-sign {
        width: 50%;
        text-align: center;
    }
    .table-sign tr {
        border: 1px solid black;
    }
    .tr-h-100 {
        height: 100px;
    }
</style>
<div class="row">
    <div class="col text-center" id="title">
        <h3 style="font-weight: 500; text-decoration: underline;">STOCK OPNAME DETAILS</h2>
    </div>
    <div class="container text-center mt-3" align="center" style="max-width:100%; margin-top: -10px">
        <div class="row" style="margin: 5px">
            <div class="col" style="padding-left: 0px;">
                <table style="color: black; width: 100%;">
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td class="text-right" style="font-size: 16px">Date {{ date_format(new DateTime($so_header_data?->so_date), 'd/m/Y') }}</td>
                    </tr>
                    <tr>
                        <td class="text-left" style="font-size: 16px">
                            SITE : {{ $so_header_data?->site_code.' - '.$so_header_data?->store_code }}
                        </td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="text-left" style="font-size: 16px">
                            LOCATION : {{ $so_header_data?->location_code.' - '.$so_header_data?->location_name }}
                        </td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="text-left font-weight-bold" style="font-size: 18px;">SO NO. {{ $so_header_data?->so_no }}</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="row" align="center">
            <div class="col-sm">
                <table class="table-data" id="tableData">
                    <thead>
                        <tr class="text-center font-weight-bold">
                            <td>Product Category</td>
                            <td>Before Qty</td>
                            <td>After Qty</td>
                            <td>Var. Qty</td>
                            <td>Unit</td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($so_detail_data as $d)
                        <tr>
                            <td>{{ $d?->catg_code.' - '.$d?->catg_desc }}</td>
                            <td>{{ $d?->before_quantity }}</td>
                            <td>{{ $d?->after_quantity }}</td>
                            <td>{{ $d?->variance_qty }}</td>
                            <td>{{ $d?->unit }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td class="font-weight-bold">Total</td>
                            <td id="totalBeforeQty"></td>
                            <td id="totalAfterQty"></td>
                            <td id="totalVarQty"></td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        /** Generate total value for table */
        var table = document.getElementById('tableData');
        let totalBeforeQty = 0;
        let totalAfterQty = 0;
        let totalVarQty = 0;

        for (let i = 1; i < table.rows.length ; i++) {
            totalBeforeQty += Number(table.rows[i].cells[1].innerText);
            totalAfterQty += Number(table.rows[i].cells[2].innerText);
            totalVarQty += Number(table.rows[i].cells[3].innerText);
        }
        document.getElementById('totalBeforeQty').innerHTML = totalBeforeQty;
        document.getElementById('totalAfterQty').innerHTML = totalAfterQty;
        document.getElementById('totalVarQty').innerHTML = totalVarQty;

        window.print();
    });
</script>
@endsection
