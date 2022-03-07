<!DOCTYPE HTML>
<html> 

<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
 
<title>
    <?php if (isset($this->title)) {
        echo $this->title;
    } else {
        echo Config::get('DEFAULT_TITLE');
    } ?>
</title>
<meta name="description" content="<?php echo $this->meta_description; ?>">
<meta name="keywords" content="<?php echo $this->meta_keywords; ?>">
<!-- Bootstrap core --> 
<link href="<?php echo Config::get('URL'); ?>theme/fuschiaderm/assets/css/bootstrap.css" rel="stylesheet">
<link href="<?php echo Config::get('URL'); ?>theme/fuschiaderm/assets/css/font-awesome.css" rel="stylesheet">
    
<!-- CSS Plugin -->
                            
<!-- Pushy -->  
<link href="<?php echo Config::get('URL'); ?>theme/fuschiaderm/modules/pushy/css/pushy.css" rel="stylesheet"> 

<!-- Slick -->  
<link rel="stylesheet" type="text/css" href="<?php echo Config::get('URL'); ?>theme/fuschiaderm/modules/slick/slick.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo Config::get('URL'); ?>theme/fuschiaderm/modules/slick/slick-theme.css"/>

<!-- H Timeline -->   
<link rel="stylesheet" href="<?php echo Config::get('URL'); ?>theme/fuschiaderm/modules/htimeline/css/style.css">

<!-- Select -->   
<link rel="stylesheet" href="<?php echo Config::get('URL'); ?>theme/fuschiaderm/modules/bootstrap-select/css/bootstrap-select.css">

<!-- Datepicker -->   
<link rel="stylesheet" href="<?php echo Config::get('URL'); ?>theme/fuschiaderm/modules/bootstrap-datepicker/css/bootstrap-datepicker.css">

<!-- Datetimepicker -->   
<link rel="stylesheet" href="<?php echo Config::get('URL'); ?>theme/fuschiaderm/modules/datetimepicker/css/bootstrap-datetimepicker.min.css">
   
<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
  <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
<![endif]-->
    
    
<!-- Custom styles for this template -->
<link href="<?php echo Config::get('URL'); ?>theme/fuschiaderm/assets/fonts/fonts.css" rel="stylesheet">
<link href="<?php echo Config::get('URL'); ?>theme/fuschiaderm/style.css" rel="stylesheet">

<!-- end new theme -->

<link rel="shortcut icon" type="image/x-icon" href="<?php echo Config::get('URL'); ?>theme/fuschiaderm/fav.png">
<meta name="webcrawlers" content="all" />
<meta name="rating" content="general" />
<meta name="spiders" content="all" />
<meta name="robots" content="all" />
<meta property="og:title" content=
"Fuschiaderm Aesthetic Clinic" />
<meta property="og:image" content=
"<?php echo Config::get('URL'); ?>theme/fuschiaderm/uploads/slider/large--y4B5IhyFDM6igobhN0T7PgNbRMyGxJ1.jpg"/>
<meta property="og:description" content=
"Klinik Kecantikan terdepan dan terpercaya di Indonesia. Teknologi perawatan terkini. Didukung para Dokter profesional & Beauty Therapist berpengalaman."/> 
<meta property="og:url" content="www.fuschiaderm.com" />
<meta property="og:site_name" content="Fuschiaderm Aesthetic Clinic" />
<meta property="fb:app_id" content="" />
<meta name="distribution" content="Global" />
<meta name="rating" content="General" />
<meta name="robots" content="index,follow" />
<meta name="googlebot" content="index,follow" />
<meta name="dc.title" content="" /> 
<meta name="dc.creator.website" content="" />
<meta name="tgn.name" content="Jakarta" />
<meta name="tgn.nation" content="Indonesia" />
<link rel="alternate”" href="#"  hreflang="x-default" />
<meta name="twitter:title" content="Fuschiaderm Aesthetic Clinic | Klinik Kecantikan Terdepan di Indonesia">
<meta name="twitter:image" content="">
<meta name="twitter:card" content="summary">
<meta name="twitter:site" content="">
<meta name="twitter:creator" content="">
<meta name="twitter:description" content="Klinik Kecantikan terdepan dan terpercaya di Indonesia. Teknologi perawatan terkini. Didukung para Dokter profesional & Beauty Therapist berpengalaman.">
<meta name="twitter:domain" content="fuschiaderm.com"> 

</head>
<body class="nav-thru">
<!-- Pushy Menu -->
<nav class="pushy pushy-left mnav">
    <div class="pushy-content">
        <ul>
            <li><a href="<?php echo Config::get('URL'); ?>">Home</a></li>
            <li><a href="story/index.html">Tentang Fuschiaderm</a></li>
            <li class="submenu">
                <a href="services/index.html">Layanan</a>                    <div class="pushy-submenu">
                    <button></button>
                    <ul>
                                                    <li><a href="services/read77a4.html?title=acne">Acne</a></li>
                                                    <li><a href="services/readd99d.html?title=pigmentation">Pigmentation</a></li>
                                                    <li><a href="services/read5626.html?title=rejuvenation">Rejuvenation</a></li>
                                                    <li><a href="services/read314b.html?title=face-shaping">Face Shaping</a></li>
                                                
