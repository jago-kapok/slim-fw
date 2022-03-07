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

<style type="text/css" media="screen">
/*! normalize.css v5.0.0 | MIT License | github.com/necolas/normalize.css */

/**
 * 1. Change the default font family in all browsers (opinionated).
 * 2. Correct the line height in all browsers.
 * 3. Prevent adjustments of font size after orientation changes in
 *    IE on Windows Phone and in iOS.
 */

/* Document
   ========================================================================== */

html {
  font-family: sans-serif; /* 1 */
  line-height: 1.15; /* 2 */
  -ms-text-size-adjust: 100%; /* 3 */
  -webkit-text-size-adjust: 100%; /* 3 */
}

/* Sections
   ========================================================================== */

/**
 * Remove the margin in all browsers (opinionated).
 */

body {
  margin: 0;
}

/**
 * Add the correct display in IE 9-.
 */

article,
aside,
footer,
header,
nav,
section {
  display: block;
}

/**
 * Correct the font size and margin on `h1` elements within `section` and
 * `article` contexts in Chrome, Firefox, and Safari.
 */

h1 {
  font-size: 2em;
  margin: 0.67em 0;
}

/* Grouping content
   ========================================================================== */

/**
 * Add the correct display in IE 9-.
 * 1. Add the correct display in IE.
 */

figcaption,
figure,
main { /* 1 */
  display: block;
}

/**
 * Add the correct margin in IE 8.
 */

figure {
  margin: 1em 40px;
}

/**
 * 1. Add the correct box sizing in Firefox.
 * 2. Show the overflow in Edge and IE.
 */

hr {
  box-sizing: content-box; /* 1 */
  height: 0; /* 1 */
  overflow: visible; /* 2 */
}

/**
 * 1. Correct the inheritance and scaling of font size in all browsers.
 * 2. Correct the odd `em` font sizing in all browsers.
 */

pre {
  font-family: monospace, monospace; /* 1 */
  font-size: 1em; /* 2 */
}

/* Text-level semantics
   ========================================================================== */

/**
 * 1. Remove the gray background on active links in IE 10.
 * 2. Remove gaps in links underline in iOS 8+ and Safari 8+.
 */

a {
  background-color: transparent; /* 1 */
  -webkit-text-decoration-skip: objects; /* 2 */
}

/**
 * Remove the outline on focused links when they are also active or hovered
 * in all browsers (opinionated).
 */

a:active,
a:hover {
  outline-width: 0;
}

/**
 * 1. Remove the bottom border in Firefox 39-.
 * 2. Add the correct text decoration in Chrome, Edge, IE, Opera, and Safari.
 */

abbr[title] {
  border-bottom: none; /* 1 */
  text-decoration: underline; /* 2 */
  text-decoration: underline dotted; /* 2 */
}

/**
 * Prevent the duplicate application of `bolder` by the next rule in Safari 6.
 */

b,
strong {
  font-weight: inherit;
}

/**
 * Add the correct font weight in Chrome, Edge, and Safari.
 */

b,
strong {
  font-weight: bolder;
}

/**
 * 1. Correct the inheritance and scaling of font size in all browsers.
 * 2. Correct the odd `em` font sizing in all browsers.
 */

code,
kbd,
samp {
  font-family: monospace, monospace; /* 1 */
  font-size: 1em; /* 2 */
}

/**
 * Add the correct font style in Android 4.3-.
 */

dfn {
  font-style: italic;
}

/**
 * Add the correct background and color in IE 9-.
 */

mark {
  background-color: #ff0;
  color: #000;
}

/**
 * Add the correct font size in all browsers.
 */

small {
  font-size: 80%;
}

/**
 * Prevent `sub` and `sup` elements from affecting the line height in
 * all browsers.
 */

sub,
sup {
  font-size: 75%;
  line-height: 0;
  position: relative;
  vertical-align: baseline;
}

sub {
  bottom: -0.25em;
}

sup {
  top: -0.5em;
}

/* Embedded content
   ========================================================================== */

