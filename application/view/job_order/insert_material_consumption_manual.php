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
#material-name {
  height: 55px;
  font-size: 15pt;
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
.ExcelTable2007 tr td input,
.ExcelTable2007 tr td textarea {
  padding: 8px 0 8px 0;
  margin: 0;
  border: none;
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
  width: 100%;
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
var materialRowCounter = 1;
localStorage.clear(); // clear local storage

function deleteRow(btn) {
  var row = btn.parentNode.parentNode;
  row.parentNode.removeChild(row);
}

function resetPage(url) {
    document.location.reload(true);
}

function isMaterialCodeInserted(product_code) {
  var cell = document.getElementsByClassName("material-code");
  for(var i = 0; i < cell.length; i++) {
      if (cell[i].textContent == product_code) {
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
  var material_array = material_string.split(' -- ');
  var material_name = material_array[0];
  var material_code = material_array[1];
  var material_unit = material_array[2];

  var table = "<tr>";
  table += '<td class="count"></td>';
  table += '<td>' + material_code + "</td>";
  table += '<td>' + material_name + "</td>";
  table += '<td>' + material_unit + "</td>";
  table += '<td><input name="consumed_quantity_' +  materialRowCounter + '" type="text"><input name="material_code_' +  materialRowCounter + '" type="hidden" value="' + material_code + '"></td>';
  table += '<td><input name="note_' +  materialRowCounter + '" type="text"></td>';
  table += '<td><input class="button-delete" type="button" value="delete" onclick="deleteRow(this)" /></td>';
  table += "</tr>";

  if (isMaterialCodeInserted(material_code)) {
    var material = document.getElementById('material-list');
    material.insertAdjacentHTML('afterbegin', table);
    document.getElementById("material-name").value = ""; // Clear input form, if not clreaed selected prooduct is not cleared (remember we are not refreshing web pages!)
    document.getElementById("total-input").value = materialRowCounter;
  } else {
    document.getElementById("material-name").value = ""; // Clear input form, if not clreaed selected prooduct is not cleared (remember we are not refreshing web pages!)
  }
  materialRowCounter++;
  return false;
}

<?php foreach ($this->material_list as $key => $value) { 
      $material_name = str_replace(array("'", '"'),'', $value->material_name);
      //Save database from mysql to localstorage ?>
        localStorage.setItem("<?php echo $value->material_code; ?>", "<?php echo $material_name . " -- " . $value->material_code . " -- " . $value->unit; ?>");
<?php } ?>
</script>
</head>

<body>
  <fieldset>
  <iframe name="material-frame" style="display:none;"></iframe> <!-- prevent page reload on empty submit -->
    <form method="POST" onsubmit="insertMaterial(); return false;" target="material-frame">
      <input id="material-name" type="text" name="material-name" placeholder=" Ketik nama atau kode material bahan baku atau BOM disini" style="width: 100%;" autocomplete="off">
    </form>
</fieldset>

  <div class="container" id="kasir-box-1">
    <form method="post" action="<?php echo Config::get('URL') . 'JobOrder/saveMaterialConsumptionByBOM'; ?>">
    <table class="ExcelTable2007">
      <thead>
        <tr>
          <th id="job_number" colspan="7">
            <?php echo $this->job_number; ?>
            <input type="hidden" name="job_number" value="<?php echo $this->job_number; ?>">
            </th>
        </tr>
        <tr>
          <th class="">#</th>
          <th class="">Code</th>
          <th class="">Material</th>
          <th class="">Satuan</th>
          <th class="">Jumlah</th>
          <th class="">Keterangan</th>
          <th class="">Delete</th>
        </tr>
      </thead>
      <tbody id="material-list"></tbody>
    </table>
    <input type="hidden" id="total-input" name="total_input" value="">
    <button type="submit" id="save-button">SIMPAN</button>
    </form>
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

// Autocomple material
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