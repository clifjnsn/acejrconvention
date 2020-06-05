<?php

include_once 'convention_db.php';

function f_error ($sql_query)
{
        print "<P>An error occured accessing the database.</P>";
        echo $sql_query;
        exit;
}

//if((int)$_GET['entries'] > 6) {
//  echo "<B>ERROR: Students cannot enroll in more than 6 events (excluding Bible Bowl and PACE Bowl).<br>\n";
//  exit;
//}

if($_GET['eventid']) {
  $sql_query = "insert into convention_scores values (0,".(int)$_GET['studentid'].",".(int)$_GET['eventid'].",0,'','',0,0);";
  $result=mysqli_query($mysql_handle, $sql_query);
  if ($result != 1) { f_error($sql_query); }
  echo "<P>New event for student added!</P>";
}

$color1 = "#F4FA58";
$color2 = "#FAAC58";
$num_entries = 0;
$color=$color1;
$sql_query = "select * from convention_schools where id = ".(int)$_GET['schoolid'];
$result=mysqli_query($mysql_handle, $sql_query);
if (!$result) { f_error($sql_query); }
$row = mysqli_fetch_assoc($result);
echo "<B>SCHOOL:</B><br>";
echo $row['name'],"<br>";
echo $row['address'],"<br>";
echo $row['city'],", ",$row['state'],"  ",$row['zip'],"<br>";
echo $row['email'],"<br><br>";
$sql_query = "select * from convention_student where id = ".(int)$_GET['studentid'];
$result=mysqli_query($mysql_handle, $sql_query);
if (!$result) { f_error($sql_query); }
$row = mysqli_fetch_assoc($result);
echo "<B>STUDENT NAME:</B><br>";
echo $row['last'],", ",$row['first'],"<br><br>";

$sql_query = "select convention_event.id as event,convention_event.name,convention_event.sex,convention_scores.* from convention_scores,convention_event where ";
$sql_query .= " convention_scores.studentid= ".(int)$_GET['studentid']." and convention_event.id = convention_scores.eventid order by convention_scores.eventid;";
$result=mysqli_query($mysql_handle, $sql_query);
if (!$result) { f_error($sql_query); }

$alternate=0;
echo "<table>";
echo "<tr bgcolor=\"#A0A0A0\"><td><B>Event<br>Code</td><td><B>Event Name</td><td><B>Placement</td><td><B>Event Time</td><td><B>Group</td><td><B>Command<br>Performance</td><td><B>CAPS</td><td> </td><td> </td></tr></b>";
while($row = mysqli_fetch_assoc($result)) {
if($row['event'] != 209 && $row['event'] != 201) { $num_entries++; }
echo "<tr bgcolor=\"",$color,"\">";
echo "<td>",$row['event']," </td>";
if ($row['sex']) {
  echo "<td>",$row['name']," (",$row['sex'],") </td>";
} else {
  echo "<td>",$row['name']," </td>";
}
echo "<td>",$row['place']," </td>";
echo "<td>",$row['time']," </td>";
echo "<td>",$row['team']," </td>";
echo "<td>",$row['command']," </td>";
echo "<td>",$row['caps']," </td>";
echo "<td><A HREF=\"del_event.php?studentid=".$row['studentid']."\&eventid=".$row['eventid']."\&schoolid=".(int)$_GET['schoolid']."\">[delete]</A></td>";
echo "<td><A HREF=\"update_scores.php?eventid=".$row['eventid']."\">[update]</A></td></tr>";
if ($alternate==1) { $alternate=0; $color=$color1; } else { $alternate=1; $color=$color2; }
}
echo "</table>";
echo "<A HREF=\"students.php?id=",(int)$_GET['schoolid'],"\"><----- Back to Student List</A>";

echo "<br><br><hr><br><br><B>Add An Event For Student</B> :<br>\n";
echo "<form name=\"add\" action=\"events.php\" method=\"GET\">\n";
echo "<input type=\"hidden\" name=\"entries\" value=\"",$num_entries,"\">\n";
echo "<input type=\"hidden\" name=\"schoolid\" value=\"",(int)$_GET['schoolid'],"\">\n";
echo "<input type=\"hidden\" name=\"studentid\" value=\"",(int)$_GET['studentid'],"\">\n";
echo "<select name=\"eventid\">\n";
$sql_query = "select * from convention_event order by id;";
$result=mysqli_query($mysql_handle, $sql_query);
if (!$result) { f_error($sql_query); }
while($row = mysqli_fetch_assoc($result)) {
  if ($row['sex']) {
    echo "<option value=\"".$row['id']."\">".$row['id']." - ".$row['name']." (".$row['sex'].")"."</option>\n";
  } else {
    echo "<option value=\"".$row['id']."\">".$row['id']." - ".$row['name']."</option>\n";
  }  
}
echo "</select>\n";
echo "<input type=\"submit\" value=\"Add Event\">\n";
echo "</form>\n";
mysqli_free_result($result);
mysqli_close($mysql_handle);
?>
