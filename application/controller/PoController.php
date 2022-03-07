<?php


class PoController extends Controller
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

    
    public function index($page = 1, $limit = 20)
    {
        $name = strtolower($_GET['find']);
        $name = trim($name);
        $find = urldecode($name);
        // pagination
        $offset = ($page > 1) ? ($page - 1) * $limit : 0;
        $prev = Config::get('URL') . 'po/index/find/' . ($page - 1) . '/' . $limit . '/?find=' . $name;
        $next = Config::get('URL') . 'po/index/find/' . ($page + 1). '/' . $limit . '/?find=' . $name;

        $sql_group = "
            SELECT
                `purchase_order`.`transaction_number`,
                `purchase_order`.`feedback_note`,
                `purchase_order`.`created_timestamp`,
                `purchase_order`.`supplier_id`,
                `purchase_order`.`status`,
                GROUP_CONCAT(IF(`material_list`.`material_name` != '', `material_list`.`material_name`, 'kosong') SEPARATOR '-, -') as `material_name`,
                GROUP_CONCAT(IF(`purchase_order_list`.`material_code` != '', `purchase_order_list`.`material_code`, `purchase_order_list`.`budget_item`) SEPARATOR '-, -') as `material_code`,
                GROUP_CONCAT(`purchase_order_list`.`quantity` SEPARATOR '-, -') as `quantity`,
                GROUP_CONCAT(`purchase_order_list`.`purchase_price` SEPARATOR '-, -') as `purchase_price`,
                GROUP_CONCAT(`purchase_order_list`.`purchase_tax` SEPARATOR '-, -') as `purchase_tax`,                    
                `users`.`full_name`,
                `contact`.`contact_name`
            FROM
                `purchase_order`
            LEFT JOIN
                `purchase_order_list` AS `purchase_order_list` ON `purchase_order_list`.`transaction_number` = `purchase_order`.`transaction_number`
            LEFT JOIN
                `material_list` AS `material_list` ON `purchase_order_list`.`material_code` = `material_list`.`material_code`
            LEFT JOIN
                `contact` AS `contact` ON `contact`.`contact_id` = `purchase_order`.`supplier_id`
            LEFT JOIN
                `users` AS `users` ON `users`.`uid` = `purchase_order`.`creator_id`
            WHERE
                `purchase_order`.`transaction_number` = '{$find}' OR
                `purchase_order`.`supplier_id` = '{$find}' OR
                `contact`.`contact_name` LIKE '%{$find}%' OR
                `material_list`.`material_name` LIKE '%{$find}%' OR
                `purchase_order_list`.`budget_item` LIKE '%{$find}%' OR
                `purchase_order_list`.`material_code` = '{$find}'

            GROUP BY
                `purchase_order`.`transaction_number`
            ORDER BY
                `purchase_order`.`created_timestamp` DESC
            LIMIT
                $offset, $limit";
            $po_list = GenericModel::rawSelect($sql_group);

        //For pagination
        $table = '`purchase_order`
            LEFT JOIN
                `purchase_order_list` AS `purchase_order_list` ON `purchase_order_list`.`transaction_number` = `purchase_order`.`transaction_number`
            LEFT JOIN
                `material_list` AS `material_list` ON `purchase_order_list`.`material_code` = `material_list`.`material_code`
            LEFT JOIN
                `contact` AS `contact` ON `contact`.`contact_id` = `purchase_order`.`supplier_id`
            LEFT JOIN
                `users` AS `users` ON `users`.`uid` = `purchase_order`.`creator_id`';
        $where = "`purchase_order`.`transaction_number` = '{$find}' OR
                `purchase_order`.`supplier_id` = '{$find}' OR
                `contact`.`contact_name` LIKE '%{$find}%' OR
                `material_list`.`material_name` LIKE '%{$find}%' OR
                `purchase_order_list`.`material_code` = '{$find}'";

        $total_record = GenericModel::rowCount($table, $where, '*');
        $find = '?find=' . $find;
        $pagination = FormaterModel::pagination('po/index', $total_record, $page, $limit, $find);
        
        $header_script = '<link rel="stylesheet" href="' . Config::get('URL') . 'bootstrap-3.3.7/css/excel-2007.css" media="screen"/><link rel="stylesheet" href="' . Config::get('URL') . 'bootstrap-3.3.7/css/datepicker.css" media="screen"/>';
        $footer_script = '<script src="' . Config::get('URL') . 'bootstrap-3.3.7/js/bootstrap-datepicker.js"></script>
        <script>
        $(".datepicker").datepicker(); //date picker
        </script>';

        $this->View->render('po/approved',
                  array(
                'header_script' => $header_script,
                'footer_script' => $footer_script,
                'title' => 'Search Purchase Order',
                'header_script' => $header_script,
                'page' => $page,
                'limit' => $limit,
                'activelink1' => 'supply chain',
                'activelink2' => 'pr/po',
                'activelink3' => 'purchase order',
                'po_list' => $po_list,
                'pagination' => $pagination,
            ));
    }

    public function draftOrder($page = 1, $limit = 20)
    {
        //po list
        $offset = ($page > 1) ? ($page - 1) * $limit : 0;
        $sql_group = "
                SELECT
                    `purchase_order`.`transaction_number`,
                    `purchase_order`.`feedback_note`,
                    `purchase_order`.`created_timestamp`,
                    `purchase_order`.`supplier_id`,
                    `purchase_order`.`status`,
                    GROUP_CONCAT(IF(`material_list`.`material_name` != '', `material_list`.`material_name`, 'kosong') SEPARATOR '-, -') as `material_name`,
                    GROUP_CONCAT(IF(`purchase_order_list`.`material_code` != '', `purchase_order_list`.`material_code`, `purchase_order_list`.`budget_item`) SEPARATOR '-, -') as `material_code`,
                    GROUP_CONCAT(`purchase_order_list`.`quantity` SEPARATOR '-, -') as `quantity`,
                    GROUP_CONCAT(`purchase_order_list`.`purchase_price` SEPARATOR '-, -') as `purchase_price`,
                    GROUP_CONCAT(`purchase_order_list`.`purchase_tax` SEPARATOR '-, -') as `purchase_tax`,                    
                    `users`.`full_name`,
                    `contact`.`contact_name`
                FROM
                    `purchase_order`
                LEFT JOIN
                    `purchase_order_list` AS `purchase_order_list` ON `purchase_order_list`.`transaction_number` = `purchase_order`.`transaction_number`
                LEFT JOIN
                    `material_list` AS `material_list` ON `purchase_order_list`.`material_code` = `material_list`.`material_code`
                
                LEFT JOIN
                    `contact` AS `contact` ON `contact`.`contact_id` = `purchase_order`.`supplier_id`
                LEFT JOIN
                    `users` AS `users` ON `users`.`uid` = `purchase_order`.`creator_id`
                WHERE
                    `purchase_order`.`status` <= -2
                GROUP BY
                    `purchase_order`.`transaction_number`
                ORDER BY
                    `purchase_order`.`created_timestamp` DESC
                LIMIT
                    $offset, $limit";

        //For pagination
        $total_record = GenericModel::rowCount('`purchase_order`', '`status` <= -2', '`transaction_number`');

        $header_script = '<link rel="stylesheet" href="' . Config::get('URL') . 'bootstrap-3.3.7/css/excel-2007.css" media="screen"/><link rel="stylesheet" href="' . Config::get('URL') . 'bootstrap-3.3.7/css/datepicker.css" media="screen"/>';
        $footer_script = '<script src="' . Config::get('URL') . 'bootstrap-3.3.7/js/bootstrap-datepicker.js"></script>
        <script>
        $(".datepicker").datepicker(); //date picker
        </script>';

        $this->View->render('po/pr',
                  array(
                'header_script' => $header_script,
                'footer_script' => $footer_script,
                'title' => 'Purchase Request',
                'activelink1' => 'supply chain',
                'activelink2' => 'pr/po',
                'activelink3' => 'Draft PO',
                'page' => $page,
                'limit' => $limit,
                'po_list' => GenericModel::rawSelect($sql_group),
                'pagination' => FormaterModel::pagination('po/pr', $total_record, $page, $limit)
            ));
    }

    public function waitingApproval($page = 1, $limit = 20)
    {
        //po list
        $offset = ($page > 1) ? ($page - 1) * $limit : 0;
        $po_list = "
                SELECT
                    `purchase_order`.`transaction_number`,
                    `purchase_order`.`feedback_note`,
                    `purchase_order`.`created_timestamp`,
                    `purchase_order`.`supplier_id`,
                    `purchase_order`.`status`,
                    GROUP_CONCAT(IF(`material_list`.`material_name` != '', `material_list`.`material_name`, 'kosong') SEPARATOR '-, -') as `material_name`,
                    GROUP_CONCAT(IF(`purchase_order_list`.`material_code` != '', `purchase_order_list`.`material_code`, `purchase_order_list`.`budget_item`) SEPARATOR '-, -') as `material_code`,
                    GROUP_CONCAT(`purchase_order_list`.`quantity` SEPARATOR '-, -') as `quantity`,
                    GROUP_CONCAT(`purchase_order_list`.`purchase_price` SEPARATOR '-, -') as `purchase_price`,
                    GROUP_CONCAT(`purchase_order_list`.`purchase_currency` SEPARATOR '-, -') as `purchase_currency`,
                    GROUP_CONCAT(`purchase_order_list`.`purchase_tax` SEPARATOR '-, -') as `purchase_tax`,
                    GROUP_CONCAT(`purchase_order_list`.`purchase_currency_rate` SEPARATOR '-, -') as `purchase_currency_rate`,
                    `users`.`full_name`,
                    `contact`.`contact_name`
                FROM
                    `purchase_order`
                LEFT JOIN
                    `purchase_order_list` AS `purchase_order_list` ON `purchase_order_list`.`transaction_number` = `purchase_order`.`transaction_number`
                LEFT JOIN
                    `material_list` AS `material_list` ON `purchase_order_list`.`material_code` = `material_list`.`material_code`
                
                LEFT JOIN
                    `contact` AS `contact` ON `contact`.`contact_id` = `purchase_order`.`supplier_id`
                LEFT JOIN
                    `users` AS `users` ON `users`.`uid` = `purchase_order`.`creator_id`
                WHERE
                    `purchase_order`.`status` = -1
                GROUP BY
                    `purchase_order`.`transaction_number`
                ORDER BY
                    `purchase_order`.`created_timestamp` DESC
                LIMIT
                    $offset, $limit";

        $nota_pembelian_list = "
            SELECT
                    `payment_transaction`.`uid`,
                    `payment_transaction`.`transaction_code`,
                    `payment_transaction`.`credit`,
                    `payment_transaction`.`currency`,
                    `payment_transaction`.`transaction_name`,
                    `payment_transaction`.`transaction_category`,
                    `payment_transaction`.`transaction_type`,
                    `payment_transaction`.`note`,
                    `payment_transaction`.`created_timestamp`
                FROM
                    `payment_transaction`
                WHERE
                    `payment_transaction`.`transaction_type` = 'nota pembelian' AND `payment_transaction`.`status` = -1
            ORDER BY `payment_transaction`.`created_timestamp` DESC";

        $change_po_list = "
            SELECT
                `purchase_order`.`transaction_number`,
                `purchase_order`.`feedback_note`,
                `purchase_order`.`created_timestamp`,
                `purchase_order`.`supplier_id`,
                `purchase_order`.`status`,
                GROUP_CONCAT(IF(`material_list`.`material_name` != '', `material_list`.`material_name`, 'kosong') SEPARATOR '-, -') as `material_name`,
                GROUP_CONCAT(IF(`purchase_order_list`.`material_code` != '', `purchase_order_list`.`material_code`, `purchase_order_list`.`budget_item`) SEPARATOR '-, -') as `material_code`,
                GROUP_CONCAT(`purchase_order_list`.`quantity` SEPARATOR '-, -') as `quantity`,
                GROUP_CONCAT(IFNULL(`table_penerimaan`.`quantity_received`, 0) SEPARATOR '-, -') as `quantity_received`,
                GROUP_CONCAT(`purchase_order_list`.`status` SEPARATOR '-, -') as `receive_status`,
                GROUP_CONCAT(`purchase_order_list`.`purchase_price` SEPARATOR '-, -') as `purchase_price`,
                GROUP_CONCAT(`purchase_order_list`.`purchase_tax` SEPARATOR '-, -') as `purchase_tax`,                    
                `users`.`full_name`,
                `contact`.`contact_name`
            FROM
                `purchase_order`
            LEFT JOIN
                `purchase_order_list` AS `purchase_order_list` ON `purchase_order_list`.`transaction_number` = `purchase_order`.`transaction_number`
            LEFT JOIN
                `material_list` AS `material_list` ON `purchase_order_list`.`material_code` = `material_list`.`material_code`
            LEFT JOIN (
                    SELECT
                        `material_list_in`.`material_code`,
                        `material_list_in`.`transaction_number`,
                        SUM(`material_list_in`.`quantity_received`) AS `quantity_received`
                    FROM
                        `material_list_in`
                    GROUP BY
                        `material_list_in`.`material_code`,
                        `material_list_in`.`transaction_number`
                    ORDER BY
                        `material_list_in`.`material_code`
                ) AS `table_penerimaan` ON `table_penerimaan`.`material_code` = `purchase_order_list`.`material_code` AND `table_penerimaan`.`transaction_number` = `purchase_order_list`.`transaction_number`
            LEFT JOIN
                `contact` AS `contact` ON `contact`.`contact_id` = `purchase_order`.`supplier_id`
            LEFT JOIN
                `users` AS `users` ON `users`.`uid` = `purchase_order`.`creator_id`
            WHERE
                `purchase_order`.`status` = 20
            GROUP BY
                `purchase_order`.`transaction_number`
            ORDER BY
                `purchase_order`.`created_timestamp` DESC,
                `table_penerimaan`.`material_code` DESC
            LIMIT
                $offset, $limit";

        //Get Limit Approval
        $limit_approval = "SELECT * FROM `system_preference` WHERE `category` = 'module_preference_purchasing' AND `item_name` = 'limit_approval_direksi'";
        $limit_approval = GenericModel::rawSelect($limit_approval, false);
        
        //Check limit approval user
        $user_id = Session::get("uid");
        //above limit approval
        $sql = "SELECT * FROM `system_preference` WHERE `category` = 'module_preference_purchasing' AND `item_name` = 'users_approval_above_limit' AND `value` = '{$user_id}'";
        $is_above_limit_approval = GenericModel::checkData($sql);

        //under limit approval
        $sql = "SELECT * FROM `system_preference` WHERE `category` = 'module_preference_purchasing' AND `item_name` = 'users_approval_under_limit' AND `value` = '{$user_id}'";
        $is_under_limit_approval = GenericModel::checkData($sql);
        //var_dump($is_above_limit_approval);exit;

        //For pagination
        $total_record = GenericModel::rowCount('`purchase_order`', '`status` = -1', '`transaction_number`');

        $header_script = '<link rel="stylesheet" href="' . Config::get('URL') . 'bootstrap-3.3.7/css/excel-2007.css" media="screen"/><link rel="stylesheet" href="' . Config::get('URL') . 'bootstrap-3.3.7/css/datepicker.css" media="screen"/>';
        $footer_script = '<script src="' . Config::get('URL') . 'bootstrap-3.3.7/js/bootstrap-datepicker.js"></script>
        <script>
        //date picker
        $(".datepicker").datepicker();

        //check all 
        function checkAll(classname) {
            var clist = document.getElementsByClassName(classname);
             if (clist[0].checked) {
                for (var i = 0; i < clist.length; ++i) { clist[i].checked = false; }
             } else {
                for (var i = 0; i < clist.length; ++i) { clist[i].checked = true; }
             }
         }
        </script>';

        $this->View->render('po/waiting_approval',
                  array(
                'header_script' => $header_script,
                'footer_script' => $footer_script,
                'title' => 'Waiting Approval Purchase Order',
                'activelink1' => 'supply chain',
                'activelink2' => 'pr/po',
                'activelink3' => 'waiting approval purchase order',
                'page' => $page,
                'limit' => $limit,
                'po_list' => GenericModel::rawSelect($po_list),
                'nota_pembelian_list' => GenericModel::rawSelect($nota_pembelian_list),
                'change_po_list' => GenericModel::rawSelect($change_po_list),
                'limit_approval' => $limit_approval,
                'is_above_limit_approval' => $is_above_limit_approval,
                'is_under_limit_approval' => $is_under_limit_approval,
                'pagination' => FormaterModel::pagination('po/waitingApproval', $total_record, $page, $limit)
            ));
    }

    public function approved($page = 1, $limit = 20)
    {
        //po list if(`table_penerimaan`.`quantity_received` = null, 0, `table_penerimaan`.`quantity_received`)
        $offset = ($page > 1) ? ($page - 1) * $limit : 0;
        $sql_group = "
            SELECT
                `purchase_order`.`transaction_number`,
                `purchase_order`.`feedback_note`,
                `purchase_order`.`created_timestamp`,
                `purchase_order`.`supplier_id`,
                `purchase_order`.`status`,
                GROUP_CONCAT(IF(`material_list`.`material_name` != '', `material_list`.`material_name`, 'kosong') SEPARATOR '-, -') as `material_name`,
                GROUP_CONCAT(IF(`purchase_order_list`.`material_code` != '', `purchase_order_list`.`material_code`, `purchase_order_list`.`budget_item`) SEPARATOR '-, -') as `material_code`,
                GROUP_CONCAT(`purchase_order_list`.`quantity` SEPARATOR '-, -') as `quantity`,
                GROUP_CONCAT(IFNULL(`table_penerimaan`.`quantity_received`, 0) SEPARATOR '-, -') as `quantity_received`,
                GROUP_CONCAT(`purchase_order_list`.`status` SEPARATOR '-, -') as `receive_status`,
                GROUP_CONCAT(`purchase_order_list`.`purchase_price` SEPARATOR '-, -') as `purchase_price`,
                GROUP_CONCAT(`purchase_order_list`.`purchase_tax` SEPARATOR '-, -') as `purchase_tax`,                    
                `users`.`full_name`,
                `contact`.`contact_name`
            FROM
                `purchase_order`
            LEFT JOIN
                `purchase_order_list` AS `purchase_order_list` ON `purchase_order_list`.`transaction_number` = `purchase_order`.`transaction_number`
            LEFT JOIN
                `material_list` AS `material_list` ON `purchase_order_list`.`material_code` = `material_list`.`material_code`
            LEFT JOIN (
                    SELECT
                        `material_list_in`.`material_code`,
                        `material_list_in`.`transaction_number`,
                        SUM(`material_list_in`.`quantity_received`) AS `quantity_received`
                    FROM
                        `material_list_in`
                    GROUP BY
                        `material_list_in`.`material_code`,
                        `material_list_in`.`transaction_number`
                    ORDER BY
                        `material_list_in`.`material_code`
                ) AS `table_penerimaan` ON `table_penerimaan`.`material_code` = `purchase_order_list`.`material_code` AND `table_penerimaan`.`transaction_number` = `purchase_order_list`.`transaction_number`
            LEFT JOIN
                `contact` AS `contact` ON `contact`.`contact_id` = `purchase_order`.`supplier_id`
            LEFT JOIN
                `users` AS `users` ON `users`.`uid` = `purchase_order`.`creator_id`
            WHERE
                `purchase_order`.`status` >= 0
            GROUP BY
                `purchase_order`.`transaction_number`
            ORDER BY
                `purchase_order`.`created_timestamp` DESC,
                `table_penerimaan`.`material_code` DESC
            LIMIT
                $offset, $limit";

        //For pagination
        $total_record = GenericModel::rowCount('`purchase_order`', '`status` >= 0 AND `status` < 100', '`transaction_number`');

        $header_script = '<link rel="stylesheet" href="' . Config::get('URL') . 'bootstrap-3.3.7/css/excel-2007.css" media="screen"/><link rel="stylesheet" href="' . Config::get('URL') . 'bootstrap-3.3.7/css/datepicker.css" media="screen"/>';
        $footer_script = '<script src="' . Config::get('URL') . 'bootstrap-3.3.7/js/bootstrap-datepicker.js"></script>
        <script>
        $(".datepicker").datepicker(); //date picker
        </script>';

        $this->View->render('po/approved',
                  array(
                'header_script' => $header_script,
                'footer_script' => $footer_script,
                'title' => 'Approved Purchase Order',
                'activelink1' => 'supply chain',
                'activelink2' => 'pr/po',
                'activelink3' => 'approved purchase order',
                'page' => $page,
                'limit' => $limit,
                'po_list' => GenericModel::rawSelect($sql_group),
                'pagination' => FormaterModel::pagination('po/approved', $total_record, $page, $limit)
            ));
    }

    public function closed($page = 1, $limit = 20)
    {
        //po list
        $offset = ($page > 1) ? ($page - 1) * $limit : 0;
        $sql_group = "
            SELECT
                `purchase_order`.`transaction_number`,
                `purchase_order`.`feedback_note`,
                `purchase_order`.`created_timestamp`,
                `purchase_order`.`supplier_id`,
                `purchase_order`.`status`,
                GROUP_CONCAT(IF(`material_list`.`material_name` != '', `material_list`.`material_name`, 'kosong') SEPARATOR '-, -') as `material_name`,
                GROUP_CONCAT(IF(`purchase_order_list`.`material_code` != '', `purchase_order_list`.`material_code`, `purchase_order_list`.`budget_item`) SEPARATOR '-, -') as `material_code`,
                GROUP_CONCAT(`purchase_order_list`.`quantity` SEPARATOR '-, -') as `quantity`,
                GROUP_CONCAT(`purchase_order_list`.`purchase_price` SEPARATOR '-, -') as `purchase_price`,
                GROUP_CONCAT(`purchase_order_list`.`purchase_tax` SEPARATOR '-, -') as `purchase_tax`,                    
                `users`.`full_name`,
                `contact`.`contact_name`
            FROM
                `purchase_order`
            LEFT JOIN
                `purchase_order_list` AS `purchase_order_list` ON `purchase_order_list`.`transaction_number` = `purchase_order`.`transaction_number`
            LEFT JOIN
                `material_list` AS `material_list` ON `purchase_order_list`.`material_code` = `material_list`.`material_code`
            LEFT JOIN
                `contact` AS `contact` ON `contact`.`contact_id` = `purchase_order`.`supplier_id`
            LEFT JOIN
                `users` AS `users` ON `users`.`uid` = `purchase_order`.`creator_id`
            WHERE
                `purchase_order`.`status` = 100
            GROUP BY
                `purchase_order`.`transaction_number`
            ORDER BY
                `purchase_order`.`created_timestamp` DESC
            LIMIT
                $offset, $limit";

        //For pagination
        $total_record = GenericModel::rowCount('`purchase_order`', '`status` = 100', '`transaction_number`');

        $header_script = '<link rel="stylesheet" href="' . Config::get('URL') . 'bootstrap-3.3.7/css/excel-2007.css" media="screen"/><link rel="stylesheet" href="' . Config::get('URL') . 'bootstrap-3.3.7/css/datepicker.css" media="screen"/>';
        $footer_script = '<script src="' . Config::get('URL') . 'bootstrap-3.3.7/js/bootstrap-datepicker.js"></script>
        <script>
        $(".datepicker").datepicker(); //date picker
        </script>';

        $this->View->render('po/approved',
                  array(
                'header_script' => $header_script,
                'footer_script' => $footer_script,
                'title' => 'Approved Purchase Order',
                'activelink1' => 'supply chain',
                'activelink2' => 'pr/po',
                'activelink3' => 'closed purchase order',
                'page' => $page,
                'limit' => $limit,
                'po_list' => GenericModel::rawSelect($sql_group),
                'pagination' => FormaterModel::pagination('po/approved', $total_record, $page, $limit)
            ));
    }

    public function submitDraftPo()
    {
        // check user input if it empty or not, inserting empty field to database is not cool at all...
        if (!empty($_POST['contact_id']) AND !empty($_POST['note']) AND !empty($_POST['due_date'])) {

            // Get latest PO Number, format PR is TBE 0013/PO-0315
            $awal_tahun = date('Y-01-01');
            $table = '`purchase_order`';
            $where = "`transaction_number` LIKE '%/PO-%' AND `created_timestamp` >= '{$awal_tahun}' ORDER BY `created_timestamp` DESC";
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

            $insert = array(
                        'supplier_id' => Request::post('contact_id'),
                        'transaction_number' => $po_number,
                        'due_date' => Request::post('due_date'),
                        'note' => Request::post('note'),
                        'log' => addslashes($log),
                        'creator_id'    => SESSION::get('uid')
                        );
            GenericModel::insert('purchase_order', $insert);
            Redirect::to('po/detail?po_number=' . urlencode($po_number));
        } else {
            
            Session::add('feedback_negative', 'Kode supplier, due date dan keterangan tidak boleh kosong.');
            Redirect::to('po/pr');
        }
    }

    public function detail()
    {
            $po_number = urldecode(Request::get('po_number'));

            $table = '`purchase_order` LEFT JOIN `contact` ON `contact`.`contact_id` = `purchase_order`.`supplier_id` LEFT JOIN `users` ON `users`.`uid` = `purchase_order`.`creator_id`';
            $where = "`transaction_number` = '{$po_number}'";
            $field = "`purchase_order`.`supplier_id`, `purchase_order`.`transaction_number`, `purchase_order`.`status`, `purchase_order`.`due_date`, `purchase_order`.`approved_date`, `purchase_order`.`note`, `purchase_order`.`feedback_note`, `purchase_order`.`creator_id`, `purchase_order`.`log`, `purchase_order`.`created_timestamp`, `contact`.`contact_id`, `contact`.`contact_name`, `contact`.`address_street`, `contact`.`address_city`, `contact`.`address_state`, `users`.`full_name`";
            $pr_data = GenericModel::getOne($table, $where, $field);

            $sql = "SELECT
                        `purchase_order_list`.*,
                        `material_list`.`material_name`,
                        `material_list_in`.`quantity_received`,
                        `material_list`.`unit` as `unit_master`
                FROM
                    `purchase_order_list`
                LEFT JOIN
                    `material_list`
                        ON
                    `material_list`.`material_code` = `purchase_order_list`.`material_code`
                LEFT JOIN 
                    (SELECT
                        SUM(`material_list_in`.`quantity_received`) AS `quantity_received`,
                        `material_list_in`.`material_code`
                    FROM
                        `material_list_in`
                    WHERE
                        `material_list_in`.`transaction_number` = '{$po_number}'
                    GROUP BY
                        `material_list_in`.`material_code`
                    ) AS `material_list_in`
                        ON
                    `material_list_in`.`material_code` = `purchase_order_list`.`material_code`
                WHERE
                    `purchase_order_list`.`transaction_number` = '{$po_number}'
                ORDER BY
                    `purchase_order_list`.`material_code` ASC
                    ";
            $pr_item_list = GenericModel::rawSelect($sql);

            $table = '
                `shipment_list` LEFT JOIN `contact` ON `contact`.`contact_id` = `shipment_list`.`supplier_code`
                ';
            $where = "`transaction_number` = '{$po_number}'";
            $field = "`contact`.`contact_name`, `warehouse_place`, `purchase_price`, `supplier_code`, `purchase_price_discount`, `purchase_tax`, `delivery_time`, `payment_term`, `packaging`,  `freight_term`, `freight_payment`, `ship_via`";
            $shipment_data = GenericModel::getOne($table, $where, $field);
            
            $table = '`material_list_in` LEFT JOIN `material_list` ON `material_list`.`material_code` = `material_list_in`.`material_code`';
            $where = "`transaction_number` = '{$po_number}' AND `status` = 'waiting_qc_approval'";
            $field = "`material_list_in`.*, `material_list`.`material_name`";
            $qc_waiting_approval = GenericModel::getAll($table, $where, $field);

            $table = '`material_list_in` LEFT JOIN `material_list` ON `material_list`.`material_code` = `material_list_in`.`material_code`';
            $where = "`transaction_number` = '{$po_number}' AND `status` = 'stock' ORDER BY `material_list_in`.`material_code` ASC";
            $field = "
                `material_list_in`.`uid`,
                `material_list_in`.`material_code`,
                `material_list_in`.`quantity`,
                `material_list_in`.`quantity_received`,
                `material_list_in`.`quantity_reject`,
                `material_list_in`.`note`,
                `material_list_in`.`incoming_date`,
                `material_list_in`.`qc_pass_date`,
                `material_list_in`.`created_timestamp`,
                `material_list`.`material_name`";
            $qc_log = GenericModel::getAll($table, $where, $field);

            $table = '`system_preference`';
            $where = "`category` = 'freight_term'";
            $field = "`item_name`, `value`";
            $freight_term = GenericModel::getAll($table, $where, $field);

            $table = '`system_preference`';
            $where = "`category` = 'payment_term' ORDER BY `item_name` ASC";
            $field = "`item_name`, `value`";
            $payment_term = GenericModel::getAll($table, $where, $field);

            $table = '`system_preference`';
            $where = "`category` = 'payment_type'";
            $field = "`item_name`";
            $payment_type = GenericModel::getAll($table, $where, $field);

             //Get Limit Approval
            $limit_approval = "SELECT * FROM `system_preference` WHERE `category` = 'module_preference_purchasing' AND `item_name` = 'limit_approval_direksi'";
            $limit_approval = GenericModel::rawSelect($limit_approval, false);
            
            //Check limit approval user
            $user_id = Session::get("uid");
            //above limit approval
            $sql = "SELECT * FROM `system_preference` WHERE `category` = 'module_preference_purchasing' AND `item_name` = 'users_approval_above_limit' AND `value` = '{$user_id}'";
            $is_above_limit_approval = GenericModel::checkData($sql);

            //under limit approval
            $sql = "SELECT * FROM `system_preference` WHERE `category` = 'module_preference_purchasing' AND `item_name` = 'users_approval_under_limit' AND `value` = '{$user_id}'";
            $is_under_limit_approval = GenericModel::checkData($sql);
            //var_dump($is_above_limit_approval);exit;

            //Uploaded File For Berita Acara
            $uploaded_file = "SELECT `item_name`, `item_id`, `value`, `uid`, `note`  FROM `upload_list` WHERE `category` =  'purchase-order' AND `item_id` = '{$po_number}' AND `is_deleted` = 0";

            //kategori budget pembelian
            $sql = "SELECT * FROM `system_preference` WHERE `category` = 'direct_expense_transaction' ORDER BY `item_name` ASC";
            $budget_category = GenericModel::rawSelect($sql);

            $header_script = '<link rel="stylesheet" href="' . Config::get('URL') . 'bootstrap-3.3.7/css/datepicker.css" media="screen"/>';
            $footer_script = '<script src="' . Config::get('URL') . 'bootstrap-3.3.7/js/bootstrap-datepicker.js"></script>
            <script>
            $(".datepicker").datepicker(
            {
                orientation: "auto",
            }); //date picker
            </script>';

            $this->View->render('po/detail',
                  array(
                'title' => 'Purchase Request',
                'activelink1' => 'supply chain',
                'activelink2' => 'pr/po',
                'activelink3' => 'purchase request',
                'header_script' => $header_script,
                'footer_script' => $footer_script,
                'pr_data' => $pr_data,
                'shipment_data' => $shipment_data,
                'pr_item_list' => $pr_item_list,
                'qc_waiting_approval' => $qc_waiting_approval,
                'qc_log' => $qc_log,
                'payment_term' => $payment_term,
                'payment_type' => $payment_type,
                'freight_term' => $freight_term,
                'limit_approval' => $limit_approval,
                'is_above_limit_approval' => $is_above_limit_approval,
                'is_under_limit_approval' => $is_under_limit_approval,
                'uploaded_file' => GenericModel::rawSelect($uploaded_file),
                'budget_category' => $budget_category,
                'company' => GenericModel::getAll('`system_preference`', "`category` = 'company_identification' ORDER BY `item_name` ASC", '`value`, `item_name`')
                )
            );
    }

    public function insertPrItem()
    {
        // check user input if it empty or not, inserting empty field to database is not cool at all...
        if ((!empty($_POST['material_code']) OR !empty($_POST['budget_category'])) AND !empty($_POST['quantity']) AND !empty($_POST['purchase_price']) AND !empty($_POST['unit'])) {

            //Make log start
            $po_number = urldecode(Request::get('po_number'));
            $table = '`purchase_order`';
            $where = "`transaction_number` = '{$po_number}'";
            $field = "`log`";
            $pr_data = GenericModel::getOne($table, $where, $field);

            $post_array             = $_POST; // get all post array
            $log             = json_encode($_POST); // change to json to easily replaced like string
            $log             = str_replace('","', '<br />', $log);
            $log             = str_replace('":"', ' : ', $log);
            $log             = str_replace('_', ' ', $log);
            $log             = str_replace('{"', '', $log);
            $log             = str_replace('"}', '', $log);
            $log             = '<li><span class="badge badge-grey">' . SESSION::get('user_name') .'</span> add material with detail:<br />' . $log . '<br>(' . date("Y-m-d") . ')</li>' . $pr_data->log;
            //make log end
            
            //Check currency and get rate
            if (Request::post('currency') != 'IDR') {
                $purchase_currency_rate = FormaterModel::currencyRateBI();
                $purchase_currency_rate = $purchase_currency_rate[Request::post('currency')]['jual'];
            } else {
                $purchase_currency_rate = 1;
            }
            $material_code = explode(' - ', Request::post('material_code')); // get material code only
            $material_code = FormaterModel::sanitize($material_code[0]);
            $insert = array(
                        'uid'    => GenericModel::guid(),
                        'transaction_number' => $po_number,
                        'material_code' => $material_code,
                        'quantity' => Request::post('quantity'),
                        'budget_category' => Request::post('budget_category'),
                        'budget_item' => Request::post('budget_item'),                        
                        'purchase_price' => Request::post('purchase_price'),
                        'purchase_price_discount' => Request::post('purchase_price_discount'),
                        'purchase_tax' => Request::post('purchase_tax'),
                        'purchase_currency' => Request::post('currency'),
                        'purchase_currency_rate' => $purchase_currency_rate,
                        'unit' => Request::post('unit'),
                        'packaging' => Request::post('packaging'),
                        'material_specification' => nl2br(Request::post('material_specification')),
                        'reason_request' => Request::post('reason_request'),
                        'creator_id'    => SESSION::get('uid')
                        );
            GenericModel::insert('purchase_order_list', $insert);

            //Update purchase Price in material List
            $update = array(
                        'purchase_price' => (Request::post('purchase_price') - Request::post('purchase_price_discount')),
                        'purchase_currency' => Request::post('currency'),
                        'purchase_unit' => Request::post('unit'),
                        'modifier_id'    => SESSION::get('user_name'),
                        );
            $cond = "`material_code` = '{$material_code}'";
            QgenericModel::update('material_list', $update, $cond);

            //make log
            $update = array(
                        'log' => addslashes($log),
                        'modifier_id'    => SESSION::get('uid'),
                        'modified_timestamp' => date("Y-m-d H:i:s")
                        );
            QgenericModel::update('purchase_order', $update, "`transaction_number` = '$po_number'");
            Redirect::to('po/detail?po_number=' . urlencode($po_number));
        } else {
            Session::add('feedback_negative', 'Kode barang atau Budged Pembelian (pilih salah satu), jumlah, harga dan satuan barang tidak boleh kosong.');
            header('location: ' . Config::get('URL') . 'po/detail/');
            Redirect::to('po/detail?po_number=' . urlencode($po_number));
        }
    }

    public function editPrItem($uid)
    {
            $table = '`purchase_order_list`';
            $where = "`uid` = '{$uid}'";
            $pr_data = GenericModel::getOne($table, $where);

            //kategori budget pembelian
            $sql = "SELECT * FROM `system_preference` WHERE `category` = 'direct_expense_transaction' ORDER BY `item_name` ASC";
            $budget_category = GenericModel::rawSelect($sql);

            $this->View->render('po/edit_pr_item',
                  array(
                'title' => 'Purchase Request',
                'activelink1' => 'supply chain',
                'activelink2' => 'purchase request',
                'pr_data' => $pr_data,
                'budget_category' => $budget_category,
                )
            );
    }

    public function saveEditPrItem($uid)
    {
            //Make log start
            $po_number = Request::post('po_number');
            $material_code = explode(' - ', Request::post('material_code')); // get material code only
            $material_code = $material_code[0];

            $table = '`purchase_order`';
            $where = "`transaction_number` = '{$po_number}'";
            $field = "`log`";
            $pr_data = GenericModel::getOne($table, $where, $field);

            $table = '`purchase_order_list`';
            $where = "`uid` = '{$uid}'";
            $pr_data_item = GenericModel::getOne($table, $where);

            $log = "
                    Material: from <span class='red'>" . $pr_data_item->material_code . "</span> to <span class='text-primary'>"  . $material_code . " </span><br />
                    Quantity: from <span class='red'>" . $pr_data_item->quantity . "</span> to <span class='text-primary'>" . Request::post('quantity') . "</span><br />
                    Price: from <span class='red'>" . $pr_data_item->purchase_price . "</span> to <span class='text-primary'>" . Request::post('purchase_price_discount') . "</span><br />
                    price_discount: from <span class='red'>"  . $pr_data_item->purchase_price_discount . " </span> to <span class='text-primary'> " . Request::post('purchase_price_discount') . " </span><br />
                    tax: from <span class='red'>" . $pr_data_item->purchase_tax . "</span> to <span class='text-primary'>" . Request::post('purchase_tax') . "</span><br />
                    satuan: from <span class='red'>" . $pr_data_item->unit . "</span> to <span class='text-primary'>" . Request::post('unit') . "</span><br />
                    packaging: from <span class='red'>" . $pr_data_item->packaging . "</span> to <span class='text-primary'>" . Request::post('packaging') . "</span><br />
                    Specification: from <span class='red'>" . $pr_data_item->material_specification . "</span> to <span class='text-primary'>" . Request::post('material_specification') . "</span><br />
                    Reason Request: from <span class='red'>" . $pr_data_item->reason_request . "</span> to <span class='text-primary'>" . Request::post('reason_request') . "</span>
                    ";
            $log             = '<li><span class="badge badge-grey">' . SESSION::get('user_name') .'</span> update material with detail:<br />' . $log . '<br />(' . date("Y-m-d") . ')</li>' . $pr_data->log;

            //Check currency and get rate
            if (Request::post('currency') != 'IDR') {
                $purchase_currency_rate = FormaterModel::currencyRateBI();
                $purchase_currency_rate = $purchase_currency_rate[Request::post('currency')]['jual'];
            } else {
                $purchase_currency_rate = 1;
            }
            
            $update = array(
                        'material_code' => $material_code,
                        'budget_category' => Request::post('budget_category'),
                        'budget_item' => Request::post('budget_item'),
                        'quantity' => Request::post('quantity'),
                        'purchase_price' => Request::post('purchase_price'),
                        'purchase_price_discount' => Request::post('purchase_price_discount'),
                        'purchase_tax' => Request::post('purchase_tax'),
                        'purchase_currency' => Request::post('currency'),
                        'purchase_currency_rate' => $purchase_currency_rate,
                        'unit' => Request::post('unit'),
                        'packaging' => Request::post('packaging'),
                        'material_specification' => nl2br(Request::post('material_specification')),
                        'reason_request' => Request::post('reason_request'),
                        'modifier_id'    => SESSION::get('uid'),
                        'modified_timestamp' => date("Y-m-d H:i:s")
                        );
            GenericModel::update('purchase_order_list', $update, "`uid` = '$uid'");

            //Update purchase Price in material List
            $update = array(
                        'purchase_price' => (Request::post('purchase_price') - Request::post('purchase_price_discount')),
                        'purchase_currency' => Request::post('currency'),
                        'purchase_unit' => Request::post('unit'),
                        'modifier_id'    => SESSION::get('user_name'),
                        );
            $cond = "`material_code` = '{$material_code}'";
            QgenericModel::update('material_list', $update, $cond);

            //make log
            $update = array(
                        'log' => addslashes($log),
                        'modifier_id'    => SESSION::get('uid'),
                        'modified_timestamp' => date("Y-m-d H:i:s")
                        );
            GenericModel::update('purchase_order', $update, "`transaction_number` = '$po_number'");
            Redirect::to('po/detail?po_number=' . urlencode($po_number));
    }

    public function makeShipment()
    {
        //check apakah membuat/memasukkan biaya pengiriman atau mengupdate biaya pengiriman
        if (Request::post('submit') == 'Masukkan Pengiriman') {
           //Make log start
            $po_number = urldecode(Request::get('po_number'));
            $table = '`purchase_order`';
            $where = "`transaction_number` = '{$po_number}'";
            $field = "`log`";
            $pr_data = GenericModel::getOne($table, $where, $field);

            $post_array             = $_POST; // get all post array
            $log             = json_encode($_POST); // change to json to easily replaced like string
            $log             = str_replace('","', '<br />', $log);
            $log             = str_replace('":"', ' : ', $log);
            $log             = str_replace('_', ' ', $log);
            $log             = str_replace('{"', '', $log);
            $log             = str_replace('"}', '', $log);
            $log             = '<li><span class="badge badge-grey">' . SESSION::get('user_name') .'</span> Memasukkan biaya pengiriman dengan detail:<br />' . $log . '<br>(' . date("Y-m-d") . ')</li>' . $pr_data->log;
            //make log end
            
            $material_code = explode(' - ', Request::post('material_code')); // get material code only
            $insert = array(
                        'uid'    => GenericModel::guid(),
                        'transaction_number' => $po_number,
                        'payment_term' => Request::post('payment_term'),
                        'supplier_code' => Request::post('supplier_code'),
                        'purchase_price' => Request::post('purchase_price'),
                        'freight_term' => Request::post('freight_term'),
                        'freight_payment' => Request::post('freight_payment'),
                        'ship_via' => Request::post('ship_via'),
                        'delivery_time' => Request::post('delivery_time'),                     
                        'creator_id'    => SESSION::get('uid')
                        );
            GenericModel::insert('shipment_list', $insert);

            //make log
            $update = array(
                        'log' => addslashes($log),
                        'modifier_id'    => SESSION::get('uid'),
                        'modified_timestamp' => date("Y-m-d H:i:s")
                        );
            GenericModel::update('purchase_order', $update, "`transaction_number` = '$po_number'");

        } elseif (Request::post('submit') == 'Update') {
            //Make log start
            $po_number = urldecode(Request::get('po_number'));
            $table = '`purchase_order`';
            $where = "`transaction_number` = '{$po_number}'";
            $field = "`log`";
            $pr_data = GenericModel::getOne($table, $where, $field);

            $post_array             = $_POST; // get all post array
            $log             = json_encode($_POST); // change to json to easily replaced like string
            $log             = str_replace('","', '<br />', $log);
            $log             = str_replace('":"', ' : ', $log);
            $log             = str_replace('_', ' ', $log);
            $log             = str_replace('{"', '', $log);
            $log             = str_replace('"}', '', $log);
            $log             = '<li><span class="badge badge-grey">' . SESSION::get('user_name') .'</span> Update pengiriman dengan detail:<br />' . $log . '<br>(' . date("Y-m-d") . ')</li>' . $pr_data->log;
            //make log end
            
            $material_code = explode(' - ', Request::post('material_code')); // get material code only
            $update = array(
                        'payment_term' => Request::post('payment_term'),
                        'purchase_price' => FormaterModel::getNumberOnly(Request::post('purchase_price')),
                        'supplier_code' => Request::post('supplier_code'),
                        'freight_term' => Request::post('freight_term'),
                        'freight_payment' => Request::post('freight_payment'),
                        'ship_via' => Request::post('ship_via'),
                        'delivery_time' => Request::post('delivery_time'),                  
                        );
            GenericModel::update('shipment_list', $update, "`transaction_number` = '$po_number'");

            //make log
            $update = array(
                        'log' => addslashes($log),
                        'modifier_id'    => SESSION::get('uid'),
                        'modified_timestamp' => date("Y-m-d H:i:s")
                        );
            GenericModel::update('purchase_order', $update, "`transaction_number` = '$po_number'");
        }
        
        Redirect::to('po/detail?po_number=' . urlencode(Request::get('po_number')));
    }

    public function printPo()
    {
        $po_number = urldecode(Request::get('po_number'));

        $po = "SELECT
                    `purchase_order`.`transaction_number`,
                    `purchase_order`.`note`,
                    IF(`purchase_order`.`approved_date` = '0000-00-00', `purchase_order`.`created_timestamp`, `purchase_order`.`approved_date`) as `approved_date`
                    ,
                    `contact`.`contact_name`,
                    `contact`.`address_street`,
                    `contact`.`address_city`,
                    `contact`.`address_state`,
                    `contact`.`address_zip`,
                    `contact`.`website`,
                    `contact`.`phone`,
                    `contact`.`fax`,
                    `contact`.`email` as `customer_email`,
                    `contact_person`.`salutation`, 
                    `contact_person`.`first_name`,
                    `contact_person`.`middle_name`,
                    `contact_person`.`last_name`,
                    `purchase_order_list`.`material_code`,
                    `purchase_order_list`.`budget_item`,
                    `purchase_order_list`.`quantity`,
                    `purchase_order_list`.`unit`,
                    `purchase_order_list`.`purchase_price`,
                    `purchase_order_list`.`purchase_currency`,
                    `purchase_order_list`.`purchase_price_discount`,
                    `purchase_order_list`.`purchase_tax`,
                    `purchase_order_list`.`transaction_number`,
                    `purchase_order_list`.`material_specification`,
                    `material_list`.`material_name`,
                    `users`.`full_name`,
                    `users`.`email`
                FROM
                    `purchase_order`
                LEFT JOIN
                    `contact` ON `contact`.`contact_id` = `purchase_order`.`supplier_id`
                LEFT JOIN
                    (SELECT
                        `contact_person`.`contact_id`, 
                        `contact_person`.`salutation`, 
                        `contact_person`.`first_name`,
                        `contact_person`.`middle_name`,
                        `contact_person`.`last_name`
                    FROM
                        `contact_person`
                    WHERE
                        `contact_person`.`is_main` = 1)
                    AS `contact_person` ON `contact_person`.`contact_id` = `purchase_order`.`supplier_id`
                LEFT JOIN
                    `purchase_order_list` ON `purchase_order_list`.`transaction_number` = `purchase_order`.`transaction_number`
                LEFT JOIN
                    `material_list` ON `material_list`.`material_code` = `purchase_order_list`.`material_code`
                LEFT JOIN
                    `users` ON `users`.`uid` = `purchase_order`.`creator_id`
                WHERE
                    `purchase_order`.`transaction_number` = '{$po_number}'";

        $table = '`shipment_list` LEFT JOIN `contact` ON `contact`.`contact_id` = `shipment_list`.`supplier_code`
                ';
        $where = "`transaction_number` = '{$po_number}'";
        $field = "`contact`.`contact_name`, `warehouse_place`, `purchase_price`, `supplier_code`, `purchase_price_discount`, `purchase_tax`, `delivery_time`, `payment_term`, `packaging`,  `freight_term`, `freight_payment`, `ship_via`";
        $shipment_data = GenericModel::getOne($table, $where, $field);
            
        $this->View->renderFileOnly('po/print_po', array(
                'product' => GenericModel::rawSelect($po),
                'shipment_data' => $shipment_data,
                'company' => GenericModel::getAll('`system_preference`', "`category` = 'company_identification' ORDER BY `item_name` ASC", '`value`, `item_name`')

        ));
    }

    public function updatePr()
    {
        // check user input if it empty or not, inserting empty field to database is not cool at all...
        if (!empty($_POST['supplier_id']) AND !empty($_POST['note'])) {

            //Start make log
            $po_number = urldecode(Request::get('po_number'));
            $previous_olg         = GenericModel::getOne('purchase_order', "`transaction_number` = '$po_number'", 'log');
            $post_array      = $_POST; // get all post array
            $log             = json_encode($_POST); // change to json to easily replaced like string
            $log             = str_replace('","', '<br />', $log);
            $log             = str_replace('":"', ' : ', $log);
            $log             = str_replace('_', ' ', $log);
            $log             = str_replace('{"', '', $log);
            $log             = str_replace('"}', '', $log);
            $log             = '<li><span class="badge badge-grey">' . SESSION::get('user_name') .'</span> edit Purchase Request:<br />' . $log . '<br>(' . date("Y-m-d") . ')</li>' . $previous_olg->log;

            $custom_array = array(
                        'log'    => addslashes($log),
                        'modifier_id'    => SESSION::get('uid'),
                        'modified_timestamp'    => date("Y-m-d H:i:s")
                        );
            $update = array_merge($post_array, $custom_array);
            //Debuger::jam($update);exit;
            GenericModel::update('purchase_order', $update, "`transaction_number` = '$po_number'");
            Redirect::to('po/detail?po_number=' . urlencode($po_number));
        } else {
            Session::add('feedback_negative', 'Kode supplier dan keterangan tidak boleh kosong.');
            Redirect::to('po/detail?po_number=' . urlencode($po_number));
        }
    }

    public function askPrApproval()
    {
            $po_number = urldecode(Request::get('po_number'));
            $previous_olg  = GenericModel::getOne('`purchase_order`', "`transaction_number` = '$po_number'", 'log');
            $log             = '<li><span class="badge badge-grey">' . SESSION::get('user_name') .'</span> ask for approval<br />(' . date("Y-m-d") . ')</li>' . $previous_olg->log;

            $update = array(
                        'status' => -1,
                        'log'    => addslashes($log),
                        'modifier_id'    => SESSION::get('uid'),
                        'modified_timestamp'    => date("Y-m-d H:i:s")
                        );
            GenericModel::update('purchase_order', $update, "`transaction_number` = '$po_number'");
            //Debuger::jam($update);exit;

            //SEND EMAIL NOTIFICATION
            //Get Limit Approval
            $limit_approval = "SELECT * FROM `system_preference` WHERE `category` = 'module_preference_purchasing' AND `item_name` = 'limit_approval_direksi'";
            $limit_approval = GenericModel::rawSelect($limit_approval, false);

            $total_purchase_value = FormaterModel::getNumberOnly(Request::get('value'));

            if ($total_purchase_value >= $limit_approval->value) {
                //Get ABOVE limit Approval user
                $authorized_users = "
                SELECT
                    `users`.`email`
                FROM
                    `system_preference`
                LEFT JOIN
                    `users` ON `users`.`uid` = `system_preference`.`value`
                WHERE
                    `system_preference`.`category` = 'module_preference_purchasing' AND `system_preference`.`item_name` = 'users_approval_above_limit'";
            } else {
                //Get UNDER limit Approval user
                $authorized_users = "
                SELECT
                    `users`.`email`
                FROM
                    `system_preference`
                LEFT JOIN
                    `users` ON `users`.`uid` = `system_preference`.`value`
                WHERE
                    `system_preference`.`category` = 'module_preference_purchasing' AND `system_preference`.`item_name` = 'users_approval_under_limit'";
            }

            $authorized_users = GenericModel::rawSelect($authorized_users);
            $email = array();
            foreach ($authorized_users as $key => $value) {
                $email[] = $value->email;
            }
            //var_dump($email);exit;

            $email_creator = SESSION::get('full_name');
            $email_subject = "PO Approval Request (" . $po_number . ') by ' . $email_creator;
            $body = ucwords($email_creator) . ' meminta  approval untuk Purchase Order nomer ' . $po_number . '. Klik link berikut untuk melihat detail PO ' .   Config::get('URL') . 'po/detail/?po_number=' . urlencode($po_number);
            $mail = new Mail;
            $mail_sent = $mail->sendMail($email, Config::get('EMAIL_VERIFICATION_FROM_EMAIL'),
                Config::get('EMAIL_VERIFICATION_FROM_NAME'), $email_subject, $body
            );
            if ($mail_sent) {
                Session::add('feedback_positive', Text::get('FEEDBACK_VERIFICATION_MAIL_SENDING_SUCCESSFUL'));
            } else {
                Session::add('feedback_negative', Text::get('FEEDBACK_VERIFICATION_MAIL_SENDING_ERROR') . $mail->getError() );
            }

            Redirect::to('po/detail?po_number=' . urlencode($po_number));
    }

    public function approvePr()
    {
            $po_number = urldecode(Request::get('po_number'));
            $previous_olg         = GenericModel::getOne('purchase_order', "`transaction_number` = '$po_number'", 'log');
            $log             = '<li><span class="badge badge-grey">' . SESSION::get('user_name') .'</span> approve PR to PO<br />(' . date("Y-m-d") . ')</li>' . $previous_olg->log;

            $update = array(
                        'status' => 0,
                        'feedback_note'    => '',
                        'log'    => addslashes($log),
                        'modifier_id'    => SESSION::get('uid'),
                        'approved_date'    => date("Y-m-d"),
                        'modified_timestamp'    => date("Y-m-d H:i:s")
                        );
            //Debuger::jam($update);exit;
            GenericModel::update('purchase_order', $update, "`transaction_number` = '$po_number'");

        //SEND EMAIL NOTIFICATION
        //GET RELATED Departemen email
        $email_address = GenericModel::getAll('users', "`department` = 'purchasing' OR `department` = 'finance'", '`email`');
        $email = array();
        foreach ($email_address as $key => $value) {
            $email[] = $value->email;
        }

        //tambah email pt.ilmui
        $email_creator = SESSION::get('full_name');
        $email_subject = "PO Approved for  " . $po_number . ' by ' . $email_creator;
        $body = ucwords($email_creator) . ' Menyetujui PO Approval untuk PO nomer ' . $po_number . '. Klik link berikut untuk melihat detail PO ' .   Config::get('URL') . 'po/detail/?po_number=' . urlencode($po_number);
        $mail = new Mail;

        $mail_sent = $mail->sendMail($email, Config::get('EMAIL_VERIFICATION_FROM_EMAIL'),
            Config::get('EMAIL_VERIFICATION_FROM_NAME'), $email_subject, $body
        );
        if ($mail_sent) {
            Session::add('feedback_positive', Text::get('FEEDBACK_VERIFICATION_MAIL_SENDING_SUCCESSFUL'));
        } else {
            Session::add('feedback_negative', Text::get('FEEDBACK_VERIFICATION_MAIL_SENDING_ERROR') . $mail->getError() );
        }

        Redirect::to('po/detail?po_number=' . urlencode($po_number));
        
    }

    public function approvePrBulk() {
        if (Request::post('button-action') == 'Approve') {
            
             //SEND EMAIL NOTIFICATION
            //GET RELATED Departemen email
            $email_address = GenericModel::getAll('users', "`department` = 'purchasing' OR `department` = 'finance'", '`email`');
            $email = array();
            foreach ($email_address as $key => $value) {
                $email[] = $value->email;
            }

            //PO
            foreach ($_POST['transaction_number'] as $key => $value) {
                if (!empty($_POST['transaction_number'][$key])) {

                    //make log
                    $po_number = FormaterModel::sanitize($_POST['transaction_number'][$key]);
                    $previous_olg         = GenericModel::getOne('purchase_order', "`transaction_number` = '$po_number'", 'log');
                    $log             = '<li><span class="badge badge-grey">' . SESSION::get('user_name') .'</span> approve PR to PO<br />(' . date("Y-m-d") . ')</li>' . $previous_olg->log;

                    //update database
                    $update = array(
                        'status' => 0,
                        'feedback_note'    => '',
                        'log'    => addslashes($log),
                        'modifier_id'    => SESSION::get('uid'),
                        'approved_date'    => date("Y-m-d"),
                        'modified_timestamp'    => date("Y-m-d H:i:s")
                        );
                    //Debuger::jam($update);exit;
                    GenericModel::update('purchase_order', $update, "`transaction_number` = '$po_number'");

                    $email_creator = SESSION::get('full_name');
                    $email_subject = 'PO Approval notification  by ' . $email_creator;
                    $body = ucwords($email_creator) . ' Menyetujui PO Approval untuk PO nomer ' . $po_number . '. Klik link berikut untuk melihat detail PO ' .   Config::get('URL') . 'po/detail/?po_number=' . urlencode($po_number);
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
            }

            //Change PO
            foreach ($_POST['change_transaction_number'] as $key => $value) {
                if (!empty($_POST['change_transaction_number'][$key])) {

                    //make log
                    $po_number = FormaterModel::sanitize($_POST['change_transaction_number'][$key]);
                    $previous_olg         = GenericModel::getOne('purchase_order', "`transaction_number` = '$po_number'", 'log');
                    $log             = '<li><span class="badge badge-grey">' . SESSION::get('user_name') .'</span> Approve request for acces to change/edit purchase order price<br />(' . date("Y-m-d") . ')</li>' . $previous_olg->log;

                    //update database
                    $update = array(
                        'status' => -2,
                        'feedback_note'    => '',
                        'log'    => addslashes($log),
                        'modifier_id'    => SESSION::get('uid'),
                        'approved_date'    => date("Y-m-d"),
                        'modified_timestamp'    => date("Y-m-d H:i:s")
                        );
                    //Debuger::jam($update);exit;
                    GenericModel::update('purchase_order', $update, "`transaction_number` = '$po_number'");

                    $email_creator = SESSION::get('full_name');
                    $email_subject = "Purchase price change request approved for  " . $po_number . ' by ' . $email_creator;
                    $body = ucwords($email_creator) . ' Menyetujui permintaan akses untuk merubah harga di PO nomer ' . $po_number . '. Klik link berikut untuk melihat detail PO ' .   Config::get('URL') . 'po/detail/?po_number=' . urlencode($po_number);

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
            }

            //NON PO
            foreach ($_POST['transaction_code'] as $key => $value) {
                if (!empty($_POST['transaction_code'][$key])) {
                    $transaction_code = FormaterModel::sanitize($_POST['transaction_code'][$key]);
                    $update = array(
                        'status' => 1,
                        'payment_due_date' => date('Y-m-d'),
                        'payment_disbursement' => date('Y-m-d'),
                        'modifier_id'    => SESSION::get('uid'),
                        'modified_timestamp' => date("Y-m-d H:i:s")
                        );
                    GenericModel::update('payment_transaction', $update, "`transaction_code` = '$transaction_code'");
                }
            }

            Redirect::to('po/waitingApproval/');
        } elseif (Request::post('button-action') == 'Delete') {
            //PO
            foreach ($_POST['transaction_number'] as $key => $value) {
                if (!empty($_POST['transaction_number'][$key])) {
                    $po_number = FormaterModel::sanitize($_POST['transaction_number'][$key]);
                    GenericModel::remove('purchase_order_list', 'transaction_number', $po_number, true); //true for silent feedback
                    GenericModel::remove('purchase_order', 'transaction_number', $po_number, true); //true for silent feedback
                }
            }

            //Change PO
            foreach ($_POST['change_transaction_number'] as $key => $value) {
                if (!empty($_POST['change_transaction_number'][$key])) {
                    $po_number = FormaterModel::sanitize($_POST['change_transaction_number'][$key]);
                    GenericModel::remove('purchase_order_list', 'transaction_number', $po_number, true); //true for silent feedback
                    GenericModel::remove('purchase_order', 'transaction_number', $po_number, true); //true for silent feedback
                }
            }

            //NON PO
            foreach ($_POST['transaction_code'] as $key => $value) {
                if (!empty($_POST['transaction_code'][$key])) {
                    $transaction_code = FormaterModel::sanitize($_POST['transaction_code'][$key]);
                    GenericModel::remove('payment_transaction', 'transaction_code', $transaction_code, true); //true for silent feedback
                }
            }
            Redirect::to('po/waitingApproval/');
        }
        
    }

    public function closePO()
    {
        $po_number = urldecode(Request::get('po_number'));
        $previous_olg         = GenericModel::getOne('purchase_order', "`transaction_number` = '$po_number'", 'log');
        $log             = '<li><span class="badge badge-grey">' . SESSION::get('user_name') .'</span> Close PO<br />(' . date("Y-m-d") . ')</li>' . $previous_olg->log;

        $update = array(
                    'status' => 100,
                    'log'    => addslashes($log),
                    'modifier_id'    => SESSION::get('uid'),
                    'modified_timestamp'    => date("Y-m-d H:i:s")
                    );
        //Debuger::jam($update);exit;
        GenericModel::update('purchase_order', $update, "`transaction_number` = '$po_number'");
        Redirect::to('po/detail?po_number=' . urlencode($po_number));
        
    }

    public function reopenPO()
    {
        $po_number = urldecode(Request::get('po_number'));
        $previous_olg         = GenericModel::getOne('purchase_order', "`transaction_number` = '$po_number'", 'log');
        $log             = '<li><span class="badge badge-grey">' . SESSION::get('user_name') .'</span> Close PO<br />(' . date("Y-m-d") . ')</li>' . $previous_olg->log;

        $update = array(
                    'status' => -2, //reset
                    'log'    => addslashes($log),
                    'modifier_id'    => SESSION::get('uid'),
                    'modified_timestamp'    => date("Y-m-d H:i:s")
                    );
        //Debuger::jam($update);exit;
        GenericModel::update('purchase_order', $update, "`transaction_number` = '$po_number'");
        Redirect::to('po/detail?po_number=' . urlencode($po_number));
        
    }

    public function deleteTransaction()
    {
        $po_number = urldecode(Request::get('po_number'));
        GenericModel::remove('purchase_order_list', 'transaction_number', $po_number, true); //true for silent feedback
        GenericModel::remove('purchase_order', 'transaction_number', $po_number, true); //true for silent feedback
        Redirect::to(Request::get('forward'));
    }

    public function giveFeedbackPr()
    {
            $po_number = urldecode(Request::get('po_number'));
            $previous_olg         = GenericModel::getOne('purchase_order', "`transaction_number` = '$po_number'", 'log');
            $log             = '<li><span class="badge badge-grey">' . SESSION::get('user_name') .'</span> Add Feedback:<br />' . Request::post('feedback_note') . '<br />(' . date("Y-m-d") . ')</li>' . $previous_olg->log;

            $update = array(
                        'status' => -3,
                        'feedback_note' => Request::post('feedback_note'),
                        'log'    => addslashes($log),
                        'modifier_id'    => SESSION::get('uid'),
                        'modified_timestamp'    => date("Y-m-d H:i:s")
                        );
            //Debuger::jam($update);exit;
            GenericModel::update('purchase_order', $update, "`transaction_number` = '$po_number'");

            //SEND EMAIL NOTIFICATION
            //GET RELATED Departemen email
            $email_address = GenericModel::getAll('users', "`department` = 'purchasing' OR `department` = 'finance'", '`email`');
            $email = array();
            foreach ($email_address as $key => $value) {
                $email[] = $value->email;
            }

            //tambah email pt.ilmui
            $email_creator = SESSION::get('full_name');
            $email_subject = "Feedback for PO approval request, number  " . $po_number . ' by ' . $email_creator;
            $body = ucwords($email_creator) . ' Direksi memberikan feedback pada permintaan PO Approval NO: '  . $po_number . '. Klik link berikut untuk melihat detail PO ' . Config::get('URL') . 'po/detail/?po_number=' . urlencode($po_number);
            $mail = new Mail;

            $mail_sent = $mail->sendMail($email, Config::get('EMAIL_VERIFICATION_FROM_EMAIL'),
                Config::get('EMAIL_VERIFICATION_FROM_NAME'), $email_subject, $body
            );
            if ($mail_sent) {
                Session::add('feedback_positive', Text::get('FEEDBACK_VERIFICATION_MAIL_SENDING_SUCCESSFUL'));
            } else {
                Session::add('feedback_negative', Text::get('FEEDBACK_VERIFICATION_MAIL_SENDING_ERROR') . $mail->getError() );
            }

            Redirect::to('po/detail?po_number=' . urlencode($po_number));
        
    }

    public function askEditPO()
    {
            $po_number = urldecode(Request::get('po_number'));
            $previous_olg  = GenericModel::getOne('`purchase_order`', "`transaction_number` = '$po_number'", 'log');
            $log             = '<li><span class="badge badge-grey">' . SESSION::get('user_name') .'</span> ask for approval to edit purchase price and its detail in PO<br />(' . date("Y-m-d") . ')</li>' . $previous_olg->log;

            $update = array(
                        'status' => 20,
                        'log'    => addslashes($log),
                        'modifier_id'    => SESSION::get('uid'),
                        'modified_timestamp'    => date("Y-m-d H:i:s")
                        );
            GenericModel::update('purchase_order', $update, "`transaction_number` = '$po_number'");
            //Debuger::jam($update);exit;

            //SEND EMAIL NOTIFICATION
            //Get Limit Approval
            $limit_approval = "SELECT * FROM `system_preference` WHERE `category` = 'module_preference_purchasing' AND `item_name` = 'limit_approval_direksi'";
            $limit_approval = GenericModel::rawSelect($limit_approval, false);

            $total_purchase_value = FormaterModel::getNumberOnly(Request::get('value'));

            if ($total_purchase_value >= $limit_approval->value) {
                //Get ABOVE limit Approval user
                $authorized_users = "
                SELECT
                    `users`.`email`
                FROM
                    `system_preference`
                LEFT JOIN
                    `users` ON `users`.`uid` = `system_preference`.`value`
                WHERE
                    `system_preference`.`category` = 'module_preference_purchasing' AND `system_preference`.`item_name` = 'users_approval_above_limit'";
            } else {
                //Get UNDER limit Approval user
                $authorized_users = "
                SELECT
                    `users`.`email`
                FROM
                    `system_preference`
                LEFT JOIN
                    `users` ON `users`.`uid` = `system_preference`.`value`
                WHERE
                    `system_preference`.`category` = 'module_preference_purchasing' AND `system_preference`.`item_name` = 'users_approval_under_limit'";
            }

            $authorized_users = GenericModel::rawSelect($authorized_users);
            $email = array();
            foreach ($authorized_users as $key => $value) {
                $email[] = $value->email;
            }
            //var_dump($email);exit;

            $email_creator = SESSION::get('full_name');
            $email_subject = "Approval request to change purchase price in PO (" . $po_number . ') by ' . $email_creator;
            $body = ucwords($email_creator) . ' meminta  approval untuk merubah harga pembelian di Purchase Order nomer ' . $po_number . '. Klik link berikut untuk melihat detail PO ' .   Config::get('URL') . 'po/detail/?po_number=' . urlencode($po_number);
            $mail = new Mail;
            $mail_sent = $mail->sendMail($email, Config::get('EMAIL_VERIFICATION_FROM_EMAIL'),
                Config::get('EMAIL_VERIFICATION_FROM_NAME'), $email_subject, $body
            );
            if ($mail_sent) {
                Session::add('feedback_positive', Text::get('FEEDBACK_VERIFICATION_MAIL_SENDING_SUCCESSFUL'));
            } else {
                Session::add('feedback_negative', Text::get('FEEDBACK_VERIFICATION_MAIL_SENDING_ERROR') . $mail->getError() );
            }

            Redirect::to('po/detail?po_number=' . urlencode($po_number));
    }

    public function approveAskEditPO()
    {
            $po_number = urldecode(Request::get('po_number'));
            $previous_olg         = GenericModel::getOne('purchase_order', "`transaction_number` = '$po_number'", 'log');
            $log             = '<li><span class="badge badge-grey">' . SESSION::get('user_name') .'</span> Approve request for acces to change purchase price<br />(' . date("Y-m-d") . ')</li>' . $previous_olg->log;

            $update = array(
                        'status' => -2,
                        'feedback_note'    => '',
                        'log'    => addslashes($log),
                        'modifier_id'    => SESSION::get('uid'),
                        'approved_date'    => date("Y-m-d"),
                        'modified_timestamp'    => date("Y-m-d H:i:s")
                        );
            //Debuger::jam($update);exit;
            GenericModel::update('purchase_order', $update, "`transaction_number` = '$po_number'");

        //SEND EMAIL NOTIFICATION
        //GET RELATED Departemen email
        $email_address = GenericModel::getAll('users', "`department` = 'purchasing' OR `department` = 'finance'", '`email`');
        $email = array();
        foreach ($email_address as $key => $value) {
            $email[] = $value->email;
        }

        //tambah email pt.ilmui
        $email_creator = SESSION::get('full_name');
        $email_subject = "Purchase price change request approved for  " . $po_number . ' by ' . $email_creator;
        $body = ucwords($email_creator) . ' Menyetujui permintaan akses untuk merubah harga PO nomer ' . $po_number . '. Klik link berikut untuk melihat detail PO ' .   Config::get('URL') . 'po/detail/?po_number=' . urlencode($po_number);
        $mail = new Mail;

        $mail_sent = $mail->sendMail($email, Config::get('EMAIL_VERIFICATION_FROM_EMAIL'),
            Config::get('EMAIL_VERIFICATION_FROM_NAME'), $email_subject, $body
        );
        if ($mail_sent) {
            Session::add('feedback_positive', Text::get('FEEDBACK_VERIFICATION_MAIL_SENDING_SUCCESSFUL'));
        } else {
            Session::add('feedback_negative', Text::get('FEEDBACK_VERIFICATION_MAIL_SENDING_ERROR') . $mail->getError() );
        }

        Redirect::to('po/detail?po_number=' . urlencode($po_number));
        
    }

    public function rejectAskEditPO()
    {
            $po_number = urldecode(Request::get('po_number'));
            $previous_olg         = GenericModel::getOne('purchase_order', "`transaction_number` = '$po_number'", 'log');
            $log             = '<li><span class="badge badge-grey">' . SESSION::get('user_name') .'</span> Reject request for acces to change purchase price<br />(' . date("Y-m-d") . ')</li>' . $previous_olg->log;

            $update = array(
                        'status' => 0,
                        'feedback_note'    => '',
                        'log'    => addslashes($log),
                        'modifier_id'    => SESSION::get('uid'),
                        'approved_date'    => date("Y-m-d"),
                        'modified_timestamp'    => date("Y-m-d H:i:s")
                        );
            //Debuger::jam($update);exit;
            GenericModel::update('purchase_order', $update, "`transaction_number` = '$po_number'");

        //SEND EMAIL NOTIFICATION
        //GET RELATED Departemen email
        $email_address = GenericModel::getAll('users', "`department` = 'purchasing' OR `department` = 'finance'", '`email`');
        $email = array();
        foreach ($email_address as $key => $value) {
            $email[] = $value->email;
        }

        //tambah email pt.ilmui
        $email_creator = SESSION::get('full_name');
        $email_subject = "Purchase price change request rejected for  " . $po_number . ' by ' . $email_creator;
        $body = ucwords($email_creator) . ' Menolak permintaan akses untuk merubah harga PO nomer ' . $po_number . '. Klik link berikut untuk melihat detail PO ' .   Config::get('URL') . 'po/detail/?po_number=' . urlencode($po_number);
        $mail = new Mail;

        $mail_sent = $mail->sendMail($email, Config::get('EMAIL_VERIFICATION_FROM_EMAIL'),
            Config::get('EMAIL_VERIFICATION_FROM_NAME'), $email_subject, $body
        );
        if ($mail_sent) {
            Session::add('feedback_positive', Text::get('FEEDBACK_VERIFICATION_MAIL_SENDING_SUCCESSFUL'));
        } else {
            Session::add('feedback_negative', Text::get('FEEDBACK_VERIFICATION_MAIL_SENDING_ERROR') . $mail->getError() );
        }

        Redirect::to('po/detail?po_number=' . urlencode($po_number));
        
    }

    public function giveFeedbackAskEditPO()
    {
            $po_number = urldecode(Request::get('po_number'));
            $previous_olg         = GenericModel::getOne('purchase_order', "`transaction_number` = '$po_number'", 'log');
            $log             = '<li><span class="badge badge-grey">' . SESSION::get('user_name') .'</span> Add Feedback to purchase price change request:<br />' . Request::post('feedback_note') . '<br />(' . date("Y-m-d") . ')</li>' . $previous_olg->log;

            $update = array(
                        'status' => 21,
                        'feedback_note' => Request::post('feedback_note'),
                        'log'    => addslashes($log),
                        'modifier_id'    => SESSION::get('uid'),
                        'modified_timestamp'    => date("Y-m-d H:i:s")
                        );
            //Debuger::jam($update);exit;
            GenericModel::update('purchase_order', $update, "`transaction_number` = '$po_number'");

            //SEND EMAIL NOTIFICATION
            //GET RELATED Departemen email
            $email_address = GenericModel::getAll('users', "`department` = 'purchasing' OR `department` = 'finance'", '`email`');
            $email = array();
            foreach ($email_address as $key => $value) {
                $email[] = $value->email;
            }

            //tambah email pt.ilmui
            $email_creator = SESSION::get('full_name');
            $email_subject = "Feedback for purchase price change request, number  " . $po_number . ' by ' . $email_creator;
            $body = ucwords($email_creator) . ' Direksi memberikan feedback atas permintaan akses untuk merubah harga pembelian PO NO: ' . $po_number . '. Klik link berikut untuk melihat detail PO ' .   Config::get('URL') . 'po/detail/?po_number=' . urlencode($po_number);
            $mail = new Mail;

            $mail_sent = $mail->sendMail($email, Config::get('EMAIL_VERIFICATION_FROM_EMAIL'),
                Config::get('EMAIL_VERIFICATION_FROM_NAME'), $email_subject, $body
            );
            if ($mail_sent) {
                Session::add('feedback_positive', Text::get('FEEDBACK_VERIFICATION_MAIL_SENDING_SUCCESSFUL'));
            } else {
                Session::add('feedback_negative', Text::get('FEEDBACK_VERIFICATION_MAIL_SENDING_ERROR') . $mail->getError() );
            }

            Redirect::to('po/detail?po_number=' . urlencode($po_number));
        
    }

    public function receiveMaterial()
    {
        $po_number = urldecode(Request::get('po_number'));
        $totalrecord = Request::post('total_record');
 
        for ($i = 1; $i <= $totalrecord; $i++) {
        
            //only insert unempty data!
            if (Request::post('quantity_received_' . $i) > 0) {

                $insert = array(
                        'uid'    => GenericModel::guid(),
                        'material_code'    => Request::post('material_code_' . $i),
                        'transaction_number'      =>  $po_number,
                        'quantity'      =>  Request::post('quantity_received_' . $i),
                        'status'      =>  'waiting_qc_approval',
                        'note' => 'Barang masuk gudang, menunggu QC Approval',
                        'incoming_date'    => Request::post('incoming_date_' . $i),
                        'creator_id'    => SESSION::get('uid')
                        );
                GenericModel::insert('material_list_in', $insert);
            }
        }

        //SEND EMAIL NOTIFICATION
        //GET RELATED Departemen email
        $email_address = GenericModel::getAll('users', "`department` = 'purchasing' OR `department` = 'finance' OR `department` = 'qc'", '`email`');
        $email = array();
        foreach ($email_address as $key => $value) {
            $email[] = $value->email;
        }
        //tambah email pt.ilmui
        $email[] = 'edi@sbautomedia.com';

        $email_creator = SESSION::get('full_name');
        $email_subject = "Warehouse Received Material on " . $po_number . ' by ' . $email_creator;
        $body = ucwords($email_creator) . ' menerima barang di gudang untuk PO nomer ' . $po_number . '. Klik link berikut untuk melihat detail PO ' .   Config::get('URL') . 'po/detail/?po_number=' . urlencode($po_number);
        $mail = new Mail;

        $mail_sent = $mail->sendMail($email, Config::get('EMAIL_VERIFICATION_FROM_EMAIL'),
            Config::get('EMAIL_VERIFICATION_FROM_NAME'), $email_subject, $body
        );
        if ($mail_sent) {
            Session::add('feedback_positive', Text::get('FEEDBACK_VERIFICATION_MAIL_SENDING_SUCCESSFUL'));
        } else {
            Session::add('feedback_negative', Text::get('FEEDBACK_VERIFICATION_MAIL_SENDING_ERROR') . $mail->getError() );
        }

        Redirect::to('po/detail?po_number=' . urlencode($po_number));
    }

    //QC
    public function approveQC()
    {
        $po_number = urldecode(Request::get('po_number'));
        $totalrecord = Request::post('total_record');
        $total_qty_rejected = 0;
        $total_qty_approved = 0;


        for ($i = 1; $i <= $totalrecord; $i++) {
            $quantity_incoming = Request::post('qty_incoming_' . $i);
            $qty_received = Request::post('qty_receive_' . $i);
            $uid = Request::post('uid_' . $i);
            if ($qty_received >= 0 AND is_numeric($qty_received)) {
                //check jika quantity diterima lebih dari qty incoming
                if ($qty_received > $quantity_incoming) {
                    $where = "`uid` = '{$uid}'";
                    $result = GenericModel::getOne('`material_list_in` LEFT JOIN `material_list` ON `material_list`.`material_code` = `material_list_in`.`material_code`', $where, '`material_list`.`material_code`,`material_list`.`material_name`');

                    Session::add('feedback_negative', 'Error, Jumlah barang yang diterima lebih besar dari jumlah barang masuk: (' . $result->material_code . ') ' . $result->material_name);
                    Redirect::to('po/detail?po_number=' . urlencode($po_number));
                    exit;
                }

                $quantity_reject = $quantity_incoming - $qty_received;
                $total_qty_rejected = $total_qty_rejected + $quantity_reject;
                $total_qty_approved = $total_qty_approved + $qty_received;

                $table = 'material_list_in';
                $update = array(
                    'quantity_stock'  =>  $qty_received,
                    'quantity_received'  =>  $qty_received,
                    'quantity_reject'  =>  $quantity_reject,
                    'qc_pass_date'    => date("Y-m-d"),
                    'status'      =>  'stock',
                    'note'      =>  Request::post('note_' . $i),
                );

                
                $where = "`uid` = '{$uid}'";
                GenericModel::update($table, $update, $where);
            }
        }

        //SEND EMAIL NOTIFICATION
        //GET RELATED Departemen email
        $email_address = GenericModel::getAll('users', "`department` = 'purchasing' OR `department` = 'ppic' OR `department` = 'finance'", '`email`');
        $email = array();
        foreach ($email_address as $key => $value) {
            $email[] = $value->email;
        }
        //tambah pt. ilmui email
        $email[] = 'edi@sbautomedia.com';

        //Notifikasi untuk reject
        if ($total_qty_rejected > 0) {
            $email_creator = SESSION::get('full_name');
            $email_subject = "QC Reject Notification for " . $po_number . ' by ' . $email_creator;
            $body = ucwords($email_creator) . ' mereject material pada PO nomer ' . $po_number . '. Klik link berikut untuk melihat detail PO ' .   Config::get('URL') . 'po/detail/?po_number=' . urlencode($po_number);
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
            $email_subject = "QC PASSED Notification for " . $po_number . ' by ' . $email_creator;
            $body = ucwords($email_creator) . ' menerima (QC PASSED) material pada PO nomer ' . $po_number . '. Klik link berikut untuk melihat detail PO ' .   Config::get('URL') . 'po/detail/?po_number=' . urlencode($po_number);
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

       Redirect::to('po/detail?po_number=' . urlencode($po_number));
    }

    public function insertPayment()
    {
        $po_number = urldecode(Request::get('po_number'));
        $totalrecord = count($_POST['value']);
        $total_order = (float)Request::post('total_order');

        //check if payment list total is same as total purchase
        $total_payment = 0;
        for ($i = 1; $i <= $totalrecord; $i++) {
            if ($_POST['value'][$i] > 0)
                { // only execute only when qty is not blank
                    $total_payment = $total_payment + FormaterModel::floatNumber($_POST['value'][$i]);

                    //check if there's empty schedule date and peyment type
                    if (empty($_POST['payment_type'][$i]) OR empty($_POST['payment_due_date'][$i])) {
                        Session::add('feedback_negative', 'GAGAGL!. Tanggal rencana pembayaran atau tipe pembayaran tidak diisi.');
                        Redirect::to('po/detail?po_number=' . urlencode($po_number));
                        exit;
                    }
                } // END IF
        } // END FOR

        /*
        if ($total_payment != $total_order) {
            Session::add('feedback_negative', 'GAGAGL!. Jumlah total uang yang dimasukkan tidak sama dengan jumlah uang pembelian.');
            Redirect::to('po/detail?po_number=' . urlencode($po_number));
            exit;
        }
        */
        
        for ($i = 1; $i <= $totalrecord; $i++) {
            $uid = GenericModel::guid();
            if ($_POST['value'][$i] > 0) { // only execute only when qty is not blank
                    $insert = array(
                        'uid'    => $uid,
                        'transaction_code' => $po_number,
                        'transaction_type' => 'purchase order',
                        'credit' => FormaterModel::floatNumber($_POST['value'][$i]),
                        'status' => -1,
                        'currency' => trim(strip_tags($_POST['currency'][$i])),
                        'payment_type' => trim(strip_tags($_POST['payment_type'][$i])),
                        'invoice_number' => trim(strip_tags($_POST['invoice_number'][$i])),
                        'invoice_date' => date("Y-m-d", strtotime(trim(strip_tags($_POST['invoice_date'][$i])))),
                        'payment_due_date' => date("Y-m-d", strtotime(trim(strip_tags($_POST['payment_due_date'][$i])))),
                        'note' => trim(strip_tags($_POST['note'][$i])),
                        'creator_id'    => SESSION::get('uid')
                    );
                    GenericModel::insert('payment_transaction', $insert);
                } // END IF

                if ($_POST['ppn'][$i] > 0) { // only execute only when qty is not blank
                    $insert = array(
                        'uid'    => $uid,
                        'transaction_reference' => $po_number,
                        'tax_type' => 'ppn',
                        'credit' => FormaterModel::floatNumber($_POST['ppn'][$i]),
                        'status' => -1,
                        'creator_id'    => SESSION::get('uid')
                    );
                    GenericModel::insert('tax', $insert);
                } // END IF
        } // END FOR
        
        Redirect::to('po/detail?po_number=' . urlencode($po_number));
    }

    public function confirmPayment()
    {
        $po_number = urldecode(Request::get('po_number'));
        $totalrecord = count($_POST['payment_disbursement']);
        $counter = 1;
        for ($i = 1; $i <= $totalrecord; $i++) {
            if (!empty($_POST['payment_disbursement'][$i]))
                { // only execute only when qty is not blank
                    $uid = trim(strip_tags($_POST['uid'][$i]));

                    $update = array(
                        'status' => 1,
                        'debit' => trim(strip_tags($_POST['debit'][$i])),
                        'payment_type' => trim(strip_tags($_POST['payment_type'][$i])),
                        'payment_disbursement' => date("Y-m-d", strtotime(trim(strip_tags($_POST['payment_disbursement'][$i])))),
                        'note' => trim(strip_tags($_POST['note'][$i])),
                        'modifier_id'    => SESSION::get('uid'),
                        'modified_timestamp' => date("Y-m-d H:i:s")
                        );
                    GenericModel::update('payment_transaction', $update, "`uid` = '$uid'");
                    $counter++;
                } // END IF
            } // END FOR

        Redirect::to('po/detail?po_number=' . urlencode($po_number));
    }

    public function deleteReceivedMaterial($uid) {

            $table = '`material_list_in`';
            $where = "`uid` = '{$uid}'";
            $pr_data_item = GenericModel::getOne($table, $where);

            $table = '`purchase_order`';
            $where = "`transaction_number` = '{$pr_data_item->transaction_number}'";
            $field = "`log`";
            $pr_data = GenericModel::getOne($table, $where, $field);

            $log = "Material: <span class='red'>" . $pr_data_item->material_code . "</span><br />
                    Quantity: <span class='red'>" . $pr_data_item->quantity . "</span> <br />
                    Quantity Diterima: <span class='red'>" . $pr_data_item->quantity_received . "</span><br />
                    Quantity Reject: <span class='red'>" . $pr_data_item->quantity_reject . "</span><br />
                    Note: <span class='red'>"  . $pr_data_item->note . " </span>";
            $log  = '<li><span class="badge badge-grey">' . SESSION::get('user_name') .'</span> Delete material yang sudah diterima dengan detail:<br />' . $log . '<br />(' . date("Y-m-d") . ')</li>' . $pr_data->log;

            GenericModel::remove('material_list_in', 'uid', $uid);

            //make log
            $update = array(
                        'log' => addslashes($log),
                        'modifier_id'    => SESSION::get('uid'),
                        'modified_timestamp' => date("Y-m-d H:i:s")
                        );
            GenericModel::update('purchase_order', $update, "`transaction_number` = '{$pr_data_item->transaction_number}'");
            Redirect::to(Request::get('forward'));
    }

    public function changeStatusReceivedMaterial($status = null, $uid) {
            $po_number = urldecode(Request::get('po_number'));
            if ($status == 0) {
                $received = 'partial received';
            } else if ($status == 1) {
                $received = 'full received';
            }

            //make log
            $update = array(
                        'status' => $received,
                        'modifier_id'    => SESSION::get('uid'),
                        'modified_timestamp' => date("Y-m-d H:i:s")
                        );
            GenericModel::update('purchase_order_list', $update, "`uid` = '{$uid}'");
            Redirect::to('po/detail?po_number=' . urlencode($po_number));
    }

    public function report($start_date = null, $end_date = null)
    {
        if (Request::post('change_date') == 'ok') { // user input change date
            Redirect::to('po/report/' . Request::post('start_date') . '/' . Request::post('end_date'));
            exit;
        }

        if ($start_date === null AND $end_date === null) {
            $start_date = date('Y-m-01');
            $end_date = date('Y-m-d');
        }

        $sql_group = "
            SELECT
                `purchase_order`.`transaction_number` as `transaction_number`,
                `purchase_order`.`created_timestamp` as `created_timestamp`,
                `purchase_order`.`supplier_id` as `supplier_id`,
                GROUP_CONCAT(`material_list`.`material_name` SEPARATOR '-, -') as `material_name`,
                GROUP_CONCAT(`purchase_order_list`.`quantity` SEPARATOR '-, -') as `quantity`,
                GROUP_CONCAT(`purchase_order_list`.`purchase_price` SEPARATOR '-, -') as `purchase_price`,
                GROUP_CONCAT(`purchase_order_list`.`purchase_tax` SEPARATOR '-, -') as `purchase_tax`,                  
                `users`.`full_name`,
                `contact`.`contact_name`
            FROM
                `purchase_order`
            LEFT JOIN
                `purchase_order_list` AS `purchase_order_list` ON `purchase_order_list`.`transaction_number` = `purchase_order`.`transaction_number`
            LEFT JOIN
                `material_list` AS `material_list` ON `purchase_order_list`.`material_code` = `material_list`.`material_code`
            LEFT JOIN
                `contact` AS `contact` ON `contact`.`contact_id` = `purchase_order`.`supplier_id`
            LEFT JOIN
                `users` AS `users` ON `users`.`uid` = `purchase_order`.`creator_id`
            WHERE
                `purchase_order`.`status` >= 0 AND (`purchase_order`.`created_timestamp` BETWEEN '$start_date 00:00:01' AND '$end_date 23:59:59')
            GROUP BY
                `purchase_order`.`transaction_number`
            ORDER BY
                `purchase_order`.`created_timestamp` ASC";

        $header_script = '<link rel="stylesheet" href="' . Config::get('URL') . 'bootstrap-3.3.7/css/excel-2007.css" media="screen"/><link rel="stylesheet" href="' . Config::get('URL') . 'bootstrap-3.3.7/css/datepicker.css" media="screen"/>';
        $footer_script = '<script src="' . Config::get('URL') . 'bootstrap-3.3.7/js/bootstrap-datepicker.js"></script>
        <script>
        $(".datepicker").datepicker(); //date picker
        </script>';

        $this->View->render('po/report',
                  array(
                'header_script' => $header_script,
                'footer_script' => $footer_script,
                'title' => 'Approved Purchase Order',
                'activelink1' => 'supply chain',
                'activelink2' => 'pr/po',
                'activelink3' => 'supply chain pr/po report',
                'po_list' => GenericModel::rawSelect($sql_group),
            ));
    }

        /**
     * Perform the upload image
     * POST-request
     */
    public function uploadImage()
    {   
        $po_number = Request::post('po_number');
        $image_name = 'file_name';
        $image_rename = Request::post('image_name');
        $destination = 'purchase-order';
        $note = Request::post('note');
        UploadModel::uploadImage($image_name, $image_rename, $destination, $po_number, $note);
        Redirect::to('po/detail/?po_number=' . urlencode($po_number));
    }

     /**
     * Perform the upload pdf, xlsx, doc, docx
     * POST-request
     */
    public function uploadDocument()
    {
        $po_number = Request::post('po_number');
        $image_name = 'file_name';
        $image_rename = Request::post('document_name');
        $destination = 'purchase-order';
        $note = Request::post('note');
        UploadModel::uploadDocument($image_name, $image_rename, $destination, $po_number, $note);
        Redirect::to('po/detail/?po_number=' . urlencode($po_number));
    }

    public function duplicatePO()
    {
        $po_reference = Request::get('po_number');

        // Get latest PO Number, format PR is TBE 0013/PO-0315
        $awal_tahun = date('Y-01-01');
        $table = '`purchase_order`';
        $where = "`transaction_number` LIKE '%/PO-%' AND `created_timestamp` >= '{$awal_tahun}' ORDER BY `created_timestamp` DESC";
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

        $po_reference_detail = GenericModel::getOne('`purchase_order`', "`transaction_number` = '{$po_reference}'", '`supplier_id`');

        $table = '`purchase_order_list`';
        $where = "`transaction_number` = '{$po_reference}'";
        $field = "*";
        $po_reference_list = GenericModel::getAll($table, $where, $field);

        foreach ($po_reference_list as $key => $value) {
            $insert = array(
                        'uid' => GenericModel::guid(),
                        'transaction_number'  => $po_number,
                        'material_code' => $value->material_code,
                        'purchase_price' => $value->purchase_price,
                        'purchase_price_discount' => $value->purchase_price_discount,
                        'purchase_currency' => $value->purchase_currency,
                        'purchase_tax' => $value->purchase_tax,
                        'packaging' => $value->packaging,
                        'material_specification' => $value->material_specification,
                        'budget_category' => $value->budget_category,
                        'budget_item' => $value->budget_item,
                        'quantity' => $value->quantity,
                        'unit' => $value->unit,
                    );
            GenericModel::insert('purchase_order_list', $insert);
        }

        $insert = array(
                        'transaction_number' => $po_number,
                        'supplier_id' => $po_reference_detail->supplier_id,
                        'due_date' => date('Y-m-d'),
                        'creator_id'    => SESSION::get('uid')
                        );
        GenericModel::insert('purchase_order', $insert);
        

        Redirect::to('po/detail/?po_number=' . urlencode($po_number));
    }

}
