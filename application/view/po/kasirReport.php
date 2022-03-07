<?php //Debuger::jam($this->pr_data); ?>
<div class="main-content">
                <div class="main-content-inner">
                    <div class="page-content">
                        <div class="row">
                            <div class="col-xs-12">
                                <!-- PAGE CONTENT BEGINS -->

<?php $this->renderFeedbackMessages();?>
<div class="row">
    <div class="col-xs-12 col-sm-6">
      <div class="panel panel-primary">
        <div class="panel-heading">
          <h3 class="panel-title"><?php echo strtoupper($this->pr_data->transaction_number);?> <a role="button" data-toggle="collapse" href="#editContact" aria-expanded="false" aria-controls="collapseExample" class="pull-right">
            <span class="glyphicon glyphicon-edit" aria-hidden="true" aria-label="edit"></span> Edit
          </a></h3>
        </div>
          <table class="table table-striped table-bordered table-hover">
          
            <tr>
              <td><strong>Supplier:</strong></td>
              <td colspan="3"><?php echo $this->pr_data->supplier_id . ' - ' . $this->pr_data->contact_name;?></td>
            </tr>
            <tr>
              <td><strong>Alamat:</strong></td>
              <td colspan="3"><?php echo $this->pr_data->address_street . ', ' . $this->pr_data->address_city . ', ' . $this->pr_data->address_state;?></td>
            </tr>
            <tr>
              <td><strong>Tanggal:</strong></td>
              <td><?php echo date("d/m/Y", strtotime($this->pr_data->created_timestamp)); ?></td>

              <td><strong>Pembuat:</strong></td>
              <td><?php echo $this->pr_data->full_name;?></td>
            </tr>                          
          </table>
      </div>

    </div><!-- /.col-sm-6 -->
    <div class="col-sm-6">
      <!-- #section:elements.tab -->
      <div class="tabbable">
        <ul class="nav nav-tabs" id="myTab">
          <li class="active">
            <a data-toggle="tab" href="#home">
              <i class="green ace-icon fa fa-comments bigger-120"></i>
              Notes
            </a>
          </li>

          <li>
            <a data-toggle="tab" href="#Logs">
              <i class="green ace-icon fa fa-undo bigger-120"></i>
              Logs
            </a>
          </li>

          <li class="dropdown">
            <a data-toggle="dropdown" class="dropdown-toggle" href="#">
              <span class="glyphicon glyphicon-plus text-danger" aria-hidden="true" aria-label="Create New"></span>
            </a>

            <ul class="dropdown-menu dropdown-info">
              <li>
                <a href="#editnotes" role="button" data-toggle="modal">
                  <i class="green ace-icon fa fa-plus bigger-120"></i>
                  Add notes
                </a>
              </li>
            </ul>
          </li>
        </ul>

        <div class="tab-content">
          <div id="home" class="tab-pane box-case active" style="overflow-x: hidden; overflow-y: scroll; height:90px;">
            <ol reversed><?php echo $this->pr_data->note;?></ol>
          </div>

          <div id="Logs" class="tab-pane box-case" style="overflow-x: hidden; overflow-y: scroll; height:90px;">
            <ol reversed><?php echo $this->pr_data->log;?></ol>
          </div>
        </div>
      </div>
      <!-- /section:elements.tab -->

    </div>
  </div><!-- ./row -->
<br>
<br>
<div class="row">
    <div class="col-xs-12 col-sm-12">
<table class="table table-striped table-bordered table-hover ExcelTable2007">
  <thead>
    <tr class="heading">
          <th rowspan="2">#</th>
          <th class="center" rowspan="2">Kode</th>
          <th class="center" rowspan="2">Nama</th>
          <th class="center" rowspan="2">Jumlah</th>
          <th class="center" colspan="2">Harga</th>
          <th class="center" rowspan="2">Sub Total</th>
        </tr>
        <tr class="heading">
          <th class="center">Satuan</th>
          <th class="center">Curah</th>
        </tr>
  </thead>

  <tbody>
    <?php
    $num = 1;
    $total_price = 0;
    foreach($this->pr_item_list as $key => $value) {
    // check jika pr bukan biaya pengiriman
          echo "<tr>";
    // check jika pr belum minta approval
          if ($this->pr_data->status <= -2) {
          echo '<td class="heading">
          <div class="dropdown">
  <button class="btn btn-info btn-xs dropdown-toggle" type="button" id="cpMenu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    ' . $num . '
    <span class="caret"></span>
  </button>
  <ul class="dropdown-menu" aria-labelledby="cpMenu">
    <li><a href="' . Config::get('URL') . 'po/editPrItem/' . $value->uid . '">Edit</a></li>
    <li role="separator" class="divider"></li>
    <li><a href="'. Config::get('URL') . 'po/erasePrItem/' . $value->uid . '/?forward=' . $_SERVER['REQUEST_URI'] . '" onclick="return confirmation(\'Are you sure to delete?\');">Delete</a></li>
  </ul>
</div>
        </td>'; } else { echo '<td class="heading">' . $num . '</td>'; }
          echo '<td>' . $value->material_code . '</td>';
          echo '<td>' . $value->material_name . '</td>';
          echo '<td class="text-right">' . number_format($value->quantity,0) . '</td>';
          echo '<td class="text-right">' . number_format($value->purchase_price,2) . '</td>';
          echo '<td class="text-right">' . number_format($value->purchase_price_bulk,2) . '</td>';
          if ($value->purchase_price_bulk <= 0 AND $value->purchase_price > 0) {
            $price = $value->purchase_price * $value->quantity;
          } else {
            $price = $value->purchase_price_bulk;
          }
          echo '<td class="text-right">' . number_format($price,2) . '</td>';
          echo "</tr>";
          $total_price = $total_price + $price;
          $num++;
        }
        echo '<tr class="info">
            <td class="text-right" colspan="6">Total: </td>
            <td class="text-right">' . number_format($total_price,2) . '</td>
          </tr>';
      ?>

    </tbody>
    </table>
    </div><!-- /.col -->
            </div><!-- /.row -->

<!-- PAGE CONTENT ENDS -->
              </div><!-- /.col -->
            </div><!-- /.row -->
          </div><!-- /.page-content -->
        </div>
      </div><!-- /.main-content -->