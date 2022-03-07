<div class="table-responsive">
<table class="table table-striped table-bordered table-hover ExcelTable2007">
<thead>
	<tr class="heading">
		<th>#</th>
		<th class="center">Nama Asset</th>
		<th class="center">Stock</th>
		<th class="center">Satuan</th>
		<th class="center">Harga</th>
		<th class="center">Cur</th>
		<th class="center">Cur Rate</th>
		<th class="center">Sub Total</th>
		<th class="center">Saldo</th>
	</tr>
</thead>
<tbody>
<?php

//stock bahan jadi
$no = 1;
$balance = 0;
$saldo_idr_asset= 0;

foreach($this->operating_supplies as $key => $value) {
	if ($value->quantity_stock > 0) {
		echo '<tr>';
        echo '<td class="heading">' . $no . '</td>';							
		echo '<td>' . $value->material_name . '</td>';
		echo '<td class="text-right">' . $value->quantity_stock . '</td>';
		echo '<td>' . $value->unit . '</td>';
		//harga asset menggunakan harga jual, jika kosong gunakan harga beli
		if (!empty($value->purchase_price)) {
			echo '<td class="text-right">' . number_format($value->purchase_price, 0) . '</td>';
			echo '<td class="text-right">' .  $value->purchase_currency . '</td>';

			if ($value->purchase_currency == 'IDR') {
				echo '<td class="text-right">1</td>';
				$balance = $value->quantity_stock * $value->purchase_price;
				
			} else {
				echo '<td class="text-right">' .  $this->currency_rate[$value->purchase_currency]['jual'] . '</td>';
				$balance = $value->quantity_stock * $value->purchase_price * $this->currency_rate[$value->purchase_currency]['jual'];
			}
		} else {
			echo '<td class="text-right">' . number_format($value->selling_price, 0) . '</td>';
			echo '<td class="text-right">' .  $value->selling_curency . '</td>';

			if ($value->selling_curency == 'IDR') {
				echo '<td class="text-right">1</td>';
				$balance = $value->quantity_stock * $value->selling_price;
				
			} else {
				echo '<td class="text-right">' .  $this->currency_rate[$value->selling_curency]['jual'] . '</td>';
				$balance = $value->quantity_stock * $value->selling_price * $this->currency_rate[$value->selling_curency]['jual'];
			}
		}
		
		$saldo_idr_asset = $saldo_idr_asset + $balance;
		echo '<td class="text-right">' . number_format($balance, 0) . '</td>';
		echo '<td class="text-right">' . number_format($saldo_idr_asset, 0) . '</td>';
		echo "</tr>";
		$no++;
		$balance = 0; //reset balance
	}
}
?>
<tbody>
</table>
</div><!-- /.table-responsive -->