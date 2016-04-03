<?php
$route = '/getting-started/:getting_started_id/';
$app->get($route, function ($getting_started_id) use ($app){

	$host = $_SERVER['HTTP_HOST'];
	$getting_started_id = prepareIdIn($getting_started_id,$host);
	$getting_started_id = mysql_real_escape_string($getting_started_id);

	$ReturnObject = array();
	$Query = "SELECT * FROM getting_started WHERE getting_started_id = " . $getting_started_id;
	$DatabaseResult = mysql_query($Query) or die('Query failed: ' . mysql_error());

	while ($Database = mysql_fetch_assoc($DatabaseResult))
		{

		$getting_started_id = $Database['getting_started_id'];
		$title = $Database['title'];
		$image = $Database['image'];
		$header = $Database['header'];
		$footer = $Database['footer'];

		$stepsQuery = "SELECT * from steps k";
 		$stepsQuery .= " WHERE getting_started_id = " . $getting_started_id;
 		$stepsQuery .= " ORDER BY name ASC";
 		$stepsResults = mysql_query($stepsQuery) or die('Query failed: ' . mysql_error());

 		$getting_started_id = prepareIdOut($getting_started_id,$host);

 		$F = array();
 		$F['getting_started_id'] = $getting_started_id;
 		$F['title'] = $title;
 		$F['image'] = $image;
 		$F['header'] = $header;
 		$F['footer'] = $footer;

 		// steps
 		$F['steps'] = array();
 		while ($steps = mysql_fetch_assoc($stepsResults))
 			{
 			$name = $steps['name'];
 			$description = $steps['description'];
 			$K = array();
 			$K['title'] = $title;
 			$K['url'] = $url;
 			array_push($F['steps'], $K);
 			}

		$ReturnObject = $F;
		}

		$app->response()->header("Content-Type", "application/json");
		echo format_json(json_encode($ReturnObject));
	});
?>
