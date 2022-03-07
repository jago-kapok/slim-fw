<?php


class QcController extends Controller {

    /**
     * Construct this object by extending the basic Controller class
     */
    public function __construct()
    {
        parent::__construct();

        Auth::checkAuthentication();
    }


    public function qcApproval()
    {
            $transaction_number = urldecode(Request::get('transaction_number'));


            $table = '`material_list_in` LEFT JOIN `material_list` ON `material_list`.`material_code` = `material_list_in`.`material_code`';
            $where = "`transaction_number` = '{$transaction_number}' AND `status` = 'waiting_qc_approval'";
            $field = "`material_list_in`.*, `material_list`.`material_name`";
            $qc_item_list = GenericModel::getAll($table, $where, $field);


            $this->View->render('qc/qc_approval',
                  array(
                'title' => 'Purchase Request',
                'activelink1' => 'QC',
                'activelink2' => 'waiting approval',
                'qc_item_list' => $qc_item_list,             
                )
            );
    }

    //QC
    public function approveQC()
    {
        $transaction_number = urldecode(Request::get('transaction_number'));
        $totalrecord = Request::post('total_record');
        $total_qty_rejected = 0;
        $total_qty_approved = 0;
        for ($i = 1; $i <= $totalrecord; $i++) {
            //echo $_POST['quantity_received'][$i];

            $total_qty_received = (int)Request::post('qty_received_' . $i) - (int)Request::post('qty_reject_' . $i);
            $total_qty_rejected = $total_qty_rejected + (int)Request::post('qty_reject_' . $i);
            $total_qty_approved = $total_qty_approved + $total_qty_received;

            $table = 'material_list_in';
            $update = array(
                'quantity_received'      =>  $total_qty_received,
                'quantity_reject'      =>  Request::post('qty_reject_' . $i),
                'status'      =>  'stock',
                'note'      =>  Request::post('note_' . $i),
            );
            $uid = Request::post('uid_' . $i);
            $where = "`uid` = '{$uid}'";
            GenericModel::update($table, $update, $where);
        }

        //update status PO
        $update = array(
                        'status'      =>  0, // kembali ke awal
                        'modifier_id'    => SESSION::get('uid'),
                        'modified_timestamp'    => date("Y-m-d H:i:s")
                        );
        GenericModel::update('purchase_order', $update, "`transaction_number` = '{$transaction_number}'");

        //SEND EMAIL NOTIFICATION
        //GET RELATED Departemen email
        $email_address = GenericModel::getAll('users', "`department` = 'purchasing' OR `department` = 'ppic' OR `department` = 'finance'", '`email`');
        $email = array();
        foreach ($email_address as $key => $value) {
            $email[] = $value->email;
        }
        //add email for notif ke PT. ILMUI
        $email[] = 'edi@sbautomedia.com';

        //Notifikasi untuk reject
        if ($total_qty_rejected > 0) {
            $email_creator = SESSION::get('full_name');
            $email_subject = "QC Reject Notification for " . $transaction_number . ' by ' . $email_creator;
            $body = ucwords($email_creator) . ' mereject material pada PO nomer ' . $transaction_number . '. Klik link berikut untuk melihat detail PO ' .   Config::get('URL') . 'po/detail/?po_number=' . urlencode($transaction_number);
            $mail = new Mail;
            $mail_sent = $mail->sendMail($email, Config::get('EMAIL_VERIFICATION_FROM_EMAIL'),
                Config::get('EMAIL_VERIFICATION_FROM_NAME'), $email_subject, $body
            );
            if ($mail_sent) {
                Session::add('feedback_positive', Text::get('FEEDBACK_VERIFICATION_MAIL_SENDING_SUCCESSFUL'));
            } else {
                Session::add('feedback_negative', Text::get('FEEDBACK_VERIFICATION_MAIL_SENDING_ERROR') . $mail->getError() );
            }
        }

        //Notifikasi untuk diterima
        if ($total_qty_approved > 0) {
            $email_creator = SESSION::get('full_name');
            $email_subject = "QC PASSED Notification for " . $transaction_number . ' by ' . $email_creator;
            $body = ucwords($email_creator) . ' menerima (QC PASSED) material pada PO nomer ' . $transaction_number . '. Klik link berikut untuk melihat detail PO ' .   Config::get('URL') . 'po/detail/?po_number=' . urlencode($transaction_number);
            $mail = new Mail;
            $mail_sent = $mail->sendMail($email, Config::get('EMAIL_VERIFICATION_FROM_EMAIL'),
                Config::get('EMAIL_VERIFICATION_FROM_NAME'), $email_subject, $body
            );
            if ($mail_sent) {
                Session::add('feedback_positive', Text::get('FEEDBACK_VERIFICATION_MAIL_SENDING_SUCCESSFUL'));
            } else {
                Session::add('feedback_negative', Text::get('FEEDBACK_VERIFICATION_MAIL_SENDING_ERROR') . $mail->getError() );
            }
        }

       Redirect::to('qc/qcApproval?transaction_number=' . urlencode($transaction_number));
    }

