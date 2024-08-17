@section('script')
<script type="module">
    $(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
      
       getData()

       function getData(){
        $.ajax({
            url: '/clinic/getAdmins/get?userType=2',
            type: 'GET',
            dataType: 'json',
            success: function(dataRes) {
                console.log(dataRes)
                $('#loading').removeClass().addClass('d-none');
                $('#content').removeClass().addClass('container-fluid');
                displayTable(dataRes);
            },
            error: function() {
               
                getData()
                $('#loadingLabel').text('Error fetching data. Refetching!');
            }
        });
       }

        $('#addAdminForm').submit(function(event) {
            event.preventDefault();
            var url = $(this).attr('action');
            $('#addAdminBtn').prop('disabled', true).text('Saving...');
            $.ajax({
                url: url,
                method: 'POST',
                data: new FormData(this),
                dataType: 'JSON',
                contentType: false,
                cache: false,
                processData: false,
                success: function(response) {
                    $('.emailForm').addClass('d-none');
                    $('.success').removeClass('d-none');

                    setTimeout(() => {
                        $('.emailForm').removeClass('d-none');
                        $('.success').addClass('d-none');
                        $('#addAdminBtn').prop('disabled', false).text('Save');
                        $('#add-admin').modal('hide');
                        $('#addAdminForm')[0].reset();
                    }, 3000);
                },
                error: function(err) {
                    const status = err.responseJSON.error;
                    $('.emailForm').addClass('d-none');
                    $('.unavailable').removeClass('d-none');
                    $('.errMess').text(status);

                    setTimeout(() => {
                        $('.emailForm').removeClass('d-none');
                        $('.unavailable').addClass('d-none');
                        $('#addAdminBtn').prop('disabled', false).text('Save');
                        $('#add-admin').modal('hide');
                        $('#addAdminForm')[0].reset();
                    }, 3000);
                }
            });
        });
    });

    function displayTable(data) {
        let tableBody = '';
        let deleteModal = '';
        let editModal = '';
        data.forEach(list => {
            list.created_at = list.created_at.slice(0, 10);
            tableBody += createTableBody(list);
            deleteModal += createDeletModal(list);
            editModal += createEditModal(list);
        });

        $('#tableData').html(tableBody);
        $('#del-modal').append(deleteModal);
        $('#edit-modal').append(editModal);
        displayDataTable();
        editAdmin();
        deleteAdmin();
    }

    function createTableBody(list){
        return `
                <tr id="admin-${list.id}">
                    <td>${list.name}</td>
                    <td>${list.email}</td>
                    <td>${list.created_at}
                        <span class="ml-3">
                            <button type="button" class="btn btn-danger ml-2 mb-1 btn-sm" data-toggle="modal" data-target="#delete-${list.id}">
                                <i class="fas fa-trash"></i>
                            </button>
                            <button type="button" class="btn btn-success ml-2 mb-1 btn-sm" data-toggle="modal" data-target="#edit-${list.id}">
                                <i class="fas fa-edit"></i>
                            </button>
                        </span>    
                    </td>
                </tr>`;
    }

    function createDeletModal(list){
        return `
                <form id="delete-form-${list.id}" class="deleteAdmin" action="/clinic/deleteAdmin" adminID="${list.id}" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="modal fade" id="delete-${list.id}" tabindex="-1" role="dialog" aria-labelledby="modal-default-label-${list.id}" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="modal-default-label-${list.id}">Confirm delete</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <p>Are you sure you want to delete admin: ${list.name}?</p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">No</button>
                                    <button type="submit" class="btn btn-danger btn-sm deleteBtn" id="${list.id}">
                                        <span id="yes${list.id}" class="deleteLabel">Yes</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>`;
    }

    function createEditModal(list){
        return ` <form id="edit-form-${list.id}" class="editAdmin" action="/clinic/editAdmin" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal fade" id="edit-${list.id}" tabindex="-1" role="dialog" aria-labelledby="modal-default-label-${list.id}" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="modal-default-label-${list.id}">Update account</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label for="admin-name${list.id}">Name</label>
                                        <input type="text" class="form-control name" id="admin-name${list.id}" placeholder="Enter name" value="${list.name}" name="name">
                                    </div>
                                    <div class="form-group">
                                        <label for="admin-email${list.id}">Email</label>
                                        <input type="email" class="form-control email" id="admin-email${list.id}" placeholder="Enter email" name="email" value="${list.email}">
                                    </div>
                                    <div class="form-group">
                                        <label for="admin-password${list.id}">New password</label>
                                        <input type="password" class="form-control password" id="admin-password${list.id}" placeholder="Enter new password" name="password">
                                    </div>
                                    <input type="hidden" value="2" class="userType">
                                    <input type="hidden" class="id" value="${list.id}">
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">No</button>
                                    <button type="submit" class="btn btn-danger btn-sm editBtn" id="edit${list.id}">
                                        <span id="yes${list.id}" class="editLabel">Yes</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>`;
    }

    function editAdmin() {
        $('.editAdmin').submit(function(event) {
            event.preventDefault();
            var form = $(this);
            var url = form.attr('action');
            const name = form.find('.name').val();
            const email = form.find('.email').val();
            const password = form.find('.password').val();
            const userType = form.find('.userType').val();
            const id = form.find('.id').val();

            const formData = {
                name: name,
                email: email,
                password: password,
                userType: userType,
                id: id
            };

            $('.editBtn').prop('disabled', true);
            $('.editLabel').text('Updating....');

            $.ajax({
                url: url,
                method: 'POST',
                data: formData,
                success: function(response) {
                    const admin = response.user;
                    admin.created_at = admin.created_at.slice(0, 10);
                    $('.editBtn').prop('disabled', false);
                    $('.editLabel').text('Update');
                    $('#edit-' + id).modal('hide');
                    $(`#admin-${id} td:nth-child(1)`).text(admin.name);
                    $(`#admin-${id} td:nth-child(2)`).text(admin.email);
                    $(`#admin-${id} td:nth-child(3)`).html(`${admin.created_at}
                                    <span class="ml-3">
                                        <button type="button" class="btn btn-danger ml-2 mb-1 btn-sm" data-toggle="modal" data-target="#delete-${admin.id}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                        <button type="button" class="btn btn-success ml-2 mb-1 btn-sm" data-toggle="modal" data-target="#edit-${admin.id}">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    </span> `);
                },
                error: function(error) {
                    alert('Error updating admin.');
                    $('.editBtn').prop('disabled', false);
                    $('.editLabel').text('Update');
                }
            });
        });
    }

    function deleteAdmin(){
    $('.deleteAdmin').submit(function(event){
        event.preventDefault();
        var form = $(this);
        var url = form.attr('action');
        var id = form.attr('adminID');
        $('.deleteBtn').prop('disabled', true);
        $('.deleteLabel').text('Deleting....');
        $.ajax({
            url: url + '/get?admin=' + id,
                type: 'DELETE',
                dataType: 'json',
                success: function(data) {
                    console.log(data)
                    $('#example1').DataTable().destroy();
                    $(`#admin-${id}`).remove();
                    $(`#delete-${id}`).modal('hide')
                    $('.deleteBtn').prop('disabled', false);
                    $('.deleteLabel').text('Yes');
                    displayDataTable();
                    setTimeout(() => {
                        $(`#delete-form-${id}`).remove();
                    }, 2000);
            },
            error: function(response) {
                $(`#delete-${id}`).modal('hide')
                $('.deleteBtn').prop('disabled', false);
                $('.deleteLabel').text('Yes');
            }
        });
    });
}

    function displayDataTable() {
        var table = $('#example1').DataTable({
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