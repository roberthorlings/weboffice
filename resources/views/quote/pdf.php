<?php
	use \Weboffice\Support\IsdatPdf;
	
	// Begin de nieuwe PDF
	$output = new IsdatPdf();
	
	$titel = "Offerte " . $quote->offertenummer;
	
	$output->setSubject( $titel );
	$output->setTitle( $titel );

	// Begin de pagina netjes
	$output->AddPage();
	
	// Add recipient
	$output->addTo( $quote->Relation );
	
	$output->h1( 'Offerte' );
	
	// Add date information
	$output->Cell( 30, 5, "Offertenummer:");
	$output->Cell( 120, 5, $quote->datum->formatLocalized( $quoteNumberPrefix ) . $quote->offertenummer, 0, 1 );
	$output->Cell( 30, 5, "Offertedatum:");
	$output->Cell( 120, 5, $quote->datum->format('d-m-Y'), 0, 1 );
	
	if($quote->vervaldatum) {
		$output->Cell( 30, 5, "Geldig tot:");
		$output->Cell( 120, 5, $quote->vervaldatum->format('d-m-Y'), 0, 1 );
	}
	
	// Add some space
	$output->ln();
	
	// Zet een voor een de paragrafen in de offerte
	foreach( $quote->getAllLines() as $paragraph ) {
		// Zorg dat er na de kop in elk geval nog 1 regel op de pagina past
		if( $output->getY() + 8 + 5 >= 290 - 27 ) {
			$output->addPage();
		}
	
		$output->Cell( 160, 3, "", 0, 1 );
		$output->h3( $paragraph->titel );
		$output->MultiCell( 160, 5, $paragraph->inhoud );
	}
	
	$output->ln();
	
	// Add signature to PDF
	$output->ln();
	
	// Make sure it fits on a single page
	if( $output->getY() + 55 > 290 - 20 ) {
		$output->addPage();
	}
	
	$output->Cell( 10, 5, "" );
	$output->Cell( 70, 5, "Voor akkoord ", 0, 1 );
	$output->ln();
	
	$output->Cell( 10, 5, "" );
	$output->Cell( 80, 5, "Namens * isdat softwareontwikkeling" );
	$output->Cell( 70, 5, "Namens " .  $quote->Relation->bedrijfsnaam, 0, 1 );
	
	$output->image( base_path('resources/handtekening.jpg'), $output->getX() + 10, $output->getY(), 0, 25 );
	
	$output->setY( $output->getY() + 22 );
	$output->Cell( 90, 3, "" );
	$output->Cell( 40, 3, "", "T", 1 );
	
	$output->Cell( 10, 5, "" );
	$output->Cell( 80, 5, "Robert Horlings" );
	$output->Cell( 40, 5, "Naam: ", 0, 1 );
	$output->Cell( 90, 5, "" );
	$output->Cell( 40, 5, "Datum: ", 0, 1 );
	$output->Cell( 90, 5, "" );
	$output->Cell( 40, 5, "Plaats: ", 0, 1 );
	
	echo $output->output( $filename, "S");
