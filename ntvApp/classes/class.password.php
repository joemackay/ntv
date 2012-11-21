<?PHP
/*
  * Project: class_password
  * File name: class_password.php
  * Description: class to make secure password creation easy.
  * URL: http://www.kaisersoft.net/t.php?cpasswd
  *
  * Author: Mirko Kaiser, http://www.KaiserSoft.net
  * Copyright (C) 2011 Mirko Kaiser
  * First created in Germany on 20 May 2011
  * License: New BSD License
  *
	Copyright (c) 2011, Mirko Kaiser, http://www.KaiserSoft.net
	All rights reserved.

	Redistribution and use in source and binary forms, with or without
	modification, are permitted provided that the following conditions are met:
		* Redistributions of source code must retain the above copyright
		  notice, this list of conditions and the following disclaimer.
		* Redistributions in binary form must reproduce the above copyright
		  notice, this list of conditions and the following disclaimer in the
		  documentation and/or other materials provided with the distribution.
		* Neither the name of the <organization> nor the
		  names of its contributors may be used to endorse or promote products
		  derived from this software without specific prior written permission.

	THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND
	ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
	WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
	DISCLAIMED. IN NO EVENT SHALL <COPYRIGHT HOLDER> BE LIABLE FOR ANY
	DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
	(INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
	LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND
	ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
	(INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
	SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
*/
/*
 * use it like this:

      require_once 'class_password.php';
      $pwd = new Password();

      //* gen new hash *
      $password = $_REQUEST['password']; //password provided by user
      $hash = $pwd->hash($password); //hash the password
      //$hash now contains the hashed pw, store this value in your db since we need it
      //when the user wants to login again


      //* user login, check password *
      $db_hash = "SELECT hash FROM table"; //retrieve previously generated hash from db
      $password = $_REQUEST['password']; //password provided by user
      $hash = $pwd->re_hash( $db_hash, $password ); //hash the entered password with the salt of the db_hash
      //if the entered pw is correct then $hash and the hash from your db must match exactly ===
      if( $hash === $db_hash )
        echo 'Valid password';
      else
        echo 'Invalid password';
*/
class Password{
    private $hash_rounds;
    private $hash_type;
    private $hash_supported;


    function __construct(){
        /* hashing stuff */
        $this->hash_type = 'sha512'; // "sha256", "sha512" or "md5" class_default: sha512
        $this->hash_supported = 'sha256|sha512|md5'; //hashed supported DO NOT CHANGE
        $this->hash_rounds['sha256'] = (int)'10000'; // min:1000 max:999999999  class_default: 10000 was 5000 before release
        $this->hash_rounds['sha512'] = (int)'10000'; // min:1000 max:999999999  class_default: 10000 was 5000 before release
        $this->hash_rounds['md5'] = (int)'6000'; //min:1000  max:999999999 class_default: 6000 was 3000 before release
    }

    /**
     * function to change the hash type
     * @param string $type Possible values sha512, sha256 or md5
     * @return bool true/false true on change, false on error
     */
    function set_hash_type( $type ){
        if( $this->is_name_valid($type) !== true ) 
            return false;
        $this->hash_type = $type;
        return true;
    }

    /**
     * function to specify how many rounds the function specified by $type will use
     * @param string $type hash type to change sha512, sha256 or md5
     * @param int $rounds rounds to run
     * @return bool true/false true on success, false on error 
     */
    function set_hash_rounds( $type, $rounds ){
        if( $this->is_name_valid($type) !== true ) 
            return false;
        settype($rounds, 'integer');
        if( is_int($rounds) === true && $rounds > 0 ){
            $this->hash_rounds[$type] = $rounds;
            return true;
        }
        return false; 
    }

