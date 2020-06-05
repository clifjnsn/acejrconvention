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
    $this->Cell(30,10,'ACE Jr. Master Schedule Report',0,1,'C',0);
    //Line break
    $this->Ln(10);
}

//Page footer
function Footer()
{
    //Position at 2.5 cm from bottom
    $this->SetY(-2.5);
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

//Create a new PDF file
$pdf=new PDF();
$pdf->AliasNbPages();

// width,height,text,border,pos after,align,fill

$result = "select * from convention_event;";
$events=mysqli_query($mysql_handle, $result);
$pdf->SetXY(10,25);
// $pdf->AddPage();
while($row = mysqli_fetch_assoc($events))
{
  $sql_query = "select studentid,time,team from convention_scores where eventid = ".$row["id"]." order by time,team;";
  $students = mysqli_query($mysql_handle, $sql_query);
  $participants = mysqli_num_rows($students);
  if($participants) {
    $pdf->AddPage();
    // Turn on Bold
    $pdf->SetFont('Arial','B',12);
    //Gray fill color
    $pdf->SetFillColor(232,232,232);
    $pdf->Cell(0,6,$row["id"]." - ".$row["name"],0,1,'C',1);
    $pdf->Ln(2);
    $pdf->Cell(30,6,"TIME",1,0,'L',0);
    $pdf->SetX(40);
    $pdf->Cell(0,6,"NAME",1,1,'L',0);
    $pdf->Ln(2);
    while($row2 = mysqli_fetch_assoc($students)) 
    {
      $sql_query = mysqli_query($mysql_handle, "select first,last,schoolid from convention_student where id = ".$row2['studentid'].";");
      $student = mysqli_fetch_row($sql_query);
      $sql_query = mysqli_query($mysql_handle, "select name from convention_schools where id = ".$student[2].";");
      $school = mysqli_fetch_row($sql_query);
      $pdf->Cell(30,6,$row2['time'],0,0,'L',0);
      $pdf->SetX(40);
      if($row2['team']) {
        $pdf->Cell(0,6,$student[1].", ".$student[0]." - ".$school[0]." (".$row2['team'].")",0,1,'L',0);
      } else {
        $pdf->Cell(0,6,$student[1].", ".$student[0]." - ".$school[0],0,1,'L',0);
      }
      $pdf->Ln(2);
    }
  }
}
mysqli_close();

$pdf->Output();
?>
