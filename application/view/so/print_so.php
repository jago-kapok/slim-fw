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
    <td class="text-center" colspan="3">
        <img src="<?php echo Config::get('URL'); ?>file/company/header_company_letter-2.jpg" style="width: 1000px;">
    </td>
  </tr>
  <tr>
    <td class="text-center" colspan="3">
        <span style="font-size: 21px; text-decoration: underline;">SALES ORDER</span>
    </td>
  </tr>
  <tr>
    <td colspan="3">
        &nbsp;
    </td>
  </tr>
  <tr>
    <td class="text-left" style="width: 30%;" contenteditable="true">
      <?php
      echo 'SO Number: ' . $this->product[0]->transaction_number . '<br>
      SO Date: ' . date('d-F, Y', strtotime($this->product[0]->created_timestamp)) . '<br>
      RFQ Refer No: <br>
      Sales Officer: ' . $this->product[0]->full_name . '<br>
      Mobile Phone: ' . $this->product[0]->phone . '<br>
      Email: '. $this->product[0]->email; ?>
    </td>
    <td class="text-left" style="width: 20%;">
      &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
    </td>
    <td class="text-right" style="width: 50%;">
      <?php
        echo $this->product[0]->contact_name . '<br>';
        echo $this->product[0]->address_street . ', ' . $this->product[0]->address_city . ', ' . $this->product[0]->address_state . '<br>';
        if (!empty($this->product[0]->customer_phone)) { echo $this->product[0]->customer_phone . '<br>';}
        if (!empty($this->product[0]->fax)) { echo $this->product[0]->fax . '<br>';}
        if (!empty($this->product[0]->customer_email)) { echo $this->product[0]->customer_email . '<br>';}
        if (!empty($this->product[0]->website)) { echo $this->product[0]->website . '<br>';}
      ?> 
    </td>
  </tr>
</table>

<table class="fullwidth">
  <thead>
    <tr class="border-bottom">
      <th colspan="9" class="border-bottom">&nbsp;</th>
    </tr>
    <tr class="border-bottom">
      <th rowspan="2" class="center">#</th>
      <th rowspan="2" class="center">Nama</th>
      <th rowspan="2" class="text-right">Jumlah</th>
      <th rowspan="2" class="text-right">Harga</th>
      <th rowspan="2" class="text-right">PPN</th>
      <th rowspan="2" class="text-right">PPh</th>
      <th rowspan="2" class="text-right">Total</th>
    </tr>
  </thead>

  <tbody>
            <?php
            $no = 1;
            $total = 0;
            foreach($this->product as $key => $value) {
      
                    echo '<tr class="border-bottom-dot">';
                    echo '<td class="text-right">' . $no . '</td>';
                    echo '<td>' . $value->material_name . '</td>';
                    echo '<td class="text-right">' . number_format($value->quantity,0) . '</td>';
                    echo '<td class="text-right">' . number_format($value->selling_price,0) . '</td>';
                    echo '<td class="text-right">' . $value->tax_ppn . '</td>';
                    echo '<td class="text-right">' . $value->tax_pph . '</td>';
                    $subtotal_price = $value->quantity * $value->selling_price;
                    $subtotal_tax_ppn = ($value->tax_ppn/100) * $subtotal_price;
                    $subtotal_tax_pph = ($value->tax_pph/100) * $subtotal_price;
                    $subtotal_all = $subtotal_price + $subtotal_tax_ppn + $subtotal_tax_pph;
                    $subtotal_all = (int)$subtotal_all;
                    echo '<td class="text-right">' . number_format($subtotal_all,0) . '</td>';
                    echo "</tr>";
                
                $no++;
                $total = $total + $subtotal_all; 
            }
            $total = (int)$total;
            ?>
                <tr>
                  <td class="text-right" colspan="6"><strong>TOTAL</strong></td>
                  <td style="text-align: right;"><?php echo number_format($total,0); ?></td>
                </tr>
                <tr class="border-top">
                  <td colspan="8" class="border-bottom">&nbsp;</td>
                </tr>
              </tbody>
</table>
  
<p>
  <pre>
    <?php echo $this->product[0]->note; ?>
  </pre>
</p>

<table class="fullwidth">
  <thead>
    <tr class="border-bottom">
      <th colspan="5" class="border-bottom">&nbsp;</th>
    </tr>
    <tr class="border-bottom">
      <th>No</th>
      <th>Lampiran SO</th>
      <th>Keterangan</th>
      <th>Tanggal</th>
      <th>Edisi</th>
    </tr>
  </thead>
  <tbody>
  <tr class="border-bottom">
    <td>1</td>
    <td contenteditable="true">PO Customer</td>
    <td contenteditable="true">Ada/Tidak</td>
    <td contenteditable="true"></td>
    <td contenteditable="true"></td>
  </tr>
  <tr class="border-bottom">
    <td>2</td>
    <td contenteditable="true">Approval Customer</td>
    <td contenteditable="true">Ada/Tidak</td>
    <td contenteditable="true"></td>
    <td contenteditable="true"></td>
  </tr>
  <tr class="border-bottom">
    <td>3</td>
    <td contenteditable="true">Arsip Penawaran</td>
    <td contenteditable="true">Ada/Tidak</td>
    <td contenteditable="true"></td>
    <td contenteditable="true"></td>
  </tr>
  <tr>
      <td colspan="5">&nbsp;</td>
  </tr>
  </tbody>
</table>

<table class="fullwidth">
  <tr>
    <td class="text-center">Customer,</td>
    <td class="text-center"><?php echo $company['nama perusahaan']; ?>,</td>
  </tr>
  <tr>
    <td colspan="2">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2">&nbsp;</td>
  </tr>
  <tr>
    <td class="text-center">&nbsp; &nbsp; &nbsp; _ _ _ _ _ _ _ &nbsp; &nbsp; &nbsp; </td>
    <td class="text-center" contenteditable="true"><?php echo $this->product[0]->full_name; ?></td>
  </tr>
</table>
<hr style="border: 1px single;">
<p class="text-center"><strong>FM-01-002-PP-Rev. 03 Tanggal 18 Februari 2019</strong></p>
</body>
</html>