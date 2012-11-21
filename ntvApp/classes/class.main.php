<?php
class main {
    
   var $last_error = array();         // holds the last error. Usually mysql_error()
   var $last_success = array();         // holds the last success.
   var $last_query;         // holds the last query executed.
   var $row_count;          // holds the last number of rows from a select
   
   var $host;               // mySQL host to connect to
   var $user;               // mySQL user name
   var $pw;                 // mySQL password
   var $db;                 // mySQL database to select

   //emailing
   var $send_copy = false; // send a mail copy to the administrator 
   
   var $db_link;            // current/last database link identifier
   var $auto_slashes;       // the class will add/strip slashes when it can
   var $time = array ( "6"=>"6 AM", "7"=>"7 AM", "8"=>"8 AM", "9"=>"9 AM", "10"=>"10 AM", "11"=>"11 AM", "12"=>"12 PM", "13"=>"1 PM", "14"=>"2 PM", "15"=>"3 PM", "16"=>"4 PM", "17"=>"5 PM", "18"=>"6 PM", "19"=>"7 PM", "20"=>"8 PM", "21"=>"9 PM", "22"=>"10 PM", "23"=>"11 PM", "24"=>"12 AM", "1"=>"1 AM", "2"=>"2 AM", "3"=>"3 AM", "4"=>"4 AM", "5"=>"5 AM",);
   //retailer
   var $user_id;
   
   function __construct() {                 
        date_default_timezone_set('Africa/Nairobi');
        $this->auto_slashes = true;
   }

   function connect($host=HOST, $user=USER, $pw=PASS, $db=DBASE, $persistant=true) {
      // Opens a connection to MySQL and selects the database.  If any of the
      // function's parameter's are set, we want to update the class variables.  
      // If they are NOT set, then we're giong to use the currently existing
      // class variables.
      // Returns true if successful, false if there is failure.  
      
      if (!empty($host)) $this->host = $host; 
      if (!empty($user)) $this->user = $user; 
      if (!empty($pw)) $this->pw = $pw; 

 
      // Establish the connection.
      if ($persistant) 
         $this->db_link = mysql_pconnect($this->host, $this->user, $this->pw);
      else 
         $this->db_link = mysql_connect($this->host, $this->user, $this->pw);
 
      // Check for an error establishing a connection
      if (!$this->db_link) {
         $this->last_error = mysql_error();
         return false;
      } 
  
      // Select the database
      if (!$this->select_db($db)) return false;
      foreach($_POST as $key=>$value) {
            if(!is_array($value)) {
                $_POST[$key]=mysql_real_escape_string( trim($value));
            } else {
                foreach($value as $key=>$val) {
                    $value[$key]=mysql_real_escape_string( trim($val));        
                }    
            }
            $value=filter($value, array('safe'=>1, 'elements'=>'b, strong, i, em')); 
        }
      return $this->db_link;  // success
   }

   function select_db($db='') {
 
      // Selects the database for use.  If the function's $db parameter is 
      // passed to the function then the class variable will be updated.
 
      if (!empty($db)) $this->db = $db; 
      
      if (!mysql_select_db($this->db)) {
         $this->last_error = mysql_error();
         return false;
      }
 
      return true;
   }
   
   function select($sql) {
      
      // Performs an SQL query and returns the result pointer or false
      // if there is an error.
 
      $this->last_query = $sql;
      
      $r = mysql_query($sql);
      if (!$r) {
         $this->last_error = mysql_error();
         return false;
      }
      $this->row_count = mysql_num_rows($r);
      return $r;
   }

   function select_one($sql) {
 
      // Performs an SQL query with the assumption that only ONE column and
      // one result are to be returned.
      // Returns the one result.
 
      $this->last_query = $sql;
      
      $r = mysql_query($sql);
      if (!$r) {
         $this->last_error = mysql_error();
         return false;
      }
      if (mysql_num_rows($r) > 1) {
         $this->last_error = "Your query in function select_one() returned more that one result.";
         return false;     
      }
      if (mysql_num_rows($r) < 1) {
         $this->last_error = "Your query in function select_one() returned no results.";        
         return false;
      }
      $ret = mysql_result($r, 0);
      mysql_free_result($r);
      if ($this->auto_slashes) return stripslashes($ret);
      else return $ret;
   }
   
