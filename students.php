<?php

include_once 'convention_db.php';

function f_error ()
{
        print "<P>An error occured accessing the database.</P>";
        exit;
}

if($_GET['first']) {
  $sql_query = "insert into convention_student values (0,".$_GET['id'].",\"";
  $sql_query .= $_GET['first']."\",\"".$_GET['last']."\",";
  $sql_query .= (int)$_GET['age'].",\"".$_GET['sex']."\",";
  $sql_query .= "\"".(int)$_GET['meals']."\",\"".$_GET['type']."\");";
  $result=mysqli_query($mysql_handle, $sql_query);
  if ($result != 1) { f_error(); }
  echo "<P>New student added!</P>";
}

$color1 = "#F4FA58";
$color2 = "#FAAC58";
$color=$color1;
$sql_query = "select * from convention_schools where id = ".(int)$_GET['id'];
$schoolid = (int)$_GET['id'];
$result=mysqli_query($mysql_handle, $sql_query);
if (!$result) { f_error(); }
$row = mysqli_fetch_assoc($result);
echo "<B>SCHOOL:</B><br>";
echo $row['name'],"<br>";
echo $row['address'],"<br>";
echo $row['city'],", ",$row['state'],"  ",$row['zip'],"<br>";
echo $row['email'],"<br><br>";
echo "<B>Choose student : </B><I>(To Add/Remove Events)</I>";
echo "<table><tr bgcolor=\"#A0A0A0\"><td><B>First</td><td><B>Last</td><td><B>Age</td><td><B>Sex</td><td><B>Meals</td><td><B>Type</td><td></td><td></td></tr>";
$sql_query = "select * from convention_student where schoolid = ".(int)$_GET['id']." order by last";
$result=mysqli_query($mysql_handle, $sql_query);
if (!$result) { f_error(); }
$alternate=0;
while($row = mysqli_fetch_assoc($result)) {
if($row['type'] == "Student") {
  echo "<tr bgcolor=\"",$color,"\"><td><A HREF=\"events.php?studentid=",$row['id'],"&schoolid=",$schoolid,"\">",$row['first']," </td>";
  if ($alternate==1) { $alternate=0; $color=$color1; } else { $alternate=1; $color=$color2; }
  }
else {
  echo "<tr bgcolor=\"#A0A0A0\"><td>",$row['first']," </td>";
}
echo "<td>",$row['last']," </td>";
echo "<td>",$row['age']," </td>";
echo "<td>",$row['sex']," </td>";
echo "<td>",$row['meals']," </td>";
echo "<td>",$row['type']," </td>";
echo "<td><A HREF=\"update_student.php?studentid=".(int)$row["id"]."\">[edit]</A></td>";
echo "<td><A HREF=\"del_student.php?studentid=".(int)$row["id"]."&schoolid=".(int)$_GET['id']."\">[delete]</A></td></tr>";
}
echo "</table><br><br>";
echo "<A HREF=\"index.php\"><----- Back to School List</A>";
mysqli_free_result($result);
mysqli_close($mysql_handle);
echo "<br><br><hr><br><br><B>Add New Person</B> :<br>";
echo "<form name=\"add\" action=\"students.php\" method=\"GET\">";
echo "<input type=\"hidden\" name=\"id\" value=\"",$schoolid,"\">";
echo "First Name : <input type=\"text\" name=\"first\"><br>";
echo "Last Name : <input type=\"text\" name=\"last\"><br>";
echo "Age : <input type=\"text\" name=\"age\"><br>";
echo "Sex : <input type=\"radio\" name=\"sex\" value=\"M\">Male <input type=\"radio\" name=\"sex\" value=\"F\">Female<br>";
echo "Meals : <input type=\"text\" name=\"meals\"> (enter total number of meals to be purchased)<br>";
echo "Type : <input type=\"radio\" name=\"type\" value=\"Student\">Student <input type=\"radio\" name=\"type\" value=\"Sponsor\">Sponsor <input type=\"radio\" name=\"type\" value=\"Guest\">Guest<br>";
echo "<input type=\"submit\" value=\"Add Person\">";
echo "<table><tr><td>";
echo "</td></tr></table></form>";
?>
