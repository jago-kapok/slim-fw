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
          <form method="post" action="<?php echo Config::get('URL') . 'kasir/summary/'; ?>">
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
          <table class="table table-striped table-bordered table-hover">
          <thead>
            <tr class="info">
              <th class="text-center" colspan="6"><?php echo $this->title; ?></th>
            </tr>
            <tr>
              <th class="text-right">#</th>
              <th class="text-left">#Kode</th>
              <th class="text-left">Nama</th>
              <th class="text-left">Jumlah</th>
              <th class="text-right">Harga</th>
              <th class="text-right">Sub</th>
            </tr>
          </thead>

          <tbody>
          <?php
          $no = 1;
          $total_qty = 0;
          $total_sales = 0;
          foreach($this->quantity_penjualan_per_product as $key => $value) {
                  echo '<tr>';
                  echo '<td class="text-right heading">' . $no . '</td>';
                  echo '<td>' . $value->material_code . '</td>';
                  echo '<td>' . $value->material_name . '</td>';
                  echo '<td class="text-right">' . number_format($value->quantity) . '</td>';
                  echo '<td class="text-right">' . number_format($value->selling_price) . '</td>';
                  //echo '<td class="text-right">' . number_format($value->tax_ppn) . '</td>';
                  
                  $sub_total = ($value->quantity * $value->selling_price);
                  $sub_total_with_ppn = $sub_total + (($value->tax_ppn/100) * $sub_total);
                  echo '<td class="text-right">' . number_format($sub_total_with_ppn,0) . '</td>';
                  echo "</tr>";
              $total_qty = $total_qty + $value->quantity;
              $total_sales = $total_sales + $sub_total_with_ppn;
              $no++;
          }
          ?>
            <tr class="warning">
              <td class="text-right" colspan="3">TOTAL</td>
              <td class="text-right"><?php echo number_format($total_qty, 0); ?></td>
              <td class="text-right" colspan="2"><?php echo number_format($total_sales, 0); ?></td>
            </tr>
            <tr class="danger">
              <td class="text-right" colspan="5">TOTAL DISCOUNT</td>
              <td class="text-right" colspan="2"><?php echo number_format($this->discount_total->discount_total,0); ?></td>
            </tr>
            <tr class="success">
              <td class="text-right" colspan="5">TOTAL PENJUALAN</td>
              <td class="text-right" colspan="2"><?php echo number_format(($total_sales - $this->discount_total->discount_total),0); ?></td>
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