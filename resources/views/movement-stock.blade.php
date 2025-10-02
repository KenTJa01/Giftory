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

        <h4 style="margin-left: 15px; margin-top: 10px; font-weight: 500; color: #424976">Stock</h4>

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
        <h2 style="font-weight: 500">Stock Movements</h2>
    </div>

    <img src="svg/circleLeft.svg" id="circleLeft" alt="">

    <div class="container mt-3 text-center " align="center" style="max-width:100%;">

        <img src="svg/circleRight.svg" id="circleRight" alt="">

        <div class="container" id="divTable" style="max-width:100%;">

            <div class="container" id="divTable-white" style="max-width:100%;">

                <div class="row">
                    {{-- <div class="col"></div> --}}
                    <div class="col">

                        <div class="container d-flex" style="margin-top: 10px">

                            <input type="hidden" name="productID" id="productID">
                            <input type="text" class="form-control inputFilter" name="product" id="product" placeholder="Product Catg" style="width: 100%;">

                            <select class="form-control inputFilter" name="site" id="site" style="width: 150%;">
                                <option value="">All Site</option>
                            </select>

                            <select class="form-control inputFilter" name="location" id="location" style="width: 90%; margin-left:10px;">
                                <option value="">All Location</option>
                            </select>

                            <select class="form-control inputFilter" name="movType" id="movType" style="width: 100%;">
                                <option value="">All Mov Type</option>
                            </select>


                            <input type="text" class="form-control inputFilter datepicker" id="dateFrom" name="dateFrom" placeholder="From Date" style="border-radius: 10px">
                            <input type="text" class="form-control inputFilter datepicker" id="dateTo" name="dateTo" placeholder="To Date" style=" border-radius: 10px">
                            <input type="hidden" id="dateFromData" name="dateFromData" />
                            <input type="hidden" id="dateToData" name="dateToData" />

                            <button type="submit" class="btn" id="buttonSearch">
                                Search
                            </button>

                        </div>

                    </div>

                </div>

                <table class="table table-bordered border-dark align-middle mt-2" id="tableData" style="width:100%;">
                    <thead class="thead-dark">
                        <tr class="text-center" style="width: 100%; background-color: #35384B; color: white;">
                            <td>Seq ID</td>
                            <td>Site</td>
                            <td>Product Catg</td>
                            <td>Location</td>
                            <td>Mov Date</td>
                            <td>Mov Type</td>
                            <td>Qty</td>
                            <td>Unit</td>
                            {{-- <td>From Site / Supp Code</td>
                            <td>To Site</td> --}}
                            <td>Ref No</td>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>

                </table>
            </div>
        </div>
    </div>

</div>

<script>
    $(document).ready(function(){
        // document.getElementById('#tableData').style.display = 'none';
        getListSite();
        getListLocation();
        getListProduct();
        getListMovType();

        /** Use select2 for dropdown */
        $("#site").select2();
        $('.datepicker').datepicker('setEndDate', new Date());
        $('#dateTo').prop('disabled', true);

    });

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(document).on('click', '#product', function() {
        autocompleteProduct();
    });

    function getListProduct() {
        $.ajax({
            type: 'GET',
            url: "{{ url('/get-product-list-filter-stock-mov') }}",
            dataType: 'json',
            data: {},
            success: function(response){
                console.log(response);
                productListData = response.map(function(item){
                    return {
                        label: item.catg_code+' - '+item.catg_name,
                        value: item.catg_code+' - '+item.catg_name,
                        key: item.catg_id,
                    }
                });
            },
            error: function(error){
                console.log(error.responseJSON);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: error.responseJSON.message ?? 'Failed get list product',
                });
            },
        });
    }

    function autocompleteProduct(productId) {
        $('#product').autocomplete({
            minLength: 2,
            ignoreCase: false,
            // source: productListData,
            source: function(request, response) {
                var result = $.ui.autocomplete.filter(productListData, request.term);
                response(result.slice(0, 50));
            },
            change: function(event, ui) {
                if (ui.item?.key == undefined) {
                    document.getElementById("product").value = null;
                    document.getElementById("productID").value = null;
                }
            },
            select: function (event, ui) {
                $('#productID').val(ui.item.key);
                $('#product').val(ui.item.label);
                $('#product').autocomplete('close');
                return false;
            },
        })
    }

    function getListSite(){
        $.ajax({
            type: 'GET',
            url: "{{ url('/get-all-user-site-permission') }}",
            dataType: 'json',
            data: {},
            success: function(response){
                var data = response;

                $('#site').find('option').remove().end().append();
                if (data.length != 1) {
                    $('#site').append('<option value="" selected>All Site</option>');
                }
                for (var i = 0; i < data.length; i++){
                    text = data[i].store_code+' - '+data[i].site_description;
                    value = data[i].site_id;
                    $('#site').append($('<option></option>').attr('value', value).text(text));
                }
            },
            error: function(error) {
                console.log(error.responseJSON);
                Swal.fire({
                    icon: 'error',
                    title: "Error",
                    text: error.responseJSON.message ?? 'Failed get list site for movement stock',
                });
            },
        });
    }

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

    function getListMovType(){
        $.ajax({
            type: 'GET',
            url: "{{ url('/get-list-mov-type') }}",
            dataType: 'json',
            data: {},
            success: function(response){
                var data = response;
                // console.log(data);

                /** Set dropdown list */
                $('#movType').find('option').remove().end().append();
                if (data.length != 1) {
                    $('#movType').append('<option value="" selected>All Mov Type</option>');
                }
                for (var i = 0; i < data.length; i++){
                    text = data[i].mov_code;
                    value = data[i].id;
                    $('#movType').append($('<option></option>').attr('value', value).text(text));
                }
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
        getStockMovDatatables();
        // tableData.ajax.reload();
    });

    function getStockMovDatatables(){
        var product = $('#productID').val();
        var site = $('#site').val();
        var movType = $('#movType').val();
        // console.log(product);
        // console.log(site);
        // console.log(movType);

        $('#tableData').DataTable({
            serverSide: true,
            processing: true,
            paginate: true,
            autoWidth: true,
            // scrollCollapse: true,
            destroy: true,
            dom: 'rtip',
            ajax: {
                type: 'POST',
                url: `{{ route("get-stock-mov-list-datatable") }}`,
                data: function (d) {
                    d.product = $('#productID').val();
                    d.site = $('#site').val();
                    d.location = $('#location').val();
                    d.movType = $('#movType').val();
                    d.from_date = $('#dateFromData').val();
                    d.to_date = $('#dateToData').val();
                },
            },
            columns:[
                {
                    data: 'id', name: 'id'
                },
                {
                    data: null,
                    render: function(data, type, row){
                        return row.store_code;
                    }
                },
                {
                    data: 'catg_name', name: 'catg_name'
                },
                {
                    data: 'location_code', name: 'location_code'
                },
                {
                    data: 'mov_date', name: 'mov_date'
                },
                {
                    data: 'mov_code', name: 'mov_code'
                },
                {
                    data: 'quantity', name: 'quantity'
                },
                {
                    data: 'unit', name: 'unit'
                },
                {
                    data: 'ref_no', name: 'ref_no'
                },
            ],
            order: [[0, 'desc']],
            columnDefs: [
                { className: "dt-center", targets: [0,1,2,3,4,5,6] }
            ],
            language: {
                loadingRecords: '&nbsp;',
                processing: '<div class="spinner" style="z-index: 1;"></div>',
                zeroRecords: "No data found",
            },
        });
        }


</script>

@endsection
