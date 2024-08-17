<div class="modal fade" id="check-availability" tabindex="-1" role="dialog" aria-labelledby="modal-default-label" aria-hidden="true">
       <div class="modal-dialog" role="document">
           <div class="modal-content">
               <div class="modal-header d-flex justify-content-center">
                    <h5 class="modal-title" id="modal-default-label">Checking availability</h5>
               </div>
               <div class="modal-body availBody">
                
                    <center id="available">
                       <div class="spinner-grow text-primary mt-3" style="width: 6rem; height: 6rem;" role="status">
                       </div>
                        <p class="mt-2">Don't close the modal</p>
                    </center>

                    <center class="d-none" id="success">
                        <div class="mt-3">
 
                             <span id="success-status" class="text-success">
                                 <li class="fas fa-check" style="font-size: 50px;"></li>    
                             </span>
 
                        </div>
                         <p class="mt-2">Slots available. <span class="text-success" data-dismiss="modal" style="cursor: pointer;">Check it out!</span></p>
                     </center>

                    <center class="d-none" id="unavailable">
                        <div class="mt-3">
 
                             <span id="success-status" class="text-danger">
                                 <li class="fas fa-times" style="font-size: 50px;"></li>    
                             </span>
 
                        </div>
                         <p class="mt-2 errMess">Doctor not available on the the choosen date</p>
                     </center>
               </div>
               <div class="modal-footer d-none closeModalSchedPickerDiv">
                    <button class="btn btn-danger btn btn-sm closeModalSchedPicker">Close</button>
               </div>
           </div>
       </div>
   </div>