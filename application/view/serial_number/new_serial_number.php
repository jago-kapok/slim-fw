<div class="main-content">
        <div class="main-content-inner">
                <!-- /section:basics/content.breadcrumbs -->
                <div class="page-content">
                    <div class="row">
                        <div class="col-xs-12">
                            <!-- PAGE CONTENT BEGINS -->
                
<?php
 $this->renderFeedbackMessages(); // Render message success or not?>


<!-- Use modal box as design -->
        <div class="modal-dialog">
            <div class="modal-content">
              <form method="post" action="<?php echo Config::get('URL') . 'serialNumber/submitSerialNumber/?forward=' . $this->forward; ?>" >
                <div class="modal-body overflow-visible">

                    <div class="row">
                        <div class="col-xs-12 col-sm-5">
                            <div class="form-group">
                                <label class="text-info"><strong>Jumlah Serial Number</strong></label>
                                <div>
                                   <input type="number" name="serial_number_quantity">
                                </div>
                            </div>

                            <div class="space-4"></div>

                            <div class="form-group">
                                <label class="text-info"><strong>Serial Number</strong></label>
                                <div>
                                  <textarea name="serial_number" placeholder="Pisahkan tiap serial number dengan tanda koma (,). Jika serial number dikosongkan, akan diisi secara otomatis oleh sistem sesuai jumlah serial number di atas" rows="7"></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-7">
                            <div class="form-group">
                                <label class="text-info"><strong>Tipe Pelanggan</strong></label>
                                <div>
                                  <select name="customer_type">
                                    <option value="P" <?php echo ($this->customer_type == 'P') ? 'selected="selected"' : ''; ?>>PLN</option>
                                    <option value="S" <?php echo ($this->customer_type == 'S') ? 'selected="selected"' : ''; ?>>Swasta</option>
                                  </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="text-info"><strong>Material Code</strong></label>
                                <div>
                                  <input name="material_code" class="form-control" type="text" value="<?php echo $this->material_code; ?>" placeholder="wajib diisi" />
                                </div>
                            </div>

                            <div class="space-4"></div>

                            <div class="form-group">
                                <label class="text-info"><strong>Sales Number</strong></label>
                                <div>
                                  <input name="sales_number" class="form-control" type="text" value="<?php echo $this->sales_number; ?>"/>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="text-info"><strong>Production Number</strong></label>
                                <div>
                                  <input name="production_number" class="form-control" type="text" value="<?php echo $this->production_number; ?>" placeholder="wajib diisi"/>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="clearfix form-actions">
                    <div class="col-md-offset-3 col-md-9">
                       
                        
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
                </form>
            </div>
        </div>
<!-- PAGE CONTENT ENDS -->
      </div><!-- /.col -->
    </div><!-- /.row -->
  </div><!-- /.page-content -->
</div><!-- /.main-content-inner -->
</div><!-- /.main-content -->