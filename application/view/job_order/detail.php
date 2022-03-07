<div class="main-content">
  <div class="main-content-inner">
        <!-- #section:basics/content.breadcrumbs -->
        <div class="breadcrumbs" id="breadcrumbs">
          <script type="text/javascript">
            try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
          </script>

          <ul class="breadcrumb" >
            <?php if ($this->production_detail->status < 100) { // not closed ?>  
            <li>
              <div class="btn-group btn-corner">
              <a role="button" href="<?php echo  Config::get('URL') . 'JobOrder/deleteProduction/?job_number=' . urlencode($this->production_detail->job_number) . '&forward=JobOrder/onProcess'; ?> " onclick="return confirmation('Are you sure to delete?');" class="btn btn-minier btn-danger"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span> Delete</a>
              <a role="button" href="<?php echo  Config::get('URL') . 'JobOrder/closeProduction/?job_number=' . urlencode($this->production_detail->job_number) . '&forward=' . $_SERVER['REQUEST_URI']; ?> " onclick="return confirmation('Are you sure to close Job Order?');" class="btn btn-minier btn-inverse"><span class="glyphicon glyphicon-lock" aria-hidden="true"></span> Close</a>
              </div>
            </li>
                 
            <li>
              <div class="btn-group btn-corner">
                  <a href="#input-production-result" role="button" data-toggle="collapse" class="btn btn-minier btn-info">
                    <span class="menu-icon fa fa-check" aria-hidden="true" aria-label="edit"></span> Input Production Result
                  </a>

                  <?php if (Auth::isPermissioned('director,management,qc')) { // QC Approval ?>
                  <a role="button" data-toggle="collapse" href="#approve-qc-manual" aria-expanded="false" aria-controls="approve-qc-manual" class="btn btn-minier btn-success"><span class="glyphicon glyphicon-check"></span> QC Approval</a>
                  <?php } ?>
              </div>
            </li>

          <?php } else {//notclosed  ?>
            <li>
                <a role="button" href="<?php echo  Config::get('URL') . 'JobOrder/openProduction/?job_number=' . urlencode($this->production_detail->job_number) . '&forward=' . $_SERVER['REQUEST_URI']; ?> " onclick="return confirmation('Are you sure to open Job Order?');" class="btn btn-minier btn-info">
                <span class="glyphicon glyphicon-check"></span> Open JO</a>
            </li>
          <?php } //closed?>

          </ul><!-- /.breadcrumb -->
        </div>

        <!-- /section:basics/content.breadcrumbs -->
        <div class="page-content">
    <div class="row">
      <div class="col-xs-12">
        <!-- PAGE CONTENT BEGINS -->

<?php $this->renderFeedbackMessages();?>

<div class="collapse" id="approve-qc-manual">
  <form method="post" action="<?php echo Config::get('URL') . 'JobOrder/qcApproval/?job_number=' . urlencode($this->production_detail->job_number) . '&so_number=' . urlencode($this->production_detail->job_reverence);?>">
  <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title">Approve Hasil Produksi Job Order</h3>
        </div>
      
<table class="table table-striped table-bordered table-hover ExcelTable2007">
<thead>
  <tr>
    <th rowspan="2"  class="center" style="width: 5%;">#</th>
    <th rowspan="2" class="center" style="width: 20%;">Kode</th>
    <th rowspan="2" class="center" style="width: 20%;">Nama</th>
    <th colspan="2" class="center" style="width: 15%;">Quantity</th>
    <th rowspan="2" class="center" style="width: 20%;">Serial Number</th>
    <th rowspan="2" class="center" style="width: 20%;">Keterangan</th>
  </tr>
  <tr>
    <th class="center">Made</th>
    <th class="center">Receive</th>
  </tr>
