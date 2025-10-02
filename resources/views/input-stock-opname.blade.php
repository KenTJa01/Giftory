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
        <h2 style="font-weight: 500">Stock Input</h2>
    </div>
    <img src="{{ asset('svg/circleLeft.svg') }}" id="circleLeft" alt="">
    <div class="container text-center mt-3" align="center" style="max-width:100%; margin-top: -10px">
        <form id="main_form" autocomplete="off">
            <input type="hidden" name="soId" id="soId" value="{{ $so_header_data?->so_id }}">
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
                                                value="{{ $so_header_data?->site_code.' - '.$so_header_data?->store_code }}"
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
                        <table class="table table-bordered border-dark align-middle" id="tableInput" style="width:100%;">
                            <thead class="thead-dark">
                                <tr class="text-center" style="width: 100%; background-color: #35384B; color: white;">
                                    <td>Products</td>
                                    <td>Qty Counted</td>
                                    <td>Unit</td>
                                </tr>
                            </thead>
                            <tbody style="background-color: white">
                                @foreach($so_detail_data as $d)
                                <tr id="{{ 'row_'.$d->detail_id }}">
                                    <td style="width:50%;">{{ $d?->catg_code.' - '.$d?->catg_desc }}</td>
                                    <td style="width:30%;">
                                        <input type="number" min="0" class="form-control qty" placeholder="Qty" style="text-align: right;"
                                            name="{{ 'qty_'.$d->detail_id }}" id="{{ 'qty_'.$d->detail_id }}" value="{{ $d->after_quantity }}" />
                                    </td>
                                    <td style="width:20%;">{{ $d?->unit }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <button type="button" id="btn-submit" class="btn btn-primary">Save</button>            
        </form>
    </div>
</div>

<script>
    $(document).ready(function () {
        document.getElementById('list-stock-opname').classList.add('active');
    });

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
        }
    });

    function disableTableRow() {
        var table = document.getElementById("tableInput");
        for (var i = 1, row ; row = table.rows[i] ; i++) {
            var tempArr = row.id.split("_");

            $('#qty_'+tempArr[1]).prop('disabled', true);
        }

        /** Disabled button */
        $("#btn-submit").prop('disabled', true);
    }

    $(document).on('click', '#btn-submit', function(event) {
        event.preventDefault();
        $("#btn-submit").prop('disabled', true);

        var headerId = '{{ $so_header_data?->so_id }}';
        var table = document.getElementById("tableInput");
        var detailData = [];

        /** Prepare data for detail data */
        for (var i = 1, row ; row = table.rows[i] ; i++) {
            var tempArr = row.id.split("_");
            var detailId = tempArr[1];
            var qty = $('#qty_'+tempArr[1]).val();

            /** Append to array detail */
            detailData.push(
                {
                    detail_id: detailId,
                    qty: qty,
                }
            );
        }

        $.ajax({
            type: 'POST',
            url: "{{ url('/post-stock-opname-input-stock') }}",
            dataType: 'json',
            data: {
                header_id: headerId,
                detail: detailData,
            },
            success: function(response) {
                /** Disable all input field */
                disableTableRow();

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
                    text: error.responseJSON.message ?? 'Failed submit input stock',
                });
                $("#btn-submit").prop('disabled', false);
            },
        });
    });
</script>
@endsection
