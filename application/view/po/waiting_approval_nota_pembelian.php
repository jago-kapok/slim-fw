<form method="post"  action="<?php echo Config::get('URL') . 'Po/approvePrBulk/'; ?>">
  <div id="PO" class="table-responsive">
    <table class="table table-striped table-bordered table-hover">
      <thead>
        <tr class="danger">
          <th colspan="10" class="text-center">Nota Pembelian</th>     
        </tr>
        <tr class="danger">
          <th><INPUT type="checkbox" onchange="checkAll('nota-pembelian-checkbox')" name="nota-pembelian-checkbox" /></th>
          <td class="text-center">No</td>
          <td class="text-center">Date</td>
          <td class="text-center" colspan="2">Pembelian</td>
          <td class="text-center">Budget</td>
          <td class="text-center" colspan="2">Keterangan</td>
          <?php if (Auth::isPermissioned('director,finance,purchasing')) { ?>
            <td class="text-right" colspan="2">Harga</td>
          <?php } ?>        
        </tr>
      </thead>

      <tbody>
<?php
$no = ($this->page * $this->limit) - ($this->limit - 1);
$checkbox_number = 1;
foreach($this->nota_pembelian_list as $key => $value) {
  echo '<tr>';
//check if director limit approval
  if ($value->credit > $this->limit_approval->value AND $this->is_above_limit_approval) {
    echo '<td class="text-center"><input name="transaction_code[' . $checkbox_number . ']' . '" type="checkbox" value="' . $value->transaction_code .'" class="nota-pembelian-checkbox"></td>';
  } elseif ($value->credit < $this->limit_approval->value AND $this->is_under_limit_approval) {
    echo '<td class="text-center"><input name="transaction_code[' . $checkbox_number . ']' . '" type="checkbox" value="' . $value->transaction_code .'" class="nota-pembelian-checkbox"></td>';
  } else {
    echo '<td></td>';
  }

  echo '<td>' . $no . '</td>';
  echo '<td>' . date("d M, y", strtotime($value->created_timestamp)). '</td>';
  echo '<td colspan="2">' .  $value->transaction_name . '</td>';
  echo '<td>' .  $value->transaction_category . '</td>';
  echo '<td colspan="2">' .  $value->note . '</td>';
  echo '<td class="text-right" colspan="2">' . number_format($value->credit, 0) . '</td>';
  echo "</tr>";
  $no++;
  $checkbox_number++;
}
?>
</tbody>
</table>
</div>
<div class="text-right">
  <div class="btn-group btn-corner">
    <input type="submit" name="button-action"  class="btn btn-danger" value="Delete">
    <?php if (Auth::isPermissioned('director')) { ?>
      <input type="submit" name="button-action" class="btn btn-info" value="Approve">
    <?php } ?>
  </div>
</div>

</form>