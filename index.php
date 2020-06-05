<?php

include_once 'convention_db.php';

function f_error ()
{
        print "<P>An error occured accessing the database.</P>";
        exit;
}

if($_GET['name']) {
  $sql_query = "insert into convention_schools values (0,\"".$_GET['name']."\",\"";
  $sql_query .= $_GET['address']."\",\"".$_GET['city']."\",\"";
  $sql_query .= $_GET['state']."\",\"".$_GET['zip']."\",\"";
  $sql_query .= $_GET['phone']."\",\"".$_GET['contact']."\",\"".$_GET['email']."\");";
  $result=mysqli_query($mysql_handle, $sql_query);
  if ($result != 1) { f_error(); }
  echo "<P>New school added!</P>";
}
$color1 = "#F4FA58";
$color2 = "#FAAC58";
$color=$color1;
echo "<P><B>Choose a school : </B><I>(To add students/sponsors and events)</I>";
echo "<table><tr bgcolor=\"#A0A0A0\"><td> </td><td><B>Name</td><td><B>Address</td><td><B>City</td><td><B>State</td><td><B>Contact</td><td><B>E-Mail</td><td><B>Payments</td></tr>";
$sql_query = "select * from convention_schools order by name";
$result=mysqli_query($mysql_handle, $sql_query);
if (!$result) { f_error(); }
$alternate=0;
while($row = mysqli_fetch_assoc($result)) {
  echo "<tr bgcolor=\"",$color,"\"><td><A HREF=\"update_school.php?id=",$row['id'],"\">[edit]</A> </td>";
  echo "<td><A HREF=\"students.php?id=",$row['id'],"\">",$row['name'],"</A> </td>";
  echo "<td>",$row['address']," </td>";
  echo "<td>",$row['city']," </td>";
  echo "<td>",$row['state']," </td>";
  echo "<td>",$row['contact']," </td>";
  echo "<td>",$row['email']," </td>";
  echo "<td><A HREF=\"payments.php?id=",$row['id'],"\">[payments]</A></td></tr>";
  if ($alternate==1) { $alternate=0; $color=$color1; } else { $alternate=1; $color=$color2; }
}

echo "</table>";
$sql_query = mysqli_query($mysql_handle, "select count(*) from convention_student where type = 'Student'");
$num_of_students = mysqli_fetch_row($sql_query);
echo "<p><B>Registration Information :<br></b> Number of Students: ",$num_of_students[0],"<br>";
$sql_query = mysqli_query($mysql_handle, "select count(*) from convention_student where type = 'Sponsor'");
$num_of_sponsors = mysqli_fetch_row($sql_query);
echo " Number of Sponsors: ",$num_of_sponsors[0],"<br>";
$sql_query = mysqli_query($mysql_handle, "select sum(meals) from convention_student where type = 'Guest'");
$num_of_meals = mysqli_fetch_row($sql_query);
echo " Number of Guest meals: ",$num_of_meals[0],"<p>";

$sql_query = "select distinct eventid from convention_scores;";
$result=mysqli_query($mysql_handle, $sql_query);
$active_events = array();
$index=0;
while($row=mysqli_fetch_assoc($result)) {
  $active_events[$index]=$row['eventid'];
  $index++;
}
mysqli_free_result($result);

echo "</table>";
echo "<br><br><hr><br><br><B>Add New School</B> :<br>";
echo "<form name=\"add\" action=\"index.php\" method=\"GET\">";
echo "School Name : <input type=\"text\" name=\"name\"><br>";
echo "Address : <input type=\"text\" name=\"address\"><br>";
echo "City : <input type=\"text\" name=\"city\"><br>";
echo "State : <input type=\"text\" name=\"state\"><br>";
echo "Zip Code : <input type=\"text\" name=\"zip\"><br>";
echo "Phone : <input type=\"text\" name=\"phone\"><br>";
echo "Contact Person : <input type=\"text\" name=\"contact\"><br>";
echo "E-mail  : <input type=\"text\" name=\"email\"><br>";
echo "<input type=\"submit\" value=\"Add School\">";
echo "<table><tr><td>";
echo "</td></tr></table></form>";

echo "<br><br><B>Update Placements/Times for Event</B> :<br>\n";
echo "<form name=\"add\" action=\"update_scores.php\" method=\"GET\">\n";
echo "<select name=\"eventid\">\n";
$sql_query = "select * from convention_event where id in (".implode(",",$active_events).") order by id;";
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
echo "<input type=\"submit\" value=\"Update Scores/Times\">\n";
echo "</form>\n";
mysqli_free_result($result);
mysqli_close($mysql_handle);
?>
