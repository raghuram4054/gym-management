<?php require_once('../Connections/localhost.php'); ?>
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

if ((isset($_GET['userid'])) && ($_GET['userid'] != "")) {
  $deleteSQL = sprintf("DELETE FROM tbl_userreg WHERE userid=%s",
                       GetSQLValueString($_GET['userid'], "text"));

  mysql_select_db($database_localhost, $localhost);
  $Result1 = mysql_query($deleteSQL, $localhost) or die(mysql_error());

  $deleteGoTo = "myembers.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $deleteGoTo .= (strpos($deleteGoTo, '?')) ? "&" : "?";
    $deleteGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $deleteGoTo));
}
?>
 
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>userregistration</title>

<style type="text/css">
.b {
	font-size: 36px;
}
.c{
	background-position:right;
	
	
	
}
</style>
<br />
<link href="../css/home.css" rel="stylesheet" type="text/css" />
<link href="../css/homemenu.css" rel="stylesheet" type="text/css" />  
 
 
  
</head>

<body>
<div id="head"> <div id="logo"> 
    <table width="900" height="80" border="0">
      <tr>
        <td width="78" ><img src="../image/ACT.png" /></td> 
        <td width="349"><span class="gym"> </span>  </td>
      </tr>
    </table>
  </div>
  
  
  <div id='cssmenu'>
<ul>
   <li class='active'><a href='home.php'>Home</a></li>
   <li><a href='userregistration.php'>user registration</a></li>
   <li><a href='payment.php'>payment</a></li>
   <li><a href='membership.php'>membership</a></li>
   <li><a href='alert.php'>alerts</a></li>
   <li><a href='logout.php'>logout</a></li>
</ul>
</div>
  <div id="content">
    &nbsp;
    <div id="c1"><span class="txtuserreg"> <img src="../image/office.png" width="74" height="76" alt="us" />Members </span></div>
    <div id="c2">
      
    </div> <div id="footer">
     &nbsp; <p style="text-align: center; text-decoration-color: white"> Made by Komal Gaire</p>
  </div>
  </div>
     
</div>
  
</div>

</body>
</html>
 