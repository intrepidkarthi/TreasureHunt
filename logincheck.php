<?php
	//Start session
	session_start();
	
	//Include database connection details
	require_once('config.php');
	
	//Array to store validation errors
	$errmsg_arr = array();
	
	//Validation error flag
	$errflag = false;
	
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
	
	//Sanitize the POST values
	$login = clean($_POST['login']);
	$password = clean($_POST['password']);
	
	//Input Validations
	if($login == '') {
		$errmsg_arr[] = 'Login ID missing';
		$errflag = true;
	}
	if($password == '') {
		$errmsg_arr[] = 'Password missing';
		$errflag = true;
	}
	
	//If there are input validations, redirect back to the login form
	if($errflag) {
		$_SESSION['ERRMSG_ARR'] = $errmsg_arr;
		session_write_close();
		header("location: login.php");
		exit();
	}
	
	//Create query
	$qry="SELECT * FROM sympeople WHERE emp_id='$login' AND emp_pwd='".md5($_POST['password'])."'";
	$result=mysql_query($qry);
	
	//Check whether the query was successful or not
	if($result) {
		if(mysql_num_rows($result) == 1) {
			//Login Successful
			session_regenerate_id();
			$member = mysql_fetch_assoc($result);
			$_SESSION['SESSION_ID'] = $member['emp_id'];
			$_SESSION['SESSION_NAME'] = $member['emp_name'];	
			$_SESSION['SESSION_MAIL'] = $member['emp_email'];
			
			session_write_close();

			//Logic for redirecting user to the appropriate level
			$level = $member['emp_level'];	
			
			//level fetching query
			$level_qry = "SELECT * FROM ans where level=".$level;
			$your_level = mysql_query($level_qry);

			//checking query status info
			if($user_level){
				if(mysql_num_rows($user_level) == 1){
				$redir_user = mysql_fetch_assoc($user_level);
				$redir_url = $redir_user['url'];

				header("location: ".$redir_url.".php");
				exit();

				}

			}			

			
		}else {
			//Login failed
			header("location: login.php");
			exit();
		}
	}else {
		die("Query failed");
	}
?>
