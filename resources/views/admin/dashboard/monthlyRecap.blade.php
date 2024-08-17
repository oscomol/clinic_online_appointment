<div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <h5 class="card-title">Monthly Recap Report</h5>
          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse">
              <i class="fas fa-minus"></i>
            </button>
            
            <button type="button" class="btn btn-tool" data-card-widget="remove">
              <i class="fas fa-times"></i>
            </button>
          </div>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
          <div class="row">
            <div class="col-md-8">
              <p class="text-center">
                <strong class="monthlyRecapTitle"></strong>
              </p>

              <div class="chart">
                <!-- Sales Chart Canvas -->
                <canvas id="salesChart" height="180" style="height: 180px;"></canvas>
              </div>
              <!-- /.chart-responsive -->
            </div>
            <!-- /.col -->
            <div class="col-md-4">
              <p class="text-center">
                <strong>Reservation Status Quick Check</strong>
              </p>

              <div class="progress-group">
                Reserved
                <span class="float-right reserverQC"><b></b><span></span></span>
                <div class="progress progress-sm">
                  <div class="progress-bar bg-primary reservePGBar"></div>
                </div>
              </div>
              <!-- /.progress-group -->

              <div class="progress-group">
                Cancelled
                <span class="float-right cancelled"><b></b><span></span></span>
                <div class="progress progress-sm">
                  <div class="progress-bar bg-danger cancelBar" style="width: 75%"></div>
                </div>
              </div>

              <!-- /.progress-group -->
              <div class="progress-group">
                <span class="progress-text">Undecided</span>
                <span class="float-right undecided"><b></b><span></span></span>
                <div class="progress progress-sm">
                  <div class="progress-bar bg-warning undecidedBar" style="width: 60%"></div>
                </div>
              </div>

              <!-- /.progress-group -->
              <div class="progress-group">
                Done
                <span class="float-right done"><b></b><span></span></span>
                <div class="progress progress-sm">
                  <div class="progress-bar bg-success doneBar" style="width: 50%"></div>
                </div>
              </div>
              <!-- /.progress-group -->
            </div>
            <!-- /.col -->
          </div>
          <!-- /.row -->
        </div>
        <!-- ./card-body -->
        <div class="card-footer">
          <div class="row topUserCont">
            
              <!-- /.description-block -->
            </div>
          </div>
          <!-- /.row -->
        </div>
        <!-- /.card-footer -->
      </div>
      <!-- /.card -->
    </div>
    <!-- /.col -->
  </div>