<?php
require('fpdf16/fpdf.php');

//Connect to your database
  $dbhost = 'localhost';
  $dbname = 'conventions';
  $dbuser = 'convention';
  $dbpass = 'Ch@ngeme!';

  $mysql_handle = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname)
    or die("Error connecting to database server");

$today = date("F j, Y");
$end_of_form = array();
$eof_counter = 0;

// Get option for printing blank forms
$blank = $_GET['blank'];

//Create a new PDF file
$pdf=new FPDF();

// Variables to control printing blank forms for Duet, Trio, Quartet, Sm Ensemble, One-Act-Play and Puppets
$printed_once = 0;
$previous_event = 0;
$group_events = array(503,504,505,506,512,515,609,610);

// width,height,text,border,pos after,align,fill

$result = "select * from convention_event where id not in (201,202,203,204,209,210,401,402,403,404,410,411,412,413)";
$events=mysqli_query($mysql_handle, $result);
$pdf->SetXY(10,30);
while($row = mysqli_fetch_assoc($events))
{
  if(in_array($row['id'],$group_events)) { if($previous_event == $row['id']) { break; } }
  $sql_query = "select studentid,time from convention_scores where eventid = ".$row["id"].";";
  $students = mysqli_query($mysql_handle, $sql_query);
  $participants = mysqli_num_rows($students);
  if($participants) {
    while($row2 = mysqli_fetch_assoc($students)) 
    {
      $sql_query = mysqli_query($mysql_handle, "select first,last,schoolid from convention_student where id = ".$row2['studentid'].";");
      $student = mysqli_fetch_row($sql_query);
      $sql_query = mysqli_query($mysql_handle, "select name,address,city,state,zip from convention_schools where id = ".$student[2].";");
      $school = mysqli_fetch_row($sql_query);
      if(in_array($row['id'],$group_events)) 
        { 
        if($previous_event == $row['id']) { break; } 
        else 
          {
          $previous_event = $row['id']; 
          unset($student);
          unset($school);
          }
        }
      if($blank == 1) { unset($student);unset($school); }
      $pdf->AddPage();
      // Turn on Bold
      $pdf->SetFont('Arial','B',12);
      //Gray fill color
      $pdf->SetFillColor(232,232,232);
      $pdf->Cell(0,6,"JUDGE'S FORM",0,1,'C',0);
      $pdf->Cell(0,6,$row["id"]." - ".$row["name"],0,1,'C',0);
      $pdf->Ln(2);

      //Print Name and Date
      $pdf->SetFont('Arial','B',12);
      $pdf->Cell(0,6,"NAME",0,0,'L',0);
      $pdf->SetFont('Arial','U',12);
      $pdf->SetX(25);
      $name = $student[0]." ".$student[1];
      $pdf->Cell(0,6,str_pad($name,56,"_"),0,0,'L',0);
      $pdf->SetFont('Arial','B',12);
      $pdf->SetX(155);
      $pdf->Cell(0,6,"DATE",0,0,'L',0);
      $pdf->SetFont('Arial','U',12);
      $pdf->SetX(170);
      $pdf->Cell(0,6,$today,0,1,'L',0);
   
      //Print School Name
      $pdf->SetFont('Arial','B',12);
      $pdf->Cell(0,6,"SCHOOL NAME",0,0,'L',0);
      $pdf->SetFont('Arial','U',12);
      $pdf->SetX(45);
      $pdf->Cell(0,6,str_pad($school[0],67,"_"),0,1,'L',0);

      //Print School Address
      $pdf->SetFont('Arial','B',12);
      $pdf->Cell(0,6,"SCHOOL ADDRESS",0,0,'L',0);
      $pdf->SetFont('Arial','U',12);
      $pdf->SetX(55);
      $address = $school[1].", ".$school[2].", ".$school[3].", ".$school[4];
      $pdf->Cell(0,6,str_pad($address,65,"_"),0,1,'L',0);
      //Print Entry Line
      if($row['id'] > 500 && $row['id'] < 600) {
        $pdf->SetFont('Arial','B',12);
        $pdf->Cell(0,6,"ENTRY _______________________ NAME OF SELECTION _______________________________",0,1,'L',0);
      }
      $pdf->Ln(2); 

      //Print Header for event specific criteria
      $pdf->SetFont('Arial','',12);
      $pdf->SetX(20);
      $pdf->Cell(50,6,"AREAS OF EVALUATION",0,0,'L',0);
      $pdf->SetX(120);
      $pdf->Cell(40,6,"POSSIBLE POINTS",0,0,'L',0);
      $pdf->SetX(160);
      $pdf->Cell(40,6,"POINTS AWARDED",0,1,'L',0);

      //Query the jforms database to retrieve the rest of the lines for each form
      $query_id = $row['id'];
      if($row['id'] > 315 && $row['id'] < 324) { $query_id = 315; }
      if($row['id'] > 500 && $row['id'] < 510) { $query_id = 501; }
      if($row['id'] > 511 && $row['id'] < 515) { $query_id = 510; }
      if($row['id'] > 602 && $row['id'] < 606) { $query_id = 602; }
      if($row['id'] > 605 && $row['id'] < 608) { $query_id = 606; }
      $sql_query = "select * from convention_jforms where eventid = ".$query_id." order by seqnum";
      $result3 = mysqli_query($mysql_handle, $sql_query);
      while($form = mysqli_fetch_assoc($result3)) {
        if($form['points'] == 100) {
          $end_of_form[$eof_counter] = $form['text'];
          $eof_counter++;
          break;
        } else { $end_of_form = ""; }
        if($form['points'] == 0) { 
          $pdf->SetFont('Arial','B',12);
          $pdf->SetX(10);
        } else {
          $pdf->SetFont('Arial','',12);
          $pdf->SetX(15);
        }
        $pdf->Cell(100,6,$form['text'],0,0,'L',0);
        $pdf->SetX(140);
        if($form['points'] > 2) {
          $pdf->Cell(5,6,$form['points'],0,0,'R',0);
          $pdf->SetX(170);
          $pdf->Cell(5,6,"__________",0,1,'L',0);
        } else {
          $pdf->Cell(5,6," ",0,1,'L',0);
        }
      }

      //Print Totals and Documentation lines
      $pdf->SetFont('Arial','B',12);
      $pdf->SetX(10);
      $pdf->Cell(70,6,"Proper documentation submitted",0,0,'L',0);
      $pdf->SetFont('Arial','',12);
      $pdf->SetX(140);
      $pdf->Cell(5,6,"5",0,0,'R',0);
      $pdf->SetX(170);
      $pdf->Cell(5,6,"__________",0,1,'L',0);
      $pdf->SetFont('Arial','B',12);
      $pdf->Ln(5);
      $pdf->SetX(100);
      $pdf->Cell(24,6,"TOTAL POINTS",0,0,'L',0);
      $pdf->SetX(140);
      $pdf->Cell(5,6,"100",0,0,'R',0);
      $pdf->SetX(170);
      $pdf->Cell(5,6,"__________",0,1,'L',0);

      //Print end of form notes (if available)
      if($end_of_form) {
        $pdf->SetXY(10,200);
        $pdf->Ln(5);
        $pdf->SetFont('Arial','',12);
        foreach($end_of_form as $eof_line) {
          $pdf->Cell(10,6,$eof_line,0,1,'L',0);
        }
        $pdf->Ln(2);
        unset($end_of_form);
        $end_of_form = array();
        $eof_counter = 0;
      }
      $pdf->SetFont('Arial','',12);
      if($row['id'] > 500) {
        if($row['id'] == 511 || $row['id'] > 600) {
          $pdf->SetXY(10,210);
          $timelimit = "5:00";
          
          if($row['id'] > 601 && $row['id' < 608]) { $timelimit = "4-6 mins"; }
          if($row['id'] == 608) { $timelimit = "8:00"; }
          if($row['id'] == 609) { $timelimit = "6-10 mins"; }
          if($row['id'] == 610) { $timelimit = "5-8 mins"; }
          $pdf->Cell(0,6,"Time Limit _".$timelimit."___ Actual Time _________",0,1,'L',0);
          $pdf->SetXY(10,220);
          $pdf->Cell(0,6,"COMMENTS______________________________________________________________________",0,1,'L',0);
          $pdf->Cell(0,6,"________________________________________________________________________________",0,1,'L',0);
          $pdf->Cell(0,6,"________________________________________________________________________________",0,1,'L',0);
        } else {
          $pdf->SetXY(10,240);
          $pdf->Cell(0,6,"Time Limit _5:00___ Actual Time _________",0,1,'L',0);
          $pdf->SetFont('Arial','I',12);
          $pdf->Cell(0,6,"Use back of page for comments",0,1,'L',0);
        }
        $pdf->SetFont('Arial','',10);
        $pdf->Cell(0,6,"___________________________",0,1,'R',0);
        $pdf->Cell(0,6,"(Judges Signature)",0,1,'R',0);
        $pdf->SetFont('Arial','I',10);
        $pdf->Cell(0,6,"*If piece exceeds the limit, a .5 deduction will be incurred for any portion of thirty second increments",0,1,'L',0);
        $pdf->Cell(0,6," The identical deduction occurs on each form.",0,1,'L',0);
        $pdf->SetFont('Arial','',12);
      } else {
      //Judges Comments and signature 
        if($row['id'] == 208) 
          { 
          $pdf->SetXY(10,220);
          $pdf->SetFont('Arial','I',10);
          $pdf->Cell(0,6,"NOTE: As many as 10 points may be subtracted if poem is not 8 to 30 lines in length",0,1,'L',0);
          $pdf->Cell(0,6,"and is not typed on plain white paper.",0,1,'L',0);
          $pdf->SetFont('Arial','',12);
          }
        $pdf->SetXY(10,233);
        $pdf->Cell(0,6,"COMMENTS______________________________________________________________________",0,1,'L',0);
        $pdf->Cell(0,6,"________________________________________________________________________________",0,1,'L',0);
        $pdf->Cell(0,6,"________________________________________________________________________________",0,1,'L',0);
        $pdf->Cell(0,6,"________________________________________________________________________________",0,1,'L',0);
        $pdf->Cell(0,6,"________________________________________________________________________________",0,1,'L',0);
        $pdf->Cell(0,6,"___________________________",0,1,'R',0);
        $pdf->Cell(0,6,"(Judges Signature)",0,1,'R',0);
      }
    }
  }
$previous_event = $row['id'];
}
mysqli_close($mysql_handle);

$pdf->Output();

?>
