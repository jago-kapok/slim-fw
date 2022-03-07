<?php $this->renderFeedbackMessages(); // Render message success or not?>
<style type="text/css">
.autocomplete-suggestions {
    text-align: left; cursor: default; border: 1px solid #ccc; border-top: 0; background: #fff; box-shadow: -1px 1px 3px rgba(0,0,0,.1);
    /* core styles should not be changed */
    position: absolute; display: none; z-index: 9999; max-height: 254px; max-width: 300px;overflow: hidden; overflow-y: auto; box-sizing: border-box;
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

<form class="form-horizontal" role="form" method="post" action="<?php echo Config::get('URL') . 'orderBook/saveOrder/';?>">
<div class="table-responsive">
	<table class="table ExcelTable2007">
	<tr>
		<th colspan="8">
			<input class="customer-id" name="customer" style="height: 38px; border: 0;width: 100%;" autocomplete="off" onclick="showCustomer()" placeholder="Pilih Customer Disini">
		</th>
	</tr>
	<tr>
		<th class="heading">#</th>
		<th>Pesanan</th>
		<th>Kategori</th>
		<th>Jumlah </th>
		<th>Harga Satuan </th>
		<th>Total</th>
		<th>Keterangan</th>
	</tr>
	<?php for ($i=1; $i <=15; $i++) { ?>
	<tr>
		<td class="heading"><?php echo $i; ?></td>
		<td>
			<textarea id="pesanan-id<?php echo $i; ?>" name="pesanan_<?php echo $i; ?>" oninput="showCategory(<?php echo $i; ?>)" style="height: 38px; border: 0;"></textarea>
		</td>
		<td id="category-id<?php echo $i; ?>"></td>
		<td>
			<input id="quantity<?php echo $i; ?>" name="quantity_<?php echo $i; ?>" class="text-right" style=" height: 38px; border: 0;" autocomplete="off" onkeyup="updateSubTotal(<?php echo $i; ?>)">
		</td>
		<td>
			<input id="price<?php echo $i; ?>" name="price_<?php echo $i; ?>" class="text-right" style="height: 38px; border: 0;" autocomplete="off" onkeyup="updateSubTotal(<?php echo $i; ?>)">
		</td>
		<td id="total<?php echo $i; ?>" class="text-right" style="padding-top: 16px;"></td>
		<td>
			<textarea id="note-id<?php echo $i; ?>" name="note_<?php echo $i; ?>" oninput="showCategory(<?php echo $i; ?>)" style="height: 38px; border: 0;"></textarea>
		</td>
	</tr>
	<?php } ?>
	<tr>
		<td colspan="4"><a href="javascript: history.go(-1)" class="btn btn-sm btn-danger" style="width: 100%;">Cancel</a></td>
		<td colspan="4"><button type="submit" class="btn btn-sm btn-primary" style="width: 100%;">Save</button></td>
		<td style="display: none">endRow separator</td>
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

function showCategory(rowNumber) {
  var text = '<select id="category-item' + rowNumber + '"  name="category_' + rowNumber + '">';
  text += '<option>Pilih Kategori</option>';
  <?php foreach($this->kategoriPenjualan as $key => $value) { ?>
                text += '<option value="<?php echo $value->item_name; ?>"><?php echo $value->item_name;?></option>';
            <?php } ?>
  text += '</select>';
  
  var categoryItem = 'category-item' + rowNumber;
  if (isElementExist(categoryItem) === false) { //check if ID already exist/already inserted, to prevent more than one insert text in DOM
  	var categoryID = 'category-id' + rowNumber;
  	var categorySelect = document.getElementById(categoryID); //get value from input form
  	categorySelect.insertAdjacentHTML('beforeend', text);
  }
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