<article id="dashboard-raw-data" style="display: none;">
<div class="row">
  <div class="col-xs-12 col-sm-6 widget-container-col" >
    <div class="widget-box widget-color-blue">
      <div class="widget-header">
        <h5 class="widget-title bigger lighter">
          <i class="ace-icon fa fa-table"></i>
          Penjulan Per Bulan
        </h5>
      </div>

      <div class="widget-body">
        <div class="widget-main no-padding">
          <div class="table-responsive">
          <table class="table table-striped table-bordered table-hover ExcelTable2007">
          <thead>
          <tr>
            <th colspan="3" class="center"><?php echo $this->title; ?></th>
          </tr>
          <tr>
          <th>No</th>
          <th class="center">Bulan</th>
          <th class="center">Total Penjualan (Rupiah)</th>
          </tr>
          </thead>

          <tbody>
          <?php
              $no = 1;
              foreach($this->value_penjualan_per_month as $key => $value) {
                $price = (int)$value->selling_price + (int)$value->selling_price_bulk;
                switch ($value->month) {
                    case 1:
                        $month = 'Januari';
                        break;
                    case 2:
                        $month = 'Februari';
                        break;
                    case 3:
                        $month = 'Maret';
                        break;
                    case 4:
                        $month = 'April';
                        break;
                    case 5:
                        $month = 'Mei';
                        break;
                    case 6:
                        $month = 'Juni';
                        break;
                    case 7:
                        $month = 'Juli';
                        break;
                    case 8:
                        $month = 'Agustus';
                        break;
                    case 9:
                        $month = 'September';
                        break;
                    case 10:
                        $month = 'Oktober';
                        break;
                    case 11:
                        $month = 'November';
                        break;
                    case 12:
                        $month = 'Desember';
                        break;
                }
              echo "<tr>";
              echo '<td>' . $no . '</td>';
              echo '<td>' .  $month . '</td>';
              echo '<td class="text-right">' . number_format($price, 0) . '</td>';
              echo "</tr>";
              $no++;
              }
              ?>
          </tbody>
          </table>
          </div><!-- /.table-responsive -->
        </div>
      </div>
    </div>
  </div><!-- /.col -->

  <div class="col-xs-12 col-sm-6 widget-container-col" >
    <div class="widget-box widget-color-green3">
      <div class="widget-header">
        <h5 class="widget-title bigger lighter">
          <i class="ace-icon fa fa-table"></i>
          Penjualan per Sales Person
        </h5>
      </div>

      <div class="widget-body">
        <div class="widget-main no-padding">
          <div class="table-responsive">
          <table class="table table-striped table-bordered table-hover ExcelTable2007">
          <thead>
          <tr>
            <th colspan="3" class="center"><?php echo $this->title; ?></th>
          </tr>
          <tr>
          <th>No</th>
          <th class="center">Sales Person</th>
          <th class="center">Total Penjualan (Rupiah)</th>
          </tr>
          </thead>

          <tbody>
              <?php
                $no = 1;
                foreach($this->sales_user as $key => $value) {
                  $price = $value->selling_price + $value->selling_price_bulk;
                    echo "<tr>";
                    echo '<td>' . $no . '</td>';
                    echo '<td>' .  $value->full_name . '</td>';
                    echo '<td class="text-right">' . number_format($price, 0) . '</td>';
                    echo "</tr>";
                    $no++;
                }
              ?>
          </tbody>
          </table>
        </div><!-- /.table-responsive -->
        </div>
      </div>
    </div>

    <div class="space-24"></div>

    <div class="widget-box widget-color-orange">
      <div class="widget-header">
        <h5 class="widget-title bigger lighter">
          <i class="ace-icon fa fa-table"></i>
          Penjualan Per Kategori
        </h5>
      </div>

      <div class="widget-body">
        <div class="widget-main no-padding">
          <div class="table-responsive">
          <table class="table table-striped table-bordered table-hover ExcelTable2007">
          <thead>
          <tr>
          <th colspan="3" class="center">
            <?php echo $this->title; ?>
          </th>
          </tr>
          <tr>
          <th>No</th>
          <th class="center">Nama Kategori</th>
          <th class="center">Total Penjualan (Rupiah)</th>
          </tr>
          </thead>

          <tbody>
              <?php
                $no = 1;
                foreach($this->value_penjualan_per_category as $key => $value) {
                  //total price per material code from 'price' and 'bulk price'
                  $price = $value->selling_price + $value->selling_price_bulk;
                    echo "<tr>";
                    echo '<td>' . $no . '</td>';
                    echo '<td>' .  $value->material_category . '</td>';
                    echo '<td class="text-right">' . number_format($price, 0) . '</td>';
                    echo "</tr>";
                    $no++;
                }
              ?>
          </tbody>
          </table>
        </div><!-- /.table-responsive -->
        </div>
      </div>
    </div>

  </div><!-- /.col -->
