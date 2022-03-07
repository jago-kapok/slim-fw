<!doctype html>

<html lang="en-150">
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

<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
<link rel="stylesheet" type="text/css" href="<?php echo Config::get('URL'); ?>bootstrap-3.3.7/css/kasir-auto-complete.css" />
<style type="text/css">
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
/*I love me some border-box*/
* {
    box-sizing: border-box;
}

body {
    overflow-x: hidden; /*This just stops me getting horizontal scrolling if anything overflows the width*/
    background-color: #fff;
    text-rendering: optimizeLegibility;
}

/*layout*/

/*Flexbox gives us the flexiness we need. The top just stays put as there is no scrolling on the body due to the page never exceeding viewport height*/
.Top {
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 3rem;
    position: relative;
    z-index: 10;
    height: 40px;
}
/*This is our main wrapping element, it's made 100vh high to ensure it is always the correct size and then moved into place and padded with negative margin and padding*/
.Container {
    display: flex;
    overflow: hidden;
    height: calc(110vh - 100px);
    position: relative;
    width: 100%;
}

/*All the scrollable sections should overflow and be whatever height they need to be. As they are flex-items (due to being inside a flex container) they could be made to stretch full height at all times if needed.
WebKit inertia scrolling is being added here for any present/future devices that are able to make use of it.
*/
.Left,
.Middle,
.Right {
    overflow: auto;
    height: auto;
    -webkit-overflow-scrolling: touch;
    -ms-overflow-style: none;
}
#table-order {
    border:1px solid #D0D7E5 !important;
}
#table-pembayaran {
    border:1px solid #D0D7E5 !important;
}
.tab {
    border-top: 2px solid #eaf1fc !important;
    border-left: 2px solid #eaf1fc !important;
    border-right: 2px solid #eaf1fc !important;
    border-bottom: 0px solid #eaf1fc !important;
}

/*  Left and Right are set sizes while the Middle is set to flex one so it occupies all remaining space. This could be set as a width too if prefereable, perhaps using calc.*/
.Left {
    width: 0rem;

}
.Middle {
    flex: 1;
    padding: 10px 3px 0 3px;
}
.Right {
    flex: 1;
    padding: 10px 3px 0 0px;
}

