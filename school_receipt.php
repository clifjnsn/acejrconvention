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
    $this->Cell(30,10,'ACE Jr. Convention School Receipt',0,1,'C',0);
    //Line break
    $this->Ln(20);
}

//Page footer
function Footer()
{
    //Position at 3.0 cm from bottom
    $this->SetY(-30);
    $this->Cell(0,6,"* Please make checks payable to Canby First Baptist Church School or Canby FBCS",0,1,'L',0);
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

// Initialize Variables
$students = 0;
$guests = 0;
$sponsors = 0;
$totals = 0;
$payments = 0;

$today = date("F j, Y  (g:i a)");                 // March 10, 2001, 5:16 pm

//Create a new PDF file
$pdf=new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetXY(10,25);
$pdf->SetFillcolor(232,232,232);
$pdf->SetFont('Arial','B',12);

$sql_query = "select * from convention_schools where id = ".(int)$_GET['id'];
$schoolid = (int)$_GET['id'];
$result=mysqli_query($mysql_handle, $sql_query);
$row = mysqli_fetch_assoc($result);
$pdf->Cell(0,6,$row['name'],0,1,'R',0);
$pdf->Cell(0,6,$row['address'],0,1,'R',0);
$pdf->Cell(0,6,$row['city'].', '.$row['state'].'  '.$row['zip'],0,1,'R',0);
mysqli_free_result($result);

$pdf->Ln(2);
$pdf->Cell(0,6,$today,0,1,'L',0);

$pdf->Ln(5);
// Students
$pdf->Cell(0,6,"STUDENTS",1,1,'C',1);
$sql_query = "select * from convention_student where schoolid = ".(int)$_GET['id']." and type = 'Student'";
$result=mysqli_query($mysql_handle, $sql_query);
while($row = mysqli_fetch_assoc($result)) {
$pdf->Cell(160,6,$row['first']." ".$row['last'],1,0,'L',0);
$pdf->SetX(170);
$pdf->Cell(30,6,"$ 60.00",1,1,'R',0);
$students = $students + 60;
}
mysqli_free_result($result);

$pdf->Ln(5);
// Sponsors
$pdf->Cell(0,6,"SPONSORS",1,1,'C',1);
$sql_query = "select * from convention_student where schoolid = ".(int)$_GET['id']." and type = 'Sponsor'";
$result=mysqli_query($mysql_handle, $sql_query);
while($row = mysqli_fetch_assoc($result)) {
$pdf->Cell(160,6,$row['first']." ".$row['last'],1,0,'L',0);
$pdf->SetX(170);
$pdf->Cell(30,6,"$ 60.00",1,1,'R',0);
$students = $students + 60;
}
mysqli_free_result($result);
$pdf->SetX(120);
$pdf->Cell(32,6,"Total Registrations",0,0,'L',0);
$pdf->SetX(170);
$pdf->Cell(30,6,"$ ".$students.".00",1,1,'R',0);

// Guest Meals
$sql_query = "select * from convention_student where schoolid = ".(int)$_GET['id']." and type = 'Guest'";
$result=mysqli_query($mysql_handle, $sql_query);
while($row = mysqli_fetch_assoc($result)) {
$guests = $guests + (int)$row['meals'];
}
mysqli_free_result($result);
$pdf->SetX(120);
$pdf->Cell(32,6,"Total Guest Meals",0,0,'L',0);
$pdf->SetX(170);
$pdf->Cell(30,6,$guests,1,1,'R',0);
$pdf->SetX(120);
$pdf->Cell(32,6,"@ $7.00 each",0,0,'L',0);
$pdf->SetX(170);
$pdf->Cell(30,6,"$ ".($guests *7).".00",1,1,'R',0);
$totals = ($guests *7) + $students;
$pdf->SetX(120);
$pdf->Cell(32,6,"Totals",0,0,'L',0);
$pdf->SetX(170);
$pdf->Cell(30,6,"$ ".$totals.".00",1,1,'R',0);

// Payments
$sql_query = "select * from convention_payments where schoolid = ".(int)$_GET['id'];
$result=mysqli_query($mysql_handle, $sql_query);
while($row = mysqli_fetch_assoc($result)) {
  $payments = $payments + $row['amount'];
}
mysqli_close($mysql_handle);
$pdf->SetX(120);
$pdf->Cell(32,6,"Payments",0,0,'L',0);
$pdf->SetX(170);
$pdf->Cell(30,6,"$ ".$payments.".00",1,1,'R',0);

$pdf->SetX(120);
$pdf->Cell(32,6,"Amount Due",0,0,'L',0);
$pdf->SetX(170);
$pdf->Cell(30,6,"$ ".($totals - $payments).".00",1,1,'R',0);

$pdf->Output();
?>
