<?php $this->renderFeedbackMessages(); // Render message success or not?>
<style type="text/css">
	.hidden {
		visibility: hidden;
		display: none;
	}
</style>
<div class="table-responsive">
<table border="1" class="table ExcelTable2007">
<tr>
	<td colspan="10">
		<div class="btn-group btn-corner">
			<a href="#" onclick="showAll()" class="btn btn-minier btn-inverse"> Semua
	        </a>

	        <a href="#" onclick="showCredit()" class="btn btn-minier btn-danger"> Hutang
	        </a>

	        <a href="#" onclick="showDebit()" class="btn btn-minier btn-success"> Piutang
	        </a>
	     </div>
	</td>
</tr>
<tr>
	<th>No</th>
	<th>Transaksi</th>
	<th class="center">#Kontak</th>
	<th>Debit</th>
	<th>Credit</th>
	<th class="center">PPN</th>
	<th class="center">Jumlah Tagihan</th>
	<th class="center">Cur</th>
	<th class="center">Nomer Invoice</th>
	<th class="center">Tgl Invoice</th>
	<th class="center">Tgl Jatuh Tempo</th>
	<th class="center">Uraian Tagihan</th>
	<th class="center">Tgl Bayar</th>
	<th style="width: 120px;">Action</th>
	
</tr>
<?php
					$no = 1;
					$credit=0;
                    $debit=0;
                    $debit_credit = '';
					foreach($this->unconfirmed_payment as $key => $value) {
						if ($value->debit > 0) {
							$debit_credit .= 'debit';
						}

						if ($value->credit > 0) {
							$debit_credit .= 'credit';
						}

						echo '<tr id="rowID' . $no . '" class="' . $debit_credit . '">';
	                    echo '<td class="heading">' . $no . '</td>';
	                    echo '<td id="transaction-reference-' . $no . '">' .  $value->transaction_code . '</td>';
						echo '<td>' .  $value->contact_name . ' (' . $value->contact_id . ')</td>';
						echo '<td class="align-right" id="debit-' . $no . '">' . FormaterModel::decimalNumberUnderline($value->debit) . '</td>';
						echo '<td class="align-right" id="credit-' . $no . '">' . FormaterModel::decimalNumberUnderline($value->credit) . '</td>';

						echo '<td class="align-right" id="ppn-' . $no . '">';
						if ($value->ppn_credit > 0) {
							echo FormaterModel::decimalNumberUnderline($value->ppn_credit);
						} elseif ($value->ppn_debit > 0) {
							echo FormaterModel::decimalNumberUnderline($value->ppn_debit);
						}
						echo '</td>';

						echo '<td class="align-right">';
						if ($value->credit > 0) {
							echo FormaterModel::decimalNumberUnderline($value->ppn_credit + $value->credit);
						} elseif ($value->debit > 0) {
							echo FormaterModel::decimalNumberUnderline($value->ppn_debit + $value->debit);
						}
						echo '</td>';

						echo '<td id="currency-' . $no . '">' .  $value->currency . '</td>';
	                    echo '<td id="invoice-number-' . $no . '">' .  $value->invoice_number . '</td>';
	                    echo '<td id="invoice-date-' . $no . '">' . (($value->invoice_date != '0000-00-00') ? date("d-M, Y", strtotime($value->invoice_date)) : '') . '</td>';
	                    echo '<td id="payment-due-date-' . $no . '">' . (($value->payment_due_date != '0000-00-00') ? date("d-M, Y", strtotime($value->payment_due_date)) : ''). '</td>';
						echo '<td id="note-' . $no . '">' .  $value->note . '</td>';

						if ($value->status == 1) {
							echo '<td>' . date("d M, Y", strtotime($value->payment_disbursement)). '</td>';
							echo '<td>Confirmed</td>';
						} else {
							if (Auth::isPermissioned('director,finance')) {
							echo '<td style="width: 100px;" id="tanggalPembayaran' . $no . '"><input class="form-control datepicker" id="tanggal' . $no . '" style="width: 100px;" data-date-format="yyyy-mm-dd"></td>';
							echo '<td class="align-right" id="saveButton' . $no . '">
							<div class="btn-group btn-corner">
								<button type="button" id="save-button" onclick="finishAndSave(' . $no . ',\'' . $value->uid . '\');" class="btn btn-minier btn-primary">Confirm</button>
								<a href="#edit-payment" data-toggle="modal" data-no-id="' . $no . '" data-uid="' . $value->uid . '" class="btn btn-minier btn-warning"> &nbsp; Edit &nbsp;</a>
								<button type="button" id="save-button" onclick="deleteAndSave(' . $no . ',\'' . $value->uid . '\');" class="btn btn-minier btn-danger">Delete</button>
							</div>
								</td>';
							} elseif (Auth::isPermissioned('purchasing')) {
								echo '<td style="width: 100px;" id="tanggalPembayaran' . $no . '"></td>';
								echo '<td class="align-right" id="saveButton' . $no . '">
								<div class="btn-group btn-corner">
									<a href="#edit-payment" data-toggle="modal" data-no-id="' . $no . '" data-uid="' . $value->uid . '" class="btn btn-minier btn-warning"> &nbsp; Edit &nbsp;</a>

									<button type="button" id="save-button" onclick="deleteAndSave(' . $no . ',\'' . $value->uid . '\');" class="btn btn-minier btn-danger">Delete</button>
								</div>
									</td>';
							}
						}
						
											
						echo "</tr>";
						$no++;
						$debit_credit = '';
					}
					?>