/* table format */

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
  height: 40px;
  font-size: 15pt;
  width: 100%;
  background-color: #00a6d8;
  color: #fff;
}
#hero-demo::-webkit-input-placeholder { /* Chrome/Opera/Safari */
  color: #fff;
}
input:focus#hero-demo,
input[focus]#hero-demo{
  border: 1px solid #e2e2e2;
  background-color: #ff3549;
  color: #fff;
  animation: blink .5s step-end infinite alternate;
  -webkit-animation: blink .5s step-end infinite alternate;
}
.ExcelTable2007 {
  
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
}
.ExcelTable2007 th {
  vertical-align: middle;
  padding:7px 3px;
  background-color: #E4ECF7;
  text-align: center;
  border: 1px solid #9EB6CE;
  border-width: 0px 1px 1px 0px;
}
.ExcelTable2007 td.heading {
  background-color: #E4ECF7;
  border: 1px solid #9EB6CE;
  border-width: 0px 1px 1px 0px;
}
.ExcelTable2007 td input,
.ExcelTable2007 td textarea {
  background: #c1fffd;
  height: 38px;
  padding: 0;
  margin: 0;
  border: 0;
  font-size: 11pt;
  width: 100%;
}
.ExcelTable2007 td select {
  background: #c1fffd;
  height: 37px;
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
option {
  height: 34px;
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
button:hover {
  color:#000!important;
  background-color:#ccc!important;
  cursor:pointer;
}
button:disabled,
button[disabled]{
  border: 1px solid #999999;
  background-color: #858c99;
  color: #fff;
}

/* Make Increment Number with CSS */

#save-button {
  padding: 5px;
  font-size: 1rem;
  width: 100%;
  min-height: 39px;
  height: 100%;
}
.product-button {
  padding: 5px;
  font-size: 1rem;
  width: 100%;
  min-height: 55px;
  height: 100%;
  float: left;
}
.color-0 {
  background-color: #efecb3;
  color: #000;
}
.color-1 {
  background-color: #f2ea57;
  color: #000;
}
.color-2 {
  background-color: #f7ea00;
  color: #000;
}
.color-3 {
  background-color: #c9efb3;
  color: #000;
}
.color-4 {
  background-color: #8cef53;
  color: #000;
}
.color-5 {
  background-color: #5dff00;
  color: #000;
}
.color-6 {
  background-color: #d4fcf9;
  color: #000;
}
.color-7 {
  background-color: #76f2e8;
  color: #000;
}
.color-8 {
  background-color: #00ffe9;
  color: #000;
}
.color-9 {
  background-color: #d6defc;
  color: #000;
}
.color-10 {
  background-color: #6687ff;
  color: #000;
}
.color-11 {
  background-color: #0037ff;
  color: #fff;
}
.color-12 {
  background-color: #eeddff;
  color: #000;
}
.color-13 {
  background-color: #b376ed;
  color: #000;
}
.color-14 {
  background-color: #8300ff;
  color: #fff;
}
.color-15 {
  background-color: #ffd8d8;
  color: #000;
}
.color-16 {
  background-color: #ff7575;
  color: #000;
}
.color-17 {
  background-color: #ef1717;
  color: #fff;
}
.color-18 {
  background-color: #d3d3d3;
  color: #000;
}
.color-19 {
  background-color: #636363;
  color: #fff;
}
.color-20 {
  background-color: #2b2b2b;
  color: #fff;
}
/* Make Increment Number with CSS */

table {
  counter-reset:section;
}
.count:before {
  counter-increment:section;
  content:counter(section);
}

/* text formating */
.text-left {
  text-align: left;
}
.text-center {
  text-align: center;
}
.text-right {
  text-align: right;
}

/*specific style */

#product-list td {
    padding: 5px;
}

