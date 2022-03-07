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
              <a role="button" href="<?php echo  Config::get('URL') . 'production/deleteProduction/?production_number=' . urlencode($this->production_detail->production_number) . '&forward=production/onProcess'; ?> " onclick="return confirmation('Are you sure to delete?');" class="btn btn-minier btn-danger"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span> Delete</a>
              <a role="button" href="<?php echo  Config::get('URL') . 'production/closeProduction/?production_number=' . urlencode($this->production_detail->production_number) . '&forward=' . $_SERVER['REQUEST_URI']; ?> " onclick="return confirmation('Are you sure to close Production Order?');" class="btn btn-minier btn-inverse"><span class="glyphicon glyphicon-lock" aria-hidden="true"></span> Close</a>
              </div>
            </li>
            
                 
            <li>
              <div class="btn-group btn-corner">
                  <div class="btn-group btn-corner">
                      <button data-toggle="dropdown" class="btn btn-minier btn-primary btn-white dropdown-toggle">
                        Input Material Consumption
                        <i class="ace-icon fa fa-angle-down icon-on-right"></i>
                      </button>

                      <ul class="dropdown-menu">
                        <li>
                          <a href="#input-material-consumption-bom" role="button" data-toggle="collapse">By BOM</a>
                        </li>

                        <li>
                          <a  href="<?php echo Config::get('URL') . 'production/insertManualMaterialConsumption/?job_number=' . urlencode($this->production_detail->production_number); ?>">By Manual</a>
                        </li>
                      </ul>
                  </div>

                  <a href="#input-production-result" role="button" data-toggle="collapse" class="btn btn-minier btn-info">
                    <span class="menu-icon fa fa-check" aria-hidden="true" aria-label="edit"></span> Input Production Result
                  </a>

                <?php if ($this->production_detail->status < -1) { // show hanya jika belum minta approval QC?> 
                  <a href="<?php echo Config::get('URL') . 'production/askQcApproval/?production_number=' . urlencode($this->production_detail->production_number) . '&so_number=' . urlencode($this->production_detail->production_reverence); ?>"  onclick="return confirmation('Minta Approval Hasil Produksi Ke QC?');" class="btn btn-minier btn-inverse">
                    <span class="menu-icon fa fa-check" aria-hidden="true" aria-label="edit"></span> Ask QC Approval
                  </a>
                  <?php }  ?>
              </div>
            </li>            </li>
            <?php } ?>

            <?php if ($this->production_detail->status == -1 OR $this->production_detail->status == 0) { // show hanya jika minta QC approval atau diapprove sebagian/partial?>  
            <li>
              <a role="button" data-toggle="collapse" href="#approve-qc-manual" aria-expanded="false" aria-controls="approve-qc-manual" class="btn btn-minier btn-info">
              <span class="glyphicon glyphicon-check"></span> QC Approval</a>
            </li>

            <?php } //notclosed ?>

          </ul><!-- /.breadcrumb -->
            </div>

        <!-- /section:basics/content.breadcrumbs -->
        <div class="page-content">
    <div class="row">
      <div class="col-xs-12">
        <!-- PAGE CONTENT BEGINS -->

<?php $this->renderFeedbackMessages();?>


<div class="collapse" id="approve-qc-manual">
  <form method="post" action="<?php echo Config::get('URL') . 'production/approveQcPartial/?production_number=' . urlencode($this->production_detail->production_number) . '&so_number=' . urlencode($this->production_detail->production_reverence) . '&is_internal=' . Request::get('is_internal');?>">
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
    <th colspan="3" class="center" style="width: 15%;">Quantity</th>
    <th rowspan="2" class="center" style="width: 20%;">Serial Number</th>
    <th rowspan="2" class="center" style="width: 20%;">Keterangan</th>
  </tr>
  <tr>
    <th class="center">Made</th>
    <th class="center">Received</th>
    <th class="center">Reject</th>
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
  <td><input type="number" class="form-control" name="qty_approved_<?php echo $i; ?>"></td>
  <td>
      <input type="number" class="form-control" name="qty_reject_<?php echo $i; ?>">
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
  <form method="post" action="<?php echo Config::get('URL') . 'production/inputResultManual/?production_number=' . urlencode($this->production_detail->production_number) . '&so_number=' . urlencode($this->production_detail->production_reverence) . '&is_internal=' . Request::get('is_internal');?>">
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