</thead>
<?php
      $i = 1;
      foreach($this->result_list as $key => $value) {
        if ($value->status == 'waiting_qc_approval') {
          
        ?>
<tr>
  <td><?php echo $i; ?></td>
  <td>
      <?php echo $value->material_code; ?>
      <input type="hidden" class="form-control" name="product_code_<?php echo $i; ?>" value="<?php echo $value->material_code; ?>">
      <input type="hidden" class="form-control" name="uid_<?php echo $i; ?>" value="<?php echo $value->uid; ?>">
  </td>
  <td>
      <?php echo $value->material_name; ?>
  </td>
  <td>
      <?php echo $value->quantity; ?>
  </td>
  <td>
      <input type="number" class="form-control" name="qty_approved_<?php echo $i; ?>">
      <input type="hidden" name="qty_production_<?php echo $i; ?>" value="<?php echo $value->quantity; ?>">
  </td>
  <td>
    <?php
      $serialNumber = explode(',', $value->serial_number);
      $serial = 0;
      foreach ($serialNumber as $serial_key => $serial_value) {
        $sn = trim($serialNumber[$serial_key]);
        if (!empty($sn)) { //make sure not to echo empty SN
              echo '<div class="checkbox"><label>
              <input name="serial_number_' . $i . '[' . $serial . ']' . '" type="checkbox" value="' . $serialNumber[$serial_key] . '" class="ace">
            <span class="lbl">' . $serialNumber[$serial_key] .'</span></label>
            </div>';
        }
        $serial++;
      }
  ?>
</td>
<td>
    <textarea class="form-control" name="note_<?php echo $i; ?>"></textarea>
  </td>
</tr>
<?php $i++; }} ?>
</table>
<input type="hidden" name="total_record" value="<?php echo ($i -1); ?>">
<div class="panel-footer">
    <a class="btn btn-danger" role="button" data-toggle="collapse" href="#approve-qc-manual" aria-expanded="false" aria-controls="approve-qc-manual">
    Cancel
    </a>
  <button type="submit" id="save-button" class="btn btn-primary">Save</button>
        </div>
</div><!-- /.panel -->
</form>
</div><!-- /.collapse #approve-qc-manual -->


<div class="collapse" id="input-production-result">
  <form method="post" action="<?php echo Config::get('URL') . 'JobOrder/inputProductionResult/?job_number=' . urlencode($this->production_detail->job_number);?>">
  <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title">Input Manual Hasil Produksi</h3>
        </div>
      
<table class="table table-striped table-bordered table-hover ExcelTable2007">
<tr>
  <th style="width: 5%;">#</th>
  <th style="width: 15%;">Kode</th>
  <th style="width: 30%;">Nama Produk</th>
  <th style="width: 10%;">Jumlah</th>
  <th style="width: 40%;">Serial Number</th>
</tr>
<?php
      $i = 1;
      foreach($this->product_list as $key => $value) {  ?>
<tr>
  <td><?php echo $i; ?></td>
  <td>
      <?php echo $value->material_code; ?>
      <input type="hidden" class="form-control" name="material_code_<?php echo $i; ?>" value="<?php echo $value->material_code; ?>">
  </td>
  <td>
      <?php echo $value->material_name; ?>
  </td>
  <td>
      <input type="number" class="form-control" name="quantity_<?php echo $i; ?>">
      <input type="hidden" name="quantity_jo_<?php echo $i; ?>" value="<?php echo $value->quantity; ?>">
  </td>
  <td>
      <textarea name="serial_number_<?php echo $i; ?>" placeholder="pisahkan serial number dengan tanda koma"></textarea>
  </td>
</tr>
<?php $i++; } ?>
</table>
<input type="hidden" name="total_record" value="<?php echo ($i -1); ?>">
<?php (stripos($this->production_detail->contact_name, 'pln ') !== false) ? $customer_type = 'P' : $customer_type = 'S';?>
<input type="hidden" name="customer_type" value="<?php echo $customer_type; ?>">
<div class="panel-footer">
    <a class="btn btn-danger" role="button" data-toggle="collapse" href="#input-production-result" aria-expanded="false" aria-controls="input-production-result">
    Cancel
    </a>
  <button type="submit" id="save-button" class="btn btn-primary">Save</button>
        </div>
</div><!-- /.panel -->
</form>
</div><!-- /.collapse #input-production-result -->

<?php
//render feedback from BOD
if (!empty($this->production_detail->feedback_note)) {
  echo '<div class="alert alert-warning" role="alert"><span class="glyphicon glyphicon-comment bigger-120" aria-hidden="true"></span> '.$this->production_detail->feedback_note.'</div>';
}

$this->renderFeedbackMessages();
?>

