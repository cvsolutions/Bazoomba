<?php
$url = 'http://ilovecode.it';

$data = array('a' => 'hello', 'b' => 'worlds');

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url.'?'.http_build_query($data));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
$response = curl_exec($ch);

echo $response;

?>
