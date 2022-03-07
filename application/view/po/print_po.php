<?php //Debuger::jam($this->product); ?>

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
    table.fullwidth,
    .fullwidth
     {
      width: 1000px;
    }
    th, td {
      padding: 5px 13px 0 0;
    }
    tr.border th {
      border-top:1pt solid black;
      border-bottom:1pt solid black;
    }
    tr.border-top th, tr.border-top td {
      border-top:1pt solid black;
    }
    tr.border-bottom th {
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
        dl.inline-flex {
      display: flex;
      flex-flow: row;
      flex-wrap: wrap;
      width: 100%;      /* set the container width*/
      overflow: visible;
    }
    dl.inline-flex dt {
      flex: 0 0 30%;
      text-align: left;
      text-overflow: ellipsis;
      overflow: hidden;
    }
    dl.inline-flex dd {
      flex:0 0 70%;
      margin-left: auto;
      text-align: left;
      text-overflow: ellipsis;
      overflow: hidden;
    }
    .float-right {
      float: right;
    }

}
@media screen {
    [contenteditable="true"] {
      background-color: #b2ffdb;
      border-bottom: dotted 1px #aaa;
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
//echo '<pre>'; var_dump($this->product); echo '</pre>'; 
?>
<body>

<table class="fullwidth">
  <tr>
    <td class="text-center" colspan="3">
        <img src="<?php echo Config::get('URL'); ?>file/company/header_company_letter-2.jpg" style="width: 1000px;">
    </td>
  </tr>
  <tr>
    <td style="width: 45%;">
      <?php
      echo '<dl class="inline-flex">
        <dt>PO Number <span class="text-right float-right">:</span></dt>
        <dd>' . $this->product[0]->transaction_number . '</dd>
        <dt>PO Date <span class="text-right float-right">:</span></dt>
        <dd>' . date('d-F, Y', strtotime($this->product[0]->approved_date)) . '</dd>
        <dt>Purchaser <span class="text-right float-right">:</span></dt>
        <dd>' . $company['nama perusahaan'] . '</dd>
        <dt>Name <span class="text-right float-right">:</span></dt>
        <dd>' . ucwords($this->product[0]->full_name) . '</dd>
        <dt>Phone <span class="text-right float-right">:</span></dt>
        <dd>' . $company['telepon'] . '</dd>
        <dt>Fax <span class="text-right float-right">:</span></dt>
        <dd>' . $company['fax'] . '</dd>
        <dt>Email <span class="text-right float-right">:</span></dt>
        <dd>' . $this->product[0]->email . '</dd>
      </dl>';
      ?>
    </td>
    <td style="width: 10%;">
      &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
    </td>
    <td style="width: 45%;" class="text-right">
      <?php
      echo '<dl class="inline-flex">
        <dt>Vendor <span class="text-right float-right">:</span></dt>
        <dd>' . $this->product[0]->contact_name . '</dd>
        <dt>Attn. <span class="text-right float-right">:</span></dt>';
      ?>
        <dd contenteditable="true"><?php echo $this->product[0]->salutation . ' '. $this->product[0]->first_name . ' ' . $this->product[0]->middle_name . ' ' . $this->product[0]->last_name; ?></dd>
        <dt>Address <span class="text-right float-right">:</span></dt>
        <dd>
          <?php
            if (!empty($this->product[0]->address_street)) { echo $this->product[0]->address_street;}
            if (!empty($this->product[0]->address_city)) { echo ', ' . $this->product[0]->address_city;}
            if (!empty($this->product[0]->address_state)) { echo ', ' . $this->product[0]->address_state;}
          ?>
        </dd>
        <dt>Phone <span class="text-right float-right">:</span></dt>
        <dd><?php echo $this->product[0]->phone; ?></dd>
        <dt>Fax <span class="text-right float-right">:</span></dt>
        <dd><?php echo $this->product[0]->fax; ?></dd>
        <dt>Email <span class="text-right float-right">:</span></dt>
        <dd><?php echo $this->product[0]->customer_email; ?></dd>
        <dt>Reference <span class="text-right float-right">:</span></dt>
        <dd contenteditable="true"></dd>
        <dt>Date <span class="text-right float-right">:</span></dt>
        <dd contenteditable="true"></dd>
      </dl>
    </td>
  </tr>
</table>

<br>

<table class="fullwidth">
  <thead>
    <tr class="border-bottom">
      <th colspan="9" class="border-bottom">&nbsp;</th>
    </tr>
    <tr class="border-bottom">
      <th class="center">#</th>
      <th class="center">Item</th>
      <th class="text-right">Quantity</th>
      <th class="text-right">Price</th>
      <th class="text-right">Discount</th>
      <th class="text-right">VAT</th>
      <th class="text-right">Sub Total</th>
    </tr>
  </thead>

  <tbody>
            <?php
            $no = 1;
            $total_price = 0;
            foreach($this->product as $key => $value) {
      
                    echo '<tr class="border-dot">';
                    echo '<td class="text-right">' . $no . '</td>';
          if (!empty($value->material_name)) {
            if (!empty($value->material_specification)) {
              echo '<td>' . $value->material_name . '<br>(' . $value->material_specification . ')</td>';
            } else {
              echo '<td>' . $value->material_name . '</td>';
            }
          } else {
            if (!empty($value->material_specification)) {
              echo '<td>' . $value->budget_item . '<br>(' . $value->material_specification . ')</td>';
            } else {
              echo '<td>' . $value->budget_item . '</td>';
            }
          }          
          echo '<td class="text-right">' . floatval($value->quantity) . ' ' . $value->unit . '</td>';
          echo '<td class="text-right">' . strtoupper($value->purchase_currency) . ' ' . number_format($value->purchase_price,2) . '</td>';
          echo '<td class="text-right">' . number_format($value->purchase_price_discount,2) . '</td>';
          echo '<td class="text-right">' . number_format($value->purchase_tax,0) . '%</td>';
          $price_after_discount = $value->purchase_price - $value->purchase_price_discount;
          $price_after_discount_after_tax = $price_after_discount + ($price_after_discount * ($value->purchase_tax/100));
          $price_per_item = $price_after_discount_after_tax;
          $sub_total = $price_per_item * $value->quantity;

          echo '<td class="text-right">' . number_format($sub_total,2) . '</td>';
          echo "</tr>";
          $total_price = $total_price + $sub_total;
          $no++;
        

            }

            $total_price_plus_shipment = $total_price + $this->shipment_data->purchase_price;
        echo '<tr>
            <td class="text-right" colspan="6">Sub Total: </td>
            <td class="text-right">' . number_format($total_price,2) . '</td>
          </tr>';
        echo '<tr class="border-top">
            <td class="text-right" colspan="6">Shipping: </td>
            <td class="text-right">' . number_format($this->shipment_data->purchase_price,2) . '</td>
          </tr>';
        echo '<tr class="border-top">
            <td class="text-right" colspan="6">Total: </td>
            <td class="text-right">' . number_format($total_price_plus_shipment,2) . '</td>
          </tr>';
            ?>
                <tr class="border-top">
                  <td colspan="8" class="border-bottom">&nbsp;</td>
                </tr>
              </tbody>
</table>

<table class="fullwidth">
  <tr>
    <td class="text-center" style="width: 40%;">
      <dl class="inline-flex">
        <dt>Delivery Time <span class="text-right float-right">:</span></dt>
        <dd contenteditable="true"><?php echo $this->shipment_data->delivery_time; ?></dd>
        <dt>Delivery Point <span class="text-right float-right">:</span></dt>
        <dd contenteditable="true"><?php echo $this->shipment_data->ship_via; ?></dd>
      </dl>
    </td>
    <td>&nbsp;</td>
    <td class="text-center" style="width: 40%;">
      <dl class="inline-flex">
        <dt>Payment Terms <span class="text-right float-right">:</span></dt>
        <dd contenteditable="true">
          <?php echo $this->shipment_data->payment_term; ?>
        </dd>
        <dt>Invoice Address <span class="text-right float-right">:</span></dt>
        <dd>
          <?php echo $company['nama perusahaan'];?>
        </dd>
        <dt>Tax Registration Number <span class="text-right float-right">:</span></dt>
        <dd>
          <br>31.583.202.2-602.000
        </dd>
      </dl>
    </td>
  </tr>
  <tr>
    <td>Customer,</td>
    <td>&nbsp;</td>
    <td><?php echo $company['nama perusahaan']; ?>,</td>
  </tr>
    <tr>
    <td colspan="3">&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td><img src="<?php echo Config::get('URL'); ?>file/company/po_signature.jpg" style="width: 200px;padding-left: 35px;"></td>
  </tr>
  <tr>
    <td><span contenteditable="true" class="text-underline">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</span></td>
    <td>&nbsp;</td>
    <td>
      <span class="text-underline">Rochma Mud Jayanah</span> &nbsp; &nbsp; &nbsp; <span class="text-underline">Eko Prihartanto</span>
      <hr>
    </td>
  </tr>
</table>
<br>
<br>
<p class="text-center">Document Number: FM-06-003-PP, Revision: 01, Date: 3 January 2018, Pages: 1</p>
<hr style="border: 1px single;">
<p class="text-center">maximatransformer.com</p>
</body>
</html>