<div class="row">
    <div class="col-xs-12 col-sm-4">
      <div class="panel panel-danger">
        <div class="panel-heading">
          <h3 class="panel-title">Job Order</h3>
        </div>
          <table class="table table-striped table-bordered table-hover">
          <tr>
              <td><strong>#JO:</strong></td>
              <td><?php echo strtoupper($this->production_detail->job_number);?></td>
            </tr>
            <tr>
              <td><strong>Tgl Dibuat:</strong></td>
              <td><?php echo date('d-M, Y', strtotime($this->production_detail->created_timestamp)) ;?></td>
            </tr>
            <tr>
              <td><strong>Tgl Diminta:</strong></td>
              <td><?php echo date('d-M, Y', strtotime($this->production_detail->delivery_request)) ;?></td>
            </tr>
            <tr>
              <td><strong>Job Type:</strong></td>
              <td><?php echo $this->production_detail->job_type;?></td>
            </tr>
            <tr>
              <td colspan="2">
                <strong>Note:</strong><br>
                <?php echo $this->production_detail->note;?>
              </td>
            </tr>
          </table>
      </div>   
    </div><!-- /.col-sm-6 -->
    <div class="col-xs-12 col-sm-8">
      <div class="panel panel-info">
        <div class="panel-heading">
          <h3 class="panel-title">Job Order Reverence</h3>
        </div>
          <table class="table table-striped table-bordered table-hover">
            <tr>
              <td><strong>#SO:</strong></td>
              <td><?php echo '<a href="' .  Config::get('URL') . 'so/detail/?so_number=' . urlencode($this->production_detail->job_reverence) . '">' . $this->production_detail->job_reverence . '</a>';?></td>

              <td><strong>Customer:</strong></td>
              <td><?php echo strtoupper($this->production_detail->contact_name);?></td>
            </tr>
          
            <tr>
              <td><strong>Alamat:</strong></td>
              <td colspan="3"><?php echo $this->production_detail->address_street;?></td>
            </tr>
            <tr>
              <td><strong>Kota:</strong></td>
              <td><?php echo $this->production_detail->address_city;?></td>

              <td><strong>Propinsi:</strong></td>
              <td><?php echo $this->production_detail->address_state;?></td>
            </tr>
            <tr>
              <td><strong>Zip:</strong></td>
              <td><?php echo $this->production_detail->address_zip;?></td>

              <td><strong>Email:</strong></td>
              <td><?php echo $this->production_detail->email;?></td>
            </tr>
            <tr>
              <td><strong>Phone:</strong></td>
              <td><?php echo $this->production_detail->phone;?></td>

              <td><strong>Fax:</strong></td>
              <td><?php echo $this->production_detail->fax;?></td>
            </tr>
            <tr>
              <td><strong>Website:</strong></td>
              <td colspan="3"><a href="http://<?php echo $this->contact->website;?>/" target="_blank"><?php echo $this->production_detail->website;?></a></td>
            </tr>
          </table>
      </div>
    </div>
  </div><!-- ./row -->

      <div class="panel panel-primary">
        <div class="panel-heading">
          <h3 class="panel-title">Sales Order</h3>
        </div>
            <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover ExcelTable2007">
              <thead>
                <tr>
                  <th rowspan="2"  class="center">#</th>
                  <th rowspan="2" class="center">Kode</th>
                  <th rowspan="2" class="center">Nama</th>
                  <th colspan="2" class="center">Quantity</th>
                  <th rowspan="2" class="center">Serial Number</th>
                </tr>
                <tr>
                  <th class="center">Order</th>
                  <th class="center">Finished</th>
                </tr>
              </thead>
            <tbody>
            <?php
            //cari serial number yang sudah selesai
            $finished_serial_number = [];
            foreach($this->result_list as $key => $value) {
                $finished_serial_number[$value->material_code][] = $value->serial_number_received;
            }

            $no = 1;
            $total_made = 0;
            $total_approved = 0;
            $total_reject = 0;
            foreach($this->product_list as $key => $value) {
      
                    echo '<tr id="row_' . $no . '">';
                    echo '<td class="text-right">' . $no . '</td>';
                    echo '<td><a href="' . Config::get('URL') . 'inventory/editMaterial/?find=' . $value->material_code . '">' . $value->material_code . '</a></td>';
                    echo '<td>' . $value->material_name . '</td>';
                    echo '<td class="text-right">' . floatval($value->quantity) . '</td>';
                    echo '<td class="text-right">' . floatval($value->quantity_received) . '</td>';
                    echo '<td class="text-right">';
                    $serial_number = $finished_serial_number[$value->material_code];
                    $serial_number = implode(',', $serial_number);
                    $serial_number = explode(',', $serial_number);
                    $serial_number = array_filter($serial_number);
                    $new_sn = [];
                    foreach ($serial_number as $key => $value) {
                      $new_sn[] = trim($value);
                    }
                    sort($new_sn);
                    foreach ($new_sn as $sn) {
                      echo '<a href="' .  Config::get('URL') . 'serialNumber/detail/' .urlencode($sn) . '">' . $sn . '</a>, ';
                    }
                    echo '</td>';
                    echo "</tr>";
                
                $no++;
                $total_made = $total_made + $value->quantity;
                $total_approved = $total_approved + $value->quantity_received;

            }
            ?>
                <tr>
                  <td  class="center" colspan="3">Total</td>
                  <td  class="text-right"><?php echo floatval($total_made);?></td>
                  <td  class="text-right"><?php echo floatval($total_approved);?></td>
                  <td  class="center" colspan="2"></td>
                </tr>
              </tbody>
            </table>
            </div>
          </div>