<div class="collapse" id="input-material-consumption-bom">
  <form method="post" action="<?php echo Config::get('URL') . 'production/inputMaterialConsumption/?production_number=' . urlencode($this->production_detail->production_number) . '&so_number=' . urlencode($this->production_detail->production_reverence); ?>">
  <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title">Input Consumption For Raw Material</h3>
        </div>
      
<table class="table table-striped table-bordered table-hover ExcelTable2007">
<tr>
  <th style="width: 5%;">#</th>
  <th style="width: 15%;">Kode</th>
  <th style="width: 30%;">Nama Produk</th>
  <th style="width: 5%;">Satuan</th>
  <th style="width: 5%;">Jumlah</th>
  <th style="width: 40%;">Keterangan</th>
</tr>
<?php
      $i = 1;
      foreach($this->material_list as $key => $value) {  ?>
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
      <?php echo $value->unit; ?>
  </td>
  <td>
      <input type="text" name="quantity_delivered_<?php echo $i; ?>">
  </td>
  <td>
      <textarea name="note_<?php echo $i; ?>" placeholder="Supplier, No PO, dll"></textarea>
  </td>
</tr>
<?php $i++; } ?>
</table>
<input type="hidden" name="total_record" value="<?php echo ($i -1); ?>">
<div class="panel-footer">
    <a class="btn btn-danger" role="button" data-toggle="collapse" href="#input-material-consumption-bom" aria-expanded="false" aria-controls="input-material-consumption-bom">
    Cancel
    </a>
  <button type="submit" id="save-button" class="btn btn-primary">Save</button>
        </div>