<!--                         <li><a href="solution.html">Face Shaping</a></li>
                        <li><a href="solution.html">Pigmentation</a></li>
                        <li><a href="solution.html">Rejuvenation</a></li> -->
                    </ul>
                </div>
            </li>
            <li><a href="event/index.html">Berita</a></li>
            <li><a href="event/index.html">Promo</a></li>
            <li><a href="<?php echo Config::get('URL'); ?>/info/kontak">Kontak</a></li>
            <li class="btn-appoint"><a href="appointment/index.html">Pesan Layanan</a></li>
            <!-- <li class="btn-appoint"><a href="#" data-toggle="modal" data-target="#modalAppoint">Make Appointment</a></li> -->
        </ul>
    </div>
</nav>

<!-- Site Overlay -->
<div class="site-overlay"></div>

<div id="container"><!-- Pushy Open -->

<!-- Header -->
<header id="header"> 
    <!-- Nav -->
    <nav class="navbar">
        <div class="container">
            <div class="navbar-header">
                <button class="menu-btn">Menu</button>
                <a class="navbar-brand" href="<?php echo Config::get('URL'); ?>"><img src="<?php echo Config::get('URL'); ?>theme/fuschiaderm/assets/img/main-logo.png"></a>
            </div>
            <div id="navbar" class="navbar-collapse collapse navbar-right">
                <ul class="nav navbar-nav">
                    <li><a href="story/index.html">Tentang Fuschiaderm</a></li>
                    <li class="dropdown mega-dropdown">
                      <a class="dropdown-link" href="services/index.html">Layanan</a>           
                      <ul class="dropdown-menu mega-dropdown-menu row">
                                                <li class="col-md-3 col-sm-6">
                            <h3><a href="services/read77a4.html?title=acne">Acne</a></h3>
                            <ul>
                                                                <li><a href="subservice/read54a1.html?title=Fuschiaderm-cream-program-for-acne">Cream Program</a></li>
                                                                <li><a href="subservice/read8d86.html?title=Fuschiaderm-aesthetic-treatment-for-acne">Aesthetic Treatment</a></li>
                                                                <li><a href="subservice/read4646.html?title=Fuschiaderm-medical-treatment-for-acne">Medical Treatment</a></li>
                                                            </ul>
                        </li>
                                                <li class="col-md-3 col-sm-6">
                            <h3><a href="services/readd99d.html?title=pigmentation">Pigmentation</a></h3>
                            <ul>
                                                                <li><a href="subservice/read2e7d.html?title=Fuschiaderm-cream-program-for-pigmentation">Cream Program</a></li>
                                                                <li><a href="subservice/readbc47.html?title=Fuschiaderm-aesthetic-treatment-for-pigmentation">Aesthetic Treatment</a></li>
                                                                <li><a href="subservice/read0572.html?title=Fuschiaderm-medical-treatment-for-pigmentation">Medical Treatment</a></li>
                                                            </ul>
                        </li>
                                                <li class="col-md-3 col-sm-6">
                            <h3><a href="services/read5626.html?title=rejuvenation">Rejuvenation</a></h3>
                            <ul>
                                                                <li><a href="subservice/readdb68.html?title=Fuschiaderm-cream-program-rejuvenation">Cream Program</a></li>
                                                                <li><a href="subservice/readfec7.html?title=Fuschiaderm-aesthetic-treatment-rejuvenation">Aesthetic Treatment</a></li>
                                                                <li><a href="subservice/read5031.html?title=Fuschiaderm-medical-treatment-rejuvenation">Medical Treatment</a></li>
                                                            </ul>
                        </li>
                                                <li class="col-md-3 col-sm-6">
                            <h3><a href="services/read314b.html?title=face-shaping">Face Shaping</a></h3>
                            <ul>
                                                                <li><a href="subservice/read4679.html?title=Fuschiaderm-cream-program-for-face-shaping">Cream Program</a></li>
                                                                <li><a href="subservice/readcad1.html?title=Fuschiaderm-aesthetic-treatment-for-face-shaping">Aesthetic Treatment</a></li>
                                                                <li><a href="subservice/read7104.html?title=Fuschiaderm-medical-treatment-for-face-shaping">Medical Treatment</a></li>
                                                            </ul>
                        </li>
                        <!--                         <li class="col-md-3 col-sm-6">
                            <h3><a href="solution.html">Face Shaping</a></h3>
                            <ul>
                                <li><a href="solution-detail.html">Fuschiaderm Cream Program</a></li>
                                <li><a href="solution-detail.html">Fuschiaderm Aesthetic Treatment</a></li>
                                <li><a href="solution-detail.html">Fuschiaderm Medical Treatment</a></li>
                            </ul>
                        </li>
                        <li class="col-md-3 col-sm-6">
                            <h3><a href="solution.html">Pigmentation</a></h3>
                            <ul>
                                <li><a href="solution-detail.html">Fuschiaderm Cream Program</a></li>
                                <li><a href="solution-detail.html">Fuschiaderm Aesthetic Treatment</a></li>
                                <li><a href="solution-detail.html">Fuschiaderm Medical Treatment</a></li>
                            </ul>
                        </li>
                        <li class="col-md-3 col-sm-6">
                            <h3><a href="solution.html">Rejuvenation</a></h3>
                            <ul>
                                <li><a href="solution-detail.html">Fuschiaderm Cream Program</a></li>
                                <li><a href="solution-detail.html">Fuschiaderm Aesthetic Treatment</a></li>
                                <li><a href="solution-detail.html">Fuschiaderm Medical Treatment</a></li>
                            </ul>
                        </li> -->
                      </ul>
                    </li>
                    <li><a href="event/index.html">Berita</a></li>
                    <li><a href="news/index.html">Promo</a></li>
                    <li><a href="<?php echo Config::get('URL'); ?>/info/kontak">Kontak</a></li>
                                           <li class="btn-appoint"><a href="#" data-toggle="modal" data-target="#modalAppoint"><i class="fa fa-calendar-plus-o" aria-hidden="true"></i>Pesan Layanan</a></li>
                                
                </ul>       
            </div><!--/.nav-collapse -->
        </div>        
    </nav>
    <!-- /Nav -->
