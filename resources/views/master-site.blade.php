@extends('layouts.main')

@section('containter')

<nav class="navbar navbar-expand-lg navbar-light" id="navbar-partial">
    <div class="container-fluid">

        <button type="button" id="sidebarCollapse" class="btn btn-primary">
            <i class="fa fa-bars"></i>
        </button>

        <h4 style="margin-left: 15px; margin-top: 10px; font-weight: 500; color: #424976">Data Master</h4>

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
        <h2 style="font-weight: 500">List of Site</h2>
    </div>

    <img src="svg/circleLeft.svg" id="circleLeft" alt="">

    <div class="container mt-3 text-center " align="center" style="max-width:100%;">

        <img src="svg/circleRight.svg" id="circleRight" alt="">

        <div class="container" id="divTable" style="max-width:100%;">

            <div class="row">
                {{-- <div class="col"></div> --}}
                <div class="col">
                    <div class="container d-flex" style="margin-top: 10px">
                        <input type="text" class="form-control inputFilter" id="siteCode" name="siteCode" placeholder="Site Code">
                        <input type="text" class="form-control inputFilter" id="storeCode" name="storeCode" placeholder="Store Code">
                        <input type="text" class="form-control inputFilter" id="siteDesc" name="siteDesc" placeholder="Site Description">

                        <button type="submit" class="btn" id="buttonSearch">
                            Search
                        </button>

                    </div>

                </div>

            </div>

            <table class="table table-bordered border-dark align-middle" id="tableData" style="width:100%; margin-top: 10px;">
                <thead class="thead-dark">
                    <tr class="text-center" style="width: 100%; background-color: #35384B; color: white;">
                        <td>Site Code</td>
                        <td>Store Code</td>
                        <td>Site Description</td>
                        <td>Active</td>
                    </tr>
                </thead>
                <tbody style="background-color: white">
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
     $('#buttonSearch').on('click', function() {
        getListExpending();
    });

    function getListExpending(){
        $('#buttonSearch').prop('disabled', true);
        tableData.ajax.reload();
        $('#buttonSearch').prop('disabled', false);
    }

    var tableData = $('#tableData').DataTable({
        serverSide: true,
        processing: true,
        paginate: true,
        autoWidth: true,
        scrollCollapse: true,
        dom: 'rtip',
        ajax: {
            type: 'GET',
            url: "{{ url('/get-list-sites-datatable') }}",
            data: function (d) {
                d.site_code = $('#siteCode').val();
                d.store_code = $('#storeCode').val();
                d.site_desc = $('#siteDesc').val();
            },
        },
        columns:[
            {data: 'site_code', name: 'site_code',},
            {data: 'store_code', name: 'store_code',},
            {data: 'site_description', name: 'site_description',},
            // {data: 'flag', name: 'flag',},
            {
                data: 'flag',
                render: function(data, type, row){
                    if (row.flag == 1){
                        return 'Active';
                    }else{
                        return 'Non-Active';
                    }
                }
            },
        ],
        order: [[0, 'asc']],
        columnDefs: [
            { className: "dt-center", targets: [0,1,2] }
        ],
        language: {
            loadingRecords: '&nbsp;',
            processing: '<div class="spinner" style="z-index: 1;"></div>',
            zeroRecords: "No data found",
        },
    });
</script>
@endsection
