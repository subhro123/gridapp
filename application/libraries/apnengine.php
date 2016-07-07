<?php
if ( ! defined('BASEPATH') )
  exit( 'No direct script access allowed' );
	// DEFINE our cipher

class Apnengine
{
	
	public function __construct()
    {
        //session_start();
	}
		
		
	function send_ios_notification($deviceToken, $message) {
        //echo $path = base_url().'push_notification_files/'; die;
        $passphrase = '123456';
        $path = $_SERVER['DOCUMENT_ROOT'] . 'know/pemfolder/final.pem';
		//$path = $_SERVER['DOCUMENT_ROOT'] . 'know/pemfolder/ck.pem';
        $ctx = stream_context_create();
        stream_context_set_option($ctx, 'ssl', 'local_cert', $path);
        stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);
        $fp = stream_socket_client(
                'ssl://gateway.sandbox.push.apple.com:2195', $err, $errstr, 60, STREAM_CLIENT_CONNECT | STREAM_CLIENT_PERSISTENT, $ctx);

        if (!$fp) {
            exit("Failed to connect: $err $errstr" . PHP_EOL);
        } else {

            echo 'Connected to APNS' . PHP_EOL;
        }

        $body['aps'] = array(
            //'badge' => +1,
            'alert' => $message,
            'sound' => 'default',
        );

        $payload = json_encode($body);
        $msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;
        $result = fwrite($fp, $msg, strlen($msg));
        fclose($fp);

        return $result;
    }

}