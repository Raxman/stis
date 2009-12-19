<?php

#   SQUID Traffic Inspection System
#   version 0.1.2 2007/10/05
#   department.php
#   
#   

require_once('auth.php');

if ($_SESSION['session_admin'] == "Y") {

    $TimerStart=microtime();
$action=$_GET['action'];
$department=$_POST['department'];
$departmentid=$_REQUEST['departmentid'];
$proc=$_POST['proc'];
$save=$_POST['save'];
    if ($action == "departmentadddo" or $action == "departmenteditdo") {
        $thisfile=basename(__FILE__);
        header("Location: ./$thisfile?action=departmentlist&sort");
    }

    if ($action == "departmentdel") {
        header("Location: $_SERVER[HTTP_REFERER]"); 
    }

    require_once('config.php');
    require_once('theme.php');

    $mysql_user=$config_db_user;
    $mysql_pass=$config_db_password;
    $mysql_server=$config_db_host;
    $mysql_port=$config_db_port;
    $mysql_dbname=$config_db_database;

####################################################

    print bodyHead();

    $connect=mysql_connect($mysql_server,$mysql_user,$mysql_pass) or die(mysql_error());
    mysql_select_db("$mysql_dbname") or die(mysql_error());
    mysql_query("set character set koi8r");

    if ($save != "") {
        if ($proc == "departmentadddo") {
            mysql_query("insert into departments (department) values ('$department')") or die(mysql_error());
            print htmlDepartmentAddDo();
        }
        if ($proc == "departmenteditdo") {
            mysql_query("update departments set department='$department' where departmentid='$departmentid'") or die(mysql_error());
            print htmlDepartmentEditDo();
        }
    }

    if ($action == "departmentadd") {
        print htmlDepartmentAdd();
    }

    if ($action == "departmentedit") {
        $result=mysql_query("select departmentid, department from departments where departmentid='$departmentid'") or die(mysql_error());
        while ($stats_data=mysql_fetch_row($result)) {
            $departmentid=$stats_data[0];
            $department=$stats_data[1];
            print htmlDepartmentEdit();
        }
    }

    if ($action == "departmentdel") {
        mysql_query("delete from departments where departmentid='$departmentid'") or die(mysql_error());
        print htmlDepartmentDel();
    }

    if ($action == "departmentlist") {
        if (!is_null($_GET['sort'])) {
            $sorttype="rsort";
        } elseif (!is_null($_GET['rsort'])) {
            $sorttype="sort";
            $desc="desc";
        }
        $departnum=1;
        $select_sort=" order by department $desc";
        $result=mysql_query("select departmentid,department from departments $select_sort") or die(mysql_error());
        $departmenttotal=mysql_num_rows($result);
        print htmlDepartmentListFields();
        while ($stats_data=mysql_fetch_row($result)) {
            $departmentid=$stats_data[0];
            $department=$stats_data[1];
            print htmlDepartmentListRow();
            $departnum++;
        }
        print htmlTableEnd();
    }

    print bodyBottom();
}

?>
