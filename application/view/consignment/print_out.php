<!DOCTYPE html>
<html >
<head>
<meta charset="UTF-8">
<title>Print Struk Kasir Nomer <?php echo $this->product[0]->transaction_number; ?> </title>  
<style type="text/css" media="all">
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

body {
  font-size: 0.8em;
  font: Georgia, "Times New Roman", Times, serif;
  margin: 0 auto;
}
#invoice-POS {
  margin: 0 auto;
  width: 100%;
  background: #FFF;
}
#invoice-POS ::selection {
  background: #f31544;
  color: #FFF;
}
#invoice-POS ::moz-selection {
  background: #f31544;
  color: #FFF;
}
#invoice-POS h1 {
  font-size: 1.5em;
  color: #222;
  font-weight: bold;
}
#invoice-POS h2 {
  font-size: 1em;
  color: #222;
  font-weight: bold;
}
#invoice-POS #top{
  /* Targets all id with 'col-' */
  border-bottom: 1px dashed #000;
  padding-bottom: 5px;
}
#invoice-POS #mid {
  padding: 5pt 0 ;
  border-bottom: 1px dashed #000;
}
#invoice-POS #mid {
  text-align: center;
}
#invoice-POS #company-name {
  display: block;
  margin-left: 0;
}
#invoice-POS table {
  width: 100%;
  margin: 5px 0;
}
table td.padding-right {
  padding-right: 3px;
}
table td.padding-top {
  padding-top: 3px;
}
table td.padding-bottom {
  padding-bottom: 3px;
}
#total-sales tr td:last-child{
    width:1%;
    white-space:nowrap;
}
#invoice-POS #legalcopy {
  text-align: center;
}
#invoice-POS .text-right {
  text-align: right;
}
#total-sales {
  border-top: 1px dashed #000;
  border-bottom: 1px dashed #000;
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
  
  <div id="invoice-POS">
    
    <center id="top">
      <?php /*
      <img src="<?php echo Config::get('URL'); ?>file/company/logo-small.jpg" class="logo">
      */ ?>
      <div id="company-name"> 
        <h1><?php echo $company['nama perusahaan']; ?></h1>
        <h2>Makan-Makan Kumpul-Kumpul</h2>
      </div><!--End Info-->
    </center><!--End InvoiceTop-->
    
    <div id="mid">
        <p class="company-info"> 
            <?php echo $company['alamat']; ?></br>
            <?php echo $company['telepon'] . ' / ' . $company['website']; ?>
        </p>
        <p class="company-info">
          <?php echo $this->product[0]->transaction_number . ' - Jam: ' . date("H:i", strtotime($this->product[0]->created_timestamp)); ?>

        <p>
    </div><!--End Invoice Mid-->
    
    <div id="sales-item">
            <table>
              <tr>
              <td>item</td>
              <td class="text-right padding-right">jumlah</td>
              <td class="text-right padding-right">harga</td>
              <td class="text-right">sub</td>
            </tr>
              

  <?php
  $no = 1;
  $total_penjualan = 0;
  foreach($this->product as $key => $value) {
  echo "<tr>";
  echo '<td>' . $no . '. ' . $value->material_name . '</td>';
  echo '<td class="text-right padding-right">' . number_format($value->quantity, 0) . '</td>';
  echo '<td class="text-right padding-right">' . number_format($value->selling_price) . '</td>';
  $sub_total_penjualan = $value->selling_price * $value->quantity;      
  echo '<td class="text-right">' . number_format($sub_total_penjualan,0) . '</td>';
  echo "</tr>";
  $no++;
  $total_penjualan = $total_penjualan + $sub_total_penjualan;
  }
  ?>
  </table>
<table id="total-sales">
  <tr>
    <td class="text-right padding-right padding-top">Total Pembelian: </td>
    <td class="text-right padding-top"><?php echo number_format($total_penjualan,0); ?></td>
  </tr>

  <tr>
    <td class="text-right padding-right">Total Discount: </td>
    <td class="payment text-right">
      <?php 
        echo number_format($value->discount_total,0);
      ?>
    </td>
  </tr>

  <tr>
    <td class="text-right padding-right">Total Tagihan: </td>
    <td class="payment text-right">
      <?php 
        echo number_format($value->price_net,0);
      ?>
    </td>
  </tr>
</table>
</div><!--End InvoiceBot-->

<div id="legalcopy">
  <p class="legal">Thanks you for your business</p>
</div>

        
</div><!--End Invoice-->
  
  
</body>
</html>