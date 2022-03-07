<?php //Debuger::jam($this->pr_item_list); ?>
<div class="main-content">
                <div class="main-content-inner">
                    <div class="breadcrumbs ace-save-state" id="breadcrumbs">
                      <?php if ($this->pr_data->status != 100) { //void or close ?>
                        <ul class="breadcrumb">
                            <?php if ($this->pr_data->status <= -2) { ?>
                            <li>
                                <a href="<?php echo Config::get('URL') . 'po/askPrApproval/?po_number=' . urlencode($this->pr_data->transaction_number)  . '&forward=po/index/pr'; ?>" onclick="return confirmation('Minta persetujuan direksi?');">
                                <span class="badge badge-info">
                                <span class="glyphicon glyphicon-send" aria-hidden="true"></span> Ask Approval
                                </span>
                              </a>
                            </li>
                            <?php } elseif ($this->pr_data->status >= 0 AND $this->pr_data->status != 20) { ?>
                              <li>
                              <a href="<?php echo Config::get('URL') . 'po/askEditPO/?po_number=' . urlencode($this->pr_data->transaction_number)  . '&forward=po/index/pr'; ?>" onclick="return confirmation('Minta perubahan harga dan PO ke direksi?');">
                                <span class="badge badge-info">
                                <span class="glyphicon glyphicon-send" aria-hidden="true"></span> Ask Price Change
                                </span>
                              </a>
                            </li>
                            <?php } ?>
                            <?php
          //Cek approval oleh direksi, apakah memiliki wewenang sesuai batas limit atau tidak

          //cek total pembelian apakah melebihi limit
          $total_price = 0;
          foreach($this->pr_item_list as $key => $value) {
              $price_after_discount = ($value->purchase_price - $value->purchase_price_discount) * $value->purchase_currency_rate;
              $price_after_discount_after_tax = $price_after_discount + ($price_after_discount * ($value->purchase_tax/100));
              $price_per_item = $price_after_discount_after_tax;
              $sub_total = $price_per_item * $value->quantity;
              $total_price = $total_price + $sub_total;
          }
          
        $total_price_plus_shipment_in_idr = $total_price + $this->shipment_data->purchase_price;        
        //check limit approval
        $user_authenticated = false;
        if ($total_price_plus_shipment_in_idr > $this->limit_approval->value AND $this->is_above_limit_approval) {
          $user_authenticated = true;
        } elseif ($total_price_plus_shipment_in_idr < $this->limit_approval->value AND $this->is_under_limit_approval) {
          $user_authenticated = true;
        }

          //check user auth
          if ($this->pr_data->status == -1 AND Auth::isPermissioned('director') AND $user_authenticated) {
          ?>
          <li>
              <div class="btn-group btn-corner">
                <a href="<?php echo Config::get('URL') . 'po/deleteTransaction/?po_number=' . urlencode($this->pr_data->transaction_number)  . '&forward=po/index/pr'; ?>" onclick="return confirmation('Are you sure to void purchase request?');" class="btn btn-danger btn-minier">
                  <i class="icon-close"></i>
                  delete
                </a>
                <a href="#feedback-pr" role="button" data-toggle="modal" class="btn btn-warning btn-minier">
                <i class="icon-pencil"></i>
                  Give Feedback
                </a>
                <a href="<?php echo Config::get('URL') . 'po/approvePr/?po_number=' . urlencode($this->pr_data->transaction_number); ?>"  onclick="return confirmation('Approve purchase request?');" class="btn btn-info btn-minier">
                  <i class="icon-check"></i>
                  Approve
                </a>
                
              </div>
          </li>
          <?php } elseif ($this->pr_data->status == 20 AND Auth::isPermissioned('director') AND $user_authenticated) {
          ?>
          <li>
              <div class="btn-group btn-corner">
                <a href="#feedback-ask-edit-po" role="button" data-toggle="modal" class="btn btn-warning btn-minier">
                <i class="icon-pencil"></i>
                  Give Feedback
                </a>
                <a href="<?php echo Config::get('URL') . 'po/approveAskEditPO/?po_number=' . urlencode($this->pr_data->transaction_number); ?>"  onclick="return confirmation('Approve purchase price change request?');" class="btn btn-info btn-minier">
                  <i class="icon-check"></i>
                  Approve
                </a>
                <a href="<?php echo Config::get('URL') . 'po/rejectAskEditPO/?po_number=' . urlencode($this->pr_data->transaction_number)  . '&forward=po/index/pr'; ?>" onclick="return confirmation('Reject purchase price change request?');" class="btn btn-danger btn-minier">
                  <i class="icon-close"></i>
                  reject
                </a>
              </div>
          
          <?php } ?>

          <?php if ($this->pr_data->status >= 0 ) { ?>
            &nbsp;
              <div class="btn-group btn-corner">
              <?php if ($this->pr_data->status < 12 AND Auth::isPermissioned('director,management,ppic')) { // 11& 12 receive barang and check permission?>
                    <a href="#receive-material" role="button" data-toggle="modal" class="btn btn-primary btn-info btn-minier"> 
                      Reveive Material
                      <i class="ace-icon glyphicon glyphicon-save icon-on-right"></i>
                    </a>
              <?php } ?>

              <?php if (Auth::isPermissioned('director,management,qc')) { // QC Approval ?>
                  <a href="#approve-qc-manual" role="button" data-toggle="collapse" class="btn btn-minier btn-success">
                    <span class="menu-icon fa fa-check" aria-hidden="true" aria-label="edit"></span> QC Approval
                  </a>
              <?php } ?>

                  <?php if (Auth::isPermissioned('director,management,finance,purchasing')) { //check permission ?>
                  <div class="btn-group">
                    <button data-toggle="dropdown" class="btn btn-primary btn-white btn-minier dropdown-toggle">
                      Pembayaran
                      <i class="ace-icon fa fa-angle-down icon-on-right"></i>
                    </button>

                    <ul class="dropdown-menu">
                      <li>
                        <a href="#manage-payment" role="button" data-toggle="modal"><span class="glyphicon glyphicon-usd" aria-hidden="true" aria-label="edit"></span> Buat Rencana Pembayaran</a>
                      </li>

                      <li>
                        <a  href="<?php echo Config::get('URL') . 'bukuBesar/confirmPayment/?transaction_number=' . urlencode($this->pr_data->transaction_number); ?>"><span class="glyphicon glyphicon-list"></span> Daftar Pembayaran Yand Sudah Dimasukkan</a>
                      </li>
                    </ul>
                  </div><!-- /.btn-group -->
                  <?php } //check permission ?>

                  <a href="<?php echo Config::get('URL') . 'po/closePO/?po_number=' . urlencode($this->pr_data->transaction_number);?>" class="btn btn-primary btn-danger btn-minier">
                  Close PO
                  <span class="glyphicon glyphicon-lock" aria-hidden="true"></span> 
                  </a>
                </div><!-- /.btn-group -->
            
          <?php } ?>

          &nbsp;
            <a href="<?php echo Config::get('URL') . 'po/printPo/?po_number=' . urlencode($this->pr_data->transaction_number);?>" >
              <span class="badge badge-success">
              <span class="glyphicon glyphicon-print" aria-hidden="true"></span> Print PO
              </span>
            </a>
          </li>
      </ul><!-- /.breadcrumb -->
      <?php } else { ?>
      <ul class="breadcrumb">
          <li>
            <a href="<?php echo Config::get('URL') . 'po/printPo/?po_number=' . urlencode($this->pr_data->transaction_number);?>" >
              <span class="badge badge-success">
              <span class="glyphicon glyphicon-print" aria-hidden="true"></span> Print PO
              </span>
            </a>

            <a href="<?php echo Config::get('URL') . 'po/reopenPO/?po_number=' . urlencode($this->pr_data->transaction_number);?>" >
              <span class="badge badge-info">
              <span class="glyphicon glyphicon-refresh" aria-hidden="true"></span>  Reopen PO
              </span>
            </a>
          </li>
      </ul><!-- /.breadcrumb -->
      <?php } //void or close ?>
  </div>

  <div class="page-content">
      <div class="row">
          <div class="col-xs-12">
              <!-- PAGE CONTENT BEGINS -->


