<?php
//Progress248
ini_set('display_errors', 1);


function sendRequest(){
  
  $authorization = "Authorization:Bearer 3ba813bccaeeb9c77c81b40343f7f7c";
  $url = 'https://api.stubhub.com/search/catalog/events/v2?';
  $param = urlencode("point=22.5,88.3&radius=120&minAvailableTickets=1&date=2014-06-01T00:00 TO 2014-06-01T23:59&sort=distance asc");

  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url.$param);
  curl_setopt($ch, CURLOPT_HTTPHEADER, array( $authorization ));
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
  $result = curl_exec($ch);
  $httpcode = curl_getinfo($ch);
 // print_r($httpcode);
  curl_close($ch);
  return $result;
}

echo '<pre>';
var_dump(sendRequest());


