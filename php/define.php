<?php
$su_rootClassClient = 'classes/class.main.php';
$rootDir=$_SERVER['DOCUMENT_ROOT'].'/tnatasks/';

    
    define('NOW', time());
    define('ROWS_PER_PAGE', 15);
    define('DEFAULT_LOGO', '_default_300.png');
    define('NO_ITEM_THUMB', 'default_300.png' ); //products module dir    
    
    /******* DIRECTORIES ********/
    
    define('INCLUDES', 'include/' );
    define('ADMIN_HOME', '');
    define('CUSTOMER_HOME', '');
    define('CUSTOMER_URL', '');
    
    
    
    
    // variables (locations) standard pages
    define('ADMIN_PAGE', URL . 'admin/index.php');
    define("ADMIN_URL", URL . "admin/");
    define("MAIN_PAGE", ADMIN_URL."index.php");
    define("HOME_PAGE", URL."index.php");
    define("LOGIN_PAGE", URL."user/login.php");
    define("START_PAGE", "./example.php");
    define("LOGOUT_PAGE_XP", URL."user/logout.php");
    define("LOGOUT_PAGE", URL."user/login.php"); // if you use the setting "USE_MYSQL_SESSIONS" you need a logout page without class object to clear the old session data from the database
    define("IMAGES_DIR", $rootDir . "images/");
    //echo IMAGES_DIR; 
    // change this constants to the right mail settings
    define("WEBMASTER_MAIL", "info@tna.co.ke"); 
    define("WEBMASTER_NAME", "TNA HQ"); 
    define("ADMIN_MAIL", "admin@tna.co.ke"); 
    define("ADMIN_NAME", "TNA Admin"); 

    // change this vars if you need...
    define("PW_MAX_LENGTH", 32);
    define("PW_MIN_LENGTH", 6);
    define("LOGIN_LENGTH", 6);
    define("SECRET_STRING", "gyiutv659957"); // since version 1.98

    define("COOKIE_NAME", "ntvVideoAppUserId");       //CHANGE THIS TO ONION
    define("COOKIE_PATH", URL);
    define("MIN_ACCESS_LEVEL", 1);
    define("MAX_ACCESS_LEVEL", 10);
    define("DEFAULT_ACCESS_LEVEL", 1);
    define("DEFAULT_ADMIN_LEVEL", 10);
    
    // change these settings to use phpmailer in place of the PHP mail() function
    define("USE_PHP_MAILER", true); // true = use phpmailer
    define("PHP_MAILER_SMTP", false); // true send by SMTP, false = sendmail
    // if you enable these features you need to install the phpmailer class!
    if (USE_PHP_MAILER) include_once($rootDir."include/classes/phpMailer/class.phpmailer.php");
    if (PHP_MAILER_SMTP) {
        define("SMTP_SERVER", "mail.domain.com");
        define("SMTP_LOGIN", "login");
        define("SMTP_PASSWD", "password");
    }
    

?>