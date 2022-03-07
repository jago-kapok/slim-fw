<div class="main-content">
  <div class="main-content-inner">


    <div class="table-responsive">
      <table class="table table-striped table-bordered table-hover ExcelTable2007">
      <thead>
      <tr>
        <th class="text-right">No</th>
        <th class="text-left">Date</th>
        <th class="text-left">#JO</th>
        <th class="text-left">SO Reverence</th>
        <th class="text-left">Material Code</th>
        <th class="text-left">Material Name</th>
        <th class="text-right">Qty</th>
        <th class="text-right">Satuan</th>
        <th class="text-right">Note</th>
      </tr>
      </thead>

      <tbody>
      <?php
      $no = 1;
      foreach($this->unconformity as $key => $value) {
          $total_item = explode('-, -', $value->material_name);
          $total_quantity_reject = explode('-, -', $value->quantity_reject);
          $total_note = explode('-, -', $value->note);
          $total_material_code = explode('-, -', $value->material_code);
          $total_unit= explode('-, -', $value->unit);
          $total_note= explode('-, -', $value->note);
          $row = count($total_item);
          if ($row > 1) {
              for ($i=1; $i <= $row; $i++) {
                  $x = $i - 1;
                  if ($i === 1) {
                      echo '<tr class="' . 'row-' . $no . '">';

                      echo '<td class="text-right" rowspan="' . $row . '">' . $no . '</td>';
                      echo '<td rowspan="' . $row . '">' . date("d M, y", strtotime($value->created_timestamp)) . '</td>';
                      echo '<td  rowspan="' . $row . '">
                          <a href="' .  Config::get('URL') . 'production/detail/?production_number=' . urlencode($value->job_number) . '">' . $value->job_number . '</a></td>';
                      echo '<td  rowspan="' . $row . '">
                          <a href="' .  Config::get('URL') . 'so/detail/?so_number=' . urlencode($value->job_reverence) . '">' . $value->job_reverence . '</a></td>';
                      echo '<td>' . $total_material_code[$x] . '</td>';
                      echo '<td>' . $total_item[$x] . '</td>';
                      echo '<td class="text-right">' . number_format($total_quantity_reject[$x],0) . '</td>';
                      echo '<td>' . $total_unit[$x] . '</td>';
                      echo '<td>' . $total_note[$x] . '</td>';
                      echo "</tr>";
                  } else {
                      echo '<tr class="' . 'row-' . $no . '">';
                      echo '<td>' . $total_material_code[$x] . '</td>';
                      echo '<td>' . $total_item[$x] . '</td>';
                      echo '<td class="text-right">' . number_format($total_quantity_reject[$x],0) . '</td>';
                      echo '<td>' . $total_unit[$x] . '</td>';
                      echo '<td>' . $total_note[$x] . '</td>';
                      echo "</tr>";
                  }
                  
              } //end for
              
          } else {
              echo '<tr class="' . 'row-' . $no . '">';
              echo '<td class="text-right">' . $no . '</td>';
              echo '<td>' . date("d M, y", strtotime($value->created_timestamp)) . '</td>';
              echo '<td><a href="' .  Config::get('URL') . 'production/detail/?production_number=' . urlencode($value->job_number) . '">' . $value->job_number . '</a></td>';
              echo '<td><a href="' .  Config::get('URL') . 'so/detail/?so_number=' . urlencode($value->job_reverence) . '">' . $value->job_reverence . '</a></td>';
              echo '<td>' . $value->material_code . '</td>';
              echo '<td>' . $value->material_name . '</td>';
              echo '<td class="text-right">' . number_format($value->quantity_reject) . '</td>';
              echo '<td>' . $value->unit . '</td>';
              echo '<td>' . $value->note . '</td>';
              echo "</tr>";
          } //end if
          
          $no++;
      }
      ?>
      </tbody>
      </table>
    </div>

  <div class="hr hr10 hr-double"></div>
  <ul class="pager pull-right">
  <?php if($this->page != 1) { ?>
      <li class="previous">
        <a href="<?php echo $this->prev;?>">← Prev</a>
      </li>
  <?php } ?>
    <li class="next">
      <a href="<?php echo $this->next;?>">Next →</a>
    </li>
  </ul>
              
  
  </div><!-- /.main-content-inner -->
</div><!-- /.main-content -->
