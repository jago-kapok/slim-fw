<!doctype html>

<html lang="en-150">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <title>
        <?php if (isset($this->title)) {
            echo $this->title;
        } else {
            echo Config::get('DEFAULT_TITLE');
        } ?>
    </title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
    <link rel="stylesheet" type="text/css" href="<?php echo Config::get('URL'); ?>bootstrap-3.3.7/css/kasir-auto-complete.css" />
    <style type="text/css">
        /* http://meyerweb.com/eric/tools/css/reset/
           v2.0 | 20110126
           License: none (public domain)
        */

        html, body, div, span, applet, object, iframe,
        h1, h2, h3, h4, h5, h6, p, blockquote, pre,
        a, abbr, acronym, address, big, cite, code,
        del, dfn, em, img, ins, kbd, q, s, samp,
        small, strike, strong, sub, sup, tt, var,
        b, u, i, center,
        dl, dt, dd, ol, ul, li,
        fieldset, form, label, legend,
        table, caption, tbody, tfoot, thead, tr, th, td,
        article, aside, canvas, details, embed,
        figure, figcaption, footer, header, hgroup,
        menu, nav, output, ruby, section, summary,
        time, mark, audio, video {
            margin: 0;
            padding: 0;
            border: 0;
            font-size: 100%;
            font: inherit;
            vertical-align: baseline;
        }
        /* HTML5 display-role reset for older browsers */
        article, aside, details, figcaption, figure,
        footer, header, hgroup, menu, nav, section {
            display: block;
        }
        body {
            line-height: 1;
        }
        ol, ul {
            list-style: none;
        }
        blockquote, q {
            quotes: none;
        }
        blockquote:before, blockquote:after,
        q:before, q:after {
            content: '';
            content: none;
        }
        table {
            border-collapse: collapse;
            border-spacing: 0;
        }

        /* Start Styling */
        * {
            -webkit-box-sizing: border-box;
            -moz-box-sizing: border-box;
            box-sizing: border-box;
        }

        /* Define the "system" font family */
        @font-face {
            font-family: system;
            font-style: normal;
            font-weight: 300;
            src: local(".SFNSText-Light"), local(".HelveticaNeueDeskInterface-Light"), local(".LucidaGrandeUI"), local("Ubuntu Light"), local("Segoe UI Light"), local("Roboto-Light"), local("DroidSans"), local("Tahoma");
        }
        body {
            overflow-x: hidden; /*This just stops me getting horizontal scrolling if anything overflows the width*/
            background-color: #fff;
            text-rendering: optimizeLegibility;
            font-family: "system"; /* use operating system font */
        }

        /*layout*/

        /*Flexbox gives us the flexiness we need. The top just stays put as there is no scrolling on the body due to the page never exceeding viewport height*/
        .Top {
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            z-index: 10;
        }
        /*This is our main wrapping element, it's made 100vh high to ensure it is always the correct size and then moved into place and padded with negative margin and padding*/
        .Container {
            display: flex;
            overflow: hidden;
            height: calc(110vh - 100px);
            position: relative;
            width: 100%;
        }

        /*All the scrollable sections should overflow and be whatever height they need to be. As they are flex-items (due to being inside a flex container) they could be made to stretch full height at all times if needed.
        WebKit inertia scrolling is being added here for any present/future devices that are able to make use of it.
        */
        .Middle,
        .Right {
            overflow: auto;
            height: auto;
            -webkit-overflow-scrolling: touch;
            -ms-overflow-style: none;
        }

        /*  Left and Right are set sizes while the Middle is set to flex one so it occupies all remaining space. This could be set as a width too if prefereable, perhaps using calc.*/

        .Middle,
        .Right {
            flex: 1;
            padding: 6px 3px 0 3px;
        }

        /* table format */
        #search-form {
            width: 100%;
            margin-top: 3px;
            margin-left: 3px;
            margin-right: 3px;
        }
        #hero-demo {
            height: 40px;
            font-size: 15pt;
            width: 100%;
            background-color: #c1fffd;
            color: #000;
            -webkit-box-shadow: 0 0 2px 1px rgba(0,0,0,.12);
            -moz-box-shadow: 0 0 2px 1px rgba(0,0,0,.12);
            box-shadow: 0 0 2px 1px rgba(0,0,0,.12);
            border: 1px solid rgba(50,50,50,.33);
        }
        input:focus#hero-demo,
        input[focus]#hero-demo{
            border: 1px solid #e2e2e2;
            background-color: #ff3549;
            color: #fff;
            animation: blink .5s step-end infinite alternate;
            -webkit-animation: blink .5s step-end infinite alternate;
        }
        #table-order {
            margin-top: 5px;
            padding: 1px;
            -webkit-box-shadow: 0 0 2px 1px rgba(0,0,0,.12);
            -moz-box-shadow: 0 0 2px 1px rgba(0,0,0,.12);
            box-shadow: 0 0 2px 1px rgba(0,0,0,.12);
            border: 1px solid rgba(50,50,50,.33);
        }
        #table-pembayaran {
            padding: 1px;
            -webkit-box-shadow: 0 0 2px 1px rgba(0, 0, 0, .12);
            -moz-box-shadow: 0 0 2px 1px rgba(0, 0, 0, .12);
            box-shadow: 0 0 2px 1px rgba(0, 0, 0, .12);
            border: 1px solid rgba(50, 50, 50, .33);
        }
        .ExcelTable2007 {
            border-width: 1px 0px 0px 1px;
            font-size: 11pt;
            font-weight: 100;
            border-spacing: 0px;
            border-collapse: collapse;
            width: 100%;
        }
        .ExcelTable2007 td {
            border: none;
            background-color: white;
            border: 1px solid #D0D7E5;
            border-width: 0px 1px 1px 0px;
        }
        .ExcelTable2007 th {
            vertical-align: middle;
            padding:7px 3px;
            background-color: #E4ECF7;
            text-align: center;
            border: 1px solid #9EB6CE;
            border-width: 0px 1px 1px 0px;
        }
        .ExcelTable2007 td.heading {
            background-color: #E4ECF7;
            border: 1px solid #9EB6CE;
            border-width: 0px 1px 1px 0px;
        }
        .ExcelTable2007 td input,
        .ExcelTable2007 td textarea {
            background: #c1fffd;
            height: 38px;
            padding: 0;
            margin: 0;
            border: 0;
            font-size: 11pt;
            width: 100%;
        }
        .ExcelTable2007 td select {
            background: #c1fffd;
            height: 37px;
            padding: 0;
            margin: 0;
            border: 0;
            font-size: 11pt;
            width: 100%;
        }
        #product-list td {
            padding: 5px;
        }
        #product-footer td {
            padding: 8px 5px;
        }
        /* make input blink on focus */

        .ExcelTable2007 td input:focus,
        .ExcelTable2007 td input[focus]{
            background-color: #ff0000;
            color: #fff;
        }
        input:focus::placeholder {
            color: white;
        }
        option {
            height: 34px;
        }
        button {
            color: #ffffff;
            padding: 5px 10px;
            border: solid 1px #c0c0c0;
            background-color: #14b6e8;
            border-radius:3px;
            cursor:pointer;
        }
        button:hover {
            color:#000!important;
            background-color:#ccc!important;
            cursor:pointer;
        }
        button:focus {
            color:#fff!important;
            background-color:#ff0000!important;
            cursor:pointer;
        }
        button:disabled,
        button[disabled]{
            border: 1px solid #999999;
            background-color: #858c99;
            color: #fff;
        }
        .click-button {
            padding: 5px;
            font-size: 1rem;
            width: 100%;
            min-height: 39px;
            height: 100%;
            box-shadow: none;
            border-radius: unset;
            border: none;
        }
        .product-button {
            padding: 5px;
            font-size: 1rem;
            width: 100%;
            min-height: 55px;
            height: 100%;
            float: left;
            -webkit-box-shadow: 0 0 2px 1px rgba(0, 0, 0, .12);
            -moz-box-shadow: 0 0 2px 1px rgba(0, 0, 0, .12);
            box-shadow: 0 0 2px 1px rgba(0, 0, 0, .12);
            border: 1px solid rgba(50, 50, 50, .33);
        }
        .color-0 {
            background-color: #009899;
            color: #fff;
        }
        .color-1 {
            background-color: #33adae;
            color: #fff;
        }
        .color-2 {
            background-color: #67c2c3;
            color: #000;
        }
        .color-3 {
            background-color: #01cc00;
            color: #fff;
        }
        .color-4 {
            background-color: #34d633;
            color: #000;
        }
        .color-5 {
            background-color: #66e067;
            color: #000;
        }
        .color-6 {
            background-color: #9fee00;
            color: #000;
        }
        .color-7 {
            background-color: #b3f134;
            color: #000;
        }
        .color-8 {
            background-color: #c5f466;
            color: #000;
        }
        .color-9 {
            background-color: #ffff01;
            color: #000;
        }
        .color-10 {
            background-color: #ffff33;
            color: #000;
        }
        .color-11 {
            background-color: #ffff67;
            color: #000;
        }
        .color-12 {
            background-color: #ffd300;
            color: #000;
        }
        .color-13 {
            background-color: #fedc34;
            color: #000;
        }
        .color-14 {
            background-color: #fee566;
            color: #000;
        }
        .color-15 {
            background-color: #ffaa01;
            color: #fff;
        }
        .color-16 {
            background-color: #ffbb34;
            color: #000;
        }
        .color-17 {
            background-color: #ffcc66;
            color: #000;
        }
        .color-18 {
            background-color: #ff7300;
            color: #fff;
        }
        .color-19 {
            background-color: #ff9034;
            color: #fff;
        }
        .color-20 {
            background-color: #ffac66;
            color: #000;
        }
        .color-21 {
            background-color: #fe0000;
            color: #fff;
        }
        .color-22 {
            background-color: #ff3334;
            color: #fff;
        }
        .color-23 {
            background-color: #ff6766;
            color: #000;
        }
        .color-24 {
            background-color: #cd0174;
            color: #fff;
        }
        .color-25 {
            background-color: #d83391;
            color: #fff;
        }
        .color-26 {
            background-color: #e266ac;
            color: #000;
        }
        .color-27 {
            background-color: #7209ab;
            color: #fff;
        }
        .color-28 {
            background-color: #8d3abc;
            color: #fff;
        }
        .color-29 {
            background-color: #ab6bcc;
            color: #000;
        }
        .color-30 {
            background-color: #3914af;
            color: #fff;
        }
        .color-31 {
            background-color: #6143bf;
            color: #fff;
        }
        .color-32 {
            background-color: #8971cf;
            color: #000;
        }
        .color-33 {
            background-color: #1241ab;
            color: #fff;
        }
        .color-34 {
            background-color: #4266bc;
            color: #fff;
        }
        .color-35 {
            background-color: #708ccd;
            color: #000;
        }

        /* Make Increment Number with CSS */
        table {
            counter-reset:section;
        }
        .count:before {
            counter-increment:section;
            content:counter(section);
        }

        /* text formating */

        .text-left {
            text-align: left;
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }

