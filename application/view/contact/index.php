<div class="main-content">
    <div class="main-content-inner">
      <div class="breadcrumbs ace-save-state hidden-print" id="breadcrumbs">
          <ul class="breadcrumb">
              <li>
                  <a href="#new-contact" role="button" data-toggle="modal">
                    <span class="badge badge-info">
                    <i class="glyphicon glyphicon-plus"></i> Buat Kontak
                    </span>
                  </a>
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

<?php
 $this->renderFeedbackMessages();
// For create new contact, only showet if created new contact executed
 if(isset($_GET['new_contact'])){?>
             <form method="post" action="<?php echo Config::get('URL') . 'contact/insertcontact/';?>">
            <p align="center" style="margin-top: 21px;">
                  <input type="hidden" name="contact_name" value="<?php echo strtoupper($_GET['new_contact']);?>" >
                  <button type="submit" class="btn btn-primary" > Buat Kontak Baru: <?php echo strtoupper($_GET['new_contact']);?> </button>
            </p>
            </form>
      <h1>Nama kontak yang mirip dengan <?php echo strtoupper($_GET['new_contact']);?></h1>
<?php } ?>
          <div class="table-responsive">
          <table id="sample-table-1" class="table table-striped table-bordered table-hover ExcelTable2007">
          <thead>
          <tr>
          <th class="text-center">No</th>
          <th class="text-center">ID</th>
          <th class="text-center">Nama</th>
          <th class="text-center">Alamat</th>
          </tr>
          </thead>

          <tbody>
          <?php
          $no = ($this->page * $this->limit) - ($this->limit - 1);
          foreach($this->contact as $key => $value) {
          echo "<tr>";
          if (SESSION::get('user_account_type') > 55) {
            echo '<td class="heading">
          <div class="dropdown">
            <button class="btn btn-info btn-minier dropdown-toggle" type="button" id="cpMenu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              ' . $no . '
              <span class="caret"></span>
            </button>
            <ul class="dropdown-menu" aria-labelledby="cpMenu">

              <li><a href="'. Config::get('URL') . 'contact/deleteContact/' . $value->contact_id . '/?forward=' . $_SERVER['REQUEST_URI'] . ' " onclick="return confirmation(\'Are you sure to delete?\');">Delete</a></li>
            </ul>
          </div>

        </td>';
          } else { 
          echo '<td class="text-right heading">' . $no . '</td>';
          }
          echo '<td>' . $value->contact_id . '</td>';
          echo '<td><a href="' . Config::get('URL') . 'contact/detail/' . $value->contact_id . '/">' . ucwords($value->contact_name) . '</a></td>';
          

          echo '<td>' . $value->address . '</td>';
          echo "</tr>";
          $no++;
          }
          ?>
          </tbody>
          </table>
          </div><!-- /.table-responsive -->


        <?php echo $this->pagination;?>
        
</div><!-- /.main-content-inner -->
</div><!-- /.main-content -->

<!-- MODAL -->
<!-- NEW Contact-->
<div class="modal fade" id="new-contact" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
    <form method="get" class="form-horizontal" action="<?php echo Config::get('URL') . 'contact/index/';?>">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Buat Kontak Baru</h4>
      </div>
      <div class="modal-body">
          <div class="form-group">
            <label for="inputEmail3" class="col-sm-2 control-label">Nama Lengkap</label>
            <div class="col-sm-10">
              <input type="text" name="new_contact" class="form-control" id="inputEmail3" placeholder="nama kontak">
            </div>
          </div>
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