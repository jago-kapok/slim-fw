<?php


class SoController extends Controller {

    /**
     * Construct this object by extending the basic Controller class
     */
    public function __construct()
    {
        parent::__construct();

        Auth::checkAuthentication();
    }

    /**
     * @param int $page
     * @param int $limit
     */
    public function index($page = 1, $limit = 20)
    {
        if (empty($_GET['find'])) {echo 'search only please'; exit;}
        $find = strtolower(Request::get('find'));
        $find = trim($find);
        $offset = ($page > 1) ? ($page - 1) * $limit : 0;

        if (Auth::isPermissioned('director,management,finance,ppic')) {
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
                    `sales_order`.`transaction_number` = '{$find}' OR
                    `sales_order`.`customer_id` = '{$find}' OR
                    `contact`.`contact_name` LIKE '%{$find}%' OR
                    `sales_order_list`.`material_code` LIKE '%{$find}%' OR
                    `material_list`.`material_name` = '{$find}'
                GROUP BY
                     `sales_order`.`transaction_number`
                LIMIT
                    $offset, $limit";
        //For pagination
        $total_record = GenericModel::totalRow('`sales_order`','`transaction_number`');

        } else {
            $creator_id = SESSION::get('uid');
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
                     `sales_order`.`creator_id` = '{$creator_id}' AND
                     (`sales_order`.`transaction_number` = '{$find}' OR
                    `sales_order`.`customer_id` = '{$find}' OR
                    `contact`.`contact_name` LIKE '%{$find}%' OR
                    `sales_order_list`.`material_code` LIKE '%{$find}%' OR
                    `material_list`.`material_name` = '{$find}')
                GROUP BY
                     `sales_order`.`transaction_number`
                LIMIT
                    $offset, $limit";

            //For pagination
            $total_record = GenericModel::rowCount('`sales_order`', "`sales_order`.`creator_id` = '{$creator_id}'", '`transaction_number`');
        }

        //For pagination
        $string_search = '?find=' . $find;
                   
        $header_script = '<link rel="stylesheet" href="' . Config::get('URL') . 'bootstrap-3.3.7/css/excel-2007.css" media="screen"/>';
        $this->View->render('so/index',
            array(
            'header_script' => $header_script,
            'title' => 'Search SO List',
            'page' => $page,
            'limit' => $limit,
            'activelink1' => 'penjualan',
            'activelink2' => 'sales order',
            'activelink3' => 'draft so',
            'transaction_group' => GenericModel::rawSelect($sql_group),
            'pagination' => FormaterModel::pagination('so/index', $total_record, $page, $limit,$string_search)
            )
        );
    }

    public function draftSo($page = 1, $limit = 20)
    {
        $offset = ($page > 1) ? ($page - 1) * $limit : 0;
        if (Auth::isPermissioned('director,management,finance,ppic')) {
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
                    `sales_order`.`status` < - 1
                GROUP BY
                     `sales_order`.`transaction_number`
                ORDER BY
                    `sales_order`.`created_timestamp` DESC
                LIMIT
                    $offset, $limit";
                //Use Left karena penjualan kasir langsung tanpa input customer, kalo pake join data customer_id gak ada di table contact!

                //For pagination
                $total_record = GenericModel::rowCount('`sales_order`', '`sales_order`.`status` < - 1', '`transaction_number`');
        } else {
            $creator_id = SESSION::get('uid');
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
                    `sales_order`.`status` < -1 AND `sales_order`.`creator_id` = '{$creator_id}'
                GROUP BY
                     `sales_order`.`transaction_number`
                ORDER BY
                    `sales_order`.`created_timestamp` DESC
                LIMIT
                    $offset, $limit";
                //Use Left karena penjualan kasir langsung tanpa input customer, kalo pake join data customer_id gak ada di table contact!

                //For pagination
                $total_record = GenericModel::rowCount('`sales_order`', "`sales_order`.`status` < -1 AND `sales_order`.`creator_id` = '{$creator_id}'", '`transaction_number`');
        }
        
        $header_script = '<link rel="stylesheet" href="' . Config::get('URL') . 'bootstrap-3.3.7/css/excel-2007.css" media="screen"/>';
        $this->View->render('so/draft_so',
            array(
            'header_script' => $header_script,
            'title' => 'Draft SO List',
            'page' => $page,
            'limit' => $limit,
            'activelink1' => 'penjualan',
            'activelink2' => 'sales order',
            'activelink3' => 'draft so',
            'transaction_group' => GenericModel::rawSelect($sql_group),
            'pagination' => FormaterModel::pagination('so/draftSo', $total_record, $page, $limit)
            )
        );
    }

    public function waitingApproval($page = 1, $limit = 20)
    {
        $offset = ($page > 1) ? ($page - 1) * $limit : 0;
        if (Auth::isPermissioned('director,management,finance,ppic')) {
            $sql_group = "
                SELECT
                    `sales_order`.`transaction_number`,
                    `sales_order`.`feedback_note`,
                    `sales_order`.`delivery_request_date`,
                    `sales_order`.`created_timestamp`,
                    `sales_order`.`customer_id`,
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
                    `sales_order`.`status` = -1
                GROUP BY
                     `sales_order`.`transaction_number`
                ORDER BY
                    `sales_order`.`created_timestamp` DESC";
                //Use Left karena penjualan kasir langsung tanpa input customer, kalo pake join data customer_id gak ada di table contact!

                //For pagination
                $total_record = GenericModel::rowCount('`sales_order`', "`sales_order`.`status` = -1", '`transaction_number`');

        } else {
            $creator_id = SESSION::get('uid');
            $sql_group = "
                SELECT
                    `sales_order`.`transaction_number`,
                    `sales_order`.`feedback_note`,
                    `sales_order`.`delivery_request_date`,
                    `sales_order`.`created_timestamp`,
                    `sales_order`.`customer_id`,
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
                    `sales_order`.`status` = -1 AND `sales_order`.`creator_id` = '{$creator_id}'
                GROUP BY
                     `sales_order`.`transaction_number`
                ORDER BY
                    `sales_order`.`created_timestamp` DESC";
                //Use Left karena penjualan kasir langsung tanpa input customer, kalo pake join data customer_id gak ada di table contact!

                //For pagination
                $total_record = GenericModel::rowCount('`sales_order`', "`sales_order`.`status` = -1 AND `sales_order`.`creator_id` = '{$creator_id}'", '`transaction_number`');
        }
                   
        $header_script = '<link rel="stylesheet" href="' . Config::get('URL') . 'bootstrap-3.3.7/css/excel-2007.css" media="screen"/>';
        $footer_script = '
            <script type="text/javascript">
  function approveSo(so_number, delivery_request_date, row_id) {
      //Send the string to server
      var http = new XMLHttpRequest();
      var url = "' . Config::get('URL') . 'so/approveSo/";
      var params = "so_number=" + so_number + "&delivery_request_date=" + delivery_request_date;
      //Send the proper header information along with the request
      http.open("POST", url, true);
      http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
      http.onreadystatechange = function() {//Call a function when the state changes.
        if(http.readyState === XMLHttpRequest.DONE && http.status == 200) {
            var salesCode = http.response;
            //check response from server, if contain strting sucess, save (force user to clik save again) and reset page
            if (salesCode.indexOf("SUKSES") === -1) {
                    alert(salesCode);
                    console.log(salesCode);
                } else {
                  toggle(row_id, "none");
                  alert(salesCode);
                  console.log(salesCode);
                }
          }
        }
      http.send(params);
}

function toggle(className, displayState){  //none or block. none hide, block show
    var elements = document.getElementsByClassName(className)

    for (var i = 0; i < elements.length; i++){
        elements[i].style.display = displayState;
    }
}
</script>';
        $this->View->render('so/waiting_approval',
            array(
            'header_script' => $header_script,
            'footer_script' => $footer_script,
            'title' => 'Waiting Approval Sales Order',
            'page' => $page,
            'limit' => $limit,
            'activelink1' => 'penjualan',
            'activelink2' => 'sales order',
            'activelink3' => 'so waiting approval',
            'transaction_group' => GenericModel::rawSelect($sql_group),
            'pagination' => FormaterModel::pagination('so/waitingApproval', $total_record, $page, $limit)
            )
        );
    }

