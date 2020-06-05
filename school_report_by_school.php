<?php

include_once 'convention_db.php';

function f_error ()
{
        print "<P>An error occured accessing the database.</P>";
        exit;
}

$color1 = "#F4FA58";
$color2 = "#FAAC58";
$color=$color1;
echo "<P><B>Choose a school : </B>";
echo "<table><tr bgcolor=\"#A0A0A0\"><td><B>Name</td></tr>";
$sql_query = "select * from convention_schools order by name";
$result=mysqli_query($mysql_handle, $sql_query);
if (!$result) { f_error(); }
$alternate=0;
while($row = mysqli_fetch_assoc($result)) {
  echo "<tr bgcolor=\"",$color,"\">";
  echo "<td><A HREF=\"school_report.php?schoolid=",$row['id'],"\">",$row['name'],"</A> </td>";
  if ($alternate==1) { $alternate=0; $color=$color1; } else { $alternate=1; $color=$color2; }
}

echo "</table>";
mysqli_free_result($result);
mysqli_close($mysql_handle);
?>
