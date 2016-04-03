<?php
$route = '/getting-started/';
$app->get($route, function ()  use ($app,$contentType,$githuborg,$githubrepo){

	$ReturnObject = array();
	//$ReturnObject["contentType"] = $contentType;

	if($contentType == 'application/apis+json')
		{
		$app->response()->header("Content-Type", "application/json");

		$apis_json_url = "http://" . $githuborg . ".github.io/" . $githubrepo . "/apis.json";
		$apis_json = file_get_contents($apis_json_url);
		echo stripslashes(format_json($apis_json));
		}
	else
		{

	 	$request = $app->request();
	 	$params = $request->params();

		if(isset($params['query'])){ $query = trim(mysql_real_escape_string($params['query'])); } else { $query = '';}
		if(isset($params['page'])){ $page = trim(mysql_real_escape_string($params['page'])); } else { $page = 0;}
		if(isset($params['count'])){ $count = trim(mysql_real_escape_string($params['count'])); } else { $count = 50;}
		if(isset($params['sort'])){ $sort = trim(mysql_real_escape_string($params['sort'])); } else { $sort = 'Title';}
		if(isset($params['order'])){ $order = trim(mysql_real_escape_string($params['order'])); } else { $order = 'ASC';}

		// Pull from MySQL
		if($query!='')
			{
			$Query = "SELECT * FROM getting_started WHERE title LIKE '%" . $query . "%' OR header LIKE '%" . $query . "%' OR footer LIKE '%" . $query . "%'";
			}
		else
			{
			$Query = "SELECT * FROM getting_started";
			}
			$Query .= " ORDER BY " . $sort . " " . $order . " LIMIT " . $page . "," . $count;
			//echo $Query . "<br />";
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
			}
	});
?>
