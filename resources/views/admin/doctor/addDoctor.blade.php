<form class="addDoctorForm" action="/clinic/addDoctor" method="POST">
    @csrf
    @method('POST')
<div class="modal fade" id="add-doctor" tabindex="-1" role="dialog" aria-labelledby="modal-default-label" aria-hidden="true">
       <div class="modal-dialog" role="document">
           <div class="modal-content">
               <div class="modal-header">
                   <h5 class="modal-title" id="modal-default-label">Add New Doctor</h5>
                   <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                       <span aria-hidden="true">&times;</span>
                   </button>
               </div>
               <div class="modal-body">

                <div class="infoCont">
                    <div class="photo-preview costumContainer">
                        <div class="preview">
                            <label for="doctor-photo" id="photo-picker-label" style="font-size: 60px; font-wieght: bold;">
                                <li class="fas fa-user-plus"></li>
                            </label>
    
                            <label for="doctor-photo" id="photo-picker-label1" class="d-none">
                                <li class="fas fa-edit"></li>
                            </label>
    
                            <img src="" class="d-none" id="photo-preview-doctor" alt="Doctor's photo">
                            <input type="file" id="doctor-photo" style="display: none;" name="photo">
                        </div>
                    </div>

                    <div class="costumContainer">
                        <div class="form-group">
                            <label for="doctor-name">Name</label>
                            <input type="text" class="form-control" id="doctor-name" placeholder="Enter name" name="name">
                        </div>

                        <div class="d-flex infoContF">
                            <div class="form-group w-50">
                                <label for="doctor-age">Age</label>
                                <input type="number" class="form-control" id="doctor-age" placeholder="Enter age" name="age">
                            </div>

                            <div class="form-group w-50">
                                <label>Sex</label>
                                <div class="form-check" style="margin-top: -5px;">
                                    <input class="form-check-input" type="radio" name="gender" id="male" value="Male">
                                    <label class="form-check-label" for="male">
                                      Male
                                    </label>
                                  </div>
                                  <div class="form-check">
                                    <input class="form-check-input" type="radio" name="gender" id="female" value="Female">
                                    <label class="form-check-label" for="female">
                                      Female
                                    </label>
                                  </div>
                            </div>
    
                        </div>
                    </div>
                </div>
                
                       <div class="form-group">
                           <label for="doctor-address">Address</label>
                           <input type="text" class="form-control" id="doctor-address" placeholder="Enter address" name="address">
                       </div>


                    <div class="form-group">
                        <label for="doctor-experience">Years of experience</label>
                        <input type="number" class="form-control" id="doctor-experience" placeholder="Enter photo" name="yrsExp">
                    </div>



                    <div class="form-group">
                        <label for="selectDoctor">Specialty</label><br>
                        <select class="form-select rounded" id="selectDoctor" aria-label="Default select example" name="specialty">
                            <option value="" selected>Open this select menu</option>
                            @foreach ($specialties as $item)
                                <option value="{{$item}}">{{$item}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="doctor-limit">Checkup payment</label>
                        <input type="number" class="form-control" id="doctor-limit" placeholder="Enter payment" name="checkupLimit">
                    </div>
               </div>
               <div class="modal-footer">
                   <button type="button" class="btn btn-secondary btn btn-sm" data-dismiss="modal">Close</button>
                   <button type="submit" class="btn btn-primary btn btn-sm" id="addDoctorBtn">
                       Save
                   </button>
               </div>
           </div>
       </div>
   </div>
</form>