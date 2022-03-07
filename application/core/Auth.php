<?php

/**
 * Class Auth
 * Checks if user is logged in, if not then sends the user to "yourdomain.com/login".
 * Auth::checkAuthentication() can be used in the constructor of a controller (to make the
 * entire controller only visible for logged-in users) or inside a controller-method to make only this part of the
 * application available for logged-in users.
 */
class Auth
{
    /**
     * The normal authentication flow, just check if the user is logged in (by looking into the session).
     * If user is not, then he will be redirected to login page and the application is hard-stopped via exit().
     */
    public static function checkAuthentication()
    {
        // initialize the session (if not initialized yet)
        Session::init();

        // self::checkSessionConcurrency();

        // if user is NOT logged in...
        // (if user IS logged in the application will not run the code below and therefore just go on)
        if (!Session::userIsLoggedIn()) {

            // ... then treat user as "not logged in", destroy session, redirect to login page
            Session::destroy();

            // send the user to the login form page, but also add the current page's URI (the part after the base URL)
            // as a parameter argument, making it possible to send the user back to where he/she came from after a
            // successful login
            header('location: ' . Config::get('URL') . 'login?redirect=' . urlencode($_SERVER['REQUEST_URI']));

            // to prevent fetching views via cURL (which "ignores" the header-redirect above) we leave the application
            // the hard way, via exit(). @see https://github.com/panique/php-login/issues/453
            // this is not optimal and will be fixed in future releases
            exit();
        }
    }

    /**
     * Detects if there is concurrent session (i.e. another user logged in with the same current user credentials),
     * If so, then logout.
     */
    public static function checkSessionConcurrency(){
        if(Session::userIsLoggedIn()){
            if(Session::isConcurrentSessionExists()){
                LoginModel::logout();
                Redirect::home();
                exit();
            }
        }
    }

    /**
    * Check Permission for employee
    * Return true or forworded
    */
    public static function checkPermission($department, $grade = 200)
    {
        // initialize the session (if not initialized yet)
        Session::init();

        //First Check: DEPARTMENT
        //explode department to array
        $department_array =  explode(',', $department);
        // check if session's department in $department_array
        if (!in_array(Session::get("department"), $department_array)) {
            header('location: ' . Config::get('URL') . 'user/index');
            exit;
        }

        //Secaond cehck: GRADE!
        if (Session::get("grade") < $grade) {
            header('location: ' . Config::get('URL') . 'user/index');
            exit;
        }

        return true; //default return (pass permission check!)
    }

    // Check Permission for employee by department
    // Return BOLEAN
    public static function isPermissioned($department, $grade = 200)
    {
        // initialize the session (if not initialized yet)
        Session::init();

        //First Check: DEPARTMENT
        //explode department to array
        $department_array =  explode(',', $department);
        // check if session's department in $department_array
        if (!in_array(Session::get("department"), $department_array)) {
            return false;
        }

        //Secaond cehck: GRADE!
        if (Session::get("grade") < $grade) {
            return false;
        }

        return true; //default return (pass permission check!)
    }

    public static function isUserType($department)
    {
        // initialize the session (if not initialized yet)
        Session::init();

        //First Check: DEPARTMENT
        //explode department to array
        $department_array =  explode(',', $department);
        // check if session's department in $department_array
        if (!in_array(Session::get("user_type"), $department_array)) {
            return false;
        }

        return true; //default return (pass permission check!)
    }

    // Check if given value is match
    // Return BOLEAN
    public static function isMatch($one, $two)
    {
        // initialize the session (if not initialized yet)
        Session::init();
        //explode department to array
        $two_array =  explode(',', $two);
        $two = [];
        foreach ($two_array as $key => $value) {
            $two[] = trim($value);
        }
        //var_dump($two);exit;
        // check if session's department in $department_array
        if (!in_array($one, $two)) {
            return false;
        }

        return true; //default return (pass permission check!)
    }
}
