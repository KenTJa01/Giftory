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
        <h2 style="font-weight: 500">List of Unit</h2>
    </div>

    <img src="svg/circleLeft.svg" id="circleLeft" alt="">

    <div class="container mt-3 text-center " align="center" style="max-width:100%;">

        <img src="svg/circleRight.svg" id="circleRight" alt="">

        <div class="container" id="divTable" style="max-width:100%;">
            <div class="row">

                {{-- BUTTON ADD --}}
                <div class="col">
                    <button class="CartBtn" onclick="window.dialog_add.showModal();">
                        <span class="IconContainer">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-circle-fill" viewBox="0 0 16 16">
                                <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8.5 4.5a.5.5 0 0 0-1 0v3h-3a.5.5 0 0 0 0 1h3v3a.5.5 0 0 0 1 0v-3h3a.5.5 0 0 0 0-1h-3v-3z"/>
                            </svg>
                        </span>
                        <p class="text mt-3">Add</p>
                    </button>
                </div>

            </div>

            <table class="table table-bordered border-dark align-middle" id="table-data" style="width:100%;">
                <thead class="thead-dark">
                    <tr class="text-center" style="width: 100%; background-color: #35384B; color: white;">
                        <td>No</td>
                        <td>Unit Name</td>
                        <td>Active</td>
                        <td>Action</td>
                    </tr>
                </thead>
                <tbody style="background-color: white">
                    @php
                        $i=1;
                    @endphp
                    @foreach ($units as $unit)
                        <tr>
                            <td>@php echo $i++; @endphp</td>
                            <td>{{ $unit->unit_name }}</td>
                            @if ($unit->flag == 1)
                                <td>Active</td>
                            @else
                                <td>Non Active</td>
                            @endif
                            <td>
                                <button type="submit" class="btn btn-primary editUnit" onclick="window.dialog_edit.showModal();" data-u="{{ $unit }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                        <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                                        <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"/>
                                    </svg>
                                </button>
                                <button type="submit" class="btn btn-danger deleteUnit" data-id="{{ $unit->id }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                                        <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5Zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5Zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6Z"/>
                                        <path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1ZM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118ZM2.5 3h11V2h-11v1Z"/>
                                    </svg>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Add New Unit -->
<form action="/create-unit" method="post">
    @csrf
    <dialog id="dialog_add">
        <h4>Add New Unit</h4>

        <div id="formAddModal">
            <table align="center">
                <tr>
                    <td>Unit Name</td>
                    <td><input type="text" class="form-control inputAdd" name="unit_name" placeholder="Enter unit name" required></td>
                </tr>
                <tr>
                    <td>Flag Active</td>
                    <td>
                        <div class="checkbox-wrapper-51">
                            <input id="cbx-51" name="flag" type="checkbox" checked>
                            <label class="toggle" for="cbx-51">
                                <span>
                                    <svg viewBox="0 0 10 10" height="10px" width="10px">
                                        <path d="M5,1 L5,1 C2.790861,1 1,2.790861 1,5 L1,5 C1,7.209139 2.790861,9 5,9 L5,9 C7.209139,9 9,7.209139 9,5 L9,5 C9,2.790861 7.209139,1 5,1 L5,9 L5,1 Z"></path>
                                    </svg>
                                </span>
                            </label>
                        </div>
                    </td>
                </tr>
            </table>
        </div>

        <div class="row text-right">
            <div class="col">
                <a class="btn" id="btn-clear-modal">Clear</a>
                <button type="submit" class="btn" id="btn-submit-modal">Submit</button>
            </div>
        </div>
        <a onclick="window.dialog_add.close();" aria-label="close" class="x">❌</a>
    </dialog>
</form>

<!-- Modal Edit Unit -->
<form action="/edit-unit" method="post">
    @csrf
    <dialog id="dialog_edit">
        <h4>Edit Unit</h4>

        <div id="formAddModal">
            <table align="center">
                <tr>
                    <td>Unit Name</td>
                    <input type="hidden" name="id" id="unit_id">
                    <td><input type="text" class="form-control inputAdd" name="unit_name" id="unit_name" placeholder="Enter unit name"></td>
                </tr>
                <tr>
                    <td>Flag Active</td>
                    <td>
                        <div class="checkbox-wrapper-51">
                            <input id="cbx-51" name="flag" type="checkbox" class="flag_edid">
                            <label class="toggle" for="cbx-51">
                                <span>
                                    <svg viewBox="0 0 10 10" height="10px" width="10px">
                                        <path d="M5,1 L5,1 C2.790861,1 1,2.790861 1,5 L1,5 C1,7.209139 2.790861,9 5,9 L5,9 C7.209139,9 9,7.209139 9,5 L9,5 C9,2.790861 7.209139,1 5,1 L5,9 L5,1 Z"></path>
                                    </svg>
                                </span>
                            </label>
                        </div>
                    </td>
                </tr>
            </table>
        </div>

        <div class="row text-right">
            <div class="col">
                <a class="btn" id="btn-clear-modal">Clear</a>
                <button class="btn" id="btn-submit-modal">Submit</button>
            </div>
        </div>

        <a onclick="window.dialog_edit.close();" aria-label="close" class="x">❌</a>
    </dialog>
</form>

{{-- Delete Unit --}}
<form action="/delete-unit" method="post" id="deleteForm">
    @csrf
    <input type="hidden" name="id" id="unitID">
</form>

<script>
    $(document).ready(()=> {
        $('.editUnit').click(function(){
            const data = $(this).data('u');
            $('#unit_id').val(data.id);
            $('#unit_name').val(data.unit_name);
            if (data.flag == 1){
                $('.flag_edid').attr('checked', true);
            } else if (data.flag == 2){
                $('.flag_edid').attr('checked', false);
            }
        })

        $('.deleteUnit').click(function(){
            Swal.fire({
                title: 'Delete Unit',
                text: 'Are you sure want to delete this unit?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#424976',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    const data = $(this).data('id');
                    $('#unitID').val(data);
                    $('#deleteForm').submit();
                }
            });
        })
    })
</script>

@endsection
