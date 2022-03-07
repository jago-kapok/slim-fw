<?php


class BukuBesarController extends Controller
{

    /**
     * Construct this object by extending the basic Controller class
     */
    public function __construct()
    {
        parent::__construct();
        // special authentication check for the entire controller: Note the check-ADMIN-authentication!
        // All methods inside this controller are only accessible for admins (= users that have role type 7)
        Auth::checkAuthentication();
        Auth::checkPermission('director,finance,sales,purchasing');
    }

    /**
     * This method controls what happens when you move to /note/index in your app.
     * Gets all notes (of the user).
     */
    
    public function index()
    {
        Auth::checkPermission('director,finance');
            $sql = "
            SELECT
                `transaction`.`transaction_code`,
                `transaction`.`debit`,
                `transaction`.`credit`,
                `transaction`.`currency`,
                `transaction`.`transaction_name`,
                `transaction`.`transaction_category`,
                `transaction`.`transaction_type`,
                `transaction`.`payment_due_date`,
                `transaction`.`payment_disbursement`,
                `transaction`.`contact_id`,
                `contact`.`contact_name`
            FROM 
                (SELECT
                    `payment_transaction`.`transaction_code`,
                    `payment_transaction`.`debit`,
                    `payment_transaction`.`credit`,
                    `payment_transaction`.`currency`,
                    `payment_transaction`.`transaction_name`,
                    `payment_transaction`.`transaction_category`,
                    `payment_transaction`.`transaction_type`,
                    `payment_transaction`.`payment_due_date`,
                    `payment_transaction`.`payment_disbursement`, 
                    IF((`sales_order`.`transaction_number` != ''), `sales_order`.`customer_id`, `purchase_order`.`supplier_id`) AS `contact_id`
                FROM
                    `payment_transaction`
                    LEFT JOIN
                        `sales_order` ON `sales_order`.`transaction_number` = `payment_transaction`.`transaction_code`
                    LEFT JOIN
                        `purchase_order` ON `purchase_order`.`transaction_number` = `payment_transaction`.`transaction_code`
                WHERE
                    `payment_transaction`.`status` = 1) AS `transaction`
            LEFT JOIN
                `contact` ON `contact`.`contact_id` = `transaction`.`contact_id`
            ORDER BY `transaction`.`payment_disbursement` ASC";
            $bukubesar = GenericModel::rawSelect($sql);
            $header_script = '<link rel="stylesheet" href="' . Config::get('URL') . 'bootstrap-3.3.7/css/excel-2007.css" media="screen"/>';

            $this->View->render('bukubesar/index',
                  array(
                'title' => 'Buku Besar',
                'header_script' => $header_script,
                'activelink1' => 'finance',
                'activelink2' => 'buku besar',
                'bukubesar' => $bukubesar
                )
            );

    }

    public function cashFlow()
    {
            Auth::checkPermission('director,finance');
            $sql = "
            SELECT
                `transaction`.`transaction_code`,
                `transaction`.`debit`,
                `transaction`.`credit`,
                `transaction`.`currency`,
                `transaction`.`transaction_name`,
                `transaction`.`transaction_category`,
                `transaction`.`transaction_type`,
                `transaction`.`payment_due_date`,
                `transaction`.`payment_disbursement`,
                `transaction`.`contact_id`, 
                `contact`.`contact_name`
            FROM 
                (SELECT
                    `payment_transaction`.`transaction_code`,
                    `payment_transaction`.`debit`,
                    `payment_transaction`.`credit`,
                    `payment_transaction`.`currency`,
                    `payment_transaction`.`transaction_name`,
                    `payment_transaction`.`transaction_category`,
                    `payment_transaction`.`transaction_type`,
                    `payment_transaction`.`payment_due_date`,
                    `payment_transaction`.`payment_disbursement`, 
                    IF((`sales_order`.`transaction_number` != ''), `sales_order`.`customer_id`, `purchase_order`.`supplier_id`) AS `contact_id`
                FROM
                    `payment_transaction`
                    LEFT JOIN
                        `sales_order` ON `sales_order`.`transaction_number` = `payment_transaction`.`transaction_code`
                    LEFT JOIN
                        `purchase_order` ON `purchase_order`.`transaction_number` = `payment_transaction`.`transaction_code`
                WHERE
                    `payment_transaction`.`status` = -1) AS `transaction`
            LEFT JOIN
                `contact` ON `contact`.`contact_id` = `transaction`.`contact_id`
            ORDER BY `transaction`.`payment_due_date` ASC";
            $cash_flow = GenericModel::rawSelect($sql);

            $sql = "
            SELECT
                SUM(`debit`) as `total_debit`,
                SUM(`credit`) as `total_credit`,
                `currency`
            FROM 
                `payment_transaction`
            WHERE
                 `status` = 1 GROUP BY `currency`";

            $saldo = GenericModel::rawSelect($sql);

            $header_script = '<link rel="stylesheet" href="' . Config::get('URL') . 'bootstrap-3.3.7/css/excel-2007.css" media="screen"/>';

            $this->View->render('bukubesar/cash_flow',
                  array(
                'title' => 'Buku Besar',
                'header_script' => $header_script,
                'activelink1' => 'finance',
                'activelink2' => 'cash flow',
                'cash_flow' => $cash_flow,
                'saldo' => $saldo,
                'currency_rate' => FormaterModel::currencyRateBI(),
                )
            );
    }

