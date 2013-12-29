<?php


header('Content-Type: text/html; charset=utf-8');


require_once ('./curl.php');

$text = $_GET['text'];
$text = strip_tags($text);





$url = 'https://montanaflynn-spellcheck.p.mashape.com/check/?text='.urlencode($text);
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
#curl_setopt($ch, CURLOPT_POST, 1);
#curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
curl_setopt($ch,CURLOPT_HTTPHEADER,array('X-Mashape-Authorization: APX21oYwqQUDHz2KsICRjX2Tei0RgQ5c'));
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$contents = curl_exec($ch);
curl_close($ch);


print $contents;
#echo json_encode($errors);

?>