</div><!-- /.panel -->
</form>
</div><!-- /.collapse #input-material-consumption-bom -->
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
              <td><?php echo strtoupper($this->production_detail->production_number);?></td>
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
              <td><?php echo '<a href="' .  Config::get('URL') . 'so/detail/?so_number=' . urlencode($this->production_detail->production_reverence) . '">' . $this->production_detail->production_reverence . '</a>';?></td>

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
            $no = 1;
            $total_made = 0;
            $total_approved = 0;
            $total_reject = 0;
            //var_dump($this->product_list);
            foreach($this->product_list as $key => $value) {
      
                    echo '<tr id="row_' . $no . '">';
                    echo '<td class="text-right">' . $no . '</td>';
                    echo '<td><a href="' . Config::get('URL') . 'inventory/editMaterial/?find=' . $value->material_code . '">' . $value->material_code . '</a></td>';
                    echo '<td>' . $value->material_name . '</td>';
                    echo '<td class="text-right">' . floatval($value->quantity) . '</td>';
                    echo '<td class="text-right">' . floatval($value->quantity_received) . '</td>';
                    echo '<td class="text-right">';
                    $serial_number = explode(',', $value->serial_number_received);
                    foreach ($serial_number as $sn) {
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

      <?php if (!empty($this->timeline)) { //timeline ?>
        <div class="panel panel-info">
        <div class="panel-heading">
          <h3 class="panel-title">Timeline</h3>
        </div>
            <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover ExcelTable2007">
              <thead>
                <tr>
                  <th>Keterangan</th>
                  <?php
                    foreach($this->timeline as $key => $value) {
                        echo '<th class="center">' . $value->job_type . '</th>';
                    }
                  ?>
                </tr>
              </thead>
            <tbody>
                <tr>
                  <td>Start Date</td>
                  <?php
                    foreach($this->timeline as $key => $value) {
                        echo '<td>' . $value->start_date .'</td>';
                    }
                  ?>
                </tr>
                <tr>
                  <td>Finish Date</td>
                  <?php
                    foreach($this->timeline as $key => $value) {
                        echo '<td>' . $value->finish_date .'</td>';
                    }
                  ?>
                </tr>
            </tbody>
            </table>
            </div>
          </div>
      <?php } //timeline ?>
      

<div class="tabbable">
  <ul class="nav nav-tabs" id="myTab">
    <li class="active">
      <a data-toggle="tab" href="#forcasting">
        <i class="green ace-icon fa fa-home bigger-120"></i>
        Forcasting
      </a>
    </li>
    <li>
      <a data-toggle="tab" href="#material-list">
        <i class="green ace-icon fa fa-home bigger-120"></i>
        Material List
      </a>
    </li>
    <li>
      <a data-toggle="tab" href="#consumed_material_list">
        <i class="green ace-icon fa fa-comments bigger-120"></i>
        Material Consumption
      </a>
    </li>
    <li>
      <a data-toggle="tab" href="#result_list">
        <i class="green ace-icon fa fa-comments bigger-120"></i>
        Finished Product
      </a>
    </li>
    <li class="dropdown">
      <a data-toggle="dropdown" class="dropdown-toggle" href="#">
        <span class="glyphicon glyphicon-plus text-danger" aria-hidden="true" aria-label="Plus Button"></span>
      </a>

      <ul class="dropdown-menu dropdown-info dropdown-menu-right">
        <li>
          <a href="#add-production-material-list" role="button" data-toggle="tab">
            Tambah Daftar Material List Production
          </a>
        </li>
      </ul>
    </li>
  </ul>

  <div class="tab-content">
      <div id="forcasting" class="tab-pane fade in active">
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover ExcelTable2007">
                <tr class="warning">
                  <td colspan="7">
                    <a href="<?php echo Config::get('URL') . 'ExportExcel/productionForcasting/?production_number=' . urlencode($this->production_detail->production_number) . '&so_number=' . urlencode($this->production_detail->production_reverence); ?>">
                      <span class="badge badge-info">
                      <i class="glyphicon glyphicon-plus"></i> Export Excel
                      </span>
                    </a>
                  </td>
                </tr>
                <tr class="info">
                  <td  class="center">#</td>
                  <td  class="center">Job Type</td>
                  <td  class="center">Kode</td>
                  <td  class="center">Nama</td>
                  <td  class="center">Qty</td>
                  <td  class="center">Satuan</td>
                  <td  class="center">Keterangan</td>
                </tr>
              
            <?php
            $no = 1;
            foreach($this->forcasting as $key => $value) {
      
                    echo '<tr id="row_' . $no . '">';
                    echo '<td class="text-right">' . $no . '</td>';
                    echo '<td>' . $value->job_type . '</td>';
                    echo '<td><a href="' . Config::get('URL') . 'inventory/editMaterial/?find=' . $value->material_code . '">' . $value->material_code . '</a></td>';
                    echo '<td>' . $value->material_name . '</td>';
                    echo '<td class="text-right">' . floatval($value->quantity) . '</td>';
                    echo '<td>' . $value->unit . '</td>';
                    echo '<td class="text-right">' . $value->note . '</td>';
                    echo "</tr>";
                
                $no++;
            }
            ?>
            </table>
          </div>
      </div>

      <div id="material-list" class="tab-pane fade in">
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover ExcelTable2007">
              <thead>
                <tr class="warning">
                  <td colspan="6">
                    <a href="#" onclick="printPage('<?php echo Config::get('URL') . 'production/printRawMaterialProduction/?production_number=' . urlencode($this->production_detail->production_number); ?>')">
                      <span class="badge badge-info">
                      <i class="glyphicon glyphicon-print"></i> Print Material
                      </span>
                    </a>

                    <a href="<?php echo Config::get('URL') . 'pr/createPrFromJo/?production_number=' . urlencode($this->production_detail->production_number); ?>">
                      <span class="badge badge-inverse">
                      <i class="fa fa-shopping-basket"></i> Create PR
                      </span>
                    </a>
                  </td>
                </tr>
                <tr>
                  <th  class="center">#</th>
                  <th  class="center">Kode</th>
                  <th  class="center">Nama</th>
                  <th  class="center">Qty</th>
                  <th  class="center">Stock</th>
                  <th  class="center">Satuan</th>
                </tr>
              </thead>
            <tbody>
            <?php
            $no = 1;
            foreach($this->material_list as $key => $value) {
      
                    echo '<tr id="row_' . $no . '">';
                    echo '<td class="text-right">' . $no . '</td>';
                    echo '<td><a href="' . Config::get('URL') . 'inventory/editMaterial/?find=' . $value->material_code . '">' . $value->material_code . '</a></td>';
                    echo '<td>' . $value->material_name . '</td>';
                    echo '<td class="text-right">' . floatval($value->quantity) . '</td>';
                    $balance = $value->debit - $value->credit - $value->balancer ;
                    echo '<td class="text-right">' . $balance . '</td>';
                    echo '<td>' . $value->unit . '</td>';
                    echo "</tr>";
                
                $no++;
            }
            ?>
              </tbody>
            </table>
          </div>
      </div>

      <div id="consumed_material_list" class="tab-pane fade in">
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover ExcelTable2007">
              <thead>
                <tr>
                  <th  class="center">#</th>
                  <th  class="center">Tanggal</th>
                  <th  class="center">Kode</th>
                  <th  class="center">Nama</th>
                  <th  class="center">Qty</th>
                  <th  class="center">Satuan</th>
                  <?php if (Auth::isPermissioned('director', 900)) { ?>
                  <th  class="center">Harga Pembelian</th>
                  <?php } ?>
                  <th  class="center">Keterangan</th>
                  <th  class="center">Delete</th>
                </tr>
              </thead>
            <tbody>
            <?php
            $no = 1;
            foreach($this->consumed_material_list as $key => $value) {
      
                    echo '<tr id="row_' . $no . '">';
                    echo '<td class="text-right">' . $no . '</td>';
                    echo '<td>' . date('d-m, Y', strtotime($value->created_timestamp)) . '</td>';
                    echo '<td><a href="' . Config::get('URL') . 'inventory/editMaterial/?find=' . $value->material_code . '">' . $value->material_code . '</a></td>';
                    echo '<td>' . $value->material_name . '</td>';
                    echo '<td class="text-right">' . floatval($value->quantity_delivered) . '</td>';
                    echo '<td>' . $value->unit . '</td>';
                    if (Auth::isPermissioned('director', 900)) { 
                    echo '<td class="text-right">' . number_format($value->selling_price, 0) . '</td>';
                  }
                    echo '<td class="text-right">' . $value->note . '</td>';
                    echo '<td class="text-right"><a href="' .  Config::get('URL') . 'production/remove/material_list_out/uid/' . $value->uid . '/?production_number=' . $this->production_detail->production_number. '&so_number=' . $this->production_detail->production_reverence . '" class="btn btn-danger btn-xs" onclick="return confirmation(\'Are you sure to delete?\');">delete</a></td>';
                    echo "</tr>";
                
                $no++;
            }
            ?>
              </tbody>
            </table>
          </div>
      </div>

      <div id="result_list" class="tab-pane fade in">
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover ExcelTable2007">
              <thead>
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
                      echo '<a href="' .  Config::get('URL') . 'serialNumber/detail/' .urlencode($sn) . '">' . $sn . '</a>, ';
                    }
                    echo '</td>';
                    echo '<td>' . $value->note . '</td>';
                    echo '<td class="text-right"><a href="' .  Config::get('URL') . 'delete/remove/material_list_in/uid/' . $value->uid . '/?production_number=' . $this->production_detail->production_number. '&so_number=' . $this->production_detail->production_reverence . '" class="btn btn-danger btn-xs" onclick="return confirmation(\'Are you sure to delete?\');">delete</a></td>';
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

      <div id="add-production-material-list" class="tab-pane fade">
        <form class="form-horizontal" method="post" action="<?php echo Config::get('URL') . 'production/addNewProductionMaterial/?production_number=' . urlencode($this->production_detail->production_number) . '&so_number=' . urlencode($this->production_detail->production_reverence); ?>">

            <div class="form-group">
              <label class="col-sm-2 control-label">Kode/Nama Material</label>
              <div class="col-sm-10">
                <div class="input-group" style="width: 100%">
                            <input type="text" class="form-control" placeholder="klik tombol pilih untuk memilih kode material" name="material_code" id="material_code_new" required/>
                          <span class="input-group-btn">
                          <a href="#" class="btn btn-purple btn-sm " onclick="selectMaterial()">Pilih Material </a>
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
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label">Jumlah</label>
              <div class="col-sm-10">
                <input type="number" class="form-control" placeholder="Jumlah bahan material" name="quantity"/>
              </div>
            </div>
            <div class="form-group">
              <div class="col-sm-offset-2 col-sm-10">
                <button type="submit" class="btn btn-info">SAVE</button>
              </div>
            </div>
        </form>
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