<?php


class SystemPreferenceController extends Controller
{
    /**
     * Construct this object by extending the basic Controller class
     */
    public function __construct()
    {
        parent::__construct();

        // this entire controller should only be visible/usable by logged in users, so we put authentication-check here
        Auth::checkAuthentication();
    }

    public function companyProfile() {
        $category = "SELECT * FROM `system_preference` WHERE `category` = 'company_identification' ORDER BY `uid` ASC";
        $this->View->render('systemPreference/companyProfile',
            array(
                'title' => 'Company Profile',
                'activelink1' => 'System Preference',
                'activelink2' => 'company profile',
                'company' => GenericModel::rawSelect($category),
                )
        );
    }

    public function saveCompanyProfile()
    {
        $totalPost = count($_POST);
        for ($i=1; $i <= $totalPost; $i++) { 
            if (!empty(Request::post('item_' . $i))) {
                    $update = array(
                        'value'    => Request::post('value_' . $i)
                        );
                    $condition = "`item_name` = '" . Request::post('item_' . $i) . "'";
                    GenericModel::update('system_preference', $update, $condition);
                }
        }

        Redirect::to('systemPreference/companyProfile/');
    }

    public function materialCategory() {
        $category = "SELECT * FROM `system_preference` WHERE `category` = 'material_type' ORDER BY `item_name` ASC, `value` ASC";
        $this->View->render('systemPreference/material_category',
            array(
                'title' => 'Seting Kategori Product',
                'activelink1' => 'System Preference',
                'activelink2' => 'material category',
                'category' => GenericModel::rawSelect($category),
                )
        );
    }

        public function insertCategoryMaterial()
    {
        $insert = array(
                    'uid'    =>  strtoupper(Request::post('category_code')),
                    'category'    => 'material_type',
                    'item_name'    => strtolower(Request::post('category_item')),
                    'value'    => Request::post('category_value'),
                    'note'    => Request::post('note')
                    );
        GenericModel::insert('system_preference', $insert);
        Redirect::to('systemPreference/materialCategory');
    }

    public function directTransactionCategory() {
        $direct_expense_transaction = "SELECT * FROM `system_preference` WHERE `category` = 'direct_expense_transaction'";
        $direct_income_transaction = "SELECT * FROM `system_preference` WHERE `category` = 'direct_income_transaction'";
        $this->View->render('systemPreference/directTransactionCategory',
            array(
                'title' => 'Seting Kategori Product',
                'activelink1' => 'System Preference',
                'activelink2' => 'directTransactionCategory',
                'direct_expense_transaction' => GenericModel::rawSelect($direct_expense_transaction),
                'direct_income_transaction' => GenericModel::rawSelect($direct_income_transaction),
                )
        );
    }



    public function insertDirectTransactionCategory()
    {
                $insert = array(
                            'uid'    => GenericModel::guid(),
                            'category'    => Request::post('category'),
                            'item_name'    => Request::post('item_name'),
                            'note'    => Request::post('note')
                            );
                GenericModel::insert('system_preference', $insert);
        Redirect::to('systemPreference/directTransactionCategory');
    }

    public function salary() {
        $this->View->render('systemPreference/salary',
            array(
                'title' => 'Seting Penggajian',
                'activelink1' => 'Employee',
                'activelink2' => 'seting gaji',
                'uang_transport' => GenericModel::getOne('system_preference', '`item_name` = "uang_transport"', '*'),
                'attendance_late_fine' => GenericModel::getOne('system_preference', '`item_name` = "attendance_late_fine"', '*'),
                'never_late_per_month_reward' => GenericModel::getOne('system_preference', '`item_name` = "never_late_per_month_reward"', '*'),
                )
        );
    }

    public function changeSalary() {
        $update = array(
                        'value'    => Request::post('uang_transport')
                    );
        $cond = '`item_name` = "uang_transport"';
        GenericModel::update('system_preference', $update, $cond);

        $update = array(
                        'value'    => Request::post('attendance_late_fine')
                    );
        $cond = '`item_name` = "attendance_late_fine"';
        GenericModel::update('system_preference', $update, $cond);

        $update = array(
                        'value'    => Request::post('never_late_per_month_reward')
                    );
        $cond = '`item_name` = "never_late_per_month_reward"';
        GenericModel::update('system_preference', $update, $cond);
        Redirect::to('systemPreference/salary');
    }

