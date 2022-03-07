<?php //Debuger::jam($this->pr_item_list); ?>
<table class="table table-striped table-bordered table-hover table-condensed">
	<thead>
		<tr>
			<th class="text-center">
				#
			</td>
			<th class="text-center">
				Kode
			</td>
			<th class="text-center">
				Nama
			</td>
			<th class="text-center">
				Jumlah
			</td>
			<th class="text-center">
				Ganti Status Penerimaan
			</td>
		</tr>
	</thead>

	<tbody>
		<?php
		$num = 1;
		foreach($this->pr_item_list as $key => $value) {
		//make color list for better view
		if ($value->status === 'full received') {
			echo '<tr class="success">';
		} else if ($value->status === 'partial received') {
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

					if (!empty($value->material_code)) {
						echo '<td>' . $value->material_name . '</td>';
					} else {
						echo '<td>' . $value->budget_item . '</td>';
					}

					echo '<td class="text-right">' . FormaterModel::decimalNumberUnderline($value->quantity_received,2) . '</td>';
					echo '<td><div class="btn-group btn-corner">
								<a href="'. Config::get('URL') . 'po/changeStatusReceivedMaterial/0/' . $value->uid . '/?po_number=' . urlencode($value->transaction_number) . '" onclick="return confirmation(\'Tandai sebagai partial received\');" class="btn btn-inverse btn-minier">Partial Received</a>
								<a href="'. Config::get('URL') . 'po/changeStatusReceivedMaterial/1/' . $value->uid . '/?po_number=' . urlencode($value->transaction_number) . '" onclick="return confirmation(\'Tandai sebagai full received\');" class="btn btn-danger btn-minier">Full Received</a>
						</div></td>';
					echo "</tr>";
					$num++;
	
			}
		?>

		</tbody>
		</table>