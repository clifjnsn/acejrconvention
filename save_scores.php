<?php

include_once 'convention_db.php';

function f_error ($sql_query)
{
        print "<P>An error occured accessing the database.</P>";
        echo $sql_query;
        exit;
}

$size = count($_POST['place']);
$i=0;
while($i < $size) {
  $place = $_POST['place'][$i];
  $time = $_POST['time'][$i];
  $team = $_POST['team'][$i];
  $id = $_POST['id'][$i];

  $sql_query = "update convention_scores set place = '".$place."' where id = '".$id."' LIMIT 1";
  $result = mysqli_query($mysql_handle, $sql_query) or die ("Error Saving data in query: $sql_query");
  $sql_query = "update convention_scores set time = '".$time."' where id = '".$id."' LIMIT 1";
  $result = mysqli_query($mysql_handle, $sql_query) or die ("Error Saving data in query: $sql_query");
  $sql_query = "update convention_scores set team = '".$team."' where id = \"".$id."\" LIMIT 1";
  $result = mysqli_query($mysql_handle, $sql_query) or die ("Error Saving data in query: $sql_query");
  ++$i;
}


echo "<br>Scores saved...<br>";
$sql_query = "select distinct eventid from convention_scores;";
$result=mysqli_query($mysql_handle, $sql_query);
$active_events = array();
$index=0;
while($row=mysqli_fetch_assoc($result)) {
  $active_events[$index]=$row['eventid'];
  $index++;
}
mysqli_free_result($result);

echo "<br><br><B>Update Placements/Scores for Event</B> :<br>\n";
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
echo "<input type=\"submit\" value=\"Update Scores\">\n";
echo "</form>\n";

mysqli_free_result($result);
mysqli_close($mysql_handle);

?>
