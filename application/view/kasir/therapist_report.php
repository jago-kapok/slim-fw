<div class="main-content">
        <div class="main-content-inner">
<?php $this->renderFeedbackMessages(); ?>
          <div class="table-responsive">
          <table class="table table-striped table-bordered table-hover ExcelTable2007">
          <thead>
          <tr>
          <th class="text-center">No</th>
          <th class="text-center">ID</th>
          <th class="text-center">Nama</th>
          <th class="text-center">Jam Absen</th>
          <th class="text-center">Status</th>
          </tr>
          </thead>

          <tbody>
          <?php
          $no = 1;
          foreach($this->contact as $key => $value) {
          echo "<tr>";
          if (SESSION::get('grade') >= 900) {
            echo '<td class="heading">
          <div class="dropdown">
            <button class="btn btn-info btn-minier dropdown-toggle" type="button" id="cpMenu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              ' . $no . '
              <span class="caret"></span>
            </button>
            <ul class="dropdown-menu" aria-labelledby="cpMenu">
              <li><a href="'. Config::get('URL') . 'kasir/updateTherapistAvailability/' . $value->uid . '/1">Ubah Ke Available</a></li>
              <li role="separator" class="divider"></li>
              <li><a href="'. Config::get('URL') . 'kasir/updateTherapistAvailability/' . $value->uid . '/0">Ubah Ke Not Available</a></li>

            </ul>
          </div>

        </td>';
          } else { 
          echo '<td class="text-right">' . $no . '</td>';
          }
          echo '<td><a href="' . Config::get('URL') . 'employee/detail/' . $value->user_name . '/">' . ucwords($value->uid) . '</a></td>';
          echo '<td>' . ucwords($value->full_name) . '</td>';
          $datetime = explode(" ", $value->created_timestamp);
          echo '<td>' . $datetime[1] . '</td>';
          if ($value->is_active == 0) {
            echo '<td>Not Available</td>';
          } else {
            echo '<td>Available</td>';
          }
          echo "</tr>";
          $no++;
          }
          ?>
          <tr style="height: 100px;">
            <td colspan="5">&nbsp;</td>
          </tr>
         
          </tbody>
          </table>
          </div><!-- /.table-responsive -->
        </div><!-- /.main-content-inner -->
      </div><!-- /.main-content -->

      <!-- NEW Nasabah-->
<div class="modal fade" id="new-contact" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
    <form method="post" class="form-horizontal" action="<?php echo Config::get('URL') . 'employee/registerNewEmployee/';?>">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Buat Pegawai Baru</h4>
      </div>
      <div class="modal-body">
          <div class="form-group">
            <label class="col-sm-3 control-label">Nama Lengkap</label>
            <div class="col-sm-9">
              <input type="text" name="full_name" class="form-control" placeholder="nama pegawai">
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-3 control-label">Username</label>
            <div class="col-sm-9">
              <input type="text" name="user_name" class="form-control" placeholder="Hanya huruf dan angka tanpa spasi.">
              <span id="helpBlock" class="help-block">Jika dikosongi username akan dibuat otomatis oleh sistem</span>
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-3 control-label">Email</label>
            <div class="col-sm-9">
              <input type="text" name="email" class="form-control" placeholder="alamat email">
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-3 control-label">Phone</label>
            <div class="col-sm-9">
              <input type="text" name="phone" class="form-control" placeholder="nomer handphone">
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-3 control-label">Password</label>
            <div class="col-sm-9">
              <input type="text" name="user_password_new" class="form-control" placeholder="Ketik password, minimal 8 karakter">
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-3 control-label">Ulang Password</label>
            <div class="col-sm-9">
              <input type="text" name="user_password_repeat" class="form-control" placeholder="Ketik ulang password, agar tidak ada salah ketik">
            </div>
          </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-primary">Simpan</button>
      </div>
      </form>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- /NEW CONTACT-->