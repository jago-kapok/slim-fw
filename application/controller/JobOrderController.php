<?php


class JobOrderController extends Controller {

    /**
     * Construct this object by extending the basic Controller class
     */
    public function __construct()
    {
        parent::__construct();

        Auth::checkAuthentication();
        Auth::checkPermission('director,management,production,ppic,qc');
    }

    public function search($page = 1, $limit = 20)
    {
        $find = urldecode(Request::get('find'));
        $offset = ($page > 1) ? ($page - 1) * $limit : 0;
        $sql_group = "
                SELECT
                    `job_order`.`job_number`,
                    `job_order`.`job_reverence`,
                    `job_order`.`job_type`,
                    `job_order_product_list`.`material_code`,
                    GROUP_CONCAT(`material_list`.`material_name` SEPARATOR '-, -') as `material_name`,
                    GROUP_CONCAT(`job_order_product_list`.`quantity` SEPARATOR '-, -') as `quantity`,
                    `users`.`full_name`
                FROM
                    `job_order`
                LEFT JOIN
                    `job_order_product_list` ON `job_order_product_list`.`job_number` = `job_order`.`job_number`
                LEFT JOIN
                    `material_list` AS `material_list` ON  `material_list`.`material_code` = `job_order_product_list`.`material_code`
                LEFT JOIN
                    `users` AS `users` ON `users`.`uid` = `job_order`.`creator_id`
                WHERE
                    `job_order`.`job_number` LIKE '%$find%'
                GROUP BY
                     `job_order`.`job_number`
                ORDER BY
                    `job_order`.`created_timestamp` DESC
                LIMIT
                    $offset, $limit";

        $header_script = '<link rel="stylesheet" href="' . Config::get('URL') . 'bootstrap-3.3.7/css/excel-2007.css" media="screen"/>';

        $total_record = GenericModel::rowCount('`job_order`', "`job_order`.`job_number` LIKE '%$find%'", '`job_number`');
        $search_string = '?find=' . urlencode($find);
        $pagination = FormaterModel::pagination('jobOrder/search', $total_record, $page, $limit, $search_string);
        $this->View->render('job_order/finished',
            array(
                'header_script' => $header_script,
                'title' => 'Production Order On Process',
                'activelink1' => 'Manufacturing',
                'page' => $page,
                'limit' => $limit,
                'on_process_list' => GenericModel::rawSelect($sql_group),
                'pagination' => $pagination,
                )
        );
    }

    public function createJobOrder()
    {
        $this->View->renderFileOnly('job_order/job_order', array(
                'product_list' => GenericModel::getAll('material_list', "`is_deleted` = 0 AND (`material_type` = 2 OR `material_type` = 3)", "`material_code`, `material_name`, `unit`"),
                'material_list' => GenericModel::getAll('material_list', "`is_deleted` = 0 AND (`material_type` = 0 OR `material_type` = 1 OR `material_type` = 3)", "`material_code`, `material_name`, `unit`"),

                )
        );
    }

