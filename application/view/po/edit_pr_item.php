<?php //Debuger::jam($this->pr_data); ?>
<div class="main-content">
                <div class="main-content-inner">
                    <div class="breadcrumbs ace-save-state" id="breadcrumbs">
                        <ul class="breadcrumb">
                            <li>
                                <a href="<?php echo Config::get('URL') . 'contact/index/'; ?>"><span class="glyphicon glyphicon-th-list" aria-hidden="true"></span></a>
                            </li>
                            <li>
                                <a href="#new-contact" role="button" data-toggle="modal"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Buat Baru</a>
                            </li>
                            
                        </ul><!-- /.breadcrumb -->

                        <div class="nav-search" id="nav-search">
                            <form class="form-search" method="get" action="<?php echo Config::get('URL') . 'contact/search/';?>">
                              <span class="input-icon">
                                <input type="text" placeholder="Search ..." class="nav-search-input" id="nav-search-input" autocomplete="off" name="find" value="<?php if(isset($_GET['find'])){ echo $_GET['find'];}?>" />
                                <i class="ace-icon fa fa-search nav-search-icon"></i>
                              </span>
                            </form>
                        </div><!-- /.nav-search -->
                    </div>



<div class="page-content">
                        <div class="row">
                            <div class="col-xs-12">
                                <!-- PAGE CONTENT BEGINS -->
<form method="POST" action="<?php echo Config::get('URL') . 'po/saveEditPrItem/' . $this->pr_data->uid;?>">
<div class="panel panel-primary">
        <div class="panel-heading">
          <h3 class="panel-title">Edit Daftar Barang</h3>
        </div>

            
            <table class="table table-bordered table-hover table-striped ">
              <?php if (empty($this->pr_data->material_code)) { ?>
                  <tr>
                      <td>Budget:</td>
                      <td>
                           <select name="budget_category" class="form-control">
                            <?php
                              foreach ($this->budget_category as $key => $value) {
                                if ($value->item_name == $this->pr_data->budget_category) {
                                  echo '<option value="' . $value->item_name . '" selected="selected">' . $value->item_name . '</option>';
                                } else {
                                  echo '<option value="' . $value->item_name . '">' . $value->item_name . '</option>';
                                }
                                
                              }
                            ?>
                          </select>
                      </td>

                      <td>Item Pembelian:</td>
                    <td>
                      <textarea name="budget_item" class="form-control" placeholder="Item "><?php echo $this->pr_data->budget_item; ?></textarea>
                    </td>
                  </tr>

              <?php  } elseif (!empty($this->pr_data->material_code)) { ?>
                <tr>
                    <td>Kode dan Nama Barang</td>
                    
                    <td colspan="3">
                        <div class="input-group" style="width: 100%">
                            <input type="text" class="form-control" placeholder="klik tombol pilih untuk memilih kode material" name="material_code" id="material_code_new" value="<?php echo $this->pr_data->material_code; ?>" />
                          <span class="input-group-btn">
                          <a href="#" class="btn btn-purple btn-sm " onclick="selectMaterial()">Pilih</a>
                          <script type="text/javascript">
                          var popup;
                          function selectMaterial() {
                          var left = (screen.width/2)-(500/2);
                          var top = (screen.height/2)-(500/2);
                          popup = window.open("<?php echo Config::get('URL') . 'inventory/selectMaterial/?id=_new'; ?>", "Popup", "width=500, height=500, top="+top+", left="+left);
                          popup.focus();
                          }
                          </script>
                          </span>
                        </div>
                    </td>
                </tr>
              <?php } ?>
                <tr>
                    <td>Harga Barang:</td>
                    <td>
                        <input type="number" name="purchase_price"  class="form-control text-right" 
                        min="0" step="0.001"
                        placeholder="gunakan angka saja" value="<?php echo floatval($this->pr_data->purchase_price); ?>"
                         />
                    </td>
                    <td>Diskon (dalam uang):</td>
                    <td>
                        <input type="number" pattern="[0-9]+([\,|\.][0-9]+)?" name="purchase_price_discount" id="price_discount"  class="form-control text-right" 
                        step="0.001"
                        placeholder="gunakan angka saja" value="<?php echo floatval($this->pr_data->purchase_price_discount); ?>"
                         />
                    </td>
                </tr>

                <tr>
                    <td>Currency:</td>
                    <td>
                         <select name="currency" class="form-control">
                                <option value="IDR" <?php if($this->pr_data->purchase_currency == 'IDR') echo "selected=selected";?>>IDR</option>
                                <option value="USD" <?php if($this->pr_data->purchase_currency == 'USD') echo "selected=selected";?>>USD</option>
                                <option value="EUR" <?php if($this->pr_data->purchase_currency == 'EUR') echo "selected=selected";?>>EUR</option>
                                <option value="CHF" <?php if($this->pr_data->purchase_currency == 'CHF') echo "selected=selected";?>>CHF</option>
                        </select>
                    </td>

                    <td>Jumlah:</td><td><input type="number" name="quantity" min="0" step="0.01" class="form-control text-right" value="<?php echo floatval($this->pr_data->quantity); ?>"></td>
                </tr>
                <tr>
                  

                    <td>Satuan:</td>
                    <td><input type="text" name="unit" class="form-control" value="<?php echo $this->pr_data->unit; ?>"></td>
                    <td>Pajak (%):</td><td><input type="number" name="purchase_tax" placeholder="masukan angka, misal: 10" class="form-control text-right" value="<?php echo floatval($this->pr_data->purchase_tax); ?>"/>
                    </td>
                    
                </tr>
                <tr>
                    <td>Packing:</td>
                    <td><input type="text" name="packaging" class="form-control" value="<?php echo $this->pr_data->packaging; ?>"></td>
                    <td>Spesifikasi:</td>
                    <td>
                      <textarea name="material_specification" class="form-control"><?php echo FormaterModel::br2nl($this->pr_data->material_specification); ?></textarea>
                    </td>
                </tr>
                <tr>
                    <td>Alasan Pembelian:</td><td colspan="3"><input type="text" name="reason_request" class="form-control" value="<?php echo $this->pr_data->reason_request; ?>"></td>
                </tr>
            </table>   
    <input type="hidden" name="po_number" value="<?php echo $this->pr_data->transaction_number; ?>"/>

    <div class="modal-footer">
            <a href="javascript: history.go(-1)" class="btn btn-danger">Cancel</a>
            <button class="btn btn-primary" type="submit">Update</button>
    </div>
       
</div>
 </form>

<!-- PAGE CONTENT ENDS -->
              </div><!-- /.col -->
            </div><!-- /.row -->
          </div><!-- /.page-content -->
        </div>
      </div><!-- /.main-content -->