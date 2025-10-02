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
        <h2 style="font-weight: 500">Create New Adjustments</h2>
    </div>

    <img src="svg/circleLeft.svg" id="circleLeft" alt="">

    <div class="container text-center mt-3" align="center" style="max-width:100%; margin-top: -20px">

        <form id="main_form">

            <img src="svg/circleRight.svg" id="circleRight" alt="">

            <div class="container" id="divTable" style="max-width:100%;">

                <div class="container" id="divTable-white" style="max-width:100%;">

                    <div class="row" style="margin: 5px">
                        <div class="col">
                            <table align="left" style="color: black">
                                <tr>
                                    <td align="left" width="150px" style="font-size: 16px; width: 180px;">Adjustment Date</td>
                                    <td><input type="text" class="form-control" name="adjDate" id="adjDate" style="width: 300px" readonly></td>
                                </tr>
                                <tr>
                                    <td align="left" width="150px" style="font-size: 16px">Site</td>
                                    <td>
                                        <select name="selectSite" id="selectSite" class="form-control" style="width: 100%;">
                                            <option value="">Select site</option>
                                        </select>
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
                                    <th>Products Categories</th>
                                    <th>Location</th>
                                    <th>Reason</th>
                                    <th>Stock Qty</th>
                                    <th>Adjust Qty</th>
                                    {{-- <th>Unit</th> --}}
                                    <th>Update Qty</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody style="background-color: white">
                            </tbody>

                        </table>
                    </div>

                    <div class="row text-left" style="width: 400px; margin-left: 2px">
                        <a class="btn btn-add-row" id="add">
                            <font style="color: white">+ Add row</font>
                        </a>
                    </div>

                </div>

            </div>

            {{-- <button class="btn" id="btn-draft">Save Draft</button> --}}
            <button type="button" class="btn btn-primary" id="btn-submit">Submit</button>

        </form>

    </div>

</div>