</header>
<!-- /Header -->

<!-- Main Body -->
<section id="main" class="page-homepage">      
    <!-- Content -->
    <div class="base-content">                  
        <!-- Page Banner -->
        <section class="page-banner">
            <img src="<?php echo Config::get('URL'); ?>theme/fuschiaderm/uploads/images/beauty-parlour.jpg" class="bg-banner">
            <div class="content-banner">
                <div class="container">
                    <div class="wrap">
                        <h1>Fuschiaderm Aesthetic Clinic</h1>
                        <p>
                            Klinik kecantikan terdepan di Indonesia                        </p>
                        <div class="btn-area">
                            <a class="btn btn-green" href="services/index.html">Our Treatment</a>
                            <a class="btn btn-green-o" href="contact/index.html">Our Location</a>
                        </div>    
                    </div>  
                </div>
            </div>
      </section>
        <!-- /Page Banner -->
        
        <!-- Home Teaser -->
        <section class="page-block home-teaser">
            <div class="container">             
                <div class="wrap">
                    <h1 class="panel-header">Dapatkan Layanan Kecantikan Terbaik di Indonesia</h1>
                    <p>
                        Kami membantu Anda mendapatkan tampilan wajah terbaik dengan menggabungkan seni dan ilmu estetika untuk menciptakan kecantikan dan keindahan wajah secara holistik dengan menggunakan metode inovatif dari sudut pandang estetika yang profesional dan ideal. Solusi yang Fuschiaderm tawarkan adalah kombinasi cream program, perawatan medis oleh dokter profesional dan perawatan estetik oleh beauty therapist berpengalaman, didukung dengan cita rasa seni yang tinggi dan teknologi estetika yang canggih.                    </p>     
                    <ul class="block-list block-2 col">
                                                <li>
                            <a href="services/read77a4.html?title=acne">
                            <div class="thumb"><img src="<?php echo Config::get('URL'); ?>theme/fuschiaderm/uploads/services/crop-ixaGjmeKb2Qtg9bmWVxo-PmvnxgF5PoP.jpg"></div>
                            <div class="content ani">
                                <div class="title">Acne</div>
                                <p>
                                    Solusi untuk kulit berminyak dan berjerawat serta bekas-bekasnya (parut, bercak & pigmentasi).                                </p>
                            </div>
                            </a>
                        </li>
                                                <li>
                            <a href="services/readd99d.html?title=pigmentation">
                            <div class="thumb"><img src="<?php echo Config::get('URL'); ?>theme/fuschiaderm/uploads/services/crop-kdW-j4YvqF2nrebeZBHA7aOcaoQdsQl2.jpg"></div>
                            <div class="content ani">
                                <div class="title">Pigmentation</div>
                                <p>
                                    Mengatasi pigmentasi, kulit kusam dan warna kulit tidak merata                                </p>
                            </div>
                            </a>
                        </li>
                                                <li>
                            <a href="services/read5626.html?title=rejuvenation">
                            <div class="thumb"><img src="<?php echo Config::get('URL'); ?>theme/fuschiaderm/uploads/services/crop-2HcyDak9-TN74rzBd__kwgF0YlopLOCt.jpg"></div>
                            <div class="content ani">
                                <div class="title">Rejuvenation</div>
                                <p>
                                    Bertujuan regenerasi sel, meremajakan, melembapkan dan memperbaiki tekstur kulit.                                </p>
                            </div>
                            </a>
                        </li>
                                                <li>
                            <a href="services/read314b.html?title=face-shaping">
                            <div class="thumb"><img src="<?php echo Config::get('URL'); ?>theme/fuschiaderm/uploads/services/crop-y_AzkAbsH-AWS24Jp3gFJVSd6t1rYW-M.jpg"></div>
                            <div class="content ani">
                                <div class="title">Face Shaping</div>
                                <p>
                                    Menjadikan wajah kencang, mengatasi keriput/kerutan, serta membentuk kontur wajah.                                </p>
                            </div>
                            </a>
                        </li>
                                                <!-- <li>
                            <a href="solution.html">
                            <div class="thumb"><img src="/<?php echo Config::get('URL'); ?>theme/fuschiaderm/uploads/images/thumb-product-02.jpg"></div>
                            <div class="content ani">
                                <div class="title">Face Shaping</div>
                                <p>
                                    Menjadikan wajah kencang, mengatasi keriput/kerutan, serta membentuk kontur wajah                                </p>
                            </div>
                            </a>
                        </li>
                        <li>
                            <a href="solution.html">
                            <div class="thumb"><img src="/<?php echo Config::get('URL'); ?>theme/fuschiaderm/uploads/images/thumb-product-03.jpg"></div>
                            <div class="content ani">
                                <div class="title">Pigmentation</div>
                                <p>
                                    Mengatasi pigmentasi, kulit kusam dan warna kulit tidak merata
                                </p>
                            </div>
                            </a>
                        </li>
                        <li>
                            <a href="solution.html">
                            <div class="thumb"><img src="/<?php echo Config::get('URL'); ?>theme/fuschiaderm/uploads/images/thumb-product-04.jpg"></div>
                            <div class="content ani">
                                <div class="title">Rejuvenation</div>
                                <p>
                                    Bertujuan regenerasi sel, meremajakan, melembapkan dan memperbaiki tekstur kulit                                </p>
                            </div>
                            </a>
                        </li> -->
                  </ul>
                </div>  
          </div>
        </section>
        <!-- /Home Teaser -->
        
        <!-- Home Reason -->

        <section class="page-block home-reason" data-parallax="scroll">
            <div class="container">
                <div class="wrap row">
                    <h1 class="panel-header">Why Fuschiaderm</h1>
                    <div class="col-xs-12">                    
                        <!-- <p>
                            Your skin is your assets. Some cool text here. Duis posuere ante id massa porta, nec sagittis purus sodales. Donec vulputate massa vitae dui eleifend blandit. In porttitor bibendum lacus a cursus. Also, parallax. 
                        </p> -->
                        
                        <ul class="block-list block-3 col">
                            <li data-mh="list-reason">
                                <div class="icon"><img src="<?php echo Config::get('URL'); ?>theme/fuschiaderm/uploads/images/icon-reason-01.png"></div>                            
                                <div class="content">
                                    <div class="title">Pengalaman</div>
                                    <p>Lebih dari 21 tahun dengan total 19 klinik adalah bukti bahwa Fuschiaderm tetap menjadi klinik estetika terdepan dan terpercaya di Indonesia.</p>
                                </div>
                            </li>
                            <li data-mh="list-reason">
                                <div class="icon"><img src="<?php echo Config::get('URL'); ?>theme/fuschiaderm/uploads/images/icon-reason-02.png"></div>                            
                                <div class="content">
                                    <div class="title">Sumber Daya Manusia</div>
                                    <p>Mulai dari keramahan frontline, beauty therapist yang berpengalaman, perawat yang terampil dan cekatan, dokter yang terlatih sesuai dengan kompetensinya (aesthetic, dermatologist, plastic surgeon).</p>
                                </div>
                            </li>
                            <li data-mh="list-reason">
                                <div class="icon"><img src="<?php echo Config::get('URL'); ?>theme/fuschiaderm/uploads/images/icon-reason-03.png"></div>                            
                                <div class="content">
                                    <div class="title">Aman</div>
                                    <p>Standard hygiene dan sterilitas tidak dikompromikan. Perawatan dilakukan berdasarkan protokol yang telah distandarkan di setiap cabang.</p>
                                </div>
                            </li>
                            <li data-mh="list-reason">
                                <div class="icon"><img src="<?php echo Config::get('URL'); ?>theme/fuschiaderm/uploads/images/icon-reason-04.png"></div>                            
                                <div class="content">
                                    <div class="title">Nyaman</div>
                                    <p>Mengutamakan kenyamanan dan privasi pelanggan. Ruang tunggu nyaman, serta tidak sakit selama melakukan perawatan.</p>
                                </div>
                            </li>
                            <li data-mh="list-reason">
                                <div class="icon"><img src="<?php echo Config::get('URL'); ?>theme/fuschiaderm/uploads/images/icon-reason-05.png"></div>                            
                                <div class="content">
                                    <div class="title">Efektif</div>
                                    <p>Memberi hasil yang nyata (efektif) karena didukung oleh produk berkualitas dan peralatan canggih yang terus mengikuti perkembangan terkini di bidang estetik.</p>
                                </div>
                            </li>
                        </ul>
                    </div>    
                </div>        
            </div>  
        </section>
        <!-- /Home Reason -->

        <!-- Home Testimonial -->
        <section class="page-block home-testimonial">
            <div class="container">
                <h1 class="panel-header">Customer Testimony</h1>  

                <div class="slide-content slide-testimonial">
                                            <div class="item">
                            <div class="item-testimonial">
                                <div class="people">
                                    <div class="thumb"><img src="<?php echo Config::get('URL'); ?>theme/fuschiaderm/assets/img/customer/1.jpeg" style="width:100%;"></div>
                                    <div class="info">
                                        <label>Shinta Mustika</label>
                                        <p>Ibu Rumah Tangga</p>
                                    </div>
                                    <div class="content">
                                        <p>
                                            "Saya senang dengan treatment di Fuschiaderm karena lengkap dan sesuai kebutuhan saya. Sampai sekarang, saya masih rutin mempercayakan perawatan kulit saya ke Fuschiaderm"</p>    
                                    </div>
                                </div>     
                            </div>
                        </div>
                                            <div class="item">
                            <div class="item-testimonial">
                                <div class="people">
                                    <div class="thumb"><img src="<?php echo Config::get('URL'); ?>theme/fuschiaderm/assets/img/customer/2.jpeg" style="width:100%;"></div>
                                    <div class="info">
                                        <label>Stevia Asmiralda</label>
                                        <p>Mahasiswi</p>
                                    </div>
                                    <div class="content">
                                        <p>
                                            "Berkat rajin perawatan di Fuschiaderm, banyak yang bilang kalau saya awet muda"                                        </p>    
                                    </div>
                                </div>     
                            </div>
                        </div>
                                            <div class="item">
                            <div class="item-testimonial">
                                <div class="people">
                                    <div class="thumb"><img src="<?php echo Config::get('URL'); ?>theme/fuschiaderm/assets/img/customer/3.jpeg" style="width:100%;"></div>
                                    <div class="info">
                                        <label>Siti Masruroh</label>
                                        <p>PNS</p>
                                    </div>
                                    <div class="content">
                                        <p>
                                            “Fuschiaderm itu serba “ter”. Tercanggih karena alat yang digunakan paling baru di kelasnya. Ternyaman karena pelayanannya sangat ramah, mulai dari security, front liner, therapist, sampai dokternya. Benar-benar membuat saya nyaman memanjakan diri di sini."                                        </p>    
                                    </div>
                                </div>     
                            </div>
                        </div>
                                            <div class="item">
                            <div class="item-testimonial">
                                <div class="people">
                                    <div class="thumb"><img src="<?php echo Config::get('URL'); ?>theme/fuschiaderm/assets/img/customer/4.jpeg" style="width:100%;"></div>
                                    <div class="info">
                                        <label>Laurensia</label>
                                        <p>Pengusaha</p>
                                    </div>
                                    <div class="content">
                                        <p>
                                            “Dari first impression, saya dibuat jatuh cinta. Setiap informasi disampaikan dengan jelas dari A sampai Z, jadi saya tidak bingung lagi memilih perawatan yang memang saya butuhkan.”                                        </p>    
                                    </div>
                                </div>     
                            </div>
                        </div>
                                            <div class="item">
                            <div class="item-testimonial">
                                <div class="people">
                                    <div class="thumb"><img src="<?php echo Config::get('URL'); ?>theme/fuschiaderm/assets/img/customer/5.jpeg" style="width:100%;"></div>
                                    <div class="info">
                                        <label>Evi Swastika</label>
                                        <p>Pegawai BUMN</p>
                                    </div>
                                    <div class="content">
                                        <p>
                                            “Sebenarnya sudah lama tahu mengenai Fuschiaderm dari beberapa teman. Ternyata setelah mencoba perawatan di sini, saya suka. Kliniknya sangat nyaman, bersih, dan yang terpenting, pelayanannya juga bagus.”                                        </p>    
                                    </div>
                                </div>     
                            </div>
                        </div>
                                            <div class="item">
                            <div class="item-testimonial">
                                <div class="people">
                                    <div class="thumb"><img src="<?php echo Config::get('URL'); ?>theme/fuschiaderm/assets/img/customer/6.jpeg" style="width:100%;"></div>
                                    <div class="info">
                                        <label>Ella Kurniawati</label>
                                        <p>PNS</p>
                                    </div>
                                    <div class="content">
                                        <p>
                                            “Cukup dua kata, professional and luxurious. It's definitely my favorite place. Di sini semua peralatan serba steril, sudah seperti klinik di luar negeri.”                                        </p>    
                                    </div>
                                </div>     
                            </div>
                        </div>
                                            <div class="item">
                            <div class="item-testimonial">
                                <div class="people">
                                    <div class="thumb"><img src="<?php echo Config::get('URL'); ?>theme/fuschiaderm/assets/img/customer/7.jpeg" style="width:100%;"></div>
                                    <div class="info">
                                        <label>M. Ali</label>
                                        <p>Pegawai Swasta</p>
                                    </div>
                                    <div class="content">
                                        <p>
                                            “Fuschiaderm itu seperti rumah. Suasananya membuat orang langsung merasa nyaman, terpercaya dan tempat ditemukannya solusi. Tak salah kalau orang-orang mempercayakan perawatannya di Fuschiaderm.”                                        </p>    
                                    </div>
                                </div>     
                            </div>
                        </div>
                                    </div>
            </div>      
            
        </section>
        <!-- /Home Testimonial -->
        <div style="max-width: 100%;overflow: hidden;">
            <div class="sliderslick">
                                <div class="item"><a href="index.html"><img src="<?php echo Config::get('URL'); ?>theme/fuschiaderm/uploads/slider/large-8xvxrDymuxyh3RRBfzEBhNOrSvGwDmid.jpg" style="width:100%" alt=""></a></div>
                                <div class="item"><a href="index.html"><img src="<?php echo Config::get('URL'); ?>theme/fuschiaderm/uploads/slider/large-wcF2MGnuWr-LhGKV3fTy-LKxguNUF0I-.jpg" style="width:100%" alt=""></a></div>
                                <div class="item"><a href="news/read5f09.html?title=teknologi-terbaru-untuk-atasi-flek-membandel"><img src="<?php echo Config::get('URL'); ?>theme/fuschiaderm/uploads/slider/large-UJ8pxfWrIkALzafVm7QxipIMnwqBxh8P.jpg" style="width:100%" alt=""></a></div>
                                <div class="item"><a href="index.html"><img src="<?php echo Config::get('URL'); ?>theme/fuschiaderm/uploads/slider/large--y4B5IhyFDM6igobhN0T7PgNbRMyGxJ1.jpg" style="width:100%" alt=""></a></div>
                            </div>
        </div>


        
        <!-- Home Update -->
        <section class="page-block home-update bg-grey">
            <div class="container">
                <h1 class="panel-header">What's New</h1>
                <label class="panel-header-sub">Find out latest news and special offers to make you beauty now
                <div class="btn-area"><a class="btn" href="event/index.html">View all</a></div>
                </label>
                
                <ul class="block-list block-4 col">
                     <li><div class="thumb"><a href="news/read2540.html?title=thematic-promo-contouring-2018"><img src="<?php echo Config::get('URL'); ?>theme/fuschiaderm/uploads/event/bags.png" alt="" style="height: 300px;"></a></div><div class="title">Thematic Promo Contouring 2018</div><div class="content">Disc 20% Treatment Fuschiaderm Yellow Laser/Fuschiaderm Beauty Booster Series/Facials in Fuschiaderm (period November-December 2018)</div></li>

                     <li><div class="thumb"><a href="news/read49a4.html?title=tdf-promo-c-scape-2018"><img src="<?php echo Config::get('URL'); ?>theme/fuschiaderm/uploads/event/bags.png" alt="" style="height: 300px;"></a></div><div class="title">TDF Promo C Scape 2018</div><div class="content">Nikmati diskon 25% untuk TDF C Scape Serum selama bulan Oktober 2018</div></li>

                     <li><div class="thumb"><a href="news/read9f4e.html?title=Fuschiaderm-on-blibli"><img src="<?php echo Config::get('URL'); ?>theme/fuschiaderm/uploads/event/bags.png" alt="" style="height: 300px;"></a></div><div class="title">Fuschiaderm on Blibli</div><div class="content">Dapatkan berbagai perawatan & produk Fuschiaderm di Blibli.com!</div></li>

                     <li><div class="thumb"><a href="news/read0ed1.html?title=lip-and-chin-beauty-promo-2018"><img src="<?php echo Config::get('URL'); ?>theme/fuschiaderm/uploads/event/bags.png" alt="" style="height: 300px;"></a></div><div class="title">Lip and Chin Beauty Promo 2018</div><div class="content">Pancarkan pesonamu dengan bibir dan dagu menawan melalui penawaran spesial unggulan hingga Oktober 2018</div></li>  
                </ul>
            </div>      
            
        </section>
        <!-- /Home Update -->
                     
    </div>    
    <!-- /Content --> 
    
