<?php //Debuger::jam($this->make_do); ?>
<div class="main-content">
  <div class="main-content-inner">
        <!-- #section:basics/content.breadcrumbs -->
        <div class="breadcrumbs" id="breadcrumbs">
          <script type="text/javascript">
            try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
          </script>
          <?php if ($this->so->status != 100) { //closed ?>
             
          <ul class="breadcrumb">
            <li>
              <?php
                if (Auth::isMatch(SESSION::get('user_name'), 'nur,root')) {
                //Not showed if SO already approved
                if ($this->so->status == -1) { ?>          
                  <div class="btn-group btn-corner">
                      <a href="<?php echo  Config::get('URL') . 'so/deleteSo/?so_number=' . urlencode($this->so->transaction_number) . '&forward=so/waitingApproval/'; ?> " onclick="return confirmation('Are you sure to delete?');" class="btn btn-minier btn-danger"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span> delete</a>

                      <a role="button" data-toggle="collapse" href="#give-feedback" aria-expanded="false" aria-controls="give-feedback" class="btn btn-minier btn-warning">
                        <span class="glyphicon glyphicon-envelope" aria-hidden="true" aria-label="edit"></span> Give Feedback
                      </a>

                      <a href="#" onclick="approveSo(<?php echo "'" . $this->so->transaction_number . "','" . $this->so->delivery_request_date . "'";?>);" class="btn btn-minier btn-success"><span class="glyphicon glyphicon-ok" aria-hidden="true"></span> approve</a>
                    </div>
                <?php 
                  } // if ($this->so->status == -1)
                  } // if (Auth::isPermissioned('director,sales,finance,management')) {
                ?>
             
                <?php if ($this->so->status >= 0) { //setelah di approve baru nongol?> 
                  <div class="btn-group btn-corner" role="group">
                    <?php if (Auth::isPermissioned('director,finance,sales')) { ?>
                    <div class="btn-group btn-corner">
                          <button data-toggle="dropdown" class="btn btn-minier btn-primary btn-white dropdown-toggle">
                            Payment
                            <i class="ace-icon fa fa-angle-down icon-on-right"></i>
                          </button>

                          <ul class="dropdown-menu">
                            <li>
                              <a href="#manage-payment" role="button" data-toggle="modal"><span class="glyphicon glyphicon-usd" aria-hidden="true" aria-label="edit"></span> Manage Payment Schedule</a>
                            </li>

                            <li>
                              <a  href="<?php echo Config::get('URL') . 'bukuBesar/confirmPayment/?transaction_number=' . urlencode($this->so->transaction_number); ?>"><span class="glyphicon glyphicon-list"></span> Payment List</a>
                            </li>
                          </ul>
                      </div>
                      <?php } //if (Auth::isPermissioned('director,finance')) { ?>
                      <?php if (Auth::isPermissioned('director,finance')) {
                            if ($this->so->status >= 1 AND $this->so->status < 4) { //setelah ada pembayaran?> 
                            <a href="<?php echo  Config::get('URL') . 'so/openDo/?so_number=' . urlencode($this->so->transaction_number); ?> " onclick="return confirmation(\'Are you sure to open access for DO (delivery order)\');" class="btn btn-minier btn-warning"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span> Open DO Access</a>
                            <?php } 
                              if ($this->so->status == 7) { //setelah ada pembayaran dan sudah minta request SJ ?>

                            <?php if (Auth::isMatch(SESSION::get('user_name'), 'nur,eko,root')) { ?>
                            <a href="<?php echo  Config::get('URL') . 'so/approveSj/?so_number=' . urlencode($this->so->transaction_number); ?> " onclick="return confirmation(\'Are you sure to approve Surat Jalan\');" class="btn btn-minier btn-info"><span class="glyphicon glyphicon-check" aria-hidden="true"></span> Approve SJ</a>
                            <?php } ?>
                      <?php } elseif ($this->so->status >= 8) { ?>
                            <a href="<?php echo  Config::get('URL') . 'so/closeSj/?so_number=' . urlencode($this->so->transaction_number); ?> " onclick="return confirmation(\'Are you sure to open access for DO (delivery order)\');" class="btn btn-minier btn-info"><span class="glyphicon glyphicon-check" aria-hidden="true"></span> Close SJ</a>
                      <?php } // elseif ($this->so->status >= 8) ?>
                    </div>
                      <?php } // if (Auth::isPermissioned('director,finance')) ?>
                <?php } // if ($this->so->status >= 0) ?>

                <?php if ($this->so->status >= 4) { //setelah diapprove access do?>
                &nbsp;
                <div class="btn-group btn-corner" role="group">
                      <div class="btn-group btn-corner" role="group">
                        <button type="button" class="btn btn-minier btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                          DO
                          <i class="ace-icon fa fa-angle-down icon-on-right"></i>
                        </button>
                        <ul class="dropdown-menu">
                          <li>
                              <a role="button" data-toggle="collapse" href="#insert-do" aria-expanded="false" aria-controls="insert-do">
                              <span class="glyphicon glyphicon-edit" aria-hidden="true" aria-label="edit"></span> Create DO
                            </a>
                          </li>
                          <li>
                              <a role="button" data-toggle="collapse" href="#do-list" aria-expanded="false" aria-controls="do-list">
                              <span class="glyphicon glyphicon-list" aria-hidden="true" aria-label="edit"></span> DO List
                            </a>
                          </li>
                          <li role="separator" class="divider"></li>
                          <li class="dropdown-header">Print DO</li>
                          <li role="separator" class="divider"></li>
                          <?php
                              $sj = '';
                              foreach($this->sj as $key => $value) {
                                  if ($value->do_number != $sj) {
                                      echo '<li><a href="' . Config::get('URL') . 'so/printDo/?sj_number=' . urlencode($value->do_number) . '"><span class="glyphicon glyphicon-print"></span> ' . $value->do_number . '</a></li>';
                                  }
                                  $sj = $value->do_number;
                              }
                          ?>

                        </ul>
                      </div>

                      <?php if ($this->so->status > 4 ) { ?>
                      <div class="btn-group" role="group">
                        <button type="button" class="btn btn-minier btn-success dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                          SJ
                          <i class="ace-icon fa fa-angle-down icon-on-right"></i>
                        </button>
                        <ul class="dropdown-menu">
                          <li class="dropdown-header">Print SJ</li>
                          <li role="separator" class="divider"></li>
                          <?php
                              $sj = '';
                              foreach($this->sj as $key => $value) {
                                  if ($value->do_number != $sj) {
                                      echo '<li><a href="' . Config::get('URL') . 'so/printSj/?sj_number=' . urlencode($value->do_number) . '"><span class="glyphicon glyphicon-print"></span> ' . $value->do_number . '</a></li>';
                                  }
                                  $sj = $value->do_number;
                              }
                          ?>
                        </ul>
                      </div>
                    <?php } //if ($this->so->status > 4 ) ?>

                      <?php if ($this->so->status == 5) { //show jika status dibawah 7/ minta approval ?>
                      <a href="<?php echo Config::get('URL') . 'so/askSjApproval/?so_number=' . urlencode($this->so->transaction_number);?>" class="btn btn-minier btn-inverse">
                        <span class="glyphicon glyphicon-send" aria-hidden="true"></span> Ask SJ Approval
                      </a>
                      <?php } ?>
                </div>
                &nbsp;
                <?php } //if ($this->so->status >= 4) { //setelah diapprove access do?>

                <?php if ($this->so->status < -1) { ?>  
                  <a href="<?php echo Config::get('URL') . 'so/askSoApproval/?so_number=' . urlencode($this->so->transaction_number);?>" >
                    <span class="badge badge-inverse">
                    <span class="glyphicon glyphicon-send" aria-hidden="true"></span> Ask SO Approval
                    </span>
                  </a>
                <?php } //if ($this->so->status < -1) { ?>

                <?php 
                //Not showed if SO already approved
                if ($this->so->status < 0) { ?>
                  &nbsp;
                  <div class="btn-group btn-corner">
                      <a href="<?php echo  Config::get('URL') . 'productionForcasting/forcast/?so_number=' . urlencode($this->so->transaction_number) . '&quantity=' . (int)$this->transaction[0]->quantity; ?> " class="btn btn-minier btn-default"><span class="glyphicon glyphicon-record" aria-hidden="true"></span> Pilih BOM</a>

                      <?php if (!empty($this->production_forcasting[0]->transaction_number)) {
                        // show hanya jika sudah ada forcasting, gak masuk akal kalo belum ada apa2 tapi ditampilkan edit dan reset
                      ?>

                        <a href="<?php echo  Config::get('URL') . 'productionForcasting/deleteForcasting/?transaction_number=' . urlencode($this->production_forcasting[0]->transaction_number) . '&forward=' . $_SERVER['REQUEST_URI']; ?>" onclick="return confirmation('Are you sure to reset forcasting of production?');" class="btn btn-minier btn-danger"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span> Reset BOM</a>
                      <?php } ?>
                  </div>
                   &nbsp;
                <?php } ?>


                <a href="<?php echo Config::get('URL') . 'so/printSo/?so_number=' . urlencode($this->so->transaction_number);?>" >
                    <span class="badge badge-inverse">
                    <span class="glyphicon glyphicon-print" aria-hidden="true"></span> Print SO
                    </span>
                </a>

                &nbsp;

                <div class="btn-group btn-corner">
                  <a href="<?php echo Config::get('URL') . 'so/closeSo/?so_number=' . urlencode($this->so->transaction_number);?>" class="btn btn-minier btn-warning">
                    <span class="glyphicon glyphicon-lock" aria-hidden="true"></span> Close SO
                  </a>
                  <a href="<?php echo Config::get('URL') . 'so/deleteSo/?so_number=' . urlencode($this->so->transaction_number) . '&forward=so/draftSo'; ?>" onclick="return confirmation('Are you sure to delete?');" class="btn btn-minier btn-danger"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span> Delete</a>
                  <a href="<?php echo Config::get('URL') . 'so/editSO/?so_number=' . urlencode($this->so->transaction_number) . '&forward=so/draftSo'; ?>" class="btn btn-minier btn-info"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span> Edit</a>
                </div>
          </li>

          </ul><!-- /.breadcrumb -->
          <?php }  else { //($this->so->status != 100)  ?>
          <ul class="breadcrumb">
            <li>
                <a href="<?php echo Config::get('URL') . 'so/printSo/?so_number=' . urlencode($this->so->transaction_number);?>" >
                  <span class="badge badge-inverse">
                  <span class="glyphicon glyphicon-print" aria-hidden="true"></span> Print SO
                  </span>
                </a>
              </li>

              <li>
                <a href="<?php echo Config::get('URL') . 'so/openSo/?so_number=' . urlencode($this->so->transaction_number);?>" >
                  <span class="badge badge-info">
                  <span class="glyphicon glyphicon-lock" aria-hidden="true"></span> Open SO
                  </span>
                </a>
              </li>

          </ul><!-- /.breadcrumb -->
          <?php } //($this->so->status != 100)  ?>

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
  <th rowspan="2" class="center">Serial Number</th>
