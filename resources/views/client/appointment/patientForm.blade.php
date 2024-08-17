<form class="addAppointment" method="POST" action="{{ url('/client-appointment/create', ['userId' => $user->id]) }}">
    @csrf
    @method('POST')
    <div class="modal fade" id="patient-form" tabindex="-1" role="dialog" aria-labelledby="modal-default-label" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-default-label">Patient form</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
 
                 <div>
                     <h6 class="drName"></h6>
                     <p class="checkupInfo"></p>
                 </div>
                 
                     <div class="d-flex justify-content-between customDis">
                         <div class="form-group">
                             <label for="name">Name</label>
                             <input type="text" class="form-control form-control" id="name" placeholder="Enter name" name="name">
                         </div>
     
                         <div class="form-group">
                             <label for="age">Age</label>
                             <input type="number" class="form-control form-control" id="age" placeholder="Enter age" name="age">
                         </div>
                     </div>
 
                     <div class="d-flex justify-content-between customDis">

                        <div class="form-group customSelect">
                            <label for="gender">Gender</label>
                            <select class="form-control" id="gender" name="gender">
                              <option value="">Select gender</option>
                              <option value="Male">Male</option>
                              <option value="Female">Female</option>
                            </select>
                          </div>
     
                         <div class="form-group">
                             <label for="address">Address</label>
                             <input type="text" class="form-control form-control" id="address" placeholder="Enter address" name="address">
                         </div>
                     </div>
 
                     <div class="form-group">
                         <label for="concern">Concern</label>
                         <textarea class="form-control form-control" id="concern" placeholder="State concern here" name="concern"></textarea>
                     </div>

                     <div class="form-group">
                        <label for="severity">Severity</label>
                        <select class="form-control" id="severity" name="severity">
                          <option value="">Severity of patient</option>
                          <option value="Low">Low</option>
                          <option value="Moderate">Moderate</option>
                          <option value="High">High</option>
                        </select>
                      </div>

                      <p class="text-danger otherErrMsg" style="margin-top: -15px;">
                        
                      </p>
 
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn btn-sm" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary btn btn-sm" id="addAppointmentBtn">
                        Save
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>