<?php

	$isradix = ($_SERVER["SERVER_NAME"] == "judges.radixdance.com") ? true : false;
	
?><!DOCTYPE html>
<html class="no-js" lang="en">
	<head>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=.5" />
		<title><?=$isradix ? "Radix" : "BTF";?> Online Judging</title>
		<link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
		<link rel="icon" href="favicon.ico" type="image/x-icon">
		<link rel="stylesheet" href="css/foundation.css" />
		<link rel="stylesheet" href="css/style.css" />
		<script src="js/vendor/modernizr.js"></script>
	</head>
	<body>
		
		<div class="row fullWidth">
			
			<div id="sidebar" class="small-3 columns">
				
				<div id="sidebar_top">
					<div id="sidebar_logo"></div>
					<div id="sidebar_dispdate">&nbsp;</div>
				</div>
				<div id="sidebar_inner"></div>
				<div id="sidebar_bottom">
					<ul class="small-block-grid-3">
						<li id="bottom_icon_judge">
							<img src="/images/icons/judge.png" style="width:35px;height:auto;display:block;">
							Judge<br/>#<span id="disp_judge"></span>
						</li>
						<li id="bottom_icon_refresh">
							<img src="/images/icons/refresh.png" style="width:35px;height:auto;display:block;">
							Refresh<br/>List
						</li>
						<li id="bottom_icon_signout">
							<img src="/images/icons/logout.png" style="width:35px;height:auto;display:block;">
							Sign<br/>Out
						</li>
					</ul>
				</div>
	        </div>
	        <div id="main" class="small-9 columns">
	        	
	        	<!-- loader -->
				<div id="loader"></div>
	        	
	        	<div id="main_inner"> 
	        	
					<div class="row">
						<div class="small-12 columns">
							<div id="routine_number_name">
								<span id="routine_number">&nbsp;</span><span id="routine_name">&nbsp;</span><span id="studio_code" style="vertical-align:middle;font-size:22px;padding-left:20px;font-weight:normal;text-shadow:none;color:#999999;"></span>
							</div>
							<div style="font-size:16px;color:#CCCCCC;">
								<span id="routine_agedivision"></span>&nbsp;&bull;&nbsp;<span id="routine_perfdivision"></span>&nbsp;<span id="routine_category"></span>
							</div>
						</div>
					</div>
					
					<div class="row" id="att_row" style="margin-top:15px;">
						<p id="memcache_data"></p>
			        </div>
			        
			        <div class="row" style="margin-top:15px;">
			        	<div class="small-6 columns">
			        		<h3>Notes</h3>
							<textarea id="routine_notes" style="height:140px;font-size:1.2rem;"></textarea>
							<div style="padding-left:15px;">
								<input type="checkbox" name="not_friendly" id="not_friendly"><label for="not_friendly" style="color:#DDDDDD;font-size:16px;"> - This routine is not Family-Friendly</label>
							</div>
							<div style="padding-left:15px;">
								<input type="checkbox" name="i_choreographed" id="i_choreographed"><label for="i_choreographed" id="i_choreographed_label" style="color:#DDDDDD;font-size:16px;"> - I choreographed this routine</label>
							</div>
			        	</div>
			        	<div class="small-1 columns">&nbsp;</div>
			        	<div class="small-5 columns">
			        		<h3>Your Score: <div id="scoring_breakdown">Scoring Breakdown</div></h3>
			        		<div style="padding-left:25px;">
								<div class="score_wrap">
									<a class="cat_dir down">-</a>
									<div id="score" class="cat_score" data-limit="100">100</div>
									<a class="cat_dir up">+</a>
								</div>
								<button id="main_save" class="button radius success" style="width:210px;text-shadow:1px 1px 1px #222;">Save</button>
								<button id="main_reset" class="button radius secondary" style="width:100px;margin-left:10px;text-shadow:1px 1px 1px #333;">Reset</button>
							</div>
						</div>
			        </div>

				</div> <!-- / main inner -->
			</div>
	    </div>
			
		
		<script src="js/vendor/jquery.js"></script>
		<script src="js/vendor/jquery.nicescroll.min.js"></script>
		<script src="js/foundation.min.js"></script>
		<script src="js/global.js"></script>
		<script type="text/javascript">
			
			$(document).foundation();
			
			$(document).ready(function(){
				
				// LOG IN
				$(document).on("click","#login_submit", function(event) {
					do_login();
					event.preventDefault();
				});
				
				// LOAD TOUR DATES
				$(document).on("change","#select_event", function(event) {
					get_tourdates($(this).val());
				});
				
				// SHOW / HIDE SETTING SUBMIT
				$(document).on("change","#select_event, #select_city, #select_judge, #select_position", function() {
					if($("#select_event").val() != "" && $("#select_city").val() != "" && $("#select_judge").val() != "" && $("#select_position").val() != "")
						$("#select_submit").fadeIn("fast");
					else $("#select_submit").fadeOut("fast");
				});
				
				$(document).on("click","#select_submit", function(event) {
					check_position();
					event.preventDefault();
				})
				
				//ROUTINE CLICK
				$(document).on("click","#routine_list li", function(){
					
					//check for unsaved changes
					if(global.unsavedchanges) {
						
						global.unsavedchanges_upcoming = $(this).attr("data-routineid");
						$('#unsaved_changes_modal').foundation("reveal","open");
						
					} else {
			
						if(!($(this).hasClass("routine_list_locked"))) {
						
							$('#routine_list li').removeClass("routine_list_active");
							$(this).addClass("routine_list_active");
							
							get_routine(global.tourdateid , $(this).attr("data-routineid") , $(this).attr("data-studioid") , $(this).attr("data-dispnumber"));
							
						} else {
							var txt = $(this).html();
								txt = txt.replace("&nbsp;&nbsp;&nbsp;","&nbsp;-&nbsp;");
							$('#routine_list_locked_which').html(txt);
							$('#routine_list_locked_modal').foundation("reveal","open");
						}	
					}

				});
				
				$('#sidebar_inner').niceScroll();
				$('#sidebar_inner').getNiceScroll().hide();
				
				// I CHOREOGRAPHED THIS ROUTINE
				$(document).on("change",'#i_choreographed',function(){
					if($(this).prop("checked"))
						$('#score').text("0");
					else
						$('#score').text("100");
				});
								
				//SCORE UP/DOWN
				var timeoutId = 0;
				$('#main_inner div.score_wrap a.cat_dir').mousedown(function() {
					
					var dir = $(this).hasClass("up") ? "up" : "down";
					var lim = 100;
					var cur = parseInt($('#score').text());
					var news = 0;
					global.unsavedchanges = true;
					
					// insta-single-click
					
					if($('#i_choreographed').prop("checked")) {
						// if they choreographed, score MUST always be 0
						news = 0;
						
					} else {
						
						if(dir == "up")
							news = (cur == lim ? cur : cur + 1);
						else
							news = (cur == 0 ? 0 : cur - 1);
							
						//score can't go below 77
						if(news == 76 && dir == "down")
							news = 77;
							
						$('#score').text(news);

					}
					
				}).bind('mouseup mouseleave', function() {
				//	clearInterval(timeoutId);
				});
				
				
				//REFRESH
				$(document).on("click","#bottom_icon_refresh",function(){
					$('#routine_list').css("opacity",0.5);
					$('#main_inner').fadeOut("fast");
					showMemcacheData();
				});
				
				//ATTRIBUTE SELECT
				$(document).on("click","#att_row ul li",function(){
					
					global.unsavedchanges = true;
					
					//first click: good
					if(!($(this).children().hasClass("chosen_good")) && !($(this).children().hasClass("chosen_bad"))) {
						$(this).children().addClass("chosen_good");
					} else {
						//second click: bad
						if($(this).children().hasClass("chosen_good")) {
							$(this).children().removeClass("chosen_good");
							$(this).children().addClass("chosen_bad");
						} else {
							//three clicks: back to off
							if($(this).children().hasClass("chosen_bad")) {
								$(this).children().removeClass("chosen_bad");
							}
							
						}
						
					}
					
				});
				
				//JUDGE ICON CLICK
				$(document).on("click","#bottom_icon_judge",function(){
					$('#settings_modal').foundation('reveal','open');
				});
				
				//SIGN OUT MODAL
				$(document).on("click","#bottom_icon_signout",function(){
					$('#signout_modal').foundation('reveal','open');
				});
				
				//SIGN OUT
				$(document).on("click","#signout_final",function(event){
					$('#signout_modal').foundation('reveal','close');
					$('#sidebar_bottom').slideUp();
					$('#main_inner').fadeOut("normal",function(){
						$('#sidebar_inner').fadeOut("normal",function(){
							$('#sidebar_top').slideUp("normal",function(){
								location.reload(true);
							});
						});
					});
				});
				
				//RETURN TO SETTINGS
				$(document).on('click','#return_settings',function(){
					$('#settings_modal').foundation('reveal','close');
					return_to_settings();
				});
				
				//SCORING BREAKDOWN
				$(document).on("click","#scoring_breakdown",function(){
					$('#scoring_breakdown_modal').foundation('reveal','open');
				});
				
				// MAIN RESET BTN
				$(document).on("click","#main_reset",function(){
					reset_score();
					$('#att_row ul li a').removeClass("chosen_good").removeClass("chosen_bad");
					$('#routine_notes').val("");
					$('#not_friendly').prop("checked",false);
					$('#i_choreographed').prop("checked",false);
				});
				
				// INITIAL SAVE
				$(document).on("click","#main_save",function(){
					$('#save_confirm_score').text($('#score').text())
					$('#save_confirm_modal').foundation("reveal","open");
				});
				
				// FINAL SAVE CANCEL
				$(document).on("click","#save_cancel",function(){
					$('#save_confirm_modal').foundation("reveal","close");
				});
				
				// LOCKED MODAL CLOSE
				$(document).on("click","#locked_modal_close",function(){
					$('#routine_list_locked_modal').foundation("reveal","close");
				});
				
				//UNSAVED CHANGES CONFIRM ROUTINE SWAP
				$(document).on("click","#unchanged_okay",function(){
					global.unsavedchanges = false;
					$('#unsaved_changes_modal').foundation("reveal","close");
					$('#routine_list *[data-routineid="'+global.unsavedchanges_upcoming+'"]').trigger("click");
				});
				
				//UNSAVED CHANGES CANCEL
				$(document).on("click","#unchanged_cancel",function(){
					$('#unsaved_changes_modal').foundation("reveal","close");
				});
				
				
				// FINAL SAVE CONFIRM
				$(document).on("click","#save_final",function(){
					
					var score = parseInt($('#score').text());
					var note = $('#routine_notes').val();
					var chosen_good = [];
					var chosen_bad = [];
					var nf = $('#not_friendly').prop("checked") ? 1 : 0;
					var ic = $('#i_choreographed').prop("checked") ? 1 : 0;
					
					$('#att_row ul li a').each(function(){	
						if($(this).hasClass("chosen_good"))
							chosen_good.push($(this).attr("name")+"**"+$(this).text());
						if($(this).hasClass("chosen_bad"))
							chosen_bad.push($(this).attr("name")+"**"+$(this).text());
					});
					$.ajax({
						url: "AJAX/save.php",
						type: "POST",
						data: "nf="+nf+"&ic="+ic+"&compgroup="+global.compgroup+"&sid="+global.studioid+"&rid="+global.routineid+"&tid="+global.tourdateid+"&jid="+global.judgeid+"&pos="+global.position+"&note="+encodeURIComponent(note)+"&score="+score+"&chosengood="+encodeURIComponent(chosen_good.join("|"))+"&chosenbad="+encodeURIComponent(chosen_bad.join("|")),
						success: function(msg) {
							
							$('#save_confirm_modal').foundation("reveal","close");
							
							setTimeout(function(){
								
								global.unsavedchanges = false;
								global.unsavedchanges_upcoming = 0;
								
								//add lock to routine on left
								$('#routine_list li[data-routineid="'+global.routineid+'"]').removeClass("routine_list_active");
								$('#routine_list li[data-routineid="'+global.routineid+'"]').addClass("routine_list_locked");
								$("#main_reset").trigger("click");
								$('#main_inner').fadeOut();
								
								loader_off();
							} , 200)
						}
					});
					
				});
				
			});

			
			get_sidebar_module("login");
		</script>
		