    public function saveJobOrder() {
        //Check Apakah Reference dan Request Date diisi atau tidak
        if (empty(Request::post('job_type')) OR empty(Request::post('job_reference'))) {
            echo 'Keterangan dan Tipe pekerjaan belum diisi!';
            exit;
        }

        $product_list   = explode(' ___ ', Request::post('product_list'));
        $product_list   = array_filter($product_list);
        $material_list  = explode(' ___ ', Request::post('material_list'));
        $material_list  = array_filter($material_list);
        //echo '<pre>';var_dump($material_list);echo '</pre>'; exit;

        //Make Production number
        $awal_tahun = date('Y-01-01');
        $table = '`job_order`';
        $where = "(`created_timestamp` >= '{$awal_tahun}' AND `job_category` = 'work order') ORDER BY `created_timestamp` DESC";
        $field = "`job_number`";
        $so_data = GenericModel::getOne($table, $where, $field);
        $job_number = $so_data->job_number;
        $find_integer = explode('/', $job_number);
        $job_number = $find_integer[0];
        $job_number = (int) FormaterModel::getNumberOnly($job_number);
        $job_number = $job_number + 1;
        $job_number = "000".$job_number;
        $job_number = substr($job_number, strlen($job_number)-5, 5);
        $job_number = Config::get('COMPANY_CODE') . ' ' . $job_number . '/WO-' . date("my");


        foreach ($product_list as  $value) {
            $product   = explode('---', $value);
            $product_code = FormaterModel::sanitize($product[0]);
            $product_qty = FormaterModel::sanitize($product[1]);
            $product_note = FormaterModel::sanitize($product[2]);
            //echo '<pre>';var_dump($product_quantity);echo '</pre>';
            if (!empty($product_code  ) AND !empty($product_qty)) {
                    $insert = array(
                        'uid'    => GenericModel::guid(),
                        'material_code'    => $product_code,
                        'quantity'    => $product_qty,
                        'job_number' => $job_number,
                        'creator_id'    => SESSION::get('uid')
                    );
                    GenericModel::insert('job_order_product_list', $insert);
                    $transaction_status = true;
            }
        }

        if ($transaction_status) {
            foreach ($material_list as $value) {
            $material   = explode('---', $value);
            //echo '<pre>';var_dump($material);echo '</pre>'; exit;
            $material_code = FormaterModel::sanitize($material[0]);
            $material_qty = FormaterModel::sanitize($material[1]);
            $material_unit = FormaterModel::sanitize($material[2]);
                if (!empty($material_code) AND !empty($material_qty)) {
                    //check if material code is BOM or Not
                    if (substr($material_code, 0, 4) === 'BOM.') {
                        $bom_list_sql = "
                        SELECT
                            `material_list_formulation`.`job_type`,
                            `material_list_formulation`.`sub_job_type`,
                            `material_list_formulation`.`material_code`,
                            `material_list_formulation`.`unit_per_quantity`,
                            `material_list_formulation`.`unit`,
                            `material_list`.`purchase_price`,
                            `material_list`.`purchase_currency`,
                            `material_list`.`purchase_unit`
                        FROM
                            `material_list_formulation`
                                JOIN
                            `material_list`
                                ON
                            `material_list`.`material_code` = `material_list_formulation`.`material_code`

                        WHERE
                            `material_list_formulation`.`formulation_code` = '{$material_code}'";
                        $bom_list = GenericModel::rawSelect($bom_list_sql);

                        foreach ($bom_list as $bom_key => $bom_value) {
                            $production_qty = $material_qty * $bom_value->unit_per_quantity;
                            $production_price = $bom_value->purchase_price * $production_qty;

                             //check usd rate if currency use usd and rate is still empty
                            if ($bom_value->purchase_currency != 'IDR' AND empty($currency_rate)) {
                                $currency_rate = FormaterModel::currencyRateBI();
                            }

                            //multiplication with dollar rate
                            if ($bom_value->purchase_currency != 'IDR') {
                                $production_price = $production_price * (int) $currency_rate[$bom_value->purchase_currency]['jual'];

                                $note = 'BOM: <a href="' . Config::get('URL') . 'inventory/editFormulation/?find=' . urlencode($material_code) . '">' . $material_code . '</a>. Kurs: ' . $bom_value->purchase_currency . ' = ' . number_format($currency_rate[$bom_value->purchase_currency]['jual'], 0) . ' rupiah.';
                            } else {
                                $note = 'BOM: <a href="' . Config::get('URL') . 'inventory/editFormulation/?find=' . urlencode($material_code) . '">' . $material_code . '</a>.';
                            }

                            $insert = array(
                                'uid'    => GenericModel::guid(),
                                'material_code'    => $bom_value->material_code,
                                'quantity'    => $production_qty,
                                'unit'    =>  $bom_value->unit,
                                'job_number'    => $job_number,
                                'purchase_price'    => $bom_value->purchase_price,
                                'purchase_currency'    => $bom_value->purchase_currency,
                                'purchase_unit'    => $bom_value->purchase_unit,
                                'production_price'    => $production_price,
                                'note'    => $note,
                                'creator_id'    => SESSION::get('uid')
                            );
                            GenericModel::insert('job_order_material_list', $insert, false);
                        }
                    } else { // Not BOM
                            //get price for material
                            $material_data = GenericModel::getOne('material_list', "`material_code` = '{$material_code}'", '`purchase_price`, `purchase_currency`, `purchase_unit`');
                            $production_price = $material_data->purchase_price * $material_qty;


                            //check usd rate if currency use usd and rate is still empty
                            if ($material_data->purchase_currency != 'IDR' AND empty($currency_rate)) {
                                $currency_rate = FormaterModel::currencyRateBI();
                            }

                            //multiplication with dollar rate
                            if ($material_data->purchase_currency != 'IDR') {
                                $production_price = $production_price * (int) $currency_rate[$material_data->purchase_currency]['jual'];

                                $note = 'Kurs ' . $material_data->purchase_currency . ' = ' . number_format($currency_rate[$material_data->purchase_currency]['jual'], 0) . ' Rupiah.';
                            } else {
                                $note = '';
                            }

                            $insert = array(
                                'uid'    => GenericModel::guid(),
                                'material_code'    => $material_code,
                                'quantity'    => $material_qty,
                                'unit'    =>  $material_unit,
                                'job_number'    => $job_number,
                                'purchase_price'    => $material_data->purchase_price,
                                'purchase_currency'    => $material_data->purchase_currency,
                                'purchase_unit'    => $material_data->purchase_unit,
                                'production_price'    => $production_price,
                                'note'    => $note,
                                'creator_id'    => SESSION::get('uid')
                            );
                            GenericModel::insert('job_order_material_list', $insert, false);
                    }
                }
            }

            //make Production Order
            $insert = array(
                            'job_number'    => $job_number,
                            'job_category'    => 'work order',
                            'job_type'    => Request::post('job_type'),
                            'job_reverence'    => Request::post('job_reference'),
                            'note'    => Request::post('note'),
                            'creator_id'    => SESSION::get('uid'),
                            'is_active'    => 1,

                    );
            // Send Status insert to front end
            QgenericModel::insert('job_order', $insert); // use silent so inser to commerce not counted as item inserted to sales
        }

        $feedback_positive = Session::get('feedback_positive');
        $feedback_negative = Session::get('feedback_negative');
        // echo out positive messages
        if (isset($feedback_positive)) {
            echo 'SUKSES, ' . count($feedback_positive) . ' job order berhasil disimpan';
            //echo Config::get('URL') . 'pos/printNotaPenjualan/?so_number=' . urlencode($so_number);
        }
        // echo out negative messages
        if (isset($feedback_negative)) {
            echo 'GAGAL!, ' . count($feedback_positive) . ' job order berhasil disimpan';
        }
        // RESET counter feedback to unconfuse user
        Session::set('feedback_positive', null);
        Session::set('feedback_negative', null);
    }

