<?php
error_reporting(E_ERROR | E_PARSE);
// Firebase Cloud Messaging Authorization Key
define('FCM_AUTH_KEY', 'AAAABRIs47U:APA91bGJsdhlYrHCZvBH_XDBaul4ecY-UvpF4Tibwf0o_RPik-l85Z8SVL2fL9THUuEmsDLQtNg4JMmBY9Q3vN5rtrjvbhgVRkzW-p5JF9QkVrF7jIRLzMq7H8gFK6RMG0SiiyOAzvfp');

function sendPush($to, $title, $body, $icon, $url) {
	$postdata = json_encode(
	    [
	        'notification' => 
	        	[
	        		'title' => $title,
	        		'body' => $body,
	        		'icon' => $icon,
	        		'click_action' => $url
	        	]
	        ,
	        'to' => $to
	    ]
	);

	$opts = array('http' =>
	    array(
	        'method'  => 'POST',
	        'header'  => 'Content-type: application/json'."\r\n"
	        			.'Authorization: key='.FCM_AUTH_KEY."\r\n",
	        'content' => $postdata
	    )
	);

	$context  = stream_context_create($opts);

	$result = file_get_contents('https://fcm.googleapis.com/fcm/send', false, $context);
	if($result) {
		return json_decode($result);
	} else return false;
}

// $keyclient = "cMhqsmH_SViHHgjtRna2Cl:APA91bFKaYvbZCH6qBugwhaNnnK6fpnRz6AOzZ0YN_OpWqQH12X9P7xw90T24eG3LK2OakDrgi9Fs-7H0a4KgympaxPLwKCvn1f2YzDZ8eGgR3Zq8A91WHuoOVkZBq0ppTC4zR94WkPk";
// $title = "Ada Pengajuan Izin Baru";
// $body = "Segera cek!";
// $icon = "";
// $url = "";
// sendPush($keyclient, $title, $body, $icon, $url);
