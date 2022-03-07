<div class="main-content">
        <!-- #section:basics/content.breadcrumbs -->
        <div class="breadcrumbs" id="breadcrumbs">
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
            <form class="form-search" method="get" action="<?php echo Config::get('URL') . 'pos/reportKasir/';?>">
              <span class="input-icon">
                <input type="text" name="find" placeholder="Search ..." class="nav-search-input" id="nav-search-input" autocomplete="off" />
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

<?php $this->renderFeedbackMessages();?>
<div class="collapse" id="changeDateRange">
  <div class="well">
    <form method="post" action="<?php echo Config::get('URL') . 'pos/reportKasir/'; ?>">
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

<!-- Nav tabs -->
  <ul class="nav nav-tabs" role="tablist">
    <li role="presentation" class="active"><a href="#detail" aria-controls="detail" role="tab" data-toggle="tab">Per Detail</a></li>
    <li role="presentation"><a href="#per-kategori" aria-controls="per-kategori" role="tab" data-toggle="tab">Per Nomer Penjualan</a></li>
    <li role="presentation"><a href="#per-minggu" aria-controls="per-minggu" role="tab" data-toggle="tab">Per Minggu</a></li>
    <li role="presentation"><a href="#per-bulan" aria-controls="per-bulan" role="tab" data-toggle="tab">Per Bulan</a></li>
  </ul>

  <!-- Tab panes -->
  <div class="tab-content">
    <div role="tabpanel" class="tab-pane active" id="detail">
      <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover">
            <thead>
            <tr>
            <th class="text-center" colspan="11"><?php echo $this->title;?></th>      
            </tr>
            <tr>
            <th class="text-right">No</th>
            <th class="text-left">Tanggal</th>
            <th class="text-left">#Sales</th>
            <th class="text-left">Item</th>
            <th class="text-right">Jumlah Order</th>
            <th class="text-right">Jumlah Dikirim</th>
            <th class="text-left">Harga Jual</th>
            <th class="text-left">Harga Beli</th>
            <th class="text-left">Total Penjualan</th>
            <th class="text-left">Total Keuntungan</th>
            <th class="text-left">Kategori</th>
            </tr>
            </thead>

            <tbody>
            <?php
            $no = 1;
            $total_penjualan = 0;
            $total_keuntungan = 0;
            $total_item_sold = 0;
            $total_item_delivered = 0;
            foreach($this->transaction as $key => $value) {
            echo "<tr>";
            echo '<td class="text-right">' . $no . '</a></td>';
            echo '<td>' . date("d/m/Y", strtotime($value->created_timestamp)) . '</td>';

            echo '<td>' . $value->so_number . '</td>';
            echo '<td>' . $value->material_name . '(' . $value->material_code .')</td>';
            echo '<td class="text-right">' . number_format($value->quantity, 0) . '</td>';
            echo '<td class="text-right">' . number_format($value->quantity_delivered, 0) . '</td>';
            
            echo '<td class="text-right">' . number_format($value->selling_price) . '</td>';
            echo '<td class="text-right">' . number_format($value->purchase_price) . '</td>';
            $sub_total_penjualan = $value->selling_price * $value->quantity;      
            echo '<td class="text-right">' . number_format($sub_total_penjualan,2) . '</td>';
            $sub_total_keuntungan = ($value->selling_price - $value->purchase_price) * $value->quantity;  
            echo '<td class="text-right">' . number_format($sub_total_keuntungan,2) . '</td>';
            echo '<td>' . $value->material_category . '</td>';
            echo "</tr>";
            $no++;
            $total_penjualan = $total_penjualan + $sub_total_penjualan;
            $total_keuntungan = $total_keuntungan + $sub_total_keuntungan;
            $total_item_sold = $total_item_sold + $value->quantity;
            $total_item_delivered = $total_item_delivered + $value->quantity_delivered;
            }
            ?>
            <tr class="success">
            <td class="text-center" colspan="4">Total</td>
            <td class="text-right"><?php echo number_format($total_item_sold); ?></td>
            <td class="text-right"><?php echo number_format($total_item_delivered); ?></td>
            <td class="text-right" colspan="2"></td>
            <td class="text-right"><?php echo number_format($total_penjualan,2); ?></td>
            <td class="text-right"><?php echo number_format($total_keuntungan,2); ?></td>
            <td class="text-center" colspan="4"></td>
            </tr>
            </tbody>
            </table>
      </div>
    </div> <!-- /#detail -->

    <div role="tabpanel" class="tab-pane" id="per-kategori">
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover">
            <thead>
            <tr>
            <th class="text-center" colspan="11"><?php echo $this->title;?></th>      
            </tr>
            <tr>
            <th class="text-right">No</th>
            <th class="text-left">Tanggal</th>
            <th class="text-left">#Sales</th>
            <th class="text-left">Customer</th>   
            <th class="text-left">Harga</th>
            <th class="text-left">Pembayaran</th>
            <th class="text-left">Kembalian</th>
            <th class="text-left">EDC</th>
             <th class="text-left">EDC Reference</th>
            <th class="text-left">Action</th>      
            </tr>
            </thead>

            <tbody>
            <?php
            $no = 1;
            $total_sales = 0;
            foreach($this->transaction_group as $key => $value) {
            echo "<tr>";
            echo '<td class="text-right">' . $no . '</a></td>';
            echo '<td>' . date("d M, y", strtotime($value->created_timestamp)) . '</a></td>';

            echo '<td>' . $value->so_number . '</td>';
            echo '<td>' . $value->contact_name . '</td>';
            echo '<td class="text-right">' . number_format($value->total_sales,0) . '</td>';
            echo '<td class="text-right">' . number_format($value->payment,0) . '</td>';
            echo '<td class="text-right">' . number_format($value->payment_return,0) . '</td>';
            echo '<td>' . $value->edc_bank . '</td>';
            echo '<td>' . $value->edc_reference . '</td>';
            

            echo '<td class="text-right"> 
            <div class="btn-group btn-corner">
                                <button data-toggle="dropdown" class="btn btn-minier btn-info dropdown-toggle">
                                  <span class="glyphicon glyphicon-print" aria-hidden="true"></span>
                                  Print
                                  <span class="ace-icon fa fa-caret-down icon-on-right"></span>
                                </button>

                                <ul class="dropdown-menu dropdown-default">
                                  <li>
                                    <a href="' .  Config::get('URL') . 'pos/printKasirToko/?so_number=' . urlencode($value->so_number) . '" target="_blank">Struk Kasir</a>
                                  </li>

                                  <li>
                                    <a href="' .  Config::get('URL') . 'pos/printNotaPenjualan/?so_number=' . urlencode($value->so_number) . '" target="_blank">Nota Penjualan</a>
                                  </li>
                                </ul>

                                <a href="' .  Config::get('URL') . 'productReturn/?so_number=' . urlencode($value->so_number) . '" class="btn btn-yellow btn-minier"><span class="glyphicon glyphicon-refresh" aria-hidden="true"></span> Retur</a>

                                <a href="' .  Config::get('URL') . 'pos/deleteSalesCode/?so_number=' . urlencode($value->so_number) . '&forward=' . $_SERVER['REQUEST_URI'] . ' " class="btn btn-danger btn-minier" onclick="return confirmation(\'Are you sure to delete?\');"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span> delete</a>
                              </div>
            </td>';

            echo "</tr>";
            $no++;
            $total_sales = $total_sales + $value->total_sales;
            }
            ?>
            <tr class="success">
            <td class="text-center" colspan="4">Total</td>
            <td class="text-right" ><?php echo number_format($total_sales, 0); ?></td>
            <td class="text-center" colspan="5"></td>
            </tr>
            </tbody>
            </table>
        </div>
    </div> <!-- /#per-kategori -->

    <div role="tabpanel" class="tab-pane" id="per-minggu">
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover">
            <thead>
            <tr>
            <th class="text-center" colspan="3"><?php echo $this->title;?></th>      
            </tr>
            <tr>
            <th class="text-right">No</th>
            <th class="text-left">Minggu Ke</th>
            <th class="text-left">Total Penjualan</th>      
            </tr>
            </thead>

            <tbody>
            <?php
            $no = 1;
            $total_sales = 0;
            foreach($this->group_by_category_week as $key => $value) {
            echo "<tr>";
            echo '<td class="text-right">' . $no . '</a></td>';
            echo '<td>' . $value->week . '</td>';
            echo '<td class="text-right">' . number_format($value->total_sales,0) . '</td>';
            echo "</tr>";
            $no++;
            $total_sales = $total_sales + $value->total_sales;
            }
            ?>
            <tr class="success">
            <td class="text-center" colspan="2">Total</td>
            <td class="text-right" ><?php echo number_format($total_sales, 0); ?></td>
            </tr>
            </tbody>
            </table>
        </div>
    </div> <!-- /#per-minggu -->

    <div role="tabpanel" class="tab-pane" id="per-bulan">
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover">
            <thead>
            <tr>
            <th class="text-center" colspan="3"><?php echo $this->title;?></th>      
            </tr>
            <tr>
            <th class="text-right">No</th>
            <th class="text-left">Bulan Ke</th>
            <th class="text-left">Total Penjualan</th>      
            </tr>
            </thead>

            <tbody>
            <?php
            $no = 1;
            $total_sales = 0;
            foreach($this->group_by_category_month as $key => $value) {
            echo "<tr>";
            echo '<td class="text-right">' . $no . '</a></td>';
            echo '<td>' . $value->month . '</td>';
            echo '<td class="text-right">' . number_format($value->total_sales,0) . '</td>';
            echo "</tr>";
            $no++;
            $total_sales = $total_sales + $value->total_sales;
            }
            ?>
            <tr class="success">
            <td class="text-center" colspan="2">Total</td>
            <td class="text-right" ><?php echo number_format($total_sales, 0); ?></td>
            </tr>
            </tbody>
            </table>
        </div>
    </div> <!-- /#per-bulan -->

  </div>
<!-- /Nav tabs -->