        public function detail()
    {
        $job_number = urldecode(Request::get('job_number'));
        $production_detail = "
                SELECT
                    `job_order`.`job_number`,
                    `job_order`.`job_reverence`,
                    `job_order`.`job_type`,
                    `job_order`.`delivery_request`,
                    `job_order`.`note`,
                    `job_order`.`status`,
                    `job_order`.`created_timestamp`,
                    `contact`.`contact_name`,
                    `contact`.`address_street`,
                    `contact`.`address_city`,
                    `contact`.`address_state`,
                    `contact`.`address_zip`,
                    `contact`.`website`,
                    `contact`.`phone`,
                    `contact`.`fax`,
                    `contact`.`email`,
                    `users`.`full_name`
                FROM
                    `job_order`
                LEFT JOIN
                    `sales_order` AS `sales_order` ON `sales_order`.`transaction_number` = `job_order`.`job_reverence`
                LEFT JOIN
                    `contact` AS `contact` ON `contact`.`contact_id` = `sales_order`.`customer_id`
                LEFT JOIN
                    `users` AS `users` ON `users`.`uid` = `sales_order`.`creator_id`
                WHERE
                    `job_order`.`job_number` = '{$job_number}'";

        $product_list = "SELECT
                    `job_order_product_list`.`uid`,
                    `job_order_product_list`.`material_code`,
                    `job_order_product_list`.`quantity`,
                    `in`.`quantity_received`,
                    `material_list`.`material_name`
                FROM
                    `job_order_product_list`
                LEFT JOIN
                    `material_list` AS `material_list` ON `material_list`.`material_code` = `job_order_product_list`.`material_code`
                LEFT JOIN (
                    SELECT
                        `material_list_in`.`material_code`,
                        SUM(`material_list_in`.`quantity_received`) AS `quantity_received`
                    FROM
                        `material_list_in`
                    WHERE
                        `material_list_in`.`transaction_number` = '{$job_number}'
                    GROUP BY `material_list_in`.`material_code`
                    ) AS `in` ON `in`.`material_code` = `job_order_product_list`.`material_code`
                WHERE
                    `job_order_product_list`.`job_number` = '{$job_number}'
                GROUP BY
                    `job_order_product_list`.`material_code`";

        $material_list = "SELECT
                    `job_order_material_list`.`job_number`,
                    `job_order_material_list`.`material_code`,
                    `job_order_material_list`.`unit` AS `formulation_unit`,
                    `job_order_material_list`.`material_code`,
                    `job_order_material_list`.`quantity`,
                    `job_order_material_list`.`production_price`,
                    `job_order_material_list`.`purchase_price`,
                    `job_order_material_list`.`purchase_currency`,
                    `job_order_material_list`.`purchase_unit`,
                    `job_order_material_list`.`job_type`,
                    `material_list`.`material_name`,
                    `material_list`.`unit` AS `stock_unit`,
                    `stock`.`quantity_stock`
                FROM
                    `job_order_material_list`
                LEFT JOIN
                    `material_list` AS `material_list` ON `material_list`.`material_code` = `job_order_material_list`.`material_code`
                LEFT JOIN
                    (SELECT
                        `material_code`,
                        SUM(quantity_stock) AS `quantity_stock`
                    FROM
                        `material_list_in`
                    GROUP  BY
                        `material_list_in`.`material_code`) AS `stock` ON `stock`.`material_code` = `material_list`.`material_code`
                WHERE
                    `job_order_material_list`.`job_number` = '{$job_number}'
                ORDER BY
                    `material_list`.`material_name`
                ";

        $consumed_material_list = "SELECT
                    `material_list_out`.`uid`,
                    `material_list_out`.`material_code`,
                    `material_list_out`.`material_lot_number`,
                    `material_list_out`.`quantity_delivered`,
                    `material_list_out`.`production_price`,
                    `material_list_out`.`purchase_price`,
                    `material_list_out`.`purchase_unit`,
                    `material_list_out`.`note`,
                    `material_list_out`.`created_timestamp`,
                    `material_list`.`material_name`,
                    `material_list`.`unit`
                FROM
                    `material_list_out`
                LEFT JOIN
                    `material_list` ON `material_list`.`material_code` = `material_list_out`.`material_code`
                WHERE
                    `material_list_out`.`transaction_number` = '{$job_number}' AND `material_list_out`.`status` = 'jo'
                ORDER BY
                    `material_list_out`.`created_timestamp` DESC";

        $result_list = "SELECT
                    `material_list_in`.`uid`,
                    `material_list_in`.`material_code`,
                    `material_list_in`.`quantity`,
                    `material_list_in`.`quantity_received`,
                    `material_list_in`.`quantity_reject`,
                    `material_list_in`.`serial_number`,
                    `material_list_in`.`serial_number_received`,
                    `material_list_in`.`created_timestamp`,
                    `material_list_in`.`note`,
                    `material_list_in`.`status`,
                    `material_list`.`material_name`
                FROM
                    `material_list_in`
                LEFT JOIN
                    `material_list` AS `material_list` ON `material_list`.`material_code` = `material_list_in`.`material_code`
                WHERE
                    `material_list_in`.`transaction_number` = '{$job_number}'
                ORDER BY
                    `material_list_in`.`created_timestamp` ASC";
        //For Printing
        if (Request::get('print') == 1) {
            if (Request::get('printConsumedMaterial') == 1) {
                $rendered_file = 'job_order/print_consumed_material';
            }
        } else {
            $rendered_file = 'job_order/detail';
        }

        $header_script = '<link rel="stylesheet" href="' . Config::get('URL') . 'bootstrap-3.3.7/css/datepicker.css" media="screen"/>
        <link rel="stylesheet" href="' . Config::get('URL') . 'bootstrap-3.3.7/css/excel-2007.css" media="screen"/>';
        $footer_script = '<script src="' . Config::get('URL') . 'bootstrap-3.3.7/js/bootstrap-datepicker.js"></script>
            <script>
            jQuery("button").click( function(e) {
                jQuery(".collapse").collapse("hide");
            }); //Hide All collapse when button is clicked
                    
                    $(".datepicker").datepicker(); //date picker
                    </script>';
        $this->View->render($rendered_file,
            array(
            'header_script' => $header_script,
            'footer_script' => $footer_script,
            'title' => 'Detail Job Order Production Number: ' . $job_number,
            'activelink1' => 'Manufacturing',
            'activelink2' => 'Production',
            'production_detail' => GenericModel::rawSelect($production_detail, false),
            'product_list' => GenericModel::rawSelect($product_list),
            'material_list' => GenericModel::rawSelect($material_list),
            'consumed_material_list' => GenericModel::rawSelect($consumed_material_list),
            'result_list' => GenericModel::rawSelect($result_list),
            )
        );
    }

