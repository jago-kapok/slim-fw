<div class="main-content">
                <div class="main-content-inner">
                    <div class="breadcrumbs ace-save-state" id="breadcrumbs">
                        
                    </div>

                    <div class="page-content">
                        <div class="row">
                            <div class="col-xs-12">
                                <!-- PAGE CONTENT BEGINS -->
                                <h3>Tambah Daftar Kategori/Shift Jam Kerja</h3>

<form method="post" class="form-horizontal"  action="<?php echo Config::get('URL') . 'systemPreference/saveWorkingHours/'; ?>">
  <div class="form-group">
    <label for="inputEmail3" class="col-sm-2 control-label">Nama Jam Kerja</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="item_name" name="group">
    </div>
  </div>
  <div class="form-group">
    <label for="inputEmail3" class="col-sm-2 control-label">Jam Masuk</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="item_name" name="start_working_hour" placeholder="Format HH:MM Contoh: 08:00">
    </div>
  </div>

  <div class="form-group">
    <label for="inputEmail3" class="col-sm-2 control-label">Jam Keluar</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="item_name" name="finish_working_hour" placeholder="Format HH:MM Contoh: 04:00">
    </div>
  </div>

  <div class="form-group">
    <label for="inputPassword3" class="col-sm-2 control-label">Keterangan</label>
    <div class="col-sm-10">
      <textarea class="form-control" rows="3" name="note"></textarea>
    </div>
  </div>
  <div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
      <button type="submit" class="btn btn-info">Save</button>
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
                                                                <th colspan="6" class="text-center">Daftar Kategori Yang Tersimpan</th>
                                                            </tr>
                                                            <tr>
                                                                <th>#</th>
                                                                <th>Nama</th>
                                                                <th>Jam Masuk</th>
                                                                <th>Jam Keluar</th>
                                                                <th>Keterangan</th>
                                                                <th>Delete</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                                $no = 1;
                                                                foreach($this->category as $value) { ?> 
                                                            <tr>
                                                                <td><?php echo $no; ?></td>
                                                                <td><?php echo $value->group; ?></td>
                                                                <td><?php echo date('H:i', strtotime($value->start_working_hour)); ?></td>
                                                                <td><?php echo date('H:i', strtotime($value->finish_working_hour)); ?></td>
                                                                <td><?php echo $value->note; ?></td>
                                                                <td>
                                                                    <div class="btn-group" role="group" aria-label="...">
                                                                        <a href="<?php echo Config::get('URL') . 'delete/remove/working_hours_preference/uid/' . $value->uid . '/?forward=' . $_SERVER['REQUEST_URI'];?>" class="btn btn-danger btn-xs" onclick="return confirmation('Are you sure to delete?');">
                                                                        delete
                                                                        </a>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            <?php $no++;} ?>
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