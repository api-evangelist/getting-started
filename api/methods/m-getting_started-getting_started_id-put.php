<?php
$route = '/getting-started/:getting_started_id/';
$app->put($route, function ($getting_started_id) use ($app){

	$host = $_SERVER['HTTP_HOST'];
	$getting_started_id = prepareIdIn($getting_started_id,$host);
	$getting_started_id = mysql_real_escape_string($getting_started_id);

	$ReturnObject = array();

 	$request = $app->request();
 	$params = $request->params();

	if(isset($params['title'])){ $title = mysql_real_escape_string($params['title']); } else { $title = date('Y-m-d H:i:s'); }
	if(isset($params['image'])){ $image = mysql_real_escape_string($params['image']); } else { $image = ''; }
	if(isset($params['header'])){ $header = mysql_real_escape_string($params['header']); } else { $header = ''; }
	if(isset($params['footer'])){ $footer = mysql_real_escape_string($params['footer']); } else { $footer = ''; }

  $Query = "SELECT * FROM getting_started WHERE ID = " . $getting_started_id;
	//echo $Query . "<br />";
	$Database = mysql_query($Query) or die('Query failed: ' . mysql_error());

	if($Database && mysql_num_rows($Database))
		{
		$query = "UPDATE getting_started SET ";
		$query .= "title = '" . mysql_real_escape_string($title) . "'";
		$query .= ", image = '" . mysql_real_escape_string($image) . "'";
		$query .= ", header = '" . mysql_real_escape_string($header) . "'";
		$query .= ", footer = '" . mysql_real_escape_string($footer) . "'";
		$query .= " WHERE getting_started_id = " . $getting_started_id;
		//echo $query . "<br />";
		mysql_query($query) or die('Query failed: ' . mysql_error());
		}

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
	echo stripslashes(format_json(json_encode($ReturnObject)));

	});
?>
