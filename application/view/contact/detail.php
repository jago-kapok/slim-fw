<div class="main-content">
                <div class="main-content-inner">
                    <div class="breadcrumbs ace-save-state hidden-print" id="breadcrumbs">
                        <ul class="breadcrumb">
                            <li>
                                <a href="<?php echo Config::get('URL') . 'contact/index/'; ?>">
                                  <span class="glyphicon glyphicon-th-list" aria-hidden="true"></span>
                                </a>
                            </li>

                            <li>
                                <div class="btn-group btn-corner">
                                    <a role="button" data-toggle="collapse" href="#editContact" aria-expanded="false" aria-controls="collapseExample" class="btn btn-minier btn-inverse">
                                      <span class="glyphicon glyphicon-edit" aria-hidden="true" aria-label="edit"></span> Edit Contact
                                    </a>
                                </div>
                          </li>
                        </ul><!-- /.breadcrumb -->

                        <div class="nav-search" id="nav-search">
                            <form class="form-search" method="get" action="<?php echo Config::get('URL') . 'contact/find/';?>">
                              <span class="input-icon">
                                <input type="text" placeholder="Search ..." class="nav-search-input" id="nav-search-input" autocomplete="off" name="find" value="<?php if(isset($_GET['find'])){ echo $_GET['find'];}?>" />
                                <i class="ace-icon fa fa-search nav-search-icon"></i>
                              </span>
                            </form>
                        </div><!-- /.nav-search -->
                    </div>

                    <div class="page-content">
                        <div class="row">
                            <div class="col-xs-12">
                                <!-- PAGE CONTENT BEGINS -->

<div class="collapse" id="editContact">
  <div class="well">
    <form method="post" action="<?php echo Config::get('URL') . 'contact/updatecontactid/' . $this->contact->contact_id; ?>">
          <table class="table table-bordered table-hover table-striped ">
            <tr>
              <td><strong>Nama:</strong></td>
              <td colspan="3">
                <input type="text" name="contact_name" class="form-control" value="<?php echo $this->contact->contact_name;?>">
                </td>
            </tr>

            <tr>
              <td><strong>Alamat:</strong></td>
              <td colspan="3"><input type="text" class="form-control" name="address_street" value="<?php echo $this->contact->address_street;?>"></td>
            </tr>
        <tr>
        <tr>
              <td><strong>Kota:</strong></td>
              <td><input type="text" name="address_city" class="form-control" value="<?php echo $this->contact->address_city;?>"></td>

              <td><strong>Propinsi:</strong></td>
              <td><input type="text" name="address_state" class="form-control" value="<?php echo $this->contact->address_state;?>"></td>
         </tr>

         <tr>
         <td><strong>Zip:</strong></td>
         <td><input type="text" name="address_zip" class="form-control" value="<?php echo $this->contact->address_zip;?>"></td>

         <td><strong>Email:</strong></td>
        <td><input type="text" name="email" class="form-control" value="<?php echo $this->contact->website;?>"></td>
       </tr>
       <tr>
        <td><strong>Phone:</strong></td>
        <td><input type="text" name="phone" class="form-control" value="<?php echo $this->contact->phone; ?>"></td>
        <td><strong>Fax:</strong></td>
        <td><input type="text" name="fax" class="form-control" value="<?php echo $this->contact->fax; ?>"></td>
      </tr>
      <tr>
        <td><strong>Website:</strong></td>
        <td colspan="3"><input type="text" name="website" class="form-control" value="<?php echo $this->contact->website; ?>"></td>
      </tr>
    </table>

  <div class="modal-footer">
   

    <a class="btn btn-sm btn-danger" data-toggle="collapse" href="#editContact" aria-expanded="false" aria-controls="collapseExample" class="pull-right">
        <span class="glyphicon glyphicon-remove" aria-hidden="true" aria-label="edit"></span>
        Cancel
    </a>


    <button class="btn btn-sm btn-primary">
      <span class="glyphicon glyphicon-floppy-disk" aria-hidden="true" aria-label="save"></span>
      Save
    </button>
  </div>
</form>
  </div>
</div>