    public function approved($page = 1, $limit = 20)
    {
        $offset = ($page > 1) ? ($page - 1) * $limit : 0;
        if (Auth::isPermissioned('director,management,finance,ppic')) {
            $sql_group = "
                SELECT
                    `sales_order`.`transaction_number`,
                    `sales_order`.`created_timestamp`,
                    `sales_order`.`customer_id`,
                    `sales_order`.`status`,
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
                    `sales_order`.`status` >= 0
                GROUP BY
                     `sales_order`.`transaction_number`
                ORDER BY
                    `sales_order`.`created_timestamp` DESC
                LIMIT
                    $offset, $limit";
                //Use Left karena penjualan kasir langsung tanpa input customer, kalo pake join data customer_id gak ada di table contact!

                //For pagination
                $total_record = GenericModel::rowCount('`sales_order`', "`sales_order`.`status` >= 0", '`transaction_number`');
        } else {
            $creator_id = SESSION::get('uid');
            $sql_group = "
                SELECT
                    `sales_order`.`transaction_number`,
                    `sales_order`.`created_timestamp`,
                    `sales_order`.`customer_id`,
                    `sales_order`.`status`,
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
                    `sales_order`.`status` >= 0 AND `sales_order`.`creator_id` = '{$creator_id}'
                GROUP BY
                     `sales_order`.`transaction_number`
                ORDER BY
                    `sales_order`.`created_timestamp` DESC
                LIMIT
                    $offset, $limit";
                //For pagination
                $total_record = GenericModel::rowCount('`sales_order`', "`sales_order`.`status` >= 0 AND `sales_order`.`creator_id` = '{$creator_id}'", '`transaction_number`');
        }
                   
        $header_script = '<link rel="stylesheet" href="' . Config::get('URL') . 'bootstrap-3.3.7/css/excel-2007.css" media="screen"/>';
        $this->View->render('so/approved',
            array(
            'header_script' => $header_script,
            'title' => 'Approved Sales Order',
            'page' => $page,
            'limit' => $limit,
            'activelink1' => 'penjualan',
            'activelink2' => 'sales order',
            'activelink3' => 'approved so',
            'transaction_group' => GenericModel::rawSelect($sql_group),
            'pagination' => FormaterModel::pagination('so/approved', $total_record, $page, $limit)
            )
        );
    }

    public function waitingSjApproval()
    {
        if (Auth::isPermissioned('director,management,finance,ppic')) {
            $sql_group = "
                SELECT
                    `sales_order`.`transaction_number`,
                    `sales_order`.`created_timestamp`,
                    `sales_order`.`customer_id`,
                    `material_list_out`.`material_code`,
                    GROUP_CONCAT(`material_list`.`material_name` SEPARATOR '-, -') as `material_name`,
                    GROUP_CONCAT(`material_list_out`.`quantity` SEPARATOR '-, -') as `quantity`,
                    GROUP_CONCAT(`material_list_out`.`serial_number` SEPARATOR '-, -') as `serial_number`,
                    `users`.`full_name`,
                    `contact`.`contact_name`
                FROM
                    `sales_order`
                LEFT JOIN
                    `material_list_out` AS `material_list_out` ON `material_list_out`.`transaction_number` = `sales_order`.`transaction_number`
                LEFT JOIN
                    `material_list` AS `material_list` ON `material_list_out`.`material_code` = `material_list`.`material_code`
                
                LEFT JOIN
                    `contact` AS `contact` ON `contact`.`contact_id` = `sales_order`.`customer_id`
                LEFT JOIN
                    `users` AS `users` ON `users`.`uid` = `sales_order`.`creator_id`
                WHERE
                    `sales_order`.`status` = 7 AND `material_list_out`.`status` = 'do'
                GROUP BY
                     `sales_order`.`transaction_number`
                ORDER BY
                    `sales_order`.`created_timestamp` DESC";
        } else {
            $creator_id = SESSION::get('uid');
            $sql_group = "
                SELECT
                    `sales_order`.`transaction_number`,
                    `sales_order`.`created_timestamp`,
                    `sales_order`.`customer_id`,
                    `material_list_out`.`material_code`,
                    GROUP_CONCAT(`material_list`.`material_name` SEPARATOR '-, -') as `material_name`,
                    GROUP_CONCAT(`material_list_out`.`quantity` SEPARATOR '-, -') as `quantity`,
                    GROUP_CONCAT(`material_list_out`.`serial_number` SEPARATOR '-, -') as `serial_number`,
                    `users`.`full_name`,
                    `contact`.`contact_name`
                FROM
                    `sales_order`
                LEFT JOIN
                    `material_list_out` AS `material_list_out` ON `material_list_out`.`transaction_number` = `sales_order`.`transaction_number`
                LEFT JOIN
                    `material_list` AS `material_list` ON `material_list_out`.`material_code` = `material_list`.`material_code`
                
                LEFT JOIN
                    `contact` AS `contact` ON `contact`.`contact_id` = `sales_order`.`customer_id`
                LEFT JOIN
                    `users` AS `users` ON `users`.`uid` = `sales_order`.`creator_id`
                WHERE
                    `sales_order`.`status` = 7 AND `material_list_out`.`status` = 'do' AND `sales_order`.`creator_id` = '{$creator_id}'
                GROUP BY
                     `sales_order`.`transaction_number`
                ORDER BY
                    `sales_order`.`created_timestamp` DESC";
                //Use Left karena penjualan kasir langsung tanpa input customer, kalo pake join data customer_id gak ada di table contact!
        }
                   
        $header_script = '<link rel="stylesheet" href="' . Config::get('URL') . 'bootstrap-3.3.7/css/excel-2007.css" media="screen"/>';
        $this->View->render('so/waiting_sj_approval',
            array(
            'header_script' => $header_script,
            'title' => 'Approved Sales Order',
                'activelink1' => 'penjualan',
                'activelink2' => 'sales order',
                'activelink3' => 'waiting sj approval',
            'transaction_group' => GenericModel::rawSelect($sql_group)
            )
        );
    }

