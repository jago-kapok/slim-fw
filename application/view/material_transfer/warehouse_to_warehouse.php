<!doctype html>

<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
<title>
    <?php if (isset($this->title)) {
        echo $this->title;
    } else {
        echo Config::get('DEFAULT_TITLE');
    } ?>
</title>
<meta name="description" content="" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
<link rel="stylesheet" type="text/css" href="<?php echo Config::get('URL'); ?>bootstrap-3.3.7/css/kasir-auto-complete.css" />
<style type="text/css" media="screen">

/* http://meyerweb.com/eric/tools/css/reset/ 
   v2.0 | 20110126
   License: none (public domain)
*/

html, body, div, span, applet, object, iframe,
h1, h2, h3, h4, h5, h6, p, blockquote, pre,
a, abbr, acronym, address, big, cite, code,
del, dfn, em, img, ins, kbd, q, s, samp,
small, strike, strong, sub, sup, tt, var,
b, u, i, center,
dl, dt, dd, ol, ul, li,
fieldset, form, label, legend,
table, caption, tbody, tfoot, thead, tr, th, td,
article, aside, canvas, details, embed, 
figure, figcaption, footer, header, hgroup, 
menu, nav, output, ruby, section, summary,
time, mark, audio, video {
  margin: 0;
  padding: 0;
  border: 0;
  font-size: 100%;
  font: inherit;
  vertical-align: baseline;
}
/* HTML5 display-role reset for older browsers */
article, aside, details, figcaption, figure, 
footer, header, hgroup, menu, nav, section {
  display: block;
}
body {
  line-height: 1;
}
ol, ul {
  list-style: none;
}
blockquote, q {
  quotes: none;
}
blockquote:before, blockquote:after,
q:before, q:after {
  content: '';
  content: none;
}
table {
  border-collapse: collapse;
  border-spacing: 0;
}

/* Start Styling */

body {
  background-color: #fff;
  text-rendering: optimizeLegibility;
}
.container {
  width: 100%;
  margin: 0 0 21px 0;
}
h1 {
  font-size: 21pt;
  text-align: center;
  margin: 21px 13px 21px 13px;
}
.text-left {
  text-align: left;
}
.text-center {
  text-align: center;
}
.text-right {
  text-align: right;
}

/* Make input Blink */
@-webkit-keyframes blink { 
   50% {
    border-color: #d5ff3f;
    box-shadow: inset 0px 0px 3px 3px #DBA632;
        } 
}
@keyframes blink { 
   50% { 
    border-color: #d5ff3f;
    box-shadow: inset 0px 0px 3px 3px #DBA632;
  } 
}
#hero-demo {
  height: 55px;
  font-size: 15pt;
}
input:focus#hero-demo,
input[focus]#hero-demo{
  border: 3px solid transparent;
  background-color: #ff3549;
  color: #fff;
  animation: blink .5s step-end infinite alternate;
  -webkit-animation: blink .5s step-end infinite alternate;
}
.ExcelTable2007 {
  border: 1px solid #B0CBEF;
  border-width: 1px 0px 0px 1px;
  font-size: 11pt;
  font-weight: 100;
  border-spacing: 0px;
  border-collapse: collapse;
  width: 100%;
}
.ExcelTable2007 td {
  border: none;
  background-color: white;
  border: 1px solid #D0D7E5;
  border-width: 0px 1px 1px 0px;
  padding: 7px;
}
.ExcelTable2007 th,
.ExcelTable2007 tr.info td{
  background: #e4ecf7; /* Old browsers */
  background-color: #E4ECF7;
  text-align: center;
  border: 1px solid #9EB6CE;
  font-weight: normal;
  font-size: 14px;
  border: 1px solid #9EB6CE;
  border-width: 0px 1px 1px 0px;
  height: 17px;
  vertical-align: middle;
  padding: 8px;
}
.ExcelTable2007 td b {
  border: 0px;
  background-color: white;
  font-weight: bold;
}
.ExcelTable2007 td.heading {
  background-color: #E4ECF7;
  text-align: center;
  border: 1px solid #9EB6CE;
  border-width: 0px 1px 1px 0px;
}
.ExcelTable2007 td input,
.ExcelTable2007 td textarea {
  height: 38px;
  padding: 0;
  margin: 0;
  border: 0;
  font-size: 11pt;
  width: 100%;
}

