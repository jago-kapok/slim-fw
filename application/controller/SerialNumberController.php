<?php


class SerialNumberController extends Controller
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

    public function newSerialNumber() {
        $this->View->render('serial_number/new_serial_number',
            array(
                'title' => 'Insert New Serial Number',
                'activelink1' => 'inventory',
                'activelink2' => 'serial number',
                'activelink3' => 'serial number not active',
                'customer_type' => urldecode(Request::get('customer_type')),
                'material_code' => urldecode(Request::get('material_code')),
                'production_number' => urldecode(Request::get('production_number')),
                'sales_number' =>urldecode(Request::get('sales_number')),
                'forward' => Request::get('forward'),
                )
        );
    }

    public function submitSerialNumber() {
        $serial_number = Request::post('serial_number');
        $serial_number_quantity = Request::post('serial_number_quantity');
        $material_code = Request::post('material_code');
        $production_number = Request::post('production_number');
        $sales_number = Request::post('sales_number');
        $customer_type = Request::post('customer_type');
        $status = (!empty($sales_number)) ? 1 : 0;
        if (empty($serial_number)) {
            $sn_counter = 0;
            $serial_number = SerialNumberModel::make($production_number, $customer_type, $serial_number_quantity, $sn_counter);
            $serial_number = explode(',', $serial_number);
        } else {
            $serial_number = explode(',', $serial_number);
        }

        if (!empty($material_code) AND !empty($production_number)) {
            foreach ($serial_number as $key => $value) {
                if (!empty($value)) {
                    $insert = array(
                        'serial_number' => trim($value),
                        'material_code'    => $material_code,
                        'transaction_number' => $sales_number,
                        'status' => $status,
                        'production_number' => $production_number,
                        'is_active' => 1,
                    );
                    GenericModel::insert('serial_number', $insert);
                }
            }
        } else {
            Session::add('feedback_negative', "ERROR!. Material code dan production number harus diisi");
            Redirect::to(Request::get('forward'));
            exit;
        }

        Redirect::to(Request::get('forward'));
    }

    public function active($page = 1, $limit = 100)
    {
        $offset = ($page > 1) ? ($page - 1) * $limit : 0;

        if (isset($_GET['find']) AND $_GET['find'] != '') {
            $name = strtolower(Request::get('find'));
            $name = urldecode($name);
            $table                      = '`serial_number`';
            $where                       = "(`material_code` = '{$name}' OR `serial_number` = '{$name}') AND `is_active` = 1";
            $field                      = '*'; //sementara dulu, sampai array untuk insert sudah final
            $sn = GenericModel::getSome($table, $where, $limit, $offset, $field);

            //Pagination
            $total_record = GenericModel::rowCount($table, $where, '`material_code`');
            $pagination = FormaterModel::pagination('serialNumber/active', $total_record, $page, $limit);
        } else {
            $table                      = '`serial_number`';
            $where                       = '`is_active` = 1 ORDER BY `created_timestamp` DESC';
            $field                      = '*'; //sementara dulu, sampai array untuk insert sudah final
            $sn = GenericModel::getSome($table, $where, $limit, $offset, $field);

            //Pagination
            $total_record = GenericModel::rowCount($table, $where, '`material_code`');
            $pagination = FormaterModel::pagination('serialNumber/active', $total_record, $page, $limit);
        }

        $header_script = '<link rel="stylesheet" href="' . Config::get('URL') . 'bootstrap-3.3.7/css/excel-2007.css" media="screen"/>';
        $this->View->render('serial_number/active',
            array(
                'title' => 'Serial Number List',
                'header_script' => $header_script,
                'activelink1' => 'inventory',
                'activelink2' => 'serial number',
                'activelink3' => 'serial number active',
                'page' => $page,
                'limit' => $limit,
                'pagination' => $pagination,
                'sn' => $sn,
                )
        );
    }

    public function notActive($page = 1, $limit = 100)
    {
        $offset = ($page > 1) ? ($page - 1) * $limit : 0;
        
        if (isset($_GET['find']) AND $_GET['find'] != '') {
            $name = strtolower(Request::get('find'));
            $name = urldecode($name);
            $table                      = '`serial_number`';
            $where                       = "(`material_code` = '{$name}' OR `serial_number` = '{$name}') AND `is_active` = 0";
            $field                      = '*'; //sementara dulu, sampai array untuk insert sudah final
            $sn = GenericModel::getSome($table, $where, $limit, $offset, $field);

            //Pagination
            $total_record = GenericModel::rowCount($table, $where, '`material_code`');
            $pagination = FormaterModel::pagination('serialNumber/notActive', $total_record, $page, $limit);
        } else {
            $table                      = '`serial_number`';
            $where                       = '`is_active` = 0';
            $field                      = '*'; //sementara dulu, sampai array untuk insert sudah final
            $sn = GenericModel::getSome($table, $where, $limit, $offset, $field);

            //Pagination
            $total_record = GenericModel::rowCount($table, $where, '`material_code`');
            $pagination = FormaterModel::pagination('serialNumber/notActive', $total_record, $page, $limit);
        }

        $header_script = '<link rel="stylesheet" href="' . Config::get('URL') . 'bootstrap-3.3.7/css/excel-2007.css" media="screen"/>';
        $this->View->render('serial_number/not_active',
            array(
                'title' => 'Serial Number List',
                'header_script' => $header_script,
                'activelink1' => 'inventory',
                'activelink2' => 'serial number',
                'activelink3' => 'serial number not active',
                'page' => $page,
                'limit' => $limit,
                'pagination' => $pagination,
                'sn' => $sn,
                )
        );
    }

    // Edit record of stock Inventory
    public function detail($serial_number)
    {
        $uploaded_file = "SELECT `item_name`, `item_id`, `value`, `uid`, `note`  FROM `upload_list` WHERE `category` =  'serial-number' AND `item_id` = '{$serial_number}' AND `is_deleted` = 0";
         $sn = "SELECT 
                `serial_number`.*,
                `material_list`.`material_name`
            FROM 
                `serial_number`
            LEFT JOIN
                `material_list` ON `material_list`.`material_code` = `serial_number`.`material_code`
            WHERE
                `serial_number`.`serial_number` =  '{$serial_number}'";
     

        $this->View->render('serial_number/detail',
            array(
                'title' => 'Detail Serial Number ' . $serial_number,
                'activelink1' => 'inventory',
                'activelink2' => 'serial number',
                'uploaded_file' => GenericModel::rawSelect($uploaded_file),
                'sn' => GenericModel::rawSelect($sn, false),
                )
        );
    }

    /**
     * Perform the upload image
     * POST-request
     */
    public function uploadImage($serial_number = null)
    {
        if (empty($serial_number)) {
            Redirect::to('serialNumber/detail/' . $serial_number);
            Session::add('feedback_negative', 'GAGAL!, upload file tidak berhasil');
        }

        $image_name = 'file_name';
        $image_rename = Request::post('image_name');
        $destination = 'serial-number';
        $note = Request::post('note');
        UploadModel::uploadImage($image_name, $image_rename, $destination, $serial_number, $note);
        Redirect::to('serialNumber/detail/' . $serial_number);
    }

     /**
     * Perform the upload pdf, xlsx, doc, docx
     * POST-request
     */
    public function uploadDocument($serial_number = null)
    {
        if (empty($serial_number)) {
            Redirect::to('serialNumber/detail/' . $serial_number);
            Session::add('feedback_negative', 'GAGAL!, upload file tidak berhasil');
        }

        $image_name = 'file_name';
        $image_rename = Request::post('document_name');
        $destination = 'serial-number';
        $note = Request::post('note');
        UploadModel::uploadDocument($image_name, $image_rename, $destination, $serial_number, $note);
        Redirect::to('serialNumber/detail/' . $serial_number);
    }

    public function updateSerialNumber($serial_number)
    {
        $update = array(
                    'material_code' => Request::post('material_code'),
                    'production_number' => Request::post('production_number'),
                    'transaction_number' => Request::post('transaction_number'),
                );
        GenericModel::update('serial_number', $update, "`serial_number` = '{$serial_number}'");
        Redirect::to('serialNumber/detail/' . $serial_number . '/?edit=' . $serial_number);
    }

}