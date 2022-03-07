<div class="main-content">
  <div class="main-content-inner">
        <!-- #section:basics/content.breadcrumbs -->
        <div class="breadcrumbs" id="breadcrumbs">
          <script type="text/javascript">
            try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
          </script>

          <ul class="breadcrumb" >
            <li>
              <div class="btn-group btn-corner">
                  <a href="<?php echo  Config::get('URL') . 'kasir/laporan'; ?>" class="btn btn-minier btn-default"><span class="glyphicon glyphicon-list" aria-hidden="true"></span> list</a>
                  <?php if (Auth::isPermissioned('director')) { ?>
                  <a href="<?php echo  Config::get('URL') . 'kasir/deleteSales/?transaction_number=' . urlencode($this->so->transaction_number) . '&forward=kasir/laporan/'; ?> " onclick="return confirmation('Are you sure to delete?');" class="btn btn-minier btn-danger"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span> delete</a>
                  <?php } //if (Auth::isPermissioned('director')) { ?>
              </div>
            </li>

            <li>
              <div class="btn-group btn-corner">
                  <div class="btn-group btn-corner">
                      <button data-toggle="dropdown" class="btn btn-minier btn-primary btn-white dropdown-toggle">
                        Pembayaran
                        <i class="ace-icon fa fa-angle-down icon-on-right"></i>
                      </button>

                      <ul class="dropdown-menu">
                        <li>
                          <a href="#manage-payment" role="button" data-toggle="modal">Rencana Pembayaran</a>
                        </li>

                        <li>
                          <a  href="<?php echo Config::get('URL') . 'bukuBesar/confirmPayment/?transaction_number=' . urlencode($this->so->transaction_number); ?>">List Pembayaran</a>
                        </li>
                      </ul>
                  </div>
                  <a href="<?php echo Config::get('URL') . 'kasir/printKasir/?transaction_number=' . urlencode($this->so->transaction_number);?>" class="btn btn-minier btn-info">
                <span class="glyphicon glyphicon-print" aria-hidden="true"></span> Print Kasir
              </a>

              </div>
            </li>
          </ul><!-- /.breadcrumb -->

          <!-- /section:basics/content.searchbox -->
        </div>

        <!-- /section:basics/content.breadcrumbs -->
        <div class="page-content">
    <div class="row">
      <div class="col-xs-12">
        <!-- PAGE CONTENT BEGINS -->

<?php $this->renderFeedbackMessages();?>


  <div class="collapse" id="give-feedback">
    <form class="form-horizontal" method="post" action="<?php echo Config::get('URL') . 'so/saveFeedback/';?>">
    <br>
    <div class="form-group">
      <label for="inputPassword3" class="col-sm-2 control-label">Feedback Message</label>
      <div class="col-sm-9">
        <textarea name="feedback_note" class="form-control" rows="3"></textarea>
        <input type="hidden" name="so_number" value="<?php echo $this->so->transaction_number; ?>" class="form-control" placeholder="Text input">
      </div>
    </div>
    <div class="form-group">
      <div class="col-sm-offset-2 col-sm-10">
        <button type="submit" class="btn btn-info">Send Feedback</button>
      </div>
    </div>
    </form>
  </div>

<div class="collapse" id="insert-do">
    <div class="table-responsive">
      <form method="post" action="<?php echo Config::get('URL') . 'so/makeDo/?so_number=' . urlencode($this->so->transaction_number); ?>">

<table class="table ExcelTable2007">
<thead>
<tr>
  <th class="center" colspan="7">Delivery Order</th>
</tr>
<tr>
  <th rowspan="2" class="center">#</th>
  <th rowspan="2" class="center">Nama Barang</th>
  <th colspan="3" class="center">Jumlah</th>
  <th rowspan="2" class="center">Tgl Pengiriman</th>
</tr>
<tr>
  <th  class="center">Order</th>
  <th  class="center">Pengiriman Sebelumnya</th>
  <th  class="center">DO</th>