/**
 * Add the correct display in IE 9-.
 */

audio,
video {
  display: inline-block;
}

/**
 * Add the correct display in iOS 4-7.
 */

audio:not([controls]) {
  display: none;
  height: 0;
}

/**
 * Remove the border on images inside links in IE 10-.
 */

img {
  border-style: none;
}

/**
 * Hide the overflow in IE.
 */

svg:not(:root) {
  overflow: hidden;
}

/* Forms
   ========================================================================== */

/**
 * 1. Change the font styles in all browsers (opinionated).
 * 2. Remove the margin in Firefox and Safari.
 */

button,
input,
optgroup,
select,
textarea {
  font-family: sans-serif; /* 1 */
  font-size: 100%; /* 1 */
  line-height: 1.15; /* 1 */
  margin: 0; /* 2 */
}

/**
 * Show the overflow in IE.
 * 1. Show the overflow in Edge.
 */

button,
input { /* 1 */
  overflow: visible;
}

/**
 * Remove the inheritance of text transform in Edge, Firefox, and IE.
 * 1. Remove the inheritance of text transform in Firefox.
 */

button,
select { /* 1 */
  text-transform: none;
}

/**
 * 1. Prevent a WebKit bug where (2) destroys native `audio` and `video`
 *    controls in Android 4.
 * 2. Correct the inability to style clickable types in iOS and Safari.
 */

button,
html [type="button"], /* 1 */
[type="reset"],
[type="submit"] {
  -webkit-appearance: button; /* 2 */
}

/**
 * Remove the inner border and padding in Firefox.
 */

button::-moz-focus-inner,
[type="button"]::-moz-focus-inner,
[type="reset"]::-moz-focus-inner,
[type="submit"]::-moz-focus-inner {
  border-style: none;
  padding: 0;
}

/**
 * Restore the focus styles unset by the previous rule.
 */

button:-moz-focusring,
[type="button"]:-moz-focusring,
[type="reset"]:-moz-focusring,
[type="submit"]:-moz-focusring {
  outline: 1px dotted ButtonText;
}

/**
 * Change the border, margin, and padding in all browsers (opinionated).
 */

fieldset {
  border: 1px solid #c0c0c0;
  border-radius:5px;
  margin: 0;
  padding: 0;
  background-color: #e5edf9;
}

/**
 * 1. Correct the text wrapping in Edge and IE.
 * 2. Correct the color inheritance from `fieldset` elements in IE.
 * 3. Remove the padding so developers are not caught out when they zero out
 *    `fieldset` elements in all browsers.
 */

legend {
  box-sizing: border-box; /* 1 */
  color: inherit; /* 2 */
  display: table; /* 1 */
  max-width: 100%; /* 1 */
  margin: 0 0 10px 10px;
  padding: 5px 10px; /* 3 */
  white-space: normal; /* 1 */
  border: 1px solid #c0c0c0;
  border-radius:5px;
  background-color: #fff;
  font-weight: bolder;

}

/**
 * 1. Add the correct display in IE 9-.
 * 2. Add the correct vertical alignment in Chrome, Firefox, and Opera.
 */

progress {
  display: inline-block; /* 1 */
  vertical-align: baseline; /* 2 */
}

/**
 * Remove the default vertical scrollbar in IE.
 */

textarea {
  overflow: auto;
}

/**
 * 1. Add the correct box sizing in IE 10-.
 * 2. Remove the padding in IE 10-.
 */

[type="checkbox"],
[type="radio"] {
  box-sizing: border-box; /* 1 */
  padding: 0; /* 2 */
}

/**
 * Correct the cursor style of increment and decrement buttons in Chrome.
 */

[type="number"]::-webkit-inner-spin-button,
[type="number"]::-webkit-outer-spin-button {
  height: auto;
}

/**
 * 1. Correct the odd appearance in Chrome and Safari.
 * 2. Correct the outline style in Safari.
 */

[type="search"] {
  -webkit-appearance: textfield; /* 1 */
  outline-offset: -2px; /* 2 */
}

