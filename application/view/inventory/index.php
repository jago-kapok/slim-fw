<div class="main-content">
        <div class="main-content-inner">
				<!-- #section:basics/content.breadcrumbs -->
				<div class="breadcrumbs" id="breadcrumbs">
					<script type="text/javascript">
						try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
					</script>

					<ul class="breadcrumb">
						<li>
	                    <a href="<?php echo Config::get('URL') . 'inventory/newMaterial/'; ?>" role="button" data-toggle="modal">
	                    	<span class="badge badge-info"><i class="ace-icon fa fa-plus"></i> New</a></span>
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
						<form class="form-search" method="get" action="<?php echo Config::get('URL');?>inventory/?find=">
							<span class="input-icon">
								<input type="text" placeholder="Search ..." class="nav-search-input" id="nav-search-input" autocomplete="off" name="find" value="<?php if(isset($_GET['find'])){ echo $_GET['find'];}?>" />
								<i class="ace-icon fa fa-search nav-search-icon"></i>
							</span>
						</form>
					</div><!-- /.nav-search -->

					<!-- /section:basics/content.searchbox -->
				</div>
<?php 

$this->renderFeedbackMessages();
// Render message success or not?>
					<div class="table-responsive">
					<table class="table table-striped table-bordered table-hover ExcelTable2007">
					<thead>
					<tr>
					<th colspan="8" class="center">Total <?php echo $this->totalStock . ' ' . $this->title . ' Tersedia'; ?></th>
					</tr>
					<tr>
					<th>No</th>
					<th class="center">Kode</th>
					<th class="center">Nama Barang</th>
					<th class="center">Satuan</th>
					<th class="center">Safety Stock</th>
					<th class="center">Description</th>
					</tr>
					</thead>

					<tbody>
					<?php
					if ($this->stock) {
					$no = ($this->page * $this->limit) - ($this->limit - 1);
					foreach($this->stock as $key => $value) {
					echo "<tr>";
					echo '<td class="heading"><div class="btn-group">
                                                <button data-toggle="dropdown" class="btn btn-minier btn-info dropdown-toggle">
                                ' . $no . '
                                <span class="fa fa-caret-down fa fa-on-right"></span>
                                                </button>

                                                <ul class="dropdown-menu dropdown-info">
                                <li>
                                    <a href="' . Config::get('URL') . 'delete/soft/material_list/material_code/' . $value->material_code . '/?forward=' . $_SERVER['REQUEST_URI'] . ' " onclick="return confirmation(\'Are you sure to delete?\');">Delete</a>
                                </li>
                            </ul>
                        </div>
                    </td>';
					
                    echo '<td><a href="' . Config::get('URL') . 'inventory/editMaterial/?find=' . $value->material_code . '">' . $value->material_code . '</a></td>';
					echo '<td style="min-width: 200px;">' . $value->material_name . '</td>';
					echo '<td>' . $value->unit . '</td>';
					echo '<td>' . $value->minimum_balance . '</td>';
					echo '<td style="min-width: 200px;">' . $value->note . '</td>';
					echo "</tr>";
					$no++;
					}            

					} else {

					echo 'No Data yet. Create one !';

					}
					?>
					</tbody>
					</table>
					</div><!-- /.table-responsive -->

				<?php echo $this->pagination;?>

      <!-- PAGE CONTENT ENDS -->
      </div><!-- /.main-content-inner -->
      </div><!-- /.main-content -->