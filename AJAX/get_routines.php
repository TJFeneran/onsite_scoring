<?php

	include("../includes/util.php");
	
	$tourdateid = intval($_POST["tourdateid"]);
	$compgroup = mysql_real_escape_string($_POST["compgroup"]);
	$position = intval($_POST["position"]);
	$judgeid = intval($_POST["judgeid"]);
	
	$memcache = new Memcache;
	$memcache = memcache_connect('btfp-memcache.6vdjaa.cfg.usw1.cache.amazonaws.com', 11211);
	$schedule =  $memcache->get("csched_".$tourdateid."_".$compgroup);
	
	$schedule = unserialize($schedule);
	if(count($schedule) > 1) {
		foreach($schedule as $routinekey=>$routine) {
			
			//check if this judge has a score already, for lock
			$check = db_one("facultyid$position","tbl_online_scoring","tourdateid='$tourdateid' AND compgroup='$compgroup' AND routineid='".$routine["routineid"]."'");
			$routine["lock"] = ($check == $judgeid) ? 1 : 0;
			$tmp[] = $routine;
		}
		
		print(json_encode($tmp));
	}	
	
	exit();
?>
