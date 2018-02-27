<?php

	include("../includes/util.php");
	
	$tid = intval($_POST["tid"]);
	$rid = intval($_POST["rid"]);
	$jid = intval($_POST["jid"]);
	$position = intval($_POST["pos"]);
	$cg = mysql_real_escape_string($_POST["cg"]);
	
	if($tid > 0 && $rid > 0 && $position > 0) {
		$routinedata = api_one("/routines/routineinfo/",array("tourdateid"=>$tid,"routineid"=>$rid));
		$rd = json_decode($routinedata,true);
		$rd["existingjudgename"] = "";
		$check = db_one("id","tbl_online_scoring","tourdateid='$tid' AND routineid='$rid' AND compgroup='$cg'");
		
		if($check > 0) { // EXISTS, attach deets

			$rd["jdata"] = json_decode(db_one("data$position","tbl_online_scoring","tourdateid='$tid' AND facultyid$position=$jid AND routineid='$rid' AND compgroup='$cg'"),true);;

			//JUDGE CHECK
			$check2 = db_one("facultyid$position","tbl_online_scoring","tourdateid='$tid' AND compgroup='$cg' AND routineid=$rid AND facultyid$position > 0");
			if($check2 > 0 && ($check2 != $jid))
				$rd["existingjudgename"] = db_one("fname","tbl_staff","id='$check2'")." ".db_one("lname","tbl_staff","id='$check2'");
			
		}

		print(json_encode($rd));
	}	
	
	exit();
?>