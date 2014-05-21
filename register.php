<?php

	//Start session
	session_start();
	
if(isset($_POST['empid']))
{
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
	if($empid == '' || !is_numeric($empid) ) {
		$errmsg_arr[] = 'Employee ID is missing or wrond ID is given. It is a number';
		$errflag = true;
	}
	
		
	if($empname == ''||!preg_match('/^[a-zA-Z]{2,40}$/', $empname)) {
		$errmsg_arr[] = 'Employee name is missing or very short/long name is given';
		$errflag = true;
	}
	
		
	$arr = explode('@', $empemail);
//	echo strlen($empemail);
	if(!($arr[1]=='symantec.com'&&strlen($empemail)>14&&strlen($empemail)<40)) {
		$errmsg_arr[] = 'Employee email ID is missing or domain name should be symantec.com';
		$errflag = true;
	}
	
		

	if($password == ''||!ctype_alnum($password)|| !(strlen($password)>5)||!(strlen($password)<16)) {
		$errmsg_arr[] = 'Password accepts letters and numbers between the length6-15';
		$errflag = true;
	}
	
	if( strcmp($password, $cpassword) != 0 ) {
		$errmsg_arr[] = 'Please check once again whether both the passwords matches';
		$errflag = true;
	}
	
	//Check for duplicate login ID
	if($empid != '' && is_numeric($empid)) {
		$_SESSION['SESSION_ID'] = $empid;
		$_SESSION['SESSION_NAME'] = $empname;
		$_SESSION['SESSION_MAIL'] = $empemail;
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
	$qry = "INSERT INTO sympeople(emp_id,emp_name, emp_pwd, emp_email, emp_level) VALUES ($empid,'$empname','".md5($password)."','$empemail',1)";
	$result = @mysql_query($qry);
	
	//Check whether the query was successful or not
	if($result) {
		unset($result);
		unset($qry);
		echo "Success!";
		header("location: login.php");
		exit();
	}else {
		die("Query failed while registration process");
	}
}
?>
<!doctype html>  
    <html lang="en">  
    <head>  
      <meta charset="utf-8">  
      <title>TreasureHunt :: Registration</title>  
      <meta name="description" content="Lets start hunting">  
      <meta name="author" content="Symantec Corporation, Chennai"> 
<link href="login.css" rel="stylesheet" type="text/css" />
</head>
<body>
<h1 align="center">TreasureHunt</h1><br><br><br>
<?php
	if( isset($_SESSION['ERRMSG_ARR']) && is_array($_SESSION['ERRMSG_ARR']) && count($_SESSION['ERRMSG_ARR']) >0 ) {
		echo '<ul class="err">';
		foreach($_SESSION['ERRMSG_ARR'] as $msg) {
			echo '<li>',$msg,'</li>'; 
		}
		echo '</ul>';
		unset($_SESSION['ERRMSG_ARR']);
	}

?>
<form id="loginForm" name="loginForm" method="post" action="">


  <table width="300" border="0" align="center" cellpadding="2" cellspacing="0">
    <tr>
      <th>Employee ID </th>
      <td><input name="empid" type="text" class="textfield" id="empid" maxlength=20 value="<?php echo $_SESSION['SESSION_ID'];  ?>" /></td>
    </tr>
    <tr>
      <th>Name </th>
      <td><input name="empname" type="text" class="textfield" id="empname" maxlength=35 value="<?php echo $_SESSION['SESSION_NAME']; ?>" /></td>
    </tr>
    <tr>
      <th width="124">Email</th>
      <td width="168"><input name="empemail" type="text" class="textfield" maxlength=60 id="empemail" value="<?php echo $_SESSION['SESSION_MAIL']; ?>" /></td>
    </tr>
    <tr>
      <th>Password</th>
      <td><input name="password" type="password" class="textfield" id="password" maxlength=20 /></td>
    </tr>
    <tr>
      <th>Confirm Password </th>
      <td><input name="cpassword" type="password" class="textfield" id="cpassword"  maxlength=20 /></td>
    </tr>
    <tr>
      <td><a href="mailto:intrepidkarthi@gmail.com;?Subject=Issue_in_TreasureHunt_registration">Report Issue</a></td>
      <td><input type="submit" name="Submit" value="Register" /></td>
    </tr>
  </table>

<br><br>
<center>  <img src="sym.png"/></center>
</form>
</body>
</html>

<?php
//destroy session
session_destroy();
?>
