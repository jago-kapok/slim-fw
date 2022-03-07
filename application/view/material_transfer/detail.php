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
					<tr class="text-left hidden-print">
					<th colspan="3" class="hidden-print">No. Material Transfer: <?php echo $this->result[0]->transaction_number;?></th>
					<th colspan="2" class="text-left hidden-print">Tanggal: <?php echo date('d M, Y',  strtotime($this->result[0]->transaction_number));?></th>
					</tr>
					<tr>
					<th>No</th>
					<th class="center">Kode Barang</th>
					<th class="center">Nama</th>
					<th class="center">Jumlah</th>
					<th class="center">Satuan</th>
					</tr>
					</thead>

					<tbody>
					<?php
					$no = 1;
					foreach($this->result as $key => $value) {
						echo "<tr>";
						echo '<td class="heading">' . $no . '</td>';
						echo '<td><a href="' . Config::get('URL') . 'inventory/editMaterial/?find=' . $value->material_code . '">' . $value->material_code . '</a></td>';
						echo '<td>' . $value->material_name . '</td>';
						echo '<td>' . $value->quantity_delivered . '</td>';
						echo '<td>' . $value->unit . '</td>';
						echo "</tr>";
						$no++;
					}            

					?>
					<tr class="text-left hidden-print">
					<td colspan="5" class="hidden-print">Keterangan: <?php echo $this->result[0]->note;?></td>
					</tr>
					</tbody>
					</table>
					</div><!-- /.table-responsive -->


      <!-- PAGE CONTENT ENDS -->
      </div><!-- /.main-content-inner -->
      </div><!-- /.main-content -->