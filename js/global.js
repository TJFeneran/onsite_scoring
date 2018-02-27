var global = {};
	global.eventid = 0;
	global.tourdateid = 0;
	global.judgeid = 0;
	global.position = 0;
	global.routineid = 0;
	global.compgroup = "finals";
	global.studioid = 0,
	global.unsavedchanges = false,
	global.unsavedchanges_upcoming = 0;
	
function loader_on() {
	$('#loader').show();
}
function loader_off() {
	$('#loader').hide();
}

function get_sidebar_module(which){
	if(which.length > 0) {
		
		$('#sidebar_inner').animate({ "margin-top" : 150 , "opacity" : 0 } , 500, function() {
			$.ajax({
				type: "GET",
				url: "modules/"+which+".php",
				success: function(msg) {
					$('#sidebar_inner').html(msg).css("margin-top",0).animate({ "opacity" : 1 } , 500);
				}
			});	
		});
	}
}

function do_login() {
	var un = $('#s1').val(),
		pw = $('#s2').val();

	if(un && pw) {
		
		loader_on();
		
		$.ajax({
			url: "AJAX/login.php",
			type: "POST",
			data: "u="+un+"&p="+pw,
			success: function(msg) {
				if(msg == "v") {
					get_sidebar_module("select");
					loader_off();
				}
			}
		});
	}
}

function return_to_settings() {
	setTimeout(function(){
		global = {};
		
		$('#att_row ul li a').removeClass("chosen");
		clear_main();
		$('#sidebar_dispdate').text("");
		$('#sidebar_logo img').fadeOut("normal",function(){
			$(this).attr("src","");
		});
		
		get_sidebar_module("select");
	} , 500);
}

function get_tourdates(eventid) {
	if(eventid > 0) {
		
		$.ajax({
			url: "AJAX/get_tourdates.php",
			type: "POST",
			data: "e="+eventid,
			success: function(msg) {
				var updatestr = "<option value=''>City</option>";
				var cities = eval("("+msg+")");
				for(key in cities) {
					var cur = cities[key]["currentcity"] == 1 ? "selected" : "";
					updatestr += "<option value='"+cities[key]["id"]+"' "+cur+">"+cities[key]["city"]+"  -  "+cities[key]["dispdate"]+"</option>";
				}
				$('#select_city').html(updatestr);
			}
		});
		
	}
}

function check_position() {
	
	//check if judge already exists in that position, if so warn about overwriting
	$.ajax({
		url: "AJAX/check_position.php",
		type: "POST",
		data: "tourdateid="+parseInt($('#select_city').val())+"&judgeid="+parseInt($('#select_judge').val())+"&position="+parseInt($('#select_position').val())+"&compgroup="+$('#select_compgroup').val(),
		success: function(msg) {
			if(msg == "NO") {
				do_settings();
			} else {
				if(confirm(msg+" already has scores from this position for this tour date. If judges are being swapped, this is fine.  Keep in mind any scores from the new judge could overwrite past scores from the previous judge. Continue?")) {
					do_settings();
				} else {
					return_to_settings();
				}
			}
		}
	});
}

function do_settings() {
	global.eventid = parseInt($('#select_event').val());
	global.tourdateid = parseInt($('#select_city').val());
	global.judgeid = parseInt($('#select_judge').val());
	global.position = parseInt($('#select_position').val());
	global.compgroup = $('#select_compgroup').val();
	
	$('#sidebar_logo').html("<img src='/images/logos/"+global.eventid+".png'>").fadeIn();
	$('#disp_judge').text(global.position);
	$('#sidebar_dispdate').text($('#select_city option:selected').text() + " - " + $('#select_judge option:selected').text());
	
	setTimeout(function(){
		get_scoring_breakdown();
		refresh_routines();
	} , 700);
}

function get_scoring_breakdown() {
	if(global.eventid > 0) {
		$.ajax({
			url: "AJAX/get_scoring_breakdown.php",
			type: "POST",
			data: "eventid="+global.eventid,
			success: function(msg) {
				var awards = eval("("+msg+")");
				var updatestr = "<table style='width:100%;'><thead><tr><th>Award</th><th>Highest</th><th>Lowest</th></tr></thead><tbody>";
				for(key in awards) {
					updatestr += "<tr><td>"+awards[key]["awardname"]+"</td><td>"+awards[key]["highest"]+"</td><td>"+awards[key]["lowest"]+"</td></tr>";
				}
				updatestr += "</tbody></table>";
				
				$('#scoring_breakdown_table_holder').html(updatestr);
			}
		});
	}
}

