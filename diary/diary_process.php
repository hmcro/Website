<?php
	
	require_once 'config.php';
	
	if (isset($_POST['CID'])) {
				
		$db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

		if($db->connect_errno > 0){
		    die('Unable to connect to database [' . $db->connect_error . ']');
		}
					
		$cid = filter_var( trim( $_POST['CID'] ), FILTER_SANITIZE_NUMBER_INT );
		$day = trim( $_POST['DAY'] );
		$waketime = filter_var( trim( $_POST['WAKETIME'] ), FILTER_SANITIZE_NUMBER_INT );
		$fthought = filter_var( trim( $_POST['FTHOUGHT'] ), FILTER_SANITIZE_MAGIC_QUOTES );
		$fmeal = filter_var( trim( $_POST['FMEAL'] ), FILTER_SANITIZE_STRING );
		$lmeal = filter_var( trim( $_POST['LMEAL'] ), FILTER_SANITIZE_STRING );
		$nlocations = filter_var( trim( $_POST['NLOCATIONS'] ), FILTER_SANITIZE_NUMBER_INT );
		$locations = filter_var( trim( $_POST['LOCATIONS'] ), FILTER_SANITIZE_STRING );
		$traveltime = filter_var( trim( $_POST['TRAVELTIME'] ), FILTER_SANITIZE_NUMBER_INT );
		$relax = filter_var( trim( $_POST['RELAX'] ), FILTER_SANITIZE_MAGIC_QUOTES );
		$companion = filter_var( trim( $_POST['COMPANION'] ), FILTER_SANITIZE_MAGIC_QUOTES );
		$sleeptime = filter_var( trim( $_POST['SLEEPTIME'] ), FILTER_SANITIZE_NUMBER_INT );
		$personality_a = filter_var( trim( $_POST['PERSONALITY_A'] ), FILTER_SANITIZE_STRING );
		$personality_q = filter_var( trim( $_POST['PERSONALITY_Q'] ), FILTER_SANITIZE_MAGIC_QUOTES );
		
		$sql = "INSERT INTO diary (CID, DAY, WAKETIME, FTHOUGHT, FMEAL, LMEAL, NLOCATIONS, LOCATIONS, TRAVELTIME, RELAX, COMPANION, SLEEPTIME, PERSONALITY_A, PERSONALITY_Q) VALUES ('$cid', '$day', '$waketime', '$fthought', '$fmeal', '$lmeal', '$nlocations', '$locations', '$traveltime', '$relax', '$companion', '$sleeptime', '$personality_a', '$personality_q')";
		
		if ($db->query($sql) === TRUE) {
		    header( 'Location: diary_success.html' );
		} else {
		    $result =  "Error: " . $sql . "<br>" . $db->error . '.';
		}
		
		$db->close();
		
	}
	else {
		header( 'Location: diary.php' );
	}
	
?>