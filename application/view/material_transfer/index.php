<div class="main-content">
        <div class="main-content-inner">
				<!-- #section:basics/content.breadcrumbs -->
				<div class="breadcrumbs" id="breadcrumbs">
					<script type="text/javascript">
						try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
					</script>

					<ul class="breadcrumb">
						<li>
	                    <a href="<?php echo Config::get('URL') . 'MaterialTransfer/warehouseToWarehouse/'; ?>">
	                      <span class="badge badge-info">
	                      <i class="glyphicon glyphicon-plus"></i> New Material Transfer
	                      </span>
	                    </a>
	                    </li>
					</ul><!-- /.breadcrumb -->

					<!-- #section:basics/content.searchbox -->
					<div class="nav-search" id="nav-search">
						<form class="form-search" method="get" action="<?php echo Config::get('URL');?>MaterialTransfer/index/?find=">
							<span class="input-icon">
								<input type="text" placeholder="Search ..." class="nav-search-input" id="nav-search-input" autocomplete="off" name="find" value="<?php if(isset($_GET['find'])){ echo $_GET['find'];}?>" />
								<i class="ace-icon fa fa-search nav-search-icon"></i>
							</span>
						</form>
					</div><!-- /.nav-search -->

					<!-- /section:basics/content.searchbox -->
				</div>
<?php 

$this->renderFeedbackMessages();
// Render message success or not?>
					<div class="table-responsive">
					<table class="table table-striped table-bordered table-hover ExcelTable2007">
					<thead>
					<tr>
					<th colspan="8" class="center">Total <?php echo $this->total_record . ' ' . $this->title; ?></th>
					</tr>
					<tr>
					<th>No</th>
					<th class="center">Kode Transfer</th>
					<th class="center">Tanggal</th>
					<th class="center">Keterangan</th>
					</tr>
					</thead>

					<tbody>
					<?php

					$no = ($this->page * $this->limit) - ($this->limit - 1);
					foreach($this->result as $key => $value) {
						echo "<tr>";
						echo '<td class="heading">' . $no . '</td>';
	                    echo '<td><a href="' . Config::get('URL') . 'MaterialTransfer/detail/?transaction_number=' . urlencode($value->transaction_number) . '">' . $value->transaction_number . '</a></td>';
						echo '<td>' . date('d M, Y',  strtotime($value->created_timestamp)) . '</td>';
						echo '<td>' . $value->note . '</td>';
						echo "</tr>";
						$no++;
					}            

					?>
					</tbody>
					</table>
					</div><!-- /.table-responsive -->

				<?php echo $this->pagination;?>

      <!-- PAGE CONTENT ENDS -->
      </div><!-- /.main-content-inner -->
      </div><!-- /.main-content -->