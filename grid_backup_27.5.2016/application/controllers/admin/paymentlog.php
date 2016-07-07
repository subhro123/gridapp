<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//session_start();
class Paymentlog extends CI_Controller {
			
			
		 function __construct()
		 {
		   	parent::__construct();
			$this->load->library('form_validation',"session");
			$this->load->library('jsonoutput');
			//$this->load->library('resizeimage');
			$this->load->helper('form');
 			$this->load->helper('url');
			$this->load->model('admin/muser','',TRUE);
			$this->load->model('admin/mpaymentlog','',TRUE);
			$this->load->model('common/functions','',TRUE);
		 }
		 
		 function index()
 	   {
	   //echo 'aaaa';
	   //die();
	   		if($this->session->userdata('admin_logged_in'))
				  {
				   $data['session_data'] = $this->session->userdata('admin_logged_in');
				   $data['admin_details'] = $this->muser->userdetails($data['session_data']['id']);
				   $data['interest'] = array();
				   $this->load->view('admin/paymentlog',$data);
				  }
					else
			      {
						 redirect('admin', 'refresh');
				  }	
	   }
	   
	   function pagingpaymentlog()
	   {
			$search = $this->input->post("sSearch");
			$limit = $this->input->post("iDisplayLength");
			$start = $this->input->post("iDisplayStart");
			$getorder= $this->input->post("sSortDir_0");
			$order = $this->input->post("sSortDir_0");
		
			if($search == "")
			{
				$count = $this->mpaymentlog->getCountPaymentlog($search,$order);
				$data["sEcho"] = $this->input->post("sEcho"); 
				$data["sSearch"] = $this->input->post("sSearch");
				$paymentlog = $this->mpaymentlog->getAllPaymentlog($limit,$start,$order);
				$data["iTotalRecords"] = $count['count']; 
				$data["iTotalDisplayRecords"] = $count['count'];
			}
			else
			{
				
				//$count = $this->muser->getCountFunctions();
				$count = $this->mpaymentlog->getCountPaymentlog($search,$order);
				$data["sEcho"] = $this->input->post("sEcho"); 
				$data["sSearch"] = $this->input->post("sSearch");
				$paymentlog = $this->mpaymentlog->getAllPaymentlog($limit,$start,$order,$search);
				$data["iTotalRecords"] = $count['count']; 
				$data["iTotalDisplayRecords"] = $count['count'];
			
			}
		
			$search = $this->input->post("sSearch");
			$_SESSION['adminsubscriptionstart'] = $start;
			//echo '<pre>';
			//print_r($assessments);
			$columns = array(
			array( 'db' => 'id', 
				   'dt' => 0 ,
				   'formatter' => function( $d, $row,$i = 0 ) {
				   $rtrn = ($_SESSION['adminsubscriptionstart']) + (++$i);
					//unset($_SESSION['start']);
					return $rtrn.".";
			}),
		  	array( 'db' => 'email', 
				   'dt' => 1 ,
				   'formatter' => function( $d, $row,$i = 0 ) {
				   $str = $row['email'];
				   return $str;
					
			}),
			array(
				'db'        => 'amount',
				'dt'        => 2,
				'formatter' => function( $d, $row, $i ) {
				 $str =  "<div style='text-align:center'>".$row['amount']."</div>";
				return $str;
				}
			),
			array(
				'db'        => 'transaction_id',
				'dt'        => 3,
				'formatter' => function( $d, $row, $i ) {
				 $str =  "<div style='text-align:center'>".$row['transaction_id']."</div>";
				return $str;
				}
			),
			array(
				'db'        => 'type',
				'dt'        => 4,
				'formatter' => function( $d, $row, $i ) {
				 $str =  "<div style='text-align:center'>".$row['payment_type']."</div>";
				return $str;
				}
			),
			array(
			'db'        => 'payment_created_date',
			'dt'        => 5,
			'formatter' => function( $d, $row, $i ) {
				$str =  "<div style='text-align:center'>".date('d/m/Y',strtotime($row['payment_created_date']))."</div>";
				return $str;
			}
			)
		);
			$data['aaData'] = $this->jsonoutput->data_output($columns,$paymentlog);
			header('Content-Type: application/json; charset=utf-8');
			echo json_encode($data);
			exit;
	   }
	   
	 
	   
	   
	   

}

?>