   function get_row($result, $type='MYSQL_BOTH') {
 
      // Returns a row of data from the query result.  You would use this
      // function in place of something like while($row=mysql_fetch_array($r)). 
      // Instead you would have while($row = $db->get_row($r)) The
      // main reason you would want to use this instead is to utilize the
      // auto_slashes feature.
      
      if (!$result) {
         $this->last_error = "Invalid resource identifier passed to get_row() function.";
         return false;  
      }
      
      if ($type == 'MYSQL_ASSOC') $row = mysql_fetch_array($result, MYSQL_ASSOC);
      if ($type == 'MYSQL_NUM') $row = mysql_fetch_array($result, MYSQL_NUM);
      if ($type == 'MYSQL_BOTH') $row = mysql_fetch_array($result, MYSQL_BOTH); 
      
      if (!$row) return false;
      if ($this->auto_slashes) {
         // strip all slashes out of row...
         foreach ($row as $key => $value) {
            $row[$key] = stripslashes($value);
         }
      }
      return $row;
   }
   
   function dump_query($sql) {
   
      // Useful during development for debugging  purposes.  Simple dumps a 
      // query to the screen in a table.
 
      $r = $this->select($sql);
      if (!$r) return false;
      echo "<div style=\"border: 1px solid blue; font-family: sans-serif; margin: 8px;\">\n";
      echo "<table cellpadding=\"3\" cellspacing=\"1\" border=\"0\" width=\"100%\">\n";
      
      $i = 0;
      while ($row = mysql_fetch_assoc($r)) {
         if ($i == 0) {
            echo "<tr><td colspan=\"".sizeof($row)."\"><span style=\"font-face: monospace; font-size: 9pt;\">$sql</span></td></tr>\n";
            echo "<tr>\n";
            foreach ($row as $col => $value) {
               echo "<td bgcolor=\"#E6E5FF\"><span style=\"font-face: sans-serif; font-size: 9pt; font-weight: bold;\">$col</span></td>\n";
            }
            echo "</tr>\n";
         }
         $i++;
         if ($i % 2 == 0) $bg = '#E3E3E3';
         else $bg = '#F3F3F3';
         echo "<tr>\n";
         foreach ($row as $value) {
            echo "<td bgcolor=\"$bg\"><span style=\"font-face: sans-serif; font-size: 9pt;\">$value</span></td>\n";
         }
         echo "</tr>\n";
      }
      echo "</table></div>\n";
   }
   
   function insert_sql($sql) {
       
        // Inserts data in the database via SQL query.
        // Returns the id of the insert or true if there is not auto_increment
        // column in the table.  Returns false if there is an error.      

        $this->last_query = $sql;

        $r = mysql_query($sql);
        if (!$r) {
         $this->last_error = mysql_error();
         return false;
        }

        $id = mysql_insert_id();
        if ($id == 0) 
            return true;
        else 
            return $id; 
    }

   function update_sql($sql) {
 
      // Updates data in the database via SQL query.
      // Returns the number or row affected or true if no rows needed the update.
      // Returns false if there is an error.

      $this->last_query = $sql;
      
      $r = mysql_query($sql);
      if (!$r) {
         $this->last_error = mysql_error();
         return false;
      }
      
      $rows = mysql_affected_rows();
      if ($rows == 0) return true;  // no rows were updated
      else return $rows;
      
   }
   
   function insert_array($table, $data) {
      
      // Inserts a row into the database from key->value pairs in an array. The
      // array passed in $data must have keys for the table's columns. You can
      // not use any MySQL functions with string and date types with this 
      // function.  You must use insert_sql for that purpose.
      // Returns the id of the insert or true if there is not auto_increment
      // column in the table.  Returns false if there is an error.
      
      if (empty($data)) {
         $this->last_error = "You must pass an array to the insert_array() function.";
         return false;
      }
      
      $cols = '(';
      $values = '(';
      
      foreach ($data as $key=>$value) {     // iterate values to input
          
         $cols .= "$key,";  
         
         $col_type = $this->get_column_type($table, $key);  // get column type
         if (!$col_type) return false;  // error!
         
         // determine if we need to encase the value in single quotes
         if (is_null($value)) {
            $values .= "NULL,";   
         } elseif (substr_count(MYSQL_TYPES_NUMERIC, "$col_type ")) {
            $values .= "$value,";
         } elseif (substr_count(MYSQL_TYPES_DATE, "$col_type ")) {
            $value = $this->sql_date_format($value, $col_type); // format date
            $values .= "'$value',";
         } elseif (substr_count(MYSQL_TYPES_STRING, "$col_type ")) {
            if ($this->auto_slashes) $value = addslashes($value);
            $values .= "'$value',";  
         }
      }
      $cols = rtrim($cols, ',').')';
      $values = rtrim($values, ',').')';     
      
      // insert values
      $sql = "INSERT INTO $table $cols VALUES $values";
      return $this->insert_sql($sql);
      
   }
   
