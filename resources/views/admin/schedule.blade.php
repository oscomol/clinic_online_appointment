@extends('layout.admin.adminLayout')

@section('title')
    Dr. {{$doctor->name}}
@endsection

@section('adminContent')
{{-- <div class="d-flex" id="loading">
    Please wait
</div> --}}

@include('admin.schedule.reservationslist')

<div class="container-fluid" id="content">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Manage schedule by day</h3>
                </div>
                <div class="card-body">
                    <table id="doctor-sched" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th style="min-width: 180px">Status</th>
                                <th>Day</th>
                                <th>Maximum patient</th>
                                <th>Time(mins)/patient</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($schedules as $day => $item)
                                <tr id="{{ $day }}" class="day">

                                    @if ($item)
                                        <td style="min-width: 180px;">
                                            <span id="status-text-{{$day}}" class="badge bg-{{$item->status === 1 ? 'primary':'secondary'}} mt-2">{{$item->status === 1 ? 'Available':'Unavailable'}}</span>

                                            <span class="switch" id="switch-{{$day}}">
                                                <span class="d-none float-right status text-warning status-switch mt-1" id="status-{{$day}}" day={{$day}} scheduledId="{{$item->id}}">
                                                    <i id="stat-icon-{{$day}}" class="fas {{$item->status === 1 ? 'fa-toggle-on' : 'fa-toggle-off'}}" style="font-size: 20px;"></i>
                                                </span>
                                                <span class="d-none float-right span mr-2">
                                                    <button type="button" class="btn btn-outline-success ml-2 btn-sm" data-toggle="modal" data-target="#add-admin-{{ $day }}">
                                                        <i class="{{$item ? 'fas fa-edit':'fas fa-plus'}}"></i>
                                                    </button>
                                                </span>
                                            </span>

                                            <span class="succes-alert d-none" id="succes-alert-{{$day}}">
                                                <span id="success-status-{{$day}}" class="float-right text-success mt-1"><li class="fas fa-check"></li></span>
                                            </span>

                                            <span class="error-alert d-none" id="error-alert-{{$day}}">
                                                <span id="success-status-{{$day}}" class="float-right text-danger mt-1"><li class="fas fa-times"></li></span>
                                            </span>
                    
                                        </td>
                                        <th style="width: 200px;">
                                            {{ $day }}
                                        </th>
                                        <td>{{ $item->maxPatient }}</td>
                                        <td>{{ $item->allotedTime }}</td>
                                    @else
                                        <td>Not found
                                            <span class="d-none float-right span mr-2">
                                                <button type="button" class="btn btn-outline-success ml-2 btn-sm" data-toggle="modal" data-target="#add-admin-{{ $day }}">
                                                    <i class="fas fa-plus"></i>
                                                </button>
                                            </span>
                                        </td>
                                        <th style="width: 200px;">
                                            {{ $day }}
                                        </th>
                                        <td>Not found</td>
                                        <td>Not found</td>
                                    @endif

                                    
                                </tr>
                                @include('admin.schedule.updateSchedule')
                            @endforeach
                        </tbody>
                        
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@include('admin.schedule.doctorViewClient')
@endsection

@include('admin.schedule.scheduleScript')