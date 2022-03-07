<form class="form-horizontal" action="<?php echo Config::get('URL') . 'po/uploadDocument/'; ?>" method="post" enctype="multipart/form-data">
    <div class="form-group">
      <label class="col-sm-2 control-label">Nama Document</label>
      <div class="col-sm-10">
        <input type="text" name="document_name" class="form-control" placeholder="Nama Dokumen yang akan diupload">
      </div>
    </div>
    <div class="form-group">
      <label class="col-sm-2 control-label">Pilih Document</label>
      <div class="col-sm-10">
        <input type="file" class="form-control" name="file_name" required />
        <span id="helpBlock" class="help-block">Hanya tipe dokumen yang diijinkan: PDF, Microsoft Word (doc, docx), Mircosoft Excel (xls, xlsx) dan Microsoft PowerPoint (ppt, pptx). Ukuran maksimum 3MB</span>
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
        <button type="submit" class="btn btn-info">Upload Document</button>
      </div>
    </div>
</form>