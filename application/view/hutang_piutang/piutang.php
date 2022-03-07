<div class="table-responsive" id="piutang" style="display: none;">
					<table class="table table-striped table-bordered table-hover ExcelTable2007">
					<tr class="info">
						<th colspan="11" class="text-center center">Daftar Piutang Customer <?php echo $this->date_range; ?></th>
					</tr>
					<tr class="info">
					<td>No</td>
					<td class="center">Nama Supplier</td>
					<td class="center">Nominal DPP</td>
					<td class="center">PPN</td>
					<td class="center">Jumlah Tagihan</td>
					<td class="center">Cur</td>
					<td class="center">Nomer Invoice</td>
					<td class="center">Tgl Invoice</td>
					<td class="center">Tgl Jatuh Tempo</td>
					<td class="center">Tgl Bayar</td>
					<td class="center">Uraian Tagihan</td>
					</tr>
					<?php
                    $no = 1;
                    $total_dpp = 0;
					$total_ppn = 0;
                    $total_piutang = 0;
					foreach($this->piutang as $key => $value) {			    
			          	echo '<tr>';
	                    echo '<td class="heading">' . $no . '</td>';
	                    echo '<td>' .  $value->contact_name . '</td>';
	                    echo '<td class="align-right">' . number_format($value->debit, 2) . '</td>';
	                    echo '<td class="align-right">' . number_format($value->ppn, 2) . '</td>';
	                    $total_tagihan = $value->debit + $value->ppn;
	                    echo '<td class="align-right">' . number_format($total_tagihan, 2) . '</td>';
	                    echo '<td>' .  $value->currency . '</td>';
	                    echo '<td>' .  $value->invoice_number . '</td>';
	                    echo '<td>' . (($value->invoice_date != '0000-00-00') ? date("d-M, y", strtotime($value->invoice_date)) : '') . '</td>';
	                    echo '<td>' . (($value->payment_due_date != '0000-00-00') ? date("d-M, y", strtotime($value->payment_due_date)) : '') . '</td>';
	                    echo '<td>' . (($value->payment_disbursement != '0000-00-00') ? date("d-M, y", strtotime($value->payment_disbursement)) : '') . '</td>';
						echo '<td>' .  $value->note . '<br>';

						if (strpos($value->transaction_code, '/SO-') !== false) {
							echo '<a href="' .  Config::get('URL') . 'so/detail/?so_number=' . urlencode($value->transaction_code) . '">' . $value->transaction_code . '</a>';
						} elseif (strpos($value->transaction_code, '/DCT-') !== false) {
							echo '<a href="' .  Config::get('URL') . 'finance/detail/?transaction_number=' . urlencode($value->transaction_code) . '">' . $value->transaction_code . '</a>';
						} else {
							echo $value->transaction_code;
						}
						echo '</td>';
						echo "</tr>";
						$no++;
						$total_dpp = $total_dpp + $value->debit;
						$total_ppn = $total_ppn + $value->ppn;
						$total_piutang = $total_piutang + $total_tagihan;
					}
					?>
					<tr>
						<td colspan="2">Total Piutang</td>
						<td class="align-right"><?php echo number_format($total_dpp, 2); ?></td>
						<td class="align-right"><?php echo number_format($total_ppn, 2); ?></td>
						<td class="align-right"><?php echo number_format($total_piutang, 2); ?></td>
						<td colspan="6">&nbsp;</td>
					</tr>
					</table>
					</div><!-- /.table-responsive -->