<div class="tabbable">
  <ul class="nav nav-tabs" id="myTab">
    <li class="active">
      <a data-toggle="tab" href="#material-list">
        <i class="green ace-icon glyphicon glyphicon-cutlery bigger-120"></i>
        Material List
      </a>
    </li>
    <li>
      <a data-toggle="tab" href="#consumed_material_list">
        <i class="green ace-icon glyphicon glyphicon-filter bigger-120"></i>
        Material Consumption
      </a>
    </li>
    <li>
      <a data-toggle="tab" href="#result_list">
        <i class="green ace-icon glyphicon glyphicon-gift bigger-120"></i>
        Finished Product
      </a>
    </li>
  </ul>

  <div class="tab-content">
      <div id="material-list" class="tab-pane fade in active">
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover ExcelTable2007">
              <thead>
                <tr class="warning">
                  <td colspan="10">
                    <a href="<?php echo Config::get('URL') . 'pr/createPrFromJo/?transaction_reference=' . urlencode($this->production_detail->job_number); ?>">
                      <span class="badge badge-inverse">
                      <i class="fa fa-shopping-basket"></i> Create PR
                      </span>
                    </a>
                  </td>
                </tr>
                <tr>
                  <th  class="center" rowspan="2">#</th>
                  <th  class="center" rowspan="2">Kode</th>
                  <th  class="center" rowspan="2">Nama</th>
                  <th  class="center" rowspan="2">Job Type</th>
                  <th  class="center" colspan="3">Pembelian</th>
                  <th  class="center" colspan="2">Forcast Produksi</th>
                  <th  class="center" colspan="2">Stock</th>
                </tr>
                <tr>
                  <th  class="center">Harga</th>
                  <th  class="center">Satuan</th>
                  <th  class="center">Cur</th>
                  <th  class="center">Qty</th>
                  <th  class="center">Harga</th>
                  <th  class="center">Qty</th>
                  <th  class="center">Satuan</th>
                </tr>
              </thead>
            <tbody>
            <?php
            $no = 1;
            $total_biaya_produksi = 0;
            foreach($this->material_list as $key => $value) {
      
                    echo '<tr id="row_' . $no . '">';
                    echo '<td class="text-right">' . $no . '</td>';
                    echo '<td><a href="' . Config::get('URL') . 'inventory/editMaterial/?find=' . $value->material_code . '">' . $value->material_code . '</a></td>';
                    echo '<td>' . $value->material_name . '</td>';
                    echo '<td>' . $value->job_type . '</td>';
                    echo '<td class="text-right">' . number_format($value->purchase_price, 2) . '</td>';
                    echo '<td>' . $value->purchase_unit . '</td>';
                    echo '<td>' . $value->purchase_currency . '</td>';
                    echo '<td class="text-right">' . floatval($value->quantity) . '</td>';
                    echo '<td class="text-right">' . number_format($value->production_price, 2) . '</td>';
                    echo '<td class="text-right">' . floatval($value->quantity_stock) . '</td>';
                    echo '<td>' . $value->stock_unit . '</td>';
                    echo "</tr>";
                
                $no++;
                $total_biaya_produksi = $total_biaya_produksi + $value->production_price;
            }
            ?>
              <tr class="warning">
                  <td colspan="8" class="text-right">
                    Forcasting Biaya Produksi:
                  </td>
                  <td colspan="3">
                    <?php echo number_format($total_biaya_produksi, 2); ?>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
      </div>

      <div id="consumed_material_list" class="tab-pane fade in">
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover ExcelTable2007">
              <thead>
                <tr class="warning">
                  <td colspan="12">
                    <a href="#" onclick="printPage('<?php echo Config::get('URL') . 'JobOrder/detail/?job_number=' . urlencode($this->production_detail->job_number); ?>&print=1&printConsumedMaterial=1')">
                      <span class="badge badge-info">
                      <i class="glyphicon glyphicon-print"></i> Print
                      </span>
                    </a>

                    <div class="btn-group btn-corner">
                        <a href="<?php echo Config::get('URL') . 'jobOrder/insertMaterialConsumptionByBOM/?job_number=' . urlencode($this->production_detail->job_number); ?>" class="btn btn-minier btn-danger">
                          <span class="menu-icon fa fa-check" aria-hidden="true" aria-label="edit"></span> Input Consumed Material (By BOM)
                        </a>

                        <a href="<?php echo Config::get('URL') . 'jobOrder/insertMaterialConsumptionManual/?job_number=' . urlencode($this->production_detail->job_number); ?>" class="btn btn-minier btn-warning">
                          <span class="menu-icon fa fa-check" aria-hidden="true" aria-label="edit"></span> Input Consumed Material (Manual)
                        </a>
                    </div>
                  </td>
                </tr>
                <tr>
                  <th  class="center">#</th>
                  <th  class="center">Tanggal</th>
                  <th  class="center">Kode</th>
                  <th  class="center">Nama</th>
                  <th  class="center">Qty</th>
                  <th  class="center">Satuan</th>
                  <th  class="center">#Lot Reference</th>
                  <?php if (Auth::isPermissioned('director', 900)) { ?>
                  <th  class="center">Harga Pembelian</th>
                  <th  class="center">Satuan Pembelian</th>
                  <th  class="center">Harga Produksi</th>
                  <?php } ?>
                  <th  class="center">Keterangan</th>
                  <th  class="center">Delete</th>
                </tr>
              </thead>
            <tbody>
            <?php
            $no = 1;
            $total_biaya_produksi = 0;
            foreach($this->consumed_material_list as $key => $value) {
      
                    echo '<tr id="row_' . $no . '">';
                    echo '<td class="text-right">' . $no . '</td>';
                    echo '<td>' . date('d-m, Y', strtotime($value->created_timestamp)) . '</td>';
                    echo '<td><a href="' . Config::get('URL') . 'inventory/editMaterial/?find=' . $value->material_code . '">' . $value->material_code . '</a></td>';
                    echo '<td>' . $value->material_name . '</td>';
                    echo '<td class="text-right">' . floatval($value->quantity_delivered) . '</td>';
                    echo '<td>' . $value->unit . '</td>';
                    echo '<td class="text-right">' . $value->material_lot_number . '</td>';
                    if (Auth::isPermissioned('director', 900)) { 
                      echo '<td class="text-right">' . number_format($value->purchase_price, 0) . '</td>';
                      echo '<td class="text-right">' . $value->purchase_unit . '</td>';
                      echo '<td class="text-right">' . number_format($value->production_price, 0) . '</td>';
                    }
                    echo '<td class="text-right">' . $value->note . '</td>';
                    echo '<td class="text-right"><a href="' .  Config::get('URL') . 'delete/remove/material_list_out/uid/' . $value->uid . '/?forward=' . $_SERVER['REQUEST_URI'] . '" class="btn btn-danger btn-xs" onclick="return confirmation(\'Are you sure to delete?\');">delete</a></td>';
                    echo "</tr>";
                $total_biaya_produksi = $total_biaya_produksi + $value->production_price;
                $no++;
            }
            ?>
              <?php if (Auth::isPermissioned('director', 900)) { ?>
              <tr class="info">
                  <td  class="text-right" colspan="8">TOTAL HARGA PRODUKSI</td>
                  <td  class="text-left" colspan="4"><?php echo number_format($total_biaya_produksi, 2); ?></td>
                </tr>
              <?php } ?>
              </tbody>
            </table>
          </div>
      </div>

      <div id="result_list" class="tab-pane fade in">
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover ExcelTable2007">
              <thead>
                <tr class="warning">
                  <td colspan="11">
                    <div class="btn-group btn-corner">
                      <a href="<?php echo Config::get('URL') . 'serialNumber/newSerialNumber/?sales_number=' . urlencode($this->production_detail->job_reverence) . '&production_number=' . urlencode($this->production_detail->job_number) . '&customer_type=' . $customer_type . '&forward=' . $_SERVER['REQUEST_URI']; ?>"  class="btn btn-minier btn-info" ><span class="glyphicon glyphicon-plus" aria-hidden="true"></span>Input Serial Number</a>
                    </div>
                  </td>
                </tr>
                <tr>
                  <th rowspan="2"  class="center">#</th>
                  <th rowspan="2"  class="center">Tanggal</th>
                  <th rowspan="2" class="center">Kode</th>
                  <th rowspan="2" class="center">Nama</th>
                  <th colspan="3" class="center">Quantity</th>
                  <th colspan="2" class="center">Serial Number</th>
                  <th rowspan="2" class="center">Keterangan</th>
                  <th rowspan="2" class="center">Delete</th>
                </tr>
                <tr>
                  <th class="center">Made</th>
                  <th class="center">Received</th>
                  <th class="center">Reject</th>
                  <th class="center">Made</th>
                  <th class="center">Received</th>
                </tr>
              </thead>
            <tbody>
            <?php
            $no = 1;
            $quantity = 0;
            $quantity_received = 0;
            $quantity_reject = 0;
            foreach($this->result_list as $key => $value) {
      
                    echo '<tr id="row_' . $no . '">';
                    echo '<td class="text-right">' . $no . '</td>';
                    echo '<td>' . date('d-m, Y', strtotime($value->created_timestamp)) . '</td>';
                    echo '<td><a href="' . Config::get('URL') . 'inventory/editMaterial/?find=' . $value->material_code . '">' . $value->material_code . '</a></td>';
                    echo '<td>' . $value->material_name . '</td>';
                    echo '<td class="text-right">' . floatval($value->quantity) . '</td>';
                    echo '<td class="text-right">' . floatval($value->quantity_received) . '</td>';
                    echo '<td class="text-right">' . floatval($value->quantity_reject) . '</td>';
                    echo '<td>' . $value->serial_number . '</td>';
                    echo '<td>';
                    $serial_number = explode(',', $value->serial_number_received);
                    foreach ($serial_number as $sn) {
                      echo '<a href="' .  Config::get('URL') . 'serialNumber/detail/' . urlencode(trim($sn)) . '">' . $sn . '</a>, ';
                    }
                    echo '</td>';
                    echo '<td>' . $value->note . '</td>';
                    echo '<td class="text-right"><a href="' .  Config::get('URL') . 'JobOrder/deleteProductionResult/' . $value->uid . '/?forward=' . $_SERVER['REQUEST_URI'] . '" class="btn btn-danger btn-xs" onclick="return confirmation(\'Are you sure to delete?\');">delete</a></td>';
                    echo "</tr>";
                
                $no++;
                $quantity = $quantity+ $value->quantity;
                $quantity_received = $quantity_received + $value->quantity_received;
                $quantity_reject = $quantity_reject + $value->quantity_reject;
            }
            ?>
              <tr class="info">
                  <td class="text-left" colspan="4">Total</td>
                  <td class="text-right"><?php echo $quantity; ?></td>
                  <td class="text-right"><?php echo $quantity_received; ?></td>
                  <td class="text-right"><?php echo $quantity_reject; ?></td>
                  <td class="text-right" colspan="4"></td>
                </tr>
              </tbody>
            </table>
          </div>
      </div>
  </div><!-- /.tab-content-->
</div><!-- /.tabbable-->

<!-- PAGE CONTENT ENDS -->
      </div><!-- /.col -->
    </div><!-- /.row -->
  </div><!-- /.page-content -->
</div><!-- /.main-content-inner -->
</div><!-- /.main-content -->

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

<div class="visible-print"><p class="text-center">Nomor dokumen : FM-04-003-PP Rev 02 Tanggal 25 Februari 2019</p></div>
