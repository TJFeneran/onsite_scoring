<?php

	include("conn.php");
	db_connect();


	function db_one($field,$table,$where = "",$limit = 1) {
		if(strlen($where)>0)
			$where = "WHERE ".$where;
		$base_sql = "SELECT $field FROM $table $where LIMIT $limit";
		$res = mysql_query($base_sql) or die(mysql_error());
		while(list($afield) = mysql_fetch_row($res))
			return $afield;
	}

	//returns nicely-formatted tourdate date string (ex: January 4-6, 2013  or  February 28 - March 2, 2013  or  May 28, 2013)
	function get_tourdate_dispdate($tourdateid = 0) {
		if($tourdateid > 1) {
			$start_date_a = db_one("start_date","tbl_tour_dates","id=$tourdateid");
			if(strlen($start_date_a) > 2) {
				list($yy,$mm,$dd) = explode("-",$start_date_a);
				$start_date = date('F j',mktime(0,0,0,$mm,$dd,$yy));
				$start_month = date('F',mktime(0,0,0,$mm,$dd,$yy));
				$end_date_a = db_one("end_date","tbl_tour_dates","id=$tourdateid");
				list($eyy,$emm,$edd) = explode("-",$end_date_a);
				$end_date = date('F j',mktime(0,0,0,$emm,$edd,$eyy));
				$end_month = date('F',mktime(0,0,0,$emm,$edd,$eyy));
				$end_day = date('j',mktime(0,0,0,$emm,$edd,$eyy));
				
				//if one day
				if($start_date == $end_date) {
					$dispdate = $start_date.", $yy";
				}
				//if not one day
				else {
					if($start_month != $end_month)
						$dispdate = $start_date."-".$end_date.", $eyy";
					else $dispdate = $start_date."-".$end_day.", $eyy";
				}
			}
			return $dispdate;
		}
	}
	
	function api_one($call,$vars) {
		$fieldstr = "";
		$isradix = ($_SERVER["SERVER_NAME"] == "judges.radixdance.com") ? true : false;
		$base = $isradix ? "https://stats.radixdance.com/api/" : "https://stats.breakthefloor.com/api/";
		//$call format = "group|[specific] EG: dancers|workshopfee
        	
        $url = $base.$call;
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
	    
        if(count($vars) > 0) {
	    	extract($vars);
	    	foreach($vars as $key=>$val) {
	    		$fieldstr .= $key .'='. $val .'&';
	    	}
	    	rtrim($fieldstr,'&');
		    curl_setopt($ch, CURLOPT_POST, count($vars));
		    curl_setopt($ch, CURLOPT_POSTFIELDS, $fieldstr);
	    }
	    $res = curl_exec($ch);
	    curl_close($ch);
 
	    return $res;
	}


?>