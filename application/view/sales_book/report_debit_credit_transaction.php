<div class="main-content">
        <!-- #section:basics/content.breadcrumbs -->
        <div class="breadcrumbs hidden-print" id="breadcrumbs">
          <script type="text/javascript">
            try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
          </script>

          <ul class="breadcrumb">
          
            <li>
              <a role="button" data-toggle="collapse" href="#changeDateRange" aria-expanded="false" aria-controls="changeDateRange">
                <span class="badge badge-info">Ganti Tanggal</span>
              </a>
            </li>
          
          </ul><!-- /.breadcrumb -->

          <!-- #section:basics/content.searchbox -->
          <div class="nav-search" id="nav-search">
            <form class="form-search">
              <span class="input-icon">
                <input type="text" placeholder="Search ..." class="nav-search-input" id="nav-search-input" autocomplete="off" />
                <i class="ace-icon fa fa-search nav-search-icon"></i>
              </span>
            </form>
          </div><!-- /.nav-search -->

          <!-- /section:basics/content.searchbox -->
        </div>

        <!-- /section:basics/content.breadcrumbs -->
        <div class="page-content">
          <div class="row">
            <div class="col-xs-12">
              <!-- PAGE CONTENT BEGINS -->

<?php $this->renderFeedbackMessages();?>
<div class="collapse" id="changeDateRange">
  <div class="well">
    <form method="post" action="<?php echo Config::get('URL') . 'finance/reportDebitCreditTransaction/'; ?>">
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

  <!-- Nav tabs -->
  <ul class="nav nav-tabs" role="tablist">
    <li role="presentation" class="active"><a href="#semua" aria-controls="semua" role="tab" data-toggle="tab">Detail</a></li>
    <li role="presentation"><a href="#per-kategori" aria-controls="per-kategori" role="tab" data-toggle="tab">Per Kategori</a></li>
    <li role="presentation"><a href="#group-by-week" aria-controls="group-by-week" role="tab" data-toggle="tab">Per Minggu</a></li>
    <li role="presentation"><a href="#group-by-month" aria-controls="group-by-month" role="tab" data-toggle="tab">Per Bulan</a></li>
  </ul>

  <!-- Tab panes -->
  <div class="tab-content">
    <div role="tabpanel" class="tab-pane active" id="semua">
      <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover">
            <thead>
            <tr>
            <th class="text-center" colspan="8"><?php echo $this->title;?></th>      
            </tr>
            <tr>
            <th class="text-right">No</th>
            <th class="text-left">Tanggal</th>
            <th class="text-left">Nama Transaksi</th>
            <th class="text-right">Debit</th>
            <th class="text-right">Credit</th>
            <th class="text-left">Kategori</th>       
            <th class="text-left">Catatan</th>
            <th class="text-left">Hapus?</th>
            </tr>
            </thead>

            <tbody>
            <?php
            //ingat.... debit dan credit dibalek disini, beda dengan yang ada di tulisan tabungan bank
            $no = 1;
            $total_debit = 0;
            $total_credit = 0;
            foreach($this->transaction as $key => $value) {
            echo "<tr>";
            echo '<td class="text-right">' . $no . '</a></td>';
            echo '<td>' . date("d F Y", strtotime($value->created_timestamp)) . '</a></td>';
            echo '<td>' . $value->transaction_name . '</td>';
            echo '<td class="text-right">' . number_format($value->debit,2) . '</td>';
            echo '<td class="text-right">' . number_format($value->credit,2) . '</td>';
            echo '<td>' . $value->transaction_category . '</td>';
            echo '<td>' . $value->note . '</td>';
            echo '<td><a href="' .  Config::get('URL') . 'delete/remove/payment_transaction/uid/' . $value->uid . '/?forward=' . $_SERVER['REQUEST_URI'] . '" class="btn btn-danger btn-xs" onclick="return confirmation(\'Are you sure to delete?\');">delete</a></td>';
            
            echo "</tr>";
            $no++;
            $total_debit = $total_debit + $value->debit;
            $total_credit = $total_credit + $value->credit;
            }
            ?>
            <tr class="success">
            <td class="text-center" colspan="3">Total</td>
            <td class="text-right"><?php echo number_format($total_debit,2); ?></td>
            <td class="text-right"><?php echo number_format($total_credit,2); ?></td>
            <td class="text-center" colspan="3"></td>
            </tr>
            </tbody>
            </table>
      </div>
    </div> <!-- /#semua -->

    <div role="tabpanel" class="tab-pane" id="per-kategori">
        <div class="table-responsive">
          <table class="table table-striped table-bordered table-hover">
          <thead>
          <tr>
          <th class="text-center" colspan="7"><?php echo $this->title;?></th>      
          </tr>
          <tr>
          <th class="text-right">No</th>
          <th class="text-left">Kategori</th>
          <th class="text-right">Debit</th>
          <th class="text-right">Credit</th>
          </tr>
          </thead>

          <tbody>
          <?php
          //ingat.... debit dan credit dibalek disini, beda dengan yang ada di tulisan tabungan bank
          $no = 1;
          $total_debit = 0;
          $total_credit = 0;
          foreach($this->transaction_group_by_category as $key => $value) {
          echo "<tr>";
          echo '<td class="text-right">' . $no . '</a></td>';
          echo '<td>' . $value->transaction_category . '</td>';
          echo '<td class="text-right">' . number_format($value->debit,2) . '</td>';
          echo '<td class="text-right">' . number_format($value->credit,2) . '</td>';
          
          echo "</tr>";
          $no++;
          $total_debit = $total_debit + $value->debit;
          $total_credit = $total_credit + $value->credit;
          }
          ?>
          <tr class="success">
          <td class="text-center" colspan="2">Total</td>
          <td class="text-right"><?php echo number_format($total_debit,2); ?></td>
          <td class="text-right"><?php echo number_format($total_credit,2); ?></td>
          </tr>
          </tbody>
          </table>
  </div>
  </div> <!-- /#per-kategori -->

  <div role="tabpanel" class="tab-pane" id="group-by-week">
      <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover">
            <thead>
            <tr>
            <th class="text-center" colspan="6"><?php echo $this->title;?></th>      
            </tr>
            <tr>
              <th class="text-right">No</th>
              <th class="text-left">Minggu Ke</th>
              <th class="text-right">Debit</th>
              <th class="text-right">Credit</th>
            </tr>
            </thead>

            <tbody>
            <?php
            //ingat.... debit dan credit dibalek disini, beda dengan yang ada di tulisan tabungan bank
            $no = 1;
            $total_debit = 0;
            $total_credit = 0;
            foreach($this->transaction_group_by_year_week as $key => $value) {
            echo "<tr>";
            echo '<td class="text-right">' . $no . '</a></td>';
            echo '<td>' . $value->week . '</td>';
            echo '<td class="text-right">' . number_format($value->debit,2) . '</td>';
            echo '<td class="text-right">' . number_format($value->credit,2) . '</td>';
            echo "</tr>";
            $no++;
            $total_debit = $total_debit + $value->debit;
            $total_credit = $total_credit + $value->credit;
            }
            ?>
            <tr class="success">
            <td class="text-center" colspan="2">Total</td>
            <td class="text-right"><?php echo number_format($total_debit,2); ?></td>
            <td class="text-right"><?php echo number_format($total_credit,2); ?></td>
            </tr>
            </tbody>
            </table>
      </div>
  <div class="row">
  <div class="col-xs-12 col-sm-12 widget-container-col" id="widget-container-col-2">
    <div class="widget-box widget-color-blue" id="widget-box-2">
      <div class="widget-header">
        <h5 class="widget-title bigger lighter">
          <i class="ace-icon fa fa-table"></i>
          Per kategori per minggu
        </h5>
      </div>

      <div class="widget-body">
        <div class="widget-main no-padding">
          <table class="table table-striped table-bordered table-hover">
            <thead class="thin-border-bottom">
              <tr>
                <th class="text-right">No</th>
                <th class="text-left">Minggku Ke</th>
                <th class="text-left">Kategori</th>
                <th class="text-right">Debit</th>
                <th class="text-right">Credit</th>
              </tr>
            </thead>

            <tbody>
              <?php
              $no = 1;
              $total_debit = 0;
              $total_credit = 0;
              foreach($this->transaction_group_by_category_week as $key => $value) {
              echo "<tr>";
              echo '<td class="text-right">' . $no . '</a></td>';
              echo '<td>' . $value->week . '</td>';
              echo '<td>' . $value->transaction_category . '</td>';
              echo '<td class="text-right">' . number_format($value->debit,2) . '</td>';
              echo '<td class="text-right">' . number_format($value->credit,2) . '</td>';
              
              echo "</tr>";
              $no++;
              $total_debit = $total_debit + $value->debit;
              $total_credit = $total_credit + $value->credit;
              }
              ?>
              <tr class="success">
              <td class="text-center" colspan="3">Total</td>
              <td class="text-right"><?php echo number_format($total_debit,2); ?></td>
              <td class="text-right"><?php echo number_format($total_credit,2); ?></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div><!-- /.col -->
