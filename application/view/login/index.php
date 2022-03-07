<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
        <meta charset="utf-8" />
        <title>
            <?php echo Config::get('DEFAULT_TITLE'); ?>
        </title>

        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

        <!-- bootstrap & fontawesome -->
        <!-- bootstrap & fontawesome -->
        <link rel="stylesheet" href="<?php echo Config::get('URL'); ?>bootstrap-3.3.7/css/bootstrap.min.css" />
        <link rel="stylesheet" href="<?php echo Config::get('URL'); ?>bootstrap-3.3.7/font-awesome/4.5.0/css/font-awesome.min.css" />

        <!-- page specific plugin styles -->

        <!-- text fonts -->
        <link rel="stylesheet" href="<?php echo Config::get('URL'); ?>bootstrap-3.3.7/css/fonts.googleapis.com.css" />
        <!-- ace styles -->
        <link rel="stylesheet" href="<?php echo Config::get('URL'); ?>bootstrap-3.3.7/css/ace.min.css" class="ace-main-stylesheet" id="main-ace-style" />

       
    </head>
        <body class="login-layout light-login">
        <div class="main-container">
            <div class="main-content">
                <div class="row">
                    <div class="col-sm-10 col-sm-offset-1">
                        <div class="login-container">
                            <div class="center">
                                <h1>
                                    <span class="blue">Maxima</span> <span class="green">Daya</span> <span class="red" id="id-text2">Indonesia</span>
                                </h1>
                                <h4 class="grey" id="id-company-text">© PT. XYZ</h4>
                                <?php $this->renderFeedbackMessages(); // Render message success or not?>
                            </div>

                            <div class="space-6"></div>

                            <div class="position-relative">
                                <div id="login-box" class="login-box visible widget-box no-border">
                                    <div class="widget-body">
                                        <div class="widget-main">
                                            <h4 class="header blue lighter bigger">
                                                <i class="ace-icon fa fa-lock icon-animated-bell green"></i>
                                                Please Enter Your Information
                                            </h4>

                                            <div class="space-6"></div>

                                            <form method="post" name="login-form" action="<?php echo Config::get('URL'); ?>login/login" role="form">
                                                <fieldset>
                                                    <label class="block clearfix">
                                                        <span class="block input-icon input-icon-right">
                                                            <input type="text" name="user_name" required class="form-control" placeholder="Username" />
                                                            <i class="ace-icon fa fa-user"></i>
                                                        </span>
                                                    </label>

                                                    <label class="block clearfix">
                                                        <span class="block input-icon input-icon-right">
                                                            <input type="password" name="user_password" required class="form-control" placeholder="Password" />
                                                            <i class="ace-icon fa fa-lock"></i>
                                                        </span>
                                                    </label>

                                                    <div class="space"></div>
                                                     <?php if (!empty($this->redirect)) { ?>
                                                      <input type="hidden" name="redirect" value="<?php echo $this->encodeHTML($this->redirect); ?>" />
                                                  <?php } ?>

                                                    <input type="hidden" name="csrf_token" value="<?= Csrf::makeToken(); ?>" />

                                                    <div class="clearfix">
                                                        <label class="inline">
                                                            <input type="checkbox" name="user_rememberme" class="ace" />
                                                            <span class="lbl"> Remember Me</span>
                                                        </label>

                                                        <button type="submit" class="width-35 pull-right btn btn-sm btn-primary">
                                                            <i class="ace-icon fa fa-key"></i>
                                                            <span class="bigger-110">Login</span>
                                                        </button>
                                                    </div>

                                                    <div class="space-4"></div>
                                                </fieldset>
                                            </form>

                                            <div class="social-or-login center">
                                                <span class="bigger-110">Hubungi Kami</span>
                                            </div>

                                            <div class="space-6"></div>

                                            <div class="social-login center">
                                                <a class="btn btn-deffault" href="http://XYZ.com">
                                                    <i class="ace-icon fa fa-globe"></i>
                                                </a>

                                                <a class="btn btn-primary" href="https://www.facebook.com/pt.XYZ">
                                                    <i class="ace-icon fa fa-facebook"></i>
                                                </a>

                                                <a class="btn btn-info" href="https://www.instagram.com/pt.XYZ/">
                                                    <i class="ace-icon fa fa-instagram"></i>
                                                </a>

                                                <a class="btn btn-danger" href="https://www.youtube.com/channel/">
                                                    <i class="ace-icon fa fa-youtube"></i>
                                                </a>
                                            </div>
                                        </div><!-- /.widget-main -->

                                        <div class="toolbar center clearfix">
                                            <div>
                                                <a href="#" data-target="#forgot-box" class="forgot-password-link">
                                                    <i class="ace-icon fa fa-arrow-left"></i>
                                                    forgot my password
                                                </a>
                                            </div>

                                            <div>
                                                <a href="#" data-target="#signup-box" class="user-signup-link">
                                                    register new user
                                                    <i class="ace-icon fa fa-arrow-right"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div><!-- /.widget-body -->
                                </div><!-- /.login-box -->

                                <div id="forgot-box" class="forgot-box widget-box no-border">
                                    <div class="widget-body">
                                        <div class="widget-main">
                                            <h4 class="header red lighter bigger">
                                                <i class="ace-icon fa fa-key"></i>
                                                Retrieve Password
                                            </h4>

                                            <div class="space-6"></div>
                                            <p>
                                                Enter your email and to receive instructions
                                            </p>

                                            <form method="post" name="password-reset-form" action="<?php echo Config::get('URL'); ?>login/getPasswordReset">
                                                <fieldset>
                                                    <label class="block clearfix">
                                                        <span class="block input-icon input-icon-right">
                                                            <input type="email" name="user_name_or_email" required class="form-control" placeholder="Username or Email" />
                                                            <i class="ace-icon fa fa-envelope"></i>
                                                        </span>
                                                    </label>

                                                    <label class="block clearfix">
                                                        <div class="input-group input-group-lg">
                                                            <span class="input-group-addon no-margin no-padding">
                                                                <img id="captcha-password-reset" src="<?php echo Config::get('URL'); ?>register/showCaptcha" />
                                                            </span>

                                                            <input type="text" name="captcha" required  id="captcha-check" autocomplete="off" class="form-control" placeholder="Type Captcha"/>
                                                        </div> 
                                                    </label>

                                                    <div class="space-24"></div>

                                                    <div class="clearfix">
                                                        <button type="button" class="width-65 pull-left btn btn-sm" onclick="document.getElementById('captcha-password-reset').src = '<?php echo Config::get('URL'); ?>register/showCaptcha?' + Math.random(); return false">
                                                            <i class="ace-icon fa fa-refresh"></i>
                                                            <span class="bigger-110">Change Captcha</span>
                                                        </button>

                                                        <button type="submit" class="width-30 pull-right btn btn-sm btn-danger">
                                                            <span class="bigger-110">Send</span>

                                                            <i class="ace-icon fa fa-arrow-right icon-on-right"></i>
                                                        </button>
                                                    </div>
                                                </fieldset>
                                            </form>
                                        </div><!-- /.widget-main -->

                                        <div class="toolbar center">
                                            <a href="#" data-target="#login-box" class="back-to-login-link">
                                                Back to login
                                                <i class="ace-icon fa fa-arrow-right"></i>
                                            </a>
                                        </div>
                                    </div><!-- /.widget-body -->
                                </div><!-- /.forgot-box -->

                                <div id="signup-box" class="signup-box widget-box no-border">
                                    <div class="widget-body">
                                        <div class="widget-main">
                                            <h4 class="header green lighter bigger">
                                                <i class="ace-icon fa fa-users blue"></i>
                                                New User Registration
                                            </h4>

                                            <div class="space-6"></div>
                                            <p> Enter your details to begin: </p>

                                            <form method="post" name="register-form" action="<?php echo Config::get('URL'); ?>register/registerNewUser">
                                                <fieldset>
                                                    <label class="block clearfix">
                                                        <span class="block input-icon input-icon-right">
                                                            <input type="email" name="email" class="form-control" required placeholder="Email" />
                                                            <i class="ace-icon fa fa-envelope"></i>
                                                        </span>
                                                    </label>

                                                    <label class="block clearfix">
                                                        <span class="block input-icon input-icon-right">
                                                            <input type="text" pattern="[a-zA-Z0-9]{2,64}" name="username" required class="form-control" placeholder="Username" />
                                                            <i class="ace-icon fa fa-user"></i>
                                                        </span>
                                                    </label>

                                                    <label class="block clearfix">
                                                        <span class="block input-icon input-icon-right">
                                                            <input type="password" name="user_password_new" required class="form-control" placeholder="Password" />
                                                            <i class="ace-icon fa fa-lock"></i>
                                                        </span>
                                                    </label>

                                                    <label class="block clearfix">
                                                        <span class="block input-icon input-icon-right">
                                                            <input type="password" name="user_password_repeat" required class="form-control" placeholder="Repeat password" />
                                                            <i class="ace-icon fa fa-retweet"></i>
                                                        </span>
                                                    </label>

                                                    <label class="block clearfix">
                                                        <div class="input-group input-group-lg">
                                                            <span class="input-group-addon no-margin no-padding">
                                                                <img id="captcha-register" src="<?php echo Config::get('URL'); ?>register/showCaptcha" />
                                                            </span>

                                                            <input type="text" name="captcha" required  id="captcha-check" autocomplete="off" class="form-control" placeholder="Type Captcha"/>
                                                        </div> 
                                                    </label>

                                                    <!--
                                                    <label class="block">
                                                        <input type="checkbox" class="ace" />
                                                        <span class="lbl">
                                                            I accept the
                                                            <a href="#">User Agreement</a>
                                                        </span>
                                                    </label>
                                                    -->
                                                    <div class="space-24"></div>

                                                    <div class="clearfix">
                                                        <button type="button" class="width-65 pull-left btn btn-sm" onclick="document.getElementById('captcha-register').src = '<?php echo Config::get('URL'); ?>register/showCaptcha?' + Math.random(); return false">
                                                            <i class="ace-icon fa fa-refresh"></i>
                                                            <span class="bigger-110">Change Captcha</span>
                                                        </button>

                                                        <button type="submit" class="width-30 pull-right btn btn-sm btn-success">
                                                            <span class="bigger-110">Register</span>

                                                            <i class="ace-icon fa fa-arrow-right icon-on-right"></i>
                                                        </button>
                                                    </div>
                                                </fieldset>
                                            </form>
                                        </div>

                                        <div class="toolbar center">
                                            <a href="#" data-target="#login-box" class="back-to-login-link">
                                                <i class="ace-icon fa fa-arrow-left"></i>
                                                Back to login
                                            </a>
                                        </div>
                                    </div><!-- /.widget-body -->
                                </div><!-- /.signup-box -->
                            </div><!-- /.position-relative -->
                        </div>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.main-content -->
        </div><!-- /.main-container -->



        <!-- basic scripts -->
                <!--[if !IE]> -->
        <script type="text/javascript">
            window.jQuery || document.write("<script src='<?php echo Config::get('URL'); ?>bootstrap-3.3.7/js/jquery-2.1.4.min.js'>"+"<"+"/script>");
        </script>

        <!-- <![endif]-->

        <!--[if IE]>
