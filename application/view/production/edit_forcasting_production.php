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

function redirectPage(url) {
    window.location = url;
}

function isMaterialCodeInserted(product_code) {
  var cell = document.getElementsByClassName("product-code");
  for(var i = 0; i < cell.length; i++) {
      if (cell[i].innerHTML == product_code) {
          return false;
      }
  }
  return true;
}

function insertMaterial() {
  var material = document.getElementById('material-name').value; //get value from input form
  if (material.indexOf(' -- ') === -1) { //check if it contain ' -- ' string or not, if not get from local storage
    material_string = localStorage.getItem(material);
  } else {
    material_string = material;
  }
  var material_array = material_string.split(" -- ");
  var material_name = material_array[0];
  var material_code = material_array[1];
  var material_unit = material_array[2];

  var table = "<tr>";
  table += '<td class="count"></td>';
  table += '<td class="saved-materials material-code">' + material_code + "</td>";
  table += '<td>' + material_name + "</td>";
  table += '<td contenteditable="true" class="saved-materials" >1</td>';
  table += '<td class="saved-materials" >' + material_unit + "</td>";
  table += '<td><input class="saved-materials button-delete" type="button" value="delete" onclick="deleteRow(this)" /></td>';
  table += "</tr>";

  if (isMaterialCodeInserted(material_code)) {
    var material = document.getElementById('material-list');
    material.insertAdjacentHTML('afterbegin', table);
    document.getElementById("material-name").value = ""; // Clear input form, if not clreaed selected prooduct is not cleared (remember we are not refreshing web pages!)
  } else {
    document.getElementById("material-name").value = ""; // Clear input form, if not clreaed selected prooduct is not cleared (remember we are not refreshing web pages!)
  }
  materialRowCounter++;
  return false;
}

function finishAndSave() {
  //Make string from product order
  var material_cell = document.getElementsByClassName("saved-materials");
  var job_type = document.getElementById("job-type").value;
  var job_note = document.getElementById("job-note").value;
  var so_number = document.getElementById("so-number").innerHTML;
  var production_number = document.getElementById("production-number").innerHTML;


  var i = 0;
  var material_string = [];
  while(material_cell[i] != undefined) {
    material_string.push(material_cell[i].innerHTML + ' --- ');
      if (material_cell[i].value == 'delete') {
        material_string.push(' ___ ');
      }
    i++;
  }//end while

  //Send the string to server
  var http = new XMLHttpRequest();
  var url = "<?php echo Config::get('URL'); ?>production/saveEditJobOrder/";
  var params = "material_list=" + material_string.join(' ') + "&so_number=" + so_number + "&production_number=" + production_number + "&job_type=" + job_type + "&job_note=" + job_note;

  //Send the proper header information along with the request
  http.open("POST", url, true);
  http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  http.onreadystatechange = function() {//Call a function when the state changes.
  if(http.readyState === XMLHttpRequest.DONE && http.status == 200) {
      var salesCode = http.response;
      //check response from server, if contain strting sucess, save (force user to clik save again) and reset page
      if (salesCode.indexOf('GAGAL') != -1) {
        alert(salesCode);
      } else {
        alert(salesCode);
        redirectPage(salesCode);
      }
    }
  }
  http.send(params);
}

<?php foreach ($this->material_list as $key => $value) { //Save database from mysql to localstorage ?>
        localStorage.setItem("<?php echo $value->material_code; ?>", "<?php echo $value->material_name . " -- " . $value->material_code . " -- " . $value->unit; ?>");
<?php } ?>
</script>
</head>

<body>
  <?php //var_dump($this->product_list ); ?>
  <div class="container" id="kasir-box-1">
    <table  id="table-pembayaran" class="ExcelTable2007">
      <thead>
        <tr>
          <th>Forcasting Number</th>
          <th id="production-number"><?php echo urldecode(Request::get('transaction_number')); ?></th>
          <th>Tipe Order:</th>
          <th>
            <select id="job-type">
              <option value="baru">Baru</option>
              <option value="modifikasi">Modifikasi</option>
              <option value="repair garansi">Repair Garansi</option>
              <option value="repair murni">Repair Murni</option>
            </select>
          </th>
        </tr>
      </thead>
    </table>
    <br>
    <br>
    <br>
    <table class="ExcelTable2007">
      <thead>
        <tr>
          <tr>
          <th colspan="6"><strong>DAFTAR BOM/BAHAN BAKU</strong>
          </th>
        </tr>
          <th colspan="6">
            <iframe name="material-frame" style="display:none;"></iframe> <!-- prevent page reload on empty submit -->
            <form method="POST" onsubmit="insertMaterial(); return false;" target="material-frame">
              <input id="material-name" type="text" name="material-name" placeholder=" Ketik nama atau kode material bahan baku atau BOM disini" style="width: 100%;" autocomplete="off">
            </form>
          </th>
        </tr>
        <tr>
          <th>#</th>
          <th>Code</th>
          <th>Name</th>
          <th>Qty</th>
          <th>Satuan</th>
          <th>Delete</th>
        </tr>
      </thead>
      <tbody id="material-list">
        <?php 
          foreach ($this->production_forcasting as $key => $value) {
            echo "<tr>";
            echo '<td class="count"></td>';
            echo '<td class="saved-materials material-code">' . $value->material_code . "</td>";
            echo '<td>' . $value->material_name . "</td>";
            echo '<td contenteditable="true" class="saved-materials text-right" >' . floatval($value->quantity) . '</td>';
            echo '<td class="saved-materials" >' . $value->unit . "</td>";
            echo '<td><input class="saved-materials button-delete" type="button" value="delete" onclick="deleteRow(this)" /></td>';
            echo "</tr>";
          }
        ?>
      </tbody>
    </table>
    <button type="button" id="save-button" onclick="finishAndSave();">SIMPAN</button>
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
// Autocomple contact
var customerID = new autoComplete({
    selector: '#material-name',
    minChars: 1,
    source: function(term, suggest){
        term = term.toLowerCase();
        
        var choices = [<?php
          foreach ($this->material_list as $key => $value) {
            echo "'{$value->material_name} -- {$value->material_code} -- {$value->unit}', ";
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