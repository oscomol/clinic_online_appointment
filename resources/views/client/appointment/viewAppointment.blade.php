<form class="editReservationForm" method="POST" action="/clinic/client-appointment/edit">
    @csrf
    @method('put')
    <div class="modal fade" id="view-reservation" tabindex="-1" role="dialog" aria-labelledby="modal-default-label" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-default-label">Reservation data</h5>
                    <button type="button" class="btn btn-sm btn btn-outline-success d-none editReservation">
                        <li id="icon-view-reservation" class="fas fa-edit"></li>
                    </button>

                    <button type="button" class="btn btn-sm btn btn-outline-success d-none viewReservationIcon">
                        <li class="fas fa-eye"></li>
                    </button>
                </div>
                <div class="modal-body">

                    <center class="reservation-loading">
                        <div class="spinner-grow text-primary mt-3" style="width: 6rem; height: 6rem;" role="status">
                        </div>
                         <p class="mt-2">Don't close the modal. We're getting info!</p>
                     </center>

                     <div class="d-none reservation-success">
                        <table class="table table-bordered table-striped">
                            <tbody>
                                <tr>
                                    <th>Number</th>
                                    <td id="number"></td>
                                </tr>
                                <tr>
                                    <th>Date</th>
                                    <td id="date"></td>
                                </tr>
                                <tr>
                                    <th>Expected time</th>
                                    <td id="time"></td>
                                </tr>
                                <tr>
                                    <th>Patient name</th>
                                    <td id="viewPatientname"></td>
                                </tr>
                                <tr>
                                    <th>Age</th>
                                    <td id="viewPatientage"></td>
                                </tr>
                                <tr>
                                    <th>Gender</th>
                                    <td id="viewPatientgender"></td>
                                </tr>
                                <tr>
                                    <th>Address</th>
                                    <td id="viewPatientaddress"></td>
                                </tr>
                                <tr>
                                    <th>Concern</th>
                                    <td id="viewPatientconcern"></td>
                                </tr>
                                <tr>
                                    <th>Severity</th>
                                    <td id="viewPatientseverity"></td>
                                </tr>
                            </tbody>
                        </table>
                     </div>

                <div class="d-none reservation-edit">
                    <div class="d-flex justify-content-between customDis">
                        <div class="form-group">
                            <label for="edit-reservation-name">Name</label>
                            <input type="text" class="form-control form-control" id="edit-reservation-name" placeholder="Enter name" name="name">
                        </div>
    
                        <div class="form-group">
                            <label for="edit-reservation-age">Age</label>
                            <input type="number" class="form-control form-control" id="edit-reservation-age" placeholder="Enter age" name="age">
                        </div>
                    </div>

                    <div class="d-flex justify-content-between customDis">

                       <div class="form-group customSelect">
                           <label for="edit-reservation-gender">Gender</label>
                           <select class="form-control" id="edit-reservation-gender" name="gender">
                             <option value="">Select gender</option>
                             <option value="Male">Male</option>
                             <option value="Female">Female</option>
                           </select>
                         </div>
    
                        <div class="form-group">
                            <label for="edit-reservation-address">Address</label>
                            <input type="text" class="form-control form-control" id="edit-reservation-address" placeholder="Enter address" name="address">
                        </div>

                        <input type="hidden" id="edit-reservation-id" name="id">
                    </div>

                    <div class="form-group">
                        <label for="edit-reservation-concern">Concern</label>
                        <textarea class="form-control form-control" id="edit-reservation-concern" placeholder="State concern here" name="concern"></textarea>
                    </div>

                    <div class="form-group">
                       <label for="edit-reservation-severity">Severity</label>
                       <select class="form-control" id="edit-reservation-severity" name="severity">
                         <option value="">Severity of patient</option>
                         <option value="Low">Low</option>
                         <option value="Moderate">Moderate</option>
                         <option value="High">High</option>
                       </select>
                     </div>
                </div>

                <center class="d-none" id="success-reservation-update">
                    <div class="mt-3">

                         <span id="success-status" class="text-success">
                             <li class="fas fa-check" style="font-size: 50px;"></li>    
                         </span>

                    </div>
                     <p class="mt-2">Updated successfully!</p>
                 </center>
 
                </div>
                <div class="modal-footer viewReservationFooter d-none">
                    <button type="button" class="btn btn-secondary btn btn-sm" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success btn btn-sm" id="updateReservationBtn">Update</button>
                </div>
            </div>
        </div>
    </div>
</form>