</div><!-- /.row -->

    </div> <!-- /#group-by-week -->
    
      <div role="tabpanel" class="tab-pane" id="group-by-month">
      <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover">
            <thead>
            <tr>
            <th class="text-center" colspan="5"><?php echo $this->title;?></th>      
            </tr>
            <tr>
              <th class="text-right">No</th>
              <th class="text-left">Bulan Ke</th>
              <th class="text-right">Debit</th>
              <th class="text-right">Credit</th>
            </tr>
            </thead>

            <tbody>
            <?php
            //echo '<pre>';var_dump($this->transaction_group_by_category_month); echo '</pre>';
            //ingat.... debit dan credit dibalek disini, beda dengan yang ada di tulisan tabungan bank
            $no = 1;
            $total_debit = 0;
            $total_credit = 0;

            foreach($this->transaction_group_by_year_month as $key => $value) {
            echo "<tr>";
            echo '<td class="text-right">' . $no . '</a></td>';
            echo '<td>' . $value->month . '</td>';
            echo '<td class="text-right">' . number_format($value->debit,2) . '</td>';
            echo '<td class="text-right">' . number_format($value->credit,2) . '</td>';
            echo "</tr>";
            $no++;
            $total_debit = $total_debit + $value->debit;
            $total_credit = $total_credit + $value->credit;
            }
            ?>
            <tr class="success">
            <td class="text-center" colspan="2">Total</td>
            <td class="text-right"><?php echo number_format($total_debit,2); ?></td>
            <td class="text-right"><?php echo number_format($total_credit,2); ?></td>
            </tr>
            </tbody>
            </table>
      </div>

