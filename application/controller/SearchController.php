<?php

class SearchController extends Controller
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
        $term = strtolower(Request::get('term')); //lower case string to easily (case insensitive) remove unwanted characters
        $replace = array(
                ' dan ',
                ' atau ',
                ' di ',
                ' ke ',
                'malang'
                );
        $replacer   = ' ';

        $term = str_replace($replace, $replacer, $term);
        $terms = array();
        $terms = explode(" ", $term);
        
        // make string search    
        $first = true;
        $stringsearch = '';
        $google_search = '';
        foreach($terms as $term) {
              if(!$first) {
                    $stringsearch .= ', ';
                    $google_search .= '+';
                }
              $stringsearch .= trim($term);
              $google_search .= trim($term);
              $first = false;
        }

        $users = SearchModel::getResult($stringsearch, 21);
        if (count($users) < 10) {
            $se_result = SearchModel::getGoogleSearch($google_search);
        }
        $this->View->render('search/index', array(
            'result' => $users,
            'se_results' => $se_result
            )
        );
    }
}
