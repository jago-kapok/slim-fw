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
<?php
    $no = 1;
    foreach ($this->transaction as $key => $value) {
      $no++;
    }
?>

var productRowCounter = <?php echo $no;?>;
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
  var quantity = document.getElementById('quantity_' + productRowCounter).textContent;
  var harga = document.getElementById('price_' + productRowCounter).textContent;
  var ppn = document.getElementById('ppn_' + productRowCounter).textContent;
  ppn = (ppn/100) * (quantity * harga);
  var pph = document.getElementById('pph_' + productRowCounter).textContent;
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
  var product_price = product_array[2].replace(/\D/g,'');

  var table = "<tr>";
  table += '<td class="count"></td>';
  table += '<td class="saved-products product-code">' + product_code + "</td>";
  table += '<td>' + product_name + "</td>";

  table += '<td contenteditable="true" id="quantity_' + productRowCounter + '" onkeyup="updateSubTotal(' +  productRowCounter + ')" class="saved-products text-right" >1</td>';
  table += '<td contenteditable="true" contenteditable="true" id="price_' + productRowCounter + '" onkeyup="updateSubTotal(' +  productRowCounter + ')" class="saved-products text-right">' + product_price + '</td>';
  table += '<td contenteditable="true" id="ppn_' + productRowCounter + '" onkeyup="updateSubTotal(' +  productRowCounter + ')" class="saved-products text-right">0</td>';
  table += '<td contenteditable="true" id="pph_' + productRowCounter + '" onkeyup="updateSubTotal(' +  productRowCounter + ')" class="saved-products text-right">0</td>';

  table += '<td class="subTotal text-right" id="subTotal_' + productRowCounter + '">' + product_price + '</td>';
  table += '<td class="subPPN text-right" id="subPpn_' + productRowCounter + '">0</td>';
  table += '<td class="subPPh text-right" id="subPph_' + productRowCounter + '">0</td>';
  table += '<td class="total text-right" id="total_' + productRowCounter + '">' + product_price + '</td>';

  table += '<td><input class="saved-products button-delete" type="button" value="delete" onclick="deleteRow(this)" /></td>';
  table += "</tr>";

  if (isProductCodeInserted(product_code)) {
    var product = document.getElementById('product-list');
    product.insertAdjacentHTML('afterbegin', table);
    document.getElementById("hero-demo").value = ""; // Clear input form, if not clreaed selected prooduct is not cleared (remember we are not refreshing web pages!)
  } else {
    document.getElementById("hero-demo").value = ""; // Clear input form, if not clreaed selected prooduct is not cleared (remember we are not refreshing web pages!)
  }
  totalPrice(); // Update totaal tagihan
  document.getElementById("save-button").disabled = false;
  productRowCounter++;
  return false;
}

