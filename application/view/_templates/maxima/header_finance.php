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
                    <li class="grey">
                        <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                                <?php
                                $notification = NotificationModel::finance();
                                $total_notif = $notification->total_open_do_request + $notification->total_payment_disbursement;

                                if ($total_notif > 0 ) {
                                    //percentage for design
                                    $percentage_open_do_request = (int)(($notification->total_open_do_request/$total_notif) * 100);

                                    $percentage_payment = (int)(($notification->total_payment_disbursement/$total_notif) * 100);
                                echo '<i class="ace-icon fa fa-bell icon-animated-bell"></i>
                                        <span class="badge badge-grey">' . $total_notif . '</span>';
                                
                                } else {
                                    echo '<i class="ace-icon fa fa-bell"></i>';
                                }
                                
                                ?>
                        </a>

                        <ul class="dropdown-menu-right dropdown-navbar dropdown-menu dropdown-caret dropdown-close">
                            <li class="dropdown-header">
                                <i class="ace-icon fa fa-check"></i>
                                <?php echo $total_notif; ?> Tasks to complete
                            </li>

                            <li class="dropdown-content">
                                <ul class="dropdown-menu dropdown-navbar">
                                    <li>
                                        <a href="<?php echo Config::get('URL') . 'so/waitingOpenDO/'; ?>">
                                            <div class="clearfix">
                                                <span class="pull-left">Open DO Request</span>
                                                <span class="pull-right"><?php echo $notification->total_open_do_request?></span>
                                            </div>

                                            <div class="progress progress-mini">
                                                <div style="width:<?php echo $percentage_open_do_request;?>%" class="progress-bar progress-bar-warning"></div>
                                            </div>
                                        </a>
                                    </li>

                                    <li>
                                        <a href="<?php echo Config::get('URL') . 'bukuBesar/paymentDisbursementNotification/'; ?>">
                                            <div class="clearfix">
                                                <span class="pull-left">Pencairan Pembayaran</span>
                                                <span class="pull-right"><?php echo $notification->total_payment_disbursement?></span>
                                            </div>
                                            <div class="progress progress-mini progress-striped active">
                                                <div style="width:<?php echo $percentage_payment;?>%" class="progress-bar progress-bar-success"></div>
                                            </div>
                                        </a>
                                    </li>
                                </ul>
                            </li>

                            <li class="dropdown-footer">
                                <a href="#">
                                    Click each menu for details
                                    <i class="ace-icon fa fa-arrow-right"></i>
                                </a>
                            </li>
                        </ul>
                    </li>

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

                                <li <?php if (isset($this->activelink1)) { if ($this->activelink1 == 'finance') {echo ' class="active open" ';}} ?> >
                                    <a href="#" class="dropdown-toggle">
                                        <i class="menu-icon fa fa-money"></i>
                                        <span class="menu-text">
                                            Finance
                                        </span>

                                        <b class="arrow fa fa-angle-down"></b>
                                    </a>

                                    <b class="arrow"></b>

                                    <ul class="submenu">

                                        <li <?php if (isset($this->activelink2)) { if ($this->activelink2 == 'buku besar') {echo ' class="active" ';}} ?> >
                                            <a href="<?php echo Config::get('URL') . 'bukuBesar/index'; ?>">
                                                <i class="menu-icon fa fa-caret-right"></i>
                                                Buku Besar
                                            </a>

                                            <b class="arrow"></b>
                                        </li>

                                        <li <?php if (isset($this->activelink2)) { if ($this->activelink2 == 'hutang piutang') {echo ' class="active" ';}} ?> >
                                            <a href="<?php echo Config::get('URL') . 'HutangPiutang/index'; ?>">
                                                <i class="menu-icon fa fa-caret-right"></i>
                                                Hutang Piutang
                                            </a>
                                            <b class="arrow"></b>
                                        </li>

                                        <li <?php if (isset($this->activelink2)) { if ($this->activelink2 == 'cash flow') {echo ' class="active" ';}} ?> >
                                            <a href="<?php echo Config::get('URL') . 'bukuBesar/cashFlow'; ?>">
                                                <i class="menu-icon fa fa-caret-right"></i>
                                                Cash Flow
                                            </a>
                                            <b class="arrow"></b>
                                        </li>

                                        <li <?php if (isset($this->activelink2)) { if ($this->activelink2 == 'value stock bahan baku') {echo ' class="active" ';}} ?> >
                                            <a href="<?php echo Config::get('URL') . 'bukuBesar/inventoryValuation'; ?>">
                                                <i class="menu-icon fa fa-caret-right"></i>
                                                Inventory Valuation
                                            </a>
                                            <b class="arrow"></b>
                                        </li>

                                        <li <?php if (isset($this->activelink2)) { if ($this->activelink2 == 'all transaction') {echo ' class="active" ';}} ?> >
                                            <a href="<?php echo Config::get('URL') . 'bukuBesar/allTransaction'; ?>">
                                                <i class="menu-icon fa fa-caret-right"></i>
                                                All Transaction
                                            </a>

                                            <b class="arrow"></b>
                                        </li>

                                        <li <?php if (isset($this->activelink2)) { if ($this->activelink2 == 'notification payment disbursement') {echo ' class="active" ';}} ?> >
                                            <a href="<?php echo Config::get('URL') . 'bukuBesar/paymentDisbursementNotification'; ?>">
                                                <i class="menu-icon fa fa-caret-right"></i>
                                                Notif Pencairan
                                            </a>

                                            <b class="arrow"></b>
                                        </li>

                                        <li <?php if (isset($this->activelink2)) { if ($this->activelink2 == 'confirm payment') {echo ' class="active" ';}} ?> >
                                            <a href="<?php echo Config::get('URL'); ?>bukuBesar/confirmPayment/">
                                                <i class="menu-icon fa fa-caret-right"></i>
                                                Confirmation
                                            </a>
                                            <b class="arrow"></b>
                                        </li>

                                        <li <?php if (isset($this->activelink2)) { if ($this->activelink2 == 'transaksi langsung') {echo ' class="active open" ';}} ?> >
                                            <a href="#" class="dropdown-toggle">
                                                <i class="menu-icon fa fa-caret-right"></i>

                                                Transaksi Langsung
                                                <b class="arrow fa fa-angle-down"></b>
                                            </a>

                                            <b class="arrow"></b>

                                            <ul class="submenu">
                                                <li <?php if (isset($this->activelink3)) { if ($this->activelink3 == 'buat transaksi') {echo ' class="active" ';}} ?> >
                                                    <a href="<?php echo Config::get('URL') . 'finance/debitCreditTransaction'; ?>">
                                                        <i class="menu-icon glyphicon glyphicon-transfer purple"></i>
                                                        Buat Transaksi
                                                    </a>
                                                    <b class="arrow"></b>
                                                </li>

                                                <li <?php if (isset($this->activelink3)) { if ($this->activelink3 == 'laporan transaksi langsung') {echo ' class="active" ';}} ?> >
                                                    <a href="<?php echo Config::get('URL') . 'finance/reportDebitCreditTransaction/'; ?>">
                                                        <i class="menu-icon fa fa-bar-chart-o purple"></i>
                                                        Laporan
                                                    </a>
                                                    <b class="arrow"></b>
                                                </li>
                                            </ul>
                                        </li>
                                    </ul>
                                </li>

                                <li <?php if (isset($this->activelink1)) { if ($this->activelink1 == 'penjualan') {echo ' class="active open" ';}} ?> >
                                    <a href="#" class="dropdown-toggle">
                                        <i class="menu-icon glyphicon glyphicon-shopping-cart"></i>
                                        <span class="menu-text">
                                            Penjualan
                                        </span>

                                        <b class="arrow fa fa-angle-down"></b>
                                    </a>

                                    <b class="arrow"></b>

                                    <ul class="submenu">
                                        <li <?php if (isset($this->activelink2)) { if ($this->activelink2 == 'sales order') {echo ' class="active open" ';}} ?> >
                                            <a href="#" class="dropdown-toggle">
                                                <i class="menu-icon fa fa-caret-right"></i>
                                                Sales Order
                                                <b class="arrow fa fa-angle-down"></b>
                                            </a>

                                            <b class="arrow"></b>

                                            <ul class="submenu">
                                                <li <?php if (isset($this->activelink3)) { if ($this->activelink3 == 'draft so') {echo ' class="active" ';}} ?> >
                                                    <a href="<?php echo Config::get('URL') . 'so/draftSo'; ?>">
                                                        <i class="menu-icon glyphicon glyphicon-tags purple"></i>
                                                        Draft SO
                                                    </a>

                                                    <b class="arrow"></b>
                                                </li>

                                                <li <?php if (isset($this->activelink3)) { if ($this->activelink3 == 'so waiting approval') {echo ' class="active" ';}} ?> >
                                                    <a href="<?php echo Config::get('URL') . 'so/waitingApproval'; ?>">
                                                        <i class="menu-icon glyphicon glyphicon-tags red"></i>
                                                        Waiting Approval
                                                    </a>

                                                    <b class="arrow"></b>
                                                </li>

                                                <li <?php if (isset($this->activelink3)) { if ($this->activelink3 == 'approved so') {echo ' class="active" ';}} ?> >
                                                    <a href="<?php echo Config::get('URL') . 'so/approved'; ?>">
                                                        <i class="menu-icon glyphicon glyphicon-tags green"></i>
                                                        Approved Order
                                                    </a>

                                                    <b class="arrow"></b>
                                                </li>
                                                <li <?php if (isset($this->activelink3)) { if ($this->activelink3 == 'waiting sj approval') {echo ' class="active" ';}} ?> >
                                                    <a href="<?php echo Config::get('URL') . 'so/waitingSjApproval/'; ?>">
                                                        <i class="menu-icon glyphicon glyphicon-hourglass"></i>
                                                        SJ Approval
                                                    </a>

                                                    <b class="arrow"></b>
                                                </li>
                                            </ul>
                                        </li>
                                        <?php /*
                                        <li <?php if (isset($this->activelink2)) { if ($this->activelink2 == 'penjualan kasir') {echo ' class="active open" ';}} ?> >
                                            <a href="#" class="dropdown-toggle">
                                                <i class="menu-icon fa fa-caret-right"></i>

                                                Kasir
                                                <b class="arrow fa fa-angle-down"></b>
                                            </a>

                                            <b class="arrow"></b>

                                            <ul class="submenu">
                                                <li <?php if (isset($this->activelink3)) { if ($this->activelink3 == 'laporan kasir by order') {echo ' class="active" ';}} ?> >
                                                    <a href="<?php echo Config::get('URL') . 'kasir/resto'; ?>">
                                                        <i class="menu-icon glyphicon glyphicon-cutlery green"></i>
                                                        Cafe &amp; Resto
                                                    </a>
                                                    <b class="arrow"></b>
                                                </li>
                                                <li <?php if (isset($this->activelink3)) { if ($this->activelink3 == 'laporan kasir by tanggal') {echo ' class="active" ';}} ?> >
                                                    <a href="<?php echo Config::get('URL') . 'kasir/supermarket'; ?>">
                                                        <i class="menu-icon glyphicon glyphicon-shopping-cart green"></i>
                                                        Swalayan
                                                    </a>
                                                    <b class="arrow"></b>
                                                </li>
                                                <li <?php if (isset($this->activelink3)) { if ($this->activelink3 == 'penjualan kasir laporan') {echo ' class="active open" ';}} ?> >
                                                    <a href="#" class="dropdown-toggle">
                                                        <i class="menu-icon glyphicon glyphicon-duplicate orange"></i>

                                                        Laporan
                                                        <b class="arrow fa fa-angle-down"></b>
                                                    </a>

                                                    <b class="arrow"></b>

                                                    <ul class="submenu">
                                                        <li <?php if (isset($this->activelink4)) { if ($this->activelink4 == 'penjualan kasir laporan by order') {echo ' class="active" ';}} ?> >
                                                            <a href="<?php echo Config::get('URL') . 'kasir/laporan'; ?>">
                                                                <i class="menu-icon glyphicon glyphicon-transfer purple"></i>
                                                                By Order
                                                            </a>
                                                            <b class="arrow"></b>
                                                        </li>
                                                        <li <?php if (isset($this->activelink4)) { if ($this->activelink4 == 'penjualan kasir laporan by tanggal') {echo ' class="active" ';}} ?> >
                                                            <a href="<?php echo Config::get('URL') . 'kasir/reportByDate'; ?>">
                                                                <i class="menu-icon glyphicon glyphicon-calendar purple"></i>
                                                                By Tanggal
                                                            </a>
                                                            <b class="arrow"></b>
                                                        </li>
                                                        <li <?php if (isset($this->activelink4)) { if ($this->activelink4 == 'penjualan kasir laporan summary') {echo ' class="active" ';}} ?> >
                                                            <a href="<?php echo Config::get('URL') . 'kasir/summary'; ?>">
                                                                <i class="menu-icon glyphicon glyphicon-save-file purple"></i>
                                                                Summary
                                                            </a>
                                                            <b class="arrow"></b>
                                                        </li>
                                                        <li <?php if (isset($this->activelink4)) { if ($this->activelink4 == 'penjualan kasir laporan therapist') {echo ' class="active" ';}} ?> >
                                                            <a href="<?php echo Config::get('URL') . 'kasir/dashboard'; ?>">
                                                                <i class="menu-icon glyphicon glyphicon-scale purple"></i>
                                                                Therapist
                                                            </a>
                                                            <b class="arrow"></b>
                                                        </li>
                                                        <li <?php if (isset($this->activelink4)) { if ($this->activelink4 == 'penjualan kasir laporan dashboard') {echo ' class="active" ';}} ?> >
                                                            <a href="<?php echo Config::get('URL') . 'kasir/dashboard'; ?>">
                                                                <i class="menu-icon glyphicon glyphicon-scale purple"></i>
                                                                Dashboard
                                                            </a>
                                                            <b class="arrow"></b>
                                                        </li>
                                                    </ul>
                                                </li>
                                            </ul>
                                        </li>
                                        <li <?php if (isset($this->activelink2)) { if ($this->activelink2 == 'penjualan consignment') {echo ' class="active open" ';}} ?> >
                                            <a href="#" class="dropdown-toggle">
                                                <i class="menu-icon fa fa-caret-right"></i>

                                                Konsinyasi
                                                <b class="arrow fa fa-angle-down"></b>
                                            </a>

                                            <b class="arrow"></b>

                                            <ul class="submenu">
                                                <li <?php if (isset($this->activelink3)) { if ($this->activelink3 == 'laporan consignment summary') {echo ' class="active" ';}} ?> >
                                                    <a href="<?php echo Config::get('URL') . 'consignment/consignment'; ?>">
                                                        <i class="menu-icon glyphicon glyphicon-ice-lolly-tasted green"></i>
                                                        Konsinyasi
                                                    </a>
                                                    <b class="arrow"></b>
                                                </li>
                                                <li <?php if (isset($this->activelink3)) { if ($this->activelink3 == 'penjualan consignment laporan') {echo ' class="active open" ';}} ?> >
                                                    <a href="#" class="dropdown-toggle">
                                                        <i class="menu-icon glyphicon glyphicon-duplicate orange"></i>

                                                        Laporan
                                                        <b class="arrow fa fa-angle-down"></b>
                                                    </a>

                                                    <b class="arrow"></b>

                                                    <ul class="submenu">
                                                        <li <?php if (isset($this->activelink4)) { if ($this->activelink4 == 'penjualan consignment laporan by order') {echo ' class="active" ';}} ?> >
                                                            <a href="<?php echo Config::get('URL') . 'consignment/laporan'; ?>">
                                                                <i class="menu-icon glyphicon glyphicon-transfer purple"></i>
                                                                By Order
                                                            </a>
                                                            <b class="arrow"></b>
                                                        </li>
                                                        <li <?php if (isset($this->activelink4)) { if ($this->activelink4 == 'penjualan consignment laporan by tanggal') {echo ' class="active" ';}} ?> >
                                                            <a href="<?php echo Config::get('URL') . 'consignment/reportByDate'; ?>">
                                                                <i class="menu-icon glyphicon glyphicon-calendar purple"></i>
                                                                By Tanggal
                                                            </a>
                                                            <b class="arrow"></b>
                                                        </li>
                                                    </ul>
                                                </li>
                                            </ul>
                                        </li>
                                        */ ?>
                                    </ul>
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

                                <li <?php if (isset($this->activelink1)) { if ($this->activelink1 == 'inventory') {echo ' class="active open" ';}} ?> >
                                    <a href="#" class="dropdown-toggle">
                                        <i class="menu-icon fa fa-home"></i>
                                        <span class="menu-text">
                                            Inventory
                                        </span>

                                        <b class="arrow fa fa-angle-down"></b>
                                    </a>

                                    <b class="arrow"></b>

                                    <ul class="submenu">
                                        <li <?php if (isset($this->activelink2)) { if ($this->activelink2 == 'daftar barang') {echo ' class="active open" ';}} ?> >
                                            <a href="#" class="dropdown-toggle">
                                                <i class="menu-icon fa fa-caret-right"></i>

                                                Daftar Barang
                                                <b class="arrow fa fa-angle-down"></b>
                                            </a>

                                            <b class="arrow"></b>

                                            <ul class="submenu">
                                                <li <?php if (isset($this->activelink3)) { if ($this->activelink3 == 'daftar barang semua') {echo ' class="active" ';}} ?> >
                                                    <a href="<?php echo Config::get('URL') . 'inventory/'; ?>">
                                                        <i class="menu-icon glyphicon glyphicon-th"></i>
                                                        Semua
                                                    </a>

                                                    <b class="arrow"></b>
                                                </li>

                                                <li <?php if (isset($this->activelink3)) { if ($this->activelink3 == 'daftar barang repacking') {echo ' class="active" ';}} ?> >
                                                    <a href="<?php echo Config::get('URL') . 'inventory/formulation'; ?>">
                                                        <i class="menu-icon glyphicon glyphicon-cutlery blue"></i>
                                                        BOM
                                                    </a>

                                                    <b class="arrow"></b>
                                                </li>

                                                <li <?php if (isset($this->activelink3)) { if ($this->activelink3 == 'daftar barang bahan baku') {echo ' class="active" ';}} ?> >
                                                    <a href="<?php echo Config::get('URL') . 'inventory/rawMaterial'; ?>">
                                                        <i class="menu-icon glyphicon glyphicon-leaf purple"></i>
                                                        Bahan Baku
                                                    </a>

                                                    <b class="arrow"></b>
                                                </li>

                                                <li <?php if (isset($this->activelink3)) { if ($this->activelink3 == 'daftar barang barang wip') {echo ' class="active" ';}} ?> >
                                                    <a href="<?php echo Config::get('URL') . 'inventory/wip'; ?>">
                                                        <i class="menu-icon fa fa-hourglass-half"></i>
                                                        Setengah Jadi/WIP
                                                    </a>

                                                    <b class="arrow"></b>
                                                </li>

                                                <li <?php if (isset($this->activelink3)) { if ($this->activelink3 == 'daftar barang barang jadi') {echo ' class="active" ';}} ?> >
                                                    <a href="<?php echo Config::get('URL') . 'inventory/finishGoods'; ?>">
                                                        <i class="menu-icon fa fa-gift green"></i>
                                                        Produk Jadi
                                                    </a>

                                                    <b class="arrow"></b>
                                                </li>

                                                <li <?php if (isset($this->activelink3)) { if ($this->activelink3 == 'daftar barang trading goods') {echo ' class="active" ';}} ?> >
                                                    <a href="<?php echo Config::get('URL') . 'inventory/tradingGoods'; ?>">
                                                        <i class="menu-icon fa fa-refresh orange"></i>
                                                        Trading Goods
                                                    </a>

                                                    <b class="arrow"></b>
                                                </li>

                                                <li <?php if (isset($this->activelink3)) { if ($this->activelink3 == 'daftar barang service/jasa') {echo ' class="active" ';}} ?> >
                                                    <a href="<?php echo Config::get('URL') . 'inventory/serviceProduct'; ?>">
                                                        <i class="menu-icon glyphicon glyphicon-glass green"></i>
                                                        Service/Jasa
                                                    </a>

                                                    <b class="arrow"></b>
                                                </li>

                                                <li <?php if (isset($this->activelink3)) { if ($this->activelink3 == 'daftar barang production tool') {echo ' class="active" ';}} ?> >
                                                    <a href="<?php echo Config::get('URL') . 'inventory/productionTool'; ?>">
                                                        <i class="menu-icon fa fa-wrench red"></i>
                                                        Production Tools
                                                    </a>

                                                    <b class="arrow"></b>
                                                </li>

                                                <li <?php if (isset($this->activelink3)) { if ($this->activelink3 == 'daftar barang operating supplies') {echo ' class="active" ';}} ?> >
                                                    <a href="<?php echo Config::get('URL') . 'inventory/operatingSupply'; ?>">
                                                        <i class="menu-icon fa fa-cubes green"></i>
                                                        Operating Supplies
                                                    </a>

                                                    <b class="arrow"></b>
                                                </li>

                                                <li <?php if (isset($this->activelink3)) { if ($this->activelink3 == 'daftar barang asset material') {echo ' class="active" ';}} ?> >
                                                    <a href="<?php echo Config::get('URL') . 'inventory/assetMaterial'; ?>">
                                                        <i class="menu-icon fa fa-home  blue"></i>
                                                        Asset
                                                    </a>

                                                    <b class="arrow"></b>
                                                </li>
                                                
                                            </ul>
                                        </li>

                                        <li <?php if (isset($this->activelink2)) { if ($this->activelink2 == 'serial number') {echo ' class="active open" ';}} ?> >
                                            <a href="#" class="dropdown-toggle">
                                                <i class="menu-icon fa fa-caret-right"></i>

                                                Serial Number
                                                <b class="arrow fa fa-angle-down"></b>
                                            </a>

                                            <b class="arrow"></b>

                                            <ul class="submenu">
                                                <li <?php if (isset($this->activelink3)) { if ($this->activelink3 == 'serial number active') {echo ' class="active" ';}} ?> >
                                                    <a href="<?php echo Config::get('URL'); ?>serialNumber/active">
                                                        <i class="menu-icon glyphicon glyphicon-barcode blue"></i>
                                                        Aktif
                                                    </a>

                                                    <b class="arrow"></b>
                                                </li>
                                                <li <?php if (isset($this->activelink3)) { if ($this->activelink3 == 'serial number not active') {echo ' class="active" ';}} ?> >
                                                    <a href="<?php echo Config::get('URL'); ?>serialNumber/notActive">
                                                        <i class="menu-icon glyphicon glyphicon-barcode red"></i>
                                                        Not Aktif
                                                    </a>

                                                    <b class="arrow"></b>
                                                </li>
                                            </ul>
                                        </li>

                                        <li <?php if (isset($this->activelink2)) { if ($this->activelink2 == 'log keluar masuk barang') {echo ' class="active open" ';}} ?> >
                                            <a href="#" class="dropdown-toggle">
                                                <i class="menu-icon fa fa-caret-right"></i>

                                                Log Keluar Masuk
                                                <b class="arrow fa fa-angle-down"></b>
                                            </a>

                                            <b class="arrow"></b>

                                            <ul class="submenu">
                                                <li <?php if (isset($this->activelink3)) { if ($this->activelink3 == 'log barang masuk') {echo ' class="active" ';}} ?> >
                                                    <a href="<?php echo Config::get('URL'); ?>inventory/logIn">
                                                        <i class="menu-icon glyphicon glyphicon-save blue"></i>
                                                        Masuk
                                                    </a>

                                                    <b class="arrow"></b>
                                                </li>
                                                <li <?php if (isset($this->activelink3)) { if ($this->activelink3 == 'log barang keluar') {echo ' class="active" ';}} ?> >
                                                    <a href="<?php echo Config::get('URL'); ?>inventory/logOut">
                                                        <i class="menu-icon glyphicon glyphicon-open red"></i>
                                                        Keluar
                                                    </a>

                                                    <b class="arrow"></b>
                                                </li>
                                            </ul>
                                        </li>
                                        <li <?php if (isset($this->activelink2)) { if ($this->activelink2 == 'surat jalan') {echo ' class="active" ';}} ?> >
                                            <a href="<?php echo Config::get('URL'); ?>inventory/suratJalan">
                                                <i class="menu-icon fa fa-caret-right"></i>
                                                Surat Jalan
                                            </a>

                                            <b class="arrow"></b>
                                        </li>
                                        <li <?php if (isset($this->activelink2)) { if ($this->activelink2 == 'material transfer') {echo ' class="active" ';}} ?> >
                                            <a href="<?php echo Config::get('URL'); ?>MaterialTransfer/">
                                                <i class="menu-icon fa fa-caret-right"></i>
                                                Material Transfer
                                            </a>

                                            <b class="arrow"></b>
                                        </li>
                                        <li <?php if (isset($this->activelink2)) { if ($this->activelink2 == 'out of safety stock') {echo ' class="active" ';}} ?> >
                                            <a href="<?php echo Config::get('URL') . 'inventory/pushSystemStock'; ?>">
                                                <i class="menu-icon fa fa-caret-right"></i>
                                                Out of Safety Stock
                                            </a>

                                            <b class="arrow"></b>
                                        </li>
                                    </ul>
                                </li>
                                
                                <li <?php if (isset($this->activelink1)) { if ($this->activelink1 == 'System Preference') {echo ' class="active open" ';}} ?> >
                                    <a href="#" class="dropdown-toggle">
                                        <i class="menu-icon fa fa-laptop"></i>
                                        <span class="menu-text">
                                            System
                                        </span>

                                        <b class="arrow fa fa-angle-down"></b>
                                    </a>

                                    <b class="arrow"></b>

                                    <ul class="submenu">

                                        <li <?php if (isset($this->activelink2)) { if ($this->activelink2 == 'directTransactionCategory') {echo ' class="active" ';}} ?> >
                                            <a href="<?php echo Config::get('URL'); ?>systemPreference/directTransactionCategory/">
                                                <i class="menu-icon fa fa-caret-right"></i>
                                                Kategori Budget
                                            </a>

                                            <b class="arrow"></b>
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