    public function inventoryValuation()
    {
            Auth::checkPermission('director,finance');

            $raw_mat = "
                SELECT
                    `material_list`.`material_code`,
                    `material_list`.`material_name`,
                    `material_list`.`unit`,
                    `material_list`.`selling_price`,
                    `material_list`.`selling_currency`,
                    `material_list`.`purchase_price`,
                    `material_list`.`purchase_currency`,
                    `material_list`.`material_type`,
                    SUM(`material_list_in`.`quantity_stock`) AS `quantity_stock`
                FROM
                    `material_list_in`
                LEFT JOIN
                    `material_list`
                        ON
                    `material_list`.`material_code` = `material_list_in`.`material_code`
                WHERE
                    `material_list`.`material_type` = 1
                GROUP BY
                    `material_list_in`.`material_code`
                ORDER BY
                    `material_list`.`material_name` DESC
                     ";
            $raw_mat = GenericModel::rawSelect($raw_mat);

            $wip = "
                SELECT
                    `material_list`.`material_code`,
                    `material_list`.`material_name`,
                    `material_list`.`unit`,
                    `material_list`.`selling_price`,
                    `material_list`.`selling_currency`,
                    `material_list`.`purchase_price`,
                    `material_list`.`purchase_currency`,
                    `material_list`.`material_type`,
                    SUM(`material_list_in`.`quantity_stock`) AS `quantity_stock`
                FROM
                    `material_list_in`
                LEFT JOIN
                    `material_list`
                        ON
                    `material_list`.`material_code` = `material_list_in`.`material_code`
                WHERE
                    `material_list`.`material_type` = 2
                GROUP BY
                    `material_list_in`.`material_code`
                ORDER BY
                    `material_list`.`material_name` DESC
                     ";
            $wip = GenericModel::rawSelect($wip);

            $finish_goods = "
                SELECT
                    `material_list`.`material_code`,
                    `material_list`.`material_name`,
                    `material_list`.`unit`,
                    `material_list`.`selling_price`,
                    `material_list`.`selling_currency`,
                    `material_list`.`purchase_price`,
                    `material_list`.`purchase_currency`,
                    `material_list`.`material_type`,
                    SUM(`material_list_in`.`quantity_stock`) AS `quantity_stock`
                FROM
                    `material_list_in`
                LEFT JOIN
                    `material_list`
                        ON
                    `material_list`.`material_code` = `material_list_in`.`material_code`
                WHERE
                    `material_list`.`material_type` = 3
                GROUP BY
                    `material_list_in`.`material_code`
                ORDER BY
                    `material_list`.`material_name` DESC
                     ";
            $finish_goods = GenericModel::rawSelect($finish_goods);

            $trading_goods = "
                SELECT
                    `material_list`.`material_code`,
                    `material_list`.`material_name`,
                    `material_list`.`unit`,
                    `material_list`.`selling_price`,
                    `material_list`.`selling_currency`,
                    `material_list`.`purchase_price`,
                    `material_list`.`purchase_currency`,
                    `material_list`.`material_type`,
                    SUM(`material_list_in`.`quantity_stock`) AS `quantity_stock`
                FROM
                    `material_list_in`
                LEFT JOIN
                    `material_list`
                        ON
                    `material_list`.`material_code` = `material_list_in`.`material_code`
                WHERE
                    `material_list`.`material_type` = 4
                GROUP BY
                    `material_list_in`.`material_code`
                ORDER BY
                    `material_list`.`material_name` DESC
                     ";
            $trading_goods = GenericModel::rawSelect($trading_goods);

            $tools = "
                SELECT
                    `material_list`.`material_code`,
                    `material_list`.`material_name`,
                    `material_list`.`unit`,
                    `material_list`.`selling_price`,
                    `material_list`.`selling_currency`,
                    `material_list`.`purchase_price`,
                    `material_list`.`purchase_currency`,
                    `material_list`.`material_type`,
                    SUM(`material_list_in`.`quantity_stock`) AS `quantity_stock`
                FROM
                    `material_list_in`
                LEFT JOIN
                    `material_list`
                        ON
                    `material_list`.`material_code` = `material_list_in`.`material_code`
                WHERE
                    `material_list`.`material_type` = 6
                GROUP BY
                    `material_list_in`.`material_code`
                ORDER BY
                    `material_list`.`material_name` DESC
                     ";
            $tools = GenericModel::rawSelect($tools);

            $operating_supplies = "
                SELECT
                    `material_list`.`material_code`,
                    `material_list`.`material_name`,
                    `material_list`.`unit`,
                    `material_list`.`selling_price`,
                    `material_list`.`selling_currency`,
                    `material_list`.`purchase_price`,
                    `material_list`.`purchase_currency`,
                    `material_list`.`material_type`,
                    SUM(`material_list_in`.`quantity_stock`) AS `quantity_stock`
                FROM
                    `material_list_in`
                LEFT JOIN
                    `material_list`
                        ON
                    `material_list`.`material_code` = `material_list_in`.`material_code`
                WHERE
                    `material_list`.`material_type` = 7
                GROUP BY
                    `material_list_in`.`material_code`
                ORDER BY
                    `material_list`.`material_name` DESC
                     ";
            $operating_supplies = GenericModel::rawSelect($operating_supplies);

            $asset = "
                SELECT
                    `material_list`.`material_code`,
                    `material_list`.`material_name`,
                    `material_list`.`unit`,
                    `material_list`.`selling_price`,
                    `material_list`.`selling_currency`,
                    `material_list`.`purchase_price`,
                    `material_list`.`purchase_currency`,
                    `material_list`.`material_type`,
                    SUM(`material_list_in`.`quantity_stock`) AS `quantity_stock`
                FROM
                    `material_list_in`
                LEFT JOIN
                    `material_list`
                        ON
                    `material_list`.`material_code` = `material_list_in`.`material_code`
                WHERE
                    `material_list`.`material_type` = 10
                GROUP BY
                    `material_list_in`.`material_code`
                ORDER BY
                    `material_list`.`material_name` DESC
                     ";
            $asset = GenericModel::rawSelect($asset);

            $header_script = '<link rel="stylesheet" href="' . Config::get('URL') . 'bootstrap-3.3.7/css/excel-2007.css" media="screen"/>';

            $this->View->render('bukubesar/inventory_valuation',
                  array(
                'title' => 'Value Stock Bahan Baku',
                'header_script' => $header_script,
                'activelink1' => 'finance',
                'activelink2' => 'value stock bahan baku',
                'raw_mat' => $raw_mat,
                'wip' => $wip,
                'finish_goods' => $finish_goods,
                'trading_goods' => $trading_goods,
                'tools' => $tools,
                'operating_supplies' => $operating_supplies,
                'asset' => $asset,
                'currency_rate' => FormaterModel::currencyRateBI(),
                )
            );
    }

