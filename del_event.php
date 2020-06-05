<?php

include_once 'convention_db.php';

function f_error ($sql_query)
{
        print "<P>An error occured accessing the database.</P>";
        echo $sql_query;
        exit;
}

$studentid=(int)$_GET['studentid'];
$eventid=(int)$_GET['eventid'];
$schoolid=(int)$_GET['schoolid'];

$sql_query = "delete from convention_scores where studentid = ".$studentid." and eventid=".$eventid.";";
$result=mysqli_query($mysql_handle, $sql_query);
if (!$result) { f_error($sql_query); }

$sql_query = "select name  from convention_event where id = ".$eventid.";";
$result=mysqli_query($mysql_handle, $sql_query);
if (!$result) { f_error($sql_query); }
$row = mysqli_fetch_assoc($result);

$deleted_event = $row['name'];

$sql_query = "select first,last from convention_student where id = ".$studentid.";";
$result=mysqli_query($mysql_handle, $sql_query);
if (!$result) { f_error($sql_query); }
$row = mysqli_fetch_assoc($result);
$student_name = $row['first']." ".$row['last'];

echo "Deleted event: <b>".$deleted_event."</b> for student: <b>".$student_name."</b>\n";

mysqli_free_result($result);
mysqli_close($mysql_handle);

echo "<br><br><A HREF=\"events.php?schoolid=",$schoolid,"\&studentid=",$studentid,"\"><---Back to Event List for ",$student_name,"</A>\n";
?>
