<?php


class SalesBookController extends Controller {

    /**
     * Construct this object by extending the basic Controller class
     */
    public function __construct()
    {
        parent::__construct();

        Auth::checkAuthentication();
    }

    public function debitCreditTransaction() {

        $pengeluaran = "SELECT * FROM `system_preference` WHERE (`category` = 'direct_expense_transaction')";
        $pemasukan = "SELECT * FROM `system_preference` WHERE (`category` = 'direct_income_transaction')";
        $header_script = '<link rel="stylesheet" href="' . Config::get('URL') . 'bootstrap-3.3.7/css/excel-2007.css" media="screen"/><link rel="stylesheet" href="' . Config::get('URL') . 'bootstrap-3.3.7/css/datepicker.css" media="screen"/>';
        $footer_script = '<script src="' . Config::get('URL') . 'bootstrap-3.3.7/js/bootstrap-datepicker.js"></script>
        <script>
        $(".datepicker").datepicker(); //date picker
        </script>';

        $this->View->render('sales_book/debit_credit_transaction',
            array(
                'title' => 'Buat Transaksi Kas Baru',
                'activelink1' => 'finance',
                'activelink2' => 'transaksi langsung',
                'activelink3' => 'buat transaksi',
                'header_script' => $header_script,
                'footer_script' => $footer_script,
                'pengeluaran' => GenericModel::rawSelect($pengeluaran),
                'pemasukan' => GenericModel::rawSelect($pemasukan)
            )
        );
    }

