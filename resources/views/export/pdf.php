<?php
	use \Weboffice\Support\IsdatPdf;
	use \Weboffice\Support\Timespan;
	use Carbon\Carbon;
	
	// Create a new PDF
	$pdf = new IsdatPDF();
	$pdf->setInternal(true);
	
	// Generic settings
	$pdf->setFillColor( 200 );
	$pdf->setNormalLine();
	$pdf->SetDrawColor( 0 );
	$pdf->encoding = 'UTF-8';
	
	$title = "Administratie * isdat softwareontwikkeling " . Timespan::create($start, $end);
	
	$pdf->setSubject($title);
	$pdf->setTitle($title);
	$pdf->addPage();
	$pdf->h1($title);
	
	// If needed, add the balances
	if( array_key_exists( "balance", $data) ) {
		$pdf->AddPage();
		Pdf::balance( $pdf, $data['balance']['start']);
		Pdf::balance( $pdf, $data['balance']['end']);
	}
	
	if( array_key_exists( "statements", $data) ) {
		$pdf->AddPage();
		Pdf::statements( $pdf, $data['statements'] );
	}
	
	if( array_key_exists( "ledgers", $data) ) {
		$pdf->AddPage();
		Pdf::ledgers( $pdf, $data['ledgers']);
	}
	
	if( array_key_exists( "p-and-l", $data) ) {
		$pdf->AddPage();
		Pdf::profitAndLoss( $pdf, $data['p-and-l'] );
	}
	
	if( array_key_exists( "saldos", $data) ) {
		$pdf->AddPage();
		Pdf::amountsDue( $pdf, $end, $data['saldos'] );
	}
	
	// Toon de PDF zelf
	echo $pdf->output( $filename, "S");
