<?php

#   SQUID Traffic Inspection System
#   version 0.1.2 2007/10/05
#   auth.php
#   
#   

session_start();

require_once('config.php');
require_once('functions.php');

$mysql_user=$config_db_user;
$mysql_pass=$config_db_password;
$mysql_server=$config_db_host;
$mysql_port=$config_db_port;
$mysql_dbname=$config_db_database;

$connect=mysql_connect($mysql_server,$mysql_user,$mysql_pass) or die(mysql_error());
mysql_select_db("$mysql_dbname") or die (mysql_error());

function checkuser($user,$password) {
    $result=mysql_query("select user from users where user='$user' and password=password('$password')") or die (mysql_error());
    return mysql_num_rows($result);
}

function authuser() {
    header('WWW-Authenticate: Basic realm="Authenticate: SQUID Traffic Inspection System"');
    header('HTTP/1.0 401 Unauthorized');
    echo error401();
    exit;
}

if (!isset($_SERVER['PHP_AUTH_USER'])) {
    authuser();
} else {
    if (checkuser($_SERVER['PHP_AUTH_USER'],$_SERVER['PHP_AUTH_PW'])) {
        $authuser=$_SERVER['PHP_AUTH_USER'];
        $result=mysql_query("select admin from users where user='$authuser'") or die(mysql_error());
        $admin=mysql_result($result,"admin");
        $_SESSION['session_username'] 	= $_SERVER['PHP_AUTH_USER'];
        $_SESSION['session_admin'] 	= $admin;
    } else {
        authuser();
    }
}

?>