    public function paymentDisbursementNotification()
    {
            Auth::checkPermission('director,finance');
            $sql = "
            SELECT
                `transaction`.`transaction_code`,
                `transaction`.`debit`,
                `transaction`.`credit`,
                `transaction`.`transaction_name`,
                `transaction`.`transaction_category`,
                `transaction`.`transaction_type`,
                `transaction`.`created_timestamp`,
                `transaction`.`payment_due_date`,
                `transaction`.`payment_disbursement`,
                `transaction`.`contact_id`, 
                `contact`.`contact_name`
            FROM 
                (SELECT
                    `payment_transaction`.`transaction_code`,
                    `payment_transaction`.`debit`,
                    `payment_transaction`.`credit`,
                    `payment_transaction`.`transaction_name`,
                    `payment_transaction`.`transaction_category`,
                    `payment_transaction`.`transaction_type`,
                    `payment_transaction`.`created_timestamp`,
                    `payment_transaction`.`payment_due_date`,
                    `payment_transaction`.`payment_disbursement`, 
                    IF((`sales_order`.`transaction_number` != ''), `sales_order`.`customer_id`, `purchase_order`.`supplier_id`) AS `contact_id`
                FROM
                    `payment_transaction`
                    LEFT JOIN
                        `sales_order` ON `sales_order`.`transaction_number` = `payment_transaction`.`transaction_code`
                    LEFT JOIN
                        `purchase_order` ON `purchase_order`.`transaction_number` = `payment_transaction`.`transaction_code`
                WHERE
                    `payment_transaction`.`status` = -1 AND `payment_transaction`.`payment_due_date` IN (CURDATE(), CURDATE() + INTERVAL 1 DAY)
                ) AS `transaction`
            LEFT JOIN
                `contact` ON `contact`.`contact_id` = `transaction`.`contact_id`
            ORDER BY `transaction`.`payment_due_date` ASC";

            $cash_flow = GenericModel::rawSelect($sql);

            $header_script = '<link rel="stylesheet" href="' . Config::get('URL') . 'bootstrap-3.3.7/css/excel-2007.css" media="screen"/>';

            $this->View->render('bukubesar/payment_disbursement_notification',
                  array(
                'title' => 'Notifikasi Pencairan Pembayaran',
                'header_script' => $header_script,
                'activelink1' => 'finance',
                'activelink2' => 'notification payment disbursement',
                'cash_flow' => $cash_flow,
                )
            );
    }

