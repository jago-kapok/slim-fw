<div class="table-responsive">
<form action="<?php echo Config::get('URL') . 'payment/confirmPayment/' . $value->uid . '/?po_number=' . urlencode($this->transaction_number); ?>" method="post">

<table class="table ExcelTable2007">
                  <tr>
                       <td class="heading"># </td>
                       <td>Jumlah </td>
                       <td>Cara Pembayaran </td>
                       <td>Rencana Pembayaran </td>
                       <td>Tanggal Cair </td>
                       <td>Catatan </td>
                       <td>Action </td>
                   </tr>
                          <?php
                          $num = 1;
                          $counter = 0;
                          foreach($this->payment_list as $key => $value) {
                          // check jika pr bukan biaya pengiriman
                            if ($value->status != 1) {
                              echo '<tr>';
                                echo '<td class="heading">' . $num . '</td>';
                                echo '<td>' . $value->debit . '</td>';
                                echo '<td><select name="payment_type">';
                                foreach ($this->payment_type as $key_payment_type => $value_payment_type) {
                                    if($value->payment_type == $value_payment_type->item_name ){
                                      echo '<option value="' . $value_payment_type->item_name . '" selected>' . $value_payment_type->item_name . '</option>';
                                    } else {
                                      echo '<option value="' . $value_payment_type->item_name . '">' . $value_payment_type->item_name . '</option>';
                                    }
                                  }
                                echo '</select></td>';
                                echo '<td>' . date("d-M-Y", strtotime($value->payment_due_date)) . '</td>';
                                echo '<td><input class="datepicker form-control" name="payment_disbursement" type="text"></td>';
                                echo '<td><input class="form-control" name="note" type="text"></td>';
                                echo '<td><div class="btn-group">
                                                <button type="submit" class="btn btn-primary btn-minier">Confirm</button>
                                        <a onclick="return confirmation()" href="' . Config::get('URL') . 'delete/remove/cash_transaction/uid/' . $value->uid . '/?forward=' . $_SERVER['REQUEST_URI'] . '" class="btn btn-danger btn-minier">delete</a>
                                    </div>
                                    </td>';
                                echo "</tr>";
                                
                              } else {
                                echo "<tr>";
                                echo '<td class="heading">' . $num . '</td>';
                                echo '<td>' . $value->debiy . '</td>';
                                echo '<td>' . $value->payment_type . '</td>';
                                echo '<td>' . date("d-M-Y", strtotime($value->payment_due_date)) . '</td>';
                                echo '<td>' . date("d-M-Y", strtotime($value->payment_disbursement)) . '</td>';
                                echo '<td>' . $value->note . '</td>';
                                echo "</tr>";
                              }

                            $num++;
                            $counter++;
                          }
                          ?>
                    
            </table>
          </form>
</div>