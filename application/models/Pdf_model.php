<?php

require_once APPPATH . '..\vendor\tecnickcom\tcpdf\tcpdf.php';

class Pdf_model extends CI_Model
{
	public function pdf($html, $title)
	{
		$pdf = new TCPDF();
		$pdf->SetTitle($title);
		$pdf->AddPage('L', 'F4');
		$pdf->SetFont('helvetica', '', 12);
		

		$pdf->writeHTML($html, true, false, true, false, '');
		$pdf->Output($title.'.pdf', 'I');
		$pdf->close();
	}
}