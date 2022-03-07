<div class="main-content">
                <div class="main-content-inner">
                    <div class="breadcrumbs ace-save-state" id="breadcrumbs">
                        <ul class="breadcrumb">
					                 <li>
                              <div class="btn-group btn-corner">
                                <a href="<?php echo Config::get('URL') . 'bukuBesar/allTransaction'; ?>" class="btn btn-minier btn-danger"><span class="glyphicon glyphicon-refresh" aria-hidden="true"></span> Reset</a>

                                <a role="button" data-toggle="collapse" href="#changeDateRange" aria-expanded="false" aria-controls="changeDateRange" class="btn btn-minier btn-primary">
                                <span class="glyphicon glyphicon-calendar" aria-hidden="true"></span> Ganti Tanggal</a>
                              </div>
                            </li>
                        </ul><!-- /.breadcrumb -->

                        <div class="nav-search" id="nav-search">
                            <form class="form-search" method="get" action="<?php echo Config::get('URL') . 'bukuBesar/confirmPayment/';?>">
                              <span class="input-icon">
                                <input type="text" placeholder="Search ..." class="nav-search-input" id="nav-search-input" autocomplete="off" name="transaction_number" value="<?php if(isset($_GET['transaction_number'])){ echo $_GET['transaction_number'];}?>" />
                                <i class="ace-icon fa fa-search nav-search-icon"></i>
                              </span>
                            </form>
                        </div><!-- /.nav-search -->
                    </div>

<div class="collapse" id="changeDateRange">
  <div class="well">
    <form method="post" action="<?php echo Config::get('URL') . 'bukuBesar/allTransaction/'; ?>">
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
				<tr class="heading">
					<th rowspan="2">#</th>
					<th class="center" colspan="3">Tanggal</th>
					<th class="center" rowspan="2">#Transaksi</th>
          <th class="center" rowspan="2">Customer/Supplier</th>
          <th class="center" rowspan="2">Debit</th>
					<th class="center" rowspan="2">Credit</th>
					<th class="center" rowspan="2">Saldo</th>
          <th class="center" rowspan="2">Print</th>
        </tr>
        <tr class="heading">
          <th class="center">Transaksi</th>
          <th class="center">Tagihan</th>
          <th class="center">Pencairan</th>
        </tr>
					</thead>
					<tbody>
					<?php
					$no = 1;
					$credit=0;
          $debit=0;
          $saldo= 0;
          $SaldoBukuBesar = 0;
					foreach($this->allTransaction as $key => $value) {
					//buat row beda warna untuk memudahkan  pengecekan dan mempersyantik penampilan
          // check apakah kode transaksi mengandung /SO- (sales ORDER) atau /SNO- (Sales Note Order)
          if (strpos($value->transaction_code, '/SO-') !== false OR strpos($value->transaction_code, '/SNO-') !== false) { 
              $row = 'success';
          // check apakah kode transaksi mengandung /PO- (purchase order) atau /PNO- (purchase note order)
          } elseif (strpos($value->transaction_code, '/PO-') !== false OR strpos($value->transaction_code, '/PNO-') !== false) {
            $row = 'warning';
          // check apakah kode transaksi mengandung /DT- (Direct Transaction)
          } elseif (strpos($value->transaction_code, '/DT') !== false) {
            $row = 'active';
          } else {
            $row = '';
          }

          echo '<tr class="' . $row . '">';
          echo '<td class="heading">' . $no . '</td>';
          echo '<td>' . date("d M, y", strtotime($value->created_timestamp)). '</td>';
          echo '<td>' . date("d M, y", strtotime($value->payment_due_date)). '</td>';
          if ($value->payment_disbursement != '0000-00-00') {
            echo '<td>' . date("d M, y", strtotime($value->payment_disbursement)). '</td>';
          } else {
            echo '<td></td>';
          }
          if ($row == 'success') { 
              echo '<td><a href="' .  Config::get('URL') . 'so/detail/?so_number=' . urlencode($value->transaction_code) . '">' . $value->transaction_code . '</td>';

          } elseif ($row == 'warning') {
            echo '<td><a href="' .  Config::get('URL') . 'po/detail/?po_number=' . urlencode($value->transaction_code) . '">' . $value->transaction_code . '</td>';
          } else {
            echo '<td>' .  $value->transaction_code . '</td>';
          }
          

          if (empty($value->contact_name) AND empty($value->transaction_name)) {
            echo '<td>' .  $value->transaction_type . '</td>';
          } elseif (empty($value->contact_name) AND !empty($value->transaction_name)) {
            echo '<td>Transaksi Langsung: ' .  $value->transaction_name . ' (' . $value->transaction_category . ')</td>';
          } else {
            echo '<td>' .  $value->contact_name . ' (' . $value->contact_id . ')</td>';
          }
          echo '<td class="align-right">' . number_format($value->debit, 0) . '</td>';
					echo '<td class="align-right">' . number_format($value->credit, 0) . '</td>';
					
          $saldo = $saldo + $value->debit - $value->credit;
					echo '<td class="align-right">' . number_format($saldo, 0) . '</td>';
          if ($value->credit > 0) {
            echo '<td><a href="' .  Config::get('URL') . 'bukuBesar/printCredit/' . $value->uid . '"><span class="badge badge-info"><i class="glyphicon glyphicon-print"></i></span></td>';
          } elseif ($value->debit > 0) {
            echo '<td><a href="' .  Config::get('URL') . 'bukuBesar/printDebit/' . $value->uid . '"><span class="badge badge-info"><i class="glyphicon glyphicon-print"></i></span></td>';
          }
					echo "</tr>";
					$no++;
          $debit = $debit + $value->debit;
					$credit = $credit + $value->credit;
          $SaldoBukuBesar = $debit - $credit;
					}
					?>
					<tr class="info">
                            <td colspan="6" class="text-center"><h4>TOTAL</h4></td>
                            <td class="text-right"><strong><?php echo number_format($credit,0);?></strong></td>
                            <td class="text-right"><strong><?php echo number_format($debit,0);?></strong></td>
                            <td class="text-right"><strong><?php echo number_format($SaldoBukuBesar,0);?></strong></td>
                            <td></td>
                    </tr>
					</tbody>
					</table>
					</div><!-- /.table-responsive -->

                      
				<div class="hr hr10 hr-double"></div>
      <!-- PAGE CONTENT ENDS -->
        </div>
      </div><!-- /.main-content -->