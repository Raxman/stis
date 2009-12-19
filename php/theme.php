<?PHP

#   SQUID Traffic Inspection System
#   version 0.1.2 2007/10/05
#   theme.php
#   
#   

require_once('russian.php');
require_once('functions.php');

######## page Head ########
function bodyHead() {
    global $prog_name_short;
        if ($_SESSION['session_admin'] == "Y") {
            $menuadmin="<a href=\"./user.php?action=userlist&sort=user\">"._USERS."</a> <a href=\"./department.php?action=departmentlist&sort\">"._DEPARTMENTS."</a>";
        }
        $menuuser="<a href=\"./stats.php?stats=profile&type=total\">"._STATISTICS."</a> <a href=\"./user.php?action=profile\">"._PROFILE."</a> ";
        $menu=$menuuser.$menuadmin;

        echo "
<html>
<head>

<title>$prog_name_short</title>
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=KOI8-R\">
<link href=\"style.css\" rel=\"stylesheet\" type=\"text/css\">
</head>

<body>



<table width=\"100%\" height=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">
  <tr>
    <td height=\"60\" class=\"topBar\"><font class=\"topFont\">$prog_name_short</font></td>
  </tr>
  <tr>
    <td height=\"22\"><table width=\"100%\" height=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">
        <tr>
          <td height=\"1\" bgcolor=\"#7D7D7D\"></td>
        </tr>
        <tr>
          <td><table width=\"100%\" height=\"100%\" border=\"0\" cellpadding=\"3\" cellspacing=\"0\" bgcolor=\"#EAEAEA\" class=\"menuBar\">
              <tr>
                <td class=\"menuButton\">$menu</td>
                <td class=\"menuButton\"><div align=\"right\">"._USER.": <strong><font color=\"#CC0000\">".$_SESSION['session_username']."</font></strong></div></td>
<!--                <td width=\"50\" class=\"menuButton\"><a href=\"logout.php?action=logout\">"._LOGOUT."</a></td>-->
              </tr>
            </table></td>
        </tr>
        <tr>
          <td height=\"1\" bgcolor=\"#7D7D7D\"></td>
        </tr>
      </table></td>
  </tr>
  <tr>
    <td valign=\"top\"><table width=\"100%\" height=\"100%\" border=\"0\" cellpadding=\"32\" cellspacing=\"0\">
        <tr>
          <td align=\"center\" valign=\"top\">";
}
######## page Bottom ########
function bodyBottom() {
    global $TimerStart, $prog_name_full, $prog_ver;
    $today=today();
    $genTime=processTime();

    echo "
</td>
        </tr>
      </table></td>
  </tr>
  <tr>
    <td height=\"32\"><table width=\"100%\" height=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">
        <tr>
          <td height=\"1\" bgcolor=\"#7D7D7D\"></td>
        </tr>
        <tr>
          <td><table width=\"100%\" height=\"100%\" border=\"0\" cellpadding=\"3\" cellspacing=\"0\" bgcolor=\"#EAEAEA\" class=\"bottomBar\">
              <tr>
                <td width=\"30%\" class=\"bottomInfo\">"._GENERATION_TIME." $genTime "._SECONDS."</td>
                <td class=\"bottomInfo\">
                <div align=\"center\">Powered by <a href  >$prog_name_full</a> $prog_ver &copy; 2007</div></td>
                <td width=\"30%\" class=\"bottomInfo\">
                <div align=\"right\">"._NOW.": $today</div></td>
              </tr>
            </table></td>
        </tr>
        <tr>
          <td height=\"1\" bgcolor=\"#7D7D7D\"></td>
        </tr>
      </table></td>
  </tr>
</table>


</body>
</html>";
}
######## table End ########
function htmlTableEnd() {
    echo "</td>
              </tr>
            </table>";
}
######## stats.php ########
$statsMenu="
              <tr>
                <td colspan=\"9\" class=\"tableListMenu\"><a href=\"./stats.php?stats=overall&type=total\">"._OVERALL."</a> <a href=\"./stats.php?stats=userall&type=total\">"._USERS."</a> <a href=\"./stats.php?stats=hostall&type=total\">"._HOSTS."</a> <a href=\"./stats.php?stats=profile&type=total\">"._MY."</a>
                </td>
              </tr>
              <tr>
                <td colspan=\"9\">&nbsp;</td>
              </tr>";
########
if ($stats == "overall") {
    $statsFieldsCol="<td width=\"100\" class=\"tableListField\">"._USERS."</td>
                      <td width=\"100\" class=\"tableListField\">"._HOSTS."</td>";
}

if ($stats == "userall") {
    $statsFieldsCol="<td width=\"100\" class=\"tableListField\">"._USERS."</td>";
}

