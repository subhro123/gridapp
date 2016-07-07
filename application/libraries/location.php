<?php
if ( ! defined('BASEPATH') )
  exit( 'No direct script access allowed' );

class  Location
{
    public function __construct()
    {
       // session_start();
    }
	
	 /********************** GET COUNTRY,CITY,ADDRESS START  *********************/
	 
	 	public function get_location($latitude='', $longitude='')
		{
			//$geolocation = $latitude.','.$longitude;
			$geocode=file_get_contents('http://maps.googleapis.com/maps/api/geocode/json?latlng='.$latitude.','.$longitude.'&sensor=false');

			$output= json_decode($geocode);
			
			for($j=0;$j<count($output->results[0]->address_components);$j++){
			
				$cn=array($output->results[0]->address_components[$j]->types[0]);
			
				if(in_array("country", $cn)){
					$country= $output->results[0]->address_components[$j]->long_name;
				}
			}
			
			return $country;
	}
	 
	  /********************** GET COUNTRY,CITY,ADDRESS START END  *********************/

}
  
