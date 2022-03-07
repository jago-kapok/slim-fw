<div class="main-content">
    <div class="main-content-inner">
                <!-- #section:basics/content.breadcrumbs -->
                <div class="breadcrumbs" id="breadcrumbs">
                    <script type="text/javascript">
                        try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
                    </script>

                    <ul class="breadcrumb">
                        <li>
                            <a href="<?php echo Config::get('URL') . 'file/manual/import_bom.xlsx';?>">
                            <span class="badge badge-info"><i class="ace-icon glyphicon glyphicon-save"></i> Download Template Excel</span></a>
                        </li>
                        <li>
                            <div class="btn-group btn-corner">
                              <a href="<?php echo Config::get('URL') . 'inventory/addFormulation/?material_code=' . urlencode($this->result->material_code); ?>" class="btn btn-minier btn-inverse">
                                            <i class="ace-icon fa fa-plus"></i> Insert Material</a>
                                <a data-toggle="collapse" href="#new-bom" aria-expanded="false" aria-controls="new-bom" class="btn btn-minier btn-info">
                                            <i class="ace-icon glyphicon glyphicon-import"></i> Import Material</a>
                              </div>
                        </li>
                        <li>
                            <a href="<?php echo Config::get('URL') . 'inventory/resetBom/?material_code=' . urlencode($this->result->material_code); ?>" onclick="return confirmation('Are you sure to reset BOM?');">
                            <span class="badge badge-danger"><i class="ace-icon glyphicon glyphicon-refresh"></i> Reset BOM</span></a>
                        </li>
                    </ul><!-- /.breadcrumb -->

                    <!-- #section:basics/content.searchbox -->
                    <div class="nav-search" id="nav-search">
                        <form class="form-search" method="get" action="<?php echo Config::get('URL');?>inventory/editStock/">
                <span class="input-icon">
                    <input type="text" placeholder="Search ..." class="nav-search-input" id="nav-search-input" autocomplete="off" name="sn" />
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
                            

   <div class="collapse" id="new-bom">
    <form class="form-horizontal" method="post" action="<?php echo Config::get('URL') . 'import/importBom/';?>">
    <div class="form-group">
      <label for="inputPassword3" class="col-sm-2 control-label">Keterangan</label>
      <div class="col-sm-9">
        <textarea name="import_post" class="form-control" rows="3" placeholder="Download template excel untuk import BOM, kemudian paste/salin kolom HASIL disini"></textarea>
        <input name ="formulation_code"  type="hidden" value="<?php echo $this->result->material_code; ?>"/>
      </div>
    </div>

    <div class="form-group">
      <div class="col-sm-offset-2 col-sm-10">
        <button type="submit" class="btn btn-info">Insert</button>
      </div>
    </div>
    </form>
    <div class="hr hr10 hr-double"></div>
</div>

<?php
 $this->renderFeedbackMessages(); // Render message success or not?>
<!-- Use modal box as design -->
<div>
       <form method="post" action="<?php echo Config::get('URL') . 'inventory/updateBom/?material_code=' . urlencode($this->result->material_code); ?>" >
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body overflow-visible">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12">
                            <div class="form-group">
                                <label for="form-field-username">Material Name</label>
                                <div>
                                    <input name ="material_name" class="form-control" type="text" value="<?php echo $this->result->material_name; ?>"/>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-12 col-sm-6">
                            <div class="form-group">
                                <label for="form-field-username">ID</label>
                                <div>
                                <input class="form-control" type="text"  placeholder="<?php echo $this->result->material_code; ?>" disabled/>
                                <input name ="material_code"  type="hidden" value="<?php echo $this->result->material_code; ?>"/>
                                </div>
                            </div>
                            
                        </div>

                        <div class="col-xs-12 col-sm-6">
                            <div class="form-group">
                                <label for="form-field-username">Satuan</label>
                                <div>
                                   <input type="text" name ="unit" class="form-control" placeholder="satuan" value="<?php echo $this->result->unit; ?>">
                                </div>
                            </div>

                            <div class="space-4"></div>

                        </div>
                    </div>

                    <div class="space-12"></div>

                    <div class="row">
                        <div class="col-xs-12 col-sm-12">
                            <label for="form-field-first">Material Description</label>

                            <div>
                                <textarea name ="note" class="autosize-transition form-control" placeholder="Keterangan tambahan"><?php echo $this->result->note; ?></textarea>
                                <input type="hidden" name ="note_old" value="<?php echo $this->result->note; ?>">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="clearfix form-actions">
                    <div class="col-md-offset-3 col-md-9">
                        <a href="javascript: history.go(-1)">
                            <button class="btn btn-danger">
                                <i class="icon-remove bigger-110"></i>
                                Cancel
                            </button>
                        </a>
                        
                        &nbsp; &nbsp; &nbsp;
                        <button class="btn" type="reset">
                            <i class="icon-undo bigger-110"></i>
                            Reset
                        </button>

                        &nbsp; &nbsp; &nbsp;
                        <button class="btn btn-info" type="submit">
                            <i class="icon-ok bigger-110"></i>
                            Update
                        </button>
                    </div>
                </div>
                <!-- /Button Action Group -->
                
            </div>
        </div>
    </form>
