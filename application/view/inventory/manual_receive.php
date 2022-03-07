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

.ExcelTable2007 {
  border: 1px solid #B0CBEF;
  border-width: 1px 0px 0px 1px;
  font-size: 11pt;
  font-weight: 100;
  border-spacing: 0px;
  border-collapse: collapse;
  width: 100%;
}
.ExcelTable2007 th {
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

.ExcelTable2007 td {
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

.ExcelTable2007 td.heading {
  background-color: #E4ECF7;
  text-align: center;
  border: 1px solid #9EB6CE;
  border-width: 0px 1px 1px 0px;
}
.ExcelTable2007 tr th input,
.ExcelTable2007 td td input,
.ExcelTable2007 tr td textarea {
  background-color: #fff9e8;
  height: 38px;
  padding: 0;
  margin: 0;
  border: 0;
  font-size: 11pt;
  width: 100%;
}

/* make input blink on focus */
.ExcelTable2007 th input:focus,
.ExcelTable2007 th input[focus],
.ExcelTable2007 td input:focus,
.ExcelTable2007 td input[focus] {
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
  margin: 3px;
  width: 90%;
  height: 27px;
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
var materialRowCounter = 1;
localStorage.clear(); // clear local storage

function deleteRow(btn) {
  var row = btn.parentNode.parentNode;
  row.parentNode.removeChild(row);
}

function resetPage() {
    window.location.reload(false); 
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

function insertProduct() {
  var product = document.getElementById('product-name').value; //get value from input form

  if (product.indexOf(' -- ') === -1) { //check if it contain ' -- ' string or not, if not get from local storage
    product_string = localStorage.getItem(product);
  } else {
    product_string = product;
  }
  var product_array = product_string.split(" -- ");
  var product_name = product_array[0];
  var product_code = product_array[1];

  var table = "<tr>";
  table += '<td class="count"></td>';
  table += '<td class="saved-products product-code">' + product_code + "</td>";
  table += '<td>' + product_name + "</td>";
  table += '<td contenteditable="true" class="saved-products text-right" >1</td>';
  table += '<td contenteditable="true"  class="saved-products"></td>';
  table += '<td><input class="saved-products button-delete" type="button" value="delete" onclick="deleteRow(this)" /></td>';
  table += "</tr>";

  if (isProductCodeInserted(product_code)) {
    var product = document.getElementById('product-list');
    product.insertAdjacentHTML('afterbegin', table);
    document.getElementById("product-name").value = ""; // Clear input form, if not clreaed selected prooduct is not cleared (remember we are not refreshing web pages!)
  } else {
    document.getElementById("product-name").value = ""; // Clear input form, if not clreaed selected prooduct is not cleared (remember we are not refreshing web pages!)
  }
  productRowCounter++;
  return false;
}

function finishAndSave() {
  //Make string from product order
  var product_cell = document.getElementsByClassName("saved-products");
  var transaction_number = document.getElementById("transaction-number").value;


  var i = 0;
  var product_string = [];
  while(product_cell[i] != undefined) {
    product_string.push(product_cell[i].textContent + ' --- ');
      if (product_cell[i].value == 'delete') {
        product_string.push(' ___ ');
      }
    i++;
  }//end while

  //Send the string to server
  var http = new XMLHttpRequest();
  var url = "<?php echo Config::get('URL'); ?>inventory/saveManualReceive/";
  var params = "product_list=" + product_string.join(' ') + "&transaction_number=" + transaction_number;

  //Send the proper header information along with the request
  http.open("POST", url, true);
  http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  http.onreadystatechange = function() {//Call a function when the state changes.
  if(http.readyState === XMLHttpRequest.DONE && http.status == 200) {
      var salesCode = http.response;
      //check response from server, if contain strting sucess, save (force user to clik save again) and reset page
      if (salesCode.indexOf('GAGAL') === -1) {
        alert(salesCode);
        resetPage();
      } else {
        alert(salesCode);
      }
    }
  }
  http.send(params);
}

</script>
</head>

<body>
  <div class="container" id="kasir-box-1">
    <table  id="table-pembayaran" class="ExcelTable2007">
      <thead>
        <tr>
          <th style="max-width: 21px;">
           Transaction Reverence:
          </th>
          <th>
            <input type="text" autocomplete="off" id="transaction-number" placeholder=" No PO, SO atau nomer transaksi yang lain" autocomplete="off"/>
          </th>
        </tr>
      </thead>
    </table>
    <table  id="table-pembayaran" class="ExcelTable2007">
      <thead>
        <tr>
          <th colspan="6">
            <iframe name="selfFrame" style="display:none;"></iframe> <!-- prevent page reload on empty submit -->
            <form method="POST" onsubmit="insertProduct(); return false;" target="selfFrame">
    <input id="product-name" autofocus type="text" name="product-name" placeholder=" Ketik nama atau kode barang disini" style="width: 100%;" autocomplete="off">
  </form>
          </th>
        </tr>
        <tr>
          <th colspan="6"><strong>DAFTAR MATERIAL</strong></th>
        </tr>
        <tr>
          <th class="">#</th>
          <th class="">Code</th>
          <th class="">Product</th>
          <th class="">Qty</th>
          <th class="">Note</th>
          <th class="">Delete</th>
        </tr>
      </thead>
      <tbody id="product-list"></tbody>
    </table>
    <button type="button" id="save-button" onclick="finishAndSave();">SIMPAN</button>
  </div>

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

// Autocomple product
var demo1 = new autoComplete({
    selector: '#product-name',
    minChars: 1,
    source: function(term, suggest){
        term = term.toLowerCase();
        
        var choices = [<?php
          foreach ($this->material_list as $key => $value) {
            $material_name = str_replace(array("'", '"'),'', $value->material_name);
            echo "'{$material_name} -- {$value->material_code}', ";
          }
        ?>];
        var suggestions = [];
        for (i=0;i<choices.length;i++)
            if (~choices[i].toLowerCase().indexOf(term)) suggestions.push(choices[i]);
        suggest(suggestions);
    }
});

<?php foreach ($this->material_list as $key => $value) { 
$material_name = str_replace(array("'", '"'),'', $value->material_name);
?>
//Save database from mysql to localstorage ?>
        localStorage.setItem("<?php echo $value->material_code; ?>", "<?php echo $material_name . " -- " . $value->material_code . " -- " . $value->unit; ?>");
<?php } ?>
</script>
</body>
</html>