<?php
date_default_timezone_set("Asia/Dhaka");
define('URL', 'http://localhost/phpmvc');
define('LIBS', 'libs/');

define('DB_TYPE', 'mysql');
define('DB_HOST', 'localhost');
define('DB_NAME', 'database_name');
define('DB_USER', 'database_user_name');
define('DB_PASS', '');
define('TIME', date("Y-m-d"));


// The sitewide hashkey, do not change this because its used for passwords!
// This is for other hash keys... Not sure yet
define('HASH_GENERAL_KEY', 'SECURITY_KEY');

// This is for database passwords only
define('HASH_PASSWORD_KEY', 'SECURITY_KEY_PASSWORD_HASH_TAG');

