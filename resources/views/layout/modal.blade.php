<form action="{{route('updateAccount')}}" method="POST" id="accountUpdateForm">
    @csrf
    @method('put')
    <div class="modal fade" id="updateModal" tabindex="-1" role="dialog" aria-labelledby="modal-default-p-update" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-default-p-update">Update</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="exampleFormControlInput1">Enter email here to link</label>
                        <input type="email" class="form-control" id="exampleFormControlInput1" required>
                    </div>
                    <input type="hidden" id="id" value="{{$user->id}}">
                    <input type="hidden" id="userType" value="{{$user->userType}}">
                    <p class="msg d-none" style="margin-top: -15px;"></p>
                </div>
    
                <div class="modal-footer">
                    <button type="submit"class="getLink btn btn-success btn-sm">
                        Get link
                    </button>
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">
                        Cancel
                    </button>
                </div>

            </div>
        </div>
    </div>
</form>




<form action="{{ route('deleteAccount', ['user' => $user->id]) }}" method="POST">
    @csrf
    @method('delete')

    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="modal-default-p-delete" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-default-p-delete">Delete Account</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this account?
                </div>
                <div class="modal-footer">


                    <button type="submit" class="btn btn-danger btn-sm">
                        Yes
                    </button>
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">
                        No
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>



<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="modal-default-p-logout" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-default-p-logout">Confirm</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure to logout ?</p>
            </div>

            <div class="modal-footer">
                <a href="{{route('logout')}}" class="btn btn-danger btn-sm">
                    Yes
                </a>
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">
                    No
                </button>
            </div>
        </div>
    </div>
</div>

