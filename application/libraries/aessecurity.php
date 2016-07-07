<?php
if ( ! defined('BASEPATH') )
  exit( 'No direct script access allowed' );
	// DEFINE our cipher

class Aessecurity
{
    
	public $iv;
	public $aes256Key ;
	public $password ;
	
	
	public function __construct()
    {
        //session_start();
		
		
    }
	
	
	function generateEncryptedSecuredData($data) {
				
			$this->password = "myPassword_!";
			$messageClear = $data;
			
			// 32 byte binary blob
			$this->aes256Key = hash("SHA256", $this->password, true);
			
			// for good entropy (for MCRYPT_RAND)
			srand((double) microtime() * 1000000);
			// generate random iv
			$this->iv = mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_CBC), MCRYPT_RAND);
			
			
			//$crypted =  $this->fnEncrypt($messageClear, $this->aes256Key);
			//echo '<br/>';
			
			$crypted = rtrim(base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256,  $this->aes256Key, $messageClear, MCRYPT_MODE_CBC, $this->iv )), "\0\3");
			
			return $crypted;			
				
	}
	
	function generateDecryptedSecuredData($encrypted) {
	
			 //$newClear = $this->fnDecrypt($encrypted, $this->aes256Key);
			 $newClear=rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $this->aes256Key, base64_decode($encrypted), MCRYPT_MODE_CBC, $this->iv ), "\0\3");	
	         return $newClear;
	}
	
	
	/*function fnEncrypt($sValue, $sSecretKey) {
    			//global $iv;
    			return rtrim(base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $sSecretKey, $sValue, MCRYPT_MODE_CBC, $this->iv )), "\0\3");
    }

  function fnDecrypt($sValue, $sSecretKey) {
   				//global $iv;
    			return rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $sSecretKey, base64_decode($sValue), MCRYPT_MODE_CBC, $this->iv ), "\0\3");
    }*/



}
?>
