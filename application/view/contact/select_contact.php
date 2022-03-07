<!doctype html>

<html lang="en">
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
<style type="text/css" media="screen">

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
body {
  background-color: #fff;
  text-rendering: optimizeLegibility;
}

.ExcelTable2007 {
  border: 1px solid #B0CBEF;
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
  font-weight: normal;
  font-size: 14px;
  border-width: 0px 1px 1px 0px;
  padding: 8px;
  height: 17px;
  vertical-align: middle;
}
.ExcelTable2007 th,
.ExcelTable2007 tr.info td{
  background: #e4ecf7; /* Old browsers */
  background-color: #E4ECF7;
  text-align: center;
  border: 1px solid #9EB6CE;
  font-weight: normal;
  font-size: 14px;
  border: 1px solid #9EB6CE;
  border-width: 0px 1px 1px 0px;
  height: 17px;
  vertical-align: middle;
  padding: 8px;
}

.ExcelTable2007 td.center {
  text-align: center;
}
.ExcelTable2007 td input,
.ExcelTable2007 td textarea {
  background-color: #fff9e8;
  height: 38px;
  padding: 0;
  margin: 0;
  border: 0;
  font-size: 11pt;
  width: 100%;
}

/* make input blink on focus */
.ExcelTable2007 td input:focus,
.ExcelTable2007 td input[focus]{
  background-color: #ff0000;
  color: #fff;
}

/* button */
.btn {
  color: #ffffff;
  padding: 5px 10px;
  border: solid 1px #c0c0c0;
  background-color: #14b6e8;
  box-shadow: 1 -1 1px  rgba(0,0,0,0.6);
  -moz-box-shadow: 2 -2 2px   rgba(0,0,0,0.6);
  -webkit-box-shadow: 2 -2 2px   rgba(0,0,0,0.6);
  -o-box-shadow: 2 -2 2px   rgba(0,0,0,0.6);
  border-radius:3px;
  cursor:pointer;
}
.btn:hover {
  background-color: #ccc;
  color: #000;
  cursor:pointer;
}
</style>
</head>

<body>

<form action="<?php echo Config::get('URL') . 'contact/selectContact/'; ?>" method="GET" >
                        <input type="hidden" name="id" value="<?php echo $_GET['id'];?>">
                    <table class="ExcelTable2007" style="width: 100%;">
                        <thead>
                            <tr >
                               <th colspan="2"><input id="appendedInputButton" type="text" name="find" style="width: 98%;" placeholder="Cari kontak di sini....." autofocus></th>
                               <th><button class="btn" id="save-button" type="submit">Cari Kontak</button></th>
                            </tr>
                            <tr >
                               <th>ID</th>
                               <th>Name</th>
                               <th>Pilih</th>
                            </tr>
                        </thead>
                        <tbody>                            
                            <?php
                            $id = $_GET['id'];
                            $no = ($this->page * $this->limit) - ($this->limit - 1);
                            foreach($this->contact as $key => $value) { ?>
                              <tr>
                                  <td id="<?php echo $value->contact_id;?>"><?php echo $value->contact_id;?></td>
                                  <td><?php echo $value->contact_name;?></td>
                                  <td class="center"><a type="button" class="btn button-delete" onclick="SetName<?php echo $no;?>();">Select</a></td>
                                  <script type="text/javascript">
                                      function SetName<?php echo $no;?>() {
                                          if (window.opener != null && !window.opener.closed) {
                                              var contact_id = window.opener.document.getElementById("contact_id<?php echo $id;?>");
                                              contact_id.value = document.getElementById("<?php echo $value->contact_id;?>").innerHTML;
                                          }
                                          window.close();
                                      }
                                  </script>
                              </tr>
                             <?php $no++;} ?>
                             <tr class="info">
                               <td><a type="button" href="<?php echo $this->prev; ?>" class="btn">&laquo; Prev</a></td>
                               <td>Halaman: <?php echo $this->page; ?></td>
                               <td><a type="button" href="<?php echo $this->next; ?>" class="btn">Next &raquo;</a></td>
                            </tr>
                        </tbody>
                    </table>
    </form>
</body>
</html>