    public function about() {
        
        $this->View->render('systemPreference/about',
            array(
                'title' => 'About This Software',
                'activelink1' => 'System Preference',
                'activelink2' => 'About',
                )
        );
    }

    public function purchaseApproval() {
        if (Auth::isMatch(Session::get("uid"), '201612045') OR Auth::isMatch(Session::get("uid"), '201612046') OR Auth::isMatch(Session::get("uid"), '1')) {
            $company = "SELECT * FROM `system_preference` WHERE `category` = 'module_preference_purchasing' AND `item_name` = 'limit_approval_direksi'";
            $users = "SELECT `full_name`, `email`, `uid` FROM `users` WHERE `department` = 'director' ORDER BY `full_name` ASC";
            $authorized_users_under_limit = "SELECT `value` FROM `system_preference`  WHERE `category` = 'module_preference_purchasing' AND `item_name` = 'users_approval_under_limit'";
            $authorized_users_above_limit = "SELECT `value` FROM `system_preference`  WHERE `category` = 'module_preference_purchasing' AND `item_name` = 'users_approval_above_limit'";
            $this->View->render('systemPreference/purchase_approval',
                array(
                    'title' => 'Company Profile',
                    'activelink1' => 'System Preference',
                    'activelink2' => 'company profile',
                    'company' => GenericModel::rawSelect($company),
                    'users' => GenericModel::rawSelect($users),
                    'authorized_users_under_limit' => GenericModel::rawSelect($authorized_users_under_limit),
                    'authorized_users_above_limit' => GenericModel::rawSelect($authorized_users_above_limit),
                    )
            );
        } else {
            Redirect::to('employee/detail/' . Session::get("user_name"));
        }

        
    }

    public function purchaseApprovalAction() {
        //Delete All users first
        QgenericModel::remove('system_preference', 'item_name', 'users_approval_above_limit');
        QgenericModel::remove('system_preference', 'item_name', 'users_approval_under_limit');

        //Insert Users
        foreach ($_POST['under_limit'] as $user_key => $user_value) {
            $user = trim($_POST['under_limit'][$user_key]);
            if (!empty($user)) {
                $insert = array(
                    'uid' => GenericModel::guid(),
                    'category'    => 'module_preference_purchasing',
                    'item_name' => 'users_approval_under_limit',
                    'value' => $user,
                    'note' => 'users yang melakukan approval dengan limit ' . FormaterModel::getNumberOnly($_POST['limit_approval']),
                );
                GenericModel::insert('system_preference', $insert);
            }
        }

        foreach ($_POST['above_limit'] as $user_key => $user_value) {
            $user = trim($_POST['above_limit'][$user_key]);
            if (!empty($user)) {
                $insert = array(
                    'uid' => GenericModel::guid(),
                    'category'    => 'module_preference_purchasing',
                    'item_name' => 'users_approval_above_limit',
                    'value' => $user,
                    'note' => 'users yang melakukan approval dengan limit ' . FormaterModel::getNumberOnly($_POST['limit_approval']),
                );
                GenericModel::insert('system_preference', $insert);
            }
        }

        $update = array(
                        'value'    => FormaterModel::getNumberOnly($_POST['limit_approval'])
                        );
        $condition = "`item_name` = 'limit_approval_direksi'";
        QgenericModel::update('system_preference', $update, $condition);


        Redirect::to('systemPreference/purchaseApproval/');
    }

    public function workingHours() {
        $category = "SELECT * FROM `working_hours_preference` ORDER BY `group` ASC";
        $this->View->render('systemPreference/working_hours',
            array(
                'title' => 'Seting Jam Kerja',
                'activelink1' => 'System Preference',
                'activelink2' => 'System Preference workingHours',
                'category' => GenericModel::rawSelect($category),
                )
        );
    }

    public function saveWorkingHours()
    {
        $insert = array(
                    'uid'    => GenericModel::guid(),
                    'group'    => Request::post('group'),
                    'start_working_hour'    => Request::post('start_working_hour'),
                    'finish_working_hour'    => Request::post('finish_working_hour'),
                    'note'    => Request::post('note')
                    );
        GenericModel::insert('working_hours_preference', $insert);
        Redirect::to('systemPreference/workingHours');
    }

}