<?php
include_once 'convention_db.php';
$color1 = "#F4FA58";
$color2 = "#FAAC58";
$color=$color1;
echo "<table><tr bgcolor=\"#A0A0A0\"><td><B>Reports</td><td></td></tr>";
echo "<tr bgcolor=\"",$color,"\"><td><A HREF=\"http://www.printyourbrackets.com\">Elimination Brackets</A><td bgcolor=\"#FFFFFF\">Use this website to create double elimination sheets for Checkers, Chess, Table Tennis, etc..</td></tr>";
$color=$color2;
echo "<tr bgcolor=\"",$color,"\"><td><A HREF=\"master_schedule.php\">Master Time Schedule</A><td bgcolor=\"#FFFFFF\">Master list of times for all events.</td></tr>";
$color=$color1;
echo "<tr bgcolor=\"",$color,"\"><td><A HREF=\"badges_report.php\">Name Badges</A><td bgcolor=\"#FFFFFF\">Download CSV file to generate name badges.</td></tr>";
$color=$color2;
echo "<tr bgcolor=\"",$color,"\"><td><A HREF=\"student_schedule_report.php\">Student Event Schedule</A><td bgcolor=\"#FFFFFF\">Event times and confirmation of registration.  Send to schools with receipt.<A HREF=\"student_sched_by_school.php\">[by school]</A></td></tr>";
$color=$color1;
echo "<tr bgcolor=\"",$color,"\"><td><A HREF=\"judges_forms.php\">Judges Forms</A><td bgcolor=\"#FFFFFF\">Pre-filled out judges forms for events with entries. <A HREF=\"judges_forms.php?blank=1\">[blank]</A></td></tr>";
$color=$color2;
echo "<tr bgcolor=\"",$color,"\"><td><A HREF=\"judges_report.php\">Judges Scoring Reports</A><td bgcolor=\"#FFFFFF\">Forms for judges of each event to indicate places.</td></tr>";
$color=$color1;
echo "<tr bgcolor=\"",$color,"\"><td><A HREF=\"student_report.php\">All Student Report</A><td bgcolor=\"#FFFFFF\">Print to verify all students get a medal</td></tr>";
$color=$color2;
echo "<tr bgcolor=\"",$color,"\"><td><A HREF=\"awards_report.php\">Awards Night Report</A><td bgcolor=\"#FFFFFF\">Print and give to presenter on awards night</td></tr>";
$color=$color1;
// echo "<tr bgcolor=\"",$color,"\"><td><A HREF=\"trophy_report.php\">Trophy Report</A><td bgcolor=\"#FFFFFF\">Print and give to presenter on awards night</td></tr>";
echo "<tr bgcolor=\"",$color,"\"><td><A HREF=\"trophy_report.php\">Trophy Report<td bgcolor=\"#FFFFFF\">Print and give to presenter on awards night</td></tr>";
$color=$color2;
echo "<tr bgcolor=\"",$color,"\"><td><A HREF=\"school_report.php\">All School Report</A><td bgcolor=\"#FFFFFF\">Print after awards night to send home with schools <A HREF=\"school_report_by_school.php\">[by school]</A></td></tr>";
echo "</table>";
?>