if ($stats == "hostall") {
    $statsFieldsCol="<td width=\"100\" class=\"tableListField\">"._HOSTS."</td>";
}
########
function htmlStatsFields() {
    global $user, $host, $stats, $statsFieldsCol, $statsMenu;
    echo "
                  <table border=\"0\" cellspacing=\"1\" cellpadding=\"0\">
                    $statsMenu
                    <tr>
                      <td colspan=\"9\" class=\"tableListHead\">"._STATISTICS." ".constant(_.strtoupper($stats))." ".$user.$host."</td>
                    </tr>
                    <tr>
                      <td width=\"50\" class=\"tableListField\">"._YEAR."</td>
                      <td width=\"100\" class=\"tableListField\">"._MONTH."</td>
                      $statsFieldsCol
                      <td colspan=\"2\" class=\"tableListField\">"._CACHE.", Bytes</td>
                      <td colspan=\"2\" class=\"tableListField\">"._DOWNLOAD.", Bytes</td>
                      <td width=\"100\" class=\"tableListField\">"._TOTAL.", Bytes</td>
                    </tr>";
}

function htmlStatsMonthFields() {
    global $user, $host, $stats, $statsFieldsCol, $year, $statsData, $statsMenu;
    echo "
                  <table border=\"0\" cellspacing=\"1\" cellpadding=\"0\">
                    $statsMenu
                    <tr>
                      <td colspan=\"8\" class=\"tableListHead\">"._STATISTICS." ".constant(_.strtoupper($stats))." ".$user.$host." : : $statsData[monthNameH] $year</td>
                    </tr>
                    <tr>
                      <td width=\"100\" class=\"tableListField\">"._DAY."</td>
                      $statsFieldsCol
                      <td colspan=\"2\" class=\"tableListField\">"._CACHE.", Bytes</td>
                      <td colspan=\"2\" class=\"tableListField\">"._DOWNLOAD.", Bytes</td>
                      <td width=\"100\" class=\"tableListField\">"._TOTAL.", Bytes</td>
                    </tr>";
}

function htmlStatsDetailFields() {
    global $user, $host, $stats, $day, $year, $statsData, $statsMenu;
    echo "
                  <table border=\"0\" cellspacing=\"1\" cellpadding=\"0\">
                    $statsMenu
                    <tr>
                      <td colspan=\"8\" class=\"tableListHead\">"._STATISTICS." "._DETAIL." ".constant(_.strtoupper($stats))." ".$user.$host." : : $day $statsData[monthNameH] $year</td>
                    </tr>
                    <tr>
                      <td width=\"100\" class=\"tableListField\">"._TIME."</td>
                      <td width=\"100\" class=\"tableListField\">"._USER."</td>
                      <td width=\"100\" class=\"tableListField\">"._HOST."</td>
                      <td width=\"500\" class=\"tableListField\">"._URL."</td>
                      <td width=\"100\" class=\"tableListField\">"._TOTAL.", Bytes</td>
                    </tr>";
}

function htmlStatsClientListMonthFields() {
    global $user, $host, $stats, $monthName, $year, $statsData, $statsMenu;
    echo "
                  <table border=\"0\" cellspacing=\"1\" cellpadding=\"0\">
                    $statsMenu
                    <tr>
                      <td colspan=\"8\" class=\"tableListHead\">"._STATISTICS." ".constant(_.strtoupper($stats))." ".$user.$host." : : $statsData[monthNameH] $year</td>
                    </tr>
                    <tr>
                      <td width=\"100\" class=\"tableListField\">"._USERS."</td>
                      <td colspan=\"2\" class=\"tableListField\">"._CACHE.", Bytes</td>
                      <td colspan=\"2\" class=\"tableListField\">"._DOWNLOAD.", Bytes</td>
                      <td width=\"100\" class=\"tableListField\">"._TOTAL.", Bytes</td>
                    </tr>";
}

function htmlStatsClientListMonthRow() {
    global $client, $statsData, $year, $month;
    $view=$client.view;
    echo "
                    <tr>
                      <td class=\"tableListRow\"><div align=\"left\"><a href=\"./stats.php?stats=$client&$client=$statsData[$client]&type=total&year=$year&month=$month\">$statsData[$client]</a></div></td>
                      <td width=\"100\" class=\"tableListRow\">$statsData[cachebytes]</td>
                      <td width=\"50\" class=\"tableListRow\">$statsData[cachebytes_rate]</td>
                      <td width=\"100\" class=\"tableListRow\">$statsData[loadbytes]</td>
                      <td width=\"50\" class=\"tableListRow\">$statsData[loadbytes_rate]</td>
                      <td class=\"tableListRow\">$statsData[totalbytes]</td>
                    </tr>";
}

function htmlStatsClientListDayFields() {
    global $user, $host, $stats, $day, $monthName, $year, $statsData, $statsMenu;
    echo "
                  <table border=\"0\" cellspacing=\"1\" cellpadding=\"0\">
                    $statsMenu
                    <tr>
                      <td colspan=\"8\" class=\"tableListHead\">"._STATISTICS." ".constant(_.strtoupper($stats))." ".$user.$host." : : $day $statsData[monthNameH] $year</td>
                    </tr>
                    <tr>
                      <td width=\"100\" class=\"tableListField\">"._USERS."</td>
                      <td colspan=\"2\" class=\"tableListField\">"._CACHE.", Bytes</td>
                      <td colspan=\"2\" class=\"tableListField\">"._DOWNLOAD.", Bytes</td>
                      <td width=\"100\" class=\"tableListField\">"._TOTAL.", Bytes</td>
                    </tr>";
}