<?php // ALERT FOR PR STATUS
  if ($this->pr_data->status == -1) {?>
<div class="alert alert-warning" role="alert">
  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <strong><span class="glyphicon glyphicon-alert" aria-hidden="true"></span> WAITING FOR APPROVAL</strong>
</div>
<?php } elseif ($this->pr_data->status == 100) { ?>
<div class="alert alert-danger">
  <strong><span class="glyphicon glyphicon-remove" aria-hidden="true"></span> PO Already Closed!</strong>
</div>
<?php } elseif ($this->pr_data->status == -3) { ?>
<div class="alert alert-warning" role="alert">
  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <strong><span class="glyphicon glyphicon-alert" aria-hidden="true"></span> Feedback: </strong><?php echo $this->pr_data->feedback_note; ?>
</div>
<?php } ?>


<div class="collapse" id="editContact">
  <div class="well">
    <form method="post" action="<?php echo Config::get('URL') . 'po/updatePr/?po_number=' . urlencode($this->pr_data->transaction_number); ?>">
          <table class="table table-bordered table-hover table-striped ">
            <tr>
              <td><strong>Supplier Name:</strong></td>
              <td>
                <div class="input-group">
                    <input type="text" class="form-control search-query" placeholder="klik browse untuk memilih" name="supplier_id" id="contact_id_edit" value="<?php echo $this->pr_data->supplier_id;?>"/>
                  <span class="input-group-btn">
                  <a href="#" class="btn btn-purple btn-sm" onclick="editSupplier()">Browse</a>
                  <script type="text/javascript">
                  var popup;
                  function editSupplier() {
                  var left = (screen.width/2)-(500/2);
                  var top = (screen.height/2)-(500/2);
                  popup = window.open("<?php echo Config::get('URL') . 'contact/selectContact/?id=_edit'; ?>", "Popup", "width=500, height=500, top="+top+", left="+left);
                  popup.focus();
                  }
                  </script>
                  </span>
                </div>
              </td>
            </tr>

            <tr>
              <td><strong>Due Date</strong></td>
              <td><input type="text" name="due_date" class="form-control datepicker" value="<?php echo $this->pr_data->due_date;?>" data-date-format="yyyy-mm-dd"></td>
            </tr>
        <tr>
              <td><strong>Keterangan</strong></td>
              <td>
                <textarea name="note" class="form-control"><?php echo $this->pr_data->note;?></textarea>
              </td>
         </tr>
    </table>

  <div class="modal-footer">
    <a class="btn btn-sm btn-danger" data-toggle="collapse" href="#editContact" aria-expanded="false" aria-controls="collapseExample" class="pull-right">
        <span class="glyphicon glyphicon-remove" aria-hidden="true" aria-label="edit"></span>
        Cancel
    </a>
    <button class="btn btn-sm btn-primary">
      <span class="glyphicon glyphicon-floppy-disk" aria-hidden="true" aria-label="save"></span>
      Save
    </button>
  </div>
