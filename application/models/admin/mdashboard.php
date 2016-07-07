<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mdashboard extends CI_Model
{
			 function getDashboardUser()
			 {
			 			$data = array();
						
						$this->db->select("count(*) as count_user");
						$this->db->from("user");
						$this->db->where('type','normal');
						$query = $this->db->get();
						$data['normal'] = $query->row_array();
						
						$this->db->select("count(*) as count_user");
						$this->db->from("user");
						$this->db->where('type','affiliate');
						$query = $this->db->get();
						$data['affiliate'] = $query->row_array();
						
						return $data;
						
			 }
			 
			 function getDashboardGraph()
			{
						$data = array();
						
						$arr_month = array("Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul","Aug","Sep","Oct","Nov","Dec");
						
						foreach($arr_month as $key=>$val){
									
												$arr_month[$key] = $val;
											
						}
						$data['labels'] = $arr_month;
						//$dataset = array();
						$this->db->select("*");
						$this->db->from("graph");
						$query = $this->db->get();
						$dataset = $query->result_array();
						
						foreach($dataset as $key=>$val){
								
								
								        $arr_month = array("January", "February", "March", "April", "May", "June", "July","August","September","October","November","December");
										
										foreach($arr_month as $keymonth=>$valmonth){
										$this->db->select("count(*) as count_user");
										$this->db->from("user");
										$this->db->where('monthname(created_date) ',$valmonth);
										$this->db->where('YEAR(created_date) ',date('Y'));	
										$this->db->where('type',$val['type']);
										//$this->db->group_by('type');
										$query = $this->db->get();
										//echo $this->db->last_query();
										$result = $query->row_array();
										$month[$keymonth] = $result['count_user'];
										}
										$dataset[$key]['data'] = $month;
						}
						$data['datasets'] = $dataset;

						if(!empty($data)){
						
								return $data;
						}
						//$arr_dataset1 = array( "label:"=>"My First dataset");
						//$arr_dataset2 = array( "label:"=>"My Second dataset");
						//$data['datasets']  = array_merge($arr_dataset1,$arr_dataset2);
						
						//echo '<pre>';
						//echo json_encode($data);
			}
}

?>
