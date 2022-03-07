<div class="main-content">
        <div class="main-content-inner">
                <!-- #section:basics/content.breadcrumbs -->
                <div class="breadcrumbs" id="breadcrumbs">
                    <script type="text/javascript">
                        try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
                    </script>

                    <!-- #section:basics/content.searchbox -->
                    <div class="nav-search" id="nav-search">
                        <form class="form-search" method="get" action="<?php echo Config::get('URL');?>inventory/editStock/">
                            <span class="input-icon">
                                <input type="text" placeholder="Search ..." class="nav-search-input" id="nav-search-input" autocomplete="off" name="sn" />
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
                            
<?php
 $this->renderFeedbackMessages(); // Render message success or not?>


<!-- Use modal box as design -->
    <form method="post" action="<?php echo Config::get('URL') . 'inventory/updateMasterMaterial/?material_code=' . urlencode($this->result->material_code); ?>" >

    <div class="row">
    <div class="col-xs-12 col-sm-12">
      <div class="panel panel-primary">
          <div class="panel-heading">
            <h3 class="panel-title">Edit Material <?php echo $this->result->material_name; ?></h3>
          </div>
          <table class="table table-striped table-bordered table-hover">
            <tr>
              <td><strong>Nama:</strong></td>
              <td colspan="3">
                <input name ="material_name" class="form-control" type="text" value="<?php echo $this->result->material_name; ?>"/>
              </td>
              <td><strong>Kode:</strong></td>
              <td>
                <?php echo $this->result->material_code; ?>
                <input name="material_code" type="hidden" value="<?php echo $this->result->material_code; ?>"/>
              </td>
            </tr>
            <tr>
              <td><strong>Stock:</strong></td>
              <td>
                <?php echo number_format($this->result->quantity_stock, 2); ?>
              </td>
              <td><strong>Safety Stock:</strong></td>
              <td>
                <input type="number" name ="minimum_balance" class="form-control align-right" placeholder="safety stock" value="<?php echo floatval($this->result->minimum_balance); ?>">
                <input type="hidden" name ="minimum_balance_old" value="<?php echo $this->result->minimum_balance; ?>">
              </td>
              <td><strong>Satuan:</strong></td>
              <td>
                <input type="text" name ="unit" class="form-control" value="<?php echo $this->result->unit; ?>">
                <input type="hidden" name ="unit_old" value="<?php echo $this->result->unit; ?>">
              </td>
            </tr>
            <tr>
              <td><strong>Harga Pembelian:</strong></td>
              <td><input type="text"  class="form-control align-right" name="purchase_price" value="<?php echo floatval($this->result->purchase_price); ?>"/></td>
              <td><strong>Currency Pembelian:</strong></td>
              <td>
                <select class="form-control" name="purchase_currency">
                    <option value="IDR" <?php if($this->result->purchase_currency == "IDR") echo "selected=selected";?>>IDR</option>
                    <option value="USD" <?php if($this->result->purchase_currency == "USD") echo "selected=selected";?>>USD</option>
                    <option value="EUR" <?php if($this->result->purchase_currency == "EUR") echo "selected=selected";?>>EUR</option>
                    <option value="CHF" <?php if($this->result->purchase_currency == "CHF") echo "selected=selected";?>>CHF</option>
                </select>
              </td>
              <td><strong>Satuan Pembelian:</strong></td>
              <td><input type="text"  class="form-control align-right" name="purchase_unit" value="<?php echo $this->result->purchase_unit; ?>"/></td>
            </tr>
            <tr>
              <td><strong>Harga Penjualan:</strong></td>
              <td>
                <input type="text"  class="form-control align-right" name="selling_price" value="<?php echo $this->result->selling_price; ?>"/>
              </td>
              <td><strong>Currency Penjualan:</strong></td>
              <td>
                <select class="form-control" name="selling_currency">
                    <option value="IDR" <?php if($this->result->selling_currency == "IDR") echo "selected=selected";?>>IDR</option>
                    <option value="USD" <?php if($this->result->selling_currency == "USD") echo "selected=selected";?>>USD</option>
                </select>
              </td>
              <td><strong>Supplier Code:</strong></td>
              <td>
                <div class="input-group">
                    <input type="text" class="form-control search-query" placeholder="klik browse untuk memilih" name="contact_id" id="contact_id_new" value="<?php echo $this->result->supplier_id; ?>"/>
                  <span class="input-group-btn">
                  <a href="#" class="btn btn-purple btn-sm" onclick="SelectContact()">Browse</a>
                  <script type="text/javascript">
                  var popup;
                  function SelectContact() {
                  var left = (screen.width/2)-(500/2);
                  var top = (screen.height/2)-(500/2);
                  popup = window.open("<?php echo Config::get('URL') . 'contact/selectContact/?id=_new'; ?>", "Popup", "width=500, height=500, top="+top+", left="+left);
                  popup.focus();
                  }
                  </script>
                  </span>
                </div>
              </td>
            </tr>
            <tr>
              <td colspan="2"><strong>Tipe Material:</strong></td>
              <td colspan="4">
                <select name="material_type" id="material-type">
                      <option value="">Pilih Tipe Material</option>
                      <option value="1" <?=($this->result->material_type == 1) ? 'selected="selected"' : ''?>>Raw Materials/Bahan Baku</option>
                      <option value="2" <?=($this->result->material_type == 2) ? 'selected="selected"' : ''?>>Work In Progress (WIP)/Setengah Jadi</option>
                      <option value="3" <?=($this->result->material_type == 3) ? 'selected="selected"' : ''?>>Finished Products/Produk Jadi</option>
                      <option value="4" <?=($this->result->material_type == 4) ? 'selected="selected"' : ''?>>Trading Goods/(Bahan Baku Sekaligus Produk Jadi)</option>
                      <option value="5" <?=($this->result->material_type == 5) ? 'selected="selected"' : ''?>>Services/Jasa</option>
                      <option value="6" <?=($this->result->material_type == 6) ? 'selected="selected"' : ''?>>Production Resources/Tools/Alat Produksi</option>
                      <option value="7" <?=($this->result->material_type == 7) ? 'selected="selected"' : ''?>>Consumed Operating Supplies/Alat Bantu Produksi Yang Terkonsumsi</option>
                      <option value="10" <?=($this->result->material_type == 10) ? 'selected="selected"' : ''?>>Asset</option>
                </select>
                <input type="hidden" name ="material_category_old" value="<?php echo $this->result->material_category; ?>">
              </td>
            </tr>
            <tr id="raw-material" <?=($this->result->material_type == 1) ? '' : 'style="display: none;"'?>>
                <td colspan="2"><strong>Kategori Raw Material/Bahan Baku</strong></td>
                <td colspan="4">
                    <select class="form-control" id="raw-material-select" <?=($this->result->material_type == 1) ? 'name="material_category"' : ''?>>
                        <option value="">Pilih Kategori</option>
                        <?php foreach($this->raw_material as $key => $category) { ?>
                            <option value="<?php echo $category->uid; ?>" <?=($this->result->material_category == $category->uid) ? 'selected="selected"' : ''?>><?php echo $category->value;?></option>
                        <?php } ?>
                    </select>
                </td>
            </tr>

            <tr id="wip" <?=($this->result->material_type == 2) ? '' : 'style="display: none;"'?>>
                <td colspan="2"><strong>Kategori Work In Progress (WIP)</strong></td>
                <td colspan="4">
                    <select class="form-control" id="wip-select" <?=($this->result->material_type == 2) ? 'name="material_category"' : ''?>>
                        <option value="">Pilih Kategori</option>
                        <?php foreach($this->wip as $key => $category) { ?>
                            <option value="<?php echo $category->uid; ?>" <?=($this->result->material_category == $category->uid) ? 'selected="selected"' : ''?>><?php echo $category->value;?></option>
                        <?php } ?>
                    </select>
                </td>
            </tr>

            <tr id="finished-product" <?=($this->result->material_type == 3) ? '' : 'style="display: none;"'?>>
                <td colspan="2"><strong>Kategori Finished Products/Produk Jadi</strong></td>
                <td colspan="4">
                    <select class="form-control" id="finished-product-select" <?=($this->result->material_type == 3) ? 'name="material_category"' : ''?>>
                        <option value="">Pilih Kategori</option>
                        <?php foreach($this->finished_product as $key => $category) { ?>
                            <option value="<?php echo $category->uid; ?>" <?=($this->result->material_category == $category->uid) ? 'selected="selected"' : ''?>><?php echo $category->value;?></option>
                        <?php } ?>
                    </select>
                </td>
            </tr>

            <tr id="trading-goods" <?=($this->result->material_type == 4) ? '' : 'style="display: none;"'?>>
                <td colspan="2"><strong>Kategori Trading Goods (Baha Baku Sekaligus Produk Jadi)</strong></td>
                <td colspan="4">
                    <select class="form-control" id="trading-goods-select" <?=($this->result->material_type == 4) ? 'name="material_category"' : ''?>>
                        <option value="">Pilih Kategori</option>
                        <?php foreach($this->trading_goods as $key => $category) { ?>
                            <option value="<?php echo $category->uid; ?>" <?=($this->result->material_category == $category->uid) ? 'selected="selected"' : ''?>><?php echo $category->value;?></option>
                        <?php } ?>
                    </select>
                </td>
            </tr>

            <tr id="service-product" <?=($this->result->material_type == 5) ? '' : 'style="display: none;"'?>>
                <td colspan="2"><strong>Kategori Service/Jasa</strong></td>
                <td colspan="4">
                    <select class="form-control" id="service-product-select" <?=($this->result->material_type == 5) ? 'name="material_category"' : ''?>>
                        <option value="">Pilih Kategori</option>
                        <?php foreach($this->service_product as $key => $category) { ?>
                            <option value="<?php echo $category->uid; ?>" <?=($this->result->material_category == $category->uid) ? 'selected="selected"' : ''?>><?php echo $category->value;?></option>
                        <?php } ?>
                    </select>
                </td>
            </tr>

            <tr id="production-tools" <?=($this->result->material_type == 6) ? '' : 'style="display: none;"'?>>
                <td colspan="2"><strong>Kategori Production Resources/Tools</strong></td>
                <td colspan="4">
                    <select class="form-control" id="production-tools-select" <?=($this->result->material_type == 6) ? 'name="material_category"' : ''?>>
                        <option value="">Pilih Kategori</option>
                        <?php foreach($this->production_tools as $key => $category) { ?>
                            <option value="<?php echo $category->uid; ?>" <?=($this->result->material_category == $category->uid) ? 'selected="selected"' : ''?>><?php echo $category->value;?></option>
                        <?php } ?>
                    </select>
                </td>
            </tr>

            <tr id="operating-supplies" <?=($this->result->material_type == 7) ? '' : 'style="display: none;"'?>>
                <td colspan="2"><strong>Kategori Consumed Operating Supplies</strong></td>
                <td colspan="4">
                    <select class="form-control" id="operating-supplies-select" <?=($this->result->material_type == 7) ? 'name="material_category"' : ''?>>
                        <option value="">Pilih Kategori</option>
                        <?php foreach($this->operating_supplies as $key => $category) { ?>
                            <option value="<?php echo $category->uid; ?>" <?=($this->result->material_category == $category->uid) ? 'selected="selected"' : ''?>><?php echo $category->value;?></option>
                        <?php } ?>
                    </select>
                </td>
            </tr>

            <tr id="asset-material" <?=($this->result->material_type == 10) ? '' : 'style="display: none;"'?>>
                <td colspan="2"><strong>Kategori Asset</strong></td>
                <td colspan="4">
                    <select class="form-control" id="asset-material-select" <?=($this->result->material_type == 10) ? 'name="material_category"' : ''?>>
                        <option value="">Pilih Kategori</option>
                        <?php foreach($this->asset as $key => $category) { ?>
                            <option value="<?php echo $category->uid; ?>" <?=($this->result->material_category == $category->uid) ? 'selected="selected"' : ''?>><?php echo $category->value;?></option>
                        <?php } ?>
                    </select>
                </td>
            </tr>

            <tr>
              <td colspan="2"><strong>Material Description:</strong></td>
              <td colspan="4">
                <textarea name ="note" class="autosize-transition form-control" placeholder="Keterangan tambahan"><?php echo $this->result->note; ?></textarea>
                                <input type="hidden" name ="note_old" value="<?php echo $this->result->note; ?>">
              </td>
            </tr>
                          
          </table>
      </div>
    </div><!-- /.col-sm-12 -->
