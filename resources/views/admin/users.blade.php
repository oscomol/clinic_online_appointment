@extends('layout.admin.adminLayout')

@section('title')
    Registered User
@endsection

@section('adminContent')
<div id="loading">
    <div class="spinner-grow" role="status">
    </div>
    <p id="loadingLabel">Documents loading. Please wait!</p>
</div>


<div class="d-none" id="content">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">List of registered user</h3>
                </div>
                <div class="card-body">
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th class="toShow">Action</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th style="min-width: 150px;">Created</th>
                            </tr>
                        </thead>
                        <tbody id="tableData">

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


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
            url: '/clinic/getAdmins/get?userType=1',
            type: 'GET',
            dataType: 'json',
            success: function(dataRes) {
                $('#loading').removeClass().addClass('d-none');
                $('#content').removeClass().addClass('container-fluid');
                displayTable(dataRes);
                handleResize();
            },
            error: function() {
                getData()
                $('#loadingLabel').text('Error fetching data. Refetching!');
            }
        });
        }

        $(document).on('mouseover', '.user-row', function() {
                const widthLength = $(window).width();
                if(widthLength >= 594){
                    const userId = $(this).attr('userId');
                    $(this).find(`#user-more-${userId}`).removeClass('d-none');
                }
        });
        $(document).on('mouseout', '.user-row', function() {
                const widthLength = $(window).width();
                if(widthLength >= 594){
                    const userId = $(this).attr('userId');
                    $(this).find(`#user-more-${userId}`).addClass('d-none');
                }   
        });

        function handleResize() {
            const widthL = $(window).width();
            if (widthL <= 594) {
                    $('.user-more').addClass('d-none');
                    $('.toShow').removeClass('d-none');
            } else {
                    $('.user-more').removeClass('d-none');
                    $('.toShow').addClass('d-none');
            }
        }

        $(window).resize(handleResize);
        handleResize();

    });

    function displayTable(data) {
        let tableBody = '';
        data.forEach(list => {
            list.created_at = list.created_at.slice(0, 10);
            tableBody += `
                <tr id="admin-${list.id}" class="user-row" userId="${list.id}">

                    <td class="toShow" style="min-width: 150px;">
                        <a href="/clinic/user/${list.id}" class="btn btn-success btn btn-sm user-more-sm" id="user-more-${list.id}-sm">More</a>
                    </td>

                    <td>${list.name}</td>
                    <td>${list.email}</td>
                    <td style="min-width: 150px;">${list.created_at}
                        <span class="user-more">
                        <a href="/clinic/user/${list.id}" class="d-none float-right btn btn-success btn btn-sm" id="user-more-${list.id}">More</a>
                        </span>
                    </td>
                </tr>`;
        });

        $('#tableData').html(tableBody);
        displayDataTable();
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