</form>
  </div>
</div>


<div class="collapse" id="approve-qc-manual">
  <form method="post" action="<?php echo Config::get('URL') . 'po/approveQC/?po_number=' . urlencode($this->pr_data->transaction_number); ?>">
  <div class="panel panel-success">
        <div class="panel-heading">
          <h3 class="panel-title">QC Approval Pembelian Barang Masuk</h3>
        </div>
      
<table class="table table-striped table-bordered table-hover ExcelTable2007">
<thead>
  <tr>
    <th rowspan="2"  class="center" style="width: 5%;">#</th>
    <th rowspan="2" class="center" style="width: 10%;">Kode</th>
    <th rowspan="2" class="center" style="width: 30%;">Nama</th>
    <th colspan="2" class="center" style="width: 15%;">Quantity</th>
    <th rowspan="2" class="center" style="width: 20%;">Keterangan</th>
  </tr>
  <tr>
    <th class="center">Incoming</th>
    <th class="center">Receive</th>
  </tr>
</thead>
<?php
      $i = 1;
      foreach($this->qc_waiting_approval as $key => $value) {
?>
<tr>
  <td><?php echo $i; ?></td>
  <td>
      <?php echo $value->material_code; ?>
      <input type="hidden" name="uid_<?php echo $i; ?>" value="<?php echo $value->uid; ?>">
      <input type="hidden" name="material_code_<?php echo $i; ?>" value="<?php echo $value->material_code; ?>">
  </td>
  <td>
      <?php echo $value->material_name; ?>
  </td>
  <td>
      <?php echo FormaterModel::decimalNumberUnderline($value->quantity, 3); ?>
  </td>
  <td>
      <input type="number" class="form-control text-right" name="qty_receive_<?php echo $i; ?>" placeholder="0.0" min="0" step="0.01">
  </td>
  <td>
      <input type="text" class="form-control" name="note_<?php echo $i; ?>">
      <input type="hidden" name="qty_incoming_<?php echo $i; ?>" value="<?php echo $value->quantity; ?>">
  </td>
</tr>
<?php $i++; } ?>
</table>
<input type="hidden" name="total_record" value="<?php echo ($i -1); ?>">
<div class="panel-footer align-right">
    <div class="btn-group btn-corner" role="group">
      <a class="btn btn-danger" role="button" data-toggle="collapse" href="#approve-qc-manual" aria-expanded="false" aria-controls="approve-qc-manual">
      Cancel
      </a>
      <button type="submit" id="save-button" class="btn btn-success">Approve</button>
    </div>
</div>
</div><!-- /.panel -->
</form>
</div><!-- /.collapse #approve-qc-manual -->

<?php $this->renderFeedbackMessages();?>

