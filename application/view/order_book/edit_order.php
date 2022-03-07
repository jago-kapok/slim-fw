<?php $this->renderFeedbackMessages(); // Render message success or not?>
<style type="text/css">
.autocomplete-suggestions {
    text-align: left; cursor: default; border: 1px solid #ccc; border-top: 0; background: #fff; box-shadow: -1px 1px 3px rgba(0,0,0,.1);
    /* core styles should not be changed */
    position: absolute; display: none; z-index: 9999; max-height: 254px; min-width: 300px; overflow: hidden; overflow-y: auto; box-sizing: border-box;
	}
.autocomplete-suggestion {
	position: relative; padding: 0 .6em; line-height: 23px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; font-size: 1.02em; color: #333;
	}
.autocomplete-suggestion b {
	font-weight: normal; color: #1f8dd6;
	}
.autocomplete-suggestion.selected {
	background: #f0f0f0;
}
</style>

<?php //Debuger::jam($this->transaction); ?>
<form class="form-horizontal" role="form" method="post" action="<?php echo Config::get('URL') . 'orderBook/saveEditOrder/';?>">
<div class="table-responsive">
	<table class="table ExcelTable2007">
	<th colspan="8">
		<input class="customer-id" name="customer" style="height: 38px; border: 0;width: 100%;" autocomplete="off" onclick="showCustomer()" value="<?php echo $this->transaction[0]->contact_id . ' -- ' . $this->transaction[0]->contact_name; ?>">
	</th>
	<tr><th>No </th>
		<th>Pesanan </th>
		<th>Kategori</th>
		<th>Jumlah </th>
		<th>Harga Satuan </th>
		<th>Total</th>
		<th>Keterangan</th>
	</tr>
	<?php
		$no = 1;
		foreach ($this->transaction as $key => $value) { ?>
			<tr>
				<td class="heading"><?php echo $no; ?></td>
				<td>
					<textarea id="pesanan-id<?php echo $no; ?>" name="pesanan_<?php echo $no; ?>" style="height: 38px; border: 0;"><?php echo $value->budget_item; ?></textarea>
				</td>
				<td>
					<select name="category_<?php echo $no; ?>">
		                <?php foreach($this->kategoriPenjualan as $key => $category) { ?>
		                    <option value="<?php echo $category->item_name; ?>" <?php if($category->item_name == $value->budged_category) echo "selected=selected";?>><?php echo $category->item_name;?></option>
		                <?php } ?>
		            </select>
				</td>
				<td>
					<input id="quantity<?php echo $no; ?>" name="quantity_<?php echo $no; ?>" class="text-right" style=" height: 38px; border: 0;" autocomplete="off" onkeyup="updateSubTotal(<?php echo $no; ?>)" value="<?php echo floatval($value->quantity);?>">
				</td>
				<td>
					<input id="price<?php echo $no; ?>" name="price_<?php echo $no; ?>" class="text-right" style="height: 38px; border: 0;" autocomplete="off" onkeyup="updateSubTotal(<?php echo $no; ?>)" value="<?php echo floatval($value->selling_price);?>">
				</td>
				<td id="total<?php echo $no; ?>" class="text-right" style="padding-top: 16px;"><?php echo number_format(($value->quantity * $value->selling_price),0); ?></td>
				<td>
					<textarea id="note-id<?php echo $no; ?>" name="note_<?php echo $no; ?>" style="height: 38px; border: 0;"><?php echo $value->note; ?></textarea>
					<input name="uid_<?php echo $no; ?>" type="hidden" value="<?php echo $value->uid;?>">
				</td>
			</tr>
	<?php $no++; } ?>
	
	<tr>
		<td colspan="4">
			<input type="hidden" name="transaction_number" value="<?php echo $this->transaction[0]->transaction_number; ?>">
			<input type="hidden" name="total_record" value="<?php echo ($no -1); ?>">
			<a href="javascript: history.go(-1)" class="btn btn-sm btn-danger" style="width: 100%;">Cancel</a>
		</td>
		<td colspan="4"><button type="submit" class="btn btn-sm btn-primary" style="width: 100%;">Save</button></td>
	</tr>
	</table>
</div><!-- /.table-responsive -->
</form>

<script src="<?php echo Config::get('URL'); ?>bootstrap-3.3.7/js/kasir-auto-complete.min.js"></script>
<script type="text/javascript">

// Autocomple contact
function showCustomer() {
	var classSelector = '.customer-id';
	var customerID = new autoComplete({
	    selector: classSelector,
	    minChars: 1,
	    source: function(term, suggest){
	        term = term.toLowerCase();
	        
	        var choices = [<?php
	          foreach ($this->customer_list as $key => $value) {
	            echo "'{$value->contact_id} -- {$value->contact_name}', ";
	          }
	        ?>];
	        var suggestions = [];
	        for (i=0;i<choices.length;i++)
	            if (~choices[i].toLowerCase().indexOf(term)) suggestions.push(choices[i]);
	        suggest(suggestions);
	    }
	});
 }


function updateSubTotal(rowNumber) {
  var quantity = document.getElementById('quantity' + rowNumber).value;
  var harga = document.getElementById('price' + rowNumber).value;
  var subTotal = getNumberOnly(quantity) * getNumberOnly(harga);
  document.getElementById('total' + rowNumber).innerHTML = moneyFormat(subTotal);
}

function isElementExist(elementID) {
	var myElem = document.getElementById(elementID);
	if (myElem === null) {
		return false;
	}
	return true;
}

function moneyFormat(x) {
  return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

function getNumberOnly(string) {
	var number = string.replace( /^\D+/g, '');
    return parseFloat(number);
}
</script>
<?php //var_dump($this->customer_list); ?>