    public function saveDebitCreditTransaction($status = 'not-settled') {
            //Check Apakah Customer Ada Atau Tidak
        $customer_code = explode(' -- ', Request::post('customer'));
        $contact_id = trim($customer_code[0]);
        //var_dump(Request::post('customer'));
        if (!GenericModel::isExist('contact', 'contact_id', "{$contact_id}")) {
            echo 'Kode customer tidak ada di database!';
            exit;
        }

        //1. Make sales number
        $awal_tahun = date('Y-01-01');
        $table = '`sales_order`';
        $where = "`created_timestamp` >= '$awal_tahun' ORDER BY `created_timestamp` DESC";
        $field = "`transaction_number`";
        $so_data = GenericModel::getOne($table, $where, $field);
        $so_number = $so_data->transaction_number;
        $find_integer = explode('/', $so_number);
        $so_number = $find_integer[0];
        $so_number = FormaterModel::getNumberOnly($so_number);
        $so_number = $so_number + 1;
        $so_number = "00000".$so_number;
        $so_number = substr($so_number, strlen($so_number)-5, 5);
        $so_number = Config::get('COMPANY_CODE') . ' ' . $so_number . '/SO-' . date("my");

        //2. very complex
        for ($i=1; $i <= (int)$_POST['total_input'] ; $i++) {
            $product_code = Request::post('product_code_'.$i);
            $product_qty = FormaterModel::getNumberOnly($_POST['quantity_'.$i]);
            $product_price = FormaterModel::getNumberOnly($_POST['price_'.$i]);
            $product_ppn = FormaterModel::getNumberOnly($_POST['ppn_'.$i]);
            $product_pph = FormaterModel::getNumberOnly($_POST['pph_'.$i]);
            //echo '<pre>';var_dump($product_quantity);echo '</pre>';

            //2.1 insert sales order list product
            $insert = array(
                'uid'    => GenericModel::guid(),
                'material_code'    => $product_code,
                'quantity'    => $product_qty,
                'selling_price'    => $product_price,
                'tax_ppn'    => $product_ppn,
                'tax_pph'    => $product_pph,
                'transaction_number'    => $so_number,
                'delivery_request_date' => Request::post('delivery_date_request'),
                'creator_id'    => SESSION::get('uid')
            );
            GenericModel::insert('sales_order_list', $insert);

            //2.2 Update Selling Price in material List
            $update = array(
                        'selling_price' => $product_price,
                        'modifier_id'    => SESSION::get('user_name'),
                        );
            $cond = "`material_code` = '{$product_code}'";
            GenericModel::update('material_list', $update, $cond, false); //silent update

            //2.3 make forcasting production cost from BOM
            //2.3.1 get daftar material BOM yang dipakai tiap product
            $product_BOM = 'BOM.' . $product_code;
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
                    `material_list_formulation`.`formulation_code` = '{$product_BOM}'";
            $bom_list = GenericModel::rawSelect($bom_list_sql);

            //2.3.2 insert daftar material BOM ke database (plus jumlah forcasting dan harganya)
            foreach ($bom_list as $bom_key => $bom_value) {
                $production_qty = $product_qty * $bom_value->unit_per_quantity;
                $production_price = $bom_value->purchase_price * $production_qty;

                //check usd rate if currency use usd and rate is still empty
                if ($bom_value->purchase_currency != 'IDR' AND empty($currency_rate)) {
                    $currency_rate = FormaterModel::currencyRateBI();
                }

                //multiplication with dollar rate
                if ($bom_value->purchase_currency != 'IDR') {
                    $production_price = $production_price * (int) $currency_rate[$bom_value->purchase_currency]['jual'];

                    $note = 'BOM: <a href="' . Config::get('URL') . 'inventory/editFormulation/?find=' . urlencode($product_BOM) . '">' . $product_BOM . '</a>. Kurs: ' . $bom_value->purchase_currency . ' = ' . number_format($currency_rate[$bom_value->purchase_currency]['jual'], 0) . ' Rupiah.';
                } else {
                    $note = 'BOM: <a href="' . Config::get('URL') . 'inventory/editFormulation/?find=' . urlencode($product_BOM) . '">' . $product_BOM . '</a>.';
                }

                $insert = array(
                    'uid'    => GenericModel::guid(),
                    'job_type'    => $bom_value->job_type,
                    'sub_job_type'    => $bom_value->sub_job_type,
                    'material_code'    => $bom_value->material_code,
                    'quantity'    => $production_qty,
                    'unit'    => $bom_value->unit,
                    'purchase_price'    => $bom_value->purchase_price,
                    'purchase_currency'    => $bom_value->purchase_currency,
                    'purchase_unit'    => $bom_value->purchase_unit,
                    'production_price'    => $production_price,
                    'transaction_number'    => $so_number,
                    'note'    => $note,
                    'creator_id'    => SESSION::get('uid')
                );
                GenericModel::insert('production_forcasting_list', $insert, false);
            }
        }

        //3. insert detail sales order
        $insert = array(
                        'transaction_number'    => $so_number,
                        'sales_channel' => 'sales order',
                        'status' => -2,
                        'note' => Request::post('note'),
                        'delivery_request_date' => Request::post('delivery_date_request'),
                        'customer_id'    => $contact_id,
                        'creator_id'    => SESSION::get('uid')
                );
        // Send Status insert to front end
        GenericModel::insert('sales_order', $insert, false); // use silent so inser to commerce not counted as item inserted to sales

        Redirect::to('so/detail/?so_number=' . urlencode($so_number));
        

            for ($i=1; $i <=20; $i++) { 
                if (!empty(Request::post('name_' . $i)) AND !empty(Request::post('category_' . $i)) AND (!empty(Request::post('debit_' . $i)) OR !empty(Request::post('credit_' . $i)))) {
                    //make transaction number
                    $table = '`payment_transaction`';
                    $where = "`transaction_type` = 'debit credit transaction' AND `created_timestamp` BETWEEN '$first_date' AND '$last_date' ORDER BY `created_timestamp` DESC";
                    $field = "`transaction_code`";

                    $transCode = GenericModel::getOne($table, $where, $field);
                    $transaction_code = $transCode->transaction_code;
                    $find_integer = explode('/', $transaction_code);
                    $transaction_code = $find_integer[0];
                    $transaction_code = (integer) FormaterModel::getNumberOnly($transaction_code);
                    $transaction_code = $transaction_code + 1;
                    $transaction_code = "00000" . $transaction_code;
                    $transaction_code = substr($transaction_code, strlen($transaction_code)-5, 5);
                    $transaction_code = Config::get('COMPANY_CODE') . ' ' . $transaction_code . '/DCT-' . date("m") . date("y");
            
                    $insert=array(
                        'uid'    => GenericModel::guid(),
                        'transaction_code'    => $transaction_code,
                        'transaction_name'    => Request::post('name_' . $i),
                        'transaction_category'    => Request::post('category_' . $i),
                        'transaction_type'    => 'debit credit transaction',
                        'payment_type'    => 'cash',
                        'status'    => $status,
                        'debit'    => Request::post('debit_' . $i),
                        'credit'    => Request::post('credit_' . $i),
                        'payment_due_date'    => Request::post('date_' . $i),
                        'note'    => Request::post('note_' . $i),
                        'creator_id'    => SESSION::get('uid')
                        );
                    GenericModel::insert('payment_transaction', $insert);
                }
            }
        
        // Send Status insert to front end
        $feedback_positive = Session::get('feedback_positive');
        $feedback_negative = Session::get('feedback_negative');
        //count total feedback
        $total_feedback_positive = (int)count($feedback_positive);
        $total_feedback_negative = (int)count($feedback_negative);
        // RESET counter feedback to unconfuse user
        Session::set('feedback_positive', null);
        Session::set('feedback_negative', null);
        // echo out positive messages
        if ($total_feedback_positive > 0) {Session::add('feedback_positive', 'SUKSES, ' . $total_feedback_positive . ' transaksi berhasil disimpan');}
        // echo out negative messages
        if ($total_feedback_negative > 0) {Session::add('feedback_negative', 'GAGAL!, ' . $total_feedback_negative . ' transaksi tidak disimpan');}

        Redirect::to('sales_book/reportDebitCreditTransaction/');
    }