</section>
<!-- /Main Body -->

<!-- Footer -->

<footer id="footer" class="bg-grey">
  <div class="footer-wrap">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-md-2">
                    <div class="footer-content">
                      <img src="<?php echo Config::get('URL'); ?>theme/fuschiaderm/assets/img/main-logo.png">
                    </div>
                </div>
                <div class="col-xs-12 col-md-3">
                  <h4>Connect with Us</h4>
                    <ul class="footer-soc">
                      <li class="fb"><a href="http://www.facebook.com/Fuschiaderm.Aesthetic.Clinic"><i class="fa fa-facebook" aria-hidden="true"></i></a></li>
                        <li class="ig"><a href="http://www.instagram.com/Fuschiaderm_clinic"><i class="fa fa-instagram" aria-hidden="true"></i></a></li>
                        <li class="ln"><a href="https://line.me/ti/p/%40sgj8817q"><img src="<?php echo Config::get('URL'); ?>theme/fuschiaderm/assets/img/ic-line.png"></a></li>
                    </ul>
                </div>
                <div class="col-xs-12 col-md-7">
                  <!-- <h4>Subscribe to Newsletter</h4>
                    <p>
                      Get special offers and the latest aesthetic insight
                    </p>
                    <div class="newsletter input-group">
                        <input class="form-control" placeholder="yourmail@site.com">
                        <span class="input-group-btn"><button class="btn btn-subscribe">Subscribe</button></span>
                    </div> -->
                  <h4>Shop Our Products</h4>
                <ul class="list-inline">
                    <li class="">
                        <a href="#"><img src="<?php echo Config::get('URL'); ?>theme/fuschiaderm/assets/img/stores/shopee_logo.png"></a>
                    </li>
                    <li class=" ">
                        <a href="#"><img src="<?php echo Config::get('URL'); ?>theme/fuschiaderm/assets/img/stores/lazada_logo.png"></a>
                    </li>
                    <li class=" ">
                        <a href="#"><img src="<?php echo Config::get('URL'); ?>theme/fuschiaderm/assets/img/stores/tokopedia_logo.png"></a>
                    </li>
                    <li class=" ">
                        <a href="#"><img src="<?php echo Config::get('URL'); ?>theme/fuschiaderm/assets/img/stores/bukalapak_logo.png"></a>
                    </li>
                </ul>

                </div>  
            </div>
        </div>
    </div>   
