<table class="table table-striped table-bordered table-hover">
	<thead>
		<tr>
			<th>
				#
			</th>
			<th>
				Kode
			</th>
			<th>
				Nama Barang
			</th>
			<th class="text-right">
				Total Dibeli
			</th>
			<th class="text-right">
				Satuan
			</th>
		</tr>
	</thead>

	<tbody>
		<?php
		$num=1;
		foreach($this->selling_item as $value) {
			echo "<tr>";
			echo '<td>' . $num . '</td>';
			echo '<td>' . $value->material_code . '</td>';
			echo '<td>' . ucwords($value->material_name) . '</td>';
			echo '<td class="text-right">' . number_format($value->total_purchased, 0)  . '</td>';
			echo '<td>' . $value->unit . '</td>';
			echo "</tr>";
			$num++;
		} 
		?>

	</tbody>
</table>