</table>
</div><!-- /.table-responsive -->


<!-- MODAL UPLOAD FOTO -->
<div class="modal fade" id="edit-payment" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
    <form action="<?php echo Config::get('URL') . 'BukuBesar/updatePaymentAction';?>" method="post" enctype="multipart/form-data">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Update Pembayaran</h4>
      </div>
      <div class="modal-body">
          <div class="form-group">
              <label>Debit</label>
              <input type="text" name="debit"  class="form-control" id="edit-debit">
          </div>

          <div class="space"></div>

          <div class="form-group">
              <label>Credit</label>
              <input type="text" name="credit"  class="form-control" id="edit-credit">
          </div>

          <div class="space"></div>

          <div class="form-group">
              <label>PPN</label>
              <input type="text" name="ppn"  class="form-control" id="edit-ppn">
          </div>

          <div class="space"></div>

          <div class="form-group">
              <label>Cur</label>
              <input type="text" name="currency"  list="currency-code" class="form-control" id="edit-currency">
          </div>

          <div class="space"></div>

          <div class="form-group">
              <label>Nomer Invoice</label>
              <input type="text" name="invoice_number"  class="form-control" id="edit-invoice-number">
          </div>

          <div class="space"></div>

          <div class="form-group">
              <label>Tgl Invoice</label>
              <input type="text" name="invoice_date"  class="form-control datepicker" id="edit-invoice-date" data-date-format="yyyy-mm-dd">
          </div>

          <div class="space"></div>

          <div class="form-group">
              <label>Tgl Jatuh Tempo</label>
              <input type="text" name="payment_due_date"  class="form-control datepicker" id="edit-payment-due-date" data-date-format="yyyy-mm-dd">
          </div>

          <div class="space"></div>

          <div class="form-group">
              <label>Keterangan</label>
              <input type="text" name="note"  class="form-control" id="edit-note">
          </div>

          <div class="space"></div>

          <input type="hidden" name="uid" id="edit-uid">
          <input type="hidden" name="transaction_reference" id="edit-transaction-reference">
          <input type="hidden" name="forward" value="<?php echo $_SERVER['REQUEST_URI']; ?>">
          <div class="space"></div>
          <br>
      </div>
      <div class="modal-footer">
        <div class="btn-group btn-corner" role="group">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-success">Update</button>
        </div>
      </div>
      </form>
    </div>
  </div>
