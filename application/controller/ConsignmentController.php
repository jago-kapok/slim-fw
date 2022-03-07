<?php

/*
* POS (Point of Sales) aka Kasir
*/
class ConsignmentController extends Controller
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
    }

    /*
     * Konsinyasi, pembayaran tidak harus cash (pake piutang) jadi wajib pake nama customer dan rencana tanggal pembayaran
     * Cocon untuk produk yang pake reproduksi/repacking dan sistem titip di toko
     */
    public function Consignment()
    {
        $this->View->renderFileOnly('consignment/consignment', array(
                'product_list' => GenericModel::getAll('material_list', "(`material_type` = 3 OR `material_type` = 0)AND `is_deleted` = 0 ORDER By `material_category` ASC, `material_name` ASC", " `material_code`, `material_name`, `selling_price`, `material_category`"),
                'customer_list' => GenericModel::getAll('contact', "`is_deleted` = 0", "`contact_id`, `contact_name`"),

                )
        );
    }

    public function saveConsignment()
    {
        //make transaction
        $transaction = ConsignmentModel::consignmentTransaction();

        if (!empty($transaction)) {
        //echo print to user/kasir
          echo Config::get('URL') . 'kasir/printKasir/?transaction_number=' . urlencode($transaction);
        }

        // RESET counter feedback to unconfuse user
        Session::set('feedback_positive', null);
        Session::set('feedback_negative', null);

    }

    public function laporan($page = 1, $limit = 50)
    {
        $offset = ($page > 1) ? ($page - 1) * $limit : 0;
        $sql_group = "
            SELECT
                `sales_order`.`transaction_number`,
                `sales_order`.`created_timestamp`,
                `sales_order`.`customer_id`,
                `sales_order`.`status`,
                `sales_order`.`discount_total`,
                `sales_order`.`customer_table_number`,
                `sales_order_list`.`material_code`,
                GROUP_CONCAT(`material_list`.`material_name` SEPARATOR '-, -') as `material_name`,
                GROUP_CONCAT(`sales_order_list`.`quantity` SEPARATOR '-, -') as `quantity`,
                GROUP_CONCAT(`sales_order_list`.`selling_price` SEPARATOR '-, -') as `selling_price`,
                GROUP_CONCAT(`sales_order_list`.`tax_ppn` SEPARATOR '-, -') as `tax_ppn`,
                GROUP_CONCAT(`sales_order_list`.`tax_pph` SEPARATOR '-, -') as `tax_pph`,
                `users`.`full_name`,
                `contact`.`contact_name`
            FROM
                `sales_order`
            LEFT JOIN
                `sales_order_list` AS `sales_order_list` ON `sales_order_list`.`transaction_number` = `sales_order`.`transaction_number`
            LEFT JOIN
                `material_list` AS `material_list` ON `sales_order_list`.`material_code` = `material_list`.`material_code`
            
            LEFT JOIN
                `contact` AS `contact` ON `contact`.`contact_id` = `sales_order`.`customer_id`
            LEFT JOIN
                `users` AS `users` ON `users`.`uid` = `sales_order`.`creator_id`
            WHERE
                `sales_order`.`status` >= 0 AND `sales_channel` = 'consignment'
            GROUP BY 
                 `sales_order`.`transaction_number`
            ORDER BY
                `sales_order`.`created_timestamp` DESC
            LIMIT
                $offset, $limit";
        //Use Left karena penjualan kasir langsung tanpa input customer, kalo pake join data customer_id gak ada di table contact!

        //For pagination
        $total_record = GenericModel::rowCount('`sales_order`', "`sales_order`.`status` >= 0", '`transaction_number`');


        $header_script = '<link rel="stylesheet" href="' . Config::get('URL') . 'bootstrap-3.3.7/css/excel-2007.css" media="screen"/>';
        $this->View->render('consignment/laporan',
            array(
                'header_script' => $header_script,
                'title' => 'Daftar Penjualan Kasir By Order',
                'page' => $page,
                'limit' => $limit,
                'activelink1' => 'penjualan',
                'activelink2' => 'penjualan consignment',
                'activelink3' => 'penjualan consignment laporan',
                'activelink4' => 'penjualan consignment laporan by order',
                'transaction_group' => GenericModel::rawSelect($sql_group),
                'pagination' => FormaterModel::pagination('kasir/laporan', $total_record, $page, $limit)
            )
        );
    }

    public function reportByDate($start_date = null, $end_date = null)
    {
        if (Request::post('change_date') == 'ok') { // user input change date
            Redirect::to('consignment/reportByDate/' . Request::post('start_date') . '/' . Request::post('end_date'));
            exit;
        }

        // if null given, show today transaction
        if ($start_date == null AND $end_date == null) {
            $start_date = $end_date = date('Y-m-d');
            $title = 'Daftar Penjualan Kasir Per Hari Ini';
        } else {
            $title = 'Daftar Penjualan Kasir Dari Tanggal ' . date("d F Y", strtotime($start_date)) . ' - ' . date("d F Y", strtotime($end_date));
        }

        $sql_group = "
                SELECT
                    `sales_order`.`transaction_number`,
                    `sales_order`.`created_timestamp`,
                    `sales_order`.`customer_id`,
                    `sales_order`.`status`,
                    `sales_order`.`customer_name`,
                    `sales_order`.`customer_table_number`,
                    `sales_order`.`discount_total`,
                    `sales_order_list`.`material_code`,
                    GROUP_CONCAT(`material_list`.`material_name` SEPARATOR '-, -') as `material_name`,
                    GROUP_CONCAT(`sales_order_list`.`quantity` SEPARATOR '-, -') as `quantity`,
                    GROUP_CONCAT(`sales_order_list`.`selling_price` SEPARATOR '-, -') as `selling_price`,
                    GROUP_CONCAT(`sales_order_list`.`tax_ppn` SEPARATOR '-, -') as `tax_ppn`,
                    GROUP_CONCAT(`sales_order_list`.`tax_pph` SEPARATOR '-, -') as `tax_pph`,
                    `users`.`full_name`,
                    `contact`.`contact_name`
                FROM
                    `sales_order`
                LEFT JOIN
                    `sales_order_list` AS `sales_order_list` ON `sales_order_list`.`transaction_number` = `sales_order`.`transaction_number`
                LEFT JOIN
                    `material_list` AS `material_list` ON `sales_order_list`.`material_code` = `material_list`.`material_code`
                
                LEFT JOIN
                    `contact` AS `contact` ON `contact`.`contact_id` = `sales_order`.`customer_id`
                LEFT JOIN
                    `users` AS `users` ON `users`.`uid` = `sales_order`.`creator_id`
                WHERE
                    `sales_channel` = 'consignment' AND (`sales_order`.`created_timestamp` BETWEEN '{$start_date} 00:00:00.000000' AND '{$end_date} 23:59:59.999999')
                GROUP BY
                     `sales_order`.`transaction_number`
                ORDER BY
                    `sales_order`.`created_timestamp` DESC";

        $header_script = '<link rel="stylesheet" href="' . Config::get('URL') . 'bootstrap-3.3.7/css/datepicker.css" media="screen"/>';
        $footer_script = '<script src="' . Config::get('URL') . 'bootstrap-3.3.7/js/bootstrap-datepicker.js"></script>
        <script>
        $(".datepicker").datepicker(); //date picker
        </script>';

        $this->View->render('consignment/report_by_date',
            array(
                'title' => $title,
                'header_script' => $header_script,
                'footer_script' => $footer_script,
                'activelink1' => 'penjualan',
                'activelink2' => 'penjualan consignment',
                'activelink3' => 'penjualan consignment laporan',
                'activelink4' => 'penjualan consignment laporan by tanggal',
                'transaction_group' => GenericModel::rawSelect($sql_group),
            )
        );
    }

    public function deleteSales()
    {
        if (Auth::isPermissioned('director', 899)) {
            $transaction_number = urldecode(Request::get('transaction_number'));
            GenericModel::remove('sales_order', 'transaction_number', $transaction_number, false); //false for silent feedback
            GenericModel::remove('sales_order_list', 'transaction_number', $transaction_number, false); //false for silent feedback
            GenericModel::remove('payment_transaction', 'transaction_code', $transaction_number, false); //false for silent feedback
            Redirect::to(Request::get('forward'));
        } else {
            $transaction_number = urldecode(Request::get('transaction_number'));
            //send email to director
            $email = array();
            $email[] = 'edi.subakir@outlook.com';
            $email[] = Config::get('EMAIL_NOTIFICATION');

            $email_creator = SESSION::get('full_name');
            $email_subject = "Mencoba mendelete transaksi penjualan: " . $transaction_number . ' oleh ' . ucwords($email_creator);
            $body ='Klik link berikut untuk melihat detail transaksi ' .   Config::get('URL') . 'kasir/printKasir/?transaction_number=' . urlencode($transaction_number);
            $mail = new Mail;
            $mail_sent = $mail->sendMail($email, Config::get('EMAIL_VERIFICATION_FROM_EMAIL'),
                Config::get('EMAIL_VERIFICATION_FROM_NAME'), $email_subject, $body
            );
        }
    }

    public function detail()
    {
        $transaction_number = urldecode(Request::get('transaction_number'));
        $so_transaction = "SELECT
                    `sales_order_list`.`uid`,
                    `sales_order_list`.`material_code`,
                    `sales_order_list`.`quantity`,
                    `sales_order_list`.`selling_price`,
                    `sales_order_list`.`tax_ppn`,
                    `sales_order_list`.`tax_pph`,
                    `sales_order_list`.`delivery_request_date`,
                    `material_list`.`material_name`
                FROM
                    `sales_order_list`
                JOIN
                    `material_list` AS `material_list` ON `material_list`.`material_code` = `sales_order_list`.`material_code`
                WHERE
                    `sales_order_list`.`transaction_number` = '{$transaction_number}'";

        $so = "
                SELECT
                    `sales_order`.`transaction_number`,
                    `sales_order`.`created_timestamp`,
                    `sales_order`.`customer_id`,
                    `sales_order`.`note`,
                    `sales_order`.`feedback_note`,
                    `sales_order`.`status`,
                    `sales_order`.`price_net`,
                    `sales_order`.`price_gross`,
                    `sales_order`.`received_payment`,
                    `sales_order`.`payment_return`,
                    `sales_order`.`discount_total`,
                    `sales_order`.`customer_table_number`,
                    `sales_order`.`customer_name`,
                    `sales_order`.`edc_bank`,
                    `sales_order`.`edc_reference`,
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
                    `sales_order`
                LEFT JOIN
                    `contact` AS `contact` ON `contact`.`contact_id` = `sales_order`.`customer_id`
                LEFT JOIN
                    `users` AS `users` ON `users`.`uid` = `sales_order`.`creator_id`
                WHERE
                    `sales_order`.`transaction_number` = '{$transaction_number}'";

        $header_script = '<link rel="stylesheet" href="' . Config::get('URL') . 'bootstrap-3.3.7/css/excel-2007.css" media="screen"/><link rel="stylesheet" href="' . Config::get('URL') . 'bootstrap-3.3.7/css/datepicker.css" media="screen"/>';
        $footer_script = '<script src="' . Config::get('URL') . 'bootstrap-3.3.7/js/bootstrap-datepicker.js">
        </script>
        <script>
        $(".datepicker").datepicker(); //date picker

        </script>';
        $this->View->render('consignment/detail',
            array(
                'header_script' => $header_script,
                'footer_script' => $footer_script,
                'title' => 'Sales Order Number: ' . $transaction_number,
                'activelink1' => 'penjualan',
                'activelink2' => 'laporan kasir',
                'so' => GenericModel::rawSelect($so, false),
                'transaction' => GenericModel::rawSelect($so_transaction),
            )
        );
    }

    /**
     * print struk kasir
     */
    public function printOut() {
        $this->View->renderFileOnly('consignment/print_out', array(
            'product' => KasirModel::getTransaction(urldecode(Request::get('transaction_number'))),
            'company' => GenericModel::getAll('`system_preference`', "`category` = 'company_identification' OR `category` = 'customer_wifi' ORDER BY `item_name` ASC", '`value`, `item_name`')

        ));
    }
}
