<div class="main-content">
        <div class="main-content-inner">
			<div class="breadcrumbs" id="breadcrumbs">
				<script type="text/javascript">
					try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
				</script>

				<!-- #section:basics/content.searchbox -->
				<div class="nav-search" id="nav-search">
					<form class="form-search" method="get" action="<?php echo Config::get('URL');?>inventory/findStock/?find=">
						<span class="input-icon">
							<input type="text" placeholder="Search ..." class="nav-search-input" id="nav-search-input" autocomplete="off" name="find" value="<?php if(isset($_GET['find'])){ echo urldecode($_GET['find']);}?>" />
							<i class="ace-icon fa fa-search nav-search-icon"></i>
						</span>
					</form>
				</div><!-- /.nav-search -->
			</div>

<?php $this->renderFeedbackMessages();?>
   
		<div class="table-responsive">
		<table id="sample-table-1" class="table table-striped table-bordered table-hover ExcelTable2007">
		<thead>
		<tr>
		<th colspan="8" class="center"><?php echo $this->totalStock . ' ' . $this->title . ' Tersedia'; ?></th>
		</tr>
		<tr>
		<th>No</th>
		<th class="center">Kode</th>
		<th class="center">Nama Barang</th>
		<th class="center">Satuan</th>
		<th class="align-right">Stock</th>
		<th class="align-right">Safety Stock</th>
		<th class="center">Keterangan</th>
		</tr>
		</thead>

		<tbody>
		<?php
		if ($this->stock) {
		$no = ($this->page * $this->limit) - ($this->limit - 1);
		
		foreach($this->stock as $key => $value) {
		//Buat status untuk message kondisi stock
		if ($value->quantity_stock <  $value->minimum_balance) {
			$safety_stock_status = 'danger';
		} else {
			$safety_stock_status = '';
		}
		echo '<tr class="' . $safety_stock_status . '">';
		echo '<td class="heading">' . $no . '</td>';
        echo '<td><a href="' . Config::get('URL') . 'inventory/editMaterial/?find=' . $value->material_code . '">' . $value->material_code . '</a></td>';
		echo '<td>' . $value->material_name . '</td>';
		echo '<td>' . $value->unit . '</td>';
		echo '<td class="align-right">' . floatval($value->quantity_stock) . '</td>';
		echo '<td class="align-right">' . floatval($value->minimum_balance) . '</td>';
		echo '<td>' . $value->note . '</td>';
		echo "</tr>";
		$no++;
		}            

		} else {

		echo 'No Data yet. Create one !';

		}
		?>
		</tbody>
		</table>
		</div><!-- /.table-responsive -->

		<?php echo $this->pagination;?>

<!-- PAGE CONTENT ENDS -->
</div><!-- /.main-content-inner -->
</div><!-- /.main-content -->