<nav class="navbar navbar-inverse navbar-fixed-top">
  <div class="container-fluid">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>

      <button type="button" class="offcanvas-toggle" data-toggle="offcanvas">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>

    </div>
    <div id="navbar" class="navbar-collapse collapse">
        <ul class="nav navbar-nav">        
        <li><a href="<?php echo Config::get('URL'); ?>transaction/direct">Kas</a></li>
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Laporan <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="<?php echo Config::get('URL'); ?>transaction/reportTable">Tabel</a></li>
            <li><a href="<?php echo Config::get('URL'); ?>transaction/chart">Graphic</a></li>
          </ul>
        </li>
      </ul>
      
        <ul class="nav navbar-nav navbar-right">
        <?php if (Session::userIsLoggedIn()) { ?>
        <li class="dropdown <?php if (View::checkForActiveController($filename, "user")) { echo 'active'; } ?>">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Profile<span class="caret"></span></a>
            <ul class="dropdown-menu">
                <li>
                    <a href="<?php echo Config::get('URL'); ?>user/editAvatar">Ganti Foto</a>
                </li>
                <li>
                    <a href="<?php echo Config::get('URL'); ?>user/editusername">Edit username</a>
                </li>
                <li>
                    <a href="<?php echo Config::get('URL'); ?>user/changePassword">Ganti Password</a>
                </li>
                
                <li role="separator" class="divider"></li>

                <li >
                    <a href="<?php echo Config::get('URL'); ?>login/logout">Logout</a>
                </li>

                <?php if (Session::get("user_account_type") == 7) { ?>
                    <li role="separator" class="divider"></li>
                    <li class="dropdown-header">SUPER USER</li>
                    <li <?php if (View::checkForActiveController($filename, "admin")) {
                        echo ' class="active" ';
                    } ?> >
                        <a href="<?php echo Config::get('URL'); ?>admin/">Admin</a>
                    </li>
                    <li >
                    <a href="<?php echo Config::get('URL'); ?>user/changeUserRole">Change account type</a>
                </li>
                <?php }; ?>

            </ul>
        </li> 
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
      <form class="navbar-form navbar-right" method="get" action="<?php echo Config::get('URL') . 'customer/search/';?>">
            <input type="text" class="form-control" placeholder="Search..." autocomplete="off" name="find" value="<?php if(isset($_GET['find'])){ echo $_GET['find'];}?>" />
      </form>
    </div>
  </div>
</nav>
<!--/HEADER -->