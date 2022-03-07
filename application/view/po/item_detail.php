<table class="table table-striped table-bordered table-hover">
	<thead>
		<tr>
			<th class="text-center">No</th>
			<th class="text-center">Kode</th>
			<th class="text-center">Alasan Pembelian</th>
			<th class="text-center">Spesifikasi</th>
			<th class="text-center">Packaging</th>
			<th class="text-center">Keterangan</th>
		</tr>
	</thead>

	<tbody>
		<?php
		$num = 1;
		
		foreach($this->pr_item_list as $key => $value) {
		//make color list for better view
		if ($value->status == 'full received') {
			echo '<tr class="success">';
		} else if ($value->status == 'partial received') {
			echo '<tr>';
		} else if ($value->quantity_received >= $value->quantity) {
			echo '<tr class="success">';
		} else {
			echo '<tr>';
		}
					echo '<td>' . $num . '</td>';
					if (!empty($value->material_code)) {
						echo '<td><a href="' . Config::get('URL') . 'inventory/editMaterial/?find=' . $value->material_code . '">' . $value->material_code . '</a></td>';
					} else {
						echo '<td>Budget: ' . $value->budget_category . '</td>';
					}
					echo '<td>' . $value->reason_request . '</td>';
					echo '<td>' . $value->material_specification . '</td>';
					echo '<td>' . $value->packaging . '</td>';
					echo '<td>' . $value->note . '</td>';
					echo "</tr>";
					
					$num++;
		}
			?>

		</tbody>
		</table>