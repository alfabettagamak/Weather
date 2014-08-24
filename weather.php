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
		'header' => 'Content-Type: application/json'
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
#echo "\nТемпература : $result[temp_current_c]"; 
#echo "\n $result[cloud_title] "; 
#echo "$result[precip_title]"; 
#echo "\nДавление : $result[pressure_avg] мм " ; 
#echo "\nВлажность : $result[humidity_avg] % "; 
echo "\Параметры:"; 

 $res = array(
 'city' => $params['city'],
 'time' => date("d.m.Y G:i:s"),
 'temp_current_c' => $result['temp_current_c'],
 'pressure_avg' => $result['pressure_avg'],
 'humidity_avg' => $result['humidity_avg'],
 'wind_avg' => $result['wind_avg'],
 );
 print_r($res);
?>

<?php 
try {
 $conn = new Mongo('185.31.161.49');

 $db = $conn->test;
 $collection = $db->weather;


 $collection->insert($res);
 
 echo 'Inserted document with ID: ' . $weather['_id'];
 // disconnect from server
 $conn->close();
} catch (MongoConnectionException $e) {
 die('Error connecting to MongoDB server');
} catch (MongoException $e) {
 die('Error: ' . $e->getMessage());
}
?>