   function update_array($table, $data, $condition) {
      
      // Updates a row into the database from key->value pairs in an array. The
      // array passed in $data must have keys for the table's columns. You can
      // not use any MySQL functions with string and date types with this 
      // function.  You must use insert_sql for that purpose.
      // $condition is basically a WHERE claus (without the WHERE). For example,
      // "column=value AND column2='another value'" would be a condition.
      // Returns the number or row affected or true if no rows needed the update.
      // Returns false if there is an error.
      
      if (empty($data)) {
         $this->last_error = "You must pass an array to the update_array() function.";
         return false;
      }
      
      $sql = "UPDATE $table SET";
      foreach ($data as $key=>$value) {     // iterate values to input
          
         $sql .= " $key=";  
         
         $col_type = $this->get_column_type($table, $key);  // get column type
         if (!$col_type) return false;  // error!
         
         // determine if we need to encase the value in single quotes
         if (is_null($value)) {
            $sql .= "NULL,";   
         } elseif (substr_count(MYSQL_TYPES_NUMERIC, "$col_type ")) {
            $sql .= "$value,";
         } elseif (substr_count(MYSQL_TYPES_DATE, "$col_type ")) {
            $value = $this->sql_date_format($value, $col_type); // format date
            $sql .= "'$value',";
         } elseif (substr_count(MYSQL_TYPES_STRING, "$col_type ")) {
            if ($this->auto_slashes) $value = addslashes($value);
            $sql .= "'$value',";  
         }

      }
      $sql = rtrim($sql, ','); // strip off last "extra" comma
      if (!empty($condition)) $sql .= " WHERE $condition";
      
      // insert values
      return $this->update_sql($sql);
   }
   
   function upload_file($file, $path) {
        if(!file_exists($path)){
            $this->last_error = "The file $file does not exist.";
            return false;
        }   
    }
   
   function execute_file ($file) {
 
      // executes the SQL commands from an external file.
      
      if (!file_exists($file)) {
         $this->last_error = "The file $file does not exist.";
         return false;
      }
      $str = file_get_contents($file);
      if (!$str) {
         $this->last_error = "Unable to read the contents of $file.";
         return false; 
      }
      
      $this->last_query = $str; 
      
      // split all the query's into an array
      $sql = explode(';', $str);
      foreach ($sql as $query) {
         if (!empty($query)) {
            $r = mysql_query($query);
 
            if (!$r) {
               $this->last_error = mysql_error();
               return false;
            }
         }
      }
      return true;
    
   }
   
   function get_column_type($table, $column) {
      
      // Gets information about a particular column using the mysql_fetch_field
      // function.  Returns an array with the field info or false if there is
      // an error.
 
      $r = mysql_query("SELECT $column FROM $table");
      if (!$r) {
         $this->last_error = mysql_error();
         return false;
      }
      $ret = mysql_field_type($r, 0);
      if (!$ret) {
         $this->last_error = "Unable to get column information on $table.$column.";
         mysql_free_result($r);
         return false;
      }
      mysql_free_result($r);
      return $ret;
      
   }
   
   function sql_date_format($value) {

      // Returns the date in a format for input into the database.  You can pass
      // this function a timestamp value such as time() or a string value
      // such as '04/14/2003 5:13 AM'. 
      
      if (gettype($value) == 'string') $value = strtotime($value);
      return date('Y-m-d H:i:s', $value);

   }
   
   function create_form_field($formelement, $label, $length = 25, $required = false, $disabled = false, $euro_date = false) {
        $form_field = "<label for=\"".$formelement."\">".$label."</label>\n";
        $form_field .= "  <input name=\"".$formelement."\" type=\"text\" size=\"".$length."\" value=\"";
        if (isset($_REQUEST[$formelement])) {
            $form_field .= $_REQUEST[$formelement];
        } elseif (isset($this->$formelement) && !isset($_REQUEST[$formelement])) {
            $form_field .= ($euro_date && $this->$formelement != "") ? strftime("%d/%m/%Y", strtotime($this->$formelement)) : $this->$formelement;
        } else {
            $form_field .= "";
        }
        $form_field .= ($disabled) ? "\" disabled>" : "\">";
        $form_field .= ($required) ? "*<br>\n" : "<br>\n";
        return $form_field;        
}
   
   function create_text_area($text_field, $label) {
        $textarea = "<label for=\"".$text_field."\">".$label."</label>\n";
        $textarea .= "  <textarea name=\"".$text_field."\">";
        if (isset($_REQUEST[$text_field])) {
            $textarea .= $_REQUEST[$text_field];
        } elseif (isset($this->$text_field)) {
            $textarea .= $this->$text_field;
        } else {
            $textarea .= "";
        }
        $textarea .= "</textarea><br>\n";
        return $textarea;        
    }
   
    /**
    * validates email addresses and domain names
    * @author Joe Okatch
    * @date 5/12/2010
    * @param mixed $mail_address
    */
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
    