<div class="row">
    <div class="col-xs-12 col-sm-6">
    <?php $this->renderFeedbackMessages();?>
      <div class="panel panel-primary">
        <div class="panel-heading">
          <h3 class="panel-title"><?php echo strtoupper($this->contact->contact_id);?></h3>
        </div>
          <table class="table table-striped table-bordered table-hover">
          <tr>
              <td><strong>Nama:</strong></td>
              <td colspan="3"><?php echo strtoupper($this->contact->contact_name);?></td>
            </tr>
          
            <tr>
              <td><strong>Alamat:</strong></td>
              <td colspan="3"><?php echo $this->contact->address_street;?></td>
            </tr>
            <tr>
              <td><strong>Kota:</strong></td>
              <td><?php echo $this->contact->address_city;?></td>

              <td><strong>Propinsi:</strong></td>
              <td><?php echo $this->contact->address_state;?></td>
            </tr>
            <tr>
              <td><strong>Zip:</strong></td>
              <td><?php echo $this->contact->address_zip;?></td>

              <td><strong>Email:</strong></td>
              <td><?php echo $this->contact->email;?></td>
            </tr>
            <tr>
              <td><strong>Phone:</strong></td>
              <td><?php echo $this->contact->phone;?></td>

              <td><strong>Fax:</strong></td>
              <td><?php echo $this->contact->fax;?></td>
            </tr>
            <tr>
              <td><strong>Website:</strong></td>
              <td colspan="3"><a href="http://<?php echo $this->contact->website;?>/" target="_blank"><?php echo $this->contact->website;?></a></td>
            </tr>
          </table>
      </div>

    </div><!-- /.col-sm-6 -->
    <div class="col-sm-6">
      <!-- #section:elements.tab -->
      <div class="tabbable">
        <ul class="nav nav-tabs" id="myTab">
          <li class="active">
            <a data-toggle="tab" href="#home">
              <i class="green ace-icon fa fa-comments bigger-120"></i>
              Catatan
            </a>
          </li>

          <li>
            <a data-toggle="tab" href="#Logs">
              <i class="green ace-icon fa fa-undo bigger-120"></i>
              Logs
            </a>
          </li>

          <li class="dropdown">
            <a data-toggle="dropdown" class="dropdown-toggle" href="#">
              <i class="glyphicon glyphicon-plus text-danger"></i>
              &nbsp;
              <i class="ace-icon fa fa-caret-down bigger-110 width-auto"></i>
            </a>

            <ul class="dropdown-menu dropdown-info">
              <li>
                <a data-toggle="tab" href="#edit-notes">Edit Catatan</a>
              </li>
            </ul>
          </li>
        </ul>

        <div class="tab-content">
          <div id="home" class="tab-pane active" style="overflow-x: hidden; overflow-y: scroll; height:185px;">
            <ol reversed><?php echo $this->contact->note;?></ol>
          </div>

          <div id="Logs" class="tab-pane fade" style="overflow-x: hidden; overflow-y: scroll; height:185px;">
            <ol reversed><?php echo $this->contact->log;?></ol>
          </div>

          <div id="edit-notes" class="tab-pane fade" style="overflow-x: hidden; overflow-y: scroll; height:185px;">
              <form class="form-horizontal" method="post" action="<?php echo Config::get('URL') . 'contact/updatenote/' . $this->contact->contact_id; ?>">
                  <div class="form-group">
                    <div class="col-sm-12">
                      <textarea name="note" class="form-control" rows="3" placeholder="Ketik catatan atau keterangan disini."></textarea>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="col-sm-12">
                      <button type="submit" class="btn btn-info">Save</button>
                    </div>
                  </div>
              </form>
          </div>
        </div>
      </div>
      <!-- /section:elements.tab -->

    </div>
  </div><!-- ./row -->


<div class="tabbable">
  <ul class="nav nav-tabs" id="myTab">

    <li class="active">
      <a data-toggle="tab" href="#contact-person">
        <i class="green ace-icon fa fa-users bigger-120"></i>
        Contact Person
      </a>
    </li>

    <li>
      <a data-toggle="tab" href="#sales-info">
        <i class="green ace-icon fa fa-shopping-bag bigger-120"></i>
        Sales Info
      </a>
    </li>

    <li>
      <a data-toggle="tab" href="#purchased-item">
        <i class="green ace-icon fa fa-shopping-cart  bigger-120"></i>
        Daftar Pembelian
      </a>
    </li>

    <li>
      <a data-toggle="tab" href="#selling-item">
        <i class="green ace-icon fa fa-shopping-cart  bigger-120"></i>
        Daftar Penjualan
      </a>
    </li>

    <li class="dropdown">
      <a data-toggle="dropdown" class="dropdown-toggle" href="#">
        <i class="glyphicon glyphicon-plus text-danger"></i>
        &nbsp;
        <i class="ace-icon fa fa-caret-down bigger-110 width-auto"></i>
      </a>

      <ul class="dropdown-menu dropdown-info dropdown-menu-right">
        <li>
          <a data-toggle="tab" href="#dropdown1">Masukkan Contact Person</a>
        </li>

        <li>
          <a data-toggle="tab" href="#dropdown2">Edit Sales Info</a>
        </li>
      </ul>
    </li>
  </ul>

  <div class="tab-content">

    <div id="contact-person" class="tab-pane fade in active">
      <div class="table-responsive">
        <?php include("contact_person.php"); //Call Contact Person ?>
      </div>
    </div>

    <div id="sales-info" class="tab-pane fade">
      <?php include("sales_info.php"); ?>
    </div>

    <div id="purchased-item" class="tab-pane fade">
      <?php include("purchased_item.php"); ?>
    </div>

    <div id="selling-item" class="tab-pane fade">
      <?php include("selling_item.php"); ?>
    </div>

    <div id="dropdown1" class="tab-pane fade">
      <?php include("add_contact_person.php"); ?>
    </div>

    <div id="dropdown2" class="tab-pane fade">
      <?php include("edit_sales_info.php"); ?>
    </div>
    
  </div>
</div>

                <!-- PAGE CONTENT ENDS -->
              </div><!-- /.col -->
            </div><!-- /.row -->
          </div><!-- /.page-content -->
        </div><!-- /.main-content-inner -->
      </div><!-- /.main-content -->