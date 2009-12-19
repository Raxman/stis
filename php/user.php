<?php

#   SQUID Traffic Inspection System
#   version 0.1.2 2007/10/05
#   user.php
#   
#   

require_once('auth.php');

$TimerStart=microtime();

$action=$_GET['action'];
$user=$_REQUEST['user'];
$password=$_POST['password'];
$passuser=$_POST['passuser'];
$currentpassword=$_POST['currentpassword'];
$newpassword=$_POST['newpassword'];
$confirmpassword=$_POST['confirmpassword'];
$admin1=$_POST['admin1'];
$fullname=$_POST['fullname'];
$departmentid=$_POST['departmentid'];
$maxdaily=$_POST['maxdaily'];
$maxmonthly=$_POST['maxmonthly'];
$proc=$_POST['proc'];
$save=$_POST['save'];
if (($action == "userlock") or ($action == "userunlock") or ($action == "userdel")) {
    header("Location: $_SERVER[HTTP_REFERER]");
}

if ($action == "useradddo") {
    header("Location: ./user.php?action=userlist&sort=user");
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

$connect=mysql_connect($mysql_server,$mysql_user,$mysql_pass);
mysql_select_db("$mysql_dbname");
mysql_query("set character set koi8r");

if ($_SESSION['session_admin'] == "Y") {
    if ($save != "") {
        if ($proc == "useradddo") {
            $result=mysql_query("select * from users where user='$user'");
            if (mysql_num_rows($result) == 0) {
                if ($passuser == "checked") {
                    $password = $user;
                }
                mysql_query("insert into users (user, password, admin, fullname, departmentid, maxdaily, maxmonthly) values ('$user', password('$password'), '$admin1', '$fullname', '$departmentid', '$maxdaily', '$maxmonthly')");
                print htmlUserAddDo();
            }
        }

        if ($proc == "usereditdo") {
            mysql_query("update users set fullname='$fullname', admin='$admin1', departmentid='$departmentid', maxdaily='$maxdaily', maxmonthly='$maxmonthly' where user='$user'");
            print htmlUserEditDo();
        }
    }

    if ($action == "useradd") {
        $result=mysql_query("select departmentid, department from departments order by department");
        while($array_data=mysql_fetch_array($result, MYSQL_ASSOC)) {
            $department_list.="<option value=\"".$array_data['departmentid']."\">".$array_data['department']."</option>";
        }
        print htmlUserAdd();
    }

    if ($action == "userview") {
        $result=mysql_query("select user, admin, fullname, department, maxdaily, maxmonthly from users left join departments on users.departmentid=departments.departmentid where user='$user'");
        while ($array_data=mysql_fetch_array($result, MYSQL_ASSOC)) {
            $user=$array_data['user'];
            $fullname=$array_data['fullname'];
            $admin=$array_data['admin'];
            $department=$array_data['department'];
            $maxdaily=$array_data['maxdaily'];
            $maxmonthly=$array_data['maxmonthly'];
            print htmlUserView();
        }
    }

    if ($action == "useredit") {
        $result=mysql_query("select user, admin, fullname, departmentid, maxdaily, maxmonthly from users where user='$user'");
        while ($array_data=mysql_fetch_array($result, MYSQL_ASSOC)) {
            $result_depart=mysql_query("select departmentid, department from departments order by department");
            while($array_depart=mysql_fetch_array($result_depart, MYSQL_ASSOC)) {
                if ($array_depart['departmentid'] == $array_data['departmentid']) {
                    $select="selected";
                } else {
                    $select="";
                }
                $department_list.="<option value=\"".$array_depart['departmentid']."\" $select>".$array_depart['department']."</option>";
            }
            $user=$array_data['user'];
            $fullname=$array_data['fullname'];
            $admin=$array_data['admin'];
            $department=$array_data['department'];
            $maxdaily=$array_data['maxdaily'];
            $maxmonthly=$array_data['maxmonthly'];
            print htmlUserEdit();
        }
    }

    if ($action == "userlock") {
        $result=mysql_query("select password from users where user='$user'");
        $pass=mysql_result($result,"password");
        $pass='~'.$pass;
        mysql_query("update users set password='$pass' where user='$user'");
    }

    if ($action == "userunlock") {
        $result=mysql_query("select password from users where user='$user'");
        $pass=mysql_result($result,"password");
        $pass=str_replace("~", "", $pass);
        mysql_query("update users set password='$pass' where user='$user'");
    }

    if ($action == "userdel") {
        mysql_query("delete from users where user='$user'");
        print htmlUserDel();
    }

    if ($action == "userlist") {
        $sort=$_GET['sort'];
        $rsort=$_GET['rsort'];
        if (!is_null($sort)) {
            $sorttype="rsort";
            $sortcol=$sort;
    } elseif (!is_null($rsort)) {
            $sorttype="sort";
            $sortcol=$rsort;
            $desc="desc";
        }
        $usernum=1;

        $select_sort=" order by $sortcol $desc";
        $result=mysql_query("select user, password, admin, fullname, department from users left join departments on users.departmentid=departments.departmentid $select_sort");
        $usertotal=mysql_num_rows($result);
        print htmlUserListFields();
        while ($array_data=mysql_fetch_array($result, MYSQL_ASSOC)) {
            $user=$array_data['user'];
            $pass=$array_data['password'];
            $admin=$array_data['admin'];
            $fullname=$array_data['fullname'];
            $department=$array_data['department'];
            if ($pass[0] == "~") {
                $passlock="userunlock";
                $passlock1="unlock";
            } else {
                $passlock="userlock";
                $passlock1="lock";
            }
            print htmlUserListRow();
            $usernum++;
        }
        print htmlTableEnd();
    }

    if ($action == "changepassword1") {
        print htmlChangePassword1();
    }

    if ($action == "changepassworddo1") {
        $result=mysql_query("select user from users where user='$user'");
        if (mysql_num_rows($result) == "1") {
            if ($passuser == "checked") {
                $newpassword = $user;
                $confirmpassword = $user;
            }
            if ($newpassword == $confirmpassword) {
                mysql_query("update users set password=password('$newpassword') where user='$user'");
                print htmlChangePasswordDo1();
            } else {
                print htmlChangePasswordFalseConfirm1();
            }
        }
    }
}

if ($action == "profile") {
    $authuser=$_SESSION['session_username'];
    $result=mysql_query("select user, admin, fullname, department, maxdaily, maxmonthly from users left join departments on users.departmentid=departments.departmentid where user='$authuser'");
    while ($array_data=mysql_fetch_array($result, MYSQL_ASSOC)) {
        $user=$array_data['user'];
        $fullname=$array_data['fullname'];
        $admin=$array_data['admin'];
        $department=$array_data['department'];
        $maxdaily=$array_data['maxdaily'];
        $maxmonthly=$array_data['maxmonthly'];
        print htmlProfileView();
    }
}

if ($action == "changepassword") {
    print htmlChangePassword();
}

if ($action == "changepassworddo") {
    $result=mysql_query("select user from users where user='$user' and password=password('$currentpassword')");
    if (mysql_num_rows($result) == "1") {
        if ($newpassword == $confirmpassword) {
            mysql_query("update users set password=password('$newpassword') where user='$user'");
            print htmlChangePasswordDo();
        } else {
            print htmlChangePasswordFalseConfirm();
        }
    } else {
        print htmlChangePasswordFalesCurrent();
    }
}

print bodyBottom($TimerStart);

?>
