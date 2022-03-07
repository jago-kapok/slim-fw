<?php

/**
 * NoteModel
 * This is basically a simple CRUD (Create/Read/Update/Delete) demonstration.
 */
class NotificationModel
{
    public static function director()
    {
        $database = DatabaseFactory::getFactory()->getConnection();
        $sql = "SELECT
                    (SELECT COUNT(`sales_order`.`status`)
                        FROM `sales_order`
                        WHERE `sales_order`.`status` = -1
                    ) AS `total_so_request_approval`,
                    (SELECT COUNT(`purchase_order`.`status`)
                        FROM `purchase_order`
                        WHERE `purchase_order`.`status` = -1
                    ) AS `total_po_request_approval`,
                    (SELECT COUNT(`purchase_order`.`status`)
                        FROM `purchase_order`
                        WHERE `purchase_order`.`status` = 20
                    ) AS `total_edit_po_request_approval`,
                    (SELECT COUNT(`payment_transaction`.`status`)
                        FROM `payment_transaction`
                        WHERE `payment_transaction`.`transaction_type` = 'nota pembelian' AND `payment_transaction`.`status` = -1
                    ) AS `total_nota_pembelian_request_approval`,
                    (SELECT COUNT(`sales_order`.`status`)
                        FROM `sales_order`
                        WHERE `sales_order`.`status` = 7
                    ) AS `total_sj_request_approval`,
                    (SELECT COUNT(`payment_transaction`.`status`)
                        FROM `payment_transaction`
                        WHERE `payment_transaction`.`status` = -1 AND `payment_transaction`.`payment_due_date` IN (CURDATE(), CURDATE() + INTERVAL 1 DAY)
                    ) AS `total_payment_disbursement`

        ";
        $query = $database->prepare($sql);
        $query->execute();
        return $query->fetch();
    }

    public static function directorSupanto()
    {
        $database = DatabaseFactory::getFactory()->getConnection();
        $sql = "SELECT
                    (SELECT COUNT(`purchase_order`.`status`)
                        FROM `purchase_order`
                        WHERE `purchase_order`.`status` = -1
                    ) AS `total_po_request_approval`,
                    (SELECT COUNT(`purchase_order`.`status`)
                        FROM `purchase_order`
                        WHERE `purchase_order`.`status` = 20
                    ) AS `total_edit_po_request_approval`,
                    (SELECT COUNT(`payment_transaction`.`status`)
                        FROM `payment_transaction`
                        WHERE `payment_transaction`.`transaction_type` = 'nota pembelian' AND `payment_transaction`.`status` = -1
                    ) AS `total_nota_pembelian_request_approval`

        ";
        $query = $database->prepare($sql);
        $query->execute();
        return $query->fetch();
    }

    public static function directorNur()
    {
        $database = DatabaseFactory::getFactory()->getConnection();
        $sql = "SELECT
                    (SELECT COUNT(`sales_order`.`status`)
                        FROM `sales_order`
                        WHERE `sales_order`.`status` = -1
                    ) AS `total_so_request_approval`, 
                    (SELECT COUNT(`payment_transaction`.`status`)
                        FROM `payment_transaction`
                        WHERE `payment_transaction`.`status` = -1 AND `payment_transaction`.`payment_due_date` IN (CURDATE(), CURDATE() + INTERVAL 1 DAY)
                    ) AS `total_payment_disbursement`

        ";
        $query = $database->prepare($sql);
        $query->execute();
        return $query->fetch();
    }

