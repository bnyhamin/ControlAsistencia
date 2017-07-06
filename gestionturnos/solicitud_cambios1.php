<?php
require('../../Includes/fpdf153/fpdf.php');

class PDF extends FPDF {
	//Cargar los datos
	function LoadData($file){
	    //Leer las líneas del fichero
	    $lines=file($file);
	    $data=array();
	    foreach($lines as $line)
	        $data[]=explode(';',chop($line));
	    return $data;
	}
	
	//Tabla simple
	function BasicTable($header,$data){
	    //Cabecera
	    foreach($header as $col)
	        $this->Cell(20,7,$col,1);
	    $this->Ln();
	    //Datos
	    foreach($data as $row)
	    {
	        foreach($row as $col)
	            $this->Cell(20,6,$col,0);
	        $this->Ln();
	    }
	}
	
	
}

$pdf=new PDF();
//Títulos de las columnas
$header=array('RUT','LOGIN','NOMBRES','FECHA','DIA','INICIO','TERMINO','DURACION','DUR.COLA','DURACI');
//Carga de datos
$data=$pdf->LoadData('tmp.txt');
$pdf->SetFont('courier','',9);
$pdf->SetTopMargin(20);
$pdf->AddPage();
$pdf->Image('..\..\images\atentolo.jpg',165,20,33);
$pdf->Cell(200,6,'SOLICITUD DE CAMBIO DE SUPLENCIA PROVISORIA',0,1,'C');
$pdf->Ln();
$pdf->Cell(200,5,'YO ....................................................RUT ....................',0,1,'L');
$pdf->Cell(200,5,'Solicito modificar mi turno actual, para el (o los) dia(s)',0,1,'L');
$pdf->Cell(200,5,'...............................................................................',0,1,'L');
$pdf->Cell(200,5,'...............................................................................',0,1,'L');
$pdf->Cell(200,5,'.......... de ................. de .......',0,1,'L');
$pdf->Ln();
$pdf->Cell(200,5,'Nombre del Servicio                       ______________________________________',0,1,'L');
$pdf->Cell(200,5,'Responsable o Solicitante                 ______________________________________',0,1,'L');
$pdf->Ln();
$pdf->Cell(200,5,'TURNO ORIGINAL',0,1,'L');
$pdf->SetFont('courier','',8);
$pdf->Cell(16,4,'RUT',1,0,'C');
$pdf->Cell(11,4,'LOGIN',1,0,'C');
$pdf->Cell(60,4,'NOMBRES',1,0,'C');
$pdf->Cell(20,4,'FECHA',1,0,'C');
$pdf->Cell(20,4,'DIA',1,0,'C');
$pdf->Cell(12,4,'INIC',1,0,'C');
$pdf->Cell(12,4,'TERM',1,0,'C');
$pdf->Cell(12,4,'DURAC',1,0,'C');
$pdf->Cell(12,4,'DUR.CO',1,0,'C');
$pdf->Cell(12,4,'DURAC',1,1,'C');
$pdf->Cell(16,4,'12345678','LR',0,'C');
$pdf->Cell(11,4,'12345','LR',0,'C');
$pdf->Cell(60,4,'ASD ASDA DASD ASD ASOMBRES','LR',0,'C');
$pdf->Cell(20,4,'1234567890','LR',0,'C');
$pdf->Cell(20,4,'1234567890','LR',0,'C');
$pdf->Cell(12,4,'hh:mm','LR',0,'C');
$pdf->Cell(12,4,'hh:mm','LR',0,'C');
$pdf->Cell(12,4,'hh:mm','LR',0,'C');
$pdf->Cell(12,4,'12345','LR',0,'C');
$pdf->Cell(12,4,'12345','LR',1,'C');
$pdf->Cell(187,0,'','T');
$pdf->Output();

?>