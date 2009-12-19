<?php

#   SQUID Traffic Inspection System
#   version 0.1.2 2007/10/05
#   stats.php
#   
#   

require_once('auth.php');

$TimerStart=microtime();

$stats=$_GET['stats'];
$type=$_GET['type'];
$year=$_GET['year'];
$month=$_GET['month'];
$day=$_GET['day'];
$user=$_GET['user'];
$host=$_GET['host'];

require_once('config.php');
require_once('theme.php');

$mysql_user=$config_db_user;
$mysql_pass=$config_db_password;
$mysql_server=$config_db_host;
$mysql_port=$config_db_port;
$mysql_dbname=$config_db_database;

####################################################

print bodyHead();

$connect=mysql_connect($mysql_server,$mysql_user,$mysql_pass);
mysql_select_db("$mysql_dbname");
mysql_query("set character set koi8r");
##########
function statsMysqlQueryOverall() {
    global $query, $statsData;
    global $year, $month, $user;

    $qMonthNameH=mysql_query("select date_format('00-$month-00', '%M') as monthNameH");
    $statsData[monthNameH]=MYSQL_RESULT($qMonthNameH,"monthNameH");
    $statsData[monthNameH]=@constant(_.strtoupper($statsData[monthNameH]));

    print eval($query[htmlFields].";");

    $numUnion=1;
    foreach ($query[tables] as $queryTable) {
        eval("\$querysDate = \"$query[sDate]\";");
        $select_date.=$querysDate;
        $num_table=count($query[tables]);
        if ($num_table > $numUnion){
            $select_date.=" union ";
        }
        $numUnion++;
    }
    $select_date.="$query[sSort]";
    $result=mysql_query("$select_date");
    while ($statsData=mysql_fetch_array($result, MYSQL_ASSOC)) {
        $statsData[monthName]=constant(_.strtoupper($statsData[monthName]));
        if ($checkyear != $statsData['year']) {
            $statsData[yearTab]=$statsData['year'];
            $checkyear=$statsData['year'];
        }
        foreach ($query[fields] as $qFields) {
            $selectTable=log.$qFields.s;
            eval("\$selectStatsEval = \"$query[sStats]\";");
            $resultStats=mysql_query("$selectStatsEval");
             while ($statsData1=mysql_fetch_array($resultStats, MYSQL_BOTH)) {
                $statsData = array_merge ($statsData, $statsData1);

                $statsData[loadbytes]=$statsData[loadbytesuser]+$statsData[loadbyteshost];
                $statsData[cachebytes]=$statsData[cachebytesuser]+$statsData[cachebyteshost];
                $statsData[totalbytes]=$statsData[loadbytes]+$statsData[cachebytes];
                if ($statsData[loadbytes] != "0"){
                    $statsData[loadbytes_rate]=number_format(($statsData[loadbytes]/$statsData[totalbytes])*100, 2)."%";
                    $statsData[cachebytes_rate]=number_format(($statsData[cachebytes]/$statsData[totalbytes])*100, 2)."%";
                }
            }
        }
        print eval($query[htmlRow].";"); 
    }
    print htmlTableEnd();
}
##########
function statsMysqlQuery() {
    global $query, $statsData;
    global $year, $month, $day, $user;

    $qMonthNameH=mysql_query("select date_format('00-$month-00', '%M') as monthNameH");
    $statsData[monthNameH]=MYSQL_RESULT($qMonthNameH,"monthNameH");
    $statsData[monthNameH]=@constant(_.strtoupper($statsData[monthNameH]));

    $qFields=$query[fields][0]; 
    $selectTable=$query[tables][0];

    print eval($query[htmlFields].";");
    eval("\$selectStatsEval = \"$query[sStats]\";");

    $resultStats=mysql_query("$selectStatsEval");

    while ($statsData=mysql_fetch_array($resultStats, MYSQL_ASSOC)) {
        $statsData[loadbytes]=$statsData[loadbytesuser]+$statsData[loadbyteshost];
        $statsData[cachebytes]=$statsData[cachebytesuser]+$statsData[cachebyteshost];
        $statsData[totalbytes]=$statsData[loadbytes]+$statsData[cachebytes];
        if ($statsData[loadbytes] != "0"){
            $statsData[loadbytes_rate]=number_format(($statsData[loadbytes]/$statsData[totalbytes])*100, 2)."%";
            $statsData[cachebytes_rate]=number_format(($statsData[cachebytes]/$statsData[totalbytes])*100, 2)."%";
        }
        print eval($query[htmlRow].";");
    }
    print htmlTableEnd();
}
####################################################
if ($stats== "overall") {
    $query[fields]=array("user", "host");
    $query[tables]=array("logusers", "loghosts");
}

