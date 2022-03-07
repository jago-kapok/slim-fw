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
        font-family: Calibri, Georgia, "Times New Roman", Times, serif;
        margin: 0;
        font-size: 18px;
        font-weight: normal;
        line-height: 1.7;
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
    .content {
      min-height: calc(100vh - 70px);
    }
    .footer {
      height: 50px;
    }
    /* Checkbox style */
    label {
      display: inline;
    }

    .regular-checkbox {
      display: none;
    }

    .regular-checkbox + label {
      background-color: #fff;
      border: 2px solid #000;
      padding: 9px;
      border-radius: 3px;
      display: inline-block;
      position: relative;
    }

    .regular-checkbox + label:active, .regular-checkbox:checked + label:active {
    }

    .regular-checkbox:checked + label {
      background-color: #e9ecee;
      border: 1px solid #adb8c0;
      color: #99a1a7;
    }

    .regular-checkbox:checked + label:after {
      content: '\2714';
      position: absolute;
      top: 0px;
      left: 3px;
      color: #99a1a7;
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
<div class="content">
    <table class="fullwidth">
    <tr>
      <td class="text-center" colspan="2">
          <img src="<?php echo Config::get('URL'); ?>file/company/header_company_letter-2.jpg" style="width: 1000px;">
      </td>
    </tr>
    <tr>
      <td style="width: 700px;" contenteditable="true">
        <span style="font-size: 21px;">Kepada Yth.</span><br>
          <?php echo $this->product[0]->contact_name; ?><br>
          <?php
          //alamat
          if (!empty($this->product[0]->address_street)) {
            echo $this->product[0]->address_street . ', ';
          }
          if (!empty($this->product[0]->address_city)) {
            echo $this->product[0]->address_city . '. <br>';
          }
          if (!empty($this->product[0]->address_state)) {
            echo $this->product[0]->address_state . ', ';
          }
          if (!empty($this->product[0]->address_zip)) {
            echo $this->product[0]->address_zip . '. <br>';
          }

          

          //kontak
          if (!empty($this->product[0]->phone)) {
            echo 'Telpon: ' . $this->product[0]->phone . '<br>';
          }
          if (!empty($this->product[0]->fax)) {
            echo 'Fax: ' .  $this->product[0]->fax . '<br>';
          }
          if (!empty($this->product[0]->email)) {
            echo 'Email: ' .  $this->product[0]->email . '<br>';
          }
          if (!empty($this->product[0]->website)) {
            echo 'Website: ' .  $this->product[0]->website . '<br>';
          }
          echo 'Attn.: ';
          ?>
      </td>
      <td>
        <span style="font-size: 21px;">&nbsp;</span><br>
        PO Reference : <span class="text-underline" contenteditable="true"><?php echo $this->product[0]->transaction_number; ?></span>
        <br>
        PO Date : <span class="text-underline" contenteditable="true"><?php echo date("d/m/Y", strtotime($this->product[0]->created_timestamp)); ?></span>
        <br>
        Kendaraan : <span class="text-underline" contenteditable="true">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; </span>
        <br>
        Nomor Polisi : <span class="text-underline" contenteditable="true">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; </span>
        <br>
      </td>
    </tr>
  </table>

  <br>
  <table class="fullwidth">
    <thead>
    <tr>
    <th class="text-center" colspan="6">
        <span style="font-size: 21px; text-decoration: underline;">SURAT JALAN</span>
        <br>
        (<?php echo $this->product[0]->do_number; ?>)</span>
    </th>     
    </tr>
    <tr class="border-bottom">
      <th colspan="6"></th>     
    </tr>
    <tr class='border-bottom'>
    <th class="text-left">No</th>
    <th class="text-left">Nama Barang</th>
    <th class="text-right">Jumlah</th>
    <th class="text-left">Satuan</th>
    <th class="text-left" style="width: 35%;">Serial Number</th>
    <th class="text-left">Keterangan</th>
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
    echo '<td class="text-left">' . $value->unit . '</td>';
    echo '<td class="text-left" style="width: 35%;">' . $value->serial_number . '</td>';
    echo '<td class="text-left" contenteditable="true"></td>';
    echo "</tr>";
    $no++;
    }
    ?>
    </tbody>
  </table>
    <table class="fullwidth">
    <tr>
      <td class="text-left">
        Checklist kelengkapan Trafo:
      </td>
      <td class="text-right" colspan="2"><input type="checkbox" id="checkbox-1" class="regular-checkbox" /><label for="checkbox-1"></label> Roda Trafo 4 PCS / Unit &nbsp; &nbsp; &nbsp; <input type="checkbox" id="checkbox-2" class="regular-checkbox" /><label for="checkbox-2"></label> DGPT / RIS &nbsp; &nbsp; &nbsp; <input type="checkbox" id="checkbox-3" class="regular-checkbox" /><label for="checkbox-3"></label> Elastimold
      </td>
    </tr>
    <tr>
      <td colspan="3" style="height: 13px;"></td>
    </tr>
    <tr>
      <td class="text-center">
          PT. Maxima Daya Indonesia,<br>
          <span contenteditable="true"><?php echo date('d-M, Y'); ?></span>
      </td>
      <td class="text-center">Ekspedisi,</td>
      <td class="text-center">Penerima,</td>
    </tr>
    <tr>
      <td colspan="3" style="height: 100px;"></td>
    </tr>
    <tr>
      <td class="text-center" contenteditable="true">&nbsp; &nbsp; &nbsp; _ _ _ _ _ _ _ &nbsp; &nbsp; &nbsp; </td>
      <td class="text-center" contenteditable="true">&nbsp; &nbsp; &nbsp; _ _ _ _ _ _ _ &nbsp; &nbsp; &nbsp; </td>
      <td class="text-center" contenteditable="true">&nbsp; &nbsp; &nbsp; _ _ _ _ _ _ _ &nbsp; &nbsp; &nbsp; </td>
    </tr>
  </table>
</div>
<footer>
  <p class="text-center">No.Dokumen : FM-01-008-PP, Revisi : 02, Tanggal : 13 Februari 2019, <span contenteditable="true">Halaman: </span></p>
  <hr style="border: 1px single;">
  <p class="text-center">Untuk konfirmasi penerimaan barang silahkan hub. Kami di 082 132 606 868 / 0321 6850007</p>
</footer>
</body>
</html>