</footer>

<div class="bg-grey" style="padding-bottom: 60px;">
    <div class="col-xs-12 text-center" style="font-size: 12px;">
        <p><a href="career/index.html">About</a> | <a href="site/privacy-policy.html">Privacy Policy</a> | <a href="site/legal.html">Legal</a></p>
        <p><strong>Fuschiaderm Aesthetic Clinic</strong> © 2018 All Rights Reserved</p>
    </div>
</div>
<!-- /Footer -->


<!-- Modal -->
<div class="modal fade modal-default modal-form" id="modalAppoint" tabindex="-1" role="dialog" aria-labelledby="myModalAppoint">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Appointment Online</h4>
        </div>
        <div class="modal-body">   
            <div class="form-content form-signin">
                <form action="http://www.fuschiaderm.com/appointment/index" method="post">
                <input type="hidden" name="_csrf" value="MmdpdTA2LW9kMwc6cndhJAYSHBpleUI4YhYnHUpaFD9TATsPZl1oGA==" />
              <div class="row">
                  <div class="col-sm-6">
                      <fieldset class="form-group">
                            <label>Branches</label>
                            <select class="selectpicker form-control" name="Appointment[branch_id]">
                                <option value="3">Balikpapan</option><option value="7">Batam</option><option value="2">Denpasar</option><option value="18">Jakarta - Kelapa Gading</option><option value="8">Jakarta - Kemang</option><option value="19">Jakarta - Mal Kota Kasablanka</option><option value="16">Jakarta - Mall Taman Anggrek</option><option value="5">Kuta</option><option value="15">Lombok</option><option value="11">Makassar</option><option value="10">Malang</option><option value="14">Manado</option><option value="12">Medan</option><option value="17">Semarang</option><option value="6">Surabaya - HR Muhammad</option><option value="9">Surabaya - Kertajaya Indah</option><option value="1" selected>Surabaya - MH Thamrin</option><option value="4">Surabaya - Tunjungan Plaza</option><option value="13">Yogyakarta</option>                            </select>
                        </fieldset>
                      <fieldset class="form-group">
                            <label>Date</label>
                            <div class="input-group date datetimepickme">
                                <input type="text" class="form-control" placeholder="Date and Time here" name="Appointment[datetime]"><span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                            </div>
                            <!-- <div class="input-group date datepicker">
                                <input type="text" class="form-control"><span class="input-group-addon"><i class="glyphicon glyphicon-th"></i></span>
                            </div> -->
                        </fieldset>
                        <fieldset class="form-group">
                            <label>Doctor</label>
                            <select class="selectpicker form-control" name="Specialist">
                                <!-- <option selected disabled value="0">Doctor</option> -->
                                <option value="0">Doctor</option>
                                <option value="1">Beauty Therapist</option>
                            </select>
                        </fieldset>
                    </div>
                    <div class="col-sm-6">
                      <fieldset class="form-group">
                            <label>Full Name</label>    
                            <input type="" class="form-control" id="" placeholder="your name here" name="Appointment[name]">
                        </fieldset>  
                        <fieldset class="form-group">
                            <label>Email</label>     
                            <input type="" class="form-control" id="" placeholder="email@domain.com" name="Appointment[email]">
                        </fieldset> 
                        <fieldset class="form-group">
                            <label>Phone Number</label> 
                            <input type="" class="form-control" id="" placeholder="081678910" name="Appointment[phone]">
                        </fieldset>
                    </div>
                </div>
                
                <fieldset class="form-group">
                    <label>Message</label>    
                    <textarea name="Appointment[message]" class="form-control" rows="3" id="" placeholder="your name here"></textarea>
                    <input type="text" name="Appointment[messages]" style="display: none;">
                </fieldset> 
                <div>
                    <div id="captcha_v2"></div>
                </div>
                <div class="btn-area btn-block">
                    <button type="submit" class="btn btn-green btn-bg" style="width: 100%;">Make an appointment</button>
                    <!-- <a class="btn btn-green btn-bg" href="#">Make an appointment</a> -->
                </div>
                </form>
            </div>
        </div>  
        <div class="modal-footer">
          <p>
              Need more help? <a href="contact/index.html">Contact us immediately</a>
            </p>
        </div>
    </div>
  </div>
