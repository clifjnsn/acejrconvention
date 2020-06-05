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
    $this->Cell(30,10,'ACE Jr. Convention Trophy Awards Report',0,1,'C',0);
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
$categories = mysqli_numrows($result);

//Create a new PDF file
$pdf=new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetXY(10,25);

$old_category = "";
$trophy = array();
$flag = 0;

while($row = mysqli_fetch_array($result))
{
  if($old_category != $row["category"]) {
    if($flag) { 
      if(isset($trophy)) {
        while(list($key,$value) = each($trophy)) {
         $pdf->Cell(0,6,"       ".$key."...............".$value." points.",0,1,"L",0);
       }
      $pdf->Ln(2);
      }
    }
    $pdf->SetFillColor(232,232,232);
    $pdf->Cell(0,6,"Category: ".$row["category"],1,1,"l",1);
    $pdf->Ln(2);
//    $pdf->SetFillColor(0,0,0);
    $old_category = $row["category"];
    unset($trophy);
    $flag = 1;
  }
  $result2="select * from convention_scores where eventid = ".(int)$row["id"];
  $students = mysqli_query($mysql_handle, $result2);
  while($row2 = mysqli_fetch_assoc($students))
  {
    $result3=mysqli_query($mysql_handle, "select schoolid from convention_student where id = ".(int)$row2["studentid"]);
    $schoolid = mysqli_fetch_row($result3);
    $result3=mysqli_query($mysql_handle, "select name from convention_schools where id = ".(int)$schoolid[0]);
    $school_name = mysqli_fetch_row($result3);
    $score = 0;
    switch($row2["place"]) {
      case 0:
        $score = 0;
        break;
      case 1:
        $score = 6;
        break;
      case 2:
        $score = 5;
        break;
      case 3:
        $score = 4;
        break;
      case 4:
        $score = 3;
        break;
      case 5:
        $score = 2;
        break;
      case 6:
        $score = 1;
        break;
    }
  if($score > 0) {
    $trophy[$school_name[0]] = $trophy[$school_name[0]] + $score;
    $score = 0;
    }
  }
}
if($flag) {
  if(isset($trophy)) {
    while(list($key,$value) = each($trophy)) {
      $pdf->Cell(0,6,"       ".$key."...............".$value." points.",0,1,"L",0);
    }
    $pdf->Ln(2);
  }
}
mysqli_close($mysql_handle);
$pdf->Output();
?>