/* make input blink on focus */
.ExcelTable2007 td input:focus,
.ExcelTable2007 td input[focus]{
  background-color: #ff0000;
  color: #fff;
}
input:focus::placeholder {
  color: white;
}
/* make input blink on focus */
.ExcelTable2007 td input:read-only,
.ExcelTable2007 td input[read-only]{
  background-color: #fff;
  color: #000;
}

button {
  color: #ffffff;
  padding: 5px 10px;
  border: solid 1px #c0c0c0;
  background-color: #14b6e8;
  box-shadow: 1 -1 1px  rgba(0,0,0,0.6);
  -moz-box-shadow: 2 -2 2px   rgba(0,0,0,0.6);
  -webkit-box-shadow: 2 -2 2px   rgba(0,0,0,0.6);
  -o-box-shadow: 2 -2 2px   rgba(0,0,0,0.6);
  border-radius:3px;
  cursor:pointer;
}
.ExcelTable2007 td input.button-delete {
  color: #ffffff;
  margin: 5px;
  width: 90%;
  max-height: 27px;
  border: solid 1px #c0c0c0;
  background-color: #ff0000;
  box-shadow: 1 -1 1px  rgba(0,0,0,0.6);
  -moz-box-shadow: 2 -2 2px   rgba(0,0,0,0.6);
  -webkit-box-shadow: 2 -2 2px   rgba(0,0,0,0.6);
  -o-box-shadow: 2 -2 2px   rgba(0,0,0,0.6);
  border-radius:3px;
}
button:hover {
  background-color: #ccc;
  color: #000;
  cursor:pointer;
}
button:disabled,
button[disabled]{
  border: 1px solid #999999;
  background-color: #ff0730;
  color: #fff;
}
#save-button {
  margin: 13px;
  height: 34px;
  float: right;
}

/* Make Increment Number with CSS */

table {
  counter-reset:section;
}
.count:before {
  counter-increment:section;
  content:counter(section);
}
</style>
</head>

<body>
  <?php //var_dump($this->material_list ); ?>
<fieldset>
  <iframe name="selfFrame" style="display:none;"></iframe> <!-- prevent page reload on empty submit -->
  <form method="POST" onsubmit="insertProduct(); return false;" target="selfFrame">
    <input id="hero-demo" autofocus type="text" name="product-name" placeholder=" Ketik nama atau kode barang disini" style="width: 100%;" autocomplete="off">
  </form>
</fieldset>



<div class="container" id="kasir-box-1" style="display: none;">
<form method="post" action="<?php echo Config::get('URL') . 'MaterialTransfer/saveWarehouseToWarehouse/';?>">
        <table  id="table-pembayaran" class="ExcelTable2007">
          <tr>
            <td>
              <select name="origin_location">
                <option>Pilih Lokasi/Department Asal</option>
                <option value="produksi">Produksi</option>
                <option value="gudang">Gudang</option>
              </select>
            </td>
            <td>
              <select name="destination_location">
                <option>Pilih Lokasi/Department Asal</option>
                <option value="produksi">Produksi</option>
                <option value="gudang">Gudang</option>
              </select>
            </td>
          </tr>
        </table>
        <table class="ExcelTable2007">
          <thead>
            <tr>
              <th  class="center">#</th>
              <th  class="center">Kode</th>
              <th  class="center">Nama</th>
              <th  class="center">Satuan</th>
              <th  class="center">Jumlah</th>
              <th  class="center">Delete</th>
            </tr>
          </thead>
          <tbody id="product-list">
          </tbody>
        </table>
        <table  id="table-pembayaran" class="ExcelTable2007">
            <tr>
              <td>
<textarea id="note" name="note" style="min-height: 200px; text-align: left;" placeholder="Keterangan Tulis Di Sini"></textarea>
              </td>
            </tr>
          </table>
<input type="hidden" id="total-input" name="total_input" value="">
<button type="submit" id="save-button" >SIMPAN</button>
</form>
</div>

