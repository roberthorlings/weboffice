<?php

	use \Weboffice\Support\IsdatPdf;
	
	// Begin de nieuwe PDF
	$output = new IsdatPdf();


	$titel = "Kilometerregistratie " . toTimespan($filter['start'], $filter['end']);
	
	$output->setSubject ( $titel );
	$output->setTitle ( $titel );

	// Begin de pagina netjes
	$output->AddPage ();
	
	$output->h1 ( "Kilometerregistratie" );
	
	// Zet de informatie neer over de datum
	$output->Cell ( 25, 5, "Periode:" );
	$output->Cell ( 135, 5, toTimespan($filter['start'], $filter['end']), 0, 1);
	
	$output->Cell ( 25, 5, "Vervoerswijze:" );
	$output->Cell ( 135, 5, "alle vervoerswijzen", 0, 1 );
	
	$output->Cell ( 25, 5, "Kenteken:" );
	$output->Cell ( 135, 5, $licenseplate, 0, 1 );
	
	// Zet wat ruimte neer
	$output->ln ();
	
	Pdf::bedrijfsEnRelatieGegevens( $output, null );
	
	$output->ln ();
	
	// Zet de tabel neer
	$output = Pdf::travelexpenses( $output, $travelexpenses, $stats, $total );
	$output->ln ();
	
	// Zet de extra informatie ook in de PDF
	$y = $output->getY();
	$pageno = $output->PageNo();
	
	$output->MultiCell( 90, 5,
			"Met eventuele vragen kunt u terecht bij " . ucfirst( Pdf::$persoon[ "naam" ] ) . ": " . Pdf::$persoon[ "email" ] . " of " . Pdf::$persoon[ "telefoon" ]
			);
	
	if( $pageno == $output->PageNo() ) {
		$output->setY( $y );
	} else {
		$output->setY( $pdf->tMargin );
	}
	
	Pdf::administratieveGegevens( $output );
	
	echo $output->output( $filename, "S");	
	