function refresh_routines() {
	
	loader_on();
	
	$.ajax({
		url: "AJAX/get_routines.php",
		type: "POST",
		data: "tourdateid="+global.tourdateid+"&compgroup="+global.compgroup+"&position="+global.position+"&judgeid="+global.judgeid,
		success: function(msg) {
			
			if(msg) {
				var routines = eval("("+msg+")"),
					updatestr = "",
					addlock = "";
					
				for(key in routines) {
					addlock = routines[key]["lock"] == 1 ? "class='routine_list_locked'" : "";
					updatestr += "<li "+addlock+" data-routineid='"+routines[key]["routineid"]+"' data-studioid='"+routines[key]["studioid"]+"' data-dispnumber='"+routines[key]["dispnumber"]+"'><span style='font-weight:bold;'>#"+routines[key]["dispnumber"]+"</span>&nbsp;&nbsp;&nbsp;"+routines[key]["routinename"]+"</li>";
				}
				$('#sidebar_inner').html('<ul id="routine_list"></ul>');
				$("#routine_list").hide().html(updatestr).fadeIn();
				
				setTimeout(function(){
					$('#sidebar_bottom').fadeIn("slow");
					loader_off();
				} , 1000);
								
			} else {
				$('#sidebar_inner').html("<button class='alert' style='width:100%;' onClick='javascript:get_sidebar_module(\"select\");'>No Routines. Click to return.</button>");
			}

		}
	});
	
};

function showMemcacheData() {
	loader_on();
	
	$.ajax({
		url: "AJAX/get_memcache_data.php",
		type: "POST",
		data: "tourdateid="+global.tourdateid+"&compgroup="+global.compgroup+"&position="+global.position+"&judgeid="+global.judgeid,
		success: function(msg) {
			
			if(msg) {
				var routines = eval("("+msg+")");
				$('#memcache_data').html(routines);
				console.log(routines);
				setTimeout(function(){
					$('#sidebar_bottom').fadeIn("slow");
					loader_off();
				} , 1000);
								
			} else {
				$('#sidebar_inner').html("<button class='alert' style='width:100%;' onClick='javascript:get_sidebar_module(\"select\");'>No Routines. Click to return.</button>");
			}

		}
	});
}


function clear_main() {
	$('#routine_number, #routine_name, #studio_code').html("&nbsp;");
	reset_score();
	$('#main_inner, #sidebar_bottom').fadeOut();
}

function reset_score() {
	
	$('#score').text("100");

}


function get_routine(tourdateid,routineid,studioid,dispnumber) {

	if(tourdateid > 0 && routineid > 0) {
		
		loader_on();
		var doload = false;
		
		$.ajax({
			url: "AJAX/get_routine.php",
			type: "POST",
			data: "tid="+tourdateid+"&rid="+routineid+"&pos="+global.position+"&jid="+global.judgeid+"&cg="+global.compgroup,
			success: function(msg) {
				
				var rd = eval("("+msg+")");
				
				//if another judge has a score for this routine and position, warn first
				if(rd["existingjudgename"].length > 0) {
					if(confirm("WARNING: "+rd["existingjudgename"]+" already has a score for this routine at this position! Any score you save for this routine will OVERWRITE theirs! Are you sure you want to load this routine?")) {
						doload = true;
					} else {
						$('#routine_list li').removeClass("routine_list_active");
						$('#routine_number, #routine_name, #studio_code').html("&nbsp;");
						$('#main_inner').fadeOut();
						reset_score();
					}
				} else {
					doload = true;
				}
				
				if(doload) {
					
					global.routineid = routineid;
					global.studioid = studioid;
					
					$('#main_reset').trigger("click");
					$('#routine_number').text("#"+dispnumber+" - ");
					$('#routine_name').text(rd["routinename"]);
					$('#studio_code').text("("+rd["studiocode"]+")");
					$('#routine_agedivision').text(rd["agedivision"]);
					$('#routine_category').text(rd["routinecategory"]);
					$('#routine_perfdivision').text(rd["perfdivision"]);
					
					if(rd["perfdivision"] == "Showcase") {
						
						//hide numeric scoring
						$('.cat_dir').hide();
						$('#score').text("0");
						
					} else {
						
						//show numeric scoring
						$('.cat_dir').show();
						$('#score').text('100');
					}
					
					$('#main_inner').fadeIn("slow");
				}
				
				loader_off();
			}
		});

	}
}

function updateSidebar() {
	$('#sidebar, #main').css({'min-height': window.innerHeight , "height" : window.innerHeight});
	$('#sidebar_inner').css({'min-height': window.innerHeight - 275 , "height" : window.innerHeight - 275});
}

$(window)
    .load(function() {	updateSidebar();	})
    .resize(function(){	updateSidebar();	});