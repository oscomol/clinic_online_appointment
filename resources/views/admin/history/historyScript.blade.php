@section('script')
    <script type="module">
        $(function(){

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            let doctorsId = $('.selectDoctor').val();
            let doctorsPrice = "NA";
            let today = $('.dateSelect').val();
            let patientData = [];
            let rowId = null;
            let status = $('.customSelect').val();

            function handleResize() {
                const widthL = $(window).width();
                if(widthL < 1090){
                    $('.break').removeClass('d-none');
                    $('.customSpan').removeClass('float-right');
                    if(widthL <= 550){
                        $('.customSpan').css('flex-direction', 'column');
                        $('.removable').removeClass('w-50 w-25').addClass('w-100');
                        $('.dateSelect').removeClass('mx-2').addClass('my-2');
                    }else{
                        $('.customSpan').css('flex-direction', 'row');
                        $('.removable').removeClass('w-100').addClass('w-50 w-25');
                        $('.dateSelect').removeClass('my-2').addClass('mx-2');
                    }
                }else{
                    $('.break').addClass('d-none');
                    $('.customSpan').addClass('float-right');
                   
                }

                if(widthL < 800){
                    $('.toRemove').addClass('d-none');
                    $('.toShow').removeClass('d-none');
                }else{
                    $('.toRemove').removeClass('d-none');
                    $('.toShow').addClass('d-none');
                }
            }

            $(window).resize(handleResize);
            handleResize()

            setInterval(() => {
                if(today && doctorsId){
               
                    $.ajax({
                        url: '/clinic/get/doctor/reservation/get?date=' + encodeURIComponent(today) + '&doctor=' + encodeURIComponent(doctorsId),
                        type: 'GET',
                        dataType: 'json',
                        success: function(response) {
                            const {hasPassed, reservations,price} = response;
                           if(reservations?.length && reservations?.length > patientData.length){
                            $('#doctorPatientView').DataTable().destroy();
                            patientData = reservations;
                            doctorsPrice = price;
                            const reservedData = status == 0 ? patientData:patientData.filter(list => list.status == status);

                            displayPatient(reservedData, status, hasPassed);
                           }
                        }
                    });
               }
            }, 3000);

            getReservationData(doctorsId, today, status)

            $('.selectDoctor').change(function(){
                doctorsId = $(this).val();
                console.log(status)
                getReservationData(doctorsId, today, status)
            })

            $('.dateSelect').focus(function(){
                $(this).attr('type', 'date');
            }).change(function(){
                today = $(this).val();
                getReservationData(doctorsId, today, status)
            });
            
            $('.dateSelect').blur(function(){
                $(this).attr('type', 'text');
            })
            $('.cancelAll').click(function(){
                $('#cancelReservationModal').modal('show')
            })

            $('.cancelReservation').submit(function(event) {
                event.preventDefault();
                $('.cancelBtnSubmit').prop('disabled', true).text('Cancelling...')
                $.ajax({
                    url: $(this).attr('action'),
                    type: 'POST',
                    data: {doctorsId, date: today, status: 3},
                    dataType: 'json',
                    success: function(data) {
                        $('.cancelBtnSubmit').text('Cancel')
                        $('.confirmCancel').addClass('d-none');
                        $('.confirmSuccess').removeClass('d-none');

                        setTimeout(() => {
                            $('.cancelBtnSubmit').prop('disabled', false);
                            $('.confirmCancel').removeClass('d-none');
                            $('.confirmSuccess').addClass('d-none');
                            $('#cancelReservationModal').modal('hide')
                        }, 3000);
                        getReservationData(doctorsId, today, status)
                    },
                    error: function(response) {
                        $('.cancelBtnSubmit').text('Cancel')
                    }
                });
            })

            $(document).on('change', '.customSelect', function(){
                $('#doctorPatientView').DataTable().destroy();
                status = $(this).val();
               let response = patientData;
               if(status > 0){
                if(status != 2){
                    response = patientData.filter(list => list.status == status)
                }else{
                    response = patientData.filter(list => list.status == 2 || list.status == 3 || list.status > 5)
                }
               }
               displayPatient(response, status)
               handleResize()
            })
            
            $(document).on('mouseover', '.doctorPatientRow', function(){
                $(this).find('button').removeClass('d-none');
            })
            
            $(document).on('mouseout', '.doctorPatientRow', function(){
                $(this).find('button').addClass('d-none');
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
                    getReservationData(doctorsId, today, status)

                    $('.markReservation').text('Updated')
                    
                    setTimeout(() => {
                        $('.markReservation').prop('disabled', false).text('Mark as done')
                        $('#doctor-view-client-info').modal('hide');
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

            $(document).on('click', '.doctorPatientViewBtn', function(){
                rowId = $(this).attr('rowId');
                const dateDiffer = $(this).attr('dateDiffer');
                const patient = patientData.find(list => list.id == rowId);
                if(patient){
                    const stat = patient.status;
                   $('.tableClientData').removeClass('d-none');
                    $('#status-view-reservation').removeClass();
                    $('#status-view-reservation').addClass(`badge bg-${stat == 1 ? 'primary':stat == 2 ? 'danger':stat == 3 ? 'warning':stat == 4 ? 'secondary':stat == 5 ? 'success':'info'}`).text(`${stat == 1 ? 'Reserved':stat == 2 ? 'Cancelled':stat == 3 ? 'Cancelled':stat == 4 ? 'Undecided':stat == 5 ? 'Done':stat == 6 ? 'Doctor not available':stat == 7 ? 'Client not attended':'System error'}`);

                    $('#doctor-view-client-info').modal('show');
                    $('#time').text(patient.expectedTime);
                    $('#payment').text(`Php ${doctorsPrice}`);
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
            })

            function getReservationData(doctorsId, date, status, err){
               if(date && doctorsId){
                $('#doctorPatientView').DataTable().destroy();
                if(!err){
                    $('#loading').removeClass('d-none');
                    $('#doctorPatientView').addClass('d-none');
                    $('.err').addClass('d-none');
                }
                    $.ajax({
                        url: '/clinic/get/doctor/reservation/get?date=' + encodeURIComponent(date) + '&doctor=' + encodeURIComponent(doctorsId),
                        type: 'GET',
                        dataType: 'json',
                        success: function(response) {

                            $('#loading').addClass('d-none');
                            $('.err').addClass('d-none');
                            $('#doctorPatientView').removeClass('d-none');

                            const {hasPassed, reservations,price} = response;
                            patientData = reservations;
                            doctorsPrice = price;
                            const reservedData = status == 0 ? patientData:patientData.filter(list => list.status == status);
                            displayPatient(reservedData, status, hasPassed);
                            handleResize()
                        },
                        error: function(err) {
                            $('#loading').addClass('d-none');
                            $('#doctorPatientView').addClass('d-none');
                            $('.err').removeClass('d-none');
                            getReservationData(doctorsId, date, status, true)
                        }
                    });
               }else{
                $('#loading').addClass('d-none');
                $('#doctorPatientView').removeClass('d-none');
                patientData = [];
                doctorsPrice = "NA";
                displayPatient(patientData, status);
               }
            }
        })

        let dateDiffer = null;

        function displayPatient(response, status, dateHasPassed){
        
           
            if(dateHasPassed !== undefined){
                dateDiffer = dateHasPassed;
            }

            $('.cancelAll').removeClass('d-none');

            if(dateDiffer < 0){
                $('.cancelAll').prop('disabled', true).text('Passed').removeClass('btn-danger').addClass('btn-success');
            }else{
                if(status == 1 && response.length){
                $('.cancelAll').prop('disabled', false).text('Cancel').removeClass('btn-success').addClass('btn-danger');
                }else{
                    $('.cancelAll').addClass('d-none');
                }
            }

            let doctorPatientView = '';
            for(let x=0; x<response.length; x++){
                const res = response[x];
                doctorPatientView += `
                <tr id="doctorPatientRow-${res.id}" class="doctorPatientRow">
                    <td class="toShow"  style="min-width: 140px;">${res.number}

                        ${(dateDiffer <= 0 && res.status < 5 && res.status == 1) ? `<span class="badge badge-warning float-right mt-1 ml-2">Unmarked</span>`:''}

                        <button id="doctorPatientView-${res.id}-sm" class="float-right btn btn-success btn btn-sm doctorPatientViewBtn" rowId="${res.id}" dateDiffer="${dateDiffer}">
                            View
                        </button>
                    </td>
                    <th>${res.patientName}</th>
                    <td>${res.age}</td>
                    <td>${res.gender}</td>
                    <td>${res.expectedTime}</td>
                    
                    <td class="toRemove">${res.number}

                        ${(dateDiffer <= 0 && res.status < 5 && res.status == 1) ? `<span class="badge badge-warning float-right mt-1 ml-2">Unmarked</span>`:''}

                        <button id="doctorPatientView-${res.id}" class="d-none float-right btn btn-success btn btn-sm doctorPatientViewBtn" rowId="${res.id}" dateDiffer="${dateDiffer}">
                            <li class="fas fa-eye"></li>
                        </button>
                    </td>
                </tr>
                `;
            }
            $('.doctorPatientView').html(doctorPatientView);
            displayDataTable();
            
        }

        function displayDataTable(){
            $("#doctorPatientView").DataTable({
                "responsive": true, "lengthChange": false, "autoWidth": false,
                "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
                }).buttons().container().appendTo('#doctorPatientView_wrapper .col-md-6:eq(0)');

    $('.dataTables_filter');
        }
    </script>
@endsection