    public function waitingOpenDO($page = 1, $limit = 20)
    {
        $offset = ($page > 1) ? ($page - 1) * $limit : 0;
        if (Auth::isPermissioned('director,management,finance,ppic')) {
            $sql_group = "
                SELECT
                    `sales_order`.`transaction_number`,
                    `sales_order`.`created_timestamp`,
                    `sales_order`.`customer_id`,
                    `sales_order`.`status`,
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
                    `sales_order`.`status` >= 0 AND `sales_order`.`status` < 4
                GROUP BY
                     `sales_order`.`transaction_number`
                ORDER BY
                    `sales_order`.`created_timestamp` DESC
                LIMIT
                    $offset, $limit";
                //Use Left karena penjualan kasir langsung tanpa input customer, kalo pake join data customer_id gak ada di table contact!

                //For pagination
                $total_record = GenericModel::rowCount('`sales_order`', "`sales_order`.`status` >= 0 AND `sales_order`.`status` < 4", '`transaction_number`');
        } else {
            $creator_id = SESSION::get('uid');
            $sql_group = "
                SELECT
                    `sales_order`.`transaction_number`,
                    `sales_order`.`created_timestamp`,
                    `sales_order`.`customer_id`,
                    `sales_order`.`status`,
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
                    `sales_order`.`status` >= 0 AND `sales_order`.`status` < 4 AND `sales_order`.`creator_id` = '{$creator_id}'
                GROUP BY
                     `sales_order`.`transaction_number`
                ORDER BY
                    `sales_order`.`created_timestamp` DESC
                LIMIT
                    $offset, $limit";
                //For pagination
                $total_record = GenericModel::rowCount('`sales_order`', "`sales_order`.`status` >= 0 AND `sales_order`.`status` < 4 AND `sales_order`.`creator_id` = '{$creator_id}'", '`transaction_number`');
        }
                   
        $header_script = '<link rel="stylesheet" href="' . Config::get('URL') . 'bootstrap-3.3.7/css/excel-2007.css" media="screen"/>';
        $this->View->render('so/approved',
            array(
            'header_script' => $header_script,
            'title' => 'Approved Sales Order',
            'page' => $page,
            'limit' => $limit,
            'activelink1' => 'penjualan',
            'activelink2' => 'sales order',
            'activelink3' => 'approved so',
            'transaction_group' => GenericModel::rawSelect($sql_group),
            'pagination' => FormaterModel::pagination('so/approved', $total_record, $page, $limit)
            )
        );
    }

    public function createSo()
    {
        $this->View->renderFileOnly('so/create_so', array(
                'product_list' => GenericModel::getAll('material_list', "`is_deleted` = 0 AND (`material_type` = 3 OR `material_type` = 4)", "`material_code`,`material_name`,`selling_price`"),
                'customer_list' => GenericModel::getAll('contact', "`is_deleted` = 0", "`contact_id`, `contact_name`"),
                'company' => GenericModel::getAll('`system_preference`', "`category` = 'company_identification' ORDER BY `item_name` ASC", '`value`, `item_name`')

                )
        );
    }

    public function saveDraft()
    {
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
    }

    public function printSo() {
        $so_number = urldecode(Request::get('so_number'));
        $sj = "SELECT
                    `sales_order`.`transaction_number`,
                    `sales_order`.`note`,
                    `sales_order`.`created_timestamp`,
                    `contact`.`contact_name`,
                    `contact`.`address_street`,
                    `contact`.`address_city`,
                    `contact`.`address_state`,
                    `contact`.`address_zip`,
                    `contact`.`website`,
                    `contact`.`phone` as `customer_phone`,
                    `contact`.`fax`,
                    `contact`.`email` as `customer_email`,
                    `sales_order_list`.`quantity`,
                    `sales_order_list`.`selling_price`,
                    `sales_order_list`.`tax_ppn`,
                    `sales_order_list`.`tax_pph`,
                    `sales_order_list`.`transaction_number`,
                    `material_list`.`material_name`,
                    `material_list`.`unit`,
                    `users`.`full_name`,
                    `users`.`email`,
                    `users`.`phone`
                FROM
                    `sales_order`
                LEFT JOIN
                    `contact` ON `contact`.`contact_id` = `sales_order`.`customer_id`
                LEFT JOIN
                    `sales_order_list` ON `sales_order_list`.`transaction_number` = `sales_order`.`transaction_number`
                LEFT JOIN
                    `material_list` ON `material_list`.`material_code` = `sales_order_list`.`material_code`
                LEFT JOIN
                    `users` ON `users`.`uid` = `sales_order`.`creator_id`
                WHERE
                    `sales_order_list`.`transaction_number` = '$so_number'";
        $this->View->renderFileOnly('so/print_so', array(
                'product' => GenericModel::rawSelect($sj),
                'company' => GenericModel::getAll('`system_preference`', "`category` = 'company_identification' ORDER BY `item_name` ASC", '`value`, `item_name`')
        ));
        
    }

