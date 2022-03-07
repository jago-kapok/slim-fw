<!DOCTYPE html>
<html lang="en" >
<head>
  <meta charset="UTF-8">
  <title>
      <?php if (isset($this->title)) {
          echo $this->title;
      } else {
          echo Config::get('DEFAULT_TITLE');
      } ?>
  </title>
<style type="text/css">
*{
  margin: 0;
  padding: 0;
}
  /*  Code39Azalea Copyright 2012 Jerry Whiting (CC BY-ND 3.0) azalea.com/web-fonts/  */
  @font-face {
    font-family: Code39AzaleaFont;
    src: url('<?php echo Config::get('URL'); ?>bootstrap-3.3.7/fonts/Code39Azalea.woff') format('woff'), /* Modern Browsers */
    url('<?php echo Config::get('URL'); ?>bootstrap-3.3.7/fonts/Code39Azalea.ttf') format('truetype'); /* Safari, Android, iOS */
    font-weight: normal;
    font-style: normal;
  }
  body {
      background-color: #d7d6d3;
      font-family:'verdana';
  }
  .id-card-holder {
    width: 225px;
    padding: 4px;
    margin: 0 auto;
    background-color: #1f1f1f;
    border-radius: 5px;
    position: relative;
  }
  .id-card-holder:after {
    content: '';
    width: 7px;
    display: block;
    background-color: #0a0a0a;
    height: 100px;
    position: absolute;
    top: 105px;
    border-radius: 0 5px 5px 0;
  }
  .id-card-holder:before {
    content: '';
    width: 7px;
    display: block;
    background-color: #0a0a0a;
    height: 100px;
    position: absolute;
    top: 105px;
    left: 222px;
    border-radius: 5px 0 0 5px;
  }
  .id-card {
    background-color: #fff;
    padding: 10px;
    border-radius: 10px;
    text-align: center;
    box-shadow: 0 0 1.5px 0px #b9b9b9;
  }
  .id-card img {
    margin: 0 auto;
  }
  .header img {
    max-width: 85px;
    max-height: 85px;
    margin-top: 15px;
  }
  .photo img {
    width: 150px;
    margin-top: 15px;
  }
  h2 {
    font-size: 15px;
    margin: 5px 0;
  }
  h3 {
    font-size: 12px;
    margin: 2.5px 0;
    font-weight: 300;
  }
  .barcode {
    font-family: Code39AzaleaFont;
    font-size:40px;
    text-align: center;
  }
  .scan-barcode{
    font-family: Code39AzaleaFont;
    text-align: center;
    height: 30px;
    width: 100%;
    border: 0;
    padding: 0;
    margin: 0;
  }
  .line-space {
    margin: 10px 0;
  }
  p {
    font-size: 5px;
  }
  .id-card-hook {
    background-color: #000;
    width: 70px;
    margin: 0 auto;
    height: 15px;
    border-radius: 5px 5px 0 0;
  }
  .id-card-hook:after {
    content: '';
    background-color: #d7d6d3;
    width: 47px;
    height: 6px;
    display: block;
    margin: 0px auto;
    position: relative;
    top: 6px;
    border-radius: 4px;
  }
  .id-card-tag-strip {
    width: 45px;
    height: 40px;
    background-color: #0950ef;
    margin: 0 auto;
    border-radius: 5px;
    position: relative;
    top: 9px;
    z-index: 1;
    border: 1px solid #0041ad;
  }
  .id-card-tag-strip:after {
    content: '';
    display: block;
    width: 100%;
    height: 1px;
    background-color: #c1c1c1;
    position: relative;
    top: 10px;
  }
  .id-card-tag {
    width: 0;
    height: 0;
    border-left: 100px solid transparent;
    border-right: 100px solid transparent;
    border-top: 100px solid #0958db;
    margin: -10px auto -30px auto;
  }
  .id-card-tag:after {
    content: '';
    display: block;
    width: 0;
    height: 0;
    border-left: 50px solid transparent;
    border-right: 50px solid transparent;
    border-top: 50px solid #d7d6d3;
    margin: 40px auto -30px auto;
    position: relative;
    top: -130px;
    left: -50px;
  }
</style>

<?php
if (!empty(Session::get('uid'))) {
  $avatar = $avatar = Config::get('URL') . 'avatars/' . Session::get('uid') . '_medium.jpg';
} else {
  $avatar = Config::get('URL') . 'avatars/default_medium.jpg';
}

if (!empty(Session::get('full_name'))) {
  $nama = Session::get('full_name');
} else {
  $nama = 'Nama Pegawai';
}

if (!empty(Session::get('uid'))) {
  $uid = '0000' . Session::get('uid') . '0000';
} else {
  $uid = '0123456789';
}

?>
</head>

<body>
<form method="POST" action="<?php echo Config::get('URL') . 'attendance/scan'; ?>">
  <input class="scan-barcode" autofocus type="number" name="scan" placeholder="Scan Here" style="width: 100%;">
</form>
  <div class="id-card-tag"></div>
  <div class="id-card-tag-strip"></div>
  <div class="id-card-hook"></div>
  <div class="id-card-holder">
    <div class="id-card">
      <div class="header">
        <img src="<?php echo Config::get('URL'); ?>file/company/logo.png">
      </div>
      <div class="photo">
        <img src="<?php echo $avatar; ?>">
      </div>
      <h2><?php echo $nama; ?></h2>
      <div class="barcode">
        *<?php echo $uid; ?>*
      </div>
      <hr class="line-space">
      <p>Jln. Batujajar, Penanggungan, Klojen, Kota Malang.<br>Jawa Timur. 65113, Indonesia</p>
    </div>
  </div>
  
  
</body>
</html>
