@extends('layout.client.clientLayout')

@section('title')
    Make an appointment
@endsection

@section('clientContent')
<input type="hidden" value="{{$user->id}}" id="userId">
<input type="hidden" value="{{$doctorsId}}" id="selectedDoctor">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Doctors</h3>
                        <input type="text" class="form-control float-right" id="date-picker-sched" placeholder="Choose date here">
                    </div>
                    <div class="card-body">
                        <table id="doctorDataTable" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th class="removable" style="width: 200px;">Select</th>
                                    <th class="drName">Name</th>
                                    <th>Specialty</th>
                                    <th>Price</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody id="tableDoctorData">
                                @foreach ($doctors as $item)
                                <tr id="doctor-row-{{$item->id}}" class="doctor-row {{$item->isSelected ? 'bg-warning':''}}">
                                    <td class="removable">
                                        <span class="float-right">
                                            <input class="form-check-input choosenDoctor" type="radio" 
                                                   name="doctor" id="choose-{{$item->id}}" value="{{$item->id}}"
                                                   {{$item->isSelected ? 'checked' : ''}}>
                                        </span>
                                    </td>
                                    <th class="drName">Dr. {{$item->name}}</th>
                                    <td>{{$item->specialty}}</td>
                                    <td>Php {{$item->checkupLimit}}</td>
                                    <td>
                                        <span id="status-text-{{$item->id}}" class="badge bg-{{$item->isAvailable === 0 ? 'primary':'secondary'}} mt-2">{{$item->isAvailable === 0 ? 'Available':'Unavailable'}}</span>
                                        <span class="float-right removableSpan">
                                            <input class="form-check-input choosenDoctor" type="radio" 
                                                   name="doctor" id="choose-{{$item->id}}" value="{{$item->id}}"
                                                   {{$item->isSelected ? 'checked' : ''}}>
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>                            
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-none schedResult">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title slotTitle">Date </h3>
                    </div>
                    <div class="card-body">
                        <table id="slotData" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th class="removable customTh" style="min-width: 130px;">Select</th>
                                    <th>Number</th>
                                    <th>Estimated time</th>
                                    <th>Availability</th>
                                </tr>
                            </thead>
                            <tbody class="slotBody">
                                
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
    @include('client.appointment.checkAvail')
    @include('client.appointment.patientForm')
    @include('client.appointment.cancelAppointment')
    @include('client.appointment.viewAppointment')
@endsection

@include('client.appointment.appointmentScript')

<style>
    .customTh{
        width: 250px;
    }
    .drName{
        min-width: 150px;;
    }
</style>