<script>
    // GLOBAL SETUP CSRF
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
        }
    });

    // ADJUSTMENT DATE
    $(document).ready(function () {
        const todayDate = new Date().toLocaleDateString('id-ID');
        document.getElementById("adjDate").value = todayDate;
        getListSite();
        generateInitialTable();

        $("#selectSite").select2();
    });

    /** Initate global variable */
    var indexTable = 2;
    var productListData = [];

    function generateInitialTable() {
        for (var i = 0 ; i < indexTable ; i++) {
            addTableRow(i);
        }
        disableTableRow(-1);
    }

    $('#add').click(function() {
        addTableRow(indexTable);
        indexTable++;
    });

    function addTableRow(index) {
        $('#tableInput').append(
            `<tr id="row_`+index+`" class="tableRow">
                <td>
                    <input type="hidden" name="product_`+index+`_id" id="product_`+index+`_id">
                    <input type="text" name="product_`+index+`" id="product_`+index+`" class="form-control product" placeholder="Input product" style="width: 300px">
                    <div name="err_product_`+index+`" id="err_product_`+index+`" style="text-align: left; display: none;">
                        <p name="err_product_msg_`+index+`" id="err_product_msg_`+index+`" style="margin-bottom: 0; color: red; font-size: 12px;"></p>
                    </div>
                </td>
                <td>
                    <select class="form-control location" name="location_`+index+`" id="location_`+index+`" disabled>
                        <option value="">Select location</option>
                    </select>
                    <div name="err_location_`+index+`" id="err_location_`+index+`" style="text-align: left; display: none;">
                        <p name="err_location_msg_`+index+`" id="err_location_msg_`+index+`" style="margin-bottom: 0; color: red; font-size: 12px;"></p>
                    </div>
                </td>
                <td>
                    <select class="form-control reason" name="reason_`+index+`" id="reason_`+index+`" disabled>
                        <option value="null">Select reason</option>
                    </select>
                    <div name="err_reason_`+index+`" id="err_reason_`+index+`" style="text-align: left; display: none;">
                        <p name="err_reason_msg_`+index+`" id="err_reason_msg_`+index+`" style="margin-bottom: 0; color: red; font-size: 12px;"></p>
                    </div>
                </td>
                <input type="hidden" name="stock_qty_`+index+`_id" id="stock_qty_`+index+`_id">
                <td name="stock_qty_`+index+`" id="stock_qty_`+index+`"></td>
                <td>
                    <input type="number" class="form-control qty" name="qty_`+index+`" id="qty_`+index+`" placeholder="Qty" style="width: 90px; text-align: right" min="1" disabled>
                    <div name="err_qty_`+index+`" id="err_qty_`+index+`" style="text-align: left; display: none;">
                        <p name="err_qty_msg_`+index+`" id="err_qty_msg_`+index+`" style="margin-bottom: 0; color: red; font-size: 12px;"></p>
                    </div>
                </td>
                <td>
                    <input type="number" class="form-control" name="update_qty_`+index+`" id="update_qty_`+index+`" style="width: 90px; text-align: right" disabled>
                </td>
                <td><button type="button" name="remove" id="`+index+`" class="btn btn-danger btn_remove">X</button></td>
            </tr>`
        )
        $('#product_'+index).focus();
    }

    $(document).on('click', '.btn_remove', function() {
        var button_id = $(this).attr("id");
        $('#row_'+button_id).remove();

        enableTableRow(button_id);
    });

    function getListSite() {
        $.ajax({
            type: 'GET',
            url: "{{ url('/get-all-user-site-permission') }}",
            dataType: 'json',
            data: {},
            success: function(response) {
                $.each(response,function(key, value)
                {
                    $("#selectSite").append('<option value="' + value.site_id + '">' + value.store_code + ' - ' + value.site_description + '</option>');
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

    $('#selectSite').change(function() {
        resetTable();
        getListProduct();

    });

    function enableTableRow(index) {
        var table = document.getElementById("tableInput");
        for (var i = 1, row ; row = table.rows[i] ; i++) {
            var tempArr = row.id.split("_");

            if (tempArr[1] != index) {
                productId = $('#product_'+tempArr[1]+'_id').val();
                locationId = $('#location_'+tempArr[1]).val();
                qty = $('#qty_'+tempArr[1]).val();
                reason = $('#reason_'+tempArr[1]).val();

                $('#product_'+tempArr[1]).prop('disabled', false);
                $('#btn_del_'+tempArr[1]).prop('disabled', false);

                // if (locationId != undefined && locationId != '' && productId != undefined && productId != '') {
                //     $('#location_'+tempArr[1]).prop('disabled', false);
                // }
                // if (qty != undefined && qty != '' && productId != undefined && productId != '') {
                //     $('#qty_'+tempArr[1]).prop('disabled', false);
                // }
                if (productId != undefined && productId != '') {
                    $('#location_'+tempArr[1]).prop('disabled', false);
                    $('#qty_'+tempArr[1]).prop('disabled', false);
                    $('#reason_'+tempArr[1]).prop('disabled', false);
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
                $('#reason_'+tempArr[1]).prop('disabled', true);
                $('#btn_del_'+tempArr[1]).prop('disabled', true);
            }
        }

        /** Disabled button */
        $('#add').prop('disabled', true);
        $("#btn-submit").prop('disabled', true);
    }

    function resetTableRow(index) {

        document.getElementById("product_"+index+"_id").value = '';
        document.getElementById("location_"+index).value = '';
        document.getElementById("qty_"+index).value = '';
        document.getElementById("stock_qty_"+index).innerHTML = '';
        document.getElementById("reason_"+index).innerHTML = '';
        document.getElementById("update_qty_"+index).value = '';

        $('#location_'+index).find('option').remove().end().append();
        $('#location_'+index).append('<option value="" disabled selected>Select location</option>');
        $('#location_'+index).prop('disabled', true);

        $('#qty_'+index).prop('disabled', true);

        $('#reason_'+index).find('option').remove().end().append();
        $('#reason_'+index).append('<option value="" disabled selected>Select reason</option>');
        $('#reason_'+index).prop('disabled', true);

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

    function getListProduct() {
        var selectSite = $('#selectSite').val();

        $.ajax({
            type: 'GET',
            url: "{{ url('/get-adj-product-list') }}",
            dataType: 'json',
            data: {
                site_id: selectSite,
            },
            success: function(response) {
                productListData = response.map(function (item) {
                    return {
                        label: item.catg_code+' - '+item.catg_name,
                        value: item.catg_code+' - '+item.catg_name,
                        key: item.product_id,
                        unit: item.unit,
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

    $(document).on('focus', '.product', function() {
        var productId = $(this).attr("id");
        autocompleteProduct(productId);
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

                    var tempArr = productId.split("_");

                    if (ui.item?.key == undefined) {
                        resetTableRow(tempArr[1]);

                        showProductErrorMessage(tempArr[1], 'Item not found');
                        disableTableRow(tempArr[1]);

                    } else {
                        checkDuplicateProduct(tempArr[1]);
                    }
                },
                select: function (event, ui) {
                    var tempArr = productId.split("_");
                    resetTableRow(tempArr[1]);

                    /** Set product value */
                    $('#'+productId+'_id').val(ui.item.key);
                    $('#'+productId).val(ui.item.label);
                    $('#'+productId).autocomplete('close');

                    /** Check duplicate product */
                    if (checkDuplicateProduct(tempArr[1])) {
                        $('#location_'+tempArr[1]).prop('disabled', true);
                        $('#qty_'+tempArr[1]).prop('disabled', true);
                        $('#reason_'+tempArr[1]).prop('disabled', true);
                        return;
                    }

                    $('#location_'+tempArr[1]).prop('disabled', false);

                    // $('#qty_'+tempArr[1]).prop('disabled', false);
                    // $('#reason_'+tempArr[1]).prop('disabled', false);

                    hideLocationErrorMessage(tempArr[1]);
                    hideQtyErrorMessage(tempArr[1]);

                    getLocation(productId);

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


    // ===== SHOW & HIDE PRODUCT ERROR ======================================
    function showProductErrorMessage(index, message) {
        document.getElementById("err_product_msg_"+index).innerHTML = message;
        document.getElementById("err_product_"+index).style.display = 'block';
    }

    function hideProductErrorMessage(index) {
        document.getElementById("err_product_msg_"+index).innerHTML = '';
        document.getElementById("err_product_"+index).style.display = 'none';
    }


    // ===== SHOW & HIDE LOCATION ERROR ======================================
    function showLocationErrorMessage(index, message) {
        document.getElementById("err_location_msg_"+index).innerHTML = message;
        document.getElementById("err_location_"+index).style.display = 'block';
    }

    function hideLocationErrorMessage(index) {
        document.getElementById("err_location_msg_"+index).innerHTML = '';
        document.getElementById("err_location_"+index).style.display = 'none';
    }


    // ===== SHOW & HIDE REASON ERROR ======================================
    function showReasonErrorMessage(index, message) {
        document.getElementById("err_reason_msg_"+index).innerHTML = message;
        document.getElementById("err_reason_"+index).style.display = 'block';
    }

    function hideReasonErrorMessage(index) {
        document.getElementById("err_reason_msg_"+index).innerHTML = '';
        document.getElementById("err_reason_"+index).style.display = 'none';
    }


    // ===== SHOW & HIDE QTY ERROR ======================================
    function showQtyErrorMessage(index, message) {
        console.log(message);
        document.getElementById("err_qty_msg_"+index).innerHTML = message;
        document.getElementById("err_qty_"+index).style.display = 'block';
    }

    function hideQtyErrorMessage(index) {
        document.getElementById("err_qty_msg_"+index).innerHTML = '';
        document.getElementById("err_qty_"+index).style.display = 'none';
    }


    // ===== CHECK DUPLICATE PRODUCT ========================================
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


    // ===== SELECT LOCATION ===============================
    function getLocation(productId)
    {
        if (productListData.length > 0) {

            var tempArr = productId.split("_");
            var product = $('#'+productId+'_id').val();

            var selectSite = $('#selectSite').val();

            $.ajax({
                type: 'GET',
                url: "{{ url('/get-adj-location') }}",
                dataType: 'json',
                data: {
                    catg_id: product,
                    site_id: selectSite,
                },
                success: function(response) {
                    $('#location_'+tempArr[1]).find('option').remove().end().append();
                    $('#location_'+tempArr[1]).append('<option value="" disabled selected>Select location</option>');

                    $.each(response,function(key, value)
                    {
                        $('#location_'+tempArr[1]).append('<option value="'+value.location_id+'">' + value.location_code + ' - ' + value.location_name + '</option>');
                    });
                }

            });
        }

    }

    function getReason(index) {
        console.log(index);
        $.ajax({
            type: 'GET',
            url: "{{ url('/get-adj-reason') }}",
            dataType: 'json',
            success: function(response) {
                $('#reason_'+index).find('option').remove().end().append();
                $('#reason_'+index).append('<option value="" disabled selected>Select reason</option>');

                $.each(response,function(key, value)
                {
                    $('#reason_'+index).append('<option value="'+value.id+'|'+value.default_operator+'">' + value.reason_code + ' - ' + value.reason_desc + '</option>');
                });
            }

        });
    }

    $(document).on('change', '.location', function() {
        var locationId = $(this).attr("id");
        var tempArr = locationId.split("_");

        getReason(tempArr[1]);

        hideLocationErrorMessage(tempArr[1]);
        hideQtyErrorMessage(tempArr[1]);
        enableTableRow(tempArr[1]);

        $('#reason_'+tempArr[1]).prop('disabled', false);

        getListQtyUnit(tempArr[1]);
    });

    function getListQtyUnit(index) {
        var selectSite = $('#selectSite').val();
        var product = $('#product_'+index+'_id').val();
        var location = $('#location_'+index).val();

        /** Reset qty, stock, unit */
        document.getElementById("stock_qty_"+index).innerHTML = '';
        document.getElementById("qty_"+index).value = '';
        // $('.reason').append('<option value="" disabled selected>Select reason</option>');

        $.ajax({
            type: 'GET',
            url: "{{ url('/get-adj-qty-unit-list') }}",
            dataType: 'json',
            data: {
                site_id: selectSite,
                product_id: product,
                location_id: location,
            },
            success: function(response) {
                var book = 0;
                if (response?.stock_booking?.quantity == undefined){
                    book = 0;
                }else{
                    book = response?.stock_booking?.quantity;
                }
                document.getElementById("stock_qty_"+index).innerHTML = (response?.data_stock?.quantity)- (book);
                $('#stock_qty_'+index+'_id').val((response?.data_stock?.quantity) - (book));

                /** Enabled text input */
                $('#qty_'+index).prop('disabled', false);

                // $('#reason_'+index).prop('disabled', false);
                // getUpdateQty(index, response.data_stock.quantity);
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


    $(document).on('change', '.reason', function() {
        var id = $(this).attr("id");
        var tempArr = id.split("_");

        var dataQtyAdj = $('#qty_'+tempArr[1]).val();
        hideReasonErrorMessage(tempArr[1]);
        enableTableRow(tempArr[1]);

        console.log(dataQtyAdj);

        if ( dataQtyAdj != null && dataQtyAdj != '' ) {
            getUpdateQty(id)
        }
    });

    $(document).on('change', '.qty', function() {
        var id = $(this).attr("id");

        getUpdateQty(id)

    });

    function getUpdateQty(index) {
        var tempArr = index.split("_");

        var reason = $('#reason_'+tempArr[1]).val();
        var getOpr = reason.split("|");
        var qty = $('#qty_'+tempArr[1]).val();
        var stockQty = $('#stock_qty_'+tempArr[1]+"_id").val();

        if ( qty != null && reason != null ) {
            if ( parseInt(qty) > 9999 ){
                showQtyErrorMessage(tempArr[1], 'Max 9999');
                disableTableRow(tempArr[1]);
                return;
            }
            if (getOpr[1] == '-') {
                if (parseInt(qty) > parseInt(stockQty)) {
                    showQtyErrorMessage(tempArr[1], 'Stock not available');
                    disableTableRow(tempArr[1]);
                } else {
                    hideQtyErrorMessage(tempArr[1]);
                    enableTableRow(tempArr[1]);
                    $.ajax({
                        type: 'GET',
                        url: "{{ url('/get-adj-update-qty-list') }}",
                        dataType: 'json',
                        data: {
                            reasonData: getOpr[0],
                            qtyData: qty,
                            stockQtyData: stockQty,
                        },
                        success: function(response) {
                            // console.log(response);
                            document.getElementById("update_qty_"+tempArr[1]).value = response;
                        },
                        error: function(error) {
                            console.log(error.responseJSON);
                            Swal.fire({
                                icon: 'error',
                                title: "Error",
                                text: error.responseJSON.message ?? 'Failed get update quantity',
                            });
                        },
                    });
                }
            } else {
                hideQtyErrorMessage(tempArr[1]);
                enableTableRow(tempArr[1]);
                $.ajax({
                    type: 'GET',
                    url: "{{ url('/get-adj-update-qty-list') }}",
                    dataType: 'json',
                    data: {
                        reasonData: getOpr[0],
                        qtyData: qty,
                        stockQtyData: stockQty,
                    },
                    success: function(response) {
                        // console.log(response);
                        document.getElementById("update_qty_"+tempArr[1]).value = response;
                    },
                    error: function(error) {
                        console.log(error.responseJSON);
                        Swal.fire({
                            icon: 'error',
                            title: "Error",
                            text: error.responseJSON.message ?? 'Failed get update quantity',
                        });
                    },
                });
            }

        }
    }

    $(document).on('click', '#btn-submit', function(event) {

        $("#btn-submit").prop('disabled', true);

        var adjustmentDate = $('#adjDate').val();
        var selectSite = $('#selectSite').val();
        var table = document.getElementById("tableInput");
        var detailData = [];

        for (var i = 1, row ; row = table.rows[i] ; i++) {
            var tempArr = row.id.split("_");
            var productId = $('#product_'+tempArr[1]+'_id').val();
            var locationId = $('#location_'+tempArr[1]).val();
            var reasonId = $('#reason_'+tempArr[1]).val();
            var reason = null;
            var stockQtyId = $('#stock_qty_'+tempArr[1]+'_id').val();
            var qtyId = $('#qty_'+tempArr[1]).val();
            var updateQtyId = $('#update_qty_'+tempArr[1]).val();

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
                if (reasonId == null) {
                    showReasonErrorMessage(tempArr[1], 'Required');
                    disableTableRow(tempArr[1]);
                    return;
                } else {
                    reason = reasonId.split("|");
                }
                /** Validate qty input */
                if (qtyId == '') {
                    showQtyErrorMessage(tempArr[1], 'Required');
                    disableTableRow(tempArr[1]);
                    return;
                }
                /** Append to array detail */
                detailData.push(
                    {
                        product_id: productId,
                        location_id: locationId,
                        reason_id: reason[0],
                        stock_qty_id: stockQtyId,
                        qty_id: qtyId,
                        update_qty_id: updateQtyId,
                    }
                );
            }


        };

        $.ajax({
            type: 'POST',
            url: "{{ url('/post-adj-req-submit') }}",
            dataType: 'json',
            data: {
                adjustment_date: adjustmentDate,
                site: selectSite,
                detail: detailData,
            },
            success: function(response) {
                console.log(response);
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
                    text: error.responseJSON.message ?? 'Failed submit receiving request',
                });
                $("#btn-submit").prop('disabled', false);
            },
        });
        event.preventDefault();

        // var product = $('#product_'+index+'_id').val();
        // var location = $('#location_'+index).val();

    });

</script>

@endsection
