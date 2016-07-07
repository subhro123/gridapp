<?php
if ( ! defined('BASEPATH') )
  exit( 'No direct script access allowed' );

class Qrcode
{
    public function __construct()
    {
        //session_start();
    }
	
	function generateQrCode($input, $size = "150", $level = "L", $margin = "0")
	{
		$chl = urlencode($input);
		
		//echo "<img src='http://chart.apis.google.com/chart?chs='.$widhtHeight.'x'.$size.'cht=qr&chld='.$level.'|'.$margin. '&chl='.$chl.' ' alt='QR code' widthHeight="'.$size.'" />";
	}
	
}
