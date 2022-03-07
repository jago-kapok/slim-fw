<!doctype html>

<html lang="en-150">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <title>
        <?php if (isset($this->title)) {
            echo $this->title;
        } else {
            echo Config::get('DEFAULT_TITLE');
        } ?>
    </title>

    <meta name="description" content="" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

    <!-- bootstrap & fontawesome -->
    <link rel="stylesheet" href="<?php echo Config::get('URL'); ?>bootstrap-3.3.7/css/bootstrap.custom.css" />
    <link rel="stylesheet" href="<?php echo Config::get('URL'); ?>bootstrap-3.3.7/font-awesome/4.5.0/css/font-awesome.min.css" />

    <!-- page specific plugin styles -->

    <!-- text fonts -->
    <link rel="stylesheet" href="<?php echo Config::get('URL'); ?>bootstrap-3.3.7/css/fonts.googleapis.com.css" />

    <!-- ace styles -->
    <link rel="stylesheet" href="<?php echo Config::get('URL'); ?>bootstrap-3.3.7/css/ace.css" class="ace-main-stylesheet" id="main-ace-style" />


    <!-- inline styles related to this page -->

    <!-- ace settings handler -->
    <script src="<?php echo Config::get('URL'); ?>bootstrap-3.3.7/js/ace-extra.min.js"></script>


    <?php if (isset($this->header_script)) {echo $this->header_script;} ?>
</head>