<div class="container" id="kasir-box-2" style="display: none;"></div>

<div class="container" id="kasir-box-3">
    <h1>Untuk Memulai, scan atau masukkan nama/ID barang</h1>
</div>

<hr>

<div class="container">
      <div style="text-align: center;">
        <a href="<?php echo Config::get('URL'); ?>dashboard/" class="center">Home</a> - 
        <a href="<?php echo Config::get('URL') . 'contact/'; ?>" class="center">Kontak</a> - 
        <a href="<?php echo Config::get('URL') . 'inventory/rawMaterial/'; ?>" class="center">Stock</a>
      </div>
</div>

<script src="<?php echo Config::get('URL'); ?>bootstrap-3.3.7/js/kasir-auto-complete.min.js"></script>
<script>
var productRowCounter = 1;
localStorage.clear(); // clear local storage

// Autocomple product
var demo1 = new autoComplete({
    selector: '#hero-demo',
    minChars: 1,
    source: function(term, suggest){
        term = term.toLowerCase();
        
        var choices = [<?php
          foreach ($this->material_list as $key => $value) {
             $material_name = str_replace(array("'", '"'),'', $value->material_name);
            echo "'{$material_name} -- {$value->material_code} -- {$value->unit}', ";
          }
        ?>];
        var suggestions = [];
        for (i=0;i<choices.length;i++)
            if (~choices[i].toLowerCase().indexOf(term)) suggestions.push(choices[i]);
        suggest(suggestions);
    }
});




function deleteRow(btn) {
  var row = btn.parentNode.parentNode;
  row.parentNode.removeChild(row);
  totalPrice(); // Update totaal tagihan
}


function resetPage() {
    window.location.reload(false); 
}

function redirectPage(url) {
    window.location = url;
}

function isProductCodeInserted(product_code) {
  var cell = document.getElementsByClassName("product-code");
  for(var i = 0; i < cell.length; i++) {
      if (cell[i].innerHTML == product_code) {
          return false;
      }
  }
  return true;
}

function insertProduct()
{
  document.getElementById('kasir-box-1').style.display = 'table'; //show
  document.getElementById('kasir-box-2').style.display = 'table'; //show
  document.getElementById('kasir-box-3').style.display = 'none'; //hide

  var product = document.getElementById('hero-demo').value; //get value from input form

  if (product.indexOf(' -- ') === -1) { //check if it contain ' -- ' string or not, if not get from local storage
    product_string = localStorage.getItem(product);
  } else {
    product_string = product;
  }
  var product_array = product_string.split(" -- ");
  var product_name = product_array[0];
  var product_code = product_array[1];
  var product_unit = product_array[2];

  var table = "<tr>";
  table += '<td class="count"></td>';
  table += '<td><input name="material_code_' + productRowCounter + '" value="' + product_code + '" readonly></td>"';
  table += '<td>' + product_name + "</td>";
  table += '<td>' + product_unit + "</td>";

  table += '<td><input name="quantity_transfer_' + productRowCounter + '" value="" class="text-right" ></td>';
  table += '<td><input class="button-delete" value="DELETE" type="button" onclick="deleteRow(this)" /></td>';
  table += "</tr>";

  if (isProductCodeInserted(product_code)) {
    var product = document.getElementById('product-list');
    product.insertAdjacentHTML('afterbegin', table);
    document.getElementById("hero-demo").value = ""; // Clear input form, if not clreaed selected prooduct is not cleared (remember we are not refreshing web pages!)
  } else {
    document.getElementById("hero-demo").value = ""; // Clear input form, if not clreaed selected prooduct is not cleared (remember we are not refreshing web pages!)
  }
  document.getElementById("save-button").disabled = false;
  document.getElementById("total-input").value = productRowCounter;
  productRowCounter++;
  return false;
}


<?php
    //Save database from mysql to localstorage
    foreach ($this->material_list as $key => $value) {
      $material_name = str_replace(array("'", '"'),'', $value->material_name);
      ?>
            localStorage.setItem("<?php echo $value->material_code; ?>", "<?php echo $material_name . " -- " . $value->material_code . " -- " . $value->unit; ?>");
<?php } ?>

</script>
</body>
</html>