    public function allTransaction($start_date = null, $end_date = null)
    {
        Auth::checkPermission('director,finance');
        if (Request::post('change_date') == 'ok') { // user input change date
            Redirect::to('bukuBesar/allTransaction/' . Request::post('start_date') . '/' . Request::post('end_date'));
            exit;
        }

        // if null given, show today transaction
        if ($start_date == null AND $end_date == null) {

            $sql = "
            SELECT
                `transaction`.`uid`,
                `transaction`.`transaction_code`,
                `transaction`.`debit`,
                `transaction`.`credit`,
                `transaction`.`transaction_name`,
                `transaction`.`transaction_category`,
                `transaction`.`transaction_type`,
                `transaction`.`created_timestamp`,
                `transaction`.`payment_due_date`,
                `transaction`.`payment_disbursement`,
                `transaction`.`contact_id`, 
                `contact`.`contact_name`
            FROM 
                (SELECT
                    `payment_transaction`.`uid`,
                    `payment_transaction`.`transaction_code`,
                    `payment_transaction`.`debit`,
                    `payment_transaction`.`credit`,
                    `payment_transaction`.`transaction_name`,
                    `payment_transaction`.`transaction_category`,
                    `payment_transaction`.`transaction_type`,
                    `payment_transaction`.`created_timestamp`,
                    `payment_transaction`.`payment_due_date`,
                    `payment_transaction`.`payment_disbursement`, 
                    IF((`sales_order`.`transaction_number` != ''), `sales_order`.`customer_id`, `purchase_order`.`supplier_id`) AS `contact_id`
                FROM
                    `payment_transaction`
                    LEFT JOIN
                        `sales_order` ON `sales_order`.`transaction_number` = `payment_transaction`.`transaction_code`
                    LEFT JOIN
                        `purchase_order` ON `purchase_order`.`transaction_number` = `payment_transaction`.`transaction_code`) AS `transaction`
            LEFT JOIN
                `contact` ON `contact`.`contact_id` = `transaction`.`contact_id`
            ORDER BY `transaction`.`created_timestamp` ASC";

            $allTransaction = GenericModel::rawSelect($sql);
            $title = 'Laporan Semua Transaksi Keuangan';
        } else {

            $sql = "
            SELECT
                `transaction`.`uid`,
                `transaction`.`transaction_code`,
                `transaction`.`debit`,
                `transaction`.`credit`,
                `transaction`.`transaction_name`,
                `transaction`.`transaction_category`,
                `transaction`.`transaction_type`,
                `transaction`.`created_timestamp`,
                `transaction`.`payment_due_date`,
                `transaction`.`payment_disbursement`,
                `transaction`.`contact_id`, 
                `contact`.`contact_name`
            FROM 
                (SELECT
                    `payment_transaction`.`uid`,
                    `payment_transaction`.`transaction_code`,
                    `payment_transaction`.`debit`,
                    `payment_transaction`.`credit`,
                    `payment_transaction`.`transaction_name`,
                    `payment_transaction`.`transaction_category`,
                    `payment_transaction`.`transaction_type`,
                    `payment_transaction`.`created_timestamp`,
                    `payment_transaction`.`payment_due_date`,
                    `payment_transaction`.`payment_disbursement`, 
                    IF((`sales_order`.`transaction_number` != ''), `sales_order`.`customer_id`, `purchase_order`.`supplier_id`) AS `contact_id`
                FROM
                    `payment_transaction`
                    LEFT JOIN
                        `sales_order` ON `sales_order`.`transaction_number` = `payment_transaction`.`transaction_code`
                    LEFT JOIN
                        `purchase_order` ON `purchase_order`.`transaction_number` = `payment_transaction`.`transaction_code`
                WHERE
                    `payment_transaction`.`created_timestamp` BETWEEN '$start_date' AND '$end_date') AS `transaction`
            LEFT JOIN
                `contact` ON `contact`.`contact_id` = `transaction`.`contact_id`
            ORDER BY `transaction`.`created_timestamp` ASC";

            $allTransaction = GenericModel::rawSelect($sql);
            $title = 'Laporan Transaksi Keuangan Dari Tanggal ' . date("d F Y", strtotime($start_date)) . ' - ' . date("d F Y", strtotime($end_date));
        }

        $header_script = '<link rel="stylesheet" href="' . Config::get('URL') . 'bootstrap-3.3.7/css/excel-2007.css" media="screen"/><link rel="stylesheet" href="' . Config::get('URL') . 'bootstrap-3.3.7/css/datepicker.css" media="screen"/>';
        $footer_script = '<script src="' . Config::get('URL') . 'bootstrap-3.3.7/js/bootstrap-datepicker.js"></script>
        <script>
        $(".datepicker").datepicker(); //date picker
        </script>';

            $this->View->render('bukubesar/allTransaction',
                  array(
                'title' => 'Buku Besar',
                'header_script' => $header_script,
                'footer_script' => $footer_script,
                'activelink1' => 'finance',
                'activelink2' => 'all transaction',
                'allTransaction' => $allTransaction
                )
            );

    }

