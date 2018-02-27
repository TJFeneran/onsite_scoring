<?php

	include("../includes/util.php");
	
	$eventid = intval($_POST["e"]);
	if($eventid > 0) {
		
		$seasonid = db_one("currentseason","events","id=$eventid");
		
		$sql = "SELECT tbl_tour_dates.id,tbl_tour_dates.city,tbl_tour_dates.webcast_this_city,tbl_states.abbreviation AS stateabbr FROM `tbl_tour_dates` LEFT JOIN tbl_states ON tbl_states.id=tbl_tour_dates.stateid WHERE eventid='$eventid' AND seasonid='$seasonid' ORDER BY start_date ASC";
		$res = mysql_query($sql) or die(mysql_error());
		if(mysql_num_rows($res) > 0) {
			while($row = mysql_fetch_assoc($res)) {
				$tourdates[] = array("id"=>$row["id"],"city"=>$row["city"],"stateabbr"=>$row["stateabbr"],"currentcity"=>$row["webcast_this_city"],"dispdate"=>get_tourdate_dispdate($row["id"]));
			}
		}		
	}
	
	print(json_encode($tourdates));
	
	
	exit();
?>
