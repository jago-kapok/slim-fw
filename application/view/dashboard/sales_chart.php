<article id="dashboard-chart">
  <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
  <script type="text/javascript">
        google.charts.load('current', {packages: ['corechart', 'line']});

      google.charts.setOnLoadCallback(drawAxisTickColors);
      function drawAxisTickColors() {
            var data = new google.visualization.DataTable();
            data.addColumn('string', 'Bulan');
            data.addColumn('number', 'Penjualan');

            data.addRows([
              <?php
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
                  echo '["' . $month . '",' . $price . '],';
                }
                ?>
              
            ]);

            var options = {
              title: '<?php echo $this->title; ?>',
              hAxis: {
                title: 'Bulan',
                textStyle: {
                  color: '#01579b',
                  fontSize: 11,
                  fontName: 'Arial',
                  bold: false,
                  italic: true,
                },
                titleTextStyle: {
                  color: '#01579b',
                  fontSize: 15,
                  fontName: 'Arial',
                  bold: true,
                  italic: false
                }
              },
              vAxis: {
                title: 'Rupiah',
                textStyle: {
                  color: '#01579b',
                  fontSize: 11,
                  bold: false
                },
                titleTextStyle: {
                  color: '#01579b',
                  fontSize: 15,
                  bold: true
                }
              },
              colors: ['#a52714', '#097138']
            };
            var chart = new google.visualization.LineChart(document.getElementById('pembelian-per-month'));
            chart.draw(data, options);
          }


        google.charts.setOnLoadCallback(drawChart);
        function drawChart() {
          var data = google.visualization.arrayToDataTable([
            ['Nama Produk', 'Total'],
            <?php
              foreach($this->penjualan_per_product as $key => $value) {
                //total price per material code from 'price' and 'bulk price'
                $price = $value->selling_price + $value->selling_price_bulk;
                echo '[\'' . $value->material_name . '\',' . $price . '],';
              }
            ?>
          ]);

          var options = {
            title: '<?php echo $this->title; ?>',
            is3D: true,
          };

          var chart = new google.visualization.PieChart(document.getElementById('value-penjualan'));
          chart.draw(data, options);
        }


        google.charts.setOnLoadCallback(quantityPenjualan);
        function quantityPenjualan() {
          var data = google.visualization.arrayToDataTable([
            ['Category', 'Total'],
            <?php
              foreach($this->penjualan_per_product as $key => $value) {
                echo '[\'' . $value->material_name . '\',' . $value->quantity . '],';
              }
            ?>
          ]);

          var options = {
            title: '<?php echo $this->title; ?>',
            is3D: true,
          };

          var chart = new google.visualization.PieChart(document.getElementById('quantity-penjualan'));
          chart.draw(data, options);
        }

        

        google.charts.setOnLoadCallback(categoryPenjualan);
        function categoryPenjualan() {
          
          var data = google.visualization.arrayToDataTable([
            ['Category', 'Total'],
            <?php
              foreach($this->value_penjualan_per_category as $key => $value) {
                //total price per material code from 'price' and 'bulk price'
                $price = $value->selling_price + $value->selling_price_bulk;
                echo '[\'' . $value->material_category . '\',' . $price . '],';
              }
            ?>
          ]);

          var options = {
            title: '<?php echo $this->title; ?>',
            is3D: true,
          };

          var chart = new google.visualization.PieChart(document.getElementById('penjualan-per-kategori'));
          chart.draw(data, options);
        }
        

        google.charts.setOnLoadCallback(salesUser);
        function salesUser() {
              // Define the chart to be drawn.
              var data = google.visualization.arrayToDataTable([
                 ['Name', 'Total Penjualan'],
                 <?php
                  foreach($this->sales_user as $key => $value) {
                    $price = $value->selling_price + $value->selling_price_bulk;
                    echo '["' . $value->full_name . '",' . $price . '],';
                  }
                ?>
              ]);

              var options = {title: '<?php echo $this->title; ?>'}; 

              // Instantiate and draw the chart.
              var chart = new google.visualization.ColumnChart(document.getElementById('sales-user'));
              chart.draw(data, options);
         }


        google.charts.setOnLoadCallback(customerPenjualan);
        function customerPenjualan() {
              // Define the chart to be drawn.
              var data = google.visualization.arrayToDataTable([
                 ['Customer', 'Penjualan'],
                 <?php
                  foreach($this->penjualan_per_customer as $key => $value) {
                    $price = (int)($value->selling_price + $value->selling_price_bulk);
                    echo '["' . $value->contact_name . '",' . $price . '],';
                  }
                ?>
              ]);

              var options = {
              title: '<?php echo $this->title; ?>',
              hAxis: {
                title: 'Customer',
                textStyle: {
                  color: '#01579b',
                  fontSize: 11,
                  fontName: 'Arial',
                  bold: false,
                  italic: true,
                },
                titleTextStyle: {
                  color: '#01579b',
                  fontSize: 15,
                  fontName: 'Arial',
                  bold: true,
                  italic: false
                }
              },
              vAxis: {
                title: 'Rupiah',
                textStyle: {
                  color: '#01579b',
                  fontSize: 11,
                  bold: false
                },
                titleTextStyle: {
                  color: '#01579b',
                  fontSize: 15,
                  bold: true
                }
              },
              colors: ['#a52714', '#097138']
            };

              // Instantiate and draw the chart.
              var chart = new google.visualization.ColumnChart(document.getElementById('customerPenjualan'));
              chart.draw(data, options);
         }
  </script>

