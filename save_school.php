<?php

include_once 'convention_db.php';

function f_error ()
{
        print "<P>An error occured accessing the database.</P>";
        exit;
}

$name = $_GET['name'];
$address = $_GET['address'];
$city = $_GET['city'];
$state = $_GET['state'];
$zip = $_GET['zip'];
$phone = $_GET['phone'];
$contact = $_GET['contact'];
$email = $_GET['email'];

echo "School: ",$name,"<br>";
echo "Address: ",$address,", ",$city,", ",$state,", ",$zip,"<br>";
echo "Phone: ",$phone,"<br>";
echo "Contact: ",$contact,"<br>";
echo "E-mail: ",$email,"<br>";

$sql_query = "update convention_schools set name = '".$name."', address = '".$address."', city = '".$city."',";
$sql_query .= "state = '".$state."', zip = '".$zip."', phone = '".$phone."',";
$sql_query .= "contact = '".$contact."', email = '".$email."' where id = ".$_GET['id'];
$result = mysqli_query($mysql_handle, $sql_query);

if (!$result) { f_error($sql_query); }

echo "<br><br><B>School Information Updated.<br>";

mysqli_close($mysql_handle);
?>
