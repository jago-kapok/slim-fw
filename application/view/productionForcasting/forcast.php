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
  table += '<td contenteditable="true" class="saved-materials" ><?php echo Request::get('quantity'); ?></td>';
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
  var so_number = document.getElementById("so_number").textContent;
  document.getElementById("save-button").disabled = true; //disabled button to prevent double input


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
  var url = "<?php echo Config::get('URL'); ?>productionForcasting/saveForcasting";
  var params = "material_list=" + material_string.join(' ') + "&so_number=" + so_number + "&job_type=" + job_type;

  //Send the proper header information along with the request
  http.open("POST", url, true);
  http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  http.onreadystatechange = function() { //Call a function when the state changes.
    if(http.readyState === XMLHttpRequest.DONE && http.status == 200) {
        var salesCode = http.response;
        //check response from server
        if (salesCode.indexOf('GAGAL') === -1) {
            //submit success, redirect to so page /redirectPage(salesCode);
            //console.log(salesCode);
            //alert(salesCode);
            redirectPage(salesCode);
          } else {
            //submit fail, echo message
            alert(salesCode);
            //console.log(salesCode);
            //submit fail, open again submit button
            document.getElementById("save-button").disabled = false; 
        }
      }
  }
  http.send(params);
}

<?php foreach ($this->material_list as $key => $value) { //Save database from mysql to localstorage
      $material_name = str_replace(array("'", '"'),'', $value->material_name);
?>
        localStorage.setItem("<?php echo $value->material_code; ?>", "<?php echo $material_name . " -- " . $value->material_code . " -- " . $value->unit; ?>");
<?php } ?>
</script>
</head>

<body>
  <?php //var_dump($this->product_list ); ?>
  <div class="container" id="kasir-box-1">
    <table  id="table-pembayaran" class="ExcelTable2007">
      <thead>
        <tr>
          <th>SO Reverence:</th>
          <th id="so_number"><?php echo urldecode(Request::get('so_number'));?></th>
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
    <table class="ExcelTable2007">
      <thead>
        <tr>
          <th colspan="6">
            <iframe name="material-frame" style="display:none;"></iframe> <!-- prevent page reload on empty submit -->
            <form method="POST" onsubmit="insertMaterial(); return false;" target="material-frame">
              <input id="material-name" type="text" name="material-name" placeholder=" Ketik kode/nama BOM atau  kode/nama material bahan baku disini" style="width: 100%;" autocomplete="off">
            </form>
          </th>
        </tr>
        <tr>
          <th class="">#</th>
          <th class="">Code</th>
          <th class="">Name</th>
          <th class="">Qty</th>
          <th class="">Satuan</th>
          <th class="">Delete</th>
        </tr>
      </thead>
      <tbody id="material-list"></tbody>
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
</script>
</body>
</html>