    public static function directorEko()
    {
        $database = DatabaseFactory::getFactory()->getConnection();
        $sql = "SELECT
                    (SELECT COUNT(`purchase_order`.`status`)
                        FROM `purchase_order`
                        WHERE `purchase_order`.`status` = -1
                    ) AS `total_po_request_approval`,
                    (SELECT COUNT(`purchase_order`.`status`)
                        FROM `purchase_order`
                        WHERE `purchase_order`.`status` = 20
                    ) AS `total_edit_po_request_approval`,
                    (SELECT COUNT(`payment_transaction`.`status`)
                        FROM `payment_transaction`
                        WHERE `payment_transaction`.`transaction_type` = 'nota pembelian' AND `payment_transaction`.`status` = -1
                    ) AS `total_nota_pembelian_request_approval`,
                    (SELECT COUNT(`sales_order`.`status`)
                        FROM `sales_order`
                        WHERE `sales_order`.`status` = 7
                    ) AS `total_sj_request_approval`,
                    (SELECT COUNT(`payment_transaction`.`status`)
                        FROM `payment_transaction`
                        WHERE `payment_transaction`.`status` = -1 AND `payment_transaction`.`payment_due_date` IN (CURDATE(), CURDATE() + INTERVAL 1 DAY)
                    ) AS `total_payment_disbursement`

        ";
        $query = $database->prepare($sql);
        $query->execute();
        return $query->fetch();
    }



    public static function finance()
    {
        $database = DatabaseFactory::getFactory()->getConnection();
        $sql = "SELECT(SELECT
                            COUNT(DISTINCT `sales_order`.`transaction_number`)
                        FROM
                            `sales_order`
                        JOIN
                            `job_order`
                                ON
                            `job_order`.`job_reverence` = `sales_order`.`transaction_number`
                        JOIN
                            `material_list_in`
                                ON
                            `material_list_in`.`transaction_number` = `job_order`.`job_number`
                        WHERE
                            `sales_order`.`status` >= 0 AND `sales_order`.`status` < 4 
                    ) AS `total_open_do_request`,
                    (SELECT COUNT(`payment_transaction`.`status`)
                        FROM `payment_transaction`
                        WHERE `payment_transaction`.`status` = -1 AND `payment_transaction`.`payment_due_date` IN (CURDATE(), CURDATE() + INTERVAL 1 DAY)
                    ) AS `total_payment_disbursement`


        ";
        $query = $database->prepare($sql);
        $query->execute();
        return $query->fetch();
    }

    public static function purchasing()
    {
        $database = DatabaseFactory::getFactory()->getConnection();
        $sql = "SELECT
                    (SELECT COUNT(`purchase_request_list`.`status`)
                        FROM `purchase_request_list`
                        WHERE `purchase_request_list`.`status` = 'waiting'
                    ) AS `total_new_pr`

        ";
        $query = $database->prepare($sql);
        $query->execute();
        return $query->fetch();
    }

    public static function qualityControl()
    {
        $database = DatabaseFactory::getFactory()->getConnection();
        $sql = "SELECT
                    (SELECT COUNT(`material_list_in`.`status`)
                        FROM `material_list_in`
                        WHERE `material_list_in`.`status` = 'waiting_qc_approval'
                    ) AS `total_waiting_qc_approval`

        ";
        $query = $database->prepare($sql);
        $query->execute();
        return $query->fetch();
    }

    public static function universityLecturer()
    {
        $user_name = SESSION::get('user_name');

        //untuk penjadwalan kalendar
        $epoch_day = intval(strtotime(date('Y-m-d')));
        $start_time = date('H:i:s');
        $start_time = explode(':', $start_time);
        $hour = $start_time[0];
        $minute = $start_time[1];
        $epoch_time = (60*60*intval($hour)) + (60*intval($minute));

        $database = DatabaseFactory::getFactory()->getConnection();
        $sql = "SELECT(
                    SELECT COUNT(`university_student_skripsi`.`uid`)
                        FROM `university_student_skripsi`
                        WHERE `university_student_skripsi`.`status` = -1
                            AND 
                        `university_student_skripsi`.`lecturer_supervisor_code_1` = '{$user_name}'
                    ) AS `total_permintaan_pembimbing_1_skripsi`,
                    (SELECT COUNT(`university_lecturer_appointment`.`uid`)
                        FROM `university_lecturer_appointment`
                        WHERE
                            `status` = 0
                            AND
                            `start_day` >= $epoch_day
                            AND
                            `start_time` >= $epoch_time
                            AND
                            `lecturer_id` = '{$user_name}'
                    ) AS `total_permintaan_penjadwalan_calendar`

        ";
        $query = $database->prepare($sql);
        $query->execute();
        return $query->fetch();
    }
}
