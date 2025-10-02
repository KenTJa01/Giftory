@extends('layouts.main')
@section('containter')
<style>
    .d-none{
        display: none;
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
        <h4 style="margin-left: 15px; margin-top: 10px; font-weight: 500; color: #424976">New Stock Opname</h4>
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
        <h2 style="font-weight: 500">Create New Stock Opname</h2>
    </div>
    <img src="{{ asset('svg/circleLeft.svg') }}" id="circleLeft" alt="">
    <div class="container text-center mt-3" align="center" style="max-width:100%; margin-top: -20px">
        <form id="main_form">
            <img src="{{ asset('svg/circleRight.svg') }}" id="circleRight" alt="">
            <div class="container" id="divTable" style="max-width:100%;">
                <div class="container" id="divTable-white" style="max-width:100%;">
                    <div class="row" style="margin: 5px">
                        <div class="col">
                            <table align="left" style="color: black; width: 100%;">
                                <tr>
                                    <td align="left" style="width: 15%; font-size: 16px;">SO Date</td>
                                    <td style="width: 30%;">
                                        <input type="text" class="form-control inputAdd" name="soDate" id="soDate" readonly>
                                    </td>
                                    <td style="width: 5%;"></td>
                                    <td align="left" style="width: 20%; font-size: 16px;">Site</td>
                                    <td style="width: 30%;" align="left">
                                        <select name="site" id="site" class="form-control inputAdd" disabled>
                                            <option value="">Select site</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <!-- <td align="left" style="width: 15%; font-size: 16px;">SO Type</td> -->
                                    <td align="left" style="width: 15%; font-size: 16px;"></td>
                                    <td style="width: 30%;">
                                        <!-- <select name="soType" id="soType" class="form-control inputAdd">
                                            <option value="" selected disabled>Select type</option>
                                            <option value="full">Full</option>
                                            <option value="partial">Partial</option>
                                        </select> -->
                                        <input type="hidden" name="soType" id="soType" value="partial" />
                                    </td>
                                    <td style="width: 5%;"></td>
                                    <td align="left" style="width: 20%; font-size: 16px;">Location</td>
                                    <td style="width: 30%;">
                                        <select name="location" id="location" class="form-control inputAdd" disabled>
                                            <option value="">Select location</option>
                                        </select>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <img src="{{ asset('svg/line.svg') }}" alt="" style="width: 100%">
                    <div id="partialInput" class="d-none">
                        <h5 style="font-weight: 500">List Item</h5>
                        <div style="height: 290px;" id="tableForm">
                            <table class="table table-bordered border-dark align-middle" id="tableInput" style="width:100%;">
                                <thead class="thead-dark">
                                    <tr class="text-center" style="width: 100%; background-color: #35384B; color: white;">
                                        <th width="70%">Products</th>
                                        <th width="15%">Unit</th>
                                        <th width="15%">Action</th>
                                    </tr>
                                </thead>
                                <tbody style="background-color: white"></tbody>
                            </table>
                        </div>
                        <div class="row text-left" style="width: 400px; margin-left: 2px;">
                            <button type="button" id="add" class="btn btn-add-row" style="display: block;">
                                <font style="color: white">+ Add row</font>
                            </button>
                        </div>
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
        document.getElementById("soDate").value = todayDate;
        getListSite();

        /** SO partial only */
        generateInitialTable();
        document.getElementById("partialInput").classList.remove('d-none');

        /** Use select2 for dropdown */
        $("#site").select2();
    });

    /** Initate global variable */
    var indexTable = 1;
    var productListData = [];

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('#soType').change(function() {
        if ($('#soType').val() == 'partial') {
            generateInitialTable();
            document.getElementById("partialInput").classList.remove('d-none');
        } else if ($('#soType').val() == 'full') {
            $("#tableInput tbody").children().remove();
            document.getElementById("partialInput").classList.add('d-none');
        }
	});

    $('#site').change(function() {
        resetTable();
        getListProductLocation();
	});

    $('#location').change(function() {
        resetTable();
        getListProduct();
    });

    $('#add').click(function() {
        addTableRow(indexTable);
        indexTable++;
        // showAddRowButton();
    });

    $(document).on('click', '.btn_remove', function() {
        var buttonId = $(this).attr("id");

        var tempArr = buttonId.split("_");
        $('#row_'+tempArr[2]).remove();

        enableTableRow(tempArr[2]);
        // showAddRowButton();
    });

    $(document).on('focus', '.product', function() {
        var productId = $(this).attr("id");
        autocompleteProduct(productId);
    });


    function generateInitialTable() {
        indexTable = 2;
        for (var i = 0 ; i < indexTable ; i++) {
            addTableRow(i);
        }

        if (productListData.length == 0) {
            disableTableRow(-1);
        }
    }

    function addTableRow(index) {
        $('#tableInput').append(
            `<tr id="row_`+index+`" class="tableRow">
                <td width="70%">
                    <input type="hidden" name="product_`+index+`_id" id="product_`+index+`_id">
                    <input type="text" name="product_`+index+`" id="product_`+index+`" class="form-control product" placeholder="Input product">
                    <div name="err_product_`+index+`" id="err_product_`+index+`" style="text-align: left; display: none;">
                        <p name="err_product_msg_`+index+`" id="err_product_msg_`+index+`" style="margin-bottom: 0; color: red; font-size: 12px;"></p>
                    </div>
                </td>
                <td width="15%">
                    <p name="unit_`+index+`" id="unit_`+index+`" style="font-size: 16px; font-weight: 400;" />
                </td>
                <td width="15%">
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

    function resetTableRow(index) {
        document.getElementById("product_"+index+"_id").value = '';
        document.getElementById("unit_"+index).innerHTML = '';
    }

    function resetTable() {
        if ($('#soType').val() == 'partial') {
            productListData = [];
            $("#tableInput tbody").children().remove();
            generateInitialTable();
        }
    }

    function getListSite() {
        $.ajax({
            type: 'GET',
            url: "{{ url('/get-all-user-site-permission') }}",
            dataType: 'json',
            data: {},
            success: function(response) {
                var data = response;

                /** Set dropdown list */
                $('#site').find('option').remove().end().append();
                if (data.length != 1) {
                    $('#site').append('<option value="" disabled selected>Select site</option>');
                }
                for (var i = 0; i < data.length; i++) {
                    text = data[i].store_code+' - '+data[i].site_description;
                    value = data[i].site_id;
                    $('#site').append($("<option></option>").attr("value", value).text(text));
                }

                if (data.length == 1) {
                    resetTable();
                    getListProductLocation();
                }

                /** Enabled dropdown */
                $('#site').prop('disabled', false);
            },
            error: function(error) {
                console.log(error.responseJSON);
                Swal.fire({
                    icon: 'error',
                    title: "Error",
                    text: error.responseJSON.message ?? 'Failed get list site',
                });
            },
        });
    }

    function getListProductLocation() {
        var site = $('#site').val();

        $.ajax({
            type: 'GET',
            url: "{{ url('/get-stock-opname-product-location-list') }}",
            dataType: 'json',
            data: {
                site_id: site,
            },
            success: function(response) {
                var data = response;

                /** Set dropdown list */
                $('#location').find('option').remove().end().append();
                if (data.length != 1) {
                    $('#location').append('<option value="" disabled selected>Select location</option>');
                }
                for (var i = 0; i < data.length; i++) {
                    text = data[i].location_name;
                    value = data[i].location_id;
                    $('#location').append($("<option></option>").attr("value", value).text(text));
                }

                if (data.length == 1) {
                    resetTable();
                    getListProduct();
                }

                /** Enabled dropdown */
                $('#location').prop('disabled', false);
            },
            error: function(error) {
                console.log(error.responseJSON);
                Swal.fire({
                    icon: 'error',
                    title: "Error",
                    text: error.responseJSON.message ?? 'Failed get list location',
                });
            },
        });
    }

    function getListProduct() {
        $.ajax({
            type: 'GET',
            url: "{{ url('/get-stock-opname-product-list') }}",
            dataType: 'json',
            data: {
                site_id: $('#site').val(),
                location_id: $('#location').val(),
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

    function showProductErrorMessage(index, message) {
        document.getElementById("err_product_msg_"+index).innerHTML = message;
        document.getElementById("err_product_"+index).style.display = 'block';
    }

    function hideProductErrorMessage(index) {
        document.getElementById("err_product_msg_"+index).innerHTML = '';
        document.getElementById("err_product_"+index).style.display = 'none';
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

    function enableTableRow(index) {
        var table = document.getElementById("tableInput");
        for (var i = 1, row ; row = table.rows[i] ; i++) {
            var tempArr = row.id.split("_");

            if (tempArr[1] != index) {
                productId = $('#product_'+tempArr[1]+'_id').val();

                $('#product_'+tempArr[1]).prop('disabled', false);
                $('#btn_del_'+tempArr[1]).prop('disabled', false);
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
                $('#btn_del_'+tempArr[1]).prop('disabled', true);
            }
        }

        /** Disabled button */
        $('#add').prop('disabled', true);
        $("#btn-submit").prop('disabled', true);
    }

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
                        return;
                    }

                    /** Set unit */
                    document.getElementById("unit_"+tempArr[1]).innerHTML = ui.item.unit;

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

    $(document).on('click', '#btn-submit', function(event) {
        event.preventDefault();
        $("#btn-submit").prop('disabled', true);

        if ($('#soType').val() == 'full') {
            submitFullStockOpname();
        } else if ($('#soType').val() == 'partial') {
            submitPartialStockOpname();
        } else {
            $("#btn-submit").prop('disabled', false);

            return Swal.fire({
                title: 'Warning',
                text: 'SO Type not valid',
                icon: "warning",
                timerProgressBar: true,
                showConfirmButton: true,
            });
        }
    });

    function submitFullStockOpname() {
        $.ajax({
            type: 'POST',
            url: "{{ url('/post-stock-opname-full-submit') }}",
            dataType: 'json',
            data: {
                so_date: $('#soDate').val(),
                site: $('#site').val(),
                location: $('#location').val(),
                type: $('#soType').val(),
            },
            success: function(response) {
                /** Disable all input field */
                $('#soType').prop('disabled', true);
                $('#site').prop('disabled', true);
                $('#location').prop('disabled', true);

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
                    text: error.responseJSON.message ?? 'Failed submit full stock opname',
                });
                $("#btn-submit").prop('disabled', false);
            },
        });
    }

    function submitPartialStockOpname() {
        var table = document.getElementById("tableInput");
        var detailData = [];

        /** Prepare data for detail data */
        for (var i = 1, row ; row = table.rows[i] ; i++) {
            var tempArr = row.id.split("_");
            var productId = $('#product_'+tempArr[1]+'_id').val();

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
                /** Append to array detail */
                detailData.push(
                    {
                        product_id: productId,
                    }
                );
            }
        }

        $.ajax({
            type: 'POST',
            url: "{{ url('/post-stock-opname-partial-submit') }}",
            dataType: 'json',
            data: {
                so_date: $('#soDate').val(),
                site: $('#site').val(),
                location: $('#location').val(),
                type: $('#soType').val(),
                detail: detailData,
            },
            success: function(response) {
                /** Disable all input field */
                $('#soType').prop('disabled', true);
                $('#site').prop('disabled', true);
                $('#location').prop('disabled', true);
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
                    text: error.responseJSON.message ?? 'Failed submit partial stock opname',
                });
                $("#btn-submit").prop('disabled', false);
            },
        });
    }
</script>
@endsection
