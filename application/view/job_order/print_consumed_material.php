<table class="table table-striped table-bordered table-hover ExcelTable2007">
              <thead>
                <tr>
                  <th  class="center">#</th>
                  <th  class="center">Tanggal</th>
                  <th  class="center">Kode</th>
                  <th  class="center">Nama</th>
                  <th  class="center">Qty</th>
                  <th  class="center">Satuan</th>
                  <th  class="center">#Lot Reference</th>
                  <th  class="center">Keterangan</th>
                </tr>
              </thead>
            <tbody>
            <?php
            $no = 1;
            foreach($this->consumed_material_list as $key => $value) {
      
                    echo '<tr id="row_' . $no . '">';
                    echo '<td class="text-right">' . $no . '</td>';
                    echo '<td>' . date('d-m, Y', strtotime($value->created_timestamp)) . '</td>';
                    echo '<td>' . $value->material_code . '</td>';
                    echo '<td>' . $value->material_name . '</td>';
                    echo '<td class="text-right">' . floatval($value->quantity_delivered) . '</td>';
                    echo '<td>' . $value->unit . '</td>';
                    echo '<td class="text-right">' . $value->material_lot_number . '</td>';
                    echo '<td class="text-right">' . $value->note . '</td>';
                $no++;
            }
            ?>
              </tbody>
            </table>