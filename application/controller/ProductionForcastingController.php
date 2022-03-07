<?php


class ProductionForcastingController extends Controller {

    /**
     * Construct this object by extending the basic Controller class
     */
    public function __construct()
    {
        parent::__construct();

        Auth::checkAuthentication();
    }

    public function forcast()
    {
        $so_number = urldecode(Request::get('so_number'));
        $this->View->renderFileOnly('productionForcasting/forcast', array(
                'material_list' => GenericModel::getAll('material_list', "`is_deleted` = 0 AND (`material_type` = 0 OR `material_type` = 1)", "`material_code`, `material_name`, `unit`")
                )
        );
    }

    public function saveForcasting() {
        $so_number   = Request::post('so_number');
        $material_list   = explode(' ___ ', Request::post('material_list'));
        $material_list   = array_filter($material_list);
        //echo '<pre>';var_dump($material_list);echo '</pre>'; exit;

        foreach ($material_list as $key => $value) {
            $material   = explode(' --- ', $value);
            //echo '<pre>';var_dump($product);echo '</pre>';
            $material_code = FormaterModel::sanitize($material[0]);
            $material_qty = FormaterModel::sanitize($material[1]);
            $material_unit = FormaterModel::sanitize($material[2]);
            if (!empty($material_code) AND !empty($material_qty)) {
                //check if material code is BOM or Not
                if (substr($material_code, 0, 4) === 'BOM.') {
                    $bom_list_sql = "
                        SELECT
                            `material_list_formulation`.`job_type`,
                            `material_list_formulation`.`sub_job_type`,
                            `material_list_formulation`.`material_code`,
                            `material_list_formulation`.`unit_per_quantity`,
                            `material_list_formulation`.`unit`,
                            `material_list`.`purchase_price`,
                            `material_list`.`purchase_currency`,
                            `material_list`.`purchase_unit`
                        FROM
                            `material_list_formulation`
                                JOIN
                            `material_list`
                                ON
                            `material_list`.`material_code` = `material_list_formulation`.`material_code`

                        WHERE
                            `material_list_formulation`.`formulation_code` = '{$material_code}'";
                    $bom_list = GenericModel::rawSelect($bom_list_sql);

                    //2.3.2 insert daftar material BOM ke database (plus jumlah forcasting dan harganya)
                    foreach ($bom_list as $bom_key => $bom_value) {
                        $production_qty = $material_qty * $bom_value->unit_per_quantity;
                        $production_price = $bom_value->purchase_price * $production_qty;

                        //check usd rate if currency use usd and rate is still empty
                        if ($bom_value->purchase_currency != 'IDR' AND empty($currency_rate)) {
                            $currency_rate = FormaterModel::currencyRateBI();
                        }

                        //multiplication with dollar rate
                        if ($bom_value->purchase_currency != 'IDR') {
                            $production_price = $production_price * (int) $currency_rate[$bom_value->purchase_currency]['jual'];

                            $note = 'BOM: <a href="' . Config::get('URL') . 'inventory/editFormulation/?find=' . urlencode($material_code) . '">' . $material_code . '</a>. Kurs: ' . $bom_value->purchase_currency . ' = ' . number_format($currency_rate[$bom_value->purchase_currency]['jual'], 0) . ' Rupiah.';
                        } else {
                            $note = 'BOM: <a href="' . Config::get('URL') . 'inventory/editFormulation/?find=' . urlencode($material_code) . '">' . $material_code . '</a>.';
                        }

                        $insert = array(
                            'uid'    => GenericModel::guid(),
                            'job_type'    => $bom_value->job_type,
                            'sub_job_type'    => $bom_value->sub_job_type,
                            'material_code'    => $bom_value->material_code,
                            'quantity'    => $production_qty,
                            'unit'    => $bom_value->unit,
                            'purchase_price'    => $bom_value->purchase_price,
                            'purchase_currency'    => $bom_value->purchase_currency,
                            'purchase_unit'    => $bom_value->purchase_unit,
                            'production_price'    => $production_price,
                            'transaction_number'    => $so_number,
                            'note'    => $note,
                            'creator_id'    => SESSION::get('uid')
                        );
                        GenericModel::insert('production_forcasting_list', $insert);
                    }
                } else { // Not BOM

                    //get price for material
                    $material_data = GenericModel::getOne('material_list', "`material_code` = '{$material_code}'", '`purchase_price`, `purchase_currency`, `purchase_unit`');
                    $production_price = $material_data->purchase_price * $material_qty;


                    //check usd rate if currency use usd and rate is still empty
                    if ($material_data->purchase_currency != 'IDR' AND empty($currency_rate)) {
                        $currency_rate = FormaterModel::currencyRateBI();
                    }

                    //multiplication with dollar rate
                    if ($material_data->purchase_currency != 'IDR') {
                        $production_price = $production_price * (int) $currency_rate[$material_data->purchase_currency]['jual'];

                        $note = 'Kurs ' . $material_data->purchase_currency . ' = ' . number_format($currency_rate[$material_data->purchase_currency]['jual'], 0) . ' Rupiah.';
                    } else {
                        $note = '';
                    }

                    $insert = array(
                                'uid'    => GenericModel::guid(),
                                'job_type'    => '',
                                'sub_job_type'    => '',
                                'material_code'    => $material_code,
                                'quantity'    => $material_qty,
                                'unit'    => $material_unit,
                                'purchase_price'    => $material_data->purchase_price,
                                'purchase_currency'    => $material_data->purchase_currency,
                                'purchase_unit'    => $material_data->purchase_unit,
                                'production_price'    => $production_price,
                                'transaction_number'    => $so_number,
                                'note'    => $note,
                                'creator_id'    => SESSION::get('uid')
                            );
                    GenericModel::insert('production_forcasting_list', $insert);

                }
            }
        }

        $feedback_positive = Session::get('feedback_positive');
        $feedback_negative = Session::get('feedback_negative');
        // echo out positive messages
        if (isset($feedback_positive)) {
            //echo 'SUKSES, ' . count($feedback_positive) . ' job order berhasil disimpan';
            echo Config::get('URL') . 'so/detail/?so_number=' . urlencode($so_number);
        }

        // echo out negative messages
        if (isset($feedback_negative)) {
            echo 'GAGAL!, ' . count($feedback_positive) . ' input Bill Of Material tidak berhasil';
        }
        // RESET counter feedback to unconfuse user
        Session::set('feedback_positive', null);
        Session::set('feedback_negative', null);
    }

    public function deleteForcasting() {
        $transaction_number = urldecode(Request::get('transaction_number'));
        GenericModel::remove('production_forcasting_list', 'transaction_number', $transaction_number, false); //false for silent feedback
        Redirect::to(Request::get('forward'));
    }
}