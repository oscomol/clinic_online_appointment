@extends('layout.client.clientLayout')

@section('title')
    Records of appointments
@endsection

@section('clientContent')
    <div class="container-fluid">
       <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title slotTitle">Reservations</h3>
                    <span class="float-right customSpan w-25">
                        <select class="customSelect w-100">
                            @foreach ($category as $item)
                                <option value="{{$item->value}}">{{$item->name}}</option>
                            @endforeach
                        </select>
                    </span>
                </div>
                <div class="card-body">
                    <table id="records-history" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th class="names">Doctor</th>
                                <th class="names">Patient</th>
                                <th>Date</th>
                                <th>Expected time</th>
                                <th>Payment</th>
                            </tr>
                        </thead>
                        <tbody class="history-records">
                           
                        </tbody>
                    </table>
                </div>
           </div>
        </div>
       </div>
    </div>

    @include('client.history.viewHistory')
@endsection

@include('client.history.historyScript')

<style>
    .recordSelect {
        height: 30px;
        border-radius: 2px;
        margin-left: 10px;
        padding: 2px;
        width: 170px;
    }
    #customSelect{
        height: 30px;
        border-radius: 2px;
        margin-left: 10px;
        padding: 2px;
        width: 100%;
    }
    .names{
        min-width: 100px;
    }
</style>
