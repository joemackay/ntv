<?php
session_start(); 
DEFINE('USERS', 'ntvusers');
class UserMain extends main {
    
    //login
    var $save_login = "yes"; //retrieve from remember me checkbox
    
    //var $user;
    var $user_id;
    var $user_email;
    var $user;
    var $username;
    var $user_pw;
    var $personal_array = array();
    
    //cookies
    var $cookie_name = COOKIE_NAME;
    var $cookie_path = COOKIE_PATH; 
    var $is_cookie;
    
    //system
    var $language = "en"; // change this property to use messages in another language 
    var $the_msg;
    var $the_success;
    var $the_success_msg;
    var $send_copy = true; // send a mail copy (after activation) to the administrator 
    var $activation_key;  //secret key to be used for activation
    
    //scripts
    var $login_page;
    var $main_page;
    
    //error output
    var $msg;  
    var $last_error;
    var $check_user_email;
    
    //database
    var $dbObj;
    var $users_table = 'users';
    
    //password
    var $cypher="sha256";
    
    function __construct($redirect = true) {
        $this->connect();
        if (empty($_SESSION['user_id'])) {
            if ($this->is_cookie) {
                $this->set_user($redirect);
            }
        }         
        if (isset($_SESSION['user_id'])) {
            $this->user_id = $_SESSION['user_id'];
        }
        if (isset($_SESSION['user_email'])) {
            $this->user_email = $_SESSION['user_email'];
        }
        $this->main_page = ADMIN_PAGE;
        $this->login_page = LOGIN_PAGE;  
        
    }
    
    function check_user($pass = "") {
        switch ($pass) {
            case "new": 
            $sql = sprintf("SELECT COUNT(*) AS num_rows FROM %s WHERE email = %s ", USERS, $this->ins_string($this->user_email));
            break;
            default:
            $sql = sprintf("SELECT COUNT(*) AS num_rows FROM %s WHERE email = %s AND password = %s", USERS, $this->ins_string($this->user_email), $this->ins_string($this->user_pw));
        }
        $result = mysql_query($sql) or die('Check error=>'.mysql_error());
        if (mysql_result($result, 0, "num_rows") == 1) {
            return true;
        } else {
            return false;
        }
    }             
     
