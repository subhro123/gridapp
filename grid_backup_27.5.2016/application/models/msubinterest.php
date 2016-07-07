<?php if (!defined('BASEPATH'))exit('No direct script access allowed');

/**
 * This class is used to manage User's login, logout and registration.
 */
class Msubinterest extends CI_Model {

    protected $CI;
    private $config;
    private $Encryption;

    function __construct() {
        parent::__construct();
        $this->load->model("key", "Key");
        $this->load->library('encrypt');
		$this->load->library('generatepass');

    }
	
	function getSubInterest($interestid){
			
		$data = array();
		$this->db->select("id as sub_id,interest_id,subinterest_name");
		$this->db->from("interest");	
		$this->db->where('interest_id',$interestid);
		$this->db->where('status','1');		
		$query = $this->db->get();
		return $query->result_array(); 
	}
	
}