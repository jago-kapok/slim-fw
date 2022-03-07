<form class="form-horizontal" action="<?php echo Config::get('URL') . 'po/uploadImage/'; ?>" method="post" enctype="multipart/form-data">
    <div class="form-group">
      <label class="col-sm-2 control-label">Nama Photo/Scan</label>
      <div class="col-sm-10">
        <input type="text" name="image_name" class="form-control" placeholder="Nama photo atau hasil scan yang akan diupload">
      </div>
    </div>
    <div class="form-group">
      <label class="col-sm-2 control-label">Pilih Photo/Scan</label>
      <div class="col-sm-10">
        <input type="file" class="form-control" name="file_name" required />
        <span id="helpBlock" class="help-block">Hanya file tipe JPG, JPEG dan PNG yang diijinkan, ukuran maksimum 3MB dan minimum dimensi 100 x 100 pixel</span>
      </div>
    </div>
    <div class="form-group">
      <label class="col-sm-2 control-label">Keterangan Berita Acara</label>
      <div class="col-sm-10">
        <textarea name="note" class="form-control" rows="3"></textarea>
        <input type="hidden" name="po_number" value="<?php echo $this->pr_data->transaction_number;?>">
      </div>
    </div>
    <div class="form-group">
      <div class="col-sm-offset-2 col-sm-10">
        <button type="submit" class="btn btn-info">Upload Photo/Scan</button>
      </div>
    </div>
</form>