function htmlStatsClientListDayRow() {
    global $client, $statsData, $year, $month, $day;
    $view=$client.view;
    $stats1=$client;
    echo "
                    <tr>
                      <td class=\"tableListRow\"><div align=\"left\"><a href=\"./stats.php?stats=$client&$client=$statsData[$client]&type=total&year=$year&month=$month&day=$day\">$statsData[$client]</a></div></td>
                      <td width=\"100\" class=\"tableListRow\">$statsData[cachebytes]</td>
                      <td width=\"50\" class=\"tableListRow\">$statsData[cachebytes_rate]</td>
                      <td width=\"100\" class=\"tableListRow\">$statsData[loadbytes]</td>
                      <td width=\"50\" class=\"tableListRow\">$statsData[loadbytes_rate]</td>
                      <td class=\"tableListRow\"><a href=\"./stats.php?stats=$stats1&$client=$statsData[$client]&type=detail&year=$year&month=$month&day=$day\">$statsData[totalbytes]</a></td>
                    </tr>";
}

function htmlStatsRow() {
    global $stats, $statsData;
    if ($statsData[yearTab] == "") {
        $htmlclass="";
    } else {
        $htmlclass=" class=\"tableListRow\"";
    }
    $statRowUser="<td class=\"tableListRow\"><a href=\"./stats.php?stats=userall&type=userlist&year=$statsData[year]&month=$statsData[month]\">$statsData[countuser]</a></td>";
    $statRowHost="<td class=\"tableListRow\"><a href=\"./stats.php?stats=hostall&type=hostlist&year=$statsData[year]&month=$statsData[month]\">$statsData[counthost]</a></td>";
    if ($stats == "overall") {
        $statsRowCol="$statRowUser
                      $statRowHost";
    }
    if ($stats == "userall") {
        $statsRowCol="$statRowUser";
    }
    if ($stats == "hostall") {
        $statsRowCol="$statRowHost";
    }
    echo "
                    <tr>
                      <td$htmlclass>$statsData[yearTab]</td>
                      <td class=\"tableListRow\"><div align=\"left\"><a href=\"./stats.php?stats=$stats&type=total&year=$statsData[year]&month=$statsData[month]\">$statsData[monthName]</a></div></td>
                      $statsRowCol
                      <td width=\"100\" class=\"tableListRow\">$statsData[cachebytes]</td>
                      <td width=\"50\" class=\"tableListRow\">$statsData[cachebytes_rate]</td>
                      <td width=\"100\" class=\"tableListRow\">$statsData[loadbytes]</td>
                      <td width=\"50\" class=\"tableListRow\">$statsData[loadbytes_rate]</td>
                      <td class=\"tableListRow\">$statsData[totalbytes]</td>
                    </tr>";
}

function htmlStatsMonthRow() {
    global $user, $stats, $statsData;
    $statRowUser="<td class=\"tableListRow\"><a href=\"./stats.php?stats=userall&type=userlist&year=$statsData[year]&month=$statsData[month]&day=$statsData[day]\">$statsData[countuser]</a></td>";
    $statRowHost="<td class=\"tableListRow\"><a href=\"./stats.php?stats=hostall&type=hostlist&year=$statsData[year]&month=$statsData[month]&day=$statsData[day]\">$statsData[counthost]</a></td>";
    if ($stats == "overall") {
        $statsRowCol="$statRowUser
                      $statRowHost";
    }
    if ($stats == "userall") {
        $statsRowCol="$statRowUser";
    }
    if ($stats == "hostall") {
        $statsRowCol="$statRowHost";
    }
    if ($stats == "user" or $stats == "host") {
        $htmlurl="&user=$user";
    }
    echo "
                    <tr>
                      <td class=\"tableListRow\"><div align=\"left\">$statsData[day]</div></td>
                      $statsRowCol
                      <td width=\"100\" class=\"tableListRow\">$statsData[cachebytes]</td>
                      <td width=\"50\" class=\"tableListRow\">$statsData[cachebytes_rate]</td>
                      <td width=\"100\" class=\"tableListRow\">$statsData[loadbytes]</td>
                      <td width=\"50\" class=\"tableListRow\">$statsData[loadbytes_rate]</td>
                      <td class=\"tableListRow\"><a href=\"./stats.php?stats=$stats$htmlurl&type=detail&year=$statsData[year]&month=$statsData[month]&day=$statsData[day]\">$statsData[totalbytes]</a></td>
                    </tr>";
}

function htmlStatsDetailRow() {
    global $statsData;
    echo "
                    <tr>
                      <td class=\"tableListRow\"><div align=\"left\">$statsData[time]</div></td>
                      <td class=\"tableListRow\"><div align=\"left\"><a href=\"./user.php?action=userview&user=$statsData[user]\">$statsData[user]</a></div></td>
                      <td class=\"tableListRow\"><div align=\"left\">$statsData[clientip]</div></td>
                      <td class=\"tableListRow\"><div align=\"left\">$statsData[url]</div></td>
                      <td class=\"tableListRow\">$statsData[bytes]</td>
                    </tr>";
}
######## user.php ########
function htmlUserAddDo() {
    echo _USER_ADDED."<br><a href=\"./user.php?action=userlist&sort=user\">"._GOBACK."</a>";
}

