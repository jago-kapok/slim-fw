<?php


class InfoController extends Controller
{

    /**
     * Construct this object by extending the basic Controller class
     */
    public function __construct()
    {
        parent::__construct();
        
    }

    /**
     * This method controls what happens when you move to /note/index in your app.
     * Gets all notes (of the user).
     */
    public function index()
    {

    }

    public function kontak()
    {
        $this->View->render('index/fuschiaderm/kontak',
                            array(
                                'title' => 'Alamat dan Kontak Detail Fuschiaderm Aesthetic Clinic | Klinik Kecantikan Amanah dan Terpercaya di Indonesia',
                                'meta_description' => 'Alamat dan Kontak Detail Fuschiaderm Aesthetic Clinic, Klinik kecantikan yang amanah dan terpercaya di kawasannya.',
                                'meta_keywords' => 'klinik kecantikan,Fuschiaderm,Aesthetic Clinic,indonesia',
                            ),
                            'fuschiaderm'
        );
    }

    public function promo($parameter = null)
    {
        if ($parameter != null) {
            $this->View->render('index/fuschiaderm/webpost_detail',
                            array(
                                'title' => 'Alamat dan Kontak Detail Fuschiaderm Aesthetic Clinic | Klinik Kecantikan Amanah dan Terpercaya di Indonesia',
                                'meta_description' => 'Alamat dan Kontak Detail Fuschiaderm Aesthetic Clinic, Klinik kecantikan yang amanah dan terpercaya di kawasannya.',
                                'meta_keywords' => 'klinik kecantikan,Fuschiaderm,Aesthetic Clinic,indonesia',
                            ),
                            'fuschiaderm'
            );
        } else {
            $this->View->render('index/fuschiaderm/webpost',
                            array(
                                'title' => 'Alamat dan Kontak Detail Fuschiaderm Aesthetic Clinic | Klinik Kecantikan Amanah dan Terpercaya di Indonesia',
                                'meta_description' => 'Alamat dan Kontak Detail Fuschiaderm Aesthetic Clinic, Klinik kecantikan yang amanah dan terpercaya di kawasannya.',
                                'meta_keywords' => 'klinik kecantikan,Fuschiaderm,Aesthetic Clinic,indonesia',
                            ),
                            'fuschiaderm'
            );
        }
    }

    public function berita()
    {
        $this->View->render('index/fuschiaderm/berita',
                            array(
                                'title' => 'Alamat dan Kontak Detail Fuschiaderm Aesthetic Clinic | Klinik Kecantikan Amanah dan Terpercaya di Indonesia',
                                'meta_description' => 'Alamat dan Kontak Detail Fuschiaderm Aesthetic Clinic, Klinik kecantikan yang amanah dan terpercaya di kawasannya.',
                                'meta_keywords' => 'klinik kecantikan,Fuschiaderm,Aesthetic Clinic,indonesia',
                            ),
                            'fuschiaderm'
        );
    }
}