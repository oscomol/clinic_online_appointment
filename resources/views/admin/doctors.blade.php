@extends('layout.admin.adminLayout')

@section('title')
    Doctor's List
@endsection

@section('adminContent')

<div id="loading">
    <div class="spinner-grow" role="status">
    </div>
    <p id="loadingLabel">Documents loading. Please wait!</p>
</div>

<div class="d-none" id="content">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">List of doctors</h3>
                    <button type="button" class="btn btn-primary btn-sm float-right" data-toggle="modal" data-target="#add-doctor"><i class="fas fa-plus"></i> <span class="ml-2">Add new</span></button>
                </div>
                <div class="card-body">
                   
                    <table id="doctorDataTable" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th style="min-width: 160px;">Name</th>
                                <th class="removable">Status</th>
                                <th>Specialty</th>
                                <th>Payment</th>
                                <th class="otherRemovable">Status</th>
                                <th class="removable">Action</th>
                            </tr>
                        </thead>
                        <tbody id="tableDoctorData">

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@include('admin.doctor.addDoctor')
<div id="doctor-del-modal"></div>
<div id="doctor-view-modal"></div>
<div id="doctor-edit-modal"></div>
@endsection

@include('admin.doctor.doctorScript')



<style>
    .infoCont{
        display: flex;
        gap: 20px;
        margin-bottom: 20px;
    }
    .costumContainer{
        width: 50%;
        height: 170px;
    }
    .photo-preview{
        border: 1px solid gray;
        border-radius: 10px;
        display: flex;
        justify-content: center;
        align-items: center;
    }
    .infoContF{
        gap: 10px;
    }
    .preview{
        width: 100%;
        height: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
        position: relative;
    }
    #photo-preview-doctor{
        width: 100%;
        height: 100%;
    }

    #photo-picker-label1, #photo-picker-edit{
        position: absolute;
        right: 10px;
        bottom: 5px;
    }
    #loading {
        width: 100%;
        height: 70vh;
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: column;
    }
    .selectDoctor, #selectDoctor{
        height: 40px;
        width: 100%;
        padding: 2px;
    }
</style>