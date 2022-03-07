<div class="main-content">
        <!-- #section:basics/content.breadcrumbs -->
        <div class="breadcrumbs hidden-print" id="breadcrumbs">
          <script type="text/javascript">
            try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
          </script>

          <ul class="breadcrumb">
          
            <li>
              <a role="button" data-toggle="collapse" href="#changeDateRange" aria-expanded="false" aria-controls="changeDateRange">
                <span class="badge badge-info"><i class="glyphicon glyphicon-time"></i> Ganti Tanggal</span>
              </a>

              &nbsp;

              <a href="<?php echo Config::get('URL') . 'ExportExcel/allMaterial/'; ?>">
                <span class="badge badge-info">
                <i class="glyphicon glyphicon-arrow-down"></i> Export Excel
                </span>
              </a>

              &nbsp;

              <div class="btn-group btn-corner">
                <a href="#" onclick="showChart()" class="btn btn-minier btn-inverse">
                  <span class="glyphicon glyphicon glyphicon-signal" aria-hidden="true"></span> Show Charts
                </a>

                <a href="#" onclick="showRawData()" class="btn btn-minier btn-success">
                  <span class="glyphicon glyphicon glyphicon-list" aria-hidden="true"></span> Show Raw Data
                </a>
              </div>
            </li>
            
          
          </ul><!-- /.breadcrumb -->

          <!-- #section:basics/content.searchbox -->
          <div class="nav-search" id="nav-search">
            <form class="form-search">
              <span class="input-icon">
                <input type="text" placeholder="Search ..." class="nav-search-input" id="nav-search-input" autocomplete="off" />
                <i class="ace-icon fa fa-search nav-search-icon"></i>
              </span>
            </form>
          </div><!-- /.nav-search -->

          <!-- /section:basics/content.searchbox -->
        </div>

        <!-- /section:basics/content.breadcrumbs -->
        <div class="page-content">
          <div class="row">
            <div class="col-xs-12">
              <!-- PAGE CONTENT BEGINS -->

<div class="collapse" id="changeDateRange">
  <div class="well">
    <form method="post" action="<?php echo Config::get('URL') . 'dashboard/sales/'; ?>">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">Ganti Tanggal Report </h3>
                </div>
                <div class="panel-body">
                  <div class="row">

                      <div class="col-xs-12 col-sm-6">
                          <div class="form-group">
                              <label for="jumlah-pinjaman">Dari Tanggal/Start Date</label>
                              <input type="text" name="start_date" class="form-control datepicker" value="<?php echo date("Y-m-d");?>" data-date-format="yyyy-mm-dd">
                          </div>
                      </div><!-- /.col -->

                      <div class="col-xs-12 col-sm-6">
                          <div class="form-group">
                              <label for="jenis-pinjaman">Sampai Tanggal/End Date</label>
                              <input type="text" name="end_date" class="form-control datepicker" value="<?php echo date("Y-m-d");?>" data-date-format="yyyy-mm-dd">
                          </div>
                      </div><!-- /.col -->

                  </div><!-- /.row -->
                  <input type="hidden" name="change_date" value="ok">
            
                </div>
                <div class="panel-footer">
                    <p align="right">
                    <a role="button" class="btn btn-danger" data-toggle="collapse" href="#changeDateRange" aria-expanded="false" aria-controls="changeDateRange">
                            Cancel
                        </a>

                        &nbsp; &nbsp; &nbsp;

                        <button class="btn" type="reset">
                            Reset
                        </button>

                        &nbsp; &nbsp; &nbsp;
                        <button class="btn btn-primary" type="submit">
                            Change Date
                        </button>
                    </p>
                </div>
            </div>
</form>
  </div>
</div>


<?php include('sales_chart.php'); ?>
<?php include('sales_raw_data.php'); ?>

          <!-- PAGE CONTENT ENDS -->
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.page-content -->
  </div><!-- /.main-content-inner -->
</div><!-- /.main-content -->

<script type="text/javascript">
function showChart() {
  var dashboard_chart = document.getElementById('dashboard-chart');
  var dashboard_raw_data = document.getElementById('dashboard-raw-data');
  dashboard_chart.style.display = 'block';
  dashboard_raw_data.style.display = 'none';
}

function showRawData() {
  var dashboard_chart = document.getElementById('dashboard-chart');
  var dashboard_raw_data = document.getElementById('dashboard-raw-data');
  dashboard_chart.style.display = 'none';
  dashboard_raw_data.style.display = 'inline';
}
</script>