if ($stats == "profile") {
    $user=$_SESSION['session_username'];
}

if ($stats == "user" or $stats == "profile") {
    $query[sStatsC]=' and user=\'$user\'';
}

if ($stats == "host") {
    $query[sStatsC]=' and host=\'$host\'';
}

if ($stats == "userall" or $stats == "user" or $stats == "profile") {
    $statscheck=$stats;
    $client='user';
    $statsType='userlist';
}

if ($stats == "hostall" or $stats == "host") {
    $statscheck=$stats;
    $client='host';
    $statsType='hostlist';
}

if ($stats == "$statscheck") {
    $query[fields]=array("$client");
    $query[tables]=array("log".$client."s");
}
##########
if ($type == total) {
    if ($month == "") {
        $query[sDate]='select distinct date_format(date, \'%Y\') as year, date_format(date, \'%m\') as month, date_format(date, \'%M\') as monthName from $queryTable';
        $query[sSort]=" order by year, month";
        $query[sStats]='select count(distinct $qFields) as count$qFields, sum(loadbytes) as loadbytes$qFields, sum(cachebytes) as cachebytes$qFields from $selectTable where date like \'$statsData[year]-$statsData[month]-%\''.$query[sStatsC];
        $query[htmlFields]='htmlStatsFields()';
        $query[htmlRow]='htmlStatsRow()';
        statsMysqlQueryOverall();
    } elseif ($month != "") {
        if ($day == "") {
            $query[sDate]='select distinct date_format(date, \'%Y\') as year, date_format(date, \'%m\') as month, date_format(date, \'%M\') as monthName, date_format(date, \'%d\') as day from $queryTable where date like \'$year-$month-%\'';
            $query[sSort]=" order by day";
            $query[sStats]='select count(distinct $qFields) as count$qFields, sum(loadbytes) as loadbytes$qFields, sum(cachebytes) as cachebytes$qFields from $selectTable where date like \'$statsData[year]-$statsData[month]-$statsData[day]\''.$query[sStatsC];
            $query[htmlFields]='htmlStatsMonthFields()';
            $query[htmlRow]='htmlStatsMonthRow()';
            statsMysqlQueryOverall();
        } elseif ($day != "") {

        }
    }
}
##########
if ($type == detail) {
    if ($month == "") {

    } elseif ($month != "") {
        if ($day == "") {

        } elseif ($day != "") {
            $query[sStats]='select time, user, clientip, url, bytes from logaccess where date like \'$year-$month-$day\''.$query[sStatsC];
            $query[htmlFields]='htmlStatsDetailFields()';
            $query[htmlRow]='htmlStatsDetailRow()';
            statsMysqlQuery();
        }
    }
} 
##########
if ($type == "$statsType") {
    $selectTable='log'.$client.'s';
    if ($month == "") {

    } elseif ($month != "") {
        if ($day == "") {
            $query[sStats]='select $qFields, sum(loadbytes) as loadbytes$qFields, sum(cachebytes) as cachebytes$qFields from $selectTable where date like \'$year-$month-%\' group by $qFields';
            $query[htmlFields]='htmlStatsClientListMonthFields()';
            $query[htmlRow]='htmlStatsClientListMonthRow()';
            statsMysqlQuery();
        } elseif ($day != "") {
            $query[sStats]='select $qFields, sum(loadbytes) as loadbytes$qFields, sum(cachebytes) as cachebytes$qFields from $selectTable where date like \'$year-$month-$day%\' group by $qFields';
            $query[htmlFields]='htmlStatsClientListDayFields()';
            $query[htmlRow]='htmlStatsClientListDayRow()';
            statsMysqlQuery();
        }
    }
}

print bodyBottom($TimerStart);

?>
