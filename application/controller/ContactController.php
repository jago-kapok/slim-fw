<?php


class ContactController extends Controller
{

    /**
     * Construct this object by extending the basic Controller class
     */
    public function __construct()
    {
        parent::__construct();

        // VERY IMPORTANT: All controllers/areas that should only be usable by logged-in users
        // need this line! Otherwise not-logged in users could do actions. If all of your pages should only
        // be usable by logged-in users: Put this line into libs/Controller->__construct
        //Auth::checkAuthentication();
    }


    /**
     * This method controls what happens when you move to /note/index in your app.
     * Gets all notes (of the user).
     */
    public function index($page = 1, $limit = 20)
    {
        $offset = ($page > 1) ? ($page - 1) * $limit : 0;

        if(isset($_GET['new_contact'])){
            // Search query
            $find = strtolower(Request::get('new_contact')); //lower case string to easily (case insensitive) remove
            $sql = "SELECT
                    `contact`.`contact_id`,
                    `contact`.`contact_name`,
                    CONCAT(`contact`.`address_street`, '. ', `contact`.`address_city`,  '. ', `contact`.`address_state`) AS `address`,
                    MATCH (`contact`.`contact_id`, `contact`.`contact_name`) AGAINST ('{$find}') as score
            FROM `contact`
            WHERE MATCH (`contact`.`contact_id`, `contact`.`contact_name`) AGAINST ('{$find}' IN NATURAL LANGUAGE MODE)
            ORDER BY score DESC LIMIT $offset, $limit";

            $contact = GenericModel::rawSelect($sql);
            $title = "Buat Kontak Baru";

            //For pagination
            $total_record = GenericModel::totalRow('`contact`','`contact_id`');
        } else  {
            $field = '`contact`.`contact_id`,
                    `contact`.`contact_name`,
                    CONCAT(`contact`.`address_street`, ". ", `contact`.`address_city`,  ". ", `contact`.`address_state`) AS `address`';
            $table = '`contact`';
            $where = "`contact`.`is_deleted` = 0 ";
            $title = 'Contact List';
            $contact = GenericModel::getSome($table, $where, $limit, $page, $field);

            //For pagination
            $total_record = GenericModel::totalRow('`contact`','`contact_id`');
        }

        $header_script = '<link rel="stylesheet" href="' . Config::get('URL') . 'bootstrap-3.3.7/css/excel-2007.css" media="screen"/>';
            $this->View->render(
                'contact/index',
                  array(
                'header_script' => $header_script,
                'title' => $title,
                'page' => $page,
                'limit' => $limit,
                'activelink1' => 'contact',
                'contact' => $contact,
                'pagination' => FormaterModel::pagination('contact/index', $total_record, $page, $limit)
                )
            );
    }

    public function find($page = 1, $limit = 50)
    {
        $find = strtolower(Request::get('find'));
        $offset = ($page > 1) ? ($page - 1) * $limit : 0;
        
        $sql = "SELECT
                    `contact`.`contact_id`,
                    `contact`.`contact_name`,
                    CONCAT(`contact`.`address_street`, '. ', `contact`.`address_city`,  '. ', `contact`.`address_state`) AS `address`,
                    MATCH (`contact`.`contact_id`, `contact`.`contact_name`) AGAINST ('{$find}') as score
        FROM `contact`
        WHERE MATCH (`contact`.`contact_id`, `contact`.`contact_name`) AGAINST ('{$find}' IN NATURAL LANGUAGE MODE)
        ORDER BY score DESC LIMIT $offset, $limit";

        //Pagination
        $total_record = GenericModel::totalRow('`contact`','`contact_id`');
        $search_string = '&find=' . $find;
        $pagination = FormaterModel::pagination('contact/find', $total_record, $page, $limit,$search_string);

        $header_script = '<link rel="stylesheet" href="' . Config::get('URL') . 'bootstrap-3.3.7/css/excel-2007.css" media="screen"/>';
            $this->View->render(
                'contact/index',
                  array(
                'header_script' => $header_script,
                'title' => "Pencarian Kontak Yang Mirip Dengan $find",
                'page' => $page,
                'limit' => $limit,
                'activelink1' => 'contact',
                'contact' => GenericModel::rawSelect($sql),
                'pagination' => $pagination
                )
            );
    }

