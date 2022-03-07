<?php


class MaterialTransferController extends Controller
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
        if(isset($_GET['find']) AND $_GET['find'] != '') {
            $offset = ($page > 1) ? ($page - 1) * $limit : 0;
            // START explode search string
            $find = strtolower(Request::get('find')); //lower case string to easily (case 

            $sql = "SELECT
                        *
                    FROM
                        `material_list`
                    WHERE
                        `transaction_number` LIKE '%$find%'
                    LIMIT
                        $offset, $limit";

            $result = GenericModel::rawSelect($sql);

            //Pagination
            $total_record = GenericModel::totalRow('`material_list`','`material_code`');
            $search_string = '&find=' . $find;
            $pagination = FormaterModel::pagination('MaterialTransfer/index', $total_record, $page, $limit,$search_string);

        } else { // To show all Product
            $offset = ($page > 1) ? ($page - 1) * $limit : 0;
            $sql = "
                SELECT
                    *
                FROM
                    `material_transfer`
                LIMIT
                    $offset, $limit";
            $result = GenericModel::rawSelect($sql);

            //Pagination
            $total_record = GenericModel::totalRow('`material_transfer`','*');
            $pagination = FormaterModel::pagination('MaterialTransfer/index', $total_record, $page, $limit);
        }

        $header_script = '<link rel="stylesheet" href="' . Config::get('URL') . 'bootstrap-3.3.7/css/excel-2007.css" media="screen"/>';
        $this->View->render('material_transfer/index',
                array(
                'header_script' => $header_script,
                'title' => 'Material Transfer',
                'activelink1' => 'inventory',
                'activelink2' => 'material transfer',
                'page' => $page,
                'limit' => $limit,
                'total_record' => $total_record,
                'result' => $result,
                'pagination' => $pagination
                )
            );
    }

        public function detail()
    {
            $transaction_number = urldecode(Request::get('transaction_number')); //lower case string to easily (case 

            $sql = "SELECT
                        `material_list_out`.`transaction_number`,
                        `material_list_out`.`quantity_delivered`,
                        `material_list_out`.`material_code`,
                        `material_list`.`material_name`,
                        `material_list`.`unit`,
                        `material_transfer`.`note`
                    FROM
                        `material_transfer`
                    LEFT JOIN
                        `material_list_out` ON `material_list_out`.`transaction_number` = `material_transfer`.`transaction_number`
                    LEFT JOIN
                        `material_list` ON `material_list`.`material_code` = `material_list_out`.`material_code`
                    WHERE
                        `material_transfer`.`transaction_number` = '{$transaction_number}'";

            $result = GenericModel::rawSelect($sql);

        $header_script = '<link rel="stylesheet" href="' . Config::get('URL') . 'bootstrap-3.3.7/css/excel-2007.css" media="screen"/>';
        $this->View->render('material_transfer/detail',
                array(
                'header_script' => $header_script,
                'title' => 'Material Transfer',
                'activelink1' => 'inventory',
                'activelink2' => 'material transfer',
                'result' => $result,
                )
            );
    }

    public function warehouseToWarehouse()
    {
        $this->View->renderFileOnly('material_transfer/warehouse_to_warehouse', array(
                'material_list' => GenericModel::getAll('material_list', "`is_deleted` = 0 AND (`material_type` = 0 OR `material_type` = 1 OR `material_type` = 2 OR `material_type` = 4 OR `material_type` = 6 OR `material_type` = 7)", "`material_code`,`material_name`,`unit`")

                )
        );
    }

    public function saveWarehouseToWarehouse() {
        //1. Make sales number
        $awal_tahun = date('Y-01-01');
        $table = '`material_transfer`';
        $where = "`created_timestamp` >= '$awal_tahun' ORDER BY `created_timestamp` DESC";
        $field = "`transaction_number`";
        $last_material_transfer = GenericModel::getOne($table, $where, $field);
        $transaction_number = $last_material_transfer->transaction_number;
        $find_integer = explode('/', $transaction_number);
        $transaction_number = $find_integer[0];
        $transaction_number = FormaterModel::getNumberOnly($transaction_number);
        $transaction_number = $transaction_number + 1;
        $transaction_number = "00000".$transaction_number;
        $transaction_number = substr($transaction_number, strlen($transaction_number)-5, 5);
        $transaction_number = Config::get('COMPANY_CODE') . ' ' . $transaction_number . '/MT-' . date("my");

        //echo $_POST['total_input'];exit;
        for ($i=1; $i <= (int) $_POST['total_input']; $i++) { 
         
            //echo '<pre>';var_dump($material);echo '</pre>';
            $material_code = Request::post('material_code_' . $i);
            $quantity_transfer = Request::post('quantity_transfer_' . $i);
            $note = Request::post('note');
            if (!empty($material_code) AND $quantity_transfer > 0) {
                //Select Detail Stock Lot Number Tiap Material
                $lot_stock = "
                    SELECT
                        `uid`,
                        `quantity_stock`,
                        `transaction_number`
                    FROM
                        `material_list_in`
                    WHERE
                        `material_code` = '{$material_code}' AND `quantity_stock` > 0
                    ORDER BY
                        `created_timestamp` ASC";
                $lot_stock_result = GenericModel::rawSelect($lot_stock);

                foreach ($lot_stock_result as $key_stock => $value_stock) {
                    //check apakah yang dikonsumsi lebih kecil dari stock di lot tertentu
                    if ($quantity_transfer <= $value_stock->quantity_stock ) {

                        $insert = array(
                            'uid'    => GenericModel::guid(),
                            'material_code' => $material_code,
                            'quantity_delivered'    => $quantity_transfer,
                            'transaction_number' => $transaction_number,
                            'material_lot_number' => $value_stock->transaction_number,
                            'purchase_price'    => $price_after_discount,
                            'purchase_unit'    => $product_price->unit,
                            'production_price'    => $production_price,
                            'status' => 'jo',
                            'creator_id'    => SESSION::get('uid'),
                        );

                        GenericModel::insert('material_list_out', $insert);

                        //update qty stock
                        $update = array(
                            'quantity_stock'    => $value_stock->quantity_stock - $quantity_transfer,
                            'modified_timestamp'    => date("Y-m-d H:i:s"),
                            'modifier_id'    => SESSION::get('uid'),
                            );
                        GenericModel::update('material_list_in', $update, "`uid` = '{$value_stock->uid}'", false); //silent message

                        //Reset $quantity_transfer menjadi 0
                        $quantity_transfer = 0;
                        break; //STOP FOEACH LOOPING!. Important!
                    
                    } else { //yang dikonsumsi lebih besar dari lot tertentu, maka dikurangkan dari tiap lot sampai nol baru ke lot berikutnya

                        $insert = array(
                            'uid'    => GenericModel::guid(),
                            'material_code' => $material_code,
                            'quantity_delivered'    => $value_stock->quantity_stock,
                            'transaction_number' => $transaction_number,
                            'material_lot_number' => $value_stock->transaction_number,
                            'purchase_price'    => $price_after_discount,
                            'purchase_unit'    => $product_price->unit,
                            'production_price'    => $production_price,
                            'status' => 'jo',
                            'creator_id'    => SESSION::get('uid'),
                        );

                        GenericModel::insert('material_list_out', $insert);


                        $update = array(
                            'quantity_stock'    => 0,
                            'modified_timestamp'    => date("Y-m-d H:i:s"),
                            'modifier_id'    => SESSION::get('uid'),
                            );
                        GenericModel::update('material_list_in', $update, "`uid` = '{$value_stock->uid}'", false); //silent message
                        //kurangi konsumsi kuantity sesuai jumlah stock lot yand diapaki
                        $quantity_transfer = $quantity_transfer - $value_stock->quantity_stock;
                    }
                } //end foreach lot number
            } //end if not empty material code and consumed quantity
        }

        $insert = array(
                        'transaction_number'    => $transaction_number,
                        'transaction_channel' => 'material transfer',
                        'origin_location' => Request::post('origin_location'),
                        'destination_location' => Request::post('destination_location'),
                        'note' => Request::post('note'),
                        
                        'creator_id'    => SESSION::get('uid')
                );
        // Send Status insert to front end
        GenericModel::insert('material_transfer', $insert, false); // use silent message
        Redirect::to('MaterialTransfer/detail/?transaction_number=' . urlencode($transaction_number));
    }
}