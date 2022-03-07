<div class="main-content">
                    <div class="breadcrumbs ace-save-state" id="breadcrumbs">
                        <ul class="breadcrumb">
                            <li>
                                <a href="#new-pr" role="button" data-toggle="modal">
                                  <span class="badge badge-info">
                                  <i class="glyphicon glyphicon-plus"></i> New</span>
                                </a>
                            </li>
                        </ul><!-- /.breadcrumb -->

                        <div class="nav-search" id="nav-search">
                            <form class="form-search" method="get" action="<?php echo Config::get('URL') . 'po/index/find/';?>">
                              <span class="input-icon">
                                <input type="text" placeholder="Search ..." class="nav-search-input" id="nav-search-input" autocomplete="off" name="find" value="<?php if(isset($_GET['find'])){ echo $_GET['find'];}?>" />
                                <i class="ace-icon fa fa-search nav-search-icon"></i>
                              </span>
                            </form>
                        </div><!-- /.nav-search -->
                    </div>



                    


                
          <div class="table-responsive">
					<table class="table table-striped table-bordered table-hover ExcelTable2007">
					<thead>
					<tr class="heading">
					<th>#</th>
          <th class="center">Tanggal</th>
					<th class="center" >#Pembelian</th>
					<th class="center">Supplier</th>
					<th class="center">PIC</th>
          <th class="center">Delete</th>
          
					</thead>
					<tbody>
					<?php
					$no = $this->number;
					foreach($this->po_list as $key => $value) {
					echo "<tr>";
          echo '<td class="heading">' . $no . '</td>';
          echo '<td>' .  date("d M, y", strtotime($value->created_timestamp)) . '</td>';
          if ($value->purchase_channel == 'PO') {
            echo '<td><a href="' . Config::get('URL') . 'po/detail/?po_number=' . $value->transaction_number . '">' .  urlencode($value->transaction_number) . '</a></td>';
          } else {
            echo '<td><a href="' . Config::get('URL') . 'po/kasirReport/?po_number=' . urlencode($value->transaction_number) . '">' .  $value->transaction_number . '</a></td>';
          }
					
					
					echo '<td>' .  $value->supplier_id  . ' - ' . $value->supplier_name . '</td>';
					echo '<td>' . $value->user_name . '</td>';
          echo '<td><a href="' . Config::get('URL') . 'po/deletePurchaseCode/?po_number=' . urlencode($value->transaction_number) . '&forward=' . $_SERVER['REQUEST_URI'] . '" onclick="return confirmation(\'Are you sure to delete?\');" class="btn btn-danger btn-xs" >Delete</a></td>';
					$no++;
					}
					?>
					</tbody>
					</table>
					</div><!-- /.table-responsive -->

				<div class="hr hr10 hr-double"></div>
				<ul class="pager pull-right">
				<?php if($this->number != 1) { ?>
						<li class="previous">
							<a href="<?php echo $this->prev;?>">← Prev</a>
						</li>
				<?php } ?>
					<li class="next">
						<a href="<?php echo $this->next;?>">Next →</a>
					</li>
				</ul>
                    
        
      <!-- PAGE CONTENT ENDS -->
      </div><!-- /.main-content -->


      <!-- MODAL NEW PR -->
<div class="modal fade" id="new-pr" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
    <form method="post" class="form-horizontal" action="<?php echo Config::get('URL') . 'po/submitPr/';?>">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Buat Kontak Baru</h4>
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