    public function reportDebitCreditTransaction($start_date = null, $end_date = null)
    {
        if (Request::post('change_date') == 'ok') { // user input change date
            Redirect::to('sales_book/reportDebitCreditTransaction/' . Request::post('start_date') . '/' . Request::post('end_date'));
            exit;
        }

        if ($start_date === null AND $end_date === null) {
            $start_date = $end_date = date('Y-m-d', time());
        }

            $sql = "
            SELECT
                    `uid`,
                    `transaction_name`,
                    `transaction_category`,
                    `debit`,
                    `credit`,
                    `note`,
                    `created_timestamp`
                FROM
                    `payment_transaction`
                WHERE
                (`payment_transaction`.`created_timestamp` BETWEEN '$start_date 00:00:00.000000' AND '$end_date 23:59:59.999999') AND `payment_transaction`.`transaction_type` = 'debit credit transaction'
                ORDER BY `created_timestamp` ASC";
            $sql_group_by_category = "
                SELECT
                    `uid`,
                    `transaction_name`,
                    `transaction_category`,
                    SUM(`debit`) AS `debit`,
                    SUM(`credit`) AS `credit`
                FROM
                    `payment_transaction`
                WHERE
                (`payment_transaction`.`created_timestamp` BETWEEN '$start_date 00:00:00.000000' AND '$end_date 23:59:59.999999') AND `payment_transaction`.`transaction_type` = 'debit credit transaction'
                GROUP BY `transaction_category`";
            $sql_group_by_category_week = "
                SELECT
                    CONCAT(YEAR(`created_timestamp`), '/', WEEK(`created_timestamp`)) as `week`,
                    `transaction_category`,
                    SUM(`debit`) AS `debit`,
                    SUM(`credit`) AS `credit`
                FROM
                    `payment_transaction`
                WHERE
                (`payment_transaction`.`created_timestamp` BETWEEN '$start_date 00:00:00.000000' AND '$end_date 23:59:59.999999') AND `payment_transaction`.`transaction_type` = 'debit credit transaction'
                GROUP BY `transaction_category`, `week`
                ORDER BY `week` ASC";
            $sql_group_by_category_month = "
                SELECT
                    CONCAT(YEAR(`created_timestamp`), '/', MONTH(`created_timestamp`)) as `month`,
                    `transaction_category`,
                    SUM(`debit`) AS `debit`,
                    SUM(`credit`) AS `credit`
                FROM
                    `payment_transaction`
                WHERE
                (`payment_transaction`.`created_timestamp` BETWEEN '$start_date 00:00:00.000000' AND '$end_date 23:59:59.999999') AND `payment_transaction`.`transaction_type` = 'debit credit transaction'
                GROUP BY `transaction_category`, `month`
                ORDER BY `month` ASC";

            $sql_group_by_year_week = "
            SELECT
                CONCAT(YEAR(`created_timestamp`), '/', WEEK(`created_timestamp`)) as `week`,
                SUM(`debit`) AS `debit`,
                SUM(`credit`) AS `credit`
                FROM
                    `payment_transaction`
                WHERE
                (`payment_transaction`.`created_timestamp` BETWEEN '$start_date 00:00:00.000000' AND '$end_date 23:59:59.999999') AND `payment_transaction`.`transaction_type` = 'debit credit transaction'
                GROUP BY `week`
                ORDER BY `week` ASC";
            $title = 'dari tanggal ' . date("d F Y", strtotime($start_date)) . ' - ' . date("d F Y", strtotime($end_date));
            $sql_group_by_year_month = "
            SELECT
                CONCAT(YEAR(`created_timestamp`), '/', MONTH(`created_timestamp`)) as `month`,
                SUM(`debit`) AS `debit`,
                SUM(`credit`) AS `credit`
                FROM
                    `payment_transaction`
                WHERE
                (`payment_transaction`.`created_timestamp` BETWEEN '$start_date 00:00:00.000000' AND '$end_date 23:59:59.999999') AND `payment_transaction`.`transaction_type` = 'debit credit transaction'
                GROUP BY `month`
                ORDER BY `month` ASC";
            $title = 'dari tanggal ' . date("d F Y", strtotime($start_date)) . ' - ' . date("d F Y", strtotime($end_date));
        

        $header_script = '<link rel="stylesheet" href="' . Config::get('URL') . 'bootstrap-3.3.7/css/datepicker.css" media="screen"/>';
        $footer_script = '<script src="' . Config::get('URL') . 'bootstrap-3.3.7/js/bootstrap-datepicker.js"></script>
        <script>
        $(".datepicker").datepicker(); //date picker
        </script>';

        $this->View->render('sales_book/report_debit_credit_transaction',
            array(
            'title' => 'Daftar Transaksi ' . $title,
            'activelink1' => 'finance',
            'activelink2' => 'transaksi langsung',
            'activelink3' => 'laporan transaksi langsung',
            'activelink4' => 'kas',
            'header_script' => $header_script,
            'footer_script' => $footer_script,
            'transaction' => GenericModel::rawSelect($sql),
            'transaction_group_by_category' => GenericModel::rawSelect($sql_group_by_category),
            'transaction_group_by_category_week' => GenericModel::rawSelect($sql_group_by_category_week),
            'transaction_group_by_category_month' => GenericModel::rawSelect($sql_group_by_category_month),
            'transaction_group_by_year_week' => GenericModel::rawSelect($sql_group_by_year_week),
            'transaction_group_by_year_month' => GenericModel::rawSelect($sql_group_by_year_month)
            )
        );
    }

    public function detail()
    {
        $transaction_number = urldecode(Request::get('transaction_number'));
        $sql = "
        SELECT
                *
            FROM
                `payment_transaction`
            WHERE
                `transaction_code` = '{$transaction_number}'";

        $this->View->render('sales_book/detail',
            array(
            'title' => 'Detail Transaski ' . $transaction_number,
            'activelink1' => 'finance',
            'activelink2' => 'transaksi langsung',
            'activelink3' => 'laporan transaksi langsung',
            'activelink4' => 'kas',
            'transaction' => GenericModel::rawSelect($sql),
            )
        );
    }
}