<div class="row">
  <div class="col-xs-12 col-sm-6 widget-container-col" >
    <div class="widget-box widget-color-blue">
      <div class="widget-header">
        <h5 class="widget-title bigger lighter">
          <i class="ace-icon fa fa-table"></i>
          Penjualan Per Produk (Dalam Rupiah)
        </h5>
      </div>

      <div class="widget-body">
        <div class="widget-main no-padding">
          <div id="value-penjualan" style="width: 100%; height: 300px;"></div>
        </div>
      </div>
    </div>
  </div><!-- /.col -->

  <div class="col-xs-12 col-sm-6 widget-container-col" >
    <div class="widget-box widget-color-green3">
      <div class="widget-header">
        <h5 class="widget-title bigger lighter">
          <i class="ace-icon fa fa-table"></i>
          Penjualan Per Produk (Dalam Unit)
        </h5>
      </div>

      <div class="widget-body">
        <div class="widget-main no-padding">
          <div id="quantity-penjualan" style="width: 100%; height: 300px;"></div>
        </div>
      </div>
    </div>
  </div><!-- /.col -->
</div><!-- /.row -->

<div class="space-24"></div>

<div class="row">
  <div class="col-xs-12 col-sm-6 widget-container-col" >
    <div class="widget-box widget-color-red2">
      <div class="widget-header">
        <h5 class="widget-title bigger lighter">
          <i class="ace-icon fa fa-table"></i>
          Penjualan Per Kategori (dalam rupiah)
        </h5>
      </div>

      <div class="widget-body">
        <div class="widget-main no-padding">
          <div id="penjualan-per-kategori" style="width: 100%; height: 300px;"></div>
        </div>
      </div>
    </div>
  </div><!-- /.col -->

  <div class="col-xs-12 col-sm-6 widget-container-col" >
    <div class="widget-box widget-color-orange">
      <div class="widget-header">
        <h5 class="widget-title bigger lighter">
          <i class="ace-icon fa fa-table"></i>
          Penjualan per Sales Person (Dalam Rupiah)
        </h5>
      </div>

      <div class="widget-body">
        <div class="widget-main no-padding">
            <div id="sales-user" style="width: 100%; height: 300px;"></div>
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
          Penjualan per Bulan (Dalam Rupiah)
        </h5>
      </div>

      <div class="widget-body">
        <div class="widget-main no-padding">
            <div id="pembelian-per-month" style="width: 100%; height: 300px;"></div>
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
          Penjualan per Customer (Dalam Rupiah)
        </h5>
      </div>

      <div class="widget-body">
        <div class="widget-main no-padding">
            <div id="customerPenjualan" style="width: 100%; height: 300px;"></div>
        </div>
      </div>
    </div>
  </div><!-- /.col -->
</div><!-- /.row -->

</article>