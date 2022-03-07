<?php


class InventoryController extends Controller
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

    /**
     * This method controls what happens when you move to /note/index in your app.
     * Gets all notes (of the user).
     */
    public function index($page = 1, $limit = 20)
    {
        if(isset($_GET['find']) AND $_GET['find'] != '') {
            $offset = ($page > 1) ? ($page - 1) * $limit : 0;
            // START explode search string
            $find = strtolower(Request::get('find')); //lower case string to easily (case 

            $sql = "SELECT
                    `material_list`.`material_code`,
                    `material_list`.`material_name`,
                    `material_list`.`note`,
                    `material_list`.`unit`,
                    `material_list`.`minimum_balance`,
                    MATCH (`material_code`, `material_name`) AGAINST ('{$find}') as score
            FROM
                `material_list`
            WHERE
                MATCH (`material_code`, `material_name`) AGAINST ('{$find}' IN NATURAL LANGUAGE MODE)
                AND
                `material_list`.`is_deleted` = 0
            ORDER BY
                score DESC
            LIMIT
                $offset, $limit";

            $result = GenericModel::rawSelect($sql);

            //Pagination
            $total_record = GenericModel::totalRow('`material_list`','`material_code`');
            $search_string = '&find=' . $find;
            $pagination = FormaterModel::pagination('inventory/index', $total_record, $page, $limit,$search_string);

        } else { // To show all Product
            $offset = ($page > 1) ? ($page - 1) * $limit : 0;
            $sql_product_detail = "
                SELECT
                    `material_list`.`material_code`,
                    `material_list`.`material_name`,
                    `material_list`.`note`,
                    `material_list`.`unit`,
                    `material_list`.`minimum_balance`
                FROM
                    `material_list`
                WHERE
                    `material_list`.`is_deleted` = 0
                LIMIT
                    $offset, $limit";
            $result = GenericModel::rawSelect($sql_product_detail);

            //Pagination
            $total_record = GenericModel::totalRow('`material_list`','`material_code`');
            $pagination = FormaterModel::pagination('inventory/index', $total_record, $page, $limit);
        }
        $header_script = '<link rel="stylesheet" href="' . Config::get('URL') . 'bootstrap-3.3.7/css/excel-2007.css" media="screen"/>';
        $this->View->render('inventory/index',
                array(
                'header_script' => $header_script,
                'title' => 'Daftar Barang',
                'activelink1' => 'inventory',
                'activelink2' => 'daftar barang',
                'activelink3' => 'daftar barang semua',
                'page' => $page,
                'limit' => $limit,
                'totalStock' => $total_record,
                'stock' => $result,
                'pagination' => $pagination
                )
            );
    }

    /**
     * This method controls what happens when you move to /note/index in your app.
     * Gets all notes (of the user).
     */
    public function findStock($page = 1, $limit = 20)
    {
        $offset = ($page > 1) ? ($page - 1) * $limit : 0;
        // START explode search string
        $find = strtolower(Request::get('find')); //lower case string to easily (case 

        $sql = "
                SELECT
                    `material_list`.`material_code`,
                    `material_list`.`material_name`,
                    `material_list`.`note`,
                    `material_list`.`unit`,
                    `material_list`.`minimum_balance`,
                    `material_list`.`quantity_per_unit`,
                    SUM(`material_list_in`.`quantity_stock`) as `quantity_stock`,
                    MATCH (`material_list`.`material_code`, `material_list`.`material_name`) AGAINST ('{$find}') as score
                FROM
                    `material_list`
                LEFT JOIN
                    `material_list_in`
                        ON
                    `material_list_in`.`material_code` = `material_list`.`material_code`
                WHERE
                    MATCH (`material_list`.`material_code`, `material_list`.`material_name`) AGAINST ('{$find}' IN NATURAL LANGUAGE MODE)
                    AND
                    `material_list`.`is_deleted` = 0
                GROUP BY
                    `material_list`.`material_code`
                ORDER BY
                    score DESC
                LIMIT
                    $offset, $limit";

        $result = GenericModel::rawSelect($sql);

        //Pagination
        $total_record = GenericModel::totalRow('`material_list`','`material_code`');
        $search_string = '&find=' . $find;
        $pagination = FormaterModel::pagination('inventory/findStock', $total_record, $page, $limit,$search_string);

        $header_script = '<link rel="stylesheet" href="' . Config::get('URL') . 'bootstrap-3.3.7/css/excel-2007.css" media="screen"/>';
        $this->View->render('inventory/find_stock',
                array(
                'header_script' => $header_script,
                'title' => 'Daftar Barang',
                'activelink1' => 'inventory',
                'activelink2' => 'daftar barang',
                'activelink3' => 'daftar barang semua',
                'page' => $page,
                'limit' => $limit,
                'totalStock' => $total_record,
                'stock' => $result,
                'pagination' => $pagination
                )
            );
    }

    /**
    * BOM
    */
    public function formulation($page = 1, $limit = 20)
    {
        $offset = ($page > 1) ? ($page - 1) * $limit : 0;
        $sql = "
            SELECT
                `material_list`.`material_code`,
                `material_list`.`material_name`,
                `material_list`.`note`
            FROM
                `material_list`
            WHERE
                `material_list`.`material_type` = 0 AND `material_list`.`is_deleted` = 0
            LIMIT
                $offset, $limit";

        $result = GenericModel::rawSelect($sql);

        //Pagination
        $total_record = GenericModel::rowCount('`material_list`', '`material_list`.`material_type` = 0', '`material_code`');
        $pagination = FormaterModel::pagination('inventory/formulation', $total_record, $page, $limit);

        $header_script = '<link rel="stylesheet" href="' . Config::get('URL') . 'bootstrap-3.3.7/css/excel-2007.css" media="screen"/>';
        $this->View->render('inventory/formulation',
                array(
                'header_script' => $header_script,
                'title' => 'Stock Material',
                'activelink1' => 'inventory',
                'activelink2' => 'daftar barang',
                'activelink3' => 'daftar barang repacking',
                'page' => $page,
                'limit' => $limit,
                'stock' => $result,
                'pagination' => $pagination,
                )
            );
    }

    public function rawMaterial($page = 1, $limit = 20)
    {
        $offset = ($page > 1) ? ($page - 1) * $limit : 0;
        $sql = "
                SELECT
                    `material_list`.`material_code`,
                    `material_list`.`material_name`,
                    `material_list`.`note`,
                    `material_list`.`unit`,
                    `material_list`.`minimum_balance`,
                    `material_list`.`quantity_per_unit`,
                    `stock`.`quantity_stock`
                FROM
                    `material_list`
                LEFT JOIN
                    (SELECT
                        `material_code`,
                        SUM(quantity_stock) AS `quantity_stock`
                    FROM
                        `material_list_in`
                    GROUP  BY
                        `material_list_in`.`material_code`)
                    AS `stock` ON `stock`.`material_code` = `material_list`.`material_code`

                WHERE
                    `material_list`.`is_deleted` = 0
                    AND
                    (`material_list`.`material_type` = 1 OR `material_list`.`material_type` = 4)
                LIMIT
                    $offset, $limit";
        $result = GenericModel::rawSelect($sql);


        //Pagination
        $total_record = GenericModel::rowCount('`material_list`', '`material_list`.`material_type` = 1 OR `material_list`.`material_type` = 4', '`material_code`');
        $pagination = FormaterModel::pagination('inventory/rawMaterial', $total_record, $page, $limit);

        $header_script = '<link rel="stylesheet" href="' . Config::get('URL') . 'bootstrap-3.3.7/css/excel-2007.css" media="screen"/>';
        $this->View->render('inventory/raw_material',
                array(
                'header_script' => $header_script,
                'title' => 'Stock Material',
                'activelink1' => 'inventory',
                'activelink2' => 'daftar barang',
                'activelink3' => 'daftar barang bahan baku',
                'page' => $page,
                'limit' => $limit,
                'stock' => $result,
                'totalStock' => $total_record,
                'pagination' => $pagination
                )
            );
    }

        public function wip($page = 1, $limit = 20)
    {
        $offset = ($page > 1) ? ($page - 1) * $limit : 0;

        $sql = "
                SELECT
                    `material_list`.`material_code`,
                    `material_list`.`material_name`,
                    `material_list`.`note`,
                    `material_list`.`unit`,
                    `material_list`.`minimum_balance`,
                    `material_list`.`quantity_per_unit`,
                    `material_list`.`selling_price`,
                    `stock`.`quantity_stock`
                FROM
                    `material_list`
                LEFT JOIN
                    (SELECT
                        `material_code`,
                        SUM(quantity_stock) AS `quantity_stock`
                    FROM
                        `material_list_in`
                    GROUP  BY `material_list_in`.`material_code`)
                    AS `stock` ON `stock`.`material_code` = `material_list`.`material_code`
                WHERE
                    `material_list`.`is_deleted` = 0
                    AND
                    `material_list`.`material_type` = 2 
                LIMIT
                    $offset, $limit";
        $result = GenericModel::rawSelect($sql);

        //Pagination
        $total_record = GenericModel::rowCount('`material_list`', '`material_list`.`material_type` = 3 OR `material_list`.`material_type` = 4', '`material_code`');
        $pagination = FormaterModel::pagination('inventory/wip', $total_record, $page, $limit);

        $header_script = '<link rel="stylesheet" href="' . Config::get('URL') . 'bootstrap-3.3.7/css/excel-2007.css" media="screen"/>';
        $this->View->render('inventory/finish_goods',
                array(
                'header_script' => $header_script,
                'title' => 'Stock Material Bahan Setengah Jadi',
                'activelink1' => 'inventory',
                'activelink2' => 'daftar barang',
                'activelink3' => 'daftar barang barang wip',
                'page' => $page,
                'limit' => $limit,
                'finishGoods' => $result,
                'totalStock' => $total_record,
                'pagination' => $pagination
                )
            );
    }

     public function finishGoods($page = 1, $limit = 20)
    {
        $offset = ($page > 1) ? ($page - 1) * $limit : 0;

        $sql = "
                SELECT
                    `material_list`.`material_code`,
                    `material_list`.`material_name`,
                    `material_list`.`note`,
                    `material_list`.`unit`,
                    `material_list`.`minimum_balance`,
                    `material_list`.`quantity_per_unit`,
                    `material_list`.`selling_price`,
                    `stock`.`quantity_stock`
                FROM
                    `material_list`
                LEFT JOIN
                    (SELECT
                        `material_code`,
                        SUM(quantity_stock) AS `quantity_stock`
                    FROM
                        `material_list_in`
                    GROUP  BY `material_list_in`.`material_code`)
                    AS `stock` ON `stock`.`material_code` = `material_list`.`material_code`
                    
                WHERE
                    `material_list`.`is_deleted` = 0
                    AND
                    `material_list`.`material_type` = 3
                LIMIT
                    $offset, $limit";
        $result = GenericModel::rawSelect($sql);

        //Pagination
        $total_record = GenericModel::rowCount('`material_list`', '`material_list`.`is_deleted` = 0 AND `material_list`.`material_type` = 3', '`material_code`');
        $pagination = FormaterModel::pagination('inventory/finishGoods', $total_record, $page, $limit);

        $header_script = '<link rel="stylesheet" href="' . Config::get('URL') . 'bootstrap-3.3.7/css/excel-2007.css" media="screen"/>';
        $this->View->render('inventory/finish_goods',
                array(
                'header_script' => $header_script,
                'title' => 'Stock Material',
                'activelink1' => 'inventory',
                'activelink2' => 'daftar barang',
                'activelink3' => 'daftar barang barang jadi',
                'page' => $page,
                'limit' => $limit,
                'finishGoods' => $result,
                'totalStock' => $total_record,
                'pagination' => $pagination
                )
            );
    }

    public function tradingGoods($page = 1, $limit = 20)
    {
        $offset = ($page > 1) ? ($page - 1) * $limit : 0;

        $sql = "
                SELECT
                    `material_list`.`material_code`,
                    `material_list`.`material_name`,
                    `material_list`.`note`,
                    `material_list`.`unit`,
                    `material_list`.`minimum_balance`,
                    `material_list`.`quantity_per_unit`,
                    `material_list`.`selling_price`,
                    `stock`.`quantity_stock`
                FROM
                    `material_list`
                LEFT JOIN
                    (SELECT
                        `material_code`,
                        SUM(quantity_stock) AS `quantity_stock`
                    FROM
                        `material_list_in`
                    GROUP  BY `material_list_in`.`material_code`)
                    AS `stock` ON `stock`.`material_code` = `material_list`.`material_code`

                WHERE
                    `material_list`.`is_deleted` = 0
                    AND
                    `material_list`.`material_type` = 4 
                LIMIT
                    $offset, $limit";
        $result = GenericModel::rawSelect($sql);

        //Pagination
        $total_record = GenericModel::rowCount('`material_list`', '`material_list`.`is_deleted` = 0 AND `material_list`.`material_type` = 4', '`material_code`');
        $pagination = FormaterModel::pagination('inventory/tradingGoods', $total_record, $page, $limit);

        $header_script = '<link rel="stylesheet" href="' . Config::get('URL') . 'bootstrap-3.3.7/css/excel-2007.css" media="screen"/>';
        $this->View->render('inventory/finish_goods',
                array(
                'header_script' => $header_script,
                'title' => 'Stock Material',
                'activelink1' => 'inventory',
                'activelink2' => 'daftar barang',
                'activelink3' => 'daftar barang trading goods',
                'page' => $page,
                'limit' => $limit,
                'finishGoods' => $result,
                'totalStock' => $total_record,
                'pagination' => $pagination
                )
            );
    }

    public function serviceProduct($page = 1, $limit = 20)
    {
        $offset = ($page > 1) ? ($page - 1) * $limit : 0;

        $sql = "
                SELECT
                    `material_list`.`material_code`,
                    `material_list`.`material_name`,
                    `material_list`.`note`,
                    `material_list`.`unit`,
                    `material_list`.`minimum_balance`,
                    `material_list`.`quantity_per_unit`,
                    `material_list`.`selling_price`,
                    `stock`.`quantity_stock`
                FROM
                    `material_list`
                LEFT JOIN
                    (SELECT
                        `material_code`,
                        SUM(quantity_stock) AS `quantity_stock`
                    FROM
                        `material_list_in`
                    GROUP  BY `material_list_in`.`material_code`)
                    AS `stock` ON `stock`.`material_code` = `material_list`.`material_code`

                WHERE
                    `material_list`.`is_deleted` = 0
                    AND
                    `material_list`.`material_type` = 5 
                LIMIT
                    $offset, $limit";
        $result = GenericModel::rawSelect($sql);

        //Pagination
        $total_record = GenericModel::rowCount('`material_list`', '`material_list`.`is_deleted` = 0 AND `material_list`.`material_type` = 5', '`material_code`');
        $pagination = FormaterModel::pagination('inventory/serviceProduct', $total_record, $page, $limit);

        $header_script = '<link rel="stylesheet" href="' . Config::get('URL') . 'bootstrap-3.3.7/css/excel-2007.css" media="screen"/>';
        $this->View->render('inventory/finish_goods',
                array(
                'header_script' => $header_script,
                'title' => 'Stock Material',
                'activelink1' => 'inventory',
                'activelink2' => 'daftar barang',
                'activelink3' => 'daftar barang service/jasa',
                'page' => $page,
                'limit' => $limit,
                'finishGoods' => $result,
                'totalStock' => $total_record,
                'pagination' => $pagination
                )
            );
    }
    
    public function productionTool($page = 1, $limit = 20)
    {
        $offset = ($page > 1) ? ($page - 1) * $limit : 0;

        $sql = "
                SELECT
                    `material_list`.`material_code`,
                    `material_list`.`material_name`,
                    `material_list`.`note`,
                    `material_list`.`unit`,
                    `material_list`.`minimum_balance`,
                    `material_list`.`quantity_per_unit`,
                    `material_list`.`selling_price`,
                    `stock`.`quantity_stock`
                FROM
                    `material_list`
                LEFT JOIN
                    (SELECT
                        `material_code`,
                        SUM(quantity_stock) AS `quantity_stock`
                    FROM
                        `material_list_in`
                    GROUP  BY `material_list_in`.`material_code`)
                    AS `stock` ON `stock`.`material_code` = `material_list`.`material_code`
                    
                WHERE
                    `material_list`.`is_deleted` = 0
                    AND
                    `material_list`.`material_type` = 6 
                LIMIT
                    $offset, $limit";
        $result = GenericModel::rawSelect($sql);

        //Pagination
        $total_record = GenericModel::rowCount('`material_list`', '`material_list`.`is_deleted` = 0 AND `material_list`.`material_type` = 6', '`material_code`');
        $pagination = FormaterModel::pagination('inventory/productionTool', $total_record, $page, $limit);

        $header_script = '<link rel="stylesheet" href="' . Config::get('URL') . 'bootstrap-3.3.7/css/excel-2007.css" media="screen"/>';
        $this->View->render('inventory/raw_material',
                array(
                'header_script' => $header_script,
                'title' => 'Stock Material',
                'activelink1' => 'inventory',
                'activelink2' => 'daftar barang',
                'activelink3' => 'daftar barang production tool',
                'page' => $page,
                'limit' => $limit,
                'stock' => $result,
                'totalStock' => $total_record,
                'pagination' => $pagination
                )
            );
    }
    
    public function operatingSupply($page = 1, $limit = 20)
    {
        $offset = ($page > 1) ? ($page - 1) * $limit : 0;

        $sql = "SELECT
                    `material_list`.`material_code`,
                    `material_list`.`material_name`,
                    `material_list`.`note`,
                    `material_list`.`unit`,
                    `material_list`.`minimum_balance`,
                    `material_list`.`quantity_per_unit`,
                    `material_list`.`selling_price`,
                    `stock`.`quantity_stock`
                FROM
                    `material_list`
                LEFT JOIN
                    (SELECT
                        `material_code`,
                        SUM(quantity_stock) AS `quantity_stock`
                    FROM
                        `material_list_in`
                    GROUP  BY `material_list_in`.`material_code`)
                    AS `stock` ON `stock`.`material_code` = `material_list`.`material_code`
                WHERE
                    `material_list`.`is_deleted` = 0
                    AND
                    `material_list`.`material_type` = 7 
                LIMIT
                    $offset, $limit";
        $result = GenericModel::rawSelect($sql);

        //Pagination
        $total_record = GenericModel::rowCount('`material_list`', '`material_list`.`is_deleted` = 0 AND `material_list`.`material_type` = 7', '`material_code`');
        $pagination = FormaterModel::pagination('inventory/operatingSupply', $total_record, $page, $limit);

        $header_script = '<link rel="stylesheet" href="' . Config::get('URL') . 'bootstrap-3.3.7/css/excel-2007.css" media="screen"/>';
        $this->View->render('inventory/raw_material',
                array(
                'header_script' => $header_script,
                'title' => 'Stock Material',
                'activelink1' => 'inventory',
                'activelink2' => 'daftar barang',
                'activelink3' => 'daftar barang operating supplies',
                'page' => $page,
                'limit' => $limit,
                'stock' => $result,
                'totalStock' => $total_record,
                'pagination' => $pagination
                )
            );
    }

    public function assetMaterial($page = 1, $limit = 20)
    {
        $offset = ($page > 1) ? ($page - 1) * $limit : 0;

        $sql = "SELECT
                    `material_list`.`material_code`,
                    `material_list`.`material_name`,
                    `material_list`.`note`,
                    `material_list`.`unit`,
                    `material_list`.`minimum_balance`,
                    `material_list`.`quantity_per_unit`,
                    `material_list`.`selling_price`,
                    `stock`.`quantity_stock`
                FROM
                    `material_list`
                LEFT JOIN
                    (SELECT
                        `material_code`,
                        SUM(quantity_stock) AS `quantity_stock`
                    FROM
                        `material_list_in`
                    GROUP  BY `material_list_in`.`material_code`)
                    AS `stock` ON `stock`.`material_code` = `material_list`.`material_code`

                WHERE
                    `material_list`.`is_deleted` = 0
                    AND
                    `material_list`.`material_type` = 10 
                LIMIT
                    $offset, $limit";
        $result = GenericModel::rawSelect($sql);

        //Pagination
        $total_record = GenericModel::rowCount('`material_list`', '`material_list`.`is_deleted` = 0 AND `material_list`.`material_type` = 10', '`material_code`');
        $pagination = FormaterModel::pagination('inventory/assetMaterial', $total_record, $page, $limit);

        $header_script = '<link rel="stylesheet" href="' . Config::get('URL') . 'bootstrap-3.3.7/css/excel-2007.css" media="screen"/>';
        $this->View->render('inventory/raw_material',
                array(
                'header_script' => $header_script,
                'title' => 'Stock Material',
                'activelink1' => 'inventory',
                'activelink2' => 'daftar barang',
                'activelink3' => 'daftar barang asset material',
                'page' => $page,
                'limit' => $limit,
                'stock' => $result,
                'totalStock' => $total_record,
                'pagination' => $pagination
                )
            );
    }

    public function pushSystemStock($page = 1, $limit = 100)
    {
        $offset = ($page > 1) ? ($page - 1) * $limit : 0;

        $sql = "
                SELECT
                    `material_list`.`material_code`,
                    `material_list`.`material_name`,
                    `material_list`.`note`,
                    `material_list`.`unit`,
                    `material_list`.`minimum_balance`,
                    `material_list`.`quantity_per_unit`,
                    `stock`.`quantity_stock`
                FROM
                    `material_list`
                LEFT JOIN
                    (SELECT
                        `material_code`,
                        SUM(quantity_stock) AS `quantity_stock`
                    FROM
                        `material_list_in`
                    GROUP  BY `material_list_in`.`material_code`)
                    AS `stock` ON `stock`.`material_code` = `material_list`.`material_code`

                WHERE
                    `stock`.`quantity_stock` < `material_list`.`minimum_balance`
                    AND
                    `material_list`.`is_deleted` = 0
                GROUP BY
                    `material_list`.`material_code`
                LIMIT
                    $offset, $limit";
        $result = GenericModel::rawSelect($sql);

        //Pagination
        $table = "`material_list`
                LEFT JOIN
                    (SELECT
                        `material_code`,
                        SUM(quantity_stock) AS `quantity_stock`
                    FROM
                        `material_list_in`
                    GROUP  BY `material_list_in`.`material_code`)
                    AS `stock` ON `stock`.`material_code` = `material_list`.`material_code`";
        $where = "`stock`.`quantity_stock` < `material_list`.`minimum_balance`
                AND
                `material_list`.`is_deleted` = 0
                GROUP BY
                    `material_list`.`material_code`";
        $total_record = GenericModel::rowCount($table, $where, '`material_list`.`material_code`');
        $pagination = FormaterModel::pagination('inventory/pushSystemStock', $total_record, $page, $limit);

        $header_script = '<link rel="stylesheet" href="' . Config::get('URL') . 'bootstrap-3.3.7/css/excel-2007.css" media="screen"/>';
        $this->View->render('inventory/push_system_stock',
                array(
                'header_script' => $header_script,
                'title' => 'Notifikasi Stock Material Kurang Dari Safety Stock',
                'activelink1' => 'inventory',
                'activelink2' => 'out of safety stock',
                'page' => $page,
                'limit' => $limit,
                'stock' => $result,
                'pagination' => $pagination,
                )
            );
    }

    public function selectMaterial($page = 1, $limit = 50) {
        if (isset($_GET['find'])) {
            $find = strtolower(Request::get('find')); //lower case string to easily (case 
            $prev = Config::get('URL') . 'inventory/selectMaterial/' . ($page - 1) . '/' . $limit . '/?id=' . Request::get('id') . '&find=' . $find;
            $next = Config::get('URL') . 'inventory/selectMaterial/' . ($page + 1) . '/' . $limit . '/?id=' . Request::get('id') . '&find=' . $find;
            
            $offset = ($page > 1) ? ($page - 1) * $limit : 0;
            $sql = "SELECT
                    `material_list`.`material_code`,
                    `material_list`.`material_name`,
                    `material_list`.`note`,
                    `material_list`.`unit`,
                    `material_list`.`minimum_balance`,
                    MATCH (`material_code`, `material_name`) AGAINST ('{$find}') as score
            FROM `material_list`
            WHERE
                MATCH (`material_code`, `material_name`) AGAINST ('{$find}' IN NATURAL LANGUAGE MODE)
                AND
                `material_list`.`is_deleted` = 0
            ORDER BY score DESC LIMIT $offset, $limit";

            $result = GenericModel::rawSelect($sql);
        } 
        
        $this->View->renderFileOnly('inventory/select_material',
                array(
                'title' => 'Select Material',
                'prev' => $prev,
                'next' => $next,
                'page' => $page,
                'limit' => $limit,
                'material_list' => $result
                )
            );
    }

    public function newMaterial() {
        $table = '`system_preference`';

        $where = "`category` = 'material_type' AND `item_name` = 'raw material' ORDER BY `system_preference`.`item_name` ASC";
        $raw_material = GenericModel::getAll($table, $where);

        $where = "`category` = 'material_type' AND `item_name` = 'wip material' ORDER BY `system_preference`.`item_name` ASC";
        $wip = GenericModel::getAll($table, $where);

        $where = "`category` = 'material_type' AND `item_name` = 'finished product' ORDER BY `system_preference`.`item_name` ASC";
        $finished_product = GenericModel::getAll($table, $where);

        $where = "`category` = 'material_type' AND `item_name` = 'trading goods' ORDER BY `system_preference`.`item_name` ASC";
        $trading_goods = GenericModel::getAll($table, $where);

        $where = "`category` = 'material_type' AND `item_name` = 'service product' ORDER BY `system_preference`.`item_name` ASC";
        $service_product = GenericModel::getAll($table, $where);

        $where = "`category` = 'material_type' AND `item_name` = 'production tools' ORDER BY `system_preference`.`item_name` ASC";
        $production_tools = GenericModel::getAll($table, $where);

        $where = "`category` = 'material_type' AND `item_name` = 'operating supplies' ORDER BY `system_preference`.`item_name` ASC";
        $operating_supplies = GenericModel::getAll($table, $where);

        $where = "`category` = 'material_type' AND `item_name` = 'asset material' ORDER BY `system_preference`.`item_name` ASC";
        $asset = GenericModel::getAll($table, $where);

        $this->View->render('inventory/new_material',
            array(
                'raw_material' => $raw_material,
                'wip' => $wip,
                'finished_product' => $finished_product,
                'trading_goods' => $trading_goods,
                'service_product' => $service_product,
                'production_tools' => $production_tools,
                'operating_supplies' => $operating_supplies,
                'asset' => $asset,
                'title' => 'Daftar Barang',
                'activelink1' => 'inventory',
                'activelink2' => 'daftar barang',
                )
        );
    }

    public function submitMasterIn() {
        $string_to_remove = [',', '/', '+', '?', ' ', '  ', '   ','<', '>', '&', '{', '}', '*','"', "'", '[', ']']; // remove unwanted character!
        $material_code = str_replace($string_to_remove, '.', Request::post('material_code'));
        $material_code = preg_replace('/\s+/', '', $material_code); // remove space!
        $material_name = str_replace($string_to_remove, ' ', Request::post('material_name'));

        if (!empty($material_name) AND !empty(Request::post('unit')) AND !empty(Request::post('material_type'))) {
            //Start Material Code Validation
            //check apakah memakai kode material sendiri atau otomatis dari sistem
            if (!empty($material_code)) {
                // check apakah kode material kurang dari 3
                if ((strlen($material_code) < 3)) {
                    Session::add('feedback_negative', 'Kode Material tidak boleh kurang dari 3 karakter');
                    Redirect::to('inventory/submitMasterIn/');
                    exit;
                }

                // check apakah kode material sudah ada atau belum
                //Get Material TYPE
                $material_type = explode('-', Request::post('material_type'));
                $material_type_number = trim($material_type[0]);
                $material_type_code = trim($material_type[1]);

                //Get Material Kategory Code
                $material_category_code = Request::post('material_category');

                //compose material code
                $material_code = $material_type_code . '.' . $material_category_code . '.' . $material_code;

                //check exist or not
                $field = "material_code";
                $material_code_exist = GenericModel::isExist('material_list', $field, $material_code);

                if ($material_code_exist) {
                    //buat suggestion produk material
                    // create material code
                    // Delimit by multiple spaces, hyphen, underscore, comma
                    $words = preg_split("/[\s,_-]+/", $material_name);
                    $acronym = "";
                    foreach ($words as $w) {
                      $acronym .= strtoupper($w[0]);
                    }
                    $query = "SELECT `material_code` FROM `material_list` WHERE `material_code` LIKE '%$acronym%' ORDER BY `created_timestamp` DESC LIMIT 1";
                    $max = GenericModel::rawSelect($query, false);
                    $material_number = FormaterModel::getNumberOnly($max->material_code);
                    $material_last_number = substr($material_number, -1) + 1;
                    $material_code = FormaterModel::getLetterOnly($acronym) . $material_number . '/' . $material_last_number;

                    Session::add('feedback_negative', 'Kode Material sudah ada, cari yang lain. Suggestion: <span class="badge">' . strtoupper($material_code)) . '</span>';
                    Redirect::to('inventory/submitMasterIn/');
                    exit;
                    
                }

            } else { // material code tidak diisi, sistem menggenerate otomatis

                // create material code
                // Delimit by multiple spaces, hyphen, underscore, comma
                $words = preg_split("/[\s,_-]+/", $material_name);
                $acronym = "";
                foreach ($words as $w) {
                  $acronym .= strtoupper($w[0]);
                }
                // check apakah kode material sudah ada atau belum
                $field = "material_code";
                $value = $acronym;
                $material_code_exist = GenericModel::isExist('material_list', $field, $value);
                if ($material_code_exist) {
                    $query = "SELECT `material_code` FROM `material_list` WHERE `material_code` LIKE '%$acronym%' ORDER BY `created_timestamp` DESC LIMIT 1";
                    $max = GenericModel::rawSelect($query, false);
                    $material_number = FormaterModel::getNumberOnly($max->material_code) + 1;
                    $material_code = FormaterModel::getLetterOnly($acronym) . $material_number;
                } else {
                    // create material code
                    // Delimit by multiple spaces, hyphen, underscore, comma
                    $words = preg_split("/[\s,_-]+/", $material_name);
                    $acronym = "";
                    foreach ($words as $w) {
                      $acronym .= strtoupper($w[0]);
                    }
                    $material_code = $material_type_code . '.' . $material_category_code . '.' . $acronym;
                }
                
            }

            
            $insert = array(
                        'material_code' => $material_code,
                        'material_name' => $material_name,
                        'supplier_id' => Request::post('contact_id'),
                        'minimum_balance' => Request::post('minimum_balance'),
                        'material_category' => $material_category_code,
                        'material_type' => $material_type_number,
                        'unit' => Request::post('unit'),
                        'selling_price' => Request::post('selling_price'),
                        'note' => Request::post('note'),
                        'creator_id'    => SESSION::get('uid')
                        );
            GenericModel::insert('material_list', $insert);
            Redirect::to('inventory/newMaterial/');
        } else {
            Session::add('feedback_negative', 'Nama Material, Satuan dan Tipe Material wajib diisi');
            Redirect::to('inventory/newMaterial/');
        }
    }

    // Edit record of stock Inventory
    public function editMaterial($page = 1, $limit = 20)
    {
        // seting page pagination
        $prev = $page - 1;
        $next = $page + 1;
        $start = ($page - 1) * $limit; // for sql query LIMIT start
        $number = ($prev * $limit) + 1;
 
        if (isset($_GET['find']) AND $_GET['find'] != '') {
            //starting to load models
            $name = strtolower(Request::get('find'));
            $name = trim($name);
            // pagination
            $prev = Config::get('URL') . '/inventory/editMaterial/' . $prev . '/' . $limit . '/?find=' . $name;
            $next = Config::get('URL') . '/inventory/editMaterial/' . $next . '/' . $limit . '/?find=' . $name;

            $sql = "
                SELECT
                    `material_list`.`material_code`,
                    `material_list`.`material_name`,
                    `material_list`.`note`,
                    `material_list`.`material_category`,
                    `material_list`.`material_type`,
                    `material_list`.`supplier_id`,
                    `material_list`.`unit`,
                    `material_list`.`minimum_balance`,
                    `material_list`.`purchase_price`,
                    `material_list`.`purchase_currency`,
                    `material_list`.`purchase_unit`,
                    `material_list`.`selling_price`,
                    `material_list`.`selling_currency`,
                    SUM(`material_list_in`.`quantity_stock`) as `quantity_stock`
                FROM
                    `material_list`
                LEFT JOIN
                    `material_list_in`
                        ON
                    `material_list_in`.`material_code` = `material_list`.`material_code`
                WHERE
                    `material_list`.`material_code` = '$name'
                GROUP BY
                    `material_list`.`material_code`
                LIMIT
                    1";
            $result = GenericModel::rawSelect($sql, false);

            $table                      = '`material_list_in`';
            $where                       = "`material_code` = '$name' AND `quantity_received` > 0 ORDER BY `created_timestamp` DESC";
            $field                      = '`transaction_number`, `quantity_received`, `serial_number_received`,`created_timestamp`'; //sementara dulu, sampai array untuk insert sudah final
            $stockIn        = GenericModel::getSome($table, $where, $limit, $page);

            $table                      = '`material_list_out`';
            $where                       = "`material_code` = '{$name}' ORDER BY `created_timestamp` DESC";
            $field                      = '`transaction_number`,`quantity_delivered`,`created_timestamp`,`serial_number`'; //sementara dulu, sampai array untuk insert sudah final
            $stockOut       = GenericModel::getSome($table, $where, $limit, $page);

            $table                      = '`material_list_adjustment`';
            $where                       = "`material_code` = '$name' ORDER BY `created_timestamp` DESC";
            $field                      = '*'; //sementara dulu, sampai array untuk insert sudah final
            $stockAdjustment = GenericModel::getSome($table, $where, $limit, $page);

            $table                      = '`sales_order_list` LEFT JOIN `users` ON `users`.`uid` = `sales_order_list`.`creator_id` LEFT JOIN `sales_order` ON `sales_order`.`transaction_number` = `sales_order_list`.`transaction_number`';
            $where                       = "`sales_order_list`.`material_code` = '$name' ORDER BY `sales_order_list`.`created_timestamp` DESC";
            $field                      = '`sales_order_list`.`transaction_number`,
                                            `sales_order_list`.`selling_price`,
                                            `sales_order_list`.`tax_ppn`,
                                            `sales_order_list`.`tax_pph`,
                                            `sales_order_list`.`created_timestamp`,
                                            `users`.`full_name`
                                            `sales_order`.`note`'; //sementara dulu, sampai array untuk insert sudah final
            $price_log = GenericModel::getSome($table, $where, $limit, $page);

            $table                      = '`serial_number`';
            $where                       = "`material_code` = '{$name}' AND `is_active` = 1 ORDER BY `serial_number` ASC";
            $field                      = '*'; //sementara dulu, sampai array untuk insert sudah final
            $sn = GenericModel::getSome($table, $where, $limit, $page, $field);

            $sql = "
                SELECT
                    `material_list_in`.`uid`,
                    `material_list_in`.`quantity_stock`,
                    `material_list_in`.`created_timestamp`,
                    `material_list_in`.`transaction_number`
                FROM
                    `material_list_in`
                WHERE
                    `material_list_in`.`material_code` = '$name' AND `material_list_in`.`quantity_stock` > 0
                ORDER BY
                    `material_list_in`.`created_timestamp` ASC
                ";
            $stock = GenericModel::rawSelect($sql);
        }

        //material type database
        $table = '`system_preference`';

        $where = "`category` = 'material_type' AND `item_name` = 'raw material' ORDER BY `system_preference`.`item_name` ASC";
        $raw_material = GenericModel::getAll($table, $where);

        $where = "`category` = 'material_type' AND `item_name` = 'wip material' ORDER BY `system_preference`.`item_name` ASC";
        $wip = GenericModel::getAll($table, $where);

        $where = "`category` = 'material_type' AND `item_name` = 'finished product' ORDER BY `system_preference`.`item_name` ASC";
        $finished_product = GenericModel::getAll($table, $where);

        $where = "`category` = 'material_type' AND `item_name` = 'trading goods' ORDER BY `system_preference`.`item_name` ASC";
        $trading_goods = GenericModel::getAll($table, $where);

        $where = "`category` = 'material_type' AND `item_name` = 'service product' ORDER BY `system_preference`.`item_name` ASC";
        $service_product = GenericModel::getAll($table, $where);

        $where = "`category` = 'material_type' AND `item_name` = 'production tools' ORDER BY `system_preference`.`item_name` ASC";
        $production_tools = GenericModel::getAll($table, $where);

        $where = "`category` = 'material_type' AND `item_name` = 'operating supplies' ORDER BY `system_preference`.`item_name` ASC";
        $operating_supplies = GenericModel::getAll($table, $where);

        $where = "`category` = 'material_type' AND `item_name` = 'asset material' ORDER BY `system_preference`.`item_name` ASC";
        $asset = GenericModel::getAll($table, $where);

        $header_script = '<link rel="stylesheet" href="' . Config::get('URL') . 'bootstrap-3.3.7/css/excel-2007.css" media="screen"/>
        <link rel="stylesheet" href="' . Config::get('URL') . 'bootstrap-3.3.7/css/datepicker.css" media="screen"/>';
        $footer_script = '<script src="' . Config::get('URL') . 'bootstrap-3.3.7/js/bootstrap-datepicker.js"></script>
        <script>
        $(".datepicker").datepicker(); //date picker
        </script>';


        $this->View->render('inventory/edit_material',
            array(
                'title' => 'Edit Stock Inventory',
                'header_script' => $header_script,
                'footer_script' => $footer_script,
                'number' => $number,
                'result' => $result,
                'stock' => $stock,
                'stockIn' => $stockIn,
                'stockOut' => $stockOut,
                'sn' => $sn,
                'stockAdjustment' => $stockAdjustment,
                'price_log' => $price_log,
                'next' => $next,
                'prev' => $prev,
                'activelink1' => 'inventory',
                'activelink2' => 'daftar barang',
                'activelink3' => 'out of safety stock',
                'raw_material' => $raw_material,
                'wip' => $wip,
                'finished_product' => $finished_product,
                'trading_goods' => $trading_goods,
                'service_product' => $service_product,
                'production_tools' => $production_tools,
                'operating_supplies' => $operating_supplies,
                'asset' => $asset,
                )
        );
    }

    public function updateMasterMaterial()
    {
        $material_code = urldecode(Request::get('material_code'));
        $update = array(
                    'material_name' => Request::post('material_name'),
                    'supplier_id' => Request::post('contact_id'),
                    'material_category' => Request::post('material_category'),
                    'unit' => Request::post('unit'),
                    'minimum_balance' => Request::post('minimum_balance'),
                    'material_type' => Request::post('material_type'),
                    'purchase_price' => Request::post('purchase_price'),
                    'purchase_currency' => Request::post('purchase_currency'),
                    'selling_price' => Request::post('selling_price'),
                    'selling_currency' => Request::post('selling_currency'),
                    'note' => Request::post('note'),
                    'modifier_id'    => SESSION::get('user_name'),
                    'modified_timestamp' => date('Y-m-d H:i:s',strtotime('NOW'))
                    );
        $cond = "`material_code` = '{$material_code}'";
        GenericModel::update('material_list', $update, $cond);

        $insert = array(
                    'uid'    => GenericModel::guid(),
                    'material_code' => Request::post('material_code'),
                    'material_name' => Request::post('material_name'),
                    'supplier_id' => Request::post('contact_id'),
                    'material_category' => Request::post('material_category'),
                    'unit' => Request::post('unit'),
                    'minimum_balance' => Request::post('minimum_balance'),
                    'purchase_price' => Request::post('purchase_price'),
                    'selling_price' => Request::post('selling_price'),
                    'note' => Request::post('note'),
                    'creator_id'    => SESSION::get('user_name')
                    );

        GenericModel::insert('material_list_adjustment', $insert);
        Redirect::to('inventory/editMaterial/?find=' . urlencode($material_code));
    }

    public function updateStock() {
        
        $material_code = urldecode(Request::get('material_code'));
        $total_input = (int)Request::post('total_input');

        for ($i = 1; $i < $total_input; $i++) {
                //update qty stock
                $update = array(
                    'created_timestamp'    => Request::post('created_timestamp_' . $i),
                    'transaction_number' => Request::post('transaction_number_' . $i),
                    'quantity_stock'    => Request::post('quantity_stock_' . $i),
                    'modified_timestamp'    => date("Y-m-d H:i:s"),
                    'modifier_id'    => SESSION::get('uid'),
                    );
                $uid = Request::post('uid_' . $i);
                GenericModel::update('material_list_in', $update, "`uid` = '{$uid}'");
        }

        for ($i=1; $i <= 10; $i++) {
            if (!empty(Request::post('lot_number_' . $i)) AND !empty(Request::post('new_stock_' . $i))) {
                if (empty(Request::post('incoming_date_' . $i))) {
                    $created_timestamp = date("Y-m-d H:i:s");
                } else {
                    $created_timestamp = Request::post('incoming_date_' . $i);
                }
                $insert = array(
                    'uid'    => GenericModel::guid(),
                    'created_timestamp'    => $created_timestamp,
                    'material_code' => $material_code,
                    'transaction_number' => Request::post('lot_number_' . $i),
                    'quantity_stock'    => Request::post('new_stock_' . $i),
                    'modified_timestamp'    => date("Y-m-d H:i:s"),
                    'modifier_id'    => SESSION::get('uid'),
                    );

                GenericModel::insert('material_list_in', $insert);

            }
        }

        Redirect::to('inventory/editMaterial/?find=' . urlencode($material_code));
    }

    public function updateBom()
    {
        $material_code = urldecode(Request::get('material_code'));
        $update = array(
                    'material_name' => Request::post('material_name'),
                    'unit' => Request::post('unit'),
                    'note' => Request::post('note'),
                    'modifier_id'    => SESSION::get('uid'),
                    'modified_timestamp' => date('Y-m-d H:i:s')
                    );
        $cond = "`material_code` = '{$material_code}'";
        GenericModel::update('material_list', $update, $cond);

        $insert = array(
                    'uid'    => GenericModel::guid(),
                    'material_code' => Request::post('material_code'),
                    'material_name' => Request::post('material_name'),
                    'unit' => Request::post('unit'),
                    'note' => Request::post('note'),
                    'creator_id'    => SESSION::get('uid')
                    );

        GenericModel::insert('material_list_adjustment', $insert);
        Redirect::to('inventory/editFormulation/?find=' . urlencode($material_code));
    }

    public function submitItemFormulation() {
            
            $material_code = explode(' - ', Request::post('material_code')); // get material code only
            if ($_POST['unit_type'] == 'percentage') {
                if ($_POST['quantity_per_unit'] > 100 ) {
                    Session::add('feedback_negative', 'Error!, lebih dari 100%.');
                    Redirect::to('inventory/editFormulation/?find=' . urlencode(Request::post('formulation_code')));
                    exit;
                }
                $insert = array(
                        'uid'    => GenericModel::guid(),
                        'material_code' => $material_code[0],
                        'formulation_code' => Request::post('formulation_code'),
                        'percentage_per_quantity' => Request::post('quantity_per_unit'),
                        'creator_id'    => SESSION::get('user_name')
                        );
                GenericModel::insert('material_list_formulation', $insert);
            } elseif ($_POST['unit_type'] == 'unit') {
                $insert = array(
                        'uid'    => GenericModel::guid(),
                        'material_code' => $material_code[0],
                        'formulation_code' => Request::post('formulation_code'),
                        'unit_per_quantity' => Request::post('quantity_per_unit'),
                        'creator_id'    => SESSION::get('user_name')
                        );
                GenericModel::insert('material_list_formulation', $insert);
            } else {
                Session::add('feedback_negative', 'Error!, gagal memasukkan formulasi produk');
                Redirect::to('inventory/editFormulation/?find=' . urlencode(Request::post('formulation_code')));
                exit;
            }
            
            //make log
            $update = array(
                        'modifier_id'    => SESSION::get('user_name'),
                        'modified_timestamp' => date("Y-m-d H:i:s")
                        );
            $formulation_code = Request::post('formulation_code');
            GenericModel::update('material_list', $update, "`material_code` = '$formulation_code'");
            Redirect::to('inventory/editFormulation/?find=' . urlencode(Request::post('formulation_code')));
    }

    // Edit record of stock Inventory
    public function editFormulation()
    {
 
        if (isset($_GET['find']) AND $_GET['find'] != '') {
            //starting to load models
            $name = strtolower(Request::get('find'));
            $name = trim($name);

            $sql = "
                SELECT
                    `material_list`.`material_code`,
                    `material_list`.`material_name`,
                    `material_list`.`note`,
                    `material_list`.`material_category`,
                    `material_list`.`material_type`,
                    `material_list`.`unit`
                FROM
                    `material_list`
                WHERE
                    `material_list`.`material_code` = '$name'
                LIMIT
                    1";
          

            $result = GenericModel::rawSelect($sql, false);

            $sql = "SELECT
                        `material_list_formulation`.`uid`,
                        `material_list_formulation`.`job_type`,
                        `material_list_formulation`.`material_code`,
                        `material_list_formulation`.`unit_per_quantity`,
                        `material_list_formulation`.`note`,
                        `material_list`.`material_name`,
                        `material_list`.`purchase_price`,
                        `material_list`.`purchase_currency`,
                        `material_list`.`purchase_unit`,
                        `material_list`.`unit` AS `formulation_unit`
                    FROM
                        `material_list_formulation`
                    LEFT JOIN
                        `material_list`
                            ON
                        `material_list_formulation`.`material_code` = `material_list`.`material_code`
                    WHERE
                        `material_list_formulation`.`formulation_code` = '{$name}'
                        AND
                        `material_list_formulation`.`is_deleted` = 0
                    ORDER BY
                        `material_list_formulation`.`material_code` ASC,
                        `material_list_formulation`.`job_type` ASC
                        ";

            $formulation_list = GenericModel::rawSelect($sql);
        }

        $this->View->render('inventory/edit_formulation',
            array(
                'title' => 'Edit BOM Inventory',
                'result' => $result,
                'formulation_list' => $formulation_list,
                'currency_rate' =>  FormaterModel::currencyRateBI(),
                )
        );
    }

    // Edit record of stock Inventory
    public function newFormulation() {
        // check user input if it empty or not, inserting empty field to database is not cool at all...
        if (!empty($_POST['bom_name'])) {
            //Start Material Code Validation
            //check apakah memakai kode material sendiri atau otomatis dari sistem
            if (!empty(Request::post('bom_code'))) {
                // check apakah kode material kurang dari 3
                if ((strlen(Request::post('bom_code')) < 3)) {
                    Session::add('feedback_negative', 'Kode Material tidak boleh kurang dari 3 karakter');
                    Redirect::to('inventory/formulation/');
                    exit;
                }

                // check apakah kode material sudah ada atau belum
                $field = "material_code";
                $value = Request::post('bom_code');
                $material_code_exist = GenericModel::isExist('material_list', $field, $value);

                if ($material_code_exist) {
                    //buat suggestion produk material
                    // create material code
                    // Delimit by multiple spaces, hyphen, underscore, comma
                    $words = preg_split("/[\s,_-]+/", Request::post('bom_name'));
                    $acronym = "";
                    foreach ($words as $w) {
                      $acronym .= strtoupper($w[0]);
                    }
                    $query = "SELECT `material_code` FROM `material_list` WHERE `material_code` LIKE '%$acronym%' ORDER BY `created_timestamp` DESC LIMIT 1";
                    $max = GenericModel::rawSelect($query, false);
                    $material_number = FormaterModel::getNumberOnly($max->material_code);
                    $material_last_number = substr($material_number, -1) + 1;
                    $material_code = FormaterModel::getLetterOnly($acronym) . $material_number . '/' . $material_last_number;

                    Session::add('feedback_negative', 'Kode Material sudah ada, cari yang lain. Suggestion: <span class="badge">' . strtoupper($material_code)) . '</span>';
                    Redirect::to('inventory/formulation/');
                    exit;
                    
                } else { // tidak ada kode material yg sama di database
                    $material_code = Request::post('bom_code');
                }

            } else { // material code tidak diisi, sistem menggenerate otomatis

                // create material code
                // Delimit by multiple spaces, hyphen, underscore, comma
                $words = preg_split("/[\s,_-]+/", Request::post('bom_name'));
                $acronym = "";
                foreach ($words as $w) {
                  $acronym .= strtoupper($w[0]);
                }
                // check apakah kode material sudah ada atau belum
                $field = "material_code";
                $value = $acronym;
                $material_code_exist = GenericModel::isExist('material_list', $field, $value);
                if ($material_code_exist) {
                    echo $query = "SELECT `material_code` FROM `material_list` WHERE `material_code` LIKE '%{$value}%' ORDER BY `created_timestamp` DESC LIMIT 1";
                    $max = GenericModel::rawSelect($query, false);
                    $material_number = FormaterModel::getNumberOnly($max->material_code) + 1;
                    $material_code = FormaterModel::getLetterOnly($value) . $material_number;
                } else {
                    // create material code
                    // Delimit by multiple spaces, hyphen, underscore, comma
                    $words = preg_split("/[\s,_-]+/", Request::post('bom_name'));
                    $acronym = "";
                    foreach ($words as $w) {
                      $acronym .= strtoupper($w[0]);
                    }
                    $material_code = $acronym;
                }
                
            }
            
            $insert = array(
                        'material_code' => 'BOM.' . trim($material_code),
                        'material_name' => Request::post('bom_name'),
                        'note' => Request::post('bom_note'),
                        'material_type' => 0,
                        'creator_id'    => SESSION::get('uid')
                        );
            GenericModel::insert('material_list', $insert);
            Redirect::to('inventory/formulation/');
        } else {
            Session::add('feedback_negative', Text::get('INSERT_FAILED'));
            Redirect::to('inventory/formulation/');
        }
    }

    public function addFormulation()
    {
        $material_code = urldecode(Request::get('material_code'));
        $this->View->renderFileOnly('inventory/add_formulation', array(
                'product_list' => GenericModel::getAll('material_list', "`is_deleted` = 0 AND (`material_type` = 1)", "`material_code`, `material_name`, `unit`"),
                'material_code' => $material_code
                )
        );
    }

    public function saveAddFormulation() {
        $formulation_code = urldecode(Request::post('formulation_code'));
        $material_list   = explode('___', Request::post('product_list'));
        $material_list   = array_filter($material_list);
        //echo '<pre>';var_dump($product_list);echo '</pre>';

        foreach ($material_list as $key => $value) {
            $material   = explode(' --- ', $value);
            //echo '<pre>';var_dump($product);echo '</pre>';KSP150/VNL --- 1 --- 17500 --- 10 ---  ---  ___ &customer=goo1 -- Oleh-Oleh Malang, CV.&deliveryRequest=2017-12-29
            $bom_type = $material[0];
            $bom_code = $material[1];
            $bom_qty = $material[2];
            $note = $material[3];
            //echo '<pre>';var_dump($product_quantity);echo '</pre>';
            if (!empty($bom_code  ) AND !empty($bom_qty)) {
                    $insert = array(
                        'uid'    => GenericModel::guid(),
                        'job_type' => trim($bom_type),
                        'material_code' => trim($bom_code),
                        'formulation_code' => trim($formulation_code),
                        'unit_per_quantity' => trim($bom_qty),
                        'note' => trim($note),
                        'creator_id'    => SESSION::get('uid')
                        );
                GenericModel::insert('material_list_formulation', $insert);
            }
        }

        $feedback_positive = Session::get('feedback_positive');
        $feedback_negative = Session::get('feedback_negative');
        // echo out positive messages
        if (isset($feedback_positive)) {
            echo 'SUKSES, ' . count($feedback_positive) . ' bill of material berhasil disimpan';
            //echo Config::get('URL') . 'pos/printNotaPenjualan/?so_number=' . urlencode($so_number);
        }
        // echo out negative messages
        if (isset($feedback_negative)) {
            echo 'GAGAL!, ' . count($feedback_positive) . ' bill of material berhasil disimpan';
        }
        // RESET counter feedback to unconfuse user
        Session::set('feedback_positive', null);
        Session::set('feedback_negative', null);
    }

    public function submitSerialNumber() { 
        if (!empty(Request::post('serial_number'))) {
                    $insert = array(
                        'serial_number' => Request::post('serial_number'),
                        'material_code' => Request::post('material_code'),
                        );
                GenericModel::insert('serial_number', $insert);
            }
        Redirect::to('inventory/editMaterial/?find=' . urlencode(Request::post('material_code')));
    }

    public function suratJalan($page = 1, $limit = 50) {
        $offset = ($page > 1) ? ($page - 1) * $limit : 0;
        $prev = Config::get('URL') . 'inventory/suratJalan/' . ($page - 1) . '/' . $limit;
        $next = Config::get('URL') . 'inventory/suratJalan/' . ($page + 1). '/' . $limit;

        $sj = "SELECT
                    `material_list_out`.`uid`,
                    GROUP_CONCAT(`material_list_out`.`material_code` SEPARATOR '--,--') as `material_code`,
                    GROUP_CONCAT(`material_list`.`material_name` SEPARATOR '--,--') as `material_name`,
                    GROUP_CONCAT(`material_list_out`.`quantity_delivered` SEPARATOR '--,--') as `quantity_delivered`,
                    `material_list_out`.`do_number`,
                    `material_list_out`.`transaction_number`,
                    `material_list_out`.`delivery_date`
                FROM
                    `material_list_out`
                JOIN
                    `material_list` ON `material_list`.`material_code` = `material_list_out`.`material_code`
                WHERE
                    `material_list_out`.`status` = 'sj'
                GROUP BY
                    `material_list_out`.`do_number`
                ORDER BY
                    `material_list_out`.`delivery_date`
                LIMIT
                    $offset, $limit
                ";
        $header_script = '<link rel="stylesheet" href="' . Config::get('URL') . 'bootstrap-3.3.7/css/excel-2007.css" media="screen"/>';
            $this->View->render(
                'inventory/surat_jalan',
                  array(
                'header_script' => $header_script,
                'title' => 'Daftar Surat Jalan: Halaman ' . $page,
                'prev' => $prev,
                'next' => $next,
                'page' => $page,
                'limit' => $limit,
                'activelink1' => 'inventory',
                'activelink2' => 'surat jalan',
                'sj' => GenericModel::rawSelect($sj)
                )
            );
    }

    public function newSj()
    {
        $this->View->renderFileOnly('inventory/new_sj', array(
                'material_list' => GenericModel::getAll('material_list', "`is_deleted` = 0 AND `material_type` = 1", "`material_code`, `material_name`, `unit`"),

                )
        );
    }

    public function makeSj() {
        if (empty(Request::post('transaction_number'))) {
            echo 'Reverensi transaksi tidak boleh kosong';
            exit;
        }

        $product_list   = explode(' ___ ', Request::post('product_list'));
        $product_list   = array_filter($product_list);

        //Make DO number
        $do = GenericModel::getOne('`material_list_out`', "1 ORDER BY `do_number` DESC", 'do_number');
        $do_number_counter = substr(strrchr($do->do_number, "."), 1);
        $do_number_counter = $do_number_counter + 1;
        $do_number =  $do_number_counter . '/SJ-' . date('y') . date('m') . date('d');


        foreach ($product_list as $key => $value) {
            $product   = explode('---', $value);
            $product_code = FormaterModel::sanitize($product[0]);
            $product_qty = FormaterModel::sanitize($product[1]);
            $product_note = FormaterModel::sanitize($product[2]);
            //echo '<pre>';var_dump($product_quantity);echo '</pre>';
            if (!empty($product_code  ) AND !empty($product_qty)) {
                    $insert = array(
                        'uid'    => GenericModel::guid(),
                        'transaction_number'    => Request::post('transaction_number'),
                        'do_number'    => $do_number,
                        'material_code'    => $product_code,
                        'quantity'    => $product_qty,
                        'delivery_date'    => date('Y-m-d'),
                        'status'    => 'sj',
                        'note' => '(Custome SJ) ' . $product_note,
                        'creator_id'    => SESSION::get('uid')
                    );
                    GenericModel::insert('material_list_out', $insert);
            }
        }

        Redirect::to('so/printSj/?sj_number=' . urlencode($do_number));
    }

    public function manualReceive()
    {
        $this->View->renderFileOnly('inventory/manual_receive', array(
                'material_list' => GenericModel::getAll('material_list', "`is_deleted` = 0 AND `material_type` = 1", "`material_code`, `material_name`, `unit`"),

                )
        );
    }

    public function saveManualReceive() {
        if (empty(Request::post('transaction_number'))) {
            echo 'Reverensi transaksi tidak boleh kosong';
            exit;
        }
        $po_number = Request::post('transaction_number');
        $product_list   = explode(' ___ ', Request::post('product_list'));
        $product_list   = array_filter($product_list);

        //Make DO number
        foreach ($product_list as $key => $value) {
            $product   = explode('---', $value);
            $product_code = FormaterModel::sanitize($product[0]);
            $product_qty = FormaterModel::sanitize($product[1]);
            $product_note = FormaterModel::sanitize($product[2]);
            //echo '<pre>';var_dump($product);echo '</pre>';
            if (!empty($product_code) AND !empty($product_qty)) {
                    $insert = array(
                        'uid'    => GenericModel::guid(),
                        'material_code'    => $product_code,
                        'transaction_number'      =>  $po_number,
                        'quantity'      =>  (int)$product_qty,
                        'status'      =>  'waiting_qc_approval',
                        'note' => 'Barang masuk gudang, menunggu QC Approval',
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
        $email_subject = "Warehouse received NON PO material on " . $email_creator;
        $body = ucwords($email_creator) . ' menerima barang NON PO di gudang Klik link berikut untuk melihat detailnya ' .   Config::get('URL') . 'qc/waitingApproval/';
        $mail = new Mail;

        $mail_sent = $mail->sendMail($email, Config::get('EMAIL_VERIFICATION_FROM_EMAIL'),
            Config::get('EMAIL_VERIFICATION_FROM_NAME'), $email_subject, $body
        );
        
        if ($mail_sent) {
            Session::add('feedback_positive', Text::get('FEEDBACK_VERIFICATION_MAIL_SENDING_SUCCESSFUL'));
        } else {
            Session::add('feedback_negative', Text::get('FEEDBACK_VERIFICATION_MAIL_SENDING_ERROR') . $mail->getError() );
        }

        $feedback_positive = Session::get('feedback_positive');
        $feedback_negative = Session::get('feedback_negative');

        // echo out positive messages
        if (isset($feedback_positive)) {
            echo 'SUKSES, ' . (count($feedback_positive) - 1)  . ' transaksi berhasil disimpan'; // -1 karena untuk mengurangi feedback dari send email
        }
        // echo out negative messages
        if (isset($feedback_negative)) {
            echo 'GAGAL!, ' . (count($feedback_positive)  - 1) . ' transaksi gagal disimpan'; // -1 karena untuk mengurangi feedback dari send email
        }
        

        // RESET counter feedback to unconfuse user
        Session::set('feedback_positive', null);
        Session::set('feedback_negative', null);

        //Redirect::to('inventory/logIn');
    }

    public function logInRawMat($page = 1, $limit = 50) {
        $where = " WHERE `material_list_in`.`quantity_received` > 1 AND `material_list`.`material_type` = 1  ";
        
        if (isset($_GET['start_date']) AND isset($_GET['start_date'])) {
            $start_date = Request::get('start_date');
            $finish_date = Request::get('finish_date');

            $where .= " AND `material_list_in`.`incoming_date` BETWEEN '{$start_date} 00:00:00' AND '{$finish_date} 23:59:59' ";

            $pagination = ''; // gak ada pagination

        } else {
            $offset = ($page > 1) ? ($page - 1) * $limit : 0;
            $sql_limit = " LIMIT $offset, $limit ";

            //Pagination
            $table = '`material_list_in`
                    JOIN
                        `material_list`
                        ON
                        `material_list`.`material_code` = `material_list_in`.`material_code`';
            $total_record = GenericModel::rowCount($table, "`material_list_in`.`quantity_received` > 1 AND `material_list`.`material_type` = 1", '`material_list`.`material_code`');
            $pagination = FormaterModel::pagination('inventory/logInRawMat', $total_record, $page, $limit);
        }
        
        $log = "SELECT
                        `material_list_in`.`material_code`,
                        `material_list`.`material_name`,
                        `material_list_in`.`quantity_reject`,
                        `material_list_in`.`quantity_received`,
                        `material_list_in`.`transaction_number`,
                        `material_list_in`.`serial_number`,
                        `material_list_in`.`serial_number_received`,
                        `material_list_in`.`incoming_date`,
                        (`purchase_order_list`.`purchase_price` - `purchase_order_list`.`purchase_price_discount`) AS `purchase_price`,
                        `purchase_order_list`.`unit`,
                        `purchase_order_list`.`purchase_currency`
                    FROM
                        `material_list_in`
                    JOIN
                        `material_list`
                        ON
                        `material_list`.`material_code` = `material_list_in`.`material_code`
                    LEFT JOIN
                        `purchase_order_list`
                        ON
                        (`purchase_order_list`.`transaction_number` = `material_list_in`.`transaction_number`
                        AND
                        `purchase_order_list`.`material_code` = `material_list_in`.`material_code`
                        )
                    {$where}
                    
                    ORDER BY
                        `material_list_in`.`incoming_date` DESC
                    {$sql_limit}";

        //export excel
        if ($_GET['export_excel'] == 1) {
            $this->exportExcelLogInRawMat(GenericModel::rawSelect($log));
            die();
        }

        $header_script = '<link rel="stylesheet" href="' . Config::get('URL') . 'bootstrap-3.3.7/css/excel-2007.css" media="screen"/>
        <link rel="stylesheet" href="' . Config::get('URL') . 'bootstrap-3.3.7/css/datepicker.css" media="screen"/>';
        $footer_script = '<script src="' . Config::get('URL') . 'bootstrap-3.3.7/js/bootstrap-datepicker.js"></script>
        <script>
        $(".datepicker").datepicker(); //date picker
        </script>';
        $this->View->render(
            'inventory/log_in_raw_mat',
              array(
            'header_script' => $header_script,
            'footer_script' => $footer_script,
            'title' => 'Log Bahan Baku Masuk',
            'page' => $page,
            'limit' => $limit,
            'activelink1' => 'inventory',
            'activelink2' => 'log keluar masuk barang',
            'activelink3' => 'log barang masuk',
            'activelink4' => 'log barang masuk bahan baku',
            'log' => GenericModel::rawSelect($log),
            'pagination' => $pagination
            )
        );
    }

    public function logInFinishGood($page = 1, $limit = 50) {
        $where = " WHERE `material_list_in`.`quantity_received` > 1 AND (`material_list`.`material_type` = 3 OR `material_list`.`material_type` = 4) AND `material_list_in`.`transaction_number` LIKE '%/JO-%'";
        
        if (isset($_GET['start_date']) AND isset($_GET['start_date'])) {
            $start_date = Request::get('start_date');
            $finish_date = Request::get('finish_date');

            $where .= " AND `material_list_in`.`incoming_date` BETWEEN '{$start_date} 00:00:00' AND '{$finish_date} 23:59:59' ";


            $pagination = ''; //gak ada pagination

        } else {
            $offset = ($page > 1) ? ($page - 1) * $limit : 0;
            $sql_limit = " LIMIT $offset, $limit ";

            //Pagination
            $table = '`material_list_in`
                    JOIN
                        `material_list`
                        ON
                        `material_list`.`material_code` = `material_list_in`.`material_code`';
            $total_record = GenericModel::rowCount($table, "`material_list_in`.`quantity_received` > 1 AND (`material_list`.`material_type` = 3 OR `material_list`.`material_type` = 4) AND `material_list_in`.`transaction_number` LIKE '%/JO-%'", '`material_list`.`material_code`');
            $pagination = FormaterModel::pagination('inventory/logInFinishGood', $total_record, $page, $limit);
        }
        
        $log = "SELECT
                        `material_list_in`.`material_code`,
                        `material_list`.`material_name`,
                        `material_list_in`.`quantity_reject`,
                        `material_list_in`.`quantity_received`,
                        `material_list_in`.`transaction_number`,
                        `material_list_in`.`serial_number`,
                        `material_list_in`.`serial_number_received`,
                        `material_list_in`.`incoming_date`
                    FROM
                        `material_list_in`
                    JOIN
                        `material_list`
                        ON
                        `material_list`.`material_code` = `material_list_in`.`material_code`
                    
                    {$where}
                    
                    ORDER BY
                        `material_list_in`.`incoming_date` DESC
                    {$sql_limit}";

        //export excel
        if ($_GET['export_excel'] == 1) {
            $this->exportExcelLogInFinishGood(GenericModel::rawSelect($log));
            die();
        }

        $header_script = '<link rel="stylesheet" href="' . Config::get('URL') . 'bootstrap-3.3.7/css/excel-2007.css" media="screen"/>
        <link rel="stylesheet" href="' . Config::get('URL') . 'bootstrap-3.3.7/css/datepicker.css" media="screen"/>';
        $footer_script = '<script src="' . Config::get('URL') . 'bootstrap-3.3.7/js/bootstrap-datepicker.js"></script>
        <script>
        $(".datepicker").datepicker(); //date picker
        </script>';
        $this->View->render(
            'inventory/log_in_finish_good',
              array(
            'header_script' => $header_script,
            'footer_script' => $footer_script,
            'title' => 'Log Finish Goods Masuk',
            'page' => $page,
            'limit' => $limit,
            'activelink1' => 'inventory',
            'activelink2' => 'log keluar masuk barang',
            'activelink3' => 'log barang masuk',
            'activelink4' => 'log barang masuk fgwh',
            'log' => GenericModel::rawSelect($log),
            'pagination' => $pagination
            )
        );
    }

    public function logInAll($page = 1, $limit = 50) {
        $where = " WHERE `material_list_in`.`quantity_received` > 1 ";
        
        if (isset($_GET['start_date']) AND isset($_GET['start_date'])) {
            $start_date = Request::get('start_date');
            $finish_date = Request::get('finish_date');

            $where .= " AND `material_list_in`.`incoming_date` BETWEEN '{$start_date} 00:00:00' AND '{$finish_date} 23:59:59' ";

            $pagination = ''; //tidak ada pagination
        } else {
            $start_date = '';
            $finish_date = '';
            $offset = ($page > 1) ? ($page - 1) * $limit : 0;
            $sql_limit = " LIMIT $offset, $limit ";
            //Pagination
            $total_record = GenericModel::rowCount('`material_list_in`',"`material_list_in`.`quantity_received` > 1", '`material_code`');
            $pagination = FormaterModel::pagination('inventory/logInAll', $total_record, $page, $limit);
        }
        
        $log = "SELECT
                        `material_list_in`.`material_code`,
                        `material_list`.`material_name`,
                        `material_list_in`.`quantity_reject`,
                        `material_list_in`.`quantity_received`,
                        `material_list_in`.`transaction_number`,
                        `material_list_in`.`serial_number`,
                        `material_list_in`.`serial_number_received`,
                        `material_list_in`.`incoming_date`
                    FROM
                        `material_list_in`
                    JOIN
                        `material_list` ON `material_list`.`material_code` = `material_list_in`.`material_code`
                    
                    {$where}
                    
                    ORDER BY
                        `material_list_in`.`incoming_date` DESC
                    {$sql_limit}";

        //export excel
        if ($_GET['export_excel'] == 1) {
            $this->exportExcelLogInAll(GenericModel::rawSelect($log));
            die();
        }

        $header_script = '<link rel="stylesheet" href="' . Config::get('URL') . 'bootstrap-3.3.7/css/excel-2007.css" media="screen"/>
        <link rel="stylesheet" href="' . Config::get('URL') . 'bootstrap-3.3.7/css/datepicker.css" media="screen"/>';
        $footer_script = '<script src="' . Config::get('URL') . 'bootstrap-3.3.7/js/bootstrap-datepicker.js"></script>
        <script>
        $(".datepicker").datepicker(); //date picker
        </script>';
        $this->View->render(
            'inventory/log_in_all',
              array(
            'header_script' => $header_script,
            'footer_script' => $footer_script,
            'title' => 'Log Semua Barang Masuk',
            'page' => $page,
            'limit' => $limit,
            'activelink1' => 'inventory',
            'activelink2' => 'log keluar masuk barang',
            'activelink3' => 'log barang masuk',
            'activelink4' => 'log barang masuk all',
            'log' => GenericModel::rawSelect($log),
            'pagination' => $pagination
            )
        );
    }

    public static function exportExcelLogInRawMat($log)
    {
       //styling header excel
        $head_style_array = [
            'font' => [
                'bold' => true,
                'size' => 15,
                'color' => array('rgb' => 'ffffff'),
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
            'borders' => [
                'top' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR,
                'rotation' => 90,
                'startColor' => [
                    'argb' => 'ff4d40',
                ],
                'endColor' => [
                    'argb' => 'c95149',
                ],
            ],
        ];

        /** Create a new Spreadsheet Object **/
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        //set default style
        $spreadsheet->getDefaultStyle()->getAlignment()->setWrapText(true); //make text wrap


        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Tanggal');
        $sheet->setCellValue('C1', 'Kode Barang');
        $sheet->setCellValue('D1', 'Nama Barang');
        $sheet->setCellValue('E1', '#Transaksi');
        if (Auth::isPermissioned('director,finance,purchasing')) {
            $sheet->setCellValue('F1', 'Jumlah');
            $sheet->setCellValue('G1', 'Harga');
            $sheet->setCellValue('H1', 'Currency');
        }
        

        $no = 2;
        foreach ($log as $key => $value) {
            $sheet->setCellValue('A' . $no, $no);
            $sheet->setCellValue('B' . $no, $value->incoming_date);
            $sheet->setCellValue('C' . $no, $value->material_code);
            $sheet->setCellValue('D' . $no, $value->material_name);
            $sheet->setCellValue('E' . $no, $value->transaction_number);
            if (Auth::isPermissioned('director,finance,purchasing')) {
                $sheet->setCellValue('F' . $no, FormaterModel::trimTrailingZeroes($value->quantity_received));
                $sheet->getStyle('F' . $no)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
                $sheet->setCellValue('G' . $no, FormaterModel::trimTrailingZeroes($value->purchase_price));
                $sheet->getStyle('G' . $no)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
                $sheet->setCellValue('H' . $no, $value->purchase_currency);
            }
            $no++;
        }

        //set width coloumn
        $sheet->getStyle('A1:H1')->applyFromArray($head_style_array); //warnai cell judul
        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getColumnDimension('D')->setWidth(30);
        $sheet->getColumnDimension('E')->setAutoSize(true);
        if (Auth::isPermissioned('director,finance,purchasing')) {
            $sheet->getColumnDimension('F')->setWidth(10);
            $sheet->getColumnDimension('G')->setWidth(15);
            $sheet->getColumnDimension('H')->setAutoSize(true);
        }

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename="log-masuk-raw-mat.xlsx"');
        $writer->save("php://output");
    }

    public static function exportExcelLogInFinishGood($log)
    {
       //styling header excel
        $head_style_array = [
            'font' => [
                'bold' => true,
                'size' => 15,
                'color' => array('rgb' => 'ffffff'),
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
            'borders' => [
                'top' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR,
                'rotation' => 90,
                'startColor' => [
                    'argb' => 'ff4d40',
                ],
                'endColor' => [
                    'argb' => 'c95149',
                ],
            ],
        ];

        /** Create a new Spreadsheet Object **/
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        //set default style
        $spreadsheet->getDefaultStyle()->getAlignment()->setWrapText(true); //make text wrap


        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Tanggal');
        $sheet->setCellValue('C1', 'Kode Barang');
        $sheet->setCellValue('D1', 'Nama Barang');
        $sheet->setCellValue('E1', '#Transaksi');
        $sheet->setCellValue('F1', 'Jumlah');
        $sheet->setCellValue('G1', 'Serial Number');

        $no = 2;
        foreach ($log as $key => $value) {
            $sheet->setCellValue('A' . $no, $no);
            $sheet->setCellValue('B' . $no, $value->incoming_date);
            $sheet->setCellValue('C' . $no, $value->material_code);
            $sheet->setCellValue('D' . $no, $value->material_name);
            $sheet->setCellValue('E' . $no, $value->transaction_number);
            $sheet->setCellValue('F' . $no, FormaterModel::trimTrailingZeroes($value->quantity_received));
            $sheet->getStyle('F' . $no)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
            $sheet->setCellValue('G' . $no, $value->serial_number_received);
            $no++;
        }

        //set width coloumn
        $sheet->getStyle('A1:G1')->applyFromArray($head_style_array); //warnai cell judul
        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getColumnDimension('D')->setWidth(30);
        $sheet->getColumnDimension('E')->setAutoSize(true);
        $sheet->getColumnDimension('F')->setWidth(10);
        $sheet->getColumnDimension('G')->setWidth(30);

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename="log-masuk-fgwh.xlsx"');
        $writer->save("php://output");
    }

    public static function exportExcelLogInAll($log)
    {
       //styling header excel
        $head_style_array = [
            'font' => [
                'bold' => true,
                'size' => 15,
                'color' => array('rgb' => 'ffffff'),
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
            'borders' => [
                'top' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR,
                'rotation' => 90,
                'startColor' => [
                    'argb' => 'ff4d40',
                ],
                'endColor' => [
                    'argb' => 'c95149',
                ],
            ],
        ];

        /** Create a new Spreadsheet Object **/
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        //set default style
        $spreadsheet->getDefaultStyle()->getAlignment()->setWrapText(true); //make text wrap


        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Tanggal');
        $sheet->setCellValue('C1', 'Kode Barang');
        $sheet->setCellValue('D1', 'Nama Barang');
        $sheet->setCellValue('E1', '#Transaksi');
        $sheet->setCellValue('F1', 'Jumlah Diterima');
        $sheet->setCellValue('G1', 'Jumlah Ditolak');
        $sheet->setCellValue('H1', 'SN Diterima');
        $sheet->setCellValue('I1', 'SN Ditolak');

        $no = 2;
        foreach ($log as $key => $value) {
            $sheet->setCellValue('A' . $no, $no);
            $sheet->setCellValue('B' . $no, $value->incoming_date);
            $sheet->setCellValue('C' . $no, $value->material_code);
            $sheet->setCellValue('D' . $no, $value->material_name);
            $sheet->setCellValue('E' . $no, $value->transaction_number);
            $sheet->setCellValue('F' . $no, FormaterModel::trimTrailingZeroes($value->quantity_received));
            $sheet->getStyle('F' . $no)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
            $sheet->setCellValue('G' . $no, FormaterModel::trimTrailingZeroes($value->quantity_reject));
            $sheet->getStyle('G' . $no)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
            $sheet->setCellValue('H' . $no, $value->serial_number);
            $sheet->setCellValue('I' . $no, $value->serial_number_received);
            $no++;
        }

        //set width coloumn
        $sheet->getStyle('A1:I1')->applyFromArray($head_style_array); //warnai cell judul
        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getColumnDimension('D')->setWidth(30);
        $sheet->getColumnDimension('E')->setAutoSize(true);
        $sheet->getColumnDimension('F')->setWidth(10);
        $sheet->getColumnDimension('G')->setWidth(10);
        $sheet->getColumnDimension('H')->setWidth(15);
        $sheet->getColumnDimension('I')->setWidth(15);

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename="log-masuk-semua-material.xlsx"');
        $writer->save("php://output");
    }

    public function logOutFinishGood($page = 1, $limit = 50) {
        $where = " WHERE `material_list_out`.`quantity_delivered` > 1 AND (`material_list`.`material_type` = 3 OR `material_list`.`material_type` = 4)";
        
        if (isset($_GET['start_date']) AND isset($_GET['start_date'])) {
            $start_date = Request::get('start_date');
            $finish_date = Request::get('finish_date');

            $where .= " AND `material_list_out`.`created_timestamp` BETWEEN '{$start_date} 00:00:00' AND '{$finish_date} 23:59:59' ";

            $pagination = ''; //tidak ada pagination
        } else {
            $offset = ($page > 1) ? ($page - 1) * $limit : 0;
            $sql_limit = " LIMIT $offset, $limit ";
            //Pagination
            $table = '`material_list_out`
                    JOIN
                        `material_list`
                        ON
                        `material_list`.`material_code` = `material_list_out`.`material_code`';
            $total_record = GenericModel::rowCount($table,"`material_list_out`.`quantity_delivered` > 1 AND (`material_list`.`material_type` = 3 OR `material_list`.`material_type` = 4)", '`material_list_out`.`material_code`');
            $pagination = FormaterModel::pagination('inventory/logOutFinishGood', $total_record, $page, $limit);
        }
        
        $log = "SELECT
                        `material_list_out`.`material_code`,
                        `material_list`.`material_name`,
                        `material_list_out`.`quantity_reject`,
                        `material_list_out`.`quantity_delivered`,
                        `material_list_out`.`transaction_number`,
                        `material_list_out`.`serial_number`,
                        `material_list_out`.`created_timestamp`
                    FROM
                        `material_list_out`
                    JOIN
                        `material_list`
                        ON
                        `material_list`.`material_code` = `material_list_out`.`material_code`
                    
                    {$where}
                    
                    ORDER BY
                        `material_list_out`.`created_timestamp` DESC
                    {$sql_limit}";

        //export excel
        if ($_GET['export_excel'] == 1) {
            $this->exportExcelLogOutFinishGood(GenericModel::rawSelect($log));
            die();
        }

        $header_script = '<link rel="stylesheet" href="' . Config::get('URL') . 'bootstrap-3.3.7/css/excel-2007.css" media="screen"/>
        <link rel="stylesheet" href="' . Config::get('URL') . 'bootstrap-3.3.7/css/datepicker.css" media="screen"/>';
        $footer_script = '<script src="' . Config::get('URL') . 'bootstrap-3.3.7/js/bootstrap-datepicker.js"></script>
        <script>
        $(".datepicker").datepicker(); //date picker
        </script>';
        $this->View->render(
            'inventory/log_out_finish_good',
              array(
            'header_script' => $header_script,
            'footer_script' => $footer_script,
            'title' => 'Log Keluar FGWH',
            'page' => $page,
            'limit' => $limit,
            'activelink1' => 'inventory',
            'activelink2' => 'log keluar masuk barang',
            'activelink3' => 'log barang keluar',
            'activelink4' => 'log barang keluar fgwh',
            'log' => GenericModel::rawSelect($log),
            'pagination' => $pagination
            )
        );
    }

    public function logOutRawMat($page = 1, $limit = 50) {
        $where = " WHERE `material_list_out`.`quantity_delivered` > 1  AND `material_list`.`material_type` = 1";
        
        if (isset($_GET['start_date']) AND isset($_GET['start_date'])) {
            $start_date = Request::get('start_date');
            $finish_date = Request::get('finish_date');

            $where .= " AND `material_list_out`.`created_timestamp` BETWEEN '{$start_date} 00:00:00' AND '{$finish_date} 23:59:59' ";

            $pagination = ''; //tidak ada pagination
        } else {
            $offset = ($page > 1) ? ($page - 1) * $limit : 0;
            $sql_limit = " LIMIT $offset, $limit ";

            //Pagination
            $table = '`material_list_out`
                    JOIN
                        `material_list`
                        ON
                        `material_list`.`material_code` = `material_list_out`.`material_code`';
            $total_record = GenericModel::rowCount($table,"`material_list_out`.`quantity_delivered` > 1  AND `material_list`.`material_type` = 1", '`material_list_out`.`material_code`');
            $pagination = FormaterModel::pagination('inventory/logOutRawMat', $total_record, $page, $limit);
        }
        
        $log = "SELECT
                        `material_list_out`.`material_code`,
                        `material_list`.`material_name`,
                        `material_list_out`.`quantity_reject`,
                        `material_list_out`.`quantity_delivered`,
                        `material_list_out`.`unit` AS `material_out_unit`,
                        `material_list_out`.`transaction_number`,
                        `material_list_out`.`material_lot_number`,
                        `material_list_out`.`serial_number`,
                        `material_list_out`.`created_timestamp`,
                        (`purchase_order_list`.`purchase_price` - `purchase_order_list`.`purchase_price_discount`) AS `purchase_price`,
                        `purchase_order_list`.`unit` AS `purchase_unit`,
                        `purchase_order_list`.`purchase_currency`
                    FROM
                        `material_list_out`
                    JOIN
                        `material_list`
                        ON
                        `material_list`.`material_code` = `material_list_out`.`material_code`
                    LEFT JOIN
                        `purchase_order_list`
                        ON
                        (`purchase_order_list`.`transaction_number` = `material_list_out`.`material_lot_number`
                        AND
                        `purchase_order_list`.`material_code` = `material_list_out`.`material_code`
                        )
                    
                    {$where}
                    
                    ORDER BY
                        `material_list_out`.`created_timestamp` DESC
                    {$sql_limit}";

        //export excel
        if ($_GET['export_excel'] == 1) {
            $this->exportExcelLogOutRawMat(GenericModel::rawSelect($log));
            die();
        }

        $header_script = '<link rel="stylesheet" href="' . Config::get('URL') . 'bootstrap-3.3.7/css/excel-2007.css" media="screen"/>
        <link rel="stylesheet" href="' . Config::get('URL') . 'bootstrap-3.3.7/css/datepicker.css" media="screen"/>';
        $footer_script = '<script src="' . Config::get('URL') . 'bootstrap-3.3.7/js/bootstrap-datepicker.js"></script>
        <script>
        $(".datepicker").datepicker(); //date picker
        </script>';
        $this->View->render(
            'inventory/log_out_raw_mat',
              array(
            'header_script' => $header_script,
            'footer_script' => $footer_script,
            'title' => 'Log Keluar Bahan Baku',
            'page' => $page,
            'limit' => $limit,
            'activelink1' => 'inventory',
            'activelink2' => 'log keluar masuk barang',
            'activelink3' => 'log barang keluar',
            'activelink4' => 'log barang keluar bahan baku',
            'log' => GenericModel::rawSelect($log),
            'pagination' => $pagination
            )
        );
    }

    public function logOutAll($page = 1, $limit = 50) {
        $where = " WHERE `material_list_out`.`quantity_delivered` > 1";
        
        if (isset($_GET['start_date']) AND isset($_GET['start_date'])) {
            $start_date = Request::get('start_date');
            $finish_date = Request::get('finish_date');

            $where .= " AND `material_list_out`.`created_timestamp` BETWEEN '{$start_date} 00:00:00' AND '{$finish_date} 23:59:59' ";

            $pagination = ''; //tidak ada pagination
        } else {
            $offset = ($page > 1) ? ($page - 1) * $limit : 0;
            $sql_limit = " LIMIT $offset, $limit ";
            //Pagination
            $total_record = GenericModel::rowCount('`material_list_out`',"`material_list_out`.`quantity_delivered` > 1", '`material_code`');
            $pagination = FormaterModel::pagination('inventory/logOutAll', $total_record, $page, $limit);
        }
        
        $log = "SELECT
                        `material_list_out`.`material_code`,
                        `material_list`.`material_name`,
                        `material_list_out`.`quantity_reject`,
                        `material_list_out`.`quantity_delivered`,
                        `material_list_out`.`transaction_number`,
                        `material_list_out`.`serial_number`,
                        `material_list_out`.`created_timestamp`
                    FROM
                        `material_list_out`
                    JOIN
                        `material_list`
                        ON
                        `material_list`.`material_code` = `material_list_out`.`material_code`
                    
                    {$where}
                    
                    ORDER BY
                        `material_list_out`.`created_timestamp` DESC
                    {$sql_limit}";

        //export excel
        if ($_GET['export_excel'] == 1) {
            $this->exportExcelLogOutAll(GenericModel::rawSelect($log));
            die();
        }

        $header_script = '<link rel="stylesheet" href="' . Config::get('URL') . 'bootstrap-3.3.7/css/excel-2007.css" media="screen"/>
        <link rel="stylesheet" href="' . Config::get('URL') . 'bootstrap-3.3.7/css/datepicker.css" media="screen"/>';
        $footer_script = '<script src="' . Config::get('URL') . 'bootstrap-3.3.7/js/bootstrap-datepicker.js"></script>
        <script>
        $(".datepicker").datepicker(); //date picker
        </script>';
        $this->View->render(
            'inventory/log_out_all',
              array(
            'header_script' => $header_script,
            'footer_script' => $footer_script,
            'title' => 'Log Semua Barang Keluar',
            'page' => $page,
            'limit' => $limit,
            'activelink1' => 'inventory',
            'activelink2' => 'log keluar masuk barang',
            'activelink3' => 'log barang keluar',
            'activelink4' => 'log barang keluar all',
            'log' => GenericModel::rawSelect($log),
            'pagination' => $pagination
            )
        );
    }

    public static function exportExcelLogOutRawMat($log)
    {
       //styling header excel
        $head_style_array = [
            'font' => [
                'bold' => true,
                'size' => 15,
                'color' => array('rgb' => 'ffffff'),
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
            'borders' => [
                'top' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR,
                'rotation' => 90,
                'startColor' => [
                    'argb' => 'ff4d40',
                ],
                'endColor' => [
                    'argb' => 'c95149',
                ],
            ],
        ];

        /** Create a new Spreadsheet Object **/
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        //set default style
        $spreadsheet->getDefaultStyle()->getAlignment()->setWrapText(true); //make text wrap


        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Tanggal');
        $sheet->setCellValue('C1', 'Kode Barang');
        $sheet->setCellValue('D1', 'Nama Barang');
        $sheet->setCellValue('E1', '#Transaksi');
        $sheet->setCellValue('F1', 'Jumlah');
        $sheet->setCellValue('G1', 'Satuan');
        if (Auth::isPermissioned('director,finance,purchasing')) {
            $sheet->setCellValue('H1', 'Harga Pembelian');
            $sheet->setCellValue('H2', 'Lot Pembelian');
            $sheet->setCellValue('I2', 'Harga');
            $sheet->setCellValue('J2', 'Satuan');
            $sheet->setCellValue('K2', 'Cur');
        }

        $no = 3;
        foreach ($log as $key => $value) {
            $sheet->setCellValue('A' . $no, $no);
            $sheet->setCellValue('B' . $no, $value->created_timestamp);
            $sheet->setCellValue('C' . $no, $value->material_code);
            $sheet->setCellValue('D' . $no, $value->material_name);
            $sheet->setCellValue('E' . $no, $value->transaction_number);
            $sheet->setCellValue('F' . $no, FormaterModel::trimTrailingZeroes($value->quantity_delivered));
            //make align
            $sheet->getStyle('F' . $no)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
            $sheet->setCellValue('G' . $no, $value->material_out_unit);
            if (Auth::isPermissioned('director,finance,purchasing')) {
                $sheet->setCellValue('H' . $no, $value->material_lot_number);
                $sheet->setCellValue('I' . $no, FormaterModel::trimTrailingZeroes($value->purchase_price));
                //make align
                $sheet->getStyle('I' . $no)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
                $sheet->setCellValue('J' . $no, $value->purchase_unit);
                $sheet->setCellValue('K' . $no, $value->purchase_currency);
            }
            $no++;
        }
        //merge header cell
        $sheet->mergeCells('A1:A2');
        $sheet->mergeCells('B1:B2');
        $sheet->mergeCells('C1:C2');
        $sheet->mergeCells('D1:D2');
        $sheet->mergeCells('E1:E2');
        $sheet->mergeCells('F1:F2');
        $sheet->mergeCells('G1:G2');
        $sheet->mergeCells('H1:K1');

        //set width coloumn
        $sheet->getStyle('A1:K2')->applyFromArray($head_style_array); //warnai cell judul
        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getColumnDimension('D')->setWidth(30);
        $sheet->getColumnDimension('E')->setAutoSize(true);
        $sheet->getColumnDimension('F')->setWidth(10);
        $sheet->getColumnDimension('G')->setWidth(10);
        if (Auth::isPermissioned('director,finance,purchasing')) {
            $sheet->getColumnDimension('H')->setAutoSize(true);
            $sheet->getColumnDimension('I')->setAutoSize(true);
            $sheet->getColumnDimension('J')->setAutoSize(true);
            $sheet->getColumnDimension('K')->setAutoSize(true);
        }

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename="log-keluar-raw-mat.xlsx"');
        $writer->save("php://output");
    }

    public static function exportExcelLogOutFinishGood($log)
    {
       //styling header excel
        $head_style_array = [
            'font' => [
                'bold' => true,
                'size' => 15,
                'color' => array('rgb' => 'ffffff'),
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
            'borders' => [
                'top' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR,
                'rotation' => 90,
                'startColor' => [
                    'argb' => 'ff4d40',
                ],
                'endColor' => [
                    'argb' => 'c95149',
                ],
            ],
        ];

        /** Create a new Spreadsheet Object **/
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        //set default style
        $spreadsheet->getDefaultStyle()->getAlignment()->setWrapText(true); //make text wrap


        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Tanggal');
        $sheet->setCellValue('C1', 'Kode Barang');
        $sheet->setCellValue('D1', 'Nama Barang');
        $sheet->setCellValue('E1', '#Transaksi');
        $sheet->setCellValue('F1', 'Jumlah');
        $sheet->setCellValue('G1', 'Serial Number');

        $no = 2;
        foreach ($log as $key => $value) {
            $sheet->setCellValue('A' . $no, $no);
            $sheet->setCellValue('B' . $no, $value->created_timestamp);
            $sheet->setCellValue('C' . $no, $value->material_code);
            $sheet->setCellValue('D' . $no, $value->material_name);
            $sheet->setCellValue('E' . $no, $value->transaction_number);
            $sheet->setCellValue('F' . $no, FormaterModel::trimTrailingZeroes($value->quantity_delivered));
            $sheet->getStyle('F' . $no)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
            $sheet->setCellValue('G' . $no, $value->serial_number);
            $no++;
        }

        //set width coloumn
        $sheet->getStyle('A1:G1')->applyFromArray($head_style_array); //warnai cell judul
        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getColumnDimension('D')->setWidth(30);
        $sheet->getColumnDimension('E')->setAutoSize(true);
        $sheet->getColumnDimension('F')->setWidth(10);
        $sheet->getColumnDimension('G')->setWidth(30);

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename="log-keluar-fgwh.xlsx"');
        $writer->save("php://output");
    }

    public static function exportExcelLogOutAll($log)
    {
       //styling header excel
        $head_style_array = [
            'font' => [
                'bold' => true,
                'size' => 15,
                'color' => array('rgb' => 'ffffff'),
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
            'borders' => [
                'top' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR,
                'rotation' => 90,
                'startColor' => [
                    'argb' => 'ff4d40',
                ],
                'endColor' => [
                    'argb' => 'c95149',
                ],
            ],
        ];

        /** Create a new Spreadsheet Object **/
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        //set default style
        $spreadsheet->getDefaultStyle()->getAlignment()->setWrapText(true); //make text wrap


        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Tanggal');
        $sheet->setCellValue('C1', 'Kode Barang');
        $sheet->setCellValue('D1', 'Nama Barang');
        $sheet->setCellValue('E1', '#Transaksi');
        $sheet->setCellValue('F1', 'Jumlah Diterima');
        $sheet->setCellValue('G1', 'Jumlah Ditolak');
        $sheet->setCellValue('H1', 'Serial Number');

        $no = 2;
        foreach ($log as $key => $value) {
            $sheet->setCellValue('A' . $no, $no);
            $sheet->setCellValue('B' . $no, $value->created_timestamp);
            $sheet->setCellValue('C' . $no, $value->material_code);
            $sheet->setCellValue('D' . $no, $value->material_name);
            $sheet->setCellValue('E' . $no, $value->transaction_number);
            $sheet->setCellValue('F' . $no, FormaterModel::trimTrailingZeroes($value->quantity_delivered));
            $sheet->getStyle('F' . $no)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
            $sheet->setCellValue('G' . $no, FormaterModel::trimTrailingZeroes($value->quantity_reject));
            $sheet->getStyle('G' . $no)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
            $sheet->setCellValue('H' . $no, $value->serial_number);
            $no++;
        }

        //set width coloumn
        $sheet->getStyle('A1:H1')->applyFromArray($head_style_array); //warnai cell judul
        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getColumnDimension('D')->setWidth(30);
        $sheet->getColumnDimension('E')->setAutoSize(true);
        $sheet->getColumnDimension('F')->setWidth(10);
        $sheet->getColumnDimension('G')->setWidth(10);
        $sheet->getColumnDimension('H')->setWidth(30);

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename="log-keluar-semua-material.xlsx"');
        $writer->save("php://output");
    }

    public function resetBom() {
        $material_code = urldecode(Request::get('material_code'));
        GenericModel::remove('material_list_formulation', 'formulation_code', $material_code, false); //false for silent feedback
        Redirect::to('inventory/editFormulation/?find=' . urlencode($material_code));
    }

    /* +++++ BULK STOCK UPDATE ++++ */

    public function updateStockBulk()
    {
        $sql = "
                SELECT
                    `stock`.`quantity_stock`,
                    `stock`.`transaction_number`,
                    `stock`.`created_timestamp`,
                    `stock`.`uid`,
                    `material_list`.`material_name`, 
                    `material_list`.`material_code`,
                    `material_list`.`unit`
                FROM
                        `material_list`
                    LEFT JOIN
                        (SELECT
                            GROUP_CONCAT(`material_list_in`.`quantity_stock` SEPARATOR '-, -') as `quantity_stock`,
                            GROUP_CONCAT(`material_list_in`.`transaction_number` SEPARATOR '-, -') as `transaction_number`,
                            GROUP_CONCAT(`material_list_in`.`created_timestamp` SEPARATOR '-, -') as `created_timestamp`,
                            GROUP_CONCAT(`material_list_in`.`uid` SEPARATOR '-, -') as `uid`,
                            `material_list_in`.`material_code`
                        FROM
                            `material_list_in`
                        WHERE
                            `material_list_in`.`quantity_stock` > 0
                        GROUP BY
                            `material_list_in`.`material_code`
                            ) AS `stock` ON `stock`.`material_code` = `material_list`.`material_code`
                WHERE
                    `material_list`.`is_deleted` = 0
                ORDER BY
                    `material_list`.`material_name`
                LIMIT
                    $offset, $limit";

        $this->View->renderFileOnly('inventory/update_stock_bulk', array(
                'job_number' => urldecode($job_number),
                'material_list' => GenericModel::rawSelect($sql),

                )
        );
    }

    public function saveUpdateStockBulk() {
        
        $job_number = urldecode(Request::get('job_number'));
        $total_input = (int)Request::post('total_input');

        for ($i = 1; $i <= $total_input; $i++) {
            if (Request::post('consumed_quantity_' . $i) > 0) {
                $material_code = Request::post('material_code_' . $i);
                $consumed_quantity = Request::post('consumed_quantity_' . $i);
                 //get price for material
                $product_price = GenericModel::getOne('purchase_order_list', "`material_code` = '{$material_code}'", '`purchase_price`, `unit`, `purchase_currency`, `purchase_price_discount`, `transaction_number`');

                $price_after_discount = $product_price->purchase_price - $product_price->purchase_price_discount;
                $production_price = $price_after_discount * $consumed_quantity;

                //check usd rate if currency use usd and rate is still empty
                if ($product_price->purchase_currency != 'IDR' AND empty($currency_rate)) {
                    $currency_rate = FormaterModel::currencyRateBI();
                }

                //multiplication with dollar rate
                if ($product_price->purchase_currency != 'IDR') {
                    $production_price = $production_price * (int) $currency_rate[$product_price->purchase_currency]['jual'];
                    $note = Request::post('note_' . $i) . '. Kurs ' . $product_price->purchase_currency . ' = ' . $currency_rate[$product_price->purchase_currency]['jual'];
                } else {
                    $note = Request::post('note_' . $i);
                }

                $insert = array(
                        'uid'    => GenericModel::guid(),
                        'material_code' => Request::post('material_code_' . $i),
                        'quantity_delivered'    => $consumed_quantity,
                        'transaction_number' => $job_number,
                        'material_lot_number' => Request::post('material_lot_number_' . $i),
                        'purchase_price'    => $price_after_discount,
                        'purchase_unit'    => $product_price->unit,
                        'production_price'    => $production_price,
                        'status' => 'jo',
                        'note' => $note,
                        'creator_id'    => SESSION::get('uid'),
                    );

                GenericModel::insert('material_list_out', $insert);

                //update qty stock
                $remaining_stock = ((float) Request::post('stock_' . $i)) - ((float) $consumed_quantity);
                $update = array(
                    'quantity_stock'    => $remaining_stock,
                    'modified_timestamp'    => date("Y-m-d H:i:s"),
                    'modifier_id'    => SESSION::get('uid'),
                    );
                $uid = Request::post('uid_' . $i);
                GenericModel::update('material_list_in', $update, "`uid` = '{$uid}'");
            }
        }

        Redirect::to('JobOrder/detail/?job_number=' . urlencode($job_number));
    }
}