/* Tab Utama (Besar) */
.tabs {
  display: flex;
  flex-wrap: wrap;
  max-width: 700px;
  box-shadow: 0 1px 1px -1px rgba(0,0,0,0.3);
}
.input {
  position: absolute;
  opacity: 0;
}
.label {
  width: 100%;
  padding:8px 16px;
  background: #c1fffd;
  cursor: pointer;
  font-weight: bold;
  color: #7f7f7f;
  transition: background 0.1s, color 0.1s;
  border-left: 1px solid #ccc;
}
.label:hover {
  background: #d8d8d8;
}
.label:active {
  background: #ccc;
}
.input:focus + .label {
  box-shadow: inset 0px 0px 0px 3px #2aa1c0;
  z-index: 1;
}
.input:checked + .label {
  background: #f44336;
  color: #fff;
}
@media (min-width: 600px) {
  .label {
    width: auto;
    border-right: 1px #aaa: 
  }
}
.panel {
  display: none;
  background: #fff;
  width: 100%;
}
@media (min-width: 600px) {
  .panel {
    order: 99;
    width: 100%;
  }
}
.input:checked + .label + .panel {
  display: block;
}

        /*tab kecil/spesifik style */
        .tab{
            width:100%;
            overflow:hidden;
            color:#fff!important;
            background-color:#14b6e8!important;
            border-top: 1px solid #D0D7E5 !important;
            border-left: 1px solid #D0D7E5 !important;
            border-right: 1px solid #D0D7E5 !important;
            border-bottom: 0px solid #D0D7E5 !important;
        }
        .tab .tab-item {
            padding:8px 16px;
            float:left;
            width:auto;
            border:none;
            display:block;
            outline:0;
        }
        .tab .button-tab{
            white-space:normal;
            background-color: #14b6e8;
            box-shadow: none;
            -moz-box-shadow: none;
            -webkit-box-shadow: none;
            border-radius:0;
            margin: 0;
        }
        .tab:before,.tab:after{
            content:"";
            display:table;
            clear:both;
        }
        .w3-border{
            border:none;
            margin-top: 1px;
        }
        .button-tab:hover{
            color:#000!important;
            background-color:#ccc!important;
        }
        /* Colors */
        .buttton-red {
            color:#fff!important;
            background-color:#f44336!important;
        }
        .hidden {
            display: none;
        }

    </style>
