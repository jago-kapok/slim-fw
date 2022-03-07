<div class="main-content">
    <div class="main-content-inner">
        <!-- #section:basics/content.breadcrumbs -->
        <div class="breadcrumbs" id="breadcrumbs">
            <script type="text/javascript">
                try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
            </script>
            <ul class="breadcrumb" >
            <li>
              <div class="btn-group btn-corner">
              <a href="<?php echo Config::get('URL');?>serialNumber/newSerialNumber/?forward=<?php echo $_SERVER['REQUEST_URI']; ?>"  class="btn btn-minier btn-info" ><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Create New</a>
              </div>
            </li>

          </ul><!-- /.breadcrumb -->

            <!-- #section:basics/content.searchbox -->
            <div class="nav-search" id="nav-search">
                <form class="form-search" method="get" action="<?php echo Config::get('URL');?>serialNumber/active?find=">
                    <span class="input-icon">
                        <input type="text" placeholder="Search ..." class="nav-search-input" id="nav-search-input" autocomplete="off" name="find" value="<?php if(isset($_GET['find'])){ echo urldecode($_GET['find']);}?>" />
                        <i class="ace-icon fa fa-search nav-search-icon"></i>
                    </span>
                </form>
            </div><!-- /.nav-search -->
            <!-- /section:basics/content.searchbox -->
        </div><!-- /.breadcrumbs -->

<?php $this->renderFeedbackMessages(); // Render message success or not?>
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover ExcelTable2007">
                <thead>
                    <tr>
                        <th>No</th>
                        <th class="center">Serial Number</th>
                        <th class="center">Material Code</th>
                        <th class="center">Production Number</th>
                        <th class="center">Transaction Number</th>
                        <th class="center">Tanggal Dibuat</th>
                        <th class="center">Action</th>
                    </tr>
                </thead>

                <tbody>
                    <?php
                    $no = ($this->page * $this->limit) - ($this->limit - 1);
                    foreach($this->sn as $key => $sn) {
                        echo "<tr>";
                        echo '<td>' . $no . '</td>';
                        echo '<td><a href="' .  Config::get('URL') . 'serialNumber/detail/' .urlencode($sn->serial_number) . '">' . $sn->serial_number . '</a></td>';
                        echo '<td >' . $sn->material_code . '</td>';
                        echo '<td >' . $sn->production_number . '</td>';
                        echo '<td >' . $sn->transaction_number . '</td>';
                        echo '<td >' . date('d M, Y', strtotime($sn->created_timestamp)) . '</td>';
                        echo '<td>
                                <div class="btn-group btn-corner" role="group" aria-label="...">
                                <a href="' .  Config::get('URL') . 'delete/remove/serial_number/uid/' . $sn->uid . '&?forward=' . $_SERVER['REQUEST_URI'] . '" class="btn btn-danger btn-xs" onclick="return confirmation(\'Are you sure to delete?\');">delete</a>

                                <a href="' .  Config::get('URL') . 'serialNumber/detail/' . urlencode($sn->serial_number) . '/?edit=' . urlencode($sn->serial_number) . '" class="btn btn-success btn-xs">Edit</a>
                                </div>
                            </td>';
                        echo "</tr>";
                        $no++;
                    }      
                    ?>

                </tbody>
            </table>
        </div>

        <?php echo $this->pagination;?>
        
    </div><!-- /.main-content-inner -->
</div><!-- /.main-content -->