<div class="row">
  <div class="col-xs-12 col-sm-12 widget-container-col" id="widget-container-col-2">
    <div class="widget-box widget-color-blue" id="widget-box-2">
      <div class="widget-header">
        <h5 class="widget-title bigger lighter">
          <i class="ace-icon fa fa-table"></i>
          Per kategori Per Bulan
        </h5>
      </div>

      <div class="widget-body">
        <div class="widget-main no-padding">
          <table class="table table-striped table-bordered table-hover">
            <thead class="thin-border-bottom">
              <tr>
                <th class="text-right">No</th>
                <th class="text-left">Bulan Ke</th>
                <th class="text-left">Kategori</th>
                <th class="text-right">Debit</th>
                <th class="text-right">Credit</th>
              </tr>
            </thead>

            <tbody>
              <?php
              $no = 1;
              $total_debit = 0;
              $total_credit = 0;
              foreach($this->transaction_group_by_category_month as $key => $value) {
              echo "<tr>";
              echo '<td class="text-right">' . $no . '</a></td>';
              echo '<td>' . $value->month . '</td>';
              echo '<td>' . $value->transaction_category . '</td>';
              echo '<td class="text-right">' . number_format($value->debit,2) . '</td>';
              echo '<td class="text-right">' . number_format($value->credit,2) . '</td>';
              
              echo "</tr>";
              $no++;
              $total_debit = $total_debit + $value->debit;
              $total_credit = $total_credit + $value->credit;
              }
              ?>
              <tr class="success">
              <td class="text-center" colspan="3">Total</td>
              <td class="text-right"><?php echo number_format($total_debit,2); ?></td>
              <td class="text-right"><?php echo number_format($total_credit,2); ?></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div><!-- /.col -->
</div><!-- /.row -->
    </div> <!-- /#group-by-month -->
  </div><!-- /Nav tabs -->