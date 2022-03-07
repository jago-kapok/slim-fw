<div id="serial-number" class="tab-pane fade">
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover">
            <thead>
                <tr>
                    <th>No</th>
                    <th class="center">Serial Number</th>
                    <th class="center">Status</th>
                    <th class="center">Delete</th>
                </tr>
            </thead>

            <tbody>
                <?php
                $no = $this->number;
                $jumlah=0;
                foreach($this->sn as $key => $sn) {
                    echo "<tr>";
                    echo '<td>' . $no . '</td>';
                    echo '<td >' . $sn->serial_number . '</td>';
                    if ($sn->status == 1) {
                        echo '<td>booked (<a href="' .  Config::get('URL') . 'po/detail/?po_number=' . urlencode($sn->transaction_number) . '">' . $sn->transaction_number . '</a>)</td>';
                    } else {
                        echo '<td></td>';
                    }

                    echo '<td><a href="' .  Config::get('URL') . 'delete/remove/serial_number/serial_number/' . $sn->serial_number . '&?forward=' . $_SERVER['REQUEST_URI'] . '" class="btn btn-danger btn-xs" onclick="return confirmation(\'Are you sure to delete?\');">delete</a></td>';
                    echo "</tr>";
                    $no++;
                }      
                ?>

            </tbody>
        </table>
    </div>
    <ul class="pager pull-right">
        <?php if($this->number != 1) { ?>
            <li class="previous">
                <a href="<?php echo $this->prev;?>">← Prev</a>
            </li>
        <?php } ?>
        <li class="next">
            <a href="<?php echo $this->next;?>">Next →</a>
        </li>
    </ul>
</div>