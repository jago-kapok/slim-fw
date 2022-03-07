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
                  </div>
            </li>
          </ul><!-- /.breadcrumb -->
        </div>

<?php $this->renderFeedbackMessages(); ?>

<div class="collapse" id="changeMonth">
  <form class="form-horizontal" method="post" action="<?php echo Config::get('URL') . 'bukuBesar/salaryReport/'; ?>">
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

          <div class="table-responsive">
          <table class="table table-striped table-bordered table-hover ExcelTable2007">
          <thead>
          <tr>
            <th class="text-center">No</th>
            <th class="text-center">Nama Pegawai</th>
            <th class="text-center">Take Home Pay</th>
          </tr>
          </thead>

          <tbody>
          <?php
          $no = 1;
          $total_uang_gaji = 0;
          //hari terakhir kerja bulan laporan, gunanya untuk mengetahui apakah sudah lewat hari terakhit kerja bulan ini, nanti digunakan untuk memeberi bonus bulanan jika tidak pernah telat sama sekali selama bulan ini
          
          foreach($this->attendance as $key => $value) {
            echo "<tr>";
            echo '<td class="text-right">' . $no . '</td>';
            echo '<td>' . $value->transaction_name . '</td>';
            echo '<td>' . $value->credit . '</td>';
            echo "</tr>";
            $no++;
            $total_uang_gaji = $total_uang_gaji + $this->transaction->credit;
          }
          ?>
          <tr class="info">
              <td colspan="2"><strong>Take Home Pay</strong></td>
              <td class="text-right">
                <?php
                
                echo number_format($total_uang_gaji);
                ?>
              </td>
              <td></td>
          </tr>
          </tbody>
          </table>
          </div><!-- /.table-responsive -->

      <!-- PAGE CONTENT ENDS -->
</div> <!-- /.main-content-inner -->
</div><!-- /.main-content -->