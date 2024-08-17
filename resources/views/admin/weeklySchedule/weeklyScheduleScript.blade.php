@section('script')
    <script type="module">
      $(function(){

        $.ajaxSetup({
                headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        function handleResize() {
            const widthL = $(window).width();
            console.log(widthL)
                if(widthL < 1200){
                    $('.break').removeClass('d-none');
                    $('.spanCont').removeClass('float-right');
                    if(widthL < 600){
                        $('.spanCont').css('flex-direction', 'column');
                        $('.removable').css('width', '100%')
                        $('.selectDay').removeClass('mx-2').addClass('my-2')
                    }else{
                        $('.spanCont').css('flex-direction', 'row');
                        $('.removable').css('width', '33.3%')
                        $('.selectDay').removeClass('my-2').addClass('mx-2')
                    }
                }else{
                    $('.break').addClass('d-none');
                    $('.spanCont').addClass('float-right');
                    $('.selectDay').removeClass('my-2').addClass('mx-2')
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
            handleResize();

        let doctorsId = $('.selectDoctor').val();
        let date = $('.selectDay').val();
        let data = [];
        let doctorsPrice = "NA";
        let rowId = null;
        let status = $('.customSelect').val();

        getReservedData(doctorsId, date, status);

        setInterval(() => {
            
            if(date && doctorsId){
                $.ajax({
                url: '/clinic/week/admin/reserve/get?date=' + encodeURIComponent(date) + '&doctor=' + encodeURIComponent(doctorsId),
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    const {hasPassed, reservations,price} = response;
                   if(reservations?.length && reservations.length > data.length){
                    doctorsPrice = price;
                    data = reservations;
                    const reservedData = status == 0 ? data:data.filter(list => list.status == status);
                    
                    displayReserveData(reservedData, status, hasPassed);
                    handleResize();
                   }
                   
                }
            });
            }
        }, 3000);

        $('.selectDoctor').change(function(){
             doctorsId =$(this).val();
             getReservedData(doctorsId, date, status)
        })

        $('.selectDay').change(function(){
            date = $(this).val();
            getReservedData(doctorsId, date, status)
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
                    getReservedData(doctorsId, date, status)

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

            $('.customSelect').change(function(){
               status = $(this).val();
               let response = data;
               if(status > 0){
                if(status != 2){
                    response = data.filter(list => list.status == status)
                }else{
                    response = data.filter(list => list.status == 2 || list.status == 3 || list.status > 5)
                }
               }
               displayReserveData(response, status)
               handleResize();
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
                    data: {doctorsId, date: date, status: 3},
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
                        getReservedData(doctorsId, date, status)
                    },
                    error: function(response) {
                        $('.cancelBtnSubmit').text('Cancel')
                    }
                });
            })

            $(document).on('click', '.doctorPatientViewBtn', function(){
                rowId = $(this).attr('rowId');
                const dateDiffer = $(this).attr('dateDiffer');
                const patient = data.find(list => list.id == rowId);
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

        function getReservedData(doctorsId, date, status){
            if(date && doctorsId){
                $("#reservationAdminTable").DataTable().destroy();
                $('#loading').removeClass('d-none');
                $('.err').addClass('d-none');
                $('.table-cont').addClass('d-none');
                $.ajax({
                url: '/clinic/week/admin/reserve/get?date=' + encodeURIComponent(date) + '&doctor=' + encodeURIComponent(doctorsId),
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    
                    const {hasPassed, reservations,price} = response;
                    doctorsPrice = price;
                    data = reservations;
                    const reservedData = status == 0 ? data:data.filter(list => list.status == status);
                    $('#loading').addClass('d-none');
                    $('.table-cont').removeClass('d-none');
                    $('.err').addClass('d-none');
                    displayReserveData(reservedData, status, hasPassed);
                    handleResize();

                },
                error: function() {
                    $('#loading').addClass('d-none');
                    $('.table-cont').addClass('d-none');
                    $('.err').removeClass('d-none');
                    getReservedData(doctorsId, date, status)
                }
            });
            }else{
                    doctorsPrice = "NA";
                    data = [];
                    displayReserveData(data, status);
                    $('#loading').addClass('d-none');
                    $('.table-container').removeClass('d-none');
            }
      }
      });

      let dateDiffer = null;

      function displayReserveData(data, status, dateHasPassed){
        $("#reservationAdminTable").DataTable().destroy();


        if(dateHasPassed !== undefined){
                dateDiffer = dateHasPassed;
            }

            $('.cancelAll').removeClass('d-none');

            if(dateDiffer < 0){
                $('.cancelAll').prop('disabled', true).text('Passed').removeClass('btn-danger').addClass('btn-success');
            }else{
                if(status == 1 && data.length){
                $('.cancelAll').prop('disabled', false).text('Cancel').removeClass('btn-success').addClass('btn-danger');
                }else{
                    $('.cancelAll').addClass('d-none');
                }
            }

        let reservationAdminData = '';
        for(let x=0; x<data.length; x++){
            const res = data[x];
            reservationAdminData += `
            <tr id="doctorPatientRow-${res.id}" class="doctorPatientRow">
                 <td class="toShow" style="width: 150px;">${res.number}

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
        $('#reservationAdminData').html(reservationAdminData);
        displayDataTable();
      }

      function displayDataTable(){
            $("#reservationAdminTable").DataTable({
                "responsive": true, "lengthChange": false, "autoWidth": false,
                "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
                }).buttons().container().appendTo('#reservationAdminTable_wrapper .col-md-6:eq(0)');
                

        }
    </script>
@endsection