    public function unconformityProduction($page = 1, $limit = 10)
    {
        $offset = ($page > 1) ? ($page - 1) * $limit : 0;
        $prev = Config::get('URL') . 'qc/unconformityProduction/' . ($page - 1) . '/' . $limit;
        $next = Config::get('URL') . 'qc/unconformityProduction/' . ($page + 1). '/' . $limit;
        $sql_group = "
                SELECT
                    `job_order`.`job_number`,
                    `job_order`.`job_reverence`,
                    `job_order`.`job_type`,
                    `material_list_in`.`created_timestamp`,
                    GROUP_CONCAT(`material_list_in`.`material_code` SEPARATOR '-, -') as `material_code`,
                    GROUP_CONCAT(`material_list_in`.`quantity_reject` SEPARATOR '-, -') as `quantity_reject`,
                    GROUP_CONCAT(`material_list_in`.`note` SEPARATOR '-, -') as `note`,
                    GROUP_CONCAT(`material_list`.`material_name` SEPARATOR '-, -') as `material_name`,
                    GROUP_CONCAT(`material_list`.`unit` SEPARATOR '-, -') as `unit`
                FROM
                    `job_order`
                LEFT JOIN
                    `material_list_in` ON `material_list_in`.`transaction_number` = `job_order`.`job_number`
                LEFT JOIN
                    `material_list` ON  `material_list`.`material_code` = `material_list_in`.`material_code`
                WHERE
                    `material_list_in`.`quantity_reject` > 0 
                GROUP BY
                    `material_list_in`.`transaction_number`
                ORDER BY
                    `material_list_in`.`created_timestamp` DESC
                LIMIT
                    $offset, $limit";

        $header_script = '<link rel="stylesheet" href="' . Config::get('URL') . 'bootstrap-3.3.7/css/excel-2007.css" media="screen"/>';
        $this->View->render('qc/unconformity_production',
            array(
                'header_script' => $header_script,
                'title' => 'Production Order On Process',
                'activelink1' => 'QC',
                'activelink2' => 'Unconformity',
                'activelink3' => 'Unconformity Production',
                'prev' => $prev,
                'next' => $next,
                'page' => $page,
                'unconformity' => GenericModel::rawSelect($sql_group)
                )
        );
    }

