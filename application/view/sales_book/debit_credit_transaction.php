<?php $this->renderFeedbackMessages(); // Render message success or not?>
<form class="form-horizontal" role="form" method="post" action="<?php echo Config::get('URL') . 'finance/saveDebitCreditTransaction/settled/';?>">
<div class="table-responsive">
<table border="1" class="table ExcelTable2007">
<tr>
	<th class="heading">&nbsp;</th>
	<th>Nama Pengantin</th>
	<th>Jenis</th>
	<th>Finishing</th>
	<th>Jumlah</th>
	<th>Harga</th>
	<th>Keterangan</th>
</tr>
<?php for ($i=1; $i <=20; $i++) { ?>
<tr>
	<td class="heading"><?php echo $i; ?></td>
	<td>
		<input id="name<?php echo $i; ?>" name="name_<?php echo $i; ?>" style="height: 38px; border: 0;" autocomplete="off" onclick="openEdit(<?php echo $i; ?>)">
	</td>
	<td>
		<input id="jenis<?php echo $i; ?>"  name="jenis_<?php echo $i; ?>" style="height: 38px; border: 0;" disabled>
	</td>
	<td>
		<select id="category<?php echo $i; ?>" id="category_<?php echo $i; ?>" style="display: none;">
            <option value="">Pilih Kategori</option>
            <?php foreach($this->pengeluaran as $key => $value) { ?>
                <option value="<?php echo $value->item_name; ?>"><?php echo $value->item_name;?></option>
            <?php } ?>
        </select>
	</td>

	<td>
		<input id="quantity<?php echo $i; ?>" class="text-right" name="quantity_<?php echo $i; ?>" style="height: 38px; border: 0;" autocomplete="off" disabled>
	</td>

	<td>
		<input id="price<?php echo $i; ?>" class="text-right" name="price_<?php echo $i; ?>" style="height: 38px; border: 0;" autocomplete="off" disabled>
	</td>
	<td>
		<input id="note<?php echo $i; ?>" name="note_<?php echo $i; ?>" style="height: 38px; border: 0;" autocomplete="off" disabled>
	</td>
</tr>
<?php } ?>
<tr>
	<td colspan="3"><a href="javascript: history.go(-1)" class="btn btn-sm btn-danger" style="width: 100%;">Cancel</a></td>
	<td colspan="4"><button type="submit" id="save-button" class="btn btn-sm btn-primary" style="width: 100%;">Save</button></td>
	<td style="display: none">endRow separator</td>
</tr>
</table>
</div><!-- /.table-responsive -->
</form>
<script type="text/javascript">

function openEdit(rowNumber) {
	var nameID = 'name' + rowNumber;
	var jenisID = 'jenis' + rowNumber;
	var categoryID = 'category' + rowNumber;
	var quantityID = 'quantity' + rowNumber;
	var priceID = 'price' + rowNumber;
	var noteID = 'note' + rowNumber;
	// Activate content editable to true on debit, credit and note

	document.getElementById(nameID).disabled = false;
	document.getElementById(jenisID).disabled = false;
	document.getElementById(categoryID).disabled = false;
	document.getElementById(quantityID).value = '<?php echo date('Y-m-d');?>';
	document.getElementById(priceID).disabled = false;
	document.getElementById(priceID).disabled = false;
}
function getNumberOnly(string) {
	var number = string.replace ( /[^0-9]/g, '' );
    return parseInt(number);
}
</script>