    public function selectContact($page = 1, $limit = 50)
    {
        if (isset($_GET['find'])) {
            // Search query
            $find = strtolower(Request::get('find')); //lower case string to easily (case insensitive) remove unwanted characters
            $find_characters = array('.', '-', ',', 'pt', 'inc', 'tbk', 'cv', 'ud');
            $find=str_replace($find_characters, '', $find);
            $prev = Config::get('URL') . 'contact/selectContact/' . ($page - 1) . '/' . $limit . '/?id=' . Request::get('id') . '&find=' . $find;
            $next = Config::get('URL') . 'contact/selectContact/' . ($page + 1) . '/' . $limit . '/?id=' . Request::get('id') . '&find=' . $find;

            $offset = ($page > 1) ? ($page - 1) * $limit : 0;
            $sql = "SELECT
                        `contact`.`contact_id`,
                        `contact`.`contact_name`,
                        CONCAT(`contact`.`address_street`, '. ', `contact`.`address_city`,  '. ', `contact`.`address_state`) AS `address`,
                        MATCH (`contact`.`contact_id`, `contact`.`contact_name`) AGAINST ('{$find}') as score
            FROM `contact`
            WHERE MATCH (`contact`.`contact_id`, `contact`.`contact_name`) AGAINST ('{$find}' IN NATURAL LANGUAGE MODE)
            ORDER BY score DESC LIMIT $offset, $limit";

            //Pagination
            $total_record = GenericModel::totalRow('`contact`','`contact_id`');
            $search_string = '&find=' . $find;
            $pagination = FormaterModel::pagination('contact/selectContact', $total_record, $page, $limit,$search_string);
                        
            $this->View->renderFileOnly('contact/select_contact',
                    array(
                    'title' => 'Select Contact',
                    'prev' => $prev,
                    'next' => $next,
                    'limit' => $limit,
                    'page' => $page,
                    'contact' => GenericModel::rawSelect($sql)
            ));
        } else {
            $this->View->renderFileOnly('contact/select_contact',
              array(
                'title' => 'Select Contact',
            ));
        }
    }

    public function detail($contact_id)
    {
        $sql = "SELECT `contact`.*  FROM `contact` WHERE `contact`.`contact_id` = '$contact_id' AND `contact`.`is_deleted` = 0 LIMIT 1";

        $sql_purchased_item = "
            SELECT
                SUM(`purchase_order_list`.`quantity`) as `quantity`,
                `purchase_order_list`.`material_code`,
                `material_list`.`material_name`,
                `material_list`.`unit`
            FROM
                `purchase_order`
            JOIN
                `purchase_order_list` ON `purchase_order_list`.`transaction_number` = `purchase_order`.`transaction_number`
            JOIN
                `material_list` ON `material_list`.`material_code` = `purchase_order_list`.`material_code`
            WHERE
                `purchase_order`.`supplier_id` = '{$contact_id}' AND `purchase_order`.`status` >= 0
            GROUP BY
                `purchase_order_list`.`material_code`
            ORDER BY
                `quantity` DESC";

        $sql_selling_item = "
            SELECT
                SUM(`sales_order_list`.`quantity`) as `total_purchased`,
                `sales_order_list`.`material_code`,
                `material_list`.`material_name`,
                `material_list`.`unit`
            FROM
                `sales_order`
            JOIN
                `sales_order_list` ON `sales_order_list`.`transaction_number` = `sales_order`.`transaction_number`
            JOIN
                `material_list` ON `material_list`.`material_code` = `sales_order_list`.`material_code`
            WHERE
                `sales_order`.`customer_id` = '{$contact_id}' AND `sales_order`.`status` >= 0
            GROUP BY
                `sales_order_list`.`material_code`
            ORDER BY
                `total_purchased` DESC";

        $this->View->render('contact/detail',
                array(
                'title' => 'Contact Detail',
                'activelink1' => 'contact',
                'contact' => GenericModel::rawSelect($sql, false),
                'contact_person' => GenericModel::getAll('contact_person', "`contact_id` = '$contact_id' AND `is_deleted` = 0"),
                'purchased_item' => GenericModel::rawSelect($sql_purchased_item),
                'selling_item' => GenericModel::rawSelect($sql_selling_item)
                )
            );
    }

