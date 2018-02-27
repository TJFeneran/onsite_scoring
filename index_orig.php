<!DOCTYPE html>
<html class="no-js" lang="en">
	<head>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=.5" />
		<title>BTFP Online Judging</title>
		<link rel="stylesheet" href="css/foundation.css" />
		<link rel="stylesheet" href="css/style.css" />
		<script src="js/vendor/modernizr.js"></script>
		<style type="text/css">
		
			/*@-ms-viewport { width: 1920; }*/
		
			body {
				user-select: none;
				-moz-user-select: none;
			}
			
			.fullWidth {
				width: 100%;
				margin-left: auto;
				margin-right: auto;
				max-width: initial;
			}
			.no-pad-right {
				padding-right: 0;
			}
			.no-pad-left{
				padding-left: 0;
			}
			
			#sidebar {
				background-color: #284765;
				z-index: 5;
				box-shadow: 3px 3px 3px #0B2232;
			}
			
			#sidebar_top {
				height: 120px;
				padding: 15px;
				border-bottom: 1px solid #2BA6CB;
				margin-bottom: 25px;
			}
			
			#sidebar_logo {
				display: none;
				text-align: center;
				height: 78px;
				margin: 0 auto;
			}
				#sidebar_logo img {
					height: 75%;
					width: auto;
				}
			#sidebar_dispdate {
				text-align: center;
				color: #9DBADD;
				font-weight: bold;
				font-size: .8rem;
			}
			
			#sidebar_inner {
				margin-bottom: 25px;
			}
			
			#sidebar_bottom {
				border-top: 1px solid #2BA6CB;
				display: none;
			}
				#sidebar_bottom ul {
					
				}
					#sidebar_bottom ul li {
						padding-top: 15px;
						text-align: center;
						color: #FFFFFF;
						font-size: 14px;
						line-height: 18px;
						cursor: pointer;
						background-color: none;
					}
						#sidebar_bottom ul li:hover {
							background-color: #183045;
						}
						#sidebar_bottom ul li img {
							margin: 0 auto 10px;
						}
			
			#main {
				background-color: #183045;
				color: #FFFFFF;
			}
			
			#routine_list {
				list-style: none;
				margin: 0;
				padding: 0;
			}
				#routine_list li {
					min-height: 60px;
					padding: 17px;
					background-color: #88B8EA;
					border-bottom: 1px solid #284765;
					cursor: pointer;
					color: #333333;
					user-select: none;
					-moz-user-select: none;
				}
					#routine_list li:hover {
						background-color: #B1DBE4;
					}
					.routine_list_active {
						background-color: #FDFFF7 !important;
					}
			
			::-webkit-scrollbar { 
				display: none; 
			}
			
			
			#main_inner {
				display: none;
			}
			
				#main_inner h3 {
					border-bottom: 1px dashed #284765;
					font-size: 1.4rem !important;
				}
					#main_inner div.score_wrap {
						position: absolute;
						right: 20px;
						top: -10px;
					}
					
						#att_row div.score_wrap div.cat_score {
							display: inline-block;
							font-size: 1.75rem;
							line-height: 1.75rem;
							vertical-align: super;
							color: #FFFFFF;
							width: 35px;
							text-align: center;
							user-select: none;
							-moz-user-select: none;
						}

						#att_row div.score_wrap a.cat_dir {
							width: 30px;
							height: 35px;
							display: inline-block;
							color: #6B8C8B;
							vertical-align: baseline;
							text-align: center;
							font-size: 2.2rem;
							user-select: none;
							-moz-user-select: none;
						}
				
				#main_inner #att_row ul {
					margin-top: 15px;
				}
				
				#main_inner #att_row li a.button {
					background-color: #2C445E;
					border-top: 1px solid #183045;
					user-select: none;
					-moz-user-select: none;
					text-shadow: 1px 1px 1px #00273E;
				}
					#main_inner #att_row li a.button:not(.chosen):hover {
						background-color: #2C445E !important; /*  477192 */
					}
					.chosen {
						background-color: #5586AE !important; 
					}
				
				#routine_number_name {
					font-size: 2.785rem;
					font-weight: bold;
					text-shadow: 1px 1px 3px #3C6E8E;
				}
				#studio_name {
					font-size: 1.5rem;
					text-shadow: 1px 1px 3px #3C6E8E;
				}
				
		</style>
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
	        	
	        	<div id="main_inner"> 
	        	
					<div class="row">

						<div class="small-12 columns">
							
							<div id="routine_number_name">
								<span id="routine_number">&nbsp;</span><span id="routine_name">&nbsp;</span>
							</div>
							<div id="studio_name">&nbsp;</div>
							
						</div>
					</div>
					
					<div class="row" id="att_row" style="margin-top:30px;">
						<div class="small-3 columns">
							<div class="score_wrap">
								<a class="cat_dir down" name="technique">-</a>
								<div id="scores_technique" class="cat_score" data-limit="50">50</div>
								<a class="cat_dir up" name="technique">+</a>
							</div>
							<h3>Technique</h3>
							<ul class="stack button-group">
								<li><a class="button">Control / Balance</a></li>
								<li><a class="button">Elevation</a></li>
								<li><a class="button">Turns</a></li>
								<li><a class="button">Arms</a></li>
								<li><a class="button">Hands</a></li>
								<li><a class="button">Extensions</a></li>
								<li><a class="button">Head / Shoulders</a></li>
								<li><a class="button">Body Lines</a></li>
								<li><a class="button">Feet</a></li>
								<li><a class="button">Legs</a></li>
							</ul>
						</div>
						
						<div class="small-3 columns">
							<div class="score_wrap">
								<a class="cat_dir down" name="performance">-</a>
								<div id="scores_performance" class="cat_score" data-limit="20">20</div>
								<a class="cat_dir up" name="performance">+</a>
							</div>
							<h3>Performance</h3>
							<ul class="stack button-group">
								<li><a class="button">Personality</a></li>
								<li><a class="button">Stage Presence</a></li>
								<li><a class="button">Smile</a></li>
								<li><a class="button">Intensity</a></li>
								<li><a class="button">Emotion</a></li>
							</ul>
						</div>
						
						<div class="small-3 columns">
							<div class="score_wrap">
								<a class="cat_dir down" name="choreo">-</a>
								<div id="scores_choreo" class="cat_score" data-limit="20">20</div>
								<a class="cat_dir up" name="choreo">+</a>
							</div>
							<h3>Choreo. &amp; Musicality</h3>
							<ul class="stack button-group">
								<li><a class="button">Choreography</a></li>
								<li><a class="button">Precision</a></li>
								<li><a class="button">Creativeness</a></li>
								<li><a class="button">Timing</a></li>
								<li><a class="button">Difficulty</a></li>
								<li><a class="button">Use of Space</a></li>
								<li><a class="button">Continuity</a></li>
							</ul>
						</div>
						
						<div class="small-3 columns">
							<div class="score_wrap">
								<a class="cat_dir down" name="overall">-</a>
								<div id="scores_overall" class="cat_score" data-limit="10">10</div>
								<a class="cat_dir up" name="overall">+</a>
							</div>
							<h3>Overall Appearance</h3>
							<ul class="stack button-group">
								<li><a class="button">Costume</a></li>
								<li><a class="button">Confidence</a></li>
								<li><a class="button">Appearance</a></li>
								<li><a class="button">Appropriateness</a></li>
							</ul>
						</div>
										
			        </div>
			        <div class="row" style="margin-top:15px;">
						<div class="small-6 columns">
							<h3>Notes</h3>
							<textarea id="routine_notes" style="height:140px;font-size:1.4rem;"></textarea>
						</div>
						<div class="small-1 columns">
							&nbsp;
						</div>
						<div class="small-5 columns">
							<h3>Your Score: <span style="color:#FFFFFF;" id="your_score">100</span></h3>
							<button id="main_save" class="button radius success" style="width:210px;text-shadow:1px 1px 1px #222;">Save</button>
							<button id="main_reset" class="button radius secondary" style="width:100px;margin-left:10px;text-shadow:1px 1px 1px #333;">Reset</button>
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
					do_settings();
					event.preventDefault();
				})
				
				//ROUTINE CLICK
				$(document).on("click","#routine_list li", function(){
					$('#routine_list li').removeClass("routine_list_active");
					$(this).addClass("routine_list_active");
					get_routine(global.tourdateid , $(this).attr("data-routineid") , $(this).attr("data-studioid") , $(this).attr("data-dispnumber"));
				});
				
				$('#sidebar_inner').niceScroll();
				$('#sidebar_inner').getNiceScroll().hide();
				
				//SCORE UP/DOWN
				var timeoutId = 0;
				$('#att_row div.score_wrap a.cat_dir').mousedown(function() {
					var cat = $(this).attr("name");
					var dir = $(this).hasClass("up") ? "up" : "down";
					var lim = parseInt($('#scores_'+cat).attr("data-limit"));
					var cur = parseInt($('#scores_'+cat).text());
					var news = 0;
					
					// insta-single-click
					if(dir == "up")
						news = (cur == lim ? cur : cur + 1);
					else
						news = (cur == 0 ? 0 : cur - 1);
					$('#scores_'+cat).text(news);
					
					do_your_score();
					
					// logic if button held
					timeoutId = setInterval(function(){
						
						cur = parseInt($('#scores_'+cat).text());
						if(dir == "up")
							news = (cur == lim ? cur : cur + 1);
						else
							news = (cur == 0 ? 0 : cur - 1);
						$('#scores_'+cat).text(news);
						
						do_your_score();
						
					}, 200);
					
				}).bind('mouseup mouseleave', function() {
					clearInterval(timeoutId);
				});
				
				
				//REFRESH
				$(document).on("click","#bottom_icon_refresh",function(){
					$('#routine_list').css("opacity",0.5);
					$('#main_inner').fadeOut("fast");
					refresh_routines();
				});
				
				//ATTRIBUTE SELECT
				$(document).on("click","#att_row ul li",function(){
					if(!($(this).children().hasClass("chosen")))
						$(this).children().addClass("chosen");
					else
						$(this).children().removeClass("chosen");
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
								window.location = "/";
							});
						});
					});
				});
				
				//RETURN TO SETTINGS
				$(document).on('click','#return_settings',function(){
					$('#settings_modal').foundation('reveal','close');
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
				});
				
				// MAIN RESET BTN
				$(document).on("click","#main_reset",function(){
					reset_scores();
					do_your_score();
					$('#att_row ul li a').removeClass("chosen");
					$('#routine_notes').val("");
				});
				
				
				
				// MAIN SAVE
				$(document).on("click","#main_save",function(){
					var ss = parseInt($('#scores_technique').text()) + "|" + parseInt($('#scores_performance').text()) + "|" + parseInt($('#scores_choreo').text()) + "|" + parseInt($('#scores_overall').text());
					var chosen = [];
					$('#att_row ul li a').each(function(){	
						if($(this).hasClass("chosen"))
							chosen.push($(this).text());
					});
					$.ajax({
						url: "AJAX/save.php",
						type: "POST",
						data: "nf=0&cg="+global.compgroup+"&sid="+global.studioid+"&rid="+global.routineid+"&tid="+global.tourdateid+"&jid="+global.judgeid+"&pos="+global.position+"&ss="+ss+"&c="+chosen.join("|")+"&n="+encodeURIComponent($('#routine_notes').val()),
						success: function(msg) {
							
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

<div id="settings_modal" class="reveal-modal tiny" data-reveal aria-labelledby="Sign Out" aria-hidden="true" role="dialog">
	<h3 id="modalTitle" style="color:#333333;">Return to setting selection?</h3>
	<div class="row" style="margin-top:15px;">
		<div class="small-5 small-centered columns">
			<a class="button expand radius" id="return_settings" style="width:100%;">Go</a>
		</div>
	</div>
	<a class="close-reveal-modal" aria-label="Close">&#215;</a>
</div>
	</body>
</html>