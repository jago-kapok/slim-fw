<?php

/**
 * NoteModel
 * This is basically a simple CRUD (Create/Read/Update/Delete) demonstration.
 */
class FormaterModel
{

    /**
     * Remove all non numeric character
     *
     * @param $user_name string username
     *
     * @return string
     */
    public static function getNumberOnly($number)
    {
        return trim(preg_replace( "/[^0-9]/", "", $number));
    }

    /**
     * Format number after decimal make striketrought
     *
     * @param $number
     *
     * @return string
     */
    public static function decimalNumberUnderline($number, $decimal = 2)
    {
      $number = number_format($number, $decimal);
      $number = explode('.', $number);

      return $number[0] . ((!empty($number[1])) ? '.<u><i>' . $number[1] . '<i><u>' : '');
    }

    /**
     * remove useless zero decimal, and only echo out decimal if not zero
     *
     * @param $number
     *
     * @return string
     */
    public static function trimTrailingZeroes($nbr, $decimal = 2) {
        $nbr = number_format($nbr, $decimal);
        return strpos($nbr,'.')!==false ? rtrim(rtrim($nbr,'0'),'.') : $nbr;
    }

    /**
     * Remove all character except number and dot (.)
     *
     * @param $string string
     *
     * @return integer
     */
    public static function floatNumber($string)
    {
        return floatval(preg_replace('/[^\d.]+/', '', $string));
    }


    /**
     * Change back nl2br (convert back nl2br)
     *
     * @param $user_name string username
     *
     * @return string
     */
    public static function br2nl( $input ) {
        return preg_replace('/<br\s?\/?>/ius', "\n", str_replace("\n","",str_replace("\r","", htmlspecialchars_decode($input))));
    }

    /**
    * Remove all characters except letters.
    *
    * @param string $string
    * @return string
    */
    public static function getLetterOnly($string) {
        return preg_replace( "/[^a-z]/i", "", $string );
    }

    /**
    * Sanitize string for database input
    *
    * @param string $string
    * @return string
    */
    public static function sanitize($string) {
        $string = htmlspecialchars($string, ENT_QUOTES);
        return trim(strip_tags($string));
    }

    public static function terbilangRupiah($nilai) {
        $nilai = abs($nilai);
        $huruf = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
        $temp = "";
        if ($nilai < 12) {
          $temp = " ". $huruf[$nilai];
        } else if ($nilai <20) {
          $temp = self::terbilangRupiah($nilai - 10). " belas";
        } else if ($nilai < 100) {
          $temp = self::terbilangRupiah($nilai/10)." puluh". self::terbilangRupiah($nilai % 10);
        } else if ($nilai < 200) {
          $temp = " seratus" . self::terbilangRupiah($nilai - 100);
        } else if ($nilai < 1000) {
          $temp = self::terbilangRupiah($nilai/100) . " ratus" . self::terbilangRupiah($nilai % 100);
        } else if ($nilai < 2000) {
          $temp = " seribu" . self::terbilangRupiah($nilai - 1000);
        } else if ($nilai < 1000000) {
          $temp = self::terbilangRupiah($nilai/1000) . " ribu" . self::terbilangRupiah($nilai % 1000);
        } else if ($nilai < 1000000000) {
          $temp = self::terbilangRupiah($nilai/1000000) . " juta" . self::terbilangRupiah($nilai % 1000000);
        } else if ($nilai < 1000000000000) {
          $temp = self::terbilangRupiah($nilai/1000000000) . " milyar" . self::terbilangRupiah(fmod($nilai,1000000000));
        } else if ($nilai < 1000000000000000) {
          $temp = self::terbilangRupiah($nilai/1000000000000) . " trilyun" . self::terbilangRupiah(fmod($nilai,1000000000000));
        }

        if($temp<0) {
          $hasil = "minus ". $temp;
        } else {
          $hasil = $temp;
        } 
        return $hasil;
  }

    public static function pagination($href, $total_record, $page, $limit, $search_string = '') {

        if ($total_record < $limit) {
          return '';
        }
        $last       = ceil( $total_record / $limit );
        $start      = ( ( $page - 3 ) > 0 ) ? $page - 3 : 1;
        $end        = ( ( $page + 3 ) < $last ) ? $page + 3 : $last;
     
        $html       = '<ul class="pagination pull-right hidden-print">';
     
        $class      = ( $page == 1 ) ? "disabled" : "";
        $html       .= '<li class="' . $class . '"><a href="' . Config::get('URL') . $href . '/' . ($page - 1) . '/' . $limit . '/' . $search_string . '">&laquo; Prev</a></li>';
     
        if ( $start > 1 ) {
            $html   .= '<li><a href="' . Config::get('URL') . $href . '/1/' . $limit . '/' . $search_string . '">1</a></li>';
            $html   .= '<li class="disabled"><span>...</span></li>';
        }
     
        for ( $i = $start ; $i <= $end; $i++ ) {
            $class  = ( $page == $i ) ? "active" : "";
            $html   .= '<li class="' . $class . '"><a href="' . Config::get('URL') . $href . '/' . $i . '/' . $limit . '/' . $search_string . '">' . $i . '</a></li>';
        }
     
        if ( $end < $last ) {
            $html   .= '<li class="disabled"><span>...</span></li>';
            $html   .= '<li><a href="' . Config::get('URL') . $href . '/' . $last . '/' . $limit . '/' . $search_string . '">' . $last . '</a></li>';
        }
     
        $class      = ( $page == $last ) ? "disabled" : "";
        $html       .= '<li class="' . $class . '"><a href="' . Config::get('URL') . $href . '/' . ($page + 1) . '/' . $limit . '/' . $search_string . '">Next &raquo;</a></li>';
     
        $html       .= '</ul>';
     
        return $html;
  }

  public static function currencyRateBI() {
      $content = file_get_contents('https://www.bi.go.id/id/moneter/informasi-kurs/transaksi-bi/Default.aspx' );
      $content = explode ('<table class="table1" cellspacing="0" rules="all" border="1" id="ctl00_PlaceHolderMain_biWebKursTransaksiBI_GridView1">',$content);
        $dom = new DOMDocument;
          $dom->loadHTML($content[1]);
          $rows = array();
          foreach ($dom->getElementsByTagName('tr') as $tr) {
            $cells = array();
            foreach ($tr->getElementsByTagName('td') as $r) {
              $cells[] = $r->nodeValue;
            }
            $rows[] = $cells;
          }
          
          $kurs = array();
          $kurs['IDR'] = array(
                  'jual' => 1,
                  'beli' => 1
                );
          $kurs['USD'] = array(
                  'jual' => 14097,
                  'beli' => 13957
                );
          $kurs['EUR'] = array(
                  'jual' => 15980.36,
                  'beli' => 15820.26
                );
          /*
          for ($i=1; $i <= 25; $i++) {
            $cur_simbol = trim($rows[$i][0]);
            $jual = floatval(preg_replace("/[^-0-9\.]/","",$rows[$i][2])) / floatval(preg_replace("/[^-0-9\.]/","",$rows[$i][1]));
            $beli = floatval(preg_replace("/[^-0-9\.]/","",$rows[$i][3])) / floatval(preg_replace("/[^-0-9\.]/","",$rows[$i][1]));

            $kurs[$cur_simbol]  = array(
                  'jual' => $jual,
                  'beli' => $beli
                );
          } */

        return $kurs;
  }


}
