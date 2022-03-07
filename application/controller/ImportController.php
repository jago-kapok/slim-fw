<?php


class ImportController extends Controller
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
        //echo 'exit;'; exit;
        
    }

    public function index()
    {
        echo "import only please :)";
    }

    
    public function importMaterial()
    {
        // format: kode -_- nama -_- keterangan -_- stock -_- satuan -_- safety stock
        $seed = "2020-10-27-_-26BAA8FD10D0B9C017091D02296C34977648D8F39A
2020-11-01-_-9E866F732CDACB4421FEF957EC287D8E141470D2D8
2020-11-06-_-1262B8390712A900C67BB31CA5E97477B4F980959D
2020-11-18-_-A80CD6E184399871A486DCE6B8E9643FB886248782
2020-11-18-_-EFB49D1E0AB670792506A45ECD5B3D22E8CA4FC58C
2020-12-16-_-6605F12B56F90DC3FEC823CF45E8205B3579704117
2020-11-30-_-8A14D3566484524A2C6449C2C9D430B71B2C232B8A
2020-12-16-_-9B5B6EEA9C9DF89F7BFAB76CC98B335EEA64B2ABF2
2020-11-24-_-87C455D92DB9D171F12AD63012AA38F191227D5808
2020-12-03-_-51AB30CC4B7361B23132446F4BB09CF5BF3B81DE85
2020-12-21-_-70B3888978EA3E2269FA7B1315929D7A2011E7A7A8
2020-12-05-_-916C8EED392D061D36C270EC1B89678F6B6FB8C2F8
2020-12-05-_-A4B531D61AA57700AF5BD23990CAA0795ABCBA7861
2020-12-23-_-A606A3B5364959463E7A227061472C2C93B09976A0
2020-12-07-_-2173BBAE11BE155A1D23D52C066F440C8BE1AC7281
2020-12-07-_-5DA9DF98253BFBC7864F41E2F2092AEE08AC9AA40A
2021-01-08-_-1CB1634DF2D6ADB59D10DF815A68F2971CF28F43B3
2020-12-11-_-4AA984ACCCF97331EFC689A6293532AFE49278F81D
2020-12-11-_-543DF62C4CA0A4B7206CA48D4221D43A6788C421CA
2020-12-28-_-46DA514CA39187B828AAE33EF67B77C65D352F3C94
2020-12-12-_-6B6F263D58F5A2771B7E37C19E7E39130246C5E812
2020-12-03-_-F32B57691A3BB049E6BCE328478227B42EC7712643
2021-01-06-_-53BECC2D893353B2B800BF5E530A0C41676DA42D16
2021-01-06-_-85032A80A3429D17E95AE1BBFBB32E5A9EA7C6F1A4
2020-12-15-_-EF8BAFCB5E9F22F723A072D1B604AA0E51D3E33FB6
2021-01-18-_-43A073A5280787BBE49639E5A6E85E44F89CBEEE60
2021-01-06-_-7ACEA4C020072214D0EF813E545A8156D83829EA9E
2020-12-22-_-5C542570567FE05262787BCECD5D4CB2C0A142FB85
2021-01-07-_-720EC7C2254D831CD94EFE0D48AD7B1030C2F913BC
2020-12-22-_-727E968E568136048341EFA8716BB5F05577EDF7A8
2020-12-22-_-95C72B87BF2D9CA073014654A1DFE34B77C60C6259
2020-12-22-_-D4150E97F782F22FA8C2D0975175F9F8DCFFFC5168
2020-12-22-_-DED9FD3B9005F80F2190E21923E748935D6F08A33E
2020-11-17-_-E8FDE6A53B0365837B8D6A6F11BD0D30400D0F6D73
2021-01-25-_-33A96063CC06643858158CA5DB36D4CA8EE00B24FD
2021-01-11-_-9945DD221E8C7B653352D46332B2E76FDD25258180
2020-12-28-_-B323C8B715695B35ED16E7F0DD8A44897FA6CCBB64
2020-12-29-_-27183B18D6E57D8BBD2590B815EFA6FE68F861735B
2020-12-31-_-2D7DB5DA557C9166588EA434254C3ED6FAAF2553E2
2021-01-01-_-6CCF73DC5DD32E022B1384C9C6BEB7A7938D7801A0
2021-02-04-_-1481F8DE2FD4E5B3FFE7B57CBBC99E1B6AAA632182
2021-02-04-_-508DA9628996BFEAE3EECDE356CAF266AC0A686BCF
2021-01-06-_-D669334C89E492E3DBB38371C84C7ACE33532F273F
2021-01-06-_-BA777E58BEBC373F46CEEAFE4A27D7E6B8003CFE51
2021-01-07-_-39D743997124284DDDD6BE231183FB09EBBCC26633
2021-01-08-_-245F1CE30738731454A7FB6EDB564714BD1E6C9F9A
2021-01-08-_-8DDA39A7DC7030B050D25DE27BEC7CA1DA2A6F5E8A
2021-01-26-_-4EB376E1519FF04B33761F2658EB0FC4019FC053F8
2021-01-26-_-C162A9F993D8811AD9ADB3D766C40C20C8A6FE50FE
2021-01-26-_-C90F213BA68865F747483AE6E4A35C744887796723
2021-01-26-_-D11E95586707F6851F2107CCE91C64323598E99B30
2021-01-11-_-2C6466B945A74A8CE73248E07807523C78B53890C7
2021-01-27-_-51BA25CFFA6B320B3B03DA42F6F395141B6C1F1E6E
2021-01-11-_-181BE7D635455A41337B86E6D0F0DCCADF553DFFE1
2021-01-11-_-AD75E793C0252EC36CE64214C880887BA116984474
2021-01-11-_-D607BAC19A4634175FFAFAAA716F679A39D5DE90C8
2021-01-11-_-FCA347AB6C171E14C1C6ED42990AA2075AC2C753C9
2021-01-13-_-3DE85A63584F8D0CD25515D42C3F9AAE09BAD318D4
2021-01-12-_-94F6F74C8021E9511091DCB1FAB838E518C3ECEC84
2020-01-27-_-D656ECF3EF8552564B1D6D87AA39C4C323D82BC50B
2020-01-27-_-DAAA71FCF6A2C766A9D02F74F1DAB713D8F5B82CB9
2021-01-13-_-514E0B703F38D1C1AEEE5AC63A83A110F9D47F039D
2021-01-14-_-C55D0131ED1C209BD6227E33025D17F3089230AF22
2021-01-15-_-04E7B6E745AB456C71BD17E2CA9B1B4DB9F1166ED3
2021-02-15-_-0A5FC1BA595226AAF78A8349EA09C5A1BDE86D9B27
2021-02-01-_-5466EAF752AEB7A75A70A9DD9B11C641B9D202C6E1
2021-02-15-_-54FE8D4E9DC6079282266907CC82C56F2501AB73FB
2021-01-15-_-5CCFE54C88199D4B5AC153DB9F4B9CFBEC2154A066
2021-01-15-_-6088B2B144BA05713E4F7B25DC142BECE8EB37B2FC
2021-01-15-_-7DD70264322DF65889C35622F7B10FB1A501888637
2021-02-15-_-918FC04650B8D27EA5C836BC07A1AE0BCF4B3BBE48
2021-02-15-_-B173E82CFE0736913694F1E158B6B9983C522C446F
2021-01-18-_-112AF953993080B222EA45ECCB16FE25601FFF93C0
2021-01-18-_-42B10C9E4EDD79860DFA8640F04A7B19EDE46833D6
2021-02-03-_-6C198753952E98ADBF0FA0552EE82CF1656BD360F1
2021-02-18-_-77DB7777256CA7995030F730628E9614F867E93FEC
2021-01-18-_-27655046DCCE30B42F0D465FFC5E8050BE652571A1
2021-01-20-_-EC909DDA7A71065DD9821A0A4B8256945A470CA35D
2021-01-22-_-46FCF13D4A6B4A8E2EF64D4A0B5C6D3F11A923E24D
2021-01-22-_-79DE7D177A810CA67D84B9EB29D3C13C92E24429CA
2021-01-22-_-9B0AF916341A43FD72DC6C82EF79122227E368CFE5
2021-01-25-_-DA618E8C6FF3B7F06EED8BCD72BEAD56F9D08D777C
2021-01-24-_-DC427CCB0306E8D6A2B3F0E3ABC1306F61F9232B26
2021-02-24-_-0FC4BA96DB68367BE1A1567479C62BF2BB67F0B35E
2021-01-25-_-45A4FE50D9268331A4E5A43137EBC4BF4C0AAFA3B8
2021-01-25-_-5407DA55D468599F4244908336EE6094080AF85221
2021-01-25-_-DFC36AD1987191D9BBD47A45477466DC7E5E769871
2021-02-24-_-FD745FB4D9CBCDED50A69F9E9AFC7CF160B06D5840
2021-01-25-_-F77991431368A61385B109FDC407392B9BD5BEA4F1
2020-05-27-_-511B3C7C61263F3C3B87BE46331C6E3534EC34484D
2021-01-26-_-A042855818265DCF1D233770F1983D56FF4AFCDDC4
2021-01-18-_-4CCCCAB072F12DF1C2E968C9B60994F165EDF7F45B
2021-01-18-_-75BBAAB57D8A18959A01EB8B6B4F3D5723956477C4
2021-01-28-_-7A6C47708AC466009143E2F9637829D05A88BB2564
2021-01-18-_-AEEB89E80A866EA4818F068C1B15806DFB7D549766
2021-01-28-_-E03541BBB01933D2AF65C672C7CE2DC1762A05CA62
2021-01-30-_-58B2190B000A7E0DD1280E8D529A5C195AB206C275
2021-02-01-_-90567B52F70D3EA9AFA1A3E3185574945E754FF57B
2021-01-30-_-AE1613423D3CA919233397A83C42A018EF37EAADEE
2021-02-01-_-CFC04355DDFA314D668186A603BA5325B25B21B436
2021-01-18-_-DFCED179679E932345C19950DE581EFC1B641BC8C7
2021-02-01-_-CCB169C1C25D61926C07817AE644B3B13C411E04BF
2021-02-17-_-D1DB37278516C82E03A7500C3A11D4610BA7B1E531
2021-02-17-_-F4471092600165BE1A7E49CEC47B376FB6F419D1E8
2021-02-04-_-95F8B8E25E4DC0EE1FDA1491BD8535720DB5C7FE8F
2021-03-01-_-A7A45C860B29F02F0CC06BC5DF96F5AD4E2D429C7C
2021-03-03-_-DC4006D8E828C1C6133C6453BF090C732C73FDEDAF
2021-03-03-_-F6B158720CDD8032C41643FB5E5E8FAD232AFC330F
2021-02-10-_-1D627E943FE81A11F0E3EF014CA950C70F39CAAA36
2021-02-25-_-0DF389D14648800B740EB3DE2F7EAB0ACCEFC8DAF5
2021-03-05-_-30E476211D007591EDC1B89172468FA296DFCC6ABA
2021-02-11-_-5B70D6F6AD68E10EE8607D3E2C25D78AA26C1755AF
2021-02-26-_-CA8353E26EA59602237DB61F1D70891E97C9BA5CD7
2021-02-15-_-2110729114FC169EA01C799A693FB4ADA1E7D92103
2021-02-16-_-EA60414CBC0DFE6BBF1E10FB2E9E2A4AC765785B3A
2021-02-17-_-13E7B26D1EDBDAB684497F225B6E0A615D4AD77EC0
2021-02-18-_-28926496C6DE16AEE1D63B074418B7569C00F14DFE
2021-02-16-_-308FAAC79D78974EB60CAEEE62AF3391D3CD78A6C2
2021-03-08-_-3E12306A3CBEA79BD02E657B27791F8C7B7BB1BF4C
2021-02-18-_-5DCF173E25AE78F9FB058F2AF2FB78652F3BCE7CF7
2021-02-18-_-DA574C60412E586C9A9D17CB23299254244F6E2378
2021-02-17-_-34A517D947C68C927473BFAC9D7F619162A5E5FAA0
2021-03-05-_-4907282B8CBA6B6C30F848B0196595E5825A1A79CF
2021-02-19-_-03535CBB46CE3EA23F311B61D5EA668D372EB81088
2021-02-22-_-D9F4E073D9B094B6CF8238768540ED8CE2F336E792
2021-02-23-_-307B32429895F7357E162E273231BDB6242006EAED
2021-02-23-_-80A57B962DDFCE9B4C53A485CD40801BFFB31326FE
2021-02-23-_-0047993891A1B79B7934336F4D6F72F51ADF3EBC0D
2021-02-26-_-187E65140D0F2F8289E05E42F784BD7440231E5C2D
2021-02-26-_-32D63FC293D059D0B94FA5E3894A0FFA5B81BD5070
2021-02-26-_-33C8EC53C1DB2B4A0E1FD2C2A1529D5F4D59FAA6DA
2021-02-26-_-6B19664F754C3EE8D8E01A7719E101D6D1D902137E
2021-02-26-_-B59A1F4E80025A5832EA1F321BC6DD7EFD80905DF8
2021-03-01-_-1D2B85470891251C0FBABAE79C9172E30E6B5B8B5E
2021-03-01-_-8C29F101EC6ABEA959D0713EC31AA0A06DE9CE3045
2021-03-01-_-D39B10D8F9FE176B2E1432C1DDCB932AB4898D295B";
        $seed_array = $this->multiexplode(array("\n", "\r", "\x0B"),$seed); // deleminiter must be an array
        /*
        " " (ASCII 32 (0x20)), an ordinary space.
        "\t" (ASCII 9 (0x09)), a tab.
        "\n" (ASCII 10 (0x0A)), a new line (line feed).
        "\r" (ASCII 13 (0x0D)), a carriage return.
        "\0" (ASCII 0 (0x00)), the NUL-byte.
        "\x0B" (ASCII 11 (0x0B)), a vertical tab.
        */

        // remove from array empty element
        $seed_array = array_filter($seed_array);
        foreach ($seed_array as $key => $value) {
           //echo $no . ' ' . $value . '<br>';
            //$cleaned_value = trim($value);
            $this->updatePayment($value);
            //$this->insertMaterial($cleaned_value);
        }
    }

    
    public function insertMaterial($string)
    {

        $exploded = $this->multiexplode(array("++//++"),$string); // deleminiter must be an array
        $material_name = trim($exploded[0]);
        $unit = trim($exploded[1]);
        $material_category = trim($exploded[2]);
        $material_type = trim($exploded[3]);
        $selling_price = trim($exploded[4]);
        $selling_price_member = trim($exploded[5]);
        $material_code = trim($exploded[6]);
    
        if (empty($material_code)) { // material code tidak diisi, sistem menggenerate otomatis

                // create material code
                // Delimit by multiple spaces, hyphen, underscore, comma
                $words = preg_split("/[\s,_-]+/", $material_name);
                $acronym = "";
                foreach ($words as $w) {
                  $acronym .= strtoupper($w[0]);
                }
                // check apakah kode material sudah ada atau belum
                $field = "material_code";
                $value = $acronym;
                $material_code_exist = GenericModel::isExist('material_list', $field, $value);
                if ($material_code_exist) {
                    $query = "SELECT `material_code` FROM `material_list` WHERE `material_code` LIKE '%$acronym%' ORDER BY `material_code` DESC, `autoint` DESC LIMIT 1";
                    $max = GenericModel::rawSelect($query, false);
                    $material_number = FormaterModel::getNumberOnly($max->material_code) + 1;
                    $material_code = FormaterModel::getLetterOnly($acronym) . $material_number;
                } else {
                    // create material code
                    // Delimit by multiple spaces, hyphen, underscore, comma
                    $words = preg_split("/[\s,_-]+/", $material_name);
                    $acronym = "";
                    foreach ($words as $w) {
                      $acronym .= strtoupper($w[0]);
                    }
                    $material_code = $acronym;
                }
        }

        $insert = array(
                        'material_code' => $material_code,
                        'material_name' => $material_name,
                        'unit' => $unit,
                        'material_category' => $material_category,
                        'material_type' => $material_type,
                        'selling_price' => $selling_price,
                        'selling_price_member' => $selling_price_member,
                        'created_timestamp' => '2018-01-01 00:00:01',
                        'modified_timestamp' => '2018-01-01 00:00:01',
                        'creator_id'    => 'ILMUI'
                        );

        if (GenericModel::insert('material_list', $insert)) {
            echo " oke $material_code <br>";
        } else {
           echo $string . " : $material_code <br>";
        }
    }

    /**
     *
     */
    public function importBom()
    {
        // format: job type -_- kode_barang -_- note -_-  jumlah -_- kode_bom
        //$seed = "";
        $formulation_code = Request::post('formulation_code');
        $seed = Request::post('import_post');
        $seed_array = explode($formulation_code, $seed); // deleminiter must be an array

        // remove from array empty element
        $seed_array = array_filter($seed_array);
        //Debuger::jam($seed_array);exit;
        foreach ($seed_array as $key => $value) {
           //echo $no . ' ' . $value . '<br>';
           $this->insertBom($value, $formulation_code);
        }

        $feedback_positive = Session::get('feedback_positive');
        //$feedback_negative = Session::get('feedback_negative');
        // echo out positive messages
        if (isset($feedback_positive)) {
            $success_message = 'SUKSES, ' . count($feedback_positive) . ' material berhasil disimpan';
            //echo Config::get('URL') . 'pos/printNotaPenjualan/?so_number=' . urlencode($so_number);
        }
        // echo out negative messages
        if (isset($feedback_negative)) {
            $fail_message = 'GAGAL!, ' . count($feedback_positive) . ' material gagal disimpan';
        }
        // RESET counter feedback to unconfuse user
        Session::set('feedback_positive', null);
        //Session::set('feedback_negative', null);

        //give message to user
        Session::add('feedback_positive', $success_message);
        //Session::add('feedback_negative', $fail_message);

        Redirect::to('inventory/editFormulation/?find=' . urlencode($formulation_code));
    }

    public function insertBom($string, $formulation_code)
    {

        $exploded = $this->multiexplode(array("-_-"),$string); // deleminiter must be an array
        $job_type = trim($exploded[0]);
        $material_code = trim($exploded[1]);
        $note = trim($exploded[2]);
        $quantity = trim($exploded[3]);

        $insert = array(
                        'uid' => GenericModel::guid(),
                        'job_type' => $job_type,
                        'material_code' => $material_code,
                        'formulation_code' => $formulation_code,
                        'unit_per_quantity' => $quantity,
                        'note' => $note,
                        'creator_id'    => 'ILMUI'
                        );
        GenericModel::insert('material_list_formulation', $insert);
    }

    public function importEmployee()
    {

        //$seed = '201205001 -_- Grafandy Dwi Eris Kawanto -_- 3515180806830000 -_- Laki - Laki -_- K/2 -_- Jl. Jatisari Permai Blok P-7 Pepelegi, Waru - Sidoarjo -_- 081230525146';
        $seed = '';
        $seed_array = $this->multiexplode(array("\n", "\r", "\x0B"),$seed); // deleminiter must be an array
        /*
        " " (ASCII 32 (0x20)), an ordinary space.
        "\t" (ASCII 9 (0x09)), a tab.
        "\n" (ASCII 10 (0x0A)), a new line (line feed).
        "\r" (ASCII 13 (0x0D)), a carriage return.
        "\0" (ASCII 0 (0x00)), the NUL-byte.
        "\x0B" (ASCII 11 (0x0B)), a vertical tab.
        */

        // remove from array empty element
        $seed_array = array_filter($seed_array);
        foreach ($seed_array as $key => $value) {
           //echo $no . ' ' . $value . '<br>';
            $cleaned_value = FormaterModel::sanitize($value);
            $this->insertEmployee($cleaned_value);
        }
    }

    
    public function insertEmployee($string)
    {
        $exploded = $this->multiexplode(array("-_-"),$string); // deleminiter must be an array
        $uid = FormaterModel::sanitize($exploded[0]);
        $full_name = FormaterModel::sanitize($exploded[1]);
        $address_street = FormaterModel::sanitize($exploded[5]);
        $phone = FormaterModel::sanitize($exploded[5]);
        $gender = FormaterModel::sanitize($exploded[3]);
        $nik = FormaterModel::sanitize($exploded[2]);
        $note = FormaterModel::sanitize($exploded[4]);
        $user_name = $this->multiexplode(array(" "),$full_name);
        $user_name =$user_name[0];
        //var_dump($user_name);echo "<br>";

        $insert = array(
                        'uid' => $uid,
                        'user_name' => $user_name[0],
                        'full_name' => $full_name,
                        'address_street' => $address_street,
                        'phone' => $phone,
                        'gender' => $gender,
                        'nik' => $nik,
                        'note' => $note,
                        'user_name' => $user_name,
                        'department' => 'director',
                        'grade' => 900,
                        'creator_id'    => SESSION::get('uid')
                        );

        if (GenericModel::insert('users', $insert)) {
            echo " OKE $full_name <br>";
        } else {
           echo $string . " Not <br>";
        }
    }

    public function multiexplode ($delimiters,$string) // deleminiter must be an array
    {
        $ready = str_replace($delimiters, $delimiters[0], $string);
        $launch = explode($delimiters[0], $ready);
        return  $launch;
    }

    //Duplicate so order list ke production order list

    public function duplicateSOList()
    {
        /*
        //duplicate product list
        $sql = "SELECT
                    `sales_order_list`.`material_code`,
                    `sales_order_list`.`quantity`,
                    `sales_order_list`.`unit`,
                    `sales_order_list`.`note`,
                    `job_order`.`job_number`
                FROM
                    `job_order`
                LEFT JOIN
                    `sales_order_list`
                        ON
                    `sales_order_list`.`transaction_number` = `job_order`.`job_reverence`
                WHERE
                    `job_order`.`job_category` = 'production order'";

        $product_list = GenericModel::rawSelect($sql);

        foreach ($product_list as $key => $value) {
            $insert = array(
                        'uid'    => GenericModel::guid(),
                        'material_code'    => $value->material_code,
                        'quantity'    => $value->quantity,
                        'unit'    => $value->unit,
                        'job_number' => $value->job_number,
                        'note' => $value->note,
                        'creator_id'    => SESSION::get('uid')
                    );
            GenericModel::insert('job_order_product_list', $insert);
        }

        //duplicate forcasting to Job order Raw material list
        /*
        $sql = "SELECT
                    `transaction_number`,
                    `job_type`,
                    `sub_job_type`,
                    `material_code`,
                    `quantity`,
                    `purchase_price`,
                    `note`
                FROM
                    `production_forcasting_list`
                WHERE
                    `transaction_number` = '{$so_number}'";

        $production_forcasting = GenericModel::rawSelect($sql);

        foreach ($production_forcasting as $key => $value) {
            $insert = array(
                        'uid'    => GenericModel::guid(),
                        'job_type'    => $value->job_type,
                        'sub_job_type'    => $value->sub_job_type,
                        'material_code'    => $value->material_code,
                        'quantity'    => $value->quantity,
                        'job_number' => $job_number,
                        'creator_id'    => SESSION::get('uid')
                    );
            GenericModel::insert('job_order_material_list', $insert);
        }
        */
        echo 'finish';
    }

    public function updateStock()
    {
        /*
        $sql = "SELECT
                        *
                FROM
                        `material_list_stock`";

        $product_list = GenericModel::rawSelect($sql);

        foreach ($product_list as $key => $value) {
            $sql ="
                SELECT
                    uid
                FROM
                    `material_list_in`
                WHERE
                    `material_code` = '{$value->material_code}'
                ORDER BY
                    `created_timestamp` DESC
                LIMIT 1
                ";
            $uid_material_code = GenericModel::rawSelect($sql, false);
            //echo $uid_material_code->uid . '<br>';
            if (empty($uid_material_code->uid)) {
                //echo $value->material_code . '<br>';
                $insert = array(
                                    'uid'    =>  GenericModel::guid(),
                                    'material_code'    => $value->material_code,
                                    'quantity_stock'    => $value->stock,
                                    'transaction_number'    => 'saldo awal',
                                    
                                    'note'    => 'Stock Awal (SALDO)',
                                    'creator_id'    => 'ILMUI',
                                    
                                    
                                    );
                //GenericModel::insert('material_list_in', $insert);
            }
            //Debuger::jam($post_array);exit;
            $update = array(
                        'quantity_stock'    => $value->stock,
                        );
            //GenericModel::update('material_list_in', $update, "`uid` = '{$uid_material_code->uid}'");

        }

        
        echo 'finish';
        */
    }

    public function updateMaterialListPurchase()
    {
        
        $sql = "SELECT
                        `purchase_price`,
                        `purchase_price_discount`,
                        `purchase_currency`,
                        `unit`,
                        `material_code`,
                        MAX(`created_timestamp`) AS `created_timestamp`

                FROM
                        `purchase_order_list`
                    GROUP BY
                        `material_code` DESC";

        $product_list = GenericModel::rawSelect($sql);
         //Debuger::jam($product_list);exit;
        foreach ($product_list as $key => $value) {
            //Debuger::jam($post_array);exit;
            $purchase_price = $value->purchase_price - $value->purchase_price_discount;
            $update = array(
                        'purchase_price'    => $purchase_price,
                        'purchase_currency'    => $value->purchase_currency,
                        'purchase_unit'    => $value->unit,
                        );
            GenericModel::update('material_list', $update, "`material_code` = '{$value->material_code}'", false);
        }
        echo 'finish';
    }

    public function updatePayment($value)
    {
        $exploded = $this->multiexplode(array("-_-"),$value); // deleminiter must be an array
        $invoice_date = trim($exploded[0]);
        $uid = trim($exploded[1]);
            $update = array(
                        'invoice_date'    => $invoice_date,
                        );
            GenericModel::update('payment_transaction', $update, "`uid` = '{$uid}'", false);
        echo 'sukses';
    }

}
