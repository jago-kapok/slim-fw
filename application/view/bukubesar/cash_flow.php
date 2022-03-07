<?php //Debuger::jam($this->stock_bahan); ?>
<div class="main-content">
                <div class="main-content-inner">
                    <div class="table-responsive">
					<table class="table table-striped table-bordered table-hover ExcelTable2007">
					<tr class="warning">
					<td class="center" colspan="9">Saldo Awal</td>
					</tr>
					<tr>
					<td colspan="8">Saldo Awal</td>
						<?php
							$total_saldo = 0;
							foreach ($this->saldo as $key => $value) {
								$total_saldo = $total_saldo + (($value->total_debit - $value->total_credit) * $this->currency_rate[$value->currency]['jual']);
							}
						?>
					<td class="text-right"><?php echo number_format($total_saldo); ?></td>
					</tr>

					<tr class="warning">
					<td class="center" colspan="9">Hutang/Piutang</td>
					</tr>
					<tr class="info">
					<td>#</td>
					<td class="center">Date</td>
					<td class="center">#Transaction</td>
					<td class="center">Customer/Supplier</td>
					<td class="center">Debit</td>
					<td class="center">Credit</td>
					<td class="center">Cur</td>
					<td class="center">Cur Rate</td>
					<td class="center">Saldo (IDR)</td>
					</tr>
					<?php
                    //stock bahan jadi
                    $no = 1;
					$credit=0;
                    $debit=0;
                    $balance = 0;
                    //echo '<pre>';var_dump($this->saldo);echo '</pre>';

					foreach($this->cash_flow as $key => $value) {

					//buat row beda warna untuk memudahkan  pengecekan dan mempersyantik penampilan
		          	// check apakah kode transaksi mengandung /SO- (sales ORDER) atau /SNO- (Sales Note Order)
		          	if (strpos($value->transaction_code, '/SO-') !== false OR strpos($value->transaction_code, '/SNO-') !== false) { 
		              	$row = 'success';
		          	// check apakah kode transaksi mengandung /PO- (purchase order) atau /PNO- (purchase note order)
		          	} elseif (strpos($value->transaction_code, '/PO-') !== false OR strpos($value->transaction_code, '/PNO-') !== false) {
		            	$row = 'warning';
		          	// check apakah kode transaksi mengandung /DT- (Direct Transaction)
		          	} elseif (strpos($value->transaction_code, '/DCT') !== false) {
		            	$row = 'active';
		          	} else {
		            	$row = '';
		          	}
		          
		          	echo '<tr class="' . $row . '">';
                    echo '<td class="heading">' . $no . '</td>';
                    echo '<td>' . date("d-M, y", strtotime($value->payment_due_date)). '</td>';

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
						echo '<td>' .  $value->transaction_name . ' (' . $value->transaction_category . ')</td>';
					} else {
						echo '<td>' .  $value->contact_name . ' (' . $value->contact_id . ')</td>';
					}
					
					echo '<td class="align-right">' . number_format($value->debit, 0) . '</td>';
					echo '<td class="align-right">' . number_format($value->credit, 0) . '</td>';
					
					echo '<td>' .  $value->currency . '</td>';
					echo '<td class="align-right">' .  $this->currency_rate[$value->currency]['jual'] . '</td>';

					$balance = ($value->debit * $this->currency_rate[$value->currency]['jual']) - ($value->credit * $this->currency_rate[$value->currency]['jual']);
					$total_saldo = $total_saldo + $balance;
					echo '<td class="align-right">' .  number_format($total_saldo, 0) . '</td>';
					echo "</tr>";
					$no++;
					$balance = 0;

					}
					?>
					</table>
					</div><!-- /.table-responsive -->
                    
        </div>
      </div><!-- /.main-content -->