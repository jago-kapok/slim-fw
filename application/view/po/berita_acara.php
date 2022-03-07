<table class="table table-striped table-bordered table-hover">
  <thead>
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
          <td><a href="' .  Config::get('URL') . 'delete/soft/upload_list/uid/' . $value->uid . '/&forward=' . $_SERVER['REQUEST_URI'] . ' " class="btn btn-danger btn-minier" onclick="return confirmation(\'Are you sure to delete?\');"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span> delete</a></td>
          </tr>';
  } ?>
  </tbody>
</table>