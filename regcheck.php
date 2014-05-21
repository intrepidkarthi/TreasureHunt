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
		die('Please inform to admin. Failed to connect to server: ' . mysql_error());
	}
	
	//Select database
	$db = mysql_select_db(DB_DATABASE);
	if(!$db) {
		die("Unable to select contest database");
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
	$empid = clean($_POST['empid']);
	$empname = clean($_POST['empname']);
	$empemail = clean($_POST['empemail']);
	$password = clean($_POST['password']);
	$cpassword = clean($_POST['cpassword']);
	
	//Input Validations
	if($empid == '' || $empid > 999999 ) {
		$errmsg_arr[] = 'Employee ID is missing or wrond ID is given';
		$errflag = true;
	}
	if($empname == ''||preg_match('/^[a-zA-Z]{2,40}$/', $empname)) {
		$errmsg_arr[] = 'Employee name is missing or very short/long name is given';
		$errflag = true;
	}
	$arr = explode('@', $empemail);
//	echo strlen($empemail);
	if(!($arr[1]=='symantec.com'&&strlen($empemail)>14&&strlen($empemail)<40)) {
		$errmsg_arr[] = 'Employee email ID is missing or check the domain name once again or it is too long';
		$errflag = true;
	}

	if($password == ''||!ctype_alnum($password)|| !(strlen($password)>5)||!(strlen($password)<16)) {
		$errmsg_arr[] = 'Password is missing or it contains symbols or special characters';
		$errflag = true;
	}
	
	if( strcmp($password, $cpassword) != 0 ) {
		$errmsg_arr[] = 'Please check once again whether both the passwords matches';
		$errflag = true;
	}
	
	//Check for duplicate login ID
	if($login != '') {
		$qry = "SELECT * FROM sympeople WHERE emp_id=".$empid." or emp_email='".$empemail."'";
		$result = mysql_query($qry);
		if($result) {
			if(mysql_num_rows($result) > 0) {
				$errmsg_arr[] = 'Employee details already in use';
				$errflag = true;
			}
			@mysql_free_result($result);
		}
		else {
			die("Query failing to fetch value for duplicate checking");
		}
	}
	
	//If there are input validations, redirect back to the registration form
	if($errflag) {
		$_SESSION['ERRMSG_ARR'] = $errmsg_arr;
		session_write_close();
		header("location: register.php");
		exit();
	}

	//Create INSERT query
	$qry = "INSERT INTO sympeople(emp_id,emp_name, emp_pwd, emp_email, emp_level) VALUES ($empid,'$empname','".md5($password)."','$empemail',0)";
	$result = @mysql_query($qry);
	
	//Check whether the query was successful or not
	if($result) {
		header("location: index.php");
		exit();
	}else {
		die("Query failed while registration process");
	}
?>
