<?php $this->renderFeedbackMessages(); // Render message success or not?>
<div class="table-responsive">

<table border="1" class="table ExcelTable2007">
<tr>
  <th colspan="2">#Transaksi: <?php echo $this->product_list[0]->so_number; ?></th>
  <th colspan="2"><?php echo $this->product_list[0]->contact_name . ' (' . $this->product_list[0]->customer_id . ')'; ?></th>
  <th colspan="2"><?php echo date("d-M-Y", strtotime($this->product_list[0]->created_timestamp)); ?></th>
</tr>
<tr>
  <th class="heading">#</th>
  <th>Kode</th>
  <th>Nama</th>
  <th>Harga </th>
  <th>Jumlah Dijual</th>
  <th>Jumlah Retur</th>
</tr>

  <?php
        $no = 1;
        $sub_total = 0;
        $total = 0;
        foreach ($this->product_list as $key => $value) { 
            $sub_total = $value->selling_price * $value->quantity_delivered;
             echo '<tr class=" border">
                      <td align="left" valign="bottom" class="heading">'.  $no .'</td>
                      <td id="code' . $no .'">' . $value->material_code . '</td>
                      <td>' . $value->material_name . '</td>
                      <td id="price' . $no .'" align="right">' . number_format($value->selling_price, 0) . '</td>
                      <td id="qtySold' . $no .'" align="right">' . $value->quantity_delivered . '</td>
                      <td id="qtyReturn' . $no .'" contenteditable="true" align="right" onkeyup="savedRetur(' . $no .');" class="warning"></td>
                      <td id="uid' . $no .'" style="display: none">' . $value->uid . '</td>
                      <td id="endRow' . $no .'" style="display: none">endRow separator</td>
                    </tr>';
            $no++;
            $total = $total + $sub_total;
      } ?>
<tr>
  <td colspan="3"><a href="javascript: history.go(-1)" class="btn btn-sm btn-danger" style="width: 100%;">Cancel</a></td>
  <td colspan="3"><button type="button" id="save-button" onclick="finishAndSave();" class="btn btn-sm btn-primary" style="width: 100%;">Retur Barang</button></td>
  <td style="display: none">endRow separator</td>
</tr>
</table>
</div><!-- /.table-responsive -->

<script type="text/javascript">
function resetPage() {
  window.location.replace("<?php echo Config::get('URL'); ?>pos/reportKasir");
}

function getNumberOnly(string) {
  var number = string.replace ( /[^0-9]/g, '' );
  return parseInt(number);
}

function savedRetur(rowNumber) {
  var code = 'code' + rowNumber;
  var price = 'price' + rowNumber;
  var qtySold = 'qtySold' + rowNumber;
  var qtyReturn = 'qtyReturn' + rowNumber;
  var uid = 'uid' + rowNumber;
  var endRow = 'endRow' + rowNumber;
   // only show when have value
  var qtyValue = getNumberOnly(document.getElementById(qtyReturn).innerHTML);
  if (qtyValue > 0) {
    document.getElementById(code).classList.add("saved-data"); //add class
    document.getElementById(price).classList.add("saved-data"); //add class
    document.getElementById(qtySold).classList.add("saved-data"); //add class
    document.getElementById(qtyReturn).classList.add("saved-data"); //add class
    document.getElementById(uid).classList.add("saved-data"); //add class
    document.getElementById(endRow).classList.add("saved-data"); //add class
  } else {
    document.getElementById(code).classList.remove("saved-data"); //delete class
    document.getElementById(price).classList.remove("saved-data"); //delete class
    document.getElementById(qtySold).classList.remove("saved-data"); //delete class
    document.getElementById(qtyReturn).classList.remove("saved-data"); //delete class
    document.getElementById(uid).classList.remove("saved-data"); //delete class
    document.getElementById(endRow).classList.remove("saved-data"); //delete class
  }
}

function finishAndSave() {
  //Make string from purchased product table
  var sales_code = "<?php echo urlencode($this->product_list[0]->so_number); ?>";
  var customer_id = "<?php echo $this->product_list[0]->customer_id; ?>";
  var totalPrice = "<?php echo $total; ?>";
  var cell = document.getElementsByClassName("saved-data");
  var i = 0;
  var product_string = '';
  while(cell[i] != undefined) {
    if (cell[i].innerHTML == 'endRow separator') {
      product_string += ' ___ ';
    } else  {
        product_string += cell[i].innerHTML + ' --- ';
     }
    i++;
  }//end while
  //Send the string to server
  var http = new XMLHttpRequest();
  var url = "<?php echo Config::get('URL'); ?>productReturn/saveReturn";
  var params = "product_list=" + product_string + "&sales_code=" + sales_code + "&customer_id=" + customer_id + "&totalPrice=" + totalPrice;
  http.open("POST", url, true);
  //console.log(params);
  //Send the proper header information along with the request
  http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  http.onreadystatechange = function() {//Call a function when the state changes.
      if(http.readyState === XMLHttpRequest.DONE && http.status == 200) {
          //alert(params);
          alert(http.response);
          resetPage();
      }
  }
  http.send(params);
  //window.print();
}
</script>