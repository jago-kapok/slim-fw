<div class="main-content">
    <div class="main-content-inner">
        <!-- #section:basics/content.breadcrumbs -->
        <div class="breadcrumbs" id="breadcrumbs">
            <script type="text/javascript">
                try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
            </script>

            <!-- #section:basics/content.searchbox -->
            <div class="nav-search" id="nav-search">
                <form class="form-search" method="get" action="<?php echo Config::get('URL');?>serialNumber/notActive?find=">
                    <span class="input-icon">
                        <input type="text" placeholder="Search ..." class="nav-search-input" id="nav-search-input" autocomplete="off" name="find" value="<?php if(isset($_GET['find'])){ echo urldecode($_GET['find']);}?>" />
                        <i class="ace-icon fa fa-search nav-search-icon"></i>
                    </span>
                </form>
            </div><!-- /.nav-search -->
            <!-- /section:basics/content.searchbox -->
        </div><!-- /.breadcrumbs -->

<?php 

$this->renderFeedbackMessages();
// Render message success or not?>

        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover ExcelTable2007">
                <thead>
                    <tr>
                        <th>No</th>
                        <th class="center">Serial Number</th>
                        <th class="center">Material Code</th>
                        <th class="center">Production Number</th>
                        <th class="center">Transaction Number</th>
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