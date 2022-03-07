<div class="main-content">
                <div class="main-content-inner">
                    <div class="breadcrumbs ace-save-state" id="breadcrumbs">
                        <ul class="breadcrumb">
                            <li>
                                <a href="#" onclick="window.print();">
                                    <span class="badge badge-info">
                                    <i class="glyphicon glyphicon-print"></i> Print
                                    </span>
                                </a>
                            </li>
                        </ul><!-- /.breadcrumb -->
                    </div>
                    <div class="table-responsive">
					<table class="table table-striped table-bordered table-hover ExcelTable2007">
					<thead>
					<tr class="heading">
					<th rowspan="2">#</th>
					<th rowspan="2" class="center">Date</th>
					<th rowspan="2" class="center">#Transaksi</th>
					<th rowspan="2" class="center">Customer/Supplier</th>
					<th rowspan="2" class="center">Debit</th>
					<th rowspan="2" class="center">Credit</th>
					<th rowspan="2" class="center">Currency</th>
					<th colspan="2" class="center">Saldo</th>
					</tr>
					<tr class="heading">
					<th class="center">IDR</th>
					<th class="center">USD</th>
					</tr>
					</thead>
					<tbody>
					<?php
					$no = 1;
					$credit=0;
                    $debit=0;
                    $saldo_idr = 0;
                    $saldo_usd = 0;
					foreach($this->bukubesar as $key => $value) {
					//buat row beda warna untuk memudahkan  pengecekan dan mempersyantik penampilan
					// check apakah kode transaksi mengandung /SO- (sales ORDER) atau /SNO- (Sales Note Order)
                    if (strpos($value->transaction_code, '/SO-') !== false OR strpos($value->transaction_code, '/SNO-') !== false) { 
					    $row = 'success';
					// check apakah kode transaksi mengandung /PO- (purchase order) atau /PNO- (purchase note order)
					} elseif (strpos($value->transaction_code, '/PO-') !== false OR strpos($value->transaction_code, '/PNO-') !== false) {
						$row = 'warning';
					// check apakah kode transaksi mengandung /DT- (Direct Transaction)
					} elseif (strpos($value->transaction_code, '/DT') !== false) {
						$row = 'none';
					} else {
						$row = 'active';
					}


					echo '<tr class="' . $row . '">';
                    echo '<td class="heading">' . $no . '</td>';
                    echo '<td>' . date("d M, y", strtotime($value->payment_disbursement)). '</td>';

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
					echo '<td class="align-right">' . number_format($value->debit, 2) . '</td>';
					echo '<td class="align-right">' . number_format($value->credit, 2) . '</td>';
					
					echo '<td class="align-right">' . $value->currency . '</td>';
					
					if ($value->currency == 'IDR') {
						$saldo_idr = $saldo_idr + $value->debit - $value->credit;
					} elseif ($value->currency == 'USD') {
						$saldo_usd = $saldo_usd + $value->debit - $value->credit;
					}

					echo '<td class="align-right">' . number_format($saldo_idr, 2) . '</td>';
					echo '<td class="align-right">' . number_format($saldo_usd, 2) . '</td>';

					echo "</tr>";
					$no++;
					}
					?>
					</tbody>
					</table>
					</div><!-- /.table-responsive -->
                    
        </div>
      </div><!-- /.main-content -->