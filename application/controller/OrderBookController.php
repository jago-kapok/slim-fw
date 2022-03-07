<?php


class OrderBookController extends Controller
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

    public function order()
    {
        $category = "SELECT * FROM `system_preference` WHERE `category` = 'direct_income_transaction' ORDER BY `item_name` ASC";


        $header_script = '<link rel="stylesheet" href="' . Config::get('URL') . 'bootstrap-3.3.7/css/excel-2007.css" media="screen"/><link rel="stylesheet" href="' . Config::get('URL') . 'bootstrap-3.3.7/css/datepicker.css" media="screen"/>';
        $footer_script = '<script src="' . Config::get('URL') . 'bootstrap-3.3.7/js/bootstrap-datepicker.js"></script>
        <script>
        $(".datepicker").datepicker(); //date picker
        </script>';

        $this->View->render('order_book/order',
            array(
                'title' => 'Buat Transaksi Kas Baru',
                'activelink1' => 'penjualan',
                'activelink2' => 'order book',
                'activelink3' => 'new order book',
                'header_script' => $header_script,
                'footer_script' => $footer_script,
                'kategoriPenjualan' => GenericModel::rawSelect($category),
                'customer_list' => GenericModel::getAll('contact', "`is_deleted` = 0", "`contact_id`, `contact_name`"),
            )
        );
    }

    public function saveOrder()
    {
        $customer_code = Request::post('customer');
        $customer_code = explode(' -- ', $customer_code);
        $customer_code   = trim($customer_code[0]);

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

        for ($i=1; $i <=15; $i++) {
            $category = Request::post('category_' . $i);
            $jenis_pesanan = Request::post('pesanan_' . $i);
            $quantity = FormaterModel::sanitize(Request::post('quantity_' . $i));
            $price = FormaterModel::sanitize(Request::post('price_' . $i));
            $note = FormaterModel::sanitize(Request::post('note_' . $i));

            if (!empty($customer_code) AND !empty($jenis_pesanan) AND !empty($price) AND !empty($quantity) AND !empty($category)) {

                //2.1 insert sales order list product
                $insert = array(
                    'uid'    => GenericModel::guid(),
                    'transaction_number'    => $so_number,
                    'budged_category'    => $category,
                    'budget_item'    => $jenis_pesanan,
                    'quantity'    => $quantity,
                    'selling_price'    => $price,
                    'note'    => $note,
                    'creator_id'    => SESSION::get('uid')
                );
                GenericModel::insert('sales_order_list', $insert);
            }
        }

        //3. insert detail sales order
        $insert = array(
                        'transaction_number'    => $so_number,
                        'sales_channel' => 'sales order',
                        'status' => 0,
                        'customer_id'    => $customer_code,
                        'creator_id'    => SESSION::get('uid')
                );
        // Send Status insert to front end
        GenericModel::insert('sales_order', $insert, false); // use silent so inser 

        Redirect::to('orderBook/report');
    }

    public function report($start_date = null, $end_date = null)
    {
        if (Request::post('change_date') == 'ok') { // user input change date
            Redirect::to('orderBook/report/' . Request::post('start_date') . '/' . Request::post('end_date'));
            exit;
        }

        if ($start_date === null AND $end_date === null) {
            $start_date = date('Y-m-01');
            $end_date = date('Y-m-d', strtotime('last day of', strtotime($start_date)));
        }

        if (isset($_GET['find']) AND !empty($_GET['find'])) {
            $find = Request::get('find');
            $sql = "
                SELECT
                    `sales_order`.`transaction_number`,
                    `sales_order`.`created_timestamp`,
                    `sales_order`.`customer_id`,
                    GROUP_CONCAT(`sales_order_list`.`quantity` SEPARATOR '-, -') as `quantity`,
                    GROUP_CONCAT(`sales_order_list`.`selling_price` SEPARATOR '-, -') as `selling_price`,
                    GROUP_CONCAT(`sales_order_list`.`budged_category` SEPARATOR '-, -') as `budged_category`,
                    GROUP_CONCAT(`sales_order_list`.`budget_item` SEPARATOR '-, -') as `budget_item`,
                    GROUP_CONCAT(`sales_order_list`.`note` SEPARATOR '-, -') as `note`,
                    `users`.`full_name`,
                    `contact`.`contact_name`
                FROM
                    `sales_order`
                LEFT JOIN
                    `sales_order_list` AS `sales_order_list` ON `sales_order_list`.`transaction_number` = `sales_order`.`transaction_number`
                LEFT JOIN
                    `contact` AS `contact` ON `contact`.`contact_id` = `sales_order`.`customer_id`
                LEFT JOIN
                    `users` AS `users` ON `users`.`uid` = `sales_order`.`creator_id`
                WHERE
                    `sales_order_list`.`transaction_number` = '{$find}' OR
                    `sales_order`.`customer_id` = '{$find}' OR
                    `sales_order_list`.`budget_item` LIKE '%{$find}%' OR
                    `sales_order_list`.`budged_category` LIKE '%{$find}%' OR
                    `contact`.`contact_name` LIKE '%{$find}%'
                GROUP BY
                    `sales_order`.`transaction_number`
                ORDER BY
                    `sales_order_list`.`created_timestamp` DESC";

            $sql_group_by_category = "
                SELECT
                    `budged_category`,
                    SUM(`quantity` * `selling_price`) AS `order`
                FROM
                    `sales_order`
                LEFT JOIN
                    `sales_order_list` ON `sales_order_list`.`transaction_number` = `sales_order`.`transaction_number`
                LEFT JOIN
                    `contact` AS `contact` ON `contact`.`contact_id` = `sales_order`.`customer_id`
                WHERE
                    `sales_order_list`.`transaction_number` = '{$find}' OR
                    `sales_order`.`customer_id` = '{$find}' OR
                    `sales_order_list`.`budget_item` LIKE '%{$find}%' OR
                    `sales_order_list`.`budged_category` LIKE '%{$find}%' OR
                    `contact`.`contact_name` LIKE '%{$find}%'
                GROUP BY
                    `sales_order_list`.`budged_category`";

            $sql_group_by_week = "
                SELECT
                    CONCAT(YEAR(`sales_order_list`.`created_timestamp`), '/', WEEK(`sales_order_list`.`created_timestamp`)) as `week`,
                    SUM(`quantity` * `selling_price`) AS `order`
                FROM
                    `sales_order`
                LEFT JOIN
                    `sales_order_list` ON `sales_order_list`.`transaction_number` = `sales_order`.`transaction_number`
                LEFT JOIN
                    `contact` AS `contact` ON `contact`.`contact_id` = `sales_order`.`customer_id`
                WHERE
                    `sales_order_list`.`transaction_number` = '{$find}' OR
                    `sales_order`.`customer_id` = '{$find}' OR
                    `sales_order_list`.`budget_item` LIKE '%{$find}%' OR
                    `sales_order_list`.`budged_category` LIKE '%{$find}%' OR
                    `contact`.`contact_name` LIKE '%{$find}%'
                GROUP BY `week`
                ORDER BY `week` ASC";

            $sql_group_by_month = "
                SELECT
                    CONCAT(YEAR(`sales_order_list`.`created_timestamp`), '/', MONTH(`sales_order_list`.`created_timestamp`)) as `month`,
                    SUM(`quantity` * `selling_price`) AS `order`
                FROM
                    `sales_order`
                LEFT JOIN
                    `sales_order_list` ON `sales_order_list`.`transaction_number` = `sales_order`.`transaction_number`
                LEFT JOIN
                    `contact` AS `contact` ON `contact`.`contact_id` = `sales_order`.`customer_id`
                WHERE
                    `sales_order_list`.`transaction_number` = '{$find}' OR
                    `sales_order`.`customer_id` = '{$find}' OR
                    `sales_order_list`.`budget_item` LIKE '%{$find}%' OR
                    `sales_order_list`.`budged_category` LIKE '%{$find}%' OR
                    `contact`.`contact_name` LIKE '%{$find}%'
                GROUP BY `month`
                ORDER BY `month` ASC";

            $sql_group_by_month_and_category = "
                SELECT
                    CONCAT(YEAR(`sales_order_list`.`created_timestamp`), '/', MONTH(`sales_order_list`.`created_timestamp`)) as `month`,
                    `budged_category`,
                    SUM(`quantity` * `selling_price`) AS `order`
                FROM
                    `sales_order`
                LEFT JOIN
                    `sales_order_list` ON `sales_order_list`.`transaction_number` = `sales_order`.`transaction_number`
                LEFT JOIN
                    `contact` AS `contact` ON `contact`.`contact_id` = `sales_order`.`customer_id`
                WHERE
                    `sales_order_list`.`transaction_number` = '{$find}' OR
                    `sales_order`.`customer_id` = '{$find}' OR
                    `sales_order_list`.`budget_item` LIKE '%{$find}%' OR
                    `sales_order_list`.`budged_category` LIKE '%{$find}%' OR
                    `contact`.`contact_name` LIKE '%{$find}%'
                GROUP BY `budged_category`,`month`
                ORDER BY `month` ASC";
        } else {
            $sql = "
                SELECT
                    `sales_order`.`transaction_number`,
                    `sales_order`.`created_timestamp`,
                    `sales_order`.`customer_id`,
                    GROUP_CONCAT(`sales_order_list`.`quantity` SEPARATOR '-, -') as `quantity`,
                    GROUP_CONCAT(`sales_order_list`.`selling_price` SEPARATOR '-, -') as `selling_price`,
                    GROUP_CONCAT(`sales_order_list`.`budged_category` SEPARATOR '-, -') as `budged_category`,
                    GROUP_CONCAT(`sales_order_list`.`budget_item` SEPARATOR '-, -') as `budget_item`,
                    GROUP_CONCAT(`sales_order_list`.`note` SEPARATOR '-, -') as `note`,
                    `users`.`full_name`,
                    `contact`.`contact_name`
                FROM
                    `sales_order`
                LEFT JOIN
                    `sales_order_list` AS `sales_order_list` ON `sales_order_list`.`transaction_number` = `sales_order`.`transaction_number`
                LEFT JOIN
                    `contact` AS `contact` ON `contact`.`contact_id` = `sales_order`.`customer_id`
                LEFT JOIN
                    `users` AS `users` ON `users`.`uid` = `sales_order`.`creator_id`
                WHERE
                    `sales_order_list`.`created_timestamp` BETWEEN '$start_date 00:00:01' AND '$end_date 23:59:59'
                GROUP BY
                     `sales_order`.`transaction_number`
                ORDER BY
                    `sales_order_list`.`created_timestamp` DESC";

            $sql_group_by_category = "
                SELECT
                    `budged_category`,
                    SUM(`quantity` * `selling_price`) AS `order`
                FROM
                    `sales_order_list`
                WHERE
                (`sales_order_list`.`created_timestamp` BETWEEN '$start_date 00:00:00' AND '$end_date 23:59:59')
                GROUP BY `budged_category`";

            $sql_group_by_week = "
                SELECT
                    CONCAT(YEAR(`created_timestamp`), '/', WEEK(`created_timestamp`)) as `week`,
                    SUM(`quantity` * `selling_price`) AS `order`
                FROM
                    `sales_order_list`
                WHERE
                (`sales_order_list`.`created_timestamp` BETWEEN '$start_date 00:00:00' AND '$end_date 23:59:59')
                GROUP BY `week`
                ORDER BY `week` ASC";

            $sql_group_by_month = "
                SELECT
                    CONCAT(YEAR(`created_timestamp`), '/', MONTH(`created_timestamp`)) as `month`,
                    SUM(`quantity` * `selling_price`) AS `order`
                FROM
                    `sales_order_list`
                WHERE
                (`sales_order_list`.`created_timestamp` BETWEEN '$start_date 00:00:00' AND '$end_date 23:59:59')
                GROUP BY `month`
                ORDER BY `month` ASC";

            $sql_group_by_month_and_category = "
                SELECT
                    CONCAT(YEAR(`created_timestamp`), '/', MONTH(`created_timestamp`)) as `month`,
                    `budged_category`,
                    SUM(`quantity` * `selling_price`) AS `order`
                FROM
                    `sales_order_list`
                WHERE
                (`sales_order_list`.`created_timestamp` BETWEEN '$start_date 00:00:00' AND '$end_date 23:59:59')
                GROUP BY `budged_category`,`month`
                ORDER BY `month` ASC";
        }

        $table = '`system_preference`';
        $where = "`category` = 'payment_type'";
        $field = "`item_name`";
        $payment_type = GenericModel::getAll($table, $where, $field);

        $header_script = '<link rel="stylesheet" href="' . Config::get('URL') . 'bootstrap-3.3.7/css/excel-2007.css" media="screen"/><link rel="stylesheet" href="' . Config::get('URL') . 'bootstrap-3.3.7/css/datepicker.css" media="screen"/>';
        $footer_script = '<script src="' . Config::get('URL') . 'bootstrap-3.3.7/js/bootstrap-datepicker.js"></script>
        <script>
        $(".datepicker").datepicker(); //date picker

        //modal payment
        $("#paymentModal").on("shown.bs.modal", function (event) {
          var button = $(event.relatedTarget) // Button that triggered the modal
          var recipient = button.data("whatever") // Extract info from data-* attributes
          // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
          // Update the modals content. We will use jQuery here, but you could use a data binding library or other methods instead.
          var modal = $(this)
          modal.find(".modal-title").text("Jadwal Pembayaran Untuk " + recipient)
          modal.find("#transaction-number").val(recipient)
        })

        //modal upload file
        $("#uploadModal").on("shown.bs.modal", function (event) {
          var button = $(event.relatedTarget) // Button that triggered the modal
          var recipient = button.data("uploadfile") // Extract info from data-* attributes
          // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
          // Update the modals content. We will use jQuery here, but you could use a data binding library or other methods instead.
          var modal = $(this)
          modal.find(".modal-title").text("Upload File Untuk " + recipient)
          modal.find("#transaction-number-upload").val(recipient)
        })
        </script>';

        $this->View->render('order_book/report',
            array(
            'title' => 'Daftar Transaksi dari tanggal ' . date("d F Y", strtotime($start_date)) . ' - ' . date("d F Y", strtotime($end_date)),
            'activelink1' => 'penjualan',
            'activelink2' => 'order book',
            'activelink3' => 'order book report',
            'header_script' => $header_script,
            'footer_script' => $footer_script,
            'payment_type' => $payment_type,
            'transaction' => GenericModel::rawSelect($sql),
            'transaction_group_by_category' => GenericModel::rawSelect($sql_group_by_category),
            'transaction_group_by_week' => GenericModel::rawSelect($sql_group_by_week),
            'transaction_group_by_month' => GenericModel::rawSelect($sql_group_by_month),
            'transaction_group_by_month_and_category' => GenericModel::rawSelect($sql_group_by_month_and_category),
            )
        );
    }

    public function insertPayment()
    {
        $transaction_number = Request::post('transaction_number');
        $totalrecord = count($_POST['value']);

        //get data from database
        $order = GenericModel::getOne('`sales_order_list`', "`transaction_number` = '{$transaction_number}'", '*');

        //check if payment list total is same as total purchase
        $total_payment = 0;
        for ($i = 1; $i <= $totalrecord; $i++) {
            if ($_POST['value'][$i] > 0)
                { // only execute only when qty is not blank
                    $total_payment = $total_payment + (float)$_POST['value'][$i];

                    //check if there's empty schedule date and peyment type
                    if (empty($_POST['value'][$i]) OR empty($_POST['payment_due_date'][$i])) {
                        Session::add('feedback_negative', 'GAGAGL!. Jumlah pembayaran dan tanggal rencana pembayaran harus diisi.');
                        Redirect::to('orderBook/report');
                        exit;
                    }
                } // END IF
        } // END FOR
        
        for ($i = 1; $i <= $totalrecord; $i++) {
            if ($_POST['value'][$i] > 0)
                { // only execute only when qty is not blank
                    $insert = array(
                        'uid'    => GenericModel::guid(),
                        'transaction_code'    => $transaction_number,
                        'transaction_name'    => $order->order_name,
                        'transaction_category'    => $order->order_category,
                        'transaction_type'    => 'sales order',
                        'currency' => 'IDR',
                        'payment_type' => 'cash',
                        'debit'    => trim(strip_tags($_POST['value'][$i])),
                        'payment_due_date' => date("Y-m-d", strtotime(trim(strip_tags($_POST['payment_due_date'][$i])))),
                        'note' => trim(strip_tags($_POST['note'][$i])),
                        'creator_id'    => SESSION::get('uid')
                    );
                    GenericModel::insert('payment_transaction', $insert);
                } // END IF
        } // END FOR
        
        Redirect::to('bukuBesar/confirmPayment/?transaction_number=' . urlencode($transaction_number));
    }

    /**
     * Perform the upload image
     * POST-request
     */
    public function uploadImage()
    {
        $transaction_number = Request::post('transaction_number');

        if (empty($transaction_number)) {
            Redirect::to('orderBook/report');
            Session::add('feedback_negative', 'GAGAL!, upload file tidak berhasil');
        }

        $image_name = 'file_name';
        $image_rename = Request::post('image_name');
        $destination = 'sales-order'; //folder
        $note = Request::post('note');
        UploadModel::uploadImage($image_name, $image_rename, $destination, $transaction_number, $note);
        Redirect::to('orderBook/uploadedImageList/?transaction_number=' . urlencode($transaction_number));
    }

    /**
     * Perform the upload image
     * POST-request
     */
    public function uploadedImageList()
    {
        $transaction_number = urldecode(Request::get('transaction_number'));
        $uploaded_file = "SELECT `item_name`, `item_id`, `value`, `uid`, `note`  FROM `upload_list` WHERE `category` =  'sales-order' AND `item_id` = '{$transaction_number}' AND `is_deleted` = 0";

        $this->View->render('order_book/uploaded_image_list',
            array(
            'title' => 'Daftar Transaksi dari tanggal ' . date("d F Y", strtotime($start_date)) . ' - ' . date("d F Y", strtotime($end_date)),
            'activelink1' => 'finance',
            'activelink2' => 'order book',
            'activelink3' => 'laporan order book',
            'header_script' => $header_script,
            'footer_script' => $footer_script,
            'uploaded_file' => GenericModel::rawSelect($uploaded_file),
            )
        );
    }

    public function editOrder()
    {
        $transaction_number = urldecode(Request::get('transaction_number'));
        $category = "SELECT * FROM `system_preference` WHERE `category` = 'direct_income_transaction' ORDER BY `item_name` ASC";

        $transaction = "SELECT
                            `sales_order_list`.`uid`,
                            `sales_order_list`.`transaction_number`,
                            `sales_order_list`.`budget_item`,
                            `sales_order_list`.`budged_category`,
                            `sales_order_list`.`quantity`,
                            `sales_order_list`.`selling_price`,
                            `sales_order_list`.`note`,
                            `contact`.`contact_id`,
                            `contact`.`contact_name`
                        FROM
                            `sales_order_list`
                        LEFT JOIN
                            `sales_order`
                                ON
                            `sales_order`.`transaction_number` = `sales_order_list`.`transaction_number`
                        LEFT JOIN
                            `contact` ON `contact`.`contact_id` = `sales_order`.`customer_id`
                        WHERE
                            `sales_order_list`.`transaction_number` = '{$transaction_number}'";

        $header_script = '<link rel="stylesheet" href="' . Config::get('URL') . 'bootstrap-3.3.7/css/excel-2007.css" media="screen"/><link rel="stylesheet" href="' . Config::get('URL') . 'bootstrap-3.3.7/css/datepicker.css" media="screen"/>';
        $footer_script = '<script src="' . Config::get('URL') . 'bootstrap-3.3.7/js/bootstrap-datepicker.js"></script>
        <script>
        $(".datepicker").datepicker(); //date picker
        </script>';

        $this->View->render('order_book/edit_order',
            array(
                'title' => 'Buat Transaksi Kas Baru',
                'activelink1' => 'finance',
                'activelink2' => 'order book',
                'activelink3' => 'buat order book',
                'header_script' => $header_script,
                'footer_script' => $footer_script,
                'transaction' => GenericModel::rawSelect($transaction),
                'kategoriPenjualan' => GenericModel::rawSelect($category),
                'customer_list' => GenericModel::getAll('contact', "`is_deleted` = 0", "`contact_id`, `contact_name`"),
            )
        );
    }

    public function saveEditOrder()
    {
        $transaction_number = Request::post('transaction_number');
        $customer_code = Request::post('customer');
        $customer_code = explode(' -- ', $customer_code);
        $customer_code   = trim($customer_code[0]);

        for ($i=1; $i <= Request::post('total_record'); $i++) { 
            $category = Request::post('category_' . $i);
            $jenis_pesanan = Request::post('pesanan_' . $i);
            $quantity = Request::post('quantity_' . $i);
            $price = Request::post('price_' . $i);
            $uid = Request::post('uid_' . $i);
            $note = Request::post('note_' . $i);

            if (!empty($customer_code) AND !empty($jenis_pesanan) AND !empty($price) AND !empty($quantity)) {
                $update = array(
                    'budged_category'    => $category,
                    'budget_item'    => $jenis_pesanan,
                    'quantity'    => $quantity,
                    'selling_price'    => $price,
                    'note'    => $note,
                    'modifier_id' => SESSION::get('uid'),
                    'modified_timestamp' => date('Y-m-d H:i:s'),
                );
                GenericModel::update('sales_order_list', $update, "`uid` = '{$uid}'");
            }
        }

        $update = array(
                        'status' => 0,
                        'customer_id' => $customer_code,
                        'modifier_id' => SESSION::get('uid'),
                        'modified_timestamp' => date('Y-m-d H:i:s'),
                );
        GenericModel::update('sales_order', $update, "`transaction_number` = '{$transaction_number}'");

        Redirect::to('orderBook/report');
    }

    public function deleteOrder()
    {
        $transaction_number = urldecode(Request::get('transaction_number'));
        GenericModel::remove('sales_order', 'transaction_number', $transaction_number, false); //false for silent feedback
        GenericModel::remove('sales_order_list', 'transaction_number', $transaction_number, false); //false for silent feedback
        Redirect::to(Request::get('forward'));
    }
}