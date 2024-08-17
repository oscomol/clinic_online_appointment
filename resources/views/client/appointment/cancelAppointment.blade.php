<form class="cancelReservation" method="POST" action="/clinic/client-appointment/cancel">
    @csrf
    @method('delete')
    <div class="modal fade" id="cancel-reservation" tabindex="-1" role="dialog" aria-labelledby="modal-default-label" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-default-label">Confirm cancel</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
 
                <p>Are you sure to cancel reservation ?</p>
                <input type="hidden" id="reservationId">
 
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-danger btn btn-sm" id="cancel-reservation-btn">
                        Yes
                    </button>
                    <button type="button" class="btn btn-secondary btn btn-sm" data-dismiss="modal">No</button>
                </div>
            </div>
        </div>
    </div>
</form>