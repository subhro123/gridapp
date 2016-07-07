<?php

class MY_Form_validation extends CI_Form_validation{
    function __construct( $config = array() ){
        parent::__construct($config);
    }
    
    function error_array(){
        $errors = array();
        if (count($this->_error_array) === 0)
            return FALSE;
        else{
            foreach( $this->_error_array as $each ){
                $errors[] = $each;
            }
        }
        return $errors;
    }
    
    function valid_url($str){
        if( !strstr($str, 'http') ){
            $str    = 'http://'.$str;
        }
        $pattern = "|^http(s)?://[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i";
        if (!preg_match($pattern, $str)){
            //$this->set_message('valid_url', 'The URL you entered is not correctly formatted.');
            return FALSE;
        }
        return TRUE;
    }
}