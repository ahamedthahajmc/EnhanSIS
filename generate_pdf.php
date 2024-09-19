<?php
require('fpdf.php');

$studentId = $_GET['studentId'];
$conn = new mysqli('localhost', 'root', '', 'schooldb');
$sql = "SELECT * FROM pay_fee WHERE student_id = '$studentId'";
$result = $conn->query($sql);
$row = $result->fetch_assoc();

$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(40, 10, 'Payment Receipt');
$pdf->Ln();
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(40, 10, 'Student ID: ' . $row['student_id']);
$pdf->Ln();
$pdf->Cell(40, 10, 'Student Name: ' . $row['student_name']);
$pdf->Ln();
$pdf->Cell(40, 10, 'Fees Type: ' . $row['fees_type']);
$pdf->Ln();
$pdf->Cell(40, 10, 'Amount: ' . $row['amount']);
$pdf->Ln();
$pdf->Cell(40, 10, 'Payment Date: ' . $row['payment_date']);
$pdf->Ln();
$pdf->Cell(40, 10, 'Payment Gateway: ' . $row['payment_gateway']);
$pdf->Output();

$conn->close();
?>
