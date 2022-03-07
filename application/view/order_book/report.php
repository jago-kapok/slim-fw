<div class="main-content">
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
            </li>
          
          </ul><!-- /.breadcrumb -->

          <!-- #section:basics/content.searchbox -->
          <div class="nav-search" id="nav-search">
            <form class="form-search" method="get" action="<?php echo Config::get('URL') . 'orderBook/report/'; ?>">
              <span class="input-icon">
                <input type="text" placeholder="Search ..." class="nav-search-input" id="nav-search-input" autocomplete="off" name="find" value="<?php echo urldecode(Request::get('find')); ?>" />
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
    <form method="post" action="<?php echo Config::get('URL') . 'orderBook/report/'; ?>">
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
    <li role="presentation" class="active">
      <a href="#by-date" aria-controls="by-date" role="tab" data-toggle="tab">Detail</a>
    </li>
    <li role="presentation"><a href="#per-kategori" aria-controls="per-kategori" role="tab" data-toggle="tab">Per Kategori</a></li>
    <li role="presentation"><a href="#group-by-week" aria-controls="group-by-week" role="tab" data-toggle="tab">Per Minggu</a></li>
    <li role="presentation"><a href="#group-by-month" aria-controls="group-by-month" role="tab" data-toggle="tab">Per Bulan</a></li>
  </ul>

  <!-- Tab panes -->
  <div class="tab-content">
    <?php include('report_by_date.php'); ?>

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
          <th class="text-right">Jumlah</th>
          </tr>
          </thead>

          <tbody>
          <?php
          //ingat.... debit dan credit dibalek disini, beda dengan yang ada di tulisan tabungan bank
          $no = 1;
          $total_order = 0;
          foreach($this->transaction_group_by_category as $key => $value) {
          echo "<tr>";
          echo '<td class="text-right">' . $no . '</a></td>';
          echo '<td>' . $value->budged_category . '</td>';
          echo '<td class="text-right">' . number_format(($value->order) , 0) . '</td>';
          
          echo "</tr>";
          $no++;
          $total_order = $total_order + $value->order;
          }
          ?>
          <tr class="success">
          <td class="text-center" colspan="2">Total</td>
          <td class="text-right"><?php echo number_format($total_order, 0); ?></td>
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
              <th class="text-right">Jumlah</th>
            </tr>
            </thead>

            <tbody>
            <?php
            //ingat.... debit dan credit dibalek disini, beda dengan yang ada di tulisan tabungan bank
            $no = 1;
            $total_order = 0;
            foreach($this->transaction_group_by_week as $key => $value) {
            echo "<tr>";
            echo '<td class="text-right">' . $no . '</a></td>';
            echo '<td>' . $value->week . '</td>';
            echo '<td class="text-right">' . number_format($value->order, 0) . '</td>';
            echo "</tr>";
            $no++;
            $total_order = $total_order + $value->order;
            }
            ?>
            <tr class="success">
            <td class="text-center" colspan="2">Total</td>
            <td class="text-right"><?php echo number_format($total_order, 0); ?></td>
            </tr>
            </tbody>
            </table>
      </div>


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
              <th class="text-right">Jumlah</th>
            </tr>
            </thead>

            <tbody>
            <?php
            //echo '<pre>';var_dump($this->transaction_group_by_category_month); echo '</pre>';
            //ingat.... debit dan credit dibalek disini, beda dengan yang ada di tulisan tabungan bank
            $no = 1;
            $total_order = 0;
            foreach($this->transaction_group_by_month as $key => $value) {
            echo "<tr>";
            echo '<td class="text-right">' . $no . '</a></td>';
            echo '<td>' . $value->month . '</td>';
            echo '<td class="text-right">' . number_format($value->order, 0) . '</td>';
            echo "</tr>";
            $no++;
            $total_order = $total_order + $value->order;
            }
            ?>
            <tr class="success">
            <td class="text-center" colspan="2">Total</td>
            <td class="text-right"><?php echo number_format($total_order, 0); ?></td>
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
                      <th class="text-right">Jumlah</th>
                    </tr>
                  </thead>

                  <tbody>
                    <?php
                    $no = 1;
                    $total_order = 0;
                    $month = 'no month';
                    foreach($this->transaction_group_by_month_and_category as $key => $value) {
                    echo "<tr>";
                    if ($month != $value->month) {
                      echo '<td class="text-right">' . $no . '</a></td>';
                      echo '<td>' . $value->month . '</td>';
                      $month = $value->month;
                      $no++;
                    } else {
                      echo '<td></td>';
                      echo '<td></td>';
                    }
                    
                    echo '<td>' . $value->budged_category . '</td>';
                    echo '<td class="text-right">' . number_format($value->order, 0) . '</td>';
                    echo "</tr>";
                    $total_order = $total_order + $value->order;
                    }
                    ?>
                    <tr class="success">
                    <td class="text-center" colspan="3">Total</td>
                    <td class="text-right"><?php echo number_format($total_order, 0); ?></td>
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

<div class="modal fade" id="paymentModal" tabindex="-1" role="dialog" aria-labelledby="paymentModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <form method="post" class="form-horizontal" action="<?php echo Config::get('URL') . 'orderBook/insertPayment';?>">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="paymentModalLabel"></h4>
      </div>
        <table class="table ExcelTable2007">
           <thead>
                <tr>
                   <th>#</th>
                   <th>Jumlah</th>
                   <th>Rencana Pembayaran</th>
                   <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>
            <?php
              for ($i=1; $i <= 5; $i++) { ?>
                  <tr>
                      <td><?php  echo  $i;?></td>
                      <td><input  type="number" class="form-control text-right" name="<?php echo "value[$i]"; ?>"></td>
                      <td>
                          <input class="datepicker form-control" name="<?php echo "payment_due_date[$i]"; ?>" id="<?php echo "payment_date_{$i}"; ?>" data-date-format="yyyy-mm-dd" type="text">
                      </td>
                      <td>
                        <textarea name="<?php echo "note[$i]"; ?>" class="form-control"></textarea>
                      </td>
                  <tr>
                <?php  } ?>
                </tbody>
            </table>
            <input type="hidden" class="form-control" id="transaction-number" name="transaction_number">
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-primary">Buat Pembayaran</button>
      </div>
    </div>
  </form>
  </div>
</div>


<div class="modal fade" id="uploadModal" tabindex="-1" role="dialog" aria-labelledby="uploadModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <form class="form-horizontal" action="<?php echo Config::get('URL') . 'orderBook/uploadImage';?>" method="post" enctype="multipart/form-data">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="uploadModalLabel"></h4>
      </div>
      <div class="modal-body">
            <div class="form-group">
              <label class="col-sm-2 control-label">Nama Photo/Scan</label>
              <div class="col-sm-10">
                <input type="text" name="image_name" class="form-control" placeholder="Nama file photo atau hasil scan yang diupload">
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label">Pilih Photo/Scan</label>
              <div class="col-sm-10">
                <input type="file" class="form-control" name="file_name" required />
                <span id="helpBlock" class="help-block">Hanya file image tipe jpg, jpeg dan png yang diijinkan, ukuran maksimum 3MB dan minimum dimensi 100 x 100 pixel</span>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label">Keterangan</label>
              <div class="col-sm-10">
                <textarea name="note" class="form-control" rows="3"></textarea>
              </div>
            </div>
            <input type="hidden" class="form-control" id="transaction-number-upload" name="transaction_number">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-primary">Upload File</button>
      </div>
    </div>
  </form>
  </div>
</div>
  <?php //echo '<pre>';var_dump($this->transaction_group_by_month_and_category );echo '</pre>';