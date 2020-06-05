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
    $this->Cell(30,10,'ACE Jr. Convention All School Report',0,1,'C',0);
    //Line break
    $this->Ln(20);
}

//Page footer
function Footer()
{
    //Position at 3.0 cm from bottom
    $this->SetY(-30);
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

if($_GET['schoolid']) {
  $result=mysqli_query("select * from convention_schools where id =".(int)$_GET['schoolid']."  order by name");
} else {
  $result=mysqli_query("select * from convention_schools order by name");
}
$schools = mysqli_numrows($result);

//Create a new PDF file
$pdf=new PDF();
$pdf->AliasNbPages();

while($row = mysqli_fetch_array($result))
{
  $pdf->AddPage();
  $name = $row["name"];
  $address = $row["address"];
  $city = $row["city"];
  $state = $row["state"];
  $zip = $row["zip"];
  $phone = $row["phone"];
  $email = $row["email"];
 
  //Gray color filling each Field Name box
  $pdf->SetFillColor(232,232,232);
  //Bold Font for Field Name
  $pdf->SetFont('Arial','B',12);
  $pdf->SetXY(10,25);  
  // width,height,text,border,pos after,align,fill
  $pdf->Cell(0,6,$name.', '.$address.', '.$city.', '.$state.', '.$zip,1,0,'L',1);

  //Turn off Bold
  $pdf->SetFont('Arial','',12);
  //No background
//  $pdf->SetFillColor(0,0,0);
  $result2=mysqli_query($mysql_handle, "select * from convention_student where schoolid = ".(int)$row["id"]." and type ='Student' order by last;");
  $students = mysqli_query($result2);
  $pdf->SetXY(15,33);
  while($row2 = mysqli_fetch_assoc($result2))
  {
    $pdf->SetX(15);
    $pdf->Cell(0,6,$row2["last"].", ".$row2["first"],1,1,'L',0);

    $result3 = "select convention_event.id,convention_event.name,convention_event.category,convention_scores.* from convention_scores,convention_event where ";
    $result3 .= " convention_scores.studentid= ".(int)$row2['id']." and convention_event.id = convention_scores.eventid order by convention_event.category";
    $events=mysqli_query($mysql_handle, $result3);
    while($row3 = mysqli_fetch_assoc($events))
    {
      switch($row3["place"]) {
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
      $pdf->Cell(20,6,$row3["category"],0,0,'L',0);
      $pdf->SetX(60);
      $pdf->Cell(20,6,$row3["name"],0,0,'L',0);
      $pdf->SetX(160);
      $pdf->Cell(20,6,$place,0,1,'L',0);
    }
  }
}
mysqli_close($mysql_handle);

$pdf->Output();
?>
