<?php
$route = '/getting-started/:getting_started_id/';
$app->delete($route, function ($getting_started_id) use ($app){

	$host = $_SERVER['HTTP_HOST'];
	$getting_started_id = prepareIdIn($getting_started_id,$host);
	$getting_started_id = mysql_real_escape_string($getting_started_id);

	$Add = 1;
	$ReturnObject = array();

 	$request = $app->request();
 	$_POST = $request->params();

	$query = "DELETE FROM getting_started WHERE getting_started_id = " . $getting_started_id;
	//echo $query . "<br />";
	mysql_query($query) or die('Query failed: ' . mysql_error());

	});
?>
