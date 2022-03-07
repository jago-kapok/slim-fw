<?php


class ProductionController extends Controller {

    /**
     * Construct this object by extending the basic Controller class
     */
    public function __construct()
    {
        parent::__construct();

        Auth::checkAuthentication();
        Auth::checkPermission('director,management,production,ppic,qc');
    }

    public function onProcess($page = 1, $limit = 20)
    {
        $offset = ($page > 1) ? ($page - 1) * $limit : 0;
        $sql_group = "
                SELECT
                    `job_order`.`job_number`,
                    `job_order`.`job_reverence`,
                    `job_order`.`job_type`,
                    `job_order_product_list`.`material_code`,
                    GROUP_CONCAT(`material_list`.`material_name` SEPARATOR '-, -') as `material_name`,
                    GROUP_CONCAT(`job_order_product_list`.`quantity` SEPARATOR '-, -') as `quantity`,
                    `users`.`full_name`
                FROM
                    `job_order`
                LEFT JOIN
                    `job_order_product_list` ON `job_order_product_list`.`job_number` = `job_order`.`job_number`
                LEFT JOIN
                    `material_list` AS `material_list` ON  `material_list`.`material_code` = `job_order_product_list`.`material_code`
                LEFT JOIN
                    `users` AS `users` ON `users`.`uid` = `job_order`.`creator_id`
                WHERE
                    `job_order`.`status` < 0 AND `job_category` = 'production order'
                GROUP BY
                     `job_order`.`job_number`
                ORDER BY
                    `job_order`.`created_timestamp` DESC
                LIMIT
                    $offset, $limit";

        $header_script = '<link rel="stylesheet" href="' . Config::get('URL') . 'bootstrap-3.3.7/css/excel-2007.css" media="screen"/>';

        $total_record = GenericModel::rowCount('`job_order`', '`job_order`.`status` < 0', '`job_number`');
        $pagination = FormaterModel::pagination('production/onProcess', $total_record, $page, $limit);

        $this->View->render('job_order/on_process',
            array(
                'header_script' => $header_script,
                'title' => 'Production Order On Process',
                'activelink1' => 'Manufacturing',
                'activelink2' => 'Production',
                'activelink3' => 'Production On Process',
                'on_process_list' => GenericModel::rawSelect($sql_group),
                'page' => $page,
                'limit' => $limit,
                'pagination' => $pagination,
                )
        );
    }

    public function finished($page = 1, $limit = 20)
    {
        $offset = ($page > 1) ? ($page - 1) * $limit : 0;
        $sql_group = "
                SELECT
                    `job_order`.`job_number`,
                    `job_order`.`job_reverence`,
                    `job_order`.`job_type`,
                    `job_order_product_list`.`material_code`,
                    GROUP_CONCAT(`material_list`.`material_name` SEPARATOR '-, -') as `material_name`,
                    GROUP_CONCAT(`job_order_product_list`.`quantity` SEPARATOR '-, -') as `quantity`,
                    `users`.`full_name`
                FROM
                    `job_order`
                LEFT JOIN
                    `job_order_product_list` ON `job_order_product_list`.`job_number` = `job_order`.`job_number`
                LEFT JOIN
                    `material_list` AS `material_list` ON  `material_list`.`material_code` = `job_order_product_list`.`material_code`
                LEFT JOIN
                    `users` AS `users` ON `users`.`uid` = `job_order`.`creator_id`
                WHERE
                    `job_order`.`status` = 100 AND `job_category` = 'production order'
                GROUP BY
                     `job_order`.`job_number`
                ORDER BY
                    `job_order`.`created_timestamp` DESC
                LIMIT
                    $offset, $limit";

        $header_script = '<link rel="stylesheet" href="' . Config::get('URL') . 'bootstrap-3.3.7/css/excel-2007.css" media="screen"/>';

        $total_record = GenericModel::rowCount('`job_order`', '`job_order`.`status` = 100', '`job_number`');
        $pagination = FormaterModel::pagination('production/finished', $total_record, $page, $limit);
        $this->View->render('job_order/finished',
            array(
                'header_script' => $header_script,
                'title' => 'Production Order On Process',
                'activelink1' => 'Manufacturing',
                'activelink2' => 'Production',
                'activelink3' => 'Production Finished',
                'page' => $page,
                'limit' => $limit,
                'on_process_list' => GenericModel::rawSelect($sql_group),
                'pagination' => $pagination,
                )
        );
    }
}