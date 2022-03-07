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
    html, body {
        display: block; 
        font-family: "Times New Roman", Times, serif;
        margin: 0;
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
    tr.border-dot td, tr.border-dot th {
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
    <td style="width: 50%;"><?php echo $company['nama perusahaan']; ?></td>
    <td rowspan="3" class="text-center" style="width: 25%;">
        <h3>
          BUKTI KAS KELUAR
        </h3>
    </td>
    <td rowspan="3" class="text-right" style="width: 25%;">NO: <?php echo $this->transaction[0]->transaction_code; ?></td>
  </tr>
  <tr>
     <td><?php echo $company['alamat']; ?></td>
  </tr>
  <tr>
    <td><?php echo $company['website']; ?></td>
  </tr>
</table>
<hr>
<br>
<table class="fullwidth">
  <tr>
    <td>Diterima Dari: <?php echo $this->transaction[0]->contact_name; ?></td>
    <td>Tanggal: <?php echo date("d M, y", strtotime($this->transaction[0]->payment_disbursement)); ?></td>
  </tr>
</table>
<br>
<table class="fullwidth">
  <thead>
          <tr class='border'>
            <th class="text-left">Perkiraan</th>
            <th class="text-left">Uraian</th>
            <th class="text-right">Jumlah</th>
          </tr>
          </thead>

          <tbody>
            <tr class="border">
              <td><?php echo $this->transaction[0]->transaction_type;?></td>
              <td><?php echo $this->transaction[0]->transaction_name . $this->transaction[0]->transaction_code;?></td>
              <td class="text-right"><?php echo $this->transaction[0]->debit;?></td>
          </tr>
          <tr class="border">
              <td colspan="2" class="text-right"><strong>Total: </strong></td>
              <td class="text-right"><?php echo number_format($this->transaction[0]->debit);?></td>
          </tr>
          <tr class="border">
              <td colspan="3"><strong>Terbilang:</strong> <?php echo ucwords(FormaterModel::terbilangRupiah($this->transaction[0]->debit)); ?></td>
          </tr>
    </tbody>
    </table>
  <br>
  <table class="fullwidth">
  <tr>
    <td class="text-center">Catatan,</td>
    <td class="text-center">Pembukuan,</td>
    <td class="text-center">Mengetahui,</td>
    <td class="text-center">Kasir,</td>
    <td class="text-center">Penyetor,</td>
  </tr>
  <tr>
    <td colspan="5"></td>
  </tr>
  <tr>
    <td colspan="5">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="5">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="5">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="5">&nbsp;</td>
  </tr>
  <tr>
    <td class="text-center">.............................</td>
    <td class="text-center">.............................</td>
    <td class="text-center">.............................</td>
    <td class="text-center">.............................</td>
    <td class="text-center">.............................</td>
  </tr>
</table>

</body>
</html>