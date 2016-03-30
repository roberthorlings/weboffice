<?php
	use \Weboffice\Support\IsdatPdf;
	
	// Begin de nieuwe PDF
	$output = new IsdatPdf();
	
	if( $invoice->creditfactuur ) {
		$type = "Creditnota";
	} else {
		$type = "Factuur";
	}
	
	$titel = $type . " " . $invoice->factuurnummer;
	
	$output->setSubject( $titel );
	$output->setTitle( $titel );

	// Begin de pagina netjes
	$output->AddPage();
	
	// Zoek basis informatie op 
	$output->addFactuurAdres( $invoice->Relation );

	$output->h1( $type );
	
	// Zet de informatie neer over de datum
	$output->Cell( 40, 5, "Factuurnummer:" );
	$output->Cell( 120, 5, $invoice->datum->formatLocalized( $invoiceNumberPrefix ) . $invoice->factuurnummer, 0, 1 );
	$output->Cell( 40, 5, "Factuurdatum:" );
	$output->Cell( 120, 5, $invoice->datum->format( "d-m-Y" ), 0, 1 );

	if( property_exists( $invoice, "periode" ) && $invoice->periode ) {
		$output->Cell( 40, 5, "Periode:" );
		$output->Cell( 120, 5, $invoice->periode, 0, 1 );
	}
	if( property_exists( $invoice, "referentie" ) && $invoice->referentie ) {
		$output->Cell( 40, 5, "Uw referentie:" );
		$output->Cell( 120, 5, $invoice->referentie, 0, 1 );
	}	
	
	if( $invoice->creditfactuur ) {
		$d = $invoice->oorspronkelijk_datum;
		$jaar = $d->format( 'Y' );
		$oorspronkelijke_datum = $d->format( 'd-m-Y' );
		$output->Cell( 160, 5, "Deze creditnota heeft betrekking op factuur nummer " . $jaar . "-" . $invoice->oorspronkelijk_factuurnummer . " van " . $oorspronkelijke_datum, 0, 1 );
	}	

	// Zet wat ruimte neer
	$output->ln(); 
	
	Pdf::bedrijfsEnRelatieGegevens( $output, $invoice->Relation );
	$output->ln();
	
	$output = Pdf::invoice( $output, $invoice );
	
	$output->ln();

	// Zet de extra informatie ook in de PDF
	$y = $output->getY();
	$pageno = $output->PageNo();
	
	$output->MultiCell( 90, 5, 
		"Gelieve het verschuldigde bedrag binnen 21 dagen over te maken op het hiernaast genoemde rekeningnummer onder vermelding van het factuurnummer.\n" . 
		"Met eventuele vragen kunt u terecht bij " . ucfirst( Pdf::$persoon[ "naam" ] ) . ": " . Pdf::$persoon[ "email" ] . " of " . Pdf::$persoon[ "telefoon" ]
	);
	
	if( $pageno == $output->PageNo() ) {
		$output->setY( $y );
	} else {
		$output->setY( $pdf->tMargin );
	}
	
	Pdf::administratieveGegevens( $output );

	// Check if we need to add hour registration
	if( $invoice->shouldShowHours() ) {
		// Voeg de uren ook toe
		$output->AddPage();
	
		$output->h1( "Urenspecificatie" );
	
		// Zet de werktijden neer
		foreach( $invoice->InvoiceProjects as $index => $invoiceProject ) {
			if( $invoiceProject->hours_overview_type != 'none' ) {
				// Zet de informatie neer over de datum en het project
				$output->Cell( 25, 5, "Project:" );
				$output->Cell( 135, 5, $invoice->InvoiceLines[$index]->omschrijving, 0, 1 );
				$output->Cell( 25, 5, "Periode:");
				$output->Cell( 135, 5, toTimespan($invoiceProject->start, $invoiceProject->end), 0, 1 );
					
				// Zet wat ruimte neer
				$output->ln();
					
				if( $invoiceProject->hours_overview_type == 'short' ) {
					$output = Pdf::hoursShort( $output, $invoiceProject );
				} elseif( $invoiceProject->hours_overview_type == 'default' ) {
					$output = Pdf::hoursFull( $output, $invoiceProject );
				}
			}
		}
		$output->ln();
	}
	
	
	echo $output->output( $filename, "S");
