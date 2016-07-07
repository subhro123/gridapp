<?php
if ( ! defined('BASEPATH') )
  exit( 'No direct script access allowed' );

class  Jsonoutput
{
    public function __construct()
    {
       // session_start();
    }
	
	 /********************** DYNAMIC OUTPUT START  *********************/
	 
	 	public function data_output( $columns, $data )
	  	{
			$out = array();
			for ( $i=0, $ien=count($data) ; $i<$ien ; $i++ ) {
			$row = array();
			for ( $j=0, $jen=count($columns) ; $j<$jen ; $j++ ) {
			$column = $columns[$j];
			// Is there a formatter?
			if ( isset( $column['formatter'] ) ) {
			$row[ $column['dt'] ] = $column['formatter']( $data[$i][ $column['db'] ], $data[$i],$i );
			}
			else {
			$row[ $column['dt'] ] = $data[$i][ $columns[$j]['db'] ];
			}
			}
			$out[] = $row;
			}
			return $out;
	   }
	 
	  /********************** DYNAMIC OUTPUT END  *********************/

}
  