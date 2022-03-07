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
    <td style="width: 35%;"><?php echo $company['nama perusahaan']; ?></td>
    <td rowspan="3" class="text-center" style="width: 30%;">
        <h3>
          <?php if ($this->employee->grade > 100) { echo 'SLIP GAJI'; } else { echo 'Laporan Kehadiran';} ?>
        </h3>
        Period: <?php echo date('M ', strtotime($this->report_month)); ?>
    </td>
    <td class="text-right" style="width: 35%;"><?php echo $this->employee->full_name; ?></td>
  </tr>
  <tr>
     <td><?php echo $company['alamat']; ?></td>
    <td class="text-right">Jabatan: <?php echo $this->employee->department; ?></td>
  </tr>
  <tr>
    <td><?php echo 'Telepon: ' . $company['telepon'] . ' / Email:' . $company['email'] . ' / Website:' . $company['website']; ?></td>
    <td class="text-right">ID Pegawai: <?php echo $this->employee->uid; ?></td>
  </tr>
</table>
<br>

<table class="fullwidth">
  <thead>
          <tr class='border'>
            <th class="text-center">No</th>
            <th class="text-left">Tanggal</th>
            <th class="text-left">Jam Masuk</th>
            <th class="text-left">Jam Keluar</th>
            <?php if ($this->employee->grade > 100) { ?>
              <th class="text-right">Uang Transport</th>
              <th class="text-right">Denda Telat</th>
            <?php } ?>
          </tr>
          </thead>

          <tbody>
          <?php
          $no = 1;
          $total_uang_transport = 0;
          $total_denda = 0;
          foreach($this->attendance as $key => $value) {
          //check telat apa tidak
          $extracted_time = date('H:i:s', strtotime($value->jam_datang));
          if (strtotime($extracted_time) > strtotime('09:00:00')) {
            $fine = $this->attendance_late_fine->value; //kena denda telat
          } else {
            $fine = 0; //tidak kena denda cui
          }
          echo "<tr class='border-dot'>";
          echo '<td class="text-center">' . $no . '</td>';
          echo '<td>' . date('d M', strtotime($value->by_date)) . '</td>';
          echo '<td>' . date('H:i', strtotime($value->jam_datang)) . '</td>';
          echo '<td>' . date('H:i', strtotime($value->jam_pulang)) . '</td>';
          if ($this->employee->grade > 100) {
            echo '<td class="text-right">' . number_format($this->uang_transport->value)  . '</td>';
            echo '<td class="text-right">' . number_format($fine) . '</td>';
          }
          echo "</tr>";
          $no++;
          $total_uang_transport = $total_uang_transport + $this->uang_transport->value;
          $total_denda = $total_denda + $fine;
          }
          ?>
          <?php if ($this->employee->grade > 100) { ?>
            <tr class="border">
                <td colspan="4" class="text-center">Total</td>
                <td class="text-right"><?php echo number_format($total_uang_transport); ?></td>
                <td class="text-right"><?php echo number_format($total_denda); ?></td>
            </tr>
            <tr><td colspan="6">&nbsp;</td></tr>          
            <tr class="border-dot">
                <td colspan="5">Gaji Pokok</td>
                <td class="text-right"><?php echo number_format($this->employee->salary); ?></td>
            </tr>
          <?php if ($total_uang_transport > 0) { ?>
            <tr class="border-dot">
              <td colspan="5">Total Uang Transport</td>
              <td class="text-right"><?php echo number_format($total_uang_transport); ?></td>
            </tr>
          <?php } ?>

          <?php if ($total_denda > 0) { ?>
            <tr class="border-dot">
              <td colspan="5">Total Denda Keterlambatan</td>
              <td class="text-right">-<?php echo number_format($total_denda); ?></td>
          </tr>
          <?php } ?>
          
          
          <?php
          $take_home_pay = $this->employee->salary + $total_uang_transport - $total_denda;
          foreach($this->salary_benefit as $key => $value) {
              if ($value->benefit_value <= 0) {
                $status = 'danger'; //kena denda telat
              } else {
                $status = 'success'; //kena denda telat
              }
              echo "<tr class='border-dot'>";
              echo '<td colspan="5">' . $value->benefit_name . '</td>';
              echo '<td class="text-right">' . number_format($value->benefit_value) . '</td>';
              echo "</tr>";
              $take_home_pay = $take_home_pay + $value->benefit_value;
          }
          ?>
          <tr class="border">
              <td colspan="5"><strong>Take Home Pay</strong></td>
              <td class="text-right"><?php echo number_format($take_home_pay);?></td>
          </tr>
          <?php } ?>
    </tbody>
    </table>
  <br>
  <table class="fullwidth">
  <tr>
    <td class="text-center">Pegawai,</td>
    <td class="text-center">Hormat Kami,</td>
  </tr>
  <tr>
    <td colspan="2"></td>
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
    <td class="text-center">..........................................</td>
    <td class="text-center">..........................................</td>
  </tr>
</table>
</body>
</html>