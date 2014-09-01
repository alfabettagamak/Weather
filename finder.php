<?php
 error_reporting(E_ALL);

if (isset($_POST["city"]) && isset($_POST["indicator"])){
	$city = $_POST["city"];
	$indicator = $_POST["indicator"];
try{
   $conn = new Mongo('185.31.161.49');
   $db = $conn->test;
   $collection = $db->weather;
   $cursor = $collection->find(array('city'=> $city));

   if ($indicator == 1) { 
   $cursor->sort(array('time' => -1))->limit(1);}

   else { $cursor->sort(array('time' => 1));
	}
   $map = iterator_to_array($cursor);
   $simpleArray = array();
   foreach ($map as $value) {
   $simpleArray[] = $value; 
   }

   $array =json_encode($simpleArray);
   
   echo "$array";

   $conn->close();
} catch (MongoConnectionException $e) {
 die('Error connecting to MongoDB server');
} catch (MongoException $e) {
 die('Error: ' . $e->getMessage());
}
}
 else { echo 'error in POST query';}
?>

