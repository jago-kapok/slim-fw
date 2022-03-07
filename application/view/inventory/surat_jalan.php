<div class="main-content">
        <div class="main-content-inner">
				<!-- #section:basics/content.breadcrumbs -->
				<div class="breadcrumbs" id="breadcrumbs">
					<script type="text/javascript">
						try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
					</script>

					<ul class="breadcrumb">
		            <li>
		              <a href="<?php echo Config::get('URL') . 'inventory/newSj/'; ?>">
		                <span class="badge badge-info">Create SJ</span>
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
<?php $this->renderFeedbackMessages();?>
   
					<div class="table-responsive">
					<table id="sample-table-1" class="table table-striped table-bordered table-hover ExcelTable2007">
					<thead>
					<tr>
					<th>No</th>
					<th class="align-left">SJ Number</th>
					<th class="align-left">SO Number</th>
					<th class="align-left">Kode Barang</th>
					<th class="align-left">Nama Barang</th>
					<th class="align-left">Jumlah</th>
					<th class="align-left">Tanggal Kirim</th>
					</tr>
					</thead>

					<tbody>
			          <?php
			          //Debuger::jam($this->sj);
			          $no = ($this->page * $this->limit) - ($this->limit - 1);
			          foreach($this->sj as $key => $value) {
			              $total_code = explode('--,--', $value->material_code);
			              $total_name = explode('--,--', $value->material_name);
			              $total_qty = explode('--,--', $value->quantity_delivered);
			              $row = count($total_code);
			              if ($row > 1) {
			                  for ($i=1; $i <= $row; $i++) {
			                      $x = $i - 1;
			                      if ($i === 1) {
			                          echo '<tr>';
			                          echo '<td class="text-right" rowspan="' . $row . '">' . $no . '</td>';
			                          echo '<td  rowspan="' . $row . '"><a href="' . Config::get('URL') . 'so/printSj/?sj_number=' . urlencode($value->do_number) . '">' . $value->do_number . '</a></td>';
			                          echo '<td  rowspan="' . $row . '"><a href="' .  Config::get('URL') . 'so/detail/?so_number=' . urlencode($value->transaction_number) . '">' . $value->transaction_number . '</a></td>';
			                          echo '<td><a href="' . Config::get('URL') . 'inventory/editMaterial/?find=' . $total_code[$x] . '">' . $total_code[$x] . '</a></td>';
			                          echo '<td class="text-right">' . $total_name[$x] . '</td>';
			                          echo '<td class="text-right">' . number_format($total_qty[$x],0) . '</td>';
			                          echo '<td rowspan="' . $row . '">' . date("d M, y", strtotime($value->delivery_date)) . '</td>';
			                          echo "</tr>";
			                      } else {
			                          echo '<tr>';
			                          echo '<td><a href="' . Config::get('URL') . 'inventory/editMaterial/?find=' . $total_code[$x] . '">' . $total_code[$x] . '</a></td>';
			                          echo '<td class="text-right">' . $total_name[$x] . '</td>';
			                          echo '<td class="text-right">' . number_format($total_qty[$x],0) . '</td>';
			                          echo "</tr>";
			                      }
			                      
			                  } //end for
			                  
			              } else {
			                  echo '<tr>';
	                          echo '<td class="text-right">' . $no . '</td>';
	                          echo '<td ><a href="' . Config::get('URL') . 'so/printSj/?sj_number=' . urlencode($value->do_number) . '">' . $value->do_number . '</a></td>';
	                          echo '<td ><a href="' .  Config::get('URL') . 'so/detail/?so_number=' . urlencode($value->transaction_number) . '">' . $value->transaction_number . '</a></td>';
	                          echo '<td><a href="' . Config::get('URL') . 'inventory/editMaterial/?find=' . $value->material_code . '">' . $value->material_code . '</a></td>';
	                          echo '<td class="text-right">' . $value->material_name . '</td>';
	                          echo '<td class="text-right">' . number_format($value->quantity_delivered,0) . '</td>';
	                          echo '<td rowspan="' . $row . '">' . date("d M, y", strtotime($value->delivery_date)) . '</td>';
	                          echo "</tr>";
			              } //end if
			              
			              $no++;
			          }
			          ?>
			          </tbody>
					</table>
					</div><!-- /.table-responsive -->

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

<!-- PAGE CONTENT ENDS -->
</div><!-- /.main-content-inner -->
      </div><!-- /.main-content -->