function htmlUserEditDo() {
    global $user;
    echo _USER_EDITED."<br><a href=\"./user.php?action=userview&user=$user\">"._GOBACK."</a>";
}

function htmlUserDel() {
    echo _USER_DELETED."<br><a href=\"./user.php?action=userlist&sort=user\">"._GOBACK."</a>";
}

function htmlUserAdd() {
    global $department_list;
    echo "
         <script language=\"JavaScript\" type=\"text/JavaScript\">
         <!--
         function passUser() {
         if (document.form1.passuser.checked) {
         document.form1.password.disabled = true
         document.form1.password2.disabled = true
         document.form1.password.value='';
         document.form1.password2.value='';
         } else {
         document.form1.password.disabled = false
         document.form1.password2.disabled = false
         };
         }
         //-->
         </script>
                  <form name=\"form1\" method=\"post\" action=\"./user.php?action=useradddo\">
                  <table width=\"550\" border=\"0\" cellpadding=\"0\" cellspacing=\"1\">
              <tr>
                <td colspan=\"3\" class=\"tableHead\">"._USER_ADD."<font color=\"#CC0000\"></font></td>
              </tr>
              <tr>
                <td class=\"tableBorder\"><table width=\"100%\" border=\"0\" cellspacing=\"1\" cellpadding=\"0\">
                    <tr>
                      <td width=\"220\" class=\"tableRow\"><div align=\"right\">"._USER_NAME.":</div></td>
                      <td class=\"tableRow\"><input name=\"user\" type=\"text\" class=\"tableInput\" size=\"45\" maxlength=\"15\" value=\"\"></td>
                    </tr>
                    <tr>
                      <td width=\"220\" class=\"tableRow\"><div align=\"right\">"._FULL_NAME.":</div></td>
                      <td class=\"tableRow\"><input name=\"fullname\" type=\"text\" class=\"tableInput\" size=\"45\" maxlength=\"50\" value=\"\"></td>
                    </tr>
                    <tr>
                      <td class=\"tableRow\"><div align=\"right\">"._USERNAME_PASSWORD.":</div></td>
                      <td class=\"tableRow\"><input name=\"passuser\" type=\"checkbox\" value=\"checked\" onClick=\"passUser()\" checked></td>
                    </tr>
                    <tr>
                      <td class=\"tableRow\"><div align=\"right\">"._PASSWORD.":</div></td>
                      <td class=\"tableRow\"><input name=\"password\" type=\"password\" class=\"tableInput\" size=\"45\" maxlength=\"50\" value=\"\" disabled></td>
                    </tr>
                    <tr>
                      <td class=\"tableRow\"><div align=\"right\">"._CONFIRM_PASSWORD.":</div></td>
                      <td class=\"tableRow\"><input name=\"password2\" type=\"password\" class=\"tableInput\" size=\"45\" maxlength=\"50\" value=\"\" disabled></td>
                    </tr>
                    <tr>
                      <td class=\"tableRow\"><div align=\"right\">"._DEPARTMENT.":</div></td>
                      <td class=\"tableRow\"><select name=\"departmentid\" class=\"tableInput\">
                        <option value=\"\" selected></option>
                        $department_list</td>
                    </tr>
                    <tr>
                      <td class=\"tableRow\"><div align=\"right\">"._MAXDAILY.":</div></td>
                      <td class=\"tableRow\"><input name=\"maxdaily\" type=\"text\" class=\"tableInput\" size=\"45\" maxlength=\"50\" value=\"$maxdaily\"></td>
                    </tr>
                    <tr>
                      <td class=\"tableRow\"><div align=\"right\">"._MAXMONTHLY.":</div></td>
                      <td class=\"tableRow\"><input name=\"maxmonthly\" type=\"text\" class=\"tableInput\" size=\"45\" maxlength=\"50\" value=\"$maxmonthly\"></td>
                    </tr>
                    <tr>
                      <td class=\"tableRow\"><div align=\"right\">"._ADMIN.":</div></td>
                      <td class=\"tableRow\"><select name=\"admin1\" class=\"tableInput\">
                        <option value=\"N\" selected>"._NO."</option>
                      <option value=\"Y\">"._YES."</option></td>
                    </tr>
                    <tr>
                      <td class=\"tableRow\">&nbsp;</td>
                      <td class=\"tableRow\">&nbsp;</td>
                    </tr>
                    <tr>
                      <td class=\"tableRow\">&nbsp;</td>
                      <td class=\"tableRow\"><input type=\"hidden\" name=\"proc\" value=\"useradddo\">
                        <input class=\"submitButton\" type=\"submit\" name=\"save\" value=\""._SAVE."\"></td>
                    </tr>
                  </table></td>
              </tr>
            </table>
                  </form>";
}

