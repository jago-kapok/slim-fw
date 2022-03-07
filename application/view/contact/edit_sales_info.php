<form method="post" action="<?php echo Config::get('URL') . 'contact/updatesalesinfo/' . $this->contact->contact_id; ?>">
 
            <i class="ace-icon fa fa-shopping-bag bigger-120"> </i> <strong>Edit Sales Info <?php echo $this->contact->contact_name;?></strong>


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

    <button class="btn btn-sm btn-primary">
      <i class="ace-icon fa fa-check"></i>
      Save
    </button>
</form>