</tr>
<tr>
  <th  class="center">Order</th>
  <th  class="center">Pengiriman Sebelumnya</th>
  <th  class="center">DO</th>
</tr>
</thead>
<tbody>
<?php
//cari serial number yang sudah selesai
$finished_serial_number = [];
foreach($this->production_result_list as $key => $value) {
    $finished_serial_number[$value->material_code][] = $value->serial_number;
}
$no = 1;
foreach($this->make_do as $key => $value) {
    echo '<tr id="row_' . $no . '">';
    echo '<td class="text-right">' . $no . '</td>';
    echo '<td>' . $value->material_name . '. (' . $value->material_code . ')</td>';
     echo '<td>' . floatval($value->quantity) . '</td>';
    echo '<td>' . floatval($value->total_quantity_delivered) . '</td>';
    echo '<td style="width: 100px;"><input type="number" name="qty_' . $no . '" class="text-right" style="border-bottom: 1px dotted;"></td>';
    echo '<td>
        <input type="text" class="datepicker" name="date_' . $no . '" data-date-format="yyyy-mm-dd" style="width: 100px; border-bottom: 1px dotted;" autocomplete="off">
        <input type="hidden" name="material_code_' . $no . '" value="' . $value->material_code . '">
        
        <input type="hidden" name="qty_purchased_' . $no . '" value="' . $value->quantity . '">
        <input type="hidden" name="total_quantity_delivered_' . $no . '" value="' . $value->total_quantity_delivered . '">
    </td>';


    //var_dump($serialNumber);
    echo '<td style="min-width: 150px;">';
    $serial_number = $finished_serial_number[$value->material_code];
    $serial_number = implode(',', $serial_number);
    $serial_number = explode(',', $serial_number);
    $serial_number = array_filter($serial_number);
    $new_sn = [];
    foreach ($serial_number as $key => $value) {
      $new_sn[] = trim($value);
    }
    sort($new_sn);
    $serial = 0;
    foreach ($new_sn as $sn) {
        if (!empty($sn)) { //make sure not to echo empty SN
            echo '<div class="checkbox"><label>
            <input name="serial_number_' . $no . '[' . $serial . ']' . '" type="checkbox" value="' . $sn. '" class="ace">
          <span class="lbl">' . $sn .'</span></label>
          </div>';
        }
        $serial++;
    }// end make sure not to echo empty SN

    echo '</td>';
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
                    echo '<td>' . floatval($value->quantity) . '</td>';
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
              <td><strong>Delivery Request:</strong></td>
              <td><?php echo date('d-M, Y', strtotime($this->so->delivery_request_date)) ;?></td>
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
              <td colspan="3"><a href="http://<?php echo $this->so->website;?>/" target="_blank"><?php echo $this->so->website;?></a></td>
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
                    if (Auth::isPermissioned('director,finance,purchasing,management,sales')) {
                      echo '<td class="text-right">' . number_format($value->selling_price,0) . '</td>';
                      echo '<td class="text-right">' . $value->tax_ppn . '</td>';
                      echo '<td class="text-right">' . $value->tax_pph . '</td>';
                      $subtotal_price = $value->quantity * $value->selling_price;
                      $subtotal_tax_ppn = ($value->tax_ppn/100) * $subtotal_price;
                      $subtotal_tax_pph = ($value->tax_pph/100) * $subtotal_price;
                      $subtotal_all = $subtotal_price + $subtotal_tax_ppn - $subtotal_tax_pph;
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
                  <td class="text-right" colspan="10"><strong>TOTAL PENJUALAN</strong></td>
                  <td style="text-align: right;"><?php echo number_format($total_transaction,0); ?></td>
                </tr>
              </tbody>
            </table>
            </div>
          </div>

<div class="row">
    <div class="col-xs-12 col-sm-12">
<div class="tabbable">
  <ul class="nav nav-tabs" id="myTab">
    <?php if (Auth::isPermissioned('director,ppic')) {
      echo '<li class="active">
          <a data-toggle="tab" href="#forcasting-produksi">
            Forcasting Produksi
          </a>
        </li>

        <li>
          <a data-toggle="tab" href="#uploaded-file">
            Uploaded File
          </a>
        </li>';
    } else {
      echo '<li class="active">
          <a data-toggle="tab" href="#uploaded-file">
            Uploaded File
          </a>
        </li>';
    }
    ?>


  </ul>

  <div class="tab-content">
    <?php if (Auth::isPermissioned('director,ppic')) { ?>
      <div id="forcasting-produksi" class="tab-pane box-case in active">
        <div class="table-responsive">
          <?php include("detail_forcasting_produksi.php"); //Call Contact Person ?>
        </div>
      </div>

      <div id="uploaded-file" class="tab-pane box-case">
        <div class="table-responsive">
          <?php include("detail_uploaded_file.php"); //Call Contact Person ?>
        </div>
      </div>
    <?php } else { //if (Auth::isPermissioned('director,ppic')) { ?>
      <div id="uploaded-file" class="tab-pane box-case in active">
        <div class="table-responsive">
          <?php include("detail_uploaded_file.php"); //Call Contact Person ?>
        </div>
      </div>
    <?php } ?>
    

    <div id="upload-image" class="tab-pane fade">
      <?php include("detail_upload_image.php"); //Call Contact Person ?>
    </div>

    <div id="upload-document" class="tab-pane fade">
      <?php include("detail_upload_document.php"); //Call Contact Person ?>
    </div>

  </div>
</div>
</div>
</div><!-- ./row -->

    

          <div class="panel panel-default">
            <div class="panel-heading">
              <h3 class="panel-title">REMARKS</h3>
            </div>
            <table class="table table-striped table-bordered table-hover ExcelTable2007">
            <tr>
              <td>
                <pre><?php echo $this->so->note; ?>
                </pre>
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
  <div class="modal-dialog modal-fullscreen" role="document">
    <div class="modal-content">
    <form method="post" action="<?php echo Config::get('URL') . 'so/insertPayment/?so_number=' . urlencode($this->so->transaction_number); ?>">
      <div class="modal-header no-padding">
        <div class="table-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
            <span class="white">×</span>
          </button>
          Manage Payment Schedule
        </div>
      </div>

      <div class="modal-body no-padding">
          <div class="table-responsive">
            <table class="table ExcelTable2007">
            <tr >
               <th>#</th>
               <th>Nominal</th>
               <th>PPN</th>
               <th>Cur</th>
               <th>No Invoice</th>
               <th>Tgl Invoice</th>
               <th>Tgl Jatuh Tempo</th>
               <th>Cara Pemebayaran</th>
               <th>Keterangan</th>
            </tr>
            <?php
                            for ($i=1; $i <=10; $i++) { ?>
            <tr>
              <td><?php  echo  $i;?></td>
              <td><input  type="text" class="form-control text-right" name="<?php echo "value[$i]"; ?>"></td>
                    <td><input  type="text" class="form-control text-right" name="<?php echo "ppn[$i]"; ?>"></td>
                    <td><select name="<?php echo "currency[$i]"; ?>">
                                <option value="IDR">IDR</option>
                                <option value="USD">USD</option>
                        </select>
                    </td>
                    <td>
                        <input class="form-control" name="<?php echo "invoice_number[$i]"; ?>" type="text">
                    </td>
                    <td>
                        <input class="datepicker form-control" name="<?php echo "invoice_date[$i]"; ?>"  data-date-format="yyyy-mm-dd" type="text">
                    </td>
                    <td>
                        <input class="datepicker form-control" name="<?php echo "payment_due_date[$i]"; ?>" id="<?php echo "payment_date_{$i}"; ?>" data-date-format="yyyy-mm-dd" type="text">
                    </td>
                    <td>
                        <select name="<?php echo "payment_type[$i]"; ?>" class="form-control">
                          <?php foreach ($this->payment_type as $key_payment_type => $value_payment_type) {

                                      echo '<option value="' . $value_payment_type->item_name . '">' . $value_payment_type->item_name . '</option>';
                                  }
                            ?>
                            
                        </select>
                    </td>
                    <td><textarea name="<?php echo "note[$i]"; ?>" class="form-control"></textarea></td>
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
  function approveSo(so_number, delivery_request_date) {
      //Send the string to server
      var http = new XMLHttpRequest();
      var url = "<?php echo Config::get('URL') . 'so/approveSo/'; ?>";
      var params = "so_number=" + so_number + "&delivery_request_date=" + delivery_request_date;
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
