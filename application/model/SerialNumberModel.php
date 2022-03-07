<?php

/**
 * UserModel
 * Handles all the PUBLIC profile stuff. This is not for getting data of the logged in user, it's more for handling
 * data of all the other users. Useful for display profile information, creating user lists etc.
 */
class SerialNumberModel
{
    
    public static function make($production_number, $customer = 'P', $quantity = 1, $start_range = 0)
    {
        $jo_code = explode('/', $production_number);
        $jo_code = (integer) FormaterModel::getNumberOnly($jo_code[0]);
        $jo_code = "00".$jo_code;
        $jo_code = substr($jo_code, strlen($jo_code)-2, 2);
        $jo_code = date("y") . $jo_code;

        $database = DatabaseFactory::getFactory()->getConnection();
        $sql = "SELECT `serial_number` FROM `serial_number` WHERE `serial_number` LIKE '%{$jo_code}%' ORDER BY `uid` DESC LIMIT 1";


        $query = $database->prepare($sql);
        $query->execute();
        

        $serial_number = $query->fetch();
        $serial_number = $serial_number->serial_number;
        $find_integer = explode('-', $serial_number);
        $integer = substr($find_integer[0], 5); //remove first 5 character
        $integer = (integer) FormaterModel::getNumberOnly($serial_number);
        $integer = $integer + $start_range;

        $sn = '';
        for ($i= 1; $i <= $quantity; $i++) {
            $integer = $integer + 1;
            $serial_number = "0000".$integer;
            $serial_number = substr($serial_number, strlen($serial_number)-4, 4);
            $serial_number = $jo_code . $serial_number. '-' . $customer;
            $sn .= $serial_number . ',';
        }
        
        return $sn;
    }

    public static function makeOne($production_number, $customer = 'P')
    {
        $jo_code = explode('/', $production_number);
        $jo_code = (integer) FormaterModel::getNumberOnly($jo_code[0]);
        $jo_code = "00".$jo_code;
        $jo_code = substr($jo_code, strlen($jo_code)-2, 2);
        $jo_code = date("y") . $jo_code;

        $database = DatabaseFactory::getFactory()->getConnection();
        $sql = "SELECT `serial_number` FROM `serial_number` WHERE `serial_number` LIKE '%{$jo_code}%' ORDER BY `uid` ASC LIMIT 1";
        $query = $database->prepare($sql);
        $serial_number = $query->execute();
        $serial_number = $serial_number->serial_number;
        $find_integer = explode('-', $serial_number);
        $integer = substr($find_integer[0], 5); //remove first 5 character
        $integer = (integer) FormaterModel::getNumberOnly($serial_number);

        $serial_number = "0000".$integer;
        $serial_number = substr($serial_number, strlen($serial_number)-4, 4);
        $serial_number = $jo_code . $serial_number. '-' . $customer;

        $sn = '';
        for ($i= 1; $i <= $quantity; $i++) {
            $integer = $integer + 1;
            $serial_number = "0000".$integer;
            $serial_number = substr($serial_number, strlen($serial_number)-4, 4);
            $serial_number = $jo_code . $serial_number. ' - ' . $customer;
            $sn .= $serial_number . ',';
        }
        
        return $sn;
    }

}
