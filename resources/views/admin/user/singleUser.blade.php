@extends('layout.admin.adminLayout')

@section('title')
    {{ $searchUser->name }}
@endsection

@section('adminContent')
    <div class="container-fluid">

        <div class="row">
            <div class="col-lg-3 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3 id="all">{{ $reservations->count() }}</h3>
                        <p>All checkups</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-bag"></i>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-6">

                <div class="small-box bg-success">
                    <div class="inner">
                        <h3 id="scheduled">{{ $scheduled }}</h3>
                        <p>Scheduled</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-stats-bars"></i>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-6">

                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3 id="success">{{ $success }}</h3>
                        <p>Success</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-person-add"></i>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-6">

                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3 id="missed">{{ $missed }}</h3>
                        <p>Missed</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-pie-graph"></i>
                    </div>
                </div>
            </div>
        </div>




        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">List of reservations</h3>
                    </div>
                    <div class="card-body">
                        <table id="user-reservations" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th class="name">Doctor</th>
                                    <th class="name">Patient name</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody class="user-reservations">
                                @foreach ($reservations as $item)
                                    <tr>
                                        <td>{{ $item->doctor_name }}</td>
                                        <td>{{ $item->patientName }}</td>
                                        <td>{{ $item->date }}</td>

                                        <td>
                                            <span
                                                class="@if ($item->status == 1)badge badge-primary
                                                @elseif ($item->status == 2)
                                                    badge badge-danger
                                                @elseif ($item->status == 3)
                                                    badge badge-warning
                                                @elseif ($item->status == 5)
                                                    badge badge-success
                                                @else
                                                badge badge-info 
                                                @endif"
                                            >
                                                @if ($item->status == 1)
                                                    Scheduled
                                                @elseif ($item->status == 2)
                                                    Cancelled
                                                @elseif ($item->status == 3)
                                                    Cancelled by you
                                                @elseif ($item->status == 5)
                                                    Done
                                                @elseif ($item->status == 6)
                                                    Not attended
                                                @elseif ($item->status == 7)
                                                    Doctor not available
                                                @elseif ($item->status == 8)
                                                    System error
                                                @else
                                                    Unknown
                                                @endif
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
    </div>
@endsection

@section('script')
    <script type="module">
        $(function() {
            $('#user-reservations').DataTable({
                "paging": true,
                "lengthChange": false,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
            });
        })
    </script>
@endsection
