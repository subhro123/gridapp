<?php 

class Emailoneseven extends CI_Model {

    function __construct() {
    	parent::__construct();
    	get_config();
    } 
    
    public function generate_code() {
        list( $usec, $sec ) = explode( ' ', microtime() );
        $seed = (float)$sec + ( (float)$usec * 100000 );
        srand( $seed );
        $code = rand( chr( 48 ), chr( 57 ) ) . rand( chr( 48 ), chr( 57 ) ) . rand( chr( 48 ), chr( 57 ) ) . rand( chr( 48 ), chr( 57 ) );
        return $code;
    }
    
    public function email_template( $user_id, $title, $body, $footer = false ) {
        $fullname = $this->get_single( $user_id, "user", "fullname" );
	
		$name = $fullname;
        $email_body    = '  <html><body>
                            <table rules="all" style="border-color: #666;" cellpadding="10">
                            <tr style="background: #eee;"><td colspan="2"><strong>Account Details</strong></td></tr>
                            <tr><td colspan="2"><strong>'.$title.'</strong> </td></tr>
                            <tr><td colspan="2">Hi '.$name.',</td></tr>
                            <tr><td colspan="2"> '.$body.'</td></tr>
                            </table>
                            </body></html>';
        return $email_body;
    }
	
	public function email_invite_template( $import_user_id, $title, $body, $footer = false ) {
        $fullname = $this->get_single( $import_user_id, "userimport", "fullname" );
	
		$name = $fullname;
        $email_body    = '  <html><body>
                            <table rules="all" style="border-color: #666;" cellpadding="10">
                            <tr style="background: #eee;"><td colspan="2"><strong>Account Details</strong></td></tr>
                            <tr><td colspan="2"><strong>'.$title.'</strong> </td></tr>
                            <tr><td colspan="2">Hi '.$name.',</td></tr>
                            <tr><td colspan="2"> '.$body.'</td></tr>
                            </table>
                            </body></html>';
        return $email_body;
    }
    
    public function send_email( $email, $subject, $body, $email_from = false ) {
	
	//echo $email;
    //    $from = $email_from ? $email_from : "appsbeeteam@gmail.com";
    //    $headers = "From: " . $from . "\r\n";
    //    //$headers .= "Reply-To: ". strip_tags($_POST['req-email']) . "\r\n";
    //    $headers .= "MIME-Version: 1.0\r\n";
    //    $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
    //    return mail($email, $subject, $body, $headers);
    //
    //===================
    //echo $email;
	
//   $headers = "From: appsbeeteam@gmail.com \r\n";	
//	$headers .= "MIME-Version: 1.0\r\n";
//	$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
	
	//$sent = mail('appsbeeteam@gmail.com',$sub, $str, $headers);
      
	  			/////////// old code start  ///////////////////
				
	 		 	/*$config['protocol']    = 'smtp';
                $config['smtp_host']    = 'localhost';
                $config['smtp_port']    = '25';
                $config['smtp_timeout'] = '7';*/
				
				
				/////////// old code end ///////////////////
				
				$config['protocol']    = 'smtp';
                $config['smtp_host']    = 'smtp.sendgrid.net';
                $config['smtp_port']    = '25';
                $config['smtp_timeout'] = '7';
                $config['smtp_user']    = 'GridService';
                $config['smtp_pass']    = 'Process360';
                $config['charset']    = 'utf-8';
                $config['newline']    = "\r\n";
                $config['mailtype'] = 'html'; // or html
                
        
                $this->load->library('email',$config);
                $email_from='GridApp.Service@gmail.com';
                //$from = "aninditadas1220@gmail.com";            
				//$each='appsbeetest@gmail.com';
                $this->email->from($email_from);
                $this->email->to($email);
                $this->email->subject($subject);
                $this->email->message($body);
				$this->email->send(); 
        
        
        /*$this->load->library('email');

        $config['charset'] = 'iso-8859-1';

        $config['wordwrap'] = TRUE;

        $config['mailtype'] = 'html';

        

        $this->email->initialize($config);
        $this->email->from('appsbeeteam@gmail.com', 'Appsbee Team');
        $this->email->to($email); 
        $this->email->subject($subject);

        $this->email->message(stripslashes($body));
        
        $this->email->send();*/
    }
    
    public function get_single( $user_id, $table, $field ) {
    	$query = $this->db->get_where( $table, array( "id" => $user_id ) );
    	$row = $query->row();
    	if ( !$row ) return false;
    	return $row->$field;
    }
}
	