function htmlUserEdit() {
    global $user, $admin, $fullname, $department_list, $maxdaily, $maxmonthly;
    if ($admin == "Y") {
        $admyes = "selected";
        $admno = "";
    } else {
        $admyes = "";
        $admno = "selected";
    }
    echo "
                  <form name=\"form1\" method=\"post\" action=\"./user.php?action=usereditdo\">
                  <table width=\"550\" border=\"0\" cellpadding=\"0\" cellspacing=\"1\">
              <tr>
                <td colspan=\"3\" class=\"tableHead\">"._USER.": <font color=\"#CC0000\">$user</font></td>
              </tr>
              <tr>
                <td class=\"tableBorder\"><table width=\"100%\" border=\"0\" cellspacing=\"1\" cellpadding=\"0\">
                    <tr>
                      <td width=\"220\" class=\"tableRow\"><div align=\"right\">"._FULL_NAME.":</div></td>
                      <td class=\"tableRow\"><input name=\"fullname\" type=\"text\" class=\"tableInput\" size=\"45\" maxlength=\"50\" value=\"$fullname\"></td>
                    </tr>
                    <tr>
                      <td class=\"tableRow\"><div align=\"right\">"._DEPARTMENT.":</div></td>
                      <td class=\"tableRow\"><select name=\"departmentid\" class=\"tableInput\">
                        <option value=\"\" selected></option>
                        $department_list</td>
                    </tr>
                    <tr>
                      <td class=\"tableRow\"><div align=\"right\">"._MAXDAILY.":</div></td>
                      <td class=\"tableRow\"><input name=\"maxdaily\" type=\"text\" class=\"tableInput\" size=\"45\" maxlength=\"50\" value=\"$maxdaily\"></td>
                    </tr>
                    <tr>
                      <td class=\"tableRow\"><div align=\"right\">"._MAXMONTHLY.":</div></td>
                      <td class=\"tableRow\"><input name=\"maxmonthly\" type=\"text\" class=\"tableInput\" size=\"45\" maxlength=\"50\" value=\"$maxmonthly\"></td>
                    </tr>
                    <tr>
                      <td class=\"tableRow\"><div align=\"right\">"._ADMIN.":</div></td>
                      <td class=\"tableRow\"><select name=\"admin1\" class=\"tableInput\">
                        <option value=\"N\" $admno>"._NO."</option>
                      <option value=\"Y\" $admyes>"._YES."</option></td>
                    </tr>
                    <tr>
                      <td class=\"tableRow\">&nbsp;</td>
                      <td class=\"tableRow\">&nbsp;</td>
                    </tr>
                    <tr>
                      <td class=\"tableRow\">&nbsp;</td>
                      <td class=\"tableRow\"><input type=\"hidden\" name=\"user\" value=\"$user\"><input type=\"hidden\" name=\"proc\" value=\"usereditdo\">
                        <input class=\"submitButton\" type=\"submit\" name=\"save\" value=\""._SAVE."\"></td>
                    </tr>
                  </table></td>
              </tr>
            </table>
                  </form>";
}

function htmlUserListFields() {
    global $sorttype, $usertotal;
    $action=$_GET['action'];
    echo "
            <table border=\"0\" cellspacing=\"1\" cellpadding=\"0\">
              <tr>
                <td colspan=\"7\" class=\"tableListMenu\"><a href=\"./user.php?action=useradd\">"._ADD."</a>
                </td>
              </tr>
              <tr>
                <td colspan=\"7\">&nbsp;</td>
              </tr>
              <tr>
                <td colspan=\"7\" class=\"tableListHead\">"._TOTAL_USERS.": $usertotal</td>
              </tr>
              <tr>
                <td width=\"20\" class=\"tableListField\">No</td>
                <td width=\"150\" class=\"tableListField\"><a href=\"./user.php?action=$action&$sorttype=user\">"._USER_NAME."</a></td>
                <td width=\"250\" class=\"tableListField\"><a href=\"./user.php?action=$action&$sorttype=fullname\">"._FULL_NAME."</a></td>
                <td width=\"250\" class=\"tableListField\"><a href=\"./user.php?action=$action&$sorttype=department\">"._DEPARTMENT."</a></td>
                <td width=\"50\" class=\"tableListField\"><a href=\"./user.php?action=$action&$sorttype=admin\">"._ADMIN."</a></td>
                <td width=\"50\" class=\"tableListField\"><a href=\"./user.php?action=$action&$sorttype=password\">"._LOCK."</a></td>
                <td width=\"50\" class=\"tableListField\">"._DELETE."</td>
              </tr>";
}

function htmlUserListRow() {
    global $usernum, $user, $passlock, $passlock1, $admin, $fullname, $department;
    if ($admin == "Y") {
        $htmlclass = "tableListRowA";
    } else {
        $htmlclass = "tableListRow";
    }
    echo "
              <tr>
                <td class=\"tableListRow\">$usernum</td>
                <td class=\"tableListRow\"><div align=\"left\"><a href=\"./user.php?action=userview&user=$user\">$user</a></div></td>
                <td class=\"tableListRow\"><div align=\"left\">$fullname</div></td>
                <td class=\"tableListRow\"><div align=\"left\">$department</div></td>
                <td class=\"$htmlclass\"><div align=\"center\">$admin</div></td>
                <td class=\"tableListRow\"><div align=\"center\"><a href=\"./user.php?action=$passlock&user=$user\">$passlock1</a></div></td>
                <td class=\"tableListRowA\"><div align=\"center\"><a href=\"./user.php?action=userdel&user=$user\">X</a></div></td>
              </tr>";
}

