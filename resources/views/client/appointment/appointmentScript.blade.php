@section('script')
<script type="module">
    $(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        function handleResize() {
                if ($(window).width() <= 780) {
                    $('.removable').removeClass('d-none');
                    $('.customDis').removeClass();
                    $('.removableSpan').addClass('d-none');
                    $('.customSelect').css('width:', '100%');
                } else {
                    $('.removable').addClass('d-none');
                    $('.customDis').addClass('d-none');
                    $('.removableSpan').removeClass('d-none');
                    $('.customSelect').css('width:', '46%');
                }
            }

        $(window).resize(handleResize);
        handleResize()

        let doctorsId = null;
        let choosenDate = null;
        let day = null;
        let choosedDate = null;
        let choosedDrName = null;
        let expectedTime = null;
        let number = null;

        displayDataTable();

        const selectedDoctor = $('#selectedDoctor').val();

        if(!isNaN(selectedDoctor)){
            choosedDrName = $(`#doctor-row-${selectedDoctor}`).find('th:first').text();
            doctorsId = selectedDoctor;
            if (choosenDate && day) {
                checkAvailability(doctorsId, choosenDate, day);
            }
        }

        $('.choosenDoctor').change(function() {
            const id = $(this).val();
            $('.doctor-row').removeClass('bg-warning');
            $(`#doctor-row-${id}`).addClass('bg-warning');
            choosedDrName = $(`#doctor-row-${id}`).find('th:first').text();
            doctorsId = id;
            if (choosenDate && day) {
                checkAvailability(doctorsId, choosenDate, day);
            }
        });

        $('#date-picker-sched').focus(function() {
            $(this).attr('type', 'date');
        }).change(function() {
            const curVal = $(this).val();
            const curDate = new Date().toLocaleString('en-US', { timeZone: 'Asia/Manila' });

            if (new Date(curVal) > new Date(curDate)) {
                choosenDate = curVal;

                const options = { weekday: 'long' };
                const selectedDayName = new Date(curVal).toLocaleDateString('en-US', options);
                day = selectedDayName;

                if (doctorsId) {
                    checkAvailability(doctorsId, choosenDate, day);
                }
            } else {
                $(this).val("");
            }
        });

        $('#date-picker-sched').blur(function() {
            $(this).attr('type', 'text');
        });

        $('.closeModalSchedPicker').click(function() {
            $('#check-availability').modal('hide');
            $('#unavailable').addClass('d-none');
            $('#success').addClass('d-none');
            $('#available').removeClass();
            $('.closeModalSchedPickerDiv').addClass('d-none');
        });

        $(document).on('mouseover', '.slotRow', function() {
            $(this).find('button').removeClass('d-none');
        });

        $(document).on('mouseout', '.slotRow', function() {
            $(this).find('button').addClass('d-none');
        });

        $(document).on('click', '.btn-reserve', function() {
            number = $(this).attr('number');
            expectedTime = $(this).attr('time');
            $('#patient-form').modal('show');
            $('.drName').text(choosedDrName)
            $('.checkupInfo').text(`${choosedDate}, ${expectedTime}`)
        });

        $(document).on('click', '.btn-cancel', function() {
            const id = $(this).attr('id');
            $('#cancel-reservation').modal('show');
            $('#reservationId').val(id);
        });

        $('.editReservation').click(function(){
            $(this).addClass('d-none');
            $('.viewReservationIcon').removeClass('d-none');
            $('.reservation-success').addClass('d-none');
            $('.reservation-edit').removeClass('d-none');
            $('#updateReservationBtn').removeClass('d-none');
        })

        $('.viewReservationIcon').click(function(){
            $(this).addClass('d-none');
            $('.editReservation').removeClass('d-none');
            $('.reservation-success').removeClass('d-none');
            $('.reservation-edit').addClass('d-none');
            $('#updateReservationBtn').addClass('d-none');
        })

        $(document).on('click', '.btn-view', function() {
            const id = $(this).attr('id');
            $('#view-reservation').modal('show');
            $('.reservation-loading').removeClass('d-none');
            $('.reservation-success').addClass('d-none');
            $('.viewReservationFooter').addClass('d-none');
            $('.reservation-edit').addClass('d-none');
            $('.editReservation').addClass('d-none');
            $('.viewReservationIcon').addClass('d-none');
            $('#updateReservationBtn').addClass('d-none');

            $.ajax({
            url: "/clinic/doctor/view/get?patient="+id,
            type: 'GET',
            data: { id },
            dataType: 'json',
            success: function(response) {
               const {date, number, expectedTime, patientName, age, gender, address, concern, severity, id} = response;
               $('.reservation-loading').addClass('d-none');
               $('.reservation-success').removeClass('d-none');
               $('.viewReservationFooter').removeClass('d-none');
               $('.reservation-edit').addClass('d-none');
               $('.editReservation').removeClass('d-none');
               $('#time').text(expectedTime);
               $('#viewPatientname').text(patientName);
               $('#viewPatientage').text(age);
               $('#viewPatientaddress').text(address);
               $('#viewPatientconcern').text(concern);
               $('#viewPatientseverity').text(severity);
               $('#viewPatientgender').text(gender);
               $('#date').text(date);
               $('#doctor').text(doctorsId);
               $('#number').text(number);

               $('#edit-reservation-name').val(patientName);
               $('#edit-reservation-age').val(age);
               $('#edit-reservation-gender').val(gender);
               $('#edit-reservation-address').val(address);
               $('#edit-reservation-concern').val(concern);
               $('#edit-reservation-severity').val(severity);
               $('#edit-reservation-id').val(id);
            },
            error: function(err) {
               alert("ERROR");
            }
        });

        });

        $(".addAppointment").submit(function(event) {
            event.preventDefault();
            $('#addAppointmentBtn').prop('disabled', true).text('Saving...')
            const form = $(this);
            const formData = new FormData(this);

            const patientName = formData.get('name');
            const age = formData.get('age');
            const gender = formData.get('gender');
            const address = formData.get('address');
            const concern = formData.get('concern');
            const severity = formData.get('severity');

            const data = {doctorsId, date: choosenDate, expectedTime, number, patientName, age, gender, address, concern, severity, day};
            
            $.ajax({
                    url: form.attr('action'),
                    type: 'POST',
                    data: data,
                    dataType: 'json',
                    success: function(response) {
                        $('#slotData').DataTable().destroy();
                        $('#addAppointmentBtn').prop('disabled', false).text('Save')
                        $('#patient-form').modal('hide');
                        displaySlotRes(response);
                        handleResize();
                    },
                    error: function(response) {
                        if(response.status === 403){
                            displayErrInp(response);
                        }else{
                            $('.OtherErrMsg').text(response.responseJSON.error)
                        }
                        $('#addAppointmentBtn').prop('disabled', false).text('Save')
                    }
                });
        })
        $(".editReservationForm").submit(function(event) {
            event.preventDefault();
            const form = $(this);
            const formData = new FormData(this);

            $('#updateReservationBtn').prop('disabled', true).text('Updating...')

            const patientName = formData.get('name');
            const age = formData.get('age');
            const gender = formData.get('gender');
            const address = formData.get('address');
            const concern = formData.get('concern');
            const severity = formData.get('severity');
            const id = formData.get('id');
            const data = {patientName, age, gender, address, concern, severity, id};

            $.ajax({
                    url: form.attr('action'),
                    type: 'PUT',
                    data: data,
                    dataType: 'json',
                    success: function(response) {

                        $('#success-reservation-update').removeClass('d-none');
                        $('.reservation-edit').addClass('d-none');
                        $('#updateReservationBtn').text('Update')
                        $('.editReservation').addClass('d-none');
                        $('.viewReservationIcon').addClass('d-none');

                        setTimeout(() => {
                            $('#updateReservationBtn').prop('disabled', false).text('Update')
                            $('#view-reservation').modal('hide');
                            $('.reservation-loading').removeClass('d-none');
                            $('.reservation-success').addClass('d-none');
                            $('.viewReservationFooter').addClass('d-none');
                            $('.reservation-edit').addClass('d-none');
                            $('#updateReservationBtn').addClass('d-none');  
                            $('#success-reservation-update').addClass('d-none'); 
                        }, 3000);                     
                    },
                    error: function(response) {
                        $('#updateReservationBtn').prop('disabled', false).text('Update')
                        alert('ERROR')
                    }
                });
        })

        $('.cancelReservation').submit(function(event){
            event.preventDefault();
            const id = $('#reservationId').val();
            $('#cancel-reservation-btn').prop('disabled', true).text('Cancelling...')
            const data = { doctorsId, choosenDate, day, id };
            $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: data,
            dataType: 'json',
            success: function(response) {
                $('#slotData').DataTable().destroy();
                $('#cancel-reservation-btn').prop('disabled', false).text('Cancel')
                $('#cancel-reservation').modal('hide');
                displaySlotRes(response);
            },
            error: function(err) {
                alert("ERROR")
                $('#cancel-reservation-btn').prop('disabled', false).text('Cancel')
            }
            })
        })

        function checkAvailability(doctorsId, choosenDate, day) {
        $('.closeModalSchedPickerDiv').addClass('d-none');

        $('#unavailable').addClass('d-none');
        $('#success').addClass('d-none');
        $('#available').removeClass('d-none');

        $('#check-availability').modal('show');
        $('#slotData').DataTable().destroy();
        $('.slotBody').html('');
        $('.schedResult').removeClass('row').addClass('d-none');
        $.ajax({
            url: "/clinic/doctor/availability",
            type: 'POST',
            data: { doctorsId, choosenDate, day },
            dataType: 'json',
            success: function(response) {
                $('#unavailable').addClass('d-none');
                $('#success').removeClass();
                $('#available').addClass('d-none');
                $('.schedResult').removeClass('d-none').addClass('row');

                const date = new Date(choosenDate);
                const options = { year: 'numeric', month: 'long', day: '2-digit' };
                choosedDate = date.toLocaleDateString('en-US', options);

                $('.slotTitle').text(`Slot available for ${choosedDate}`);
                displaySlotRes(response);
                handleResize();
            },
            error: function(err) {
                const errMess = err.responseJSON.error;
                $('.errMess').text(errMess);
                $('#available').addClass('d-none');
                $('#success').addClass('d-none');
                $('#unavailable').removeClass();
                $('.closeModalSchedPickerDiv').removeClass('d-none');
            }
        });
    }
    
    });

    function displayErrInp(response){
        const {address, age, patientName, gender, concern, severity} = response?.responseJSON?.error;

        $('input').removeClass('is-invalid');
        $('textarea').removeClass('is-invalid');
        
        if(address?.length){
            const input = $(`#address`);
            input.addClass('is-invalid');
        }
        if(age?.length){
            const input = $(`#age`);
            input.addClass('is-invalid');
        }
        if(patientName?.length){
            const input = $(`#name`);
            input.addClass('is-invalid');
        }
        if(gender?.length){
            const input = $(`#gender`);
            input.addClass('is-invalid');
        }
        if(severity?.length){
            const input = $(`#severity`);
            input.addClass('is-invalid');
        }
        if(concern?.length){
            const input = $(`#concern`);
            input.addClass('is-invalid');
        }
    }

    function displaySlotRes(response) {
        const userId = $('#userId').val();
        const { clientSched, schedules } = response;
        const maxPatient = schedules.maxPatient;
        const allotedTime = schedules.allotedTime;

        let slotBody = '';

        let hr = 8;
        let mins = 0;
        let label = 'AM';
        for (let x = 1; x <= maxPatient; x++) {

            const isTaken = clientSched.find(list => list.number === x && list.status === 1);

            mins += allotedTime;

            if (mins >= 60) {
                mins -= 60;
                hr++;
            }

            if (hr > 12) {
                label = 'PM';
                hr = 1;
            }

            const formattedHr = hr.toString().padStart(2, '0');
            const formattedMins = mins.toString().padStart(2, '0');

            slotBody += `
            <tr class="slotRow" id="slotRow-${x}">
                 <td class="removable customTh" style="min-width: 130px;">
                    ${!isTaken ? `<button id="btn-reserve-${x}-sm" class="btn btn-success btn-sm float-right btn-reserve" number="${x}" time="${formattedHr}:${formattedMins} ${label}">Reserve</button>`:isTaken.userId == userId ? `<button class="btn btn-danger btn-sm float-right d-none btn-cancel" id="${isTaken.id}" ${isTaken.isCancellable ? 'disabled':''}>Cancel</button><button class="btn btn-primary mr-2 btn-sm float-right d-none btn-view" id="${isTaken.id}">View</button>`:''}

                </td>
                <th>${x}</th>
                <td>${formattedHr}:${formattedMins} ${label}</td>
                <td>
                    <span id="sched-avail-${x}" class="badge bg-${!isTaken ? 'primary':isTaken.userId == userId ? 'success':'secondary'} mt-2">${!isTaken ? 'Available':isTaken.userId == userId ? 'Reserved by you':'Taken'}</span>

                   <span class="removableSpan">

                     ${!isTaken ? `<button id="btn-reserve-${x}" class="btn btn-success btn-sm float-right d-none btn-reserve" number="${x}" time="${formattedHr}:${formattedMins} ${label}">Reserve</button>`:isTaken.userId == userId ? `<button class="btn btn-danger btn-sm float-right d-none btn-cancel" id="${isTaken.id}" ${isTaken.isCancellable ? 'disabled':''}>Cancel</button><button class="btn btn-primary mr-2 btn-sm float-right d-none btn-view" id="${isTaken.id}">View</button>`:''}

                    </span>

                </td>
            </tr>
            `;
        }
        $('.slotBody').html(slotBody);
        displaySlotDataTable();
    }

    function displaySlotDataTable() {
        var table = $('#slotData').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": false,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true,
        });
    }

    function displayDataTable() {
        var table = $('#doctorDataTable').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true,
        });
    }
</script>
@endsection

<style>
    #date-picker-sched {
        height: 31px;
        width: 180px;
    }
    /* .customSelect{
        width: 46%;
    } */
</style>