<div class="row">
    <div class="col-xs-12 col-sm-6">
      <div class="panel panel-primary">
        <div class="panel-heading">
          <h3 class="panel-title"><?php echo strtoupper($this->pr_data->transaction_number);?>
            <div class="btn-group btn-corner pull-right">
              <a role="button" data-toggle="collapse" href="#editContact" aria-expanded="false" aria-controls="collapseExample" class="btn btn-warning btn-minier">
              <i class="icon-pencil"></i>
                Edit
              </a>
              <a href="<?php echo Config::get('URL') . 'po/duplicatePO/?po_number=' . urlencode($this->pr_data->transaction_number); ?>"  onclick="return confirmation('Are you sure to duplicate this PO?');" class="btn btn-info btn-minier">
                <i class="icon-check"></i>
                Duplicate PO
              </a>
            </div>
          </h3>
        </div>
          <table class="table table-striped table-bordered table-hover">
          
            <tr>
              <td><strong>Supplier:</strong></td>
              <td colspan="3"><a href="<?php echo Config::get('URL') . 'contact/detail/' . $this->pr_data->supplier_id; ?>"><?php echo $this->pr_data->supplier_id . ' - ' . $this->pr_data->contact_name;?></a></td>
            </tr>
            <tr>
              <td><strong>Address:</strong></td>
              <td colspan="3"><?php echo $this->pr_data->address_street . ', ' . $this->pr_data->address_city . ', ' . $this->pr_data->address_state;?></td>
            </tr>
            <tr>
              <td><strong>Created Date:</strong></td>
              <td><?php echo date("d M, y", strtotime($this->pr_data->created_timestamp)); ?></td>
              <td><strong>Approved Date:</strong></td>
              <td><?php echo ($this->pr_data->approved_date !== '0000-00-00') ? date("d M, y", strtotime($this->pr_data->approved_date)) : ''; ?></td>
            </tr>
            <tr>
              <td><strong>User:</strong></td>
              <td><?php echo $this->pr_data->full_name;?></td>
              <td><strong>Due Date:</strong></td>
              <td><?php echo date("d M, y", strtotime($this->pr_data->due_date));?></td>
            </tr>
                          
          </table>
      </div>

    </div><!-- /.col-sm-6 -->
    <div class="col-xs-12 col-sm-6">
      <!-- #section:elements.tab -->
      <div class="tabbable">
        <ul class="nav nav-tabs" id="myTab">
          <li class="active">
            <a data-toggle="tab" href="#home">
              <i class="green ace-icon fa fa-comments bigger-120"></i>
              Notes
            </a>
          </li>

          <li>
            <a data-toggle="tab" href="#Logs">
              <i class="green ace-icon fa fa-undo bigger-120"></i>
              Logs
            </a>
          </li>
        </ul>

        <div class="tab-content">
          <div id="home" class="tab-pane box-case active" style="overflow-x: hidden; overflow-y: scroll; height:185px;">
            <ol reversed><?php echo $this->pr_data->note;?></ol>
          </div>

          <div id="Logs" class="tab-pane box-case" style="overflow-x: hidden; overflow-y: scroll; height:185px;">
            <ol reversed><?php echo $this->pr_data->log;?></ol>
          </div>
        </div>
      </div>
      <!-- /section:elements.tab -->

    </div>
</div><!-- ./row -->

<br>

<div class="row">
    <div class="col-xs-12 col-sm-12">
<div class="tabbable">
  <ul class="nav nav-tabs" id="myTab">

    <li class="active">
      <a data-toggle="tab" href="#daftar-barang">
        Daftar Barang
      </a>
    </li>

    <li>
      <a data-toggle="tab" href="#detail-barang">
        Detail Barang
      </a>
    </li>
    <li>
      <a data-toggle="tab" href="#shipment-payment">
        Pengiriman &amp; Pembayaran
      </a>
    </li>
    <li>
      <a data-toggle="tab" href="#barang-diterima">
        Barang Diterima
      </a>
    </li>
    <li>
      <a data-toggle="tab" href="#qc-log">
        QC Log
      </a>
    </li>
    <li>
      <a data-toggle="tab" href="#berita-acara">
        Berita Acara
      </a>
    </li>

    
    <li class="dropdown">
      <a data-toggle="dropdown" class="dropdown-toggle" href="#">
        <span class="glyphicon glyphicon-plus text-danger" aria-hidden="true" aria-label="Create New"></span>
      </a>

      <ul class="dropdown-menu dropdown-info dropdown-menu-right">
        <?php if ($this->pr_data->status <= -2) { ?>
        <li>
          <a href="#add-po-item" role="button" data-toggle="modal">
            Tambah Pembelian
          </a>
        </li>
        <li>
          <a href="#add-po-budget" role="button" data-toggle="modal">
            Tambah Pembelian (By Budget)
          </a>
        </li>
        <?php } ?>
        <li>
          <a href="#payment-shipment-modal" role="button" data-toggle="modal">
            Edit Pengiriman &amp; Pembayaran
          </a>
        </li>
        <li role="separator" class="divider"></li>
        <li class="dropdown-header">Berita Acara</li>
        <li role="separator" class="divider"></li>
        <li>
            <a data-toggle="tab" href="#upload-image">Upload Photo/Scan</a>
          </li>

          <li>
            <a data-toggle="tab" href="#upload-document">Upload Document</a>
          </li>
      </ul>
    </li>
    
  </ul>

  <div class="tab-content">
    <div id="daftar-barang" class="tab-pane box-case in active">
      <div class="table-responsive">
        <?php include("item_list.php"); //Call Contact Person ?>
      </div>
    </div>

    <div id="detail-barang" class="tab-pane box-case">
      <?php include("item_detail.php"); ?>
    </div>

    <div id="shipment-payment" class="tab-pane box-case">
      <div class="table-responsive">
        <?php include("shipment_detail.php"); //Call Contact Person ?>
      </div>
    </div>

    <div id="barang-diterima" class="tab-pane box-case">
      <div class="table-responsive">
        <?php include("barang_diterima.php"); //Call Contact Person ?>
      </div>
    </div>

    <div id="qc-log" class="tab-pane box-case">
      <div class="table-responsive">
        <?php include("qc_log.php"); //Call Contact Person ?>
      </div>
    </div>

    <div id="berita-acara" class="tab-pane box-case">
      <div class="table-responsive">
        <?php include("berita_acara.php"); //Call Contact Person ?>
      </div>
    </div>

    <div id="upload-image" class="tab-pane fade">
      <?php include("upload_image.php"); //Call Contact Person ?>
    </div>

    <div id="upload-document" class="tab-pane fade">
      <?php include("upload_document.php"); //Call Contact Person ?>
    </div>

  </div>