    public function confirmPayment() {
        $where = ' WHERE 1 ';
        if (isset($_GET['transaction_number']) AND !empty($_GET['transaction_number'])) {
            $transaction_number = urldecode(Request::get('transaction_number'));
            $where .= " AND `payment_transaction`.`transaction_code` = '{$transaction_number}' ";
        } else {
            $where .= " AND `payment_transaction`.`status` != 1 ";
        }

        $sql = "
            SELECT
                `transaction`.`uid`,
                `transaction`.`transaction_code`,
                `transaction`.`debit`,
                `transaction`.`credit`,
                `transaction`.`currency`,
                `transaction`.`transaction_name`,
                `transaction`.`transaction_category`,
                `transaction`.`transaction_type`,
                `transaction`.`payment_due_date`,
                `transaction`.`payment_disbursement`,
                `transaction`.`invoice_number`,
                `transaction`.`invoice_date`,
                `transaction`.`status`,
                `transaction`.`created_timestamp`,
                `transaction`.`contact_id`,
                `transaction`.`note`,
                `transaction`.`ppn_credit`,
                `transaction`.`ppn_debit`,
                `contact`.`contact_name`
            FROM 
                (SELECT
                    `payment_transaction`.`uid`,
                    `payment_transaction`.`transaction_code`,
                    `payment_transaction`.`debit`,
                    `payment_transaction`.`credit`,
                    `payment_transaction`.`currency`,
                    `payment_transaction`.`transaction_name`,
                    `payment_transaction`.`transaction_category`,
                    `payment_transaction`.`transaction_type`,
                    `payment_transaction`.`payment_due_date`,
                    `payment_transaction`.`payment_disbursement`,
                    `payment_transaction`.`invoice_number`,
                    `payment_transaction`.`invoice_date`,
                    `payment_transaction`.`status`,
                    `payment_transaction`.`created_timestamp`,
                    `payment_transaction`.`note`,
                    IF((`sales_order`.`transaction_number` != ''), `sales_order`.`customer_id`, `purchase_order`.`supplier_id`) AS `contact_id`,
                    `tax`.`credit` AS `ppn_credit`,
                    `tax`.`debit` AS `ppn_debit`
                FROM
                    `payment_transaction`
                    LEFT JOIN
                        `sales_order` ON `sales_order`.`transaction_number` = `payment_transaction`.`transaction_code`
                    LEFT JOIN
                        `purchase_order` ON `purchase_order`.`transaction_number` = `payment_transaction`.`transaction_code`
                    LEFT JOIN
                        `tax`
                        ON
                        `tax`.`uid` = `payment_transaction`.`uid`

                    {$where}
                )  AS `transaction`
            LEFT JOIN
                `contact` ON `contact`.`contact_id` = `transaction`.`contact_id`
            ORDER BY `transaction`.`payment_due_date` ASC";

        $header_script = '<link rel="stylesheet" href="' . Config::get('URL') . 'bootstrap-3.3.7/css/excel-2007.css" media="screen"/><link rel="stylesheet" href="' . Config::get('URL') . 'bootstrap-3.3.7/css/datepicker.css" media="screen"/>';
        $footer_script = '<script src="' . Config::get('URL') . 'bootstrap-3.3.7/js/bootstrap-datepicker.js"></script>
        <script>
        $(".datepicker").datepicker(); //date picker

        //Initiate Modal
        $("#edit-payment").on("show.bs.modal", function (event) {
            var button = $(event.relatedTarget) // Button that triggered the modal
            var no = button.data("no-id") // Extract info from data-* attributes
            var uid = button.data("uid") // Extract info from data-* attributes
            var transaction_reference = document.getElementById("transaction-reference-" + no).textContent
            var note = document.getElementById("note-" + no).textContent
            var debit = document.getElementById("debit-" + no).textContent
            var credit = document.getElementById("credit-" + no).textContent
            var ppn = document.getElementById("ppn-" + no).textContent
            var currency = document.getElementById("currency-" + no).textContent
            var invoice_number = document.getElementById("invoice-number-" + no).textContent
            var invoice_date = document.getElementById("invoice-date-" + no).textContent
            var payment_due_date = document.getElementById("payment-due-date-" + no).textContent

            // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
            // Update the modal"s content. We"ll use jQuery here, but you could use a data binding library or other methods instead.
            var modal = $(this)
            modal.find(".modal-body #edit-uid").val(uid)
            modal.find(".modal-body #edit-transaction-reference").val(transaction_reference)
            modal.find(".modal-body #edit-note").val(note)
            modal.find(".modal-body #edit-debit").val(debit)
            modal.find(".modal-body #edit-credit").val(credit)
            modal.find(".modal-body #edit-ppn").val(ppn)
            modal.find(".modal-body #edit-currency").val(currency)
            modal.find(".modal-body #edit-invoice-number").val(invoice_number)
            modal.find(".modal-body #edit-invoice-date").val(invoice_date)
            modal.find(".modal-body #edit-payment-due-date").val(payment_due_date)

          });
        </script>';

        $this->View->render('bukubesar/confirm_payment',
            array(
                'title' => 'List Pembayaran dan Konfirmasi',
                'activelink1' => 'finance',
                'activelink2' => 'confirm payment',
                'header_script' => $header_script,
                'footer_script' => $footer_script,
                'unconfirmed_payment' => GenericModel::rawSelect($sql),
            )
        );
    }

