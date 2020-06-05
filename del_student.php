<?php

include_once 'convention_db.php';

function f_error ($sql_query)
{
        print "<P>An error occured accessing the database.</P>";
        echo $sql_query;
        exit;
}

$studentid=(int)$_GET['studentid'];
$schoolid=(int)$_GET['schoolid'];

$sql_query = "delete from convention_scores where studentid = ".$studentid.";";
$result=mysqli_query($mysql_handle, $sql_query);
if (!$result) { f_error($sql_query); }
echo "<b>Deleted Events.<br>";

$sql_query = "delete from convention_student where id = ".$studentid.";";
$result=mysqli_query($mysql_handle, $sql_query);
if (!$result) { f_error($sql_query); }
echo "<b>Deleted Student Entry.<br></b>";

mysqli_close($mysql_handle);

echo "<A HREF=\"students.php?id=".(int)$_GET['schoolid']."\"><----- Back to Student List</A>";

?>
