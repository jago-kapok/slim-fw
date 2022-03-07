<div class="main-content">
        <div class="main-content-inner">
<?php $this->renderFeedbackMessages();?>
		<div class="table-responsive">
		<table id="sample-table-1" class="table table-striped table-bordered table-hover ExcelTable2007">
		<thead>
		<tr>
		<th>No</th>
		<th class="center">Kode</th>
		<th class="center">Nama Barang</th>
		<th class="center">Satuan</th>
		<th class="align-right">Stock</th>
		<th class="align-right">Safety Stock</th>
		</tr>
		</thead>

		<tbody>
		<?php
		$no = ($this->page * $this->limit) - ($this->limit - 1);
		foreach($this->stock as $key => $value) {

			echo '<tr>';
			echo '<td class="heading">' . $no . '</td>';
	        echo '<td><a href="' . Config::get('URL') . 'inventory/editMaterial/?find=' . $value->material_code . '">' . $value->material_code . '</a></td>';
			echo '<td>' . $value->material_name . '</td>';
			echo '<td>' . $value->unit . '</td>';
			echo '<td class="align-right">' .$value->quantity_stock . '</td>';
			echo '<td class="align-right">' . $value->minimum_balance . '</td>';
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