</div><!-- ./row -->

                    <div class="align-right">
                        <div class="btn-group btn-corner" role="group">
                            
                                <button class="btn btn-danger">
                                    <a href="javascript: history.go(-1)">
                                    <i class="icon-remove bigger-110"></i>
                                    Cancel
                                    </a>
                                </button>
                            
                            
                            <button class="btn btn-default" type="reset">
                                <i class="icon-undo bigger-110"></i>
                                Reset
                            </button>

                            <button class="btn btn-info" type="submit">
                                <i class="icon-ok bigger-110"></i>
                                Update
                            </button>
                        </div>
                    </div>
                
    </form>

<div class="hr hr10 hr-double"></div>

<section id="detail-material">

<!-- #section:elements.tab -->
                                        <div class="tabbable">
                                            <ul class="nav nav-tabs" id="myTab">
                                                <li class="active">
                                                    <a data-toggle="tab" href="#stock">
                                                        Stock
                                                    </a>
                                                </li>

                                                
                                                <?php if ($this->result->material_type == 3) {
                                                  echo '<li>
                                                    <a data-toggle="tab" href="#serial-number">
                                                        Serial Number
                                                    </a>
                                                </li>';
                                                } ?>

                                                <li>
                                                    <a data-toggle="tab" href="#stock-in">
                                                        Log Masuk
                                                    </a>
                                                </li>

                                                 <li>
                                                    <a data-toggle="tab" href="#stock-out">
                                                       Log Keluar
                                                    </a>
                                                </li>

                                                <li>
                                                    <a data-toggle="tab" href="#adjustment">
                                                        Log Adjustment
                                                    </a>
                                                </li>
                                                <?php if ($this->result->material_type == 3) {
                                                  echo '<li>
                                                            <a data-toggle="tab" href="#price-log">
                                                                Log Harga
                                                            </a>
                                                        </li>';
                                                } ?>
                                                
                                            </ul>

                                            <div class="tab-content">
                                                <div id="stock" class="tab-pane fade in active">
                                                <form method="post" action="<?php echo Config::get('URL') . 'inventory/updateStock/?material_code=' . urlencode($this->result->material_code); ?>" >

                                                <div class="table-responsive">
                                                    <table class="table table-striped table-bordered table-hover">
                                                        <thead>
                                                        <tr>
                                                        <th>No</th>
                                                        <th class="center">Tgl Masuk</th>
                                                        <th class="center">Lot Number</th>
                                                        <th class="center">Jumlah</th>
                                                        </tr>
                                                        </thead>

                                                        <tbody>
                                                        <?php
                                                        $no = 1;
                                                        $jumlah=0;
                                                        foreach($this->stock as $key => $value) {
                                                        echo "<tr>";
                                                        echo '<td>' . $no . '</td>';
                                                        echo '<td><input type="text" name="created_timestamp_' . $no . '" class="form-control datepicker" data-date-format="yyyy-mm-dd" value="' . date('Y-m-d', strtotime($value->created_timestamp)) . '"></td>';
                                                        echo '<td>
                                                            <input class="form-control" name="transaction_number_' . $no . '" type="text" value="' . $value->transaction_number . '">
                                                            <input name="uid_' . $no . '" type="hidden" value="' . $value->uid . '">
                                                            </td>';
                                                        echo '<td>
                                                            <input class="form-control text-right" name="quantity_stock_' . $no . '" type="number" value="' . floatval($value->quantity_stock) . '" min="0"></td>';
                                                        echo "</tr>";
                                                        $no++;
                                                        }
                                                        echo '<tr class="info">
                                                        <td colspan="4" class="text-center">Masukkan Stock Baru</td>
                                                        </tr>';
                                                        for ($i=1; $i <= 10; $i++) { 
                                                            echo '<td>' . $i . '</td>';
                                                            echo '<td><input type="text" name="incoming_date_' . $i . '" class="form-control datepicker" data-date-format="yyyy-mm-dd" autocomplete="off"></td>';
                                                            echo '<td>
                                                                <input class="form-control" name="lot_number_' . $i . '" type="text" autocomplete="off">
                                                                </td>';
                                                            echo '<td>
                                                                <input class="form-control text-right" name="new_stock_' . $i . '" type="number" autocomplete="off"></td>';
                                                            echo "</tr>";
                                                        }
                                                        ?>
                                                        </tbody>
                                                        </table>
                                                        <input name="total_input" type="hidden" value="<?php echo $no; ?>">

                                                        </div>
                                                        <div class="align-right">
                                                                <div class="btn-group btn-corner" role="group">
                                                                    
                                                                        <button class="btn btn-danger">
                                                                            <a href="javascript: history.go(-1)">
                                                                            <i class="icon-remove bigger-110"></i>
                                                                            Cancel
                                                                            </a>
                                                                        </button>
                                                                    
                                                                    
                                                                    <button class="btn btn-default" type="reset">
                                                                        <i class="icon-undo bigger-110"></i>
                                                                        Reset
                                                                    </button>

                                                                    <button class="btn btn-info" type="submit">
                                                                        <i class="icon-ok bigger-110"></i>
                                                                        Update
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        
                                            </form>
                                                        <ul class="pager pull-right">
                                                        <?php if($this->number != 1) { ?>
                                                                <li class="previous">
                                                                    <a href="<?php echo $this->prev;?>">← Prev</a>
                                                                </li>
                                                        <?php } ?>
                                                            <li class="next">
                                                                <a href="<?php echo $this->next;?>">Next →</a>
                                                            </li>
                                                        </ul>
                                                </div>

                                                <?php if ($this->result->material_type == 3) {
                                                  include('edit_material_serial_number.php');
                                                } ?>

                                                

                                                <div id="stock-in" class="tab-pane fade">
                                                <div class="table-responsive">
                                                    <table class="table table-striped table-bordered table-hover">
                                                        <thead>
                                                        <tr>
                                                        <th>No</th>
                                                        <th class="center">Incoming Date</th>
                                                        <th class="center">#Transaction</th>
                                                        <th class="center">Quantity</th>
                                                        <th class="center">#SN</th>
                                                        </tr>
                                                        </thead>

                                                        <tbody>
                                                        <?php
                                                        $no = $this->number;
                                                        $jumlah=0;
                                                        foreach($this->stockIn as $key => $value) {
                                                        echo "<tr>";
                                                        echo '<td>' . $no . '</td>';
                                                        echo '<td>' . date("d-M-Y", strtotime($value->created_timestamp)) . '</td>';
                                                        echo '<td class="align-right">' . $value->transaction_number . '</td>';
                                                        echo '<td class="align-right">' . number_format($value->quantity_received, 2) . '</td>';
                                                        echo '<td>' . $value->serial_number . '</td>';
                                                        echo "</tr>";
                                                        $no++;
                                                        }      
                                                        ?>

                                                        </tbody>
                                                        </table>
                                                        </div>
                                                        <ul class="pager pull-right">
                                                        <?php if($this->number != 1) { ?>
                                                                <li class="previous">
                                                                    <a href="<?php echo $this->prev;?>">← Prev</a>
                                                                </li>
                                                        <?php } ?>
                                                            <li class="next">
                                                                <a href="<?php echo $this->next;?>">Next →</a>
                                                            </li>
                                                        </ul>
                                                </div>

                                                <div id="stock-out" class="tab-pane fade">
                                                <div class="table-responsive">
                                                    <table class="table table-striped table-bordered table-hover">
                                                        <thead>
                                                        <tr>
                                                        <th>No</th>
                                                        <th class="center">Tanggal Kirim</th>
                                                        <th class="center">#Transaction</th>
                                                        <th class="center">Quantity</th>
                                                        <th class="center">#SN</th>
                                                        </tr>
                                                        </thead>

                                                        <tbody>
                                                        <?php
                                                        $no = $this->number;
                                                        foreach($this->stockOut as $key => $value) {
                                                        echo "<tr>";
                                                         echo '<td>' . $no . '</td>';
                                                         echo '<td>' . date("d-M-Y", strtotime($value->created_timestamp)) . '</td>';
                                                        echo '<td class="align-right">' . $value->transaction_number . '</td>';
                                                        echo '<td class="align-right">' . number_format($value->quantity_delivered, 2) . '</td>';
                                                        echo '<td>' . $value->serial_number . '</td>';
                                                        echo "</tr>";
                                                        $no++;
                                                        }
                                                        ?>

                                                        </tbody>
                                                        </table>
                                                        </div>
                                                        <ul class="pager pull-right">
                                                        <?php if($this->number != 1) { ?>
                                                                <li class="previous">
                                                                    <a href="<?php echo $this->prev;?>">← Prev</a>
                                                                </li>
                                                        <?php } ?>
                                                            <li class="next">
                                                                <a href="<?php echo $this->next;?>">Next →</a>
                                                            </li>
                                                        </ul>
                                                </div>

                                                <div id="adjustment" class="tab-pane fade">
                                                    <div class="table-responsive">
                                                    <table class="table table-striped table-bordered table-hover">
                                                        <thead>
                                                        <tr>
                                                        <th>Date</th>
                                                        <th class="center">No</th>
                                                        <th class="center">User</th>
                                                        <th class="center">Nama Barang</th>
                                                        <th class="center">Stock</th>
                                                        <th class="center">Safety Stock</th>
                                                        <th class="center">Harga Jual</th>
                                                        <th class="center">Keterangan</th>
                                                        </tr>
                                                        </thead>

                                                        <tbody>
                                                        <?php
                                                        $no = $this->number;
                                                        foreach($this->stockAdjustment as $key => $value) {
                                                        echo "<tr>";
                                                         echo '<td>' . date("d-M-Y", strtotime($value->created_timestamp)) . '</td>';
                                                         echo '<td>' . $no . '</td>';
                                                        echo '<td>' . $value->creator_id . '</td>';
                                                        echo '<td>' . $value->material_name . '</a></td>';
                                                        echo '<td class="align-right">' . number_format($value->balance, 2) . '</td>';
                                                        echo '<td class="align-right">' . number_format($value->minimum_balance, 2) . '</td>';
                                                        echo '<td>' . number_format($value->selling_price, 2) . '</td>';
                                                        echo '<td>' . $value->note . '</td>';
                                                        echo "</tr>";
                                                        $no++;
                                                        }
                                                        ?>

                                                        </tbody>
                                                        </table>
                                                        </div>
                                                        <ul class="pager pull-right">
                                                        <?php if($this->number != 1) { ?>
                                                                <li class="previous">
                                                                    <a href="<?php echo $this->prev;?>">← Prev</a>
                                                                </li>
                                                        <?php } ?>
                                                            <li class="next">
                                                                <a href="<?php echo $this->next;?>">Next →</a>
                                                            </li>
                                                        </ul>
                                                </div>

                                                <?php if ($this->result->material_type == 3) {
                                                  include('edit_material_log_harga.php');
                                                } ?>

                                            </div>
                                        </div>