    function check_twitter_handle($twitter_address) {
 
        // Validate the twitter handle @([A-Za-z0-9_]+)
        if (!preg_match("/@([A-Za-z0-9_]+)/i", $twitter_address)) {  
            $this->the_msg= "The twitter handle must be in the @name format.";
            return false;
        } elseif (strlen($twitter_address) > 160) { 
            $this->the_msg= "The twitter handle can be no longer than 160 characters.";
            return false;
        } 
        return true;
    }
    
    /**
    * validates urls and domain names
    * @author Joe Okatch
    * @date 5/12/2010
    * @param mixed int
    */
    function checkURL($url) {
        $schemes = array('http', 'https', 'ftp', 'ftps',); 
        $bits = parse_url($url);

        if (!in_array($bits["scheme"], $schemes)) {
            $this->the_msg= "URL must begin with http://.";
        } elseif (empty($bits["host"])) {
            $this->the_msg= "URL must include a host name.";
        } elseif (function_exists('checkdnsrr') && !checkdnsrr($bits["host"], 'A')) {
            $this->the_msg= "Host does not exist.";
        }
        return 0;  
            
    }
    
    
    function FixMyURL($address) {
        if (!empty($address) AND $address{0} != '#' AND strpos(strtolower($address), 'mailto:') === FALSE AND strpos(strtolower($address), 'javascript:') === FALSE) {
            $address = explode('/', $address);
            $keys = array_keys($address, '..');

            foreach($keys AS $keypos => $key)
                array_splice($address, $key - ($keypos * 2 + 1), 2);

            $address = implode('/', $address);
            $address = str_replace('./', '', $address);
           
            $scheme = parse_url($address);
           
            if (empty($scheme['scheme']))
                $address = 'http://' . $address;

            $parts = parse_url($address);
            $address = strtolower($parts['scheme']) . '://';

            if (!empty($parts['user'])) {
                $address .= $parts['user'];

                if (!empty($parts['pass']))
                    $address .= ':' . $parts['pass'];

                $address .= '@';
            }

            if (!empty($parts['host'])) {
                $host = str_replace(',', '.', strtolower($parts['host']));

                if (strpos(ltrim($host, 'www.'), '.') === FALSE)
                    $host .= '.com';

                $address .= $host;
            }

            if (!empty($parts['port']))
                $address .= ':' . $parts['port'];

            $address .= '/';

            if (!empty($parts['path'])) {
                $path = trim($parts['path'], ' /\\');

                if (!empty($path) AND strpos($path, '.') === FALSE)
                    $path .= '/';
                   
                $address .= $path;
            }

            if (!empty($parts['query']))
                $address .= '?' . $parts['query'];

            return $address;
        }

        else
            return FALSE;
    }
    
    function urliFy($text) {
        //urlify - turn URL's into HTML links
        $text = "Check the website, http://www.oreilly.com/catalog/repr.";
        $regex =      
                "{ \\b                      # start at word\n"
             .  "                           # boundary\n"
             .  "(                          # capture to $1\n"
             .  "(https?|telnet|gopher|file|wais|ftp) : \n"
             .  "                           # resource and colon\n"
             .  "[\\w/\\#~:.?+=&%@!\\-]+?   # one or more valid\n"
             .  "                           # characters\n"
             .  "                           # but take as little as\n"
             .  "                           # possible\n"
             .  ")\n"
             .  "(?=                        # lookahead\n"
             .  "[.:?\\-]*                  # for possible punct\n"
             .  "(?:[^\\w/\\#~:.?+=&%@!\\-] # invalid character\n"
             .  "|$)                        # or end of string\n"
             .  ") }x";
        echo preg_replace($regex, "<a href=\"$1\">$1</a>", $text);
    }

    function check_mobile($mobile_pre, $mobile_sux) {
        $mobile_prefix_min= 700;    
        $mobile_prefix_max= 740;
        $mobile_pre=substr($mobile_pre, 1, 3);
        if(($mobile_pre !="") || ($mobile_sux !="")) {
            if(($mobile_pre < $mobile_prefix_min) || ($mobile_pre > $mobile_prefix_max)) {
                $this->the_msg=$this->messages(44);
                return false;
            }
            
            //we should check country locale to get the recommended mobile fone length b4 deciding on this
            if((intval($mobile_sux)>999999) || (intval($mobile_sux)<abs(0))) {
                $this->the_msg=$this->messages(44);
                return false;    
            }    
        } else {
            return false;
        }
        return true;    
    }

    function pearclean($array, $index, $maxlength, $connection='') {
        if (isset($array["{$index}"])) {
            $input = trim(substr($array["{$index}"], 0, $maxlength));
            $input = mysql_real_escape_string($input);
            return ($input);
        }
        return NULL;
    }

    // Test if a mandatory field is empty
    function checkMandatory($field, $errorString, $errors, $formVars) {
        if (!isset($_SESSION["{$formVars}"]["{$field}"]) || empty($_SESSION["{$formVars}"]["{$field}"])) {
            $_SESSION["{$errors}"]["{$field}"] = "The {$errorString} field cannot be blank.";
            return false;
        }
        return true;
    }
    
