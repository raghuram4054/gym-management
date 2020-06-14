<?php require_once('../Connections/localhost.php'); ?>
<?php
//initialize the session
if (!isset($_SESSION)) {
  session_start();
}

// ** Logout the current user. **
$logoutAction = $_SERVER['PHP_SELF']."?doLogout=true";
if ((isset($_SERVER['QUERY_STRING'])) && ($_SERVER['QUERY_STRING'] != "")){
  $logoutAction .="&". htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_GET['doLogout'])) &&($_GET['doLogout']=="true")){
  //to fully log out a visitor we need to clear the session varialbles
  $_SESSION['MM_Username'] = NULL;
  $_SESSION['MM_UserGroup'] = NULL;
  $_SESSION['PrevUrl'] = NULL;
  unset($_SESSION['MM_Username']);
  unset($_SESSION['MM_UserGroup']);
  unset($_SESSION['PrevUrl']);
	
  $logoutGoTo = "../index.php";
  if ($logoutGoTo) {
    header("Location: $logoutGoTo");
    exit;
  }
}
?>
<?php
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "";
$MM_donotCheckaccess = "true";

// *** Restrict Access To Page: Grant or deny access to this page
function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup) { 
  // For security, start by assuming the visitor is NOT authorized. 
  $isValid = False; 

  // When a visitor has logged into this site, the Session variable MM_Username set equal to their username. 
  // Therefore, we know that a user is NOT logged in if that Session variable is blank. 
  if (!empty($UserName)) { 
    // Besides being logged in, you may restrict access to only certain users based on an ID established when they login. 
    // Parse the strings into arrays. 
    $arrUsers = Explode(",", $strUsers); 
    $arrGroups = Explode(",", $strGroups); 
    if (in_array($UserName, $arrUsers)) { 
      $isValid = true; 
    } 
    // Or, you may restrict access to only certain users based on their username. 
    if (in_array($UserGroup, $arrGroups)) { 
      $isValid = true; 
    } 
    if (($strUsers == "") && true) { 
      $isValid = true; 
    } 
  } 
  return $isValid; 
}

$MM_restrictGoTo = "../login.php";
if (!((isset($_SESSION['MM_Username'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))) {   
  $MM_qsChar = "?";
  $MM_referrer = $_SERVER['PHP_SELF'];
  if (strpos($MM_restrictGoTo, "?")) $MM_qsChar = "&";
  if (isset($_SERVER['QUERY_STRING']) && strlen($_SERVER['QUERY_STRING']) > 0) 
  $MM_referrer .= "?" . $_SERVER['QUERY_STRING'];
  $MM_restrictGoTo = $MM_restrictGoTo. $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
  header("Location: ". $MM_restrictGoTo); 
  exit;
}
?>
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

$maxRows_DetailRS1 = 10;
$pageNum_DetailRS1 = 0;
if (isset($_GET['pageNum_DetailRS1'])) {
  $pageNum_DetailRS1 = $_GET['pageNum_DetailRS1'];
}
$startRow_DetailRS1 = $pageNum_DetailRS1 * $maxRows_DetailRS1;

$colname_DetailRS1 = "-1";
if (isset($_GET['recordID'])) {
  $colname_DetailRS1 = $_GET['recordID'];
}
mysql_select_db($database_localhost, $localhost);
$query_DetailRS1 = sprintf("SELECT * FROM tbl_userreg WHERE userid = %s", GetSQLValueString($colname_DetailRS1, "int"));
$query_limit_DetailRS1 = sprintf("%s LIMIT %d, %d", $query_DetailRS1, $startRow_DetailRS1, $maxRows_DetailRS1);
$DetailRS1 = mysql_query($query_limit_DetailRS1, $localhost) or die(mysql_error());
$row_DetailRS1 = mysql_fetch_assoc($DetailRS1);

if (isset($_GET['totalRows_DetailRS1'])) {
  $totalRows_DetailRS1 = $_GET['totalRows_DetailRS1'];
} else {
  $all_DetailRS1 = mysql_query($query_DetailRS1);
  $totalRows_DetailRS1 = mysql_num_rows($all_DetailRS1);
}
$totalPages_DetailRS1 = ceil($totalRows_DetailRS1/$maxRows_DetailRS1)-1;
?>
 
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Member Details</title>

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
<link href="../css/me.css" rel="stylesheet" type="text/css" />  
 
 
  
</head>

<body>
<div id="head">
<img src="../image/main.png" />
<div id="logo"> 
     
  </div>
  
  
  
  <div id='cssmenu'>
 <ul>
 
 
 
 
  
 
 
   <li class='active'><a href='home.php'>Dashboard</a></li>
   <li><a href='myembers.php'>Members List</a></li>
   <li><a href='equiplist.php'>Equipment List</a></li>
   <li><a href='pay.php'>Payment</a></li>
   <li style="color:#DDDDC7"><a href="alerts.php">Status</a></li>
   <li><a href='<?php echo $logoutAction ?>'>logout</a></li>
   
</ul>  
</div>
  <div id="content">
    
    <div id="c1"><span class="txtuserreg"> <img src="../image/office.png" width="74" height="76" alt="us" />Member Details:</span></div>
    <div id="c2"><hr /><p>
    <table border="0" align="center" cellpadding="4" cellspacing="2">
  <tr>
    <td width="66" bgcolor="#999966">userid</td>
    <td width="191"><?php echo $row_DetailRS1['userid']; ?></td>
  </tr>
  <tr>
    <td bgcolor="#999966">firstname</td>
    <td><?php echo $row_DetailRS1['firstname']; ?></td>
  </tr>
  <tr>
    <td bgcolor="#999966">lastname</td>
    <td><?php echo $row_DetailRS1['lastname']; ?></td>
  </tr>
  <tr>
    <td bgcolor="#999966">age</td>
    <td><?php echo $row_DetailRS1['age']; ?></td>
  </tr>
  <tr>
    <td bgcolor="#999966">sex</td>
    <td><?php echo $row_DetailRS1['sex']; ?></td>
  </tr>
  <tr>
    <td bgcolor="#999966">phone</td>
    <td><?php echo $row_DetailRS1['phone']; ?></td>
  </tr>
  <tr>
    <td bgcolor="#999966">address</td>
    <td><?php echo $row_DetailRS1['address']; ?></td>
  </tr>
  <tr>
    <td bgcolor="#999966">service</td>
    <td><?php echo $row_DetailRS1['service']; ?></td>
  </tr>
  <tr>
    <td bgcolor="#999966">timestap</td>
    <td><?php echo $row_DetailRS1['timestap']; ?></td>
  </tr>
  <tr>
    <td bgcolor="#999966">amount</td>
    <td><?php echo $row_DetailRS1['amount']; ?></td>
  </tr>
  <tr>
    <td bgcolor=>&nbsp;</td>
    <td><a href="myembers.php" class="kk">BACK</a></td>
  </tr>
    </table>
    <form action="" method="post" name="form3">
        <a href="myembers.php" class="kk"></a>
      </form>
      <hr/>
    </div> <div id="footer">
     &nbsp;
  </div>
  </div>
     
</div>
  
</div>



</body>
</html><?php
mysql_free_result($DetailRS1);
?>
 