</div>
</div>
  </div><!-- ./row -->
  
<!-- PAGE CONTENT ENDS -->
              </div><!-- /.col -->
            </div><!-- /.row -->
          </div><!-- /.page-content -->
        </div><!-- /.main-content-inner -->
      </div><!-- /.main-content -->


<?php
if ($this->pr_data->status != 100) { // 100 is void, only open modal if not void
if ($this->pr_data->status <= -2) { ?>
<!--MODAL PR ITEMS By Material Code -->
<form method="POST" action="<?php echo Config::get('URL') . 'po/insertPrItem/?po_number=' . urlencode($this->pr_data->transaction_number);?>">
<div id="add-po-item" class="modal fade" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header no-padding">
        <div class="table-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
            <span class="white">&times;</span>
          </button>
          Tambah Daftar Pembelian Purchase Request
        </div>
      </div>

      <div class="modal-body no-padding">
        <table class="table table-striped table-bordered table-hover no-margin-bottom no-border-top">
                <tr>
                    <td>Kode dan Nama Barang</td>
                    
                    <td colspan="3">
                        <div class="input-group" style="width: 100%">
                            <input type="text" class="form-control" placeholder="klik tombol pilih untuk memilih kode material" name="material_code" id="material_code_new" required/>
                          <span class="input-group-btn">
                          <a href="#" class="btn btn-purple btn-sm " onclick="selectMaterial()">Pilih</a>
                          <script type="text/javascript">
                          var popup;
                          function selectMaterial() {
                          var left = (screen.width/2)-(500/2);
                          var top = (screen.height/2)-(500/2);
                          popup = window.open("<?php echo Config::get('URL') . 'inventory/selectMaterial/?id=_new'; ?>", "Popup", "width=500, height=500, top="+top+", left="+left);
                          popup.focus();
                          }
                          </script>
                          </span>
                        </div>
                    </td>
                </tr>
                <tr>
                  <td>Harga Barang:</td>
                    <td>
                        <input name="purchase_price" type="number" min="0" step="0.001"
                        placeholder="gunakan angka dan titik (.) untuk decimal" required
                        class="form-control text-right"
                         />
                    </td>
                    <td>Diskon (dalam uang):</td>
                    <td>
                        <input name="purchase_price_discount"  class="form-control text-right" 
                        type="number" min="0" step="0.01"
                        placeholder="gunakan angka saja"
                         />
                        <div id="price_discountwarning"></div>
                    </td>
                </tr>

                <tr>
                    <td>Currency:</td>
                    <td>
                         <select name="currency" class="form-control">
                                <option value="IDR">IDR</option>
                                <option value="USD">USD</option>
                                <option value="EUR">EUR</option>
                                <option value="CHF">CHF</option>
                        </select>
                    </td>
                    <td>Jumlah:</td>
                    <td><input type="number" name="quantity" min="0" step="0.01" class="form-control text-right" required></td>
                    
                </tr>
                <tr>
                    <td>Satuan:</td>
                    <td><input type="text" name="unit" class="form-control" required></td>
                    <td>Pajak (%):</td><td><input type="number" name="purchase_tax" placeholder="masukan angka, misal: 10" class="form-control text-right"/>
                    </td>
                </tr>
                <tr>
                    <td>Packaging:</td>
                     <td><input type="text" name="packaging" class="form-control"></td>
                    <td>Spesifikasi:</td>
                    <td>
                      <textarea name="material_specification" class="form-control"></textarea>
                    </td>
                </tr>
                <tr>
                    <td>Alasan Pembelian:</td><td colspan="3"><textarea name="reason_request" class="form-control"></textarea></td>
                </tr>
              </table>   
        </div>

      <div class="modal-footer no-margin-top">
        <button class="btn btn-sm btn-danger pull-left" data-dismiss="modal">
          <i class="ace-icon fa fa-times"></i>
          Close
        </button>

        <input type="submit" class="btn btn-primary" value="Save">
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div>
</form><!--/MODAL PR ITEMS By Material Code -->

<!-- MODAL PR ITEMS By Kategori Budged -->
<form method="POST" action="<?php echo Config::get('URL') . 'po/insertPrItem/?po_number=' . urlencode($this->pr_data->transaction_number);?>">
<div id="add-po-budget" class="modal fade" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header no-padding">
        <div class="table-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
            <span class="white">&times;</span>
          </button>
          Tambah Daftar Pembelian Purchase Request (By Budget)
        </div>
      </div>

      <div class="modal-body no-padding">
        <table class="table table-striped table-bordered table-hover no-margin-bottom no-border-top">
                <tr>
                    <td>Budget</td>
                    <td colspan="3">
                      <select name="budget_category" class="form-control">
                          <option value="">Pilih Budged</option>
                          <?php
                            foreach ($this->budget_category as $key => $value) {
                              echo '<option value="' . $value->item_name . '">' . $value->item_name . '</option>';
                            }
                          ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Nama Pembelian:</td>
                    <td colspan="3">
                      <textarea name="budget_item" class="form-control" placeholder="Item "></textarea>
                    </td>
                </tr>
                <tr>
                  <td>Harga Barang:</td>
                    <td>
                        <input name="purchase_price" type="number" min="0" step="0.001"
                        placeholder="gunakan angka dan titik (.) untuk decimal" required
                        class="form-control text-right"
                         />
                    </td>
                    <td>Diskon (dalam uang):</td>
                    <td>
                        <input name="purchase_price_discount"  class="form-control text-right" 
                        type="number" min="0" step="0.01"
                        placeholder="gunakan angka saja"
                         />
                        <div id="price_discountwarning"></div>
                    </td>
                </tr>

                <tr>
                    <td>Currency:</td>
                    <td>
                         <select name="currency" class="form-control">
                                <option value="IDR">IDR</option>
                                <option value="USD">USD</option>
                                <option value="EUR">EUR</option>
                                <option value="CHF">CHF</option>
                        </select>
                    </td>
                    <td>Jumlah:</td>
                    <td><input type="number" name="quantity" min="0" step="0.01" class="form-control text-right" required></td>
                    
                </tr>
                <tr>
                    <td>Satuan:</td>
                    <td><input type="text" name="unit" class="form-control" required></td>
                    <td>Pajak (%):</td><td><input type="number" name="purchase_tax" placeholder="masukan angka, misal: 10" class="form-control text-right"/>
                    </td>
                </tr>
                <tr>
                    <td>Packaging:</td>
                     <td><input type="text" name="packaging" class="form-control"></td>
                    <td>Spesifikasi:</td>
                    <td>
                      <textarea name="material_specification" class="form-control"></textarea>
                    </td>
                </tr>
                <tr>
                    <td>Alasan Pembelian:</td><td colspan="3"><textarea name="reason_request" class="form-control"></textarea></td>
                </tr>
              </table>   
        </div>

      <div class="modal-footer no-margin-top">
        <button class="btn btn-sm btn-danger pull-left" data-dismiss="modal">
          <i class="ace-icon fa fa-times"></i>
          Close
        </button>

        <input type="submit" class="btn btn-primary" value="Save">
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div>
</form><!--/MODAL PR ITEMS By Kategori Budged -->
<?php } ?>

<!-- Payment and shipment -->
<form method="POST" action="<?php echo Config::get('URL') . 'po/makeShipment/?po_number=' . urlencode($this->pr_data->transaction_number);?>">
<div id="payment-shipment-modal" class="modal fade" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header no-padding">
        <div class="table-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
            <span class="white">&times;</span>
          </button>
          Edit Biaya Pengiriman
        </div>
      </div>

<?php
$payment_term_shipment = $this->shipment_data->payment_term;
$freight_price_shipment = $this->shipment_data->purchase_price;
$supplier_code_shipment = $this->shipment_data->supplier_code;
$freight_term_shipment = $this->shipment_data->freight_term;
$freight_payment_shipment = $this->shipment_data->freight_payment;
$ship_via_shipment = $this->shipment_data->ship_via;
$delivery_time = $this->shipment_data->delivery_time;

?>
      <div class="modal-body no-padding">
        <table class="table table-striped table-bordered table-hover no-margin-bottom no-border-top">
          <tbody>
            <tr class="success">
              <td colspan="4" class="text-center">
                Cara Pembayaran
              </td>
            </tr>
            <tr>
                <td>Payment Term:</td>
                <td>
                    <select name="payment_term" id="payment_term" onchange='CheckColors(this.value);'>
                      <option value="">Pilih</option>
                      <?php
                      if (!empty($payment_term_shipment)) {
                        echo '<option value="' . $payment_term_shipment . '" selected>' . $payment_term_shipment . '</option>';
                      } else {
                        echo '<option value="">Pilih</option>';
                      }
                      foreach ($this->payment_term as $key_payment_term => $value_payment_term) {
                       
                          echo '<option value="' . $value_payment_term->item_name . '">' . $value_payment_term->item_name . '</option>';
                      } ?>
                    </select>
<script type="text/javascript">
  function CheckColors(val){
   var element=document.getElementById('other_payment_term');
   if(val=='pick a color'|| val=='Others') {
      element.style.display='block';
      document.getElementById('payment_term').name = '';
      element.name = 'payment_term';
    } else  {
     element.style.display='none';
    }
  }
</script> 


                </td>
                <td colspan="2">
                  <input type="text" name="other_payment_term" id="other_payment_term" style='display:none;'/>
                </td>
            </tr>

            <tr class="success">
                <td colspan="4" class="text-center">Cara Pengiriman</td>
            </tr>

            <tr>
                <td>Biaya Pengiriman:</td>
                <td><input type="text" name="purchase_price" placeholder="masukan angka, misal: 10" class="form-control text-right" value="<?php echo (int)$freight_price_shipment; ?>" /></td>
                <td>Freight Vendor</td>
                <td>
                    <div class="input-group" style="width: 100%">
                        <input type="text" class="form-control" placeholder="klik browse untuk memilih vendor" name="supplier_code" id="contact_id_vendor" value="<?php echo $supplier_code_shipment; ?>"/>
                      <span class="input-group-btn">
                      <a href="#" class="btn btn-purple btn-sm" onclick="SelectNameEdit()">Pilih</a>
                      <script type="text/javascript">
                      var popup;
                      function SelectNameEdit() {
                      var left = (screen.width/2)-(500/2);
                      var top = (screen.height/2)-(500/2);
                      popup = window.open("<?php echo Config::get('URL') . 'contact/selectContact/?id=_vendor'; ?>", "Popup", "width=500, height=500, top="+top+", left="+left);
                      popup.focus();
                      }
                      </script>
                      </span>
                    </div>
                </td>
            </tr>

            <tr>
                <td>Freight Term:</td>
                <td>
                    <select name="freight_term" class="form-control">
                      <option value="">Pilih</option>
                      <?php foreach ($this->freight_term as $key_freight_term => $value_freight_term) {
                        if ($freight_term_shipment == $value_freight_term->item_name ) {
                          echo '<option value="' . $value_freight_term->item_name . '" selected >' . $value_freight_term->item_name . '</option>';
                        } else {
                          echo '<option value="' . $value_freight_term->item_name . '">' . $value_freight_term->item_name . '</option>';
                        }
                      } ?>
                    </select>
                </td>
                <td>Freight Payment:</td>
                <td>
                    <select name="freight_payment">
                        <option value="">Please Select</option>
                        <option value="COLLECT" <?php if ($freight_payment_shipment == "COLLECT") {echo 'selected';} ?> >COLLECT</option>
                        <option value="PREPAID" <?php if ($freight_payment_shipment == "PREPAID") {echo 'selected';} ?>>PREPAID</option>
                    </select>
                </td>
            </tr>

            <tr>
              <td>Delivery Time:</td>
              <td colspan="3">
                  <input type="text" name="delivery_time" class="form-control" value="<?php echo $delivery_time; ?>">
              </td>
            </tr>
            
            <tr>
                <td>Delivery Point:</td>
                <td colspan="3"><input type="text" name="ship_via" class="form-control" value="<?php 
                if (empty($ship_via_shipment)) { echo 'PT. Maxima Daya Indonesia Jl. Raya Trawas KM 3,8 Pungging - Mojokerto';} else {echo $ship_via_shipment;} ?>"></td>
            </tr>

            
          </div>
          </tbody>
        </table>
      </div>

      <div class="modal-footer no-margin-top">
        <button class="btn btn-sm btn-danger pull-left" data-dismiss="modal">
          <i class="ace-icon fa fa-times"></i>
          Close
        </button>
        <?php
          if (!empty($this->shipment_data->purchase_price)) {
              echo '<input type="submit" name="submit" class="btn btn-primary" value="Update">';
          } else {
              echo '<input type="submit" name="submit" class="btn btn-primary" value="Masukkan Pengiriman">';
          }

        ?>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div>
</form><!--/ Payment and shipment -->
<?php } ?>