function finishAndSave()
{
  //Make string from purchased product table
  var so_number = '<?php echo $this->so->transaction_number; ?>';
  var cell = document.getElementsByClassName("saved-products");
  var customer = document.getElementById("contact_id").value;
  var deliveryRequest = document.getElementById("deliveryRequest").value;
  var note = document.getElementById("note").value;
  //imedeadly disable submit button to prevent double input
  document.getElementById("save-button").disabled = true; 

  //Check if contact id is not empty
  if (!customer.length) {
      alert("Customer Code masih kosong!, silahkan isi dahulu.");
      //submit fail, open again submit button
      document.getElementById("save-button").disabled = false;
    } else {
      var i = 0;
      var product_string = '';
      while(cell[i] != undefined) {
        product_string += cell[i].innerHTML + ' --- ';
          if (cell[i].value == 'delete') {
            product_string += ' ___ ';
          }
        i++;
      } //end while

      //Send the string to server
      var http = new XMLHttpRequest();
      var url = "<?php echo Config::get('URL'); ?>so/saveEditSO/";
      var params = "product_list=" + product_string + "&customer=" + customer + "&deliveryRequest=" + deliveryRequest + "&note=" + note + "&so_number=" + so_number;

      //Send the proper header information along with the request
      http.open("POST", url, true);
      http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
      http.onreadystatechange = function() { //Call a function when the state changes.
        if(http.readyState === XMLHttpRequest.DONE && http.status == 200) {
            var salesCode = http.response;
            //check response from server
            if (salesCode.indexOf('GAGAL') === -1) {
                //submit success, redirect to so page /redirectPage(salesCode);
                redirectPage(salesCode);
              } else {
                //submit fail, echo message
                alert(salesCode);
                //submit fail, open again submit button
                document.getElementById("save-button").disabled = false; 
            }
          }
      }
      http.send(params);
    }
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
    <input id="hero-demo" autofocus type="text" name="product-name" placeholder=" Ketik nama atau kode barang disini" style="width: 100%;" autocomplete="off">
  </form>
</fieldset>

<div class="container" id="kasir-box-1">
        <table  id="table-pembayaran" class="ExcelTable2007">
          <tr>
            <td class="heading">
              Customer Code
            </td>
            <td>
              <input type="text" placeholder=" Ketik dan pilih pelanggan" autocomplete="off" class="" name="contact_id" id="contact_id" value="<?php echo $this->so->customer_id . ' -- ' . $this->so->contact_name; ?>" />
            </td>
            <td class="heading">
              Delivery Request
            </td>
            <td>
              <input type="text" placeholder=" Rencana Tanggal Pengiriman" name="date" autocomplete="off" class="date" id="deliveryRequest" data-pmu-format="Y-m-d" value="<?php echo date('Y-m-d', strtotime($this->so->created_timestamp)); ?>"/>
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
            <?php
                $no = 1;
                foreach ($this->transaction as $key => $value) {
                  $subTotal = $value->quantity * $value->selling_price;
                  $ppn = ($value->tax_ppn/100) * $subTotal;
                  $pph = ($value->tax_pph/100) * $subTotal;
                  $total = $subTotal + $ppn - $pph;

                  echo '<tr>
                          <td class="count"></td>
                          <td class="saved-products product-code">' . $value->material_code . '</td>
                          <td>' . $value->material_name . '</td>
                          <td id="quantity_' . $no . '" onkeyup="updateSubTotal(1)" class="saved-products text-right" contenteditable="true">' . floatval($value->quantity ). '</td>
                          <td id="price_' . $no . '" onkeyup="updateSubTotal(' . $no . ')" class="saved-products text-right" contenteditable="true">' . floatval($value->selling_price) . '</td>
                          <td id="ppn_' . $no . '" onkeyup="updateSubTotal(' . $no . ')" class="saved-products text-right" contenteditable="true">' . $value->tax_ppn . '</td>
                          <td id="pph_' . $no . '" onkeyup="updateSubTotal(' . $no . ')" class="saved-products text-right" contenteditable="true">' . $value->tax_pph . '</td>
                          <td class="subTotal text-right" id="subTotal_' . $no . '">' . number_format($subTotal, 0) . '</td>
                          <td class="subPPN text-right" id="subPpn_' . $no . '">' . number_format($ppn, 0) . '</td>
                          <td class="subPPh text-right" id="subPph_' . $no . '">' . number_format($pph, 0) . '</td>
                          <td class="total text-right" id="total_' . $no . '">' . number_format($total, 0) . '</td>
                          <td><input class="saved-products button-delete" type="button" value="delete" onclick="deleteRow(this)"></td>
                        </tr>';
                  $no++;
                }
            ?>
            <tr class="info">
              <td class="text-center" colspan="7"><strong>TOTAL</strong></td>
              <td style="text-align: right;" id="sum-sub-total"></td>
              <td style="text-align: right;" id="sum-ppn"></td>
              <td style="text-align: right;" id="sum-pph"></td>
              <td style="text-align: right;" id="sum-total"></td>
              <td></td>
            </tr>
          </tbody>
        </table>
        <table  id="table-pembayaran" class="ExcelTable2007">
            <tr>
              <td>
<textarea id="note" style="min-height: 200px; text-align: left;"><?php echo $this->so->note; ?></textarea>
              </td>
            </tr>
          </table>
<button type="button" id="save-button" onclick="finishAndSave();">SIMPAN</button>
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
//date picker
pickmeup('#deliveryRequest', {
  default_date: false
});

// Autocomple product
var demo1 = new autoComplete({
    selector: '#hero-demo',
    minChars: 1,
    source: function(term, suggest){
        term = term.toLowerCase();
        
        var choices = [<?php
          foreach ($this->product_list as $key => $value) {
            echo "'{$value->material_name} -- {$value->material_code} -- " . number_format($value->selling_price, 0) . "', ";
          }
        ?>];
        var suggestions = [];
        for (i=0;i<choices.length;i++)
            if (~choices[i].toLowerCase().indexOf(term)) suggestions.push(choices[i]);
        suggest(suggestions);
    }
});

// Autocomple contact
var customerID = new autoComplete({
    selector: '#contact_id',
    minChars: 1,
    source: function(term, suggest){
        term = term.toLowerCase();
        
        var choices = [<?php
          foreach ($this->customer_list as $key => $value) {
            echo "'{$value->contact_id} -- {$value->contact_name}', ";
          }
        ?>];
        var suggestions = [];
        for (i=0;i<choices.length;i++)
            if (~choices[i].toLowerCase().indexOf(term)) suggestions.push(choices[i]);
        suggest(suggestions);
    }
});
    </script>
</body>
</html>