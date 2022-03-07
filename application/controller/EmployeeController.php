<?php


class EmployeeController extends Controller
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
        Auth::checkAuthentication();
        Auth::checkPermission('director,hr');
        
    }

    /**
     * This method controls what happens when you move to /note/index in your app.
     * Gets all notes (of the user).
     */
    public function index()
    {
        //CHECK PERMISSION
        Auth::checkPermission('director,hr', 500);

        if(isset($_GET['find'])) {
            $find = strtolower(Request::get('find')); //lower case string to easily (case insensitive) remove unwanted characters
            $terms = explode(" ", trim($find));
            $first = true;
            $string_search = '';
            foreach($terms as $term)
                {
                    if($term != '') {
                        if(!$first) $string_search .= " OR ";
                          $string_search .= "`users`.`user_name` LIKE '%".trim($term)."%' OR `full_name` LIKE '%".trim($term)."%'";
                          $first = false;
                    }
                }
            $where = $string_search . ' AND `users`.`is_deleted` != 1 GROUP BY `users`.`user_name` ORDER By `users`.`full_name`';
            $sql = "SELECT
                        `users`.`uid`,
                        `users`.`user_name`,
                        `users`.`full_name`,
                        CONCAT(`users`.`address_street`, '. ', `users`.`address_city`,  '. ', `users`.`address_state`) AS `address`
                    FROM 
                        `users`
                    WHERE
                        $where ASC";
            $contact = GenericModel::rawSelect($sql);
        } else {
            $field = '`users`.`uid`,
                    `users`.`user_name`,
                    `users`.`full_name`,
                    CONCAT(`users`.`address_street`, ". ", `users`.`address_city`,  ". ", `users`.`address_state`) AS `address`';
            $table = '`users`';
            $where = "`users`.`is_deleted` = 0 ORDER BY `users`.`full_name`";
            $contact = GenericModel::getAll($table, $where, $field);
        }

        $header_script = '<link rel="stylesheet" href="' . Config::get('URL') . 'bootstrap-3.3.7/css/excel-2007.css" media="screen"/>';
        $this->View->render('employee/index',
              array(
                'header_script' => $header_script,
                'title' => 'Daftar Pegawai',
                'activelink1' => 'Employee',
                'activelink2' => 'daftar pegawai',
                'contact' => $contact
            )
        );
    }

    public function detail($user_name)
    {
        if (!Auth::isPermissioned('director,hr', 200)) {
            $user_name = Session::get('user_name');
        }

        $contact = "SELECT `users`.*  FROM `users` WHERE `users`.`user_name` = '$user_name' AND `users`.`is_deleted` = 0 LIMIT 1";
        $uploaded_file = "SELECT `item_name`, `item_id`, `value`, `uid`, `note`  FROM `upload_list` WHERE `category` =  'employee' AND `item_id` = '{$user_name}' AND `is_deleted` = 0";
        $jam_kerja = "SELECT * FROM `working_hours_preference` ORDER BY `group` ASC";

        $header_script = '<link rel="stylesheet" href="' . Config::get('URL') . 'bootstrap-3.3.7/css/excel-2007.css" media="screen"/><link rel="stylesheet" href="' . Config::get('URL') . 'bootstrap-3.3.7/css/datepicker.css" media="screen"/>';
        $footer_script = '<script src="' . Config::get('URL') . 'bootstrap-3.3.7/js/bootstrap-datepicker.js"></script>
        <script>
        $(".datepicker").datepicker(); //date picker
        </script>';

        $this->View->render('employee/detail',
                array(
                'title' => 'Contact Detail ' . $user_name,
                'activelink1' => 'Employee',
                'activelink2' => 'daftar pegawai',
                'header_script' => $header_script,
                'footer_script' => $footer_script,
                'contact' => GenericModel::rawSelect($contact, false),
                'uploaded_file' => GenericModel::rawSelect($uploaded_file),
                'jam_kerja' => GenericModel::rawSelect($jam_kerja),
                )
            );
    }

    public function updateEmployee($user_name)
    {
        if (!Auth::isPermissioned('director,hr', 200)) {
            $user_name = Session::get('user_name');
        }
        //Start make log
        $oldData         = GenericModel::getOne('users', "`user_name` = '$user_name'", 'log');
        $post_array      = $_POST; // get all post array
        $log             = json_encode($_POST); // change to json to easily replaced like string
        $log             = str_replace('","', '<br />', $log);
        $log             = str_replace('":"', ' : ', $log);
        $log             = str_replace('_', ' ', $log);
        $log             = str_replace('{"', '', $log);
        $log             = str_replace('"}', '', $log);
        $log             = '<li><span class="badge badge-grey">' . SESSION::get('user_name') .'</span> edit employee:<br />' . $log . '<br>(' . date("Y-m-d") . ')</li>' . $oldData->log;

        $custom_array = array(
                        'log'    => $log,
                        'modifier_id'    => SESSION::get('user_name'),
                        'modified_timestamp'    => date("Y-m-d H:i:s")
                        );
        $update = array_merge($post_array, $custom_array);
        GenericModel::update('users', $update, "`user_name` = '$user_name'");
        Redirect::to('employee/detail/' . $user_name);
    }

    /**
     * Handles the entire registration process for DEFAULT users (not for people who register with
     * 3rd party services, like facebook) and creates a new user in the database if everything is fine
     *
     * @return boolean Gives back the success status of the registration
    */
    public static function registerNewEmployee()
    {
        //validate name
        if (empty(Request::post('full_name'))) {
            Session::add('feedback_negative', 'Nama Lengkap Tidak Boleh Kosong.');
            Redirect::to('employee/index'); exit;
        }

        // clean the input
        $full_name = strtolower(Request::post('full_name'));
        $user_name = strtolower(Request::post('user_name'));
        $email = Request::post('email');
        $phone = FormaterModel::getNumberOnly(Request::post('phone'));
        $user_password_new = Request::post('user_password_new');
        $user_password_repeat = Request::post('user_password_repeat');

        // create user_name jika username kosong
        if (empty($user_name)) {
            $name = trim($full_name);
            $name_count = str_word_count($name);
            $name_array = str_word_count($name, 1);

            if ($name_count > 2) {
                $name = substr($name, 0, 1).substr($name_array[1], 0, 1).substr($name_array[2], 0, 1);
            } else if ($name_count == 2) {
                $name = substr($name, 0, 1).substr($name_array[1], 0, 2);
            } else {
                $name = substr($name, 0, 3);
            }

            $query = "SELECT `user_name` AS max FROM `users` WHERE `user_name` LIKE '%$name%' ORDER BY user_name DESC LIMIT 1";
            $max = GenericModel::rawSelect($query, false);
            $max = (int) FormaterModel::getNumberOnly($max->max) +1;
            $user_name = $name . $max;
        }


        //Validate username
        if (!RegistrationModel::validateUserName($user_name)) {
            Session::add('feedback_negative', 'Format username tidak benar, hanya gunakan huruf dan angka tanpa spasi');
            Redirect::to('employee/index'); exit;
        }

        //Validate Email
        if (!RegistrationModel::validateUserEmail($email)) {
            Session::add('feedback_negative', 'Format Email Keliru');
            Redirect::to('employee/index'); exit;
        }

        //Validate username, email and password
        if (!RegistrationModel::validateUserPassword($user_password_new, $user_password_repeat)) {
            Session::add('feedback_negative', 'Password tidak sama');
            Redirect::to('employee/index'); exit;
        }

        // crypt the password with the PHP 5.5's password_hash() function, results in a 60 character hash string.
        // @see php.net/manual/en/function.password-hash.php for more, especially for potential options
        $user_password_hash = password_hash($user_password_new, PASSWORD_DEFAULT);

        // check if username already exists
        if (UserModel::doesUsernameAlreadyExist($user_name)) {
            Session::add('feedback_negative', 'Maaf username sudah dipakai orang lain');
            Redirect::to('employee/index'); exit;
        }

        // check if email already exists
        if (UserModel::doesEmailAlreadyExist($email)) {
            Session::add('feedback_negative', 'Alamat email sudah pernah dipakai');
            Redirect::to('employee/index'); exit;
        }

        // check if phone already exists
        if (UserModel::doesPhoneAlreadyExist($phone)) {
            Session::add('feedback_negative', 'Nomer telpon sudah pernah dipakai');
            Redirect::to('employee/index'); exit;
        }

        $insert = array(
                        'user_name' => $user_name,
                        'full_name' => $full_name,
                        'user_password_hash' => $user_password_hash,
                        'email' => $email,
                        'phone' => $phone,
                        'is_active' => 1,
                        'creator_id'    => SESSION::get('uid'),
                        );
        GenericModel::insert('users', $insert);

        Redirect::to('employee/detail/' . $user_name);
    }

    public function deleteEmployee($user_name)
    {
        if (SESSION::get('user_account_type') > 55) { //Make sure only previleged user can delete this data
           $update = array(
                        'is_deleted'      =>  1,
                        'modifier_id'    => SESSION::get('user_name'),
                        'modified_timestamp'    => date("Y-m-d H:i:s")
                        );
           GenericModel::update('users', $update, "`user_name` = '$user_name'");
           Redirect::to(Request::get('forward'));
        } else {
            Redirect::to(Request::get('forward'));
        }
    }

    public function attendanceReport($date_time = null, $user_id = null)
    {
        if (Request::post('change_date') == 'ok') { // user input change date
            Redirect::to('employee/attendanceReport/' . Request::post('date') . '/' . Request::post('user_id'));
        }

        $header_script = '<link rel="stylesheet" href="' . Config::get('URL') . 'bootstrap-3.3.7/css/excel-2007.css" media="screen"/><link rel="stylesheet" href="' . Config::get('URL') . 'bootstrap-3.3.7/css/datepicker.css" media="screen"/>';
        $footer_script = '<script src="' . Config::get('URL') . 'bootstrap-3.3.7/js/bootstrap-datepicker.js"></script>
        <script>
        $(".datepicker").datepicker(); //date picker
        </script>';
        //jika user_id gak ada, tampilkan laporan sendiri pakai data dari session
        if ($user_id == null) {
            $user_id = Session::get('uid');
        }

        $this->View->render('employee/attendance_report',
                array(
                'title' => 'Laporan Absensi',
                'header_script' => $header_script,
                'footer_script' => $footer_script,
                'user_id' => $user_id,
                'report_month' => $date_time,
                'last_business_day' => AttendanceModel::lastBusinessDay($date_time),
                'attendance' => AttendanceModel::report($date_time, $user_id),
                'salary_benefit' => AttendanceModel::salaryBenefit($date_time, $user_id),
                'uang_transport' => GenericModel::getOne('system_preference', '`item_name` = "uang_transport"', '*'),
                'attendance_late_fine' => GenericModel::getOne('system_preference', '`item_name` = "attendance_late_fine"', '*'),
                'never_late_per_month_reward' => GenericModel::getOne('system_preference', '`item_name` = "never_late_per_month_reward"', '*'),
                'employee' => GenericModel::getOne('`users`', "`uid` = '{$user_id}'", '`salary`, `grade`')
                )
            );
    }

    public function salaryBenefit()
    {
        for ($i=1; $i <= 10; $i++) { 
            if (!empty(Request::post('benefit_name_' . $i)) AND !empty(Request::post('benefit_value_' . $i))) {
                    $insert=array(
                        'uid'    => GenericModel::guid(),
                        'user_id'    => Request::post('user_id'),
                        'benefit_name'    => Request::post('benefit_name_' . $i),
                        'benefit_value'    => Request::post('benefit_value_' . $i),
                        'benefit_date'    => Request::post('benefit_date'),
                        'creator_id'    => SESSION::get('user_name')
                        );
                    GenericModel::insert('users_benefit', $insert);
                }
        }

        Redirect::to('employee/attendanceReport/' . Request::post('benefit_date') . '/' . Request::post('user_id'));
    }

    public function printSalarySlip($date_time = null, $user_id = null)
    {

        //jika user_id gak ada, tampilkan laporan sendiri pakai data dari session
        if ($user_id == null) {
            $user_id = Session::get('uid');
        }

        $this->View->renderFileOnly('employee/printSalarySlip',
                array(
                'title' => 'Print Slip Gaji',
                'user_id' => $user_id,
                'report_month' => $date_time,
                'attendance' => AttendanceModel::report($date_time, $user_id),
                'salary_benefit' => AttendanceModel::salaryBenefit($date_time, $user_id),
                'uang_transport' => GenericModel::getOne('`system_preference`', '`item_name` = "uang_transport"', '*'),
                'attendance_late_fine' => GenericModel::getOne('`system_preference`', '`item_name` = "attendance_late_fine"', '*'),
                'employee' => GenericModel::getOne('`users`', "`uid` = '{$user_id}'", '*'),
                'company' => GenericModel::getAll('`system_preference`', "`category` = 'company_identification' ORDER BY `item_name` ASC", '`value`, `item_name`')
                )
            );
    }

    /**
     * Perform the upload image
     * POST-request
     */
    public function uploadImage($user_name = null)
    {
        if (empty($user_name)) {
            Redirect::to('employee/detail/' . $user_name);
            Session::add('feedback_negative', 'GAGAL!, upload file tidak berhasil');
        }

        $image_name = 'file_name';
        $image_rename = Request::post('image_name');
        $destination = 'employee';
        $note = Request::post('note');
        UploadModel::uploadImage($image_name, $image_rename, $destination, $user_name, $note);
        Redirect::to('employee/detail/' . $user_name);
    }

     /**
     * Perform the upload pdf, xlsx, doc, docx
     * POST-request
     */
    public function uploadDocument($user_name = null)
    {
        if (empty($user_name)) {
            Redirect::to('employee/detail/' . $user_name);
            Session::add('feedback_negative', 'GAGAL!, upload file tidak berhasil');
        }

        $image_name = 'file_name';
        $image_rename = Request::post('document_name');
        $destination = 'employee';
        $note = Request::post('note');
        UploadModel::uploadDocument($image_name, $image_rename, $destination, $user_name, $note);
        Redirect::to('employee/detail/' . $user_name);
    }

}