<?php

class AttendanceController extends Controller
{
    /**
     * Construct this object by extending the basic Controller class
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Handles what happens when user moves to URL/index/index - or - as this is the default controller, also
     * when user moves to /index or enter your application at base level
     */
    public function index()
    {
        $this->View->renderFileOnly('attendance/index');
    }

    public function scan()
    {
        $employee_id = FormaterModel::getNumberOnly(Request::post('scan'));
        $status = AttendanceModel::scan($employee_id);
        if (!empty($status->uid)) {
            $insert = array(
                        'uid'    => GenericModel::guid(),
                        'user_id'    => $employee_id,
                        'created_timestamp' => date('Y-m-d H:i:s')
                        );
            GenericModel::insert('users_attendance_log', $insert);
            Session::set('full_name', $status->full_name);
            Session::set('uid', $status->uid);
            Session::set('user_name', $status->user_name);
        } else {
            Session::destroy();
        }
        Redirect::to('attendance/index/');
    }

    public function attendanceReport($date_time = null)
    {
        //jika user_id gak ada, tampilkan laporan sendiri pakai data dari session
        $user_id = Session::get('uid');


        if (Request::post('change_date') == 'ok') { // user input change date
            Redirect::to('attendance/attendanceReport/' . Request::post('date') . '/');
        }

        $header_script = '<link rel="stylesheet" href="' . Config::get('URL') . 'bootstrap-3.3.7/css/excel-2007.css" media="screen"/><link rel="stylesheet" href="' . Config::get('URL') . 'bootstrap-3.3.7/css/datepicker.css" media="screen"/>';
        $footer_script = '<script src="' . Config::get('URL') . 'bootstrap-3.3.7/js/bootstrap-datepicker.js"></script>
        <script>
        $(".datepicker").datepicker(); //date picker
        </script>';

        $this->View->render('attendance/attendance_report',
                array(
                'title' => 'Laporan Absensi',
                'header_script' => $header_script,
                'footer_script' => $footer_script,
                'user_id' => $user_id,
                'data' => AttendanceModel::report($date_time, $user_id)
                )
            );
    }
}

