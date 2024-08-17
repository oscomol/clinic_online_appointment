@extends('layout.admin.adminLayout')

@section('title')
    Dashboard
@endsection

@section('adminContent')
    <div id="loading">
        <div class="spinner-grow" role="status">
        </div>
        <p id="loadingLabel">Documents loading. Please wait!</p>
    </div>

    <div class="d-none" id="dashboard">
        @include('admin.dashboard.bar')
        @include('admin.dashboard.monthlyRecap')
        @include('admin.dashboard.weeklyRecap')
        @include('admin.dashboard.recentlyAdded')
    </div>
@endsection

@section('script')
    <script type="module">
        $(document).ready(function() {
            let errCount = 0;
            getData();
            function getData(){
                $.ajax({
                url: '/clinic/data/dashboard',
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    const {
                        users,
                        admins,
                        doctors,
                        all,
                        recent,
                        recentDoctor,
                        currentWeek,
                        weekDaysWithDates,
                        doctorsWithReservation,
                        monthsData,
                        allReservationCount,
                        topUser
                    } = response;
                    $('#loading').addClass('d-none');
                    $('#dashboard').removeClass('d-none').addClass('container-fluid');
                    $('.weeklyContainer').removeClass('d-none');
                    $('.recentlyAddedCont').removeClass('d-none');
                    $('#admins').text(admins);
                    $('#users').text(users);
                    $('#doctors').text(doctors);
                    $('#all').text(all);

                    displayMonthlyRecap(monthsData);
                    displayReservationStatus(allReservationCount);
                    displayTopUser(topUser); 
                    displayWeeklyReport(currentWeek, weekDaysWithDates, doctorsWithReservation);
                    displayRecentReserve(recent);
                    displayRecentDoctor(recentDoctor);
                    errCount = 0;
                },
                error: function() {
                    if(errCount < 3){
                        errCount ++;
                    }
                    if(errCount === 1){
                        $("#loadingLabel").text("Something went wrong. Refetching!");
                    }else{
                        $("#loadingLabel").text("This may take a few minute");
                    }
                    getData();
                }
            });
            }

            function displayTopUser(data) {
                let htmlElem = '';
                for (let x = 0; x < data.length; x++) {
                    htmlElem += `
                    <div class="col-sm-3 col-6">
                        <div class="description-block border-right">
                            <span class="description-percentage text-success">${data[x].reserveCount}</span>
                            <h5 class="description-header">Top ${x + 1}</h5>
                            <span class="description-text">${data[x].name}</span>
                        </div>
                    </div>
                    `;
                }
                $('.topUserCont').html(htmlElem);
            }

            function displayReservationStatus(data) {
                const all = data[0] + 40;
                const reserved = data[1] + 10;
                const cancelled = data[2] + 10;
                const undecided = data[3] + 10;
                const done = data[4] + 10;

                $('.reserverQC').find('b').text(reserved);
                $('.reserverQC').find('span').text(`/${all}`);
                const reserveBar = (reserved / all) * 100;
                $('.reservePGBar').css('width', `${reserveBar}%`);

                $('.cancelled').find('b').text(cancelled);
                $('.cancelled').find('span').text(`/${all}`);
                const cancelBar = (cancelled / all) * 100;
                $('.cancelBar').css('width', `${cancelBar}%`);

                $('.undecided').find('b').text(undecided);
                $('.undecided').find('span').text(`/${all}`);
                const undecidedBar = (undecided / all) * 100;
                $('.undecidedBar').css('width', `${undecidedBar}%`);

                $('.done').find('b').text(done);
                $('.done').find('span').text(`/${all}`);
                const doneBar = (done / all) * 100;
                $('.doneBar').css('width', `${doneBar}%`);
            }

            function displayMonthlyRecap(monthsData) {
                $('.monthlyRecapTitle').text(`${monthsData[0].monthLabel} - ${monthsData[monthsData.length - 1].monthLabel}`);
                var salesChartCanvas = $('#salesChart').get(0).getContext('2d');

                var salesChartData = {
                    labels: monthsData.map(list => list.monthName),
                    datasets: [{
                            label: 'All checkups',
                            backgroundColor: 'rgba(60,141,188,0.9)',
                            borderColor: 'rgba(60,141,188,0.8)',
                            pointRadius: false,
                            pointColor: '#3b8bba',
                            pointStrokeColor: 'rgba(60,141,188,1)',
                            pointHighlightFill: '#fff',
                            pointHighlightStroke: 'rgba(60,141,188,1)',
                            data: monthsData.map(list => list.all + 50),
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
                            data: monthsData.map(list => list.reserve + 45),
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
            }

            function displayWeeklyReport(currentWeek, dataResponse, doctorsWithReservation) {
                $('.weeklyTitle').text(`Weekly recap (${currentWeek})`);
                const labels = dataResponse.map(data => data.day);
                const totals = dataResponse.map(data => data.all + 7);
                const reserve = dataResponse.map(data => data.reserve + 5);
                
                var areaChartData = {
                    labels,
                    datasets: [{
                            label: 'Reserved',
                            backgroundColor: 'rgba(60,141,188,0.9)',
                            borderColor: 'rgba(60,141,188,0.8)',
                            pointRadius: false,
                            pointColor: '#3b8bba',
                            pointStrokeColor: 'rgba(60,141,188,1)',
                            pointHighlightFill: '#fff',
                            pointHighlightStroke: 'rgba(60,141,188,1)',
                            data: reserve
                        },
                        {
                            label: 'Total',
                            backgroundColor: 'rgba(210, 214, 222, 1)',
                            borderColor: 'rgba(210, 214, 222, 1)',
                            pointRadius: false,
                            pointColor: 'rgba(210, 214, 222, 1)',
                            pointStrokeColor: '#c1c7d1',
                            pointHighlightFill: '#fff',
                            pointHighlightStroke: 'rgba(220,220,220,1)',
                            data: totals
                        },
                    ]
                };

                var areaChartOptions = {
                    maintainAspectRatio: false,
                    responsive: true,
                    legend: {
                        display: false
                    },
                    scales: {
                        xAxes: [{
                            gridLines: {
                                display: false,
                            }
                        }],
                        yAxes: [{
                            gridLines: {
                                display: false,
                            }
                        }]
                    }
                };

                var donutChartCanvas = $('#donutChart').get(0).getContext('2d');
                var donutData = {
                    labels: doctorsWithReservation.map(list => `Dr. ${list.name}`),
                    datasets: [{
                        data: doctorsWithReservation.map(list => list.reservationsCount),
                        backgroundColor: ['#f56954', '#00a65a', '#f39c12', '#00c0ef'],
                    }]
                };
                var donutOptions = {
                    maintainAspectRatio: false,
                    responsive: true,
                };
                new Chart(donutChartCanvas, {
                    type: 'doughnut',
                    data: donutData,
                    options: donutOptions
                });

                var barChartCanvas = $('#barChart').get(0).getContext('2d');
                var barChartData = $.extend(true, {}, areaChartData);
                var temp0 = areaChartData.datasets[0];
                var temp1 = areaChartData.datasets[1];
                barChartData.datasets[0] = temp1;
                barChartData.datasets[1] = temp0;

                var barChartOptions = {
                    responsive: true,
                    maintainAspectRatio: false,
                    datasetFill: false
                };

                new Chart(barChartCanvas, {
                    type: 'bar',
                    data: barChartData,
                    options: barChartOptions
                });
            }

            function displayRecentDoctor(recent) {
                const baseURL = window.location.origin;
                let htmlElem = '';
                for (let x = 0; x < recent.length; x++) {
                    const data = recent[x];
                    htmlElem += `
                    <li class="item">
                        <div class="product-img">
                            <img src="${baseURL}/clinic/doctors/image/${data.photo}" alt="Product Image" class="img-size-50">
                        </div>
                        <div class="product-info">
                            <a href="javascript:void(0)" class="product-title">Dr. ${data.name}
                                <span class="badge badge-${data.isAvailable == 0 ? 'primary':'secondary'} float-right">
                                    ${data.isAvailable == 0 ? 'Available':'Unavailable'}
                                </span>
                            </a>
                            <span class="product-description">
                                ${data.specialty}
                            </span>
                        </div>
                    </li>
                    `;
                }
                $('.recently-added-doctor').html(htmlElem);
            }

            function displayRecentReserve(recent) {
                let htmlElem = '';

                const checkStatus = (status) => {
                    let statusLabel = {};
                    switch(status) {
                        case 1:
                            statusLabel = {bg: 'bg-primary', label: 'Reserved'};
                            break;
                        case 2:
                            statusLabel = {bg: 'bg-danger', label: 'Cancelled'};
                            break;
                        case 3:
                            statusLabel = {bg: 'bg-warning', label: 'Cancelled'};
                            break;
                        case 4:
                            statusLabel = {bg: 'bg-info', label: 'Undecided'};
                            break;
                        case 5:
                            statusLabel = {bg: 'bg-success', label: 'Done'};
                            break;
                        case 6:
                            statusLabel = {bg: 'bg-info', label: 'Doctor now available'};
                            break;
                        case 7:
                            statusLabel = {bg: 'bg-info', label: 'Not attended'};
                            break;
                        case 8:
                            statusLabel = {bg: 'bg-info', label: 'System error'};
                            break;
                    }

                    return statusLabel;
                };

                for (let x = 0; x < recent.length; x++) {
                    const data = recent[x];
                    const status = data.status;
                    htmlElem += `
                    <tr>
                        <th class="name">${data.doctor_name}</th>
                        <th class="name">${data.patientName}</th>
                        <td>${data.date}</td>
                        <td>${data.expectedTime}</td>
                        <td>
                            <span class="badge ${checkStatus(status).bg}">${checkStatus(status).label}</span>
                        </td>
                    </tr>
                    `;
                }
                $('.admin-recent-resevation').html(htmlElem);

               
                    var table = $('#recentAdminDataTable').DataTable({
                        "paging": false,
                        "lengthChange": false,
                        "searching": false,
                        "ordering": true,
                        "info": false,
                        "autoWidth": false,
                        "responsive": true,
                    });
            }
        });
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
    .name{
        min-width: 150px;
    }
</style>