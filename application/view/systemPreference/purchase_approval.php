<div class="main-content">
                <div class="main-content-inner">
                    <div class="breadcrumbs ace-save-state" id="breadcrumbs">
                        
                    </div>

                    <div class="page-content">
                        <div class="row">
                            <div class="col-xs-12">
                                <!-- PAGE CONTENT BEGINS -->
                                <?php $this->renderFeedbackMessages();?>
                                <h3>SETUP PROFIL PERUSAHAAN</h3>
<div class="hr hr10 hr-double"></div>
<form method="post" class="form-horizontal"  action="<?php echo Config::get('URL') . 'systemPreference/purchaseApprovalAction/'; ?>">
    <div class="form-group">
      <label for="inputEmail3" class="col-sm-2 control-label">Limit Approval</label>
      <div class="col-sm-10">
        <input type="text" class="form-control" name="limit_approval" value="<?php echo number_format($this->company[0]->value); ?>">
      </div>
    </div>

    <div class="row">
        <div class="col-xs-6">
          <h4>Users Yang Melakukan Approve DIBAWAH Nilai Limit</h4>
          <div class="form-group">
            <div class="col-sm-12">
              <?php
                  $authorized_users_under_limit = array();
                  foreach ($this->authorized_users_under_limit as $authorized_user) {
                          $authorized_users_under_limit[] = $authorized_user->value;
                  }

                  $num = 0;
                  foreach ($this->users as $user => $value) {
                    if (in_array($value->uid, $authorized_users_under_limit)) {
                        echo '<div class="checkbox"><label>
                            <input name="under_limit[' . $num . ']' . '" type="checkbox" value="' . $value->uid . '" class="ace" checked="checked">
                          <span class="lbl"> ' . ucwords($value->full_name) .'</span></label>
                          </div>';
                    } else {
                        echo '<div class="checkbox"><label>
                          <input name="under_limit[' . $num . ']' . '" type="checkbox" value="' . $value->uid . '" class="ace">
                        <span class="lbl"> ' . ucwords($value->full_name) .'</span></label>
                        </div>';
                    }
                            
                    $num++;
                  }
              ?>
            </div>
          </div>
        </div>

        <div class="col-xs-6">
          <h4>Users Yang Melakukan Approve DIATAS Nilai Limit</h4>
          <div class="form-group">
              <div class="col-sm-12">
                <?php
                    $authorized_users_above_limit = array();
                    foreach ($this->authorized_users_above_limit as $authorized_user) {
                            $authorized_users_above_limit[] = $authorized_user->value;
                    }

                    $num = 0;
                    foreach ($this->users as $user => $value) {
                      if (in_array($value->uid, $authorized_users_above_limit)) {
                          echo '<div class="checkbox"><label>
                              <input name="above_limit[' . $num . ']' . '" type="checkbox" value="' . $value->uid . '" class="ace" checked="checked">
                            <span class="lbl"> ' . ucwords($value->full_name) .'</span></label>
                            </div>';
                      } else {
                          echo '<div class="checkbox"><label>
                            <input name="above_limit[' . $num . ']' . '" type="checkbox" value="' . $value->uid . '" class="ace">
                          <span class="lbl"> ' . ucwords($value->full_name) .'</span></label>
                          </div>';
                      }
                              
                      $num++;
                    }
                ?>
              </div>
            </div>
        </div>
    </div>

  <div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
      <button type="submit" class="btn btn-primary">Save</button>
    </div>
  </div>
</form>


<!-- PAGE CONTENT ENDS -->
              </div><!-- /.col -->
            </div><!-- /.row -->
          </div><!-- /.page-content -->
        </div>
      </div><!-- /.main-content -->