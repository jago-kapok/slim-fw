<?php //Debuger::jam($this->pr_data); ?>
<div class="main-content">
                <div class="main-content-inner">
                    <div class="table-responsive">
					<table class="table table-striped table-bordered table-hover ExcelTable2007">
					<thead>
					<tr class="heading">
					<th>#</th>
					<th class="center">Date</th>
					<th class="center">#Transaksi</th>
					<th class="center">Customer/Supplier</th>
					<th class="center">Credit</th>
					<th class="center">Debit</th>
					<th class="center">Saldo</th>
					</thead>
					<tbody>
					<?php
					$no = 1;
					$credit=0;
                    $debit=0;
                    $SaldoBukuBesar = 0;
                    $saldo= 0;
					foreach($this->cash_flow as $key => $value) {

					//buat row beda warna untuk memudahkan  pengecekan dan mempersyantik penampilan
		          	// check apakah kode transaksi mengandung /SO- (sales ORDER) atau /SNO- (Sales Note Order)
		          	if (strpos($value->transaction_code, '/SO-') !== false OR strpos($value->transaction_code, '/SNO-') !== false) { 
		              	$row = 'success';
		          	// check apakah kode transaksi mengandung /PO- (purchase order) atau /PNO- (purchase note order)
		          	} elseif (strpos($value->transaction_code, '/PO-') !== false OR strpos($value->transaction_code, '/PNO-') !== false) {
		            	$row = 'warning';
		          	// check apakah kode transaksi mengandung /DT- (Direct Transaction)
		          	} elseif (strpos($value->transaction_code, '/DT') !== false) {
		            	$row = 'active';
		          	} else {
		            	$row = '';
		          	}
		          
		          	echo '<tr class="' . $row . '">';
                    echo '<td class="heading">' . $no . '</td>';
                    echo '<td>' . date("d-F, Y", strtotime($value->payment_due_date)). '</td>';

					if ($row == 'success') { 
					    echo '<td><a href="' .  Config::get('URL') . 'so/detail/?so_number=' . urlencode($value->transaction_code) . '">' . $value->transaction_code . '</td>';

					} elseif ($row == 'warning') {
						echo '<td><a href="' .  Config::get('URL') . 'po/detail/?po_number=' . urlencode($value->transaction_code) . '">' . $value->transaction_code . '</td>';
					} else {
						echo '<td>' .  $value->transaction_code . '</td>';
					}

					if (empty($value->contact_name) AND empty($value->transaction_name)) {
						echo '<td>' .  $value->transaction_type . '</td>';
					} elseif (empty($value->contact_name) AND !empty($value->transaction_name)) {
						echo '<td>Transaksi Langsung: ' .  $value->transaction_name . ' (' . $value->transaction_category . ')</td>';
					} else {
						echo '<td>' .  $value->contact_name . ' (' . $value->contact_id . ')</td>';
					}
					echo '<td class="align-right">' . number_format($value->credit, 2) . '</td>';
					echo '<td class="align-right">' . number_format($value->debit, 2) . '</td>';
                    $saldo = $saldo + $value->credit - $value->debit;
					echo '<td class="align-right">' . number_format($saldo, 2) . '</td>';
					echo "</tr>";
					$no++;
					$credit = $credit + $value->credit;
                    $debit = $debit + $value->debit;
                    $SaldoBukuBesar = $credit - $debit;
					}
					?>
					<tr class="info">
                            <td colspan="4" class="text-center">Total</td>
                            <td class="text-right"><?php echo number_format($credit,2);?></td>
                            <td class="text-right"><?php echo number_format($debit,2);?></td>
                            <td class="text-right"><?php echo number_format($SaldoBukuBesar,2);?></td>
                    </tr>
					</tbody>
					</table>
					</div><!-- /.table-responsive -->

				<div class="hr hr10 hr-double"></div>
                    
        </div>
      </div><!-- /.main-content -->