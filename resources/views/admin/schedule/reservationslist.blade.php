<div class="container-fluid" id="content">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Reservations</h3>
                    <button class="btn btn-sm btn btn-danger ml-2 float-right cancelAll d-none">Cancel</button>
                    <br class="break d-none">
                    <br class="break d-none">
                    <span class="float-right spanCont d-flex ">
                        <span class="removebleSelects d-flex w-100">
                            <input type="text" value="{{$today}}" class="form-control w-50 day-picker-sched" id="day-picker-sched" placeholder="Choose date here">
                        <select class="customSelect" name="customSelect" id="customSelect">
                           <option value="0">All</option>
                           <option value="1" selected>Reserved</option>
                           <option value="2">Cancelled</option>
                           <option value="5">Done</option>
                        </select>
                        </span>
                    </span>
                    {{-- <span class="d-flex smScreenSelect">
                        <input type="text" value="{{$today}}" class="form-control day-picker-sched" id="day-picker-sched" placeholder="Choose date here">
                        <select class="customSelect" name="customSelect" id="customSelect">
                           <option value="0">All</option>
                           <option value="1" selected>Reserved</option>
                           <option value="2">Cancelled</option>
                           <option value="5">Done</option>
                        </select>
                    </span> --}}
                </div>
                <div class="card-body">
                    
                    <div id="loading">
                        <div class="spinner-grow" role="status">
                        </div>
                        <p>Documents loading. Please wait!</p>
                    </div>

                    <div class="err d-none">
                        <div class="spinner-grow" role="status">
                        </div>
                        <p>An error occur. Refetching!</p>
                    </div>

                    <table id="doctors-reservation-list" class="table table-bordered table-striped d-none">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Age</th>
                                <th>Gender</th>
                                <th>Time</th>
                                <th>Number</th>
                            </tr>
                        </thead>
                        <tbody class="doctors-reservation-list-row">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>




<form class="cancelReservation" action="/clinic/admin/cancel/reservation" method="POST">
    @csrf
    @method('POST')
<div class="modal fade" id="cancelReservationModal" tabindex="-1" role="dialog" aria-labelledby="modal-default-label" aria-hidden="true">
       <div class="modal-dialog" role="document">
           <div class="modal-content">
               <div class="modal-header">
                   <h5 class="modal-title" id="modal-default-label">Confirm</h5>
                   <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                       <span aria-hidden="true">&times;</span>
                   </button>
               </div>
               <div class="modal-body">
                <p class="confirmCancel">Are you sure to cancel reservations ?</p>
                <center class="d-none confirmSuccess">
                    <div class="mt-3">

                         <span id="success-status" class="text-success">
                             <li class="fas fa-check" style="font-size: 50px;"></li>    
                         </span>

                    </div>
                     <p class="mt-2">Reservations cancelled succesfully!</p>
                 </center>
               </div>
               <div class="modal-footer">
                <button type="submit" class="btn btn-danger btn btn-sm cancelBtnSubmit">
                    Cancel
                </button>
                   <button type="button" class="btn btn-secondary btn btn-sm" data-dismiss="modal">Close</button>
               </div>
           </div>
       </div>
   </div>
</form>


<style>
     .customSelect{
        width: 50%;
        height: 31px;
        border-radius: 3px;
        padding: 2px;
        margin-left: 7px;
    }
    #loading, .err{
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        gap: 5px;
    }
</style>