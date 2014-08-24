<?php
error_reporting(E_ALL);
 
$server = 'http://pogoda.ngs.ru/json/';
$method = 'getForecast';
$params = array('name' => 'current',
                'city' => 'nsk');
$request = array(
    'method'    => $method,
    'params'     => $params,
);
$request = json_encode($request);
$opts = array(
    'http'=>array(
        'method'=>"POST",
        'content'=>$request,
    )
); 

$context = stream_context_create($opts);
$result = file_get_contents($server, 0, $context);
$result = json_decode($result, true);

print_r($result);
?>
