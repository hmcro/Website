<?php

	require_once 'config.php';

	if ( isset( $_COOKIE['CID']) ) {
		$cid = $_COOKIE['CID'];
	}

	if ( isset($_GET['CID']) ){
		$cid = $_GET['CID'];
		setcookie('CID', $cid);
	}

	// connect to db
	$db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

	if($db->connect_errno > 0){
			die('Unable to connect to database [' . $db->connect_error . ']');
	}

	// GET THE USERNAME
	$sql = "SELECT FNAME FROM `names` WHERE `CID` = '$cid'";
	$result = $db->query($sql);
	$citizen_fname = $result->fetch_object()->FNAME;
	$result->free();

	$sql = "SELECT *, DATE_FORMAT(DAY,'%a %b %D') AS niceDay
	FROM diary
	LEFT JOIN names ON diary.CID = names.CID
	WHERE diary.CID = '$cid'
	ORDER BY DAY DESC";
	$entries = $db->query($sql);

	// close DB connection
	$db->close();


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

				<a href="/diary"><img src="../img/HMCRO_LOGO_WHITE.svg" alt="HM Citizen Rotation Office Logo" class="logo" /></a>

				<h1>Citizen diary for <?php echo($citizen_fname); ?></h1>

				<?php if (isset($cid)) {

					while ($row = $entries->fetch_assoc()) {
						printf("<hr/><p>On <u>%s</u> I woke at <u>%s</u> and the first thing I thought about was <u>%s</u>. For my first meal I ate <u>%s</u>, and for my last meal I ate <u>%s</u>. Throughout the day I spent time at <u>%s</u> locations in the city, including <u>%s</u>, and I probably spent around <u>%s</u> hours travelling between these locations. To relax I <u>%s</u> with <u>%s</u> and when I decided to go to sleep it was <u>%s</u>. I <u>%s %s</u>.</p>",
							$row['niceDay'],
							$row['WAKETIME'],
							$row['FTHOUGHT'],
							$row['FMEAL'],
							$row['LMEAL'],
							$row['NLOCATIONS'],
							$row['LOCATIONS'],
							$row['TRAVELTIME'],
							$row['RELAX'],
							$row['COMPANION'],
							$row['SLEEPTIME'],
							$row['PERSONALITY_A'],
							$row['PERSONALITY_Q']
						);
					} ?>

					<hr>

					<input type="submit" value="Submit another entry" name="submit" class="button">

				<?php } else { ?>

				<p>Your Citizen ID cannot be identified. Please use the link from your email which includes your Citizen ID at the end.</p>

				<?php }; ?>

			</main>

		</div>

		<footer class="fixed_footer">
			<span id="copy">HM Citizen Rotation Office</span>
			<span id="info"><?php if(isset($cid)) {?><a href="diary_reset.php">Not <?php echo($citizen_fname); ?></a> | <?php }; ?><a href="mailto:office@hmcro.org">Contact</a></span>
		</footer>

		<script>

			$(function(){
				$('input[name=submit]').on('click',function(){
					window.location = "/diary";
				});
			});

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
