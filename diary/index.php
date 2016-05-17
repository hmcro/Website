<?php
	
	require_once 'config.php';
	
	$citizen_names = [
		"1234567" => "Luke", 
		"4755644" => "Mikel", 
		"4755643" => "Debbie", 
		"4755642" => "Weiyin", 
		"4755641" => "Carla", 
		"4755640" => "Neba", 
		"4755639" => "Rodrigo", 
		"4755680" => "Sara", 
		"4755645" => "Charlotte", 
		"4755646" => "Nina", 
		"4755647" => "Joseph", 
		"4755649" => "Lucie", 
		"4755684" => "Alexander", 
		"4755685" => "Sam", 
		"4755686" => "Talal", 
		"4755687" => "Gareth", 
		"4755650" => "Naama", 
		"4755651" => "Andrew", 
		"4755652" => "Mette", 
		"4755653" => "Peter", 
		"4755654" => "Ted", 
		"4755655" => "Lluisa", 
		"4755656" => "Joseph", 
		"4755657" => "Nick", 
		"4755658" => "Ankkit", 
		"4755659" => "Thea", 
		"4755660" => "Brian", 
		"4755661" => "Ivy", 
		"4755662" => "Ruko", 
		"4755663" => "Kaidi", 
		"4755664" => "Shamik", 
		"4755665" => "emily", 
		"4755667" => "Joshua", 
		"4755668" => "Laura", 
		"4755669" => "Cosimo", 
		"4755670" => "Anastasia", 
		"4755638" => "Vanea", 
		"4755671" => "Chris", 
		"4755672" => "Janna", 
		"4755679" => "geoffrey", 
		"4755678" => "Kim-Leigh", 
		"4755677" => "Sam", 
		"4755676" => "Annelise", 
		"4755675" => "Vladimir", 
		"4755681" => "Amal", 
		"4755682" => "Kate", 
		"4755683" => "Noel", 
		"4755688" => "Sarah"
	];
	
	if ( isset( $_COOKIE['CID']) ) {
		$cid = $_COOKIE['CID'];
		$citizen = ' for ' . $citizen_names[$cid];
	}
	
	else if ( isset($_GET['CID']) && array_key_exists($_GET['CID'], $citizen_names) ){
		$cid = $_GET['CID'];
		$citizen = ' for ' . $citizen_names[$cid];
		setcookie('CID', $cid);
	}

	$date_picker_options = "";
	
	// Set timezone
	date_default_timezone_set('Europe/London');	
	
	// Create day picker
	$yesterday = new DateTime();
	$yesterday->modify( '-1 day' );
	
	$begin = new DateTime();
	$begin->modify( '-7 day' );
	$end = new DateTime();
	
	$interval = new DateInterval('P1D');
	$daterange = new DatePeriod($begin, $interval, $end);
	
	foreach($daterange as $date){		
		if ($date == $yesterday) {
			$date_picker_options .= '<option selected value="'.$date->format("Y-m-d").'">Yesterday</option>';
		} else {
			$date_picker_options .= '<option value="'.$date->format("Y-m-d").'">On '.$date->format("l F jS").'</option>';
		}
	}
	
	
	// Create 24 hours picker	
	$hour_options = "";
	$start = (new DateTime())->setTime(1,0);
	$end = (new DateTime())->setTime(1,0);
	$end->modify('1 day');
	$interval = new DateInterval('PT1H');
	$daterange = new DatePeriod($start, $interval, $end);
	
	foreach($daterange as $date){
		$selected = ($date->format(h) == 8) ? 'selected' : '';
		$hour_options .= '<option '.$selected.' value="'. $date->format('G') .'">'. $date->format('ga') .'</option>';
	}
	
		
	/// Create a number picker
	$num_options = "";
	for ($i = 1; $i <= 9; $i++) {
		$selected = ($i == 1) ? 'selected' : '';
		$num_options .= '<option '.$selected.' value="'.$i.'">'. $i .'</option>';
	}
	
	$personality_questions = array(
		"feel like I'm on an emotional roller coaster",
		"feel uneasy in situations where I am expected to display physical affection",
		"present myself in ways that are very different from who I really am",
		"accurately estimate the amount of time it will take me to complete a task",
		"procrastinate on matters relevant to work",
		"break promises",
		"miss deadlines",
		"get upset when things don't go my way",
		"make comments that I wish I could take back",
		"procrastinate",
		"make to-do lists",
		"contemplate all the pros and cons before making a decision",
		"lose important things or documents",
		"second-guess my decisions",
		"calm myself down when I'm under stress",
		"need someone to tell me that I have done a good job in order to feel good about my work",
		"like to attend gatherings where I can meet new people",
		"do favors for other people without being asked",
		"like to talk about myself when I meet someone new",
		"think of myself as a private person",
		"seek the company of others",
		"enjoy exploring new places",
		"like being me",
		"get more things done in a day than the average person",
		"pride myself on being different",
		"say I am good at thinking 'outside the box'",
		"go out of my way to help others",
		"get angry",
		"lose my temper"
	);
	
	$db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

	if($db->connect_errno > 0){
	    die('Unable to connect to database [' . $db->connect_error . ']');
	}
	
	$sql = "SELECT CID FROM diary WHERE CID=$cid";
	$result = $db->query($sql);
	$n = $result->num_rows;
	$db->close();
	
// 	echo("already $n entries found");
	
	// if all quetions have been asked, just pick a random
	if ($n >= count($personality_questions)) {
		$n = rand( 0, count($personality_questions) );
	}	
	$personality_q = $personality_questions[$n];
	
	