function htmlUserView() {
    global $user, $admin, $fullname, $department, $maxdaily, $maxmonthly;
    if ($admin == "Y") {
        $htmladmin = _YES;
    } else {
        $htmladmin = _NO;
    }
    echo "
         <table width=\"550\" border=\"0\" cellpadding=\"0\" cellspacing=\"1\">
              <tr>
                <td colspan=\"3\" class=\"tableHead\">"._USER.": <font color=\"#CC0000\">$user</font></td>
              </tr>
              <tr>
                <td class=\"tableBorder\"><table width=\"100%\" border=\"0\" cellspacing=\"1\" cellpadding=\"0\">
                    <tr>
                      <td width=\"220\" class=\"tableRow\"><div align=\"right\">"._FULL_NAME.":</div></td>
                      <td class=\"tableRow\">$fullname</td>
                    </tr>
                    <tr>
                      <td class=\"tableRow\"><div align=\"right\">"._PASSWORD.":</div></td>
                      <td class=\"tableRow\"><a href=\"./user.php?action=changepassword1&user=$user\">"._CHANGE."</a></td>
                    </tr>
                    <tr>
                      <td class=\"tableRow\"><div align=\"right\">"._DEPARTMENT.":</div></td>
                      <td class=\"tableRow\">$department</td>
                    </tr>
                    <tr>
                      <td class=\"tableRow\"><div align=\"right\">"._MAXDAILY.":</div></td>
                      <td class=\"tableRow\">$maxdaily</td>
                    </tr>
                    <tr>
                      <td class=\"tableRow\"><div align=\"right\">"._MAXMONTHLY.":</div></td>
                      <td class=\"tableRow\">$maxmonthly</td>
                    </tr>
                    <tr>
                      <td class=\"tableRow\"><div align=\"right\">"._ADMIN.":</div></td>
                      <td class=\"tableRow\">$htmladmin</td>
                    </tr>
                    <tr>
                      <td class=\"tableRow\">&nbsp;</td>
                      <td class=\"tableRow\">&nbsp;</td>
                    </tr>
                    <tr>
                      <td class=\"tableRow\">&nbsp;</td>
                      <td class=\"tableRow\"><a href=\"./user.php?action=useredit&user=$user\">"._EDIT."</a></td>
                    </tr>
                  </table></td>
              </tr>
            </table>";
}

function htmlProfileView() {
    global $user, $admin, $fullname, $department, $maxdaily, $maxmonthly;
    if ($admin == "Y") {
        $htmladmin = _YES;
    } else {
        $htmladmin = _NO;
    }
    echo "
         <table width=\"550\" border=\"0\" cellpadding=\"0\" cellspacing=\"1\">
              <tr>
                <td colspan=\"3\" class=\"tableHead\">"._USER.": <font color=\"#CC0000\">$user</font></td>
              </tr>
              <tr>
                <td class=\"tableBorder\"><table width=\"100%\" border=\"0\" cellspacing=\"1\" cellpadding=\"0\">
                    <tr>
                      <td width=\"220\" class=\"tableRow\"><div align=\"right\">"._FULL_NAME.":</div></td>
                      <td class=\"tableRow\">$fullname</td>
                    </tr>
                    <tr>
                      <td class=\"tableRow\"><div align=\"right\">"._PASSWORD.":</div></td>
                      <td class=\"tableRow\"><a href=\"./user.php?action=changepassword\">"._CHANGE."</a></td>
                    </tr>
                    <tr>
                      <td class=\"tableRow\"><div align=\"right\">"._DEPARTMENT.":</div></td>
                      <td class=\"tableRow\">$department</td>
                    </tr>
                    <tr>
                      <td class=\"tableRow\"><div align=\"right\">"._MAXDAILY.":</div></td>
                      <td class=\"tableRow\">$maxdaily</td>
                    </tr>
                    <tr>
                      <td class=\"tableRow\"><div align=\"right\">"._MAXMONTHLY.":</div></td>
                      <td class=\"tableRow\">$maxmonthly</td>
                    </tr>
                    <tr>
                      <td class=\"tableRow\"><div align=\"right\">"._ADMIN.":</div></td>
                      <td class=\"tableRow\">$htmladmin</td>
                    </tr>
                    <tr>
                      <td class=\"tableRow\">&nbsp;</td>
                      <td class=\"tableRow\">&nbsp;</td>
                    </tr>
                  </table></td>
              </tr>
            </table>";
}

function htmlChangePasswordDo() {
    global $user;
    echo _PASSWORD_CHANGED."<br><a href=\"./user.php?action=profile\">"._GOBACK."</a>";
}

function htmlChangePasswordDo1() {
    global $user;
    echo _PASSWORD_CHANGED."<br><a href=\"./user.php?action=userview&user=$user\">"._GOBACK."</a>";
}