    public function confirmPaymentAction()
    {
        Auth::checkPermission('director,finance');
        //check apakah tanggal pembayaran tidak kosong dan sesuai format yyyy-mm-dd
        $disbursement_date = $_POST['payment_disbursement'];
        if (preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$disbursement_date)) {
            $uid = Request::post('uid');
            $update = array(
                'status' => 1,
                'payment_disbursement' => date('Y-m-d H:i:s',strtotime(Request::post('payment_disbursement'))),
                'modifier_id'    => SESSION::get('uid'),
                'modified_timestamp' => date("Y-m-d H:i:s")
                );
            GenericModel::update('payment_transaction', $update, "`uid` = '$uid'");

            $update = array(
                'status' => 1
                );
            GenericModel::update('tax', $update, "`uid` = '$uid'", false);
        } else {
            echo 'Tanggal Pencairan Tidak Belum Diisi Atau Format Pengisian Salah';
        }
    
        // Send Status insert to front end
        $feedback_positive = Session::get('feedback_positive');
        $feedback_negative = Session::get('feedback_negative');
        // echo out positive messages
        if (isset($feedback_positive)) { echo 'SUKSES, ' . count($feedback_positive) . ' transaksi pembayaran berhasil diconfirm';}
        // echo out negative messages
        if (isset($feedback_negative)) {echo 'GAGAL!, ' . count($feedback_positive) . ' transaksi tidak berhasil diconfirm';}
        // RESET counter feedback to unconfuse user
        Session::set('feedback_positive', null);
        Session::set('feedback_negative', null);
    }

    public function updatePaymentAction()
    {
        Auth::checkPermission('director,finance,purchasing');
        $uid = Request::post('uid');
        $transaction_reference = Request::post('transaction_reference');
        $debit = FormaterModel::floatNumber($_POST['debit']);
        $credit = FormaterModel::floatNumber($_POST['credit']);
        $payment_due_date = date('Y-m-d', strtotime($_POST['payment_due_date']));
        $invoice_date = date('Y-m-d', strtotime($_POST['invoice_date']));
        $invoice_number = Request::post('invoice_number');
        $currency = Request::post('currency');
        $note = Request::post('note');

        $update = array(
                'debit' => $debit,
                'credit' => $credit,
                'payment_due_date' => $payment_due_date,
                'invoice_date' => $invoice_date,
                'invoice_number' => $invoice_number,
                'currency' => $currency,
                'note' => $note,

            );
        GenericModel::update('payment_transaction', $update, "`uid` = '$uid'");

        if ($debit > 0) {
            $ppn_debit = FormaterModel::floatNumber($_POST['ppn']);
            $ppn_credit = 0;
        }

        if ($credit > 0) {
            $ppn_debit = 0;
            $ppn_credit = FormaterModel::floatNumber($_POST['ppn']);
        }

        //delete ppn yang uidnya ngelink ke uid pembayaran
        GenericModel::remove('tax', 'uid', $uid, false);
        $insert = array(
            'uid'    => $uid,
            'transaction_reference' => $transaction_reference,
            'tax_type' => 'ppn',
            'debit' => $ppn_debit,
            'credit' => $ppn_credit,
            'status' => -1,
            'creator_id'    => SESSION::get('uid')
        );
        GenericModel::insert('tax', $insert);
    
        Redirect::to(Request::post('forward'));
    }

    public function deletePaymentAction()
    {
        Auth::checkPermission('director,finance,purchasing');
        $uid = Request::post('uid');
        GenericModel::remove('payment_transaction', 'uid', $uid);
        GenericModel::remove('tax', 'uid', $uid, false);
        
    
        // Send Status insert to front end
        $feedback_positive = Session::get('feedback_positive');
        $feedback_negative = Session::get('feedback_negative');
        // echo out positive messages
        if (isset($feedback_positive)) { echo 'SUKSES, ' . count($feedback_positive) . ' transaksi pembayaran berhasil dihapus';}
        // echo out negative messages
        if (isset($feedback_negative)) {echo 'GAGAL!, ' . count($feedback_positive) . ' transaksi tidak berhasil dihapus';}
        // RESET counter feedback to unconfuse user
        Session::set('feedback_positive', null);
        Session::set('feedback_negative', null);

    }

    public function printDebit($uid)
    {
        $sql = "
            SELECT
                `transaction`.`uid`,
                `transaction`.`transaction_code`,
                `transaction`.`debit`,
                `transaction`.`credit`,
                `transaction`.`transaction_name`,
                `transaction`.`transaction_category`,
                `transaction`.`transaction_type`,
                `transaction`.`created_timestamp`,
                `transaction`.`payment_due_date`,
                `transaction`.`payment_disbursement`,
                `transaction`.`contact_id`, 
                `contact`.`contact_name`
            FROM 
                (SELECT
                    `payment_transaction`.`uid`,
                    `payment_transaction`.`transaction_code`,
                    `payment_transaction`.`debit`,
                    `payment_transaction`.`credit`,
                    `payment_transaction`.`transaction_name`,
                    `payment_transaction`.`transaction_category`,
                    `payment_transaction`.`transaction_type`,
                    `payment_transaction`.`created_timestamp`,
                    `payment_transaction`.`payment_due_date`,
                    `payment_transaction`.`payment_disbursement`, 
                    IF((`sales_order`.`transaction_number` != ''), `sales_order`.`customer_id`, `purchase_order`.`supplier_id`) AS `contact_id`
                FROM
                    `payment_transaction`
                    LEFT JOIN
                        `sales_order` ON `sales_order`.`transaction_number` = `payment_transaction`.`transaction_code`
                    LEFT JOIN
                        `purchase_order` ON `purchase_order`.`transaction_number` = `payment_transaction`.`transaction_code`
                WHERE
                    `payment_transaction`.`uid` = '{$uid}') AS `transaction`
            LEFT JOIN
                `contact` ON `contact`.`contact_id` = `transaction`.`contact_id`
            ";

            $transaction = GenericModel::rawSelect($sql);

        $this->View->renderFileOnly('bukubesar/print_debit',
                array(
                'title' => 'Print Kas Keluar',
                'transaction' => $transaction,
                'company' => GenericModel::getAll('`system_preference`', "`category` = 'company_identification' ORDER BY `item_name` ASC", '`value`, `item_name`')
                )
            );

    }

    public function printCredit($uid)
    {
        $sql = "
            SELECT
                `transaction`.`uid`,
                `transaction`.`transaction_code`,
                `transaction`.`debit`,
                `transaction`.`credit`,
                `transaction`.`transaction_name`,
                `transaction`.`transaction_category`,
                `transaction`.`transaction_type`,
                `transaction`.`created_timestamp`,
                `transaction`.`payment_due_date`,
                `transaction`.`payment_disbursement`,
                `transaction`.`contact_id`, 
                `contact`.`contact_name`
            FROM 
                (SELECT
                    `payment_transaction`.`uid`,
                    `payment_transaction`.`transaction_code`,
                    `payment_transaction`.`debit`,
                    `payment_transaction`.`credit`,
                    `payment_transaction`.`transaction_name`,
                    `payment_transaction`.`transaction_category`,
                    `payment_transaction`.`transaction_type`,
                    `payment_transaction`.`created_timestamp`,
                    `payment_transaction`.`payment_due_date`,
                    `payment_transaction`.`payment_disbursement`, 
                    IF((`sales_order`.`transaction_number` != ''), `sales_order`.`customer_id`, `purchase_order`.`supplier_id`) AS `contact_id`
                FROM
                    `payment_transaction`
                    LEFT JOIN
                        `sales_order` ON `sales_order`.`transaction_number` = `payment_transaction`.`transaction_code`
                    LEFT JOIN
                        `purchase_order` ON `purchase_order`.`transaction_number` = `payment_transaction`.`transaction_code`
                WHERE
                    `payment_transaction`.`uid` = '{$uid}') AS `transaction`
            LEFT JOIN
                `contact` ON `contact`.`contact_id` = `transaction`.`contact_id`
            ";

            $transaction = GenericModel::rawSelect($sql);

        $this->View->renderFileOnly('bukubesar/print_credit',
                array(
                'title' => 'Print Kas Masuk',
                'transaction' => $transaction,
                'company' => GenericModel::getAll('`system_preference`', "`category` = 'company_identification' ORDER BY `item_name` ASC", '`value`, `item_name`')
                )
            );
    }

}
