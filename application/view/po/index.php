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
            <tr>
              <th class="text-right">No</th>
              <th class="text-left">Tgl Transaksi</th>
              <th class="text-left">#PO</th>
              <th class="text-left">Customer</th>
              <th class="text-left">Item</th>
              <th class="text-right">Jumlah</th>
              <?php if (Auth::isPermissioned('director,finance,purchasing')) { ?>
              <th class="text-right">Harga</th>
              <th class="text-right">PPN</th>
              <th class="text-left">Total</th>
              <?php } ?>        
              <th class="text-left">Action</th>   
            </tr>
            </thead>

            <tbody>
            <?php
            $no = ($this->page * $this->limit) - ($this->limit - 1);
            foreach($this->po_list as $key => $value) {
                $total_item = explode('-, -', $value->material_name);
                $total_quantity = explode('-, -', $value->quantity);
                $total_price = explode('-, -', $value->purchase_price);
                $total_tax = explode('-, -', $value->purchase_tax);
                $row = count($total_item);
                if ($value->feedback_note != '') {
                  $feedbackStatus = "warning";
                  $feedbackNote = "<blockquote>{$value->feedback_note}</blockquote>";
                } else {
                  $feedbackStatus = "";
                  $feedbackNote = "";
                }
                if ($row > 1) {
                    for ($i=1; $i <= $row; $i++) {
                        $x = $i - 1;
                        if ($i === 1) {
                            echo '<tr class="' . 'row-' . $no . ' ' . $feedbackStatus . '">';

                            echo '<td class="text-right" rowspan="' . $row . '">' . $no . '</td>';
                            echo '<td rowspan="' . $row . '">' . date("d M, y", strtotime($value->created_timestamp)) . '</td>';
                            echo '<td  rowspan="' . $row . '">
                                <a href="' .  Config::get('URL') . 'po/detail/?po_number=' . urlencode($value->transaction_number) . '">' . $value->transaction_number . '</a><br>'. $feedbackNote.'</td>';
                            echo '<td rowspan="' . $row . '">' . $value->contact_name . '</td>';
                            echo '<td>' . $total_item[$x] . '</td>';
                            echo '<td class="text-right">' . number_format($total_quantity[$x], 2) . '</td>';
                            if (Auth::isPermissioned('director,finance,purchasing')) {
                              echo '<td class="text-right">' . number_format($total_price[$x], 2) . '</td>';
                              echo '<td class="text-right">' . number_format($total_tax[$x], 2) . '</td>';
                              $sub_total = ($total_quantity[$x] * $total_price[$x]);
                              $sub_total_with_ppn = $sub_total + (($total_tax[$x]/100) * $sub_total);
                              echo '<td class="text-right">' . number_format($sub_total_with_ppn, 2) . '</td>';
                            }
                            if ($value->status < 0) { // not showed if already approved, void or close
                              echo '<td class="text-right" rowspan="' . $row . '"> 
                              <a href="' .  Config::get('URL') . 'po/deleteTransaction/?po_number=' . urlencode($value->transaction_number) . '&forward=' . $_SERVER['REQUEST_URI'] . ' " onclick="return confirmation(\'Are you sure to delete?\');"><span class="badge badge-danger"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span> delete</span></a>
                              </td>';
                            } else {
                              echo '<td></td>';
                            }
                            echo "</tr>";
                        } else {
                            echo '<tr class="' . 'row-' . $no . ' ' . $feedbackStatus . '">';
                            echo '<td>' . $total_item[$x] . '</td>';
                            echo '<td class="text-right">' . number_format($total_quantity[$x], 2) . '</td>';
                            if (Auth::isPermissioned('director,finance,purchasing')) {
                              echo '<td class="text-right">' . number_format($total_price[$x], 2) . '</td>';
                              echo '<td class="text-right">' . number_format($total_tax[$x], 2) . '</td>';
                              $sub_total = ($total_quantity[$x] * $total_price[$x]);
                              $sub_total_with_ppn = $sub_total + (($total_tax[$x]/100) * $sub_total);
                              echo '<td class="text-right">' . number_format($sub_total_with_ppn, 2) . '</td>';
                            }
                            echo "</tr>";
                        }
                        
                    } //end for
                    
                } else {
                    echo '<tr class="' . 'row-' . $no . ' ' . $feedbackStatus . '">';
                    echo '<td class="text-right">' . $no . '</td>';
                    echo '<td>' . date("d M, y", strtotime($value->created_timestamp)) . '</td>';
                    echo '<td>
                                <a href="' .  Config::get('URL') . 'po/detail/?po_number=' . urlencode($value->transaction_number) . '">' . $value->transaction_number . '</a><br>'. $feedbackNote.'</td>';
                    echo '<td>' . $value->contact_name . '</td>';
                    echo '<td>' . $value->material_name . '</td>';
                    echo '<td class="text-right">' . number_format($value->quantity) . '</td>';
                    if (Auth::isPermissioned('director,finance,purchasing')) {
                      echo '<td class="text-right">' . number_format($value->purchase_price) . '</td>';
                      echo '<td class="text-right">' . number_format($value->purchase_tax) . '</td>';
                      $sub_total = ($value->quantity * $value->purchase_price);
                      $sub_total_with_ppn = $sub_total + (($value->purchase_tax/100) * $sub_total);
                      echo '<td class="text-right">' . number_format($sub_total_with_ppn, 2) . '</td>';
                    }
                    if ($value->status < 0) { // not showed if already approved, void or close
                      echo '<td class="text-right"> 
                        <a href="' .  Config::get('URL') . 'po/deleteTransaction/?po_number=' . urlencode($value->transaction_number) . '&forward=' . $_SERVER['REQUEST_URI'] . ' " onclick="return confirmation(\'Are you sure to delete?\');"><span class="badge badge-danger"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span> delete</span></a>
                        <br>
                      </td>';
                    } else {
                      echo '<td></td>';
                    }
                    echo "</tr>";
                } //end if
                
                $no++;
            }
            ?>
            </tbody>
            </table>
          </div>

				<div class="hr hr10 hr-double"></div>
				
        <ul class="pagination pull-right">
          <li>
            <a href="<?php echo $this->prev;?>">
              <i class="ace-icon fa fa-angle-double-left"></i> Prev
            </a>
          </li>

          <li class="active">
            <a href="#"><?php echo $this->page;?></a>
          </li>

          <li>
            <a href="<?php echo $this->next;?>">
              Next <i class="ace-icon fa fa-angle-double-right"></i>
            </a>
          </li>
        </ul>
                    
        
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