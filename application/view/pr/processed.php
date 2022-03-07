<div class="main-content">
        <div class="main-content-inner">
          <div class="breadcrumbs ace-save-state" id="breadcrumbs">
              <ul class="breadcrumb">
                  <li>
                      <div class="btn-group btn-corner">
                          <a href="<?php echo Config::get('URL') . 'pr/newPr/'; ?>" role="button" data-toggle="modal" class="btn btn-info btn-minier">
                              <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Buat PR
                          </a>
                    </div>
                  </li>
              </ul><!-- /.breadcrumb -->

              <div class="nav-search" id="nav-search">
                  <form class="form-search" method="get" action="<?php echo Config::get('URL') . 'pr/processed/';?>">
                    <span class="input-icon">
                      <input type="text" placeholder="Search ..." class="nav-search-input" id="nav-search-input" autocomplete="off" name="find" value="<?php if(isset($_GET['find'])){ echo $_GET['find'];}?>" />
                      <i class="ace-icon fa fa-search nav-search-icon"></i>
                    </span>
                  </form>
              </div><!-- /.nav-search -->
          </div>

          <?php $this->renderFeedbackMessages(); // Render message success or not?>

          <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover ExcelTable2007">
            <thead>
            <tr>
              <th class="text-right">No</th>
              <th class="text-left">Material Code</th>
              <th class="text-left">Material Name</th>
              <th class="text-right">Quantity</th>
              <th class="text-left">Unit</th>
              <th class="text-left">Notes</th>
              <th class="text-left">Date</th>
              <th class="text-left">#PO/#Nota</th>
              <th class="text-left">User</th>
            </tr>
            </thead>

            <tbody>
            <?php
            $no = ($this->page * $this->limit) - ($this->limit - 1);
            foreach($this->po_list as $key => $value) {
              if (empty($value->material_code)) {
                echo '<tr>';
                echo '<td class="text-right">' . $no . '</td>';
                echo '<td></td>';
                echo '<td>' . $value->material_specification . '</td>';
                echo '<td class="text-right">' . floatval($value->quantity) .'</td>';
                echo '<td>' . $value->unit .'</td>';
                echo '<td><a href="' .  Config::get('URL') . 'production/detail/?production_number=' . urlencode($value->transaction_reference) . '">' . $value->transaction_reference . '</a> ' . $value->note . '</td>';
                echo '<td>' . date("d M, y", strtotime($value->created_timestamp)) . '</td>';

                if (strpos($value->transaction_number_created, '/PO-') !== false) {
                  echo '<td><a href="' .  Config::get('URL') . 'po/detail/?po_number=' . urlencode($value->transaction_number_created) . '">' . $value->transaction_number_created . '</a></td>';
                } else {
                  echo '<td>' . $value->transaction_number_created . '</td>';
                }

                echo '<td>' . $value->full_name . '</td>';
                echo "</tr>";
              } else {
                echo '<tr>';
                echo '<td class="text-right">' . $no . '</td>';
                echo '<td><a href="' . Config::get('URL') . 'inventory/editMaterial/?find=' . $value->material_code . '">' . $value->material_code . '</a></td>';
                echo '<td>' . $value->material_name . '</td>';
                echo '<td class="text-right">' . floatval($value->quantity) .'</td>';
                echo '<td>' . $value->unit .'</td>';
               echo '<td><a href="' .  Config::get('URL') . 'production/detail/?production_number=' . urlencode($value->transaction_reference) . '">' . $value->transaction_reference . '</a> ' . $value->note . '</td>';
              echo '<td>' . date("d M, y", strtotime($value->created_timestamp)) . '</td>';
              echo '<td><a href="' .  Config::get('URL') . 'po/detail/?po_number=' . urlencode($value->transaction_number_created) . '">' . $value->transaction_number_created . '</a></td>';
              echo '<td>' . $value->full_name . '</td>';
              echo "</tr>";
            }
            $no++;
          }?>
              
                
              
            </tbody>
            </table>
          </div>

          <?php echo $this->pagination;?>
  </div><!-- /.main-content-inner -->
</div><!-- /.main-content -->


<!-- MODAL NEW PR -->
<div class="modal fade" id="new-pr" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
    <form method="post" class="form-horizontal" action="<?php echo Config::get('URL') . 'po/submitPr/';?>">
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