<?php

//PlaceWatermark("cavatina.pdf", "lmp_logo.png");
require_once("fpdf/fpdf.php");
require_once("fpdf/fpdi.php");

$pdf = new FPDI();

$pageCount = $pdf->setSourceFile("cavatina.pdf");
$tplIdx = $pdf->importPage(1);

$pdf->addPage();
$pdf->useTemplate($tplIdx, 100, 100, 90);

$pdf->Output();


?>





