    public function detail()
    {
        $so_number = urldecode(Request::get('so_number'));

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
                    `sales_order_list`.`transaction_number` = '{$so_number}'";

        $make_do = "SELECT
                    `sales_order_list`.`material_code`,
                    `sales_order_list`.`quantity`,
                    `o`.`total_quantity_delivered`,
                    `material_list`.`material_name`
                FROM
                    `sales_order_list`
                JOIN
                    `material_list` AS `material_list` ON `material_list`.`material_code` = `sales_order_list`.`material_code`
                LEFT JOIN (
                    SELECT
                        `material_list_out`.`material_code`,
                        SUM(`material_list_out`.`quantity_delivered`) AS `total_quantity_delivered`
                    FROM
                        `material_list_out`
                    WHERE
                        `material_list_out`.`transaction_number` = '{$so_number}'
                    GROUP BY
                        `material_list_out`.`material_code`
                    ) AS `o` ON `o`.`material_code` = `sales_order_list`.`material_code`
                WHERE
                    `sales_order_list`.`transaction_number` = '{$so_number}'

                GROUP BY `sales_order_list`.`material_code`";

        $production_result_list = "SELECT
                    `serial_number`.`material_code`,
                    `serial_number`.`serial_number`
                    FROM
                        `serial_number`
                    WHERE
                        `serial_number`.`is_active` = 1
                        AND
                        (
                            `serial_number`.`transaction_number`  = '{$so_number}'
                            OR
                            `serial_number`.`transaction_number`  = ''
                        )";

        $production_forcasting = "SELECT
                    `production_forcasting_list`.`transaction_number`,
                    `production_forcasting_list`.`material_code`,
                    SUM(`production_forcasting_list`.`quantity`) AS `quantity`,
                    `production_forcasting_list`.`purchase_price`,
                    `production_forcasting_list`.`purchase_unit`,
                    SUM(`production_forcasting_list`.`production_price`) AS `production_price`,
                    `production_forcasting_list`.`note`,
                    `material_list`.`material_name`,
                    `material_list`.`unit`,
                    `stock`.`quantity_stock`
                FROM
                    `production_forcasting_list`
                LEFT JOIN
                    `material_list` AS `material_list` ON `material_list`.`material_code` = `production_forcasting_list`.`material_code`
                LEFT JOIN
                    (SELECT
                        `material_code`,
                        SUM(`material_list_in`.`quantity_stock`) AS `quantity_stock`
                    FROM
                        `material_list_in`
                    GROUP  BY
                        `material_list_in`.`material_code`) AS `stock` ON `stock`.`material_code` = `material_list`.`material_code`
            
                WHERE
                    `production_forcasting_list`.`transaction_number` = '{$so_number}'
                GROUP BY
                    `production_forcasting_list`.`material_code`
                ORDER BY
                    `production_forcasting_list`.`material_code`";

        $do = "SELECT
                    `material_list_out`.`uid`,
                    `material_list_out`.`material_code`,
                    `material_list_out`.`quantity`,
                    `material_list_out`.`quantity_delivered`,
                    `material_list_out`.`do_number`,
                    `material_list_out`.`selling_price`,
                    `material_list_out`.`selling_tax`,
                    `material_list_out`.`delivery_date`,
                    `material_list_out`.`serial_number`,
                    `material_list_out`.`status`,
                    `material_list`.`material_name`
                FROM
                    `material_list_out`
                JOIN
                    `material_list` AS `material_list` ON `material_list`.`material_code` = `material_list_out`.`material_code`
                WHERE
                    `material_list_out`.`transaction_number` = '{$so_number}' AND `material_list_out`.`status` = 'do' ORDER BY `material_list_out`.`do_number`";
        $sj = "SELECT
                    `material_list_out`.`uid`,
                    `material_list_out`.`material_code`,
                    `material_list_out`.`quantity`,
                    `material_list_out`.`quantity_delivered`,
                    `material_list_out`.`do_number`,
                    `material_list_out`.`delivery_date`,
                    `material_list_out`.`serial_number`,
                    `material_list_out`.`status`,
                    `material_list`.`material_name`
                FROM
                    `material_list_out`
                JOIN
                    `material_list` AS `material_list` ON `material_list`.`material_code` = `material_list_out`.`material_code`
                WHERE
                    `material_list_out`.`transaction_number` = '{$so_number}' AND `material_list_out`.`status` = 'sj' ORDER BY `material_list_out`.`do_number`";
        $so = "
                SELECT
                    `sales_order`.`transaction_number`,
                    `sales_order`.`created_timestamp`,
                    `sales_order`.`delivery_request_date`,
                    `sales_order`.`customer_id`,
                    `sales_order`.`note`,
                    `sales_order`.`feedback_note`,
                    `sales_order`.`status`,
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
                    `sales_order`.`transaction_number` = '{$so_number}'";

        //Uploaded File For Berita Acara
        $uploaded_file = "SELECT `item_name`, `item_id`, `value`, `uid`, `note`  FROM `upload_list` WHERE `category` =  'sales-order' AND `item_id` = '{$so_number}' AND `is_deleted` = 0";

        $table = '`system_preference`';
        $where = "`category` = 'payment_type'";
        $field = "`item_name`";
        $payment_type = GenericModel::getAll($table, $where, $field);

        $header_script = '<link rel="stylesheet" href="' . Config::get('URL') . 'bootstrap-3.3.7/css/excel-2007.css" media="screen"/><link rel="stylesheet" href="' . Config::get('URL') . 'bootstrap-3.3.7/css/datepicker.css" media="screen"/>';
        $footer_script = '<script src="' . Config::get('URL') . 'bootstrap-3.3.7/js/bootstrap-datepicker.js">
        </script>
        <script>
        $(".datepicker").datepicker(); //date picker

        </script>';
        $this->View->render('so/detail',
            array(
            'header_script' => $header_script,
            'footer_script' => $footer_script,
            'title' => 'Sales Order Number: ' . $so_number,
            'activelink1' => 'penjualan',
            'activelink2' => 'sales order',
            'so' => GenericModel::rawSelect($so, false),
            'transaction' => GenericModel::rawSelect($so_transaction),
            'production_result_list' => GenericModel::rawSelect($production_result_list),
            'make_do' => GenericModel::rawSelect($make_do),
            'do' => GenericModel::rawSelect($do),
            'sj' => GenericModel::rawSelect($sj),
            'production_forcasting' => GenericModel::rawSelect($production_forcasting),
            'uploaded_file' => GenericModel::rawSelect($uploaded_file),
            'payment_type' => $payment_type,
            )
        );
    }
        //approve so dengan tiap sub jo dijadikan job order sendiri sendiri
        public function approveSoTrial()
    {
        $so_number = Request::post('so_number');
        $delivery_request = Request::post('delivery_request_date');
        
        //duplicate product list
        $sql = "SELECT
                    `material_code`,
                    `quantity`,
                    `unit`
                FROM
                    `sales_order_list`
                WHERE
                    `transaction_number` = '{$so_number}'";

        $product_list = GenericModel::rawSelect($sql);

        foreach ($product_list as $key => $value) {

            //Untuk Duplicate satu bahan jadi 1 JO
            for ($i=1; $i <= $value->quantity ; $i++) {

                //Make Production number Utama DUlu sebelum membuat job number sub job
                $awal_tahun = date('Y-01-01');
                $table = '`job_order`';
                $where = "(`created_timestamp` >= '{$awal_tahun}' AND `job_category` = 'production order') ORDER BY `created_timestamp` DESC";
                $field = "`job_number`";
                $so_data = GenericModel::getOne($table, $where, $field);
                $job_number = $so_data->job_number;
                $find_integer = explode('/', $job_number);
                $job_number = $find_integer[0];
                $job_number = (int) FormaterModel::getNumberOnly($job_number);
                $job_number = $job_number + 1;
                $job_number = "000".$job_number;
                $job_number = substr($job_number, strlen($job_number)-5, 5);
                $job_number = Config::get('COMPANY_CODE') . ' ' . $job_number . '/JO-' . date("my");

                $insert = array(
                                'uid'    => GenericModel::guid(),
                                'material_code'    => $value->material_code,
                                'quantity'    => 1,
                                'unit'    => $value->unit,
                                'job_number' => $job_number,
                                'creator_id'    => SESSION::get('uid')
                            );
                GenericModel::insert('job_order_product_list', $insert, false);

                //duplicate forcasting to Job order Raw material list
                $sql = "SELECT
                            `transaction_number`,
                            `job_type`,
                            `sub_job_type`,
                            `material_code`,
                            `quantity`,
                            `purchase_price`,
                            `note`
                        FROM
                            `production_forcasting_list`
                        WHERE
                            `transaction_number` = '{$so_number}'";

                $production_forcasting = GenericModel::rawSelect($sql);

                foreach ($production_forcasting as $key_material => $value_material) {
                    $insert = array(
                                'uid'    => GenericModel::guid(),
                                'job_type'    => $value_material->job_type,
                                'sub_job_type'    => $value_material->sub_job_type,
                                'material_code'    => $value_material->material_code,
                                'job_number' => $job_number,
                                'creator_id'    => SESSION::get('uid')
                            );
                    GenericModel::insert('job_order_material_list', $insert, false);
                }

                //make Production Order
                $insert = array(
                                'job_number'    => $job_number,
                                'job_category'    => 'production order',
                                'job_reverence'    => $so_number,
                                'delivery_request'    => $delivery_request,
                                'creator_id'    => SESSION::get('uid'),
                                'is_active'    => 1,

                        );
                // Send Status insert to front end
                GenericModel::insert('job_order', $insert, false); // use silent so inser to commerce not counted as item inserted to sales

                //+++ JOB UTAMA SELESAI +++//

                //Untuk dupicate tiap sub job jadi satu JO Number
                $sql = "SELECT
                            `job_type`
                        FROM
                            `production_forcasting_list`
                        WHERE
                            `transaction_number` = '{$so_number}'
                        GROUP BY
                            `job_type`";

                $production_forcasting = GenericModel::rawSelect($sql);

                foreach ($production_forcasting as $key_forcasting => $value_forcasting) {
                    if (!empty($value_forcasting->job_type)) {
                        //buat sub job number
                        $sub_job_number = $job_number . '-' . $value_forcasting->job_type;
                        $insert = array(
                                    'uid'    => GenericModel::guid(),
                                    'material_code'    => $value->material_code,
                                    'material_specification' => $value_forcasting->job_type,
                                    'quantity'    => 1,
                                    'unit'    => $value->unit,
                                    'job_number' => $sub_job_number,
                                    'creator_id'    => SESSION::get('uid')
                                );
                        GenericModel::insert('job_order_product_list', $insert, false);

                        //duplicate forcasting to Job order Raw material list
                        /*
                        $sql = "SELECT
                                    `transaction_number`,
                                    `job_type`,
                                    `sub_job_type`,
                                    `material_code`,
                                    `quantity`,
                                    `purchase_price`,
                                    `note`
                                FROM
                                    `production_forcasting_list`
                                WHERE
                                    `transaction_number` = '{$so_number}'";

                        $production_forcasting = GenericModel::rawSelect($sql);

                        foreach ($production_forcasting as $key => $value) {
                            $insert = array(
                                        'uid'    => GenericModel::guid(),
                                        'job_type'    => $value->job_type,
                                        'sub_job_type'    => $value->sub_job_type,
                                        'material_code'    => $value->material_code,
                                        'job_number' => $sub_job_number,
                                        'creator_id'    => SESSION::get('uid')
                                    );
                            GenericModel::insert('job_order_material_list', $insert, false);
                        }
                        */

                        //make Production Order
                        $insert = array(
                                        'job_number'    => $sub_job_number,
                                        'job_category'    => 'production order',
                                        'job_reverence'    => $so_number,
                                        'delivery_request'    => $delivery_request,
                                        'creator_id'    => SESSION::get('uid'),
                                        'note'    => 'Sub JO ' . $job_number . ' Tipe Pekerjaan: ' . $value_forcasting->job_type,
                                        'is_active'    => 1,

                                );
                        // Send Status insert to front end
                        GenericModel::insert('job_order', $insert, false); // use silent so inser to commerce not counted as item inserted to sales
                    } //end if !empty
                } //end foreach
            } //end for
        } //end foreach


        //update status sales order
        $update = array(
                        'status'    => 0,
                        'feedback_note'    => '',
                        'modifier_id'    => SESSION::get('uid'),
                        'modified_timestamp'    => date("Y-m-d H:i:s")
                        );
        GenericModel::update('sales_order', $update, "`transaction_number` = '$so_number'", false);

        //feedback for ajax request
        $feedback_positive = Session::get('feedback_positive');
        $feedback_negative = Session::get('feedback_negative');
        // echo out positive messages
        if (isset($feedback_positive)) {
            echo 'SUKSES, Sales Order Disetujui dan Production Order secara otomatis dibuat dengan nomer JO: ' . $job_number;
        }
        // echo out negative messages
        if (isset($feedback_negative)) {
            echo 'GAGAL!, Sales Order Gagal Disetujui';
        }
        // RESET counter feedback to unconfuse user
        Session::set('feedback_positive', null);
        Session::set('feedback_negative', null);
    }