    // Test if a field is less than a min or greater than a max length
    function checkMinAndMaxLength($field, $minlength, $maxlength, $errorString, $errors, $formVars) {
       if (isset($_SESSION["{$formVars}"]["{$field}"]) && (strlen($_SESSION["{$formVars}"]["{$field}"]) < $minlength || strlen($_SESSION["{$formVars}"]["{$field}"]) > $maxlength)) {
          $_SESSION["{$errors}"]["{$field}"] = "The {$errorString} field must be greater than or equal to" .
             "{$minlength} and less than or equal to {$maxlength} characters in length.";
          return false;
       }
       return true;
    }
    
    // Simple zipcode validator -- there's a better one in Chapter 9!
    function checkZipcode($field, $errorString, $errors, $formVars) {
       if (isset($_SESSION["{$formVars}"]["{$field}"]) && !ereg("^([0-9]{4,5})$", $_SESSION["{$formVars}"]["{$field}"])){
          $_SESSION["{$errors}"]["{$field}"] = "The zipcode must be 4 or 5 digits in length";
          return false;
       }
       return true;
    }
    
    // Check a phone number
    function checkPhone($field, $errorString, $errors, $formVars) {
       $validPhoneExpr = "^([0-9]{2,3}[ ]?)?[0-9]{4}[ ]?[0-9]{4}$";
       if (isset($_SESSION["{$formVars}"]["{$field}"]) && !ereg($validPhoneExpr, $_SESSION["{$formVars}"]["{$field}"])) {
          $_SESSION["{$errors}"]["{$field}"] = "The {$field} field must be 8 digits in length, " . "with an optional 2 or 3 digit area code";
          return false;
       }
       return true;
    }
    
    // Check a birth date and that the user is 18+ years
    function checkDateAndAdult($field, $errorString, $errors, $formVars) {
       if (!ereg("^([0-9]{2})/([0-9]{2})/([0-9]{4})$", $_SESSION["{$formVars}"]["{$field}"], $parts)) {
          $_SESSION["{$errors}"]["{$field}"] = "The date of birth is not a valid date " . "in the format DD/MM/YYYY";
          return false;
       }

       if (!checkdate($parts[2],$parts[1],$parts[3])) {
          $_SESSION["{$errors}"]["{$field}"] = "The date of birth is invalid. Please " . "check that the month is between 1 and 12, " . "and the day is valid for that month.";
          return false;
       }

       if (intval($parts[3]) < 1902 || intval($parts[3]) > intval(date("Y"))) {
          $_SESSION["{$errors}"]["{$field}"] = "You must be alive to use this service.";
          return false;
       }
       $dob = mktime(0, 0, 0, $parts[2], $parts[1], $parts[3]);
       // Check whether the user is 18 years old
       // See Chapter 9 for an MS Windows version
       if ((float)$dob > (float)strtotime("-18years")) {
          $_SESSION["{$errors}"]["{$field}"] = "You must be 18+ years of age to use this service";
          return false;
       }
       return true;
    }
    // Check an email address
    function emailCheck($field, $errorString, $errors, $formVars) {
       // Check syntax
       $validEmailExpr =  "^[0-9a-z~!#$%&_-]([.]?[0-9a-z~!#$%&_-])*" . "@[0-9a-z~!#$%&_-]([.]?[0-9a-z~!#$%&_-])*$";
       
       if (!eregi($validEmailExpr, $_SESSION["{$formVars}"]["{$field}"])) {
          $_SESSION["{$errors}"]["{$field}"] = "The email must be in the name@domain format.";
          return false;
       }

       // See Chapter 7 for an MS Windows version
       if (function_exists("getmxrr") && function_exists("gethostbyname")) {
         // Extract the domain of the email address
         $maildomain = substr(strstr($_SESSION["{$formVars}"]["{$field}"], '@'), 1);

         if (!(getmxrr($maildomain, $temp) || gethostbyname($maildomain) != $maildomain)) {
           $_SESSION["{$errors}"]["{$field}"] = "The email domain does not exist.";
           return false;
         }
       }  
       return true;
    }

    public function checkPaymentCode($field, $errors, $formVars) {
        //BV20ZU370 Confirmed. You have received Ksh400.00 from SUSAN WONG 254710670165 on 25/11/11 at 6:11 PM New M-PESA balance is Ksh630.00
        //Match dates formatted like MM/DD/YYYY, MM-DD-YY,...
        $date = "12/30/1969";
        $p    = "!(\\d\\d)[-/](\\d\\d)[-/](\\d\\d(?:\\d\\d)?)!";
    
    }

