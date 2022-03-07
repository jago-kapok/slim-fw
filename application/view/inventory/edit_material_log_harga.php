<div id="price-log" class="tab-pane fade">
    <div class="table-responsive">
    <table class="table table-striped table-bordered table-hover">
        <thead>
        <tr>
        <th class="center">No</th>
        <th>Tgl</th>
        <th class="center">Harga</th>
        <th class="center">PPN</th>
        <th class="center">PPH</th>
        <th class="center">SO Number</th>
        <th class="center">Sales</th>
        <th class="center">Keterangan</th>
        </tr>
        </thead>

        <tbody>
        <?php
        $no = $this->number;
        foreach($this->price_log as $key => $value) {
        echo "<tr>";
        echo '<td>' . $no . '</td>';
        echo '<td>' . date("d M, y", strtotime($value->created_timestamp)) . '</td>';
        
        echo '<td>' . number_format($value->selling_price, 0) . '</td>';
        echo '<td>' . number_format($value->tax_ppn, 0) . '</td>';
        echo '<td>' . number_format($value->tax_pph, 0) . '</td>';
        echo '<td><a href="' .  Config::get('URL') . 'so/detail/?so_number=' . urlencode($value->transaction_number) . '">' . $value->transaction_number . '</a></td>';
        echo '<td class="align-right">' . $value->full_name . '</td>';
        echo '<td><pre>' . $value->note . '</pre></td>';
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