<?php
 
$hostname_localhost = "localhost";
$database_localhost = "gym";
$username_localhost = "root";
$password_localhost = "";
$localhost = @mysql_pconnect($hostname_localhost, $username_localhost, $password_localhost) or trigger_error(mysql_error(),E_USER_ERROR); 
?>