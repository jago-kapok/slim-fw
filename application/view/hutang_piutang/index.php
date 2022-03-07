<div class="main-content">
	<div class="main-content-inner">
		<div class="breadcrumbs ace-save-state" id="breadcrumbs">
						<ul class="breadcrumb">
							<li>
					            <div class="btn-group btn-corner">
					                <a href="#" onclick="showHutang()" class="btn btn-minier btn-inverse">
					                  <span class="glyphicon glyphicon glyphicon-signal" aria-hidden="true"></span> Hutang
					                </a>

					                <a href="#" onclick="showPiutang()" class="btn btn-minier btn-success">
					                  <span class="glyphicon glyphicon glyphicon-list" aria-hidden="true"></span> Piutang
					                </a>
					              </div>
					              &nbsp;
					              <a href="<?php echo $_SERVER['REQUEST_URI'] . '&export_excel=1'; ?>">
					                <span class="badge badge-info">Export Excel</span>
					              </a>

					              &nbsp;
					              <a role="button" data-toggle="collapse" href="#changeDateRange" aria-expanded="false" aria-controls="changeDateRange">
					                <span class="badge badge-info"><i class="glyphicon glyphicon-time"></i> Ganti Tanggal</span>
					              </a>
							</li>
						</ul><!-- /.breadcrumb -->

						<div class="nav-search" id="nav-search">
							<form class="form-search">
								<span class="input-icon">
									<input type="text" placeholder="Search ..." class="nav-search-input" id="nav-search-input" autocomplete="off" />
									<i class="ace-icon fa fa-search nav-search-icon"></i>
								</span>
							</form>
						</div><!-- /.nav-search -->
					</div>

		<div class="collapse" id="changeDateRange">
  <div class="well">
    <form method="post" action="<?php echo Config::get('URL') . 'HutangPiutang/index/'; ?>">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">Ganti Tanggal Report </h3>
                </div>
                <div class="panel-body">
                  <div class="row">

                      <div class="col-xs-12 col-sm-4">
                          <div class="form-group">
                              <label for="jumlah-pinjaman">Dari Tanggal:</label>
                              <input type="text" name="start_date" class="form-control datepicker" value="<?php echo date("Y-m-d");?>" data-date-format="yyyy-mm-dd">
                          </div>
                      </div><!-- /.col -->

                      <div class="col-xs-12 col-sm-4">
                          <div class="form-group">
                              <label for="jenis-pinjaman">Sampai Tanggal:</label>
                              <input type="text" name="end_date" class="form-control datepicker" value="<?php echo date("Y-m-d");?>" data-date-format="yyyy-mm-dd">
                          </div>
                      </div><!-- /.col -->

                      <div class="col-xs-12 col-sm-4">
                          <div class="form-group">
                              <label for="jenis-pinjaman">Berdasar Tgl:</label>
                              <select class="form-control" name="by_date">
                                <option value="invoice_date" >Tanggal Invoice</option>
                                <option value="payment_due_date" >Tanggal Jatuh Tempo</option>
                                <option value="payment_disbursement" >Tanggal Pembayaran</option>
                              </select>
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

						<?php
							include('hutang.php');
					
							include('piutang.php');
						
						?>
	</div><!-- /.main-content-inne -->
</div><!-- /.main-content -->

<script type="text/javascript">
function showHutang() {
  document.getElementById('hutang').style.display = 'block';
  document.getElementById('piutang').style.display = 'none';
}

function showPiutang() {
	document.getElementById('hutang').style.display = 'none';
 	document.getElementById('piutang').style.display = 'block';
}
</script>
