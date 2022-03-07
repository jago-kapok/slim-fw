<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="<?php echo Config::get('URL'); ?>asset/icon/favicon.ico">

     <title>
            <?php if (isset($this->title)) {
                echo $this->title;
            } else {
                echo "Mini Bank";
            } ?>
        </title>
    <!-- CSS -->
    <link rel="stylesheet" href="<?php echo Config::get('URL'); ?>asset/css/bootstrap.min.css" media="all"/>
        <!-- Custom styles for this template -->
    <link rel="stylesheet" href="<?php echo Config::get('URL'); ?>asset/css/offcanvas.css" media="screen">
    <link rel="stylesheet" href="<?php echo Config::get('URL'); ?>asset/css/datepicker.css" media="screen"/>
    <link rel="stylesheet" href="<?php echo Config::get('URL'); ?>asset/css/print.css" media="print"/>
    <?php if (isset($this->head_addon)) { echo $this->head_addon; } ?>
  </head>
<body>
<section class="row-offcanvas row-offcanvas-right">