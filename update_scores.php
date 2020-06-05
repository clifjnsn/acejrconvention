<?php

include_once 'convention_db.php';

function f_error ($sql_query)
{
        print "<P>An error occured accessing the database.</P>";
        echo $sql_query;
        exit;
}

$eventid = $_GET['eventid'];

$color1 = "#F4FA58";
$color2 = "#FAAC58";
$color=$color1;

$sql_query = "select convention_event.id as event,convention_event.name,convention_scores.* from convention_scores,convention_event where ";
$sql_query .= " convention_event.id = convention_scores.eventid and convention_event.id = ".$eventid." order by convention_event.id";
$result=mysqli_query($mysql_handle, $sql_query);
if (!$result) { f_error($sql_query); }

$alternate=0;
echo "<form name=\"update\" method=\"post\" action=\"save_scores.php\">";
echo "<table>";
echo "<tr bgcolor=\"#A0A0A0\"><td><B>Event<br>Code</td><td><B>Event Name</td><td><B>Student Name</td><td><B>Placement</td><td><B>Event Time</td><td><B>Group</td><td><B>Command<br>Performance</td><td><B>CAPS</td></tr></b>";
$i = 0;
while($row = mysqli_fetch_assoc($result)) {
  echo "<input type='hidden' name='id[$i]' value='{$row['id']}' >";
  echo "<tr bgcolor=\"",$color,"\">";
  echo "<td>",$row['event']," </td>";
  if ($row['sex']) {
    echo "<td>",$row['name']," (",$row['sex'],") </td>";
  } else {
    echo "<td>",$row['name']," </td>";
  }
  $sql_query2 = "select first,last,schoolid from convention_student where id = ".$row['studentid'];
  $result2=mysqli_query($mysql_handle, $sql_query2);
  $student = mysqli_fetch_assoc($result2);
  $sql_query3 = "select name from convention_schools where id = ".$student['schoolid'];
  $result3=mysqli_query($mysql_handle, $sql_query3);
  $school = mysqli_fetch_assoc($result3);
  echo "<td>",$student['first']," ",$student['last']," (",$school['name'],") </td>";
  echo "<td align=\"center\"><input name=\"place[$i]\" type=\"text\" value=",$row['place'],"></td>";
  echo "<td align=\"center\"><input name=\"time[$i]\" type=\"text\"  value=",$row['time'],"></td>";
  echo "<td align=\"center\"><input name=\"team[$i]\" type=\"text\"  value=",$row['team'],"></td>";
//  echo "<td align=\"center\"><input name=\"command[]\" type=\"text\" id=\"command\" value=",$row['command'],"></td>";
  echo "<td>",$row['command'],"</td>";
  echo "<td>",$row['caps']," </td></tr>";
  if ($alternate==1) { $alternate=0; $color=$color1; } else { $alternate=1; $color=$color2; }
  ++$i;
}
echo "</table><input type=\"submit\" name=\"Submit\" value=\"Submit\"></form>";
mysqli_free_result($result);
mysqli_close($mysql_handle);
?>
