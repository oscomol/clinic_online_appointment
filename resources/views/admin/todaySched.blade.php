@extends('layout.admin.adminLayout')

@section('title')
    Weekly schedule
@endsection

@section('adminContent')

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{$currentWeek}}</h3>
                    <button class="btn btn-sm btn btn-danger float-right ml-2 cancelAll d-none">Cancel</button>
                    <br class="break d-none">
                    <br class="break d-none">
                    <span class="float-right d-flex gap-2 spanCont">
                        <select class="form-select selectDoctor removable" aria-label="Default select example">
                            @foreach ($doctors as $doctor)
                                <option value="{{$doctor->id}}">{{$doctor->name}}</option>
                            @endforeach
                        </select>
                        <select class="form-select selectDay mx-2 removable" aria-label="Default select example">
                            @foreach ($weekDaysWithDates as $dayDate)
                                @foreach ($dayDate as $day => $date)
                                    <option value="{{ $date }}">{{ $day }}</option>
                                @endforeach
                            @endforeach
                        </select>

                        <select class="customSelect removable" name="customSelect" id="customSelect" value="${status}">
                            @foreach ($statusData as $status)
                            <option value="{{ $status['value'] }}" {{ $status['value'] == 1 ? 'selected' : '' }}>{{ $status['name'] }}</option>
                            @endforeach
                        </select>
                    </span>

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

                        <table id="reservationAdminTable" class="table table-bordered table-striped table-cont">
                            <thead>
                                <tr>
                                    <th class="toShow" style="width: 150px;">Number</th>
                                    <th>Name</th>
                                    <th>Age</th>
                                    <th>gender</th>
                                    <th>Time</th>
                                    <th class="toRemove">Number</th>
                                </tr>
                            </thead>
                            <tbody id="reservationAdminData">
    
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

@include('admin.weeklySchedule.weeklyScheduleScript')


<style>
    .selectDoctor, .selectDay, .customSelect{
        width: 180px;
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
</style>