<?php
require('fpdf16/fpdf.php');

class PDF extends FPDF
{
//Page header
function Header()
{
    //Arial bold 15
    $this->SetFont('Arial','B',15);
    //Move to the right
    $this->Cell(80);
    //Title
    $this->Cell(30,10,'ACE Jr. Convention Awards Report',0,1,'C',0);
    //Line break
    $this->Ln(20);
}

//Page footer
function Footer()
{
    //Position at 2.5 cm from bottom
    $this->SetY(-25);
    //Arial italic 8
    $this->SetFont('Arial','I',8);
    //Page number
    $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
}
}

//Connect to your database
  $dbhost = 'localhost';
  $dbname = 'conventions';
  $dbuser = 'convention';
  $dbpass = 'Ch@ngeme!';

  $mysql_handle = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname)
    or die("Error connecting to database server");

$result=mysqli_query($mysql_handle, "select * from convention_event order by category,id");
$schools = mysqli_numrows($result);

//Create a new PDF file
$pdf=new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetXY(10,25);  

$old_category = "";

while($row = mysqli_fetch_array($result))
{
  //Gray color filling each Field Name box
  $pdf->SetFillColor(232,232,232);
  //Bold Font for Field Name
  $pdf->SetFont('Arial','B',12);
  if($old_category != $row["category"]) {
    // width,height,text,border,pos after,align,fill
    $pdf->SetX(10);
    $pdf->Cell(0,6,$row["category"],1,1,'L',1);
    }
  $old_category = $row["category"];
  $pdf->SetX(15);
  $pdf->Cell(0,6,$row["id"]."  ".$row["name"],1,1,'L',1);

  //Turn off Bold
  $pdf->SetFont('Arial','',12);
  //No background
  $pdf->SetFillColor(0,0,0);
  $result2="select convention_scores.place,convention_scores.team,convention_student.last,convention_student.first,convention_schools.name from convention_scores,convention_student,"; 
  $result2 .= "convention_schools where convention_scores.eventid= ";
  $result2 .= (int)$row["id"]." and convention_student.id = convention_scores.studentid and convention_schools.id = convention_student.schoolid ";
  $result2 .= "order by convention_scores.place DESC;";
  $students = mysqli_query($mysql_handle, $result2);
  while($row2 = mysqli_fetch_assoc($students))
  {
    $pdf->SetX(20);
    switch($row2["place"]) {
      case 0:
        $place = "No Placement";
        break;
      case 1:
        $place = "First";
        break;
      case 2:
        $place = "Second";
        break;
      case 3:
        $place = "Third";
        break;
      case 4:
        $place = "Fourth";
        break;
      case 5:
        $place = "Fifth";
        break;
      case 6:
        $place = "Six";
        break;
    }
    $pdf->Cell(80,6,$row2["last"].", ".$row2["first"],0,0,'L',0);
    $pdf->SetX(80);
    if($row2["team"]) {
      $pdf->Cell(50,6,$row2["name"]." (".$row2["team"].")",0,0,'L',0);
    } else {
      $pdf->Cell(50,6,$row2["name"],0,0,'L',0);
    }
    $pdf->SetX(160);
    $pdf->Cell(0,6,$place,0,1,'L',0);
  }
  $pdf->Ln();
  $place = "No Placement";
}
mysqli_close($mysql_handle);

$pdf->Output();
?>
