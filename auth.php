<?php
	//Start session
	session_start();
	
	//Check whether the session variable SESS_MEMBER_ID is present or not
	if(!isset($_SESSION['SESSION_ID']) || (trim($_SESSION['SESSION_EMPID']) == '')|| (trim($_SESSION['SESSION_MAIL']) == '')|| (trim($_SESSION['SESSION_NAME']) == '')) {
		header("location: login.php");
		exit();
	}
?>
