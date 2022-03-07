<?php

class PurchaseBookController extends Controller
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
        $category = "SELECT * FROM `system_preference` WHERE `category` = 'direct_expense_transaction' ORDER BY `item_name` ASC";

        $header_script = '<link rel="stylesheet" href="' . Config::get('URL') . 'bootstrap-3.3.7/css/excel-2007.css" media="screen"/><link rel="stylesheet" href="' . Config::get('URL') . 'bootstrap-3.3.7/css/datepicker.css" media="screen"/>';
        $footer_script = '<script src="' . Config::get('URL') . 'bootstrap-3.3.7/js/bootstrap-datepicker.js"></script>
        <script>
        $(".datepicker").datepicker(); //date picker
        </script>';

        $this->View->render('purchase_book/order',
            array(
                'title' => 'Buat Transaksi Kas Baru',
                'activelink1' => 'pembelian',
                'activelink2' => 'purchase book',
                'activelink3' => 'buat purchase book',
                'header_script' => $header_script,
                'footer_script' => $footer_script,
                'kategoriPenjualan' => GenericModel::rawSelect($category),
                'customer_list' => GenericModel::getAll('contact', "`is_deleted` = 0", "`contact_id`, `contact_name`"),
                'material_list' => GenericModel::getAll('material_list', "`is_deleted` = 0 AND (`material_type` = 1 OR `material_type` = 4)", "`material_code`, `material_name`"),
            )
        );
    }

    public function saveOrder()
    {
        for ($i=1; $i <=15; $i++) {
            $customer_code = Request::post('customer_' . $i);
            $customer_code = explode(' -- ', $customer_code);
            $customer_code   = trim($customer_code[0]);
            $category = Request::post('category_' . $i);
            $jenis_pesanan = Request::post('pesanan_' . $i);
            $quantity = FormaterModel::getNumberOnly(Request::post('quantity_' . $i));
            $price = FormaterModel::getNumberOnly(Request::post('price_' . $i));
            $lunas = (int) Request::post('lunas_' . $i);

            if (!empty($customer_code) AND !empty($jenis_pesanan) AND !empty($price) AND !empty($quantity)) {
                //insert to table PO
                // Get latest PO Number, format PR is TBE 0013/PO-0315
                $table = '`purchase_order`';
                $where = "`transaction_number` LIKE '%/PB-%' ORDER BY `created_timestamp` DESC";
                $field = "`transaction_number`";
                $pr_data = GenericModel::getOne($table, $where, $field);
                $transaction_number = $pr_data->transaction_number;
                $find_integer = explode('/', $transaction_number);
                $transaction_number = $find_integer[0];
                $transaction_number = (integer) FormaterModel::getNumberOnly($transaction_number);
                $transaction_number = $transaction_number + 1;
                $transaction_number = "00000".$transaction_number;
                $transaction_number = substr($transaction_number, strlen($transaction_number)-5, 5);
                $transaction_number = Config::get('COMPANY_CODE') . ' ' . $transaction_number . '/PB-' . date("m") . date("y");

                $log = '<li><span class="badge badge-grey">' . SESSION::get('user_name') .'</span> creates Purchase Request (' . date("Y-m-d") . ')</li>';

                $insert = array(
                            'supplier_id' => $customer_code,
                            'transaction_number' => $transaction_number,
                            'due_date' => date('Y-m-d'),
                            'status' => -1,
                            'creator_id'    => SESSION::get('uid')
                            );
                GenericModel::insert('purchase_order', $insert);


                //insert po list table
                $insert = array(
                        'uid'    => GenericModel::guid(),
                        'transaction_number' => $transaction_number,
                        'material_code' => 'purchase_book',
                        'material_specification'  => $jenis_pesanan,
                        'material_category'  => $category,
                        'quantity' => $quantity,
                        'purchase_price' => $price,
                        'purchase_currency' => 'idr',
                        'creator_id'    => SESSION::get('uid')
                        );
                 GenericModel::insert('purchase_order_list', $insert);

                //insert payment if lunas
                if ($lunas === 1) {
                    $insert = array(
                        'uid'    => GenericModel::guid(),
                        'transaction_code'    => $transaction_number,
                        'transaction_name'    => $jenis_pesanan,
                        'transaction_category'    => $category,
                        'transaction_type'    => 'purchase order',
                        'credit'    => ($price * $quantity),
                        'status' => 1,
                        'payment_due_date' => date('Y-m-d'),
                        'payment_disbursement'    => date('Y-m-d'),
                        'creator_id'    => SESSION::get('uid')
                    );

                    // Send Status insert to front end
                    QgenericModel::insert('payment_transaction', $insert); // use silent so inser to commerce not counted as item inserted to sales
                }
            }
        }
        Redirect::to('purchaseBook/order');
    }

    public function report($start_date = null, $end_date = null)
    {
        if (Request::post('change_date') == 'ok') { // user input change date
            Redirect::to('purchaseBook/report/' . Request::post('start_date') . '/' . Request::post('end_date'));
            exit;
        }

        if ($start_date === null AND $end_date === null) {
            $start_date = date('Y-m-01');
            $end_date = date('Y-m-d');
        }

        if (isset($_GET['find']) AND !empty($_GET['find'])) {
            $find = Request::get('find');
            $sql = "
            SELECT
                    `purchase_order`.`transaction_number`,
                    `purchase_order`.`supplier_id`,
                    `purchase_order_list`.`material_specification`,
                    `purchase_order_list`.`quantity`,
                    `purchase_order_list`.`purchase_price`,
                    `purchase_order_list`.`purchase_currency`,
                    `purchase_order`.`created_timestamp`,
                    `contact`.`contact_name`
                FROM
                    `purchase_order`
                LEFT JOIN
                    `contact` AS `contact` ON `contact`.`contact_id` = `purchase_order`.`customer_id`
                LEFT JOIN
                    `purchase_order_list` AS `purchase_order_list` ON `purchase_order_list`.`transaction_number` = `purchase_order`.`transaction_number`
                WHERE
                    `purchase_order`.`transaction_number` = '{$find}' OR
                    `purchase_order`.`supplier_id` = '{$find}' OR
                    `purchase_order_list`.`material_specification` LIKE '%{$find}%' OR
                    `contact`.`contact_name` LIKE '%{$find}%'
                ORDER BY
                    `purchase_order`.`created_timestamp` DESC";

            $sql_group_by_category = "
                SELECT
                    `purchase_category`,
                    SUM(`quantity` * `purchase_price`) AS `purchase`
                FROM
                    `purchase_order`
                LEFT JOIN
                    `contact` AS `contact` ON `contact`.`contact_id` = `purchase_order`.`customer_id`
                WHERE
                    `purchase_order`.`transaction_number` = '{$find}' OR
                    `purchase_order`.`customer_id` = '{$find}' OR
                    `purchase_order`.`purchase_name` LIKE '%{$find}%' OR
                    `purchase_order`.`purchase_category` LIKE '%{$find}%' OR
                    `contact`.`contact_name` LIKE '%{$find}%'
                GROUP BY `purchase_category`";

            $sql_group_by_week = "
                SELECT
                    CONCAT(YEAR(`purchase_order`.`created_timestamp`), '/', WEEK(`purchase_order`.`created_timestamp`)) as `week`,
                    SUM(`quantity` * `purchase_price`) AS `purchase`
                FROM
                    `purchase_order`
                LEFT JOIN
                    `contact` AS `contact` ON `contact`.`contact_id` = `purchase_order`.`customer_id`
                WHERE
                    `purchase_order`.`transaction_number` = '{$find}' OR
                    `purchase_order`.`customer_id` = '{$find}' OR
                    `purchase_order`.`purchase_name` LIKE '%{$find}%' OR
                    `purchase_order`.`purchase_category` LIKE '%{$find}%' OR
                    `contact`.`contact_name` LIKE '%{$find}%'
                GROUP BY `week`
                ORDER BY `week` ASC";

            $sql_group_by_month = "
                SELECT
                    CONCAT(YEAR(`purchase_order`.`created_timestamp`), '/', MONTH(`purchase_order`.`created_timestamp`)) as `month`,
                    SUM(`quantity` * `purchase_price`) AS `purchase`
                FROM
                    `purchase_order`
                LEFT JOIN
                    `contact` AS `contact` ON `contact`.`contact_id` = `purchase_order`.`customer_id`
                WHERE
                    `purchase_order`.`transaction_number` = '{$find}' OR
                    `purchase_order`.`customer_id` = '{$find}' OR
                    `purchase_order`.`purchase_name` LIKE '%{$find}%' OR
                    `purchase_order`.`purchase_category` LIKE '%{$find}%' OR
                    `contact`.`contact_name` LIKE '%{$find}%'
                GROUP BY `month`
                ORDER BY `month` ASC";

            $sql_group_by_month_and_category = "
                SELECT
                    CONCAT(YEAR(`purchase_order`.`created_timestamp`), '/', MONTH(`purchase_order`.`created_timestamp`)) as `month`,
                    `purchase_category`,
                    SUM(`quantity` * `purchase_price`) AS `purchase`
                FROM
                    `purchase_order`
                LEFT JOIN
                    `contact` AS `contact` ON `contact`.`contact_id` = `purchase_order`.`customer_id`
                WHERE
                    `purchase_order`.`transaction_number` = '{$find}' OR
                    `purchase_order`.`supplier_id` = '{$find}' OR
                    `purchase_order`.`purchase_name` LIKE '%{$find}%' OR
                    `purchase_order`.`purchase_category` LIKE '%{$find}%' OR
                    `contact`.`contact_name` LIKE '%{$find}%'
                GROUP BY `purchase_category`,`month`
                ORDER BY `month` ASC";
        } else {
            $sql = "
            SELECT
                    `purchase_order`.`transaction_number`,
                    `purchase_order`.`supplier_id`,
                    `purchase_order_list`.`material_specification`,
                    `purchase_order_list`.`material_category`,
                    `purchase_order_list`.`quantity`,
                    `purchase_order_list`.`purchase_price`,
                    `purchase_order_list`.`purchase_currency`,
                    `purchase_order`.`created_timestamp`,
                    `contact`.`contact_name`
                FROM
                    `purchase_order`
                LEFT JOIN
                    `contact` AS `contact` ON `contact`.`contact_id` = `purchase_order`.`supplier_id`
                LEFT JOIN
                    `purchase_order_list` AS `purchase_order_list` ON `purchase_order_list`.`transaction_number` = `purchase_order`.`transaction_number`
                WHERE
                    `purchase_order`.`transaction_number` LIKE '%/PB-%' AND (`purchase_order`.`created_timestamp` BETWEEN '$start_date 00:00:01' AND '$end_date 23:59:59')
                ORDER BY
                    `purchase_order`.`created_timestamp` DESC";

            $sql_group_by_category = "
                SELECT
                    `material_category`,
                    SUM(`quantity` * `purchase_price`) AS `purchase`
                FROM
                    `purchase_order_list`
                WHERE
                    `purchase_order_list`.`transaction_number` LIKE '%/PB-%' AND (`purchase_order_list`.`created_timestamp` BETWEEN '$start_date 00:00:00' AND '$end_date 23:59:59')
                GROUP BY `material_category`";

            $sql_group_by_week = "
                SELECT
                    CONCAT(YEAR(`created_timestamp`), '/', WEEK(`created_timestamp`)) as `week`,
                    SUM(`quantity` * `purchase_price`) AS `purchase`
                FROM
                    `purchase_order_list`
                WHERE
                    `purchase_order_list`.`transaction_number` LIKE '%/PB-%' AND (`purchase_order_list`.`created_timestamp` BETWEEN '$start_date 00:00:00' AND '$end_date 23:59:59')
                GROUP BY `week`
                ORDER BY `week` ASC";

            $sql_group_by_month = "
                SELECT
                    CONCAT(YEAR(`created_timestamp`), '/', MONTH(`created_timestamp`)) as `month`,
                    SUM(`quantity` * `purchase_price`) AS `purchase`
                FROM
                    `purchase_order_list`
                WHERE
                    `purchase_order_list`.`transaction_number` LIKE '%/PB-%' AND (`purchase_order_list`.`created_timestamp` BETWEEN '$start_date 00:00:00' AND '$end_date 23:59:59')
                GROUP BY `month`
                ORDER BY `month` ASC";

            $sql_group_by_month_and_category = "
                SELECT
                    CONCAT(YEAR(`created_timestamp`), '/', MONTH(`created_timestamp`)) as `month`,
                    `material_category`,
                    SUM(`quantity` * `purchase_price`) AS `purchase`
                FROM
                    `purchase_order_list`
                WHERE
                    `purchase_order_list`.`transaction_number` LIKE '%/PB-%' AND (`purchase_order_list`.`created_timestamp` BETWEEN '$start_date 00:00:00' AND '$end_date 23:59:59')
                GROUP BY `material_category`,`month`
                ORDER BY `month` ASC";

            $table = '`system_preference`';
            $where = "`category` = 'payment_type'";
            $field = "`item_name`";
            $payment_type = GenericModel::getAll($table, $where, $field);
        }

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

        $this->View->render('purchase_book/report',
            array(
            'title' => 'Daftar Transaksi dari tanggal ' . date("d F Y", strtotime($start_date)) . ' - ' . date("d F Y", strtotime($end_date)),
            'activelink1' => 'pembelian',
            'activelink2' => 'purchase book',
            'activelink3' => 'laporan purchase book',
            'header_script' => $header_script,
            'footer_script' => $footer_script,
            'transaction' => GenericModel::rawSelect($sql),
            'transaction_group_by_category' => GenericModel::rawSelect($sql_group_by_category),
            'transaction_group_by_week' => GenericModel::rawSelect($sql_group_by_week),
            'transaction_group_by_month' => GenericModel::rawSelect($sql_group_by_month),
            'transaction_group_by_month_and_category' => GenericModel::rawSelect($sql_group_by_month_and_category),
            'payment_type' => $payment_type,
            
            )
        );
    }

    public function insertPayment()
    {
        $transaction_number = Request::post('transaction_number');
        $totalrecord = count($_POST['value']);

        //get data from database
        $order = GenericModel::getOne('`purchase_order_list`', "`transaction_number` = '{$transaction_number}'", '*');

        //check if payment list total is same as total purchase
        $total_payment = 0;
        for ($i = 1; $i <= $totalrecord; $i++) {
            if ($_POST['value'][$i] > 0)
                { // only execute only when qty is not blank
                    $total_payment = $total_payment + (float)$_POST['value'][$i];

                    //check if there's empty schedule date and peyment type
                    if (empty($_POST['value'][$i]) OR empty($_POST['payment_due_date'][$i])) {
                        Session::add('feedback_negative', 'GAGAGL!. Jumlah pembayaran dan tanggal rencana pembayaran harus diisi.');
                        Redirect::to('purchaseBook/report');
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
                        'transaction_name'    => $order->material_specification,
                        'transaction_category'    => $order->material_category,
                        'transaction_type'    => 'purchase order',
                        'currency' => trim(strip_tags($_POST['currency'][$i])),
                        'payment_type' => trim(strip_tags($_POST['payment_type'][$i])),
                        'debit'    => trim(strip_tags($_POST['value'][$i])),
                        'payment_due_date' => date("Y-m-d", strtotime(trim(strip_tags($_POST['payment_due_date'][$i])))),
                        'note' => trim(strip_tags($_POST['note'][$i])),
                        'creator_id'    => SESSION::get('uid')
                    );
                    GenericModel::insert('payment_transaction', $insert);
                } // END IF
        } // END FOR
        
       Redirect::to('purchaseBook/report');
    }

    /**
     * Perform the upload image
     * POST-request
     */
    public function uploadImage()
    {
        $transaction_number = Request::post('transaction_number');

        if (empty($transaction_number)) {
            Redirect::to('purchaseBook/report');
            Session::add('feedback_negative', 'GAGAL!, upload file tidak berhasil');
        }

        $image_name = 'file_name';
        $image_rename = Request::post('image_name');
        $destination = 'purchase-book';
        $note = Request::post('note');
        UploadModel::uploadImage($image_name, $image_rename, $destination, $transaction_number, $note);
        Redirect::to('purchaseBook/report');
    }

    /**
     * Perform the upload image
     * POST-request
     */
    public function uploadedImageList()
    {
        $transaction_number = urldecode(Request::get('transaction_number'));
        $uploaded_file = "SELECT `item_name`, `item_id`, `value`, `uid`, `note`  FROM `upload_list` WHERE `category` =  'purchase-book' AND `item_id` = '{$transaction_number}' AND `is_deleted` = 0";

        $this->View->render('purchase_book/uploaded_image_list',
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

    public function deleteOrder()
    {
        $transaction_number = urldecode(Request::get('transaction_number'));
        GenericModel::remove('purchase_order', 'transaction_number', $transaction_number, false); //false for silent feedback
        GenericModel::remove('purchase_order_list', 'transaction_number', $transaction_number, false); //false for silent feedback
        GenericModel::remove('payment_transaction', 'transaction_code', $transaction_number, false); //false for silent feedback
        Redirect::to(Request::get('forward'));
    }

    public function notaPembelian() {

        $pengeluaran = "SELECT * FROM `system_preference` WHERE (`category` = 'direct_expense_transaction')";
        $header_script = '<link rel="stylesheet" href="' . Config::get('URL') . 'bootstrap-3.3.7/css/excel-2007.css" media="screen"/>';

        $this->View->render('finance/credit_transaction',
            array(
                'title' => 'Buat Transaksi Kas Baru',
                'activelink1' => 'finance',
                'activelink2' => 'transaksi langsung',
                'activelink3' => 'buat transaksi',
                'header_script' => $header_script,
                'pengeluaran' => GenericModel::rawSelect($pengeluaran),
            )
        );
    }
}