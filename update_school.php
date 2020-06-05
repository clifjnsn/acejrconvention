<?php

include_once 'convention_db.php';

function f_error ()
{
        print "<P>An error occured accessing the database.</P>";
        exit;
}

$sql_query = mysqli_query($mysql_handle, "select * from convention_schools where id = ".$_GET['id']);
$row=mysqli_fetch_row($sql_query);
echo "<form name=\"add\" action=\"save_school.php\" method=\"GET\">";
echo "<input type=\"hidden\" name=\"id\" value=\"".$_GET['id']."\">";
echo "School Name : <input type=\"text\" name=\"name\" value=\"".$row[1]."\"><br>";
echo "Address : <input type=\"text\" name=\"address\" value=\"".$row[2]."\"><br>";
echo "City : <input type=\"text\" name=\"city\" value=\"".$row[3]."\"><br>";
echo "State : <input type=\"text\" name=\"state\" value=\"".$row[4]."\"><br>";
echo "Zip Code : <input type=\"text\" name=\"zip\" value=\"".$row[5]."\"><br>";
echo "Phone : <input type=\"text\" name=\"phone\" value=\"".$row[6]."\"><br>";
echo "Contact Person : <input type=\"text\" name=\"contact\" value=\"".$row[7]."\"><br>";
echo "E-mail  : <input type=\"text\" name=\"email\" value=\"".$row[8]."\"><br>";
echo "<input type=\"submit\" value=\"Update School\">";

mysqli_close($mysql_handle);
?>
