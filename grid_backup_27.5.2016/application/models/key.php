<?php

/**
 * This class is used to manage API keys.
 */
class Key extends CI_Model
{
	function __construct() {
        parent::__construct();
        $this->load->helper("security");
    }
	
	/***
	 * Create API Key
	 ***/
	public function create_key ($user_id = '', $level = 1, $ignore_limits = 1) {		
		// Build a new key
		$key = self::_generate_key();			
		return $key;
	}
    
    private function _generate_key() {
		//$this->load->helper('security');
		
		do {
			$salt = do_hash(time().mt_rand());
			$new_key = substr($salt, 0, config_item('rest_key_length'));
			log_message('info', 'Key: ' .$new_key);		
		} 
		// Already in the DB? Fail. Try again
		while (self::_key_exists($new_key));

		return $new_key;
	}
    
    private function _key_exists($key) {
		$CI =& get_instance();
		$query = $CI->db->query("SELECT count(*) AS total FROM users WHERE api_key = ?", array($key));
		$row = $query->row();
		
		$total = 0;	
		if ($query->num_rows() > 0) {
			$total = $row->total;
		}
			
		return ($total > 0);
	}
	
}