/**
 * Remove the inner padding and cancel buttons in Chrome and Safari on macOS.
 */

[type="search"]::-webkit-search-cancel-button,
[type="search"]::-webkit-search-decoration {
  -webkit-appearance: none;
}

/**
 * 1. Correct the inability to style clickable types in iOS and Safari.
 * 2. Change font properties to `inherit` in Safari.
 */

::-webkit-file-upload-button {
  -webkit-appearance: button; /* 1 */
  font: inherit; /* 2 */
}

/* Interactive
   ========================================================================== */

/*
 * Add the correct display in IE 9-.
 * 1. Add the correct display in Edge, IE, and Firefox.
 */

details, /* 1 */
menu {
  display: block;
}

/*
 * Add the correct display in all browsers.
 */

summary {
  display: list-item;
}

/* Scripting
   ========================================================================== */

/**
 * Add the correct display in IE 9-.
 */

canvas {
  display: inline-block;
}

/**
 * Add the correct display in IE.
 */

template {
  display: none;
}
/* ==== END NORMALIZE ==== */

/* ==== GRID SYSTEM ==== */

.container {
  width: 100%;
  margin-left: auto;
  margin-right: auto;
}

.row {
  position: relative;
  width: 100%;
}

.row [class^="col"] {
  float: left;
  margin: 0.5rem 2%;
  min-height: 0.125rem;
}

.col-1,
.col-2,
.col-3,
.col-4,
.col-5,
.col-6,
.col-7,
.col-8,
.col-9,
.col-10,
.col-11,
.col-12 {
  width: 96%;
}

.col-1-sm {
  width: 4.33%;
}

.col-2-sm {
  width: 12.66%;
}

.col-3-sm {
  width: 21%;
}

.col-4-sm {
  width: 29.33%;
}

.col-5-sm {
  width: 37.66%;
}

.col-6-sm {
  width: 46%;
}

.col-7-sm {
  width: 54.33%;
}

.col-8-sm {
  width: 62.66%;
}

.col-9-sm {
  width: 71%;
}

.col-10-sm {
  width: 79.33%;
}

.col-11-sm {
  width: 87.66%;
}

.col-12-sm {
  width: 96%;
}

.row::after {
  content: "";
  display: table;
  clear: both;
}

.hidden-sm {
  display: none;
}

@media only screen and (min-width: 33.75em) {  /* 540px */
  .container {
    width: 100%;
  }
}

@media only screen and (min-width: 45em) {  /* 720px */
  .col-1 {
    width: 4.33%;
  }

  .col-2 {
    width: 12.66%;
  }

  .col-3 {
    width: 21%;
  }

  .col-4 {
    width: 29.33%;
  }

  .col-5 {
    width: 37.66%;
  }

  .col-6 {
    width: 46%;
  }

  .col-7 {
    width: 54.33%;
  }

  .col-8 {
    width: 62.66%;
  }

  .col-9 {
    width: 71%;
  }

  .col-10 {
    width: 79.33%;
  }

  .col-11 {
    width: 87.66%;
  }

  .col-12 {
    width: 96%;
  }

  .hidden-sm {
    display: block;
  }
}

@media only screen and (min-width: 60em) { /* 960px */
  .container {
    width: 100%;
  }
}
/* ==== END GRID SYETEM ==== */


/* Style */

body {
  background-color: #cee2ff;
  text-rendering: optimizeLegibility;
}

.table-fill {
  background: white;
  border-radius:5px;
  border-collapse: collapse;
  padding:5px;
  width: 100%;
  box-shadow: 0 5px 10px rgba(0, 0, 0, 0.1);
  animation: float 5s infinite;
  width: 100%;
  margin-top: 32px;
}
th {
  color:#D5DDE5;;
  background:#1b1e24;
  border-bottom:4px solid #9ea7af;
  border-right: 1px solid #343a45;
  font-size:23px;
  font-weight: 100;
  padding:13px;
  text-align:left;
  text-shadow: 0 1px 1px rgba(0, 0, 0, 0.1);
  vertical-align:middle;
}
th:first-child {
  border-top-left-radius:5px;
}
 
