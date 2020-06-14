<?php require_once('../Connections/localhost.php'); ?>
<?php require_once('../Connections/kk.php'); ?>
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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE tbl_userreg SET firstname=%s, lastname=%s, age=%s, sex=%s, phone=%s, address=%s, service=%s, amount=%s WHERE userid=%s",
                       GetSQLValueString($_POST['firstname'], "text"),
                       GetSQLValueString($_POST['lastname'], "text"),
                       GetSQLValueString($_POST['age'], "int"),
                       GetSQLValueString($_POST['sex'], "text"),
                       GetSQLValueString($_POST['phone'], "int"),
                       GetSQLValueString($_POST['address'], "text"),
                       GetSQLValueString($_POST['service'], "text"),
                       GetSQLValueString($_POST['amount'], "int"),
                       GetSQLValueString($_POST['userid'], "int"));

  mysql_select_db($database_localhost, $localhost);
  $Result1 = mysql_query($updateSQL, $localhost) or die(mysql_error());

  $updateGoTo = "myembers.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

$colname_update = "-1";
if (isset($_GET['userid'])) {
  $colname_update = $_GET['userid'];
}
mysql_select_db($database_kk, $kk);
$query_update = sprintf("SELECT * FROM tbl_userreg WHERE userid = %s", GetSQLValueString($colname_update, "int"));
$update = mysql_query($query_update, $kk) or die(mysql_error());
$row_update = mysql_fetch_assoc($update);
$totalRows_update = mysql_num_rows($update);

$colname_Recordset1 = "-1";
if (isset($_GET['userid'])) {
  $colname_Recordset1 = $_GET['userid'];
}
mysql_select_db($database_localhost, $localhost);
$query_Recordset1 = sprintf("SELECT userid, firstname, lastname, age, sex, phone, address, service, timestap, amount, plan FROM tbl_userreg WHERE userid = %s", GetSQLValueString($colname_Recordset1, "int"));
$Recordset1 = mysql_query($query_Recordset1, $localhost) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Update</title>
<link href="../image/kk_logo.png" rel="icon" type="image/x-icon" />
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
<img src="../image/main.png" /><div id="logo"> 
     
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
    
    <div id="c1"><span class="txtuserreg"> <img src="../image/office.png" width="74" height="76" alt="us" />Update Members </span></div>
    <div id="c2">
      <form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
      <p>
      <hr />
        <table align="center">
          <tr valign="baseline">
            <td nowrap="nowrap" align="right" bgcolor="#999966">Userid:</td>
            <td><?php echo $row_Recordset1['userid']; ?></td>
          </tr>
          <tr valign="baseline">
            <td nowrap="nowrap" align="right" bgcolor="#999966">Firstname:</td>
            <td><input type="text" name="firstname" value="<?php echo $row_Recordset1['firstname']; ?>" size="20" /></td>
          </tr>
          <tr valign="baseline">
            <td nowrap="nowrap" align="right" bgcolor="#999966">Lastname:</td>
            <td><input type="text" name="lastname" value="<?php echo $row_Recordset1['lastname']; ?>" size="20" /></td>
          </tr>
          <tr valign="baseline">
            <td nowrap="nowrap" align="right" bgcolor="#999966">Age:</td>
            <td><input type="text" name="age" value="<?php echo $row_Recordset1['age']; ?>" size="20" /></td>
          </tr>
          <tr valign="baseline">
            <td nowrap="nowrap" align="right" bgcolor="#999966">Sex:</td>
            <td><input type="text" name="sex" value="<?php echo $row_Recordset1['sex']; ?>" size="20" /></td>
          </tr>
          <tr valign="baseline">
            <td nowrap="nowrap" align="right" bgcolor="#999966">Phone:</td>
            <td><input type="text" name="phone" value="<?php echo $row_Recordset1['phone']; ?>" size="20" /></td>
          </tr>
          <tr valign="baseline">
            <td nowrap="nowrap" align="right" bgcolor="#999966">Address:</td>
            <td><input type="text" name="address" value="<?php echo $row_Recordset1['address']; ?>" size="20" /></td>
          </tr>
          <tr valign="baseline">
            <td nowrap="nowrap" align="right" bgcolor="#999966">Service:</td>
            <td><input type="text" name="service" value="<?php echo $row_Recordset1['service']; ?>" size="20" /></td>
          </tr>
           
           
          <tr valign="baseline">
            <td nowrap="nowrap" align="right" bgcolor="#999966">Amount</td>
            <td><label for="amount"></label>
            <input name="amount" type="text" id="amount" value="<?php echo $row_Recordset1['amount']; ?>" size="20" /></td>
          </tr>
          <tr valign="baseline">
            <td nowrap="nowrap" align="right">&nbsp;</td>
            <td><input type="submit" onclick="alert('sucessfully updated!')"value="Update record" /></td>
          </tr>
        </table>
        <input type="hidden" name="MM_update" value="form1" />
        <input type="hidden" name="userid" value="<?php echo $row_update['userid']; ?>" />
        <input type="hidden" name="MM_update" value="form1" />
      </form>
      <p>&nbsp;</p>
    </div> 
    <div id="footer">
     &nbsp;
  </div>
  </div>
     
</div>
  
</div>

</body>
</html>
<?php
mysql_free_result($update);

//mysql_free_result($update)//;

mysql_free_result($Recordset1);
?>
