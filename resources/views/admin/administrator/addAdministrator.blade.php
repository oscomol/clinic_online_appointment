<form action="{{route('adminRegister')}}" method="post" id="addAdminForm">
 
 <div class="modal fade" id="add-admin" tabindex="-1" role="dialog" aria-labelledby="modal-default-label" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-default-label">Add New Administrator</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                  
                <center class="d-none success">
                    <div class="mt-3">

                         <span id="success-status" class="text-success">
                             <li class="fas fa-check" style="font-size: 50px;"></li>    
                         </span>

                    </div>
                     <p class="mt-2">Email sent successfully</p>
                 </center>

                <center class="d-none unavailable">
                    <div class="mt-3">

                         <span id="success-status" class="text-danger">
                             <li class="fas fa-times" style="font-size: 50px;"></li>    
                         </span>

                    </div>
                     <p class="mt-2 errMess">Something went wrong</p>
                 </center>
                
                <div class="input-group mb-3 emailForm">
                    <input type="email" class="form-control" placeholder="Email" name="email">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-envelope"></span>
                        </div>
                    </div>
                </div>
                
                <input type="hidden" value="2" name="userType">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn btn-sm" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary btn btn-sm" id="addAdminBtn">
                    <span id="span">Save</span>
                </button>
            </div>
        </div>
    </div>
</div>

</form>