</tr>
</thead>
<tbody>
<?php
$no = 1;
foreach($this->make_do as $key => $value) {
    echo '<tr id="row_' . $no . '">';
    echo '<td class="text-right">' . $no . '</td>';
    echo '<td>' . $value->material_name . '. (' . $value->material_code . ')</td>';
     echo '<td>' . floatval($value->quantity) . '</td>';
    echo '<td>' . floatval($value->total_quantity_delivered) . '</td>';
    echo '<td style="width: 100px;"><input type="number" name="qty_' . $no . '" style="border-bottom: 1px dotted;"></td>';
    echo '<td>
        <input type="text" class="datepicker" name="date_' . $no . '" data-date-format="yyyy-mm-dd" style="width: 100px; border-bottom: 1px dotted;">
        <input type="hidden" name="material_code_' . $no . '" value="' . $value->material_code . '">
        
        <input type="hidden" name="qty_purchased_' . $no . '" value="' . $value->quantity . '">
        <input type="hidden" name="total_quantity_delivered_' . $no . '" value="' . $value->total_quantity_delivered . '">
    </td>';          
    echo "</tr>";
    $no++;
}
?>
<tr>
  <td colspan="2">
    <input type="hidden" name="total_record" value="<?php echo ($no - 1); ?>">
    <input type="hidden" name="so_number" value="<?php echo $this->so->transaction_number; ?>">
    
    <a class="btn btn-danger" role="button" data-toggle="collapse" href="#insert-do" aria-expanded="false" aria-controls="insert-do" style="width: 100%;">
    Cancel
    </a>
  </td>
  <td colspan="6"><button type="submit" class="btn btn-primary" style="width: 100%;">Save</button></td>
</tr>
</tbody>
</table>
</form>
</div><!-- /.table-responsive -->
</div>

<div class="collapse" id="do-list">
    <div class="table-responsive">
<table class="table ExcelTable2007">
<thead>
  <tr>
  <th class="center" colspan="8">Delivery Order List</th>
</tr>
                <tr>
                  <th class="center">#</th>
                  <th class="center">Nama Barang</th>
                  <th class="center">Jumlah</th>
                  <th class="center">#DO Number</th>
                  <th class="center">Tgl Pengiriman</th>
                  <th class="center">Serial Number</th>
                  <th class="center">Status</th>
                  <th class="center">Delete</th>
                </tr>
              </thead>
            <tbody>
            <?php
            $no = 1;
            foreach($this->do as $key => $value) {
                  echo '<tr id="row_' . $no . '">';
                    echo '<td class="text-right">' . $no . '</td>';
                    echo '<td>' . $value->material_name . '. (' . $value->material_code . ')</td>';
                    echo '<td>' . $value->quantity . '</td>';
                    echo '<td>' . $value->do_number . '</td>';
                    echo '<td>' . date('d M, y', strtotime($value->delivery_date)) . '</td>';
                    echo '<td>' . $value->serial_number . '</td>';
                    echo '<td>Pending</td>';
                    echo '<td class="text-right"> 
                          <a href="' . Config::get('URL') . 'so/deleteDo/' . $value->uid . '/?so_number=' . urlencode($this->so->transaction_number) .'"><span class="badge badge-danger"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span> delete</span></a>
                    </td>';
                    echo "</tr>";
                $no++;
            }
            ?>
</table>
</div><!-- /.table-responsive -->
</div>

<?php
//render feedback from BOD
if (!empty($this->so->feedback_note)) {
  echo '<div class="alert alert-warning" role="alert"><span class="glyphicon glyphicon-comment bigger-120" aria-hidden="true"></span> '.$this->so->feedback_note.'</div>';
}
?>

