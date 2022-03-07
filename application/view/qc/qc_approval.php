<?php //Debuger::jam($this->pr_data); ?>
<div class="main-content">
                <div class="main-content-inner">
                    <div class="page-content">
                        <div class="row">
                            <div class="col-xs-12">
                                <!-- PAGE CONTENT BEGINS -->



<?php $this->renderFeedbackMessages();?>

<div id="approve-qc-manual">
  <form method="post" action="<?php echo Config::get('URL') . 'qc/approveQC/?transaction_number=' . urlencode($this->qc_item_list[0]->transaction_number); ?>">
  <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title">Approve Barang Masuk PO</h3>
        </div>
      
<table class="table table-striped table-bordered table-hover ExcelTable2007">
<thead>
  <tr>
    <th rowspan="2"  class="center" style="width: 5%;">#</th>
    <th rowspan="2" class="center" style="width: 10%;">Kode</th>
    <th rowspan="2" class="center" style="width: 30%;">Nama</th>
    <th colspan="2" class="center" style="width: 15%;">Quantity</th>
    <th rowspan="2" class="center" style="width: 20%;">Keterangan</th>
  </tr>
  <tr>
    <th class="center">Received</th>
    <th class="center">Reject</th>
  </tr>
</thead>
<?php
      $i = 1;
      foreach($this->qc_item_list as $key => $value) {
?>
<tr>
  <td><?php echo $i; ?></td>
  <td>
      <?php echo $value->material_code; ?>
      <input type="hidden" class="form-control" name="uid_<?php echo $i; ?>" value="<?php echo $value->uid; ?>">
  </td>
  <td>
      <?php echo $value->material_name; ?>
  </td>
  <td>
      <?php echo $value->quantity; ?>
  </td>
  <td>
      <input type="number" class="form-control" name="qty_reject_<?php echo $i; ?>">
  </td>
  <td>
      <input type="text" class="form-control" name="note_<?php echo $i; ?>">
      <input type="hidden" name="qty_received_<?php echo $i; ?>" value="<?php echo $value->quantity; ?>">
  </td>
</tr>
<?php $i++; } ?>
</table>
<input type="hidden" name="total_record" value="<?php echo ($i -1); ?>">
<div class="panel-footer">
    <a class="btn btn-danger" role="button" data-toggle="collapse" href="#approve-qc-manual" aria-expanded="false" aria-controls="approve-qc-manual">
    Cancel
    </a>
  <button type="submit" id="save-button" class="btn btn-primary">Approve</button>
        </div>
</div><!-- /.panel -->
</form>
</div>


<!-- PAGE CONTENT ENDS -->
</div><!-- /.col-xs-12 -->
</div><!-- /.row -->
</div><!-- /.page-content -->
</div><!-- /.main-content-inner -->
</div><!-- /.main-content -->

