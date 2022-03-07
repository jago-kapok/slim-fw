<div class="main-content">
    <div class="main-content-inner">
        <!-- #section:basics/content.breadcrumbs -->
        <div class="breadcrumbs" id="breadcrumbs">
          <script type="text/javascript">
            try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
          </script>
          <ul class="breadcrumb">
            <li>
                <div class="btn-group btn-corner">
                    <a role="button" data-toggle="collapse" href="#changeMonth" aria-expanded="false" aria-controls="changeMonth" class="btn btn-minier btn-inverse"><span class="glyphicon glyphicon-calendar" aria-hidden="true"></span> Ganti Bulan</a>

                    <a role="button" data-toggle="collapse" href="#benefit" aria-expanded="false" aria-controls="benefit" class="btn btn-minier btn-info"><span class="glyphicon glyphicon-usd" aria-hidden="true"></span> Benefit</a>

                    <a onclick="printPage('<?php echo Config::get('URL') . 'employee/printSalarySlip/' . $this->report_month . '/' . $this->user_id;?>');" class="btn btn-minier btn-success"><span class="glyphicon glyphicon-print" aria-hidden="true"></span> Print</a>
                  </div>
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

<div class="collapse" id="changeMonth">
  <form class="form-horizontal" method="post" action="<?php echo Config::get('URL') . 'employee/attendanceReport/'; ?>">
    <br>
    <div class="form-group">
      <label class="col-sm-2 control-label">Pilih Bulan</label>
      <div class="col-sm-8">
        <input type="text" name="date" class="form-control datepicker" data-date-format="yyyy-mm-dd" placeholder="pilih bulannya saja, abaikan tanggal">
      </div>
    </div>
    <input type="hidden" name="change_date" value="ok">
    <input type="hidden" name="user_id" value="<?php echo $this->user_id; ?>">
    <div class="form-group">
      <div class="col-sm-offset-2 col-sm-10">
        <button role="button" class="btn btn-danger" data-toggle="collapse" href="#changeMonth" aria-expanded="false" aria-controls="changeMonth">Cancel</button>
        &nbsp; &nbsp; &nbsp;
        <button type="submit" class="btn btn-primary">Ganti</button>
      </div>
    </div>
  </form>
</div>

<div class="collapse" id="benefit">
  <form class="form-horizontal" method="post" action="<?php echo Config::get('URL') . 'employee/salaryBenefit/'; ?>">
    <br>
    <div class="row">
        <div class="col-sm-offset-1 col-sm-10">
            <div class="panel panel-default">
              <div class="panel-heading">
                <h3 class="panel-title">Masukkan Bonus dan Denda Tambahan</h3>
              </div>
                <table class="table table-striped table-bordered table-hover">
                  <thead>
                  <tr>
                    <th class="text-center">No</th>
                    <th class="text-center">Nama Items</th>
                    <th class="text-center">Value (+/-)</th>
                  </tr>
                  </thead>
                  <tbody>
                      <?php
                for ($i=1; $i <=10; $i++) { ?>
<tr>
  <td align="left" valign="bottom" class="heading"><?php echo $i; ?></td>
  <td><input class="form-control" name="benefit_name_<?php echo $i; ?>" style="width: 100%;"></td>
  <td><input class="form-control" name="benefit_value_<?php echo $i; ?>" style="width: 100%;"></td>
</tr>
<?php } ?>
<tr style="display: none">
  <td style="display: none"><input name="benefit_date" value="<?php echo $this->report_month; ?>"></td>
  <td style="display: none"><input name="user_id" value="<?php echo $this->user_id; ?>"></td>
</tr>
<tr>
  <td align="left" valign="bottom" class="heading"></td>
  <td><button role="button" class="btn btn-danger" data-toggle="collapse" href="#benefit" aria-expanded="false" aria-controls="benefit" style="width: 100%;">Cancel</button></td>
  <td><button type="submit" class="btn btn-primary" style="width: 100%;">Save</button></td>
