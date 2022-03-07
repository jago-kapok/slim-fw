<?php //Debuger::jam($this->pr_item_list); ?>
<table class="table table-striped table-bordered table-hover table-condensed">
	<thead>
		<tr>
			<th rowspan="2" class="text-center">
				#
			</th>
			<th rowspan="2" class="text-center">
				Kode
			</th>
			<th rowspan="2" class="text-center">
				Nama
			</th>
			<th colspan="3" class="text-center">
				Jumlah
			</th>
			<th colspan="3" class="text-center">
				Tanggal
			</th>
			<th rowspan="2" class="text-center">
				Keterangan
			</th>
		</tr>
		<tr>
			<th class="text-center">
				Masuk
			</th>
			<th class="text-center">
				Diterima
			</th>
			<th class="text-center">
				Ditolak
			</th>
			<th class="text-center">
				Masuk
			</th>
			<th class="text-center">
				Input
			</th>
			<th class="text-center">
				QC Check
			</th>
		</tr>
	</thead>

	<tbody>
		<?php
		$num = 1;
		foreach($this->qc_log as $key => $value) {
					echo "<tr>";
					echo '<td>
					<div class="dropdown">
  <button class="btn btn-info btn-xs dropdown-toggle" type="button" id="cpMenu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    ' . $num . '
    <span class="caret"></span>
  </button>
  <ul class="dropdown-menu" aria-labelledby="cpMenu">
    <li><a href="'. Config::get('URL') . 'po/deleteReceivedMaterial/' . $value->uid . '/?forward=' . $_SERVER['REQUEST_URI'] . '" onclick="return confirmation(\'Are you sure to delete?\');">Delete</a></li>
  </ul>
</div>
				</td>';
					echo '<td><a href="' . Config::get('URL') . 'inventory/editMaterial/?find=' . $value->material_code . '">' . $value->material_code . '</a></td>';
					echo '<td>' . $value->material_name . '</td>';
					echo '<td class="text-right">' . FormaterModel::decimalNumberUnderline($value->quantity) . '</td>';
					echo '<td class="text-right">' . FormaterModel::decimalNumberUnderline($value->quantity_received) . '</td>';
					echo '<td class="text-right">' . FormaterModel::decimalNumberUnderline($value->quantity_reject) . '</td>';
					echo '<td>' . date('d M, Y', strtotime($value->incoming_date)) . '</td>';
					echo '<td>' . date('d M, Y', strtotime($value->created_timestamp)) . '</td>';
					echo '<td>' . date('d-M, Y', strtotime($value->qc_pass_date)) . '</td>';
					echo '<td>' . $value->note . '</td>';
					echo "</tr>";
					$num++;
	
			}
		?>

		</tbody>
		</table>
