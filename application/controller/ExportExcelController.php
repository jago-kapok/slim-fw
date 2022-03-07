<?php


class ExportExcelController extends Controller
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

    public static function productionForcasting()
    {
        $production_number = urldecode(Request::get('production_number'));
        $so_number = urldecode(Request::get('so_number'));
        //formulation list
        $forcasting = "SELECT
                    `production_forcasting_list`.`job_type`,
                    `production_forcasting_list`.`material_code`,
                    `production_forcasting_list`.`quantity`,
                    `production_forcasting_list`.`note`,
                    `material_list`.`material_name`,
                    `material_list`.`unit`
                FROM
                    `production_forcasting_list`
                LEFT JOIN
                    `material_list` AS `material_list` ON `material_list`.`material_code` = `production_forcasting_list`.`material_code`
                WHERE
                    `production_forcasting_list`.`transaction_number` = '{$so_number}'
                ORDER BY
                    `production_forcasting_list`.`job_type` ASC,
                    `production_forcasting_list`.`material_code`";
        $forcasting = GenericModel::rawSelect($forcasting);
        //echo '<pre>';var_dump($user);echo '</pre>';

        /** Create a new Spreadsheet Object **/
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Job Type');
        $sheet->setCellValue('C1', 'Kode Barang');
        $sheet->setCellValue('D1', 'Nama Barang');
        $sheet->setCellValue('E1', 'Qty');
        $sheet->setCellValue('F1', 'Satuan');
        $sheet->setCellValue('G1', 'Keterangan');

        $no = 2;
        foreach ($forcasting as $key => $value) {
            $sheet->setCellValue('A' . $no, $no);
            $sheet->setCellValue('B' . $no, $value->job_type);
            $sheet->setCellValue('C' . $no, $value->material_code);
            $sheet->setCellValue('D' . $no, $value->material_name);
            $sheet->setCellValue('E' . $no, $value->quantity);
            $sheet->setCellValue('F' . $no, $value->unit);
            $no++;
        }


        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename="material-list-produksi-' . $production_number . '.xlsx"');
        $writer->save("php://output");
    }

    public static function allMaterial()
    {
        $sql_product_detail = "
            SELECT
                `material_list`.`material_code`,
                `material_list`.`material_name`,
                `material_list`.`note`,
                `material_list`.`unit`,
                `material_list`.`purchase_price`,
                `material_list`.`purchase_currency`,
                `material_list`.`purchase_unit`,
                `material_list`.`minimum_balance`
            FROM
                `material_list`
            WHERE
                `material_list`.`is_deleted` = 0
            GROUP BY
                `material_list`.`material_code`";
        $result = GenericModel::rawSelect($sql_product_detail);

        /** Create a new Spreadsheet Object **/
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Kode Barang');
        $sheet->setCellValue('C1', 'Nama Barang');
        $sheet->setCellValue('D1', 'Satuan');
        $sheet->setCellValue('E1', 'Safety Stock');
        $sheet->setCellValue('F1', 'Harga Pembelian');
        $sheet->setCellValue('G1', 'Harga Satuan');
        $sheet->setCellValue('H1', 'Harga Currency');
        $sheet->setCellValue('I1', 'Keterangan');

        $no = 2;
        foreach ($result as $key => $value) {
            $sheet->setCellValue('A' . $no, $no);
            $sheet->setCellValue('B' . $no, $value->material_code);
            $sheet->setCellValue('C' . $no, $value->material_name);
            $sheet->setCellValue('D' . $no, $value->unit);
            $sheet->setCellValue('E' . $no, $value->minimum_balance);
            $sheet->setCellValue('F' . $no, $value->purchase_price);
            $sheet->setCellValue('G' . $no, $value->purchase_unit);
            $sheet->setCellValue('H' . $no, $value->purchase_currency);
            $sheet->setCellValue('I' . $no, $value->note);
            $no++;
        }

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename="material-list.xlsx"');
        $writer->save("php://output");
    }

    public static function cashFlow()
    {
        $sql = "
            SELECT
                `transaction`.`transaction_code`,
                `transaction`.`debit`,
                `transaction`.`credit`,
                `transaction`.`transaction_name`,
                `transaction`.`transaction_category`,
                `transaction`.`transaction_type`,
                `transaction`.`payment_due_date`,
                `transaction`.`payment_disbursement`,
                `transaction`.`contact_id`, 
                `contact`.`contact_name`
            FROM 
                (SELECT
                    `payment_transaction`.`transaction_code`,
                    `payment_transaction`.`debit`,
                    `payment_transaction`.`credit`,
                    `payment_transaction`.`transaction_name`,
                    `payment_transaction`.`transaction_category`,
                    `payment_transaction`.`transaction_type`,
                    `payment_transaction`.`payment_due_date`,
                    `payment_transaction`.`payment_disbursement`, 
                    IF((`sales_order`.`transaction_number` != ''), `sales_order`.`customer_id`, `purchase_order`.`supplier_id`) AS `contact_id`
                FROM
                    `payment_transaction`
                    LEFT JOIN
                        `sales_order` ON `sales_order`.`transaction_number` = `payment_transaction`.`transaction_code`
                    LEFT JOIN
                        `purchase_order` ON `purchase_order`.`transaction_number` = `payment_transaction`.`transaction_code`
                WHERE
                    `payment_transaction`.`status` = -1) AS `transaction`
            LEFT JOIN
                `contact` ON `contact`.`contact_id` = `transaction`.`contact_id`
            ORDER BY `transaction`.`payment_due_date` ASC";

            $cash_flow = GenericModel::rawSelect($sql);

            $sql = "
                SELECT
                    `material_list`.`material_code`,
                    `material_list`.`material_name`,
                    `material_list`.`unit`,
                    `material_list`.`selling_price`,
                    `material_list`.`balance` as `balancer`,
                    `outcoming`.`total_delivered` as `credit`,
                    `incoming`.`total_received` as `debit`
                FROM
                    `material_list`
                    LEFT JOIN
                        (SELECT
                            `material_code`,
                            SUM(quantity_received) AS `total_received`
                        FROM
                            `material_list_in`
                        GROUP  BY `material_list_in`.`material_code`) AS `incoming` ON `incoming`.`material_code` = `material_list`.`material_code`
                    LEFT JOIN
                        (SELECT
                            `material_code`,
                            SUM(quantity_delivered) AS `total_delivered`
                        FROM
                            `material_list_out`
                        GROUP  BY `material_list_out`.`material_code`) AS `outcoming` ON `outcoming`.`material_code` = `material_list`.`material_code`
                WHERE
                    `material_list`.`material_type` = 1 OR `material_list`.`material_type` = 3
                ORDER BY
                    `material_list`.`material_type` DESC
                     ";
            $stock_bahan = GenericModel::rawSelect($sql);

        $table = '`payment_transaction`';
        $where = "`status` = 1";
        $field = " SUM(`debit`) as `total_debit`,
                    SUM(`credit`) as `total_credit`";
        $saldo = GenericModel::getOne($table, $where, $field);
            

        /** Create a new Spreadsheet Object **/
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $no = 1;
        $credit=0;
        $debit=0;
        $SaldoBukuBesar = 0;
        $saldo = $saldo->total_credit - $saldo->total_debit;

        //start insert header
        $sheet->setCellValue('A' . $no, 'No');
        $sheet->setCellValue('B' . $no, 'Date');
        $sheet->setCellValue('C' . $no, '#Transaction');
        $sheet->setCellValue('D' . $no, 'Customer/Supplier');
        $sheet->setCellValue('E' . $no, 'Credit');
        $sheet->setCellValue('F' . $no, 'Debit');
        $sheet->setCellValue('F' . $no, 'Saldo');

        //start insert content: SALDO
        $no = 2;
        $sheet->setCellValue('A' . $no, $no);
        $sheet->setCellValue('F' . $no, $saldo);

        //start insert content: Stock Bahan Jadi
        $no = 3;
        foreach ($stock_bahan as $key => $value) {
            $balance = $value->debit - $value->credit - $value->balancer;
            if ($balance > 0) {
                $sheet->setCellValue('A' . $no, $no);

                $transaction = $value->material_name . ' (' . $balance . ' ' . $value->unit . ' x ' . $value->selling_price . ')';
                $balance = $balance * $value->selling_price;

                $sheet->setCellValue('B' . $no, $transaction);
                $sheet->setCellValue('E' . $no, $balance);

                $saldo = $saldo + $balance;

                $sheet->setCellValue('F' . $no, $saldo);
                $no++;
            }
        }

        foreach ($cash_flow as $key => $value) {
            $sheet->setCellValue('A' . $no, $no);
            $sheet->setCellValue('B' . $no, date("d-F, Y", strtotime($value->payment_due_date)));

            if (empty($value->contact_name) AND empty($value->transaction_name)) {
                $transaction = $value->transaction_type;
            } elseif (empty($value->contact_name) AND !empty($value->transaction_name)) {
                $transaction = 'Transaksi Langsung: ' .  $value->transaction_name . ' (' . $value->transaction_category . ')';
            } else {
                $transaction = $value->contact_name . ' (' . $value->contact_id . ')';
            }

            $sheet->setCellValue('C' . $no, $transaction);
            $sheet->setCellValue('D' . $no, $value->credit);
            $sheet->setCellValue('E' . $no, $value->debit);

            $saldo = $saldo + $value->credit - $value->debit;

            $sheet->setCellValue('F' . $no, $saldo);
            $no++;
        }


        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename="cash-flow.xlsx"');
        $writer->save("php://output");
    }
}