.tab{width:100%;overflow:hidden;color:#fff!important;background-color:#14b6e8!important}
.tab .tab-item{padding:8px 16px;float:left;width:auto;border:none;display:block;outline:0}
.tab .button-tab{
  white-space:normal, color;
  background-color: #14b6e8;
  box-shadow: 0px;
  -moz-box-shadow: 0px;
  -webkit-box-shadow: 0px;
  -o-box-shadow: 0px;
  border-radius:0px;
  margin: 0px;
  }
.tab:before,.tab:after{content:"";display:table;clear:both;}
.w3-border{border:1px solid #eaf1fc!important}
.button-tab:hover{color:#000!important;background-color:#ccc!important}
/* Colors */
.buttton-red{color:#fff!important;background-color:#f44336!important}
h1 {
  text-align: center;
  color: #ef1717;
  font-size: 21pt;
}
</style>

    <?php if (isset($this->header_script)) {echo $this->header_script;} ?>
</head>
    <body>
<div id="flex">
<div class="Top">
  <iframe name="selfFrame" style="display:none;"></iframe> <!-- prevent page reload on empty submit -->
  <form method="POST" onsubmit="insertProduct(); return false;" target="selfFrame" style="width: 100%;">
    <input id="hero-demo" autofocus type="text" name="product-name" placeholder=" Ketik nama atau kode barang disini" autocomplete="off">
  </form>
</div>
<div class="Container">
<div class="Middle">
    <table id="table-pembayaran" class="ExcelTable2007" style="width: 100%;">
        <tbody>
            <tr>
              <td>
                <input type="number" placeholder="Diskon (%)" id="diskon-persen" name="diskon-persen" autocomplete="off" style="width: 100%;" onkeyup="totalKembalian();" class="text-right big-input"/>
              </td>
              <td>
                <input type="number" placeholder="Diskon (Rupiah)" id="potongan-harga" name="potongan-harga" autocomplete="off" style="width: 100%;" onkeyup="totalKembalian();" class="text-right big-input"/>
              </td>
            <tr>
              <td>
                <SELECT class="big-input" style="width: 100%;" id="edc-bank">
                    <option>Pilih Vendor Bank EDC</option>
                    <option value="bca">BCA</option>
                    <option value="bri">BRI</option>
                    <option value="bni">BNI</option>
                    <option value="mandiri">MANDIRI</option>
                    <option value="cimb niaga">CIMB NIAGA</option>
                </SELECT>
              </td>
            
              <td class="text-right">
                <input type="text" placeholder="Kode Reference EDC" id="edc-reference" autocomplete="off" style="width: 100%;" class="big-input"/>
              </td>
            </tr>
            <tr>
              <td class="text-right">
                <input type="text" placeholder="Jumlah Pembayaran" id="pembayaran" name="pembayaran" autocomplete="off" style="width: 100%;" onkeyup="totalKembalian();" class="text-right big-input"/>
              </td>
              <td>
                <button type="button" onclick="finishAndSave();" id="save-button" disabled="disabled">SIMPAN</button>
              </td>
            </tr>
            
        </tbody>
        </table>
    <table id="table-order" class="ExcelTable2007">
          <thead>
            <tr>
              <th id="total-price" colspan="3">TAGIHAN</th>
              <th id="kembalian" colspan="4">KEMBALIAN</th>
            </tr>
            <tr>
              <th  class="center">#</th>
              <th class="center">Kode</th>
              <th  class="center">Nama</th>
              <th  class="center">Qty</th>
              <th  class="center">Harga</th>
              <th  class="center">Sub</th>
              <th  class="center"></th>
            </tr>
          </thead>
          <tbody id="product-list">
            <tr>
              <td  class="center heading text-right" colspan="5">Total</td>
              <td  class="text-right heading" id="sub-total" colspan="2"></td>
            </tr>
          </tbody>
        </table>
</div>
<div class="Right">
      <div class="tab">
        <?php
          $menu_category = 'no menu';
          $no = 0;
          $category_array = array();
          foreach($this->product_list as $key => $value) {
            if ($menu_category != $value->material_category) {
              $style = ($no === 0) ? 'buttton-red' : '';
              echo '<button class="tab-item button-tab tablink ' . $style . '" onclick="openTab(event,\'menu-' . $no .'\')">' . ucwords($value->material_category) . '</th>';
              $category_array['number'][] = 'menu-' . $no;
              $category_array['menu'][] = $value->material_category;
              $no++;
            }
            $menu_category = $value->material_category;
          }
        ?>
        <button class="tab-item button-tab tablink" onclick="openTab(event,'London')">Semua</button>
      </div>
      <?php 
        //echo count($category_array['number']);
        //echo '<pre>';var_dump($category_array);echo '</pre>';
        $menu_num = 0;
        $menu_category = 'no menu';
        foreach ($category_array['number'] as $category) {
        //echo $category;
        $style = ($menu_num === 0) ? '' : 'style="display:none"';
      ?>
          <div id="<?php echo $category; ?>" class="w3-container w3-border city" <?php echo $style ?>>
              <table style="width: 100%;" class="ExcelTable2007">
                <?php
                  $no = 0;
                  foreach ($this->product_list as $key => $value) {
                    if ($category_array['menu'][$menu_num] == $value->material_category) {
                      if ($no % 3 == 0) echo '<tr>';
                        echo '<td><button class="product-button color-' . $no . '" onclick="clikProduct(\'' . $value->material_code .'\');">' . $value->material_name . '</button></td>';
                      if ($no % 3 == 2) echo '</tr>';
                      $no++;
                    }
                    
                  }
                  $menu_name = $value->material_name;
                  $menu_category = $value->material_category;
                  if ($no % 3 != 0) echo '</tr>'; // close last line, unless total count was multiple of 3
                ?>
              </table>
          </div>
      <?php $menu_num++;} ?>

      <div id="London" class="w3-container w3-border city" style="display:none">
            <table style="width: 100%;">
              <?php
                $no = 0;
                foreach ($this->product_list as $key => $value) {
                  if ($no % 3 == 0) echo '<tr>';
                    echo '<td><button class="product-button color-' . $no . '" onclick="clikProduct(\'' . $value->material_code .'\');">' . $value->material_name . '</button></td>';
                  if ($no % 3 == 2) echo '</tr>';
                  $no++;
                }
                if ($no % 3 != 0) echo '</tr>'; // close last line, unless total count was multiple of 3
              ?>        
                    
            </table>
      </div>
</div>
</div>
</div>

<div id="offline">
  <h1>SISTEM OFFLINE/PUTUS!</h1>
</div>
<script src="<?php echo Config::get('URL'); ?>bootstrap-3.3.7/js/kasir-auto-complete.min.js"></script>
<script>
var productRowCounter = 1;
localStorage.clear(); // clear local storage

//check internet connection online or ofline

function updateOnlineStatus()
{
    document.getElementById('flex').style.display = 'inherit'; //hide
    document.getElementById('offline').style.display = 'none'; //show
}
function updateOfflineStatus()
{
    document.getElementById('flex').style.display = 'none'; //hide
    document.getElementById('offline').style.display = 'flex'; //show
}

window.addEventListener('online',  updateOnlineStatus);
window.addEventListener('offline', updateOfflineStatus);


// FUNCTION FOR PRINT
function closePrint () {
  document.body.removeChild(this.__container__);
}

function setPrint () {
  this.contentWindow.__container__ = this;
  this.contentWindow.onbeforeunload = closePrint;
  this.contentWindow.onafterprint = closePrint;
  this.contentWindow.focus(); // Required for IE
  this.contentWindow.print();
}

function printPage (sURL) {
  var oHiddFrame = document.createElement("iframe");
  oHiddFrame.onload = setPrint;
  oHiddFrame.style.visibility = "hidden";
  oHiddFrame.style.position = "fixed";
  oHiddFrame.style.right = "0";
  oHiddFrame.style.bottom = "0";
  oHiddFrame.src = sURL;
  document.body.appendChild(oHiddFrame);
}
// END FUNCTION FOR PRINT

function makeFloat(str) { // change string to float
  var result = str.replace (/,/g, ""); // remove comma in format money number, eg 2,500.68
   return  parseFloat(result);
}

function moneyFormat(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

function totalKembalian() {
  var totalprice = makeFloat(document.getElementById("sub-total").textContent);
  var pembayaran = makeFloat(document.getElementById("pembayaran").value);
  var diskonPersen = Number(document.getElementById("diskon-persen").value);
  var potonganHarga = Number(document.getElementById("potongan-harga").value);
  var setelahDiskon = totalprice - ((diskonPersen/100) * totalprice) - potonganHarga;
  var kembalian = pembayaran - setelahDiskon;
  //kembalian = parseFloat(kembalian).toFixed(0);
  document.getElementById('total-price').textContent = moneyFormat(setelahDiskon);
  document.getElementById('kembalian').textContent = moneyFormat(kembalian);
    if (kembalian >= 0) {
      document.getElementById("save-button").disabled = false;
    }
}

function deleteRow(btn) {
  var row = btn.parentNode.parentNode;
  row.parentNode.removeChild(row);
  totalPrice(); // Update totaal tagihan
  totalKembalian(); // update total tagihan
}

function updateQtyProd(harga, product_code) { // Ajax to update quantity in
  var quantity = document.getElementById(product_code).textContent; //get value from input form
  var subTotal = parseInt(quantity) * parseInt(harga); //get value from input form
  document.getElementById('subTotal' + product_code).textContent = moneyFormat(subTotal);
  totalPrice(); // Update totaal tagihan
  totalKembalian(); // update total tagihan
}

function totalPrice() {
 var cls = document.getElementsByClassName("subTotal");
    var sum = 0;
    for (var i = 0; i < cls.length; i++){
        sum += makeFloat(cls[i].textContent);
    }
    if (sum == 0) {
      document.getElementById("sub-total").textContent = "TAGIHAN";
    } else {
      document.getElementById("sub-total").textContent = moneyFormat(sum);
    }
}

function insertProduct()
{
  var product = document.getElementById('hero-demo').value; //get value from input form
  if (product.indexOf(' -- ') === -1) {
    product_string = localStorage.getItem(product);
  } else {
    product_string = product;
  }
  var product_array = product_string.split(" -- ");
  var product_name = product_array[0];
  var product_code = product_array[1];
  var product_price = product_array[2];

  var table = "<tr>";
  table += '<td class="count heading"></td>';
  table += '<td class="saved-products product-code">' + product_code + "</td>";
  table += '<td>' + product_name + "</td>";
  table += '<td class="text-right saved-products" id="' + product_code + '" onkeyup="updateQtyProd(' +  parseInt(product_price.replace(/,/g, ''), 10) + ',\'' + product_code + '\')" contenteditable="true">1</td>';
  table += '<td class="text-right saved-products" id="' + product_code + '-price">' + product_price + "</td>";
  table += '<td class="text-right subTotal" id="subTotal' + product_code + '">' + moneyFormat(product_price) + '</td>';
  table += '<td ><button class="saved-products button-delete"onclick="deleteRow(this)"/>delete</button></td>';
  table += "</tr>";

  if (isProductCodeInserted(product_code)) {
    var product = document.getElementById('product-list');
    product.insertAdjacentHTML('afterbegin', table);
  }

  document.getElementById("hero-demo").value = ""; // Clear input form, if not clreaed selected prooduct is not cleared (remember we are not refreshing web pages!)
  totalPrice(); // Update totaal tagihan
  totalKembalian(); // update total tagihan
  productRowCounter++;
  return false;
}

function clikProduct(product)
{
  product_string = localStorage.getItem(product);

  var product_array = product_string.split(" -- ");
  var product_name = product_array[0];
  var product_code = product_array[1];
  var product_price = product_array[2];

  var table = "<tr>";
  table += '<td class="count heading"></td>';
  table += '<td class="saved-products product-code">' + product_code + "</td>";
  table += '<td>' + product_name + "</td>";
  table += '<td class="saved-products text-right" id="' + product_code + '" onkeyup="updateQtyProd(' +  parseInt(product_price.replace(/,/g, ''), 10) + ',\'' + product_code + '\')" contenteditable="true">1</td>';
  table += '<td class="text-right saved-products" id="' + product_code + '-price">' + product_price + "</td>";
  table += '<td class="text-right subTotal" id="subTotal' + product_code + '">' + moneyFormat(product_price) + '</td>';
  table += '<td ><button class="saved-products button-delete"onclick="deleteRow(this)"/>delete</button></td>';
  table += "</tr>";

  if (isProductCodeInserted(product_code)) {
    var product = document.getElementById('product-list');
    product.insertAdjacentHTML('afterbegin', table);
    document.getElementById("hero-demo").value = ""; // Clear input form, if not clreaed selected prooduct is not cleared (remember we are not refreshing web pages!)
  }
  document.getElementById("hero-demo").value = ""; // Clear input form, if not clreaed selected prooduct is not cleared (remember we are not refreshing web pages!)
  totalPrice(); // Update totaal tagihan
  totalKembalian(); // update total tagihan
  productRowCounter++;
  return false;
}

function finishAndSave()
{
  //Make string from purchased product table
  var product_cell = document.getElementsByClassName("saved-products");
  var priceGross = document.getElementById("sub-total").textContent;
  var pembayaran = document.getElementById("pembayaran").value;
  var diskonPersen = document.getElementById("diskon-persen").value;
  var potonganHarga = document.getElementById("potongan-harga").value;
  var edcBank = document.getElementById("edc-bank").value;
  var edcReference = document.getElementById("edc-reference").value;
  var priceNet = document.getElementById("total-price").textContent;
  var kembalian = document.getElementById("kembalian").textContent;

  var i = 0;
  var product_string = [];
  while(product_cell[i] != undefined) {
    
    if (product_cell[i].textContent == 'delete') {
      product_string.push(' ___ ');
    } else {
      product_string.push(product_cell[i].textContent + ' --- ');
    }
    i++;
  }//end while

  //Send the string to server
  var http = new XMLHttpRequest();
  var url = "<?php echo Config::get('URL'); ?>kasir/simpanResto";
  var params = "product_list=" + product_string.join(' ') + "&priceNet=" + priceNet + "&priceGross=" + priceGross + "&diskonPersen=" + diskonPersen + "&potonganHarga=" + potonganHarga + "&pembayaran=" + pembayaran + "&kembalian=" + kembalian + "&edcBank=" + edcBank + "&edcReference=" + edcReference;
  http.open("POST", url, true);

  //Send the proper header information along with the request
  http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

  http.onreadystatechange = function() {//Call a function when the state changes.
      if(http.readyState === XMLHttpRequest.DONE && http.status == 200) {
          var salesCode = http.response;
          if (salesCode.indexOf('GAGAL') === -1) {
              printPage(salesCode);
              resetPage();
            } else {
              //alert(salesCode);
            }
          
      }
  }
  http.send(params);
}

function resetPage() {
    var list = document.getElementById('product-list');
    while (list.hasChildNodes()) {
        list.removeChild(list.firstChild);
    }
    document.getElementById("pembayaran").value='';
    document.getElementById("diskon-persen").value='';
    document.getElementById("potongan-harga").value='';
    document.getElementById("edc-bank").value='Pilih Vendor Bank EDC';
    document.getElementById("edc-reference").value='';
    document.getElementById("total-price").textContent='TAGIHAN';
    document.getElementById("kembalian").textContent='KEMBALIAN';
    document.getElementById("sub-total").textContent='';
    document.getElementById("save-button").disabled = true;
}

function isProductCodeInserted(product_code) {
  var cell = document.getElementsByClassName("product-code");
  for(var i = 0; i < cell.length; i++) {
      if (cell[i].textContent == product_code) {
          var product = document.getElementById(product_code).textContent; //get qty from product table
          product = parseInt(product) + 1;
          document.getElementById(product_code).textContent = product;
          var price = document.getElementById(product_code + '-price').textContent;
          updateQtyProd(parseInt(price.replace(/,/g, ''), 10),product_code);
          return false;
      }
  }
  return true;
}


//Save database from mysql to localstorage
<?php
    foreach ($this->product_list as $key => $value) { 
      $material_name = str_replace(array("'", '"'),'', $value->material_name);
?>
      localStorage.setItem("<?php echo $value->material_code; ?>", "<?php echo $material_name . " -- " . $value->material_code . " -- " . number_format($value->selling_price, 0); ?>");
<?php } ?>

function openTab(evt, cityName) {
  var i, x, tablinks;
  x = document.getElementsByClassName("city");
  for (i = 0; i < x.length; i++) {
      x[i].style.display = "none";
  }
  tablinks = document.getElementsByClassName("tablink");
  for (i = 0; i < x.length; i++) {
      tablinks[i].className = tablinks[i].className.replace("buttton-red", "");
  }
  document.getElementById(cityName).style.display = "block";
  evt.currentTarget.className += " buttton-red";
}

//Auto Complete
var demo1 = new autoComplete({
    selector: '#hero-demo',
    minChars: 1,
    source: function(term, suggest){
        term = term.toLowerCase();
        
        var choices = [<?php
          foreach ($this->product_list as $key => $value) {
            $material_name = str_replace(array("'", '"'),'', $value->material_name);
            echo "'{$material_name} -- {$value->material_code} -- " . number_format($value->selling_price, 0) . "', ";
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
