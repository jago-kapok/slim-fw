<?php

/**
 * NoteModel
 * This is basically a simple CRUD (Create/Read/Update/Delete) demonstration.
 */
class AttendanceModel
{
    public static function scan($uid)
    {
        $database = DatabaseFactory::getFactory()->getConnection();
        $query = $database->prepare("SELECT `uid`, `user_name`, `full_name` FROM `users` WHERE `uid` = :uid LIMIT 1");
        $query->execute(array(':uid' => $uid));
        return $query->fetch();
    }

    public static function report($date_time, $user_id)
    {	
    	$database = DatabaseFactory::getFactory()->getConnection();
    	// if null given, show this month transaction
        if ($date_time == null) {
            $start_date = date('Y-m-01'); //!!!Always start with date 01 (first date)
        } else {
            $start_date = date('Y-m-d', strtotime('first day of', strtotime($date_time)));
        }

        $end_date = date('Y-m-d', strtotime('last day of', strtotime($start_date)));
        $sql = "SELECT
        			`user_id`,
        			MIN(`created_timestamp`) as `jam_datang`,
        			MAX(`created_timestamp`) as `jam_pulang`,
        			DATE(`created_timestamp`) AS `by_date`
        		FROM
        			`users_attendance_log`
        		WHERE
        			`user_id` = :user_id
        				AND
        			(`created_timestamp` BETWEEN '$start_date 00:00:00' AND '$end_date 23:59:00')
        		GROUP BY
        			`user_id`,
        			`by_date`";
        $query = $database->prepare($sql);
        $query->execute(array(':user_id' => $user_id));
        return $query->fetchAll();
    }

    public static function salaryBenefit($date_time, $user_id)
    {   
        $database = DatabaseFactory::getFactory()->getConnection();
        // if null given, show this month transaction
        if ($date_time == null) {
            $start_date = date('Y-m-01'); //!!!Always start with date 01 (first date)
        } else {
            $start_date = date('Y-m-d', strtotime('first day of', strtotime($date_time)));
        }

        $end_date = date('Y-m-d', strtotime('last day of', strtotime($start_date)));
        $sql = "SELECT *
                FROM
                    `users_benefit`
                WHERE
                    `user_id` = :user_id
                        AND
                    (`benefit_date` BETWEEN '$start_date' AND '$end_date') ORDER BY `benefit_value` DESC ";
        $query = $database->prepare($sql);
        $query->execute(array(':user_id' => $user_id));
        return $query->fetchAll();
    }

    public static function lastBusinessDay($date_time)
    {
        $lastdateofthemonth = date("Y-m-t", strtotime($date_time));

        $lastworkingday = date('l', strtotime($lastdateofthemonth));
        $lastworkingdate = date('Y-m-d', strtotime($lastdateofthemonth));

        if($lastworkingday == "Saturday") { 
            $newdate = strtotime ('-1 day', strtotime($lastdateofthemonth));
            $lastworkingdate = date ('Y-m-j', $newdate);
        } elseif ($lastworkingday == "Sunday") { 
            $newdate = strtotime ('-2 day', strtotime($lastdateofthemonth));
            $lastworkingdate = date ( 'Y-m-j' , $newdate );
        }

        return $lastworkingdate;

    }

}
