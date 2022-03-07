<?php

/*
* POS (Point of Sales) aka Kasir
*/
class ProductReturnController extends Controller
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
     * kasir paling sederhana, tanpa bonus, diskon, tanggal dll.
     * cocok untuk toko kelontong sederhana, beli A jual A tanpa ada proses reproduksi/repacking
     */
    public function index()
    {
        $sales_number = urldecode(Request::get('so_number'));
        $sql = "SELECT
                    `sales_order`.`customer_id`,
                    `sales_order`.`so_number`,
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
                    `material_list_out`.`quantity`,
                    `material_list_out`.`quantity_delivered`,
                    `material_list_out`.`selling_price`,
                    `material_list_out`.`so_number`,
                    `material_list_out`.`created_timestamp`,
                    `material_list`.`material_name` as `material_name`,
                    `material_list`.`material_category` as `material_category`
                FROM
                    `sales_order`
                LEFT JOIN
                    `contact` ON `contact`.`contact_id` = `sales_order`.`customer_id`
                LEFT JOIN
                    `material_list_out` ON `material_list_out`.`so_number` = '$sales_number'
                LEFT JOIN
                    `material_list` ON`material_list`.`material_code` = `material_list_out`.`material_code`

                WHERE `sales_order`.`so_number` = '$sales_number' AND `material_list_out`.`status` = 1";


                $product = GenericModel::rawSelect($sql);

        $header_script = '<link rel="stylesheet" href="' . Config::get('URL') . 'bootstrap-3.3.7/css/excel-2007.css" media="screen"/><link rel="stylesheet" href="' . Config::get('URL') . 'bootstrap-3.3.7/css/datepicker.css" media="screen"/>';
        $footer_script = '<script src="' . Config::get('URL') . 'bootstrap-3.3.7/js/bootstrap-datepicker.js"></script>
        <script>
        $(".datepicker").datepicker(); //date picker
        </script>';

        $this->View->render('productreturn/index',
            array(
                'title' => 'Produk Retur',
                'activelink1' => 'transaksi',
                'activelink2' => 'laporan',
                'header_script' => $header_script,
                'footer_script' => $footer_script,
                'product_list' => $product,
            )
        );
    }


    public function saveReturn() {
        //$product_list = "KM100/GOLEH --- 10,000 --- 10 --- 2 --- 4A6B9CC9B6D7844C548D67E74A893F63D87A942234 ---  ___ &sales_code=ST 00005/KSR-1117&customer_id=see1&totalPrice=100000";
        //$product_list   = explode(' ___ ', $product_list);
        $product_list   = explode(' ___ ', Request::post('product_list'));
        $product_list   = array_filter($product_list);
        $sales_code   = urldecode(Request::post('sales_code'));
        $customer_id   = Request::post('customer_id');
        $credit   = FormaterModel::getNumberOnly(Request::post('totalPrice'));

        foreach ($product_list as $key => $value) {
            $product   = explode(' --- ', $value);
            //var_dump($product);
            $material_code = $product[0];
            $product_price = FormaterModel::getNumberOnly($product[1]);
            $product_qty_sold = FormaterModel::getNumberOnly($product[2]);
            $product_qty_return = FormaterModel::getNumberOnly($product[3]);
            $remaining_qty = $product_qty_sold - $product_qty_return;
            $product_uid = $product[4];


            if (!empty($material_code) AND !empty($product_qty_return) AND !empty($product_price)) {
                //Cek Formulation/Repackaging Product
                $query = "SELECT `material_list_formulation`.`percentage_per_quantity`, `material_list_formulation`.`unit_per_quantity`, `material_list_formulation`.`material_code`, `material_list`.`quantity_per_unit` FROM `material_list_formulation` LEFT JOIN `material_list` AS `material_list` ON `material_list`.`material_code` = `material_list_formulation`.`formulation_code` WHERE `formulation_code` = '$material_code'";
                $formulation = GenericModel::rawSelect($query);

                
                if (count($formulation) > 0) { // is repackaging/formulation product
                    foreach ($formulation as $key => $value) {
                        //check apakah pakai persentase atau pake satuan
                        if ($value->percentage_per_quantity > 0 AND $value->unit_per_quantity == 0) {
                            $quantity = ($value->percentage_per_quantity / 100) * $value->quantity_per_unit * $product_qty_return ;
                        } elseif ($value->percentage_per_quantity == 0 AND $value->unit_per_quantity > 0) {
                            $quantity = $value->unit_per_quantity * $product_qty_return ;
                        }
                        $insert = array(
                                'uid'    => GenericModel::guid(),
                                'material_code'    => $value->material_code,
                                'quantity'    => $quantity,
                                'quantity_received'    => $quantity,
                                'po_number'    => $sales_code,
                                'note'    => 'Product Retur',
                                'creator_id'    => SESSION::get('user_name')
                            );
                        QgenericModel::insert('material_list_in', $insert); // untuk menambah stock bahan
                    }
                        // update selling product for log dan print nota penjualan
                        $quantity =  $product_qty_sold - $product_qty_return;
                        $update = array(
                                    'quantity'    => $remaining_qty,
                                    'quantity_delivered'    => $remaining_qty,
                                    'creator_id'    => SESSION::get('user_name')
                                );
                        GenericModel::update('material_list_out', $update, "`uid` = '$product_uid'");

                        //kurangi total penjualan dengan harga barang yang diretur
                        $credit = $credit - ($product_qty_return * $product_price);
                    
                } else { // not formulation priduct
                    // update selling product for log dan print nota penjualan
                    $quantity =  $product_qty_sold - $product_qty_return;
                    $update = array(
                                'quantity'    => $remaining_qty,
                                'quantity_delivered'    => $remaining_qty,
                                'creator_id'    => SESSION::get('user_name')
                            );
                    GenericModel::update('material_list_out', $update, "`uid` = '$product_uid'");

                    //kurangi total penjualan dengan harga barang yang diretur
                    $credit = $credit - ($product_qty_return * $product_price);
                }
            }
        }

        // update total payment
        $update = array(
                        'credit'    => $credit,
                    );
        GenericModel::update('payment_transaction', $update, "`transaction_code` = '$sales_code'", false);

        $feedback_positive = Session::get('feedback_positive');
        $feedback_negative = Session::get('feedback_negative');
        // echo out positive messages
        if (isset($feedback_positive)) {
            echo 'SUKSES, ' . count($feedback_positive) . ' transaksi berhasil disimpan';
        }
        // echo out negative messages
        if (isset($feedback_negative)) {echo 'GAGAL!, ' . count($feedback_positive) . ' transaksi berhasil disimpan';}
        // RESET counter feedback to unconfuse user
        Session::set('feedback_positive', null);
        Session::set('feedback_negative', null);
    }

}
