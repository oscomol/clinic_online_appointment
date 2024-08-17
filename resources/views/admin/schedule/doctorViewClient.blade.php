    <div class="modal fade" id="doctor-view-client-info" tabindex="-1" role="dialog" aria-labelledby="modal-default-label" aria-hidden="true">
       <div class="modal-dialog" role="document">
           <div class="modal-content">
               <div class="modal-header">
                   <h5 class="modal-title" id="modal-default-label">Patient <span class="patientNumber">?</span></h5>

                   <select class="form-select selectLabel" aria-label="Default select example">
                        <option value="5">Success</option>
                        <option value="6">Doctor not available</option>
                        <option value="7">Client not attended</option>
                        <option value="8">System error</option>
                   </select>

                   <button type="button" class="close closeBtnClientInfo" data-dismiss="modal" aria-label="Close">
                       <span aria-hidden="true">&times;</span>
                   </button>

               </div>
               <div class="modal-body">

                <table class="table table-bordered table-striped tableClientData">
                    <tbody>
                        <tr>
                            <th>Status</th>
                            <td>
                                <span id="status-view-reservation" class="badge bg-primary">
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>Payment</th>
                            <td id="payment"></td>
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
               <div class="modal-footer">
                   <button type="button" class="btn btn-success btn btn-sm d-none markReservation">Add remarks</button>
                   <button type="button" class="btn btn-secondary btn btn-sm" data-dismiss="modal">Close</button>
               </div>
           </div>
       </div>
   </div>
   

   <style>
    .selectLabel{
        width: 180px;
        height: 31px;
        border-radius: 3px;
        padding: 2px;
    }
   </style>