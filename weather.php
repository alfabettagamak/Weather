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
#echo '<p>Encode</p>'; 
#print_r($request);
$opts = array(
    'http'=>array(
        'method'=>"POST",
        'content'=>$request,
		'header' => 'application/json'
    )
); 
#echo '<p>Context</p>'; 
$context = stream_context_create($opts);
#print_r($context);
$result = file_get_contents($server, 0, $context);
#echo '<p>No json decode</p>'; 
#print_r($result);

$result = json_decode($result, true);
$result = $result[result];
echo '<p>Decode</p>'; 
print_r($result);
echo "����������� : $result[temp_current_c]"; 
echo "�������� : $result[pressure_avg] �� " ; 
echo "��������� : $result[humidity_avg] % "; 
echo "�������� ����� : $result[wind_avg] �/�"; 
?>