    public function insertContact()
    {
        if (empty(Request::post('contact_name'))) {
            Session::add('feedback_negative', Text::get('INSERT_FAILED'));
            Redirect::to('contact/detail');
        }
        // create contact ID
        $name = strtolower(Request::post('contact_name')); //lower case string to easily (case insensitive) remove unwanted characters
        $find = array('.', '-', ',', 'pt', 'inc', 'tbk', 'cv', 'ud');
        $name = str_replace($find, '', $name);
        $contact_name = $name = trim($name);
        $name_count=str_word_count($name);
        $name_array=str_word_count($name, 1);
        if ($name_count > 2) {
            $name = substr($name, 0, 1).substr($name_array[1], 0, 1).substr($name_array[2], 0, 1);
        } else if ($name_count == 2) {
            $name = substr($name, 0, 1).substr($name_array[1], 0, 2);
        } else {
            $name = substr($name, 0, 3);
        }
        $query = "SELECT ABS(mid(`contact_id`, 4, 2)) AS max FROM `contact` WHERE `contact_id` LIKE '%$name%' ORDER BY max DESC LIMIT 1";
        $max = GenericModel::rawSelect($query, false);
        $max = (int)$max->max +1;
        $contact_id = $name.$max;
        $log = '<li><span class="badge badge-grey">' . SESSION::get('user_name') .'</span> creates contact. (' . date("Y-m-d") . ')</li>';

        $insert = array(
                        'log'    => $log,
                        'contact_name'    => Request::post('contact_name'),
                        'contact_id'    => $contact_id,
                        'creator_id'    => SESSION::get('uid'),
                        );
        GenericModel::insert('contact', $insert);
        Redirect::to('contact/detail/' . $contact_id);
    }

    public function updateContactId($contact_id)
    {
        //Start make log
        $oldData                = GenericModel::getOne('contact', "`contact_id` = '$contact_id'", 'log');
        $post_array             = $_POST; // get all post array
        $log             = json_encode($_POST); // change to json to easily replaced like string
        $log             = str_replace('","', '<br />', $log);
        $log             = str_replace('":"', ' : ', $log);
        $log             = str_replace('_', ' ', $log);
        $log             = str_replace('{"', '', $log);
        $log             = str_replace('"}', '', $log);
        $log             = '<li><span class="badge badge-grey">' . SESSION::get('user_name') .'</span> edit contact:<br />' . $log . '<br>(' . date("Y-m-d") . ')</li>' . $oldData->log;

        $custom_array = array(
                        'log'    => $log,
                        'modifier_id'    => SESSION::get('user_name'),
                        'modified_timestamp'    => date("Y-m-d H:i:s")
                        );
        $update = array_merge($post_array, $custom_array);
        //Debuger::jam($post_array);exit;
        GenericModel::update('contact', $update, "`contact_id` = '$contact_id'");
        Redirect::to('contact/detail/' . $contact_id);
    }

    public function updateNote($contact_id)
    {
        $oldData = GenericModel::getOne('contact', "`contact_id` = '$contact_id'", '`note`, `log`');
        $log = '<li><span class="badge badge-grey">' . SESSION::get('user_name') .'</span> menambahkan note: ' . Request::post('note') . ' (' . date("Y-m-d") . ')</li>' . $oldData->log;
        $note = '<li><span class="badge badge-grey">' . SESSION::get('user_name') .'</span> ' . Request::post('note') . ' (' . date("Y-m-d") . ')</li>' . $oldData->note;
        
        
        $update = array(
            'note'      =>  $note,
            'log'      =>  $log,
            'modifier_id'    => SESSION::get('user_name'),
            'modified_timestamp'    => date("Y-m-d H:i:s")
        );
        GenericModel::update('contact', $update, "`contact_id` = '$contact_id'");
        Redirect::to('contact/detail/' . $contact_id);
    }

    public function insertContactPerson($contact_id)
    {
        // Start Make log
        $oldData        = GenericModel::getOne('contact', "`contact_id` = '$contact_id'", '`note`, `log`');
        $post_array      = $_POST; // get all post array
        $post_array = array_map("strip_tags", $post_array);
        $post_array = array_map("strip_tags", $post_array);
        $log             = json_encode($post_array); // change to json to easily replaced like string
        $log             = str_replace('","', '<br />', $log);
        $log             = str_replace('":"', ' : ', $log);
        $log             = str_replace('_', ' ', $log);
        $log             = str_replace('{"', '', $log);
        $log             = str_replace('"}', '', $log);
        $log             = '<li><span class="badge badge-grey">' . SESSION::get('user_name') .'</span> menambahkan contact person:<br />' . $log . '<br>(' . date("Y-m-d") . ')</li>' . $oldData->log;

        $log_array = array(
                        'log'      =>  $log,
                        'modifier_id'    => SESSION::get('user_name'),
                        'modified_timestamp'    => date("Y-m-d H:i:s")
                        );

        // Insert new contact person
        $uid = GenericModel::guid();
        $new_contact_array = array(
                        'uid'    => $uid,
                        'contact_id'    => $contact_id,
                        'creator_id'    => SESSION::get('user_name')
                        );
        $insert = array_merge($post_array, $new_contact_array);
        GenericModel::insert('contact_person', $insert);

        // update log in contact table
        QgenericModel::update('contact', $log_array, "`contact_id` = '$contact_id'");

        //If main contact checked, than make sure other contact person is removed as main contact
         if ($_POST['is_main'] == 1) {
                $cond = "contact_id='$contact_id' AND uid != '$uid'";
                $update = array(
                        'is_main'    => 0
                        );
              QgenericModel::update('contact_person', $update, $cond);
          }
        Redirect::to('contact/detail/' . $contact_id);
    }

