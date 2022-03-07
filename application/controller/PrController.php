<?php


class PrController extends Controller
{
    /**
     * Construct this object by extending the basic Controller class
     */
    public function __construct()
    {
        parent::__construct();
        Auth::checkAuthentication();
        Auth::checkPermission('director,management,finance,purchasing,ppic,qc');
    }

    //create PR from production

    public function createPrFromJo()
    {
        $transaction_reference = urldecode(Request::get('transaction_reference'));
        
        $material_list = "SELECT
                    `job_order_material_list`.`job_number`,
                    `job_order_material_list`.`material_code`,
                    SUM(`job_order_material_list`.`quantity`) AS `quantity`,
                    `material_list`.`material_name`,
                    `material_list`.`unit`,
                    `material_list`.`minimum_balance`,
                    `stock`.`quantity_stock`
                FROM
                    `job_order_material_list`
                LEFT JOIN
                    `material_list` ON `material_list`.`material_code` = `job_order_material_list`.`material_code`
                LEFT JOIN
                    (SELECT
                        `material_list_in`.`material_code`,
                        SUM(`material_list_in`.`quantity_stock`) AS `quantity_stock`
                    FROM
                        `material_list_in`
                    GROUP  BY
                        `material_list_in`.`material_code`) AS `stock` ON `stock`.`material_code` = `material_list`.`material_code`
                WHERE
                    `job_order_material_list`.`job_number` = '{$transaction_reference}'
                GROUP BY
                    `job_order_material_list`.`material_code`
                ORDER BY
                    `material_list`.`material_name`
                ";

        $header_script = '<link rel="stylesheet" href="' . Config::get('URL') . 'bootstrap-3.3.7/css/datepicker.css" media="screen"/>
        <link rel="stylesheet" href="' . Config::get('URL') . 'bootstrap-3.3.7/css/excel-2007.css" media="screen"/>';
        $footer_script = '<script src="' . Config::get('URL') . 'bootstrap-3.3.7/js/bootstrap-datepicker.js"></script>
<script>
function checkAll(ele) {
     var checkboxes = document.getElementsByTagName("input");
     if (ele.checked) {
         for (var i = 0; i < checkboxes.length; i++) {
             if (checkboxes[i].type == "checkbox") {
                 checkboxes[i].checked = true;
             }
         }
     } else {
         for (var i = 0; i < checkboxes.length; i++) {
             console.log(i)
             if (checkboxes[i].type == "checkbox") {
                 checkboxes[i].checked = false;
             }
         }
     }
 }
        </script>';
        $this->View->render('pr/create_pr_from_jo',
            array(
            'header_script' => $header_script,
            'footer_script' => $footer_script,
            'title' => 'Buat PR dari Job Order Produksi: ' . $transaction_reference,
            'activelink1' => 'Manufacturing',
            'activelink2' => 'Production',
            'transaction_reference' => $transaction_reference,
            'material_list' => GenericModel::rawSelect($material_list),
            )
        );
    }

