<?php $this->renderFeedbackMessages(); // Render message success or not?>
<div class="table-responsive">

<table border="1" class="table ExcelTable2007">
<tr>
	<th class="heading">&nbsp;</th>
	<th>Nama Transaksi</th>
	<th>Debit </th>
	<th>Credit </th>
	<th>Kategori </th>
	<th>Tanggal </th>
	<th>Keterangan</th>
</tr>
<?php
								for ($i=1; $i <=20; $i++) { ?>
<tr>
	<td align="left" valign="bottom" class="heading"><?php echo $i; ?></td>
	<td id="name<?php echo $i; ?>" contenteditable="true" align="left" valign="bottom" oninput="openEdit(<?php echo $i; ?>)"></td>
	<td id="debit<?php echo $i; ?>" contenteditable="false" align="right" valign="bottom" onkeyup="showDebit(<?php echo $i; ?>)" ></td>
	<td id="credit<?php echo $i; ?>" contenteditable="false" align="right" valign="bottom" onkeyup="showCredit(<?php echo $i; ?>)" ></td>
	<td align="left" valign="bottom">
		<select id="categoryCredit<?php echo $i; ?>" style="display: none;">
            <option value="">Pilih Kategori</option>
            <?php foreach($this->pengeluaran as $key => $value) { ?>
                <option value="<?php echo $value->item_name; ?>"><?php echo $value->item_name;?></option>
            <?php } ?>
        </select>

        <select id="categoryDebit<?php echo $i; ?>" style="display: none;">
            <option value="">Pilih Kategori</option>
            <?php foreach($this->pemasukan as $key => $value) { ?>
                <option value="<?php echo $value->item_name; ?>"><?php echo $value->item_name;?></option>
            <?php } ?>
        </select>
	</td>
	<td style="width: 100px;"><input class="form-control datepicker" id="tanggal<?php echo $i; ?>" style="width: 100px;"></td>
	<td id="note<?php echo $i; ?>" contenteditable="false" align="left" valign="bottom"></td>
	<td id="endRow<?php echo $i; ?>" style="display: none">endRow separator</td>
</tr>
<?php } ?>
<tr>
	<td colspan="3"><a href="javascript: history.go(-1)" class="btn btn-sm btn-danger" style="width: 100%;">Cancel</a></td>
	<td colspan="4"><button type="button" id="save-button" onclick="finishAndSave();" class="btn btn-sm btn-primary" style="width: 100%;">Save</button></td>
	<td style="display: none">endRow separator</td>
</tr>
</table>
</div><!-- /.table-responsive -->

<script type="text/javascript">

function openEdit(rowNumber) {
	var nameID = 'name' + rowNumber;
	var debitID = 'debit' + rowNumber;
	var creditID = 'credit' + rowNumber;
	var categoryDebitID = 'categoryDebit' + rowNumber;
	var categoryCreditID = 'categoryCredit' + rowNumber;
	var noteID = 'note' + rowNumber;
	var tanggalID = 'tanggal' + rowNumber;
	var endRowID = 'endRow' + rowNumber;
	var nameEdit = document.getElementById(nameID);
	var debitEdit = document.getElementById(debitID);
	var creditEdit = document.getElementById(creditID);
	var noteEdit = document.getElementById(noteID);
	var tanggalEdit = document.getElementById(tanggalID);
	var endRow = document.getElementById(endRowID);
	// Activate content editable to true on debit, credit and note
	 
	debitEdit.setAttribute("contenteditable", true);
	creditEdit.setAttribute("contenteditable", true);
	noteEdit.setAttribute("contenteditable", true);

	//Add saved-data class to name, credit, debit, note (category will be given on showDebit/showCredit)
	nameEdit.classList.add("saved-data");
	debitEdit.classList.add("saved-data");
	creditEdit.classList.add("saved-data");
	noteEdit.classList.add("saved-data");
	tanggalEdit.classList.add("saved-data");
	endRow.classList.add("saved-data");
}

function showDebit(rowNumber) {
	var debitID = 'debit' + rowNumber;
	var creditID = 'credit' + rowNumber;
	var categoryDebitID = 'categoryDebit' + rowNumber;
	var categoryCreditID = 'categoryCredit' + rowNumber;
  	var debitValue = getNumberOnly(document.getElementById(debitID).innerHTML);
  	var creditValue = getNumberOnly(document.getElementById(creditID).innerHTML);
  	
	// only show when have value
	if (+debitValue > 0) {
  		document.getElementById(categoryDebitID).style.display = 'inline'; //show
		document.getElementById(categoryCreditID).style.display = 'none'; //hide
		document.getElementById(categoryDebitID).classList.add("saved-data"); //add class
		document.getElementById(categoryCreditID).classList.remove("saved-data"); //delete class
		document.getElementById(creditID).innerHTML = '';
  	}
}

function showCredit(rowNumber) {
	var debitID = 'debit' + rowNumber;
	var creditID = 'credit' + rowNumber;
	var categoryDebitID = 'categoryDebit' + rowNumber;
	var categoryCreditID = 'categoryCredit' + rowNumber;
  	var debitValue = getNumberOnly(document.getElementById(debitID).innerHTML);
  	var creditValue = getNumberOnly(document.getElementById(creditID).innerHTML);
  	

	// only show when have value
	if (+creditValue > 0 ) {
  		document.getElementById(categoryDebitID).style.display = 'none'; //show
		document.getElementById(categoryCreditID).style.display = 'inline'; //hide
		document.getElementById(categoryDebitID).classList.remove("saved-data"); //add class
		document.getElementById(categoryCreditID).classList.add("saved-data"); //delete class
		document.getElementById(debitID).innerHTML = '';
  	}
}

function finishAndSave()
{
  //Make string from purchased product table
  var cell = document.getElementsByClassName("saved-data");
  var i = 0;
  var product_string = '';
  while(cell[i] != undefined) {
  	if (cell[i].tagName === 'SELECT' || cell[i].tagName === 'INPUT') { // because we take value on dropdown value select (number three in fields)
  		product_string += cell[i].value + ' --- ';
  	} else if (cell[i].innerHTML == 'endRow separator') {
  		product_string += ' ___ ';
  	} else  {
        product_string += cell[i].innerHTML + ' --- ';
     }
    i++;
  }//end while
  //Send the string to server
  var http = new XMLHttpRequest();
  var url = "<?php echo Config::get('URL'); ?>cashTransaction/saveCashTransaction";
  var params = "transaction_list=" + product_string;
  http.open("POST", url, true);
console.log(params);
  //Send the proper header information along with the request
  http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

  http.onreadystatechange = function() {//Call a function when the state changes.
      if(http.readyState === XMLHttpRequest.DONE && http.status == 200) {
          alert(http.responseText);
          resetPage();
      }
  }
  http.send(params);
  //window.print();
  
}
function resetPage() {
    window.location.reload(false); 
}
function getNumberOnly(string) {
	var number = string.replace ( /[^0-9]/g, '' );
    return parseInt(number);
}
</script>