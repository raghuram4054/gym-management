 
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

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO tbl_userreg (firstname, lastname, age, sex, phone, address, service, timestap, amount, plan) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['fname'], "text"),
                       GetSQLValueString($_POST['lname'], "text"),
                       GetSQLValueString($_POST['agetxt'], "int"),
                       GetSQLValueString($_POST['radio'], "text"),
                       GetSQLValueString($_POST['phonetxt'], "int"),
                       GetSQLValueString($_POST['addresstxt'], "text"),
                       GetSQLValueString($_POST['service'], "text"),
                       GetSQLValueString($_POST['datetime-local'], "date"),
                       GetSQLValueString($_POST['amount'], "int"),
                       GetSQLValueString($_POST['select'], "int"));

  mysql_select_db($database_localhost, $localhost);
  $Result1 = mysql_query($insertSQL, $localhost) or die(mysql_error());

  $insertGoTo = "myembers.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

mysql_select_db($database_localhost, $localhost);
$query_register = "SELECT * FROM tbl_userreg";
$register = mysql_query($query_register, $localhost) or die(mysql_error());
$row_register = mysql_fetch_assoc($register);
$totalRows_register = mysql_num_rows($register);
?> 
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>userregistration</title>
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
     
    <div id="c1"><span class="txtuserreg"> <img src="../image/office.png" width="74" height="76" alt="us" />User Registration </span></div>
    <div id="c2">
     <fieldset>   <form action="<?php echo $editFormAction; ?>" id="form1" name="form1" method="POST"> <hr/><table width="394"  height="562" align="left"  >
        <tr>
          <td height="25" class="table" bgcolor="#999966" >FirstName</td>
          <td  > 
            <input name="fname" type="text" autofocus="autofocus" required="required" id="fname"   />
          </td>
        </tr>
      
      
      
      
  <tr>
    <td width="136" height="25" class="table" bgcolor="#999966"  >LastName</td>
    <td width="327"  > 
      <input name="lname" type="text" required="required" id="lname"   />
       </td>
  </tr>
  <tr>
    <td height="25"  bgcolor="#669999" class="table">Age</td>
    <td>
      <input name="agetxt" type="text" required="required" id="agetxt2"   />
    </td>
  </tr>
  <tr>
    <td height="25" bgcolor="#0066FF" class="table">Sex</td>
    <td> 
      <input type="radio" name="radio" id="m" value="male" />
     Male 
      <input type="radio" name="radio" id="m2" value="female" />
     Female 
      </td>
  </tr>
  <tr>
    <td height="25" bgcolor="#9999CC" class="table">Phone:</td>
    <td> 
      <input name="phonetxt" type="text" required="required" id="agetxt"   />
          </td>
  </tr>
  <tr>
    <td height="25" bgcolor="#9999FF" class="table">Address</td>
    <td> 
      <input name="addresstxt" type="text" required="required" id="agetxt"  />
       </td>
  </tr>
  <tr>
    <td height="25" bgcolor="#99FFFF">Service</td>
    <td> 
      
         
           <input type="radio" name="service" value="gym" id="service_0" />
          Gym
          <input type="radio" name="service" value="cardio" id="service_1" />Cardio
          <input type="radio" name="service" value="sauna" id="service_2" />
          Sauna 
        
     </td>
  </tr>
  <tr>
    <td height="25" bgcolor="#999999">Date</td>
    <td> 
      <input name="datetime-local" type="datetime-local" required="required" id="datetime-local" />
      </td>
  </tr>
  <tr>
    <td height="25" bgcolor="#999966">Amount</td>
    <td> 
      <input type="number" name="amount" id="amount" /></td>
  </tr>
  <tr>
    <td height="25" bgcolor="#996666">Plan</td>
    <td>  
       
        <select name="select" required="required" id="select">
          <option value="30" selected="selected">1 month</option>
          <option value="90">3 months</option>
          <option value="180">6 months</option>
          <option value="365">1 year</option>
        </select>
       
     </td>
  </tr>
  
  <tr>
    <td height="25"> </td>
    <td><hr/><input type="submit" name="submit" id="submit" value="Submit" /></td>
  </tr>
  
      </table> 
        <input type="hidden" name="MM_insert" value="form1" />
      </form> </fieldset>
       
     </div> <div id="footer">
     &nbsp;
  </div>
  </div>
     
</div>
  
</div>

</body>
</html>
<?php
mysql_free_result($register);
?>