    public function pr($page = 1, $limit = 40)
    {
        $offset = ($page > 1) ? ($page - 1) * $limit : 0;
        $sql_group = "
                SELECT
                    `material_list`.`material_name`,
                    `purchase_request`.`transaction_reference`,
                    `purchase_request_list`.`transaction_number`,
                    `purchase_request_list`.`material_code`,
                    `purchase_request_list`.`material_specification`,
                    `purchase_request_list`.`uid`,
                    `purchase_request_list`.`quantity`,
                    `purchase_request_list`.`unit`,
                    `purchase_request_list`.`note`,
                    `purchase_request_list`.`created_timestamp`,            
                    `users`.`full_name`
                FROM
                    `purchase_request_list`
                JOIN
                    `purchase_request`
                    ON
                    `purchase_request`.`transaction_number` = `purchase_request_list`.`transaction_number`
                LEFT JOIN
                    `material_list` AS `material_list`
                    ON
                    `purchase_request_list`.`material_code` = `material_list`.`material_code`
                JOIN
                    `users` AS `users`
                    ON
                    `users`.`uid` = `purchase_request_list`.`creator_id`
                WHERE
                    `purchase_request_list`.`status` = 'waiting'
                ORDER BY
                    `purchase_request_list`.`created_timestamp` ASC
                LIMIT
                $offset, $limit";

        //Pagination
        $total_record = GenericModel::rowCount('`purchase_request_list`', "`status` = 'waiting'");
        $pagination = FormaterModel::pagination('pr/pr', $total_record, $page, $limit);

        $header_script = '<link rel="stylesheet" href="' . Config::get('URL') . 'bootstrap-3.3.7/css/excel-2007.css" media="screen"/><link rel="stylesheet" href="' . Config::get('URL') . 'bootstrap-3.3.7/css/datepicker.css" media="screen"/>';
        $footer_script = '<script src="' . Config::get('URL') . 'bootstrap-3.3.7/js/bootstrap-datepicker.js"></script>
<script>
function checkAll(ele) {
     var checkboxes = document.getElementsByTagName("input");
     if (ele.checked) {
         for (var i = 0; i < checkboxes.length; i++) {
             if (checkboxes[i].type == "checkbox") {
                 checkboxes[i].checked = true;
             }
         }
     } else {
         for (var i = 0; i < checkboxes.length; i++) {
             console.log(i)
             if (checkboxes[i].type == "checkbox") {
                 checkboxes[i].checked = false;
             }
         }
     }
 }
        </script>';
        $this->View->render('pr/pr',
                  array(
                'header_script' => $header_script,
                'footer_script' => $footer_script,
                'title' => 'Draft Purchase Request',
                'activelink1' => 'supply chain',
                'activelink2' => 'pr/po',
                'activelink3' => 'purchase request',
                'activelink4' => 'waiting request',
                'page' => $page,
                'limit' => $limit,
                'po_list' => GenericModel::rawSelect($sql_group),
                'pagination' => $pagination,
            ));
    }

    public function processed($page = 1, $limit = 50)
    {
        $offset = ($page > 1) ? ($page - 1) * $limit : 0;
        if (isset($_GET['find']) AND !empty($_GET['find'])) {
            $find = strtolower($_GET['find']);
            $find = trim($find);
            $find = urldecode($find);

            $sql_group = "
                    SELECT
                        `material_list`.`material_name`,
                        `purchase_request`.`transaction_reference`,
                        `purchase_request_list`.`transaction_number`,
                        `purchase_request_list`.`transaction_number_created`,
                        `purchase_request_list`.`material_code`,
                        `purchase_request_list`.`material_specification`,
                        `purchase_request_list`.`uid`,
                        `purchase_request_list`.`quantity`,
                        `purchase_request_list`.`unit`,
                        `purchase_request_list`.`note`,
                        `purchase_request_list`.`created_timestamp`,            
                        `users`.`full_name`
                    FROM
                        `purchase_request_list`
                    JOIN
                        `purchase_request` ON `purchase_request`.`transaction_number` = `purchase_request_list`.`transaction_number`
                    LEFT JOIN
                        `material_list` AS `material_list` ON `purchase_request_list`.`material_code` = `material_list`.`material_code`
                    JOIN
                        `users` AS `users` ON `users`.`uid` = `purchase_request_list`.`creator_id`
                    WHERE
                        `purchase_request_list`.`status` = 'processed'
                            AND
                        (
                            `purchase_request_list`.`transaction_number` = '{$find}' OR
                            `purchase_request_list`.`material_code` = '{$find}' OR
                            `purchase_request_list`.`material_specification` LIKE '%{$find}%' OR
                            `purchase_request`.`transaction_reference` = '%{$find}%' OR
                            `material_list`.`material_name` LIKE '%{$find}%'
                        )
                    ORDER BY
                        `purchase_request_list`.`created_timestamp` ASC
                    LIMIT
                        $offset, $limit";

            //For pagination
            $total_record = GenericModel::rowCount(
                            '`purchase_request_list`
                                JOIN
                                    `purchase_request` ON `purchase_request`.`transaction_number` = `purchase_request_list`.`transaction_number`
                                LEFT JOIN
                                    `material_list` AS `material_list` ON `purchase_request_list`.`material_code` = `material_list`.`material_code`',
                            "`purchase_request_list`.`status` = 'processed'
                                AND
                            (
                                `purchase_request_list`.`transaction_number` = '{$find}' OR
                                `purchase_request_list`.`material_code` = '{$find}' OR
                                `purchase_request_list`.`material_specification` LIKE '%{$find}%' OR
                                `purchase_request`.`transaction_reference` = '%{$find}%' OR
                                `material_list`.`material_name` LIKE '%{$find}%'
                            )",
                            '`purchase_request_list`.*'
                    );
            $pagination = FormaterModel::pagination('pr/processed', $total_record, $page, $limit, '&find=' . urldecode($find));
        } else {
            $sql_group = "
                    SELECT
                        `material_list`.`material_name`,
                        `purchase_request`.`transaction_reference`,
                        `purchase_request_list`.`transaction_number`,
                        `purchase_request_list`.`transaction_number_created`,
                        `purchase_request_list`.`material_code`,
                        `purchase_request_list`.`material_specification`,
                        `purchase_request_list`.`uid`,
                        `purchase_request_list`.`quantity`,
                        `purchase_request_list`.`unit`,
                        `purchase_request_list`.`note`,
                        `purchase_request_list`.`created_timestamp`,            
                        `users`.`full_name`
                    FROM
                        `purchase_request_list`
                    JOIN
                        `purchase_request` ON `purchase_request`.`transaction_number` = `purchase_request_list`.`transaction_number`
                    LEFT JOIN
                        `material_list` AS `material_list` ON `purchase_request_list`.`material_code` = `material_list`.`material_code`
                    JOIN
                        `users` AS `users` ON `users`.`uid` = `purchase_request_list`.`creator_id`
                    WHERE
                        `purchase_request_list`.`status` = 'processed'
                    ORDER BY
                        `purchase_request_list`.`created_timestamp` ASC
                    LIMIT
                        $offset, $limit";

            //For pagination
            $total_record = GenericModel::rowCount('`purchase_request_list`', "`purchase_request_list`.`status` = 'processed'", '`transaction_number`');
            $pagination = FormaterModel::pagination('pr/processed', $total_record, $page, $limit);
        }
        

        $header_script = '<link rel="stylesheet" href="' . Config::get('URL') . 'bootstrap-3.3.7/css/excel-2007.css" media="screen"/><link rel="stylesheet" href="' . Config::get('URL') . 'bootstrap-3.3.7/css/datepicker.css" media="screen"/>';
        $this->View->render('pr/processed',
                  array(
                'header_script' => $header_script,
                'title' => 'Processed Purchase Request',
                'activelink1' => 'supply chain',
                'activelink2' => 'pr/po',
                'activelink3' => 'purchase request',
                'activelink4' => 'processed request',
                'po_list' => GenericModel::rawSelect($sql_group),
                'page' => $page,
                'limit' => $limit,
                'pagination' => $pagination,
            ));
    }