    // Check a credit card using Luhn's algorithm
    function checkCard($field, $errors, $formVars) {
        if (!preg_match("/^[0-9 ]*$/", $_SESSION["{$formVars}"]["{$field}"])) {
            $_SESSION["{$errors}"]["{$field}"] = "Card number must contain only digits and spaces.";
            return false;
        }              
        // Remove spaces
        $_SESSION["{$formVars}"]["{$field}"] = preg_replace('/[ ]/', '', $_SESSION["{$formVars}"]["{$field}"]);
        // Check first four digits
        $firstFour = intval(substr($_SESSION["{$formVars}"]["{$field}"], 0, 4));
        $type = "";
        $length = 0;
        if ($firstFour >= 4000 && $firstFour <= 8999) {
            // Try: 8000 0000 0000 1001
            $type = "SurchargeCard";
            $length = 16;
        }

        if (empty($type)) {
            $_SESSION["{$errors}"]["{$field}"] = "Please check your card details.";
            return false;
        }

        if (strlen($_SESSION["{$formVars}"]["{$field}"]) != $length) {
            $_SESSION["{$errors}"]["{$field}"] = "Card number must contain {$length} digits.";
            return false;                                                                     
        }

        $check = 0;
        // Add up every 2nd digit, beginning at the right end

        for($x=$length-1;$x>=0;$x-=2)
            $check += intval(substr($_SESSION["{$formVars}"]["{$field}"], $x, 1));

        // Add up every 2nd digit doubled, beginning at the right end - 1.
        // Subtract 9 where doubled value is greater than 10
        for($x=$length-2;$x>=0;$x-=2) {
            $double = intval(substr($_SESSION["{$formVars}"]["{$field}"], $x, 1)) * 2;
            if ($double >= 10)
            $check += $double - 9;
            else
            $check += $double;
        }
      
        // Is $check not a multiple of 10?
        if ($check % 10 != 0) {
            $_SESSION["{$errors}"]["{$field}"] = "Credit card invalid. Please check number.";
            return false;
        }
        return true;
    }

    // Check a credit card expiry date
    function checkExpiry($field, $errors, $formVars) { 
        if (!preg_match("/^([0-9]{2})\/([0-9]{2})$/i", $_SESSION["{$formVars}"]["{$field}"], $parts)) {
            $_SESSION["{$errors}"]["{$field}"] = "The expiry date is not a valid date " . "in the format MM/YY";
            return false;
        }

        // Check the month
        if (!is_numeric($parts[1]) || intval($parts[1]) < 1 || intval($parts[1]) > 12) {
            $_SESSION["{$errors}"]["{$field}"] = "The month is invalid.";
            return false;
        }

        // Check the date             // Year has passed?                       // This year, but the month has passed?                                               // More than 10 years in the future?
        if (!is_numeric($parts[2]) || intval($parts[2]) < intval(date("y")) || (intval($parts[2]) == intval(date("y")) &&  intval($parts[1]) < intval(date("n"))) || intval($parts[2]) > (intval(date("y")) + 10)) {
            $_SESSION["{$errors}"]["{$field}"] ="The date is invalid.";
            return false;
        }                 
        return true;

    }
    
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
    
    /**
    * remove slashes from strings fetched from dbase
    * 
    * @param bool $value
    * @return bool
    */
    function stripslashes_deep($value) {
        $value = is_array($value) ? array_map(stripslashes, $value) : stripslashes($value);

        return $value;
    }
    
    /**
    * add slashes to strings added to dbase
    * 
    * @param bool $value
    * @return bool
    */
    function addslashes_deep($value) {
        $value = is_array($value) ? array_map(addslashes, $value) : addslashes($value);

        return $value;
    }
    
    /** 
    * This function returns a unique array even in multi dimensional array
    *@param array $array the array to be parsed
    *@return array $arrayRewrite the unique value array
    *@author ??
    *@date 
    *obtained from the web, authon name lost
    */
    function arrayUnique($array) {  
        // Unique Array for return  
        $arrayRewrite = array();  
        // Array with the md5 hashes  
        $arrayHashes = array();  
        foreach($array as $item) {  
            // Serialize the current element and create a md5 hash  
            $hash = md5(serialize($item));  
            // If the md5 didn't come up yet, add the element to  
            // to arrayRewrite, otherwise drop it  
            if (!isset($arrayHashes[$hash])) {  
                // Save the current element hash  
                $arrayHashes[$hash] = $hash;  
                // Add element to the unique Array  
                $arrayRewrite[] = $item;  
            }  
        }  
        return $arrayRewrite;  
    }
    