<?php if (isset($this->header_script)) {echo $this->header_script;} ?>
</head>
<body>
<div id="flex">
    <div class="Top">
        <iframe name="selfFrame" style="display:none;"></iframe> <!-- prevent page reload on empty submit -->
        <form id="search-form" method="POST" onsubmit="insertProduct(); return false;" target="selfFrame">
            <input id="hero-demo" autofocus type="text" name="product-name" placeholder=" Ketik nama atau kode barang disini" autocomplete="off">
        </form>
    </div>
    <div class="Container">
        <div class="Middle">
            <table id="table-pembayaran" class="ExcelTable2007">
                <tbody>
                <tr>
                    <td>
                        <input type="number" placeholder="Diskon (%)" id="diskon-persen" autocomplete="off" style="width: 100%;" onkeyup="totalKembalian();" class="text-right big-input"/>
                    </td>
                    <td>
                        <input type="number" placeholder="Potongan Harga (Rupiah)" id="potongan-harga" autocomplete="off" style="width: 100%;" onkeyup="totalKembalian();" class="text-right big-input"/>
                    </td>
                <tr>
                    <td class="text-right">
                        <input type="text" placeholder="Member ID" id="member-id" autocomplete="off" style="width: 100%;" class="text-right big-input"/>
                    </td>
                    <td>
                        <button type="button" onclick="checkMember();" id="check-member-button" class="click-button">Check Member</button>
                    </td>
                </tr>
                <tr>
                    <td class="text-right">
                        <input type="text" placeholder="Jumlah Pembayaran" id="pembayaran" name="pembayaran" autocomplete="off" style="width: 100%;" onkeyup="totalKembalian();" class="text-right big-input"/>
                    </td>
                    <td >
                        <button type="button" onclick="finishAndSave();" id="save-button" class="click-button" disabled="disabled">SIMPAN</button>
                    </td>
                </tr>
                </tbody>
            </table>

            <table id="table-order" class="ExcelTable2007" style="display: none;">
                <thead>
                <tr>
                    <th id="total-price" colspan="3">TAGIHAN</th>
                    <th id="kembalian" colspan="4">KEMBALIAN</th>
                </tr>
                <tr>
                    <th  class="center">#</th>
                    <th class="center">Kode</th>
                    <th  class="center">Nama</th>
                    <th  class="center">Qty</th>
                    <th  class="center">Harga</th>
                    <th  class="center">Sub</th>
                    <th  class="center">Hapus</th>
                </tr>
                </thead>
                <tbody id="product-list">
                </tbody>
                <tbody id="product-footer">
                <tr>
                    <td  class="center text-center" colspan="5"><strong>Total</strong></td>
                    <td  class="text-right" id="sub-total" colspan="2"></td>
                </tr>
                </tbody>
            </table>
        </div>
        <div class="Right">
            <div class="tabs">
              <input class="input" name="tabs" type="radio" id="tab-1" checked="checked"/>
              <label class="label" for="tab-1">Dokter</label>
              <div class="panel">
                <?php include('beauty_clinic_doctor.php'); ?>
              </div>
              <input class="input" name="tabs" type="radio" id="tab-2"/>
              <label class="label" for="tab-2">Terapi</label>
              <div class="panel">
                <?php include('beauty_clinic_therapy.php'); ?>
              </div>
              <input class="input" name="tabs" type="radio" id="tab-3"/>
              <label class="label" for="tab-3">Obat</label>
              <div class="panel">
                <?php include('beauty_clinic_medicine.php'); ?>
              </div>
            </div><!-- /.tabs -->
        </div><!-- /.right -->
    </div>
