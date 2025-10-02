@extends('layouts.main')
@section('containter')
<style>
    .btn-container {
        display: flex;
        justify-content: center;
    }
</style>
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
        <h2 style="font-weight: 500">Freeze Stock</h2>
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
                        @if ($so_header_data->so_type == 'PARTIAL')
                        <table class="table table-bordered border-dark align-middle" id="table-data" style="width:100%;">
                            <thead class="thead-dark">
                                <tr class="text-center" style="width: 100%; background-color: #35384B; color: white;">
                                    <td>Products</td>
                                    <td>Unit</td>
                                </tr>
                            </thead>
                            <tbody style="background-color: white">
                                @foreach($so_detail_data as $d)
                                <tr>
                                    <td>{{ $d?->catg_code.' - '.$d?->catg_desc }}</td>
                                    <td>{{ $d?->unit }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @elseif($so_header_data->so_type == 'FULL')
                        <h6 style="font-weight: 500">All Item in {{ $so_header_data?->location_name }}</h6>
                        @endif
                    </div>
                </div>
            </div>
            <button type="button" class="btn" id="btn-reject" style="margin-horizontal: 10px;">Cancel</button>
            <button type="button" class="btn" id="btn-approve">Freeze Stock</button>
            <div class="btn-container">
                <a href="{{ '/input-stock-opname/'.$so_id }}" title="Stock Input">
                    <button type="button" class="btn" id="btn-generate" style="display: none;">Stock Input</button>
                </a>
            </div>
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

    $(document).on('click', '#btn-approve', function(event) {
        var so_id = '{{ $so_header_data?->so_id }}';

        Swal.fire({
            icon: 'warning',
            title: "Are you sure?",
            text: "Freeze stock",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes",
            showLoaderOnConfirm: true,
            preConfirm: () => {
                freezeStock(event, so_id)
            }
        });
    });

    $(document).on('click', '#btn-reject', function(event) {
        var so_id = '{{ $so_header_data?->so_id }}';

        Swal.fire({
            icon: 'warning',
            title: "Are you sure?",
            text: "Cancel this transaction",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes",
            showLoaderOnConfirm: true,
            preConfirm: () => {
                cancelTrx(event, so_id)
            }
        });
    });

    function enableButton() {
        $("#btn-reject").prop('disabled', false);
        $("#btn-approve").prop('disabled', false);
    }

    function disableButton() {
        $("#btn-reject").prop('disabled', true);
        $("#btn-approve").prop('disabled', true);
    }

    function freezeStock(event, headerId) {
        disableButton();
        $.ajax({
            type: 'POST',
            url: "{{ url('/post-stock-opname-freeze-stock') }}",
            dataType: 'json',
            data: {
                header_id: headerId,
            },
            success: function(response) {
                document.getElementById('btn-reject').style.display = 'none';
                document.getElementById('btn-approve').style.display = 'none';
                document.getElementById('btn-generate').style.display = 'block';

                return Swal.fire({
                    title: response.title,
                    text: response.message,
                    timer: 5000,
                    icon: "success",
                    timerProgressBar: true,
                    showConfirmButton: true,
                });
            },
            error: function(error) {
                console.log(error.responseJSON);
                Swal.fire({
                    icon: 'error',
                    title: "Error",
                    text: error.responseJSON.message ?? 'Failed freeze stock',
                });
                enableButton();
            },
        });
        event.preventDefault();
    }

    function cancelTrx(event, headerId) {
        disableButton();
        $.ajax({
            type: 'POST',
            url: "{{ url('/post-stock-opname-cancel') }}",
            dataType: 'json',
            data: {
                header_id: headerId,
            },
            success: function(response) {
                document.getElementById('btn-reject').style.display = 'none';
                document.getElementById('btn-approve').style.display = 'none';

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
                    text: error.responseJSON.message ?? 'Failed cancel stock opname',
                });
                enableButton();
            },
        });
        event.preventDefault();
    }
</script>
@endsection
