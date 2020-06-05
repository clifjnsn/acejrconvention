<?php

include_once 'convention_db.php';

function f_error ()
{
        print "<P>An error occured accessing the database.</P>";
        exit;
}

$sql_query = mysqli_query($mysql_handle, "select * from convention_student where id = ".$_GET['studentid']);
$row=mysqli_fetch_row($sql_query);
echo "<form name=\"add\" action=\"save_student.php\" method=\"GET\">";
echo "<input type=\"hidden\" name=\"studentid\" value=\"".$_GET['studentid']."\">";
echo "First Name : <input type=\"text\" name=\"first\" value=\"".$row[2]."\"><br>";
echo "Last Name : <input type=\"text\" name=\"last\" value=\"".$row[3]."\"><br>";
echo "Age : <input type=\"text\" name=\"age\" value=\"".$row[4]."\"><br>";
echo "Sex : <input type=\"text\" name=\"sex\" value=\"".$row[5]."\"><br>";
echo "Meals : <input type=\"text\" name=\"meals\" value=\"".$row[6]."\"><br>";
echo "<input type=\"submit\" value=\"Update Person\">";

mysqli_close($mysql_handle);
?>
