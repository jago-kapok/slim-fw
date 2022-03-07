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
<link rel="stylesheet" href="<?php echo Config::get('URL'); ?>bootstrap-3.3.7/css/pickmeup.css" media="screen"/>
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
  padding: 0 7px 0 7px;
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
  background-color: #fff9e8;
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
<script src="<?php echo Config::get('URL'); ?>bootstrap-3.3.7/js/pickmeup.js"></script>
<script>

var productRowCounter = 1;
localStorage.clear(); // clear local storage

function makeFloat(str) { // change string to float
  var result = str.replace (/,/g, ""); // remove comma in format money number, eg 2,500.68
  return  parseFloat(result);
}

function moneyFormat(x) {
  return parseInt(x).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

function deleteRow(btn) {
  var row = btn.parentNode.parentNode;
  row.parentNode.removeChild(row);
  totalPrice(); // Update totaal tagihan
}

function updateSubTotal(productRowCounter) {
  var quantity = document.getElementById('quantity_' + productRowCounter).value;
  var harga = document.getElementById('price_' + productRowCounter).value;
  var ppn = document.getElementById('ppn_' + productRowCounter).value;
  ppn = (ppn/100) * (quantity * harga);
  var pph = document.getElementById('pph_' + productRowCounter).value;
  pph = (pph/100) * (quantity * harga);
  var subTotal = quantity * harga;
  var total = subTotal + ppn - pph;

  document.getElementById('subTotal_' + productRowCounter).textContent = moneyFormat(subTotal);
  document.getElementById('subPpn_' + productRowCounter).textContent = moneyFormat(ppn);
  document.getElementById('subPph_' + productRowCounter).textContent = moneyFormat(pph);
  document.getElementById('total_' + productRowCounter).textContent = moneyFormat(total);
  totalPrice(); // Update totaal tagihan
}

function totalPrice() {
  var subTotal = document.getElementsByClassName("subTotal");
  var sumSubTotal = 0;
  for (var i = 0; i < subTotal.length; i++){
      sumSubTotal += makeFloat(subTotal[i].textContent);
  }

  var subPPN = document.getElementsByClassName("subPPN");
  var sumSubPPN = 0;
  for (var i = 0; i < subPPN.length; i++){
      sumSubPPN += makeFloat(subPPN[i].textContent);
  }

  var subPPh = document.getElementsByClassName("subPPh");
  var sumSubPPh = 0;
  for (var i = 0; i < subPPh.length; i++){
      sumSubPPh += makeFloat(subPPh[i].textContent);
  }

  var total = document.getElementsByClassName("total");
  var sumTotal = 0;
  for (var i = 0; i < total.length; i++){
      sumTotal += makeFloat(total[i].textContent);
  }

  document.getElementById("sum-sub-total").textContent = moneyFormat(sumSubTotal);
  document.getElementById("sum-ppn").textContent = moneyFormat(sumSubPPN);
  document.getElementById("sum-pph").textContent = moneyFormat(sumSubPPh);
  document.getElementById("sum-total").textContent = moneyFormat(sumTotal);
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
      if (cell[i].value == product_code) {
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
  var product_price = product_array[2].replace(/\D/g,'');

  var table = "<tr>";
  table += '<td class="count"></td>';
  table += '<td><input type="text" name="product_code_' + productRowCounter + '" class="saved-products product-code" value="' + product_code + '"></td>';
  table += '<td>' + product_name + '</td>';

  table += '<td><input type="text" name="quantity_' + productRowCounter + '" id="quantity_' + productRowCounter + '" onkeyup="updateSubTotal(' +  productRowCounter + ')" class="saved-products text-right" value="1"></td>';
  table += '<td><input type="text" name="price_' + productRowCounter + '" id="price_' + productRowCounter + '" onkeyup="updateSubTotal(' +  productRowCounter + ')" class="saved-products text-right" value="' + product_price + '"></td>';
  table += '<td><input type="text" name="ppn_' + productRowCounter + '" id="ppn_' + productRowCounter + '" onkeyup="updateSubTotal(' +  productRowCounter + ')" class="saved-products text-right" value="0"></td>';
  table += '<td><input type="text" name="pph_' + productRowCounter + '" id="pph_' + productRowCounter + '" onkeyup="updateSubTotal(' +  productRowCounter + ')" class="saved-products text-right" value="0"></td>';

  table += '<td class="subTotal text-right" id="subTotal_' + productRowCounter + '">' + product_price + '</td>';
  table += '<td class="subPPN text-right" id="subPpn_' + productRowCounter + '">0</td>';
  table += '<td class="subPPh text-right" id="subPph_' + productRowCounter + '">0</td>';
  table += '<td class="total text-right" id="total_' + productRowCounter + '">' + product_price + '</td>';

  table += '<td><input class="saved-products button-delete" type="button" value="delete" onclick="deleteRow(this)" /></td>';
  table += '</tr>';

  if (isProductCodeInserted(product_code)) {
    var product = document.getElementById('product-list');
    product.insertAdjacentHTML('afterbegin', table);
    document.getElementById("hero-demo").value = ""; // Clear input form, if not clreaed selected prooduct is not cleared (remember we are not refreshing web pages!)
  } else {
    document.getElementById("hero-demo").value = ""; // Clear input form, if not clreaed selected prooduct is not cleared (remember we are not refreshing web pages!)
  }
  
  document.getElementById("total_input").value = productRowCounter;
  totalPrice(); // Update totaal tagihan
  document.getElementById("save-button").disabled = false;
  productRowCounter++;
  return false;
}

//Save database from mysql to localstorage
<?php
    foreach ($this->product_list as $key => $value) { ?>
            localStorage.setItem("<?php echo $value->material_code; ?>", "<?php echo $value->material_name . " -- " . $value->material_code . " -- " . number_format($value->selling_price, 0); ?>");
<?php } ?>
</script>
</head>

<body>
  <?php //var_dump($this->product_list ); ?>
<fieldset>
  <iframe name="selfFrame" style="display:none;"></iframe> <!-- prevent page reload on empty submit -->
  <form method="POST" onsubmit="insertProduct(); return false;" target="selfFrame">
    <input id="hero-demo" type="text" name="product-name" placeholder=" Ketik nama atau kode barang disini" style="width: 100%;" autocomplete="off" list="material-list" autofocus>
  </form>
</fieldset>

<div class="container" id="kasir-box-1" style="display: none;">
    <form method="post" action="<?php echo Config::get('URL') . 'so/saveDraft/'; ?>">

        <table  id="table-pembayaran" class="ExcelTable2007">
          <tr>
            <td class="heading">
              Customer Code
            </td>
            <td>
              <input type="text" placeholder=" Ketik dan pilih pelanggan" autocomplete="off" id="contact_id" name="customer" list="customer-list" />
            </td>
            <td class="heading">
              Delivery Request
            </td>
            <td>
              <input type="text" placeholder=" Rencana Tanggal Pengiriman" autocomplete="off" class="date" id="deliveryRequest" name="delivery_date_request" data-pmu-format="Y-m-d"/>
            </td>
          </tr>
        </table>
        <table class="ExcelTable2007">
          <thead>
            <tr>
              <th rowspan="2" class="center">#</th>
              <th rowspan="2" class="center">Kode</th>
              <th rowspan="2" class="center">Nama</th>
              <th rowspan="2" class="center">Jumlah</th>
              <th rowspan="2" class="center">Harga</th>
              <th rowspan="2" class="center">PPN</th>
              <th rowspan="2" class="center">PPh</th>
              <th colspan="4" class="text-center">Sub Total</th>
              <th rowspan="2"></th>
            </tr>
            <tr>
              <th class="center">Harga</th>
              <th class="center">PPN</th>
              <th class="center">PPh</th>
              <th class="center">Total</th>
            </tr>
          </thead>
          <tbody id="product-list">
            <tr class="info">
              <td class="text-center" colspan="7"><strong>TOTAL</strong></td>
              <td style="text-align: right;" id="sum-sub-total"></td>
              <td style="text-align: right;" id="sum-ppn"></td>
              <td style="text-align: right;" id="sum-pph"></td>
              <td style="text-align: right;" id="sum-total"></td>
              <td><input type="hidden" name="total_input" id="total_input"></td>
            </tr>
          </tbody>
        </table>
        <table  id="table-pembayaran" class="ExcelTable2007">
            <tr>
              <td>
<textarea id="name" name="note" style="min-height: 200px; text-align: left;">
Terms and Conditions
1. Delivery Point: 
2. Delivery Time: 
3. Skema Pengiriman: Diambil customer/Diambil Ekspedisi customer/Dikirim Maxima melalui ekspedisi (Delete yang tidak perlu)
4. Terms of Payment: 
5. Validity of Offer: 
6. Termasuk sertifikat pengujian rutin dan commisioning/include routine test certificate
7. Sudah termasuk packing standard/Include basic standard packing
8. Garansi: 
</textarea>
              </td>
            </tr>
          </table>
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

<datalist id="material-list">
  <?php
    foreach ($this->product_list as $key => $value) {
      echo '<option value="' . str_replace(["\"","'"], "", $value->material_name) . ' -- ' . str_replace(['"',"'"], "", $value->material_code) . ' -- ' . number_format($value->selling_price, 0) . '">';
    }
  ?>
</datalist>

<datalist id="customer-list">
  <?php
    foreach ($this->customer_list as $key => $value) {
      echo '<option value="' . str_replace(["\"","'"], "", $value->contact_id) . ' -- ' . str_replace(['"',"'"], "", $value->contact_name) . '">';
    }
  ?>
</datalist>
<script>
//date picker
pickmeup('#deliveryRequest');
</script>
</body>
</html>