    public function onProcess($page = 1, $limit = 20)
    {
        $offset = ($page > 1) ? ($page - 1) * $limit : 0;
        $sql_group = "
                SELECT
                    `job_order`.`job_number`,
                    `job_order`.`job_reverence`,
                    `job_order`.`job_type`,
                    `job_order_product_list`.`material_code`,
                    GROUP_CONCAT(`material_list`.`material_name` SEPARATOR '-, -') as `material_name`,
                    GROUP_CONCAT(`job_order_product_list`.`quantity` SEPARATOR '-, -') as `quantity`,
                    `users`.`full_name`
                FROM
                    `job_order`
                LEFT JOIN
                    `job_order_product_list` ON `job_order_product_list`.`job_number` = `job_order`.`job_number`
                LEFT JOIN
                    `material_list` AS `material_list` ON  `material_list`.`material_code` = `job_order_product_list`.`material_code`
                LEFT JOIN
                    `users` AS `users` ON `users`.`uid` = `job_order`.`creator_id`
                WHERE
                    `job_order`.`status` < 0 AND `job_category` = 'work order'
                GROUP BY
                     `job_order`.`job_number`
                ORDER BY
                    `job_order`.`created_timestamp` DESC
                LIMIT
                    $offset, $limit";

        $header_script = '<link rel="stylesheet" href="' . Config::get('URL') . 'bootstrap-3.3.7/css/excel-2007.css" media="screen"/>';

        $total_record = GenericModel::rowCount('`job_order`', '`job_order`.`status` < 0', '`job_number`');
        $pagination = FormaterModel::pagination('jobOrder/onProcess', $total_record, $page, $limit);

        $this->View->render('job_order/on_process',
            array(
                'header_script' => $header_script,
                'title' => 'Production Order On Process',
                'activelink1' => 'Manufacturing',
                'activelink2' => 'Production',
                'activelink3' => 'Internal Job On Process',
                'on_process_list' => GenericModel::rawSelect($sql_group),
                'page' => $page,
                'limit' => $limit,
                'pagination' => $pagination,
                )
        );
    }

    public function finished($page = 1, $limit = 20)
    {
        $offset = ($page > 1) ? ($page - 1) * $limit : 0;
        $sql_group = "
                SELECT
                    `job_order`.`job_number`,
                    `job_order`.`job_reverence`,
                    `job_order`.`job_type`,
                    `job_order_product_list`.`material_code`,
                    GROUP_CONCAT(`material_list`.`material_name` SEPARATOR '-, -') as `material_name`,
                    GROUP_CONCAT(`job_order_product_list`.`quantity` SEPARATOR '-, -') as `quantity`,
                    `users`.`full_name`
                FROM
                    `job_order`
                LEFT JOIN
                    `job_order_product_list` ON `job_order_product_list`.`job_number` = `job_order`.`job_number`
                LEFT JOIN
                    `material_list` AS `material_list` ON  `material_list`.`material_code` = `job_order_product_list`.`material_code`
                LEFT JOIN
                    `users` AS `users` ON `users`.`uid` = `job_order`.`creator_id`
                WHERE
                    `job_order`.`status` = 100 AND `job_category` = 'work order'
                GROUP BY
                     `job_order`.`job_number`
                ORDER BY
                    `job_order`.`created_timestamp` DESC
                LIMIT
                    $offset, $limit";

        $header_script = '<link rel="stylesheet" href="' . Config::get('URL') . 'bootstrap-3.3.7/css/excel-2007.css" media="screen"/>';

        $total_record = GenericModel::rowCount('`job_order`', '`job_order`.`status` = 100', '`job_number`');
        $pagination = FormaterModel::pagination('jobOrder/finished', $total_record, $page, $limit);
        $this->View->render('job_order/finished',
            array(
                'header_script' => $header_script,
                'title' => 'Production Order On Process',
                'activelink1' => 'Manufacturing',
                'activelink2' => 'Production',
                'activelink3' => 'Internal Job Finished',
                'page' => $page,
                'limit' => $limit,
                'on_process_list' => GenericModel::rawSelect($sql_group),
                'pagination' => $pagination,
                )
        );
    }

