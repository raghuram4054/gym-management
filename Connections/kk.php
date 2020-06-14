<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_kk = "localhost";
$database_kk = "gym";
$username_kk = "root";
$password_kk = "";
$kk = @mysql_pconnect($hostname_kk, $username_kk, $password_kk) or trigger_error(mysql_error(),E_USER_ERROR); 
?>