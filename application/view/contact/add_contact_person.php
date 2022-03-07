<form method="post" action="<?php echo Config::get('URL') . 'contact/insertcontactperson/' . $this->contact->contact_id; ?>">

  <i class="ace-icon fa fa-users bigger-120"></i> <strong>Menambahkan Contact Person</strong>

  <table class="table table-bordered table-hover table-striped ">
    <tr>
      <td>Salutation:</td>
      <td>
        <select name="salutation">
          <option value="">Select</option>
          <option value="Bapak">Bapak</option>
          <option value="Ibu">Ibu</option>
          <option value="Tuan">Tuan</option>
          <option value="Nyonya">Nyonya</option>
          <option value="Nona">Nona</option>
        </select>
      </td>
      <td>Jabatan:</td>
      <td><input type="text" name="job_title" class="form-control"></td>
    </tr>
    <tr>
      <td>Nama:</td>
      <td colspan="3"><input type="text" name="first_name" class="form-control"></td>
    </tr>
    <tr>
      <td>Email:</td>
      <td><input type="text" name="email" class="form-control"></td>
      <td>Phone:</td>
      <td><input type="text" name="phone" class="form-control"></td>
    </tr>
    <tr>
      <td>Phone EXT:</td>
      <td><input type="text" name="phone_ext" class="form-control"></td>
      <td>Fax:</td>
      <td><input type="text" name="fax" class="form-control"></td>
    </tr>
    <tr>
      <td>Main Contact:</td>
      <td>
        <input type="checkbox" name="is_main" value="1">
      </td>
      <td>mobile:</td>
      <td><input type="text" name="mobile" class="form-control"></td>
    </tr>
  </table>

  <button class="btn btn-sm btn-primary" type="submit">
    <i class="ace-icon fa fa-check"></i>
    Save
  </button>
</form>