    public function deleteProductionResult($uid) {
        $serial_number_result = GenericModel::getOne('`material_list_in`', "`uid` = '{$uid}'", '`serial_number`');
        $serial_number_result = explode(',', $serial_number_result->serial_number);
        foreach ($serial_number_result AS $value) {
            $serial_number = trim($value);
            GenericModel::remove('serial_number', 'serial_number', $serial_number, false); //false for silent feedback
        }
        GenericModel::remove('material_list_in', 'uid', $uid, false); //false for silent feedback
        Redirect::to(Request::get('forward'));
    }

    public function deleteProduction() {
        $job_number = urldecode(Request::get('job_number'));
        GenericModel::remove('job_order_product_list', 'job_number', $job_number, false); //false for silent feedback
        GenericModel::remove('job_order_material_list', 'job_number', $job_number, false); //false for silent feedback
        GenericModel::remove('material_list_out', 'transaction_number', $job_number, false); //false for silent feedback
        GenericModel::remove('material_list_in', 'transaction_number', $job_number, false); //false for silent feedback
        GenericModel::remove('production_forcasting_list', 'transaction_number', $job_number, false); //false for silent feedback
        GenericModel::remove('serial_number', 'production_number', $job_number, false); //false for silent feedback
        GenericModel::remove('job_order', 'job_number', $job_number, false); //false for silent feedback
        Redirect::to(Request::get('forward'));
    }

    public function closeProduction() {
        $job_number = urldecode(Request::get('job_number'));
        $update = array(
                        'status'    => 100,
                        'modifier_id'    => SESSION::get('uid'),
                        'modified_timestamp'    => date("Y-m-d H:i:s")
                        );
        GenericModel::update('job_order', $update, "`job_number` = '{$job_number}'");
        Redirect::to(Request::get('forward'));
    }

    public function openProduction() {
        $job_number = urldecode(Request::get('job_number'));
        $update = array(
                        'status'    => -2, //default value for new Job Order
                        'modifier_id'    => SESSION::get('uid'),
                        'modified_timestamp'    => date("Y-m-d H:i:s")
                        );
        GenericModel::update('job_order', $update, "`job_number` = '{$job_number}'");
        Redirect::to(Request::get('forward'));
    }

    public function inputProductionResult() {
        
        $job_number = urldecode(Request::get('job_number'));
        $so_number = urldecode(Request::get('so_number'));
        $total_record = (int)Request::post('total_record');
        $sn_counter = 0;

        for ($i = 1; $i <= $total_record; $i++) { 
            if (Request::post('quantity_' . $i) <= Request::post('quantity_jo_' . $i) AND Request::post('quantity_' . $i) > 0) {

                if (!empty(Request::post('serial_number_' . $i))) {
                    $serial_number = Request::post('serial_number_' . $i);
                } else {
                    $serial_number = SerialNumberModel::make($job_number, Request::post('customer_type'), Request::post('quantity_' . $i), $sn_counter);
                }
                
                $insert = array(
                        'uid'    => GenericModel::guid(),
                        'material_code' => Request::post('material_code_' . $i),
                        'quantity'    => Request::post('quantity_' . $i),
                        'serial_number'    => $serial_number,
                        'transaction_number' => $job_number,
                        'status' => 'waiting_qc_approval',
                        'incoming_date'    => date("Y-m-d"),
                    );
                GenericModel::insert('material_list_in', $insert);
                $sn_counter = $sn_counter + Request::post('quantity_' . $i);
            }
        }

        //SEND EMAIL NOTIFICATION
        //GET RELATED Departemen email
        $email_address = GenericModel::getAll('users', "`department` = 'qc'", '`email`');
        $email = array();
        foreach ($email_address as $key => $value) {
            $email[] = $value->email;
        }
        //tambah email pt.ilmui
        $email[] = 'edi@sbautomedia.com';

        $email_creator = SESSION::get('full_name');
        $email_subject = "PPIC meminta approval QC untuk hasil produksi JO number: " . $job_number . ' by ' . $email_creator;
        $body = ucwords($email_creator) . ' meminta approval QC untuk hasil produksi JO number ' . $job_number . '. Klik link berikut untuk melihat detail JO ' .   Config::get('URL') . 'JobOrder/detail/?job_number=' . urlencode($job_number);
        $mail = new Mail;

        $mail_sent = $mail->sendMail($email, Config::get('EMAIL_VERIFICATION_FROM_EMAIL'),
            Config::get('EMAIL_VERIFICATION_FROM_NAME'), $email_subject, $body
        );
        if ($mail_sent) {
            Session::add('feedback_positive', Text::get('FEEDBACK_VERIFICATION_MAIL_SENDING_SUCCESSFUL'));
        } else {
            Session::add('feedback_negative', Text::get('FEEDBACK_VERIFICATION_MAIL_SENDING_ERROR') . $mail->getError() );
        }

        Redirect::to('JobOrder/detail/?job_number=' . urlencode($job_number));
    }

