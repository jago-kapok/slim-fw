<div class="main-content">
        <!-- #section:basics/content.breadcrumbs -->
        <div class="breadcrumbs hidden-print" id="breadcrumbs">
          <script type="text/javascript">
            try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
          </script>

          <ul class="breadcrumb">
          
            <li>
              <a role="button" data-toggle="collapse" href="#changeDateRange" aria-expanded="false" aria-controls="changeDateRange">
                <span class="badge badge-info"><i class="glyphicon glyphicon-time"></i> Ganti Tanggal</span>
              </a>



              &nbsp;
              <a href="<?php echo Config::get('URL') . 'ExportExcel/allMaterial/'; ?>">
                <span class="badge badge-info">
                <i class="glyphicon glyphicon-arrow-down"></i> Export Excel
                </span>
              </a>
            </li>
            
          
          </ul><!-- /.breadcrumb -->

          <!-- #section:basics/content.searchbox -->
          <div class="nav-search" id="nav-search">
            <form class="form-search">
              <span class="input-icon">
                <input type="text" placeholder="Search ..." class="nav-search-input" id="nav-search-input" autocomplete="off" />
                <i class="ace-icon fa fa-search nav-search-icon"></i>
              </span>
            </form>
          </div><!-- /.nav-search -->

          <!-- /section:basics/content.searchbox -->
        </div>

        <!-- /section:basics/content.breadcrumbs -->
        <div class="page-content">
          <div class="row">
            <div class="col-xs-12">
              <!-- PAGE CONTENT BEGINS -->

<div class="collapse" id="changeDateRange">
  <div class="well">
    <form method="post" action="<?php echo Config::get('URL') . 'dashboard/purchase/'; ?>">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">Ganti Tanggal Report </h3>
                </div>
                <div class="panel-body">
                  <div class="row">

                      <div class="col-xs-12 col-sm-6">
                          <div class="form-group">
                              <label for="jumlah-pinjaman">Dari Tanggal/Start Date</label>
                              <input type="text" name="start_date" class="form-control datepicker" value="<?php echo date("Y-m-d");?>" data-date-format="yyyy-mm-dd">
                          </div>
                      </div><!-- /.col -->

                      <div class="col-xs-12 col-sm-6">
                          <div class="form-group">
                              <label for="jenis-pinjaman">Sampai Tanggal/End Date</label>
                              <input type="text" name="end_date" class="form-control datepicker" value="<?php echo date("Y-m-d");?>" data-date-format="yyyy-mm-dd">
                          </div>
                      </div><!-- /.col -->

                  </div><!-- /.row -->
                  <input type="hidden" name="change_date" value="ok">
            
                </div>
                <div class="panel-footer">
                    <p align="right">
                    <a role="button" class="btn btn-danger" data-toggle="collapse" href="#changeDateRange" aria-expanded="false" aria-controls="changeDateRange">
                            Cancel
                        </a>

                        &nbsp; &nbsp; &nbsp;

                        <button class="btn" type="reset">
                            Reset
                        </button>

                        &nbsp; &nbsp; &nbsp;
                        <button class="btn btn-primary" type="submit">
                            Change Date
                        </button>
                    </p>
                </div>
            </div>
</form>
  </div>
</div>
<?php //Debuger::jam($this->value_pembelian_per_month); ?>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
    google.charts.load('current', {packages: ['corechart', 'line']});

    google.charts.setOnLoadCallback(drawAxisTickColors);
    function drawAxisTickColors() {
          var data = new google.visualization.DataTable();
          data.addColumn('string', 'Bulan');
          data.addColumn('number', 'Total Pembelian');

          data.addRows([
            <?php
              foreach($this->value_pembelian_per_month as $key => $value) {
                $price = (int)$value->purchase_order;
                switch ($value->month) {
                    case 1:
                        $month = 'Januari';
                        break;
                    case 2:
                        $month = 'Ferbuari';
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
            hAxis: {
              title: 'Bulan',
              title: 'Bulan',
              textStyle: {
                color: '#01579b',
                fontSize: 20,
                fontName: 'Arial',
                bold: true,
                italic: true,
              },
              titleTextStyle: {
                color: '#01579b',
                fontSize: 16,
                fontName: 'Arial',
                bold: false,
                italic: true
              }
            },
            vAxis: {
              title: 'Rupiah',
              textStyle: {
                color: '#1a237e',
                fontSize: 13,
                bold: true
              },
              titleTextStyle: {
                color: '#1a237e',
                fontSize: 24,
                bold: true
              }
            },
            colors: ['#a52714', '#097138']
          };
          var chart = new google.visualization.LineChart(document.getElementById('pembelian-per-month'));
          chart.draw(data, options);
        }

      google.charts.setOnLoadCallback(topMaterial);
      function topMaterial() {
            // Define the chart to be drawn.
            var data = google.visualization.arrayToDataTable([
               ['Material Name', 'Pembelian'],
               <?php
              foreach($this->value_pembelian_per_product as $key => $value) {
                $price = (int)$value->purchase_price;
                echo '["' . $value->material_name . '",' . $price . '],';
              }
              ?>
            ]);

            var options = {title: 'Top 20 Material (Dalam Rupiah)'}; 

            // Instantiate and draw the chart.
            var chart = new google.visualization.ColumnChart(document.getElementById('topMaterial'));
            chart.draw(data, options);
       }

       google.charts.setOnLoadCallback(topSupplier);
      function topSupplier() {
            // Define the chart to be drawn.
            var data = google.visualization.arrayToDataTable([
               ['Supplier', 'Pembelian'],
               <?php
              foreach($this->value_pembelian_per_supplier as $key => $value) {
                $price = (int)$value->purchase_price;
                echo '["' . $value->contact_name . '",' . $price . '],';
              }
              ?>
            ]);

            var options = {title: 'Pembelian Per Supplier (Top 10 Dalam Rupiah)'}; 

            // Instantiate and draw the chart.
            var chart = new google.visualization.ColumnChart(document.getElementById('topSupplier'));
            chart.draw(data, options);
       }
    </script>

<div class="row">
  <div class="col-xs-12 col-sm-12 widget-container-col" >
    <div class="widget-box widget-color-purple">
      <div class="widget-header">
        <h5 class="widget-title bigger lighter">
          <i class="ace-icon fa fa-table"></i>
          <?php echo $this->title; ?>
        </h5>
      </div>

      <div class="widget-body">
        <div class="widget-main no-padding">
            <div id="topMaterial" style="width: 100%; height: 300px;"></div>
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
          <?php echo $this->title; ?>
        </h5>
      </div>

      <div class="widget-body">
        <div class="widget-main no-padding">
            <div id="topSupplier" style="width: 100%; height: 300px;"></div>
        </div>
      </div>
    </div>
  </div><!-- /.col -->
</div><!-- /.row -->

<div class="row">
  <div class="col-xs-12 col-sm-12 widget-container-col" >
    <div class="widget-box widget-color-purple">
      <div class="widget-header">
        <h5 class="widget-title bigger lighter">
          <i class="ace-icon fa fa-table"></i>
          <?php echo $this->title; ?>
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

