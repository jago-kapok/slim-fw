<div class="main-content">
    <div class="main-content-inner">

        <!-- /section:basics/content.breadcrumbs -->
      <?php $this->renderFeedbackMessages();?>
      <div class="table-responsive">
          <table class="table table-striped table-bordered table-hover ExcelTable2007">
          <thead>
          <tr>
            <th class="text-right" rowspan="2">No</th>
            <th class="text-left" rowspan="2">Kode Produk</th>
            <th class="text-left" rowspan="2">Nama Produk</th>
            <th class="text-left" colspan="3">Lama Pengerjaan (Hari)</th>
          </tr>
          <tr>
            <th class="text-right">Tercepat</th>
            <th class="text-left">Terlama</th>
            <th class="text-left">Rata - Rata</th>
          </tr>
          </thead>

          <tbody>
          <?php
          $no = 1;
          foreach($this->result as $key => $value) {
            if (!empty($value->material_code)) {
              $array_transaction_number = explode('-, -', $value->transaction_number);
              $array_lama_kerja = explode('-, -', $value->lama_kerja);
              $total_produksi = count($array_lama_kerja);
              $total_lama_kerja = 0;
              foreach ($array_lama_kerja as $value_lama_kerja) {
                $total_lama_kerja = $total_lama_kerja + (int)$value_lama_kerja;
              }
              $rata_lama_produksi = $total_lama_kerja / $total_produksi;
              $tercepat = min($array_lama_kerja);
              $terlama = max($array_lama_kerja);
              echo '<tr>';
              echo '<td class="text-right">' . $no . '</td>';
              echo '<td>' . $value->material_code . '</td>';
              echo '<td>' . $value->material_name . '</td>';
              echo '<td class="text-right">' . number_format($tercepat, 0) . '</td>';
              echo '<td class="text-right">' . number_format($terlama, 0) . '</td>';
              echo '<td class="text-right">' . number_format($rata_lama_produksi, 0) . '</td>';
              echo "</tr>";
              $no++;
            }
          }
          ?>
          </tbody>
          </table>
        </div>
  </div><!-- /.main-content-inner -->
</div><!-- /.main-content -->