<div class="main-content">
  <div class="main-content-inner">
    <div class="breadcrumbs ace-save-state" id="breadcrumbs">
        <ul class="breadcrumb">
          <li>
            <a href="#new-pr" role="button" data-toggle="modal">
                <span class="badge badge-info">
                  <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> New
                </span>
              </a>
          </li>
        </ul><!-- /.breadcrumb -->

        <div class="nav-search" id="nav-search">
              <form class="form-search" method="get" action="<?php echo Config::get('URL') . 'po/index/';?>">
                <span class="input-icon">
                  <input type="text" placeholder="Search ..." class="nav-search-input" id="nav-search-input" autocomplete="off" name="find" value="<?php if(isset($_GET['find'])){ echo $_GET['find'];}?>" />
                  <i class="ace-icon fa fa-search nav-search-icon"></i>
                </span>
              </form>
        </div><!-- /.nav-search -->
    </div>

    <div class="page-content">
      <div class="tabbable">
        <ul class="nav nav-tabs" id="myTab">
          <?php
            echo '<li class="active">
                <a data-toggle="tab" href="#purchase-order">
                  <span class="badge badge-warning">' . count($this->po_list) . '</span>
                  New Purchase Order
                </a>
              </li>';
          
            echo '<li>
                <a data-toggle="tab" href="#edit-purchase-order">
                  <span class="badge badge-warning">' . count($this->change_po_list) . '</span>
                  Revisi Purchase Order
                </a>
              </li>';

            echo '<li>
                <a data-toggle="tab" href="#nota-pembelian">
                  <span class="badge badge-warning">' . count($this->nota_pembelian_list) . '</span>
                  Nota Pembelian
                </a>
              </li>';
          ?>
        </ul>

        <div class="tab-content">
          <div id="purchase-order" class="tab-pane fade in active">
            <?php
              include('waiting_approval_po.php');
            ?>
          </div>

          <div id="edit-purchase-order" class="tab-pane fade">
            <?php
              include('waiting_approval_edit_po.php');
            ?>
          </div>

          <div id="nota-pembelian" class="tab-pane fade">
            <?php
              include('waiting_approval_nota_pembelian.php');
            ?>
          </div>
        </div>
      </div>
      <?php echo $this->pagination;?>
    </div><!-- /.page-content -->
  </div><!-- /.main-content-inne -->
</div><!-- /.main-content -->





   
          
  
                    



<!-- MODAL NEW PR -->
<div class="modal fade" id="new-pr" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
    <form method="post" class="form-horizontal" action="<?php echo Config::get('URL') . 'Po/submitDraftPo/';?>">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Buat Purchase Request</h4>
      </div>
      <div class="modal-body">
          <div class="form-group">
              <label class="col-sm-3 control-label">Supplier Name</label>
              <div class="col-sm-9">
                <div class="input-group">
                    <input type="text" class="form-control search-query" placeholder="klik browse untuk memilih" name="contact_id" id="contact_id_new"/>
                  <span class="input-group-btn">
                  <a href="#" class="btn btn-purple btn-sm" onclick="SelectName1()">Browse</a>
                  <script type="text/javascript">
                  var popup;
                  function SelectName1() {
                  var left = (screen.width/2)-(500/2);
                  var top = (screen.height/2)-(500/2);
                  popup = window.open("<?php echo Config::get('URL') . 'contact/selectContact/?id=_new'; ?>", "Popup", "width=500, height=500, top="+top+", left="+left);
                  popup.focus();
                  }
                  </script>
                  </span>
                </div>
              </div>
          </div>

          <div class="form-group">
            <label class="col-sm-3 control-label">Due Date</label>
            <div class="col-sm-9">
              <input type="text" name="due_date" class="form-control datepicker" value="<?php echo date("Y-m-d");?>" data-date-format="yyyy-mm-dd">
            </div>
          </div>



          <div class="form-group">
            <label class="col-sm-3 control-label">Keterangan</label>
            <div class="col-sm-9">
              <textarea name="note" class="form-control" ></textarea>
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
<!-- /MODAL NEW PR -->