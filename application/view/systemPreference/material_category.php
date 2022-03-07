<div class="main-content">
                <div class="main-content-inner">
                    <div class="breadcrumbs" id="breadcrumbs">
                    <script type="text/javascript">
                        try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
                    </script>

                    <ul class="breadcrumb">
                        <li>
                        <a href="<?php echo Config::get('URL') . 'inventory/newMaterial/'; ?>" role="button" data-toggle="modal">
                            <span class="badge badge-info"><i class="ace-icon fa fa-plus"></i> New</a></span>
                        </li>
                    </ul><!-- /.breadcrumb -->

                    <!-- /section:basics/content.searchbox -->
                </div>

                    <div class="page-content">
                        <div class="row">
                            <div class="col-xs-12">
                                <!-- PAGE CONTENT BEGINS -->
                                <h3>Tambah Daftar Kategori Material</h3>

<form method="post" class="form-horizontal"  action="<?php echo Config::get('URL') . 'systemPreference/insertCategoryMaterial/'; ?>">
    <div class="form-group">
        <label class="col-sm-2 control-label">Tipe Material</label>
        <div class="col-sm-10">
            <select name="category_item" id="material-type">
                    <option value="">Pilih Tipe Material</option>
                    <option value="raw material">Raw Materials/Bahan Baku</option>
                    <option value="wip material">Work In Progress (WIP)/Setengah Jadi</option>
                    <option value="finished product">Finished Products/Produk Jadi</option>
                    <option value="trading goods">Trading Goods/Bahan Baku + Produk Jadi</option>
                    <option value="service product">Services/Jasa</option>
                    <option value="production tools">Production Resources/Tools/Alat Produksi</option>
                    <option value="operating supplies">Consumed Operating Supplies/Alat Bantu Produksi Yang Terkonsumsi</option>
                    <option value="asset material">Asset</option>
            </select>
        </div>
    </div>

      <div class="form-group">
        <label class="col-sm-2 control-label">Nama Kategori</label>
        <div class="col-sm-10">
          <input type="text" class="form-control" id="item_name" name="category_value">
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-2 control-label">Kode Kategori</label>
        <div class="col-sm-10">
          <input type="text" class="form-control" id="item_name" name="category_code">
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-2 control-label">Keterangan</label>
        <div class="col-sm-10">
          <textarea class="form-control" rows="3" name="note"></textarea>
        </div>
      </div>
      <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
          <button type="submit" class="btn btn-default">Save</button>
        </div>
      </div>
</form>


<div class="hr hr10 hr-double"></div>

                  <div class="row">
                                    <div class="col-xs-12 col-sm-12">
                                            <div class="table-responsive">
                                                <table class="table table-condensed table-striped table-hover table-bordered">
                                                        <thead>
                                                            <tr>
                                                                <th colspan="4" class="text-center">Daftar Kategori Yang Tersimpan</th>
                                                            </tr>
                                                            <tr>
                                                                <th>Tipe Material</th>
                                                                <th>Nama Kategori</th>
                                                                <th>Kode Kategori</th>
                                                                <th>Keterangan</th>
                                                                <th>Delete</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                                $no = 1;
                                                                $category = '';
                                                                foreach($this->category as $value) { ?> 
                                                            <tr>
                                                                <td><?php
                                                                    if ($value->item_name != $category) {
                                                                        echo ucwords($value->item_name);
                                                                    }
                                                                    ?></td>
                                                                <td><?php echo $value->value; ?></td>
                                                                <td><?php echo $value->uid; ?></td>
                                                                <td><?php echo $value->note; ?></td>
                                                                <td>
                                                                    <div class="btn-group" role="group" aria-label="...">
                                                                        <a href="<?php echo Config::get('URL') . 'delete/remove/system_preference/uid/' . $value->uid . '/?forward=' . $_SERVER['REQUEST_URI'];?>" class="btn btn-danger btn-xs" onclick="return confirmation('Are you sure to delete?');">
                                                                        delete
                                                                        </a>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            <?php
                                                                
                                                                $category = $value->item_name;
                                                            }
                                                            ?>
                                                        </tbody>
                                                </table>
                                            </div>
                                    </div><!-- /.col -->
                                </div><!-- /.row -->

<!-- PAGE CONTENT ENDS -->
              </div><!-- /.col -->
            </div><!-- /.row -->
          </div><!-- /.page-content -->
        </div>
      </div><!-- /.main-content -->