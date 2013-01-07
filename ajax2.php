<?
	if ($page == 15)
	{
		$athlete_id		= $_GET[athlete_id];
		$wod_id			= $_GET[wod_id];
		$res			= $_POST[val];
		$wod_type		= $_GET[wod_type];
		if ($wod_type == 'time')
		{
	     $a = explode(":", $res);
         $res =  $a[0] * 60 + $a[1];
		}
		$sql = "UPDATE ".$prefix."event_result set result = ".$res." where athlete_id = ".$athlete_id." and wod_id = ".$wod_id." ";
	//	print $sql;
		mysql_query($sql) or die("error in sql:<br>".$sql );
	}
	
	if ($page == 16 )
	{
		
		$sql = "Update ".$prefix."event_autoconfigurator set selfscore = ".$_GET[data]." where event_id = ".$_GET[event_id]." ";
		mysql_query($sql) or die ("Error: ".$sql);
	}
	?>