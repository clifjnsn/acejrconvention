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
    $this->Cell(30,10,'ACE Jr. Convention All Student Report',0,1,'C',0);
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

//Create a new PDF file
$pdf=new PDF();
$pdf->AliasNbPages();

// width,height,text,border,pos after,align,fill

$result = "select * from convention_student where type='Student' order by last";
$students=mysqli_query($mysql_handle, $result);
$pdf->SetXY(10,25);
while($row = mysqli_fetch_assoc($students))
{
  $pdf->AddPage();
  // Turn on Bold
  $pdf->SetFont('Arial','B',12);
  //Gray fill color
  $pdf->SetFillColor(232,232,232);
  $pdf->SetX(15);
  $pdf->Cell(0,6,$row["last"].", ".$row["first"],1,1,'L',0);

  $result2 = "select convention_event.id,convention_event.name,convention_event.category,convention_scores.* from convention_scores,convention_event where ";
  $result2 .= " convention_scores.studentid= ".(int)$row['id']." and convention_event.id = convention_scores.eventid order by convention_event.category";
  $events=mysqli_query($mysql_handle, $result2);
  while($row2 = mysqli_fetch_assoc($events))
  {
    // Turn off Bold
    $pdf->SetFont('Arial','',12);
    //No background
    //  $pdf->SetFillColor(0,0,0);
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
    $pdf->SetX(20);
    $pdf->Cell(20,6,$row2["category"],0,0,'L',0);
    $pdf->SetX(60);
    $pdf->Cell(20,6,$row2["name"],0,0,'L',0);
    $pdf->SetX(160);
    $pdf->Cell(20,6,$place,0,1,'L',0);
  }
}
mysqli_close();

$pdf->Output();
?>
