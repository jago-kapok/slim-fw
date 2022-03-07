<div class="main-content">
        <div class="main-content-inner">
        <!-- #section:basics/content.breadcrumbs -->
        <div class="breadcrumbs" id="breadcrumbs">
          <script type="text/javascript">
            try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
          </script>

  

          <!-- #section:basics/content.searchbox -->
          <div class="nav-search" id="nav-search">
              <form class="form-search" method="get" action="<?php echo Config::get('URL') . 'so/index/';?>">
                <span class="input-icon">
                  <input type="text" placeholder="Search ..." class="nav-search-input" id="nav-search-input" autocomplete="off" name="find" value="<?php if(isset($_GET['find'])){ echo $_GET['find'];}?>" />
                  <i class="ace-icon fa fa-search nav-search-icon"></i>
                </span>
              </form>
          </div><!-- /.nav-search -->
        </div>

        <!-- /section:basics/content.breadcrumbs -->
<?php $this->renderFeedbackMessages();?>
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover ExcelTable2007">
            <thead>
            <tr>
              <th class="text-right">No</th>
              <th class="text-left">Tgl Transaksi</th>
              <th class="text-left">SO</th>
              <th class="text-left">Customer</th>
              <th class="text-left">Item</th>
              <th class="text-right">Jumlah</th>
              <?php if (Auth::isPermissioned('director,management,finance,sales')) { ?>
              <th class="text-right">Harga</th>
              <th class="text-right">PPN</th>
              <th class="text-right">PPh</th>
              <th class="text-left">Total</th>
              <?php } ?>         
              <th class="text-left">Sales</th>
              <th class="text-left">Action</th>   
            </tr>
            </thead>

            <tbody>
            <?php
            $no = ($this->page * $this->limit) - ($this->limit - 1);
            foreach($this->transaction_group as $key => $value) {
                $total_item = explode('-, -', $value->material_name);
                $total_quantity = explode('-, -', $value->quantity);
                $total_price = explode('-, -', $value->selling_price);
                $total_tax_ppn = explode('-, -', $value->tax_ppn);
                $total_tax_pph = explode('-, -', $value->tax_pph);
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
                                <a href="' .  Config::get('URL') . 'so/detail/?so_number=' . urlencode($value->transaction_number) . '">' . $value->transaction_number . '</a><br>'. $feedbackNote.'</td>';
                            echo '<td rowspan="' . $row . '">' . $value->contact_name . '</td>';
                            echo '<td>' . $total_item[$x] . '</td>';
                            echo '<td class="text-right">' . number_format($total_quantity[$x],0) . '</td>';
                            if (Auth::isPermissioned('director,management,finance,sales')) {
                              echo '<td class="text-right">' . number_format($total_price[$x],0) . '</td>';
                              echo '<td class="text-right">' . number_format($total_tax_ppn[$x],0) . '</td>';
                              echo '<td class="text-right">' . number_format($total_tax_pph[$x],0) . '</td>';
                              $sub_total = ($total_quantity[$x] * $total_price[$x]);
                              $sub_total_with_ppn = $sub_total + (($total_tax_ppn[$x]/100) * $sub_total);
                              $sub_total_with_ppn_pph = $sub_total_with_ppn + (($total_tax_pph[$x]/100) * $sub_total);
                              echo '<td class="text-right">' . number_format($sub_total_with_ppn_pph,0) . '</td>';
                            }
                            echo '<td rowspan="' . $row . '">' . $value->full_name . '</td>';
                          if (Auth::isPermissioned('director,management')) {
                            echo '<td class="text-right" rowspan="' . $row . '"> 
                                              <a href="' .  Config::get('URL') . 'so/deleteSo/?so_number=' . urlencode($value->transaction_number) . '&forward=' . $_SERVER['REQUEST_URI'] . ' " onclick="return confirmation(\'Are you sure to delete?\');"><span class="badge badge-danger"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span> delete</span></a><br>
                                              
                                              <a href="#" onclick="approveSo(\'' . $value->transaction_number . '\', \'' . $value->delivery_request_date . '\', \'' . 'row-' . $no . '\');"><span class="badge badge-info"><span class="glyphicon glyphicon-ok" aria-hidden="true"></span> Approve</span></a>
                              </td>';
                          }
                            echo "</tr>";
                        } else {
                            echo '<tr class="' . 'row-' . $no . ' ' . $feedbackStatus . '">';
                            echo '<td>' . $total_item[$x] . '</td>';
                            echo '<td class="text-right">' . number_format($total_quantity[$x],0) . '</td>';
                            if (Auth::isPermissioned('director,management,finance,sales')) {
                              echo '<td class="text-right">' . number_format($total_price[$x],0) . '</td>';
                              echo '<td class="text-right">' . number_format($total_tax_ppn[$x],0) . '</td>';
                              echo '<td class="text-right">' . number_format($total_tax_pph[$x],0) . '</td>';
                              $sub_total = ($total_quantity[$x] * $total_price[$x]);
                              $sub_total_with_ppn = $sub_total + (($total_tax_ppn[$x]/100) * $sub_total);
                              $sub_total_with_ppn_pph = $sub_total_with_ppn + (($total_tax_pph[$x]/100) * $sub_total);
                              echo '<td class="text-right">' . number_format($sub_total_with_ppn_pph,0) . '</td>';
                            }
                            echo "</tr>";
                        }
                        
                    } //end for
                    
                } else {
                    echo '<tr class="' . 'row-' . $no . ' ' . $feedbackStatus . '">';
                    echo '<td class="text-right">' . $no . '</td>';
                    echo '<td>' . date("d M, y", strtotime($value->created_timestamp)) . '</td>';
                    echo '<td>
                                <a href="' .  Config::get('URL') . 'so/detail/?so_number=' . urlencode($value->transaction_number) . '">' . $value->transaction_number . '</a><br>'. $feedbackNote.'</td>';
                    echo '<td>' . $value->contact_name . '</td>';
                    echo '<td>' . $value->material_name . '</td>';
                    echo '<td class="text-right">' . number_format($value->quantity) . '</td>';
                    if (Auth::isPermissioned('director,management,finance,sales')) {
                      echo '<td class="text-right">' . number_format($value->selling_price) . '</td>';
                      echo '<td class="text-right">' . number_format($value->tax_ppn) . '</td>';
                      echo '<td class="text-right">' . number_format($value->tax_pph) . '</td>';
                      $sub_total = ($value->quantity * $value->selling_price);
                      $sub_total_with_ppn = $sub_total + (($value->tax_ppn/100) * $sub_total);
                      $sub_total_with_ppn_pph = $sub_total_with_ppn + (($value->tax_pph/100) * $sub_total);
                      echo '<td class="text-right">' . number_format($sub_total_with_ppn_pph,0) . '</td>';
                    }
                    echo '<td>' . $value->full_name . '</td>';
                  if (Auth::isPermissioned('director,management')) {
                    echo '<td class="text-right"> 
                                              <a href="' .  Config::get('URL') . 'so/deleteSo/?so_number=' . urlencode($value->transaction_number) . '&forward=' . $_SERVER['REQUEST_URI'] . ' " onclick="return confirmation(\'Are you sure to delete?\');"><span class="badge badge-danger"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span> delete</span></a>
                                              <br>
                                              <a href="#" onclick="approveSo(\'' . $value->transaction_number . '\', \'' . $value->delivery_request_date . '\', \'' . 'row-' . $no . '\');"><span class="badge badge-info"><span class="glyphicon glyphicon-ok" aria-hidden="true"></span> Approve</span></a>
                    </td>';
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
    
    <?php echo $this->pagination;?>

  </div><!-- /.main-content-inner -->
</div><!-- /.main-content -->