    public function approveSo()
    {
        $so_number = Request::post('so_number');
        $delivery_request = Request::post('delivery_request_date');
        $update = array(
                        'status'    => 0,
                        'feedback_note'    => '',
                        'modifier_id'    => SESSION::get('uid'),
                        'modified_timestamp'    => date("Y-m-d H:i:s")
                        );
        GenericModel::update('sales_order', $update, "`transaction_number` = '$so_number'");
        
        //check apakah sudah pernah buat JO dari SO ini (Takutnya hasil edit ulang SO, kalo hasil edit ulang so gak usah bikin JO)
        $is_jo_exist = GenericModel::getOne('job_order', "`job_reverence` = '$so_number'", 'job_number');

        if (empty($is_jo_exist->job_number)) {
                //Buat Job Order Otomatis
                //Make Production number
                $job_number = str_replace("/SO-","/JO-",$so_number);
                //duplicate product list
                $sql = "SELECT
                            `material_code`,
                            `quantity`,
                            `unit`,
                            `note`
                        FROM
                            `sales_order_list`
                        WHERE
                            `transaction_number` = '{$so_number}'";

                $product_list = GenericModel::rawSelect($sql);

                foreach ($product_list as $key => $value) {
                    $insert = array(
                                'uid'    => GenericModel::guid(),
                                'material_code'    => $value->material_code,
                                'quantity'    => $value->quantity,
                                'unit'    => $value->unit,
                                'job_number' => $job_number,
                                'note' => $value->note,
                                'creator_id'    => SESSION::get('uid')
                            );
                    GenericModel::insert('job_order_product_list', $insert);
                }

                //duplicate forcasting to Job order Raw material list
                $sql = "SELECT
                            `transaction_number`,
                            `job_type`,
                            `sub_job_type`,
                            `material_code`,
                            `quantity`,
                            `purchase_price`,
                            `purchase_currency`,
                            `purchase_unit`,
                            `production_price`,
                            `note`
                        FROM
                            `production_forcasting_list`
                        WHERE
                            `transaction_number` = '{$so_number}'";

                $production_forcasting = GenericModel::rawSelect($sql);

                foreach ($production_forcasting as $key => $value) {
                    $insert = array(
                                'uid'    => GenericModel::guid(),
                                'job_type'    => $value->job_type,
                                'sub_job_type'    => $value->sub_job_type,
                                'material_code'    => $value->material_code,
                                'quantity'    => $value->quantity,
                                'purchase_currency'    => $value->purchase_currency,
                                'purchase_price'    => $value->purchase_price,
                                'purchase_unit'    => $value->purchase_unit,
                                'production_price'    => $value->production_price,
                                
                                'job_number' => $job_number,
                                'creator_id'    => SESSION::get('uid')
                            );
                    GenericModel::insert('job_order_material_list', $insert);
                }

                 //make Production Order
                    $insert = array(
                                    'job_number'    => $job_number,
                                    'job_category'    => 'production order',
                                    'job_reverence'    => $so_number,
                                    'delivery_request'    => $delivery_request,
                                    'creator_id'    => SESSION::get('uid'),
                                    'is_active'    => 1,

                            );
                    // Send Status insert to front end
                    GenericModel::insert('job_order', $insert, false); // use silent so inser to commerce not counted as item inserted to sales

            $sukses_status = 'SUKSES, Sales Order Disetujui dan Production Order secara otomatis dibuat dengan nomer JO: ' . $job_number;
        } else {
            $sukses_status = 'SUKSES, Sales Order Disetujui';
        }

        //feedback for ajax request
        $feedback_positive = Session::get('feedback_positive');
        $feedback_negative = Session::get('feedback_negative');
        // echo out positive messages
        if (isset($feedback_positive)) {
            echo $sukses_status;
        }
        // echo out negative messages
        if (isset($feedback_negative)) {
            echo 'GAGAL!, Sales Order Gagal Disetujui';
        }
        // RESET counter feedback to unconfuse user
        Session::set('feedback_positive', null);
        Session::set('feedback_negative', null);
    }

    public function askSoApproval()
    {
        $transaction_number = Request::get('so_number');
        $update = array(
                        'status'    => -1,
                        );

        GenericModel::update('sales_order', $update, "`transaction_number` = '{$transaction_number}'");

        //SEND EMAIL NOTIFICATION
        //get ALl director email
        $email_address = GenericModel::getAll('users', "`user_name` =  'nur' OR `user_name` =  'root'", '`email`');
        $email = array();
        foreach ($email_address as $key => $value) {
            $email[] = $value->email;
        }

        $email_creator = SESSION::get('full_name');
        $email_subject = "SO Approval Request " . $transaction_number . ' by ' . $email_creator;
        $body = ucwords($email_creator) . ' meminta  approval untuk sales order nomer ' . $transaction_number . '. Klik link berikut untuk melihat detail SO ' .   Config::get('URL') . 'so/detail/?so_number=' . urlencode($transaction_number);
        $mail = new Mail;
        $mail_sent = $mail->sendMail($email, Config::get('EMAIL_VERIFICATION_FROM_EMAIL'),
            Config::get('EMAIL_VERIFICATION_FROM_NAME'), $email_subject, $body
        );
        if ($mail_sent) {
            Session::add('feedback_positive', Text::get('FEEDBACK_VERIFICATION_MAIL_SENDING_SUCCESSFUL'));
        } else {
            Session::add('feedback_negative', Text::get('FEEDBACK_VERIFICATION_MAIL_SENDING_ERROR') . $mail->getError() );
        }

        Redirect::to('so/waitingApproval/');
    }


