<?php

	include_once("../includes/util.php");

	// GET ALL FACULTY
	$sql = "SELECT id, fname, lname FROM `tbl_staff` WHERE stafftypeid = 1 ORDER BY lname ASC, fname ASC";
	$res = mysql_query($sql) or die(mysql_error());
	if(mysql_num_rows($res) > 0) {
		while($row = mysql_fetch_assoc($res))
			$faculty[] = $row;
	} 
	
?>
<form method="POST" action="">
	<select id="select_event">
<?php if($isradix) { ?>
		<option value="">Event</option>
		<option value="28">RADIX</option>
<?php } else { ?>
		<option value="">Event</option>
		<option value="7">JUMP</option>
		<option value="8">NUVO</option>
		<option value="18">24 SEVEN</option>
		<option value="14">The Dance Awards</option>
		<option value="38">American Tap Championships</option>
<?php } ?>	
	</select>
	
	<select id="select_city">
		<option value="">City</option>
	</select>
	
	<select id="select_judge">
		<option value="">Judge Name</option>
	<?php
		foreach($faculty as $fac) { ?>
			<option value="<?=$fac["id"];?>"><?=stripslashes($fac["fname"]." ".$fac["lname"]);?></option>
	<?	} ?>
	</select>
	
	<select id="select_position">
		<option value="">Judge Position</option>
		<option value="1">1</option>
		<option value="2">2</option>
		<option value="3">3</option>
		<option value="4">4</option>
	</select>
	
	<select id="select_compgroup">
		<option value="finals">Finals</option>
		<option value="prelims">Prelims</option>
		<option value="vips">Best Dancers</option>
	</select>
	
	<button id="select_submit" style="width:100%;display:none;">Submit</button>
</form>