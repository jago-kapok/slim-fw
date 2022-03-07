<div class="main-content">
                <!-- /section:basics/content.breadcrumbs -->
                <div class="page-content">
                    <div class="row">
                        <div class="col-xs-12">
                            <!-- PAGE CONTENT BEGINS -->

<?php $this->renderFeedbackMessages(); // Render message success or not ?>

<form method="post" action="<?php echo Config::get('URL') ;?>cashTransaction/insertCashCategory/">
<div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title">Masukkan Kategori Pemasukan/Pengeluaran Baru</h3>
  </div>
  <div class="panel-body">
        <div class="row">
            <div class="col-xs-12 col-sm-6">
                <div class="form-group">
                    <label for="jumlah-pinjaman">Nama Kategori</label>
                    <input type="text" name="category_name" autocomplete="off" class="form-control" id="jumlah-pinjaman">
                </div>
            </div><!-- /.col -->

            <div class="col-xs-12 col-sm-6">
                <div class="form-group">
                    <label for="jenis-pinjaman">Jenis Kategory</label>
                    <select name="jenis" class="form-control">
                        <option value="">Pilih</option>
                        <option value="pemasukan">Pemasukan</option>
                        <option value="pengeluaran">Pengeluaran</option>
                    </select>
                </div>
            </div><!-- /.col -->
        </div><!-- /.row -->

        <div class="row">
            <div class="col-xs-12 col-sm-12">
                <label for="form-field-first">Catatan</label>
                <textarea class="form-control" rows="3" name="note"></textarea>
            </div><!-- /.col -->
        </div><!-- /.row -->
  </div>
  <div class="panel-footer">
        <p align="right">
            <button class="btn" type="reset">Reset</button>
            &nbsp; &nbsp; &nbsp;
            <button class="btn btn-primary" type="submit">Save</button>
        </p>
  </div>
</div>
</form>


                  <div class="row">
                                    <div class="col-xs-12 col-sm-6">
                                            <div class="table-responsive">
                                                <table class="table table-condensed table-striped table-hover table-bordered">
                                                        <thead>
                                                            <tr>
                                                                <th colspan="4" class="text-center">Pemasukan</th>
                                                            </tr>
                                                            <tr>
                                                                <th>#</th>
                                                                <th>Nama</th>
                                                                <th>Keterangan</th>
                                                                <th>Delete</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                                $no = 1;
                                                                foreach($this->pemasukan as $value) { ?> 
                                                            <tr>
                                                                <td><?php echo $no; ?></td>
                                                                <td><?php echo $value->category_name; ?></td>
                                                                <td><?php echo $value->note; ?></td>
                                                                <td>
                                                                    <div class="btn-group" role="group" aria-label="...">
                                                                        <a href="<?php echo Config::get('URL') . 'delete/remove/cash_transaction_category/uid/' . $value->uid . '/?forward=' . $_SERVER['REQUEST_URI'];?>" class="btn btn-danger btn-xs" onclick="return confirmation('Are you sure to delete?');">
                                                                        delete
                                                                        </a>
                                                                        <!-- Button trigger modal -->
                                                                        <button type="button" class="edit-category btn btn-primary btn-xs" data-name="<?php echo $value->category_name; ?>" data-note="<?php echo $value->note; ?>" data-toggle="modal" data-target="#editCategory">
                                                                          edit
                                                                        </button>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            <?php $no++;} ?>
                                                        </tbody>
                                                </table>
                                            </div>
                                    </div><!-- /.col -->

                                    <div class="col-xs-12 col-sm-6">
                                        <div class="table-responsive">
                                                <table class="table table-condensed table-striped table-hover table-bordered">
                                                        <thead>
                                                            <tr>
                                                                <th colspan="4" class="text-center">Pengeluaran</th>
                                                            </tr>
                                                            <tr>
                                                                <th>#</th>
                                                                <th>Nama</th>
                                                                <th>Keterangan</th>
                                                                <th>Delete</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                                $no = 1;
                                                                foreach($this->pengeluaran as $value) { ?> 
                                                            <tr>
                                                                <td><?php echo $no; ?></td>
                                                                <td><?php echo $value->category_name; ?></td>
                                                                <td><?php echo $value->note; ?></td>
                                                                <td>
                                                                    <div class="btn-group" role="group" aria-label="...">
                                                                        <a href="<?php echo Config::get('URL') . 'delete/remove/cash_transaction_category/uid/' . $value->uid . '/?forward=' . $_SERVER['REQUEST_URI'];?>" class="btn btn-danger btn-xs" onclick="return confirmation('Are you sure to delete?');">
                                                                        delete
                                                                        </a>
                                                                        <!-- Button trigger modal -->
<button type="button" class="edit-category btn btn-primary btn-xs" data-name="<?php echo $value->category_name; ?>" data-note="<?php echo $value->note; ?>" data-toggle="modal" data-target="#editCategory">
  edit
</button>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            <?php $no++;} ?>
                                                        </tbody>
                                                </table>
                                            </div>
                                    </div><!-- /.col -->

                                </div><!-- /.row -->


<!-- Modal -->
<div class="modal fade" id="editCategory" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
<form method="post" action="<?php echo Config::get('URL');?>cashTransaction/updateCashCategory/">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Edit Kategori</h4>
      </div>
      <div class="modal-body">
        
        

  <div class="form-group">
    <label for="exampleInputEmail1">Nama Kategori</label>
    <input type="hidden" class="form-control" name="name" id="name" value="" />
    <input type="text" class="form-control" name="category_name" id="namaKategori" value=""/>
  </div>
  <div class="form-group">
    <label for="exampleInputPassword1">Keterangan</label>
    <input type="text" name="note" id="keteranganKategori" class="form-control" value=""/>
  </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
  </form>
</div>