    /**
     * function responsible for hashing strings securely
     * @param string &$string Password to be hashed
     * @param string $pass_the_salt=null Optional salt, uses rand_string() if null
     * @param bool $use_crypt=true Option not to use crypt() even if it is installed
     * @return string/bol hashed string OR boolean false on failure.
     */
    function hash( &$string, $pass_the_salt=null, $use_crypt=true) {
        $hash = null;                        //restore this line:  && (CRYPT_SHA512 === 1 || CRYPT_SHA256 == 1)
        if( function_exists('crypt') === true  && $use_crypt === true ) {
            switch($this->hash_type) {
                case 'sha256':
                    if( $pass_the_salt === null )
                        $salt = $this->rand_string(16, array('A','Z','a','z',0,9), '.,/');
                    else
                        $salt = $pass_the_salt;

                    //get expected hash length                
                    $length = $this->get_hash_length('sha256');
                    if( $length === false ) 
                        break; //invalid length

		            //hash the value
		            $hash = crypt($string, '$5$'."rounds={$this->hash_rounds['sha256']}".'$'.$salt.'$');
		            if( strlen($hash) === $length ) 
                        return $hash;
		        break;
                case 'sha512':
                    if( $pass_the_salt === null )
                        $salt = $this->rand_string(16, array('A','Z','a','z',0,9), '.,/');
                    else
                        $salt = $pass_the_salt;

                    //get expected hash length
                    $length = $this->get_hash_length('sha512');
                    if( $length === false ) 
                        break; //invalid length

                    //hash the value
                    $hash = crypt($string, '$6$'."rounds={$this->hash_rounds['sha512']}".'$'.$salt.'$');
                    if( strlen($hash) === $length ) 
                        return $hash;
                break;
	    }

        } else
	        $salt = null; //Set to null to avoid issues where a salt was passed to be used in blowfish 
      
      /* MD5 fallback. Use this if something went wrong or if the system does not come with the good stuff */
        if( $pass_the_salt === null ) {
            $rand = $this->rand_string(20, array('A','Z','a','z',0,9), '`,~,!,@,#,%,^,&,*,(,),_,|,+,=,-');
            $salt = md5(microtime(true).$rand);
        }else
	        $salt = $pass_the_salt;
			      
        //get expected hash length
        $length = $this->get_hash_length('md5');
        $hash = $string;
        if( $length === false ) 
            break; //invalid length

        for( $x=0 ; $x < $this->hash_rounds['md5'] ; ++$x) {
            $hash = md5($salt.md5($salt.$hash).md5($hash.$salt));
        }
        $hash = '$CL$'.$this->hash_rounds['md5'].'$'.$salt.'$'.$hash; //my format CL stands for class_login
        if( strlen($hash) === $length ) 
            return $hash;
        return false; //error
    }

    /**
     * function to compare a string to a previously hashed string
     * @param string &$hash previously hashed string
     * @param string &$password string to validate
     * @return bool true if the password generates the same hash OR false if they don't match 
     */
    function validate( &$hash, &$password ) {
        $new_hash = $this->re_hash( $hash, $password);
        if( $hash === $new_hash && $new_hash !== false )
            return true;
        else
            return false;
    }

    /**
     * function to rehash a previously hashed string. 
     * @param string &$hash previously hashed string
     * @param string &$password string to validate
     * @return string/bool hashed password or bool false on failure
     */
    function re_hash( &$hash, &$password ) {    
        $check_hash = explode('$', $hash);
        if( isset($check_hash[1]) !== true ) return false; //invalid hash passed
        if( isset($check_hash[2]) !== true ) return false; //invalid hash passed
        if( isset($check_hash[3]) !== true ) return false; //invalid hash passed
      //
      //determine the type of hash and prepare the hash
        
      /* SHA256 */
        if( $check_hash[1] == '5' ) { //sha256
            $length = $this->get_hash_length('sha256');
            if( CRYPT_SHA256 === 1 && strlen($hash) === $length && function_exists('crypt') === true ) {
                //example hash: $5$5000$4AiQrGXEUiRbhYJDsx4LmvU4Jpf9s5tkiPct2zQfi94
                //[0] = '', [1] = 5, [2] = rounds, [3] = salt/hash
                $salt = substr($check_hash[3], 0, 16); //get only the salt part
                $ret = crypt($password, '$5$'.$check_hash[2].'$'.$salt); //and try to recreate the password
                if( strlen($ret) === $length && $ret !== false ) 
                    return $ret;
            }else{
                if(CRYPT_SHA256 === 1 && strlen($hash) !== $length && function_exists('crypt') === true)
                return false; //the hash does not have the expected length. this happends if you change $rounds and pass a hash with a differen $round setting
                else
                die('This server does not support sha256 and can not verify the hash: '.$hash);
            }
        }
        
      /* SHA512 */
        if( $check_hash[1] == '6' ) {
            $length = $this->get_hash_length('sha512');
            if( CRYPT_SHA512 === 1 && strlen($hash) === $length && function_exists('crypt') === true ) {
                //example hash: $6$5000$nYt6jEzMC44skkjon6BEyZQOa3dcYa/pqgyHRtEd3BVkJRlaXJwtqRCsLiWdZ3OT1Xo54r2EXDuv5yxfOQPKo0
                //[0] = '', [1] = 5, [2] = rounds, [3] = salt/hash
                $salt = substr($check_hash[3], 0, 16); //get only the salt part
                $ret = crypt($password, '$6$'.$check_hash[2].'$'.$salt); //and try to recreate the password
                if( strlen($ret) === $length && $ret !== false ) 
                    return $ret;
            } else {
                if( CRYPT_SHA512 === 1 && strlen($hash) !== $length && function_exists('crypt') === true )
                    return false; //the hash does not have the expected length. this happends if you change $rounds and pass a hash with a differen $round setting
                else
                    die('This server does not support sha512 and can not verify the hash: '.$hash);
            }
        }

        $length = $this->get_hash_length('md5');
        if( $check_hash[1] == 'CL' && strlen($hash) === $length ) {
            //md5 fallback
            //[0] = '', [1] = CL, [2] = rounds, [3] = salt, [4] = hash
            $ret = $this->hash($password, $check_hash[3] , false);
            if( strlen($ret) === $length && $ret !== false )
                return $ret;
        }

      return false; //error / invalid
    }

