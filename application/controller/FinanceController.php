<?php


class FinanceController extends Controller
{
    /**
     * Construct this object by extending the basic Controller class
     */
    public function __construct()
    {
        parent::__construct();

        // this entire controller should only be visible/usable by logged in users, so we put authentication-check here
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

        $this->View->render('finance/debit_credit_transaction',
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
            if ($status === 'not-settled') {
                $status = -1;
            } elseif ($status === 'settled') {
                $status = 1;
            }
            //Make sales number
            // cari tanggal pertama dan terakhir bulan ini untuk query semua SO bulan ini, nanti so terakhir ditambah 1 buat dijadikan so number
            $first_date = date('Y-m-01'); //!!!Always start with date 01 (first date)
            $last_date = date('Y-m-t',strtotime('today')); // tanggal terakhir bulan ini

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

        Redirect::to('finance/reportDebitCreditTransaction/');
    }

    public function reportDebitCreditTransaction($start_date = null, $end_date = null)
    {
        if (Request::post('change_date') == 'ok') { // user input change date
            Redirect::to('finance/reportDebitCreditTransaction/' . Request::post('start_date') . '/' . Request::post('end_date'));
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

        $this->View->render('finance/report_debit_credit_transaction',
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

        $this->View->render('finance/detail',
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