<div id="signout_modal" class="reveal-modal tiny" data-reveal aria-labelledby="Sign Out" aria-hidden="true" role="dialog">
	<h3 id="modalTitle" style="color:#333333;">Are you sure you want to sign out?</h3>
	<div class="row" style="margin-top:15px;">
		<div class="small-5 small-centered columns">
			<a class="button expand alert radius" id="signout_final" style="width:100%;">Sign Out</a>
		</div>
	</div>
	<a class="close-reveal-modal" aria-label="Close">&#215;</a>
</div>

<div id="settings_modal" class="reveal-modal tiny" data-reveal aria-labelledby="Settings" aria-hidden="true" role="dialog">
	<h3 id="modalTitle" style="color:#333333;">Return to setting selection?</h3>
	<div class="row" style="margin-top:15px;">
		<div class="small-5 small-centered columns">
			<a class="button expand radius" id="return_settings" style="width:100%;">Go</a>
		</div>
	</div>
	<a class="close-reveal-modal" aria-label="Close">&#215;</a>
</div>

<div id="scoring_breakdown_modal" class="reveal-modal tiny" data-reveal aria-labelledby="Scoring Breakdown" aria-hidden="true" role="dialog">
	<h3 id="modalTitle" style="color:#333333;">Scoring Breakdown</h3>
	<div class="row" style="margin-top:15px;">
		<div class="small-12 centered columns" id="scoring_breakdown_table_holder">
			
		</div>
	</div>
	<a class="close-reveal-modal" aria-label="Close">&#215;</a>
