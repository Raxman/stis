<?php

#   SQUID Traffic Inspection System
#   version 0.1.2 2007/10/05
#   functions.php
#   
#   

function today() {
    $nowWeekName=constant(_.strtoupper(date("l")));
    $nowMonthName=constant(_.strtoupper(date("F")));
    $nowDateTime=date("$nowWeekName, j $nowMonthName Y, H:i");
    return "$nowDateTime";
}

function processTime() {
    global $TimerStart;
    $TimerFinish=microtime();
    $TimerStart=explode(" ", $TimerStart);
    $TimerFinish=explode(" ", $TimerFinish);
    $TimerStart=$TimerStart[0]+$TimerStart[1];
    $TimerFinish=$TimerFinish[0]+$TimerFinish[1];
    $TimerTotal=number_format($TimerFinish-$TimerStart, 4);
    return "$TimerTotal";
}

function error401() {
    require_once('russian.php');
    global $mail, $prog_name_full, $prog_ver;
    $today=today();
    $server=$_SERVER['HTTP_HOST'];
    echo "
<html>
<head>
<title>$prog_name: ".Authentication_required."!</title>
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=KOI8-R\" />
<style type=\"text/css\">
<!--
body { 
    color: #000000; background-color: #FFFFFF; 
    font-family: Arial, Helvetica, sans-serif;
    font-size: 14px;
}
a:link { color: #0000CC; }
-->
</style>
</head>

<body>
<h1><font font size=\"7\">".Authentication_required."!</font></h1>
<HR noshade size=1px>
<dl>
<dd>


    This server could not verify that you are authorized to access
    the URL.
    You either supplied the wrong credentials (e.g., bad password), or your
    browser doesn't understand how to supply the credentials required.

  </dd></dl><dl><dd>


    In case you are allowed to request the document, please
    check your user-id and password and try again.

</dd></dl><dl><dd>
If you think this is a server error, please contact
the <a href=\"mailto:$mail\">$mail</a>

</dd></dl>

<h2><font font size=\"6\">"._ERROR." 401</font></h2>
<HR noshade size=1px>
<dl>
<dd>
<address>
  <a href=\"/\">$server</a>
  <br />

  <small>$today</small>
  <br />
  <small>$prog_name_full $prog_ver</small>
</address>
</dd>
</dl>
</body>
</html>
";
}

?>
