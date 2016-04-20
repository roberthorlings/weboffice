<?php
	use \Weboffice\Support\IsdatPdf;
	use \Weboffice\Support\Timespan;
	use Carbon\Carbon;
	use Weboffice\Models\Finance\ProfitAndLossStatement;
	
	$timespan = Timespan::create($start, $end);
	
	// Create a new PDF
	$pdf = new IsdatPDF();
	$pdf->setInternal(true);
	
	// Generic settings
	$pdf->setFillColor( 200 );
	$pdf->setNormalLine();
	$pdf->SetDrawColor( 0 );
	$pdf->encoding = 'UTF-8';
	
	$title = "Jaarrekening * isdat softwareontwikkeling " . $start->format( 'Y' );
	$pdf->setTitle($title);
	$pdf->setSubject($title);
	
	// Zet de titel neer in het rood
	$pdf->addPage();
	$pdf->h1($title);
	$pdf->cell( 160, 5, $start->format( 'd-m-Y' )  . " tot " . $end->format( 'd-m-Y' ), 0, 1 );
	
	// Maak een beetje ruimte
	$pdf->Cell(160, 6, '', 0, 1 );
	
	_introduction($pdf, $start);
	
	// Maak een beetje ruimte
	$pdf->Cell(160, 6, '', 0, 1 );
	
	_indicators($pdf, $data['p-and-l'], $data['balance']['end']);
	
	$pdf->addPage();
	Pdf::balanceComparison($pdf, $end, $data['balance']['end'], $data['balance']['start']);
	
	$pdf->AddPage();
	Pdf::profitAndLossComparison($pdf, $timespan, $data['p-and-l'], $previousYearProfitAndLoss);
	
	$pdf->AddPage();
	_commentsEquity($pdf, $data['balance'], $data['p-and-l']);

	$pdf->AddPage();
	_commentsSaldos($pdf, $data['grouped-saldos']);
	
	$pdf->AddPage();
	_commentsAssets($pdf, $data['assets'], $start, $end);
	
	$pdf->AddPage();
	_commentsVAT($pdf, $data['vat'], $data['p-and-l'], $timespan);

	// Toon de PDF zelf
	echo $pdf->output( "", "S");
	
	function _introduction($pdf, $start) {
		$pdf->h2( "Inleiding" );
		$pdf->MultiCell( 160, 5, "In dit rapport vindt u de jaarrekening van * isdat softwareontwikkeling over het jaar " . $start->format( 'Y' ) .". De jaarrekening is samengesteld op basis van de gegevens uit de administratie.", "", 1 );
		
		$pdf->Cell(160, 3, '', 0, 1 );
		
		$pdf->MultiCell( 160, 5, "De activiteit waarop * isdat softwareontwikkeling zich richt bestaat uit softwareontwikkeling, webdevelopment en IT consultancy. De onderneming wordt gedreven voor rekening en risico van de heer Horlings, geboren op 29 oktober 1982.", "", 1);
		
		// Maak een beetje ruimte
		$pdf->Cell(160, 6, '', 0, 1 );
		
		$pdf->h2( "Grondslagen" );
		
		$pdf->setFont( "Gill", "B" );
		$pdf->Cell( 50, 5, "Materiële vaste activa" );
		$pdf->setFont( "Gill", "" );
		$pdf->MultiCell( 110, 5, "De materiële vaste activa met een aanschafwaarde van meer dan 450 euro worden gewaardeerd tegen aanschafwaarde onder aftrek van afschrijvingen gebaseerd op de geschatte economische levensduur.", "", 1 );
		
		$pdf->Cell(160, 2, '', 0, 1 );
		
		/*
		 $pdf->setFont( "Gill", "B" );
		 $pdf->Cell( 50, 5, "Willekeurige afschrijving" );
		 $pdf->setFont( "Gill", "" );
		 $pdf->MultiCell( 110, 5, "In dit jaar is willekeurige afschrijving toegepast op een of meer investeringen.", "", 1 );
		
		 $pdf->Cell(160, 2, '', 0, 1 );
		 */
		
		$pdf->setFont( "Gill", "B" );
		$pdf->Cell( 50, 5, "Overige activa en passiva" );
		$pdf->setFont( "Gill", "" );
		$pdf->MultiCell( 110, 5, "De overige activa en passiva worden gewaardeerd tegen nominale waarde zonder aftrek van een voorziening van oninbaarheid.", "", 1 );
		
		$pdf->Cell(160, 2, '', 0, 1 );
		
		$pdf->setFont( "Gill", "B" );
		$pdf->Cell( 50, 5, "Netto-omzet" );
		$pdf->setFont( "Gill", "" );
		$pdf->MultiCell( 110, 5, "De netto-omzet is de opbrengst van aan derden geleverde goederen en diensten onder aftrek van verleende korting en omzetbelasting.", "", 1 );
	}

	function _indicators($pdf, $profitAndLossStatement, $balance) {
		$pdf->h2( "Kengetallen" );
		
		$pdf->setFont( "Gill", "B" );
		$pdf->Cell( 50, 5, "Omzet" );
		$pdf->setFont( "Gill", "" );
		$pdf->Cell( 110, 5, number_format( $profitAndLossStatement->getTurnover(), 2, ',', '.' ), "", 1 );
		
		$pdf->Cell(160, 2, '', 0, 1 );
		
		$pdf->setFont( "Gill", "B" );
		$pdf->Cell( 50, 5, "Winst" );
		$pdf->setFont( "Gill", "" );
		$pdf->Cell( 110, 5, number_format( $profitAndLossStatement->getRevenue(), 2, ',', '.' ) . " (" . number_format( $profitAndLossStatement->getRevenue() / $profitAndLossStatement->getTurnover() * 100, 0 ) . '% van de omzet)', "", 1 );
		
		$pdf->Cell(160, 2, '', 0, 1 );
		
		$pdf->setFont( "Gill", "B" );
		$pdf->Cell( 50, 5, "Eigen vermogen " . $balance->getDate()->format( 'd-m' ) );
		$pdf->setFont( "Gill", "" );
		$pdf->Cell( 110, 5, number_format( $balance->getEquity()->getAmount() , 2, ',', '.' ), "", 1 );
	}
	
	function _commentsEquity($pdf, $balances, $profitAndLossStatement) {
		$pdf->h2( "Toelichting eigen vermogen" );
		
		// Maak wat ruimte
		$pdf->Cell( 160,6, "", 0, 1 );
		
		// Start eigen vermogen
		$pdf->setFont( "Gill", "B" );
		$pdf->setNormalLine();
		
		$pdf->Cell( 120, 5, "Eigen vermogen " . $balances['start']->getDate()->format( 'd-m-Y' ), "" );
		$pdf->Cell( 20, 5, number_format( $balances['start']->getEquity()->getAmount() , 2, ',', '.' ), "", 0, "R" );
		$pdf->Cell( 60, 5, "", "", 1 );
		
		// Maak wat ruimte
		$pdf->Cell( 160,5, "", 0, 1 );
		
		// Bedrijfsresultaat
		$pdf->Cell( 120, 5, "Bedrijfsresultaat", "" );
		$pdf->Cell( 20, 5, number_format( $profitAndLossStatement->getRevenue(), 2, ',', '.' ), "", 0, "R" );
		$pdf->Cell( 60, 5, "", "", 1 );
		
		$pdf->Cell( 160,3, "", 0, 1 );
		
		$pdf->setFont( "Gill", "" );
		
		Pdf::profitAndLossType($pdf, $profitAndLossStatement->getOther(), -$balances['end']->getEquity()->getAmount(), "Eigen vermogen " . $profitAndLossStatement->getEnd()->format( 'd-m-Y' ) );
		
		$pdf->setFont( "Gill", "" );
		
		// Als er een deel kosten beperkt aftrekbaar is, vermeld dat dan ook
		if( $profitAndLossStatement->getLimitedTotal() != 0 ) {
			$pdf->Cell( 160, 20, "", 0, 1 );
			$pdf->h2( "Toelichting beperkt aftrekbare kosten" );
		
			$pdf->setFontSize( 9 );
		
			// Toon de data
			$pdf->setFont( "Gill", "" );
		
			Pdf::profitAndLossType($pdf, $profitAndLossStatement->getLimited(), $profitAndLossStatement->getLimitedTotal(), "Totaal beperkt aftrekbaar" );
		}
	}
	
	function _commentsSaldos($pdf, $groupedSaldos) {
		$stdLH = 5;
		$pdf->setFontSize( 9 );
		
		if( count( $groupedSaldos ) > 0 ):
			$pdf->AddPage();
			$pdf->h2( "Toelichting debiteuren / crediteuren" );
			
			foreach( $groupedSaldos as $post_id => $postData ) {
				$post = $postData['post'];
				$total = $postData['total'];
				
				// Maak wat ruimte
				$pdf->Cell( 160,5, "", 0, 1 );
					
				$pdf->setFontSize( 9 );
				$pdf->setFont( "Gill", "B" );
					
				// Toon het nummer en de naam van de post
				$pdf->setTextColor( 128 );
				$pdf->Cell(10, $stdLH, $post->nummer, "B" );
				$pdf->setTextColor( 0 );
					
				$pdf->Cell(105, $stdLH, $post->omschrijving, "B" );
					
				$pdf->Cell(15, $stdLH, $total < 0 ? 'credit' : 'debet', "B" );
					
				// Toon het bedrag
				if( $total >= 0 ) {
					// debet
					$pdf->Cell(15, $stdLH, chr(128) . " " . number_format( abs( $total ), 2 ), "B", 0, "R" );
					$pdf->Cell(15, $stdLH, "", "B", 0, "R" );
				} else {
					// credit
					$pdf->Cell(30, $stdLH, chr(128) . " " . number_format( abs( $total ), 2 ), "B", 0, "R" );
				}
				$pdf->ln();
					
				// Zet hieronder alle saldo's bij deze post
				$pdf->setFontSize( 8 );
				$pdf->setFont( "Gill", "" );
				foreach( $postData['saldos'] as $saldo ) {
					// Compute the total for this saldo
					$saldoAmount = 0 ; 	// - = credit, + = debet
					foreach( $saldo->StatementLines as $line ):
						if( $line->post_id == $post_id ) {
							$saldoAmount += $line->getSignedAmount();
						}
					endforeach;
			
					// Print het totaal op het scherm
					if( $saldoAmount != 0 ) {
						// Toon de datum en omschrijving van deze boeking
						$pdf->Cell(80,$stdLH, $saldo->omschrijving );
			
						$pdf->setTextColor( 128 );
						$pdf->Cell(35,$stdLH, $saldo->Relation->bedrijfsnaam );
						$pdf->setTextColor( 0 );
							
						$pdf->Cell(15, $stdLH, $saldoAmount >= 0 ? 'debet' : 'credit' );
						if( $saldoAmount >= 0 ) {
							$pdf->Cell(15, $stdLH, chr(128) . " " . number_format( $saldoAmount, 2, '.', ',' ), 0, 0, "R" );
						} else {
							$pdf->Cell(30, $stdLH, chr(128) . " " . number_format( -$saldoAmount, 2, '.', ',' ), 0, 0, "R" );
						}
			
						$pdf->ln();
					}
				}
			}
		
		endif;
	}
	
	function _commentsAssets($pdf, $assets, $start, $end) {
		$stdLH = 5;
		
		$pdf->h2( "Toelichting activa" );
		
		$investeringPost = -1;
		foreach( $assets as $asset ) {
			if( $investeringPost != $asset->post_investering ) {
				// Maak wat extra ruimte
				$pdf->Cell( 160,5, "", 0, 1 );
		
				$pdf->setFontSize( 9 );
				$pdf->setFont( "Gill", "B" );
		
				// Toon het nummer en de naam van de post
				$pdf->setTextColor( 128 );
				$pdf->Cell(10, $stdLH, $asset->PostInvestering->nummer, "B" );
				$pdf->setTextColor( 0 );
				$pdf->Cell(70, $stdLH, $asset->PostInvestering->omschrijving, "B" );
		
				$pdf->setFont( "Gill", "" );
				$pdf->Cell(20, $stdLH,  "Termijn", "B" );
				$pdf->Cell(20, $stdLH, $start->formatLocalized('%e %b'), "B", 0, "R" );
				$pdf->Cell(20, $stdLH, $end->formatLocalized('%e %b'), "B", 0, "R" );
				$pdf->Cell(20, $stdLH, "Afschrijving", "B", 0, "R" );
		
				$pdf->ln();
				$investeringPost = $asset->post_investering;
			}
			
			$amortization = $asset->amortization();
			
			$pdf->setTextColor( 0 );
			$pdf->Cell( 80, $stdLH, $asset->omschrijving );
			$pdf->Cell( 20, $stdLH, $asset->afschrijvingsduur . " " . $amortization->getPeriodDescription() );
			
			if( $asset->aanschafdatum->lte($start)) {
				// Take the value of the day before, as the value is computed at the end of the day
				$startValue = $asset->getValueOnDate($start->copy()->subDay());
				$pdf->Cell( 20, $stdLH, number_format($startValue, 2 ), "", 0, "R" );
			} else {
				$startValue = $asset->bedrag;
				$pdf->Cell( 20, $stdLH, "-", "", 0, "R" );
			}
			
			$endValue = $asset->getValueOnDate($end);
			$pdf->Cell( 20, $stdLH, number_format( $endValue, 2 ), "", 0, "R" );
			$pdf->Cell( 20, $stdLH, number_format( $startValue - $endValue, 2 ), "", 1, "R" );
				
			$pdf->setTextColor( 100 );
			$pdf->Cell( 50, $stdLH, "Aanschaf: " . $asset->aanschafdatum->format( 'd-m-Y' ) . " voor " . number_format( $asset->bedrag, 2 ) );
			$pdf->Cell( 40, $stdLH, "Restwaarde: " . number_format( $asset->rest_waarde, 2 ) );
				
			// Maak wat ruimte
			$pdf->Cell( 160,5, "", 0, 1 );
				
		}
		
		$pdf->setTextColor( 0 );
	}
	
	function _commentsVAT($pdf, $vatOverviews, $profitAndLossStatement, $timespan) {
		$pdf->addPage();
		
		$pdf->h2( "Toelichting omzetbelasting" );
		
		$pdf->Cell( 160,3, "", 0, 1 );
		
		$pdf->Cell( 70, 5, "" );
		$pdf->Cell( 30, 5, "omzet", "", 0, "R" );
		$pdf->Cell( 30, 5, "omzetbelasting", "", 1, "R" );
		
		$pdf->Cell( 160,3, "", 0, 1 );
		
		$pdf->Cell( 130, 5, "Gehele jaar", "B", 1 );
		$pdf->Cell( 160,1, "", 0, 1 );
		
		$pdf->Cell( 70, 5, "Omzetbelasting " . $timespan, "" );
		$pdf->Cell( 30, 5, number_format( $profitAndLossStatement->getTurnover(), 2, ',', '.' ), "", 1, "R" );
		
		$pdf->Cell( 160,3, "", 0, 1 );
		
		$pdf->Cell( 130, 5, "Eerdere aangiftes", "B", 1 );
		$pdf->Cell( 160,1, "", 0, 1 );
		
		$btw_totaal = array( "omzet" => 0, "btw" => 0 );
		foreach( $vatOverviews as $vatOverview ) {
			$revenue = -$vatOverview->getRevenue()->getTotal();
			$vat = -$vatOverview->getVAT();
			
			$pdf->Cell( 70, 5, $vatOverview->getPeriod(), "" );
			$pdf->Cell( 30, 5, number_format( $revenue , 2, ',', '.' ), "", 0, "R" );
			$pdf->Cell( 30, 5, number_format( $vat, 2, ',', '.' ), "", 0, "R" );	// BTW wordt afgerond
		
			$btw_totaal[ "omzet" ] += $revenue;
			$btw_totaal[ "btw" ] += $vat;
		
			if( !$vatOverview->isPayed() ) {
				$pdf->Cell( 20, 5, "Nog niet betaald" );
			}
		
			$pdf->ln();
		}
		
		$pdf->Cell( 160,2, "", 0, 1 );
		
		$pdf->Cell( 70, 6, "Totaal eerdere aangiftes", "T" );
		$pdf->Cell( 30, 6, number_format( $btw_totaal[ "omzet" ] , 2, ',', '.' ), "T", 0, "R" );
		$pdf->Cell( 30, 6, number_format( $btw_totaal[ "btw" ], 2, ',', '.' ), "T", 1, "R" );
				
	}