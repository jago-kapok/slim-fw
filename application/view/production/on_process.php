<div class="main-content">
        <div class="main-content-inner">
        <!-- #section:basics/content.breadcrumbs -->
        <div class="breadcrumbs" id="breadcrumbs">
          <script type="text/javascript">
            try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
          </script>

  

          <!-- #section:basics/content.searchbox -->
          <div class="nav-search" id="nav-search">
            <form class="form-search" method="get" action="<?php echo Config::get('URL') . 'production/index/';?>">
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
              <th class="text-left">#JO</th>
              <th class="text-left">SO Reverence</th>
              <th class="text-left">Item</th>
              <th class="text-right">Jumlah</th>
              <th class="text-right">Users</th>
              <th class="text-right">Delete</th>
            </tr>
            </thead>

            <tbody>
            <?php
            $no = 1;
            foreach($this->on_process_list as $key => $value) {
                $total_item = explode('-, -', $value->material_name);
                $total_quantity = explode('-, -', $value->quantity);
                $row = count($total_item);
                if ($row > 1) {
                    for ($i=1; $i <= $row; $i++) {
                        $x = $i - 1;
                        if ($i === 1) {
                            echo '<tr class="' . 'row-' . $no . '">';

                            echo '<td class="text-right" rowspan="' . $row . '">' . $no . '</td>';
                            echo '<td  rowspan="' . $row . '">
                                <a href="' .  Config::get('URL') . 'production/detail/?production_number=' . urlencode($value->production_number) . '">' . $value->production_number . '</a></td>';
                            echo '<td  rowspan="' . $row . '">
                                <a href="' .  Config::get('URL') . 'so/detail/?so_number=' . urlencode($value->production_reverence) . '">' . $value->production_reverence . '</a></td>';
                            echo '<td>' . $total_item[$x] . '</td>';
                            echo '<td class="text-right">' . number_format($total_quantity[$x],0) . '</td>';                            
                            echo '<td rowspan="' . $row . '">' . $value->full_name . '</td>';
                            echo '<td class="text-right" rowspan="' . $row . '"> 


                                              <a href="' .  Config::get('URL') . 'production/deleteProduction/?production_number=' . urlencode($value->production_number) . '&forward=' . $_SERVER['REQUEST_URI'] . ' " onclick="return confirmation(\'Are you sure to delete?\');"><span class="badge badge-danger"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span> delete</span></a><br>
                            </td>';
                            echo "</tr>";
                        } else {
                            echo '<tr class="' . 'row-' . $no . '">';
                            echo '<td>' . $total_item[$x] . '</td>';
                            echo '<td class="text-right">' . number_format($total_quantity[$x],0) . '</td>';
                            echo "</tr>";
                        }
                        
                    } //end for
                    
                } else {
                    echo '<tr class="' . 'row-' . $no . '">';
                    echo '<td class="text-right">' . $no . '</td>';
                    echo '<td>
                      <a href="' .  Config::get('URL') . 'production/detail/?production_number=' . urlencode($value->production_number) . '">' . $value->production_number . '</a>
                      </td>';
                    echo '<td><a href="' .  Config::get('URL') . 'so/detail/?so_number=' . urlencode($value->production_reverence) . '">' . $value->production_reverence . '</a></td>';
                    echo '<td>' . $value->material_name . '</td>';
                    echo '<td class="text-right">' . number_format($value->quantity) . '</td>';
                    echo '<td>' . $value->full_name . '</td>';
                    echo '<td class="text-right"> 
                                              <a href="' .  Config::get('URL') . 'production/deleteProduction/?production_number=' . urlencode($value->production_number) . '&forward=' . $_SERVER['REQUEST_URI'] . ' " onclick="return confirmation(\'Are you sure to delete?\');"><span class="badge badge-danger"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span> delete</span></a>
                    </td>';
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
