<?php
//session initiating
session_start();

//Authentication
require_once('auth.php');

//Include database connection details
require_once('config.php');

//Connect to mysql server
	$link = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);
	if(!$link) {
		die('Failed to connect to server: ' . mysql_error());
	}
	
	//Select database
	$db = mysql_select_db(DB_DATABASE);
	if(!$db) {
		die("Unable to select database");
	}
	
	//Function to sanitize values received from the form. Prevents SQL injection
	function clean($str) {
		$str = @trim($str);
		if(get_magic_quotes_gpc()) {
			$str = stripslashes($str);
		}
		return mysql_real_escape_string($str);
	}

$val = clean($_POST["answer"]);



if($val!=NULL){
		//Check whether the query was successful or not
		$ansqry = "SELECT * FROM ans WHERE level = 2";
		$myres = mysql_query($ansqry);
	
		if(mysql_num_rows($myres)==1) {
				$mem = mysql_fetch_assoc($myres);
				if($mem['answ'] == md5($val))
				{
					$updqry = "Update sympeople set emp_level = 3 where emp_id =".$_SESSION['SESSION_EMPID'];
					$res = mysql_query($updqry);
					if($res){
					unset($val);unset($ansqry);unset($mem);
					@mysql_free_result($myres);
					header("location: dolldance.php");
					exit();
				}}
		else
					header("location: symantec.php");
			
			
		}
		else {
			die("Query failing to fetch value from DB. Please contact contest admin");
		}
}

else
	header("location: treasurehunt.php");


?>


