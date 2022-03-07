<div class="main-content">
  <div class="main-content-inner">
          <!-- PAGE CONTENT BEGINS -->

      <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover">
            <thead>
            <tr class="heading">
            <th class="text-center" colspan="8"><?php echo $this->title;?></th>      
            </tr>
            <tr class="heading">
            <th class="text-right">No</th>
            <th class="text-left">Tanggal</th>
            <th class="text-left">Nama Transaksi</th>
            <th class="text-right">Debit</th>
            <th class="text-right">Credit</th>
            <th class="text-left">Kategori</th>       
            <th class="text-left">Catatan</th>
            <th class="text-left">Hapus?</th>
            </tr>
            </thead>

            <tbody>
            <?php
            //ingat.... debit dan credit dibalek disini, beda dengan yang ada di tulisan tabungan bank
            $no = 1;
            $total_debit = 0;
            $total_credit = 0;
            foreach($this->transaction as $key => $value) {
            echo "<tr>";
            echo '<td class="text-right">' . $no . '</a></td>';
            echo '<td>' . date("d F Y", strtotime($value->created_timestamp)) . '</a></td>';
            echo '<td>' . $value->transaction_name . '</td>';
            echo '<td class="text-right">' . number_format($value->debit,2) . '</td>';
            echo '<td class="text-right">' . number_format($value->credit,2) . '</td>';
            echo '<td>' . $value->transaction_category . '</td>';
            echo '<td>' . $value->note . '</td>';
            echo '<td><a href="' .  Config::get('URL') . 'delete/remove/payment_transaction/uid/' . $value->uid . '/?forward=' . $_SERVER['REQUEST_URI'] . '" class="btn btn-danger btn-xs" onclick="return confirmation(\'Are you sure to delete?\');">delete</a></td>';
            
            echo "</tr>";
            $no++;
            $total_debit = $total_debit + $value->debit;
            $total_credit = $total_credit + $value->credit;
            }
            ?>
            <tr class="success">
            <td class="text-center" colspan="3">Total</td>
            <td class="text-right"><?php echo number_format($total_debit,2); ?></td>
            <td class="text-right"><?php echo number_format($total_credit,2); ?></td>
            <td class="text-center" colspan="3"></td>
            </tr>
            </tbody>
            </table>
      </div>
  </div><!-- /.main-content-inne -->
</div><!-- /.main-content -->