</section>
</div><!-- /.main-content-inner -->
</div><!-- /.main-content -->

<script type="text/javascript">
    function showHideElement() {
        var el = document.getElementById("material-type");
        el.addEventListener("change", function() {
          var elems = document.querySelectorAll('#raw-material,#wip,#finished-product,#trading-goods,#service-product,#production-tools,#operating-supplies,#asset-material')
          for (var i = 0; i < elems.length; i++) {
            elems[i].style.display = 'none';
          }

          var elems = document.querySelectorAll('#raw-material-select,#wip-select,#finished-product-select,#trading-goods-select,#service-product-select,#production-tools-select,#operating-supplies-select,#asset-material-select')
          for (var i = 0; i < elems.length; i++) {
            elems[i].name = '';
          }

            if (this.selectedIndex === 1) {
                document.getElementById('raw-material').style.display = 'table-row';
                document.getElementById('raw-material-select').name = 'material_category';
            } else if (this.selectedIndex === 2) {
                document.getElementById('wip').style.display = 'table-row';
                document.getElementById('wip-select').name = 'material_category';
            } else if (this.selectedIndex === 3) {
                document.getElementById('finished-product').style.display = 'table-row';
                document.getElementById('finished-product-select').name = 'material_category';
            } else if (this.selectedIndex === 4) {
                document.getElementById('trading-goods').style.display = 'table-row';
                document.getElementById('trading-goods-select').name = 'material_category';
            } else if (this.selectedIndex === 5) {
                document.getElementById('service-product').style.display = 'table-row';
                document.getElementById('service-product-select').name = 'material_category';
            } else if (this.selectedIndex === 6) {
                document.getElementById('production-tools').style.display = 'table-row';
                document.getElementById('production-tools-select').name = 'material_category';
            } else if (this.selectedIndex === 7) {
                document.getElementById('operating-supplies').style.display = 'table-row';
                document.getElementById('operating-supplies-select').name = 'material_category';
            } else if (this.selectedIndex === 8) {
                document.getElementById('asset-material').style.display = 'table-row';
                document.getElementById('asset-material-select').name = 'material_category';
            }

        }, false);
    }
    showHideElement();
</script>
