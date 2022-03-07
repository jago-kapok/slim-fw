<div class="main-content">
        <div class="main-content-inner">

          <?php $this->renderFeedbackMessages(); // Render message success or not?>

          <form method="post"  action="<?php echo Config::get('URL') . 'pr/createDraftPo/'; ?>">
          <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover ExcelTable2007">
            <thead>
            <tr class="warning">
              <td colspan="9">
                    <div class="btn-group btn-corner">
                          <a href="<?php echo Config::get('URL') . 'pr/newPr/'; ?>" role="button" data-toggle="modal" class="btn btn-info btn-minier">
                              <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Buat PR
                          </a>
                    </div>
              </td>
            </tr>
            <tr>
              <th><INPUT type="checkbox" onchange="checkAll(this)" name="chk[]" /></th>
              <th class="text-right">No</th>
              <th class="text-left">Material Code</th>
              <th class="text-left">Material Name</th>
              <th class="text-right">Quantity</th>
              <th class="text-left">Unit</th>
              <th class="text-left">Notes</th>
              <th class="text-left">Date</th>
              <th class="text-left">User</th>
            </tr>
            </thead>

            <tbody>
            <?php
            $no = ($this->page * $this->limit) - ($this->limit - 1);
            foreach($this->po_list as $key => $value) {
                echo '<tr>';
                echo '<td class="text-center">
                  <input name="uid[' . $no . ']' . '" type="checkbox" value="' . $value->uid .'" style="text-decoration: underline;">
                  <input name="material_code[' . $no . ']' . '" type="hidden" value="' . $value->material_code .'" style="text-decoration: underline;">
                  <input name="material_specification[' . $no . ']' . '" type="hidden" value="' . $value->material_specification .'" style="text-decoration: underline;">
                  <input name="transaction_reference[' . $no . ']' . '" type="hidden" value="' . $value->transaction_reference .'" style="text-decoration: underline;">
                  </td>';
                echo '<td class="text-right">' . $no . '</td>';
                echo '<td>' . $value->material_code . '</td>';
                if (!empty($value->material_code)) {
                  echo '<td>' . $value->material_name . '</td>';
                } else {
                  echo '<td>' . $value->material_specification . '</td>';
                }
                echo '<td class="text-right"><input name="quantity[' . $no . ']' . '" type="number" value="' . floatval($value->quantity) .'" class="text-right" style="text-decoration: underline;"></td>';
                echo '<td><input name="unit[' . $no . ']' . '" type="text" value="' . $value->unit .'" style="text-decoration: underline;"></td>';
                echo '<td>' . $value->note . '</td>';
                echo '<td>' . date("d M, y", strtotime($value->created_timestamp)) . '</td>';
                echo '<td>' . $value->full_name . '</td>';
                echo "</tr>";
            $no++;
          }?>
              
                
              
            </tbody>
            </table>

          </div>
          <div class="text-right">
            <input type="hidden" name="button-action" id="button-action" class="btn btn-info">
            <div class="btn-group btn-corner">
              <input type="submit" class="btn btn-danger" value="Delete" onclick="DoSubmit('Delete');">
              <input type="submit" class="btn btn-success" value="Buat Nota Pembelian" onclick="DoSubmit('Buat Nota Pembelian');">
              <input type="submit" class="btn btn-info" value="Buat Draft PO" onclick="DoSubmit('Buat Draft PO');">
            </div>
          </div>
          </form>
          <br>
          <hr>
          <?php echo $this->pagination;?>
  </div><!-- /.main-content-inner -->
</div><!-- /.main-content -->


<!-- MODAL NEW PR -->
<div class="modal fade" id="new-pr" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
    <form method="post" class="form-horizontal" action="<?php echo Config::get('URL') . 'po/submitPr/';?>">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Buat Purchase Request</h4>
      </div>
      <div class="modal-body">
          <div class="form-group">
              <label class="col-sm-3 control-label">Supplier Name</label>
              <div class="col-sm-9">
                <div class="input-group">
                    <input type="text" class="form-control search-query" placeholder="klik browse untuk memilih" name="contact_id" id="contact_id_new"/>
                  <span class="input-group-btn">
                  <a href="#" class="btn btn-purple btn-sm" onclick="SelectName1()">Browse</a>
                  <script type="text/javascript">
                  var popup;
                  function SelectName1() {
                  var left = (screen.width/2)-(500/2);
                  var top = (screen.height/2)-(500/2);
                  popup = window.open("<?php echo Config::get('URL') . 'contact/selectContact/?id=_new'; ?>", "Popup", "width=500, height=500, top="+top+", left="+left);
                  popup.focus();
                  }
                  </script>
                  </span>
                </div>
              </div>
          </div>

          <div class="form-group">
            <label class="col-sm-3 control-label">Due Date</label>
            <div class="col-sm-9">
              <input type="text" name="due_date" class="form-control datepicker" value="<?php echo date("Y-m-d");?>" data-date-format="yyyy-mm-dd">
            </div>
          </div>



          <div class="form-group">
            <label class="col-sm-3 control-label">Keterangan</label>
            <div class="col-sm-9">
              <textarea name="note" class="form-control" ></textarea>
            </div>
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-primary">OK</button>
      </div>
      </form>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- /MODAL NEW PR -->
<script type="text/javascript">
  function DoSubmit(valueButton){
    document.getElementById('button-action').value = valueButton;
  return true;
}
</script>