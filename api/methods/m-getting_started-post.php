<?php
$route = '/getting-started/';
$app->post($route, function () use ($app){

 	$request = $app->request();
 	$params = $request->params();

	if(isset($params['title'])){ $title = mysql_real_escape_string($params['title']); } else { $title = date('Y-m-d H:i:s'); }
	if(isset($params['image'])){ $image = mysql_real_escape_string($params['image']); } else { $image = ''; }
	if(isset($params['header'])){ $header = mysql_real_escape_string($params['header']); } else { $header = ''; }
	if(isset($params['footer'])){ $footer = mysql_real_escape_string($params['footer']); } else { $footer = ''; }

  $Query = "SELECT * FROM getting_started WHERE title = '" . $title . "'";
	//echo $Query . "<br />";
	$Database = mysql_query($Query) or die('Query failed: ' . mysql_error());

	if($Database && mysql_num_rows($Database))
		{
		$Thisgetting_started = mysql_fetch_assoc($Database);
		$getting_started_id = $Thisgetting_started['ID'];
		}
	else
		{
		$Query = "INSERT INTO getting_started(title,image,header,footer)";
		$Query .= " VALUES(";
		$Query .= "'" . mysql_real_escape_string($title) . "',";
		$Query .= "'" . mysql_real_escape_string($image) . "',";
		$Query .= "'" . mysql_real_escape_string($header) . "',";
		$Query .= "'" . mysql_real_escape_string($footer) . "'";
		$Query .= ")";
		//echo $Query . "<br />";
		mysql_query($Query) or die('Query failed: ' . mysql_error());
		$getting_started_id = mysql_insert_id();
		}

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