</div>

<div id="save_confirm_modal" class="reveal-modal tiny" data-reveal aria-labelledby="Save" aria-hidden="true" role="dialog">
	<h3 id="modalTitle" style="color:#333333;">Are you sure you want to save?</h3>
	<div style="text-align:center;padding:10px 0;color:#000000;font-size:22px;">Your Score: <span id="save_confirm_score" style="font-weight:bold;"></span></div>
	<p style="text-align:center;color:#000000;">
		* You will NOT be able to go back and edit your score! *
	</p>
	<div class="row" style="margin-top:15px;">
		<div class="small-6 columns text-lef">
			<a class="button expand success radius" id="save_final" style="width:100%;">Yes, Save</a>
		</div>
		<div class="small-6 columns text-right">
			<a class="button expand alert radius" id="save_cancel" style="width:100%;">Go Back</a>
		</div>
	</div>
	<a class="close-reveal-modal" aria-label="Close">&#215;</a>
</div>

<div id="routine_list_locked_modal" class="reveal-modal tiny" data-reveal aria-labelledby="Locked" aria-hidden="true" role="dialog">
	<div style="text-align:center;padding:10px 0;color:#000000;">You have already entered a score for <span id="routine_list_locked_which" style="font-weight:bold;"></span>.</div>
	<div class="row" style="margin-top:15px;">
		<div class="small-5 small-centered columns">
			<a class="button expand radius" id="locked_modal_close" style="width:100%;">Okay</a>
		</div>
	</div>
	<a class="close-reveal-modal" aria-label="Close">&#215;</a>
</div>

<div id="unsaved_changes_modal" class="reveal-modal tiny" data-reveal aria-labelledby="Unsaved Changes" aria-hidden="true" role="dialog">
	<h3 id="modalTitle" style="color:#333333;">You have unsaved changes!</h3>
	<div class="row" style="margin-top:15px;">
		<p style="text-align:center;color:#000000;">
			You have not saved this routine.  Are you sure you want to change routines without saving?
		</p>
		<div class="small-6 columns text-right">
			<a class="button expand success radius" id="unchanged_okay" style="width:100%;">Yes, change routines</a>
		</div>
		<div class="small-6 columns text-right">
			<a class="button expand alert radius" id="unchanged_cancel" style="width:100%;">Go Back</a>
		</div>
	</div>
	<a class="close-reveal-modal" aria-label="Close">&#215;</a>
</div>

	</body>
</html>