<?php if ($this->pr_data->status == -1 AND Auth::isPermissioned('director') AND $user_authenticated) { ?>
<!-- MODAL Feedback PR -->
<div class="modal fade" id="feedback-pr" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
    <form method="post" class="form-horizontal" action="<?php echo Config::get('URL') . 'po/giveFeedbackPr/?po_number=' . urlencode($this->pr_data->transaction_number);?>">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Buat Feedback Untuk Purchase Request</h4>
      </div>
      <div class="modal-body">
          <textarea name="feedback_note" class="form-control"></textarea>
      </div>
      <div class="modal-footer no-margin-top">
        <button class="btn btn-sm btn-danger pull-left" data-dismiss="modal">
          <i class="ace-icon fa fa-times"></i>
          Close
        </button>
        <input type="submit" class="btn btn-sm btn-primary" value="Send Feedback">
      </div>
      </form>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- /MODAL Feedback PR -->
<?php } elseif ($this->pr_data->status == 20 AND Auth::isPermissioned('director') AND $user_authenticated) { ?>
<!-- MODAL Feedback for Ask Edit PO Apptoval-->
<div class="modal fade" id="feedback-ask-edit-po" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
    <form method="post" class="form-horizontal" action="<?php echo Config::get('URL') . 'po/giveFeedbackAskEditPO/?po_number=' . urlencode($this->pr_data->transaction_number);?>">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Buat Feedback Untuk Permintaan Akses Merubah Harga Pembelian</h4>
      </div>
      <div class="modal-body">
          <textarea name="feedback_note" class="form-control"></textarea>
      </div>
      <div class="modal-footer no-margin-top">
        <button class="btn btn-sm btn-danger pull-left" data-dismiss="modal">
          <i class="ace-icon fa fa-times"></i>
          Close
        </button>
        <input type="submit" class="btn btn-sm btn-primary" value="Send Feedback">
      </div>
      </form>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- /MODAL Feedback for Ask Edit PO Apptoval-->

<?php } ?>

