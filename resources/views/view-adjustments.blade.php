@extends('layouts.main')

@section('containter')

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
        <h2 style="font-weight: 500">View Adjustments</h2>
    </div>

    <img src="{{ asset('svg/circleLeft.svg') }}" id="circleLeft" alt="">

    <div class="container text-center mt-3" align="center" style="max-width:100%; margin-top: -20px">

        <img src="{{ asset('svg/circleRight.svg') }}" id="circleRight" alt="">

        <div class="container" id="divTable" style="max-width:100%;">

            <div class="container" id="divTable-white" style="max-width:100%;">

                <div class="row" style="margin: 5px">
                    <div class="col d-flex">
                        <table align="left" style="color: black">
                            <tr>
                                <td align="left" style="font-size: 16px; width: 160px">Adjustment No</td>
                                <td><input type="text" class="form-control inputAdd" name="adjNum" id="adjNum" value="{{ $adj_header_data?->adj_no }}" style="width: 270px" disabled></td>
                            </tr>
                            <tr>
                                <td align="left" style="font-size: 16px">Adjustment Date</td>
                                <td><input type="text" class="form-control inputAdd" name="adjDate" id="adjDate" value="{{ $adj_header_data?->adj_date }}" style="width: 270px" disabled></td>
                            </tr>
                            <tr>
                                <td align="left" style="font-size: 16px">Site</td>
                                <td>
                                    <input type="text" class="form-control inputAdd" name="site" id="site" value="{{ $adj_header_data?->store_code.' - '.$adj_header_data?->site_description }}" style="width: 270px" disabled>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

                <img src="{{ asset('svg/line.svg') }}" alt="" style="width: 100%">

                <h5 style="font-weight: 500">List Product Categories</h5>

                {{-- <div style="height: 290px;" id="tableForm"> --}}
                    <table class="table table-bordered border-dark align-middle" id="table-data" style="width:100%;">
                        <thead class="thead-dark">
                            <tr class="text-center" style="width: 100%; background-color: #35384B; color: white;">
                                <td>Products Category</td>
                                <td>Location</td>
                                <td>Before Adjustment</td>
                                <td>Adjust Qty</td>
                                <td>After Adjustment</td>
                                <td>Unit</td>
                                <td>Reason</td>
                            </tr>
                        </thead>
                        <tbody style="background-color: white">
                            @foreach ( $adj_detail_data as $add )
                                <tr>
                                    <td>{{ $add->catg_desc.' - '.$add->catg_code }}</td>
                                    <td>{{ $add->location_name }}</td>
                                    <td>{{ $add->stock_before_adj }}</td>
                                    <td>{{ $add->adj_qty }}</td>
                                    <td>{{ $add->stock_after_adj }}</td>
                                    <td>{{ $add->unit }}</td>
                                    <td>{{ $add->reason_desc }}</td>
                                </tr>
                            @endforeach
                        </tbody>

                    </table>
                {{-- </div> --}}

            </div>

        </div>

        {{-- <button class="btn" id="btn-draft">Save Draft</button> --}}
        {{-- <button class="btn" id="btn-generate">Generate PDF</button> --}}

    </div>

</div>
<script>
    $(document).ready(function () {
        document.getElementById('list-adjustments').classList.add('active');
    });
</script>
@endsection
