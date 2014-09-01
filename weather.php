<html>
<head>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8">
<meta http-equiv="Content-Language" content="ru">
</head>
</html>

<?php
 error_reporting(E_ALL);

$method = 'getForecasts';
$params = array('name' => 'current',
                'cities' => array("nsk","omsk","tomsk"));
$request = array(
    'method'    => $method,
    'params'     => $params,
);
$request = json_encode($request);

$opts = array(
    'http'=>array(
        'method'=>"POST",
        'content'=>$request,
		'header' => 'Content-Type: application/json'
    )
); 

$server = 'http://pogoda.ngs.ru/json/';
$context = stream_context_create($opts);
$result = file_get_contents($server, 0, $context);
$result = json_decode($result, true);
$result = $result['result'];
 
print_r($result);
try{
   $conn = new Mongo('185.31.161.49');
   $db = $conn->test;
   $collection = $db->weather;
//from json to db
foreach ($result as $city => $data) {
   $current = $data['current'];
   $time = $current['last_success_update_date'];
   $res = array (
    'city' => $city,
    'time' => $time,
    'temp_current_c' => $current['temp_current_c'],
    'pressure_avg' => $current['pressure_avg'],
    'humidity_avg' => $current['humidity_avg'],
    'wind_avg' => $current['wind_avg']
   );
//find value city and time equal value from json-file
   $cursor = $collection->find(array('city'=>$city, 'time' => $time)); 
	  
   $count = $cursor->count();
   if ($count == 0) {
      $collection->insert($res);
   }
} 
//delete where time > 24 h
$rangeQuery = array('time' => array( '$lt' => (String)(time() + strtotime('-1 day', $timestamp))));
$collection->remove($rangeQuery);

 // disconnect from server
 $conn->close();
} catch (MongoConnectionException $e) {
 die('Error connecting to MongoDB server');
} catch (MongoException $e) {
 die('Error: ' . $e->getMessage());
}

?>