    /**
     * function determins the expected hash length. Only affects 2ha256/512 but I handle all
     * lengths here to have a consistent place to handle them
     * @param string $type supported hash type
     * @return bool/int integer length value on success or boolean false on error
     */
    private function get_hash_length( $type ) {
      $doloop = false; //only run loop for sha256/512
      
        //make sure the hash type is valid
        if( $this->is_name_valid($type) !== true ) return false;
      
        if( $type === 'sha512' || $type === 'sha256' ) {
            //check if rounds is valid
            if( $this->hash_rounds[$type] < 1000 ) $this->hash_rounds[$type] = 1000;
            if( $this->hash_rounds[$type] > 999999999 ) $this->hash_rounds[$type] = 999999999; //this will take a while
            $doloop = true;
            $plus = ($type === 'sha512' ) ? 117 : 74 ; //base length of hash 117 for sha512 and 74 for sha256
        } elseif( $type === 'md5' ) {
            //check if rounds is valid
            if( $this->hash_rounds[$type] < 1000 ) $this->hash_rounds[$type] = 1000;
            if( $this->hash_rounds[$type] > 999999999 ) $this->hash_rounds[$type] = 999999999; //this will take a while
            $doloop = true;
            $plus = 73; //base length of hash 117 for sha512 and 74 for sha256
        } else
            return false;
      
        //trying to determine how the rounds argument affects the length of the hash
        //only affect sha256 and sha512
        $length = 0;
        $divby = 1000;
        while( $doloop === true ) {
            ++$length;
            if( floor($this->hash_rounds[$type]/$divby) < 10 )
                return (int)$length+$plus;
            $divby *= 10;
        }
      return false;
    }

    /**
     * function checks if the passed hash name matches hash_supported
     * @param string &$name string to check
     * @return bool true/false true if the name passed matches
     */
    private function is_name_valid( &$name ) {
        $name = strtolower($name);
        $tmp = explode('|', $this->hash_supported);
        foreach( $tmp as $hash ) {
            if( $name === $hash )
            return true; 
        }
        return false;
    }

    /**
     * create a random string, uses mt_rand. This one is faster then my old GetRandomString()
     * rand_string(20, array('A','Z','a','z',0,9), '`,~,!,@,#,%,^,&,*,(,),_,|,+,=,-');
     * rand_string(16, array('A','Z','a','z',0,9), '.,/')
     * @param integer $lenth length of random string
     * @param array $range specify range as array array('A','Z','a','z',0,9) == [A-Za-z0-9]
     * @param string $other comma separated list of characters !,@,#,$,%,^,&,*,(,)
     * @return string random string of requested length
     */
    function rand_string($lenth, $range=array('A','Z','a','z',0,9), $other='' ) {
        $cnt = count($range);
        $sel_range = array();
        for( $x=0 ; $x < $cnt ; $x=$x+2 )
            $sel_range = array_merge($sel_range, range($range[$x], $range[$x+1]));
        if( $other !== '' )
            $sel_range = array_merge($sel_range, explode (',', $other));
        $out =''; 
        $cnt = count($sel_range);
        for( $x = 0 ; $x < $lenth ; ++$x )
            $out .= $sel_range[mt_rand(0,$cnt-1)];
        return $out; 
    /*
        // test the "randomness", replace mt_rand() with rand() to see why you should use mt_rand()
        header("Content-type: image/png");
        $img = imagecreatetruecolor(500,500);
        $ink = imagecolorallocate($img,255,255,255);
        for($i=0;$i<500;++$i) {
	      for($j=0;$j<500;++$j) {
		    imagesetpixel($img, mt_rand(1,500), mt_rand(1,500), $ink);
	      }
        }
        imagepng($img);
        imagedestroy($img);
    */
    }
}
?>