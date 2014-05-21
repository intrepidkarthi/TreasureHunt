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
	
	$query = "insert into symlogger (ip_addr, emp_id, ref_url, ref_detail, login_out) values('".$ip_addr."','".$emp_id."','".$file_name."','".$ref_details."',1 )";
	
	
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

//Unset the variables stored in session
	unset($_SESSION['SESSION_ID']);
	unset($_SESSION['SESSION_EMPID']);
	unset($_SESSION['SESSION_NAME']);
	unset($_SESSION['SESSION_MAIL']);

session_destroy();

?>
<!doctype html>  
    <html lang="en">  
    <head>  
      <meta charset="utf-8">  
      <title>TreasureHunt :: LogOut</title>  
      <meta name="description" content="Lets start hunting">  
      <meta name="author" content="">  
      <link href="style.css" rel="stylesheet" type="text/css" media="screen">
	<link href="login.css" rel="stylesheet" type="text/css" />
</head>
</body>
<p align="center">&nbsp;</p>
<h4 align="center" class="err">You have been logged out.</h4>
<p align="center">Click here to <a href="login.php">Login again</a></p>
<br><br>
<center>  <img src="sym.png"/></center>
</body>
</html>
