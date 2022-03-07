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
  padding: 8px;
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

<form method="post" action="<?php echo Config::get('URL') . 'JobOrder/saveInsertBOMConsumption/?job_number=' . urlencode($this->job_number); ?>">

  <div class="container">
    <table class="ExcelTable2007">
      <thead>
        <tr>
          <th id="production-number" colspan="8">Input Material Consumption Nomer: <?php echo $this->job_number; ?></th>
        </tr>
        <tr>
          <th class="" rowspan="2">#</th>
          <th class="" rowspan="2">Kode</th>
          <th class="" rowspan="2">Nama</th>
          <th class="" colspan="4">Stock</th>
          <th class="" rowspan="2">Keluarkan</th>
        </tr>
        <tr>
          <th class="">#Kedatangan</th>
          <th class="">#Lot</th>
          <th class="">Qty</th>
          <th class="">Satuan</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $no = 1;
        $input_counter = 1;
        foreach($this->material_list as $key => $value) {
          $total_lot_number = explode('-, -', $value->transaction_number);
          $total_quantity = explode('-, -', $value->quantity_stock);
          $total_created_timestamp = explode('-, -', $value->created_timestamp);
          $total_uid = explode('-, -', $value->uid);
          $row = count($total_lot_number);
          if ($row > 1) {
            for ($i=1; $i <= $row; $i++) {
              $x = $i - 1;
              if ($i === 1) {
                echo '<tr>';
                echo '<td class="text-right heading" rowspan="' . $row . '">' . $no . '</td>';
                echo '<td  rowspan="' . $row . '">' . $value->material_code . '</td>';
                echo '<td  rowspan="' . $row . '">' . $value->material_name . '</td>';
                echo '<td>' . date('d m, y', strtotime($total_created_timestamp[$x])) . '</td>';
                echo '<td>' . $total_lot_number[$x] . '</td>';
                echo '<td class="text-right">' . number_format($total_quantity[$x]) . '</td>';
                echo '<td>' . $value->unit . '</td>';
                echo '<td>
                <input name="consumed_quantity_' . number_format($input_counter) . '" type="number" min="0" step="0.001">
                <input name="uid_' . number_format($input_counter) . '" type="hidden" value="' . $total_uid[$x] . '">
                <input name="stock_' . number_format($input_counter) . '" type="hidden" value="' . $total_quantity[$x] . '">
                <input name="material_code_' . number_format($input_counter) . '" type="hidden" value="' . $value->material_code . '">
                <input name="material_lot_number_' . number_format($input_counter) . '" type="hidden" value="' . $total_lot_number[$x] . '">
                </td>';
                echo "</tr>";
                $input_counter++;
              } else {
                echo '<tr>';
                echo '<td>' . date('d m, y', strtotime($total_created_timestamp[$x])) . '</td>';
                echo '<td>' . $total_lot_number[$x] . '</td>';
                echo '<td class="text-right">' . number_format($total_quantity[$x]) . '</td>';
                echo '<td>' . $value->unit . '</td>';   
                echo '<td>
                <input name="consumed_quantity_' . number_format($input_counter) . '" type="number" min="0" step="0.001">
                <input name="uid_' . number_format($input_counter) . '" type="hidden" value="' . $total_uid[$x] . '">
                <input name="stock_' . number_format($input_counter) . '" type="hidden" value="' . $total_quantity[$x] . '">
                <input name="material_code_' . number_format($input_counter) . '" type="hidden" value="' . $value->material_code . '">
                <input name="material_lot_number_' . number_format($input_counter) . '" type="hidden" value="' . $total_lot_number[$x] . '">
                </td>';
                echo "</tr>";
                $input_counter++;
              }

            } //end for

          } else {
              echo '<tr class="' . 'row-' . $no . '">';
              echo '<td class="text-right heading" rowspan="' . $row . '">' . $no . '</td>';
              echo '<td  rowspan="' . $row . '">' . $value->material_code . '</td>';
              echo '<td  rowspan="' . $row . '">' . $value->material_name . '</td>';
              echo '<td>' . date('d M, y', strtotime($value->created_timestamp)) . '</td>';
              echo '<td>' . $value->transaction_number . '</td>';
              echo '<td class="text-right">' . number_format($value->quantity_stock) . '</td>';
              echo '<td>' . $value->unit . '</td>';
              echo '<td>
              <input name="consumed_quantity_' . number_format($input_counter) . '" type="number" min="0" step="0.001">
              <input name="uid_' . number_format($input_counter) . '" type="hidden" value="' . $value->uid . '">
              <input name="stock_' . number_format($input_counter) . '" type="hidden" value="' . $value->quantity_stock . '">
              <input name="material_code_' . number_format($input_counter) . '" type="hidden" value="' . $value->material_code . '">
              <input name="material_lot_number_' . number_format($input_counter) . '" type="hidden" value="' . $value->transaction_number . '">
              </td>';
              echo "</tr>";
              $input_counter++;
          } //end if

        $no++;
        }
?>
</tbody>
</table>
<input type="hidden" name="total_input" value="<?php echo $input_counter; ?>">
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