    /**
    * This function removes an element from an array
    *@param array $array the array to be processed
    *@param array $key_index the index of the item to be removed from array
    *@return array $arrayRewrite the unique value array
    *@author ??
    *@date 
    *obtained from the web, authon name lost
    */  
    function arrayRemove($array, $key_index) {
        if ( is_array ( $array ) ) { 
            unset($array[$key_index]);    
            if(gettype($key_index)!="string") { 
                $temparray=array(); 
                $i=0; 
                foreach ($array as $value) { 
                    $temparray[$i]=$value; 
                    $i++; 
                } 
                $array=$temparray; 
            } 
            return $array; 
        } else { 
            return false; 
        } 
    }    
    /**
    * This function prints out a formatted array using print_r function
    *
    *@param array $theArray the array to print
    *@return string $trackstring the tracking number
    *@author Joe Okatch
    *
    */
    function printArray($array) {
        echo "<pre>";
        print_r($array);
        echo "</pre>";
    }  
    /**
    * This function generates a random string
    *@param integer $length length of string
    *@return string $code the random string
    *@author Nasser Olwero
    *@date  July 12 2009
    *
    */   
      
    function getUniqueCode($length = ""){
        $code = md5(uniqid(rand(), true));
      if ($length != "") return substr($code, 0, $length);
      else return $code;
    }
    
    /**
    * The letter l (lowercase L) and the number 1
    * have been removed, as they can be mistaken
    * for each other.
    */
    function createRandomName() {
        $chars = "abcde_fghijkmnopqrstuvwxyz_0123456789ABCDEFG_HIJKLMNPQRSTUVW_XYZ012345678900000_00";
        srand((double)microtime()*1000000);
        $i = 0;
        $pass = '' ;

        while ($i <= 30) {
            $num = rand() % 33;
            $tmp = substr($chars, $num, 1);
            $pass = $pass . $tmp;
            $i++;
        }
        return $pass;
    }

        /**
    * Use this to save the image name and size in the database tables
    * 
    * @param mixed $table
    * @param mixed $imageName
    * @param mixed $size
    */
    function saveFileInDBase() {
        return 0;    
    }
    
    function formatException(Exception $e) {
        return "Error {$e->getCode( )}: {$e->getMessage( )} (line: {$e->getline( )} of {$e->getfile( )})";
    }


    
    /* Works out the time since the entry post, takes a an argument in unix time (seconds) */
    /**
    * get the time since a transaction happened
    * 
    * @param mixed $original
    */
    function time_since($original) {
        if($original > 0) {
            // array of time period chunks
            $chunks = array(
                array(60 * 60 * 24 * 365 , 'year'),
                array(60 * 60 * 24 * 30 , 'month'),
                array(60 * 60 * 24 * 7, 'week'),
                array(60 * 60 * 24 , 'day'),
                array(60 * 60 , 'hour'),
                array(60 , 'minute'),
            );
            
            $today = time(); /* Current unix time  */
            $since = $today - $original;
            
            // $j saves performing the count function each time around the loop
            for ($i = 0, $j = count($chunks); $i < $j; $i++) {
                
                $seconds = $chunks[$i][0];
                $name = $chunks[$i][1];
                
                // finding the biggest chunk (if the chunk fits, break)
                if (($count = floor($since / $seconds)) != 0) {
                    // DEBUG print "<!-- It's $name -->\n";
                    break;
                }
            }
            
            $print = ($count == 1) ? '1 '.$name : "$count {$name}s";
            
            if ($i + 1 < $j) {
                // now getting the second item
                $seconds2 = $chunks[$i + 1][0];
                $name2 = $chunks[$i + 1][1];
                
                // add second item if it's greater than 0
                if (($count2 = floor(($since - ($seconds * $count)) / $seconds2)) != 0) {
                    $print .= ($count2 == 1) ? ', 1 '.$name2 : ", $count2 {$name2}s";
                }
            }
            return $print;
        } else {
            return $print;   
        }
    }
    
    /* Works out the time remaininig, takes a an argument in unix time (seconds) */
    /**
    * get the time remaininig til a transaction happens
    * 
    * @param mixed $original
    */
    function time_until($original) {
        if($original>0) {
            // array of time period chunks
            $chunks = array(
                array(60 * 60 * 24 * 365 , 'year'),
                array(60 * 60 * 24 * 30 , 'month'),
                array(60 * 60 * 24 * 7, 'week'),
                array(60 * 60 * 24 , 'day'),
                array(60 * 60 , 'hour'),
                array(60 , 'minute'),
            );
            
            $today = time(); /* Current unix time  */
            $until = $original- $today;
            if($until<0)
                return -1;
            // $j saves performing the count function each time around the loop
            for ($i = 0, $j = count($chunks); $i < $j; $i++) {
                
                $seconds = $chunks[$i][0];
                $name = $chunks[$i][1];
                
                // finding the biggest chunk (if the chunk fits, break)
                if (($count = floor($until / $seconds)) != 0) {
                    // DEBUG print "<!-- It's $name -->\n";
                    break;
                }
            }
            
            $print = ($count == 1) ? '1 '.$name : "$count {$name}s";
            
            if ($i + 1 < $j) {
                // now getting the second item
                $seconds2 = $chunks[$i + 1][0];
                $name2 = $chunks[$i + 1][1];
                
                // add second item if it's greater than 0
                if (($count2 = floor(($until - ($seconds * $count)) / $seconds2)) != 0) {
                    $print .= ($count2 == 1) ? ', 1 '.$name2 : ", $count2 {$name2}s";
                }
            }
            return $print;
        } else {
            return -1; 
        }
    }
    
