<?php


class HutangPiutangController extends Controller
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
        Auth::checkPermission('director,finance,purchasing');
    }

    public function index($start_date = null, $end_date = null, $by_date = '`payment_transaction`.`invoice_date`')
    {
        Auth::checkPermission('director,finance,purchasing');
        
        if (Request::post('change_date') == 'ok') { // user input change date
            Redirect::to('HutangPiutang/index/' . Request::post('start_date') . '/' . Request::post('end_date') . '/' . Request::post('by_date'));
            exit;
        }

        if (!empty($start_date) AND !empty($end_date)) {
            $where_hutang = "WHERE `payment_transaction`.`transaction_type` = 'purchase order' AND ({$by_date} BETWEEN '{$start_date} 00:00:00' AND '{$end_date} 23:59:59')";
            $where_piutang = "WHERE (`payment_transaction`.`transaction_type` = 'sales order' OR `payment_transaction`.`transaction_type` = 'point of sale') AND ({$by_date} BETWEEN '{$start_date} 00:00:00' AND '{$end_date} 23:59:59')";

            if ($by_date == 'invoice_date') {
                $title_hutang = 'Laporan Hutang Berdasarkan Tanggal Invoice Dari ' . $start_date . ' sampai ' . $end_date;
            } elseif ($by_date == 'payment_due_date') {
                $title_hutang = 'Laporan Hutang Berdasarkan Tanggal Jatuh Tempo Dari ' . $start_date . ' sampai ' . $end_date;
            } elseif ($by_date == 'payment_disbursement') {
                $title_hutang = 'Laporan Hutang Berdasarkan Tanggal Pembayaran Dari ' . $start_date . ' sampai ' . $end_date;
            }
            
        } else {
            $where_hutang = 'WHERE `payment_transaction`.`status` != 1 AND `payment_transaction`.`transaction_type` = "purchase order"';

            $where_piutang = "WHERE
                `payment_transaction`.`status` = -1 AND (`payment_transaction`.`transaction_type` = 'sales order' OR `payment_transaction`.`transaction_type` = 'point of sale')";

            $title_hutang = 'Laporan Hutang';
        }

        $sql = "
            SELECT
                `payment_transaction`.`transaction_code`,
                `payment_transaction`.`debit`,
                `payment_transaction`.`credit`,
                `payment_transaction`.`currency`,
                `payment_transaction`.`transaction_name`,
                `payment_transaction`.`transaction_category`,
                `payment_transaction`.`transaction_type`,
                `payment_transaction`.`invoice_number`,
                `payment_transaction`.`invoice_date`,
                `payment_transaction`.`payment_due_date`,
                `payment_transaction`.`payment_disbursement`,
                `payment_transaction`.`note`,
                `purchase_order`.`supplier_id`, 
                `contact`.`contact_name`,
                `tax`.`credit` AS `ppn`
            FROM
                `payment_transaction`
                LEFT JOIN
                    `purchase_order` ON `purchase_order`.`transaction_number` = `payment_transaction`.`transaction_code`
                LEFT JOIN
                    `contact` ON `contact`.`contact_id` = `purchase_order`.`supplier_id`
                LEFT JOIN
                    `tax`
                    ON
                    `tax`.`uid` = `payment_transaction`.`uid`

            {$where_hutang}

            ORDER BY {$by_date} ASC";

        $hutang = GenericModel::rawSelect($sql);

        $sql = "
                SELECT
                    `payment_transaction`.`transaction_code`,
                    `payment_transaction`.`debit`,
                    `payment_transaction`.`credit`,
                    `payment_transaction`.`currency`,
                    `payment_transaction`.`transaction_name`,
                    `payment_transaction`.`transaction_category`,
                    `payment_transaction`.`transaction_type`,
                    `payment_transaction`.`invoice_number`,
                    `payment_transaction`.`invoice_date`,
                    `payment_transaction`.`payment_due_date`,
                    `payment_transaction`.`payment_disbursement`,
                    `payment_transaction`.`note`,
                    `sales_order`.`customer_id`, 
                    `contact`.`contact_name`,
                    `tax`.`debit` AS `ppn`
                FROM
                    `payment_transaction`
                JOIN
                    `sales_order` ON `sales_order`.`transaction_number` = `payment_transaction`.`transaction_code`
                JOIN
                    `contact` ON `contact`.`contact_id` = `sales_order`.`customer_id`
                LEFT JOIN
                        `tax`
                        ON
                        `tax`.`uid` = `payment_transaction`.`uid`

            {$where_piutang}

            ORDER BY `payment_transaction`.`payment_due_date` ASC";

            $piutang = GenericModel::rawSelect($sql);
            
            //export excel
            if ($_GET['export_excel'] == 1) {
                $this->exportExcelHutang($hutang);
                die();
            }

            $header_script = '<link rel="stylesheet" href="' . Config::get('URL') . 'bootstrap-3.3.7/css/datepicker.css" media="screen"/><link rel="stylesheet" href="' . Config::get('URL') . 'bootstrap-3.3.7/css/excel-2007.css" media="screen"/>';
            $footer_script = '<script src="' . Config::get('URL') . 'bootstrap-3.3.7/js/bootstrap-datepicker.js"></script>
            <script>
            $(".datepicker").datepicker(); //date picker
            </script>';

            $this->View->render('hutang_piutang/index',
                  array(
                'title' => 'Laporan hutang piutang',
                'title_hutang' => $title_hutang,
                'header_script' => $header_script,
                'footer_script' => $footer_script,
                'activelink1' => 'finance',
                'activelink2' => 'hutang piutang',
                'hutang' => $hutang,
                'piutang' => $piutang,
                //'currency_rate' => FormaterModel::currencyRateBI(),
                'filter' => Request::post('filter'),
                )
            );
    }

    public function exportExcelHutang($hutang)
    {
       //styling header excel
        $head_style_array = [
            'font' => [
                'bold' => true,
                'size' => 15,
                'color' => array('rgb' => 'ffffff'),
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
            'borders' => [
                'top' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR,
                'rotation' => 90,
                'startColor' => [
                    'argb' => 'ff4d40',
                ],
                'endColor' => [
                    'argb' => 'c95149',
                ],
            ],
        ];

        /** Create a new Spreadsheet Object **/
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        //set default style
        $spreadsheet->getDefaultStyle()->getAlignment()->setWrapText(true); //make text wrap

        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Nama Supplier');
        $sheet->setCellValue('C1', 'Nominal DPP');
        $sheet->setCellValue('D1', 'PPN');
        $sheet->setCellValue('E1', 'Jumlah Tagihan');
        $sheet->setCellValue('F1', 'Cur');
        $sheet->setCellValue('G1', 'Nomer Invoice');
        $sheet->setCellValue('H1', 'Tgl Invoice');
        $sheet->setCellValue('I1', 'Tgl Jatuh Tempo');
        $sheet->setCellValue('J1', 'Tgl Bayar');
        $sheet->setCellValue('K1', 'Uraian Tagihan');
        
        $no = 2;
        foreach ($hutang as $key => $value) {
            $total_tagihan = $value->credit + $value->ppn;
            $sheet->setCellValue('A' . $no, ($no -1));
            $sheet->setCellValue('B' . $no, $value->contact_name);
            $sheet->setCellValue('C' . $no, $value->credit);
            $sheet->setCellValue('D' . $no, $value->ppn);
            $sheet->setCellValue('E' . $no, $total_tagihan);
            $sheet->setCellValue('F' . $no, $value->currency); 
            $sheet->setCellValue('G' . $no, $value->invoice_number);
            $sheet->setCellValue('H' . $no, (($value->invoice_date != '0000-00-00') ? date("d-M, y", strtotime($value->invoice_date)) : '')); 
            $sheet->setCellValue('I' . $no, (($value->payment_due_date != '0000-00-00') ? date("d-M, y", strtotime($value->payment_due_date)) : '')); 
            $sheet->setCellValue('J' . $no, (($value->payment_disbursement != '0000-00-00') ? date("d-M, y", strtotime($value->payment_disbursement)) : ''));
            $sheet->setCellValue('K' . $no, $value->note . ' (' . $value->transaction_code . ')'); 
            $no++;
        }

        //set width coloumn
        $sheet->getStyle('A1:K1')->applyFromArray($head_style_array); //warnai cell judul
        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getColumnDimension('D')->setAutoSize(true);
        $sheet->getColumnDimension('E')->setAutoSize(true);
        $sheet->getColumnDimension('F')->setAutoSize(true);
        $sheet->getColumnDimension('G')->setAutoSize(true);
        $sheet->getColumnDimension('H')->setAutoSize(true);
        $sheet->getColumnDimension('I')->setAutoSize(true);
        $sheet->getColumnDimension('J')->setAutoSize(true);

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename="hutang.xlsx"');
        $writer->save("php://output");
    }

}
