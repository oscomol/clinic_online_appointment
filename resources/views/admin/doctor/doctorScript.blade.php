@section('script')
    <script type="module">
        $(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            function handleResize() {
                if ($(window).width() <= 900) {
                    $('.removable').removeClass('d-none');
                    $('.otherRemovable').addClass('d-none');
                } else {
                    $('.removable').addClass('d-none');
                    $('.otherRemovable').removeClass('d-none');
                }
            }

            $(window).resize(handleResize);
            handleResize()

            getData()

            function getData(){
                $.ajax({
                url: '/clinic/getDoctor',
                type: 'GET',
                dataType: 'json',
                success: function(dataRes) {
                    $('#loading').addClass('d-none');
                    $('#content').removeClass('d-none').addClass('container-fluid');
                    const baseURL = window.location.origin;
                    let tableBody = '';
                    let doctorDel = '';
                    let doctorView = '';
                    let doctorEdit = '';
                    for (let x = 0; x < dataRes.length; x++) {
                        tableBody += createTableBody(dataRes[x]);
                        doctorDel += doctorDeletModal(dataRes[x]);
                        doctorView += doctorViewModal(dataRes[x]);
                        doctorEdit += createEditDoctorModal(dataRes[x]);
                    }
                    $('#tableDoctorData').html(tableBody);
                    $('#doctor-del-modal').html(doctorDel);
                    $('#doctor-view-modal').html(doctorView);
                    $('#doctor-edit-modal').html(doctorEdit);
                    displayDataTable();
                    deleteDoctor();
                    handlePhotoEditChange()
                    saveEditedDate();
                    handleResize();
                },
                error: function() {
                    getData()
                    $('#loadingLabel').text('Error fetching data. Refetching!');
                }
            });
            }

            $("#doctor-photo").change(function() {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        $('#photo-picker-label').addClass('d-none');
                        $('#photo-picker-label1').removeClass('d-none').addClass('btn btn-success btn btn-sm');
                        $('#photo-preview-doctor').removeClass('d-none');
                        $('#photo-preview-doctor').attr('src', e.target.result);
                    }
                    reader.readAsDataURL(file);
                }
            });

            $(document).on('submit', '.addDoctorForm', function(event) {
                event.preventDefault();
                var url = $(this).attr('action');
                $('#addDoctorBtn').prop('disabled', true).text('Saving...');
                $.ajax({
                    url: url,
                    method: 'POST',
                    data: new FormData(this),
                    dataType: 'JSON',
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function(response) {
                        $('#add-doctor').modal('hide');
                        $('#addDoctorBtn').prop('disabled', false).text('Save');
                        $('#doctorDataTable').DataTable().destroy();
                        $('.addDoctorForm')[0].reset();

                        $('#photo-picker-label').removeClass();
                        $('#photo-picker-label1').removeClass().addClass('d-none');
                        $('#photo-preview-doctor').removeClass().addClass('d-none');
                        $('#photo-preview-doctor').attr('src', '');

                        $('#tableDoctorData').append(createTableBody(response));
                        $('#doctor-del-modal').append(doctorDeletModal(response));
                        $('#doctor-view-modal').append(doctorViewModal(response));
                        $('#doctor-edit-modal').append(createEditDoctorModal(response));

                        displayDataTable();
                        deleteDoctor();
                        handlePhotoEditChange()
                        saveEditedDate()
                    },
                    error: function() {
                        alert('Something went wrong!');
                        $('#addDoctorBtn').prop('disabled', false).text('Save');
                        $('.addDoctorForm')[0].reset();
                    }
                });
            });

            $(document).on('mouseover', '.doctorsBody', function() {
                const widthLength = $(window).width();
                if(widthLength > 900){
                    const id = $(this).attr('id');
                    $(`#btns-${id}`).removeClass('d-none');
                    $(`#status-${id}`).removeClass('d-none');
                }
            });

            $(document).on('mouseout', '.doctorsBody', function() {
                const id = $(this).attr('id');
                $(`#btns-${id}`).addClass('d-none');
                $(`#status-${id}`).addClass('d-none');
            });

            $(document).on('click', '.status', function() {
                const widthL = $(window).width();
                const statusID = $(this).attr('statusID');
                const status = widthL <= 900 ? $(`#doctorsStat${statusID}`).text():$(`#doctorsStatSec${statusID}`).text();
                let stat = null;
                    if(status === "Available"){
                        $(this).find(`#icon-${statusID}`).removeClass().addClass('fas fa-toggle-off');
                        if(widthL <= 900){
                            $(`#doctorsStat${statusID}`).removeClass('bg-primary').addClass('bg-secondary').text('Leave');
                        }else{
                            $(`#doctorsStatSec${statusID}`).removeClass('bg-primary').addClass('bg-secondary').text('Leave');
                        }
                        stat = 1;
                    }else{
                        $(this).find(`#icon-${statusID}`).removeClass().addClass('fas fa-toggle-on');
                        if(widthL <= 900){
                            $(`#doctorsStat${statusID}`).removeClass('bg-secondary').addClass('bg-primary').text('Available');
                        }else{
                            $(`#doctorsStatSec${statusID}`).removeClass('bg-secondary').addClass('bg-primary').text('Available');
                        }
                        stat = 0;
                    }

                    $.ajax({
                        url: "/clinic/updateStatus",
                        type: 'POST',
                        data: {
                            stat,
                            id: statusID
                        },
                        dataType: 'json',
                        success: function(response) {
                        stat = null;
                        },
                        error: function() {
                            alert('Something went wrong!');
                        }
                    });
            });
        });

        function createTableBody(list) {
            return `
            <tr id="admin-${list.id}" class="doctorsBody">
                <td style="min-width: 160px;">Dr. ${list.name}
                     <span id="btns-admin-${list.id}" class="d-none float-right">
                        <button type="button" class="btn btn-primary btn-sm btn-edit" data-id="${list.id}">
                            <li class="fas fa-edit"></li>
                        </button>
                        <button type="button" class="btn btn-success btn-sm btn-view" data-id="${list.id}">
                            <li class="fas fa-eye"></li>
                        </button>
                        <button type="button" class="btn btn-danger btn-sm btn-delete" data-id="${list.id}">
                            <i class="fas fa-trash"></i>
                        </button>
                    </span>
                </td>
                 <td class="removable">

                    <span id="doctorsStat${list.id}" class="badge bg-${list.isAvailable === 0 ? 'primary':'secondary'} mt-2">${list.isAvailable === 0 ? 'Available':'Leave'}</span>
                </td>
                <td>${list.specialty}</td>
                <td>Php ${list.checkupLimit}</td>


                <td style="width: 150px;" class="otherRemovable">


                <span id="doctorsStatSec${list.id}" class="badge bg-${list.isAvailable === 0 ? 'primary':'secondary'} mt-2">${list.isAvailable === 0 ? 'Available':'Leave'}</span>

                <span id="status-admin-${list.id}" class="d-none float-right status text-warning" statusID="${list.id}">
                    <i id="icon-${list.id}" class="fas ${list.isAvailable === 0 ? 'fa-toggle-on' : 'fa-toggle-off'}" style="font-size: 20px;"></i>
                </span>


                </td>


                <td class="removable">
                    <span class="d-flex float-right">
                        <button type="button" class="btn btn-primary btn-sm btn-edit" data-id="${list.id}">
                            <li class="fas fa-edit"></li>
                        </button>
                        <button type="button" class="btn btn-success btn-sm btn-view mx-2" data-id="${list.id}">
                            <li class="fas fa-eye"></li>
                        </button>
                        <button type="button" class="btn btn-danger btn-sm btn-delete" data-id="${list.id}">
                            <i class="fas fa-trash"></i>
                        </button>

                        <span id="status-admin-${list.id}" class="removable status text-warning mx-2" statusID="${list.id}">
                            <i id="icon-${list.id}" class="fas ${list.isAvailable === 0 ? 'fa-toggle-on' : 'fa-toggle-off'}" style="font-size: 20px;"></i>
                        </span>
                    </span>
                    
                </td>
            </tr>`;
        }

        function doctorDeletModal(list) {
            return `
                <form id="doctor-delete-form-${list.id}" class="deleteDoctor" action="/clinic/deleteDoctor" doctorID="${list.id}" method="POST" photo="${list.photo}">
                    @csrf
                    @method('DELETE')
                    <div class="modal fade" id="doctor-delete-${list.id}" tabindex="-1" role="dialog" aria-labelledby="modal-default-p-${list.id}" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="modal-default-p-${list.id}">Confirm delete</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <p>Are you sure you want to delete admin: ${list.name}?</p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">No</button>
                                    <button type="submit" class="btn btn-danger btn-sm deleteDoctorBtn" id="${list.id}">
                                       Yes
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    </form>`;
        }

        function doctorViewModal(list) {
            const baseURL = window.location.origin;
            return `
                    <div class="modal fade" id="doctor-view-${list.id}" tabindex="-1" role="dialog" aria-labelledby="modal-default-p-${list.id}" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="modal-default-p-${list.id}">${list.name}</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    
                                    
                <div class="infoCont">
                    <div class="photo-preview costumContainer">
                        <div class="preview">
                             <img src="${baseURL}/clinic/doctors/image/${list.photo}" class="photo-view" alt="Doctor's photo">
                        </div>
                    </div>

                    <div class="costumContainer edit-view-info">
                        <h6>Dr. ${list.name}</h6>
                        <p>${list.gender}, ${list.age} years old<br>
                            <span style="font-weight: bold;">${list.specialty}</span>, with ${list.yrsExp} years of experience<br>
                            ${list.address}<br>
                            ${list.isAvailable === 0 ? '<span class="badge bg-primary mt-2">Available</span>':'<span class="badge bg-secondary mt-2">Unavailable</span>'}
                        </p>
                    </div>
                </div>

                                </div>
                                <div class="modal-footer">
                                    <a href="/clinic/admin/doctors/view/get?doctor=${list.id}" class="btn btn-success btn-sm" id="view${list.id}" >View more</a>
                                    <button type="button" class="btn btn-danger btn-sm" id="${list.id}" data-dismiss="modal">
                                       Close
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>`;
        }

        function createEditDoctorModal(list) {

            const specialties = [
            'Allergy and Immunology',
            'Anesthesiology',
            'Cardiology',
            'Dermatology',
            'Emergency Medicine',
            'Endocrinology',
            'Family Medicine',
            'Gastroenterology',
            'Geriatrics',
            'Hematology',
            'Infectious Disease',
            'Internal Medicine',
            'Nephrology',
            'Neurology',
            'Nuclear Medicine',
            'Obstetrics and Gynecology',
            'Oncology',
            'Ophthalmology',
            'Orthopedic Surgery',
            'Otolaryngology',
            'Pediatrics',
            'Physical Medicine and Rehabilitation',
            'Plastic Surgery',
            'Podiatry',
            'Preventive Medicine',
            'Psychiatry',
            'Pulmonology',
            'Rheumatology',
            'Sleep Medicine',
            'Surgery',
            'Thoracic Surgery',
            'Urology',
            'Vascular Surgery'
        ];

            return `
    <form id="doctor-edit-form-${list.id}" class="editDoctor" action="/clinic/editDoctor" method="POST">
        @csrf
        @method('POST')
        <div class="modal fade" id="doctor-edit-${list.id}" tabindex="-1" role="dialog" aria-labelledby="modal-default-label" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modal-default-label">Edit Doctor</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                <div class="infoCont">
                    <div class="photo-preview costumContainer">
                         <div class="preview">
                             <label for="doctor-photo-${list.id}" id="photo-picker-label1" class="btn btn-success btn btn-sm">
                                        <li class="fas fa-edit"></li>
                                    </label>

                                    <img src="${window.location.origin}/clinic/doctors/image/${list.photo}"  class="preview-edit" id="photo-preview-doctor-${list.id}" alt="Doctor's photo">

                                    <input type="file" id="doctor-photo-${list.id}" class="photo-select" style="display: none;" name="photo"  data-doctors-id="${list.id}">
                        </div>
                    </div>
                    <div class="costumContainer">
                        <div class="form-group">
                                    <label for="doctor-name-${list.id}">Name</label>
                                    <input type="text" class="form-control" id="doctor-name-${list.id}" placeholder="Enter name" name="name" value="${list.name}">
                                </div>
                         <div class="d-flex infoContF">
                            <div class="form-group w-50">
                                        <label for="doctor-age-${list.id}">Age</label>
                                        <input type="number" class="form-control" id="doctor-age-${list.id}" placeholder="Enter age" name="age" value="${list.age}">
                                    </div>

                            <div class="form-group w-50">
                                <label>Sex</label>
                                <div class="form-check" style="margin-top: -5px;">
                                    <input class="form-check-input" type="radio" name="gender" id="male-${list.id}" value="Male" ${list.gender === 'Male' ? 'checked' : ''}>
                                <label class="form-check-label" for="male-${list.id}">
                                              Male
                                </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="gender" id="female-${list.id}" value="Female" ${list.gender === 'Female' ? 'checked' : ''}>
                                <label class="form-check-label" for="female-${list.id}">
                                          Female
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <input type="hidden" id="id-${list.id}" name="id" value="${list.id}">
                <input type="hidden" id="existing-${list.id}" name="existingPhoto" value="${list.photo}">

                 <div class="form-group">
                                <label for="doctor-address-${list.id}">Address</label>
                                <input type="text" class="form-control" id="doctor-address-${list.id}" placeholder="Enter address" name="address" value="${list.address}">
                            </div>

                            <div class="form-group">
                                <label for="doctor-experience-${list.id}">Years of experience</label>
                                <input type="number" class="form-control" id="doctor-experience-${list.id}" placeholder="Enter years of experience" name="yrsExp" value="${list.yrsExp}">
                            </div>


                            <div class="form-group">
                                <labelfor="doctor-specialty-${list.id}">Specialty</label><br>
                                <select class="form-select rounded selectDoctor" id="doctor-specialty-${list.id}" aria-label="Default select example" name="specialty">
                                    <option value="" selected>Open this select menu</option>
                                      ${specialties.map(spc => `<option value="${spc}" ${spc == list.specialty ? 'selected' : ''}>${spc}</option>`).join('')}
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="doctor-limit-${list.id}">Checkup payment</label>
                                <input type="number" class="form-control" id="doctor-limit-${list.id}" placeholder="Enter payment" name="checkupLimit" value="${list.checkupLimit}">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary btn-sm editDoctorBtn" id="editDoctorBtn-${list.id}">
                                Save
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    `;
        }


        function deleteDoctor() {
            $('.deleteDoctor').submit(function(event) {
                event.preventDefault();
                $('.deleteDoctorBtn').prop('disabled', true).text('Deleting...');
                var form = $(this);
                var url = form.attr('action');
                var id = form.attr('doctorID');
                var photo = form.attr('photo');
                $.ajax({
                    url: url + '/get?doctor=' + id,
                    type: 'DELETE',
                    data: {
                        photo
                    },
                    dataType: 'json',
                    success: function(data) {
                        $('#doctorDataTable').DataTable().destroy();
                        $(`#doctor-delete-${id}`).modal('hide')
                        $(`#admin-${id}`).remove();
                        $('.deleteDoctorBtn').prop('disabled', false).text('Yes');
                        displayDataTable();
                        setTimeout(() => {
                            $(`#doctor-delete-form-${id}`).remove();
                        }, 2000);
                    },
                    error: function(response) {
                        $(`#doctor-delete-${id}`).modal('hide')
                        $('.deleteDoctorBtn').prop('disabled', false).text('Yes');
                    }
                });
            });
        }

        function saveEditedDate() {
    $('.editDoctor').submit(function(event) {
        event.preventDefault();
        const form = $(this);

        const id = new FormData(this).get('id');

        $('.editDoctorBtn').prop('disabled', true).text('Updating...');
        $.ajax({
            url: form.attr('action'),
            method: 'POST',
            data: new FormData(this),
            dataType: 'JSON',
            contentType: false,
            cache: false,
            processData: false,
            success: function(response) {
                $('.editDoctorBtn').prop('disabled', false).text('Save');
                $(`#doctor-edit-${id}`).modal('hide');
                $('#doctorDataTable').DataTable().destroy();
                $(`#admin-${id}`).remove();
                $(`#doctor-view-${id}`).remove();
                $(`#doctor-delete-form-${id}`).remove();
                $('#tableDoctorData').append(createTableBody(response));
                $('#doctor-view-modal').append(doctorViewModal(response));
                $('#doctor-del-modal').append(doctorDeletModal(response));
                displayDataTable();
                deleteDoctor();
            },
            error: function(error) {
                alert('Error updating admin.');
                $('.editDoctorBtn').prop('disabled', false).text('Save');
                $(`#doctor-edit-${id}`).modal('hide');
                }
            });
    });
}

        function handlePhotoEditChange() {
            $(".photo-select").change(function() {
                const doctorsId = $(this).data('doctors-id');
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        $(`#photo-preview-doctor-${doctorsId}`).attr('src', e.target.result);
                    }
                    reader.readAsDataURL(file);
                }
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

        $(document).on('click', '.btn-view', function() {
            const id = $(this).data('id');
            $(`#doctor-view-${id}`).modal('show');
        });

        $(document).on('click', '.btn-edit', function() {
            const id = $(this).data('id');
            $(`#doctor-edit-${id}`).modal('show');
        });

        $(document).on('click', '.btn-delete', function() {
            const id = $(this).data('id');
            $(`#doctor-delete-${id}`).modal('show');
        });
    </script>
@endsection

<style>
    .doctorsBody {
        cursor: pointer;
    }

    .photo-view, .preview-edit {
        width: 100%;
        height: 100%;
    }
</style>