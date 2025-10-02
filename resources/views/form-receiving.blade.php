@extends('layouts.main')

@section('containter')
<style>
    .d-none{
        display: none;
    }
    .read-only{
        pointer-events: none;
        background-color: yellow;
    }
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
        <h2 style="font-weight: 500">Create New Receiving</h2>
    </div>

    <img src="svg/circleLeft.svg" id="circleLeft" alt="">

    <div class="container text-center mt-3" align="center" style="max-width:100%; margin-top: -20px">

        <form id="main_form">

            <img src="svg/circleRight.svg" id="circleRight" alt="">

            <div class="container" id="divTable" style="max-width:100%;">

                <div class="container" id="divTable-white" style="max-width:100%;">

                    <div class="row" style="margin: 5px">
                        <div class="col">
                            <table align="center" style="color: black" style="margin-right: 20px;">
                                <tr>
                                    <td align="left" style="width: 20%; font-size: 16px">Receiving Date</td>
                                    <td align="left" style="width: 30%">
                                        <input type="text" class="form-control" name="receivingDate" id="receivingDate" style="width: 300px" readonly>
                                    </td>
                                    <td width="5%"></td>
                                    <td align="left" style="width: 15%; font-size: 16px">Type</td>
                                    <td align="left" style="width: 30%">
                                        <select name="selectType" id="selectType" onchange="selectOpt(this)" class="form-control" style="width: 300px">
                                            <option value="">Select type</option>
                                            <option value="transfer">Transfer</option>
                                            <option value="supplier">Supplier</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="left" style="width: 20%; font-size: 16px">Site</td>
                                    <td align="left" style="width: 30%">
                                        <select name="selectSite" id="selectSite" class="form-control" style="width: 100%;" disabled>
                                            <option value="">Select site</option>
                                        </select>
                                    </td>
                                    <td width="5%"></td>
                                    <td align="left" class="trans d-none" style="width: 15%; font-size: 16px">From Transfer</td>
                                    <td class="trans d-none" style="width: 30%">
                                        <select name="selectTransfer" id="selectTransfer" class="form-control" style="width: 300px">
                                            <option value="">Select from transfer</option>
                                        </select>
                                    </td>
                                    <td align="left" class="supp d-none" style="width: 15%">From Supplier</td>
                                    <td class="supp d-none" style="width: 30%">
                                        {{-- <input type="text" name="selectSupplier" id="selectSupplier" class="form-control" placeholder="Enter Supplier" style="width: 300px;"> --}}
                                        {{-- <input type="hidden" name="supplier_id" id="supplier_id"> --}}
                                        <select name="selectSupplier" id="selectSupplier" class="form-control" style="width: 100%;">
                                            <option value="">Select supplier</option>
                                        </select>
                                    </td>
                                </tr>
                            </table>

                        </div>
                    </div>

                    <img src="svg/line.svg" alt="" style="width: 100%">

                    <div style="height: 290px;" id="tableForm">

                    </div>

                    <div class="row text-left" id="addRowDiv" style="width: 400px; margin-left: 2px">
                        <button type="button" id="add" class="btn btn-add-row" style="display: none;">
                            <font style="color: white">+ Add row</font>
                        </button>
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

    // RECEIVING DATE
    $(document).ready(function () {
        const todayDate = new Date().toLocaleDateString('id-ID');
        document.getElementById("receivingDate").value = todayDate;
        getListSite();

        $("#selectType").select2();
        $("#selectSite").select2();
        $("#selectSupplier").select2();
    });

    // SELECT TYPE : TRANSFER OR SUPPLIER
    function selectOpt(data){
        const trans = document.getElementsByClassName('trans');
        const supp = document.getElementsByClassName('supp');
        if (data.value == 'transfer'){
            trans[0].classList.remove('d-none');
            trans[1].classList.remove('d-none');
            supp[0].classList.add('d-none');
            supp[1].classList.add('d-none');
            $('#selectSite').prop('disabled', false);
            document.getElementById('add').style.display = 'none';
            $('#tableForm').html('');
            // tableTransfer();

            var site = $("#selectSite").val();
            if ( site != "" ) {
                getFromTransfer(site);
            }

        }
        else if (data.value == 'supplier'){
            trans[0].classList.add('d-none');
            trans[1].classList.add('d-none');
            supp[0].classList.remove('d-none');
            supp[1].classList.remove('d-none');
            $('#selectSite').prop('disabled', false);
            tableSupplier();
            // getListSupplier();

            var site = $("#selectSite").val();
            if ( site != "" ) {
                getFromSupplier();
            } else {
                $('#selectSupplier').prop('disabled', true);
            }
        }
    }

    var indexTable = 2;
    var productListData = [];

    // $('#selectSupplier').change(function(){
    //     tableSupplier();
    // });

    // ===== TABLE SUPPLIER ==================================================================================
    function tableSupplier() {
        $('#tableForm').html(
            `<h5 style="font-weight: 500">List Item</h5>
            <table class="table table-bordered border-dark align-middle" id="tableInput" style="width:100%;">
                <thead class="thead-dark">
                    <tr class="text-center" style="width: 100%; background-color: #35384B; color: white;">
                        <th>Products</th>
                        <th>Qty</th>
                        <th>Unit</th>
                        <th>To location</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody style="background-color: white"></tbody>
            </table>`
        )
        document.getElementById('add').style.display = 'block';

        for (var i = 0 ; i < indexTable ; i++) {
            addTableRow(i);
        }

        getListProduct();

    }

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
                    <input type="number" min="1" class="form-control qty" name="qty_`+index+`" id="qty_`+index+`" placeholder="Qty" style="width: 90px; text-align: right" disabled>
                    <div name="err_qty_`+index+`" id="err_qty_`+index+`" style="text-align: left; display: none;">
                        <p name="err_qty_msg_`+index+`" id="err_qty_msg_`+index+`" style="margin-bottom: 0; color: red; font-size: 12px;"></p>
                    </div>

                </td>
                <td>
                    <p name="unit_`+index+`" id="unit_`+index+`" style="font-size: 16px; font-weight: 400;" />
                    <input type="hidden" name="unit_` + index + `_hidden" id="unit_` + index + `_hidden" value="">
                </td>
                <td>
                    <select class="form-control location" name="location_`+index+`" id="location_`+index+`" disabled>
                        <option value="">Select location</option>
                    </select>
                    <div name="err_location_`+index+`" id="err_location_`+index+`" style="text-align: left; display: none;">
                        <p name="err_location_msg_`+index+`" id="err_location_msg_`+index+`" style="margin-bottom: 0; color: red; font-size: 12px;"></p>
                    </div>

                </td>
                <td><button type="button" name="remove" id="`+index+`" class="btn btn-danger btn_remove">X</button></td>
            </tr>`
        )
        $('#product_'+index).focus();
    }


    // ===== SHOW ADD BUTTON =================================================================================
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


    // ===== ADD CLICKED =====================================================================================
    $('#add').click(function() {
        addTableRow(indexTable);
        indexTable++;
        showAddRowButton();
    });


    // ===== BUTTON REMOVE CLICKED ===========================================================================
    $(document).on('click', '.btn_remove', function() {
        var button_id = $(this).attr("id");
        $('#row_'+button_id).remove();

        var buttonId = $(this).attr("id");

        var tempArr = buttonId.split("_");
        $('#row_'+tempArr[2]).remove();

        enableTableRow(tempArr[2]);
        showAddRowButton();

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


    // ===== SELECT SITE CHANGE => SHOW TRANSFER DATA ========================================================
    $('#selectSite').change(function() {

        var selectType = $("#selectType").val();
        $('#selectSupplier').prop('disabled', false);

		var site = $(this).val();

        if ( selectType == "supplier" ) {
            getFromSupplier();
        } else if ( selectType == "transfer" ) {
            getFromTransfer(site);
        }



	});

    function getFromTransfer(site) {

        $("#selectTransfer").html('<option value="">Select from transfer</option>');

		$.ajax({
            type: 'GET',
            url: "{{ url('/get-rcv-transfer-list') }}",
            dataType: 'json',
            data: {
                data_site: site
            },

			success: function(response) {
                $.each(response,function(key, value)
                {
                    $("#selectTransfer").append('<option value="' + value.id + '">' + value.trf_no + '</option>');
                });
			}

		});

    }

    function getFromSupplier() {

        // $("#selectSupplier").html('<option value="">Select from supplier</option>');

        $.ajax({
            type: 'GET',
            url: "{{ url('/get-rcv-supplier-list') }}",
            dataType: 'json',
            data: {},
			success: function(response) {
                $.each(response,function(key, value)
                {
                    $("#selectSupplier").append('<option value="' + value.id + '">' + value.supp_name + '</option>');
                });
			}

		});

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
                    $('#qty_'+tempArr[1]).prop('disabled', false);
                    $('#location_'+tempArr[1]).prop('disabled', false);
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

    function resetTableRow(index) {
        document.getElementById("product_"+index+"_id").value = '';
        document.getElementById("location_"+index).value = '';
        document.getElementById("qty_"+index).value = '';
        document.getElementById("unit_"+index).innerHTML = '';

        $('#location_'+index).find('option').remove().end().append();
        $('#location_'+index).append('<option value="" disabled selected>Select location</option>');
        $('#location_'+index).prop('disabled', true);
        $('#qty_'+index).prop('disabled', true);

        hideLocationErrorMessage(index);
        hideQtyErrorMessage(index);

        getLocationForSupp();
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


    // ===== AUTOCOMPLETE PRODUCT =============================================================================
    function getListProduct() {
        var selectSite = $('#selectSite').val();

        $.ajax({
            type: 'GET',
            url: "{{ url('/get-rcv-product-list') }}",
            dataType: 'json',
            data: {
                from_site_id: selectSite,
            },
            success: function(response) {
                productListData = response.map(function (item) {
                    return {
                        label: item.catg_code+' - '+item.catg_name,
                        value: item.catg_code+' - '+item.catg_name,
                        key: item.id,
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
        autocompleteProduct(productId);resetTableRow
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
                        return;
                    }

                    $('#qty_'+tempArr[1]).prop('disabled', false);
                    $('#location_'+tempArr[1]).prop('disabled', false);

                    document.getElementById('unit_'+tempArr[1]).innerHTML = ui.item.unit;
                    $('#unit_'+tempArr[1]+'_hidden').val(ui.item.unit);

                    hideLocationErrorMessage(tempArr[1]);
                    hideQtyErrorMessage(tempArr[1]);

                    getLocationForSupp(tempArr[1]);

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


    // ===== ERROR MESSAGE ====================================================================================

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
                showProductErrorMessage(data, 'Item already exist');
                disableTableRow(data);
                return true;
            } else {
                hideProductErrorMessage(data);
                enableTableRow(data);
            }
        }
        return false;
    }

    function tableTransfer(){

    }

    // ===== SELECT TRANSFER => AUTO SHOW DATA PRODUCT =====================================================
    $('#selectTransfer').change(function() {
		var transfer = $(this).val();

        $('#tableForm').html(
            `<h5 style="font-weight: 500">List Item</h5>
            <table class="table table-bordered border-dark align-middle" id="tableInput" style="width:100%;">
                <thead class="thead-dark">
                    <tr class="text-center" style="width: 100%; background-color: #35384B; color: white;">
                        <th>Products</th>
                        <th>Qty</th>
                        <th>Unit</th>
                        <th>To location</th>
                    </tr>
                </thead>
                <tbody style="background-color: white"></tbody>
            </table>`
        )

		$.ajax({
            type: 'GET',
            url: "{{ url('/get-rcv-transfer-detail') }}",
            dataType: 'json',
            data: {
                data_transfer: transfer
            },
			success: function(response) {
                // console.log(response)
                $.each(response,function(key, value)
                {
                    $('#tableInput').append(
                        `<tr id="row_`+key+`" class="tableRow">
                            <td>
                                ` + value.catg_desc + `
                                <input type="hidden" name="product_` + key + `_tf" id="product_` + key + `_tf" value="`+value.catg_id+`">
                            </td>
                            <td>
                                ` + value.quantity + `
                                <input type="hidden" name="qty_` + key + `_tf" id="qty_` + key + `_tf" value="`+value.quantity+`">
                            </td>
                            <td>
                                ` + value.unit + `
                                <input type="hidden" name="unit_` + key + `_tf" id="unit_` + key + `_tf" value="`+value.unit+`">
                            </td>
                            <td>
                                <select class="form-control location"  name="location_` + key + `_tf" id="location_` + key + `_tf" >
                                    <option value="">Select location</option>
                                </select>
                            </td>
                        </tr>`
                    )
                    getLocation(key);
                });
			}
		});


	});


    // ===== SELECT LOCATION ===============================================================================
    function getLocation(index)
    {
        $.ajax({
            type: 'GET',
            url: "{{ url('/get-rcv-location') }}",
            dataType: 'json',
			success: function(response) {
                // console.log(response)
                $('#location_'+index+'_tf').find('option').remove().end().append();
                $('#location_'+index+'_tf').append('<option value="" disabled selected>Select location</option>');

                $.each(response,function(key, value)
                {
                    $('#location_'+index+'_tf').append('<option value="'+value.id+'">' + value.location_code + ' - ' + value.location_name + '</option>');
                });
			}
		});
    }

    // ===== SELECT LOCATION ===============================================================================
    function getLocationForSupp(index)
    {
        $.ajax({
            type: 'GET',
            url: "{{ url('/get-rcv-location') }}",
            dataType: 'json',
			success: function(response) {
                // console.log(response)
                $('#location_'+index).find('option').remove().end().append();
                $('#location_'+index).append('<option value="" disabled selected>Select location</option>');

                $.each(response,function(key, value)
                {
                    $('#location_'+index).append('<option value="'+value.id+'">' + value.location_code + ' - ' + value.location_name + '</option>');
                });
			}
		});
    }


    // ===== SUBMITION =====================================================================================
    $(document).on('click', '#btn-submit', function(event)
    {

        $("#btn-submit").prop('disabled', true);

        var receivingDate = $('#receivingDate').val();
        var selectType = $('#selectType').val();
        var selectSite = $('#selectSite').val();
        var table = document.getElementById("tableInput");
        var detailData = [];

        // document.write(selectSite);

        // ===== IF TRANSFER
        if ( selectType == "transfer" ) {

            var selectTransfer = $('#selectTransfer').val();
            // document.write(selectTransfer);

            for (var i = 1, row ; row = table.rows[i] ; i++) {
                var tempArr = row.id.split("_");
                var productId = $('#product_'+tempArr[1]+'_tf').val();
                var qtyId = $('#qty_'+tempArr[1]+'_tf').val();
                var unitId = $('#unit_'+tempArr[1]+'_tf').val();
                var locationId = $('#location_'+tempArr[1]+'_tf').val();

                if( productId != '' && locationId != null && qtyId != '' && unitId != '' ){
                    detailData.push(
                        {
                            product_id: productId,
                            qty: qtyId,
                            unit_id: unitId,
                            location_id: locationId,
                        }
                    );
                }

            };

            console.log(detailData);

            $.ajax({
                type: 'POST',
                url: "{{ url('/post-rec-req-submit-trans') }}",
                dataType: 'json',
                data: {
                    receiving_date: receivingDate,
                    type: selectType,
                    site: selectSite,
                    detail: detailData,
                    transfer: selectTransfer,
                },
                success: function(response) {
                    // console.log(response);
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


        }

        // ===== ELSE IF SUPPLIER
        else if ( selectType == "supplier" ) {

            var selectSupplier = $('#selectSupplier').val();

            // document.write(selectSupplier);

            for (var i = 1, row ; row = table.rows[i] ; i++) {
                var tempArr = row.id.split("_");
                var productId = $('#product_'+tempArr[1]+'_id').val();
                var qtyId = $('#qty_'+tempArr[1]).val();
                var locationId = $('#location_'+tempArr[1]).val();

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
                            qty: qtyId,
                        }
                    );
                }


            };


            console.log(detailData);

            $.ajax({
                type: 'POST',
                url: "{{ url('/post-rec-req-submit-supp') }}",
                dataType: 'json',
                data: {
                    receiving_date: receivingDate,
                    type: selectType,
                    site: selectSite,
                    detail: detailData,
                    supplier: selectSupplier,
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
        }


    });

</script>

@endsection
