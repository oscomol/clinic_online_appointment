@section('script')
    <script type="module">
        $(function(){
            $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        let data = [];
        let rowId = null;
        let payment = "NA";
        let today = $('.day-picker-sched').val();
        let selectedCategory = $('.customSelect').val();

        getDoctorsReserve(today, selectedCategory);

        function handleResize() {
            const widthL = $(window).width();
                if (widthL < 630) {
                   
                    $('.break').removeClass('d-none');
                    $('.spanCont').removeClass('float-right')
                   
                } else {
                    $('.break').addClass('d-none');
                    $('.spanCont').addClass('float-right')
                    
                }
            }

            $(window).resize(handleResize);
            handleResize()


        $("#doctor-sched").DataTable({
            "responsive": true, "lengthChange": false, "autoWidth": false, "sorting": false, "searching": false, "info": false, "paging": false
        })

            $('.day').on('mouseover', function(){
                const day = $(this).attr('id');
                $(this).find('.span').removeClass('d-none');
                $(this).find(`#status-${day}`).removeClass('d-none');
            })
            $('.day').on('mouseout', function(){
                const day = $(this).attr('id');
                $(this).find('.span').addClass('d-none');
                $(this).find(`#status-${day}`).addClass('d-none');
            })

            $('.status-switch').click(function(){
                const day = $(this).attr('day');
                const scheduledId = $(this).attr('scheduledId');
                const statusText = $('#status-text-'+day);
                const isAvailable = statusText.text() === "Available";
                let status = null;
                if(isAvailable){
                    $(`#stat-icon-${day}`).removeClass().addClass('fas fa-toggle-off');
                    statusText.removeClass('bg-primary').addClass('bg-secondary').text('Unavailable')
                    status = 0;
                }else{
                    $(`#stat-icon-${day}`).removeClass().addClass('fas fa-toggle-on');
                    statusText.removeClass('bg-secondary').addClass('bg-primary').text('Available')
                    status = 1;
                }

                $.ajax({
                        url: "/clinic/schedule/updateStatus",
                        type: 'PUT',
                        data: {
                            status,
                            id: scheduledId
                        },
                        dataType: 'json',
                        success: function(response) {
                        status = null;
                        $(`#succes-alert-${day}`).removeClass('d-none');
                        $(`#switch-${day}`).addClass('d-none');

                        setTimeout(() => {
                            $(`#succes-alert-${day}`).addClass('d-none');
                            $(`#switch-${day}`).removeClass('d-none');
                        }, 5000);

                        },
                        error: function() {
                             $(`#error-alert-${day}`).removeClass('d-none');
                            $(`#switch-${day}`).addClass('d-none');

                            setTimeout(() => {
                                $(`#error-alert-${day}`).addClass('d-none');
                                $(`#switch-${day}`).removeClass('d-none');
                            }, 5000);
                            if(isAvailable){
                                statusText.removeClass('bg-secondary').addClass('bg-primary').text('Available');
                            }else{
                                statusText.removeClass('bg-primary').addClass('bg-secondary').text('Unavailable');
                            }
                        }
                    });
            })

            $('.addDoctorSched').submit(function(event){
                event.preventDefault();
                const url = '/clinic/doctorsSched/create';
                const form = $(this);
                const day = form.attr('day');
                const submitBtn = $(`#drSched-${day}`);
                submitBtn.prop('disabled', true).text('Saving...');
                $.ajax({
                url: url,
                method: 'POST',
                data: new FormData(this),
                dataType: 'JSON',
                contentType: false,
                cache: false,
                processData: false,
                success: function(response) {
                const error = response?.error;
                    if(error){
                        displayAddError(error, day);
                        submitBtn.prop('disabled', false).text('Save');
                        return;
                    }
                    $(`#add-admin-${day}`).modal('hide');
                    submitBtn.prop('disabled', false).text('Save');
                    window.location.reload();
                },
                error: function() {
                    alert('Something went wrong!');
                    submitBtn.prop('disabled', false).text('Save');
                    $(`#add-admin-${day}`).modal('hide');
                }
            })
            })

            $('.editDoctorSched').submit(function(event){
                event.preventDefault();
                const url = '/clinic/doctorsSched/edit';
                const day = $(this).attr('day');
                const submitBtn = $(`#editDrSched-${day}`);
                submitBtn.prop('disabled', true).text('Updating...');
                $.ajax({
                url: url,
                method: 'POST',
                data: new FormData(this),
                dataType: 'JSON',
                contentType: false,
                cache: false,
                processData: false,
                success: function(response) {
                const error = response?.error;
                submitBtn.prop('disabled', false).text('Update');
                $(`#add-admin-${day}`).modal('hide');
                updateRow(response, day);
                },
                error: function() {
                    $(`#add-admin-${day}`).modal('hide');
                    submitBtn.prop('disabled', false).text('Update');
                    alert('Something went wrong!');
                }
            })
            });

            $('.cancelAll').click(function(){
                $('#cancelReservationModal').modal('show')
            })

            $('.cancelReservation').submit(function(event) {
                event.preventDefault();
                $('.cancelBtnSubmit').prop('disabled', true).text('Cancelling...')
                const url = new URL(window.location.href);
                const doctorsId = url.searchParams.get('doctor');
                $.ajax({
                    url: $(this).attr('action'),
                    type: 'POST',
                    data: {doctorsId, date: today, status: 3},
                    dataType: 'json',
                    success: function(data) {
                        console.log(data)
                        $('.cancelBtnSubmit').text('Cancel')
                        $('.confirmCancel').addClass('d-none');
                        $('.confirmSuccess').removeClass('d-none');

                        setTimeout(() => {
                            $('.cancelBtnSubmit').prop('disabled', false);
                            $('.confirmCancel').removeClass('d-none');
                            $('.confirmSuccess').addClass('d-none');
                            $('#cancelReservationModal').modal('hide')
                        }, 3000);
                        getDoctorsReserve(today, selectedCategory )
                    },
                    error: function(response) {
                        $('.cancelBtnSubmit').text('Cancel')
                    }
                });
            })


            $('.markReservation').click(function(){
                const status = $('.selectLabel').val();
                const data = {status, id: rowId};
                $(this).prop('disabled', true).text('Updating...')
                $.ajax({
                url: '/clinic/admin/status/update',
                type: 'POST',
                data: data,
                dataType: 'json',
                success: function(response) {

                    $('.markReservation').text('Updated')
                    
                    setTimeout(() => {
                        $('.markReservation').prop('disabled', false).text('Mark as done')
                        $('#doctor-view-client-info').modal('hide');
                        getDoctorsReserve(today, selectedCategory )
                    }, 2000);
                },
                error: function(err) {
                    $('.markReservation').text('Error');
                    setTimeout(() => {
                        $('.markReservation').prop('disabled', false).text('Mark as done')
                        $('#doctor-view-client-info').modal('hide');
                    }, 2000);
                }
            });
            })

            

            $('.day-picker-sched').focus(function(){
                $(this).attr('type', 'date');
            }).change(function(){
                today = $('.day-picker-sched').val();
                getDoctorsReserve(today, selectedCategory)
            });

            $('.customSelect').change(function(){
                selectedCategory = $(this).val();
                $('#doctors-reservation-list').DataTable().destroy();
                let response = data;
                if(selectedCategory > 0){
                    if(selectedCategory != 2){
                    response = data.filter(list => list.status == selectedCategory)
                    }else{
                        response = data.filter(list => list.status == 2 || list.status == 3 || list.status > 5)
                    }
                }

                displayDoctorsReserve(response, selectedCategory)
            })

            $(document).on('blur', '.day-picker-sched', function() {
                $(this).attr('type', 'text');
            });
            $(document).on('mouseover', '.doctors-view-row', function() {
                $(this).find('button').removeClass('d-none');
            });
            $(document).on('mouseout', '.doctors-view-row', function() {
                $(this).find('button').addClass('d-none');
            });
            $(document).on('click', '.doctors-view-reserve', function() {
              rowId = $(this).attr('patientId');
              const dateDiffer = $(this).attr('dateDiffer');
                const patient = data.find(list => list.id == rowId);
                if(patient){
                    const stat = patient.status;
                   $('.tableClientData').removeClass('d-none');
                    $('#status-view-reservation').removeClass();
                    $('#status-view-reservation').addClass(`badge bg-${stat == 1 ? 'primary':stat == 2 ? 'danger':stat == 3 ? 'warning':stat == 4 ? 'secondary':stat == 5 ? 'success':'info'}`).text(`${stat == 1 ? 'Reserved':stat == 2 ? 'Cancelled':stat == 3 ? 'Cancelled':stat == 4 ? 'Undecided':stat == 5 ? 'Done':stat == 6 ? 'Doctor not available':stat == 7 ? 'Client not attended':'System error'}`);

                    $('#doctor-view-client-info').modal('show');
                    $('#time').text(patient.expectedTime);
                    $('#payment').text(payment);
                    $('#viewPatientname').text(patient.patientName);
                    $('#viewPatientage').text(patient.age);
                    $('#viewPatientaddress').text(patient.address);
                    $('#viewPatientconcern').text(patient.concern);
                    $('#viewPatientseverity').text(patient.severity);
                    $('#viewPatientgender').text(patient.gender);
                    $('#date').text(patient.date);
                    $('.patientNumber').text(patient.number);

                    if(dateDiffer <= 0 && stat == 1){
                        $('.closeBtnClientInfo').addClass('d-none');
                        $('.selectLabel').removeClass('d-none');
                        $('.markReservation').removeClass('d-none');
                    }else{
                        $('.closeBtnClientInfo').removeClass('d-none');
                        $('.selectLabel').addClass('d-none');
                        $('.markReservation').addClass('d-none');
                    }
                }
            });

            function getDoctorsReserve(date, selectedCategory, err ){

            const url = new URL(window.location.href);
            const doctorsId = url.searchParams.get('doctor');

            $('#doctors-reservation-list').DataTable().destroy();

            if(!err){
                    $('#loading').removeClass('d-none');
                    $('#doctors-reservation-list').addClass('d-none');
                    $('.err').addClass('d-none');
                }
            
                $.ajax({
                url: '/clinic/get/doctor/reservation/get?date=' + encodeURIComponent(date) + '&doctor=' + encodeURIComponent(doctorsId),

                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    $('#loading').addClass('d-none');
                    $('#doctors-reservation-list').removeClass('d-none');
                    $('.err').addClass('d-none');
                    const {reservations, hasPassed, price} = response;
                    data = reservations;
                    payment = price;
                    let toPassedData = data;

                    if(selectedCategory > 0){
                        if(selectedCategory != 2){
                            toPassedData = data.filter(list => list.status == selectedCategory)
                        }else{
                            toPassedData = data.filter(list => list.status == 2 || list.status == 3 || list.status > 5)
                        }
                    }

                    displayDoctorsReserve(toPassedData, selectedCategory, hasPassed)
                },
                error: function(err) {
                    $('#loading').addClass('d-none');
                    $('#doctors-reservation-list').addClass('d-none');
                    $('.err').removeClass('d-none');
                    getDoctorsReserve(date, selectedCategory, true)
                }
            });
            
            }
        })

        let dateDiffer = null;

        function displayDoctorsReserve(response, selectedCategory, dateHasPassed){


            if(dateHasPassed !== undefined){
                dateDiffer = dateHasPassed;
            }

            $('.cancelAll').removeClass('d-none');

            if(dateDiffer < 0){
                $('.cancelAll').prop('disabled', true).text('Passed').removeClass('btn-danger').addClass('btn-success');
            }else{
                if(selectedCategory == 1 && response.length){
                $('.cancelAll').prop('disabled', false).text('Cancel').removeClass('btn-success').addClass('btn-danger');
                }else{
                    $('.cancelAll').addClass('d-none');
                }
            }

            let reservationRow = '';

            for(let x=0; x<response.length; x++){
                const data = response[x];
                reservationRow += `
                    <tr class="doctors-view-row" id="doctors-view-row-${data.id}">
                        <th>${data.patientName}</th>
                        <td>${data.age}</td>
                        <td>${data.gender}</td>
                        <td>${data.expectedTime}</td>
                        <td>${data.number}

                            ${(dateDiffer <= 0 && data.status < 5 && data.status == 1) ? `<span class="badge badge-warning float-right mt-1 ml-2">Unmarked</span>`:''}


                            <button id="doctors-view-reserve-${data.id}" class="btn btn-success btn btn-sm float-right d-none doctors-view-reserve" patientId=${data.id} dateDiffer="${dateDiffer}">
                                <li class="fas fa-eye"></li>
                            </button>
                        </td>
                    </tr>
                `;
            }
            
            $('.doctors-reservation-list-row').html(reservationRow);
            displayDataTable()
        }

        function displayDataTable(){
            $("#doctors-reservation-list").DataTable({
                "responsive": true, "lengthChange": false, "autoWidth": false,
                "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
                }).buttons().container().appendTo('#doctors-reservation-list_wrapper .col-md-6:eq(0)');
        }

        function updateRow(data, day) {
            const row = $(`#${day}`);
            row.find('td').eq(0).text(data.maxPatient);
            row.find('td').eq(1).text(data.allotedTime);
            $(`#succes-alert-${day}`).removeClass('d-none');
            $(`#switch-${day}`).addClass('d-none');

            setTimeout(() => {
                $(`#succes-alert-${day}`).addClass('d-none');
                $(`#switch-${day}`).removeClass('d-none');
            }, 5000);
        }

        function displayAddError(error, day){
            const {allotedTime, maxPatient} = error;
            $('input[type="number"]').removeClass('is-invalid');

            if(allotedTime?.length){
                const input = $(`#time-${day}`);
                input.addClass('is-invalid');
            }
            if(maxPatient?.length){
                const input = $(`#max-${day}`);
                input.addClass('is-invalid');
            }
        }
    </script>
@endsection

<style>
    .checkAlert{
        float: right;
    }
    #day-picker-sched{
        height: 30px;
        width: 180px;
    }
</style>