function htmlChangePasswordFalesCurrent() {
    global $user;
    echo _CURRENT_PASSWORD_FALSE."<br><a href=\"./user.php?action=changepassword\">"._GOBACK."</a>";
}

function htmlChangePasswordFalseConfirm() {
    global $user;
    echo _NEW_AND_CONFIRM_PASSWORD_FALSE."<br><a href=\"./user.php?action=changepassword\">"._GOBACK."</a>";
}

function htmlChangePasswordFalseConfirm1() {
    global $user;
    echo _NEW_AND_CONFIRM_PASSWORD_FALSE."<br><a href=\"./user.php?action=changepassword1&user=$user\">"._GOBACK."</a>";
}

function htmlChangePassword() {
    global $authuser;
    echo "
                  <form name=\"form1\" method=\"post\" action=\"./user.php?action=changepassworddo\">
                  <table width=\"550\" border=\"0\" cellpadding=\"0\" cellspacing=\"1\">
              <tr>
                <td colspan=\"3\" class=\"tableHead\">"._USER.": <font color=\"#CC0000\">$authuser</font></td>
              </tr>
              <tr>
                <td class=\"tableBorder\"><table width=\"100%\" border=\"0\" cellspacing=\"1\" cellpadding=\"0\">
                    <tr>
                      <td width=\"220\" class=\"tableRow\"><div align=\"right\">"._CURRENT_PASSWORD.":</div></td>
                      <td class=\"tableRow\"><input name=\"currentpassword\" type=\"password\" class=\"tableInput\" size=\"45\" maxlength=\"50\"></td>
                    </tr>
                    <tr>
                      <td class=\"tableRow\"><div align=\"right\">"._NEW_PASSWORD.":</div></td>
                      <td class=\"tableRow\"><input name=\"newpassword\" type=\"password\" class=\"tableInput\" size=\"45\" maxlength=\"50\"></td>
                    </tr>
                    <tr>
                      <td class=\"tableRow\"><div align=\"right\">"._CONFIRM_PASSWORD.":</div></td>
                      <td class=\"tableRow\"><input name=\"confirmpassword\" type=\"password\" class=\"tableInput\" size=\"45\" maxlength=\"50\"></td>
                    </tr>
                    <tr>
                      <td class=\"tableRow\">&nbsp;</td>
                      <td class=\"tableRow\">&nbsp;</td>
                    </tr>
                    <tr>
                      <td class=\"tableRow\">&nbsp;</td>
                      <td class=\"tableRow\"><input type=\"hidden\" name=\"user\" value=\"$authuser\"><input type=\"hidden\" name=\"proc\" value=\"changepassworddo\">
                        <input class=\"submitButton\" type=\"submit\" name=\"save\" value=\""._SAVE."\"></td>
                    </tr>
                  </table></td>
              </tr>
            </table>
                  </form>";
}

function htmlChangePassword1() {
    global $user;
    echo "
         <script language=\"JavaScript\" type=\"text/JavaScript\">
         <!--
         function passUser() {
                  if (document.form1.passuser.checked) {
                     document.form1.newpassword.disabled = true
                     document.form1.confirmpassword.disabled = true
                     document.form1.newpassword.value='';
                     document.form1.confirmpassword.value='';
                  } else {
                    document.form1.newpassword.disabled = false
                    document.form1.confirmpassword.disabled = false
                  };
         }
         //-->
         </script>
                  <form name=\"form1\" method=\"post\" action=\"./user.php?action=changepassworddo1\">
                  <table width=\"550\" border=\"0\" cellpadding=\"0\" cellspacing=\"1\">
              <tr>
                <td colspan=\"3\" class=\"tableHead\">"._USER.": <font color=\"#CC0000\">$user</font></td>
              </tr>
              <tr>
                <td class=\"tableBorder\"><table width=\"100%\" border=\"0\" cellspacing=\"1\" cellpadding=\"0\">
                    <tr>
                      <td width=\"220\" class=\"tableRow\"><div align=\"right\">"._USERNAME_PASSWORD.":</div></td>
                      <td class=\"tableRow\"><input name=\"passuser\" type=\"checkbox\" value=\"checked\" onClick=\"passUser()\" checked></td>
                    </tr>
                    <tr>
                      <td class=\"tableRow\"><div align=\"right\">"._NEW_PASSWORD.":</div></td>
                      <td class=\"tableRow\"><input name=\"newpassword\" type=\"password\" class=\"tableInput\" size=\"45\" maxlength=\"50\" value=\"\" disabled></td>
                    </tr>
                    <tr>
                      <td class=\"tableRow\"><div align=\"right\">"._CONFIRM_PASSWORD.":</div></td>
                      <td class=\"tableRow\"><input name=\"confirmpassword\" type=\"password\" class=\"tableInput\" size=\"45\" maxlength=\"50\" value=\"\" disabled></td>
                    </tr>
                    <tr>
                      <td class=\"tableRow\">&nbsp;</td>
                      <td class=\"tableRow\">&nbsp;</td>
                    </tr>
                    <tr>
                      <td class=\"tableRow\">&nbsp;</td>
                      <td class=\"tableRow\"><input type=\"hidden\" name=\"user\" value=\"$user\"><input type=\"hidden\" name=\"proc\" value=\"changepassworddo\">
                        <input class=\"submitButton\" type=\"submit\" name=\"save\" value=\""._SAVE."\"></td>
                    </tr>
                  </table></td>
              </tr>
            </table>
                  </form>";
}