<body class="no-skin">
    <div id="navbar" class="navbar navbar-default          ace-save-state">
        <div class="navbar-container ace-save-state" id="navbar-container">
            <button type="button" class="navbar-toggle menu-toggler pull-left" id="menu-toggler" data-target="#sidebar">
                <span class="sr-only">Toggle sidebar</span>

                <span class="icon-bar"></span>

                <span class="icon-bar"></span>

                <span class="icon-bar"></span>
            </button>

            <div class="navbar-header pull-left">
                <span class="navbar-brand">
                    <small>
                        SOMAX
                    </small>
                </span>
            </div>

            <div class="navbar-buttons navbar-header pull-right" role="navigation">
                <ul class="nav ace-nav">

                    <li class="light-blue">
                        <a data-toggle="dropdown" href="#" class="dropdown-toggle">
                            <img class="nav-user-photo" src='<?php echo Session::get('user_avatar_file'); ?>' alt="<?php echo Session::get('user_name'); ?>'s Photo"/>
                            <span class="user-info">
                                <small>Salam!,</small>
                                <?php echo Session::get('user_name'); ?>
                            </span>

                            <i class="ace-icon fa fa-caret-down"></i>
                        </a>

                        <ul class="user-menu dropdown-menu-right dropdown-menu dropdown-yellow dropdown-caret dropdown-close">
                            <?php if (Session::userIsLoggedIn()) { ?>
                                <li>
                                    <a href="<?php echo Config::get('URL') . 'employee/detail/' .  Session::get('user_name'); ?>"><i class="ace-icon fa fa-user"></i>My profile</a>
                                </li>
                                <li class="divider"></li>
                                <li>
                                    <a href="<?php echo Config::get('URL'); ?>user/editAvatar"><i class="ace-icon fa fa-camera-retro"></i>Ganti Foto</a>
                                </li>
                                <li>
                                    <a href="<?php echo Config::get('URL'); ?>user/editusername"><i class="ace-icon fa fa-key"></i>Edit username</a>
                                </li>
                                <li>
                                    <a href="<?php echo Config::get('URL'); ?>user/changePassword"><i class="ace-icon fa fa-key"></i>Edit Password</a>
                                </li>

                                <li role="separator" class="divider"></li>

                                <li >
                                    <a href="<?php echo Config::get('URL'); ?>login/logout"><i class="ace-icon fa fa-off"></i>Logout</a>
                                </li>

                                <?php if (Session::get("user_account_type") == 7) { ?>
                                    <li role="separator" class="divider"></li>
                                    <li class="dropdown-header"><i class="ace-icon fa fa-gavel">SUPER USER</li>
                                        <li <?php if (View::checkForActiveController($filename, "admin")) {
                                            echo ' class="active" ';
                                        } ?> >
                                        <a href="<?php echo Config::get('URL'); ?>admin/"><i class="ace-icon fa fa-certificate">Admin</a>
                                        </li>
                                        <li >
                                            <a href="<?php echo Config::get('URL'); ?>user/changeUserRole"><i class="ace-icon fa fa-unlock">Change account type</a>
                                            </li>
                                            <?php }; ?>


                                            <?php } else { ?>
                                            <!-- for not logged in users -->
                                            <li <?php if (View::checkForActiveControllerAndAction($filename, "login/index")) { echo ' class="active" '; } ?> >
                                                <a href="<?php echo Config::get('URL'); ?>login/index">Masuk</a>
                                            </li>
                                            <li <?php if (View::checkForActiveControllerAndAction($filename, "register/index")) { echo ' class="active" '; } ?> >
                                                <a href="<?php echo Config::get('URL'); ?>register/index">Daftar</a>
                                            </li>
                                            <?php } ?>
                                        </ul>
                                    </li>
                                </ul>
                            </div>
                        </div><!-- /.navbar-container -->
                    </div>

                    <div class="main-container ace-save-state" id="main-container">
                        <script type="text/javascript">
                            try{ace.settings.loadState('main-container')}catch(e){}
                        </script>

                        <div id="sidebar" class="sidebar responsive ace-save-state">
                            <script type="text/javascript">
                                try{ace.settings.loadState('sidebar')}catch(e){}
                            </script>

                            <div class="sidebar-shortcuts" id="sidebar-shortcuts">
                                <div class="sidebar-shortcuts-large" id="sidebar-shortcuts-large">
                                    <button class="btn btn-success">
                                        <i class="ace-icon fa fa-signal"></i>
                                    </button>

                                    <button class="btn btn-info">
                                        <i class="ace-icon fa fa-pencil"></i>
                                    </button>

                                    <button class="btn btn-warning">
                                        <i class="ace-icon fa fa-users"></i>
                                    </button>

                                    <button class="btn btn-danger">
                                        <i class="ace-icon fa fa-cogs"></i>
                                    </button>
                                </div>

                                <div class="sidebar-shortcuts-mini" id="sidebar-shortcuts-mini">
                                    <span class="btn btn-success"></span>

                                    <span class="btn btn-info"></span>

                                    <span class="btn btn-warning"></span>

                                    <span class="btn btn-danger"></span>
                                </div>
                            </div><!-- /.sidebar-shortcuts -->

                            <ul class="nav nav-list">

                                <li <?php if (isset($this->activelink1)) { if ($this->activelink1 == 'contact') {echo ' class="active open" ';}} ?> >
                                    <a href="<?php echo Config::get('URL'); ?>contact/">
                                        <i class="menu-icon glyphicon glyphicon-book"></i>
                                        <span class="menu-text"> Kontak </span>
                                    </a>
                                </li>

                                <li <?php if (isset($this->activelink1)) { if ($this->activelink1 == 'supply chain') {echo ' class="active open" ';}} ?> >
                                    <a href="#" class="dropdown-toggle">
                                        <i class="menu-icon fa fa-briefcase"></i>
                                        <span class="menu-text">
                                            Pembelian
                                        </span>

                                        <b class="arrow fa fa-angle-down"></b>
                                    </a>

                                    <b class="arrow"></b>

                                    <ul class="submenu">
                                        <li <?php if (isset($this->activelink2)) { if ($this->activelink2 == 'pr/po') {echo ' class="active open" ';}} ?> >
                                            <a href="#" class="dropdown-toggle">
                                                <i class="menu-icon fa fa-caret-right"></i>

                                                Purchase Order
                                                <b class="arrow fa fa-angle-down"></b>
                                            </a>

                                            <b class="arrow"></b>

                                            <ul class="submenu">
                                                <li <?php if (isset($this->activelink3)) { if ($this->activelink3 == 'purchase request') {echo ' class="active open" ';}} ?> >
                                                    <a href="#" class="dropdown-toggle">
                                                        <i class="menu-icon fa fa-shopping-basket green"></i>

                                                        PR
                                                        <b class="arrow fa fa-angle-down"></b>
                                                    </a>

                                                    <b class="arrow"></b>

                                                    <ul class="submenu">
                                                        <li <?php if (isset($this->activelink4)) { if ($this->activelink4 == 'waiting request') {echo ' class="active" ';}} ?> >
                                                            <a href="<?php echo Config::get('URL') . 'pr/pr'; ?>">
                                                                <i class="menu-icon glyphicon glyphicon-hourglass green"></i>
                                                                Waiting
                                                            </a>

                                                            <b class="arrow"></b>
                                                        </li>

                                                        <li <?php if (isset($this->activelink4)) { if ($this->activelink4 == 'processed request') {echo ' class="active" ';}} ?> >
                                                            <a href="<?php echo Config::get('URL') . 'pr/processed'; ?>">
                                                                <i class="menu-icon glyphicon glyphicon-ok green"></i>
                                                                Processed
                                                            </a>

                                                            <b class="arrow"></b>
                                                        </li>
                                                    </ul>
                                                </li>

                                                <li <?php if (isset($this->activelink3)) { if ($this->activelink3 == 'Draft PO') {echo ' class="active" ';}} ?> >
                                                    <a href="<?php echo Config::get('URL') . 'po/draftOrder'; ?>">
                                                        <i class="menu-icon glyphicon glyphicon-edit red"></i>
                                                        Draft Order
                                                    </a>

                                                    <b class="arrow"></b>
                                                </li>

                                                <li <?php if (isset($this->activelink3)) { if ($this->activelink3 == 'waiting approval purchase order') {echo ' class="active" ';}} ?> >
                                                    <a href="<?php echo Config::get('URL') . 'po/waitingApproval/'; ?>">
                                                        <i class="menu-icon glyphicon glyphicon-hourglass purple"></i>
                                                        Waiting Approval
                                                    </a>

                                                    <b class="arrow"></b>
                                                </li>

                                                <li <?php if (isset($this->activelink3)) { if ($this->activelink3 == 'approved purchase order') {echo ' class="active" ';}} ?> >
                                                    <a href="<?php echo Config::get('URL') . 'po/approved/'; ?>">
                                                        <i class="menu-icon glyphicon glyphicon-ok blue"></i>
                                                        Approved Order
                                                    </a>

                                                    <b class="arrow"></b>
                                                </li>

                                                <li <?php if (isset($this->activelink3)) { if ($this->activelink3 == 'closed purchase order') {echo ' class="active" ';}} ?> >
                                                    <a href="<?php echo Config::get('URL') . 'po/closed/'; ?>">
                                                        <i class="menu-icon glyphicon glyphicon-lock"></i>
                                                        Closed Order
                                                    </a>

                                                    <b class="arrow"></b>
                                                </li>
                                            </ul>
                                        </li>
                                        <?php
                                        /*
                                        <li <?php if (isset($this->activelink2)) { if ($this->activelink2 == 'purchase book') {echo ' class="active open" ';}} ?> >
                                            <a href="#" class="dropdown-toggle">
                                                <i class="menu-icon fa fa-caret-right"></i>

                                                Purchase Book
                                                <b class="arrow fa fa-angle-down"></b>
                                            </a>

                                            <b class="arrow"></b>

                                            <ul class="submenu">
                                                <li <?php if (isset($this->activelink3)) { if ($this->activelink3 == 'buat purchase book') {echo ' class="active" ';}} ?> >
                                                    <a href="<?php echo Config::get('URL') . 'purchaseBook/order'; ?>">
                                                        <i class="menu-icon fa fa-briefcase purple"></i>
                                                        Buat Transaksi
                                                    </a>
                                                    <b class="arrow"></b>
                                                </li>

                                                <li <?php if (isset($this->activelink3)) { if ($this->activelink3 == 'laporan purchase book') {echo ' class="active" ';}} ?> >
                                                    <a href="<?php echo Config::get('URL') . 'purchaseBook/report'; ?>">
                                                        <i class="menu-icon glyphicon glyphicon-duplicate orange"></i>
                                                        Laporan
                                                    </a>
                                                    <b class="arrow"></b>
                                                </li>
                                            </ul>
                                        </li>
                                        */
                                        ?>
                                        <li <?php if (isset($this->activelink2)) { if ($this->activelink2 == 'nota pembelian') {echo ' class="active open" ';}} ?> >
                                            <a href="#" class="dropdown-toggle">
                                                <i class="menu-icon fa fa-caret-right"></i>

                                                Nota Pembelian
                                                <b class="arrow fa fa-angle-down"></b>
                                            </a>

                                            <b class="arrow"></b>

                                            <ul class="submenu">
                                                <li <?php if (isset($this->activelink3)) { if ($this->activelink3 == 'nota pembelian make new') {echo ' class="active" ';}} ?> >
                                                    <a href="<?php echo Config::get('URL') . 'NotaPembelian/makeNew'; ?>">
                                                        <i class="menu-icon fa fa-briefcase purple"></i>
                                                        Buat Transaksi
                                                    </a>
                                                    <b class="arrow"></b>
                                                </li>

                                                <li <?php if (isset($this->activelink3)) { if ($this->activelink3 == 'nota pembelian report') {echo ' class="active" ';}} ?> >
                                                    <a href="<?php echo Config::get('URL') . 'NotaPembelian/report'; ?>">
                                                        <i class="menu-icon glyphicon glyphicon-duplicate orange"></i>
                                                        Laporan
                                                    </a>
                                                    <b class="arrow"></b>
                                                </li>
                                            </ul>
                                        </li>
                                    </ul>
                                </li>


                                <li class="">
                                    <a href="#" class="dropdown-toggle">
                                        <i class="menu-icon fa fa-tag"></i>
                                        <span class="menu-text"> More Pages </span>

                                        <b class="arrow fa fa-angle-down"></b>
                                    </a>

                                    <b class="arrow"></b>

                                    <ul class="submenu">
                                        <li class="">
                                            <a href="<?php echo Config::get('URL'); ?>attendance/">
                                                <i class="menu-icon fa fa-caret-right"></i>
                                                Mesin Absen
                                            </a>

                                            <b class="arrow"></b>
                                        </li>

                                        <li class="">
                                            <a href="<?php echo Config::get('URL'); ?>attendance/attendanceReport/">
                                                <i class="menu-icon fa fa-caret-right"></i>
                                                Laporan Absensi
                                            </a>

                                            <b class="arrow"></b>
                                        </li>
                                    </ul>
                                </li>
                            </ul><!-- /.nav-list -->

                            <div class="sidebar-toggle sidebar-collapse" id="sidebar-collapse">
                                <i id="sidebar-toggle-icon" class="ace-icon fa fa-angle-double-left ace-save-state" data-icon1="ace-icon fa fa-angle-double-left" data-icon2="ace-icon fa fa-angle-double-right"></i>
                            </div>
                        </div>
