<?php

/**
 * Class RegistrationModel
 *
 * Everything registration-related happens here.
 */
class RegistrationModel
{
    /**
     * Handles the entire registration process for DEFAULT users (not for people who register with
     * 3rd party services, like facebook) and creates a new user in the database if everything is fine
     *
     * @return boolean Gives back the success status of the registration
     */
    public static function registerNewUser()
    {
        // clean the input
        $username = Request::post('username');
        $email = Request::post('email');
        $user_password_new = Request::post('user_password_new');
        $user_password_repeat = Request::post('user_password_repeat');
        $captcha = Request::post('captcha');

        //input validation
        if (!self::validateUserName($username)) {
            Session::add('feedback_negative', 'Format Username tidak diizinkan');
            return false;
        }

        if (!self::validateUserEmail($email)) {
            Session::add('feedback_negative', 'Format email salah');
            return false;
        }

        if (!self::validateUserPassword($user_password_new, $user_password_repeat)) {
            Session::add('feedback_negative', 'Password tidak sama');
            return false;
        }

        // check captcha
        if (!CaptchaModel::checkCaptcha($captcha)) {
            Session::add('feedback_negative', Text::get('FEEDBACK_CAPTCHA_WRONG'));
            return false;
        }
        // check if username already exists
        if (UserModel::doesUsernameAlreadyExist($username)) {
            Session::add('feedback_negative', 'Maaf username sudah dipakai orang lain');
            return false;
        }
        // check if email already exists
        if (UserModel::doesEmailAlreadyExist($email)) {
            Session::add('feedback_negative', 'Alamat email sudah pernah dipakai');
            return false;
        }

        // crypt the password with the PHP 5.5's password_hash() function, results in a 60 character hash string.
        // @see php.net/manual/en/function.password-hash.php for more, especially for potential options
        $user_password_hash = password_hash($user_password_new, PASSWORD_DEFAULT);

        // generate random hash for email verification (40 char string)
        $user_activation_hash = sha1(uniqid(mt_rand(), true));

        // create data user
        if (!self::writeNewUserToDatabase($username, $user_password_hash, $email, $user_activation_hash)) {
            Session::add('feedback_negative', Text::get('FEEDBACK_ACCOUNT_CREATION_FAILED'));
            return false; // no reason not to return false here
        }


        // get uid of the user that has been created, to keep things clean we DON'T use lastInsertId() here
        $uid = UserModel::getUserIdByUsername($username);

        if (!$uid) {
            Session::add('feedback_negative', Text::get('FEEDBACK_UNKNOWN_ERROR'));
            return false;
        }

        // send verification email
        if (self::sendVerificationEmail($username, $email, $user_activation_hash)) {
            Session::add('feedback_positive', Text::get('FEEDBACK_ACCOUNT_SUCCESSFULLY_CREATED'));
            return true;
        }

        // if verification email sending failed: instantly delete the user
        self::rollbackRegistrationByUserId($uid);
        Session::add('feedback_negative', Text::get('FEEDBACK_VERIFICATION_MAIL_SENDING_FAILED'));
        return false;
    }


    /**
     * Validates the username
     *
     * @param $user_name
     * @return bool
     */
    public static function validateUserName($username)
    {
        if (empty($username)) {
            Session::add('feedback_negative', Text::get('FEEDBACK_USERNAME_FIELD_EMPTY'));
            return false;
        }

        // if username is too short (2), too long (64) or does not fit the pattern (aZ09)
        if (!preg_match('/^[a-zA-Z0-9]{2,64}$/', $username)) {
            Session::add('feedback_negative', Text::get('FEEDBACK_USERNAME_DOES_NOT_FIT_PATTERN'));
            return false;
        }

        return true;
    }

    /**
     * Validates the email
     *
     * @param $email
     * @param $email_repeat
     * @return bool
     */
    public static function validateUserEmail($email)
    {
        if (empty($email)) {
            Session::add('feedback_negative', Text::get('FEEDBACK_EMAIL_FIELD_EMPTY'));
            return false;
        }

        // validate the email with PHP's internal filter
        // side-fact: Max length seems to be 254 chars
        // @see http://stackoverflow.com/questions/386294/what-is-the-maximum-length-of-a-valid-email-address
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            Session::add('feedback_negative', Text::get('FEEDBACK_EMAIL_DOES_NOT_FIT_PATTERN'));
            return false;
        }

