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
              <?php if (isset($_GET['edit']) AND !empty($_GET['edit'])) { ?>
              <form method="post" action="<?php echo Config::get('URL') . 'serialNumber/updateSerialNumber/' . urlencode($this->sn->serial_number); ?>" >
              <?php } ?>
                <div class="modal-body overflow-visible">

                    <div class="row">
                        <div class="col-xs-12 col-sm-12">
                            <div class="form-group">
                                <label class="text-info"><strong>Material Name</strong></label>
                                <div>
                                    <?php echo $this->sn->material_name; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-12 col-sm-5">
                            <div class="form-group">
                                <label class="text-info"><strong>Serial Number</strong></label>
                                <div>
                                  <?php echo $this->sn->serial_number; ?>
                                </div>
                            </div>

                            <div class="space-4"></div>

                            <div class="form-group">
                                <label class="text-info"><strong>Tanggal Approval</strong></label>

                                <div>
                                    <?php echo $this->sn->created_timestamp; ?>
                                </div>
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-7">
                            <div class="form-group">
                                <label class="text-info"><strong>Material Code</strong></label>
                                <div>
                                  <input name="material_code" class="form-control" type="text" value="<?php echo $this->sn->material_code; ?>"/>
                                </div>
                            </div>

                            <div class="space-4"></div>

                            <div class="form-group">
                                <label class="text-info"><strong>Sales Number</strong></label>
                                <div>
                                  <input name="transaction_number" class="form-control" type="text" value="<?php echo $this->sn->transaction_number; ?>"/>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="text-info"><strong>Production Number</strong></label>
                                <div>
                                  <input name="production_number" class="form-control" type="text" value="<?php echo $this->sn->production_number; ?>"/>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="space-12"></div>

                    <div class="row">
                        <div class="col-xs-12 col-sm-12">
                            
                        </div>
                    </div>

                </div>

                <?php if (isset($_GET['edit']) AND !empty($_GET['edit'])) { ?>
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
                <?php } ?>
            </div>
        </div>

<div class="hr hr10 hr-double"></div>

<section id="tab">
    <div class="row">
          <div class="col-xs-12 col-sm-12">

            <div class="tabbable">
                <ul class="nav nav-tabs" id="myTab">
                    <li class="active">
                      <a data-toggle="tab" href="#uploaded-file">
                        <i class="green ace-icon fa fa-cloud-download bigger-120"></i>
                        Uploaded File
                      </a>
                    </li>

                    <li class="dropdown">
                      <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                        <i class="glyphicon glyphicon-plus text-danger"></i>
                        &nbsp;
                        <i class="ace-icon fa fa-caret-down bigger-110 width-auto"></i>
                      </a>

                      <ul class="dropdown-menu dropdown-info">
                        <li>
                          <a data-toggle="tab" href="#dropdown1">Upload Photo/Scan</a>
                        </li>

                        <li>
                          <a data-toggle="tab" href="#dropdown2">Upload Document</a>
                        </li>
                      </ul>
                    </li>
                </ul>

                <div class="tab-content">
                    <div id="uploaded-file" class="tab-pane fade in active">
                      <table class="table table-striped table-bordered table-hover">
                        <thead>
                          <tr>
                            <th>Nama</th>
                            <th>Keterangan</th>
                            <th>Delete</th>
                          </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($this->uploaded_file as $key => $value) {
                          echo '<tr>
                                <td><a href="' .  Config::get('URL') . 'file/' . $value->value . '" target="_blank">' . $value->item_name . '</td>
                                <td>' . $value->note . '</td>
                                <td><a href="' .  Config::get('URL') . 'delete/soft/upload_list/uid/' . $value->uid . '/&forward=' . $_SERVER['REQUEST_URI'] . ' " class="btn btn-danger btn-minier" onclick="return confirmation(\'Are you sure to delete?\');"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span> delete</a></td>
                                </tr>';
                        } ?>
                        </tbody>
                      </table>
                    </div>

                    <div id="dropdown1" class="tab-pane fade">
                        <form class="form-horizontal" action="<?php echo Config::get('URL') . 'serialNumber/uploadImage/' . $this->sn->serial_number; ?>" method="post" enctype="multipart/form-data">
                            <div class="form-group">
                              <label class="col-sm-2 control-label">Nama Photo/Scan</label>
                              <div class="col-sm-10">
                                <input type="text" name="image_name" class="form-control" placeholder="Nama file photo atau hasil scan yang diupload">
                              </div>
                            </div>
                            <div class="form-group">
                              <label class="col-sm-2 control-label">Pilih Photo/Scan</label>
                              <div class="col-sm-10">
                                <input type="file" class="form-control" name="file_name" required />
                                <span id="helpBlock" class="help-block">Hanya file image tipe jpg, jpeg dan png yang diijinkan, ukuran maksimum 3MB dan minimum dimensi 100 x 100 pixel</span>
                              </div>
                            </div>
                            <div class="form-group">
                              <label class="col-sm-2 control-label">Keterangan</label>
                              <div class="col-sm-10">
                                <textarea name="note" class="form-control" rows="3"></textarea>
                              </div>
                            </div>
                            <div class="form-group">
                              <div class="col-sm-offset-2 col-sm-10">
                                <button type="submit" class="btn btn-info">Upload Photo</button>
                              </div>
                            </div>
                        </form>
                    </div>

                    <div id="dropdown2" class="tab-pane fade">
                      <form class="form-horizontal" action="<?php echo Config::get('URL') . 'serialNumber/uploadDocument/' . $this->sn->serial_number; ?>" method="post" enctype="multipart/form-data">
                            <div class="form-group">
                              <label class="col-sm-2 control-label">Nama Document</label>
                              <div class="col-sm-10">
                                <input type="text" name="document_name" class="form-control" placeholder="Nama file photo atau hasil scan yang diupload">
                              </div>
                            </div>
                            <div class="form-group">
                              <label class="col-sm-2 control-label">Pilih Document</label>
                              <div class="col-sm-10">
                                <input type="file" class="form-control" name="file_name" required />
                                <span id="helpBlock" class="help-block">Hanya file tipe dokumen yang diijinkan, seperti: PDF, Microsoft Word (doc, docx), Mircosoft Excel (xls, xlsx) dan Microsoft PowerPoint (ppt, pptx). Ukuran maksimum 3MB</span>
                              </div>
                            </div>
                            <div class="form-group">
                              <label class="col-sm-2 control-label">Keterangan</label>
                              <div class="col-sm-10">
                                <textarea name="note" class="form-control" rows="3"></textarea>
                              </div>
                            </div>
                            <div class="form-group">
                              <div class="col-sm-offset-2 col-sm-10">
                                <button type="submit" class="btn btn-info">Upload Document</button>
                              </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div><!-- /.tabbable-->
          </div>
        </div><!-- ./row -->

</section><!-- #tab -->
<!-- PAGE CONTENT ENDS -->
      </div><!-- /.col -->
    </div><!-- /.row -->
  </div><!-- /.page-content -->
</div><!-- /.main-content-inner -->
</div><!-- /.main-content -->