    function get_personal_info($id='') { 
        $user_id = (isset($id) && !empty($id)) ? $id : $this->user_id;  
        $personal_info = array();
        $sql_info = sprintf("SELECT *
                             FROM `users` 
                             WHERE id = %d", $user_id);
        //echo $sql_info;
        $res_info = mysql_query($sql_info);
        $row_info = mysql_fetch_array($res_info, MYSQL_ASSOC);
        return $row_info;
        
    }
    
    function check_new_password($pass, $pw_conform) {
        
        if ($pass === $pw_conform) {
            //echo $pass;
            //echo $pw_conform;
            if (strlen($pass) >= PW_MIN_LENGTH && strlen($pass) <= PW_MAX_LENGTH) {
                return true; 
                //echo 'passed';
            } else {
                return false;  
                //echo 'failed';
            }
        } else {
            //echo 'failed match';
            return false;
        }    
    }
    
    function check_exisiting_password($password) { 
        $sql = sprintf("SELECT password FROM `users` WHERE id = %d ", $this->user_id);
        $result = mysql_query($sql) or die(mysql_error());
        if (!$result ||(mysql_num_rows($result)<1)){
            return 1;
        }
        $dbarray = mysql_fetch_array($result);
        $dbarray['password'] = stripslashes($dbarray['password']);
        
        if (strrev(md5(strrev($password))) === $dbarray['password']){
           return 0;
        }else{
           return 2;
        }
    }
    
    /**
    * fetch user id from users table overrides the ancestor
    * @return (int)user id
    */
    function get_user_id() {
        $sql = sprintf("SELECT * 
                        FROM `ntvusers`
                        WHERE email = %s", $this->ins_string($this->user_email));
        $result = mysql_query($sql) or die(mysql_error());
        if (!$result) {
           $this->the_msg = $this->messages(14);
        } else {
            $user = mysql_fetch_assoc($result);
            return $user;// mysql_result($result, 0, "id");
        }
    }
    
    /**
    * multi-purpose method to fetch password
    * @returns string password
    */
    public function fetchPass() {
        $sql = sprintf("SELECT password FROM %s WHERE email = %s AND active = 1", USERS, $this->ins_string($this->user_email));
        if (!$result = mysql_query($sql)) {
           $this->the_msg = $this->messages(14);
        } else {
            $row = mysql_fetch_array($result);
            return $row['password']; 
        }        
    }
    
    public function login_user($email, $password) {
        if ($email != "" && $password != "") {
            $this->user_email = $email;
            
            $db_pass = $this->fetchPass();
            if ($this->comparePassword($db_pass, $password)) {
                $this->login_saver();
                $this->set_user();
            } else {
                $this->the_msg = $this->messages(10);
            }
        } else {
            $this->the_msg = $this->messages(11);
        }
    }
    
    private function set_user() {
        $user = $this->get_user_id();
        $_SESSION['user_id'] = $this->user_id = $user['user_id'];
        $_SESSION['user_email'] = $this->user_email;
        $_SESSION["loginIP"] = $_SERVER["REMOTE_ADDR"];        
    }
    
    /**
    * sets a cookie with user details for 'remember me'
    * 
    */
    public function login_saver() {
        if ($this->save_login == "no") {
            if (isset($_COOKIE[$this->cookie_name])) {
                $expire = time()-3600;
            } else {
                return;
            }
        } else {
            $expire = time()+2592000;  //30 days
        }    
        $cookie_str = $this->user_id.chr(31).$this->user_email.chr(31).base64_encode($this->user_pw);
        setcookie($this->cookie_name, $cookie_str, $expire, $this->cookie_path);
        setcookie('NtvVidzAppUserId', $this->user_id, $expire, $this->cookie_path);
        setcookie('NtvVidzAppUserEmail', $this->user_email, $expire, $this->cookie_path);
        setcookie('NtvVidzAppUserPass', base64_encode($this->user_pw), $expire, $this->cookie_path);
        /*print_r('<pre>');
        print_r($_COOKIE); */
    }
    
    /**
    * check if user set 'remember me' in cookies 
    * @returns nothing
    */
    public function login_reader() {
        if (isset($_COOKIE[$this->cookie_name])) {
            $cookie_parts = explode(chr(31), $_COOKIE[$this->cookie_name]);
            $this->user_id = $cookie_parts[0];
            $this->user_email = $cookie_parts[1];
            $this->user_pw = base64_decode($cookie_parts[2]);
            if ($this->check_user()) {
                $this->is_cookie = true;
                return $this->user_id;
            } else {
                unset($this->user_id);
                unset($this->user_pw);
            }
        }             
    }
    
    /**
    * logs out the user
    * 
    */
    public function log_out() {
        unset($_SESSION['user_id']);
        unset($_SESSION['user_email']);
        unset($_SESSION['user_name']);
        
        session_destroy(); 
        header("Location: ".LOGOUT_PAGE);
        exit;
    }
    
    /**
    * this protects the sessions by refering the user back to the login page while 
    * storing the page he/she wanted to go to, then after login, redirects to the required page
    * 
    * @param mixed $refer
    * @param mixed $qs
    * @param mixed $level
    */
    public function access_page($refer = "", $qs = "", $level = DEFAULT_ACCESS_LEVEL) {
        $refer_qs = $refer;
        $refer_qs .= ($qs != "") ? "?".$qs : "";
        if (!$this->sessionAuthenticate()) {
            $_SESSION['referer'] = $refer_qs;
            header("Location: ".$this->login_page);
            exit;
        }
        /**if ($this->get_access_level() < $level) {
            header("Location: ".$this->deny_access_page);
            exit;
        } */
    }
    
    /**
    * generates a new password
    * 
    * @param mixed $password
    * @return string
    */
    public function generatePassword($password) {
        $pwd = new Password();
        $cypher=$this->cypher;
        $pwd->set_hash_rounds($cypher, 10000);
        $pwd->set_hash_type( $cypher );
        $formhash = $pwd->hash($password);
        return $formhash;
    }
    
    /**
    * this compares the login password with the password in the database
    * it is the central password comparer, and uses an external function
    * 
    * @param mixed $db_hash
    * @param mixed $password
    * @return boolean
    */
    public function comparePassword($db_hash, $password) {
        $pwd = new Password();
        $cypher=$this->cypher;
        $pwd->set_hash_rounds($cypher, 10000);
        $pwd->set_hash_type( $cypher );
        $hash = $pwd->re_hash( $db_hash, $password );
        return ( $hash === $db_hash ) ? true : false; 
    }
    
    public function register_user_profile($email, $first_password, $confirm_password) {  //EXPAND TO ACCOMODATE ADDITIONAL PRPOPERTIES
        if($email !=='' && $first_password !=='') {
            if ($this->check_new_password($first_password, $confirm_password)) {
                if ($this->check_email($email)) {
                    $this->user_email = $email;
                    $password = $this->generatePassword($first_password);
                    if ($this->check_user("new")) {
                        $this->the_msg = $this->messages(12);
                        return;
                    } else { 
                        $sqlUsers = sprintf("INSERT INTO `ntvusers`
                                                SET
                                                    `email` = %s,
                                                    `password` = %s,
                                                    `date_joined` = %s,
                                                    `active` = %d
                                                ",
                            $this->ins_string($email),
                            $this->ins_string($password),
                            $this->ins_string(time()),
                            1
                        );
                        $resUsers = mysql_query($sqlUsers) or die('Error=>'.mysql_error());
                        if ($resUsers) {  
                            $this->user_id = mysql_insert_id();
                            $this->user_pw = $password;
                            $this->login_saver();
                            //return $this->user_id;
                        } else {
                            $this->the_msg = $this->messages(15);
                        }
                    }
                } else {
                    $this->the_msg = $this->messages(16);
                }
            } 
        }
    }
    
    public function update_user_profile($username, $email, $mobile, $firstname, $lastname) {
        if($email !=='' && $firstname !=='') {
            if ($this->check_email($email)) {
                $this->user_email = $email;
                $sqlUsers = sprintf("UPDATE `users`
                                        SET
                                            `fname` = %s,
                                            `lname` = %s,
                                            `phone` = %s,
                                            `email` = %s,
                                            `active` = %d
                                      WHERE id = %d",
                    $this->ins_string($firstname),
                    $this->ins_string($lastname),
                    $this->ins_string($mobile),
                    $this->ins_string($email),
                    1,
                    $_SESSION['user_id']
                );
                $resUsers = mysql_query($sqlUsers) or die('Registration 1 Error=>'.mysql_error());
                if ($resUsers) {
                    if(!isset($_SESSION['referer'])) $_SESSION['referer'] = ADMIN_PAGE;
                    $this->login_saver();
                    $this->set_user(false);
                } else {
                    $this->the_msg = $this->messages(15);
                }
            } else {
                $this->the_msg = $this->messages(16);
            }            
        } 
    }

    function update_user_password($old_password, $new_password, $new_confirm_conf) {
        $db_pass = $this->fetchPass();  
        if($this->check_new_password($new_password, $new_confirm_conf)) {
            if ($this->comparePassword($db_pass, $old_password)) {
                $ins_password = $this->generatePassword($new_password);
                $update_pw = true;
                $upd_sql = sprintf("UPDATE `".USERS."` 
                                    SET 
                                        `password` = %s 
                                    WHERE `id` = %d", 
                                    $this->ins_string($ins_password),
                                    $this->user_id);  
                //echo $upd_sql;
                $upd_res = mysql_query($upd_sql) or die('update error' . mysql_error());                
                if ($upd_res) {
                    if ($update_pw) {
                        //$_SESSION['futor_user_password'] = $this->user_pw = $ins_password;
                        if (isset($_COOKIE[$this->cookie_name])) {
                            $this->save_login = "yes";
                            $this->login_saver();
                        }                         
                        $this->the_success = "Successfully updated your password";               
                    }
                    return true;
                    $this->the_msg = $this->messages(30);
                } else {
                    $this->the_msg = $this->messages(15);
                }
            } else {
                $this->the_msg = 'Wrong Password';
            }    
        } else {
            $this->the_msg = 'New Password fields must Passwords match';   
        }   
    }
    
    function validate_email($validation_key, $key_id) {
        if ($validation_key != "" && strlen($validation_key) == 32 && $key_id > 0) {
            $this->id = $key_id;
            if ($this->check_user("validate")) {
                $upd_sql = sprintf("UPDATE %s SET email = tmp_mail, tmp_mail = '' WHERE id = %d AND MD5(pw) = %s", USERS, $key_id, $this->ins_string($validation_key));
                if (mysql_query($upd_sql)) {
                    $this->the_msg = $this->messages(18);
                } else {
                    $this->the_msg = $this->messages(19);
                }
            } else {
                $this->the_msg = $this->messages(34);
            }
        } else {
            $this->the_msg = $this->messages(21);
        }
    }
    
    /**
    * send an email with a password reset link to the user
    * 
    * @param mixed $forgot_email
    */
    
    function ins_string($value) {
        if (preg_match("/^(.*)(##)(int|date|eu_date)$/", $value, $parts)) {
            $value = $parts[1];
            $type = $parts[3];
        } else {
            $type = "";
        }
        $value = (!get_magic_quotes_gpc()) ? addslashes($value) : $value;
        switch ($type) {
            case "int":
                $value = ($value != "") ? intval($value) : NULL;
            break;
            case "eu_date":
                $date_parts = preg_split ("/[\-\/\.]/", $value); 
                $time = mktime(0, 0, 0, $date_parts[1], $date_parts[0], $date_parts[2]);
                $value = strftime("'%Y-%m-%d'", $time);
            break;
            case "date":
                $value = "'".preg_replace("/[\-\/\.]/", "-", $value)."'";
            break;
            default:
                $value = ($value != "") ? "'" . trim($value) . "'" : "''";      //ADD A FUNCTION TO SETTYPES STRVAL
            break;
        }
        return $value;
    }
    
    function check_email($mail_address) {
 
        // Validate the email
        if (!preg_match("/^[0-9a-z]+(([\.\-_])[0-9a-z]+)*@[0-9a-z]+(([\.\-])[0-9a-z-]+)*\.[a-z]{2,4}$/i", $mail_address)) {  
            $this->the_msg= "The email must be in the name@domain format.";
            return false;
        } elseif (strlen($mail_address) > 60) { 
            $this->the_msg= "The email address can be no longer than 60 characters.";
            return false;
        } /**elseif (function_exists("getmxrr") && function_exists("gethostbyname")) {
            // Extract the domain of the email address
            $maildomain = substr(strstr($mail_address, '@'), 1);

            if (!(getmxrr($maildomain, $temp) || gethostbyname($maildomain) != $maildomain)) {
                print "The domain does not exist.";
                return false;
            }
        } */
        return true;
    }
    
    // Connects to a session and checks that the user has authenticated and that the remote IP address matches the address used to create the session.
    public function sessionAuthenticate( ) {
        // Check if the user hasn't logged in
        if (!isset($_SESSION["user_email"]) && !isset($_SESSION["user_id"])) {
            //save page
            if(isset($_SERVER["HTTP_REFERER"]))
                if(!isset($_SESSION['referer'])) $_SESSION['referer'] = $_SERVER["HTTP_REFERER"];
            
            // The request does not identify a session
            if(!isset($_SESSION['logout_message'])) $_SESSION["logout_message"] = "Please <strong>login</strong> to continue ";
            header("Location: ".LOGOUT_PAGE);
            exit;
        }

        // Check if the request is from a different IP address to previously
        if (!isset($_SESSION["loginIP"]) || ($_SESSION["loginIP"] != $_SERVER["REMOTE_ADDR"])) {
            // The request did not originate from the machine that was used to create the session.          
            // THIS IS POSSIBLY A SESSION HIJACK ATTEMPT     
            $_SESSION["logout_message"] = "You need to login to access the page <strong> {$_SERVER["REQUEST_URI"]} from the address {$_SERVER["REMOTE_ADDR"]}</strong>";
            header("Location:".LOGOUT_PAGE);
            exit;
        }
    }    
    // General-purpose validation functions     
    
    public function send_confirmation($id) {
        $sql = sprintf("SELECT email FROM `".USERS."` WHERE user_id = %d", intval($id));
        $res = mysql_query($sql);
        $user_email = mysql_result($resDetails, 0, "email");
        
        $user_full_name = ($user_firstname !='') ? $user_firstname : "NTV Videos App User"; // change "User" to whatever you want, it's just a default name
        if ($this->send_mail($user_email, 37, 24, true)) {
            return true;
        } else {
            return false;
        }
    }
    
    // new in version 1.99 support for phpmailer as alternative mail program    
    function send_mail($mail_address, $msg = 29, $subj = 28, $send_admin = false) {
        $mail = new SimpleMail();
        if($mail->send_mail($mail_address, $msg, $subj, $send_admin)) {
            return true;
        }
    }
    
    private function messages($num) {
        $host = "http://".$_SERVER['HTTP_HOST'];
    
        $msg[10] = "Please enter the right email address or password.";
        $msg[11] = "Login and/or password field is empty!";
        $msg[12] = "Sorry, a user with this E-mail address already exists, choose a new one.";
        $msg[13] = "Please check your e-mail and follow the instructions.";
        $msg[14] = "Sorry, an error occurred please try it again.";
        $msg[15] = "Sorry, an error occurred please try it again later.";
        $msg[16] = "The e-mail address is not valid.";
        $msg[17] = "The field login (min. ".LOGIN_LENGTH." char.) is required.";
        $msg[18] = "Your request is processed. Login to continue.";
        $msg[19] = "Sorry, cannot activate your account.";
        $msg[20] = "There is no account to activate.";
        $msg[21] = "Sorry, this activation key is not valid!";
        $msg[22] = "Sorry, there is no active account which matches this e-mail address.";
        $msg[23] = "Please check your e-mail to get your new password.";
        $msg[24] = "Your user account is activated... ";
        $msg[25] = "Sorry, cannot activate your password.";
        $msg[26] = "Your forgotten password..."; 
        $msg[27] = "Please check your e-mail and activate your modification(s).";
        $msg[28] = "Your request must be processed...";
        $msg[29] = "Hello,\r\n\r\nto activate your request click the following link:\r\nident=".$this->user_id."&activate=".$this->activation_key."&language=".$this->language."\r\n\r\nkind regards\r\n";
        $msg[30] = "Your account is modified.";
        $msg[31] = "This e-mail address already exist, please use another one.";
        $msg[32] = "The field password (min. ".LOGIN_LENGTH." char) is required.";
        $msg[33] = "Hello,\r\n\r\nthe new e-mail address must be validated, click the following link:\r\n".$host.$this->login_page."?id=".$this->user_id."&validate=".md5($this->user_pw)."&language=".$this->language."\r\n\r\nKind regards\r\n Onion Team \r\n";
        $msg[34] = "There is no e-mail address for validation.";
        $msg[35] = "Hello,\r\n\r\nEnter your new password next, please click the following link to enter the form:\r\n?activate=".md5($this->user_email)."&language=".$this->language."\r\n\r\nkind regards\r\n";
        $msg[36] = "Your request is processed and is pending for validation by the admin. \r\nYou will get an e-mail if it's done.";
        $msg[37] = "Hello,\r\n\r\nThe account is active and it's possible to login now.\r\n\r\nClick on this link to access the login page:\r\n".$host.$this->login_page."\r\n\r\nkind regards\r\n";
        $msg[38] = "The confirmation password does not match the password. Please try again.";
        $msg[39] = "A new user...";
        $msg[40] = "There was a new user registration on ".date("Y-m-d").":\r\n\r\nClick here to enter the admin page:\r\n\r\n".$host."?login_id=".$this->user_id;
        $msg[41] = "Validate your e-mail address..."; // subject in e-mail
        $msg[42] = "Your e-mail address is modified.";
        $msg[43] = "Sorry this activation key is expired";
        $msg[44] = "Mobile phone number should be in the format **** ******";
        $msg[45] = "Order Confirmation";
        $msg[46] = "Your order was successfully received";
    
        return $msg[$num];
    }
}
