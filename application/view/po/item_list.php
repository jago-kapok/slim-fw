<?php //Debuger::jam($this->pr_item_list); ?>
<table class="table table-striped table-bordered table-hover table-condensed">
	<thead>
		<tr>
			<th class="text-center">#</th>
			<th class="text-center">Kode</th>
			<th class="text-center">Nama</th>
			<th class="text-center">Qty</th>
			<th class="text-center">Satuan</th>
			<?php if (Auth::isPermissioned('director,finance,purchasing')) { ?>
			<th class="text-center">Harga</th>
			<th class="text-center">Disc</th>
			<th class="text-center">Cur</th>
			<th class="text-center">PPN</th>
			<th class="text-center">Sub</th>
			<?php } ?>
		</tr>
	</thead>

	<tbody>
		<?php
		$num = 1;
		$total_price = 0;
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

					
		// check jika pr belum minta approval
					if ($this->pr_data->status < 0) {
					echo '<td>
					<div class="dropdown">
  <button class="btn btn-info btn-xs dropdown-toggle" type="button" id="cpMenu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    ' . $num . '
    <span class="caret"></span>
  </button>
  <ul class="dropdown-menu" aria-labelledby="cpMenu">
    <li><a href="' . Config::get('URL') . 'po/editPrItem/' . $value->uid . '">Edit</a></li>
    <li role="separator" class="divider"></li>
    <li><a href="'. Config::get('URL') . 'delete/remove/purchase_order_list/uid/' . $value->uid . '?forward=' . $_SERVER['REQUEST_URI'] . '" onclick="return confirmation(\'Are you sure to delete?\');">Delete</a></li>
  </ul>
</div>
				</td>'; } else { echo '<td>' . $num . '</td>'; }

					
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
					
					echo '<td class="text-right">' . FormaterModel::decimalNumberUnderline($value->quantity, 2) . '</td>';
					echo '<td>' . $value->unit . '</td>';
					if (Auth::isPermissioned('director,finance,purchasing')) {
						echo '<td class="text-right">' . FormaterModel::decimalNumberUnderline($value->purchase_price, 2) . '</td>';
						echo '<td class="text-right">' . (($value->purchase_price_discount > 0) ? FormaterModel::decimalNumberUnderline($value->purchase_price_discount, 2) : '' ) . '</td>';
						echo '<td>' . $value->purchase_currency . '</td>';
						echo '<td class="text-right">' . FormaterModel::decimalNumberUnderline($value->purchase_tax, 0) . '%</td>';
						$price_after_discount = $value->purchase_price - $value->purchase_price_discount;
						$price_after_discount_after_tax = $price_after_discount + ($price_after_discount * ($value->purchase_tax/100));
						$price_per_item = $price_after_discount_after_tax;
						$sub_total = $price_per_item * $value->quantity;

						echo '<td class="text-right">' . FormaterModel::decimalNumberUnderline($sub_total, 2) . '</td>';
					}
					echo "</tr>";
					$total_price = $total_price + $sub_total;
					$num++;
	
			}
			if (Auth::isPermissioned('director,finance,purchasing')) {
				$total_price_plus_shipment = $total_price + $this->shipment_data->purchase_price;
				echo '<tr class="danger">
						<td class="text-right" colspan="9">Sub Total: </td>
						<td class="text-right">' . FormaterModel::decimalNumberUnderline($total_price, 2) . '</td>
					</tr>';
				echo '<tr class="warning">
						<td class="text-right" colspan="9">Biaya Pengiriman: </td>
						<td class="text-right">' . FormaterModel::decimalNumberUnderline($this->shipment_data->purchase_price, 2) . '</td>
					</tr>';
				echo '<tr class="info">
						<td class="text-right" colspan="9">Total: </td>
						<td class="text-right">' . FormaterModel::decimalNumberUnderline($total_price_plus_shipment, 2) . '</td>
					</tr>';
			}
			?>

		</tbody>
		</table>