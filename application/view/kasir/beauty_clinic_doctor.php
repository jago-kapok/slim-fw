<div class="tab">
    <?php
    $menu_category = 'no menu';
    $no = 0;
    $category_array = array();
    foreach($this->doctor_treatment_list as $key => $value) {
        if ($menu_category != $value->material_category) {
            $style = ($no === 0) ? 'buttton-red' : '';
            echo '<button class="tab-item button-tab tablink ' . $style . '" onclick="openTab(event,\'doctor-' . $no .'\')">' . ucwords($value->material_category) . '</th>';
            $category_array['number'][] = 'doctor-' . $no;
            $category_array['menu'][] = $value->material_category;
            $no++;
        }
        $menu_category = $value->material_category;
    }
    ?>
    <button class="tab-item button-tab tablink" onclick="openTab(event,'semua-doctor')">Semua</button>
</div>
<?php
//echo count($category_array['number']);
//echo '<pre>';var_dump($category_array);echo '</pre>';
$menu_num = 0;
$menu_category = 'no menu';
foreach ($category_array['number'] as $category) {
//echo $category;
    $style = ($menu_num === 0) ? '' : 'style="display:none"';
    ?>
    <div id="<?php echo $category; ?>" class="w3-container w3-border city" <?php echo $style ?>>
        <table style="width: 100%;" class="ExcelTable2007">
            <?php
            $no = 0;
            foreach ($this->doctor_treatment_list as $key => $value) {
                if ($category_array['menu'][$menu_num] == $value->material_category) {
                    if ($no % 3 == 0) echo '<tr>';
                    echo '<td><button class="product-button color-' . $no . '" onclick="clikProduct(\'' . $value->material_code .'\');">' . $value->material_name . '</button></td>';
                    if ($no % 3 == 2) echo '</tr>';

//reset nomer ke 0 jika lebih dari 35, untuk pewarnaan daftar menu
                    if ($no == 35 ) {
                        $no = 0;
                    } else {
                        $no++;
                    }
                }

            }
            $menu_name = $value->material_name;
            $menu_category = $value->material_category;
if ($no % 3 != 0) echo '</tr>'; // close last line, unless total count was multiple of 3
?>
</table>
</div>
<?php $menu_num++;} ?>

<div id="semua-doctor" class="w3-container w3-border city" style="display:none">
    <table style="width: 100%;">
        <?php
        $no = 0;
        foreach ($this->doctor_treatment_list as $key => $value) {
            if ($no % 3 == 0) echo '<tr>';
            echo '<td><button class="product-button color-' . $no . '" onclick="clikProduct(\'' . $value->material_code .'\');">' . $value->material_name . '</button></td>';
            if ($no % 3 == 2) echo '</tr>';

//reset nomer ke 0 jika lebih dari 35, untuk pewarnaan daftar menu
            if ($no == 35 ) {
                $no = 0;
            } else {
                $no++;
            }

        }
if ($no % 3 != 0) echo '</tr>'; // close last line, unless total count was multiple of 3
?>
</table>
</div>