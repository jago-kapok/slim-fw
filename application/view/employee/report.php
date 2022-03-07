<div class="main-content">
        <!-- #section:basics/content.breadcrumbs -->
        <div class="breadcrumbs" id="breadcrumbs">
          <script type="text/javascript">
            try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
          </script>
          <ul class="breadcrumb">
          <li>
              <a role="button" data-toggle="collapse" href="#changeDateRange" aria-expanded="false" aria-controls="changeDateRange">
                <span class="label label-sm label-primary arrowed arrowed-right">Ganti Bulan</span>
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
  <form method="post" action="<?php echo Config::get('URL') . 'employee/attendanceReport/'; ?>">
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
                  <input type="hidden" name="user_id" value="<?php echo $this->user_id; ?>">
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
            <?php if (Session::get('grade') > 100) { ?>
              <th class="text-center">Uang Transport</th>
              <th class="text-center">Denda Telat</th>
            <?php } ?>
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
          if (strtotime($extracted_time) > strtotime('08:00:00')) {
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
          if (Session::get('grade') > 100) {
            echo '<td class="text-right">' . number_format($this->uang_transport->value)  . '</td>';
            echo '<td class="text-right">' . number_format($fine) . '</td>';
          }
          echo "</tr>";
          $no++;
          $total_uang_transport = $total_uang_transport + $this->uang_transport->value;
          $total_denda = $total_denda + $fine;
          }
          ?>
          <?php if (Session::get('grade') > 100) { ?>
          <tr class="active">
              <td colspan="4" class="text-center">Total</td>
              <td class="text-right"><?php echo number_format($total_uang_transport); ?></td>
              <td class="text-right"><?php echo number_format($total_denda); ?></td>
          </tr>
          <tr>
              <td colspan="6">&nbsp;</td>

          <tr class="active">
              <td colspan="5">Gaji Pokok</td>
              <td class="text-right"><?php echo number_format($this->gaji_pokok->salary); ?></td>
          </tr>
          <tr class="warning">
              <td colspan="5">Total Uang Transport</td>
              <td class="text-right"><?php echo number_format($total_uang_transport); ?></td>
          </tr>
          <tr class="success">
              <td colspan="5">Total Denda</td>
              <td class="text-right"><?php echo number_format($total_denda); ?></td>
          </tr>
          <tr class="info">
              <td colspan="5"><strong>Take Home Pay</strong></td>
              <td class="text-right">
                <?php
                $take_home_pay = $this->gaji_pokok->salary + $total_uang_transport - $total_denda;
                echo number_format($take_home_pay);
                ?>
              </td>
          </tr>
          <?php } ?>
          </tbody>
          </table>
          </div><!-- /.table-responsive -->

      <!-- PAGE CONTENT ENDS -->
</div><!-- /.main-content -->