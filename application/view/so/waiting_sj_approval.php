<div class="main-content">
        <!-- #section:basics/content.breadcrumbs -->
        <div class="breadcrumbs" id="breadcrumbs">
          <script type="text/javascript">
            try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
          </script>

  

          <!-- #section:basics/content.searchbox -->
          <div class="nav-search" id="nav-search">
            <form class="form-search" method="get" action="<?php echo Config::get('URL') . 'pos/reportKasir/';?>">
              <span class="input-icon">
                <input type="text" name="find" placeholder="Search ..." class="nav-search-input" id="nav-search-input" autocomplete="off" />
                <i class="ace-icon fa fa-search nav-search-icon"></i>
              </span>
            </form>
          </div><!-- /.nav-search -->

          <!-- /section:basics/content.searchbox -->
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
              <th class="text-left">Serial Number</th>
              <th class="text-left">Sales</th>
            </tr>
            </thead>

            <tbody>
            <?php
            $no = 1;
            foreach($this->transaction_group as $key => $value) {
                $total_item = explode('-, -', $value->material_name);
                $total_quantity = explode('-, -', $value->quantity);
                $total_serial_number = explode('-, -', $value->serial_number);
                $row = count($total_item);
                if ($row > 1) {
                    for ($i=1; $i <= $row; $i++) {
                        $x = $i - 1;
                        if ($i === 1) {
                            echo '<tr>';

                            echo '<td class="text-right" rowspan="' . $row . '">' . $no . '</td>';
                            echo '<td rowspan="' . $row . '">' . date("d M, y", strtotime($value->created_timestamp)) . '</td>';
                            echo '<td  rowspan="' . $row . '"><a href="' .  Config::get('URL') . 'so/detail/?so_number=' . urlencode($value->transaction_number) . '">' . $value->transaction_number . '</a></td>';
                            echo '<td rowspan="' . $row . '">' . $value->contact_name . '</td>';
                            echo '<td>' . $total_item[$x] . '</td>';
                            echo '<td class="text-right">' . number_format($total_quantity[$x],0) . '</td>';
                            echo '<td class="text-right">' . $total_serial_number[$x] . '</td>';
                            echo '<td rowspan="' . $row . '">' . $value->full_name . '</td>';
                            echo "</tr>";
                        } else {
                            echo '<tr>';
                            echo '<td>' . $total_item[$x] . '</td>';
                            echo '<td class="text-right">' . number_format($total_quantity[$x],0) . '</td>';
                            echo '<td class="text-right">' . $total_serial_number[$x] . '</td>';
                            echo "</tr>";
                        }
                        
                    } //end for
                    
                } else {
                    echo '<tr>';
                    echo '<td class="text-right">' . $no . '</td>';
                    echo '<td>' . date("d M, y", strtotime($value->created_timestamp)) . '</td>';
                    echo '<td rowspan="' . $row . '"><a href="' .  Config::get('URL') . 'so/detail/?so_number=' . urlencode($value->transaction_number) . '">' . $value->transaction_number . '</a></td>';
                    echo '<td>' . $value->contact_name . '</td>';
                    echo '<td>' . $value->material_name . '</td>';
                    echo '<td class="text-right">' . number_format($value->quantity) . '</td>';
                    echo '<td class="text-right">' . $value->serial_number . '</td>';
                    echo '<td>' . $value->full_name . '</td>';
                    echo "</tr>";
                } //end if
                
                $no++;
            }
            ?>
            </tbody>
            </table>
          </div>
        </div>