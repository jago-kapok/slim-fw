</section><!--/row-offcanvas-->
    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="<?php echo Config::get('URL'); ?>asset/js/jquery.min.js"></script>
    <script src="<?php echo Config::get('URL'); ?>asset/js/bootstrap.min.js"></script>
     <script src="<?php echo Config::get('URL'); ?>asset/js/offcanvas.js"></script>
     <script src="<?php echo Config::get('URL'); ?>asset/js/bootstrap-datepicker.js"></script>
     <?php if (isset($this->footer_addon)) { echo $this->footer_addon; } ?>
     <script>
     //date picker
      $('.datepicker').datepicker();

    // Auto dismiss Alert

    $(".alert-dismissible").fadeTo(2000, 500).slideUp(500, function(){
      $(".alert-dismissible").slideUp(500);
    });
  </script>

  <?php //var_dump($_SESSION); ?>
  </body>
</html>