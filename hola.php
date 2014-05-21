 <?php
	require_once('auth.php');

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
$qry = "select emp_level from sympeople where emp_id=".$_SESSION['SESSION_EMPID'];
$res = mysql_query($qry);

if($res)
{

	$temp = mysql_fetch_assoc($res);

	if($temp['emp_level']!=1){
	//level fetching query
			$level_qry = "SELECT * FROM ans where level=".$temp['emp_level'];
			//echo $level_qry;
			$user_level = mysql_query($level_qry);

			//checking query status info
			if($user_level){
				if(mysql_num_rows($user_level) == 1){
				$redir_user = mysql_fetch_assoc($user_level);
				$redir_url = $redir_user['url'];
						
				header("location: ".$redir_url);
				exit();

					}}
				}

}

?>
<!doctype html>  
    <html lang="en">  
    <head>  
      <meta charset="utf-8">  
      <title>TreasureHunt</title>  
      <meta name="description" content="Lets start hunting">  
      <meta name="author" content="">  
      <link href="style.css" rel="stylesheet" type="text/css" media="screen">
 
  <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>

<script type="text/javascript" src="./js/ddaccordion.js"></script>


<script type="text/javascript">


ddaccordion.init({
	headerclass: "silverheader", 
	contentclass: "submenu", 
	revealtype: "mouseover",
	mouseoverdelay: 200, 
	collapseprev: true, 
	defaultexpanded: [0], 
	onemustopen: true, 
	animatedefault: true, 
	persiststate: true, 
	toggleclass: ["", "selected"], 
	togglehtml: ["", "", ""], 
	animatespeed: "fast", 
	
})


</script>
    
    </head>  
    <body bgcolor="black">

<table width="90%" align="center" style="height: 100%;" >
<tr>

<!-- ============ HEADER SECTION ============== -->
<td width="90%"  style="height: 150px;" >

<h1>SymHunt</h1>


</td></tr>

<tr>
<!-- ============ LEFT COLUMN  ============== -->
<td width="80%" height="650px" valign="top" >

<table  height="500px" width="70%">
<tr>
<td align="center">
<img src="./img/whatishappening.jpeg">
</td></tr>
<tr>
<td align="center">
<form method="post" action="initiate.php">
<input name="answer" type="text" class="textfield" id="answer" maxlength=25 />
<input name="submit" type="submit" value="Go"/>
</form>
</td></tr>
</table>

<!--did you read rule no:5 ? -->
</td>


<!-- ============ RIGHT COLUMN  ============== -->
<td width="20%" valign="top" >
<div class="applemenu">
<div class="silverheader"><a href="#">Rule #0</a></div>
	<div class="submenu">
	Use of a HTML5 supporting browser will give you better performance.<br />
	</div>
<div class="silverheader"><a href="#">Rule #1</a></div>
	<div class="submenu">
	This is an individual event. Always try to be ahead of your colleagues. Then you have more possibilities to finish off.<br />
	</div>
<div class="silverheader"><a href="#" >Rule #2</a></div>
	<div class="submenu">
	Always try reaching the next level by using the given clues. Be aware of traps.<br />
	</div>
<div class="silverheader"><a href="#">Rule #3</a></div>
	<div class="submenu">
	Clues can be found in the image, title of the page, sourcecode, URL, etc.,<br />
	</div>
<div class="silverheader"><a href="#">Rule #4</a></div>
	<div class="submenu">
	Clue may be found even in the comments or anywhere else. Nobody knows.<br />
	
	</div>
<div class="silverheader"><a href="#">Rule #5</a></div>
	<div class="submenu">
	Google is your close friend for leading you to the next level. But your mind is faster than a search engine. <br />
	</div>	
<div class="silverheader"><a href="#">Rule #6</a></div>
	<div class="submenu">
	It is a maze, you may go ahead for subsequent levels to reach a dead end.<br />
	</div>	
<div class="silverheader"><a href="#">Rule #7</a></div>
	<div class="submenu">
	Don't use cCAPS, u_n_d_e_r_s_c_o_r_e_s and s  p  a  c  e  s.<br />
	</div>		
<div class="silverheader"><a href="#">Your current Level #1</a></div>
	
<div class="silverheader"><a href="logout.php">LogOut</a></div>
			

		

</div>

</td>

</tr>

<!-- ============ FOOTER SECTION ============== -->
<tr>
<td colspan="2" align="center" height="20" ><p style="color:rgb(243,208,10);">Chennai</p></td>

</tr>
</table>  
   </center>
    </body>  
    </html>  


