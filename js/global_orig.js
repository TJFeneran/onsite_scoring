var global = {};
	global.eventid = 0;
	global.tourdateid = 0;
	global.judgeid = 0;
	global.position = 0;
	global.routineid = 0;
	global.compgroup = "finals";
	global.studioid = 0;
	
function loader_on() {
	
}
function loader_off() {
	
}

function get_sidebar_module(which){
	if(which.length > 0) {
		loader_on();
		
		$('#sidebar_inner').animate({ "margin-top" : 150 , "opacity" : 0 } , 500, function() {
			$.ajax({
				type: "GET",
				url: "modules/"+which+".php",
				success: function(msg) {
					$('#sidebar_inner').html(msg).css("margin-top",0).animate({ "opacity" : 1 } , 500);
					loader_off();
				}
			});	
		});
	}
}

function do_login() {
	var un = $('#s1').val(),
		pw = $('#s2').val();

	if(un && pw) {
		$.ajax({
			url: "AJAX/login.php",
			type: "POST",
			data: "u="+un+"&p="+pw,
			success: function(msg) {
				if(msg == "v") {
					get_sidebar_module("select");
				}
			}
		});
	}
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

function do_settings() {
	
	global.eventid = parseInt($('#select_event').val());
	global.tourdateid = parseInt($('#select_city').val());
	global.judgeid = parseInt($('#select_judge').val());
	global.position = parseInt($('#select_position').val());
	global.compgroup = $('#select_compgroup').val();
	
	$('#sidebar_logo').html("<img src='/images/logos/"+global.eventid+".png'>").fadeIn();
	$('#disp_judge').text(global.position);
	//$('#sidebar_dispdate').text($('#select_event option:selected').text() + " " + $('#select_city option:selected').text() + " - " + $('#select_judge option:selected').text());
	$('#sidebar_dispdate').text($('#select_city option:selected').text() + " - " + $('#select_judge option:selected').text());
	get_sidebar_module("routines");
	
	setTimeout(function(){
		refresh_routines();
	} , 700);
	
	
}

function refresh_routines() {
	
	$.ajax({
		url: "AJAX/get_routines.php",
		type: "POST",
		data: "tourdateid="+global.tourdateid+"&compgroup="+global.compgroup,
		success: function(msg) {
			
			if(msg) {
				var routines = eval("("+msg+")"),
					updatestr = "";
				for(key in routines) {
					updatestr += "<li data-routineid='"+routines[key]["routineid"]+"' data-studioid='"+routines[key]["studioid"]+"' data-dispnumber='"+routines[key]["dispnumber"]+"'><span style='font-weight:bold;'>#"+routines[key]["dispnumber"]+"</span>&nbsp;&nbsp;&nbsp;"+routines[key]["routinename"]+"</li>";
				}
				$('#sidebar_inner').html('<ul id="routine_list"></ul>');
				$("#routine_list").hide().html(updatestr).fadeIn();
				
				setTimeout(function(){
					$('#sidebar_bottom').fadeIn("slow");
				} , 1000);
								
			} else {
				$('#sidebar_inner').html("<button class='alert' style='width:100%;' onClick='javascript:get_sidebar_module(\"select\");'>No Routines. Click to return.</button>");
			}

		}
	});
	
};

function clear_main() {
	$('#routine_number, #routine_name, #studio_name').html("&nbsp;");
	$('#routine_note').val("");
	reset_scores();
	$('#main_inner, #sidebar_bottom').fadeOut();
}

function reset_scores() {
	
	$('#scores_technique').text("50");
	$('#scores_performance').text("20");
	$('#scores_choreo').text("20");
	$('#scores_overall').text("10");
	
}


function get_routine(tourdateid,routineid,studioid,dispnumber) {

	if(tourdateid > 0 && routineid > 0) {
		$.ajax({
			url: "AJAX/get_routine.php",
			type: "POST",
			data: "tid="+tourdateid+"&rid="+routineid+"&pos="+global.position+"jid="+global.judgeid+"&&cg="+global.compgroup,
			success: function(msg) {
				
				global.routineid = routineid;
				global.studioid = studioid;
				
				var rd = eval("("+msg+")");
				
				$('#main_reset').trigger("click");
				$('#routine_number').text("#"+dispnumber+" - ");
				$('#routine_name').text(rd["routinename"]);
				$('#studio_name').text(rd["studioname"]);
				
				if(rd["jdata"]) {
					
					$('#scores_technique').text(rd["jdata"]["score_technique"]);
					$('#scores_performance').text(rd["jdata"]["score_performance"]);
					$('#scores_choreo').text(rd["jdata"]["score_choreo"]);
					$('#scores_overall').text(rd["jdata"]["score_overall"]);
					
					$('#routine_notes').val(rd["jdata"]["note"]);
					
					for(key in rd["jdata"]["attributes"]) {
						$('#att_row ul li a').each(function(){
							if($(this).text() == rd["jdata"]["attributes"][key])
								$(this).addClass("chosen");
						});
					}	
				}
				
				do_your_score();
				
				$('#main_inner').fadeIn("slow");
			}
		});
	}
}

function do_your_score() {
	$('#your_score').text(parseInt($('#scores_technique').text()) + parseInt($('#scores_performance').text()) + parseInt($('#scores_choreo').text()) + parseInt($('#scores_overall').text()));
}

function updateSidebar() {
	$('#sidebar, #main').css({'min-height': window.innerHeight , "height" : window.innerHeight});
	$('#sidebar_inner').css({'min-height': window.innerHeight - 275 , "height" : window.innerHeight - 275});
}

$(window)
    .load(function() {	updateSidebar();	})
    .resize(function(){	updateSidebar();	});