@extends('layout.client.clientLayout')

@section('title')
    Dashboard
@endsection

@section('clientContent')
    <input type="hidden" id="userID" value="{{ $user->id }}">

    <div id="loading">
        <div class="spinner-grow" role="status">
        </div>
        <p class="msg">Documents loading. Please wait!</p>
    </div>

    <div class="d-none" id="dashboard">
        <div class="row">
            <div class="col-lg-3 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3 id="all">0</h3>
                        <p>All checkups</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-bag"></i>
                    </div>
                    <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>

            <div class="col-lg-3 col-6">

                <div class="small-box bg-success">
                    <div class="inner">
                        <h3 id="scheduled">0</h3>
                        <p>Scheduled</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-stats-bars"></i>
                    </div>
                    <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>

            <div class="col-lg-3 col-6">

                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3 id="success">0</h3>
                        <p>Success</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-person-add"></i>
                    </div>
                    <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>

            <div class="col-lg-3 col-6">

                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3 id="missed">0</h3>
                        <p>Missed</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-pie-graph"></i>
                    </div>
                    <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
        </div>
        @include('client.dashboard.weeklyRecap')
        <div class="container-fluid d-none recenCont pb-3">
            @include('client.dashboard.recent')
        </div>
    </div>
@endsection

@section('script')
    <script type="module">
        $(function() {

            getData();

            function getData(){
                console.log($('#userID').val())
                $.ajax({
                url: '/clinic/getData/dashboard/get?user=' + $('#userID').val(),
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    const {
                        all,
                        scheduled,
                        success,
                        missed,
                        recent,
                        currentWeek,
                        weeksData,
                        weeklyStatusData,
                        id
                    } = response;


                    $('#all').text(all);
                    $('#scheduled').text(scheduled);
                    $('#success').text(success);
                    $('#missed').text(missed);



                    $('#loading').removeClass().addClass('d-none');
                    $('#dashboard').removeClass().addClass('container-fluid');

                    displayWeeklyRecap(currentWeek, weeksData, weeklyStatusData);
                    $('.recenCont').removeClass('d-none');

                    if(recent?.length){
                        $('.recentlyAddedCont').removeClass('d-none');
                        $('.noRecent').addClass('d-none');
                        displayRecent(recent);
                        getScreenWidth();
                    }else{
                        $('.recentlyAddedCont').addClass('d-none');
                        $('.noRecent').removeClass('d-none');
                    }
                },
                error: function() {
                    $('.msg').text('Something went wrong. Refetch');
                    getData();
                }
            });

            getStatusRecord(1)

            $('.customSelect').change(function(){
                const val = $(this).val();
                getStatusRecord(val)
            })
            }


            function getScreenWidth() {

            var width = $(window).width();
                if(width < 500){
                    $('.smallScreen').removeClass('d-none');
                    $('.recentlyAddedCont').addClass('d-none');
                }else{
                    $('.smallScreen').addClass('d-none');
                    $('.recentlyAddedCont').removeClass('d-none');
                }
            }

            $(window).resize(function() {
                getScreenWidth();
            });

           

        });


        function displayWeeklyRecap(currentWeek, weeksData, data) {
                $('.weeklyRecapTitle').text(currentWeek);
                var salesChartCanvas = $('#salesChart').get(0).getContext('2d');

                var salesChartData = {
                    labels: weeksData.map(list => list.day),
                    datasets: [{
                            label: 'All checkups',
                            backgroundColor: 'rgba(60,141,188,0.9)',
                            borderColor: 'rgba(60,141,188,0.8)',
                            pointRadius: false,
                            pointColor: '#3b8bba',
                            pointStrokeColor: 'rgba(60,141,188,1)',
                            pointHighlightFill: '#fff',
                            pointHighlightStroke: 'rgba(60,141,188,1)',
                            data: weeksData.map(list => list.all + 50),
                        },
                        {
                            label: 'Reserved',
                            backgroundColor: 'rgba(210, 214, 222, 1)',
                            borderColor: 'rgba(210, 214, 222, 1)',
                            pointRadius: false,
                            pointColor: 'rgba(210, 214, 222, 1)',
                            pointStrokeColor: '#c1c7d1',
                            pointHighlightFill: '#fff',
                            pointHighlightStroke: 'rgba(220,220,220,1)',
                            data: weeksData.map(list => list.reserve + 45),
                        }
                    ]
                };

                var salesChartOptions = {
                    maintainAspectRatio: false,
                    responsive: true,
                    legend: {
                        display: false
                    },
                    scales: {
                        xAxes: [{
                            gridLines: {
                                display: false
                            }
                        }],
                        yAxes: [{
                            gridLines: {
                                display: false
                            }
                        }]
                    }
                };

                new Chart(salesChartCanvas, {
                    type: 'line',
                    data: salesChartData,
                    options: salesChartOptions
                });

                const all = data[0] + 40;
                const reserved = data[1] + 10;
                const cancelled = data[2] + 10;
                const done = data[3] + 10;

                $('.reserverQC').find('b').text(reserved);
                $('.reserverQC').find('span').text(`/${all}`);
                const reserveBar = (reserved / all) * 100;
                $('.reservePGBar').css('width', `${reserveBar}%`);

                $('.cancelled').find('b').text(cancelled);
                $('.cancelled').find('span').text(`/${all}`);
                const cancelBar = (cancelled / all) * 100;
                $('.cancelBar').css('width', `${cancelBar}%`);


                $('.done').find('b').text(done);
                $('.done').find('span').text(`/${all}`);
                const doneBar = (done / all) * 100;
                $('.doneBar').css('width', `${doneBar}%`);
            }

        function getStatusRecord(status){
            $.ajax({
                url: '/clinic/getStatus/dashboard/get?status=' + status + '&user='+ $('#userID').val(),
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if(response?.length){
                        $('.statusCont').removeClass('d-none');
                        $('.noStatus').addClass('d-none');
                        
                        displayStatus(response)
                    }else{
                        $('.statusCont').addClass('d-none');
                        $('.noStatus').removeClass('d-none');
                       
                    }
                },
                error: function() {
                    getStatusRecord(status)
                }
            });

            }

        function displayStatus(recent){
            let statusRecent = '';

            for(let x=0; x<recent.length; x++){
                const list = recent[x];

                const date = new Date(list.date);
                const options = { year: 'numeric', month: 'long', day: '2-digit' };
                const choosedDate  = date.toLocaleDateString('en-US', options);

                statusRecent += `
                <tr>
                    <th>Dr. ${list.doctor_name}</th>
                    <th>${list.patientName}</th>
                    <td>${choosedDate}</td>
                    <td>${list.expectedTime}</td>
                    <td>${list.number}</td>
                </tr>
                `;
            }
            $('.dashboardStatus').html(statusRecent)
        }

        const checkStatus = (status) => {
                    let statusLabel = {};
                    switch(status) {
                        case 1:
                            statusLabel = {bg: 'primary', label: 'Reserved'};
                            break;
                        case 2:
                            statusLabel = {bg: 'danger', label: 'Cancelled'};
                            break;
                        case 3:
                            statusLabel = {bg: 'warning', label: 'Cancelled'};
                            break;
                        case 4:
                            statusLabel = {bg: 'info', label: 'Undecided'};
                            break;
                        case 5:
                            statusLabel = {bg: 'success', label: 'Done'};
                            break;
                        case 6:
                            statusLabel = {bg: 'info', label: 'Doctor now available'};
                            break;
                        case 7:
                            statusLabel = {bg: 'info', label: 'Not attended'};
                            break;
                        case 8:
                            statusLabel = {bg: 'info', label: 'System error'};
                            break;
                    }

                    return statusLabel;
                };

        function formatDate(dateStr) {
            var date = new Date(dateStr);
            var options = { year: 'numeric', month: 'long', day: 'numeric' };
            return date.toLocaleDateString('en-US', options);
        }


        function displayRecent(recent){
            let dashboardRecent = '';
            let smallScreen = '';

            for(let x=0; x<recent.length; x++){
                const list = recent[x];

                const date = new Date(list.date);
                const options = { year: 'numeric', month: 'long', day: '2-digit' };
                const choosedDate  = date.toLocaleDateString('en-US', options);

                dashboardRecent += `
                <tr>
                    <th>Dr. ${list.doctor_name}</th>
                    <th>${list.patientName}</th>
                    <td> ${formatDate(list.date)}</td>
                    <td>${list.expectedTime}</td>
                    <td>
                        <span class="badge badge-${checkStatus(list.status).bg}">${checkStatus(list.status).label}</span>
                    </td>
                </tr>
                `;
                smallScreen += `
                    <li class="item">
                            <div class="">
                                <h6 class="product-title">${list.patientName}
                                    <span class="badge badge-${checkStatus(list.status).bg} float-right">${checkStatus(list.status).label}</span></h6>
                                <span class="product-description">
                                    Dr. ${list.doctor_name}
                                </span>
                                <span class="product-description">
                                    ${formatDate(list.date)} ${list.expectedTime}
                                </span>
                                
                            </div>
                        </li>
               `;
            }
            $('.dashboardRecent').html(dashboardRecent)
            $('.smallScreen').html(smallScreen)
        }
    </script>
@endsection

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
