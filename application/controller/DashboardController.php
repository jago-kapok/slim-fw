<?php

/**
 * This controller shows an area that's only visible for logged in users (because of Auth::checkAuthentication(); in line 16)
 */
class DashboardController extends Controller
{
    /**
     * Construct this object by extending the basic Controller class
     */
    public function __construct()
    {
        parent::__construct();

        // this entire controller should only be visible/usable by logged in users, so we put authentication-check here
        Auth::checkAuthentication();
        Auth::checkPermission('director', 900);
    }

    public function sales($start_date = null, $end_date = null)
    {
        if (Request::post('change_date') == 'ok') { // user input change date
            Redirect::to('dashboard/sales/' . Request::post('start_date') . '/' . Request::post('end_date'));
            exit;
        }

        $header_script = '<link rel="stylesheet" href="' . Config::get('URL') . 'bootstrap-3.3.7/css/datepicker.css" media="screen"/>';
        $footer_script = '<script src="' . Config::get('URL') . 'bootstrap-3.3.7/js/bootstrap-datepicker.js"></script>
        <script>
        $(".datepicker").datepicker(); //date picker
        </script>';

        // if null given, show today transaction
        if ($start_date == null AND $end_date == null) {
            $this_year = date('Y-01-01');
            $value_penjualan_per_month = "
                SELECT
                    SUM(`sales_order_list`.`quantity` * `sales_order_list`.`selling_price`) as `selling_price`,
                    SUM(`sales_order_list`.`selling_price_bulk`) as `selling_price_bulk`,
                    MONTH(`sales_order_list`.`created_timestamp`) as `month`
                FROM 
                    `sales_order_list`
                LEFT JOIN
                    `sales_order`
                ON
                    `sales_order`.`transaction_number` = `sales_order_list`.`transaction_number`
                WHERE 
                    `sales_order`.`status` >= 0 AND (`sales_order_list`.`created_timestamp` >= '$this_year 23:59:59')
                GROUP BY
                    `month`
                ORDER BY
                    `month` ASC";

            $penjualan_per_product = "
            SELECT
                `sales_order_list`.`material_code`,
                `material_list`.`material_name`,
                SUM(`sales_order_list`.`quantity`) as `quantity`,
                SUM(`sales_order_list`.`quantity` * `sales_order_list`.`selling_price`) as `selling_price`,
                SUM(`sales_order_list`.`selling_price_bulk`) as `selling_price_bulk`
            FROM 
                `sales_order_list`
            JOIN
                `material_list`
                ON
                `material_list`.`material_code` = `sales_order_list`.`material_code`
            JOIN
                `sales_order`
                ON
                `sales_order`.`transaction_number` = `sales_order_list`.`transaction_number`
            WHERE 
                `sales_order`.`status` >= 0 AND YEAR(`sales_order_list`.`created_timestamp`) = YEAR(CURDATE()) 
            GROUP BY
                `sales_order_list`.`material_code`";

            $value_penjualan_per_category = "
            SELECT
                `material_list`.`material_category`,
                SUM(`sales_order_list`.`quantity` * `sales_order_list`.`selling_price`) as `selling_price`,
                SUM(`sales_order_list`.`selling_price_bulk`) as `selling_price_bulk`
            FROM 
                `sales_order_list`
            JOIN
                `material_list`
                ON
                `material_list`.`material_code` = `sales_order_list`.`material_code`
            JOIN
                `sales_order`
                ON
                `sales_order`.`transaction_number` = `sales_order_list`.`transaction_number`
            WHERE
                `sales_order`.`status` >= 0 AND  YEAR(`sales_order_list`.`created_timestamp`) = YEAR(CURDATE())
            GROUP BY
                `material_list`.`material_category`";

            $penjualan_per_customer = "
            SELECT
                `contact`.`contact_name`,
                SUM(`sales_order_list`.`quantity`) as `quantity`,
                SUM(`sales_order_list`.`quantity` * `sales_order_list`.`selling_price`) as `selling_price`,
                SUM(`sales_order_list`.`selling_price_bulk`) as `selling_price_bulk`
            FROM 
                `contact`
            JOIN
                `sales_order` ON `sales_order`.`customer_id` = `contact`.`contact_id`
            JOIN
                `sales_order_list` ON `sales_order_list`.`transaction_number` = `sales_order`.`transaction_number`
            WHERE
                `sales_order`.`status` >= 0 AND YEAR(`sales_order_list`.`created_timestamp`) = YEAR(CURDATE())
            GROUP BY
                `contact`.`contact_id`";

            $sales_user = "
            SELECT
                SUM(`sales_order_list`.`quantity` * `sales_order_list`.`selling_price`) as `selling_price`,
                SUM(`sales_order_list`.`selling_price_bulk`) as `selling_price_bulk`,
                `users`.`full_name`
            FROM 
                `sales_order_list`
            JOIN
                `users`
                ON
                `sales_order_list`.`creator_id` = `users`.`uid`
            JOIN
                `sales_order`
                ON
                `sales_order`.`transaction_number` = `sales_order_list`.`transaction_number`
            WHERE
                `sales_order`.`status` >= 0 AND
                YEAR(`sales_order_list`.`created_timestamp`) = YEAR(CURDATE())
            GROUP BY
                `sales_order_list`.`creator_id`";

        $title = 'Per Tahun ini';

        } else {
            $value_penjualan_per_month = "
                SELECT
                    SUM(`sales_order_list`.`quantity` * `sales_order_list`.`selling_price`) as `selling_price`,
                    SUM(`sales_order_list`.`selling_price_bulk`) as `selling_price_bulk`,
                    MONTH(`sales_order_list`.`created_timestamp`) as `month`
                FROM 
                    `sales_order_list`
                LEFT JOIN
                    `sales_order`
                ON
                    `sales_order`.`transaction_number` = `sales_order_list`.`transaction_number`
                WHERE 
                    `sales_order`.`status` >= 0 AND (`sales_order_list`.`created_timestamp` BETWEEN '$start_date 00:00:00' AND '$end_date 23:59:59')
                GROUP BY
                    `month`
                ORDER BY
                    `month` ASC";

            $penjualan_per_product = "
            SELECT
                `sales_order_list`.`material_code`,
                `material_list`.`material_name`,
                SUM(`sales_order_list`.`quantity`) as `quantity`,
                SUM(`sales_order_list`.`quantity` * `sales_order_list`.`selling_price`) as `selling_price`,
                SUM(`sales_order_list`.`selling_price_bulk`) as `selling_price_bulk`
            FROM 
                `sales_order_list`
            JOIN
                `material_list`
                ON
                `material_list`.`material_code` = `sales_order_list`.`material_code`
            JOIN
                `sales_order`
                ON
                `sales_order`.`transaction_number` = `sales_order_list`.`transaction_number`
            WHERE 
                `sales_order`.`status` >= 0 AND (`sales_order_list`.`created_timestamp` BETWEEN '$start_date 00:00:00' AND '$end_date 23:59:59') 
            GROUP BY
                `sales_order_list`.`material_code`";

            $value_penjualan_per_category = "
            SELECT
                `material_list`.`material_category`,
                SUM(`sales_order_list`.`quantity` * `sales_order_list`.`selling_price`) as `selling_price`,
                SUM(`sales_order_list`.`selling_price_bulk`) as `selling_price_bulk`
            FROM 
                `sales_order_list`
            JOIN
                `material_list`
                ON
                `material_list`.`material_code` = `sales_order_list`.`material_code`
            JOIN
                `sales_order`
                ON
                `sales_order`.`transaction_number` = `sales_order_list`.`transaction_number`
            WHERE
                `sales_order`.`status` >= 0 AND  (`sales_order_list`.`created_timestamp` BETWEEN '$start_date 00:00:00' AND '$end_date 23:59:59')
            GROUP BY
                `material_list`.`material_category`";

            $penjualan_per_customer = "
            SELECT
                `contact`.`contact_name`,
                SUM(`sales_order_list`.`quantity`) as `quantity`,
                SUM(`sales_order_list`.`quantity` * `sales_order_list`.`selling_price`) as `selling_price`,
                SUM(`sales_order_list`.`selling_price_bulk`) as `selling_price_bulk`
            FROM 
                `contact`
            JOIN
                `sales_order` ON `sales_order`.`customer_id` = `contact`.`contact_id`
            JOIN
                `sales_order_list` ON `sales_order_list`.`transaction_number` = `sales_order`.`transaction_number`
            WHERE
                `sales_order`.`status` >= 0 AND (`sales_order_list`.`created_timestamp` BETWEEN '$start_date 00:00:00' AND '$end_date 23:59:59')
            GROUP BY
                `contact`.`contact_id`";

            $sales_user = "
            SELECT
                SUM(`sales_order_list`.`quantity` * `sales_order_list`.`selling_price`) as `selling_price`,
                SUM(`sales_order_list`.`selling_price_bulk`) as `selling_price_bulk`,
                `users`.`full_name`
            FROM 
                `sales_order_list`
            JOIN
                `users`
                ON
                `sales_order_list`.`creator_id` = `users`.`uid`
            JOIN
                `sales_order`
                ON
                `sales_order`.`transaction_number` = `sales_order_list`.`transaction_number`
            WHERE
                `sales_order`.`status` >= 0 AND
                (`sales_order_list`.`created_timestamp` BETWEEN '$start_date 00:00:00' AND '$end_date 23:59:59')
            GROUP BY
                `sales_order_list`.`creator_id`";

        $title = 'Per Tanggal ' . date("d F Y", strtotime($start_date)) . ' - ' . date("d F Y", strtotime($end_date));
            
        }

        $this->View->render('dashboard/sales',
            array(
            'title' => $title,
            'activelink1' => 'dashboard',
            'activelink1' => 'dashboard penjualan',
            'header_script' => $header_script,
            'footer_script' => $footer_script,
            'penjualan_per_product' => GenericModel::rawSelect($penjualan_per_product),
            'value_penjualan_per_category' => GenericModel::rawSelect($value_penjualan_per_category),
            'penjualan_per_customer' => GenericModel::rawSelect($penjualan_per_customer),
            'sales_user' => GenericModel::rawSelect($sales_user),
            'value_penjualan_per_month' => GenericModel::rawSelect($value_penjualan_per_month),
            )
        );
    }

