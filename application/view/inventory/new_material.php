<div class="main-content">
    <div class="main-content-inner">
         <!-- /section:basics/content.breadcrumbs -->
            <div class="page-content">
                <div class="row">
                    <div class="col-xs-12">
                    <!-- PAGE CONTENT BEGINS -->
                    <?php $this->renderFeedbackMessages(); // Render message success or not?>
                    <form method="post" action="<?php echo Config::get('URL');?>inventory/submitMasterIn">
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <h1 class="panel-title align-center">Buat Material Baru</h1>
                            </div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-xs-12 col-sm-12">
                                        <label>Nama Barang</label>

                                        <div>
                                            <input name ="material_name" class="form-control" type="text" placeholder="Nama Barang"/>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="col-xs-12 col-sm-7">
                                        <div class="form-group">
                                            <label for="form-field-username">Satuan</label>
                                            <div>
                                                <input type="text" name ="unit" class="form-control" placeholder="Misal Kg">
                                            </div>
                                        </div>

                                        <div class="space-4"></div>

                                        <div class="form-group">
                                            <label>Tipe Material</label>
                                            <div>
                                                <select name="material_type" id="material-type">
                                                        <option value="">Pilih Tipe Material</option>
                                                        <option value="1-RM">Raw Materials/Bahan Baku</option>
                                                        <option value="2-WIP">Work In Progress (WIP)/Setengah Jadi</option>
                                                        <option value="3-FP">Finished Products/Produk Jadi</option>
                                                        <option value="4-TG">Trading Goods/(Bahan Baku Sekaligus Produk Jadi)</option>
                                                        <option value="5-SV">Services/Jasa</option>
                                                        <option value="6-PRT">Production Resources/Tools/Alat Produksi</option>
                                                        <option value="7-COP">Consumed Operating Supplies/Alat Bantu Produksi Yang Terkonsumsi</option>
                                                        <option value="10-AST">Asset</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="space-4"></div>

                                        <div class="form-group" id="raw-material" style="display: none;">
                                            <label>Kategori Raw Material/Bahan Baku</label>
                                            <div>
                                                <select id="raw-material-select" name="">
                                                    <option value="">Pilih Kategori</option>
                                                    <?php foreach($this->raw_material as $key => $category) { ?>
                                                        <option value="<?php echo $category->uid; ?>"><?php echo $category->value;?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="space-4"></div>

                                        <div class="form-group" id="wip" style="display: none;">
                                            <label>Kategori Work In Progress (WIP)</label>
                                            <div>
                                                <select id="wip-select" name="">
                                                    <option value="">Pilih Kategori</option>
                                                    <?php foreach($this->wip as $key => $category) { ?>
                                                        <option value="<?php echo $category->uid; ?>"><?php echo $category->value;?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="space-4"></div>

                                        <div class="form-group" id="finished-product" style="display: none;">
                                            <label>Kategori Finished Products/Produk Jadi</label>
                                            <div>
                                                <select id="finished-product-select" name="">
                                                    <option value="">Pilih Kategori</option>
                                                    <?php foreach($this->finished_product as $key => $category) { ?>
                                                        <option value="<?php echo $category->uid; ?>"><?php echo $category->value;?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="space-4"></div>

                                        <div class="form-group" id="trading-goods" style="display: none;">
                                            <label>Kategori Trading Goods (Baha Baku Sekaligus Produk Jadi)</label>
                                            <div>
                                                <select id="trading-goods-select" name="">
                                                    <option value="">Pilih Kategori</option>
                                                    <?php foreach($this->trading_goods as $key => $category) { ?>
                                                        <option value="<?php echo $category->uid; ?>"><?php echo $category->value;?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="space-4"></div>

                                        <div class="form-group" id="service-product" style="display: none;">
                                            <label>Kategori Service/Jasa</label>
                                            <div>
                                                <select id="service-product-select" name="">
                                                    <option value="">Pilih Kategori</option>
                                                    <?php foreach($this->service_product as $key => $category) { ?>
                                                        <option value="<?php echo $category->uid; ?>"><?php echo $category->value;?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="space-4"></div>

                                        <div class="form-group" id="production-tools" style="display: none;">
                                            <label>Kategori Production Resources/Tools</label>
                                            <div>
                                                <select id="production-tools-select" name="">
                                                    <option value="">Pilih Kategori</option>
                                                    <?php foreach($this->production_tools as $key => $category) { ?>
                                                        <option value="<?php echo $category->uid; ?>"><?php echo $category->value;?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="space-4"></div>

                                        <div class="form-group" id="operating-supplies" style="display: none;">
                                            <label>Kategori Consumed Operating Supplies</label>
                                            <div>
                                                <select id="operating-supplies-select" name="">
                                                    <option value="">Pilih Kategori</option>
                                                    <?php foreach($this->operating_supplies as $key => $category) { ?>
                                                        <option value="<?php echo $category->uid; ?>"><?php echo $category->value;?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="space-4"></div>

                                        <div class="form-group" id="asset-material" style="display: none;">
                                            <label>Kategori Asset</label>
                                            <div>
                                                <select id="asset-material-select" name="">
                                                    <option value="">Pilih Kategori</option>
                                                    <?php foreach($this->asset as $key => $category) { ?>
                                                        <option value="<?php echo $category->uid; ?>"><?php echo $category->value;?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="space-4"></div>

                                        <div class="form-group" id="selling-price" style="display: none;">
                                            <label for="form-field-username">Harga Jual</label>
                                            <div>
                                            <input name="selling_price" type="number" class="form-control align-right" placeholder="Hanya angka saja" autocomplete="off" />
                                            </div>
                                        </div>

                                        
                                    </div>

                                    <div class="col-xs-12 col-sm-5">
                                        <div class="form-group">
                                            <label for="form-field-username">Kode barang</label>
                                            <div>
                                            <input name ="material_code" type="text" class="form-control align-right" placeholder="Kode Barang" autocomplete="off" />
                                            </div>
                                        </div>

                                        <div class="space-4"></div>

                                        <div class="form-group">
                                            <label for="form-field-username">Safety Stock</label>
                                            <div>
                                                <input type="number" name ="minimum_balance" class="form-control align-right" placeholder="Minimum stock yang harus tersedia">
                                            </div>
                                        </div>

                                        <div class="space-4"></div>

                                        <div class="form-group">
                                            <label for="form-field-username">Supplier Name</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control search-query" placeholder="klik browse untuk memilih" name="contact_id" id="contact_id_new"/>
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
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-xs-12 col-sm-12">
                                        <label for="form-field-first">Material Description</label>

                                        <div>
                                            <textarea name ="note" class="autosize-transition form-control" placeholder="Keterangan tambahan"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="panel-footer">
                                <div class="align-right">
                                    <div class="btn-group btn-corner" role="group">
                                        <button class="btn btn-danger">
                                            <a href="javascript: history.go(-1)">
                                                <i class="icon-remove bigger-110"></i>
                                                Cancel
                                            </a>
                                        </button>
                                        
                                        <button class="btn" type="reset">
                                            <i class="icon-undo bigger-110"></i>
                                            Reset
                                        </button>

                                        <button class="btn btn-info" type="submit">
                                            <i class="icon-ok bigger-110"></i>
                                            Submit
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <!-- PAGE CONTENT ENDS -->
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.page-content -->
    </div><!-- /.main-content-inner -->
</div><!-- /.main-content -->
<script type="text/javascript">
    function showHideElement() {
        var el = document.getElementById("material-type");
        el.addEventListener("change", function() {
          var elems = document.querySelectorAll('#raw-material,#wip,#finished-product,#trading-goods,#service-product,#production-tools,#operating-supplies,#asset-material,#selling-price')
          for (var i = 0; i < elems.length; i++) {
            elems[i].style.display = 'none';
          }

          var elems = document.querySelectorAll('#raw-material-select,#wip-select,#finished-product-select,#trading-goods-select,#service-product-select,#production-tools-select,#operating-supplies-select,#asset-material-select')
          for (var i = 0; i < elems.length; i++) {
            elems[i].name = '';
          }

            if (this.selectedIndex === 1) {
                document.getElementById('raw-material').style.display = 'block';
                document.getElementById('raw-material-select').name = 'material_category';
            } else if (this.selectedIndex === 2) {
                document.getElementById('wip').style.display = 'block';
                document.getElementById('wip-select').name = 'material_category';
            } else if (this.selectedIndex === 3) {
                document.getElementById('finished-product').style.display = 'block';
                document.getElementById('finished-product-select').name = 'material_category';
                document.getElementById('selling-price').style.display = 'block';
            } else if (this.selectedIndex === 4) {
                document.getElementById('trading-goods').style.display = 'block';
                document.getElementById('trading-goods-select').name = 'material_category';
                document.getElementById('selling-price').style.display = 'block';
            } else if (this.selectedIndex === 5) {
                document.getElementById('service-product').style.display = 'block';
                document.getElementById('service-product-select').name = 'material_category';
                document.getElementById('selling-price').style.display = 'block';
            } else if (this.selectedIndex === 6) {
                document.getElementById('production-tools').style.display = 'block';
                document.getElementById('production-tools-select').name = 'material_category';
            } else if (this.selectedIndex === 7) {
                document.getElementById('operating-supplies').style.display = 'block';
                document.getElementById('operating-supplies-select').name = 'material_category';
            } else if (this.selectedIndex === 8) {
                document.getElementById('asset-material').style.display = 'block';
                document.getElementById('asset-material-select').name = 'material_category';
            }

        }, false);
    }
    showHideElement();
</script>