</div><!-- /.row -->

    <div class="space-24"></div>

    <div class="row">
  <div class="col-xs-12 col-sm-12 widget-container-col" >
    <div class="widget-box widget-color-purple">
      <div class="widget-header">
        <h5 class="widget-title bigger lighter">
          <i class="ace-icon fa fa-table"></i>
          Penjualan Per Produk
        </h5>
      </div>

      <div class="widget-body">
        <div class="widget-main no-padding">
            <div class="table-responsive">
          <table class="table table-striped table-bordered table-hover ExcelTable2007">
          <thead>
          <tr>
          <th colspan="5" class="center">
            <?php echo $this->title; ?>
          </th>
          </tr>
          <tr>
            <th rowspan="2">No</th>
            <th rowspan="2" class="center">Kode Material</th>
            <th rowspan="2" class="center">Nama Material</th>
            <th colspan="2" class="center">Total Penjualan</th>
          </tr>
          <tr>
            <th class="center">Rupiah</th>
            <th class="center">Unit</th>
          </tr>
          </thead>

          <tbody>
            <?php
              $no = 1;
              foreach($this->penjualan_per_product as $key => $value) {
                //total price per material code from 'price' and 'bulk price'
                $price = $value->selling_price + $value->selling_price_bulk;
                  echo "<tr>";
                  echo '<td>' . $no . '</td>';
                  echo '<td>' .  $value->material_code . '</td>';
                  echo '<td>' .  $value->material_name . '</td>';
                  echo '<td class="text-right">' . number_format($price, 0) . '</td>';
                  echo '<td class="text-right">' . number_format($value->quantity, 0) . '</td>';
                  echo "</tr>";
              $no++;
              }
              ?>
          </tbody>
          </table>
          </div><!-- /.table-responsive -->
        </div>
      </div>
    </div>
  </div><!-- /.col -->
</div><!-- /.row -->

<div class="space-24"></div>

<div class="row">
  <div class="col-xs-12 col-sm-12 widget-container-col" >
    <div class="widget-box widget-color-red">
      <div class="widget-header">
        <h5 class="widget-title bigger lighter">
          <i class="ace-icon fa fa-table"></i>
          Penjualan per Customer
        </h5>
      </div>

      <div class="widget-body">
        <div class="widget-main no-padding">
            <div class="table-responsive">
              <table class="table table-striped table-bordered table-hover ExcelTable2007">
              <thead>
              <tr>
              <th colspan="4" class="center">
                 <?php echo $this->title; ?>
              </th>
              </tr>
              <tr>
                <th rowspan="2">No</th>
                <th class="center" rowspan="2">Nama Customer</th>
                <th class="center" colspan="2">Total Penjualan</th>
              </tr>
              <tr>
                <th class="center" >Rupiah</th>
                <th class="center" >Unit</th>
              </tr>
              </thead>

              <tbody>
                  <?php
                    $no = 1;
                    foreach($this->penjualan_per_customer as $key => $value) {
                      $price = (int)($value->selling_price + $value->selling_price_bulk);
                        echo "<tr>";
                        echo '<td>' . $no . '</td>';
                        echo '<td>' .  $value->contact_name . '</td>';
                        echo '<td class="text-right">' . number_format($price, 0) . '</td>';
                        echo '<td class="text-right">' . number_format($value->quantity, 0) . '</td>';
                        echo "</tr>";
                        $no++;
                    }
                  ?>
              </tbody>
              </table>
            </div><!-- /.table-responsive -->
        </div>
      </div>
    </div>
  </div><!-- /.col -->
</div><!-- /.row -->

        
</article>