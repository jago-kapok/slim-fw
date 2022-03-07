<div class="main-content">
 <h1 class="hero-header text-center row-bordered">Daftar pegawai baru</h1>
    <!-- echo out the system feedback (error and success messages) -->
    <?php $this->renderFeedbackMessages(); ?>

    <form class="form-horizontal" method="post" action="<?php echo Config::get('URL'); ?>register/registerNewUser/">

  <div class="form-group">
    <label for="user_name" class="col-sm-2 control-label">Pilih Username</label>
    <div class="col-sm-10">
      <input class="form-control" id="user_name" type="text" name="user_name" placeholder="huruf/angka tanpa spasi, panjang 2-64 karakter)" required>
    </div>
  </div>

  <div class="form-group">
    <label for="user_name" class="col-sm-2 control-label">Nama Lengkap</label>
    <div class="col-sm-10">
      <input class="form-control" id="user_name" type="text" name="full_name" placeholder="huruf/angka tanpa spasi, panjang 2-64 karakter)" required>
    </div>
  </div>

  <div class="form-group">
    <label for="contact" class="col-sm-2 control-label">Email</label>
    <div class="col-sm-10">
      <input class="form-control" id="contact" type="text" name="email" placeholder="Email" required>
    </div>
  </div>

  <div class="form-group">
    <label for="contact_repeat" class="col-sm-2 control-label">Nomor Telepon</label>
    <div class="col-sm-10">
      <input class="form-control" id="contact_repeat" type="text" name="phone" placeholder="Phone" required>
    </div>
  </div>

  <div class="form-group">
    <label for="user_password_new" class="col-sm-2 control-label">Password</label>
    <div class="col-sm-10">
      <input class="form-control" id="user_password_new" type="password" name="user_password_new" pattern=".{6,}" placeholder="Minimal 6 karakter" required autocomplete="off">
    </div>
  </div>

  <div class="form-group">
    <label for="user_password_repeat" class="col-sm-2 control-label">Password (Ulangi Lagi)</label>
    <div class="col-sm-10">
      <input class="form-control" id="user_password_repeat" type="password" name="user_password_repeat" pattern=".{6,}" required placeholder="Ulangi lagi untuk memastikan tidak salah ketik" autocomplete="off" >
    </div>
  </div>
  

  <div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
      <button type="submit" class="btn btn-default">Daftar</button>
    </div>
  </div>
</form>
</div>