?><!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="description" content="Citizen diary for a fictional government service, as part of a speculative design proposal by Luke Sturgeon.">
		<meta name="keywords" content="speculative design, critical design, future of government, research, design research, ethnography, performance, walk, gps, location, housing, accomodation, smart city, future city.">
		<meta name="author" content="Luke Sturgeon">		
		<meta name='viewport' content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no' />
	
		<title>Citizen Diary</title>
		
		<link rel="apple-touch-icon" href="apple-touch-icon.png">
		<link rel="icon" href="../favicon.ico">
		<link rel="home" href="index.php" />
		<link rel="stylesheet" href="../css/diary.css">
	
		<!--[if lt IE 9]>
		<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->
		
		<script src="http://code.jquery.com/jquery-2.2.2.min.js"></script>
		
	</head>
	<body>
		
		<div class="main_wrapper">
			
			<main id="content">			
			
				<img src="../img/HMCRO_LOGO_WHITE.svg" alt="HM Citizen Rotation Office Logo" class="logo" />
				
				<h1>Citizen diary<?php echo $citizen; ?>.</h1>
				
				<?php if (isset($cid)) { ?>
				
				<form id="diary_entry_form" action="diary_process.php" method="post">
					
					<input type="hidden" name="CID" value="<?php echo $cid;?>">
					<input type="hidden" name="PERSONALITY_Q" value="<?php echo $personality_q; ?>">
				
					<p><select name="DAY"><?php echo $date_picker_options; ?></select> I woke at <select name="WAKETIME"><?php echo $hour_options; ?></select> and the first thing I thought about was <input type="text" value="" name="FTHOUGHT">. For my first meal I ate <input type="text" value="" name="FMEAL">, and for my last meal I ate <input type="text" value="" name="LMEAL">. Throughout the day I spent time at <select name="NLOCATIONS"><?php echo $num_options; ?></select> locations in the city, including <input type="text" value="" name="LOCATIONS">, and I probably spent around <select name="TRAVELTIME"><?php echo $num_options; ?></select> hours travelling between these locations. To relax I <input type="text" value="" name="RELAX"> with <input type="text" value="" name="COMPANION"> and when I decided to go to sleep it was <select name="SLEEPTIME"><?php echo $hour_options; ?></select>. I <select name="PERSONALITY_A"><option value="quite often">quite often</option><option value="often">often</option><option value="occasionally">occasionally</option><option value="rarely">rarely</option><option value="never">never</option></select> <?php echo $personality_q; ?>.</p>
				
					<input type="submit" value="Submit entry" name="submit" class="button">
				
				</form>
								
				<?php } else { ?>
				
				<p>Your Citizen ID cannot be identified. Please use the link from your email which includes your Citizen ID at the end.</p>
				
				<?php }; ?>
				
			</main>
			
		</div>
		
		<footer class="fixed_footer">
			<span id="copy">HM Citizen Rotation Office</span>
			<span id="info"><?php if(isset($cid)) {?><a href="diary_reset.php">Not <?php echo($citizen_names[$cid]); ?></a> | <?php }; ?><a href="mailto:office@hmcro.org">Contact</a></span>
		</footer>
		
		<script src="https://code.jquery.com/jquery-2.2.2.min.js" integrity="sha256-36cp2Co+/62rEAAYHLmRCPIych47CvdM+uTBJwSzWjI=" crossorigin="anonymous"></script>
		<script>
			$(function(){
				
				$("#diary_entry_form").submit(function(event){
					
					$('input[name="FTHOUGHT"]').removeClass("error");
					$('input[name="FMEAL"]').removeClass("error");
					$('input[name="LMEAL"]').removeClass("error");
					$('input[name="LOCATIONS"]').removeClass("error");
					$('input[name="RELAX"]').removeClass("error");
					$('input[name="COMPANION"]').removeClass("error");
					
					// validate the answers
					if ($('input[name="FTHOUGHT"]').val() == "") {
						$('input[name="FTHOUGHT"]').addClass("error");
						alert("Please write something about your first thought.");
						event.preventDefault();
						return;
					}
					
					if ($('input[name="FMEAL"]').val() == "") {
						$('input[name="FMEAL"]').addClass("error");
						alert("Please write something about your first meal.");
						event.preventDefault();
						return;
					}
					
					if ($('input[name="LMEAL"]').val() == "") {
						$('input[name="LMEAL"]').addClass("error");
						alert("Please write something about your last meal.");
						event.preventDefault();
						return;
					}
					
					if ($('input[name="LOCATIONS"]').val() == "") {
						$('input[name="LOCATIONS"]').addClass("error");
						alert("Please write something about the locations you visited.");
						event.preventDefault();
						return;
					}
					
					if ($('input[name="RELAX"]').val() == "") {
						$('input[name="RELAX"]').addClass("error");
						alert("Please write something about how you relaxed.");
						event.preventDefault();
						return;
					}
					
					if ($('input[name="COMPANION"]').val() == "") {
						$('input[name="COMPANION"]').addClass("error");
						alert("Please write something about who you spend time with.");
						event.preventDefault();
						return;
					}
					
				});
				
			})
		</script>
		
		<script>
		(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
		(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
		m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
		})(window,document,'script','//www.google-analytics.com/analytics.js','ga');
		
		ga('create', 'UA-46847953-4', 'auto');
		ga('send', 'pageview');		
		</script>
	</body>
</html>