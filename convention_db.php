<?php

  $dbhost = 'localhost';
  $dbname = 'conventions';
  $dbuser = 'convention';
  $dbpass = 'Ch@ngeme!';

  $mysql_handle = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname)
    or die("Error connecting to database server");

  echo "<P><CENTER><B>ACE Jr Convention<br>Master Control</B></P>";
  echo "<table><tr><td bgcolor=\"#CC6600\"> MENU :: </td><td bgcolor=\"#FAAC58\">";
  echo "<A HREF=\"index.php\"> Return to Main Page </A></td><td bgcolor=\"#FAAC58\"><A HREF=\"reports.php\">  Reports </A></td>";
  echo "</tr></table><br><br></center>";