    public function qcApproval() {
        $job_number = urldecode(Request::get('job_number'));
        $so_number = urldecode(Request::get('so_number'));

        //make booking status of serial number
        if (!empty($so_number)) {
            $status = 1;
        } else {
            $status = 0;
        }

        //Validation Input
        $totalrecord = Request::post('total_record');
        for ($i = 1; $i <= $totalrecord; $i++) {
            if (!empty(Request::post('qty_approved_' . $i))) { // hanya validasi data yang tidak kosong isian jumlanya
                // To make sure qty received is not bigger than qty production
                $qty_approved = (int)Request::post('qty_approved_' . $i);
                $qty_made = Request::post('qty_production_' . $i);
                 // To make sure qty received is not bigger than qty purchased
                if ($qty_approved > $qty_made) {
                        Session::add('feedback_negative', "ERROR!. Total quantity Approved & Reject items is bigger than quantity made in production");
                        Redirect::to('JobOrder/detail/?job_number=' . urlencode($job_number));
                        exit;
                }
                $serial_number = $_POST['serial_number_' . $i];
                $total_serial_number = count($serial_number);
                if ($total_serial_number != $qty_approved) {
                        Session::add('feedback_negative', "ERROR!. Jumlah SERIAL NUMBER yang dipilih tidak sama dengan jumlah yang diterima");
                        Redirect::to('JobOrder/detail/?job_number=' . urlencode($job_number));
                        exit;
                }
            }
        }

        // Start Insert Database;
        $total_qty_rejected = 0;
        $total_qty_approved = 0;
        for ($i = 1; $i <= $totalrecord; $i++) {
            // hanya validasi data yang tidak kosong isian jumlanya
            $qty_approved_ = Request::post('qty_approved_' . $i);
            if ($qty_approved_ >= 0 AND is_numeric($qty_approved_)) {
                $selected_serial_number = implode(", ", $_POST['serial_number_' . $i]);
                $product_code = Request::post('product_code_' . $i);
                $uid = Request::post('uid_' . $i);
                $qty_approved = Request::post('qty_approved_' . $i);
                $qty_made = Request::post('qty_production_' . $i);
                $qty_reject = $qty_made - $qty_approved;
                $note = Request::post('note_' . $i);

                $total_qty_rejected = $total_qty_rejected + $qty_reject;
                $total_qty_approved = $total_qty_approved + $$qty_approved;
        

                //Insert Serial Number
                foreach ($_POST['serial_number_' . $i] as $serial_key => $serial_value) {
                    $sn = trim($_POST['serial_number_' . $i][$serial_key]);
                    if (!empty($sn)) {
                        $insert = array(
                            'serial_number' => $sn,
                            'material_code'    => $product_code,
                            'transaction_number' => $so_number,
                            'status' => $status,
                            'production_number' => $job_number,
                            'is_active' => 1,
                        );
                        QgenericModel::insert('serial_number', $insert);
                    }
                }

                //update qty received
                $update = array(
                    'quantity_received'    => $qty_approved,
                    'quantity_reject'    => $qty_reject,
                    'modifier_id'    => SESSION::get('uid'),
                    'status'    => 'stock',
                    'serial_number_received'    => $selected_serial_number,
                    'note'    => $note,
                    'qc_pass_date'    => date("Y-m-d"),
                    'modified_timestamp'    => date("Y-m-d H:i:s")
                    );
                GenericModel::update('material_list_in', $update, "`uid` = '{$uid}'");
            } // END IF
        } // END FOR

        //SEND EMAIL NOTIFICATION
        //GET RELATED Departemen email
        $email_address = GenericModel::getAll('users', "`department` = 'ppic' OR `department` = 'management' OR `department` = 'finance'", '`email`');
        $email = array();
        foreach ($email_address as $key => $value) {
            $email[] = $value->email;
        }
        //tambah pt. ilmui email
        $email[] = 'edi@sbautomedia.com';

        //Notifikasi untuk reject
        if ($total_qty_rejected > 0) {
            $email_creator = SESSION::get('full_name');
            $email_subject = "QC Reject Notification for JO number: " . $job_number . ' by ' . $email_creator;
            $body = ucwords($email_creator) . ' mereject hasil produksi pada JO nomer ' . $job_number . '. Klik link berikut untuk melihat detail JO ' . Config::get('URL') . 'JobOrder/detail/?job_number=' . urlencode($job_number);
            $mail = new Mail;
            $mail_sent = $mail->sendMail($email, Config::get('EMAIL_VERIFICATION_FROM_EMAIL'),
                Config::get('EMAIL_VERIFICATION_FROM_NAME'), $email_subject, $body
            );
            if ($mail_sent) {
                Session::add('feedback_positive', Text::get('FEEDBACK_VERIFICATION_MAIL_SENDING_SUCCESSFUL'));
            } else {
                Session::add('feedback_negative', Text::get('FEEDBACK_VERIFICATION_MAIL_SENDING_ERROR') . $mail->getError() );
            }
        }

        //Notifikasi untuk diterima
        if ($total_qty_approved > 0) {
            $email_creator = SESSION::get('full_name');
            $email_subject = "QC PASSED Notification for JO number: " . $job_number . ' by ' . $email_creator;
            $body = ucwords($email_creator) . ' menerima (QC PASSED) hasil produksi pada JO nomer ' . $job_number . '. Klik link berikut untuk melihat detail JO ' . Config::get('URL') . 'JobOrder/detail/?job_number=' . urlencode($job_number);
            $mail = new Mail;
            $mail_sent = $mail->sendMail($email, Config::get('EMAIL_VERIFICATION_FROM_EMAIL'),
                Config::get('EMAIL_VERIFICATION_FROM_NAME'), $email_subject, $body
            );
            if ($mail_sent) {
                Session::add('feedback_positive', Text::get('FEEDBACK_VERIFICATION_MAIL_SENDING_SUCCESSFUL'));
            } else {
                Session::add('feedback_negative', Text::get('FEEDBACK_VERIFICATION_MAIL_SENDING_ERROR') . $mail->getError() );
            }
        }

        Redirect::to('JobOrder/detail/?job_number=' . urlencode($job_number));
    }

