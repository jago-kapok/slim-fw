<?php $this->renderFeedbackMessages(); // Render message success or not?>
<form class="form-horizontal" role="form" method="post" action="<?php echo Config::get('URL') . 'finance/saveDebitCreditTransaction/not-settled/';?>">
<div class="table-responsive">
<table border="1" class="table ExcelTable2007">
<tr>
	<th class="heading">&nbsp;</th>
	<th>Nama Transaksi</th>
	<th>Harga</th>
	<th>Kategori</th>
	<th>Keterangan</th>
	<th>Tanggal</th>
</tr>
<?php for ($i=1; $i <=20; $i++) { ?>
<tr>
	<td class="heading"><?php echo $i; ?></td>
	<td>
		<input id="name<?php echo $i; ?>" name="name_<?php echo $i; ?>" style="height: 38px; border: 0;" autocomplete="off" onclick="openEdit(<?php echo $i; ?>)">
	</td>
	<td>
		<input id="credit<?php echo $i; ?>" class="text-right" name="credit_<?php echo $i; ?>" style="height: 38px; border: 0;" autocomplete="off" onkeyup="showCredit(<?php echo $i; ?>)" disabled>
	</td>
	<td>
		<select id="categoryCredit<?php echo $i; ?>" name="category_<?php echo $i; ?>" style="display: none;">
            <option value="">Pilih Kategori</option>
            <?php foreach($this->pengeluaran as $key => $value) { ?>
                <option value="<?php echo $value->item_name; ?>"><?php echo $value->item_name;?></option>
            <?php } ?>
        </select>
	</td>
		<td>
		<input id="note<?php echo $i; ?>" name="note_<?php echo $i; ?>" style="height: 38px; border: 0;" autocomplete="off" onkeyup="showCredit(<?php echo $i; ?>)" disabled>
	</td>
	<td style="width: 100px;">
		<input class="form-control datepicker" data-date-format="yyyy-mm-dd" id="tanggal<?php echo $i; ?>" style="width: 100px;" name="date_<?php echo $i; ?>" disabled>
	</td>
</tr>
<?php } ?>
<tr>
	<td colspan="3"><a href="javascript: history.go(-1)" class="btn btn-sm btn-danger" style="width: 100%;">Cancel</a></td>
	<td colspan="4"><button type="submit" id="save-button" class="btn btn-sm btn-primary" style="width: 100%;">Save</button></td>
</tr>
</table>
</div><!-- /.table-responsive -->
</form>
<script type="text/javascript">

function openEdit(rowNumber) {
	var nameID = 'name' + rowNumber;
	var creditID = 'credit' + rowNumber;
	var categoryCreditID = 'categoryCredit' + rowNumber;
	var noteID = 'note' + rowNumber;
	var tanggalID = 'tanggal' + rowNumber;
	// Activate content editable to true on debit, credit and note

	document.getElementById(creditID).disabled = false;
	document.getElementById(tanggalID).disabled = false;
	document.getElementById(tanggalID).value = '<?php echo date('Y-m-d');?>';
	document.getElementById(noteID).disabled = false;
}

function showCredit(rowNumber) {
	var debitID = 'debit' + rowNumber;
	var creditID = 'credit' + rowNumber;
	var categoryCreditID = 'categoryCredit' + rowNumber;
  	var creditValue = getNumberOnly(document.getElementById(creditID).value);

	// only show when have value
	if (+creditValue > 0 ) {
		document.getElementById(categoryCreditID).style.display = 'inline'; //hide
		document.getElementById(categoryCreditID).name = 'category_' + rowNumber;
  	}
}
function getNumberOnly(string) {
	var number = string.replace ( /[^0-9]/g, '' );
    return parseInt(number);
}
</script>