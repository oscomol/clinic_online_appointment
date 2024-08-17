@section('script')
<script type="module">
    $(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        getStatusRecord(0);

        let data = [];

        
        $('.customSelect').change(function(){
            const val = $(this).val();
            getStatusRecord(val);
        })

        $(document).on('mouseover', '.recordRow', function() {
                const id = $(this).attr('recordId');
                $(this).find('.reserve-more').removeClass('d-none');
            });

        $(document).on('touchstart', '.recordRow', function() {
                const id = $(this).attr('recordId');
                $(this).find('.reserve-more').removeClass('d-none');
            });

        $(document).on('mouseout', '.recordRow', function() {
                const id = $(this).attr('recordId');
                $(this).find('.reserve-more').addClass('d-none');
        });

        $(document).on('click', '.editReservation', function() {
               $(this).addClass('d-none');
               $('#modal-default-label').text('Editing reservation details');
               $('.viewReservationIcon').removeClass('d-none');
               $('.reservation-success').addClass('d-none');
               $('.reservation-edit-form').removeClass('d-none');
               $('#updateReservationBtn').removeClass('d-none');
        });

        $(document).on('click', '.viewReservationIcon', function() {
               $(this).addClass('d-none');
               $('#modal-default-label').text('Viewing reservation details');
               $('.editReservation').removeClass('d-none');
               $('.reservation-success').removeClass('d-none');
               $('.reservation-edit-form').addClass('d-none');
               $('#updateReservationBtn').addClass('d-none');
        });

        $(document).on('click', '.reserve-more', function() {
            const id = $(this).attr('recordId');
            const selectedRecord = data.find(list => list.id == id);
            modalDefaults();

            if(selectedRecord){
                const stat = selectedRecord.status;
                if(stat == 1 && selectedRecord.isPassed > 0){
                    $('.editReservation').removeClass('d-none');
                }else{
                    $('.editReservation').addClass('d-none');
                }

                $('.reservation-success').removeClass('d-none');
                $('#doctor').text(selectedRecord.doctor_name);


                $('#status-view-reservation').removeClass();
                $('#status-view-reservation').addClass(`badge bg-${checkStatus(stat, selectedRecord.isPassed).bg}`).text(`${checkStatus(stat, selectedRecord.isPassed).label}`);

                $('#number').text(selectedRecord.number);
                $('#date').text(selectedRecord.date);
                $('#time').text(selectedRecord.expectedTime);
                $('#payment').text(`Php ${selectedRecord.payment}`);
                $('#doctor').text(selectedRecord.doctor_name);
                $('#viewPatientname').text(selectedRecord.patientName);
                $('#viewPatientage').text(selectedRecord.age);
                $('#viewPatientgender').text(selectedRecord.gender);
                $('#viewPatientaddress').text(selectedRecord.address);
                $('#viewPatientconcern').text(selectedRecord.concern);
                $('#viewPatientseverity').text(selectedRecord.severity);

                $('#edit-reservation-name').val(selectedRecord.patientName);
                $('#edit-reservation-age').val(selectedRecord.age);
                $('#edit-reservation-gender').val(selectedRecord.gender);
                $('#edit-reservation-address').val(selectedRecord.address);
                $('#edit-reservation-concern').val(selectedRecord.concern);
                $('#edit-reservation-severity').val(selectedRecord.severity);
                $('#edit-reservation-id').val(selectedRecord.id);
            }else{
                $('.no-reservation').removeClass('d-none');
                $('.reservation-success').addClass('d-none');
            }
            $('#view-history').modal('show');
        });

        $(".editReservationFormRecord").submit(function(event) {
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
                        $('.reservation-edit-form').addClass('d-none');
                        $('.update-success').removeClass('d-none');
                        $('#updateReservationBtn').prop('disabled', true).text('Updated');

                        setTimeout(() => {
                            $('#view-history').modal('hide');
                            modalDefaults();
                        }, 2000);

                        const val = $('.recordSelect').val();
                        getStatusRecord(val);
                    },
                    error: function(response) {
                        alert('ERROR')
                        $('#view-history').modal('hide');
                        modalDefaults();
                    }
                });
        })

        function checkStatus(status, passed){
            let labels = {};

            if(passed < 0 && status == 1){
                labels = {label: "Passed", bg: "info"}
            }else{
                if(status == 1){
                    labels = {label: "Reserved", bg: "primary"}
                }else if(status == 2){
                    labels = {label: "Cancelled", bg: "danger"}
                }else if(status == 3){
                    labels = {label: "Cancelled", bg: "warning"}
                }else if(status == 4){
                    labels = {label: "Missing", bg: "info"}
                }else if(status == 5){
                    labels = {label: "Done", bg: "success"}
                }else if(status == 6){
                    labels = {label: "Doctor unavailable", bg: "warning"}
                }else if(status == 7){
                    labels = {label: "Client not attended", bg: "warning"}
                }else{
                    labels = {label: "System error", bg: "warning"}
                }
            }
            return labels;
        }

        function getStatusRecord(status) {
        $.ajax({
            url: '/clinic/getData/records/get?status=' + status,
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                $('#records-history').DataTable().destroy();   
                data = response;
                displayHistory(data);          
                displayDataTable();
            },
            error: function() {
                alert('Error fetching data.');
            }
        });
    }
    });

    function modalDefaults(){
        $('#updateReservationBtn').addClass('d-none');
            $('.no-reservation').addClass('d-none');
            $('.reservation-edit-form').addClass('d-none');
            $('#modal-default-label').text('Viewing reservation details');
            $('.editReservation').removeClass('d-none');
            $('.reservation-success').addClass('d-none');
            $('.reservation-edit-form').addClass('d-none');
            $('.update-success').addClass('d-none');
            $('.viewReservationIcon').addClass('d-none');
            $('#updateReservationBtn').prop('disabled', false).text('Update')
    }

    function displayHistory(recent) {
        let statusRecent = '';

        for (let x = 0; x < recent.length; x++) {
            const list = recent[x];
            const date = new Date(list.date);
            const options = { year: 'numeric', month: 'long', day: '2-digit' };
            const choosedDate = date.toLocaleDateString('en-US', options);

            statusRecent += `
                <tr id="records-${list.id}" class="recordRow" recordId="${list.id}">
                    <th class="names">Dr. ${list.doctor_name}</th>
                    <th class="names">${list.patientName}</th>
                    <td>${choosedDate}</td>
                    <td class="removable">${list.expectedTime}</td>
                     <td class="removable">${list.payment} 
                        <button id="reserve-btn-${list.id}" class="d-none reserve-more float-right btn btn-success btn btn-sm opt" recordId="${list.id}">
                            <li class="fas fa-eye"></li>
                        </button>
                    </td>
                     
                </tr>
            `;
        }
        $('.history-records').html(statusRecent);
        handleResize()
    }

    function handleResize() {
        const widthL = $(window).width();
                if (widthL <= 550) {
                   $('.opt').removeClass('d-none');
                } else {
                    $('.opt').addClass('d-none');
                }

                if(widthL < 700){
                    $('.customSpan').removeClass('w-25').addClass('w-50');
                }else{
                    $('.customSpan').removeClass('w-50').addClass('w-25');
                }
            }

    $(window).resize(handleResize);

    handleResize()


    function displayDataTable() {

        var table = $('#records-history').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true
        });
    }
</script>
@endsection