</div><!-- /.modal -->

</div><!-- Pushy Close -->

<script src='https://www.google.com/recaptcha/api.js'></script>

<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster  -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
<script src="<?php echo Config::get('URL'); ?>theme/fuschiaderm/assets/js/bootstrap.min.js"></script>

<!-- AddOns  -->
  <script src="<?php echo Config::get('URL'); ?>theme/fuschiaderm/assets/js/classie.js"></script>
  <script src="<?php echo Config::get('URL'); ?>theme/fuschiaderm/assets/js/modernizr.js"></script>
    <script src="<?php echo Config::get('URL'); ?>theme/fuschiaderm/assets/js/jquery.easing.js"></script>
    <script src="<?php echo Config::get('URL'); ?>theme/fuschiaderm/assets/js/jquery.mousewheel.js"></script>
    <script src="<?php echo Config::get('URL'); ?>theme/fuschiaderm/assets/js/moment.js"></script>


<!-- JS Plugin  -->  

    <!-- Pushy -->
    <script src="<?php echo Config::get('URL'); ?>theme/fuschiaderm/modules/pushy/js/pushy.min.js" type="text/javascript"></script>

    <!-- Slick -->
    <script type="text/javascript" src="<?php echo Config::get('URL'); ?>theme/fuschiaderm/modules/slick/slick.min.js"></script>

    <!-- Parallax -->
    <script src="<?php echo Config::get('URL'); ?>theme/fuschiaderm/modules/parallax/parallax.js"></script>
    
    <!-- H Timeline -->
    <script src="<?php echo Config::get('URL'); ?>theme/fuschiaderm/modules/htimeline/js/main.js"></script>
    
    <!-- Match Height -->
    <script src="<?php echo Config::get('URL'); ?>theme/fuschiaderm/modules/jquery.matchHeight.js"></script>
    
    <!-- Bootstrap Select -->
    <script src="<?php echo Config::get('URL'); ?>theme/fuschiaderm/modules/bootstrap-select/js/bootstrap-select.js"></script>
    
    <!-- Bootstrap Datepicker -->
    <script src="<?php echo Config::get('URL'); ?>theme/fuschiaderm/modules/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>

    <!-- Bootstrap Datetimepicker -->
    <script src="<?php echo Config::get('URL'); ?>theme/fuschiaderm/modules/datetimepicker/js/bootstrap-datetimepicker.min.js"></script>
    
    <!-- Jssocials -->
    <script src="<?php echo Config::get('URL'); ?>theme/fuschiaderm/modules/jssocials/jssocials.min.js"></script>    
    
