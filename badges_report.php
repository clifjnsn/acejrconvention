<?php

//Connect to your database
  $dbhost = 'localhost';
  $dbname = 'conventions';
  $dbuser = 'convention';
  $dbpass = 'Ch@ngeme!';

  $mysql_handle = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname)
    or die("Error connecting to database server");


$result = "select convention_student.*,convention_schools.name from convention_student,convention_schools where ";
$result .= "convention_schools.id = convention_student.schoolid order by convention_student.last";
$students=mysqli_query($mysql_handle, $result);
$csv_output = "\"First\",\"Last\",\"School Name\",\"Type\"\n";
while($row = mysqli_fetch_assoc($students))
{
  $csv_output .= "\"".$row['first']."\",\"".$row['last']."\",\"".$row['name']."\",\"".$row['type']."\"\n";
}
mysqli_close($mysql_handle);

header("Content-type: application/vnd.ms-excel");
header("Content-disposition: attachment; filename=badges.csv");
print $csv_output;
?>
