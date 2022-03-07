<!doctype html>

<html lang="en">
<head>
  <meta charset="utf-8">

  <title>
      <?php if (isset($this->title)) {
          echo $this->title;
      } else {
          echo Config::get('DEFAULT_TITLE');
      } ?>
  </title>


  <style type="text/css">

     @media all {

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

/* END CSS RESET */



    html, body {
        display: block; 
        font-family: "Times New Roman", Times, serif;
        margin: 0;
        font-size: 15px;
    }
    h1 {
      font-size: 21px;
      font-weight: bolder;
    }
    table {
      width: 1000px;
    }
    th, td {
      padding: 5px 13px 5px 13px;
    }
    tr.border th, tr.border td {
      border-top:1pt solid black;
      border-bottom:1pt solid black;
    }
    tr.border-top th, tr.border-top td {
      border-top:1pt solid black;
    }
    tr.border-bottom th,  tr.border-bottom td{
      border-bottom:1pt solid black;
    }
    tr.border-bottom-dot td {
      border-bottom:1pt dotted black;
    }
    .text-right {
      text-align: right;
    }
    .text-center {
      text-align: center;
    }
    .text-left {
      text-align: left;
    }
    .text-underline {
      border-bottom: 1px dotted #000;
    }

}
@media screen {
    [contenteditable="true"] {
      background-color: #b2ffdb;
    }
}
</style>
</head>
<?php 
//Olah data company jadi key dan value dalam array
//echo '<pre>'; var_dump($this->company); echo '</pre>'; 
$company = [];
foreach ($this->company as $key => $value) {
  $company[] = array($value->item_name => $value->value);
}
$company = call_user_func_array('array_merge', $company);
//echo '<pre>'; var_dump($company); echo '</pre>'; 
?>
<body>

<table class="fullwidth">
  <tr>
    <td class="text-center" colspan="2">
        <img src="<?php echo Config::get('URL'); ?>file/company/header_company_letter-2.jpg" style="width: 1000px;">
    </td>
  </tr>
  <tr>
    <td style="width: 700px;" contenteditable="true">
      To : <?php echo $this->product[0]->contact_name; ?><br>
      Address : <?php
        //alamat
        if (!empty($this->product[0]->address_street)) {
          echo $this->product[0]->address_street;
        }
        if (!empty($this->product[0]->address_city)) {
          echo ', ' . $this->product[0]->address_city;
        }
        if (!empty($this->product[0]->address_state)) {
          echo '. ' . $this->product[0]->address_state;
        }
        if (!empty($this->product[0]->address_zip)) {
          echo '. ' . $this->product[0]->address_zip;
        } ?><br>
      Attn : <br>
      Phone/Fax : <?php echo  $this->product[0]->phone . '/' . $this->product[0]->fax  . '<br>'; ?>
      Email : <?php echo  $this->product[0]->phone . '<br>'; ?>
    </td>
    <td contenteditable="true">
      PO Reverence: <?php echo $this->product[0]->transaction_number; ?>
      <br>
      PO Date: <?php echo date("d/m/Y", strtotime($this->product[0]->created_timestamp)); ?>
      <br>
      Forwarder: &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; 
      <br>
      Nopol: &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; 
      <br>
      Shipment: &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; 
      <br>
      Date: &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; 
      <br>
    </td>
  </tr>
</table>

<br>
<table class="fullwidth">
  <thead>
  <tr>
  <th class="text-center" colspan="5">
      <span style="font-size: 21px; text-decoration: underline;">DELIVERY ORDER</span>
      <br>
      (<?php echo $this->product[0]->do_number; ?>)</span>
  </th>     
  </tr>
  <tr class="border-bottom">
  <th colspan="5">
  </th>     
  </tr>
  <tr class='border-bottom'>
  <th class="text-left">No</th>
  <th class="text-left">Nama Barang</th>
  <th class="text-right">Jumlah</th>
  <th class="text-right">Satuan</th>
  <th class="text-right">Serial Number</th>      
  </tr>
  </thead>

  <tbody>
  <?php
  $no = 1;
  $total_penjualan = 0;
  $total_item_sold = 0;
  $total_item_delivered = 0;
  foreach($this->product as $key => $value) {
  echo "<tr class='border-bottom-dot'>";
  echo '<td class="text-left">' . $no . '</a></td>';
  echo '<td>' . $value->material_name . '</td>';
  echo '<td class="text-right">' . number_format($value->quantity_delivered, 0) . '</td>';
  echo '<td class="text-right">' . $value->unit . '</td>';
  echo '<td class="text-right">' . $value->serial_number . '</td>';
  echo "</tr>";
  $no++;
  }
  ?>
  </tbody>
</table>
  
  <br>
  <br>

  <table class="fullwidth">
  <tr>
    <td class="text-center">PT. Maxima Daya Indonesia,</td>
    <td class="text-center">Penerima,</td>
  </tr>
  <tr>
    <td colspan="3" style="height: 100px;"></td>
  </tr>
  <tr>
    <td class="text-center" contenteditable="true">&nbsp; &nbsp; &nbsp; _ _ _ _ _ _ _ &nbsp; &nbsp; &nbsp; </td>
    <td class="text-center" contenteditable="true">&nbsp; &nbsp; &nbsp; _ _ _ _ _ _ _ &nbsp; &nbsp; &nbsp; </td>
  </tr>
</table>

<footer>
  <hr style="border: 1px single;">
  <p class="text-center">No.Dokumen : FM-02-007-PP, Rev 02, Tanggal 19 Februari 2019</p>
</footer>
</body>
</html>