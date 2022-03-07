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
  vertical-align: top;
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
  float: right;
}

.clearfix:before,
.clearfix:after {
    content: " "; /* 1 */
    display: table; /* 2 */
}

.clearfix:after {
    clear: both;
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
  <?php //echo '<pre>';var_dump($this->material_list);echo '</pre>'; ?>

<form method="post" action="<?php echo Config::get('URL') . 'JobOrder/saveMaterialConsumptionByBOM'; ?>">

  <div class="container">
    <table class="ExcelTable2007">
      <thead>
        <tr>
          <th id="production-number" colspan="8">
            Input Material Consumption Nomer: <?php echo $this->job_number; ?>
            <input type="hidden" name="job_number" value="<?php echo $this->job_number; ?>">
          </th>
        </tr>
        <tr>
          <th>#</th>
          <th>Kode</th>
          <th>Nama</th>
          <th>Stock</th>
          <th>Satuan</th>
          <th>Jumlah</th>
          <th>Keterangan</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $no = 1;
        foreach($this->material_list as $key => $value) {
              echo '<tr>';
              echo '<td class="text-right heading">' . $no . '</td>';
              echo '<td>' . $value->material_code . '</td>';
              echo '<td>' . $value->material_name . '</td>';
              echo '<td class="text-right">' . floatval($value->quantity_stock) . '</td>';
              echo '<td>' . $value->unit . '</td>';
              echo '<td class="text-right">
                <input name="consumed_quantity_' . $no . '" type="number" min="0" step="0.001" class="text-right">
                <input name="material_code_' . $no . '" type="hidden" value="' . $value->material_code . '" readonly></td>';
              echo '<td><input name="note_' . $no . '" type="text"></td>';
              echo "</tr>";
        $no++;
        }
?>
</tbody>
</table>
<input type="hidden" name="total_input" value="<?php echo ($no - 1); ?>">
<div class="clearfix">
<button type="submit" id="save-button" class="text-right"> SIMPAN</button>
</div>
</div>
</form>

<hr>

<div class="container">
  <div style="text-align: center;">
    <a href="<?php echo Config::get('URL'); ?>dashboard/" class="center">Home</a> - 
    <a href="<?php echo Config::get('URL') . 'contact/'; ?>" class="center">Kontak</a> - 
    <a href="<?php echo Config::get('URL') . 'inventory/rawMaterial/'; ?>" class="center">Stock</a>
  </div>
</div>
</body>
</html>