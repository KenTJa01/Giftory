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

        <h4 style="margin-left: 15px; margin-top: 10px; font-weight: 500; color: #424976">Transfer</h4>

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
        <h2 style="font-weight: 500">Create New Transfer</h2>
    </div>

    <img src="svg/circleLeft.svg" id="circleLeft" alt="">

    <div class="container text-center mt-3" align="center" style="max-width:100%; margin-top: -20px">

        <form id="main_form">

            <img src="svg/circleRight.svg" id="circleRight" alt="">

            <div class="container" id="divTable" style="max-width:100%;">

                <div class="container" id="divTable-white" style="max-width:100%;">

                    <div class="row" style="margin: 5px">
                        <div class="col d-flex">
                            <table align="left" style="color: black">
                                <tr>
                                    <td align="left" style="font-size: 16px; width: 150px;">Transfer Date</td>
                                    <td>
                                        <input type="text" class="form-control inputAdd" name="transferDate" id="transferDate" style="margin-left: 20px; width: 300px;" readonly>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="left" style="font-size: 16px; width: 150px;">From Site</td>
                                    <td>
                                        <div style="margin-left: 20px;">
                                            <select name="siteFrom" id="siteFrom" class="form-control inputAdd" style="width: 100%;" disabled>
                                                <option value="">Select from site</option>
                                            </select>
                                        </div>
                                        <!-- <input type="hidden" name="siteFromData" id="siteFromData">
                                        <input type="text" class="form-control inputAdd" name="siteFromInput" id="siteFromInput" style="width: 350px" placeholder="Select from site"> -->
                                    </td>
                                </tr>
                                <tr>
                                    <td align="left" style="font-size: 16px; width: 150px;">To Site</td>
                                    <td>
                                        <div style="margin-left: 20px;">
                                            <select name="siteTo" id="siteTo" class="form-control inputAdd" style="width: 100%;" disabled>
                                                <option value="">Select to site</option>
                                            </select>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <img src="svg/line.svg" alt="" style="width: 100%">

                    <h5 style="font-weight: 500">List Product Categories</h5>

                    <div style="height: 290px;" id="tableForm">
                        <table class="table table-bordered border-dark align-middle" id="tableInput" style="width:100%;">
                            <thead class="thead-dark">
                                <tr class="text-center" style="width: 100%; background-color: #35384B; color: white;">
                                    <th>Products</th>
                                    <th>From Location</th>
                                    <th>Qty</th>
                                    <th>Stock Qty</th>
                                    <th>Unit</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody style="background-color: white"></tbody>
                        </table>
                    </div>

                    <div class="row text-left" style="width: 400px; margin-left: 2px;">
                        <!-- <a class="btn btn-add-row" id="add" style="display: block;">
                            <font style="color: white">+ Add row</font>
                        </a> -->
                        <button type="button" id="add" class="btn btn-add-row" style="display: block;">
                            <font style="color: white">+ Add row</font>
                        </button>
                    </div>

                </div>
            </div>

            <button type="button" id="btn-submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
</div>

