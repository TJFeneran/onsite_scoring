<?php
	//error_reporting(E_ALL);
	include("../includes/util.php");
	
	$routineid = intval($_POST["rid"]);
	$tourdateid = intval($_POST["tid"]);
	$studioid = intval($_POST["sid"]);
	$judgeid = intval($_POST["jid"]);
	$position = intval($_POST["pos"]);
	$not_friendly = intval($_POST["nf"]);
	$i_choreographed = intval($_POST["ic"]);
	$score = intval($_POST["score"]);
	$chosens_good = explode("|",mysql_real_escape_string($_POST["chosengood"]));
	$chosens_bad = explode("|",mysql_real_escape_string($_POST["chosenbad"]));
	$note = mysql_real_escape_string($_POST["note"]);
	$compgroup = mysql_real_escape_string($_POST["compgroup"]);
	
	
	if($routineid > 1 && $studioid > 1 && $tourdateid > 1 && $judgeid > 0 && $position > 0 && strlen($compgroup) > 0) {
		
		$chosens = array();
		
		if(count($chosens_good) > 0) {
			foreach($chosens_good as $cg) {
				list($cat,$att) = explode("**",$cg);
				$chosens["good"][] = $att;
			}
		}
		if(count($chosens_bad) > 0) {
			foreach($chosens_bad as $cb) {
				list($cat,$att) = explode("**",$cb);
				$chosens["bad"][] = $att;
			}
		}
		
		$eventid = db_one("eventid","tbl_tour_dates","id='$tourdateid'");
		
		$data = array(
			"score"				=>	$score,
			"attributes"		=>	$chosens,
			"note"				=>	$note,
			"not_friendly"		=>	$not_friendly,
			"i_choreographed"	=>	$i_choreographed
		);
		
		$check = db_one("id","tbl_online_scoring","routineid='$routineid' AND tourdateid='$tourdateid' AND compgroup='$compgroup'");
		if($check > 0) {
			//Exists, append or update
			// TODO : CANNOT UPDATE, LET ALONE ACCESS RECORD TO BEGIN WITH... maybe keep in case they click final 'save' twice by accident. whatever.		
			$sql = "UPDATE `tbl_online_scoring` SET facultyid$position='$judgeid', data$position='".mysql_real_escape_string(json_encode($data,true))."' WHERE routineid='$routineid' AND tourdateid='$tourdateid' AND compgroup='$compgroup'";
			$res = mysql_query($sql) or die(mysql_error());
		}
		else {
			//Create row
			$sql = "INSERT INTO `tbl_online_scoring` (routineid,tourdateid,studioid,facultyid$position,data$position,compgroup) VALUES('$routineid','$tourdateid','$studioid','$judgeid','".mysql_real_escape_string(json_encode($data,true))."','$compgroup')";
			$res = mysql_query($sql) or die(mysql_error());
		}
		
		//UPDATE tbl_date_routines score /compgroup position
		$sql = "UPDATE tbl_date_routines SET ".$compgroup."_score".$position."='$score' WHERE tourdateid='$tourdateid' AND routineid='$routineid'";
		$res = mysql_query($sql) or die(mysql_error());
		
		
		//UPDATE TOTAL & DROPPED SCORE
		$sql = "SELECT data1,data2,data3,data4 FROM `tbl_online_scoring` WHERE routineid='$routineid' AND tourdateid='$tourdateid' AND compgroup='$compgroup'";
		$res = mysql_query($sql) or die(mysql_error());
		if(mysql_num_rows($res) > 0) {
			while($row = mysql_fetcH_assoc($res)) {
				$d1 = json_decode($row["data1"],true);
				$d2 = json_decode($row["data2"],true);
				$d3 = json_decode($row["data3"],true);
				$d4 = json_decode($row["data4"],true);
				
				$d1s = $d1["score"];
				$d2s = $d2["score"];
				$d3s = $d3["score"];
				$d4s = $d4["score"];
				
				$n = array($d1s,$d2s,$d3s,$d4s);
				arsort($n);
				$dropped = array_pop($n);
				$total = array_sum($n);
				
				$awardid = db_one("id","tbl_competition_awards","eventid='$eventid' AND $total <= highest AND $total >= lowest");
				
				//update online scoring (this sql2 really won't be used at all if it can be helped.  add these scores to & rely on classic stats scoring / reporting system.)
				$sql2 = "UPDATE `tbl_online_scoring` SET dropped_score='$dropped',total_score='$total',awardid='$awardid' WHERE routineid='$routineid' AND tourdateid='$tourdateid' AND compgroup='$compgroup'";
				$res2 = mysql_query($sql2) or die(mysql_error());
				
				//update classic scoring
				$sql3 = "UPDATE `tbl_date_routines` SET ".$compgroup."_dropped_score='$dropped', ".$compgroup."_total_score='$total', ".$compgroup."_awardid='$awardid' WHERE routineid='$routineid' AND tourdateid='$tourdateid'";
				$res3 = mysql_query($sql3) or die(mysql_error());
				
			}
		}

	}
	
	print("DONE");
	
	exit();
?>
