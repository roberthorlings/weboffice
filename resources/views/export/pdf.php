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
	
	$firstpage = true;
	
	// If needed, add the balances
	if( array_key_exists( "balance", $data) ) {
		_pdfBalans( $pdf, $data['balance']['start'], $firstpage );
		$firstpage = !_pdfBalans( $pdf, $data['balance']['end'], $firstpage );
	}
	
	if( array_key_exists( "statements", $data) ) {
		_pdfStatements( $pdf, $data['statements'], $firstpage );
	}
	
	if( array_key_exists( "ledgers", $data) ) {
		_pdfLedgers( $pdf, $data['ledgers'], $firstpage );
	}
	
	if( array_key_exists( "p-and-l", $data) ) {
		_pdfProfitAndLoss( $pdf, $data['p-and-l'], $firstpage );
	}
	
	if( array_key_exists( "saldos", $data) ) {
		_pdfAmountsDue( $pdf, $end, $data['saldos'], $firstpage );
	}
	
	// Toon de PDF zelf
	echo $pdf->output( $filename, "S");
	
	function _pdfBalans( $pdf, $balance, $firstpage ) {
		// Zonder bedragen ook geen balans
		if( !$balance->hasData() ) {
			return !$firstpage;
		}
	
		if( !$firstpage ) {
			$pdf->AddPage();
		}
	
		$pdf->h2( "Balans " . $balance->getDate()->format( "d-m-Y" ) );
	
		// Zet de gegevens in de PDF
		$pdf->setFontSize( 9 );
		$pdf->setNormalLine();
	
		// Zet de datum erboven en teken lijntjes
		$pdf->Cell( 160, 5, $balance->getDate()->format( "d-m-Y" ), "B", 1, "C" );
	
		$y = $pdf->getY();
	
		// Bepaal waar het eind van de regels is, en het totaal getoond kan worden.
		$yEind = $y + max( count( $balance->credit() ), count( $balance->debet() ) ) * 5 + 2 * 1;
		$lijnEind = $yEind + 5;
	
		$pdf->Line( 105, $y, 105, $lijnEind );
	
		// Zet beide zijden neer
		$offset = array( "debet" => 27, "credit" => 107 );
			
		foreach( array( "debet", "credit" ) as $zijde ) {
			$pdf->setY( $y );
	
			// Voeg wat ruimte in
			$pdf->Cell( 10, 1, "", 0, 1 );
	
			foreach( $balance->getBalance($zijde) as $postTotal ) {
				$pdf->setX( $offset[ $zijde ] );

				// Zet het nummer grijs op het scherm
				$pdf->setTextColor( 128 );
				$pdf->Cell( 8, 5, $postTotal->getPost()->nummer );

				$pdf->setTextColor( 0 );
				$pdf->Cell( 55, 5, $postTotal->getPost()->omschrijving );
				$pdf->Cell( 13, 5, number_format( $postTotal->getAmount(), 2, ".", "" ), 0, 1, "R" );
			}
		}
	
		$pdf->setY( $yEind );
	
		// Zet ook het balanstotaal neer
		$pdf->setNormalLine();
		$pdf->Cell( 78, 5, number_format( $balance->debetTotal(), 2, ".", "" ), "T", 0, "R" );
		$pdf->Cell( 80, 5, number_format( $balance->creditTotal(), 2, ".", "" ), "T", 0, "R" );
		$pdf->Cell( 2, 5, "", "T", 1 );
	
		return true;
	}
	
	function _pdfStatements($pdf, $statements, $firstpage) {
		if( !$firstpage ) {
			$pdf->AddPage();
		}
		$firstpage = false;
		
		$stdLH = 4;
		$deelLH = 3;
		
		$pdf->h2( "Boekingen" );
		
		$pdf->setFontSize( 7 );
		
		foreach( $statements as $statement ) {
			// Controleer of deze boeking nog wel op de pagina past
			// Zo nee, dan voegen we een nieuwe pagina in
			if( $pdf->getY() + 3 + $stdLH + count( $statement->StatementLines ) * $deelLH > 290 - 20 ) {
				$pdf->addPage();
			}
		
			$pdf->SetFont( "Gill", "B" );
		
			// Toon de datum en omschrijving van deze boeking
			$pdf->Cell(160,3, "",0, 1);
			$pdf->Cell(20,$stdLH, $statement->datum->format( 'd-m-Y' ) );
			$pdf->Cell(70,$stdLH, $statement->omschrijving);
		
			$pdf->setFont( "Gill", "" );
		
			// Sla de X en Y positie op om later hier weer verder te gaan
			$pdf->ln();
			$y = $pdf->getY();
			$x = $pdf->getX();
		
			// Toon de hele beschrijving
			$pdf->setX( 125 );
			$pdf->MultiCell( 60, $deelLH, $statement->opmerkingen );
		
			// Bepaal hoe hoog het hok van de multicell is geworden
			// Als die namelijk straks langer is dan de lijst met boekingen, dan moet
			// er wat extra ruimte gemaakt worden
			$nieuwY = $pdf->getY();
		
			// Stel nu de X en Y weer in, zodat de boekingen er netjes naast komen
			$pdf->setXY( $x, $y );
		
			// Zet ook de boekingdelen weer neer
			foreach( $statement->StatementLines as $statementLine ) {
				$pdf->Cell(10, $deelLH, ( $statementLine->credit ? "aan" : "" ) );
				$pdf->Cell(10, $deelLH, $statementLine->Post->nummer );
				$pdf->Cell(45, $deelLH, $statementLine->Post->omschrijving );
				if( !$statementLine->credit ) {
					$pdf->Cell(30, $deelLH, number_format( $statementLine->bedrag, 2 ), 0, 0, "R" );
				} else {
					$pdf->Cell(30, $deelLH, number_format( $statementLine->bedrag, 2 ), 0, 0, "R" );
				}
		
				$pdf->ln();
			}
		
			// Controleer nu of het systeem nog verder naar beneden moet of niet
			if( $nieuwY > $pdf->getY() ) {
				$pdf->setY( $nieuwY );
			}
		}
		
		
	}
	
	function _pdfLedgers($pdf, $ledgers, $firstpage) {
		$stdLH = 4;
		$deelLH = 3;
		
		if( !$firstpage ) {
			$pdf->AddPage();
		}
		$firstpage = false;
		
		$pdf->h2( "Grootboeken" );
		
		$pdf->setFontSize( 7 );
		
		foreach( $ledgers->getLedgers() as $ledger ) {
			// Als alleen de titel (of niks meer) op de pagina past, ga dan
			// eerst naar de volgende pagina
			if( $pdf->getY() + $stdLH + $stdLH > $pdf->getPageBreakTrigger() ) {
				$pdf->addPage();
			}
	
			// Toon de grootboektitel
			_ledgerTitle( $pdf, $ledger->getPost(), $stdLH );
	
			// Zet ook de boekingdelen weer neer
			$totaal = 0;
	
			// Zet eerst het startbedrag neer (Van balans)
			if( $ledger->getInitial() != 0 ) {
				_showLedgerLine($pdf, $ledger->getPost(), null, "Van balans", $ledger->getInitial(), $stdLH, $deelLH, $color = 80 );
			}
				
			// Zet dan ook de andere boekingdelen neer
			foreach($ledger->getStatementLines() as $statementLine) {
				_showLedgerLine($pdf, $ledger->getPost(), $statementLine->Statement->datum, $statementLine->Statement->omschrijving, $statementLine->getSignedAmount(), $stdLH, $deelLH );
			}
	
			if( $pdf->getY() + $deelLH > $pdf->getPageBreakTrigger() ) {
				$pdf->addPage();
				_ledgerTitle( $pdf, $post, $stdLH, true );
			}
	
			// Zet de totaalregel neer
			$pdf->setFont( "Gill", "B" );
			_showLedgerLine($pdf, $ledger->getPost(), null, "Totaal", $ledger->getTotal(), $stdLH, $deelLH );
			$pdf->setFont( "Gill", "" );
	
			// Maak een beetje ruimte
			$pdf->Cell(20, 3, '', 0, 1 );
		}
	}


	function _pdfProfitAndLoss($pdf, $statement, $firstpage) {
		$stdLH = 4;
		$deelLH = 3;
		
		if( !$firstpage ) {
			$pdf->AddPage();
		}
		$firstpage = false;
	
		$pdf->h2( "Winst en verliesrekening " . Timespan::create($statement->getStart(), $statement->getEnd()) );
	
		$pdf->setFontSize( 9 );
	
		// Zet nu de typen posten die bijdragen aan het resultaat in de PDF
		_showProfitAndLossType($pdf, $statement->getResults(), $statement->getResultTotal(), "Bedrijfsresultaat");
	
		if(abs($statement->getOtherTotal()) > 0.005) {
			// Maak wat ruimte
			$pdf->Cell( 160,8, "", 0, 1 );
	
			_showProfitAndLossType($pdf, $statement->getOther(), $statement->getResultTotal() + $statement->getOtherTotal(), "Wijziging eigen vermogen");
		}
	
		if(abs($statement->getLimitedTotal()) > 0.005) {
			// Maak wat ruimte
			$pdf->Cell( 160,16, "", 0, 1 );
	
			$pdf->h2( "Beperkt aftrekbare kosten" );
			$pdf->setFontSize( 9 );
			_showProfitAndLossType($pdf, $statement->getLimited(), $statement->getLimitedTotal(), "Totaal beperkt aftrekbare kosten");
		}
	}
	

	// Voeg daarna, indien gewenst, de openstaande posten toe
	function _pdfAmountsDue($pdf, $date, $saldos, $firstpage) {
		$stdLH = 4;
		$deelLH = 3;
		
		if( !$firstpage ) {
			$pdf->AddPage();
		}
		$firstpage = false;
	
		$pdf->h2( "Openstaande posten " . $date->format( "d-m-Y" ) );
	
		$pdf->setFontSize( 9 );
	
		if( count( $saldos ) > 0 ):
			foreach ($saldos as $saldo):
				// Als alleen de titel (of niks meer) op de pagina past, ga dan
				// eerst naar de volgende pagina
				if( $pdf->getY() + $stdLH + $stdLH > $pdf->getPageBreakTrigger() ) {
					$pdf->addPage();
				}
					
				// Toon de grootboektitel
				_saldoTitle( $pdf, $saldo, $stdLH );
					
				$totaal = 0;
					
				foreach( $saldo->StatementLines as $statementLine) {
					$totaal += $statementLine->getSignedAmount();
			
					if( $pdf->getY() + $stdLH > $pdf->getPageBreakTrigger() ) {
						$pdf->addPage();
						_saldoTitle( $pdf, $saldo, $stdLH, true );
					}
			
					$pdf->Cell(20, $stdLH, $statementLine->Statement->datum->format( 'd-m-Y' ) );
			
					$pdf->setTextColor( 128 );
					$pdf->Cell(10, $stdLH, $statementLine->Post->nummer );
					$pdf->setTextColor( 0 );
			
					$pdf->Cell(50, $stdLH, $statementLine->Post->omschrijving );
					$pdf->Cell(20, $stdLH, $statementLine->credit ? 'credit' : 'debet' );
					if( !$statementLine->credit ) {
						$pdf->Cell(15, $stdLH, number_format( $statementLine->bedrag, 2 ), 0, 0, "R" );
					} else {
						$pdf->Cell(30, $stdLH, number_format( $statementLine->bedrag, 2 ), 0, 0, "R" );
					}
			
					$pdf->ln();
				}
					
				if( $pdf->getY() + $deelLH > $pdf->getPageBreakTrigger() ) {
					$pdf->addPage();
					_saldoTitle( $pdf, $saldo, $stdLH, true );
				}
					
				// Zet de totaalregel neer
				$pdf->setFont( "Gill", "B" );
				$pdf->Cell(80, $stdLH, "Open sinds " . $date->diffInDays($saldo->getStartDate()) . " dagen");
				$pdf->Cell(20, $stdLH, $totaal > 0 ? 'debet' : ( $totaal < 0 ? 'credit' : '' ) );
				if( $totaal >= 0 ) {
					// debet
					$pdf->Cell(15, $stdLH, number_format( abs( $totaal ), 2 ), 0, 0, "R" );
				} else {
					// credit
					$pdf->Cell(30, $stdLH, number_format( abs( $totaal ), 2 ), 0, 0, "R" );
				}
				$pdf->setFont( "Gill", "" );
					
				// Maak een beetje ruimte
				$pdf->Cell(20, 5, '', 0, 1 );
			endforeach;
		else:
			$pdf->Cell(160, $stdLH, "Geen openstaande posten." );
		endif;
	}
	
	function _showLedgerLine($pdf, $post, $date, $title, $amount, $stdLH, $deelLH, $color = 0) {
		$stdLH = 4;
		$deelLH = 3;
		if( $pdf->getY() + $deelLH > $pdf->getPageBreakTrigger() ) {
			$pdf->addPage();
			_ledgerTitle( $pdf,  $post, $stdLH, true );
		}
	
		$pdf->setTextColor( $color );
	
		$credit = $amount < 0;
	
		$pdf->Cell(20, $deelLH, $date ? $date->format('d-m-Y') : '' );
		$pdf->Cell(80, $deelLH, $title );
		$pdf->Cell(20, $deelLH, $credit ? "credit" : "debet" );
		if( !$credit ) {
			$pdf->Cell(15, $deelLH, number_format( abs($amount), 2 ), 0, 0, "R" );
		} else {
			$pdf->Cell(30, $deelLH, number_format( abs($amount), 2 ), 0, 0, "R" );
		}
	
		$pdf->ln();
		$pdf->setTextColor( 0 );
	}
	
	
	function _ledgerTitle( &$pdf, $post, $stdLH, $continued = false ) {
		$pdf->SetFont( "Gill", "B" );
		$pdf->setFontSize( 8 );
	
		// Toon de datum en omschrijving van deze boeking
		$pdf->Cell(160,3, "",0, 1);
		$pdf->Cell(20,$stdLH, $post->nummer );
		$pdf->Cell(70,$stdLH, $post->omschrijving . ( $continued ? ' (vervolgd)' : '' ) );
	
		$pdf->setFont( "Gill", "" );
		$pdf->setFontSize( 7 );
	
		$pdf->ln();
	}
	
	function _saldoTitle( $pdf, $saldo, $stdLH, $continued = false ) {
		$pdf->SetFont( "Gill", "B" );
		$pdf->setFontSize( 8 );
	
		// Toon de datum en omschrijving van deze boeking
		$pdf->Cell(160, 3, "", 0, 1);
		$pdf->Cell(90,$stdLH, $saldo->omschrijving . ( $continued ? ' (vervolgd)' : '' ) );
		$pdf->Cell(70,$stdLH, $saldo->Relation->bedrijfsnaam, 0, 0, 'R' );
	
		$pdf->ln();
	
		$pdf->setFontSize( 7 );
		$pdf->setFont( "Gill", "" );
	
	}
	
	function _showProfitAndLossType($pdf, $data, $total, $totalDescription) {
		foreach( $data as $category => $posts):
			_wenvType($pdf, $category, $posts);
		endforeach;
		
		// Zet het resultaat in de PDF
		$pdf->setFont( "Gill", "B" );
		$pdf->setMediumLine();
		
		// Maak wat ruimte
		$pdf->Cell( 160,3, "", 0, 1 );
		
		$pdf->Cell( 100, 5, $totalDescription, "T" );
		$pdf->Cell( 20, 5, number_format( -$total, 2, '.', '' ), "T", 0, "R" );
		$pdf->Cell( 20,5, "", "T" );
		
		$pdf->setFont( "Gill", "" );
				
	}
	
	function _wenvType( &$pdf, $category, $posts ) {
		// Zorg voor een klein beetje ruimte tussen de verschillende categorieen
		$pdf->Cell( 160,3 , "", 0, 1 );
	
		// Zet de omschrijving en het totaal neer
		$pdf->setFont( "Gill", "B" );
		$pdf->setNormalLine();
	
		$pdf->Cell( 100, 5, $posts->getLabel(), "B" );
		$pdf->Cell( 20, 5, number_format( -$posts->getTotal(), 2, '.', '' ), "B", 0, "R" );
		$pdf->Cell( 20, 5, "", "B" );
		$pdf->ln();
	
		$pdf->setFont( "Gill", "" );
		$pdf->setThinLine();
	
		$eerste = true;
		foreach($posts as $total):
			// Dit zorgt ervoor dat de eerste geen border krijgt, en de rest wel
			$border = "B"; // ( $eerste ? "" : "T" );
		
			$pdf->Cell( 10, 5, "", $border );
			$pdf->Cell( 100, 5, $total->getPost()->omschrijving, $border );
		
			if( $total->getPost()->percentage_aftrekbaar > 0 && $total->getPost()->percentage_aftrekbaar < 100 ):
				$pdf->setTextColor( 128 );
				$pdf->Cell( 10, 5, $total->getPost()->percentage_aftrekbaar . " %", $border, "R"  );
				$pdf->setTextColor( 0 );
			else:
				$pdf->Cell( 10, 5, "", $border  );
			endif;
		
			$pdf->Cell( 20, 5, number_format( -$total->getSignedAmount(), 2, '.', '' ), $border, 0, "R" );
			$pdf->ln();
		
			$eerste = false;
		endforeach;
	}