        return true;
    }

    /**
     * Validates the password
     *
     * @param $user_password_new
     * @param $user_password_repeat
     * @return bool
     */
    public static function validateUserPassword($user_password_new, $user_password_repeat)
    {
        if (empty($user_password_new) OR empty($user_password_repeat)) {
            Session::add('feedback_negative', Text::get('FEEDBACK_PASSWORD_FIELD_EMPTY'));
            return false;
        }

        if ($user_password_new !== $user_password_repeat) {
            Session::add('feedback_negative', Text::get('FEEDBACK_PASSWORD_REPEAT_WRONG'));
            return false;
        }

        if (strlen($user_password_new) < 6) {
            Session::add('feedback_negative', Text::get('FEEDBACK_PASSWORD_TOO_SHORT'));
            return false;
        }

        return true;
    }

    /**
     * Writes the new user's data to the database
     *
     * @param $username
     * @param $user_password_hash
     * @param $email
     * @param $user_creation_timestamp
     * @param $user_activation_hash
     *
     * @return bool
     */
    public static function writeNewUserToDatabase($username, $user_password_hash, $email, $user_activation_hash)
    {
        $database = DatabaseFactory::getFactory()->getConnection();

        // write new users data into database
        $sql = "INSERT INTO users (user_name, user_password_hash, email, user_activation_hash, is_active, created_timestamp)
                    VALUES (:user_name, :user_password_hash, :email, :user_activation_hash, :is_active, :created_timestamp)";
        $query = $database->prepare($sql);
        $query->execute(array(
                            ':user_name' => $username,
                            ':user_password_hash' => $user_password_hash,
                            ':email' => $email,
                            ':user_activation_hash' => $user_activation_hash,
                            ':is_active' => 1,
                            ':created_timestamp' => date("Y-m-d H:i:s"),
                            ));

        $count =  $query->rowCount();
        if ($count == 1) {
            return true;
        }

        return false;
    }

    /**
     * Deletes the user from users table. Currently used to rollback a registration when verification mail sending
     * was not successful.
     *
     * @param $uid
     */
    public static function rollbackRegistrationByUserId($uid)
    {
        $database = DatabaseFactory::getFactory()->getConnection();

        $query = $database->prepare("DELETE FROM users WHERE uid = :uid");
        $query->execute(array(':uid' => $uid));
    }

    /**
     * Sends the verification email (to confirm the account).
     * The construction of the mail $body looks weird at first, but it's really just a simple string.
     *
     * @param int $uid user's id
     * @param string $email user's email
     * @param string $user_activation_hash user's mail verification hash string
     *
     * @return boolean gives back true if mail has been sent, gives back false if no mail could been sent
     */
    public static function sendVerificationEmail($username, $email, $user_activation_hash)
    {
        $body = Config::get('EMAIL_VERIFICATION_CONTENT') . Config::get('URL') . Config::get('EMAIL_VERIFICATION_URL')
                . '/' . urlencode($username) . '/' . urlencode($user_activation_hash);

        $mail = new Mail;
        $mail_sent = $mail->sendMail($email, Config::get('EMAIL_VERIFICATION_FROM_EMAIL'),
            Config::get('EMAIL_VERIFICATION_FROM_NAME'), Config::get('EMAIL_VERIFICATION_SUBJECT'), $body
        );

        if ($mail_sent) {
            Session::add('feedback_positive', Text::get('FEEDBACK_VERIFICATION_MAIL_SENDING_SUCCESSFUL'));
            return true;
        } else {
            Session::add('feedback_negative', Text::get('FEEDBACK_VERIFICATION_MAIL_SENDING_ERROR') . $mail->getError() );
            return false;
        }
    }

    /**
     * checks the email/verification code combination and set the user's activation status to true in the database
     *
     * @param int $uid user id
     * @param string $user_activation_verification_code verification token
     *
     * @return bool success status
     */
    public static function verifyNewUser($username, $user_activation_verification_code)
    {
        $database = DatabaseFactory::getFactory()->getConnection();

        $sql = "UPDATE users SET is_active = 1, user_activation_hash = NULL
                WHERE user_name = :username AND user_activation_hash = :user_activation_hash LIMIT 1";
        $query = $database->prepare($sql);
        $query->execute(array(':username' => $username, ':user_activation_hash' => $user_activation_verification_code));

        if ($query->rowCount() == 1) {
            Session::add('feedback_positive', Text::get('FEEDBACK_ACCOUNT_ACTIVATION_SUCCESSFUL'));
            return true;
        }

        Session::add('feedback_negative', Text::get('FEEDBACK_ACCOUNT_ACTIVATION_FAILED'));
        return false;
    }
}