<script>
    $(document).ready(function () {
        const todayDate = new Date().toLocaleDateString('id-ID');
        /** Initial value */
        document.getElementById("transferDate").value = todayDate;
        getListSiteFrom();
        generateInitialTable();

        /** Use select2 for dropdown */
        $("#siteFrom").select2();
        $("#siteTo").select2();
    });

    /** Initate global variable */
    var indexTable = 2;
    var productListData = [];

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
        }
    });

    function generateInitialTable() {
        for (var i = 0 ; i < indexTable ; i++) {
            addTableRow(i);
        }
        disableTableRow(-1);
    }

    function addTableRow(index) {
        $('#tableInput').append(
            `<tr id="row_`+index+`" class="tableRow">
                <td style="width: 30%">
                    <input type="hidden" name="product_`+index+`_id" id="product_`+index+`_id">
                    <input type="text" name="product_`+index+`" id="product_`+index+`" class="form-control product" placeholder="Input product" style="width: 300px">
                    <div name="err_product_`+index+`" id="err_product_`+index+`" style="text-align: left; display: none;">
                        <p name="err_product_msg_`+index+`" id="err_product_msg_`+index+`" style="margin-bottom: 0; color: red; font-size: 12px;"></p>
                    </div>
                </td>
                <td style="width: 25%">
                    <select class="form-control location" name="location_`+index+`" id="location_`+index+`" disabled>
                        <option value="">Select location</option>
                    </select>
                    <div name="err_location_`+index+`" id="err_location_`+index+`" style="text-align: left; display: none;">
                        <p name="err_location_msg_`+index+`" id="err_location_msg_`+index+`" style="margin-bottom: 0; color: red; font-size: 12px;"></p>
                    </div>
                </td>
                <td style="width: 15%">
                    <input type="number" min="1" class="form-control qty" name="qty_`+index+`" id="qty_`+index+`" placeholder="Qty" style="width: 90px; text-align: right" disabled>
                    <div name="err_qty_`+index+`" id="err_qty_`+index+`" style="text-align: left; display: none;">
                        <p name="err_qty_msg_`+index+`" id="err_qty_msg_`+index+`" style="margin-bottom: 0; color: red; font-size: 12px;"></p>
                    </div>
                </td>
                <td style="width: 13%">
                    <p name="stock_qty_`+index+`" id="stock_qty_`+index+`" style="font-size: 16px; font-weight: 400;" />
                </td>
                <td style="width: 7%">
                    <p name="unit_`+index+`" id="unit_`+index+`" style="font-size: 16px; font-weight: 400;" />
                </td>
                <td style="width: 10%">
                    <button type="button" name="remove" id="btn_del_`+index+`" class="btn btn-danger btn_remove">X</button>
                </td>
            </tr>`
        )
        $('#product_'+index).focus();
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

    $('#add').click(function() {
        addTableRow(indexTable);
        indexTable++;
        showAddRowButton();
    });

    $(document).on('click', '.btn_remove', function() {
        var buttonId = $(this).attr("id");

        var tempArr = buttonId.split("_");
        $('#row_'+tempArr[2]).remove();

        enableTableRow(tempArr[2]);
        showAddRowButton();
    });

    $('#siteFrom').change(function() {
        /** Reset input value */
        resetTable();

        getListSiteTo();
        getListProduct();
	});

    $(document).on('focus', '.product', function() {
        var productId = $(this).attr("id");
        autocompleteProduct(productId);
    });

    $(document).on('change', '.location', function() {
        var locationId = $(this).attr("id");

        var tempArr = locationId.split("_");
        hideLocationErrorMessage(tempArr[1]);
        hideQtyErrorMessage(tempArr[1]);
        enableTableRow(tempArr[1]);
        getStockQty(tempArr[1]);
    });

    $(document).on('change', '.qty', function() {
        var qtyId = $(this).attr("id");

        var tempArr = qtyId.split("_");
        validateQty(tempArr[1]);
    });

    function autocompleteProduct(productId) {
        if (productListData.length > 0) {
            $('#'+productId).autocomplete({
                minLength: 2,
                ignoreCase: false,
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

                    /** Set product value */
                    $('#'+productId+'_id').val(ui.item.key);
                    $('#'+productId).val(ui.item.label);
                    $('#'+productId).autocomplete('close');

                    /** Check duplicate product */
                    if (checkDuplicateProduct(tempArr[1])) {
                        $('#location_'+tempArr[1]).prop('disabled', true);
                        $('#qty_'+tempArr[1]).prop('disabled', true);
                        return;
                    }

                    /** Reset qty, stock, unit */
                    document.getElementById("qty_"+tempArr[1]).value = '';
                    document.getElementById("stock_qty_"+tempArr[1]).innerHTML = '';
                    document.getElementById("unit_"+tempArr[1]).innerHTML = '';
                    hideLocationErrorMessage(tempArr[1]);
                    hideQtyErrorMessage(tempArr[1]);

                    /** Get list product location */
                    getListProductLocation(tempArr[1]);

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

    function resetTableRow(index) {
        document.getElementById("product_"+index+"_id").value = '';
        document.getElementById("location_"+index).value = '';
        document.getElementById("qty_"+index).value = '';
        document.getElementById("stock_qty_"+index).innerHTML = '';
        document.getElementById("unit_"+index).innerHTML = '';

        $('#location_'+index).find('option').remove().end().append();
        $('#location_'+index).append('<option value="" disabled selected>Select location</option>');
        $('#location_'+index).prop('disabled', true);
        $('#qty_'+index).prop('disabled', true);

        hideLocationErrorMessage(index);
        hideQtyErrorMessage(index);
    }

    function resetTable() {
        var table = document.getElementById("tableInput");
        for (var i = 1, row ; row = table.rows[i] ; i++) {
            var tempArr = row.id.split("_");
            var index = tempArr[1];

            document.getElementById("product_"+index).value = '';
            hideProductErrorMessage(index);
            resetTableRow(index);
        }
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

    function checkDuplicateProduct(data) {
        var productId = $('#product_'+data+'_id').val();

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

    function getListSiteFrom() {
        $.ajax({
            type: 'GET',
            url: "{{ url('/get-all-user-site-permission') }}",
            dataType: 'json',
            data: {},
            success: function(response) {
                var data = response;

                /** Set dropdown list */
                $('#siteFrom').find('option').remove().end().append();
                if (data.length != 1) {
                    $('#siteFrom').append('<option value="" disabled selected>Select from site</option>');
                }
                for (var i = 0; i < data.length; i++) {
                    text = data[i].store_code+' - '+data[i].site_description;
                    value = data[i].site_id;
                    $('#siteFrom').append($("<option></option>").attr("value", value).text(text));
                }

                if (data.length == 1) {
                    /** Reset input value */
                    resetTable();

                    getListSiteTo();
                    getListProduct();
                }

                /** Enabled dropdown */
                $('#siteFrom').prop('disabled', false);
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

    function getListSiteTo() {
        var siteFrom = $('#siteFrom').val();

        $.ajax({
            type: 'GET',
            url: "{{ url('/get-trf-site-to-list') }}",
            dataType: 'json',
            data: {
                from_site_id: siteFrom,
            },
            success: function(response) {
                var data = response;

                /** Set dropdown list */
                $('#siteTo').find('option').remove().end().append();
                if (data.length != 1) {
                    $('#siteTo').append('<option value="" disabled selected>Select to site</option>');
                }
                for (var i = 0; i < data.length; i++) {
                    text = data[i].store_code+' - '+data[i].site_description;
                    value = data[i].site_id;
                    $('#siteTo').append($("<option></option>").attr("value", value).text(text));
                }

                /** Enabled dropdown */
                $('#siteTo').prop('disabled', false);
            },
            error: function(error) {
                console.log(error.responseJSON);
                Swal.fire({
                    icon: 'error',
                    title: "Error",
                    text: error.responseJSON.message ?? 'Failed get list site to',
                });
            },
        });
    }

    function getListProduct() {
        var siteFrom = $('#siteFrom').val();

        $.ajax({
            type: 'GET',
            url: "{{ url('/get-trf-product-list') }}",
            dataType: 'json',
            data: {
                from_site_id: siteFrom,
            },
            success: function(response) {
                productListData = response.map(function (item) {
                    return {
                        label: item.catg_code+' - '+item.catg_name,
                        value: item.catg_code+' - '+item.catg_name,
                        key: item.product_id,
                    }
                });

                if (response.length > 0) {
                    enableTableRow(-1);
                } else {
                    disableTableRow(-1);
                }
            },
            error: function(error) {
                console.log(error.responseJSON);
                Swal.fire({
                    icon: 'error',
                    title: "Error",
                    text: error.responseJSON.message ?? 'Failed get list product',
                });
            },
        });
    }

    function getListProductLocation(index) {
        var siteFrom = $('#siteFrom').val();
        var product = $('#product_'+index+'_id').val();

        $.ajax({
            type: 'GET',
            url: "{{ url('/get-trf-product-location-list') }}",
            dataType: 'json',
            data: {
                from_site_id: siteFrom,
                product_id: product,
            },
            success: function(response) {
                var data = response;

                /** Set dropdown list */
                $('#location_'+index).find('option').remove().end().append();
                if (data.length != 1) {
                    $('#location_'+index).append('<option value="" disabled selected>Select location</option>');
                }
                for (var i = 0; i < data.length; i++) {
                    text = data[i].location_name;
                    value = data[i].location_id;
                    $('#location_'+index).append($("<option></option>").attr("value", value).text(text));
                }

                /** Enabled dropdown */
                $('#location_'+index).prop('disabled', false);

                /** Get stock qty if location only one */
                if (data.length == 1) {
                    getStockQty(index);
                } else {
                    $('#qty_'+index).prop('disabled', true);
                }
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

    function getStockQty(index) {
        var siteFrom = $('#siteFrom').val();
        var product = $('#product_'+index+'_id').val();
        var location = $('#location_'+index).val();

        /** Reset qty, stock, unit */
        document.getElementById("qty_"+index).value = '';
        document.getElementById("stock_qty_"+index).innerHTML = '';
        document.getElementById("unit_"+index).innerHTML = '';

        $.ajax({
            type: 'GET',
            url: "{{ url('/get-trf-stock-qty') }}",
            dataType: 'json',
            data: {
                from_site_id: siteFrom,
                product_id: product,
                location_id: location,
            },
            success: function(response) {
                document.getElementById("stock_qty_"+index).innerHTML = response?.data_stock?.quantity - response?.data_stock_booking;
                document.getElementById("unit_"+index).innerHTML = response?.data_stock?.unit;

                /** Enabled text input */
                $('#qty_'+index).prop('disabled', false);
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

    function validateQty(index) {
        var qty = $('#qty_'+index).val();
        var stockQty = document.getElementById("stock_qty_"+index).innerHTML;

        if (parseInt(qty) > parseInt(stockQty)) {
            showQtyErrorMessage(index, 'Stock not available');
            disableTableRow(index);
        } else {
            hideQtyErrorMessage(index);
            enableTableRow(index);
        }
    }

    function enableTableRow(index) {
        var table = document.getElementById("tableInput");
        for (var i = 1, row ; row = table.rows[i] ; i++) {
            var tempArr = row.id.split("_");

            if (tempArr[1] != index) {
                productId = $('#product_'+tempArr[1]+'_id').val();
                locationId = $('#location_'+tempArr[1]).val();
                qty = $('#qty_'+tempArr[1]).val();

                $('#product_'+tempArr[1]).prop('disabled', false);
                $('#btn_del_'+tempArr[1]).prop('disabled', false);

                if (productId != undefined && productId != '') {
                    $('#location_'+tempArr[1]).prop('disabled', false);
                }
                if (productId != undefined && productId != '' && locationId != undefined && locationId != '') {
                    $('#qty_'+tempArr[1]).prop('disabled', false);
                }
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
                $('#location_'+tempArr[1]).prop('disabled', true);
                $('#qty_'+tempArr[1]).prop('disabled', true);
                $('#btn_del_'+tempArr[1]).prop('disabled', true);
            }
        }

        /** Disabled button */
        $('#add').prop('disabled', true);
        $("#btn-submit").prop('disabled', true);
    }

    $(document).on('click', '#btn-submit', function(event) {
        event.preventDefault();
        $("#btn-submit").prop('disabled', true);

        var transferDate = $('#transferDate').val();
        var siteFrom = $('#siteFrom').val();
        var siteTo = $('#siteTo').val();
        var table = document.getElementById("tableInput");
        var detailData = [];

        /** Prepare data for detail data */
        for (var i = 1, row ; row = table.rows[i] ; i++) {
            var tempArr = row.id.split("_");
            var productId = $('#product_'+tempArr[1]+'_id').val();
            var locationId = $('#location_'+tempArr[1]).val();
            var qty = $('#qty_'+tempArr[1]).val();

            /** Get data detail that contain product id */
            if (productId != '') {
                /** Check product duplicate or not */
                var checkDuplicate = detailData.find(function (element) {
                    return element.product_id == productId;
                });
                if (checkDuplicate != undefined) {
                    showProductErrorMessage(tempArr[1], 'Item already exists');
                    disableTableRow(tempArr[1]);
                    return;
                }
                /** Validate location input */
                if (locationId == null) {
                    showLocationErrorMessage(tempArr[1], 'Required');
                    disableTableRow(tempArr[1]);
                    return;
                }
                /** Validate qty input */
                if (qty == '') {
                    showQtyErrorMessage(tempArr[1], 'Required');
                    disableTableRow(tempArr[1]);
                    return;
                }
                /** Append to array detail */
                detailData.push(
                    {
                        product_id: productId,
                        location_id: locationId,
                        qty: qty,
                    }
                );
            }
        }

        $.ajax({
            type: 'POST',
            url: "{{ url('/post-trf-req-submit') }}",
            dataType: 'json',
            data: {
                transfer_date: transferDate,
                from_site_id: siteFrom,
                to_site_id: siteTo,
                detail: detailData,
            },
            success: function(response) {
                /** Disable all input field */
                $('#siteFrom').prop('disabled', true);
                $('#siteTo').prop('disabled', true);
                disableTableRow(-1);

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
                    text: error.responseJSON.message ?? 'Failed submit transfer request',
                });
                $("#btn-submit").prop('disabled', false);
            },
        });
    });

    // ----------------------------------------------------------------
    // $("#siteFromInput").on("focus", function() {
    //     $(this).autocomplete({
    //         minLength: 0,
    //         source: function(request, response) {
    //             $.ajax({
    //                 url: '/get-all-user-site-permission',
    //                 type: 'GET',
    //                 dataType: "json",
    //                 data: {
    //                     input_search: request.term
    //                 },
    //                 success: function(data) {
    //                     response($.map(data, function (item) {
    //                         return {
    //                             label: item.store_code+' - '+item.site_description,
    //                             value: item.site_id
    //                         };
    //                     }));
    //                 }
    //             });
    //         },
    //         select: function (event, ui) {
    //             $('#siteFromData').val(ui.item.value);
    //             $('#siteFromInput').val(ui.item.label);
    //             $('#siteFromInput').autocomplete('close');

    //             /** Reset site to */
    //             $('#siteToData').val('');
    //             $('#siteTo').val('');
    //             return false;
    //         }
    //     });
    //     $(this).trigger("keydown");
    // });
    // ----------------------------------------------------------------
</script>

@endsection
