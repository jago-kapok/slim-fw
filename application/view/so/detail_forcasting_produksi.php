      <div class="panel panel-success">
        <div class="panel-heading">
          <h3 class="panel-title">Production Forcasting</h3>
        </div>
            <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover ExcelTable2007">
              <thead>
                <tr>
                  <th class="center" rowspan="2">#</th>
                  <th class="center" rowspan="2">Kode</th>
                  <th class="center" rowspan="2">Nama</th>
                  <th class="center" rowspan="2">Jumlah</th>
                  <th class="center" rowspan="2">Satuan</th>
                  <th class="center" rowspan="2">Stock</th>
                  <?php if (Auth::isPermissioned('director,sales,finance,management')) {  ?>
                  <th class="center" colspan="2">Harga Pembelian</th>
                  <th class="center" rowspan="2">Biaya Produksi</th>
                  <?php } ?>
                  <th class="center" rowspan="2">Keterangan</th>
                </tr>
                <?php if (Auth::isPermissioned('director,sales,finance,management')) {  ?>
                <tr>
                  <th class="center" rowspan="2">Harga</th>
                  <th class="center" rowspan="2">Satuan</th>
                </tr>
                <?php } ?>
              </thead>
            <tbody>
            <?php
            $no = 1;
            $total_production_forcasting = 0;
            foreach($this->production_forcasting as $key => $value) {

                  //check apakah satuan dalam bijian
                  $satuan_pieces = array('unit', 'pcs', 'biji', 'pieces', 'buah','set');
                  if (in_array(strtolower($value->unit), $satuan_pieces)) {
                    $converted_production_unit = 'bijian';
                  } else {
                    $converted_production_unit = strtolower($value->unit);
                  }
                  if (in_array(strtolower($value->purchase_unit), $satuan_pieces)) {
                    $converted_purchase_unit = 'bijian';
                  } else {
                    $converted_purchase_unit = strtolower($value->purchase_unit);
                  }

                  //buat warna row sesuai status satuan
                  if ($converted_production_unit == $converted_purchase_unit) {
                    $status = '';
                  }

                  if ($converted_production_unit != $converted_purchase_unit) {
                    $status = 'warning';
                  }

                  if ($value->purchase_price <= 0 OR $value->production_price <= 0) {
                    $status = 'danger';
                  }
                    echo '<tr class="' . $status . '">';
                    echo '<td class="text-right">' . $no . '</td>';
                    echo '<td><a href="' . Config::get('URL') . 'inventory/editMaterial/?find=' . urlencode($value->material_code) . '">' . $value->material_code . '</a></td>';
                    echo '<td>' . $value->material_name . '</td>';

                    echo '<td class="text-right">' . floatval($value->quantity) . '</td>';
                    echo '<td>' . $value->unit . '</td>';
                    echo '<td class="text-right">' . floatval($value->quantity_stock) . '</td>';
                    if (Auth::isPermissioned('director,sales,finance,management')) { 
                      echo '<td class="text-right">' . number_format($value->purchase_price, 2) . '</td>';
                      echo '<td>' . $value->purchase_unit . '</td>';
                      echo '<td class="text-right">' . number_format($value->production_price, 2) . '</td>';
                    }
                    echo '<td>' . $value->note . '</td>';
                    echo "</tr>";
                
                $no++;
                $total_production_forcasting = $total_production_forcasting + $value->production_price;
                $status = $converted_purchase_unit = $converted_production_unit = '';
            }
            $total_production_forcasting = (int)$total_production_forcasting;
            ?>
            <?php if (Auth::isPermissioned('director,sales,finance,management')) {  ?>
                <tr class="info">
                  <td class="text-right" colspan="8"><strong>TOTAL BIAYA PRODUKSI</strong></td>
                  <td style="text-align: right;"><?php echo number_format($total_production_forcasting,0); ?></td>
                  <td style="text-align: right;"></td>
                </tr>
                <tr class="success">
                  <td class="text-right" colspan="8"><strong>MARGIN</strong></td>
                  <td style="text-align: right;"><?php echo number_format($total_transaction - $total_production_forcasting,0); ?></td>
                  <td style="text-align: right;"></td>
                </tr>
            <?php }  ?>
              </tbody>
            </table>
            </div>
          </div>