th:last-child {
  border-top-right-radius:5px;
  border-right:none;
}
tr {
  border-top: 1px solid #C1C3D1;
  border-bottom-: 1px solid #C1C3D1;
  color:#666B85;
  font-size:13px;
  font-weight:normal;
  text-shadow: 0 1px 1px rgba(256, 256, 256, 0.1);
}
tr:hover td {
  background:#4E5066;
  color:#FFFFFF;
  border-top: 1px solid #22262e;
}
tr:first-child {
  border-top:none;
}
tr:last-child {
  border-bottom:none;
}
tr:nth-child(odd) td {
  background:#EBEBEB;
}
td.red-background td.red-background {
  background-color: #ff3549;
  color: #fff;
}
tr:nth-child(odd) td.red-background {
  background-color: #ff3549;
  color: #fff;
}
tr:nth-child(odd):hover td {
  background:#4E5066;
}
tr:last-child td:first-child {
  border-bottom-left-radius:3px;
}
tr:last-child td:last-child {
  border-bottom-right-radius:3px;
}
td {
  background:#FFFFFF;
  padding:13px;
  text-align:left;
  vertical-align:middle;
  font-weight:300;
  font-size:15px;
  text-shadow: -1px -1px 1px rgba(0, 0, 0, 0.1);
  border-right: 1px solid #C1C3D1;
}
td:last-child {
  border-right: 0px;
}
th {
  text-align: center;
}
th.text-left {
  text-align: left;
}
th.text-center {
  text-align: center;
}

th.text-right {
  text-align: right;
}

td.text-left {
  text-align: left;
}

td.text-center {
  text-align: center;
}

td.text-right {
  text-align: right;
}

/* INPUT with CSS */
@-webkit-keyframes blink { 
   50% {
    border-color: #fff;
        } 
}
@keyframes blink { 
   50% { 
    border-color: #fff;

  } 
}
input {
  background-color: #fff9e8;
  outline: none;
}
input:focus,
input[focus]{
  border: 3px solid transparent;
  background-color: #ff3549;
  color: #fff;
  animation: blink .5s step-end infinite alternate;
  -webkit-animation: blink .5s step-end infinite alternate;
}
input:focus::placeholder {
  color: white;
}
input[type=number] {
  text-align: right
}

/* Make Increment Number with CSS */
table {
  counter-reset:section;
}
.count:before {
  counter-increment:section;
  content:counter(section);
}
button {
  color: #ffffff;
  padding: 5px 10px;
  border: solid 1px #c0c0c0;
  background-color: #8c8eff;
  box-shadow: 1 -1 1px  rgba(0,0,0,0.6);
  -moz-box-shadow: 2 -2 2px   rgba(0,0,0,0.6);
  -webkit-box-shadow: 2 -2 2px   rgba(0,0,0,0.6);
  -o-box-shadow: 2 -2 2px   rgba(0,0,0,0.6);
  border-radius:3px;
  cursor:pointer;
}
.button-delete {
  color: #ffffff;
  padding: 5px 10px;
  border: solid 1px #c0c0c0;
  background-color: #f92a2a;
  box-shadow: 1 -1 1px  rgba(0,0,0,0.6);
  -moz-box-shadow: 2 -2 2px   rgba(0,0,0,0.6);
  -webkit-box-shadow: 2 -2 2px   rgba(0,0,0,0.6);
  -o-box-shadow: 2 -2 2px   rgba(0,0,0,0.6);
  border-radius:3px;

}

button:hover,
.button-delete:hover {
  background-color: #ccc;
  color: #000;
  cursor:pointer;
}
button:disabled,
button[disabled],
.button-delete:disabled {
  background-color: #c6c6c6;
  color: #fff;
}
.rounded-fieldset {
  -webkit-border-radius: 5px;
  -moz-border-radius: 5px;
  border-radius: 5px;
  margin-top: 21px;
}
.red-background  {
  background-color: #ff3549;
  color: #fff;
}
#price-net {
  font-size: 34px;
  color: red;
}
.big-input {
  height: 34px;
  font-size: 18px;
}
.text-right {
  text-align: right;
}
</style>