    public function createDraftPo() {
        if (Request::post('button-action') == 'Buat Draft PO') {
            // Get latest PO Number, format PR is TBE 0013/PO-0315
            $table = '`purchase_order`';
            $where = "`transaction_number` LIKE '%/PO-%' ORDER BY `created_timestamp` DESC";
            $field = "`transaction_number`";
            $pr_data = GenericModel::getOne($table, $where, $field);
            $po_number = $pr_data->transaction_number;
            $find_integer = explode('/', $po_number);
            $po_number = $find_integer[0];
            $po_number = (integer) FormaterModel::getNumberOnly($po_number);
            $po_number = $po_number + 1;
            $po_number = "00000".$po_number;
            $po_number = substr($po_number, strlen($po_number)-5, 5);
            $po_number = Config::get('COMPANY_CODE') . ' ' . $po_number . '/PO-' . date("m") . date("y");

            $log = '<li><span class="badge badge-grey">' . SESSION::get('user_name') .'</span> creates Purchase Request (' . date("Y-m-d") . ')</li>';

            //Insert purchase list with material code
            foreach ($_POST['uid'] as $key => $value) {
                if (!empty($_POST['material_code'][$key])) {
                    //echo $_POST['material_code'][$key];
                    //cari data pembelian terakhir
                    $material_code = FormaterModel::sanitize($_POST['material_code'][$key]);
                    $last_po = GenericModel::getOne('`purchase_order_list` JOIN `purchase_order` ON `purchase_order`.`transaction_number` = `purchase_order_list`.`transaction_number`', "`purchase_order_list`.`material_code` = '{$material_code}' AND `purchase_order`.`status` >= 0 ORDER BY `purchase_order`.`created_timestamp` DESC", '`purchase_order_list`.`purchase_price`, `purchase_order_list`.`purchase_price_discount`, `purchase_order_list`.`purchase_currency`, `purchase_order_list`.`purchase_tax`,
                        `purchase_order_list`.`packaging`,
                        `purchase_order_list`.`material_specification`,
                        `purchase_order`.`supplier_id`
                        ');
                    $note = '';
                    if (!empty($_POST['note'][$key])) {
                        $note = FormaterModel::sanitize($_POST['note'][$key]) . '.';
                    }
                    if (!empty($_POST['transaction_reference'][$key])) {
                        $note .= '. Transaction Reference: ' . FormaterModel::sanitize($_POST['transaction_reference'][$key]);
                    }

                    $insert = array(
                        'uid' => GenericModel::guid(),
                        'transaction_number'  => $po_number,
                        'material_code' => $material_code,
                        'purchase_price' => $last_po->purchase_price,
                        'purchase_price_discount' => $last_po->purchase_price_discount,
                        'purchase_currency' => $last_po->purchase_currency,
                        'purchase_tax' => $last_po->purchase_tax,
                        'packaging' => $last_po->packaging,
                        'material_specification' => $last_po->material_specification,
                        'quantity' => FormaterModel::sanitize($_POST['quantity'][$key]),
                        'unit' => FormaterModel::sanitize($_POST['unit'][$key]),
                        'note' => $note,
                    );
                    GenericModel::insert('purchase_order_list', $insert);
                    //GenericModel::remove('purchase_request_list', 'uid', FormaterModel::sanitize($_POST['uid'][$key]), false);

                    $update = array(
                        'transaction_number_created' => $po_number,
                        'status' => 'processed',
                        );
                    $uid = FormaterModel::sanitize($_POST['uid'][$key]);
                    $cond = "`uid` = '{$uid}'";
                    GenericModel::update('purchase_request_list', $update, $cond, false); //false silent feedback
                } elseif (!empty($_POST['material_specification'][$key])) {
                    //echo $_POST['material_code'][$key];
                    $insert = array(
                        'uid' => GenericModel::guid(),
                        'transaction_number'  => $po_number,
                        'budget_item' => FormaterModel::sanitize($_POST['material_specification'][$key]),
                        'quantity' => FormaterModel::sanitize($_POST['quantity'][$key]),
                        'unit' => FormaterModel::sanitize($_POST['unit'][$key]),
                        'note' => 'Transaction Reference: ' . FormaterModel::sanitize($_POST['transaction_reference'][$key]),
                    );
                    GenericModel::insert('purchase_order_list', $insert);

                    $update = array(
                        'transaction_number_created' => $po_number,
                        'status' => 'processed',
                        );
                    $uid = FormaterModel::sanitize($_POST['uid'][$key]);
                    $cond = "`uid` = '{$uid}'";
                    GenericModel::update('purchase_request_list', $update, $cond, false); //false silent feedback
                }
            }

            $insert = array(
                        'transaction_number' => $po_number,
                        'supplier_id' => $last_po->supplier_id,
                        'due_date' => date('Y-m-d'),
                        'log' => addslashes($log),
                        'creator_id'    => SESSION::get('uid')
                        );
            GenericModel::insert('purchase_order', $insert);
            Redirect::to('po/detail?po_number=' . urlencode($po_number));

        }  elseif (Request::post('button-action') == 'Buat Nota Pembelian') {
            // Get latest draft nota pembelian (DNP)
            $table = '`purchase_request`';
            $where = "`transaction_number` LIKE '%/DNP-%' ORDER BY `created_timestamp` DESC";
            $field = "`transaction_number`";
            $pr_data = GenericModel::getOne($table, $where, $field);
            $po_number = $pr_data->transaction_number;
            $find_integer = explode('/', $po_number);
            $po_number = $find_integer[0];
            $po_number = (integer) FormaterModel::getNumberOnly($po_number);
            $po_number = $po_number + 1;
            $po_number = "00000".$po_number;
            $po_number = substr($po_number, strlen($po_number)-5, 5);
            $po_number = Config::get('COMPANY_CODE') . ' ' . $po_number . '/DNP-' . date("m") . date("y");

            $insert = array(
                        'transaction_number' => $po_number,
                        'creator_id'    => SESSION::get('uid')
                        );
            GenericModel::insert('purchase_request', $insert);

            foreach ($_POST['uid'] as $key => $value) {
                if (!empty($_POST['uid'][$key])) {
                    $update = array(
                        'transaction_number_created' => $po_number,
                        );
                    $uid = FormaterModel::sanitize($_POST['uid'][$key]);
                    $cond = "`uid` = '{$uid}'";
                    GenericModel::update('purchase_request_list', $update, $cond, false); //false silent feedback
                }
            }

            Redirect::to('NotaPembelian/makeNew/?transaction_reference=' . urlencode($po_number));
        } elseif (Request::post('button-action') == 'Delete') {
            //Insert Users
            foreach ($_POST['uid'] as $key => $value) {
                if (!empty($_POST['uid'][$key])) {
                    GenericModel::remove('purchase_request_list', 'uid', FormaterModel::sanitize($_POST['uid'][$key]), false);
                }
            }
            Redirect::to('pr/pr');
        }
        
    }

    public function newPr()
    {

        $this->View->render('pr/new_pr',
            array(
                'title' => 'Buat Draft PR (Purchase Request)',
                'activelink1' => 'supply chain',
                'activelink2' => 'pr/po',
                'activelink3' => 'purchase request',
                'material_list' => GenericModel::getAll('material_list', "`is_deleted` = 0 AND (`material_type` = 1 OR `material_type` = 4 OR `material_type` = 7)", "`material_code`, `material_name`"),
            )
        );
    }

    public function newPrAction()
    {
        if (!empty($_POST['order_name']) AND $_POST['quantity']) {

            // Get latest PR Number, format PR is TBE 0013/PO-0315
            $table = '`purchase_request`';
            $where = "`transaction_number` LIKE '%/PR-%' ORDER BY `created_timestamp` DESC";
            $field = "`transaction_number`";
            $pr_data = GenericModel::getOne($table, $where, $field);
            $pr_number = $pr_data->transaction_number;
            $find_integer = explode('/', $pr_number);
            $pr_number = $find_integer[0];
            $pr_number = (integer) FormaterModel::getNumberOnly($pr_number);
            $pr_number = $pr_number + 1;
            $pr_number = "00000".$pr_number;
            $pr_number = substr($pr_number, strlen($pr_number)-5, 5);
            $pr_number = Config::get('COMPANY_CODE') . ' ' . $pr_number . '/PR-' . date("m") . date("y");

            $insert = array(
                        'transaction_reference' => Request::post('transaction_reference'),
                        'transaction_number' => $pr_number,
                        'creator_id'    => SESSION::get('uid')
                        );
            GenericModel::insert('purchase_request', $insert);

            //Insert Users
            foreach ($_POST['order_name'] as $key => $value) {
                $order_name = FormaterModel::sanitize($_POST['order_name'][$key]);
                $order_name = explode('---', $order_name);

                //pakai material code atau tidak
                if (empty(trim($order_name[1]))) {
                    $material_code = '';
                    $material_specification = trim($order_name[0]);
                } else {
                    $material_code = trim($order_name[0]);
                    $material_specification ='';
                }

                if (!empty($material_code) OR !empty($material_specification)) {
                    $insert = array(
                        'uid' => GenericModel::guid(),
                        'transaction_number'    => $pr_number,
                        'material_code' => $material_code,
                        'material_specification' => $material_specification,
                        'quantity' => FormaterModel::sanitize($_POST['quantity'][$key]),
                        'unit' => FormaterModel::sanitize($_POST['unit'][$key]),
                        'note' => FormaterModel::sanitize($_POST['note'][$key]),
                        'creator_id'    => SESSION::get('uid'),
                    );
                    GenericModel::insert('purchase_request_list', $insert);
                }
            }


            //GET RELATED Departemen email
            $email_address = GenericModel::getAll('users', "`department` = 'purchasing'", '`email`');
            $email = array();
            foreach ($email_address as $key => $value) {
                $email[] = $value->email;
            }
            //tambah pt. ilmui email
            $email[] = 'edi.subakir@outlook.com';

            $email_creator = SESSION::get('full_name');
            $email_subject = "New Purchase Request From " . $email_creator;
            $body = ucwords($email_creator) . ' membuat purchase request baru. Klik link berikut ini untuk melihat detail permintaan pembelian yang diminta: ' .   Config::get('URL') . 'pr/pr/';
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

        Redirect::to('pr/pr');
    }


}
