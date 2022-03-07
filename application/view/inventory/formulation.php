<div class="main-content">
	<div class="main-content-inner">
				<!-- #section:basics/content.breadcrumbs -->
				<div class="breadcrumbs" id="breadcrumbs">
					<script type="text/javascript">
						try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
					</script>

					<ul class="breadcrumb">
						<li>
	                    	<a data-toggle="collapse" href="#new-bom" aria-expanded="false" aria-controls="new-bom">
                            <span class="badge badge-info"><i class="ace-icon fa fa-plus"></i> New BOM</span></a>
	                    </li>
					</ul><!-- /.breadcrumb -->

					<!-- #section:basics/content.searchbox -->
					<div class="nav-search" id="nav-search">
						<form class="form-search" method="get" action="<?php echo Config::get('URL');?>inventory/findStock/?find=">
							<span class="input-icon">
								<input type="text" placeholder="Search ..." class="nav-search-input" id="nav-search-input" autocomplete="off" name="find" value="<?php if(isset($_GET['find'])){ echo urldecode($_GET['find']);}?>" />
								<i class="ace-icon fa fa-search nav-search-icon"></i>
							</span>
						</form>
					</div><!-- /.nav-search -->

					<!-- /section:basics/content.searchbox -->
				</div>

				<!-- /section:basics/content.breadcrumbs -->
<?php 

$this->renderFeedbackMessages();
// Render message success or not?>

   <div class="collapse" id="new-bom">
    <form class="form-horizontal" method="post" action="<?php echo Config::get('URL') . 'inventory/newFormulation/';?>">
    <div class="form-group">
      <label for="inputPassword3" class="col-sm-2 control-label">BOM Code</label>
      <div class="col-sm-9">
        <input type="text" name="bom_code" class="form-control" placeholder="Kode Barang akan otomatis ditambahkan kata BOM. didepannya">
      </div>
    </div>
    <div class="form-group">
      <label for="inputPassword3" class="col-sm-2 control-label">BOM Name</label>
      <div class="col-sm-9">
        <input type="text" name="bom_name" class="form-control" placeholder="Kode Barang akan otomatis ditambahkan kata BOM. didepannya">
      </div>
    </div>
    <div class="form-group">
      <label for="inputPassword3" class="col-sm-2 control-label">Keterangan</label>
      <div class="col-sm-9">
        <textarea name="bom_note" class="form-control" rows="3"></textarea>
      </div>
    </div>
    <div class="form-group">
      <div class="col-sm-offset-2 col-sm-10">
        <button type="submit" class="btn btn-info">Insert New BOM</button>
      </div>
    </div>
    </form>
    <div class="hr hr10 hr-double"></div>
</div>
					<div class="table-responsive">
					<table id="sample-table-1" class="table table-striped table-bordered table-hover ExcelTable2007">
					<thead>
					<tr>
					<th>No</th>
					<th class="center">Kode</th>
					<th class="center" style="min-width: 200px;">Nama Barang</th>
					<th class="center">Keterangan</th>
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
                    echo '<td><a href="' . Config::get('URL') . 'inventory/editFormulation/?find=' . $value->material_code . '">' . $value->material_code . '</a></td>';
					echo '<td style="min-width: 200px;">' . $value->material_name . '</td>';
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