<!-- All Custom JS -->
  <script src="<?php echo Config::get('URL'); ?>theme/fuschiaderm/assets/js/custom.js" type="text/javascript"></script>

    <script type="text/javascript">
        $(function () {
            var mindate = new Date();
            mindate.setDate(mindate.getDate()+2);
            var defdate = new Date();
            var month = defdate.getMonth();
            var day = defdate.getDate()+3;
            var year = defdate.getFullYear();
            $('.datetimepickme').datetimepicker({
                //locale: 'id',
                // sideBySide:true,
                useCurrent:false,
                format: 'DD-MMM-YYYY HH:mm', 
                minDate: mindate, 
                defaultDate: new Date(year, month, day, 10, 00), 
                stepping: 30
            });
        });

        // $("#btnsatu").hide();
        // $("#btnsatu").click(function(){
        //     $("#btnsatu").hide();
        //     $("#btndua").show();
        // });
        // $("#btndua").click(function(){
        //     $("#btnsatu").show();
        //     $("#btndua").hide();
        // });

        $('#modal1').on('shown.bs.modal', function (e) {
            try {
                grecaptcha.render("captcha2", {sitekey: "6LfPUVAUAAAAADGcIiXIDzxU-zxUvzaIbWClWnbP", theme: "light"});
            }
            catch(err) {
                console.log(err);
            }

            //get data-id attribute of the clicked element
            var jobId = $(e.relatedTarget).data('job-id');
            var jobName = $(e.relatedTarget).data('job-name');
            console.log(jobName);
            //populate the textbox
            $(e.currentTarget).find('input[name="Career[identitas_jabatan]"]').val(jobId);
            $(e.currentTarget).find('#name-job').html(jobName);
            // $('#btnsatu').tab('show');
            // $("#btnsatu").hide();
            // $("#btndua").show();
        });
        $('#modal1').on('hide.bs.modal', function () {
            $("#recaptcha2").empty();
        });

        $('#modalAppoint').on('shown.bs.modal', function (e) {
            try {
                grecaptcha.render("captcha_v2", {sitekey: "6LfPUVAUAAAAADGcIiXIDzxU-zxUvzaIbWClWnbP", theme: "light"});
            }
            catch(err) {
                console.log(err);
            }
        });
        $('#modalAppoint').on('hide.bs.modal', function () {
            $("#captcha_v2").empty();
        });

        $('#modaltest').on('shown.bs.modal', function (e) {
            var source = $(e.relatedTarget).data('source');
            $("#imgaward").attr("src",source);
        });
                
    </script>
</body>
</html>