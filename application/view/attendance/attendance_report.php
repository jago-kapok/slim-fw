<div class="main-content">
        <!-- #section:basics/content.breadcrumbs -->
        <div class="breadcrumbs" id="breadcrumbs">
          <script type="text/javascript">
            try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
          </script>
          <ul class="breadcrumb">
          <li>
              <a role="button" data-toggle="collapse" href="#changeDateRange" aria-expanded="false" aria-controls="changeDateRange">
                <span class="badge badge-info">Ganti Bulan</span>
              </a>
            </li>
          </ul><!-- /.breadcrumb -->

          <div class="nav-search" id="nav-search">
              <form class="form-search" method="get" action="<?php echo Config::get('URL') . 'employee/index/';?>">
                <span class="input-icon">
                  <input type="text" placeholder="Search ..." class="nav-search-input" id="nav-search-input" autocomplete="off" name="find" value="<?php if(isset($_GET['find'])){ echo $_GET['find'];}?>" />
                  <i class="ace-icon fa fa-search nav-search-icon"></i>
                </span>
              </form>
          </div><!-- /.nav-search -->
        </div>

<?php $this->renderFeedbackMessages(); ?>

<div class="collapse" id="changeDateRange">
  <form method="post" action="<?php echo Config::get('URL') . 'attendance/attendanceReport/'; ?>">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">Ganti Bulan Report </h3>
                </div>

                <div class="panel-body">
                          <div class="form-group">
                              <label for="jumlah-pinjaman">Pilih Bulan</label>
                              <input type="text" name="date" class="form-control datepicker" data-date-format="yyyy-mm-dd" placeholder="pilih bulannya saja, abaikan tanggal">
                          </div>
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

          <div class="table-responsive">
          <table class="table table-striped table-bordered table-hover ExcelTable2007">
          <thead>
          <tr>
            <th class="text-center">No</th>
            <th class="text-center">Tanggal</th>
            <th class="text-center">Jam Masuk</th>
            <th class="text-center">Jam Keluar</th>
          </tr>
          </thead>

          <tbody>
          <?php
          $no = 1;
          $total_uang_transport = 0;
          $total_denda = 0;
          foreach($this->data as $key => $value) {
            //check telat apa tidak
            $extracted_time = date('H:i:s', strtotime($value->jam_datang));
            if (strtotime($extracted_time) > strtotime('09:00:00')) {
              $fine = $this->attendance_late_fine->value; //kena denda telat
              $status = 'danger'; //kena denda telat
            } else {
              $fine = 0; //tidak kena denda cui
              $status = ''; //kena denda telat
            }
            echo "<tr class='{$status}'>";
            echo '<td class="text-right">' . $no . '</td>';
            echo '<td>' . date('d M', strtotime($value->by_date)) . '</td>';
            echo '<td>' . date('H:i', strtotime($value->jam_datang)) . '</td>';
            echo '<td>' . date('H:i', strtotime($value->jam_pulang)) . '</td>';
            echo "</tr>";
            $no++;
          }
          ?>
          </tbody>
          </table>
          </div><!-- /.table-responsive -->

      <!-- PAGE CONTENT ENDS -->
</div><!-- /.main-content -->