@extends('layouts.main')
@section('containter')
<div class="row">
  <div class="container" align="center" style="max-width:100%;">
    <img src="images/item2.png" alt="" id="item2">
    <img src="images/item1.png" alt="" id="item1">
    <nav class="navbar navbar-expand-lg navbar-light" id="navbar-partial">
        <div class="container-fluid">
            <button type="button" id="sidebarCollapse" class="btn btn-primary">
                <i class="fa fa-bars"></i>
            </button>
            <h5 style="margin-left: 15px; margin-top: 10px; font-weight: 500; color: #424976">Welcome to Giftory!</h5>
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
        <div id="spaceDiv">
            <br><br>
        </div>
        <div id="homeDiv">
            <div class="image"><img class="fpdl" src="images/imgHome.jpg" /></div>
            <div id="homeDiv-white">
                <div id="homeDiv-text">
                    <h2 style="font-weight: 600" align="left">Welcome!</h2>
                    <p style="font-size: 20px; color: black" align="left">Here you can manage the gifts at Yogya’s inventory.</p>
                </div>
            </div>
        </div>
        <div id="expPendingApprove" style="max-width:100%; display:none;">
            <div class="dashCard" style="max-width:100%;">
                <div class="dashCardContainer" style="max-width:100%;">
                    <table style="color: black; width:100%;">
                        <tr>
                            <td style="width:90%;">Need approve expending</td>
                            <td style="width:10%;">
                                <a href="{{ '/list-expending' }}" title="View">
                                    <button class="btn-view">View</button>
                                </a>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <div id="trfPendingApprove" style="max-width:100%; display:none;">
            <div class="dashCard" style="max-width:100%;">
                <div class="dashCardContainer" style="max-width:100%;">
                    <table style="color: black; width:100%;">
                        <tr>
                            <td style="width:90%;">Need approve transfer</td>
                            <td style="width:10%;">
                                <a href="{{ '/list-transfer' }}" title="View">
                                    <button class="btn-view">View</button>
                                </a>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <div id="retPendingApprove" style="max-width:100%; display:none;">
            <div class="dashCard" style="max-width:100%;">
                <div class="dashCardContainer" style="max-width:100%;">
                    <table style="color: black; width:100%;">
                        <tr>
                            <td style="width:90%;">Need approve return</td>
                            <td style="width:10%;">
                                <a href="{{ '/list-return' }}" title="View">
                                    <button class="btn-view">View</button>
                                </a>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <div id="trfApprove" style="max-width:100%; display:none;">
            <div class="dashCard" style="max-width:100%;">
                <div class="dashCardContainer" style="max-width:100%;">
                    <table style="color: black; width:100%;">
                        <tr>
                            <td style="width:90%;">Need receiving transfer</td>
                            <td style="width:10%;">
                                <a href="{{ '/form-receiving' }}" title="View">
                                    <button class="btn-view">View</button>
                                </a>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        getExpPendingApproveList();
        getTrfPendingApproveList();
        getRetPendingApproveList();
        getTrfApproveList();
    });

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
        }
    });

    function showHomeDiv(data) {
        if (data) {
            document.getElementById('homeDiv').style.display = 'block';
            document.getElementById('spaceDiv').style.display = 'block';
        } else {
            document.getElementById('homeDiv').style.display = 'none';
            document.getElementById('spaceDiv').style.display = 'none';
        }
    }

    function getExpPendingApproveList() {
        $.ajax({
            type: 'GET',
            url: "{{ url('/get-exp-pending-approve-list') }}",
            dataType: 'json',
            data: {},
            success: function(response) {
                var data = response;
                console.log(data);

                if (data.length > 0) {
                    showHomeDiv(false);
                    document.getElementById('expPendingApprove').style.display = 'block';
                }
            },
            error: function(error) {
                console.log(error.responseJSON);
                Swal.fire({
                    icon: 'error',
                    title: "Error",
                    text: error.responseJSON.message ?? 'Failed get list expending pending approve',
                });
            },
        });
    }

    function getRetPendingApproveList() {
        $.ajax({
            type: 'GET',
            url: "{{ url('/get-ret-pending-approve-list') }}",
            dataType: 'json',
            data: {},
            success: function(response) {
                var data = response;

                if (data.length > 0) {
                    showHomeDiv(false);
                    document.getElementById('retPendingApprove').style.display = 'block';
                }
            },
            error: function(error) {
                console.log(error.responseJSON);
                Swal.fire({
                    icon: 'error',
                    title: "Error",
                    text: error.responseJSON.message ?? 'Failed get list return pending approve',
                });
            },
        });
    }

    function getTrfPendingApproveList() {
        $.ajax({
            type: 'GET',
            url: "{{ url('/get-trf-pending-approve-list') }}",
            dataType: 'json',
            data: {},
            success: function(response) {
                var data = response;

                if (data.length > 0) {
                    showHomeDiv(false);
                    document.getElementById('trfPendingApprove').style.display = 'block';
                }
            },
            error: function(error) {
                console.log(error.responseJSON);
                Swal.fire({
                    icon: 'error',
                    title: "Error",
                    text: error.responseJSON.message ?? 'Failed get list transfer pending approve',
                });
            },
        });
    }

    function getTrfApproveList() {
        $.ajax({
            type: 'GET',
            url: "{{ url('/get-trf-approve-list') }}",
            dataType: 'json',
            data: {},
            success: function(response) {
                var data = response;

                if (data.length > 0) {
                    showHomeDiv(false);
                    document.getElementById('trfApprove').style.display = 'block';
                }
            },
            error: function(error) {
                console.log(error.responseJSON);
                Swal.fire({
                    icon: 'error',
                    title: "Error",
                    text: error.responseJSON.message ?? 'Failed get list transfer approve',
                });
            },
        });
    }
</script>
@endsection
