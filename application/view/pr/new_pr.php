<?php $this->renderFeedbackMessages(); // Render message success or not?>
<style>
.ExcelTable2007 {
  border: 1px solid #B0CBEF;
  border-width: 1px 0px 0px 1px;
  font-size: 11pt;
  font-weight: 100;
  border-spacing: 0px;
  border-collapse: collapse;
  width: 100%;
}
.ExcelTable2007 td {
  border: none;
  background-color: white;
  border: 1px solid #D0D7E5;
  border-width: 0px 1px 1px 0px;
}
.ExcelTable2007 th {
  background: #e4ecf7; /* Old browsers */
  background-color: #E4ECF7;
  text-align: center;
  border: 1px solid #9EB6CE;
  font-weight: normal;
  font-size: 14px;
  border: 1px solid #9EB6CE;
  border-width: 0px 1px 1px 0px;
  vertical-align: middle;
  padding: 8px;
}
.ExcelTable2007 td.heading {
  background-color: #E4ECF7;
  text-align: center;
  border: 1px solid #9EB6CE;
  border-width: 0px 1px 1px 0px;
}
.ExcelTable2007 td input,
.ExcelTable2007 td textarea {
  padding: 0;
  margin: 0;
  border: 0;
  width: 100%;
}
</style>
<form class="form-horizontal" role="form" method="post" action="<?php echo Config::get('URL') . 'pr/newPrAction/';?>">
<div class="table-responsive">
	<table class="table ExcelTable2007">
		<tr>
			<th class="heading">#</th>
			<th width="50%">Material</th>
			<th>Jumlah</th>
			<th>Satuan</th>
			<th width="20%">Keterangan</th>
		</tr>
		<?php for ($i=1; $i <=15; $i++) { ?>
			<tr>
				<td class="heading">
					<?php echo $i; ?>
				</td>
				<td>
					<input type="text" name="order_name[<?php echo $i; ?>]" autocomplete="off" list="material_list">
				</td>
				<td>
					<input name="quantity[<?php echo $i; ?>]" type="number" class="text-right" autocomplete="off">
				</td>
				<td>
					<input name="unit[<?php echo $i; ?>]" type="text" class="text-left" autocomplete="off">
				</td>
				<td>
					<input name="note[<?php echo $i; ?>]" type="text" class="text-left" autocomplete="off">
				</td>
			</tr>
		<?php } ?>
		<tr>
			<td colspan="2"><a href="javascript: history.go(-1)" class="btn btn-sm btn-danger" style="width: 100%;">Cancel</a></td>
			<td colspan="3"><button type="submit" class="btn btn-sm btn-primary" style="width: 100%;">Save</button></td>
		</tr>
	</table>
</div><!-- /.table-responsive -->
</form>

<datalist id="material_list">
	<?php
		foreach ($this->material_list as $key => $value) {
			$material_name = str_replace(array("'", '"'),'', $value->material_name);
			echo '<option value="' . $value->material_code . ' --- ' . $material_name . '"/>';
		}
	?>
</datalist>
<?php //var_dump($this->material_list); ?>