    public function insertPayment()
    {
        $so_number = urldecode(Request::get('so_number'));
        $total_order = (float)Request::post('total_order');
        $totalrecord = count($_POST['value']);

        //check if payment list total is same as total purchase
        $total_payment = 0;
        for ($i = 1; $i <= $totalrecord; $i++) {
            if ($_POST['value'][$i] > 0)
                { // only execute only when qty is not blank
                    $total_payment = $total_payment + FormaterModel::floatNumber($_POST['value'][$i]) + FormaterModel::floatNumber($_POST['ppn'][$i]);

                    //check if there's empty schedule date and peyment type
                    if (empty($_POST['payment_type'][$i]) OR empty($_POST['payment_due_date'][$i])) {
                        Session::add('feedback_negative', 'GAGAL!. Tanggal rencana pembayaran atau tipe pembayaran tidak diisi.');
                        Redirect::to('so/detail/?so_number=' . $so_number);
                        exit;
                    }
                } // END IF
        } // END FOR

        /*
        if ($total_payment != $total_order) {
            Session::add('feedback_negative', 'GAGAGL!. Jumlah total uang yang dimasukkan tidak sama dengan jumlah uang penjualan.');
            Redirect::to('so/detail/?so_number=' . $so_number);
            exit;
        }
        */
        
        for ($i = 1; $i <= $totalrecord; $i++) {
            $uid = GenericModel::guid();
            if ($_POST['value'][$i] > 0) { // only execute only when qty is not blank
                    $insert = array(
                        'uid'    => $uid,
                        'transaction_code' => $so_number,
                        'transaction_type' => 'sales order',
                        'debit' => FormaterModel::floatNumber($_POST['value'][$i]),
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
                        'transaction_reference' => $so_number,
                        'tax_type' => 'ppn',
                        'debit' => FormaterModel::floatNumber($_POST['ppn'][$i]),
                        'status' => -1,
                        'creator_id'    => SESSION::get('uid')
                    );
                    GenericModel::insert('tax', $insert);
                } // END IF
        } // END FOR
        
        //Update Meta Sales
        if ($total_payment == $total_order) {
            $update = array(
                        'status'    => 1,
                        'modifier_id'    => SESSION::get('uid'),
                        'modified_timestamp'    => date("Y-m-d H:i:s")
                        );
            GenericModel::update('sales_order', $update, "`transaction_number` = '$so_number'");
        }
        
        Redirect::to('so/detail/?so_number=' . $so_number);
        
    }

    public function editSO()
    {
        $so_number = urldecode(Request::get('so_number'));
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
                    `sales_order_list`.`transaction_number` = '{$so_number}'";

        $so = "
                SELECT
                    `sales_order`.`transaction_number`,
                    `sales_order`.`created_timestamp`,
                    `sales_order`.`customer_id`,
                    `sales_order`.`note`,
                    `sales_order`.`feedback_note`,
                    `sales_order`.`status`,
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
                    `sales_order`.`transaction_number` = '{$so_number}'";

        $this->View->renderFileOnly('so/edit_so', array(
                'product_list' => GenericModel::getAll('material_list', "`is_deleted` = 0 AND `material_type` = 3", "`material_code`,`material_name`,`selling_price`"),
                'customer_list' => GenericModel::getAll('contact', "`is_deleted` = 0", "`contact_id`, `contact_name`"),
                'so' => GenericModel::rawSelect($so, false),
                'transaction' => GenericModel::rawSelect($so_transaction),

                )
        );
    }

    public function saveEditSO()
    {
        $so_number = Request::post('so_number');
        //Check Apakah Customer Ada Atau Tidak
        $customer_code = explode(' -- ', Request::post('customer'));
        $contact_id = trim($customer_code[0]);
        if (!GenericModel::isExist('contact', 'contact_id', "$contact_id")) {
            echo 'Kode customer tidak ada di database!';
            exit;
        }

        $product_list   = explode(' ___ ', Request::post('product_list'));
        $product_list   = array_filter($product_list);
        //echo '<pre>';var_dump($product_list);echo '</pre>';

        //delete semua barang pembelian lama
        GenericModel::remove('sales_order_list', 'transaction_number', $so_number, false); //false for silent feedback

        //2. insert detail barang pembelian baru
        foreach ($product_list as $key => $value) {
            $product   = explode(' --- ', $value);
            //echo '<pre>';var_dump($product);echo '</pre>';KSP150/VNL --- 1 --- 17500 --- 10 ---  ---  ___ &customer=goo1 -- Oleh-Oleh Malang, CV.&deliveryRequest=2017-12-29
            $product_code = trim($product[0]);
            $product_qty = FormaterModel::getNumberOnly($product[1]);
            $product_price = FormaterModel::getNumberOnly($product[2]);
            $product_ppn = FormaterModel::getNumberOnly($product[3]);
            $product_pph = FormaterModel::getNumberOnly($product[4]);
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
                'delivery_request_date' => Request::post('deliveryRequest'),
                'creator_id'    => SESSION::get('uid')
            );
            GenericModel::insert('sales_order_list', $insert);

            //2.2 Update Selling Price in material List
            $update = array(
                        'selling_price' => $product_price,
                        'modifier_id'    => SESSION::get('user_name'),
                        );
            $cond = "`material_code` = '{$product_code}'";
            QgenericModel::update('material_list', $update, $cond);
    
        }


        //2.2 Update SO
        $update = array(
                        'transaction_number'    => $so_number,
                        'sales_channel' => 'sales order',
                        'status' => -2,
                        'note' => Request::post('note'),
                        'customer_id'    => $contact_id,
                        'creator_id'    => SESSION::get('uid')
                );
        $cond = "`transaction_number` = '{$so_number}'";
        QgenericModel::update('sales_order', $update, $cond);

