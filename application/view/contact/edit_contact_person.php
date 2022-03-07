<div class="main-content">
        <!-- /section:basics/content.breadcrumbs -->
        <div class="page-content">
          <div class="row">
            <div class="col-xs-12">
              <!-- PAGE CONTENT BEGINS -->

<div class="panel panel-info">
                <div class="panel-heading">
                    <h3 class="panel-title">Buat Pinjaman Baru</h3>
                </div>
      <form method="post" action="<?php echo Config::get('URL') . 'contact/updatecontactperson/' .  $this->contact_person->uid; ?>">
        <div class="table-responsive">
          <table class="table table-bordered table-hover table-striped ">
            <tr>
              <td>Salutation:</td>
              <td>
               <select name="salutation">
               <option value="">
                  Select
              </option>
               <option value="Bapak" <?php if(strtolower($this->contact_person->salutation) == strtolower('Bapak')) echo "selected=selected";?> >
                  Bapak
                </option>
                <option value="Ibu" <?php if(strtolower($this->contact_person->salutation) == strtolower('Ibu')) echo "selected=selected";?> >
                  Ibu
                </option>
                <option value="Tuan" <?php if(strtolower($this->contact_person->salutation) == strtolower('Tuan')) echo "selected=selected";?> >
                  Tuan
                </option>
                <option value="Nyonya" <?php if(strtolower($this->contact_person->salutation) == strtolower('Nyonya')) echo "selected=selected";?> >
                  Nyonya
                </option>
                <option value="Nona" <?php if(strtolower($this->contact_person->salutation) == strtolower('Nona')) echo "selected=selected";?> >
                  Nona
                </option>
              </select>
            </td>
            <td>Hubungan:</td>
              <td colspan="3"><input type="text" class="form-control" name="job_title" value="<?php echo $this->contact_person->job_title;?>"></td>
            </tr>
            
            <tr>
              <td>Nama:</td>
              <td colspan="3"><input type="text" class="form-control" name="first_name" value="<?php echo $this->contact_person->first_name;?>"></td>
         </tr>
         
       <tr>
        <td>Mobile:</td>
        <td><input type="text" class="form-control" name="mobile" value="<?php echo $this->contact_person->mobile;?>"></td>
        <td>Email:</td>
        <td><input type="text" class="form-control" name="email" value="<?php echo $this->contact_person->email;?>"></td>
        
      </tr>

      <tr>
        <td>Phone:</td>
        <td><input type="text" class="form-control" name="phone" value="<?php echo $this->contact_person->phone;?>"></td>
        <td>Phone EXT:</td>
        <td><input type="text" class="form-control" name="phone_ext" value="<?php echo $this->contact_person->phone_ext;?>"></td>
        
      </tr>

      <tr>
      <td>Fax:</td>
        <td><input type="text" class="form-control" name="fax" value="<?php echo $this->contact_person->fax;?>"></td>
        <td>Main Contact:</td>
            <td>
             <input type="checkbox" name="is_main" value="1" <?php if($this->contact_person->is_main == 1) echo "checked=checked";?> >
           </td>
        
      </tr>
      </table>
  </div>

  <div class="modal-footer">

    <a href="javascript: history.go(-1)" class="btn btn-sm btn-danger">
    <i class="ace-icon fa fa-times"></i>
    Cancel
    </a>

    <button class="btn btn-sm btn-primary">
      <i class="ace-icon fa fa-check"></i>
      Save
    </button>
  </div>
</form>
</div>
<!-- /EDIT CONTACT PERSON -->

