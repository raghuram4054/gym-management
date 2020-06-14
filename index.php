<?php @session_start();  ?> 
<?php require_once('Connections/localhost.php'); ?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

$colname_Recordset1 = "-1";
if (isset($_SESSION['MM_username'])) {
  $colname_Recordset1 = $_SESSION['MM_username'];
}
mysql_select_db($database_localhost, $localhost);
$query_Recordset1 = sprintf("SELECT username FROM admin_tbl WHERE username = %s", GetSQLValueString($colname_Recordset1, "text"));
$Recordset1 = mysql_query($query_Recordset1, $localhost) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);
?>
<?php
// *** Validate request to login to this site.
if (!isset($_SESSION)) {
  session_start();
}

$loginFormAction = $_SERVER['PHP_SELF'];
if (isset($_GET['accesscheck'])) {
  $_SESSION['PrevUrl'] = $_GET['accesscheck'];
}

if (isset($_POST['username'])) {
  $loginUsername=$_POST['username'];
  $password=$_POST['password'];
  $MM_fldUserAuthorization = "";
  $MM_redirectLoginSuccess = "pages/home.php";
  $MM_redirectLoginFailed = "login.php";
  $MM_redirecttoReferrer = false;
  mysql_select_db($database_localhost, $localhost);
  
  $LoginRS__query=sprintf("SELECT username, password FROM admin_tbl WHERE username=%s AND password=%s",
    GetSQLValueString($loginUsername, "text"), GetSQLValueString($password, "text")); 
   
  $LoginRS = mysql_query($LoginRS__query, $localhost) or die(mysql_error());
  $loginFoundUser = mysql_num_rows($LoginRS);
  if ($loginFoundUser) {
     $loginStrGroup = "";
    
	if (PHP_VERSION >= 5.1) {session_regenerate_id(true);} else {session_regenerate_id();}
    //declare two session variables and assign them
    $_SESSION['MM_Username'] = $loginUsername;
    $_SESSION['MM_UserGroup'] = $loginStrGroup;	      

    if (isset($_SESSION['PrevUrl']) && false) {
      $MM_redirectLoginSuccess = $_SESSION['PrevUrl'];	
    }
    header("Location: " . $MM_redirectLoginSuccess );
  }
  else {
    header("Location: ". $MM_redirectLoginFailed );
  }
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Login</title>
<link href="css/style.css" rel="stylesheet" type="text/css"  />
<style type="text/css">
p {
	font-size: 18px;
	color: #000;
	text-align: left;
}
body,td,th {
	font-size: 18px;
	color: #000;
}
h1,h2,h3,h4,h5,h6 {
	font-family: Arial, Helvetica, sans-serif;
}
</style>
 
  
</style>
 
</head>

<body>
<div id="head">

<div id="head2"> </div>
<div id="head3"> </div>
<div id="logo"><div id="inside">
   <label>GYM MANAGEMENT SYSTEM</label>
</div>
   
</div>
</div>
<div id="content">
  
  <div id="contentl">&nbsp; </div>
<div id="contentr">ADMIN LOGIN</div>

</div>



<div id="content2">
<div id="content2l"><img src="image/gym-hero.png" align="right" width="167" height="240" /></div>
<div id="content2r">
  <form ACTION="<?php echo $loginFormAction; ?>" id="form1" name="form1" method="POST">
    <table width="498" height="146"   border="0" bgcolor="#CCFFCC">
      <tr>
        <td width="85" height="43">Username:</td>
        <td width="392"><p>
          <span id="sprytextfield1">
          <input name="username" type="text" autofocus="autofocus" required="required" id="username2" />
           </span></p></td>
      </tr>
      <tr>
        <td height="48">Password:</td>
        <td><p>
          <label for="password"></label>
             
            <span id="sprytextfield2">
            
            <input name="password" type="password" autofocus="autofocus" required="required" id="password" />
             </span></td>
      </tr>
      <tr>
        <td height="47">&nbsp;</td>
        <td><p>
          <input type="submit" name="submit" id="submit" value="LOGIN" />
        </p></td>
      </tr>
    </table>
  </form>
</div>
<div id="footer">   
  <p style="text-align: center; text-decoration-color: white"> Made by Komal Gaire</p>
  
</div>
</div>
 
</body>
</html>
<?php
mysql_free_result($Recordset1);
?>
