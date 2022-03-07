<div role="tabpanel" class="tab-pane active" id="by-date">
  <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover">
        <thead>
        <tr>
        <th class="text-center" colspan="10"><?php echo $this->title;?></th>      
        </tr>
        <tr>
        <th class="text-right">No</th>
        <th class="text-left">Tanggal</th>
        <th class="text-left">#Transaksi</th>
        <th class="text-left">Customer</th>
        <th class="text-left">Nama Transaksi</th>
        <th class="text-left">Kategori</th>
        <th class="text-right">Jumlah</th>
        <th class="text-right">Harga</th>
        <th class="text-right">Total</th> 
        <th class="text-right">Keterangan</th> 
        </tr>
        </thead>

                <tbody>
        <?php
        $no = 1;
        foreach($this->transaction as $key => $value) {
            $total_item = explode('-, -', $value->budget_item);
            $total_category = explode('-, -', $value->budged_category);
            $total_quantity = explode('-, -', $value->quantity);
            $total_price = explode('-, -', $value->selling_price);
            $total_note = explode('-, -', $value->note);
            $row = count($total_item);
            if ($row > 1) {
                for ($i=1; $i <= $row; $i++) {
                    $x = $i - 1;
                    if ($i === 1) {
                        echo '<tr>';
                        echo '<td rowspan="' . $row . '">
                            <div class="btn-group">
                              <button data-toggle="dropdown" class="btn btn-primary btn-white btn-minier dropdown-toggle">
                                ' . $no . '
                                <i class="ace-icon fa fa-angle-down icon-on-right"></i>
                              </button>

                              <ul class="dropdown-menu">
                                <li class="dropdown-header">Pembayaran</li>
                                <li class="divider"></li>
                                <li>
                                  <a href="#paymentModal" data-toggle="modal" data-target="#paymentModal" data-whatever="' . $value->transaction_number . '">Buat Rencana Pembayaran</a>
                                </li>

                                <li>
                                  <a  href="' . Config::get('URL') . 'bukuBesar/confirmPayment/?transaction_number=' . urlencode($value->transaction_number) . '">List Pembayaran</a>
                                </li>
                                <li class="divider"></li>
                                <li class="dropdown-header">Upload File</li>
                                <li class="divider"></li>
                                <li>
                                  <a href="#uploadModal" data-toggle="modal" data-target="#uploadModal" data-uploadfile="' . $value->transaction_number . '">Upload SPK</a>
                                </li>

                                <li>
                                  <a  href="' . Config::get('URL') . 'orderBook/uploadedImageList/?transaction_number=' . urlencode($value->transaction_number) . '">List SPK</a>
                                </li>
                                 <li class="divider"></li>
                                <li class="dropdown-header">Edit &amp; Delete</li>
                                <li class="divider"></li>
                                <li>
                                  <a href="' .  Config::get('URL') . 'orderBook/editOrder/?transaction_number=' . urlencode($value->transaction_number) . '&forward=' . $_SERVER['REQUEST_URI'] . '">Edit</a>
                                </li>

                                <li>
                                  <a href="' .  Config::get('URL') . 'orderBook/deleteOrder/?transaction_number=' . urlencode($value->transaction_number) . '&forward=' . $_SERVER['REQUEST_URI'] . ' " class="danger" onclick="return confirmation(\'Are you sure to delete?\');">delete</a>
                                </li>
                              </ul>
                            </div></td>';
                        echo '<td rowspan="' . $row . '">' . date("d M, y", strtotime($value->created_timestamp)) . '</td>';
                        echo '<td  rowspan="' . $row . '">' . $value->transaction_number . '</td>';
                        echo '<td  rowspan="' . $row . '">
                            <a href="' .  Config::get('URL') . '/contact/detail/' . urlencode($value->customer_id) . '">' . $value->contact_name . '</a></td>';
                        echo '<td>' . $total_item[$x] . '</td>';
                        echo '<td>' . $total_category[$x] . '</td>';
                        echo '<td class="text-right">' . number_format($total_quantity[$x],0) . '</td>';
                        echo '<td class="text-right">' . number_format($total_price[$x],0) . '</td>';
                        $sub_total = ($total_quantity[$x] * $total_price[$x]);
                        echo '<td class="text-right">' . number_format($sub_total,0) . '</td>';
                        echo '<td>' . $total_note[$x] . '</td>';
                        echo "</tr>";
                    } else {
                        echo '<tr>';
                        echo '<td>' . $total_item[$x] . '</td>';
                        echo '<td>' . $total_category[$x] . '</td>';
                        echo '<td class="text-right">' . number_format($total_quantity[$x],0) . '</td>';
                        echo '<td class="text-right">' . number_format($total_price[$x],0) . '</td>';
                        $sub_total = ($total_quantity[$x] * $total_price[$x]);
                        echo '<td class="text-right">' . number_format($sub_total,0) . '</td>';
                        echo '<td>' . $total_note[$x] . '</td>';
                        echo "</tr>";
                    }
                    
                } //end for
                
            } else {
                echo '<tr>';
                echo '<td>
                            <div class="btn-group">
                              <button data-toggle="dropdown" class="btn btn-primary btn-white btn-minier dropdown-toggle">
                                ' . $no . '
                                <i class="ace-icon fa fa-angle-down icon-on-right"></i>
                              </button>

                              <ul class="dropdown-menu">
                                <li class="dropdown-header">Pembayaran</li>
                                <li class="divider"></li>
                                <li>
                                  <a href="#paymentModal" data-toggle="modal" data-target="#paymentModal" data-whatever="' . $value->transaction_number . '">Buat Rencana Pembayaran</a>
                                </li>

                                <li>
                                  <a  href="' . Config::get('URL') . 'bukuBesar/confirmPayment/?transaction_number=' . urlencode($value->transaction_number) . '">List Pembayaran</a>
                                </li>
                                <li class="divider"></li>
                                <li class="dropdown-header">Upload File</li>
                                <li class="divider"></li>
                                <li>
                                  <a href="#uploadModal" data-toggle="modal" data-target="#uploadModal" data-uploadfile="' . $value->transaction_number . '">Upload Photo</a>
                                </li>

                                <li>
                                  <a  href="' . Config::get('URL') . 'orderBook/uploadedImageList/?transaction_number=' . urlencode($value->transaction_number) . '">List Photo</a>
                                </li>
                                 <li class="divider"></li>
                                <li class="dropdown-header">Edit &amp; Delete</li>
                                <li class="divider"></li>
                                <li>
                                  <a href="' .  Config::get('URL') . 'orderBook/editOrder/?transaction_number=' . urlencode($value->transaction_number) . '&forward=' . $_SERVER['REQUEST_URI'] . '">Edit</a>
                                </li>

                                <li>
                                  <a href="' .  Config::get('URL') . 'orderBook/deleteOrder/?transaction_number=' . urlencode($value->transaction_number) . '&forward=' . $_SERVER['REQUEST_URI'] . ' " class="danger" onclick="return confirmation(\'Are you sure to delete?\');">delete</a>
                                </li>
                              </ul>
                            </div></td>';
                echo '<td>' . date("d M, y", strtotime($value->created_timestamp)) . '</td>';
                echo '<td>' . $value->transaction_number . '</td>';
                echo '<td><a href="' .  Config::get('URL') . '/contact/detail/' . urlencode($value->customer_id) . '">' . $value->contact_name . '</a></td>';
                echo '<td>' . $value->budget_item . '</td>';
                echo '<td>' . $value->budged_category . '</td>';
                echo '<td class="text-right">' . number_format($value->quantity) . '</td>';
                echo '<td class="text-right">' . number_format($value->selling_price) . '</td>';
                $sub_total = ($value->quantity * $value->selling_price);
                echo '<td class="text-right">' . number_format($sub_total,0) . '</td>';
                echo '<td>' . $value->note . '</td>';
                echo "</tr>";
            } //end if
            
            $no++;
        }
        ?>
        </tbody>
        </table>
  </div>
</div> <!-- /#semua -->