######## department.php ######## 
function htmlDepartmentAddDo() {
    echo _DEPARTMENT_ADDED."<br><a href=\"./department.php?action=departmentlist&sort\">"._DEPARTMENT."</a>";
}

function htmlDepartmentEditDo() {
    echo _DEPARTMENT_EDITED."<br><a href=\"./department.php?action=departmentlist&sort\">"._DEPARTMENT."</a>";
}

function htmlDepartmentDel() {
    echo _DEPARTMENT_DELETED."<br><a href=\"./department.php?action=departmentlist&sort\">"._DEPARTMENT."</a>";
}

function htmlDepartmentAdd() {
    echo "
            <form name=\"form1\" method=\"post\" action=\"./department.php?action=departmentadddo\">
              <table width=\"550\" border=\"0\" cellpadding=\"0\" cellspacing=\"1\">
              <tr>
                <td colspan=\"3\" class=\"tableHead\">"._ADD.": <font color=\"#CC0000\"></font></td>
              </tr>
                <tr>
                  <td class=\"tableBorder\"><table width=\"100%\" border=\"0\" cellspacing=\"1\" cellpadding=\"0\">
                      <tr>
                        <td width=\"220\" class=\"tableRow\"><div align=\"right\">"._DEPARTMENT.":</div></td>
                        <td class=\"tableRow\"><input name=\"department\" type=\"text\" class=\"tableInput\" value=\"\" size=\"45\" maxlength=\"50\"></td>
                      </tr>
                      <tr>
                        <td class=\"tableRow\"><input type=\"hidden\" name=\"proc\" value=\"departmentadddo\">&nbsp;</td>
                        <td class=\"tableRow\"><input class=\"submitButton\" type=\"submit\" name=\"save\" value=\""._SAVE."\"></td>
                      </tr>
                    </table></td>
                </tr>
              </table>
            </form>";
}

function htmlDepartmentEdit() {
    global $departmentid, $department;
    echo "
            <form name=\"form1\" method=\"post\" action=\"./department.php?action=departmenteditdo\">
              <table width=\"550\" border=\"0\" cellpadding=\"0\" cellspacing=\"1\">
              <tr>
                <td colspan=\"3\" class=\"tableHead\">"._EDIT.": <font color=\"#CC0000\"></font></td>
              </tr>
                <tr>
                  <td class=\"tableBorder\"><table width=\"100%\" border=\"0\" cellspacing=\"1\" cellpadding=\"0\">
                      <tr>
                        <td width=\"220\" class=\"tableRow\"><div align=\"right\">"._DEPARTMENT.":</div></td>
                        <td class=\"tableRow\"><input name=\"department\" type=\"text\" class=\"tableInput\" value=\"$department\" size=\"45\" maxlength=\"50\"></td>
                      </tr>
                      <tr>
                        <td class=\"tableRow\"><input type=\"hidden\" name=\"proc\" value=\"departmenteditdo\">
                            <input type=\"hidden\" name=\"departmentid\" value=\"$departmentid\">&nbsp;</td>
                        <td class=\"tableRow\"><input class=\"submitButton\" type=\"submit\" name=\"save\" value=\""._SAVE."\"></td>
                      </tr>
                    </table></td>
                </tr>
              </table>
            </form>";
}

function htmlDepartmentListFields() {
    global $action, $sorttype, $departmenttotal;
    echo "
            <table border=\"0\" cellspacing=\"1\" cellpadding=\"0\">
              <tr>
                <td colspan=\"3\" class=\"tableListMenu\"><a href=\"./department.php?action=departmentadd\">"._ADD."</a>
                </td>
              </tr>
              <tr>
                <td colspan=\"3\">&nbsp;</td>
              </tr>
              <tr>
                <td colspan=\"3\" class=\"tableListHead\">"._TOTAL_DEPARTMENTS.": $departmenttotal</td>
              </tr>
              <tr>
                <td width=\"20\" class=\"tableListField\">No</td>
                <td width=\"250\" class=\"tableListField\"><a href=\"./department.php?action=$action&$sorttype\">"._DEPARTMENT."</a></td>
                <td width=\"50\" class=\"tableListField\">"._DELETE."</td>
              </tr>";
}

function htmlDepartmentListRow() {
    global $departnum, $departmentid, $department;
    echo "
              <tr>
                <td class=\"tableListRow\">$departnum</td>
                <td class=\"tableListRow\"><div align=\"left\"><a href=\"./department.php?action=departmentedit&departmentid=$departmentid\">$department</a></div></td>
                <td class=\"tableListRowA\">
                <div align=\"center\"><a href=\"./department.php?action=departmentdel&departmentid=$departmentid\">X</a></div></td>
              </tr>";
}

?>
