<div class="modal fade" id="add-admin-{{ $day }}" tabindex="-1" role="dialog" aria-labelledby="modal-default-label-{{ $day }}" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-default-label-{{ $day }}">{{$item ? 'Update':'Create'}} schedule for {{ $day }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            @if ($item)
            <div class="modal-body">
                <form method="POST" id="editDoctorSched-{{$day}}" class="editDoctorSched" day="{{$day}}">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="admin-name-{{ $day }}">Maximum patient</label>
                        <input type="number" class="form-control" id="edit-max-{{ $day }}" placeholder="Enter max patient" name="maxPatient" value="{{$item->maxPatient}}">
                    </div>
                    <div class="form-group">
                        <label for="admin-email-{{ $day }}">Alloted time (Per patient)</label>
                        <input type="number" class="form-control" id="edit-time-{{ $day }}" placeholder="Enter time in minutes" name="allotedTime" value="{{$item->allotedTime}}">
                    </div>
                    <input type="hidden" value="{{$item->id}}" name="id">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary btn-sm" id="editDrSched-{{ $day }}">Update</button>
            </div>
        </form>
            @else
            <div class="modal-body">
                <form method="POST" id="addDoctorSched-{{$day}}" class="addDoctorSched" day="{{$day}}">
                    @csrf
                    @method('POST')
                    <div class="form-group">
                        <label for="admin-name-{{ $day }}">Maximum patient</label>
                        <input type="number" class="form-control" id="max-{{ $day }}" placeholder="Enter max patient" name="maxPatient">
                    </div>
                    <div class="form-group">
                        <label for="admin-email-{{ $day }}">Alloted time (Per patient)</label>
                        <input type="number" class="form-control" id="time-{{ $day }}" placeholder="Enter time in minutes" name="allotedTime">
                    </div>

                    <input type="hidden" value="{{$doctor->id}}" name="doctorsId">
                    <input type="hidden" value="{{$day}}" name="day">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary btn-sm" id="drSched-{{ $day }}">Save</button>
            </div>
        </form>
            @endif
        </div>
    </div>
</div>