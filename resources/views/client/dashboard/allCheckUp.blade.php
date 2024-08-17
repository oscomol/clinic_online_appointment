<div class="card">
    <div class="card-header">
        <h3 class="card-title">Quick check</h3>
        <select class="float-right customSelect">
            <option value="1" selected>Scheduled</option>
            <option value="2">Cancelled</option>
            <option value="5">Done</option>
        </select>
    </div>
    <div class="card-body">
        <table class="table table-bordered table-striped d-none statusCont">
            <thead>
                <tr>
                    <th>Doctor</th>
                    <th>Patient</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Number</th>
                </tr>
            </thead>
            <tbody class="dashboardStatus">
                
            </tbody>
        </table>

    <center class="noStatus">
        <div class="mt-3">

             <span id="success-status" class="text-danger">
                 <li class="fas fa-times" style="font-size: 80px;"></li>    
             </span>

        </div>
         <p class="mt-2 errMess">Recent records not available</p>
     </center>
    </div>
</div>

<style>
       .customSelect{
        height: 30px;
        border-radius: 2px;
        margin-left: 10px;
        padding: 2px;
        width: 170px;
    }
</style>