<?php

class IndexController extends Controller
{
    /**
     * Construct this object by extending the basic Controller class
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Handles what happens when user moves to URL/index/index - or - as this is the default controller, also
     * when user moves to /index or enter your application at base level
     */
    public function index()
    {
        $this->View->renderFileOnly('login/index',
                            array(
                                'title' => 'Jual Beli Rumah, Villa dan Apartemen Mewah - The Luxury Property',
                                'meta_description' => 'Konsultan Properti Rumah, Villa dan Apartemen Mewah di Indonesia. Dapatkan penawaran properti super mewah dengan lokasi yang strategis.',
                                'meta_keywords' => 'Rumah,Villa,Apartemen,properti,real estate,property,jual,beli',
                            )
        );
    }
}
