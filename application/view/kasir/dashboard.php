<div class="main-content">
        <!-- #section:basics/content.breadcrumbs -->
        <div class="breadcrumbs hidden-print" id="breadcrumbs">
          <script type="text/javascript">
            try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
          </script>

          <ul class="breadcrumb">
            <li>
              <a role="button" data-toggle="collapse" href="#changeDateRange" aria-expanded="false" aria-controls="changeDateRange">
                <span class="badge badge-info">Ganti Tanggal</span>
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
    <form method="post" action="<?php echo Config::get('URL') . 'kasir/dashboard/'; ?>">
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

    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load("current", {packages:["corechart"]});

      google.charts.setOnLoadCallback(valuePenjualanProduct);
      function valuePenjualanProduct() {
        var data = google.visualization.arrayToDataTable([
          ['Nama Produk', 'Total'],
          <?php
        foreach($this->value_penjualan_per_product as $key => $value) {
          //total price per material code from 'price' and 'bulk price'
          if ($value->selling_price > 0) {echo '[\'' . $value->material_name . '\',' . $value->selling_price . '],';}
        }
          ?>
        ]);

        var options = {
          title: 'Penjualan Produk (dalam rupiah)',
          is3D: true,
        };

        var chart = new google.visualization.PieChart(document.getElementById('value-penjualan-product'));
        chart.draw(data, options);
      }


      google.charts.setOnLoadCallback(quantityPenjualanProduct);
      function quantityPenjualanProduct() {
        var data = google.visualization.arrayToDataTable([
          ['Category', 'Total'],
          <?php
        foreach($this->quantity_penjualan_per_product as $key => $value) {
          if ($value->quantity > 0) {echo '[\'' . $value->material_name . '\',' . $value->quantity . '],';}
        }
          ?>
        ]);

        var options = {
          title: 'Penjualan Produk (dalam satuan)',
          is3D: true,
        };

        var chart = new google.visualization.PieChart(document.getElementById('quantity-penjualan-product'));
        chart.draw(data, options);
      }

      

      google.charts.setOnLoadCallback(valuePenjualanCategory);
      function valuePenjualanCategory() {
        
        var data = google.visualization.arrayToDataTable([
          ['Category', 'Total'],
          <?php
        foreach($this->value_penjualan_per_category as $key => $value) {
          //total price per material code from 'price' and 'bulk price'
          if ($value->selling_price > 0) {echo '[\'' . $value->material_category . '\',' . $value->selling_price . '],';}
        }
          ?>
        ]);

        var options = {
          title: 'Penjualan Per Kategori (dalam rupiah)',
          is3D: true,
        };

        var chart = new google.visualization.PieChart(document.getElementById('value-penjualan-category'));
        chart.draw(data, options);
      }

      google.charts.setOnLoadCallback(quantityPenjualanCategory);
      function quantityPenjualanCategory() {
        var data = google.visualization.arrayToDataTable([
          ['Category', 'Total'],
          <?php
        foreach($this->quantity_penjualan_per_category as $key => $value) {
          if ($value->quantity > 0) {echo '[\'' . $value->material_category . '\',' . $value->quantity . '],';}
        }
          ?>
        ]);

        var options = {
          title: 'Penjualan Per Kategori (dalam satuan)',
          is3D: true,
        };

        var chart = new google.visualization.PieChart(document.getElementById('quantity-penjualan-category'));
        chart.draw(data, options);
      }



      google.charts.setOnLoadCallback(penjualanByJam);
      function penjualanByJam() {
            var data = new google.visualization.DataTable();
            data.addColumn('timeofday', 'Jam Penjualan');
            data.addColumn('number', 'Value');
            data.addColumn('number', 'Quantity');

            data.addRows([
              <?php
                foreach($this->penjualan_by_hours as $key => $value) {
                  if ($value->quantity > 0 OR $value->selling_price > 0) {
                    //echo '[\'' . $value->material_category . '\',' . $value->quantity . '],';
                    echo '[{v: [' . (Int) $value->hours .', 0, 0], f: \'Jam ' . (int) $value->hours .'\'}, ' . (int)($value->selling_price/1000) . ', ' . (int)$value->quantity . '],';
                  }
                }
              ?>
            ]);

            var options = {
              title: 'Total Value (Rupiah) dan Quantity Penjualan dalam Jam',
              hAxis: {
                title: 'Jam Penjualan',
                format: 'h:mm a',
                viewWindow: {
                  min: [1, 00, 0], //jam, menit, detik
                  max: [24, 00, 0] //jam, menit, detik
                }
              },
              vAxis: {
                title: 'Penjualan (Ribuan)'
              }
            };

            var chart = new google.visualization.ColumnChart(
            document.getElementById('penjualan-by-hours'));

            chart.draw(data, options);
          }


      google.charts.setOnLoadCallback(penjualanByWeekday);
      function penjualanByWeekday() {
            var data = google.visualization.arrayToDataTable([
                  ['Month', 'Value (Ribuan)', 'Quantity'],
                  <?php
                    foreach($this->penjualan_by_weekdays as $key => $value) {
                      if ($value->quantity > 0 OR $value->selling_price > 0) {
                        echo '[\'' . $value->weekday . '\',' . (int)($value->selling_price/1000) . ',' . (int)$value->quantity . '],';
                        //echo '[{v: [' . (Int) $value->hours .', 0, 0], f: \'Jam ' . (int) $value->hours .'\'}, ' . number_format((($value->selling_price +  $value->selling_price_bulk)/1000), 1, '.', '.') .', ' . number_format($value->quantity, 0, '.', '') . '],';
                      }
                    }
                  ?>
                ]);

            var view = new google.visualization.DataView(data);
            view.setColumns([0, 1, {
                calc: 'stringify',
                sourceColumn: 1,
                type: 'string',
                role: 'annotation'
              }, 2, {
                calc: 'stringify',
                sourceColumn: 2,
                type: 'string',
                role: 'annotation'
            }]);

            var options = {
              title: 'Total Value (Rupiah) dan Quantity Penjualan dalam Hari',
              hAxis: {
                title: 'Jam Penjualan'
              },
              vAxis: {
                title: 'Penjualan (Ribuan)'
              }
            };

            var chart = new google.visualization.ColumnChart(document.getElementById('penjualan-by-weekdays'));
            chart.draw(data, options);
          }

    </script>


  
      <div class="row">
        <div class="col-xs-12 col-sm-6 widget-container-col" >
          <div class="widget-box widget-color-blue">
            <div class="widget-header">
              <h5 class="widget-title bigger lighter">
                <i class="ace-icon fa fa-table"></i>
                <?php echo $this->title; ?>
              </h5>
            </div>

            <div class="widget-body">
              <div class="widget-main no-padding">
                <div id="value-penjualan-product" style="width: 100%; min-height: 350px; height: 100%;"></div>
              </div>
            </div>
          </div>
        </div><!-- /.col -->

        <div class="col-xs-12 col-sm-6 widget-container-col" >
          <div class="widget-box widget-color-green3">
            <div class="widget-header">
              <h5 class="widget-title bigger lighter">
                <i class="ace-icon fa fa-table"></i>
                <?php echo $this->title; ?>
              </h5>
            </div>

            <div class="widget-body">
              <div class="widget-main no-padding">
                <div id="quantity-penjualan-product" style="width: 100%; min-height: 350px; height: 100%;"></div>
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
                <?php echo $this->title; ?>
              </h5>
            </div>

            <div class="widget-body">
              <div class="widget-main no-padding" >
                <div id="value-penjualan-category" style="width: 100%; min-height: 350px; height: 100%;"></div>
              </div>
            </div>
          </div>
        </div><!-- /.col -->

        <div class="col-xs-12 col-sm-6 widget-container-col" >
          <div class="widget-box widget-color-orange">
            <div class="widget-header">
              <h5 class="widget-title bigger lighter">
                <i class="ace-icon fa fa-table"></i>
                <?php echo $this->title; ?>
              </h5>
            </div>

            <div class="widget-body">
              <div class="widget-main no-padding">
                  <div id="quantity-penjualan-category" style="width: 100%; min-height: 350px; height: 100%;"></div>
              </div>
            </div>
          </div>
        </div><!-- /.col -->
      </div><!-- /.row -->


<div class="space-24"></div>


<div class="tabbable">
  <ul class="nav nav-tabs" id="myTab">
    <li class="active">
      <a data-toggle="tab" href="#per-jam">
        <i class="green ace-icon fa fa-home bigger-120"></i>
        Per Jam
      </a>
    </li>

    <li>
      <a data-toggle="tab" href="#per-hari">
        Per Hari
      </a>
    </li>
  </ul>
  <div class="tab-content">
    <div id="per-jam" class="tab-pane fade in active">
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
                  <div id="penjualan-by-hours" style="width: 100%; height: 300px;"></div>
              </div>
            </div>
          </div>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div>

    <div id="per-hari" class="tab-pane fade">
      <div class="row">
        <div class="col-xs-12 col-sm-12 widget-container-col" >
          <div class="widget-box widget-color-dark">
            <div class="widget-header">
              <h5 class="widget-title bigger lighter">
                <i class="ace-icon fa fa-table"></i>
                <?php echo $this->title; ?>
              </h5>
            </div>
            <div class="widget-body">
              <div class="widget-main no-padding">
                  <div id="penjualan-by-weekdays" style="width: 100%; height: 300px;"></div>
              </div>
            </div>
          </div>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div>
  </div>
</div>