<script type="text/javascript">
 window.jQuery || document.write("<script src='<?php echo Config::get('URL'); ?>bootstrap-3.3.7/js/jquery1x.min.js'>"+"<"+"/script>");
</script>
<![endif]-->
        <script type="text/javascript">
            if('ontouchstart' in document.documentElement) document.write("<script src='<?php echo Config::get('URL'); ?>bootstrap-3.3.7/js/jquery.mobile.custom.min.js'>"+"<"+"/script>");
        </script>
        <script src="<?php echo Config::get('URL'); ?>bootstrap-3.3.7/js/bootstrap.min.js"></script>

        <!-- page specific plugin scripts -->

        <!-- ace scripts -->
        <script src="<?php echo Config::get('URL'); ?>bootstrap-3.3.7/js/ace-elements.min.js"></script>
        <script src="<?php echo Config::get('URL'); ?>bootstrap-3.3.7/js/ace.min.js"></script>

        <!-- inline scripts related to this page -->
        <script type="text/javascript">
            jQuery(function($) {
             $(document).on('click', '.toolbar a[data-target]', function(e) {
                e.preventDefault();
                var target = $(this).data('target');
                $('.widget-box.visible').removeClass('visible');//hide others
                $(target).addClass('visible');//show target
             });
            });
        </script>
    </body>
</html>
