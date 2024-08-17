@extends('layout.client.clientLayout')

@section('title')
    Doctor's list
@endsection

@section('clientContent')
    <input type="hidden" id="userID" value="{{ $user->id }}">

    <div id="loading">
        <div class="spinner-grow" role="status">
        </div>
        <p class="mess">Documents loading. Please wait!</p>
    </div>

    <div class="d-none" id="appoint">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Doctor's list</h3>
                        <br class="break d-none">
                        <select id="customSelect" class="float-right customSelect">
                            @foreach ($specialties as $item)
                                <option value="{{$item}}">{{$item ? $item:"All"}}</option>
                            @endforeach
                        </select>
                        
                    </div>
                    <div class="card-body">

                        <table id="doctorDataTable" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th class="removable">Specialty</th>
                                    <th class="removable">Price</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody id="tableDoctorData">

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="more-doctor-modal"></div>
@endsection

@section('script')
    <script type="module">
        $(function() {

            let doctorsList = [];

            getData()

           function getData(){
            $.ajax({
                url: '/clinic/getData/appoint/get?user=' + 3,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    $('#loading').removeClass().addClass('d-none');
                    $('#appoint').removeClass().addClass('container-fluid');
                    doctorsList = response;
                    processList(doctorsList);
                    
                },
                error: function(err) {
                    getData()
                    $('.mess').text('An error occur. Refetching!');
                }
            });
           }

            $(document).on('change', '#customSelect', function() {
                const selected = $(this).val();
                $('#doctorDataTable').DataTable().destroy();
                let dataSelected = doctorsList;
                if(selected){
                    dataSelected = doctorsList.filter(list => list.specialty === selected);
                }
                processList(dataSelected);
            });

            $(document).on('mouseover', '.doctorsBody', function() {
                const id = $(this).attr('id');
                $(`#${id}-more`).removeClass('d-none');
            });

            $(document).on('mouseout', '.doctorsBody', function() {
                const id = $(this).attr('id');
                $(`#${id}-more`).addClass('d-none');
            });

            $(document).on('click', '.doctorsBody', function() {
                const id = $(this).attr('id');
                $(`#${id}-more`).removeClass('d-none');
            });
            
        });

        function handleResize() {
                if ($(window).width() <= 550) {
                    $('.removable').addClass('d-none');
                    $('.doctorName').find('.mobileViewBtn').removeClass('d-none');
                    $('.customSpan').removeClass('float-right').addClass('ml-5');
                    $('.break').removeClass('d-none')
                    $('.customSelect').removeClass('float-right w-25').addClass('w-100');
                } else {
                    $('.removable').removeClass('d-none'); 
                    $('.doctorName').find('.mobileViewBtn').addClass('d-none');
                    $('.customSpan').addClass('float-right');
                    $('.break').addClass('d-none')
                    $('.customSelect').removeClass('w-100').addClass('float-right w-25');
                }
            }

        $(window).resize(handleResize);

        function processList(list) {
            let doctorsList = '';
            let viewDoctor = '';
            for (let x = 0; x < list.length; x++) {
                doctorsList += createTableBody(list[x]);
                viewDoctor += doctorViewModal(list[x]);
            }
            $('#tableDoctorData').html(doctorsList);
            $('#more-doctor-modal').html(viewDoctor);
            displayDataTable();
            handleResize();
        }

        function createTableBody(list) {
            return `
            <tr id="doctors-list-${list.id}" class="doctorsBody">
                <th style="width: 280px;" class="doctorName">Dr. ${list.name}

                <span class="d-none float-right mt-1 mobileViewBtn" data-toggle="modal" data-target="#more-${list.id}">
                    ...
                </span>
                
                </th>

                <td class="removable">${list.specialty}</td>
                <td class="removable">Php ${list.checkupLimit}</td>

                <td style="width: 150px;">
                 <span id="doctorsStat${list.id}" class="badge bg-${list.isAvailable === 0 ? 'primary':'secondary'} mt-2">${list.isAvailable === 0 ? 'Available':'Leave'}</span>

                  <span id="doctors-list-${list.id}-more" class="d-none float-right mt-1">
                    <button type="button" class="btn btn-success btn btn-sm" data-toggle="modal" data-target="#more-${list.id}">
                            View
                    </button>
                </span>

                </td>
            </tr>`;
        }

        
        
        function doctorViewModal(list) {
            const baseURL = window.location.origin;
            return `
                    <div class="modal fade" id="more-${list.id}" tabindex="-1" role="dialog" aria-labelledby="modal-default-p-${list.id}" aria-hidden="true">
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
                                    <a href="/clinic/appointment/get?default=${list.id}" class="btn btn-success btn btn-sm">Make schedule</a>
                                    <button type="button" class="btn btn-danger btn-sm" id="${list.id}" data-dismiss="modal">
                                       Close
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>`;
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
    #loading {
        width: 100%;
        height: 70vh;
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: column;
    }
    #customSelect{
        height: 30px;
        border-radius: 2px;
        padding: 2px;
        width: 100%;
    }
    
    .infoCont{
        display: flex;
        gap: 20px;
        margin-bottom: 20px;
    }
    .costumContainer{
        width: 50%;
        height: 170px;
    }
    .photo-preview{
        border: 1px solid gray;
        border-radius: 10px;
        display: flex;
        justify-content: center;
        align-items: center;
    }
    .infoContF{
        gap: 10px;
    }
    .preview{
        width: 100%;
        height: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
        position: relative;
    }

    #photo-picker-label1, #photo-picker-edit{
        position: absolute;
        right: 10px;
        bottom: 5px;
    }
    .photo-view {
        width: 100%;
        height: 100%;
    }
</style>
