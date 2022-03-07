<div class="main-content">
    <div class="main-content-inner">
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
            <form class="form-search" method="get" action="<?php echo Config::get('URL') . 'kasir/index/';?>">
              <span class="input-icon">
                <input type="text" placeholder="Search ..." class="nav-search-input" id="nav-search-input" autocomplete="off" name="find" value="<?php if(isset($_GET['find'])){ echo $_GET['find'];}?>" />
                <i class="ace-icon fa fa-search nav-search-icon"></i>
              </span>
            </form>
        </div><!-- /.nav-search -->
      </div>

      <div class="collapse" id="changeDateRange">
        <div class="well">
          <form method="post" action="<?php echo Config::get('URL') . 'kasir/reportByDate/'; ?>">
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
      <!-- /section:basics/content.breadcrumbs -->
      <?php $this->renderFeedbackMessages();?>
      <div class="table-responsive">
          <table class="table table-striped table-bordered table-hover ExcelTable2007">
          <thead>
          <tr class="info">
              <th class="text-center" colspan="12"><?php echo $this->title; ?></th>
            </tr>
          <tr>
            <th class="text-center">#</th>
            <th class="text-center">Tanggal</th>
            <th class="text-center">#Transaksi</th>
            <th class="text-center">Customer</th>
            <th class="text-center">Item</th>
            <th class="text-center">Qty</th>
            <th class="text-center">Harga</th>
            <th class="text-center">PPN</th>
            <th class="text-center">Sub</th>
            <th class="text-center">Sales</th>
            <th class="text-center">
                <span class="glyphicon glyphicon-print" aria-hidden="true"></span></span>
            </th>
            <?php if (Auth::isPermissioned('director')) { ?>
            <th class="text-center">
                delete
            </th>
            <?php } //if (Auth::isPermissioned('director')) { ?>
          </tr>
          </thead>

          <tbody>
          <?php
          $no = 1;
          $total_harga = 0;
          $total_qty = 0;
          $total_sales = 0;
          foreach($this->transaction_group as $key => $value) {
              $total_item = explode('-, -', $value->material_name);
              $total_quantity = explode('-, -', $value->quantity);
              $total_price = explode('-, -', $value->selling_price);
              $total_tax_ppn = explode('-, -', $value->tax_ppn);
              $row = count($total_item);
              if ($row > 1) {
                  for ($i=1; $i <= $row; $i++) {
                      $x = $i - 1;
                      if ($i === 1) {
                          echo '<tr>';
                          echo '<td class="text-right heading" rowspan="' . $row . '">' . $no . '</td>';
                          echo '<td rowspan="' . $row . '">' . date("d M, y", strtotime($value->created_timestamp)) . '<br>' . date("H:i:s", strtotime($value->created_timestamp)) . '</td>';
                          echo '<td  rowspan="' . $row . '"><a href="' .  Config::get('URL') . 'kasir/detail/?transaction_number=' . urlencode($value->transaction_number) . '">' . $value->transaction_number . '</a></td>';
                          if (!empty($value->table_number)) {
                              $table_number = '<span class="badge badge-warning"><span class="glyphicon glyphicon-bookmark" aria-hidden="true"></span> ' . $value->table_number . '</span><br>';
                          } else {
                              $table_number = '';
                          }
                          if (!empty($value->customer_name)) {
                              $customer_name = '<kbd>' . $value->customer_name . '</kbd>';
                          } else {
                              $customer_name = '';
                          }
                          echo '<td  rowspan="' . $row . '" class="text-center">' . $table_number . $customer_name . '</td>';
                          echo '<td>' . $total_item[$x] . '</td>';
                          echo '<td class="text-right">' . number_format($total_quantity[$x],0) . '</td>';
                          echo '<td class="text-right">' . number_format($total_price[$x],0) . '</td>';
                          echo '<td class="text-right">' . number_format($total_tax_ppn[$x],0) . '</td>';
                          $sub_total = ($total_quantity[$x] * $total_price[$x]);
                          $sub_total_with_ppn = $sub_total + (($total_tax_ppn[$x]/100) * $sub_total);
                          echo '<td class="text-right">' . number_format($sub_total_with_ppn,0) . '</td>';
                          echo '<td rowspan="' . $row . '">' . $value->full_name . '</td>';
                          echo '<td rowspan="' . $row . '"><a href="#" onclick=printPage("' . Config::get('URL') . 'kasir/printKasir/?transaction_number=' . urlencode($value->transaction_number) . '")>
                            <span class="badge badge-inverse">
                            <span class="glyphicon glyphicon-print" aria-hidden="true"></span>
                            </span>
                          </a>
                          </td>';
                          if (Auth::isPermissioned('director')) {
                            echo '<td rowspan="' . $row . '">
                            <a href="' . Config::get('URL') . 'kasir/deleteSales/?transaction_number=' . urlencode($value->transaction_number) . '&forward=kasir/laporan/" onclick="return confirmation(\'Are you sure to delete?\');">
                              <span class="badge badge-danger">
                              <span class="glyphicon glyphicon-remove" aria-hidden="true"></span> Delete
                              </span>
                            </a>
                            </td>';
                          }
                          echo "</tr>";
                          $total_harga = $total_harga + $sub_total_with_ppn;
                          $total_qty = $total_qty + $total_quantity[$x];
                      } else {
                          echo '<tr>';
                          echo '<td>' . $total_item[$x] . '</td>';
                          echo '<td class="text-right">' . number_format($total_quantity[$x],0) . '</td>';
                          echo '<td class="text-right">' . number_format($total_price[$x],0) . '</td>';
                          echo '<td class="text-right">' . number_format($total_tax_ppn[$x],0) . '</td>';
                          $sub_total = ($total_quantity[$x] * $total_price[$x]);
                          $sub_total_with_ppn = $sub_total + (($total_tax_ppn[$x]/100) * $sub_total);
                          echo '<td class="text-right">' . number_format($sub_total_with_ppn,0) . '</td>';
                          echo "</tr>";
                          $total_harga = $total_harga + $sub_total_with_ppn;
                          $total_qty = $total_qty + $total_quantity[$x];
                      }
                      
                  } //end for
                  $total_harga_setelah_discount = $total_harga - $value->discount_total;
                  echo '<tr class="success">';
                  echo '<td colspan="2" class="text-right">Total Harga:</td>';
                  echo '<td colspan="2" class="text-right">' . number_format($total_harga,0) . '</td>';
                  echo '<td colspan="1" class="text-right">Total Discount:</td>';
                  echo '<td colspan="2" class="text-right">' . number_format($value->discount_total,0) . '</td>';
                  echo '<td colspan="2" class="text-right">Total Penjualan:</td>';
                  echo '<td colspan="2" class="text-right">' . number_format($total_harga_setelah_discount,0) . '</td>';
                  echo "</tr>";
                  $total_sales = $total_sales + $total_harga_setelah_discount;
              } else {
                  echo '<tr>';
                  echo '<td class="text-right heading">' . $no . '</td>';
                  echo '<td>' . date("d M, y", strtotime($value->created_timestamp)) . '<br>' . date("H:i:s", strtotime($value->created_timestamp)) .'</td>';
                  echo '<td><a href="' .  Config::get('URL') . 'kasir/detail/?transaction_number=' . urlencode($value->transaction_number) . '">' . $value->transaction_number . '</a></td>';
                  if (!empty($value->table_number)) {
                      $table_number = '<span class="badge badge-warning"><span class="glyphicon glyphicon-bookmark" aria-hidden="true"></span> ' . $value->table_number . '</span><br>';
                  } else {
                      $table_number = '';
                  }
                  if (!empty($value->customer_name)) {
                      $customer_name = '<kbd>' . $value->customer_name . '</kbd>';
                  } else {
                      $customer_name = '';
                  }
                  echo '<td class="text-center">' . $table_number . $customer_name . '</td>';
                  echo '<td>' . $value->material_name . '</td>';
                  echo '<td class="text-right">' . number_format($value->quantity) . '</td>';
                  echo '<td class="text-right">' . number_format($value->selling_price) . '</td>';
                  echo '<td class="text-right">' . number_format($value->tax_ppn) . '</td>';
                  
                  $sub_total = ($value->quantity * $value->selling_price);
                  $sub_total_with_ppn = $sub_total + (($value->tax_ppn/100) * $sub_total);
                  echo '<td class="text-right">' . number_format($sub_total_with_ppn,0) . '</td>';
                  echo '<td>' . $value->full_name . '</td>';
                  echo '<td><a href="#" onclick=printPage("' . Config::get('URL') . 'kasir/printKasir/?transaction_number=' . urlencode($value->transaction_number) . '")>
                    <span class="badge badge-inverse">
                    <span class="glyphicon glyphicon-print" aria-hidden="true"></span>
                    </span>
                  </a></td>';
                  if (Auth::isPermissioned('director')) {
                    echo '<td>
                    <a href="' . Config::get('URL') . 'kasir/deleteSales/?transaction_number=' . urlencode($value->transaction_number) . '&forward=kasir/laporan/" onclick="return confirmation(\'Are you sure to delete?\');">
                      <span class="badge badge-danger">
                      <span class="glyphicon glyphicon-remove" aria-hidden="true"></span> Delete
                      </span>
                    </a></td>';
                  }
                  echo "</tr>";
                  $total_harga = $sub_total_with_ppn;
                  $total_qty = $total_qty + $value->quantity;

                  $total_harga_setelah_discount = $total_harga - $value->discount_total;
                  echo '<tr class="success">';
                  echo '<td colspan="2" class="text-right">Total Harga:</td>';
                  echo '<td colspan="2" class="text-right">' . number_format($total_harga,0) . '</td>';
                  echo '<td colspan="1" class="text-right">Total Discount:</td>';
                  echo '<td colspan="2" class="text-right">' . number_format($value->discount_total,0) . '</td>';
                  echo '<td colspan="2" class="text-right">Total Penjualan:</td>';
                  echo '<td colspan="2" class="text-right">' . number_format($total_harga_setelah_discount,0) . '</td>';
                  echo "</tr>";
                  $total_sales = $total_sales + $total_harga_setelah_discount;
              } //end if              
              $no++;
              $total_harga = 0;
          }
          ?>
          <tr class="info">
            <td class="text-right" colspan="5">TOTAL</td>
            <td class="text-right"><?php echo number_format($total_qty, 0); ?></td>
            <td class="text-right" colspan="5"><?php echo number_format($total_sales, 0); ?></td>
            <td class="text-right"></td>
          </tr>
          </tbody>
          </table>
        </div>

  </div><!-- /.main-content-inner -->
</div><!-- /.main-content -->
<script type="text/javascript">

// FUNCTION FOR PRINT
function closePrint () {
  document.body.removeChild(this.__container__);
}

function setPrint () {
  this.contentWindow.__container__ = this;
  this.contentWindow.onbeforeunload = closePrint;
  this.contentWindow.onafterprint = closePrint;
  this.contentWindow.focus(); // Required for IE
  this.contentWindow.print();
}

function printPage (sURL) {
  var oHiddFrame = document.createElement("iframe");
  oHiddFrame.onload = setPrint;
  oHiddFrame.style.visibility = "hidden";
  oHiddFrame.style.position = "fixed";
  oHiddFrame.style.right = "0";
  oHiddFrame.style.bottom = "0";
  oHiddFrame.src = sURL;
  document.body.appendChild(oHiddFrame);
}
// END FUNCTION FOR PRINT

</script>