    public function insertMaterialConsumptionByBOM()
    {
        $job_number = Request::get('job_number');
        $sql = "
                SELECT
                    `material_list`.`material_name`, 
                    `material_list`.`material_code`,
                    `material_list`.`unit`,
                    `stock`.`quantity_stock`
                FROM
                    `job_order_material_list`
                LEFT JOIN
                    `material_list`
                        ON
                    `material_list`.`material_code` = `job_order_material_list`.`material_code`
                LEFT JOIN
                    (SELECT
                        `material_code`,
                        SUM(quantity_stock) AS `quantity_stock`
                    FROM
                        `material_list_in`
                    GROUP  BY
                        `material_list_in`.`material_code`) AS `stock` ON `stock`.`material_code` = `job_order_material_list`.`material_code`
                WHERE
                    `job_order_material_list`.`job_number` = '{$job_number}'
                GROUP BY
                    `material_list`.`material_code`
                ORDER BY
                    `material_list`.`material_name`";

        $this->View->renderFileOnly('job_order/insert_material_consumption_by_bom', array(
                'job_number' => urldecode($job_number),
                'material_list' => GenericModel::rawSelect($sql),

                )
        );
    }

    public function insertMaterialConsumptionManual() {
        $this->View->renderFileOnly('job_order/insert_material_consumption_manual', array(
                'job_number' => urldecode(Request::get('job_number')),
                'material_list' => GenericModel::getAll('material_list', "`is_deleted` = 0 AND (`material_type` = 0 OR `material_type` = 1 OR `material_type` = 2 OR `material_type` = 4 OR `material_type` = 6 OR `material_type` = 7)", "`material_code`, `material_name`, `unit`"),

                )
        );
    }

    public function saveMaterialConsumptionByBOM() {
        
        $job_number = Request::post('job_number');
        for ($i=1; $i <= Request::post('total_input'); $i++) {
            $material_code = Request::post('material_code_' . $i);
            $consumed_quantity = Request::post('consumed_quantity_' . $i);
            $note = Request::post('note_' . $i);
            if (!empty($material_code) AND $consumed_quantity > 0) {
                //Select Detail Stock Lot Number Tiap Material
                $lot_stock = "
                    SELECT
                        `material_list_in`.`uid`,
                        `material_list_in`.`quantity_stock`,
                        `material_list_in`.`transaction_number`,
                        `material_list`.`purchase_price`,
                        `material_list`.`purchase_currency`,
                        `material_list`.`purchase_unit`
                    FROM
                        `material_list_in`
                    LEFT JOIN
                        `material_list`
                            ON
                        `material_list`.`material_code` = `material_list_in`.`material_code`
                    WHERE
                        `material_list_in`.`material_code` = '{$material_code}' AND `material_list_in`.`quantity_stock` > 0
                    ORDER BY
                        `material_list_in`.`created_timestamp` ASC";
                $lot_stock_result = GenericModel::rawSelect($lot_stock);

                foreach ($lot_stock_result as $key_stock => $value_stock) {
                    //check apakah yang dikonsumsi lebih kecil dari stock di lot tertentu
                    if ($consumed_quantity <= $value_stock->quantity_stock ) {
                        //cari harga pembeian
                        $price_after_discount = $value_stock->purchase_price - $value_stock->purchase_price_discount;

                        $production_price = $price_after_discount * $consumed_quantity;
                        //check usd rate if currency use usd and rate is still empty
                        if ($value_stock->purchase_currency != 'IDR' AND empty($currency_rate)) {
                            $currency_rate = FormaterModel::currencyRateBI();
                        }

                        //multiplication with dollar rate
                        if ($value_stock->purchase_currency != 'IDR') {
                            $production_price = $production_price * (int) $currency_rate[$value_stock->purchase_currency]['jual'];
                            $note = $note . '. Kurs ' . $value_stock->purchase_currency . ' = ' . $currency_rate[$value_stock->purchase_currency]['jual'];
                        } else {
                            $note = $note;
                        }

                        $insert = array(
                            'uid'    => GenericModel::guid(),
                            'material_code' => $material_code,
                            'quantity_delivered'    => $consumed_quantity,
                            'transaction_number' => $job_number,
                            'material_lot_number' => $value_stock->transaction_number,
                            'purchase_price'    => $price_after_discount,
                            'purchase_unit'    => $value_stock->unit,
                            'production_price'    => $production_price,
                            'status' => 'jo',
                            'note' => $note,
                            'creator_id'    => SESSION::get('uid'),
                        );

                        GenericModel::insert('material_list_out', $insert);

                        //update qty stock
                        $update = array(
                            'quantity_stock'    => $value_stock->quantity_stock - $consumed_quantity,
                            'modified_timestamp'    => date("Y-m-d H:i:s"),
                            'modifier_id'    => SESSION::get('uid'),
                            );
                        GenericModel::update('material_list_in', $update, "`uid` = '{$value_stock->uid}'", false); //silent message

                        //Reset $consumed_quantity menjadi 0
                        $consumed_quantity = 0;
                        break; //STOP FOEACH LOOPING!. Important!
                    
                    } else { //yang dikonsumsi lebih besar dari lot tertentu, maka dikurangkan dari tiap lot sampai nol baru ke lot berikutnya

                        //cari harga pembeian
                        $price_after_discount = $value_stock->purchase_price - $value_stock->purchase_price_discount;

                        $production_price = $price_after_discount * $value_stock->quantity_stock; //jumlaah stock yang ada (habis dikonsumsi!);
                        //check usd rate if currency use usd and rate is still empty
                        if ($value_stock->purchase_currency != 'IDR' AND empty($currency_rate)) {
                            $currency_rate = FormaterModel::currencyRateBI();
                        }

                        //multiplication with dollar rate
                        if ($value_stock->purchase_currency != 'IDR') {
                            $production_price = $production_price * (int) $currency_rate[$value_stock->purchase_currency]['jual'];
                            $note = $note . '. Kurs ' . $value_stock->purchase_currency . ' = ' . $currency_rate[$value_stock->purchase_currency]['jual'];
                        } else {
                            $note = $note;
                        }

                        $insert = array(
                            'uid'    => GenericModel::guid(),
                            'material_code' => $material_code,
                            'quantity_delivered'    => $value_stock->quantity_stock, //jumlaah stock yang ada (habis dikonsumsi!)
                            'transaction_number' => $job_number,
                            'material_lot_number' => $value_stock->transaction_number,
                            'purchase_price'    => $price_after_discount,
                            'purchase_unit'    => $value_stock->unit,
                            'production_price'    => $production_price,
                            'status' => 'jo',
                            'note' => $note,
                            'creator_id'    => SESSION::get('uid'),
                        );

                        GenericModel::insert('material_list_out', $insert);

                        $update = array(
                            'quantity_stock'    => 0,
                            'modified_timestamp'    => date("Y-m-d H:i:s"),
                            'modifier_id'    => SESSION::get('uid'),
                            );
                        GenericModel::update('material_list_in', $update, "`uid` = '{$value_stock->uid}'", false); //silent message
                        //kurangi konsumsi kuantity sesuai jumlah stock lot yand diapaki
                        $consumed_quantity = $consumed_quantity - $value_stock->quantity_stock;
                    }
                } //end foreach lot number
            } //end if not empty material code and consumed quantity
        }

        Redirect::to('JobOrder/detail/?job_number=' . urlencode($job_number));
    }

