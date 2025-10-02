@extends('layouts.main')

@section('containter')

<style>
    .select2-container .select2-selection--single{
        text-align: left;
        border-radius: 5px !important;
        font-size: 16px !important;
        height: 40px !important;
        border: 1px solid #ced4da !important;
        padding-left: 8px !important;
        /* margin-right: 10px !important; */
    }

    .select2-selection__rendered{
        line-height: 40px !important;
        font-size: 16px !important;
    }

    .select2-selection_arror {
        height: 39px !important;
        font-size: 16px !important;
    }

    .select2-dropdown.select2-dropdown--below{
        font-size: 16px !important;
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
        <h2 style="font-weight: 500">Create New Expendings</h2>
    </div>

    <img src="svg/circleLeft.svg" id="circleLeft" alt="">

    <div class="container text-center mt-3" align="center" style="max-width:100%; margin-top: -20px">
        <form id="main_form">
            <img src="svg/circleRight.svg" id="circleRight" alt="">

            <div class="container" id="divTable" style="max-width:100%;">

                <div class="container" id="divTable-white" style="max-width:100%;">

                    <div class="row" style="margin: 5px">
                        <div class="col">
                            <table align="left" style="color: black;">
                                <tr>
                                    <td align="left" style="font-size: 16px; width: 180px;">Expending Date</td>
                                    <td><input type="text" class="form-control inputAdd" name="expendingDate" id="expendingDate" style="width: 300px" readonly></td>
                                </tr>
                                <tr>
                                    <td align="left" style="font-size: 16px">Site</td>
                                    <td>
                                        <select name="site" id="site" class="form-control inputAdd" style="width: 100%;" disabled>
                                            <option value="">Select site</option>
                                        </select>
                                    </td>
                                    {{-- <td align="left" style="font-size: 16px">Site</td>
                                    <input type="hidden" name="siteID" id="site" required>
                                    <td><input type="text" class="form-control inputAdd" id="siteDesc" style="width: 350px" readonly></td> --}}
                                </tr>
                                <tr>
                                    <td align="left" style="font-size: 16px">Location</td>
                                    {{-- <input type="hidden" name="locationID" id="location" required> --}}
                                    <td>
                                        <div>
                                            <select name="locationID" id="locationID" class="form-control inputAdd" style="width: 100%;" disabled>
                                                <option value="">Select Location</option>
                                            </select>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col">
                            <table align="left" style="color: black; width: 100%;">
                                <tr>
                                    <td align="left" style="font-size: 16px; width: 90px; text-align: right; padding-right: 30px;">Note</td>
                                    <td><textarea name="note" id="note" rows="4" maxlength="100" class="form-control inputAdd" style="width: 350px; max-height: 120px; min-height: 120px;" placeholder="Input note" disabled required></textarea></td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <img src="svg/line.svg" alt="" style="width: 100%">

                    <h5 style="font-weight: 500">List Product Categories</h5>

                    <div style="height: 290px;" id="tableForm">
                        <table class="table table-bordered border-dark align-middle" id="tableInput" style="width:100%; text-align:center">
                            <thead class="thead-dark">
                                <tr class="text-center" style="width: 100%; background-color: #35384B; color: white; border:">
                                    <th>Products Categories</th>
                                    <th>Stock Qty</th>
                                    <th>Expending Qty</th>
                                    <th>Unit</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody style="background-color: white">
                            </tbody>
                        </table>
                    </div>

                    <div class="row text-left" style="width: 400px; margin-left: 2px">
                        {{-- <a class="btn btn-add-row" id="add">
                            <font style="color: white">+ Add row</font>
                        </a> --}}
                        <button type="button" id="add" class="btn btn-add-row" style="display: block;" disabled>
                            <font style="color: white">+ Add row</font>
                        </button>
                    </div>

                </div>

            </div>

            {{-- <button class="btn" id="btn-draft">Save Draft</button> --}}
            <button type="button" class="btn" id="btn-submit" disabled>Submit</button>

        </div>
    </form>
</div>

<script>
    $(document).ready(function () {
        const todayDate = new Date().toLocaleDateString('id-ID');
        /** Initial value */
        document.getElementById("expendingDate").value = todayDate;

        getListSite();

        generateInitialTable();

        $("#site").select2();
    });

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
        }
    });


    function getListSite() {
        $.ajax({
            type: 'GET',
            url: "{{ url('/get-all-user-site-permission') }}",
            dataType: 'json',
            data: {},
            success:function(response) {
                var data = response;

                $('#site').prop('disabled', false);

                $('#site').find('option').remove().end().append();
                if (data.length != 1) {
                    $('#site').append('<option value="" disabled selected>Select site</option>');
                }
                for (var i = 0; i < data.length; i++){
                    text = data[i].store_code+' - '+data[i].site_description;
                    value = data[i].site_id;
                    $('#site').append($('<option></option>').attr('value', value).text(text));
                }

                if (data.length == 1){
                    getListProductLocation();
                    $('#btn-submit').prop('disabled', false);
                } else {
                    $('#locationID').prop('disabled', true);
                }

            },
            error: function(error){
                console.log(error.responseJSON);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: error.responseJSON.message ?? 'Failed get list site from',
                });
            },
        });
    }

    var indexTable = 2;
    var productListData = [];

    function generateInitialTable() {
        for (var i=0; i<indexTable; i++){
            addTableRow(i);
        }
    }

    //Location table
    // <td style="width: 25%">
    //     <select class="form-control location" name="location_`+index+`" id="location_`+index+`" disabled>
    //         <option value="" hidden>Select location</option>
    //     </select>
    //     <div name="err_location_`+index+`" id="err_location_`+index+`" style="text-align: left; display: none;">
    //         <p name="err_location_msg_`+index+`" id="err_location_msg_`+index+`" style="margin-bottom: 0; color: red; font-size: 12px;"></p>
    //     </div>
    // </td>

    function addTableRow(index){
        $('#tableInput').append(
            `<tr id="row_`+index+`">
                <td style="width: 30%">
                    <input type="hidden" name="product_`+index+`_id" id="product_`+index+`_id">
                    <input type="text" name="product_`+index+`" id="product_`+index+`" class="form-control product" placeholder="Input Product" style="width:300px;" autocomplete="off" disabled>
                    <div name="err_product_`+index+`" id="err_product_`+index+`" style="text-align: left; display:none;">
                        <p name="err_product_msg_`+index+`" id="err_product_msg_`+index+`" style="margin-bottom: 0; color: red;">Item already exists</p>
                    </div>
                </td>

                <td style="width: 13%">
                    <p name="stock_qty_`+index+`" id="stock_qty_`+index+`" style="font-size:17px;" />
                </td>

                <td style="width: 15%">
                    <input  type="number" min="1" name="qty_`+index+`" id="qty_`+index+`" class="form-control qty" placeholder="Qty" style="width: 90px; text-align: right;" disabled>
                    <div name="err_qty_`+index+`" id="err_qty_`+index+`" style="text-align: left; display: none;">
                        <p name="err_qty_msg_`+index+`" id="err_qty_msg_`+index+`" style="margin-bottom: 0; color: red;">Stock not available</p>
                    </div>
                </td>
                <td style="width: 7%">
                    <p name="unit_`+index+`" id="unit_`+index+`" style="font-size:16px; font-weight: 400px;" />
                </td>
                <td style="width: 10%"><button type="button" name="remove" id="btn_del_`+index+`" class="btn btn-danger btn_remove" style="height:35px">X</button>
            </tr>`
        )
    }

    function showAddRowButton() {
        var table = document.getElementById("tableInput");
        var btn = document.getElementById("add");

        var rowCount = table.tBodies[0].rows.length;
        if (rowCount >= 15) {
            btn.style.display = "none";
        } else {
            btn.style.display = "block";
        }
    }

    $('#add').click(function(){
        addTableRow(indexTable);

        var location = $('#locationID').val();
        if (location != null){
            $('.product').prop('disabled', false);
            $('#product_'+indexTable).focus();
        }

        indexTable++;
        showAddRowButton();
    });

    $(document).on('click', '.btn_remove', function(){
        var button_id = $(this).attr('id');
        var tempArr = button_id.split("_");

        // console.log(tempArr[2]);
        $('#row_'+tempArr[2]).remove();

        enableTableRow(tempArr[2]);
        showAddRowButton();
    });

    // $('#site').change(function(){
    //     // Reset input
    //     // resetTable();

    //     getListProduct();
    // });

    $('#site').change(function() {
        resetTable();
        getListProductLocation();
        $('#btn-submit').prop('disabled', false);
	});

    function getListProductLocation(index){
        var site = $('#site').val();
        // console.log(site);
        // var product = $('#product_'+index+'_id').val();

        // /** Enabled dropdown */
        $('#locationID').prop('disabled', false);

        $.ajax({
            type: 'GET',
            url: "{{ url('/get-exp-product-location-list') }}",
            dataType: 'json',
            data: {
                site_id: site,
                // product_id: product,
            },
            success:function(response){
                var data = response;

                // console.log(data);

                $('#locationID').find('option').remove().end().append();
                if (data.length != 1) {
                    $('#locationID').append('<option value="" disabled selected>Select Location</option>');
                }
                if (data.length == 1){
                    $('#note').prop('disabled', false);
                }
                for (var i = 0; i < data.length; i++){
                    text = data[i].location_code+' - '+data[i].location_name;
                    value = data[i].location_id;
                    $('#locationID').append($('<option></option>').attr('value', value).text(text));
                }

                if (data.length == 1){
                    getListProduct();
                } else {
                    $('.product').prop('disabled', true);
                }

                // $('#location_'+index).find('option').remove().end().append();
                // if (data.length != 1) {
                //     $('#location_'+index).append('<option value="" disabled selected>Select Location</option>');
                // }
                // for (var i=0; i<data.length; i++){
                //     text = data[i].location_name;
                //     value = data[i].location_id;
                //     $('#location_'+index).append($("<option></option>").attr("value", value).text(text));
                // }

                // $('#location_'+index).prop('disabled', false);

                // if (data.length == 1){
                //     getStockQty(index);
                // }
            },
            error: function(error) {
                console.log(error.responseJSON);
                Swal.fire({
                    icon: 'error',
                    title: "Error",
                    text: error.responseJSON.message ?? 'Failed get list product location',
                });
            },
        });
    }

    $('#locationID').change(function(){
    //     // Reset input
        resetTable();
        getListProduct();
        $('#add').prop('disabled', false);
        $('#note').prop('disabled', false);
    });


    function getListProduct() {
        var site = $('#site').val();
        var location = $('#locationID').val();
        // console.log(location);

        $.ajax({
            type: 'GET',
            url: "{{ url('/get-exp-product-list') }}",
            dataType: 'json',
            data: {
                site_id: site,
                location_id: location,
            },
            success: function(response){
                $('.product').prop('disabled', false);
                // console.log(response);
                productListData = response.map(function(item){
                    return {
                        label: item.catg_code+' - '+item.catg_name,
                        value: item.catg_code+' - '+item.catg_name,
                        key: item.product_id,
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

    $(document).on('click', '.product', function() {
        var productId = $(this).attr("id");
        autocompleteProduct(productId);
    });

    function autocompleteProduct(productId) {

        if (productListData.length > 0){
            $('#'+productId).autocomplete({
                minLength: 2,
                ignoreCase: false,
                // source: productListData,
                source: function(request, response) {
                    var result = $.ui.autocomplete.filter(productListData, request.term);
                    response(result.slice(0, 50));
                },
                change: function(event, ui) {
                    /** Triggered when the field is blurred, if the value has changed */
                    var tempArr = productId.split("_");

                    if (ui.item?.key == undefined) {
                        /** Reset row */
                        resetTableRow(tempArr[1]);

                        /** Show error message */
                        showProductErrorMessage(tempArr[1], 'Item not found');
                        disableTableRow(tempArr[1]);
                    } else {
                        checkDuplicateProduct(tempArr[1]);
                    }
                },
                select: function (event, ui) {
                    var tempArr = productId.split("_");

                    $('#'+productId+'_id').val(ui.item.key);
                    $('#'+productId).val(ui.item.label);
                    $('#'+productId).autocomplete('close');

                    /** Check duplicate product */
                    if (checkDuplicateProduct(tempArr[1])) {
                        // $('#location_'+tempArr[1]).prop('disabled', true);
                        $('#qty_'+tempArr[1]).prop('disabled', true);
                        return;
                    }

                     /** Reset qty, stock, unit */
                    document.getElementById("qty_"+tempArr[1]).value = '';
                    document.getElementById("stock_qty_"+tempArr[1]).innerHTML = '';
                    document.getElementById("unit_"+tempArr[1]).innerHTML = '';
                    // hideLocationErrorMessage(tempArr[1]);
                    // hideQtyErrorMessage(tempArr[1]);

                    // console.log(tempArr[1]);
                    getStockQty(tempArr[1]);

                    return false;
                },
                response: function(event, ui) {
                    var tempArr = productId.split("_");

                    if (!ui.content.length) {
                        showProductErrorMessage(tempArr[1], 'Item not found');
                        disableTableRow(tempArr[1]);
                    } else {
                        hideProductErrorMessage(tempArr[1]);
                    }
                }

            });
        }
    }


    function checkDuplicateProduct(data) {
        var productId = $('#product_'+data+'_id').val();
        // var message = document.getElementById("err_product_"+data);
        // console.log(message);

        var table = document.getElementById("tableInput");
        for (var i = 1, row ; row = table.rows[i] ; i++) {
            var tempArr = row.id.split("_");

            if (productId == $('#product_'+tempArr[1]+'_id').val() && tempArr[1] != data) {
                showProductErrorMessage(data, 'Item already exists');
                disableTableRow(data);
                return true;
            } else {
                hideProductErrorMessage(data);
                enableTableRow(data);
            }
        }
        return false;
    }

    function resetTable() {
        var table = document.getElementById("tableInput");
        for (var i = 1, row ; row = table.rows[i] ; i++) {
            var tempArr = row.id.split("_");
            var index = tempArr[1];

            document.getElementById("product_"+index).value = '';
            // hideProductErrorMessage(index);
            resetTableRow(index);
        }
    }


    function resetTableRow(index) {
        document.getElementById("product_"+index+"_id").value = '';
        // document.getElementById("location_"+index).value = '';
        document.getElementById("qty_"+index).value = '';
        document.getElementById("stock_qty_"+index).innerHTML = '';
        document.getElementById("unit_"+index).innerHTML = '';

        // $('#location_'+index).find('option').remove().end().append();
        // $('#location_'+index).append('<option value="" disabled selected>Select location</option>');
        // $('#location_'+index).prop('disabled', true);
        $('#qty_'+index).prop('disabled', true);
        $('.btn_remove').prop('disabled', false);

        // hideLocationErrorMessage(index);
        hideProductErrorMessage(index);
        hideQtyErrorMessage(index);
    }


    function showProductErrorMessage(index, message) {
        document.getElementById("err_product_msg_"+index).innerHTML = message;
        document.getElementById("err_product_"+index).style.display = 'block';
    }

    function hideProductErrorMessage(index) {
        document.getElementById("err_product_msg_"+index).innerHTML = '';
        document.getElementById("err_product_"+index).style.display = 'none';
    }

    function showLocationErrorMessage(index, message) {
        document.getElementById("err_location_msg_"+index).innerHTML = message;
        document.getElementById("err_location_"+index).style.display = 'block';
    }

    function hideLocationErrorMessage(index) {
        document.getElementById("err_location_msg_"+index).innerHTML = '';
        document.getElementById("err_location_"+index).style.display = 'none';
    }

    function showQtyErrorMessage(index, message) {
        document.getElementById("err_qty_msg_"+index).innerHTML = message;
        document.getElementById("err_qty_"+index).style.display = 'block';
    }

    function hideQtyErrorMessage(index) {
        document.getElementById("err_qty_msg_"+index).innerHTML = '';
        document.getElementById("err_qty_"+index).style.display = 'none';
    }

    function enableTableRow(index) {
        var table = document.getElementById('tableInput');
        for (var i=1, row; row=table.rows[i]; i++){
            var tempArr = row.id.split('_');

            if (tempArr[1] != index){
                productId = $('#product_'+tempArr[1]+'_id').val();
                // locationId = $('#location_'+tempArr[1]).val();
                // qty = $('#qty_'+tempArr[1]).val();

                /** Disabled text input */
                $('#product_'+tempArr[1]).prop('disabled', false);
                $('#btn_del_'+tempArr[1]).prop('disabled', false);
                // $('#qty_'+tempArr[1]).prop('disabled', false);
                // $('#location_'+tempArr[1]).prop('disabled', false);

                if (productId != undefined && productId != '') {
                    // $('#location_'+tempArr[1]).prop('disabled', false);
                    $('#qty_'+tempArr[1]).prop('disabled', false);
                }
                // if (qty != undefined && qty != '' && productId != undefined && productId != '') {
                //     $('#qty_'+tempArr[1]).prop('disabled', false);
                // }
            }
        }
        /** Enabled button */
        $('#add').prop('disabled', false);
        $("#btn-submit").prop('disabled', false);
    }

    function disableTableRow(index) {
        var table = document.getElementById("tableInput");
        for (var i = 1, row ; row = table.rows[i] ; i++) {
            var tempArr = row.id.split("_");

            if (tempArr[1] != index) {
                /** Disabled text input */
                $('#product_'+tempArr[1]).prop('disabled', true);
                // $('#location_'+tempArr[1]).prop('disabled', true);
                $('#qty_'+tempArr[1]).prop('disabled', true);
                $('#btn_del_'+tempArr[1]).prop('disabled', true);
            }
        }
        /** Disabled button */
        $('#add').prop('disabled', true);
        $("#btn-submit").prop('disabled', true);
    }


    // $(document).on('change', '.location', function(){
    //     var locationId = $(this).attr('id');

    //     var tempArr = locationId.split('_');
    //     hideLocationErrorMessage(tempArr[1]);
    //     hideQtyErrorMessage(tempArr[1]);
    //     enableTableRow(tempArr[1]);
    //     getStockQty(tempArr[1]);
    // });

    function getStockQty(index){
        var site = $('#site').val();
        var product = $('#product_'+index+'_id').val();
        var location = $('#locationID').val();

        // console.log(site);
        // console.log(product);
        // console.log(location);

        /** Reset qty, stock, unit */
        document.getElementById("qty_"+index).value = '';
        document.getElementById("stock_qty_"+index).innerHTML = '';
        document.getElementById("unit_"+index).innerHTML = '';

        $.ajax({
            type: 'GET',
            url: "{{ url('/get-exp-stock-qty') }}",
            dataType: 'json',
            data: {
                site_id: site,
                product_id: product,
                location_id: location,
            },
            success:function(response){
                console.log(response?.data_stock?.quantity);

                document.getElementById("stock_qty_"+index).innerHTML = response?.data_stock?.quantity - response?.data_stock_booking;
                document.getElementById("unit_"+index).innerHTML = response?.data_stock?.unit;

                $('#qty_'+index).prop('disabled', false);
            },
            error: function(error) {
                console.log(error.responseJSON);
                Swal.fire({
                    icon: 'error',
                    title: "Error",
                    text: error.responseJSON.message ?? 'Failed get list quantity & unit',
                });
            },
        });
    }

    $(document).on('change', '.qty', function() {
        var qtyId = $(this).attr("id");
        var tempArr = qtyId.split("_");
        validateQty(tempArr[1]);
    });


    function validateQty(index){
        var qty = $('#qty_'+index).val();
        var stockQty = document.getElementById("stock_qty_"+index).innerHTML;
        // var message = document.getElementById("err_qty_"+index);

        if (parseInt(qty) > parseInt(stockQty)) {
            showQtyErrorMessage(index, 'Stock not available');
            disableTableRow(index);
        } else {
            hideQtyErrorMessage(index);
            enableTableRow(index);
        }
    }

    $(document).on('click', '#btn-submit', function(event){
        event.preventDefault();
        $("#btn-submit").prop("disabled", true);

        var expendingDate = $("#expendingDate").val();
        var site = $("#site").val();
        var locationId = $("#locationID").val();
        var note = $('#note').val();
        var table = document.getElementById('tableInput');
        var detailData = [];

        // Detail Data
        for (var i=1, row; row=table.rows[i]; i++){
            var tempArr = row.id.split("_");
            var productId = $("#product_"+tempArr[1]+"_id").val();
            var qty = $('#qty_'+tempArr[1]).val();
            var location = $('#locationID').val();

            if (productId != '') {
                /** Validate qty input */
                if (qty == '') {
                    showQtyErrorMessage(tempArr[1], 'Required');
                    disableTableRow(tempArr[1]);
                    return;
                }
                detailData.push(
                    {
                        product_id: productId,
                        qty: qty,
                    }
                );
            }
        }

        $.ajax({
            type: 'POST',
            url: "{{ url('/post-exp-req-submit') }}",
            dataType: 'json',
            data: {
                expending_date: expendingDate,
                site_id: site,
                location_id: locationId,
                note: note,
                detail: detailData,
            },
            success: function(response){

                console.log(response);
                // disableTableRow(-1);

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
                    text: error.responseJSON.message ?? 'Failed submit expending request',
                });
                $("#btn-submit").prop('disabled', false);
            },

        });
    });



    // -----------------------------------------------------------------------------------------------------------------------------
    // -----------------------------------------------------------------------------------------------------------------------------
    // -----------------------------------------------------------------------------------------------------------------------------
    // -----------------------------------------------------------------------------------------------------------------------------
    // -----------------------------------------------------------------------------------------------------------------------------
    // -----------------------------------------------------------------------------------------------------------------------------
    // -----------------------------------------------------------------------------------------------------------------------------

    // SCRIPT LAMA---------------------------------------
    // function submit_form(){
    //     var form = document.getElementById("formSite");
    //     form.submit();
    // }

    // var i = 4;
    // $('#adds').click(function(){
    //     ++i;
    //     $('#tableRow').append(
    //         `<tr id="row`+i+`">
    //             <td><input type="text" name="" id="pc`+i+`" class="pc" placeholder="Enter Product" style="width: 200px"></td>
    //             <td>
    //                 <select class="form-label" name="" id="">
    //                     <option value="" hidden>Select location</option>
    //                 </select>
    //             </td>
    //             <td style="color: red;">*get data from DB</td>
    //             <td><input type="number" name="" id="" placeholder="Enter qty" style="width: 90px; text-align: right"></td>
    //             <td style="color: red;">*get data from DB</td>
    //             <td>
    //                 <input type="text" name="" id="">
    //             </td>
    //             <td><button type="button" name="remove" id="`+i+`" class="btn btn-danger btn_remove">X</button>
    //         </tr>`
    //     )
    // });

    // $(document).on('click', '.btn_remove', function(){
    //     var button_id = $(this).attr("id");
    //     $('#row'+button_id+'').remove();
    //   });



    // // GLOBAL SETUP CSRF
    // $.ajaxSetup({
    //     headers: {
    //         'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
    //     }
    // });


    // // Site
    // $('#selectSite').change(function() {
	// 	var site = $(this).val();
    //     var $data = [];
    //     // document.write(site);

    //     $(".location").html('<option value="">Select location</option>');

	// 	$.ajax({
    //         url: 'expending-detail-ajax/' + site + '/edit',
	// 		type: 'GET',

	// 		success: function(response) {
    //             // document.write(response)
    //             $.each(response,function(key, value)
    //             {
    //                 $data[key] = value.catg_name;
    //                 $( ".pc" ).autocomplete({
    //                     source: $data
    //                 });

    //                 $('.location').append('<option value=' + value.location_id + '>' + value.location_code + '</option>');
    //             });
	// 		}
	// 	});
	// });

    // // Show detail data
    // $('.location').change(function() {
    //     getQtyStock();
    // });

    // $('.pc').on('keyup', function() {
    //     getQtyStock();
    // });

    // function getQtyStock() {
    //     var location = $('.location').val();
    //     var pc = $('.pc').val();

    //     // document.write(pc);

    //     if (location != 0 && pc != '') {
    //         $.ajax({
    //             url: 'expending-detail-ajax/' + location,
    //             type: 'GET',
    //             data: {
    //                 location: location,
    //                 product: pc
    //             },
    //             success: function(response) {
    //                 console.log(response);
    //                 // $.each(response,function(key, value)
    //                 // {
    //                 //     $('#qty'+key).val(value.quantity);
    //                 //     $('#unit'+key).html(value.unit);
    //                 // });
    //             }
    //         });
    //     }
    // }
</script>
@endsection
