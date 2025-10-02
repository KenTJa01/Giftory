@extends('layouts.main')

@section('containter')

<nav class="navbar navbar-expand-lg navbar-light" id="navbar-partial">
    <div class="container-fluid">

        <button type="button" id="sidebarCollapse" class="btn btn-primary">
            <i class="fa fa-bars"></i>
        </button>

        <h4 style="margin-left: 15px; margin-top: 10px; font-weight: 500; color: #424976">Receiving</h4>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="nav navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="/">Logout</a>
                </li>
            </ul>
        </div>
    </div>
</nav>


<div class="row">

    <div class="col text-center" id="title">
        <h2 style="font-weight: 500">Edit Draft Receiving</h2>
    </div>

    <img src="svg/circleLeft.svg" id="circleLeft" alt="">

    <div class="container text-center mt-3" align="center" style="max-width:100%; margin-top: -10px">

        <img src="svg/circleRight.svg" id="circleRight" alt="">

        <div class="container" id="divTable" style="max-width:100%;">
            
            <div class="container" id="divTable-white" style="max-width:100%;">
            

                <div class="row" style="margin: 5px">
                    <div class="col d-flex">
                        <table align="center" style="color: black">
                            <tr>
                                <td align="left">Receiving No</td>
                                <td><input type="text" class="form-control inputAdd" name="" id="" style="width: 350px"></td>
                            </tr>
                            <tr>
                                <td align="left">Receiving Date</td>
                                <td><input type="text" class="form-control inputAdd" name="" id="" placeholder="*default tanggal hari ini" style="width: 350px"></td>
                            </tr>
                            <tr>
                                <td align="left">Type</td>
                                <td>
                                    <select name="" id="" class="form-control inputAdd" style="width: 350px">
                                        <option value="">Select type</option>
                                    </select>
                                </td>
                            </tr>
                        </table>
                        <table align="center" style="color: black">
                            <tr>
                                <td align="left">Site</td>
                                <td>
                                    <select name="" id="" class="form-control inputAdd" style="width: 350px">
                                        <option value="">Select site</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td align="left">From Transfer</td>
                                <td>
                                    <select name="" id="" class="form-control inputAdd" style="width: 350px">
                                        <option value="">Select from transfer</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td align="left">From Supplier</td>
                                <td>
                                    <select name="" id="" class="form-control inputAdd" style="width: 350px">
                                        <option value="">*autocomplete select from master supplier</option>
                                    </select>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

                <img src="svg/line.svg" alt="" style="width: 100%">
                
                <h5 style="font-weight: 500">List Item</h5>

                <div style="height: 290px;" id="tableForm">
                    <table class="table table-bordered border-dark align-middle" id="table-data" style="width:100%;">
                        <thead class="thead-dark">
                            <tr class="text-center" style="width: 100%; background-color: #35384B; color: white;">
                                <th>Products</th>
                                <th>Qty</th>
                                <th>Unit</th>
                                <th>To location</th>
                                <th>Purch Price</th>
                                <th>Sales Price</th>
                            </tr>
                        </thead>
                        <tbody style="background-color: white">
                            <tr>
                                <td><input type="text" name="" id="" placeholder="*autocomplete" style="width: 300px"></td>
                                <td><input type="number" name="" id="" placeholder="Enter qty" style="width: 90px; text-align: right"></td>
                                <td style="color: red;">*get data from DB</td>
                                <td>
                                    <select class="form-label" name="" id="">
                                        <option value="">Select location</option>
                                    </select>
                                </td>
                                <td><input type="number" name="" id="" value="0.00" style="width: 90px; text-align: right"></td>
                                <td><input type="number" name="" id="" value="0.00" style="width: 90px; text-align: right"></td>
                            </tr>
                            <tr>
                                <td><input type="text" name="" id="" placeholder="*autocomplete" style="width: 300px"></td>
                                <td><input type="number" name="" id="" placeholder="Enter qty" style="width: 90px; text-align: right"></td>
                                <td style="color: red;">*get data from DB</td>
                                <td>
                                    <select class="form-label" name="" id="">
                                        <option value="">Select location</option>
                                    </select>
                                </td>
                                <td><input type="number" name="" id="" value="0.00" style="width: 90px; text-align: right"></td>
                                <td><input type="number" name="" id="" value="0.00" style="width: 90px; text-align: right"></td>
                            </tr>
                            <tr>
                                <td><input type="text" name="" id="" placeholder="*autocomplete" style="width: 300px"></td>
                                <td><input type="number" name="" id="" placeholder="Enter qty" style="width: 90px; text-align: right"></td>
                                <td style="color: red;">*get data from DB</td>
                                <td>
                                    <select class="form-label" name="" id="">
                                        <option value="">Select location</option>
                                    </select>
                                </td>
                                <td><input type="number" name="" id="" value="0.00" style="width: 90px; text-align: right"></td>
                                <td><input type="number" name="" id="" value="0.00" style="width: 90px; text-align: right"></td>
                            </tr>
                            <tr>
                                <td><input type="text" name="" id="" placeholder="*autocomplete" style="width: 300px"></td>
                                <td><input type="number" name="" id="" placeholder="Enter qty" style="width: 90px; text-align: right"></td>
                                <td style="color: red;">*get data from DB</td>
                                <td>
                                    <select class="form-label" name="" id="">
                                        <option value="">Select location</option>
                                    </select>
                                </td>
                                <td><input type="number" name="" id="" value="0.00" style="width: 90px; text-align: right"></td>
                                <td><input type="number" name="" id="" value="0.00" style="width: 90px; text-align: right"></td>
                            </tr>
                            <tr>
                                <td><input type="text" name="" id="" placeholder="*autocomplete" style="width: 300px"></td>
                                <td><input type="number" name="" id="" placeholder="Enter qty" style="width: 90px; text-align: right"></td>
                                <td style="color: red;">*get data from DB</td>
                                <td>
                                    <select class="form-label" name="" id="">
                                        <option value="">Select location</option>
                                    </select>
                                </td>
                                <td><input type="number" name="" id="" value="0.00" style="width: 90px; text-align: right"></td>
                                <td><input type="number" name="" id="" value="0.00" style="width: 90px; text-align: right"></td>
                            </tr>
                            <tr>
                                <td><input type="text" name="" id="" placeholder="*autocomplete" style="width: 300px"></td>
                                <td><input type="number" name="" id="" placeholder="Enter qty" style="width: 90px; text-align: right"></td>
                                <td style="color: red;">*get data from DB</td>
                                <td>
                                    <select class="form-label" name="" id="">
                                        <option value="">Select location</option>
                                    </select>
                                </td>
                                <td><input type="number" name="" id="" value="0.00" style="width: 90px; text-align: right"></td>
                                <td><input type="number" name="" id="" value="0.00" style="width: 90px; text-align: right"></td>
                            </tr>
                            <tr>
                                <td><input type="text" name="" id="" placeholder="*autocomplete" style="width: 300px"></td>
                                <td><input type="number" name="" id="" placeholder="Enter qty" style="width: 90px; text-align: right"></td>
                                <td style="color: red;">*get data from DB</td>
                                <td>
                                    <select class="form-label" name="" id="">
                                        <option value="">Select location</option>
                                    </select>
                                </td>
                                <td><input type="number" name="" id="" value="0.00" style="width: 90px; text-align: right"></td>
                                <td><input type="number" name="" id="" value="0.00" style="width: 90px; text-align: right"></td>
                            </tr>
                        </tbody>

                    </table>
                </div>

                <div class="row text-left" style="width: 300px; margin-left: 2px">
                    <button class="btn" id="btn-add-row">
                        {{-- <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus" viewBox="0 0 16 16">
                            <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                        </svg> --}}
                        + Add row
                    </button>
                </div>

            </div>

        </div>

        <button class="btn" id="btn-draft">Save Draft</button>
        <button class="btn" id="btn-submit">Submit</button>

    </div>

</div>
        
@endsection
