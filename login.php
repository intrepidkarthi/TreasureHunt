<?php
	//Start session
	session_start();

	logger();

	function logger()
	{

	$ip_addr = $_SERVER['REMOTE_ADDR']; 
	($_SESSION['SESSION_EMPID'])?$emp_id = $_SESSION['SESSION_EMPID']:$emp_id = 1;
	$file_acc = explode("/", $_SERVER['HTTP_REFERER']); 
	$file_name = $file_acc[4];
	$ref_details = $_SERVER['HTTP_USER_AGENT'];
	
	$query = "insert into symlogger (ip_addr, emp_id, ref_url, ref_detail, login_out) values('".$ip_addr."','".$emp_id."','".$file_name."','".$ref_details."',0 )";
	
	
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

	mysql_query($query);
	if(!$query)
		die("Issue in SymHunt logger");
	unset($query);
	}

		 

	
		
		
		
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
	$login = clean($_POST['empid']);
	$password = clean($_POST['password']);
	echo $password;

	//Input Validations
	if($login == ''||$password == ''||!is_numeric($login)) {
		$errmsg_arr[] = $login.'   '.$password.'Employee ID/Password is missing/wrong';
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
	//echo $qry;
	$result=mysql_query($qry);
	
	//Check whether the query was successful or not
	if($result) {
		if(mysql_num_rows($result) == 1) {
			//Login Successful
			session_regenerate_id();
			$member = mysql_fetch_assoc($result);
			$_SESSION['SESSION_ID'] =  session_id();
			$_SESSION['SESSION_EMPID'] = $member['emp_id'];
			$_SESSION['SESSION_NAME'] = $member['emp_name'];	
			$_SESSION['SESSION_MAIL'] = $member['emp_email'];
			echo $_SESSION['SESSION_ID'];
			

			//Logic for redirecting user to the appropriate level
			$level = $member['emp_level'];	
			
			//level fetching query
			$level_qry = "SELECT * FROM ans where level=".$level;
			//echo $level_qry;
			$user_level = mysql_query($level_qry);

			//checking query status info
			if($user_level){
				if(mysql_num_rows($user_level) == 1){
				$redir_user = mysql_fetch_assoc($user_level);
				$redir_url = $redir_user['url'];
				echo $redir_url;
				
				header("location: ".$redir_url);
				exit();

				}

			}			

			
		}else {
			//Login failed
			$_SESSION['error'] = "Employee ID or Password is wrong";
			session_write_close();
			header("location: login.php");
			exit();
		}
	}else {
		die("Query failed");
	}
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
<title>TreasureHunt :: LogIn</title>
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
		if(isset($_SESSION['error']))echo '<li>',$_SESSION['error'],'</li>';
		echo '</ul>';
		unset($_SESSION['ERRMSG_ARR']);
	}
		


?>
<form id="loginForm" name="loginForm" method="post" action="">
  <table width="310" border="0" align="center" cellpadding="2" cellspacing="0">
    <tr>
      <td width="122"><b>Employee ID</b></td>
      <td width="188"><input name="empid" type="text" class="textfield" maxlength=20 id="empid" value="<?php echo $_SESSION['SESSION_EMPID'];  ?>" /></td>
    </tr>
    <tr>
      <td width="122"><b>Password</b></td>
      <td width="188"><input name="password" type="password" maxlength=20 class="textfield" id="password" /></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><input type="submit" name="Submit" value="Login" /></td>
    </tr>
 <tr>
      <td><a href="mailto:intrepidkarthi@gmailcom;?Subject=Issue_in_TreasureHunt_login">Report Issue</a></td>
      <td><a href="register.php">Register here</a></td>
    </tr>
   
   
  </table>

<br><br>
<center>  <img src="sym.png"/></center>
</form>
</body>
</html>