    public function purchase($start_date = null, $end_date = null)
    {
        if (Request::post('change_date') == 'ok') { // user input change date
            Redirect::to('dashboard/purchase/' . Request::post('start_date') . '/' . Request::post('end_date'));
            exit;
        }


        $header_script = '<link rel="stylesheet" href="' . Config::get('URL') . 'bootstrap-3.3.7/css/datepicker.css" media="screen"/>';
        $footer_script = '<script src="' . Config::get('URL') . 'bootstrap-3.3.7/js/bootstrap-datepicker.js"></script>
        <script>
        $(".datepicker").datepicker(); //date picker
        </script>';

        // if null given, show today transaction
        if ($start_date == null AND $end_date == null) {
            $this_year = date('Y-01-01');
            $value_pembelian_per_month = "
                SELECT
                    SUM(CASE WHEN
                        `purchase_order_list`.`purchase_currency` != 'IDR'
                            THEN
                                (`purchase_order_list`.`quantity` * (`purchase_order_list`.`purchase_price` - `purchase_order_list`.`purchase_price_discount`) * `purchase_order_list`.`purchase_currency_rate`)
                            ELSE
                                (`purchase_order_list`.`quantity` * (`purchase_order_list`.`purchase_price` - `purchase_order_list`.`purchase_price_discount`))
                            END
                    ) as `purchase_order`,
                    `purchase_order_list`.`purchase_currency`,
                    MONTH(`purchase_order_list`.`created_timestamp`) as `month`
                FROM 
                    `purchase_order_list`
                JOIN
                    `purchase_order`
                    ON
                    `purchase_order`.`transaction_number` = `purchase_order_list`.`transaction_number`
                WHERE 
                    `purchase_order`.`status` >= 0 AND (`purchase_order_list`.`created_timestamp` >= '$this_year 23:59:59')
                GROUP BY
                    `month`
                ORDER BY
                    `month` ASC";

            $value_pembelian_per_product = "
                SELECT
                    `purchase_order_list`.`material_code`,
                    `material_list`.`material_name`,
                    SUM(CASE WHEN
                        `purchase_order_list`.`purchase_currency` = 'usd'
                            THEN
                                (`purchase_order_list`.`quantity` * (`purchase_order_list`.`purchase_price` - `purchase_order_list`.`purchase_price_discount`) * `purchase_order_list`.`purchase_currency_rate`)
                            ELSE
                                (`purchase_order_list`.`quantity` * (`purchase_order_list`.`purchase_price` - `purchase_order_list`.`purchase_price_discount`))
                            END
                    ) as `purchase_price`,
                    `purchase_order_list`.`purchase_currency`
                FROM 
                    `purchase_order_list`
                JOIN
                    `material_list`
                    ON
                    `material_list`.`material_code` = `purchase_order_list`.`material_code`
                JOIN
                    `purchase_order`
                    ON
                    `purchase_order`.`transaction_number` = `purchase_order_list`.`transaction_number`
                WHERE 
                    `purchase_order`.`status` >= 0 AND (`purchase_order_list`.`created_timestamp` >= '$this_year 23:59:59')
                GROUP BY
                    `purchase_order_list`.`material_code`
                ORDER BY
                    `purchase_price` DESC
                LIMIT 20";

            $value_pembelian_per_supplier = "
                SELECT
                    `contact`.`contact_name`,
                    SUM(CASE WHEN
                        `purchase_order_list`.`purchase_currency` = 'usd'
                            THEN
                                (`purchase_order_list`.`quantity` * (`purchase_order_list`.`purchase_price` - `purchase_order_list`.`purchase_price_discount`) * `purchase_order_list`.`purchase_currency_rate`)
                            ELSE
                                (`purchase_order_list`.`quantity` * (`purchase_order_list`.`purchase_price` - `purchase_order_list`.`purchase_price_discount`))
                            END
                    ) as `purchase_price`,
                    `purchase_order_list`.`purchase_currency`
                FROM 
                    `purchase_order_list`
                JOIN
                    `purchase_order` ON `purchase_order`.`transaction_number` = `purchase_order_list`.`transaction_number`
                JOIN
                    `contact` ON `contact`.`contact_id` = `purchase_order`.`supplier_id`
                WHERE
                    `purchase_order`.`status` >= 0 AND (`purchase_order_list`.`created_timestamp` >= '$this_year 23:59:59')
                GROUP BY
                    `contact`.`contact_id`
                ORDER BY
                    `purchase_price` DESC
                LIMIT 10";

            $title = 'Per Tahun ini';
        } else {
            $value_pembelian_per_month = "
                SELECT
                    SUM(CASE WHEN
                        `purchase_order_list`.`purchase_currency` = 'usd'
                            THEN
                                (`purchase_order_list`.`quantity` * (`purchase_order_list`.`purchase_price` - `purchase_order_list`.`purchase_price_discount`) * `purchase_order_list`.`purchase_currency_rate`)
                            ELSE
                                (`purchase_order_list`.`quantity` * (`purchase_order_list`.`purchase_price` - `purchase_order_list`.`purchase_price_discount`))
                            END
                    ) as `purchase_order`,
                    `purchase_order_list`.`purchase_currency`,
                    MONTH(`purchase_order_list`.`created_timestamp`) as `month`
                FROM 
                    `purchase_order_list`
                JOIN
                    `purchase_order`
                    ON
                    `purchase_order`.`transaction_number` = `purchase_order_list`.`transaction_number`
                WHERE 
                    `purchase_order`.`status` >= 0 AND (`purchase_order_list`.`created_timestamp` BETWEEN '$start_date 00:00:00' AND '$end_date 23:59:59')
                GROUP BY
                    `month`
                ORDER BY
                    `month` ASC";

            $value_pembelian_per_product = "
                SELECT
                    `purchase_order_list`.`material_code`,
                    `material_list`.`material_name`,
                    SUM(CASE WHEN
                        `purchase_order_list`.`purchase_currency` = 'usd'
                            THEN
                                (`purchase_order_list`.`quantity` * (`purchase_order_list`.`purchase_price` - `purchase_order_list`.`purchase_price_discount`) * `purchase_order_list`.`purchase_currency_rate`)
                            ELSE
                                (`purchase_order_list`.`quantity` * (`purchase_order_list`.`purchase_price` - `purchase_order_list`.`purchase_price_discount`))
                            END
                    ) as `purchase_price`,
                    `purchase_order_list`.`purchase_currency`
                FROM 
                    `purchase_order_list`
                JOIN
                    `material_list`
                    ON
                    `material_list`.`material_code` = `purchase_order_list`.`material_code`
                JOIN
                    `purchase_order`
                    ON
                    `purchase_order`.`transaction_number` = `purchase_order_list`.`transaction_number`
                WHERE 
                    `purchase_order`.`status` >= 0 AND (`purchase_order_list`.`created_timestamp` BETWEEN '$start_date 00:00:00' AND '$end_date 23:59:59')
                GROUP BY
                    `purchase_order_list`.`material_code`
                ORDER BY
                    `purchase_price` DESC
                LIMIT 20";

            $value_pembelian_per_supplier = "
                SELECT
                    `contact`.`contact_name`,
                    SUM(CASE WHEN
                        `purchase_order_list`.`purchase_currency` = 'usd'
                            THEN
                                (`purchase_order_list`.`quantity` * (`purchase_order_list`.`purchase_price` - `purchase_order_list`.`purchase_price_discount`) * `purchase_order_list`.`purchase_currency_rate`)
                            ELSE
                                (`purchase_order_list`.`quantity` * (`purchase_order_list`.`purchase_price` - `purchase_order_list`.`purchase_price_discount`))
                            END
                    ) as `purchase_price`,
                    `purchase_order_list`.`purchase_currency`
                FROM 
                    `purchase_order_list`
                JOIN
                    `purchase_order` ON `purchase_order`.`transaction_number` = `purchase_order_list`.`transaction_number`
                JOIN
                    `contact` ON `contact`.`contact_id` = `purchase_order`.`supplier_id`
                WHERE
                    `purchase_order`.`status` >= 0 AND (`purchase_order_list`.`created_timestamp` BETWEEN '$start_date 00:00:00' AND '$end_date 23:59:59')
                GROUP BY
                    `contact`.`contact_id`
                ORDER BY
                    `purchase_price` DESC
                LIMIT 10";

            $title = 'Per tanggal ' . date("d F Y", strtotime($start_date)) . ' - ' . date("d F Y", strtotime($end_date));
        }

        $this->View->render('dashboard/purchase',
            array(
            'title' => $title,
            'activelink1' => 'dashboard',
            'activelink1' => 'dashboard penjualan',
            'header_script' => $header_script,
            'footer_script' => $footer_script,
            
            'value_pembelian_per_month' => GenericModel::rawSelect($value_pembelian_per_month),
            'value_pembelian_per_product' => GenericModel::rawSelect($value_pembelian_per_product),
            'value_pembelian_per_supplier' => GenericModel::rawSelect($value_pembelian_per_supplier),
            )
        );
    }

}
