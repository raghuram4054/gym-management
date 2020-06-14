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

$colname_Recordset1 = "-1";
if (isset($_GET['userid'])) {
  $colname_Recordset1 = $_GET['userid'];
}
mysql_select_db($database_localhost, $localhost);
$query_Recordset1 = sprintf("SELECT * FROM tbl_userreg WHERE userid = %s", GetSQLValueString($colname_Recordset1, "int"));
$Recordset1 = mysql_query($query_Recordset1, $localhost) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);
?>
<?php

require("../fpdf/fpdf.php");

require("../Connections/localhost.php");
 
 
  
 $pdf = new FPDF();
  
$pdf->AddPage();
$pdf->SetFont('Arial','B',18);
 
$pdf->Cell(410,40,'KK GYM INVOICE      ');
$pdf->Ln(40);
$pdf->SetFont('Arial','B',10);
$pdf->Cell(15,8,' Id',0);
 $pdf->Cell(50,8,'Username',0);
  
  
 $pdf->Cell(40  ,8,'Address',0);
 $pdf->Cell(25 ,8,'Service',0);
 $pdf->Cell(25,8,'Amount',0);
$pdf->Ln(15);

  
		  
		 $pdf->Cell(16,8, $row_Recordset1['userid'],0);
 $pdf->Cell(51,8,$row_Recordset1['firstname'] ,0);
   
 $pdf->Cell(41  ,8,$row_Recordset1['address'] ,0);
 $pdf->Cell(26 ,8, $row_Recordset1['service'],0);
 $pdf->Cell(26,8,$row_Recordset1['amount'] ,0);
		 
		 
		 
	  
 
$pdf->Output();

?>
<?php
mysql_free_result($Recordset1);
?>
<html>
<link href="../image/kk_logo.png" rel="icon" type="image/x-icon" />

</html>