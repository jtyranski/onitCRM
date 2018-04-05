<?php
//$pdf_output = "FI";
if($pdf_output == "") $pdf_output = "I";

require_once "includes/functions.php";

require_once('tcpdf/config/lang/eng.php');
require_once('tcpdf/tcpdf.php');

if(!(class_exists("MYPDF"))){
class MYPDF extends TCPDF {
 
	//Page header
	public function Header() {

	}

	// Page footer
	public function Footer() {

		
	}
	
}	
}

$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor($MAIN_CO_NAME);
$pdf->SetTitle('Service Dispatch Invoice');
$pdf->SetSubject('Service Dispatch Invoice');
// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

//set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, 13, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(0);
$pdf->SetFooterMargin(5);

//set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

//set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

//set some language-dependent strings
$pdf->setLanguageArray($l);

$pdf->SetFont('helvetica', '', 12);


$leak_id = $_GET['leak_id'];
$filename = "INVOICE_DOCUMENTS_" . $leak_id . ".pdf";

include "fcs_sd_invoice_pdf_documents_data.php";

$pdf->Output('uploaded_files/invoices/' . $filename, "I");
?>