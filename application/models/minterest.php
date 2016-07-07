<?php if (!defined('BASEPATH'))exit('No direct script access allowed');

/**
 * This class is used to manage User's login, logout and registration.
 */
class Minterest extends CI_Model {
		
		
	protected $CI;
    private $config;
    private $Encryption;

    function __construct() {
        parent::__construct();
        $this->load->model("key", "Key");
        $this->load->library('encrypt');
		$this->load->library('generatepass');

    }
	public function getallinterest(){
					
					$data = array();
					$this->db->select("id,interest_name");
					$this->db->from("interest");
					$this->db->where('interest_id',0);	
					$this->db->where('status','1');	
					$query = $this->db->get();
   					//echo $this->db->last_query();
 					return $query->result_array(); 
		}
}

?>