<div class="row">
    <div class="col-xs-12 col-sm-4">
      <div class="panel panel-success">
        <div class="panel-heading">
          <h3 class="panel-title">Sales Order</h3>
        </div>
          <table class="table table-striped table-bordered table-hover">
          <tr>
              <td><strong>Number:</strong></td>
              <td><?php echo strtoupper($this->so->transaction_number);?></td>
            </tr>
          
            <tr>
              <td><strong>Date:</strong></td>
              <td><?php echo date('d-M, Y', strtotime($this->so->created_timestamp)) ;?></td>
            </tr>
            <tr>
              <td><strong>Sales Person:</strong></td>
              <td><?php echo $this->so->full_name;?></td>
          </table>
      </div>   
    </div><!-- /.col-sm-6 -->
    <div class="col-xs-12 col-sm-8">
      <div class="panel panel-info">
        <div class="panel-heading">
          <h3 class="panel-title">Customer Info</h3>
        </div>
          <table class="table table-striped table-bordered table-hover">
          <tr>
              <td><strong>Nama:</strong></td>
              <td colspan="3"><?php echo strtoupper($this->so->contact_name);?></td>
            </tr>
          
            <tr>
              <td><strong>Alamat:</strong></td>
              <td colspan="3"><?php echo $this->so->address_street;?></td>
            </tr>
            <tr>
              <td><strong>Kota:</strong></td>
              <td><?php echo $this->so->address_city;?></td>

              <td><strong>Propinsi:</strong></td>
              <td><?php echo $this->so->address_state;?></td>
            </tr>
            <tr>
              <td><strong>Zip:</strong></td>
              <td><?php echo $this->so->address_zip;?></td>

              <td><strong>Email:</strong></td>
              <td><?php echo $this->so->email;?></td>
            </tr>
            <tr>
              <td><strong>Phone:</strong></td>
              <td><?php echo $this->so->phone;?></td>

              <td><strong>Fax:</strong></td>
              <td><?php echo $this->so->fax;?></td>
            </tr>
            <tr>
              <td><strong>Website:</strong></td>
              <td colspan="3"><a href="http://<?php echo $this->contact->website;?>/" target="_blank"><?php echo $this->so->website;?></a></td>
            </tr>
          </table>
      </div>
    </div>
  </div><!-- ./row -->

      <div class="panel panel-primary">
        <div class="panel-heading">
          <h3 class="panel-title">Product List</h3>
        </div>
            <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover ExcelTable2007">
              <thead>
                <tr>
                  <th rowspan="2" class="center">#</th>
                  <th rowspan="2" class="center">Kode</th>
                  <th rowspan="2" class="center">Nama</th>
                  <th rowspan="2" class="center">Qty</th>
                  <th rowspan="2" class="center">Harga</th>
                  <th colspan="2" class="center">Pajak</th>
                  <th colspan="4" class="text-center">Sub Total</th>
                </tr>
                <tr>
                  <th class="center">PPN</th>
                  <th class="center">PPh</th>
                  <th class="center">Harga</th>
                  <th class="center">PPN</th>
                  <th class="center">PPh</th>
                  <th class="center">Total</th>
                </tr>
              </thead>
            <tbody>
            <?php
            $no = 1;
            $total_transaction = 0;
            foreach($this->transaction as $key => $value) {
      
                    echo '<tr id="row_' . $no . '">';
                    echo '<td class="text-right">' . $no . '</td>';
                    echo '<td><a href="' . Config::get('URL') . 'inventory/editMaterial/?find=' . urlencode($value->material_code) . '">' . $value->material_code . '</a></td>';
                    echo '<td>' . $value->material_name . '</td>';
                    echo '<td class="text-right">' . number_format($value->quantity,0) . '</td>';
                    if (Auth::isPermissioned('director,finance,purchasing')) {
                      echo '<td class="text-right">' . number_format($value->selling_price,0) . '</td>';
                      echo '<td class="text-right">' . $value->tax_ppn . '</td>';
                      echo '<td class="text-right">' . $value->tax_pph . '</td>';
                      $subtotal_price = $value->quantity * $value->selling_price;
                      $subtotal_tax_ppn = ($value->tax_ppn/100) * $subtotal_price;
                      $subtotal_tax_pph = ($value->tax_pph/100) * $subtotal_price;
                      $subtotal_all = $subtotal_price + $subtotal_tax_ppn + $subtotal_tax_pph;
                      $subtotal_all = (int)$subtotal_all;
                      echo '<td class="text-right">' . number_format($subtotal_price,0) . '</td>';
                      echo '<td class="text-right">' . number_format($subtotal_tax_ppn,0) . '</td>';
                      echo '<td class="text-right">' . number_format($subtotal_tax_pph,0) . '</td>';
                      echo '<td class="text-right">' . number_format($subtotal_all,0) . '</td>';
                    }
                    echo "</tr>";
                
                $no++;
                $total_transaction = $total_transaction + $subtotal_all; 
            }
            $total_transaction = (int)$total_transaction;
            ?>
                <tr class="info">
                  <td class="text-right" colspan="10"><strong>PENJUALAN</strong></td>
                  <td style="text-align: right;"><?php echo number_format($this->so->price_gross,0); ?></td>
                </tr>
                <tr class="danger">
                  <td class="text-right" colspan="10"><strong>DISCOUNT</strong></td>
                  <td style="text-align: right;"> -<?php echo number_format($this->so->discount_total,0); ?></td>
                </tr>
                <tr class="success">
                  <td class="text-right" colspan="10"><strong>TAGIHAN</strong></td>
                  <td style="text-align: right;"><?php echo number_format($this->so->price_net,0); ?></td>
                </tr>
                <tr class="warning">
                  <td class="text-right" colspan="10"><strong>PEMBAYARAN</strong></td>
                  <td style="text-align: right;"><?php echo number_format($this->so->received_payment,0); ?></td>
                </tr>
                <tr class="default">
                  <td class="text-right" colspan="10"><strong>KEMBALIAN</strong></td>
                  <td style="text-align: right;"><?php echo number_format($this->so->payment_return,0); ?></td>
                </tr>
              </tbody>
            </table>
            </div>
          </div>


          <div class="panel panel-default">
            <div class="panel-heading">
              <h3 class="panel-title">REMARKS</h3>
            </div>
            <table class="table table-striped table-bordered table-hover ExcelTable2007">
            <tr>
              <td>Table Number</td>
              <td>: <?php echo $this->so->customer_table_number; ?></td>
              <td>Customer Name</td>
              <td>: <?php echo $this->so->customer_name; ?></td>
            </tr>
            <tr>
              <td>EDC Bank</td>
              <td>: <?php echo $this->so->edc_bank; ?></td>
              <td>EDC Reference</td>
              <td>: <?php echo $this->so->edc_reference; ?></td>
            </tr>
            <tr>
              <td colspan="4">
                <pre><?php echo $this->so->note; ?></pre>
              </td>
            </tr>
          </table>
          </div>