    public function madeTimeReport()
    {
        $sql_group = "
                SELECT
                    `job_order`.`job_number`,
                    `job_order`.`created_timestamp` as `tanggal_mulai`,
                    GROUP_CONCAT(`production_result`.`material_name` SEPARATOR '-, -') as `material_name`,
                    GROUP_CONCAT(`production_result`.`material_code` SEPARATOR '-, -') as `material_code`,
                    GROUP_CONCAT(`production_result`.`created_timestamp` SEPARATOR '-, -') as `tanggal_selesai`
                FROM
                    `job_order`
                LEFT JOIN
                    (SELECT
                        `material_list`.`material_name`,
                        `material_list_in`.`transaction_number`,
                        `material_list_in`.`material_code`,
                        `material_list_in`.`created_timestamp`
                    FROM
                        `material_list_in`
                    LEFT JOIN
                        `material_list`
                        ON
                        `material_list`.`material_code` = `material_list_in`.`material_code`
                    ) AS `production_result` ON `production_result`.`transaction_number` = `job_order`.`job_number`
                GROUP BY
                    `job_order`.`job_number`";

        $sql_group = "
                SELECT
                    `material_list`.`material_code`,
                    `material_list`.`material_name`,
                    GROUP_CONCAT(`production_result`.`transaction_number` SEPARATOR '-, -') as `transaction_number`,
                    GROUP_CONCAT(`production_result`.`lama_kerja` SEPARATOR '-, -') as `lama_kerja`
                FROM
                    `job_order`
                LEFT JOIN
                    (SELECT
                        `material_list_in`.`transaction_number`,
                        DATEDIFF(`material_list_in`.`created_timestamp`, `job_order`.`created_timestamp`) AS `lama_kerja`
                    FROM
                        `material_list_in`
                    LEFT JOIN
                        `job_order`
                        ON
                        `job_order`.`job_number` = `material_list_in`.`transaction_number`
                    ) AS `production_result` ON `production_result`.`transaction_number` = `job_order`.`job_number`
                LEFT JOIN
                    `material_list_in`
                    ON
                    `material_list_in`.`transaction_number` = `job_order`.`job_number`
                LEFT JOIN
                    `material_list`
                    ON
                    `material_list`.`material_code` = `material_list_in`.`material_code`
                GROUP BY
                    `material_list_in`.`material_code`";


        $header_script = '<link rel="stylesheet" href="' . Config::get('URL') . 'bootstrap-3.3.7/css/excel-2007.css" media="screen"/>';

        $this->View->render('job_order/made_time_report',
            array(
                'header_script' => $header_script,
                'title' => 'Production Order On Process',
                'activelink1' => 'Manufacturing',
                'activelink2' => 'Production',
                'activelink3' => 'Production Finished',
                'result' => GenericModel::rawSelect($sql_group),
                )
        );
    }

}