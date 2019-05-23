<?php
//	ini_set('display_errors', 1);

if( isset( $_POST['saved']) ) {
		
	$saved = json_decode($_POST['saved']);
	
//	echo var_dump($saved);

	// Include the main TCPDF library (search for installation path).
	require_once('../TCPDF-master/tcpdf.php');

	// create new PDF document
	$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

	// set document information
	$pdf->SetCreator("Yale Peabody Museum of Natural History");
	$pdf->SetAuthor('Yale Peabody Museum of Natural History');
	$pdf->SetTitle('Saved Cards');
	$pdf->SetSubject('PBCI Cards');

	// remove default header/footer
	$pdf->setPrintHeader(false);
	$pdf->setPrintFooter(false);

	// set default monospaced font
	$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

	// set margins
	$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);

	// set image scale factor
	$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

	// ---------------------------------------------------------

	// set font
	$pdf->SetFont('times', 'BI', 20);

	// disable auto-page-break
	$pdf->SetAutoPageBreak(false, 0);

	// set images
	
	foreach( $saved as $card ) {
		// 280 x 216
		$imageFile = 'http://images.peabody.yale.edu/ci/' . $card . '.jpg';
		list($width, $height) = getimagesize($imageFile);
		if( $height > $width ) {
			$pdf->AddPage('P', 'LETTER');
			if( $width/$height > 8.5/11 ) {
				$w = 216;
				$h = (216*$height)/$width;
				$x = 0;
				$y = (280-$h)/2;
			} else if ( $width/$height < 8.5/11 ) {
				$w = ($width*280)/$height;
				$h = 280;
				$x = (216-$w)/2;
				$y = 0;
			} else {
				$w = 216;
				$h = 280;
				$x = 0;
				$y = 0;
			}

		} else if ( $height < $width ){
			$pdf->AddPage('L', 'LETTER');
			if( $width/$height > 11/8.5 ) {
				$w = 280;
				$h = (280*$height)/$width;
				$x = 0;
				$y = (216-$h)/2;
			} else if ( $width/$height < 11/8.5 ) {
				$w = ($width*216)/$height;
				$h = 216;
				$x = (280-$w)/2;
				$y = 0;
			} else {
				$w = 280;
				$h = 216;
				$x = 0;
				$y = 0;
			}

		} else if ( $height == $width ) {
			$pdf->AddPage('P', 'LETTER');
			$w = 216;
			$h = 216;
			$x = 0;
			$y = (280-$h)/2;
		}
		
//		$pdf->AddPage();
		$pdf->Image($imageFile, $x, $y, $w, $h, 'JPG', '', '', false, 300, '', false, false, 0, false, false, false, false);

		$pdf->setPageMark();
		
	}

	// ...

	// ---------------------------------------------------------

	//Close and output PDF document
	$pdf->Output('saved_cards.pdf', 'I');


	
} else {
	
	echo ("no saved cards.");
	
}

//============================================================+
// END OF FILE
//============================================================+
