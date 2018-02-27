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
	print(json_encode($schedule));
	
	exit();
?>
