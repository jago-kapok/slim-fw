<!DOCTYPE html>
<html >
<head>
  <meta charset="UTF-8">
  <title>Print Struk Kasir Nomer <?php echo $this->product[0]->so_number; ?> </title>
  
  <style type="text/css" media="all">
    #invoice-POS {
  box-shadow: 0 0 1in -0.25in rgba(0, 0, 0, 0.5);
  padding: 2mm;
  margin: 0 auto;
  width: 44mm;
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
}
#invoice-POS h2 {
  font-size: .9em;
}
#invoice-POS h3 {
  font-size: 1.2em;
  font-weight: 300;
  line-height: 2em;
}
#invoice-POS p {
  font-size: .7em;
  color: #666;
  line-height: 1.2em;
}
#invoice-POS #top, #invoice-POS #mid, #invoice-POS #bot {
  /* Targets all id with 'col-' */
  border-bottom: 1px solid #EEE;
}
#invoice-POS #top {
  min-height: 100px;
}
#invoice-POS #mid {
  text-align: center;
}
#invoice-POS #bot {
  min-height: 50px;
}
#invoice-POS #top .logo {
  height: 60px;
  width: 60px;
  background: url(<?php echo Config::get('URL'); ?>file/company/logo.png) no-repeat;
  background-size: 60px 60px;
}

#invoice-POS .info {
  display: block;
  margin-left: 0;
}
#invoice-POS .title {
  float: right;
}
#invoice-POS .title p {
  text-align: right;
}
#invoice-POS table {
  width: 100%;
  border-collapse: collapse;
}
#invoice-POS .tabletitle {
  font-size: .5em;
  background: #EEE;
}
#invoice-POS .service {
  border-bottom: 1px solid #EEE;
}
#invoice-POS .item {
  width: 24mm;
}
#invoice-POS .itemtext {
  font-size: .5em;
}
#invoice-POS #legalcopy {
  margin-top: 5mm;
}

#invoice-POS .text-right {
  text-align: right;
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
      <div class="logo"></div>
      <div class="info"> 
        <h2><?php echo $company['nama perusahaan']; ?></h2>
      </div><!--End Info-->
    </center><!--End InvoiceTop-->
    
    <div id="mid">
      <div class="info">
        <p> 
            <?php echo $company['alamat']; ?></br>
            <?php echo $company['telepon'] . ' / ' . $company['website']; ?>
        </p>
        <p class="itemtext"><?php echo $this->product[0]->so_number . ' - ' . $this->product[0]->created_timestamp ; ?><p>
      </div>
    </div><!--End Invoice Mid-->
    
    <div id="bot">

          <div id="table">
            <table>
              <tr class="tabletitle">
                <td class="item"><h2>Item</h2></td>
                <td class="Hours text-right"><h2>Qty</h2></td>
                <td class="Hours text-right"><h2>Harga</h2></td>
                <td class="Rate text-right"><h2>Sub</h2></td>
              </tr>

              <?php
  $no = 1;
  $total_penjualan = 0;
  foreach($this->product as $key => $value) {
  echo "<tr class='service'>";
  echo '<td class="tableitem"><p class="itemtext">' . $no . '. ' . $value->material_name . '</td>';
  echo '<td class="tableitem"><p class="itemtext text-right">' . number_format($value->quantity, 0) . '</p></td>';
  echo '<td class="tableitem"><p class="itemtext text-right">' . number_format($value->selling_price) . '</p></td>';
  $sub_total_penjualan = $value->selling_price * $value->quantity;      
  echo '<td class="tableitem"><p class="itemtext text-right">' . number_format($sub_total_penjualan,0) . '</p></td>';
  echo "</tr>";
  $no++;
  $total_penjualan = $total_penjualan + $sub_total_penjualan;
  }
  ?>

              <tr class="tabletitle">
                <td class="Rate" colspan="2"><h2>Total</h2></td>
                <td class="payment text-right" colspan="2"><h2><?php echo number_format($total_penjualan,0); ?></h2></td>
              </tr>

              <tr class="tabletitle">
                <td class="Rate" colspan="2"><h2>Pembayaran</h2></td>
                <td class="payment text-right" colspan="2"><h2><?php echo number_format($value->payment,0); ?></h2></td>
              </tr>
              
              <tr class="tabletitle">
                <td class="Rate" colspan="2"><h2>Kembalian</h2></td>
                <td class="payment text-right" colspan="2"><h2><?php echo number_format($value->payment_return,0); ?></h2></td>
              </tr>
            </table>
          </div><!--End Table-->

          <div id="legalcopy">
            <p class="legal"><strong>Terimakasih telah berbelanja ditempat kami!</strong>  Jika ada keluhan masalah sistem, hubungi support PT.XYZ
            </p>
          </div>

        </div><!--End InvoiceBot-->
  </div><!--End Invoice-->
  
  
</body>
</html>