        $feedback_positive = Session::get('feedback_positive');
        $feedback_negative = Session::get('feedback_negative');
        // echo out positive messages
        if (isset($feedback_positive)) {
            //echo 'SUKSES, ' . count($feedback_positive) . ' transaksi berhasil disimpan';
            echo Config::get('URL') . 'so/detail/?so_number=' . urlencode($so_number);
        }
        // echo out negative messages
        if (isset($feedback_negative)) {
            echo 'GAGAL!, ' . count($feedback_positive) . ' transaksi tidak disimpan';
        }
        // RESET counter feedback to unconfuse user
        Session::set('feedback_positive', null);
        Session::set('feedback_negative', null);
    }

    public function openDo()
    {
        $so_number = urldecode(Request::get('so_number'));
        $do = GenericModel::getOne('`sales_order`', "`transaction_number` = '{$so_number}'", 'status');
        if ($do->status >= 1) {
            $update = array(
                        'status'    => 4,
                        'modifier_id'    => SESSION::get('uid'),
                        'modified_timestamp'    => date("Y-m-d H:i:s")
                        );
            GenericModel::update('sales_order', $update, "`transaction_number` = '$so_number'");
        }
        Redirect::to('so/detail/?so_number=' . $so_number);
    }

    public function askSjApproval() {
        $so_number = urldecode(Request::get('so_number'));
        $update = array(
                    'status'    => 7,
                    'modifier_id'    => SESSION::get('uid'),
                    'modified_timestamp'    => date("Y-m-d H:i:s")
                    );
        GenericModel::update('sales_order', $update, "`transaction_number` = '$so_number'");

        //SEND EMAIL NOTIFICATION
        //get ALl director email
        $email_address = GenericModel::getAll('users', "`user_name` = 'eko' OR `user_name` = 'root'", '`email`');
        $email = array();
        foreach ($email_address as $key => $value) {
            $email[] = $value->email;
        }
        //var_dump($email);exit;

        $email_creator = SESSION::get('full_name');
        $email_subject = "Surat Jalan Request " . $so_number . ' by ' . $email_creator;
        $body = ucwords($email_creator) . ' meminta  approval surat jalan untuk sales order nomer ' . $so_number . '. Klik link berikut untuk melihat detail SO ' .   Config::get('URL') . 'so/detail/?so_number=' . urlencode($so_number);
        $mail = new Mail;
        $mail_sent = $mail->sendMail($email, Config::get('EMAIL_VERIFICATION_FROM_EMAIL'),
            Config::get('EMAIL_VERIFICATION_FROM_NAME'), $email_subject, $body
        );
        if ($mail_sent) {
            Session::add('feedback_positive', Text::get('FEEDBACK_VERIFICATION_MAIL_SENDING_SUCCESSFUL'));
        } else {
            Session::add('feedback_negative', Text::get('FEEDBACK_VERIFICATION_MAIL_SENDING_ERROR') . $mail->getError() );
        }

        Redirect::to('so/detail/?so_number=' . $so_number);
    }

    public function approveSj()
    {
        $so_number = urldecode(Request::get('so_number'));

        //make SN inactive
        $serial_number_sql = "SELECT
                    `material_list_out`.`serial_number`,
                    `material_list_out`.`do_number`
                FROM
                    `material_list_out`
                WHERE
                    `material_list_out`.`transaction_number` = '{$so_number}' AND `status` = 'do'";
        $serial_number = GenericModel::rawSelect($serial_number_sql);
        $do_number = $serial_number[0]->do_number;

        foreach ($serial_number as $key => $value) {
            if (!empty($value->serial_number)) {
                $sn_array = explode(',', $value->serial_number);
                foreach ($sn_array as $key => $sn_value) {
                    $sn_key = trim($sn_array[$key]);
                    $update = array(
                                'is_active' => 0
                                );
                    QgenericModel::update('serial_number', $update, "`serial_number` = '{$sn_key}'");
                }
            }
        }

        //update do to sj
        $query = "UPDATE `material_list_out` SET `material_list_out`.`quantity_delivered` = `material_list_out`.`quantity`, `material_list_out`.`status`= 'sj' WHERE `material_list_out`.`transaction_number` = '{$so_number}' AND `material_list_out`.`status` = 'do'";
        GenericModel::rawQuery($query);

        //update status to surat jalan delivered
        $update = array(
                        'status'    => 8,
                        'modifier_id'    => SESSION::get('uid'),
                        'modified_timestamp'    => date("Y-m-d H:i:s")
                        );
        GenericModel::update('sales_order', $update, "`transaction_number` = '$so_number'");

        Redirect::to('so/detail/?so_number=' . $so_number);
    }

    public function closeSj()
    {
        $so_number = urldecode(Request::get('so_number'));
        $update = array(
                        'status'    => 4,
                        'modifier_id'    => SESSION::get('uid'),
                        'modified_timestamp'    => date("Y-m-d H:i:s")
                        );
        GenericModel::update('sales_order', $update, "`transaction_number` = '$so_number'");
                
        Redirect::to('so/detail/?so_number=' . $so_number);
    }

    public function deleteDo($uid) {
        $so_number = urldecode(Request::get('so_number'));
        $do = GenericModel::getOne('`material_list_out`', "`uid` = '{$uid}'", '`serial_number`,`transaction_number`');
        if (!empty($do->serial_number)) {
            $serial_number = explode(',', $do->serial_number);
            foreach ($serial_number as $key => $value) {
                            $sn = trim($serial_number[$key]);
                            $update = array(
                                'status'    => 0,
                                'transaction_number' => $value->transaction_number
                                );
                GenericModel::update('serial_number', $update, "`serial_number` = '{$sn}'", false);
            }
        }
        GenericModel::remove('material_list_out', 'uid', $uid);
        Redirect::to('so/detail/?so_number=' . urlencode($so_number));
    }

    public function printDo() {
        $sj_number = urldecode(Request::get('sj_number'));
        $sj = "SELECT
                    `sales_order`.`customer_id`,
                    `sales_order`.`transaction_number`,
                    `sales_order`.`sales_channel`,
                    `sales_order`.`creator_id`,
                    `sales_order`.`modifier_id`,
                    `sales_order`.`created_timestamp`,
                    `contact`.`contact_name`,
                    `contact`.`address_street`,
                    `contact`.`address_city`,
                    `contact`.`address_state`,
                    `contact`.`address_zip`,
                    `contact`.`website`,
                    `contact`.`phone`,
                    `contact`.`fax`,
                    `contact`.`email`,
                    `material_list_out`.`uid`,
                    `material_list_out`.`material_code`,
                    `material_list_out`.`quantity_delivered`,
                    `material_list_out`.`serial_number`,
                    `material_list_out`.`transaction_number`,
                    `material_list_out`.`do_number`,
                    `material_list_out`.`created_timestamp`,
                    `material_list`.`material_name`,
                    `material_list`.`unit`
                FROM
                    `material_list_out`
                
                LEFT JOIN
                    `sales_order` ON `material_list_out`.`transaction_number` = `sales_order`.`transaction_number`
                LEFT JOIN
                    `contact` ON `contact`.`contact_id` = `sales_order`.`customer_id`
                LEFT JOIN
                    `material_list` ON`material_list`.`material_code` = `material_list_out`.`material_code`
                WHERE `material_list_out`.`do_number` = '{$sj_number}'";
        $this->View->renderFileOnly('so/print_do', array(
                'product' => GenericModel::rawSelect($sj),
                'company' => GenericModel::getAll('`system_preference`', "`category` = 'company_identification' ORDER BY `item_name` ASC", '`value`, `item_name`')

        ));
        
    }

    public function printSj() {
        $sj_number = urldecode(Request::get('sj_number'));
        $sj = "SELECT
                    `sales_order`.`customer_id`,
                    `sales_order`.`transaction_number`,
                    `sales_order`.`sales_channel`,
                    `sales_order`.`creator_id`,
                    `sales_order`.`modifier_id`,
                    `sales_order`.`created_timestamp`,
                    `contact`.`contact_name`,
                    `contact`.`address_street`,
                    `contact`.`address_city`,
                    `contact`.`address_state`,
                    `contact`.`address_zip`,
                    `contact`.`website`,
                    `contact`.`phone`,
                    `contact`.`fax`,
                    `contact`.`email`,
                    `material_list_out`.`uid`,
                    `material_list_out`.`material_code`,
                    `material_list_out`.`quantity_delivered`,
                    `material_list_out`.`serial_number`,
                    `material_list_out`.`transaction_number`,
                    `material_list_out`.`do_number`,
                    `material_list_out`.`created_timestamp`,
                    `material_list`.`material_name`,
                    `material_list`.`unit`
                FROM
                    `material_list_out`
                
                LEFT JOIN
                    `sales_order` ON `material_list_out`.`transaction_number` = `sales_order`.`transaction_number`
                LEFT JOIN
                    `contact` ON `contact`.`contact_id` = `sales_order`.`customer_id`
                LEFT JOIN
                    `material_list` ON`material_list`.`material_code` = `material_list_out`.`material_code`
                WHERE `material_list_out`.`do_number` = '{$sj_number}'";
        $this->View->renderFileOnly('so/print_sj', array(
                'product' => GenericModel::rawSelect($sj),
                'company' => GenericModel::getAll('`system_preference`', "`category` = 'company_identification' ORDER BY `item_name` ASC", '`value`, `item_name`')

        ));
        
    }

    public function makeDo() {
        $so_number = Request::post('so_number');
        $totalrecord = Request::post('total_record');
        for ($i = 1; $i <= $totalrecord; $i++) {
            if (!empty(Request::post('qty_' . $i))) { // hanya validasi data yang tidak kosong isian jumlanya
                // To make sure qty received is not bigger than qty purchased
                $totalQtyDelivered = (int)Request::post('qty_' . $i) + (int)Request::post('total_quantity_delivered_' . $i);
                $totalQtyPurchased = (int)Request::post('qty_purchased_' . $i);
                 // To make sure qty received is not bigger than qty purchased
                if ($totalQtyDelivered > $totalQtyPurchased) {
                        Session::add('feedback_negative', "ERROR!. Total delivered items is bigger than qty sale");
                        Redirect::to('so/detail/?so_number=' . urlencode($so_number));
                        exit;
                }
                //validasi jumlah serial number dan jumlah do
                $serial_number = $_POST['serial_number_' . $i];
                $total_serial_number = count($serial_number);
                $quantity = Request::post('qty_' . $i);
                if ($total_serial_number != $quantity) {
                        Session::add('feedback_negative', "ERROR!. Jumlah SERIAL NUMBER yang dipilih tidak sama dengan jumlah barang yang akan dikirim");
                        Redirect::to('so/detail/?so_number=' . urlencode($so_number));
                        exit;
                }

                if (empty(Request::post('date_' . $i))) {
                        Session::add('feedback_negative', "ERROR!. Ada tanggal pengiriman yang tidak diisi");
                        Redirect::to('so/detail/?so_number=' . urlencode($so_number));
                        exit;
                }
            }
        }

        //Make DO number
        $do = GenericModel::getOne('`material_list_out`', "`transaction_number` = '{$so_number}' ORDER BY `created_timestamp` DESC", 'do_number');
        $do_number_counter = substr(strrchr($do->do_number, "."), 1);
        $do_number_counter = $do_number_counter + 1;
        $do_number = str_replace('SO', 'DO', $so_number) . '.' . $do_number_counter;
        
        // var_dump($totalrecord);
        for ($i = 1; $i <= $totalrecord; $i++) {
        if (Request::post('qty_' . $i) > 0) { // only execute only when qty is not blank
                    $serial_number = $_POST['serial_number_' . $i];
                    $total_serial_number = count($serial_number);
                    $serial_number = implode(", ", $serial_number);
                    $material_code = Request::post('material_code_' . $i);
                    $quantity = Request::post('qty_' . $i);
                    $delivery_date = Request::post('date_' . $i);

                    $insert = array(
                        'uid'    => GenericModel::guid(),
                        'transaction_number'    => $so_number,
                        'do_number'    => $do_number,
                        'material_code'    => $material_code,
                        'quantity'    => $quantity,
                        'delivery_date'    => $delivery_date,
                        'status'    => 'do',
                        'serial_number' => $serial_number,
                        'creator_id'    => SESSION::get('uid')
                    );
                    GenericModel::insert('material_list_out', $insert);

                    //Book Serial Number
                    $serial_number = $_POST['serial_number_' . $i];
                    foreach ($serial_number as $key => $value) {
                        $sn = $serial_number[$key];
                        $update = array(
                            'serial_number' => $sn,
                            'status'    => 1,
                            'transaction_number' => $do_number
                            );
                        GenericModel::update('serial_number', $update, "`serial_number` = '{$sn}'");
                    }
            //status updates SO success
            $transaction_status = true;

            } // END IF
        } // END FOR

        if ($transaction_status == true) {
            $update = array(
                'status'    => 5,
                'modifier_id' => SESSION::get('uid')
            );
            GenericModel::update('sales_order', $update, "`transaction_number` = '{$so_number}'");
        }


        Redirect::to('so/detail/?so_number=' . urlencode($so_number));
    }

    public function saveFeedback()
    {
        $so_number = Request::post('so_number');
        $update = array(
                        'status'    => -3,
                        'feedback_note'    => Request::post('feedback_note'),
                        'modifier_id'    => SESSION::get('uid'),
                        'modified_timestamp'    => date("Y-m-d H:i:s")
                        );
        GenericModel::update('sales_order', $update, "`transaction_number` = '$so_number'");
        Redirect::to('so/detail/?so_number=' . $so_number);
    }

    public function deleteSo()
    {
        $so_number = urldecode(Request::get('so_number'));
        GenericModel::remove('material_list_out', 'transaction_number', $so_number, false); //false for silent feedback
        GenericModel::remove('sales_order', 'transaction_number', $so_number, false); //false for silent feedback
        GenericModel::remove('sales_order_list', 'transaction_number', $so_number, false); //false for silent feedback
        GenericModel::remove('payment_transaction', 'transaction_code', $so_number, false); //false for silent feedback
        Redirect::to(Request::get('forward'));
    }

    public function closeSo()
    {
        $so_number = urldecode(Request::get('so_number'));
        $update = array(
                        'status'    => 100,
                        'modifier_id'    => SESSION::get('uid'),
                        'modified_timestamp'    => date("Y-m-d H:i:s")
                        );
        GenericModel::update('sales_order', $update, "`transaction_number` = '$so_number'");
                
        Redirect::to('so/detail/?so_number=' . $so_number);
    }

    public function openSo()
    {
        $so_number = urldecode(Request::get('so_number'));
        $update = array(
                        'status'    => 0,
                        'modifier_id'    => SESSION::get('uid'),
                        'modified_timestamp'    => date("Y-m-d H:i:s")
                        );
        GenericModel::update('sales_order', $update, "`transaction_number` = '$so_number'");
                
        Redirect::to('so/detail/?so_number=' . $so_number);
    }

     /**
     * Perform the upload image
     * POST-request
     */
    public function uploadImage()
    {   
        $so_number = Request::post('so_number');
        $image_name = 'file_name';
        $image_rename = Request::post('image_name');
        $destination = 'sales-order';
        $note = Request::post('note');
        UploadModel::uploadImage($image_name, $image_rename, $destination, $so_number, $note);
        Redirect::to('so/detail/?so_number=' . urlencode($so_number));
    }

     /**
     * Perform the upload pdf, xlsx, doc, docx
     * POST-request
     */
    public function uploadDocument()
    {
        $so_number = Request::post('so_number');
        $image_name = 'file_name';
        $image_rename = Request::post('document_name');
        $destination = 'sales-order';
        $note = Request::post('note');
        UploadModel::uploadDocument($image_name, $image_rename, $destination, $so_number, $note);
        Redirect::to('so/detail/?so_number=' . urlencode($so_number));
    }

    public function removeUploadFile($table, $uid, $value)
    {
        GenericModel::remove($table, $uid, $value);
        Redirect::to('so/detail/?so_number=' . Request::get('so_number'));
        
    }
}