    function explodeX($delimiters, $string) {
        $return_array = Array($string); // The array to return
        $d_count = 0;
        while (isset($delimiters[$d_count])) {  // Loop to loop through all delimiters
            $new_return_array = Array();
            foreach($return_array as $el_to_split) { // Explode all returned elements by the next delimiter
                $put_in_new_return_array = explode($delimiters[$d_count],$el_to_split);
                foreach($put_in_new_return_array as $substr) { // Put all the exploded elements in array to return
                    $new_return_array[] = $substr;
                }
            }
            $return_array = $new_return_array; // Replace the previous return array by the next version
            $d_count++;
        }
        return $return_array; // Return the exploded elements
    }
    
    /**
    * Redirects to a specified page taking care of header problems
    * 
    * @author Joe Okatch 13/5/2010
    * @param mixed $url destination page
    */
    function redirect($url='') {
        $url = !empty($url) ? $url : 'index.php';
        if (headers_sent()) {
            echo "<script  language=\"javascript\" type=\"text/javascript\">document.location.href='$url';</script>\n";
        } else {
            @ob_end_clean(); // clear output buffer
            session_write_close();
            header( 'HTTP/1.1 301 Moved Permanently' );
            header( "Location: ". $url );
        }
        exit();
    }
    
    public function view_course($course_id) {
        //fetch from dbase && clean/unstrip data
        $sql = sprintf("SELECT *
                        FROM `courses`
                        WHERE 1
                        AND `course_id` = %d", $course_id);
        $res = mysql_query($sql); 
        //push into array
        $row = $this->get_row($res, 'MYSQL_ASSOC');
        //return array
        $courseName = $row['course_name'];
        return $courseName!=NULL ? $courseName : '';
    }
    
    public function getCountries() {
        //fetch from dbase && clean/unstrip data  SELECT id, bizlocationName FROM `all_bizlocations` WHERE parent={$city} ORDER BY bizlocationName
        $sql = sprintf("SELECT id, countryName 
                        FROM `all_countries`
                        WHERE active=1 
                        ORDER BY countryName
                        ");
        $res = mysql_query($sql); 
        if(mysql_num_rows($res)>0) {
            while($row = $this->get_row($res, 'MYSQL_ASSOC')) {
                //push into array
                $array[] = $row;   
            } 
            //return array 
            return sizeof($array) ? $array : 0;
        }
    }
    
    public function getCities($city, $country='') {
        //fetch from dbase && clean/unstrip data  SELECT id, bizlocationName FROM `all_bizlocations` WHERE parent={$city} ORDER BY bizlocationName
        $country = !empty($country) ? "AND bizlocation_countryId= " . $country : '';
        $sql = sprintf("SELECT id, bizlocationName 
                        FROM `all_bizlocations`
                        WHERE parent=%s 
                        {$country}
                        ORDER BY bizlocationName
                        ", $city);
        $res = mysql_query($sql); 
        if(mysql_num_rows($res)>0) {
            while($row = $this->get_row($res, 'MYSQL_ASSOC')) {
                //push into array
                $array[] = $row;   
            } 
            //return array 
            return sizeof($array) ? $array : 0;
        }
    }
    
    function print_last_error($show_query=true) {
      
      // Prints the last error to the screen in a nicely formatted error message.
      // If $show_query is true, then the last query that was executed will
      // be displayed aswell.
 
      ?>
      <div style="border: 1px solid red; font-size: 9pt; font-family: monospace; color: red; padding: .5em; margin: 8px; background-color: #FFE2E2">
         <span style="font-weight: bold">db.class.php Error:</span><br><?php 
         foreach($this->last_error as $key => $val) {
             echo '<br/>'.$val;
         }
          ?>
      </div>
      <?php
 
   }

    function print_last_query() {

      // Prints the last query that was executed to the screen in a nicely formatted
      // box.
     
      ?>
      <div style="border: 1px solid blue; font-size: 9pt; font-family: monospace; color: blue; padding: .5em; margin: 8px; background-color: #E6E5FF">
         <span style="font-weight: bold">Last SQL Query:</span><br><?php echo str_replace("\n", '<br>', $this->last_query) ?>
      </div>
      <?php  
    } 
}
?>