<?php if ($this->pr_data->status >= 0) { ?>
<!-- MODAL Receive Partial -->
<div class="modal fade" id="receive-material" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
    <form method="post" class="form-horizontal" action="<?php echo Config::get('URL') . 'po/receiveMaterial/?po_number=' . urlencode($this->pr_data->transaction_number);?>">
      <div class="modal-header no-padding">
        <div class="table-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
            <span class="white">×</span>
          </button>
          Receive Purchase Order
        </div>
      </div>

      <div class="modal-body no-padding">
        <table class="table table-striped table-bordered table-hover no-margin-bottom no-border-top">
                       <thead> 
                            <tr>
                                <th rowspan="2">#</th>
                                <th rowspan="2">Kode</th>
                                <th rowspan="2">Nama</th>
                                <th colspan="2" class="text-center">Qty Pembelian</th>
                                <th colspan="3" class="text-center">Qty Diterima</th>
                                <th rowspan="2">Tgl Masuk</th>
                            </tr>
                            <tr>
                                <th>Qty</th>
                                <th>Satuan</th>
                                <th>Sebelumnya</th>
                                <th>Sekarang</th>                                
                                <th>Satuan</th>
                            </tr>
                        </thead>  
                        
                        <tbody>
                          <?php
                          $num = 1;
                          $counter = 1;
                          foreach($this->pr_item_list as $key => $value) {
                          // check jika pr bukan biaya pengiriman
                            if ($value->material_code != 'shipment_method') {
                                if ($value->quantity_received != $value->quantity) {
                                echo "<tr>";
                                echo '<td>' . $num . '</td>';
                                echo '<td>' . $value->material_code . '</td>';
                                echo '<td>' . $value->material_name . '</td>';
                                echo '<td>' . FormaterModel::decimalNumberUnderline($value->quantity, 2) . '</td>';
                                echo '<td>' . $value->unit . '</td>';
                                echo '<td>' . FormaterModel::decimalNumberUnderline($value->quantity_received, 2) . '</td>';
                                echo '<td>
                                      <input type="number" class="form-control text-right" name="quantity_received_'.$counter.'" placeholder="0.0" min="0" step="0.01">
                                      <input type="hidden" name="quantity_'.$counter.'" value="' . $value->quantity . '">
                                      <input type="hidden" name="total_quantity_received_'.$counter.'" value="' . $value->quantity_received . '">
                                      <input type="hidden" name="uid_'.$counter.'" value="' . $value->uid . '">
                                      <input type="hidden" name="material_code_'.$counter.'" value="' . $value->material_code . '">
                                      </td>';
                                echo '<td>' . $value->unit_master . '</td>';
                                echo '<td>
                                          <input class="datepicker form-control" name="incoming_date_'.$counter.'" data-date-format="yyyy-mm-dd" type="text" style="min-width: 100px;">
                                      </td>';
                                echo "</tr>";
                                $counter++;
                                }
                                
                                $num++;
                                
                              }
                          }
                          ?>
                          </tbody>
            </table>
            <input type="hidden" name="total_record" value="<?php echo ($counter -1); ?>">
      </div>
      <div class="modal-footer no-margin-top">
        <button class="btn btn-sm btn-danger pull-left" data-dismiss="modal">
          <i class="ace-icon fa fa-times"></i>
          Close
        </button>
        <input type="submit" class="btn btn-sm btn-primary" value="receive">
      </div>
      </form>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- /MODAL Receive Partial -->

<!-- MODAL Manage Payment -->
<div class="modal fade" id="manage-payment" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
    <form method="post" class="form-horizontal" action="<?php echo Config::get('URL') . 'po/insertPayment/?po_number=' . urlencode($this->pr_data->transaction_number);?>">
      <div class="modal-header no-padding">
        <div class="table-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
            <span class="white">×</span>
          </button>
          Buat Rencana Pembayaran
        </div>
      </div>

      <div class="modal-body no-padding">
        <table class="table table-striped table-bordered table-hover no-margin-bottom no-border-top">
                       <thead>
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
                        </thead>
                        <tbody>
                        <?php
            for ($i=1; $i < 10; $i++) { ?>
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
                    
                <tr>
                <?php  } ?>
                    
                <tbody>
            </table>
            <input type="hidden" name="total_order" value="<?php echo $total_price_plus_shipment; ?>">
      </div>
      <div class="modal-footer no-margin-top">
        <button class="btn btn-sm btn-danger" data-dismiss="modal">
          <i class="ace-icon fa fa-times"></i>
          Cancel
        </button>
        <input type="submit" class="btn btn-sm btn-primary" value="Submit">

      </div>
      </form>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- /MODAL Manage Payment -->
<?php } // 100 is close/void only open modal if not void ?>

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