    public function unconformityRawMaterial($page = 1, $limit = 10)
    {
        $offset = ($page > 1) ? ($page - 1) * $limit : 0;
        $prev = Config::get('URL') . 'qc/unconformityRawMaterial/' . ($page - 1) . '/' . $limit;
        $next = Config::get('URL') . 'qc/unconformityRawMaterial/' . ($page + 1). '/' . $limit;
       
         $sql_group = "
                SELECT
                    `purchase_order`.`transaction_number`,
                    `purchase_order`.`supplier_id`,
                    `material_list_in`.`created_timestamp`,
                    GROUP_CONCAT(`material_list_in`.`material_code` SEPARATOR '-, -') as `material_code`,
                    GROUP_CONCAT(`material_list_in`.`quantity_reject` SEPARATOR '-, -') as `quantity_reject`,
                    GROUP_CONCAT(`material_list_in`.`note` SEPARATOR '-, -') as `note`,
                    GROUP_CONCAT(`material_list`.`material_name` SEPARATOR '-, -') as `material_name`,
                    GROUP_CONCAT(`material_list`.`unit` SEPARATOR '-, -') as `unit`,
                    `contact`.`contact_name`
                FROM
                    `purchase_order`
                LEFT JOIN
                    `material_list_in` AS `material_list_in` ON `material_list_in`.`transaction_number` = `purchase_order`.`transaction_number`
                LEFT JOIN
                    `material_list` AS `material_list` ON  `material_list`.`material_code` = `material_list_in`.`material_code`
                LEFT JOIN
                    `contact` AS `contact` ON `contact`.`contact_id` = `purchase_order`.`supplier_id`
                WHERE
                    `material_list_in`.`quantity_reject` > 0 
                GROUP BY
                    `material_list_in`.`transaction_number`
                ORDER BY
                    `material_list_in`.`created_timestamp` DESC
                LIMIT
                    $offset, $limit";

        $header_script = '<link rel="stylesheet" href="' . Config::get('URL') . 'bootstrap-3.3.7/css/excel-2007.css" media="screen"/>';
        $this->View->render('qc/unconformity_raw_material',
            array(
                'header_script' => $header_script,
                'title' => 'Production Order On Process',
                'activelink1' => 'QC',
                'activelink2' => 'Unconformity',
                'activelink3' => 'Unconformity Raw Material',
                'prev' => $prev,
                'next' => $next,
                'page' => $page,
                'unconformity' => GenericModel::rawSelect($sql_group)
                )
        );
    }

    public function waitingApproval($page = 1, $limit = 20)
    {
        $offset = ($page > 1) ? ($page - 1) * $limit : 0;
        $prev = Config::get('URL') . 'qc/waitingApproval/' . ($page - 1) . '/' . $limit;
        $next = Config::get('URL') . 'qc/waitingApproval/' . ($page + 1). '/' . $limit;

        $sql_group = "
                SELECT
                    `material_list_in`.`transaction_number`,
                    `purchase_order`.`transaction_number` AS `po_number`,
                    `job_order`.`job_number`,
                    `job_order`.`job_reverence`,
                    `material_list_in`.`created_timestamp`,
                    GROUP_CONCAT(`material_list_in`.`material_code` SEPARATOR '-, -') as `material_code`,
                    GROUP_CONCAT(`material_list_in`.`quantity` SEPARATOR '-, -') as `quantity`,
                    GROUP_CONCAT(`material_list`.`material_name` SEPARATOR '-, -') as `material_name`,
                    GROUP_CONCAT(`material_list`.`unit` SEPARATOR '-, -') as `unit`
                FROM
                    `material_list_in`
                LEFT JOIN
                    `material_list` ON `material_list`.`material_code` = `material_list_in`.`material_code`
                LEFT JOIN
                    `purchase_order` ON `purchase_order`.`transaction_number` = `material_list_in`.`transaction_number`
                LEFT JOIN
                    `job_order` ON `job_order`.`job_number` = `material_list_in`.`transaction_number`
                WHERE
                    `material_list_in`.`status` = 'waiting_qc_approval'
                GROUP BY
                    `material_list_in`.`transaction_number`
                ORDER BY
                    `material_list_in`.`created_timestamp` DESC
                LIMIT
                    $offset, $limit";

        $header_script = '<link rel="stylesheet" href="' . Config::get('URL') . 'bootstrap-3.3.7/css/excel-2007.css" media="screen"/>';
        $this->View->render('qc/waiting_approval',
            array(
                'header_script' => $header_script,
                'title' => 'Production Order On Process',
                'activelink1' => 'QC',
                'activelink2' => 'waiting approval',
                'prev' => $prev,
                'next' => $next,
                'page' => $page,
                'limit' => $limit,
                'waiting_approval' => GenericModel::rawSelect($sql_group)
                )
        );
    }
}