</div>
<!-- MODAL UPLOAD FOTO -->

<datalist id="currency-code">
  <option value="USD">
  <option value="IDR">
</datalist>

<script type="text/javascript">

function finishAndSave(no, uid)
{
	var tanggalPencairan = 'tanggal' + no;
	var rowID = 'rowID' + no;
	var tanggalPembayaran = 'tanggalPembayaran' + no;
	var saveButton = 'saveButton' + no;
  	var payment_disbursement = document.getElementById(tanggalPencairan).value;

  	if (!payment_disbursement.length) {
      alert("Tanggal Pembayaran Belum Diisi!");
    } else {
		var http = new XMLHttpRequest();
		var url = "<?php echo Config::get('URL'); ?>bukuBesar/confirmPaymentAction";
		var params = "payment_disbursement=" + payment_disbursement + "&uid=" + uid;
		http.open("POST", url, true);
		//console.log(params);
		//Send the proper header information along with the request
		http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

		http.onreadystatechange = function() {//Call a function when the state changes.
		  if(http.readyState === XMLHttpRequest.DONE && http.status == 200) {
		  		var salesCode = http.response;
	          	//check response from server, if contain strting sucess, save (force user to clik save again) and reset page
	          	if (salesCode.indexOf('SUKSES') === -1) {
	            	alert(salesCode);
	            } else {
	            	alert(http.responseText);
		      		document.getElementById(rowID).outerHTML='';
	            }
		  }
		}
		http.send(params);
	}
}

function deleteAndSave(no, uid)
{
	var tanggalPencairan = 'tanggal' + no;
	var rowID = 'rowID' + no;
	var tanggalPembayaran = 'tanggalPembayaran' + no;
	var saveButton = 'saveButton' + no;


		var http = new XMLHttpRequest();
		var url = "<?php echo Config::get('URL'); ?>bukuBesar/deletePaymentAction";
		var params = "uid=" + uid;
		http.open("POST", url, true);
		//console.log(params);
		//Send the proper header information along with the request
		http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

		http.onreadystatechange = function() {//Call a function when the state changes.
		  if(http.readyState === XMLHttpRequest.DONE && http.status == 200) {
		  		var salesCode = http.response;
	          	//check response from server, if contain strting sucess, save (force user to clik save again) and reset page
	          	if (salesCode.indexOf('SUKSES') === -1) {
	            	alert(salesCode);
	            } else {
	            	alert(http.responseText);
		      		document.getElementById(rowID).outerHTML='';
	            }
		  }
		}
		http.send(params);
}

function showDebit()
{
	var divsToHide = document.getElementsByClassName("credit"); //divsToHide is an array
    for(var i = 0; i < divsToHide.length; i++) {
        divsToHide[i].classList.add("hidden"); // or
    }

    var divsToHide = document.getElementsByClassName("debit"); //divsToHide is an array
    for(var i = 0; i < divsToHide.length; i++) {
        divsToHide[i].classList.remove("hidden");
    }
}

function showCredit()
{
	var divsToHide = document.getElementsByClassName("credit"); //divsToHide is an array
    for(var i = 0; i < divsToHide.length; i++) {
        divsToHide[i].classList.remove("hidden");
    }

    var divsToHide = document.getElementsByClassName("debit"); //divsToHide is an array
    for(var i = 0; i < divsToHide.length; i++) {
        divsToHide[i].classList.add("hidden"); // or
    }
}

function showAll()
{
	var divsToHide = document.getElementsByClassName("credit"); //divsToHide is an array
    for(var i = 0; i < divsToHide.length; i++) {
        divsToHide[i].classList.remove("hidden");
    }

    var divsToHide = document.getElementsByClassName("debit"); //divsToHide is an array
    for(var i = 0; i < divsToHide.length; i++) {
        divsToHide[i].classList.remove("hidden");
    }
}
</script>
