@extends('layout.admin.adminLayout')

@section('title')
    Administrator
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
                    <h3 class="card-title">List of administrators</h3>
                    <button type="button" class="btn btn-primary btn-sm float-right" data-toggle="modal" data-target="#add-admin"><i class="fas fa-plus"></i> <span class="ml-2">Add new</span></button>
                </div>
                <div class="card-body">
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Created</th>
                            </tr>
                        </thead>
                        <tbody id="tableData">

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@include('admin.administrator.addAdministrator')
<div id="del-modal"></div>
<div id="edit-modal"></div>
@endsection
@include('admin.administrator.jsscript')

<style>
    #loading {
        width: 100%;
        height: 70vh;
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: column;
    }
</style>