</tr>
                  </tbody>
                </table>
            </div>
        </div><!-- /.col -->
      </div><!-- /.row -->
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
            <?php if ($this->employee->grade > 100) { ?>
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
          $late_days = 0;
          //hari terakhir kerja bulan laporan, gunanya untuk mengetahui apakah sudah lewat hari terakhit kerja bulan ini, nanti digunakan untuk memeberi bonus bulanan jika tidak pernah telat sama sekali selama bulan ini
          
          foreach($this->attendance as $key => $value) {
            //check telat apa tidak
            $extracted_time = date('H:i:s', strtotime($value->jam_datang));
            if (strtotime($extracted_time) > strtotime('09:00:00')) {
              $fine = $this->attendance_late_fine->value; //kena denda telat
              $status = 'danger'; //kena denda telat
              $late_days++;
            } else {
              $fine = 0; //tidak kena denda cui
              $status = ''; //tidak kena denda telat
            }

            echo "<tr class='{$status}'>";
            echo '<td class="text-right">' . $no . '</td>';
            echo '<td>' . date('d M', strtotime($value->by_date)) . '</td>';
            echo '<td>' . date('H:i', strtotime($value->jam_datang)) . '</td>';
            echo '<td>' . date('H:i', strtotime($value->jam_pulang)) . '</td>';
            if ($this->employee->grade > 100) {
              echo '<td class="text-right">' . number_format($this->uang_transport->value)  . '</td>';
              echo '<td class="text-right">' . number_format($fine) . '</td>';
            }
            echo "</tr>";
            $no++;
            $total_uang_transport = $total_uang_transport + $this->uang_transport->value;
            $total_denda = $total_denda + $fine;

            //untuk cek dan memberi status apakah laporan absen sudah lebih dari jumat terakhit bulan laporan
            if ($value->by_date >= $this->last_business_day) {
                $last_business_day = 'finish';
            } else {
                $last_business_day = 'in progress';
            }
          }
          ?>
          <?php if ($this->employee->grade > 100) { ?>
          <tr class="active">
              <td colspan="4" class="text-center">Total</td>
              <td class="text-right"><?php echo number_format($total_uang_transport); ?></td>
              <td class="text-right"><?php echo number_format($total_denda); ?></td>
          </tr>
          <tr><td colspan="6">&nbsp;</td></tr>
          <tr><td class="heading text-center" colspan="6">SLIP GAJI</td></tr>
          
          <tr class="active">
              <td colspan="4">Gaji Pokok</td>
              <td class="text-right"><?php echo number_format($this->employee->salary); ?></td>
              <td></td>
          </tr>
          <tr class="warning">
              <td colspan="4">Total Uang Transport</td>
              <td class="text-right"><?php echo number_format($total_uang_transport); ?></td>
              <td></td>
          </tr>
          <tr class="danger">
              <td colspan="4">Total Denda Keterlambatan</td>
              <td class="text-right">-<?php echo number_format($total_denda); ?></td>
              <td></td>
          </tr>
          <?php
          if ($late_days == 0 AND $last_business_day == 'finish') {
            $never_late_reward = $this->never_late_per_month_reward->value;
          } else {
            $never_late_reward = 0;
          }
          ?>
          <tr class="success">
              <td colspan="4">Bonus Tidak Pernah Telat Dalam Sebulan</td>
              <td class="text-right"><?php echo number_format($never_late_reward); ?></td>
              <td></td>
          </tr>
          <?php
          $take_home_pay = $this->employee->salary + $total_uang_transport - $total_denda + $never_late_reward;
          foreach($this->salary_benefit as $key => $value) {
              if ($value->benefit_value <= 0) {
                $status = 'danger'; //kena denda telat
              } else {
                $status = 'success'; //kena denda telat
              }
              echo "<tr class='{$status}'>";
              echo '<td colspan="4">' . $value->benefit_name . '</td>';
              echo '<td class="text-right">' . number_format($value->benefit_value) . '</td>';
              echo '<td class="text-right"><a href="'. Config::get('URL') . 'delete/remove/users_benefit/uid/' . $value->uid . '/?forward=' . $_SERVER['REQUEST_URI'] . '" onclick="return confirmation(\'Are you sure to delete?\');"><span class="badge badge-danger">delete</span></a></td>';
              echo "</tr>";
              $take_home_pay = $take_home_pay + $value->benefit_value;
          }
          ?>
          <tr class="info">
              <td colspan="4"><strong>Take Home Pay</strong></td>
              <td class="text-right">
                <?php
                
                echo number_format($take_home_pay);
                ?>
              </td>
              <td></td>
          </tr>
          <?php } ?>
          </tbody>
          </table>
          </div><!-- /.table-responsive -->

      <!-- PAGE CONTENT ENDS -->
</div> <!-- /.main-content-inner -->
</div><!-- /.main-content -->
<script type="text/javascript">
// FUNCTION FOR PRINT
function closePrint () {
  document.body.removeChild(this.__container__);
}

function setPrint () {
  this.contentWindow.__container__ = this;
  this.contentWindow.onbeforeunload = closePrint;
  this.contentWindow.onafterprint = closePrint;
  this.contentWindow.focus(); // Required for IE
  this.contentWindow.print();
}

function printPage (sURL) {
  var oHiddFrame = document.createElement("iframe");
  oHiddFrame.onload = setPrint;
  oHiddFrame.style.visibility = "hidden";
  oHiddFrame.style.position = "fixed";
  oHiddFrame.style.right = "0";
  oHiddFrame.style.bottom = "0";
  oHiddFrame.src = sURL;
  document.body.appendChild(oHiddFrame);
}
// END FUNCTION FOR PRINT

</script>