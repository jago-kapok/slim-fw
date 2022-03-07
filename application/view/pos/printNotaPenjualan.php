<!doctype html>

<html lang="en">
<head>
  <meta charset="utf-8">

  <title>Print Sales Order <?php echo $this->product[0]->so_number; ?> </title>


  <style type="text/css">
     @media all {
    html, body {
        display: block; 
        font-family: "Times New Roman", Times, serif;
        margin: 0;
    }

    @page {
      size: 21.59cm 13.97cm;
    }
    table.fullwidth {
      width: 100%;
    }
    tr.border th {
      border-top:1pt solid black;
      border-bottom:1pt solid black;
    }
    tr.border td, tr.border th {
      border-bottom:1pt solid black;
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
<page size="A4">
<table class="fullwidth">
  <tr>
    <td><?php echo $company['nama perusahaan']; ?></td>
    <td class="text-right"><?php echo $this->product[0]->contact_name; ?></td>
  </tr>
  <tr>
     <td><?php echo $company['alamat']; ?></td>
    <td class="text-right"><?php echo $this->product[0]->address_street . ', ' . $this->product[0]->address_city . ', ' . $this->product[0]->address_state; ?></td>
  </tr>
  <tr>
    <td><?php echo 'Telepon: ' . $company['telepon'] . ' / Email:' . $company['email'] . ' / Website:' . $company['website']; ?></td>
    <td class="text-right"><?php echo $this->product[0]->phone; ?></td>
  </tr>
</table>
<br>

<table class="fullwidth">
  <thead>
  <tr class='border'>
  <th class="text-left">No</th>
  <th class="text-left">Nama Barang</th>
  <th class="text-right">Jumlah</th>
  <th class="text-right">Harga</th>
  <th class="text-right">Sub Total</th>      
  </tr>
  </thead>

  <tbody>
  <?php
  $no = 1;
  $total_penjualan = 0;
  $total_item_sold = 0;
  $total_item_delivered = 0;
  foreach($this->product as $key => $value) {
  echo "<tr class='border'>";
  echo '<td class="text-left">' . $no . '</a></td>';
  echo '<td>' . $value->material_name . ' (' . $value->material_code .')</td>';
  echo '<td class="text-right">' . number_format($value->quantity, 0) . '</td>';
  echo '<td class="text-right">' . number_format($value->selling_price) . '</td>';
  $sub_total_penjualan = $value->selling_price * $value->quantity;      
  echo '<td class="text-right">' . number_format($sub_total_penjualan,2) . '</td>';
  echo "</tr>";
  $no++;
  $total_penjualan = $total_penjualan + $sub_total_penjualan;
  $total_item_sold = $total_item_sold + $value->quantity;
  $total_item_delivered = $total_item_delivered + $value->quantity_delivered;
  }
  ?>
  <tr class='border'>
  <td class="text-center" colspan="2">Total</td>
  <td class="text-right"><?php echo number_format($total_item_sold); ?></td>
  <td class="text-right"></td>
  <td class="text-right"><?php echo number_format($total_penjualan,2); ?></td>
  </tr>
  </tbody>
</table>
  
  <br>

  <table class="fullwidth">
  <tr>
    <td style="width: 85pt;">Nomer Transaksi</td>
    <td style="width: 110pt;">: <span class="text-underline"><?php echo $this->product[0]->so_number; ?></span></td>
    <td class="text-center">Pelanggan,</td>
    <td class="text-center">Hormat Kami,</td>
  </tr>
  <tr>
    <td style="width: 85pt;">Tanggal</td>
    <td style="width: 110pt;">: <span class="text-underline"><?php echo date("d/m/Y", strtotime($this->product[0]->created_timestamp)); ?></span></td>
    <td colspan="2"></td>
  </tr>
  <tr>
    <td colspan="4">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="4">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="4">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="4">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2"></td>
    <td class="text-center">..........................................</td>
    <td class="text-center">..........................................</td>
  </tr>
</table>
</page>
</body>
</html>