</div>

                <div class="hr hr10 hr-double"></div>

<section id="detail-bom">

<div class="table-responsive">
<table class="table table-striped table-bordered table-hover">
    <thead>
    <tr>
    <th rowspan="2">No</th>
    <th class="center" rowspan="2">Job Type</th>
    <th class="center" rowspan="2">Code</th>
    <th class="center" rowspan="2">Material Name</th>
    <th class="center" colspan="2">Pembelian</th>
    <th class="center" colspan="3">Produksi</th>
    <th class="center" rowspan="2">Keterangan</th>
    <th class="center" rowspan="2">Delete</th>
    </tr>
    <tr>
    <th class="center">Harga (IDR)</th>
    <th class="center">Satuan</th>
    <th class="center">Qty</th>
    <th class="center">Satuan</th>
    <th class="center">Biaya (IDR)</th>
    </tr>
    </thead>

    <tbody>
    <?php
    
    $no = 1;
    $total_biaya_produksi = 0;
    foreach($this->formulation_list as $key => $value) {
        $harga_pembelian = $value->purchase_price;
        $biaya_produksi = $harga_pembelian * $value->unit_per_quantity * $this->currency_rate[$value->purchase_currency]['jual'];
        $total_biaya_produksi = $total_biaya_produksi + $biaya_produksi;
    //check apakah satuan dalam bijian
      $satuan_pieces = array('unit', 'pcs', 'biji', 'pieces', 'buah','set');
      if (in_array(strtolower($value->formulation_unit), $satuan_pieces)) {
        $converted_production_unit = 'bijian';
      } else {
        $converted_production_unit = strtolower($value->formulation_unit);
      }
      if (in_array(strtolower($value->purchase_unit), $satuan_pieces)) {
        $converted_purchase_unit = 'bijian';
      } else {
        $converted_purchase_unit = strtolower($value->purchase_unit);
      }

    //check apakah satuan sama/equal
      if ($converted_production_unit == $converted_purchase_unit) {
        $status = '';
      }

      if ($converted_production_unit != $converted_purchase_unit) {
        $status = 'warning';
      }

      //check apakah harga pembelian atau produksi nilainya kosong!
      if ($harga_pembelian <= 0 OR $biaya_produksi <= 0) {
        $status = 'danger';
      }

    echo '<tr class="' . $status . '">';
    echo '<td>' . $no . '</td>';
    echo '<td>' . $value->job_type . '</td>';
    echo '<td><a href="' . Config::get('URL') . 'inventory/editMaterial/?find=' . $value->material_code . '">' . $value->material_code . '</a></td>';
    echo '<td>' . $value->material_name . '</a></td>';
    echo '<td class="text-right">' . number_format($harga_pembelian,2) . '</td>';
    echo '<td>' . $value->purchase_unit . '</td>';
    echo '<td class="text-right">' . floatval($value->unit_per_quantity) . '</td>';
    echo '<td>' . $value->formulation_unit . '</td>';
    echo '<td class="text-right">' . number_format($biaya_produksi,2) . '</td>';
    echo '<td>' . $value->note . '</td>';
    echo '<td><a href="' .  Config::get('URL') . 'delete/remove/material_list_formulation/uid/' . $value->uid . '&?forward=' . $_SERVER['REQUEST_URI'] . '" class="btn btn-danger btn-xs" onclick="return confirmation(\'Are you sure to delete?\');">delete</a></td>';
    
    echo "</tr>";
    $no++;
    $status = '';
    }
    ?>
    <tr class="success">
    <td class="text-right" colspan="8">Total Biaya Produksi</td>
    <td class="text-left" colspan="3"><?php echo number_format($total_biaya_produksi,2);?></td>
    </tr>
    </tbody>
    </table>
    </div>
</section>
<script type="text/javascript">
$('#with-step').on('change keyup', function(event) {
    if ( event.target.validity.valid ) {
         $('#test2').text('');
    } else {
        $('#test2').text('NOPE, Use numbers only and dot as separator (max 2)');
    }    
});
</script>
<!-- PAGE CONTENT ENDS -->
      </div><!-- /.col -->
    </div><!-- /.row -->
  </div><!-- /.page-content -->
</div><!-- /.main-content-inner -->
</div><!-- /.main-content -->