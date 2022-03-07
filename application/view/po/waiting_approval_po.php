<form method="post"  action="<?php echo Config::get('URL') . 'Po/approvePrBulk/'; ?>">
  <div id="PO" class="table-responsive">
    <table class="table table-striped table-bordered table-hover">
      <thead>
        <tr class="info">
          <th colspan="10" class="text-center">PERMINTAAN PERSETUJUAN PO</th>     
        </tr>
        <tr class="info">
          <th><INPUT type="checkbox" onchange="checkAll('purchase-order-checkbox')" name="purchase-order-checkbox" /></th>
          <th class="text-center">No</th>
          <th class="text-center">Date</th>
          <th class="text-center">#PO</th>
          <th class="text-center">Supplier</th>
          <th class="text-center">Item</th>
          <th class="text-right">Jumlah</th>
          <?php if (Auth::isPermissioned('director,finance,purchasing')) { ?>
            <th class="text-right">Harga</th>
            <th class="text-right">PPN</th>
            <th class="text-right">Total</th>
          <?php } ?>        
        </tr>
      </thead>

      <tbody>
        <?php
        $no = ($this->page * $this->limit) - ($this->limit - 1);
        $checkbox_number = 1;
        foreach($this->po_list as $key => $value) {
          $total_purchase_per_po_number = 0;
          $total_material_name = explode('-, -', $value->material_name);
          $total_material_code = explode('-, -', $value->material_code);
          $total_quantity = explode('-, -', $value->quantity);
          $total_price = explode('-, -', $value->purchase_price);
          $total_currency = explode('-, -', $value->purchase_currency);
          $total_tax = explode('-, -', $value->purchase_tax);
          $total_currency_rate = explode('-, -', $value->purchase_currency_rate);
          $row = count($total_material_name);
          if ($value->feedback_note != '') {
            $feedbackStatus = "warning";
            $feedbackNote = "<blockquote>{$value->feedback_note}</blockquote>";
          } else {
            $feedbackStatus = "";
            $feedbackNote = "";
          }

          if ($row > 1) {
            //untuk check apakah total pembelian melebihi batas maksimal dan minimal approval direksi
            for ($i=1; $i <= $row; $i++) {
              $x = $i - 1;
              $sub_total = ($total_quantity[$x] * $total_price[$x] * $total_currency_rate[$x]);
              $sub_total_with_ppn = $sub_total + (($total_tax[$x]/100) * $sub_total);
              $total_purchase_per_po_number = $total_purchase_per_po_number + $sub_total_with_ppn;
            } //end for

            for ($i=1; $i <= $row; $i++) {
              $x = $i - 1;
              if ($i === 1) {
                echo '<tr class="' . 'row-' . $no . ' ' . $feedbackStatus . '">';

                //check limit approval
                if ($total_purchase_per_po_number > $this->limit_approval->value AND $this->is_above_limit_approval) {
                  echo '<td rowspan="' . $row . '" class="text-center"><input name="transaction_number[' . $checkbox_number . ']' . '" type="checkbox" value="' . $value->transaction_number .'" class="purchase-order-checkbox"></td>
                  ';
                } elseif ($total_purchase_per_po_number < $this->limit_approval->value AND $this->is_under_limit_approval) {
                  echo '<td rowspan="' . $row . '" class="text-center"><input name="transaction_number[' . $checkbox_number . ']' . '" type="checkbox" value="' . $value->transaction_number .'" class="purchase-order-checkbox"></td>
                  ';
                } else {
                  echo '<td rowspan="' . $row . '"></td>';
                }

                echo '<td class="text-right" rowspan="' . $row . '">' . $no . '</td>';
                echo '<td rowspan="' . $row . '">' . date("d M, y", strtotime($value->created_timestamp)) . '</td>';
                echo '<td  rowspan="' . $row . '">
                <a href="' .  Config::get('URL') . 'po/detail/?po_number=' . urlencode($value->transaction_number) . '">' . $value->transaction_number . '</a><br>'. $feedbackNote.'</td>';
                echo '<td rowspan="' . $row . '">' . $value->contact_name . '</td>';

                if ($total_material_name[$x] != 'kosong') {
                  echo '<td>' . $total_material_name[$x] . '</td>';
                } else {
                  echo '<td>' . $total_material_code[$x] . '</td>';
                }

                echo '<td class="text-right">' . number_format($total_quantity[$x], 0) . '</td>';
                if (Auth::isPermissioned('director,finance,purchasing')) {
                  echo '<td class="text-right">' . number_format($total_price[$x], 2) . ' ' . $total_currency[$x] . '</td>';
                  echo '<td class="text-right">' . number_format($total_tax[$x], 0) . '</td>';
                  $sub_total = ($total_quantity[$x] * $total_price[$x]);
                  $sub_total_with_ppn = $sub_total + (($total_tax[$x]/100) * $sub_total);
                  echo '<td class="text-right">' . number_format($sub_total_with_ppn, 2) . '</td>';
                }
                echo "</tr>";
              } else {
                echo '<tr class="' . 'row-' . $no . ' ' . $feedbackStatus . '">';

                if ($total_material_name[$x] != 'kosong') {
                  echo '<td>' . $total_material_name[$x] . '</td>';
                } else {
                  echo '<td>' . $total_material_code[$x] . '</td>';
                }

                echo '<td class="text-right">' . number_format($total_quantity[$x], 0) . '</td>';
                if (Auth::isPermissioned('director,finance,purchasing')) {
                  echo '<td class="text-right">' . number_format($total_price[$x], 2) . ' ' . $total_currency[$x] . '</td>';
                  echo '<td class="text-right">' . number_format($total_tax[$x], 0) . '</td>';
                  $sub_total = ($total_quantity[$x] * $total_price[$x]);
                  $sub_total_with_ppn = $sub_total + (($total_tax[$x]/100) * $sub_total);
                  echo '<td class="text-right">' . number_format($sub_total_with_ppn, 2) . '</td>';
                }
                echo "</tr>";
              }

            } //end for
          } else {
            //untuk check apakah total pembelian melebihi batas maksimal dan minimal approval direksi
            $sub_total = ($value->quantity * $value->purchase_price * $value->purchase_currency_rate);
            $sub_total_with_ppn = $sub_total + (($value->purchase_tax/100) * $sub_total);
            $total_purchase_per_po_number = $total_purchase_per_po_number + $sub_total_with_ppn;

            echo '<tr class="' . 'row-' . $no . ' ' . $feedbackStatus . '">';

            //check if director limit approval
            if  ($total_purchase_per_po_number > $this->limit_approval->value AND $this->is_above_limit_approval) {
              echo '<td class="text-center"><input name="transaction_number[' . $checkbox_number . ']' . '" type="checkbox" value="' . $value->transaction_number .'" class="purchase-order-checkbox"></td>';
            } elseif ($total_purchase_per_po_number < $this->limit_approval->value AND $this->is_under_limit_approval) {
                  echo '<td class="text-center"><input name="transaction_number[' . $checkbox_number . ']' . '" type="checkbox" value="' . $value->transaction_number .'" class="purchase-order-checkbox"></td>';
            } else {
              echo '<td></td>';
            }

            echo '<td class="text-right">' . $no . '</td>';
            echo '<td>' . date("d M, y", strtotime($value->created_timestamp)) . '</td>';
            echo '<td><a href="' .  Config::get('URL') . 'po/detail/?po_number=' . urlencode($value->transaction_number) . '">' . $value->transaction_number . '</a><br>'. $feedbackNote.'</td>';
            echo '<td>' . $value->contact_name . '</td>';

            if ($value->material_name != 'kosong') {
              echo '<td>' . $value->material_name . '</td>';
            } else {
              echo '<td>' . $value->material_code . '</td>';
            }

            echo '<td class="text-right">' . number_format($value->quantity, 0) . '</td>';
            if (Auth::isPermissioned('director,finance,purchasing')) {
              echo '<td class="text-right">' . number_format($value->purchase_price, 2) . ' ' . $value->purchase_currency . '</td>';
              echo '<td class="text-right">' . number_format($value->purchase_tax, 0) . '</td>';
              echo '<td class="text-right">' . number_format($sub_total_with_ppn, 2) . '</td>';
            }
            echo "</tr>";
          } //end if

        echo '<tr class="success"><td class="text-left" colspan="7">Total Pembelian</td>
        <td class="text-right" colspan="3">' . number_format($total_purchase_per_po_number, 2) . '</td></tr>';

        $no++;
        $checkbox_number++;
      } //end foreach
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