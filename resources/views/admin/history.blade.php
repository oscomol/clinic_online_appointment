@extends('layout.admin.adminLayout')

@section('title')
    Appointments
@endsection

@section('adminContent')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">List of reservations</h3>
                        <button class="btn btn-sm btn btn-danger float-right ml-2 cancelAll d-none">Cancel</button>
                        <br class="break d-none">
                        <br class="break d-none">
                        <span class="removableSpan">
                            <span class="float-right d-flex gap-2 customSpan">
                                <select class="form-select selectDoctor w-50 removable" aria-label="Default select example">
                                    @foreach ($doctors as $item)
                                        <option value="{{$item->id}}">Dr. {{$item->name}}</option>
                                    @endforeach
                                </select>
                                <input type="text" class="form-control form-control-sm mx-2 dateSelect w-25 removable" placeholder="Select date" value="{{$today}}">
                                <select class="customSelect w-25 removable" name="customSelect" id="customSelect" value="1">
                                    @foreach ($statusData as $item)
                                    <option value="{{ $item->value }}" {{ $item->value == 1 ? 'selected' : '' }}>
                                        {{ $item->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </span>
                        </span>
                        <div class="row d-none smallScreenBtn">
                            <select class="form-select selectDoctor w-100 m-1" aria-label="Default select example">
                                @foreach ($doctors as $item)
                                    <option value="{{$item->id}}">Dr. {{$item->name}}</option>
                                @endforeach
                            </select>
                            <input type="text" class="form-control form-control-sm dateSelect w-100" placeholder="Select date" value="{{$today}}">
                            <select class="customSelect w-100 m-1" name="customSelect" id="customSelect">
                                @foreach ($statusData as $item)
                                <option value="{{ $item->value }}" {{ $item->value == 1 ? 'selected' : '' }}>
                                    {{ $item->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="loading">
                            <div class="spinner-grow" role="status">
                            </div>
                            <p>Documents loading. Please wait!</p>
                        </div>
    
                        <div class="err d-none">
                            <div class="spinner-grow" role="status">
                            </div>
                            <p>An error occur. Refetching!</p>
                        </div>


                        <table id="doctorPatientView" class="d-none table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th class="toShow" style="min-width: 140px;">Number</th>
                                    <th>Name</th>
                                    <th>Age</th>
                                    <th>Gender</th>
                                    <th>Time</th>
                                    <th class="toRemove">Number</th>
                                </tr>
                            </thead>
                            <tbody class="doctorPatientView">

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('admin.schedule.doctorViewClient')

    <form class="cancelReservation" action="/clinic/admin/cancel/reservation" method="POST">
        @csrf
        @method('POST')
    <div class="modal fade" id="cancelReservationModal" tabindex="-1" role="dialog" aria-labelledby="modal-default-label" aria-hidden="true">
           <div class="modal-dialog" role="document">
               <div class="modal-content">
                   <div class="modal-header">
                       <h5 class="modal-title" id="modal-default-label">Confirm</h5>
                       <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                           <span aria-hidden="true">&times;</span>
                       </button>
                   </div>
                   <div class="modal-body">
                    <p class="confirmCancel">Are you sure to cancel reservations ?</p>
                    <center class="d-none confirmSuccess">
                        <div class="mt-3">
 
                             <span id="success-status" class="text-success">
                                 <li class="fas fa-check" style="font-size: 50px;"></li>    
                             </span>
 
                        </div>
                         <p class="mt-2">Reservations cancelled succesfully!</p>
                     </center>
                   </div>
                   <div class="modal-footer">
                    <button type="submit" class="btn btn-danger btn btn-sm cancelBtnSubmit">
                        Cancel
                    </button>
                       <button type="button" class="btn btn-secondary btn btn-sm" data-dismiss="modal">Close</button>
                   </div>
               </div>
           </div>
       </div>
    </form>



@endsection

@include('admin.history.historyScript')

<style>
    .customW{
        width: 180px;
    }
    .selectDoctor{
        height: 31px;
        border-radius: 3px;
        padding: 2px;
    }
    .dateSelect{
        height: 31px;
    }
    .customSelect{
        height: 31px;
        border-radius: 3px;
        padding: 2px;
    }
    #loading, .err{
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        gap: 5px;
    }
    .otherCont{
        flex-direction: column;
    }
</style>