<!-- PAGE CONTENT ENDS -->
      </div><!-- /.col -->
    </div><!-- /.row -->
  </div><!-- /.page-content -->
</div><!-- /.main-content-inner -->
</div><!-- /.main-content -->


<!-- MODAL Manage Payment -->
<div class="modal fade" id="manage-payment" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
    <form method="post" action="<?php echo Config::get('URL') . 'kasir/insertPayment/?transaction_number=' . urlencode($this->so->transaction_number); ?>">
      <div class="modal-header no-padding">
        <div class="table-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
            <span class="white">×</span>
          </button>
          Atur Rencana Pembayaran
        </div>
      </div>

      <div class="modal-body no-padding">
          <div class="table-responsive">
            <table class="table ExcelTable2007">
            <tr>
              <th class="heading">&nbsp;</th>
              <th>Value </th>
              <th>Schedule Date</th>
              <th>Note</th>
            </tr>
            <?php
                            for ($i=1; $i <=10; $i++) { ?>
            <tr>
              <td style="width: 30px;" class="heading"><?php echo $i; ?></td>
              <td><input type="number" class="form-control" name="value_<?php echo $i; ?>"></td>
              <td style="width: 100px;"><input class="form-control datepicker" name="payment_due_date_<?php echo $i; ?>" style="width: 100px;" data-date-format="yyyy-mm-dd"></td>
              <td><input class="form-control" name="note_<?php echo $i; ?>"></td>
            </tr>
            <?php } ?>
            </table>
            </div><!-- /.table-responsive -->
      <input type="hidden" name="total_order" value="<?php echo $total_transaction; ?>">
      </div>
      <div class="modal-footer no-margin-top">
        <button class="btn btn-sm btn-danger pull-left" data-dismiss="modal">
          <i class="ace-icon fa fa-times"></i>
          Close
        </button>
        <input type="submit" class="btn btn-sm btn-primary" value="Save">
      </div>
      </form>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- /MODAL Manage Payment -->

<script type="text/javascript">
  function approveSo(so_number) {
      //Send the string to server
      var http = new XMLHttpRequest();
      var url = "<?php echo Config::get('URL') . 'so/approveSo/'; ?>";
      var params = "so_number=" + so_number;
      //Send the proper header information along with the request
      http.open("POST", url, true);
      http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
      http.onreadystatechange = function() {//Call a function when the state changes.
        if(http.readyState === XMLHttpRequest.DONE && http.status == 200) {
            var salesCode = http.response;
            //check response from server, if contain strting sucess, save (force user to clik save again) and reset page
            if (salesCode.indexOf("SUKSES") === -1) {
                  alert(salesCode);
                } else {
                  alert(salesCode);
                  window.location.reload(); //refresh page
                }
          }
        }
      http.send(params);
}

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