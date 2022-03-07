<?php

/*
* POS (Point of Sales) aka Kasir
*/
class KasirController extends Controller
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

    public function index($page = 1, $limit = 20)
    {
        if (empty($_GET['find'])) {echo 'search only please'; exit;}
        $find = strtolower(Request::get('find'));
        $find = trim($find);
        $offset = ($page > 1) ? ($page - 1) * $limit : 0;

        $sql_group = "
            SELECT
                `sales_order`.`transaction_number`,
                `sales_order`.`feedback_note`,
                `sales_order`.`created_timestamp`,
                `sales_order`.`customer_id`,
                `sales_order_list`.`material_code`,
                GROUP_CONCAT(`material_list`.`material_name` SEPARATOR '-, -') as `material_name`,
                GROUP_CONCAT(`sales_order_list`.`quantity` SEPARATOR '-, -') as `quantity`,
                GROUP_CONCAT(`sales_order_list`.`selling_price` SEPARATOR '-, -') as `selling_price`,
                GROUP_CONCAT(`sales_order_list`.`tax_ppn` SEPARATOR '-, -') as `tax_ppn`,
                GROUP_CONCAT(`sales_order_list`.`tax_pph` SEPARATOR '-, -') as `tax_pph`,
                `users`.`full_name`
            FROM
                `sales_order`
            LEFT JOIN
                `sales_order_list` AS `sales_order_list` ON `sales_order_list`.`transaction_number` = `sales_order`.`transaction_number`
            LEFT JOIN
                `material_list` AS `material_list` ON `sales_order_list`.`material_code` = `material_list`.`material_code`
            LEFT JOIN
                `users` AS `users` ON `users`.`uid` = `sales_order`.`creator_id`
            
            WHERE
                `sales_order`.`transaction_number` = '{$find}' OR
                `sales_order`.`customer_id` = '{$find}' OR
                `sales_order_list`.`material_code` LIKE '%{$find}%' OR
                `material_list`.`material_name` = '{$find}'
            GROUP BY
                 `sales_order`.`transaction_number`
            LIMIT
                $offset, $limit";
        //For pagination
        $total_record = GenericModel::totalRow('`sales_order`','`transaction_number`');

        //For pagination
        $string_search = '?find=' . $find;
                   
        $header_script = '<link rel="stylesheet" href="' . Config::get('URL') . 'bootstrap-3.3.7/css/excel-2007.css" media="screen"/>';
        $this->View->render('kasir/index',
            array(
            'header_script' => $header_script,
            'title' => 'Search SO List',
            'page' => $page,
            'limit' => $limit,
            'activelink1' => 'penjualan',
            'activelink2' => 'penjualan kasir',
                'activelink3' => 'penjualan kasir laporan',
                'activelink4' => 'penjualan kasir laporan by order',
            'transaction_group' => GenericModel::rawSelect($sql_group),
            'pagination' => FormaterModel::pagination('kasir/index', $total_record, $page, $limit,$string_search)
            )
        );
    }
    
    public function dashboard($start_date = null, $end_date = null)
    {
        if (Request::post('change_date') == 'ok') { // user input change date
            Redirect::to('kasir/dashboard/' . Request::post('start_date') . '/' . Request::post('end_date'));
            exit;
        }
        
        $header_script = '<link rel="stylesheet" href="' . Config::get('URL') . 'bootstrap-3.3.7/css/datepicker.css" media="screen"/>';
        $footer_script = '<script src="' . Config::get('URL') . 'bootstrap-3.3.7/js/bootstrap-datepicker.js"></script>
        <script>
        $(".datepicker").datepicker(); //date picker
        </script>';
        
        // if null given, show today transaction
        if ($start_date == null AND $end_date == null) {
            $todays_years = date('Y-01-01 00:00:00');
            $value_penjualan_per_product = "
            SELECT
            `sales_order_list`.`material_code`,
            `material_list`.`material_name`,
            SUM(`sales_order_list`.`quantity` * `sales_order_list`.`selling_price`) as `selling_price`
            FROM
            `sales_order`
            JOIN
            `sales_order_list`
            ON
            `sales_order`.`transaction_number` = `sales_order_list`.`transaction_number`
            JOIN
            `material_list`
            ON
            `material_list`.`material_code` = `sales_order_list`.`material_code`
            WHERE
            `sales_order`.`sales_channel` = 'point of sales' AND `sales_order_list`.`created_timestamp` >= '{$todays_years}'
            GROUP BY
            `sales_order_list`.`material_code`
            ORDER BY
            `selling_price` DESC";
            
            $value_penjualan_per_category = "
            SELECT
            `material_list`.`material_category`,
            SUM(`sales_order_list`.`quantity` * `sales_order_list`.`selling_price`) as `selling_price`
            FROM
            `sales_order`
            JOIN
            `sales_order_list`
            ON
            `sales_order`.`transaction_number` = `sales_order_list`.`transaction_number`
            JOIN
            `material_list`
            ON
            `material_list`.`material_code` = `sales_order_list`.`material_code`
            WHERE
            `sales_order`.`sales_channel` = 'point of sales' AND `sales_order_list`.`created_timestamp` >= '{$todays_years}'
            GROUP BY
            `material_list`.`material_category`
            ORDER BY
            `selling_price` DESC";
            
            $quantity_penjualan_per_product = "
            SELECT
            `sales_order_list`.`material_code`,
            `material_list`.`material_name`,
            SUM(`sales_order_list`.`quantity`) as `quantity`
            FROM
            `sales_order`
            JOIN
            `sales_order_list`
            ON
            `sales_order`.`transaction_number` = `sales_order_list`.`transaction_number`
            JOIN
            `material_list`
            ON
            `material_list`.`material_code` = `sales_order_list`.`material_code`
            WHERE
            `sales_order`.`sales_channel` = 'point of sales' AND `sales_order_list`.`created_timestamp` >= '{$todays_years}'
            GROUP BY
            `sales_order_list`.`material_code`
            ORDER BY
            `quantity` DESC";
            
            $quantity_penjualan_per_category = "
            SELECT
            `material_list`.`material_category`,
            SUM(`sales_order_list`.`quantity`) as `quantity`
            FROM
            `sales_order`
            JOIN
            `sales_order_list`
            ON
            `sales_order`.`transaction_number` = `sales_order_list`.`transaction_number`
            JOIN
            `material_list`
            ON
            `material_list`.`material_code` = `sales_order_list`.`material_code`
            WHERE
            `sales_order`.`sales_channel` = 'point of sales' AND `sales_order_list`.`created_timestamp` >= '{$todays_years}'
            GROUP BY
            `material_list`.`material_category`
            ORDER BY
            `quantity` DESC";
            
            $penjualan_by_hours = "
            SELECT
            SUM(`sales_order_list`.`quantity`) as `quantity`,
            SUM(`sales_order_list`.`quantity` * `sales_order_list`.`selling_price`) as `selling_price`,
            date_format(`sales_order_list`.`created_timestamp`, '%H' ) as `hours`
            FROM
            `sales_order`
            JOIN
            `sales_order_list`
            ON
            `sales_order`.`transaction_number` = `sales_order_list`.`transaction_number`
            WHERE
            `sales_order`.`sales_channel` = 'point of sales' AND`sales_order_list`.`created_timestamp` >= '{$todays_years}'
            GROUP BY
            `hours`
            ORDER BY
            `sales_order_list`.`created_timestamp` DESC";
            
            $penjualan_by_weekdays = "
            SELECT
            SUM(`sales_order_list`.`quantity`) as `quantity`,
            SUM(`sales_order_list`.`quantity` * `sales_order_list`.`selling_price`) as `selling_price`,
            date_format(`sales_order_list`.`created_timestamp`, '%W' ) as `weekday`
            FROM
            `sales_order`
            JOIN
            `sales_order_list`
            ON
            `sales_order`.`transaction_number` = `sales_order_list`.`transaction_number`
            WHERE
            `sales_order`.`sales_channel` = 'point of sales' AND `sales_order_list`.`created_timestamp` >= '{$todays_years}'
            GROUP BY
            `weekday`
            ORDER BY
            `weekday`";
            
            $title = 'Per Tahun ini';
            
            
        } else {
            $value_penjualan_per_product = "
            SELECT
            `sales_order_list`.`material_code`,
            `material_list`.`material_name`,
            SUM(`sales_order_list`.`quantity` * `sales_order_list`.`selling_price`) as `selling_price`
           FROM
            `sales_order`
            JOIN
            `sales_order_list`
            ON
            `sales_order`.`transaction_number` = `sales_order_list`.`transaction_number`
            JOIN
            `material_list`
            ON
            `material_list`.`material_code` = `sales_order_list`.`material_code`
            WHERE
            `sales_order`.`sales_channel` = 'point of sales' AND `sales_order_list`.`created_timestamp` BETWEEN '{$start_date} 00:00:00' AND '{$end_date} 23:59:59'
            GROUP BY
            `sales_order_list`.`material_code`
            ORDER BY
            `selling_price` DESC";
            
            $value_penjualan_per_category = "
            SELECT
            `material_list`.`material_category`,
            SUM(`sales_order_list`.`quantity` * `sales_order_list`.`selling_price`) as `selling_price`
            FROM
            `sales_order`
            JOIN
            `sales_order_list`
            ON
            `sales_order`.`transaction_number` = `sales_order_list`.`transaction_number`
            JOIN
            `material_list`
            ON
            `material_list`.`material_code` = `sales_order_list`.`material_code`
            WHERE
            `sales_order`.`sales_channel` = 'point of sales' AND `sales_order_list`.`created_timestamp` BETWEEN '{$start_date} 00:00:00' AND '{$end_date} 23:59:59'
            GROUP BY
            `material_list`.`material_category`
            ORDER BY
            `selling_price` DESC";
            
            $quantity_penjualan_per_product = "
            SELECT
            `sales_order_list`.`material_code`,
            `material_list`.`material_name`,
            SUM(`sales_order_list`.`quantity`) as `quantity`
            FROM
            `sales_order`
            JOIN
            `sales_order_list`
            ON
            `sales_order`.`transaction_number` = `sales_order_list`.`transaction_number`
            JOIN
            `material_list`
            ON
            `material_list`.`material_code` = `sales_order_list`.`material_code`
            WHERE
            `sales_order`.`sales_channel` = 'point of sales' AND `sales_order_list`.`created_timestamp` BETWEEN '{$start_date} 00:00:00' AND '{$end_date} 23:59:59'
            GROUP BY
            `sales_order_list`.`material_code`
            ORDER BY
            `quantity` DESC";
            
            $quantity_penjualan_per_category = "
            SELECT
            `material_list`.`material_category`,
            SUM(`sales_order_list`.`quantity`) as `quantity`
            FROM
            `sales_order`
            JOIN
            `sales_order_list`
            ON
            `sales_order`.`transaction_number` = `sales_order_list`.`transaction_number`
            JOIN
            `material_list`
            ON
            `material_list`.`material_code` = `sales_order_list`.`material_code`
            WHERE
            `sales_order`.`sales_channel` = 'point of sales' AND `sales_order_list`.`created_timestamp` BETWEEN '{$start_date} 00:00:00' AND '{$end_date} 23:59:59'
            GROUP BY
            `material_list`.`material_category`
            ORDER BY
            `quantity` DESC";
            
            $penjualan_by_hours = "
            SELECT
            SUM(`sales_order_list`.`quantity`) as `quantity`,
            SUM(`sales_order_list`.`quantity` * `sales_order_list`.`selling_price`) as `selling_price`,
            date_format(`sales_order_list`.`created_timestamp`, '%H' ) as `hours`
            FROM
            `sales_order`
            JOIN
            `sales_order_list`
            ON
            `sales_order`.`transaction_number` = `sales_order_list`.`transaction_number`
            WHERE
            `sales_order`.`sales_channel` = 'point of sales' AND `sales_order_list`.`created_timestamp` BETWEEN '{$start_date} 00:00:00' AND '{$end_date} 23:59:59'
            GROUP BY
            `hours`
            ORDER BY
            `sales_order_list`.`created_timestamp` DESC";
            
            $penjualan_by_weekdays = "
            SELECT
            SUM(`sales_order_list`.`quantity`) as `quantity`,
            SUM(`sales_order_list`.`quantity` * `sales_order_list`.`selling_price`) as `selling_price`,
            date_format(`sales_order_list`.`created_timestamp`, '%W' ) as `weekday`
            FROM
            `sales_order`
            JOIN
            `sales_order_list`
            ON
            `sales_order`.`transaction_number` = `sales_order_list`.`transaction_number`
            WHERE
            `sales_order`.`sales_channel` = 'point of sales' AND `sales_order_list`.`created_timestamp` BETWEEN '{$start_date} 00:00:00' AND '{$end_date} 23:59:59'
            GROUP BY
            `weekday`
            ORDER BY
            `weekday`";
            
            $title = 'tanggal ' . date("d F Y", strtotime($start_date)) . ' - ' . date("d F Y", strtotime($end_date));
        }
        
        $this->View->render('kasir/dashboard',
                            array(
                                  'title' => $title,
                                'activelink1' => 'penjualan',
                                'activelink2' => 'penjualan kasir',
                                'activelink3' => 'penjualan kasir laporan',
                                'activelink4' => 'penjualan kasir laporan dashboard',
                                  'header_script' => $header_script,
                                  'footer_script' => $footer_script,
                                  'value_penjualan_per_product' => GenericModel::rawSelect($value_penjualan_per_product),
                                  'value_penjualan_per_category' => GenericModel::rawSelect($value_penjualan_per_category),
                                  'quantity_penjualan_per_product' => GenericModel::rawSelect($quantity_penjualan_per_product),
                                  'quantity_penjualan_per_category' => GenericModel::rawSelect($quantity_penjualan_per_category),
                                  'penjualan_by_hours' => GenericModel::rawSelect($penjualan_by_hours),
                                  'penjualan_by_weekdays' => GenericModel::rawSelect($penjualan_by_weekdays),
                                  )
                            );
    }

    /**
     * kasir untuk restoan dan cafe, semua menu/produk ditampilkan dilayar
     */
    public function resto()
    {
        $this->View->renderFileOnly('kasir/resto', array(
                'product_list' => GenericModel::getAll('material_list', "`material_type` = 3 AND `is_deleted` = 0 ORDER By `material_category` ASC, `material_name` ASC", " `material_code`, `material_name`, `selling_price`, `material_category`")
                )
        );
    }

    public function simpanResto()
    {
        //make transaction
        $transaction = KasirModel::kasirTransaction();

        if (!empty($transaction)) {
        //echo print to user/kasir
          echo Config::get('URL') . 'kasir/printKasir/?transaction_number=' . urlencode($transaction);
        }

        // RESET counter feedback to unconfuse user
        Session::set('feedback_positive', null);
        Session::set('feedback_negative', null);

    }

    /**
     * print struk kasir
     */
    public function printKasir() {
        $this->View->renderFileOnly('kasir/print_kasir', array(
                'product' => KasirModel::getTransaction(urldecode(Request::get('transaction_number'))),
                'company' => GenericModel::getAll('`system_preference`', "`category` = 'company_identification' OR `category` = 'customer_wifi' ORDER BY `item_name` ASC", '`value`, `item_name`')

        ));
    }

    /**
     * print struk kasir
     */
    public function printClinic() {
        $this->View->renderFileOnly('kasir/print_kasir_clinic', array(
                'product' => KasirModel::getTransactionClinic(urldecode(Request::get('transaction_number'))),
                'company' => GenericModel::getAll('`system_preference`', "`category` = 'company_identification' OR `category` = 'customer_wifi' ORDER BY `item_name` ASC", '`value`, `item_name`')

        ));
    }

    /**
     * kasir untuk restoan dan cafe, semua menu/produk ditampilkan dilayar
     */
    public function clinic()
    {
        $this->View->renderFileOnly('kasir/beauty_clinic', array(
                'title' => 'Kasir Beauty Clinic',
                'medicine_list' => GenericModel::getAll('material_list', "`material_type` = 34 AND `is_deleted` = 0 ORDER By `material_category` ASC, `material_name` ASC", " `material_code`, `material_name`, `selling_price`, `selling_price_member`, `material_category`"),
                'doctor_list' => GenericModel::getAll('material_list', "`material_type` = 30 AND `is_deleted` = 0 ORDER By `material_category` ASC, `material_name` ASC", " `material_code`, `material_name`, `selling_price`, `selling_price_member`, `material_category`"),
                'doctor_treatment_list' => GenericModel::getAll('material_list', "`material_type` = 31 AND `is_deleted` = 0 ORDER By `material_category` ASC, `material_name` ASC", " `material_code`, `material_name`, `selling_price`, `selling_price_member`, `material_category`"),
                'therapist_treatment_list' => GenericModel::getAll('material_list', "`material_type` = 33 AND `is_deleted` = 0 ORDER By `material_category` ASC, `material_name` ASC", " `material_code`, `material_name`, `selling_price`, `selling_price_member`, `material_category`"),
                )
        );
    }

    public function simpanClinic()
    {
        //make transaction
        $transaction = KasirModel::clinicTransaction();

        if (!empty($transaction)) {
        //echo print to user/kasir
          echo Config::get('URL') . 'kasir/printClinic/?transaction_number=' . urlencode($transaction);
        }

        // RESET counter feedback to unconfuse user
        Session::set('feedback_positive', null);
        Session::set('feedback_negative', null);

    }

    public function checkMember()
    {

        if (GenericModel::isExist('contact', 'contact_id', Request::post('memberID')) AND KasirModel::memberActive(Request::post('memberID'))) {
            echo 'Member Valid dan Aktif';
        } elseif (GenericModel::isExist('contact', 'contact_id', Request::post('memberID')) AND !KasirModel::memberActive(Request::post('memberID'))){
            echo 'GAGAL Member Valid Tetapi Tidak Aktif';
        } else {
            echo 'GAGAL Tidak Terdaftar';
        }

        // RESET counter feedback to unconfuse user
        Session::set('feedback_positive', null);
        Session::set('feedback_negative', null);
    
    }

    /**
     * This method controls what happens when you move to /note/index in your app.
     * Gets all notes (of the user).
     */
    public function therapistReport()
    {
        $today = date("Y-m-d");
        $field = '`users`.`uid`,
                `users`.`user_name`,
                `users`.`full_name`,
                `users`.`is_active`,
                `users_attendance_log`.`created_timestamp`
                ';
        $table = '`users`
                    LEFT JOIN
                `users_attendance_log` ON `users_attendance_log`.`user_id` = `users`.`uid`';
        $where = "`users`.`is_deleted` = 0 AND `users_attendance_log`.`created_timestamp` > '$today' GROUP BY `users`.`uid` ORDER BY 
        `users_attendance_log`.`created_timestamp`";
        $contact = GenericModel::getAll($table, $where, $field);

        $header_script = '<link rel="stylesheet" href="' . Config::get('URL') . 'bootstrap-3.3.7/css/excel-2007.css" media="screen"/>';
        $this->View->render('kasir/therapist_report',
              array(
                'header_script' => $header_script,
                'title' => 'Laporan Therapist',
                'activelink1' => 'penjualan',
                'activelink2' => 'penjualan kasir',
                'activelink3' => 'penjualan kasir laporan',
                'activelink4' => 'penjualan kasir laporan therapist',
                'contact' => $contact
            )
        );
    }

    public function updateTherapistAvailability($uid, $status)
    {
        $update = array(
                'is_active' => $status
                );
        GenericModel::update('users', $update, "`uid` = '$uid'");
        Redirect::to('kasir/therapistReport/');
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
        $this->View->render('kasir/detail',
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

    public function insertPayment()
    {
        $transaction_number = urldecode(Request::get('transaction_number'));
        $total_order = (float)Request::post('total_order');

        //check if payment list total is same as total penjualan
        $total_payment = 0;
        for ($i=1; $i < 10; $i++) {
            if (!empty($_POST['value_' . $i]))
                { // only execute only when qty is not blank
                    $total_payment = $total_payment + (float)Request::post('value_' . $i);

                    //check if there's empty schedule date and peyment type
                    if (empty($_POST['payment_due_date_' . $i])) {
                        Session::add('feedback_negative', 'GAGAGL!. Tanggal rencana pembayaran atau tipe pembayaran tidak diisi.');
                        Redirect::to('kasir/detail/?transaction_number=' . $transaction_number);
                        exit;
                    }
                } // END IF
        } // END FOR

        if ($total_payment != $total_order) {
            Session::add('feedback_negative', 'GAGAGL!. Jumlah total uang yang dimasukkan tidak sama dengan jumlah uang penjualan.');
            Redirect::to('kasir/detail/?transaction_number=' . $transaction_number);
            exit;
        }

        for ($i=1; $i < 10; $i++) {
            //only insert not empty value
            if (!empty(Request::post('value_' . $i)))
            {
                $insert = array(
                        'uid'    => GenericModel::guid(),
                        'transaction_reference' => $transaction_number,
                        'transaction_type' => 'point of sale',
                        'status' => -1,
                        'debit' => FormaterModel::getNumberOnly(Request::post('value_' . $i)),
                        'payment_type' => 'cash',
                        'payment_due_date' => Request::post('payment_due_date_' . $i),
                        'note' => Request::post('note_' . $i),
                        'creator_id'    => SESSION::get('uid')
                    );
                //Debuger::jam($insert);
                GenericModel::insert('payment_transaction', $insert);
            }
        }
        
        Redirect::to('kasir/detail/?transaction_number=' . $transaction_number);
        
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
            $email[] = 'jabrik.ta01@gmail.com';
            $email[] = 'jabrik.ta02@gmail.com';

            $email_creator = SESSION::get('full_name');
            $email_subject = "Mencoba mendelete transaksi penjualan: " . $transaction_number . ' oleh ' . ucwords($email_creator);
            $body ='Klik link berikut untuk melihat detail transaksi ' .   Config::get('URL') . 'kasir/printKasir/?transaction_number=' . urlencode($transaction_number);
            $mail = new Mail;
            $mail_sent = $mail->sendMail($email, Config::get('EMAIL_VERIFICATION_FROM_EMAIL'),
                Config::get('EMAIL_VERIFICATION_FROM_NAME'), $email_subject, $body
            );
        }
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
                `sales_order`.`customer_name`,
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
                `sales_order`.`status` >= 0 AND `sales_channel` = 'point of sales'
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
        $this->View->render('kasir/laporan',
            array(
            'header_script' => $header_script,
            'title' => 'Daftar Penjualan Kasir By Order',
            'page' => $page,
            'limit' => $limit,
                'activelink1' => 'penjualan',
                'activelink2' => 'penjualan kasir',
                'activelink3' => 'penjualan kasir laporan',
                'activelink4' => 'penjualan kasir laporan by order',
            'transaction_group' => GenericModel::rawSelect($sql_group),
            'pagination' => FormaterModel::pagination('kasir/laporan', $total_record, $page, $limit)
            )
        );
    }

    public function reportByDate($start_date = null, $end_date = null)
    {
        if (Request::post('change_date') == 'ok') { // user input change date
            Redirect::to('kasir/reportByDate/' . Request::post('start_date') . '/' . Request::post('end_date'));
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
                    `sales_channel` = 'point of sales' AND (`sales_order`.`created_timestamp` BETWEEN '{$start_date} 00:00:00.000000' AND '{$end_date} 23:59:59.999999')
                GROUP BY
                     `sales_order`.`transaction_number`
                ORDER BY
                    `sales_order`.`created_timestamp` DESC";

        $header_script = '<link rel="stylesheet" href="' . Config::get('URL') . 'bootstrap-3.3.7/css/datepicker.css" media="screen"/>';
        $footer_script = '<script src="' . Config::get('URL') . 'bootstrap-3.3.7/js/bootstrap-datepicker.js"></script>
        <script>
        $(".datepicker").datepicker(); //date picker
        </script>';

        $this->View->render('kasir/report_by_date',
            array(
            'title' => $title,
            'header_script' => $header_script,
            'footer_script' => $footer_script,
                'activelink1' => 'penjualan',
                'activelink2' => 'penjualan kasir',
                'activelink3' => 'penjualan kasir laporan',
                'activelink4' => 'penjualan kasir laporan by tanggal',
            'transaction_group' => GenericModel::rawSelect($sql_group),
            )
        );
    }

    public function summary($start_date = null, $end_date = null)
    {
        if (Request::post('change_date') == 'ok') { // user input change date
            Redirect::to('kasir/summary/' . Request::post('start_date') . '/' . Request::post('end_date'));
            exit;
        }

        $header_script = '<link rel="stylesheet" href="' . Config::get('URL') . 'bootstrap-3.3.7/css/datepicker.css" media="screen"/>';
        $footer_script = '<script src="' . Config::get('URL') . 'bootstrap-3.3.7/js/bootstrap-datepicker.js"></script>
        <script>
        $(".datepicker").datepicker(); //date picker
        </script>';

        // if null given, show today transaction
        if ($start_date == null AND $end_date == null) {
            $today = date('Y-m-d');
            $quantity_penjualan_per_product = "
            SELECT
                `sales_order_list`.`material_code`,
                `material_list`.`material_name`,
                `sales_order_list`.`selling_price`,
                SUM(`sales_order_list`.`quantity`) as `quantity`
            FROM
                `sales_order`
            LEFT JOIN
                `sales_order_list` ON `sales_order_list`.`transaction_number` = `sales_order`.`transaction_number`
            LEFT JOIN
                `material_list`
                ON
                `material_list`.`material_code` = `sales_order_list`.`material_code`
            WHERE
                `sales_channel` = 'point of sales' AND (`sales_order`.`created_timestamp` BETWEEN '{$today} 00:00:00' AND '{$today} 23:59:59')
            GROUP BY
                `sales_order_list`.`material_code`
            ORDER BY
                `quantity` DESC";

            $discount_total = "
            SELECT
                SUM(`sales_order`.`discount_total`) as `discount_total`
            FROM
                `sales_order`
            WHERE
                `sales_order`.`created_timestamp` BETWEEN '{$today} 00:00:00' AND '{$today} 23:59:59'";

        $title = 'Summary Penjualan Kasir Hari Ini';
        
        } else {

            $quantity_penjualan_per_product = "
            SELECT
                `sales_order_list`.`material_code`,
                `material_list`.`material_name`,
                `sales_order_list`.`selling_price`,
                SUM(`sales_order_list`.`quantity`) as `quantity`
            FROM
                `sales_order`
            LEFT JOIN
                `sales_order_list` ON `sales_order_list`.`transaction_number` = `sales_order`.`transaction_number`
            LEFT JOIN
                `material_list`
                ON
                `material_list`.`material_code` = `sales_order_list`.`material_code`
            WHERE
                `sales_channel` = 'point of sales' AND (`sales_order`.`created_timestamp` BETWEEN '{$start_date} 00:00:00' AND '{$end_date} 23:59:59')
            GROUP BY
                `sales_order_list`.`material_code`
            ORDER BY
                `quantity` DESC";

        $discount_total = "
            SELECT
                SUM(`sales_order`.`discount_total`) as `discount_total`
            FROM
                `sales_order`
            WHERE
                `sales_order`.`created_timestamp` BETWEEN '{$start_date} 00:00:00' AND '{$end_date} 23:59:59'";

        $title = 'Summary Penjualan Kasir Tanggal ' . date("d F Y", strtotime($start_date)) . ' - ' . date("d F Y", strtotime($end_date));
        }

        $this->View->render('kasir/summary',
            array(
            'title' => $title,
                'activelink1' => 'penjualan',
                'activelink2' => 'penjualan kasir',
                'activelink3' => 'penjualan kasir laporan',
                'activelink4' => 'penjualan kasir laporan summary',
            'header_script' => $header_script,
            'footer_script' => $footer_script,
            'quantity_penjualan_per_product' => GenericModel::rawSelect($quantity_penjualan_per_product),
            'discount_total' => GenericModel::rawSelect($discount_total, false),
            )
        );
    }

    /**
     * kasir yang komplek untuk supermarket/swalayan, ada diskon %, potongan harga dalam bentuk uang, ada EDC bank
     * cocok untuk swalayan dan supermarket menengah keatas
     */
    public function Supermarket()
    {
        $this->View->renderFileOnly('kasir/supermarket', array(
                'product_list' => GenericModel::getAll('material_list', "`material_type` = 3 AND `is_deleted` = 0 ORDER By `material_category` ASC, `material_name` ASC", " `material_code`, `material_name`, `selling_price`, `material_category`")
                )
        );
    }
}
