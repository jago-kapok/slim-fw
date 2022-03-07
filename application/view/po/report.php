<div class="main-content">
        <div class="main-content-inner">
        <div class="breadcrumbs" id="breadcrumbs">
          <script type="text/javascript">
            try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
          </script>

          <ul class="breadcrumb">
          
            <li>
              <a role="button" data-toggle="collapse" href="#changeDateRange" aria-expanded="false" aria-controls="changeDateRange">
                <span class="badge badge-info">
                  <i class="glyphicon glyphicon-time"></i> Ganti Tanggal
                </span>
              </a>

              &nbsp;
              <a href="<?php echo Config::get('URL') . 'ExportExcel/allMaterial/'; ?>">
                <span class="badge badge-info">
                <i class="glyphicon glyphicon-arrow-down"></i> Export Excel
                </span>
              </a>
            </li>
          
          </ul><!-- /.breadcrumb -->

          <!-- #section:basics/content.searchbox -->
          <div class="nav-search" id="nav-search">
          </div><!-- /.nav-search -->

          <!-- /section:basics/content.searchbox -->
        </div>

        <div class="collapse" id="changeDateRange">
          <div class="well">
            <form method="post" action="<?php echo Config::get('URL') . 'po/report/'; ?>">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="panel-title">Ganti Tanggal Report </h3>
                        </div>
                        <div class="panel-body">
                          <div class="row">

                              <div class="col-xs-12 col-sm-6">
                                  <div class="form-group">
                                      <label for="jumlah-pinjaman">Dari Tanggal/Start Date</label>
                                      <input type="text" name="start_date" class="form-control datepicker" value="<?php echo date("Y-m-d");?>" data-date-format="yyyy-mm-dd">
                                  </div>
                              </div><!-- /.col -->

                              <div class="col-xs-12 col-sm-6">
                                  <div class="form-group">
                                      <label for="jenis-pinjaman">Sampai Tanggal/End Date</label>
                                      <input type="text" name="end_date" class="form-control datepicker" value="<?php echo date("Y-m-d");?>" data-date-format="yyyy-mm-dd">
                                  </div>
                              </div><!-- /.col -->

                          </div><!-- /.row -->
                          <input type="hidden" name="change_date" value="ok">
                    
                        </div>
                        <div class="panel-footer">
                            <p align="right">
                            <a role="button" class="btn btn-danger" data-toggle="collapse" href="#changeDateRange" aria-expanded="false" aria-controls="changeDateRange">
                                    Cancel
                                </a>

                                &nbsp; &nbsp; &nbsp;

                                <button class="btn" type="reset">
                                    Reset
                                </button>

                                &nbsp; &nbsp; &nbsp;
                                <button class="btn btn-primary" type="submit">
                                    Change Date
                                </button>
                            </p>
                        </div>
                    </div>
        </form>
          </div>
        </div>


          <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover ExcelTable2007">
            <thead>
            <tr>
              <th class="text-right">No</th>
              <th class="text-left">Tgl Transaksi</th>
              <th class="text-left">#PO</th>
              <th class="text-left">Supplier</th>
              <th class="text-left">Item</th>
              <th class="text-right">Jumlah</th>
              <?php if (Auth::isPermissioned('director,finance,purchasing')) { ?>
              <th class="text-right">Harga</th>
              <th class="text-right">PPN</th>
              <th class="text-left">Sub</th>
              <th class="text-left">Total</th>
              <?php } ?>        
            </tr>
            </thead>

            <tbody>
            <?php
            //Debuger::jam($this->po_list);
            $no = 1;
            $total = 0;
            foreach($this->po_list as $key => $value) {
                $total_item = explode('-, -', $value->material_name);
                $total_quantity = explode('-, -', $value->quantity);
                $total_price = explode('-, -', $value->purchase_price);
                $total_tax = explode('-, -', $value->purchase_tax);
                $row = count($total_item);
                if ($row > 1) {
                    for ($i=1; $i <= $row; $i++) {
                        $x = $i - 1;
                        if ($i === 1) {

                            echo '<tr class="' . 'row-' . $no . '">';
                            echo '<td class="text-right" rowspan="' . $row . '">' . $no . '</td>';
                            echo '<td rowspan="' . $row . '">' . date("d M, y", strtotime($value->created_timestamp)) . '</td>';
                            echo '<td  rowspan="' . $row . '">
                                <a href="' .  Config::get('URL') . 'po/detail/?po_number=' . urlencode($value->transaction_number) . '">' . $value->transaction_number . '</a></td>';
                            echo '<td rowspan="' . $row . '">' . $value->contact_name . '</td>';
                            echo '<td>' . $total_item[$x] . '</td>';
                            echo '<td class="text-right">' . number_format($total_quantity[$x], 2) . '</td>';
                            if (Auth::isPermissioned('director,finance,purchasing')) {
                              echo '<td class="text-right">' . number_format($total_price[$x], 2) . '</td>';
                              echo '<td class="text-right">' . number_format($total_tax[$x], 0) . '</td>';
                              $sub_total = ($total_quantity[$x] * $total_price[$x]);
                              $sub_total_with_ppn = $sub_total + (($total_tax[$x]/100) * $sub_total);
                              $total = $total + $sub_total_with_ppn;
                              echo '<td class="text-right">' . number_format($sub_total_with_ppn, 2) . '</td>';
                              echo '<td class="text-right">' . number_format($total, 2) . '</td>';
                            }
                            echo "</tr>";
                        } else {
                            echo '<tr class="' . 'row-' . $no . '">';
                            echo '<td>' . $total_item[$x] . '</td>';
                            echo '<td class="text-right">' . number_format($total_quantity[$x], 2) . '</td>';
                            if (Auth::isPermissioned('director,finance,purchasing')) {
                              echo '<td class="text-right">' . number_format($total_price[$x], 2) . '</td>';
                              echo '<td class="text-right">' . number_format($total_tax[$x], 0) . '</td>';
                              $sub_total = ($total_quantity[$x] * $total_price[$x]);
                              $sub_total_with_ppn = $sub_total + (($total_tax[$x]/100) * $sub_total);
                              $total = $total + $sub_total_with_ppn;
                              echo '<td class="text-right">' . number_format($sub_total_with_ppn, 2) . '</td>';
                              echo '<td class="text-right">' . number_format($total, 2) . '</td>';
                            }
                            echo "</tr>";
                        }
                        
                    } //end for
                    
                } else {
                    echo '<tr class="' . 'row-' . $no . '">';
                    echo '<td class="text-right">' . $no . '</td>';
                    echo '<td>' . date("d M, y", strtotime($value->created_timestamp)) . '</td>';
                    echo '<td><a href="' .  Config::get('URL') . 'po/detail/?po_number=' . urlencode($value->transaction_number) . '">' . $value->transaction_number . '</a></td>';
                    echo '<td>' . $value->contact_name . '</td>';
                    echo '<td>' . $value->material_name . '</td>';
                    echo '<td class="text-right">' . number_format(floatval($value->quantity), 2) . '</td>';
                    if (Auth::isPermissioned('director,finance,purchasing')) {
                      echo '<td class="text-right">' . number_format(floatval($value->purchase_price), 2) . '</td>';
                      echo '<td class="text-right">' . number_format(floatval($value->purchase_tax), 0) . '</td>';
                      $sub_total = (floatval($value->quantity) * floatval($value->purchase_price));
                      $sub_total_with_ppn = $sub_total + ((floatval($value->purchase_tax)/100) * $sub_total);
                      $total = $total + $sub_total_with_ppn;
                      echo '<td class="text-right">' . number_format($sub_total_with_ppn, 2) . '</td>';
                      echo '<td class="text-right">' . number_format($total, 2) . '</td>';
                    }
                    echo "</tr>";
                } //end if
                
                $no++;
            }
            ?>
            </tbody>
            </table>
          </div>                            
  </div><!-- /.main-content-inner -->
</div><!-- /.main-content -->