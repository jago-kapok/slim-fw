<table class="table table-striped table-bordered table-hover">
  <thead>
    <tr class="info">
      <th class="text-left" colspan="3">
        <div class="btn-group btn-corner">
          <a data-toggle="tab" href="#upload-image" class="btn btn-minier btn-inverse"><span class="glyphicon glyphicon-camera" aria-hidden="true"></span> Upload Photo/Scan</a>

          <a data-toggle="tab" href="#upload-document" class="btn btn-minier btn-success"><span class="glyphicon glyphicon-file" aria-hidden="true"></span> Upload Document</a>
        </div>
      </th>
    </tr>
    <tr>
      <th class="text-center">Nama</th>
      <th class="text-center">Keterangan</th>
      <th class="text-center">Delete</th>
    </tr>
  </thead>
  <tbody>
  <?php foreach ($this->uploaded_file as $key => $value) {
    echo '<tr>
          <td><a href="' .  Config::get('URL') . 'file/' . $value->value . '" target="_blank">' . $value->item_name . '</td>
          <td>' . $value->note . '</td>
          <td><a href="' .  Config::get('URL') . 'so/removeUploadFile/upload_list/uid/' . $value->uid . '/&so_number=' . $this->so->transaction_number . '" class="btn btn-danger btn-minier" onclick="return confirmation(\'Are you sure to delete?\');"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span> delete</a></td>
          </tr>';
  } ?>
  </tbody>
</table>