</div>

<input type="hidden" name="" id="token" value="<?php echo GenericModel::guid(); ?>">
<script src="<?php echo Config::get('URL'); ?>bootstrap-3.3.7/js/kasir-auto-complete.min.js"></script>
<script>
    var productRowCounter = 1;
    localStorage.clear(); // clear local storage

    //check internet connection online or ofline
    window.addEventListener('load', function() {
        var status = document.getElementById("flex");

        function updateOnlineStatus(event) {
            status.style.display = 'inherit';
        }

        function updateOfflineStatus(event) {
            status.style.display = 'none';
            alert('SISTEM OFFLINE, CEK KONEKSI INTERNET!');
        }

        window.addEventListener('online',  updateOnlineStatus);
        window.addEventListener('offline', updateOfflineStatus);
    });


    // FUNCTION FOR PRINT
    function closePrint () {
        document.body.removeChild(this.__container__);
    }

    function setPrint () {
        this.contentWindow.__container__ = this;
        this.contentWindow.onbeforeunload = closePrint;
        this.contentWindow.onafterprint = closePrint;
        this.contentWindow.focus(); // Required for IE
        this.contentWindow.print();
    }

    function printPage (sURL) {
        var oHiddFrame = document.createElement("iframe");
        oHiddFrame.onload = setPrint;
        oHiddFrame.style.visibility = "hidden";
        oHiddFrame.style.position = "fixed";
        oHiddFrame.style.right = "0";
        oHiddFrame.style.bottom = "0";
        oHiddFrame.src = sURL;
        document.body.appendChild(oHiddFrame);
    }
    // END FUNCTION FOR PRINT

    function makeFloat(str) { // change string to float
        var result = str.replace (/,/g, ""); // remove comma in format money number, eg 2,500.68
        return  parseFloat(result);
    }

    function moneyFormat(x) {
        return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }

    function totalKembalian() {
        var totalprice = makeFloat(document.getElementById("sub-total").textContent);
        var pembayaran = makeFloat(document.getElementById("pembayaran").value);
        var diskonPersen = Number(document.getElementById("diskon-persen").value);
        var potonganHarga = Number(document.getElementById("potongan-harga").value);
        var setelahDiskon = totalprice - ((diskonPersen/100) * totalprice) - potonganHarga;
        var kembalian = pembayaran - setelahDiskon;
        //kembalian = parseFloat(kembalian).toFixed(0);
        document.getElementById('total-price').textContent = 'Tagihan: ' + moneyFormat(setelahDiskon);
        document.getElementById('kembalian').textContent = 'Kembalian: ' + moneyFormat(kembalian);
        if (kembalian >= 0) {
            document.getElementById("save-button").disabled = false;
        }
    }

    function deleteRow(btn) {
        var row = btn.parentNode.parentNode;
        row.parentNode.removeChild(row);
        totalPrice(); // Update totaal tagihan
        totalKembalian(); // update total tagihan
    }

    function updateQtyProd(product_code) { // Ajax to update quantity in
        var quantity = document.getElementById(product_code).textContent; //get value from input form
        var harga = document.getElementById(product_code + '-price').textContent;
        var subTotal = parseInt(quantity) * parseInt(makeFloat(harga)); //get value from input form
        document.getElementById('subTotal' + product_code).textContent = moneyFormat(subTotal);
        totalPrice(); // Update totaal tagihan
        totalKembalian(); // update total tagihan
    }

    function totalPrice() {
        var cls = document.getElementsByClassName("subTotal");
        var sum = 0;
        for (var i = 0; i < cls.length; i++){
            sum += makeFloat(cls[i].textContent);
        }
        if (sum == 0) {
            document.getElementById("sub-total").textContent = "TAGIHAN";
        } else {
            document.getElementById("sub-total").textContent = moneyFormat(sum);
        }
    }

    function insertProduct() {
        document.getElementById("table-order").style.display = 'table';
        var product = document.getElementById('hero-demo').value; //get value from input form
        if (product.indexOf(' -- ') === -1) {
            product_string = localStorage.getItem(product.toUpperCase()); //bikin kode barang ke huruf besar semua!.
        } else {
            product_string = product;
        }
        var product_array = product_string.split(" -- ");
        var product_code = product_array[0];
        clikProduct(product_code);
        return false;
    }

    function clikProduct(product) {
        var memberID = document.getElementById("member-id").value;
        var memberButton = document.getElementById("check-member-button").textContent;
        document.getElementById("table-order").style.display = 'table';
        product_string = localStorage.getItem(product);

        var product_array = product_string.split(" -- ");
        var product_name = product_array[0];
        var product_code = product_array[1];

        var product_price = product_array[2];
        var product_price_member = product_array[3];

        var table = "<tr>";
        table += '<td class="count heading"></td>';
        table += '<td class="saved-products product-code">' + product_code + "</td>";
        table += '<td>' + product_name + "</td>";
        table += '<td class="saved-products text-right" id="' + product_code + '" onkeyup="updateQtyProd(\'' + product_code + '\')" contenteditable="true">1</td>';

        if (memberID == '' && memberButton != 'VALID') {
            table += '<td class="text-right"><span class="text-right saved-products product-price" id="' + product_code + '-price">' + product_price + '</span><span class="hidden product-price-member" id="' + product_code + '-price-member">' + product_price_member + '</span></td>';
            table += '<td class="text-right subTotal" id="subTotal' + product_code + '">' + moneyFormat(product_price) + '</td>';
        } else {
            table += '<td class="text-right"><span class="text-right product-price hidden">' + product_price + '</span><span class="saved-products product-price-member" id="' + product_code + '-price">' + product_price_member + '</span></td>';
            table += '<td class="text-right subTotal" id="subTotal' + product_code + '">' + moneyFormat(product_price_member) + '</td>';
        }
        
        table += '<td class="text-center"><button class="saved-products button-delete" onclick="deleteRow(this)"/>hapus</button></td>';
        table += "</tr>";

        if (isProductCodeInserted(product_code)) {
            var product = document.getElementById('product-list');
            product.insertAdjacentHTML('afterbegin', table);
            document.getElementById("hero-demo").value = ""; // Clear input form, if not clreaed selected prooduct is not cleared (remember we are not refreshing web pages!)
        }
        document.getElementById("hero-demo").value = ""; // Clear input form, if not clreaed selected prooduct is not cleared (remember we are not refreshing web pages!)
        totalPrice(); // Update totaal tagihan
        totalKembalian(); // update total tagihan
        productRowCounter++;
        return false;
    }

    function finishAndSave()
    {
        //Make string from purchased product table
        var product_cell = document.getElementsByClassName("saved-products");
        var priceGross = document.getElementById("sub-total").textContent;
        var pembayaran = document.getElementById("pembayaran").value;
        var diskonPersen = document.getElementById("diskon-persen").value;
        var potonganHarga = document.getElementById("potongan-harga").value;
        var memberID = document.getElementById("member-id").value;
        var priceNet = document.getElementById("total-price").textContent;
        var kembalian = document.getElementById("kembalian").textContent;
        var token = document.getElementById("token").value;

        var i = 0;
        var product_string = [];
        while(product_cell[i] != undefined) {

            if (product_cell[i].textContent == 'hapus') {
                product_string.push(' ___ ');
            } else {
                product_string.push(product_cell[i].textContent + ' --- ');
            }
            i++;
        }//end while

        //Send the string to server
        var http = new XMLHttpRequest();
        var url = "<?php echo Config::get('URL'); ?>kasir/simpanClinic";
        var params = "product_list=" + product_string.join(' ') + "&priceNet=" + priceNet + "&priceGross=" + priceGross + "&diskonPersen=" + diskonPersen + "&potonganHarga=" + potonganHarga + "&pembayaran=" + pembayaran + "&kembalian=" + kembalian + "&memberID=" + memberID + "&token=" + token;
        http.open("POST", url, true);

        //Send the proper header information along with the request
        http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

        http.onreadystatechange = function() {//Call a function when the state changes.
            if(http.readyState === XMLHttpRequest.DONE && http.status == 200) {
                var salesCode = http.response;
                if (salesCode.indexOf('GAGAL') === -1) {
                    //alert(salesCode);
                    printPage(salesCode);
                    resetPage();
                } else {
                    alert(salesCode);
                }
            }
        }
        http.send(params);
    }

    function checkMember()
    {
        var memberID = document.getElementById("member-id").value;
        //Send the string to server
        var http = new XMLHttpRequest();
        var url = "<?php echo Config::get('URL'); ?>kasir/checkMember";
        var params = "memberID=" + memberID;
        http.open("POST", url, true);

        //Send the proper header information along with the request
        http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

        http.onreadystatechange = function() {//Call a function when the state changes.
            if(http.readyState === XMLHttpRequest.DONE && http.status == 200) {
                var salesCode = http.response;
                if (salesCode.indexOf('GAGAL') === -1) {
                    alert(salesCode);
                    document.getElementById('check-member-button').textContent = 'VALID';
                    changePriceToMember();
                } else {
                    alert(salesCode);
                }
            }
        }
        http.send(params);
    }

    function changePriceToMember()
    {
        //Product price regular
        var product_price = document.getElementsByClassName("product-price");
        for (var i = 0; i < product_price.length; i++) {
            product_price[i].classList.remove('saved-products');
            product_price[i].classList.add('hidden');
        }

        //Product price member
        var product_price_member = document.getElementsByClassName("product-price-member");
        for (var i = 0; i < product_price_member.length; i++) {
            product_price_member[i].classList.add('saved-products');
            product_price_member[i].classList.remove('hidden');
        }

        //change ID of price to calculate sub price (quantity * price used)

        var product_code = document.getElementsByClassName("product-code");
        for(var i = 0; i < product_code.length; i++) {
            var prodcode = product_code[i].textContent;
            //delete id product price yang lama (product-price asli)
            var divID = prodcode + '-price';
            document.getElementById(divID).id = "";

            //masukkan id baru (product-price) ke element id product-price-member
            var divID = prodcode + '-price-member';
            document.getElementById(divID).id = prodcode + '-price';
            updateQtyProd(prodcode);
        }
    }

    function uuidv4() {
        return ([1e7]+-1e3+-4e3+-8e3+-1e11).replace(/[018]/g, c =>
            (c ^ crypto.getRandomValues(new Uint8Array(1))[0] & 15 >> c / 4).toString(16)
        )
    }

    function resetPage() {
        var list = document.getElementById('product-list');
        while (list.hasChildNodes()) {
            list.removeChild(list.firstChild);
        }

        document.getElementById("table-order").style.display = 'none';
        document.getElementById("pembayaran").value='';
        document.getElementById("diskon-persen").value='';
        document.getElementById("potongan-harga").value='';
        document.getElementById("member-id").value='';
        document.getElementById("total-price").textContent='TAGIHAN';
        document.getElementById("kembalian").textContent='KEMBALIAN';
        document.getElementById("sub-total").textContent='';
        document.getElementById("token").value = uuidv4();
        document.getElementById("save-button").disabled = true;
    }

    function isProductCodeInserted(product_code) {
        var cell = document.getElementsByClassName("product-code");
        for(var i = 0; i < cell.length; i++) {
            if (cell[i].textContent == product_code) {
                var product = document.getElementById(product_code).textContent; //get qty from product table
                product = parseInt(product) + 1;
                document.getElementById(product_code).textContent = product;
                var price = document.getElementById(product_code + '-price').textContent;
                updateQtyProd(product_code);
                return false;
            }
        }
        return true;
    }

    //Save database from mysql to localstorage

    <?php
    foreach ($this->medicine_list as $key => $value) {
    $material_name = str_replace(array("'", '"'),'', $value->material_name);
    ?>
    localStorage.setItem("<?php echo $value->material_code; ?>", "<?php echo $material_name . " -- " . $value->material_code . " -- " . number_format($value->selling_price, 0) . " -- " . number_format($value->selling_price_member, 0); ?>");
    <?php } ?>

    <?php
    foreach ($this->doctor_list as $key => $value) {
    $material_name = str_replace(array("'", '"'),'', $value->material_name);
    ?>
    localStorage.setItem("<?php echo $value->material_code; ?>", "<?php echo $material_name . " -- " . $value->material_code . " -- " . number_format($value->selling_price, 0) . " -- " . number_format($value->selling_price_member, 0); ?>");
    <?php } ?>

    <?php
    foreach ($this->doctor_treatment_list as $key => $value) {
    $material_name = str_replace(array("'", '"'),'', $value->material_name);
    ?>
    localStorage.setItem("<?php echo $value->material_code; ?>", "<?php echo $material_name . " -- " . $value->material_code . " -- " . number_format($value->selling_price, 0) . " -- " . number_format($value->selling_price_member, 0); ?>");
    <?php } ?>

    <?php
    foreach ($this->therapist_treatment_list as $key => $value) {
    $material_name = str_replace(array("'", '"'),'', $value->material_name);
    ?>
    localStorage.setItem("<?php echo $value->material_code; ?>", "<?php echo $material_name . " -- " . $value->material_code . " -- " . number_format($value->selling_price, 0) . " -- " . number_format($value->selling_price_member, 0); ?>");
    <?php } ?>

    function openTab(evt, cityName) {
        var i, x, tablinks;
        x = document.getElementsByClassName("city");
        for (i = 0; i < x.length; i++) {
            x[i].style.display = "none";
        }
        tablinks = document.getElementsByClassName("tablink");
        for (i = 0; i < x.length; i++) {
            tablinks[i].className = tablinks[i].className.replace("buttton-red", "");
        }
        document.getElementById(cityName).style.display = "block";
        evt.currentTarget.className += " buttton-red";
    }

    //Auto Complete
    var demo1 = new autoComplete({
        selector: '#hero-demo',
        minChars: 1,
        source: function(term, suggest){
            term = term.toLowerCase();

            var choices = [<?php
                foreach ($this->medicine_list as $key => $value) {
                    $material_name = str_replace(array("'", '"'),'', $value->material_name);
                    echo "'{$value->material_code} -- {$material_name} -- " . number_format($value->selling_price, 0) . "', ";
                }
                foreach ($this->doctor_list as $key => $value) {
                    $material_name = str_replace(array("'", '"'),'', $value->material_name);
                    echo "'{$value->material_code} -- {$material_name} -- " . number_format($value->selling_price, 0) . "', ";
                }
                foreach ($this->doctor_treatment_list as $key => $value) {
                    $material_name = str_replace(array("'", '"'),'', $value->material_name);
                    echo "'{$value->material_code} -- {$material_name} -- " . number_format($value->selling_price, 0) . "', ";
                }
                foreach ($this->therapist_treatment_list as $key => $value) {
                    $material_name = str_replace(array("'", '"'),'', $value->material_name);
                    echo "'{$value->material_code} -- {$material_name} -- " . number_format($value->selling_price, 0) . "', ";
                }
                ?>];
            var suggestions = [];
            for (i=0;i<choices.length;i++)
                if (~choices[i].toLowerCase().indexOf(term)) suggestions.push(choices[i]);
            suggest(suggestions);
        }
    });
</script>
</body>
</html>