<?php

include_once 'convention_db.php';

function f_error ()
{
        print "<P>An error occured accessing the database.</P>";
        exit;
}

if($_GET['amount']) {
  $sql_query = "insert into convention_payments values ('".(int)$_GET['id']."',";
  $sql_query .= "'".$_GET['amount']."','".$_GET['type']."');";
  $result=mysqli_query($mysql_handle, $sql_query);
  if ($result != 1) { f_error(); }
  echo "<P>Payment Added!</P>";
}

$students = 0;
$guests = 0;
$sponsors = 0;
$totals = 0;
$payments = 0;

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
mysqli_free_result($result);

// Students
echo "<table><tr><td colspan=4><B><center>STUDENTS</center></td></tr><tr><td><B>First</td><td><B>Last</td><td><B>Age</td><td><B>Amount</td></tr>";
$sql_query = "select * from convention_student where schoolid = ".(int)$_GET['id']." and type = 'Student'";
$result=mysqli_query($mysql_handle, $sql_query);
if (!$result) { f_error(); }
while($row = mysqli_fetch_assoc($result)) {
echo "<tr><td>",$row['first']," </td>";
echo "<td>",$row['last']," </td>";
echo "<td>",$row['age']," </td>";
echo "<td> $60.00 </td></tr>";
$students = $students + 60;
}
echo "</table><br><br>";
mysqli_free_result($result);

// Sponsors
echo "<table><tr><td colspan=4><B><center>SPONSORS</center></td</tr><tr><td><B>First</td><td><B>Last</td><td><B>Male/Female</td><td><B>Amount</td></tr>";
$sql_query = "select * from convention_student where schoolid = ".(int)$_GET['id']." and type = 'Sponsor'";
$result=mysqli_query($mysql_handle, $sql_query);
if (!$result) { f_error(); }
while($row = mysqli_fetch_assoc($result)) {
echo "<tr><td>",$row['first']," </td>";
echo "<td>",$row['last']," </td>";
echo "<td>",$row['sex']," </td>";
echo "<td> $60.00 </td></tr>";
$students = $students + 60;
}
echo "</table><br><br>";
mysqli_free_result($result);

// Guest Meals
$sql_query = "select * from convention_student where schoolid = ".(int)$_GET['id']." and type = 'Guest'";
$result=mysqli_query($mysql_handle, $sql_query);
if (!$result) { f_error(); }
while($row = mysqli_fetch_assoc($result)) {
$guests = $guests + (int)$row['meals'];
}
mysqli_free_result($result);
echo "<p><b>Total Guest Meals :</b>",$guests," @ $7.00 each = $",$guests*7,".00 </p>";
$totals = ($guests *7) + $students;
echo "<p><b>Total Due :</b> $",$totals,".00 </p>";

// Payments
$sql_query = "select * from convention_payments where schoolid = ".(int)$_GET['id'];
$result=mysqli_query($mysql_handle, $sql_query);
if (!$result) { f_error(); }
while($row = mysqli_fetch_assoc($result)) {
  $payments = $payments + $row['amount'];
}
echo "<p><b>Payments Applied :</b> $",$payments,".00 </p>";
echo "<p><b>Balance Due :</b> $",$totals-$payments,".00 </p>";
echo "<br><br><A HREF=\"school_receipt.php?id=".(int)$_GET['id']."\">Print Receipt</A>";
echo "<br><br><hr><br><br><B>Add New Payment</B> :<br>";
echo "<form name=\"add\" action=\"payments.php\" method=\"GET\">";
echo "<input type=\"hidden\" name=\"id\" value=\"".(int)$_GET['id']."\" >";
echo "Amount : <input type=\"text\" name=\"amount\"><br>";
echo "Payment Type : <input type=\"radio\" name=\"type\" value=\"Cash\">Cash <input type=\"radio\" name=\"type\" value=\"Check\">Check <input type=\"radio\" name=\"type\" value=\"Discount\">Discount <br>";
echo "<input type=\"submit\" value=\"Add Payment\">";
echo "<table><tr><td>";
echo "</td></tr></table></form>";

mysqli_close($mysql_handle);
?>