    public function updateSalesInfo($contact_id)
    {
        // Make log
        $oldData        = GenericModel::getOne('contact', "`contact_id` = '$contact_id'", '`note`, `log`');
        $post_array      = $_POST; // get all post array
        $post_array = array_map("strip_tags", $post_array);

        $log             = json_encode($post_array); // change to json to easily replaced like string
        $log             = str_replace('","', '<br />', $log);
        $log             = str_replace('":"', ' : ', $log);
        $log             = str_replace('_', ' ', $log);
        $log             = str_replace('{"', '', $log);
        $log             = str_replace('"}', '', $log);
        $log             = '<li><span class="badge badge-grey">' . SESSION::get('user_name') .'</span> mengubah sales info:<br />' . $log . '<br>(' . date("Y-m-d") . ')</li>' . $oldData->log;

        $log_array = array(
                        'log'      =>  $log,
                        'modifier_id'    => SESSION::get('user_name'),
                        'modified_timestamp'    => date("Y-m-d H:i:s")
                        );
        $update = array_merge($post_array, $log_array);
        GenericModel::update('contact', $update, "`contact_id` = '$contact_id'");
        Redirect::to('contact/detail/' . $contact_id);
    }

    public function editContactPerson($uid)
    {
        $this->View->render('contact/edit_contact_person', array(
                'title' => 'Edit Contact Person',
                'contact_person' => GenericModel::getOne('contact_person', "`uid` = '$uid'")
            ));
    }

    public function updateContactPerson($uid)
    {
        // get contact_id first from contact_person person with uid from contact_person
        $t_contact_person = GenericModel::getOne('contact_person', "`uid` = '$uid'", '`contact_id`');
        $contact_id = $t_contact_person->contact_id;

        // Start Make log
        $oldData        = GenericModel::getOne('contact', "`contact_id` = '$contact_id'", '`note`, `log`');
        $post_array      = $_POST; // get all post array
        $post_array = array_map("strip_tags", $post_array);

        $log             = json_encode($post_array); // change to json to easily replaced like string
        $log             = str_replace('","', '<br />', $log);
        $log             = str_replace('":"', ' : ', $log);
        $log             = str_replace('_', ' ', $log);
        $log             = str_replace('{"', '', $log);
        $log             = str_replace('"}', '', $log);
        $log             = '<li><span class="badge badge-grey">' . SESSION::get('user_name') .'</span> mengubah contact person:<br />' . $log . '<br>(' . date("Y-m-d") . ')</li>' . $oldData->log;

        $log_array = array(
                        'log'      =>  $log,
                        'modifier_id'    => SESSION::get('user_name'),
                        'modified_timestamp'    => date("Y-m-d H:i:s")
                        );
        // update log in contact table
        QgenericModel::update('contact', $log_array, "`contact_id` = '$contact_id'");

        // update contact_person table
        $custom_array = array(
                        'modifier_id'    => SESSION::get('user_name'),
                        'modified_timestamp'    => date("Y-m-d H:i:s")
                        );

        $update = array_merge($post_array, $custom_array);
        GenericModel::update('contact_person', $update, "`uid` = '$uid'");


        //If main contact checked, than make sure other contact person is removed as main contact
         if ($_POST['is_main'] == 1) {
                $cond = "`contact_id` = '$contact_id' AND `uid` != '$uid'";
                $update = array(
                        'is_main'    => 0
                        );
              QgenericModel::update('contact_person', $update, $cond);
          }
        Redirect::to('contact/detail/' . $contact_id);
    }               


    public function deleteContact($contact_id)
    {
        if (SESSION::get('user_account_type') > 55) { //Make sure only previleged user can delete this data
           $update = array(
                        'is_deleted'      =>  1,
                        'modifier_id'    => SESSION::get('user_name'),
                        'modified_timestamp'    => date("Y-m-d H:i:s")
                        );
           GenericModel::update('contact', $update, "`contact_id` = '$contact_id'");
           Redirect::to(Request::get('forward'));
        } else {
            Redirect::to(Request::get('forward'));
        }
    }

}
