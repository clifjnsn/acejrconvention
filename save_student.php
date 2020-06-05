<?php

include_once 'convention_db.php';

function f_error ()
{
        print "<P>An error occured accessing the database.</P>";
        exit;
}

$first = $_GET['first'];
$last = $_GET['last'];
$age = $_GET['age'];
$sex = $_GET['sex'];
$meals = $_GET['meals'];

echo "First: ",$first,"<br>";
echo "Last: ",$last,"<br>";
echo "Age: ",$age,"<br>";
echo "Sex: ",$sex,"<br>";
echo "Meals: ",$meals,"<br>";

$sql_query = "update convention_student set first = '".$first."', last = '".$last."', age = ".$age.", ";
$sql_query .= "sex = '".$sex."', meals = ".$meals." where id = ".$_GET['studentid'];
$result = mysqli_query($mysql_handle, $sql_query);

if (!$result) { f_error($sql_query); }

echo "<br><br><B>Student/Sponsor Information Updated.<br>";

mysqli_close($mysql_handle);
?>
