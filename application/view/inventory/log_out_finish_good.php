<div class="main-content">
        <div class="main-content-inner">
				<!-- #section:basics/content.breadcrumbs -->
				<div class="breadcrumbs" id="breadcrumbs">
					<script type="text/javascript">
						try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
					</script>
					<ul class="breadcrumb">
          
            <li>
              <a role="button" data-toggle="collapse" href="#changeDateRange" aria-expanded="false" aria-controls="changeDateRange">
                <span class="badge badge-info">Ganti Tanggal</span>
              </a>

              &nbsp;
              <a href="<?php echo $_SERVER['REQUEST_URI'] . '&export_excel=1'; ?>">
                <span class="badge badge-info">Export Excel</span>
              </a>
            </li>
            
          
          </ul><!-- /.breadcrumb -->

					<!-- #section:basics/content.searchbox -->
					<div class="nav-search" id="nav-search">
						<form class="form-search" method="get" action="<?php echo Config::get('URL');?>inventory/?find=">
							<span class="input-icon">
								<input type="text" placeholder="Search ..." class="nav-search-input" id="nav-search-input" autocomplete="off" name="find" value="<?php if(isset($_GET['find'])){ echo $this->find;}?>" />
								<i class="ace-icon fa fa-search nav-search-icon"></i>
							</span>
						</form>
					</div><!-- /.nav-search -->

					<!-- /section:basics/content.searchbox -->
				</div>

				<!-- /section:basics/content.breadcrumbs -->
<div class="collapse" id="changeDateRange">
  <div class="well">
    <form method="get" action="<?php echo Config::get('URL') . 'inventory/logOutFinishGood/'; ?>">
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
                              <input type="text" name="finish_date" class="form-control datepicker" value="<?php echo date("Y-m-d");?>" data-date-format="yyyy-mm-dd">
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
<?php $this->renderFeedbackMessages();?>
   
					<div class="table-responsive">
					<table id="sample-table-1" class="table table-striped table-bordered table-hover ExcelTable2007 summary">
					<thead>
					<tr>
					<th>No</th>
					<th class="align-center">Tanggal</th>
					<th class="align-center">Nama Barang</th>
					<th class="align-center">#Transaction</th>
					<th class="align-center">Jumlah</th>
					<th class="align-center">Serial Number</th>
					</tr>
					</thead>

					<tbody>
			          <?php
			          $tgl = '';
			          $no = ($this->page * $this->limit) - ($this->limit - 1);
			          foreach($this->log as $key => $value) {
			                  echo '<tr>';
	                          echo '<td class="text-right">' . $no . '</td>';
                              if ($tgl != $value->created_timestamp) {
                                  echo '<td>' . date("d M, y", strtotime($value->created_timestamp)) . '</td>';
                              } else {
                                echo '<td></td>';
                              }
	                          
	                          echo '<td style="width: 40%;">' . $value->material_name . '<br><a href="' . Config::get('URL') . 'inventory/editMaterial/?find=' . $value->material_code . '">' . $value->material_code . '</a></td>';
	                          echo '<td>' . $value->transaction_number . '</td>';
	                          echo '<td class="text-right">' . FormaterModel::trimTrailingZeroes($value->quantity_delivered) . '</td>';
	                          echo '<td>' . $value->serial_number . '</td>';    	                          
	                          echo "</tr>";
                        $tgl = $value->created_timestamp; 
                        $no++;
			          }
			          ?>
			          </tbody>
					</table>
					</div><!-- /.table-responsive -->

<?php echo $this->pagination;?>

<!-- PAGE CONTENT ENDS -->
</div><!-- /.main-content-inner -->
      </div><!-- /.main-content -->
