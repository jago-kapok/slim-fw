<div class="main-content" id="main-container">
  <div class="main-content-inner">

    <?php $this->renderFeedbackMessages(); // Render message success or not?>

    <div class="table-responsive">
      <form method="post"  action="<?php echo Config::get('URL') . 'pr/newPrAction/'; ?>">
            <table class="table table-striped table-bordered table-hover ExcelTable2007">
              <thead>
                <tr>
                  <th rowspan="2">
                    <input type="checkbox" onchange="checkAll(this)" name="chk[]" />
                  </th>
                  <th  class="center" rowspan="2">#</th>
                  <th  class="center" rowspan="2">Kode</th>
                  <th  class="center" rowspan="2">Nama</th>
                  <th  class="center" colspan="4">Jumlah</th>
                  <th  class="center" rowspan="2">Keterangan</th>
                </tr>
                <tr>
                  <th  class="center">Order</th>
                  <th  class="center">Stock</th>
                  <th  class="center">Safety Stock</th>
                  <th  class="center">Satuan</th>
                  
                </tr>
              </thead>
            <tbody>
            <?php
            $no = 1;
            foreach($this->material_list as $key => $value) {
                    echo '<tr>';
                    echo '<td class="text-center"><input name="order_name[' . $no . ']' . '" type="checkbox" value="' . $value->material_code . ' --- 1"></td>';
                    echo '<td class="text-right">' . $no . '</td>';
                    echo '<td>' . $value->material_code . '</td>';
                    echo '<td>' . $value->material_name . '</td>';
                    echo '<td><input type="text" class="form-control text-right" name="quantity[' . $no . ']" value="' . ceil($value->quantity) .'" required style="text-decoration: underline;"/></td>';
                    echo '<td class="text-right">' . floatval($value->quantity_stock) . '</td>';
                    echo '<td class="text-right">' . ceil($value->minimum_balance) . '</td>';
                    echo '<td><input type="text" class="form-control" name="unit[' . $no . ']" value="' . $value->unit .'" required style="text-decoration: underline;"/></td>';
                    echo '<td><textarea class="form-control" name="note[' . $no . ']" style="text-decoration: underline;">transaction reference: ' .  $this->transaction_reference . ' </textarea></td>';
                    echo "</tr>";
                $no++;
            }
            ?>
            <tr>
              <td colspan="9" class="align-right">
                <input type="hidden" name="transaction_reference" value="<?php echo $this->transaction_reference; ?>" />
                <input type="hidden" name="total_record" value="<?php echo ($no - 1); ?>" />
                <button type="submit" class="btn btn-primary">Create PR</button>
              </td>
            </tr>
            
              </tbody>
            </table>
          </div>
          </form>
  </div>
</div>