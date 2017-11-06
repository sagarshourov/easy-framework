<?php

/**
 * 
 */
class Auth {

    public static function handleLogin() {

        @session_start();
        $logged = $_SESSION['user_id'];

        // $db = new Database(DB_TYPE, DB_HOST, DB_NAME, DB_USER, DB_PASS);
        // $array = $db->select("SELECT * FROM permission WHERE user_id='$logged'");  


        if ($logged == false) {
            // session_destroy();
            header('Location: ' . URL.'/login');
            exit;
        }
    }

    


}
