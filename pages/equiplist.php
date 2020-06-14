<?php require_once('../Connections/localhost.php'); ?>
<?php require_once('../Connections/kk.php'); ?>
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

$currentPage = $_SERVER["PHP_SELF"];

$maxRows_eqp = 10;
$pageNum_eqp = 0;
if (isset($_GET['pageNum_eqp'])) {
  $pageNum_eqp = $_GET['pageNum_eqp'];
}
$startRow_eqp = $pageNum_eqp * $maxRows_eqp;

mysql_select_db($database_localhost, $localhost);
$query_eqp = "SELECT * FROM tbl_equip";
$query_limit_eqp = sprintf("%s LIMIT %d, %d", $query_eqp, $startRow_eqp, $maxRows_eqp);
$eqp = mysql_query($query_limit_eqp, $localhost) or die(mysql_error());
$row_eqp = mysql_fetch_assoc($eqp);

if (isset($_GET['totalRows_eqp'])) {
  $totalRows_eqp = $_GET['totalRows_eqp'];
} else {
  $all_eqp = mysql_query($query_eqp);
  $totalRows_eqp = mysql_num_rows($all_eqp);
}
$totalPages_eqp = ceil($totalRows_eqp/$maxRows_eqp)-1;

mysql_select_db($database_kk, $kk);
$query_del = "SELECT * FROM tbl_equip";
$del = mysql_query($query_del, $kk) or die(mysql_error());
$row_del = mysql_fetch_assoc($del);
$totalRows_del = mysql_num_rows($del);

mysql_select_db($database_kk, $kk);
$query_delete = "SELECT * FROM tbl_equip";
$delete = mysql_query($query_delete, $kk) or die(mysql_error());
$row_delete = mysql_fetch_assoc($delete);
$totalRows_delete = mysql_num_rows($delete);

$queryString_eqp = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_eqp") == false && 
        stristr($param, "totalRows_eqp") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_eqp = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_eqp = sprintf("&totalRows_eqp=%d%s", $totalRows_eqp, $queryString_eqp);


//search starts from here

$startRow_eqp = $pageNum_eqp * $maxRows_eqp;


$colname_eqp = "-1";
 mysql_select_db($database_kk, $kk);
if (isset($_POST['txt_search'])) {
	$searchword=$_POST['txt_search'];
	$query_eqp="select * from tbl_equip where   name LIKE  '%".$searchword."%'";
   
}

else{
	
$query_eqp="select * from tbl_equip";	
	
}
$query_limit_eqp = sprintf("%s LIMIT %d, %d", $query_eqp, $startRow_eqp, $maxRows_eqp);
$eqp = mysql_query($query_limit_eqp, $localhost) or die(mysql_error());
$row_eqp = mysql_fetch_assoc($eqp); 
// ends here

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>equipment list</title>
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
<img src="../image/main.png" /> <div id="logo"> 
     
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
     
    <div id="c1"><span class="txtuserreg"> <img src="../image/office.png" width="74" height="76" alt="us" />Equipment List:</span></div>
    <div id="c2"><hr/><form action="" id="form1" method="post" name="form1">
      <label for="txt_search"></label>
      <input type="text" name="txt_search" id="txt_search" />
      <input class="abc" type="submit" name="btn_search" id="btn_search" value="Search" />
    </form>
     <br/>  <table border="0" align="center" cellpadding="7" cellspacing="7">
        <tr>
          <td bgcolor="#438C47">equipid</td>
          <td bgcolor="#438C47">name</td>
          <td bgcolor="#438C47">vendor</td>
           
          <td bgcolor="#438C47">amount</td>
          <td bgcolor="#438C47">phone</td>
          <td bgcolor="#438C47">address</td>
          <td bgcolor="#438C47">date</td>
          <td bgcolor="#CC9">&nbsp;</td>
        </tr>
        <?php do { ?>
        <tr>
          <td><?php echo $row_eqp['equipid']; ?>&nbsp; </td>
          <td><a> <?php echo $row_eqp['name']; ?>&nbsp; </a></td>
          <td><?php echo $row_eqp['vendor']; ?>&nbsp; </td>
           
          <td><?php echo $row_eqp['amount']; ?></td>
          <td><?php echo $row_eqp['phone']; ?>&nbsp; </td>
          <td><?php echo $row_eqp['address']; ?>&nbsp; </td>
          <td><?php echo $row_eqp['date']; ?>&nbsp; </td>
          <td><a href="eqpdel.php?equipid=<?php echo $row_delete['equipid']; ?>" class="kk"><img src="../image/delete.png" width="30" height="25" /></a></td>
        </tr>
        <?php } while ($row_eqp = mysql_fetch_assoc($eqp)); ?>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td><a href="equi.php" class="my">ADD</a></td>
          <td>&nbsp;</td>
        </tr>
        
      </table> <span class="kk"><a href="<?php printf("%s?pageNum_eqp=%d%s", $currentPage, max(0, $pageNum_eqp - 1), $queryString_eqp); ?>" class="kk">Previous</a></span><a href="<?php printf("%s?pageNum_eqp=%d%s", $currentPage, min($totalPages_eqp, $pageNum_eqp + 1), $queryString_eqp); ?>" class="kk">||Next</a> 
      
<br />
 <br />     
Records <?php echo ($startRow_eqp + 1) ?> to <?php echo min($startRow_eqp + $maxRows_eqp, $totalRows_eqp) ?> of <?php echo $totalRows_eqp ?>
      <hr/>
    </div> <div id="footer">
     &nbsp;
  </div>
  </div>
     
</div>
  
</div>

</body>
</html>
<?php
mysql_free_result($eqp);

mysql_free_result($del);

mysql_free_result($delete);
?>
