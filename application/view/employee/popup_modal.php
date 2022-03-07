<div id="editnotes" class="modal fade" tabindex="-1">
  <form method="post" action="<?php echo Config::get('URL') . 'customer/updatenote/' . $this->contact->user_name; ?>">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="blue bigger">Add Notes</h4>
        </div>

        <div class="modal-body overflow-visible">
          <div class="row">
            <div class="col-xs-12">
              <div class="form-group">
                <div>
                <textarea type="text" name="note" class="form-control" rows="3"></textarea>
                </div>
              </div>

            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button class="btn btn-sm btn-danger" data-dismiss="modal">
            Cancel
          </button>

          <button class="btn btn-sm btn-primary">
            Save Notes
          </button>
        </div>
      </div>
    </div>
  </form>
</div>
<!-- /ADD NOTES-->

<!-- START SALES INFO -->
<div id="salesinfo-modal" class="modal fade" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content modal-content-full-screen">
      <form method="post" action="<?php echo Config::get('URL') . 'customer/updatesalesinfo/' . $this->contact->user_name; ?>">
        <div class="modal-header no-padding">
          <div class="table-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
              <span class="white">&times;</span>
            </button>
            <i class="ace-icon fa fa-building-o"></i> Edit Sales Info <?php echo $this->contact->full_name; ?>
          </div>
        </div>

        <div class="modal-body no-padding table-responsive">
          <table class="table table-bordered table-hover table-striped ">
            <tr>
              <td><i class="icon-user"></i> Account Manager</td>
              <td>
                <input type="text" name="sales_account_manager" class="form-control" value="<?php echo $this->contact->sales_account_manager;?>">
              </td>
            </tr>

            <tr>
              <td><i class="icon-user"></i> Account Officer</td>
              <td>
                <input type="text" name="sales_account_executive" class="form-control" value="<?php echo $this->contact->sales_account_executive;?>">
              </td>
            </tr>

            <tr>
              <td>Lead Source</td>
              <td>
                <input type="text" name="sales_lead_source" class="form-control" value="<?php echo $this->contact->sales_lead_source;?>">
              </td>
            </tr>
    </table>
  </div>

  <div class="modal-footer">
    <button class="btn btn-sm btn-danger" data-dismiss="modal">
      <i class="ace-icon fa fa-times"></i>
      Cancel
    </button>

    <button class="btn btn-sm btn-primary">
      <i class="ace-icon fa fa-check"></i>
      Save Changes
    </button>
  </div>
</form>
</div>
</div>
</div>
<!--END SALES INFO-->

<!-- NEW CONTACT PERSON -->
<div id="add-contactperson-modal" class="modal modal-wide fade" tabindex="-1">
  <div class="modal-dialog modal-dialog-full-screen">
    <div class="modal-content modal-content-full-screen">
      <form method="post" action="<?php echo Config::get('URL') . 'customer/insertcontactperson/' . $this->contact->user_name; ?>">
        <div class="modal-header no-padding">
          <div class="table-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
              <span class="white">&times;</span>
            </button>
            Menambahkan contact person <?php echo $this->contact->full_name; ?>
          </div>
        </div>

        <div class="modal-body no-padding table-responsive">
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
            <td>Hubungan:</td>
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
  </div>

  <div class="modal-footer">
    <button class="btn btn-sm btn-danger" data-dismiss="modal">
      <i class="ace-icon fa fa-times"></i>
      Cancel
    </button>

    <button class="btn btn-sm btn-primary">
      <i class="ace-icon fa fa-check"></i>
      Save
    </button>
  </div>
</form>
</div>
</div>
</div>
<!-- /NEW CONTACT PERSON -->

<!-- NEW Nasabah-->
<div class="modal fade" id="new-contact" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
    <form method="get" class="form-horizontal" action="<?php echo Config::get('URL') . 'customer/index/';?>">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Buat Nasabah Baru</h4>
      </div>
      <div class="modal-body">
          <div class="form-group">
            <label for="inputEmail3" class="col-sm-2 control-label">Nama Lengkap</label>
            <div class="col-sm-10">
              <input type="text" name="find" class="form-control" id="inputEmail3" placeholder="nama nasabah">
            </div>
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-primary">Add Nasabah</button>
      </div>
      </form>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- /NEW CONTACT-->

<!-- NEW Rekening-->
<div class="modal fade" id="new-account" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
    <form method="post" class="form-horizontal" action="<?php echo Config::get('URL') . 'customer/newAccountNumber/' . $this->contact->user_name;?>">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Buat Rekening Baru</h4>
      </div>
      <div class="modal-body">
        <p>Apakah anda yakin akan membuat rekening baru untuk <?php echo $this->contact->full_name;?>?&hellip;</p>
        <input type="hidden" name="confirm" class="form-control" id="inputEmail3" value="yesNewAccountNumber">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-primary">OK</button>
      </div>
      </form>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- /NEW CONTACT-->