<link rel="stylesheet" type="text/css" href="<?php echo Config::get('URL'); ?>bootstrap-3.3.7/css/kasir-auto-complete.css" />
<script>
var productRowCounter = 1;
localStorage.clear(); // clear local storage

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
  var totalprice = makeFloat(document.getElementById("price-gross").textContent);
  var pembayaran = document.getElementById("pembayaran").value;
  var diskonPersen = document.getElementById("diskonPersen").value;
  var potonganHarga = document.getElementById("potonganHarga").value;
  var setelahDiskon = totalprice - ((diskonPersen/100) * totalprice) - potonganHarga;
  var kembalian = pembayaran - setelahDiskon;
  kembalian = parseFloat(kembalian).toFixed(0);
  document.getElementById('price-net').textContent = moneyFormat(setelahDiskon);
  document.getElementById('kembalian').value = moneyFormat(kembalian);
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
  var subTotal = quantity * harga; //get value from input form
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
      document.getElementById("price-gross").textContent = "TOTAL";
    } else {
      document.getElementById("price-gross").textContent = moneyFormat(sum);
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
  table += '<td class="saved-products text-right" id="' + product_code + '" onkeyup="updateQtyProd(' +  parseInt(product_price.replace(/,/g, ''), 10) + ',\'' + product_code + '\')" contenteditable="true">1</td>';
  table += '<td class="text-right saved-products" id="' + product_code + '-price">' + product_price + "</td>";
  table += '<td class="text-right subTotal red-background" id="subTotal' + product_code + '">' + moneyFormat(product_price) + '</td>';
  table += '<td class="text-center"><button class="saved-products button-delete text-center"onclick="deleteRow(this)"/>Hapus</button></td>';
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

function finishAndSave()
{
  //Make string from purchased product table
  var product_cell = document.getElementsByClassName("saved-products");
  var priceGross = document.getElementById("price-gross").textContent;
  var priceNet = document.getElementById("price-net").textContent;
  var pembayaran = document.getElementById("pembayaran").value;
  var diskonPersen = document.getElementById("diskonPersen").value;
  var potonganHarga = document.getElementById("potonganHarga").value;
  var edcBank = document.getElementById("edcBank").value;
  var edcReference = document.getElementById("edcReference").value;
  var kembalian = document.getElementById("kembalian").value;
  var token = document.getElementById("token").value;

  var i = 0;
  var product_string = [];
  while(product_cell[i] != undefined) {
    
    if (product_cell[i].textContent == 'Hapus') {
      product_string.push(' ___ ');
    } else {
      product_string.push(product_cell[i].textContent + ' --- ');
    }
    i++;
  }//end while

  //Send the string to server
  var http = new XMLHttpRequest();
  var url = "<?php echo Config::get('URL'); ?>kasir/simpanResto";
  var params = "product_list=" + product_string.join(' ') + "&priceNet=" + priceNet + "&priceGross=" + priceGross + "&diskonPersen=" + diskonPersen + "&potonganHarga=" + potonganHarga + "&pembayaran=" + pembayaran + "&kembalian=" + kembalian + "&edc_bank=" + edcBank + "&edc_reference=" + edcReference + "&token=" + token;

  http.open("POST", url, true);

  //Send the proper header information along with the request
  http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

  http.onreadystatechange = function() {//Call a function when the state changes.
      if(http.readyState === XMLHttpRequest.DONE && http.status == 200) {
          var salesCode = http.response;
          if (salesCode.indexOf('GAGAL') === -1) {
              //alert(salesCode);
              printPage(salesCode);
              resetPage();
          } else {
              alert(salesCode);
          }
      }
  }
  http.send(params);
}

function uuidv4() {
  return ([1e7]+-1e3+-4e3+-8e3+-1e11).replace(/[018]/g, c => (c ^ crypto.getRandomValues(new Uint8Array(1))[0] & 15 >> c / 4).toString(16))
}

function resetPage() {
    var list = document.getElementById('product-list');
    while (list.hasChildNodes()) {
        list.removeChild(list.firstChild);
    }

    document.getElementById("pembayaran").value='';
    document.getElementById("diskonPersen").value='';
    document.getElementById("potonganHarga").value='';
    document.getElementById("kembalian").value='';
    document.getElementById("edcBank").value='';
    document.getElementById("edcReference").value='';
    document.getElementById("price-net").textContent='';
    document.getElementById("price-gross").textContent='Total Tagihan';
    document.getElementById("token").value = uuidv4();
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
</script>
</head>

<body>
<fieldset>
  <iframe name="votar" style="display:none;"></iframe> <!-- prevent page reload on empty submit -->
  <form method="POST" onsubmit="insertProduct(); return false;" target="votar">
  <input id="hero-demo" class="big-input" autofocus type="text" name="product-name" placeholder="ketik nama atau kode barang disini" style="width: 100%;">

  </form>
</fieldset>

<div class="container">
  <div class="row">
    <div class="col-8">
      <!-- This content will take up 3/12 (or 1/4) of the container -->
        <table class="table-fill">
          <thead>
              <tr>
                <th>#</th>
                <th>Kode</th>
                <th>Nama</th>
                <th>Qty</th>
                <th>Harga</th>
                <th id="price-gross" class="red-background text-right">Total</th>
                <th>Hapus</th>
              </tr>
          </thead>
          <tbody id="product-list">
          </tbody>
        </table>
    </div>

    <div class="col-4">
      <!-- This content will take up 3/12 (or 1/4) of the container -->
      <fieldset class="rounded-fieldset">
          <legend id="price-net">TAGIHAN</legend>
          <table style="width: 100%;margin-top: 8px;">
            <tr>
              <td>
                <input type="number" placeholder="Diskon (dalam %)" id="diskonPersen" name="diskonPersen" autocomplete="off" style="width: 100%;" onkeyup="totalKembalian();" class="text-right big-input"/>
              </td>
            </tr>
            <tr>
              <td>
                <input type="number" placeholder="Diskon (dalam rupiah)" id="potonganHarga" name="potonganHarga" autocomplete="off" style="width: 100%;" onkeyup="totalKembalian();" class="align-right big-input"/>
              </td>
            </tr>
            <tr>
              <td>
                <SELECT class="big-input" style="width: 100%;" id="edcBank">
                    <option>Pilih Vendor Bank EDC</option>
                    <option value="bca">BCA</option>
                    <option value="bri">BRI</option>
                    <option value="bni">BNI</option>
                    <option value="mandiri">MANDIRI</option>
                    <option value="cimb niaga">CIMB NIAGA</option>
                </SELECT>
              </td>
            </tr>
            <tr>
              <td class="text-right">
                <input type="text" placeholder="Kode Reference EDC" id="edcReference" autocomplete="off" style="width: 100%;" class="big-input"/>
              </td>
            </tr>
            <tr>
              <td>
                <input type="number" placeholder="Jumlah Pembayaran" id="pembayaran" name="pembayaran" autocomplete="off" style="width: 100%;" onkeyup="totalKembalian();" class="align-right big-input"/>
              </td>
            </tr>
            <tr>
              <td class="text-right">
                <input type="text" placeholder="Kembalian" id="kembalian" name="kembalian" autocomplete="off" style="width: 100%;" class="text-right big-input" readonly/>
              </td>
            </tr>
            <tr>
              <td>
                <button type="button" id="save-button" onclick="finishAndSave();" disabled="">SIMPAN</button>
              </td>
            </tr>
        </table>
      </fieldset>
    </div>
  </div>
</div>

<input type="hidden" name="" id="token" value="<?php echo GenericModel::guid(); ?>">

<script src="<?php echo Config::get('URL'); ?>bootstrap-3.3.7/js/kasir-auto-complete.min.js"></script>
<script>
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
