			<div class="footer hidden-print">
				<div class="footer-inner">
					<div class="footer-content">
						<span class="bigger-120">
							<span class="blue bolder">PT. XYZ</span>
							&copy; 2013-<?php echo date('Y'); ?>
						</span>

						&nbsp; &nbsp;
						
						<span class="action-buttons">
							<a href="http://xyz.com">
								<i class="ace-icon fa fa-globe orange bigger-150"></i>
							</a>

							<a href="https://www.facebook.com/pt.xyz/">
								<i class="ace-icon fa fa-facebook-square text-primary bigger-150"></i>
							</a>

							<a href="https://www.instagram.com/pt.xyz/">
								<i class="ace-icon fa fa-instagram text-info bigger-150"></i>
							</a>

							<a href="https://www.youtube.com/channel">
								<i class="ace-icon fa fa-youtube-play red bigger-150"></i>
							</a>
						</span>
					
					</div>
				</div>
			</div>

			
		</div><!-- /.main-container -->

		<!-- basic scripts -->

		<!--[if !IE]> -->
		<script src="<?php echo Config::get('URL'); ?>bootstrap-3.3.7/js/jquery-2.1.4.min.js"></script>

		<!-- <![endif]-->

		<!--[if IE]>
		<script src="<?php echo Config::get('URL'); ?>bootstrap-3.3.7/js/jquery-1.11.3.min.js"></script>
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
		<?php if (isset($this->footer_script)) {echo $this->footer_script;} ?>
		<script type="text/javascript">

		function confirmation(message){
			var question= confirm(message);
			if(question){
				return true;
			}else{
				return false;
			}
		}

		//close all collapse if button clicked
		jQuery("button").click( function(e) {
		    jQuery(".collapse").collapse("hide");
		});

		// Auto dismiss Alert
		$(".alert-dismissible").fadeTo(10000, 500).slideUp(500, function(){
		  $(".alert-dismissible").slideUp(500);
		});
		</script>
	</body>
</html>
