<?php
namespace Weboffice\Support;

use Weboffice\Models\Finance\Ledgers;
use Weboffice\Models\Finance\Balance;
use Illuminate\Database\Eloquent\Collection;
use Weboffice\Models\Finance\ProfitAndLossStatement;
use Carbon\Carbon;

class PdfHelper
{
	static $persoon = array(
		"naam" => "Robert",
		"email" => "robert@isdat.nl",
		"telefoon" => "06-14247418"
	);
	
	// IsdatPdf::getBedrijfsGegevens()
	
	/**
	 * Writes the details of an invoice to the PDF
	 * @param unknown $pdf
	 * @param unknown $invoice
	 */
	static function invoice( $pdf, $invoice ) {
			
		// zet de titels van de kolommen neer
		$pdf->setFont( "Gill", "B" );
		$pdf->Cell( 100, 5, "Omschrijving", 0, 0, "L", 1 );
		$pdf->Cell( 16, 5, "Aantal", 0, 0, "L", 1 );
		$pdf->Cell( 22, 5, "Prijs", 0, 0, "R", 1 );
		$pdf->Cell( 22, 5, "Subtotaal", 0, 1, "R", 1 );
		$pdf->setFont( "Gill", "" );
			
		// Zorg voor een klein beetje ruimte
		$pdf->Cell( 160, 3, "", 0, 1 );
			
		// Zet alle factuurregels neer
		$pdf->SetDrawColor( 150 );
			
		foreach( $invoice->InvoiceLines as $line ) {
			// Zet eerst een lijn neer
			$pdf->Cell( 160, 1,"", "T", 2 );
	
			// Zet eerst de tekst neer
			$y_regel = $pdf->getY();
			$pdf->MultiCell( 95, 5, $line->omschrijving );
	
			// Zet de extra tekst iets kleiner en schuin neer
			if( trim( $line->extra ) != "" ) {
				$pdf->Cell( 5, 4 );
				$pdf->setFontSize( $pdf->stdFontSize - 1 );
				$pdf->setFont( "Gill", "I" );
					
				$pdf->MultiCell( 90, 4, $line->extra );
					
				$pdf->setFont( "Gill" );
				$pdf->setFontSize( $pdf->stdFontSize );
			}
	
			$y_volgende_regel = $pdf->getY();
	
			// Zet ook de getallen neer
			$pdf->setXY( 100 + $pdf->getLeftMargin(), $y_regel );
			$pdf->Cell( 16, 5, (float) $line->aantal  );
			$pdf->Cell( 22, 5, chr( 128 ) . " " . number_format( $line->prijs, 2, ",", "" ), 0, 0, "R", 0, "", false );
			$pdf->Cell( 22, 5, chr( 128 ) . " " . number_format( $line->getSubtotal(), 2, ",", "" ), 0, 1, "R", 0, "", false );
	
			$pdf->setY( $y_volgende_regel + 2 );
		}
	
		// Zet eerst een lijn neer
		$pdf->Cell( 160, 6,"", "T", 2 );
	
		$pdf->SetDrawColor( 0 );
			
		if( $invoice->btw ) {
			// Toon het totaal
			$pdf->Cell( 100, 5, "" );
			$pdf->Cell( 38, 5, "Totaal (excl. BTW):", "T" );
				
			$pdf->Cell( 22, 5, chr( 128 ) . " " . number_format( $invoice->getSubtotal(), 2, ",", "" ), "T", 1, "R", 0, "", false );
				
			// Toon de BTW
			$pdf->Cell( 100, 5 );
			$pdf->Cell( 38, 5, "BTW " . $invoice->getVATPercentage() . "%", "B" );
				
			$pdf->Cell( 22, 5, chr( 128 ) . " " . number_format( $invoice->getVAT(), 2, ",", "" ), "B", 1, "R", 0, "", false );
		}
	
		// Toon het totaalbedrag
		$pdf->Cell( 160,3, "", 0, 1 );
		$pdf->setFont( "Gill", "B" );
		$pdf->Cell( 100, 5 );
		$pdf->Cell( 38, 5, "Totaal" . ( $invoice->btw ? " (incl. BTW)" : "" ) . ":", 0, 0, "L", 1 );
	
		$pdf->Cell( 22, 5, chr( 128 ) . " " . number_format( $invoice->getTotal(), 2, ",", "" ), 0, 1, "R", 1, "", false );
	
		// Zorg voor een klein beetje ruimte
		$pdf->setFont( "Gill", "" );
		$pdf->ln();
			
		return $pdf;
	}
	
	/**
	 * Writes a full report on the hours for a project
	 * @param unknown $pdf
	 * @param unknown $invoiceProject
	 * @param unknown $werktijden
	 * @param unknown $totale_duur
	 */
	static function hoursFull( $pdf, $invoiceProject ) {
		// zet de titels van de kolommen neer
		$pdf->setFont( "Gill", "B" );
		$pdf->Cell( 25, 5, "Datum", 0, 0, "L", 1 );
		$pdf->Cell( 110, 5, "Omschrijving", 0, 0, "L", 1 );
		$pdf->Cell( 25, 5, "Aantal uur", 0, 0, "R", 1 );
		$pdf->setFont( "Gill", "" );
			
		// Zorg voor een klein beetje ruimte
		$pdf->Cell( 160, 5, "", 0, 1 );
			
		// Zet alle regels met specificatie neer
		$pdf->SetDrawColor( 150 );
			
		// Hardcoded flag to disable showing the company name for now
		// TODO: consider removing this code if it turns out not to be used
		$showRelation = false;
		
		foreach( $invoiceProject->WorkingHours as $registration ) {
			// Zet eerst een lijn neer
			$pdf->Cell( 160, 1,"", "T", 2 );
	
			if( $pdf->getY() + 5 + 5 + ( $showRelation ? 4 : 0 ) + 5 > $pdf->getPageBreakTrigger() ) {
				$pdf->addPage();
			}
	
			$y_regel = $pdf->getY();
	
	
			$pdf->Cell( 25, 5, $registration->datum->format( 'd-m' ) );
	
			// Zet eerst de omschrijving neer
			$pdf->MultiCell( 105, 5, $registration->opmerkingen );
	
			// Zet de klantnaam iets kleiner en schuin neer
			if( $showRelation ) {
				$pdf->Cell( 25, 4 );
				$pdf->setFontSize( $pdf->stdFontSize - 1 );
				$pdf->setFont( "Gill", "I" );
					
				$pdf->MultiCell( 105, 4, $registration->Relation->bedrijfsnaam );
					
				$pdf->setFont( "Gill" );
				$pdf->setFontSize( $pdf->stdFontSize );
			}
	
			$y_volgende_regel = $pdf->getY();
	
			// Zet ook de getallen neer
			$pdf->setXY( 130 + $pdf->getLeftMargin(), $y_regel );
			$pdf->Cell( 30, 5, $registration->duration->format( '%H:%I' ), 0, 0, "R" );
	
			$pdf->setY( $y_volgende_regel + 2 );
		}
	
		// Zet eerst een lijn neer
		$pdf->Cell( 160, 6,"", "T", 2 );
	
		$pdf->SetDrawColor( 0 );
			
		// Show the total sum
		$totalMinutes = $invoiceProject->getTotalWorkingHours() * 60;
		$pdf->Cell( 25, 5, "", "T" );
		$pdf->Cell( 105, 5, "Totaal:", "T" );
		$pdf->Cell( 30, 5, sprintf( '%d:%02d', floor( $totalMinutes / 60 ), $totalMinutes % 60 ), "T", 1, "R" );
	
		// Zorg voor een klein beetje ruimte
		$pdf->setFont( "Gill" );
		$pdf->ln();
	
		return $pdf;
	}

	/**
	 * Shows the registration in the short overview
	 * @param unknown $pdf
	 * @param unknown $registration
	 */
	protected static function registrationShort( $pdf, $registration ) {
		$pdf->Cell( 35, 5, $registration->datum->format( 'd-m' ) );
		$pdf->Cell( 35, 5, $registration->duration->format( '%H:%I' ), 0, 0, "R" );
	}
	
	/**
	 * Shows a short overview of hour registration in PDF
	 * @param unknown $pdf
	 * @param unknown $relatie
	 * @param unknown $werktijden
	 * @param unknown $totale_duur
	 */
	static function hoursShort( $pdf, $invoiceProject ) {
		// zet de titels van de kolommen neer
		$pdf->setFont( "Gill", "B" );
		$pdf->Cell( 35, 5, "Datum", 0, 0, "L", 1 );
		$pdf->Cell( 35, 5, "Aantal uur", 0, 0, "R", 1 );
		$pdf->Cell( 20, 5, '', 0, 0, 'L', 1 );
		$pdf->Cell( 35, 5, "Datum", 0, 0, "L", 1 );
		$pdf->Cell( 35, 5, "Aantal uur", 0, 0, "R", 1 );
			
		$pdf->setFont( "Gill", "" );
			
		// Zorg voor een klein beetje ruimte
		$pdf->Cell( 160, 5, "", 0, 1 );
			
		// Zet alle regels met specificatie neer
		$pdf->SetDrawColor( 150 );
			
		// Divide registration in two columns
		$numRegistrations = count( $invoiceProject->WorkingHours );
		$halfRegistrations = ceil( $numRegistrations / 2 );
			
		for( $i = 0; $i < $halfRegistrations; $i++ ) {
			// Zet eerst een lijn neer
			$pdf->Cell( 160, 1,"", "T", 2 );
	
			// Ga naar de volgende pagina als deze regel er niet meer op past
			if( $pdf->getY() + 5 + 5 + 5 > $pdf->getPageBreakTrigger() ) {
				$pdf->addPage();
			}
	
			$y_regel = $pdf->getY();
	
			// Show the left column
			self::registrationShort($pdf, $invoiceProject->WorkingHours[$i]);
	
			// Zet ook de tweede werktijd neer
			if( $i + $halfRegistrations < $numRegistrations ) {
				$pdf->Cell( 20, 5, '' );
				self::registrationShort($pdf, $invoiceProject->WorkingHours[$i + $halfRegistrations]);
			}
	
			$pdf->ln();
		}
	
		// Zet eerst een lijn neer
		$pdf->Cell( 160, 6,"", "T", 2 );
	
		$pdf->SetDrawColor( 0 );
			
		// Toon het totaal
		$pdf->Cell( 125, 5, "Totaal:", "T" );
		$totalMinutes = $invoiceProject->getTotalWorkingHours() * 60;
		$pdf->Cell( 35, 5, sprintf( '%d:%02d', floor( $totalMinutes / 60 ), $totalMinutes % 60 ), "T", 1, "R" );
	
		// Zorg voor een klein beetje ruimte
		$pdf->setFont( "Gill" );
		$pdf->ln();
	
		return $pdf;
	}
	
	
	static function travelexpenses( $pdf, $list, $stats, $total ) {
		// zet de titels van de kolommen neer
		$pdf->setFont( "Gill", "B" );
		$pdf->Cell( 25, 5, "Datum", 0, 0, "L", 1 );
		$pdf->Cell( 105, 5, "Bestemming", 0, 0, "L", 1 );
		$pdf->Cell( 30, 5, "Aantal km", 0, 0, "R", 1 );
		$pdf->setFont( "Gill", "" );
			
		// Zorg voor een klein beetje ruimte
		$pdf->Cell( 160, 5, "", 0, 1 );
			
		// Zet alle regels met specificatie neer
		$pdf->SetDrawColor( 150 );
		
		foreach($list as $item) {
			// Zet eerst een lijn neer
			$pdf->Cell( 160, 1,"", "T", 2 );
	
			if( $pdf->getY() + 5 + 5 > $pdf->getPageBreakTrigger() ) {
				$pdf->addPage();
			}
	
			$y_regel = $pdf->getY();
	
			$pdf->Cell( 25, 5, $item->WorkingHour->datum->format('d-m-Y') );
	
			// Zet eerst de omschrijving neer
			$pdf->MultiCell( 105, 5, $item->van_naar );
	
			// Zet het bezoekadres iets kleiner en schuin neer
			$pdf->Cell( 25, 4 );
			$pdf->setFontSize( $pdf->stdFontSize - 2 );
			$pdf->setFont( "Gill", "I" );
	
			$pdf->MultiCell( 105, 4, $item->bezoekadres );
	
			$pdf->setFont( "Gill" );
			$pdf->setFontSize( $pdf->stdFontSize );
	
			// Ga terug naar de juiste hoogte om het aantal kilometer neer te zeten
			$y_volgende_regel = $pdf->getY();
	
			// Zet ook de getallen neer
			$pdf->setXY( 130 + $pdf->getLeftMargin(), $y_regel );
			$pdf->Cell( 30, 5, number_format( $item->afstand, 0, '', '.' ) . " km", 0, 1, "R" );
	
			// Zet ook de kilometerstanden neer, in het grijs en klein
			$pdf->Cell( 130, 4, '' );
			$pdf->setFontSize( $pdf->stdFontSize - 3 );
			$pdf->setFont( "Gill", "I" );
			$pdf->setTextColor( 128 );
	
			if( $item->wijze ):
			$pdf->Cell( 30, 4, number_format( $item->km_begin, 0, '', '.' ) . ' - ' . number_format( $item->km_eind, 0, '', '.' ) , 0, 0, "R" );
			else:
			$pdf->Cell( 30, 4, $item->wijze, 0, 0, "R" );
			endif;
	
			$pdf->setTextColor( 0 );
			$pdf->setFont( "Gill" );
			$pdf->setFontSize( $pdf->stdFontSize );
	
			$pdf->setY( $y_volgende_regel + 2 );
		}
	
		// Zet eerst een lijn neer
		$pdf->Cell( 160, 6,"", "T", 2 );
	
		$pdf->SetDrawColor( 0 );
		$pdf->setFont( "Gill" );
		
		foreach($stats as $stat) {
			$pdf->Cell( 25, 5, "" );
			$pdf->Cell( 105, 5, "Totaal " . $stat->wijze . ":" );
			$pdf->Cell( 30, 5, $stat->total . " km", "", 1, "R" );
				
		}
		
		$pdf->setFont( "Gill", "B" );
		
		// Toon het totaal
		$pdf->Cell( 25, 5, "", "T" );
		$pdf->Cell( 105, 5, "Totaal:", "T" );
		$pdf->Cell( 30, 5, $total . " km", "T", 1, "R" );

		// Zorg voor een klein beetje ruimte
		$pdf->setFont( "Gill" );
		$pdf->ln();
	
		return $pdf;
	}
	
	static function bedrijfsEnRelatieGegevens( $pdf, $relation ) {
		// Zet nu de persoonsgegevens en bedrijfsgegevens neer
		$pdf->setFont( "Gill", "B" );
		$pdf->Cell( 100, 5, !is_null( $relation ) ? "Klantgegevens:" : "" , 0, 0, "L", 1 );
		$pdf->Cell( 60, 5, "Bedrijfsgegevens:", 0, 1, "L", 1 );
	
		// Zorg voor een klein beetje ruimte
		$pdf->Cell( 160, 2, "", 0, 1 );
			
		$pdf->setFont( "Gill", "" );
			
		if( is_null( $relation ) ) {
			$pgegevens = "";
		} else {
			$pgegevens = $relation->bedrijfsnaam . "\n" . $relation->adres . "\n" . $relation->postcode . " " . $relation->plaats . "\n\nContactpersoon: " . $relation->contactpersoon;
		}
			
		$companyInfo = IsdatPdf::getBedrijfsGegevens();
		$bgegevens = $companyInfo[ "naam" ] . "\n" .
				$companyInfo[ "adres" ] . "\n" .
				$companyInfo[ "postcode" ] . " " .
				$companyInfo[ "plaats" ] . "\n\n" .
				"IBAN:  " .
				$companyInfo[ "iban" ] . "\n" .
				"t.n.v. " . $companyInfo[ "tnv" ];
	
		$y = $pdf->getY();
		if( !is_null( $relation ) ) {
			$pdf->MultiCell( 100, 5, $pgegevens );
		}
		$pdf->setXY( 100 + $pdf->getLeftMargin(), $y );
		$pdf->MultiCell( 60, 5, $bgegevens );
			
		$pdf->ln();
	}
	
	static function administratieveGegevens( $pdf ) {
		$companyInfo = IsdatPdf::getBedrijfsGegevens();
		
		// Zoals de administratieve gegevens
		$pdf->Cell( 100, 5 );
		$pdf->Cell( 21, 5, "BTW:" );
		$pdf->Cell( 39, 5, $companyInfo[ "btwnummer" ] );
		$pdf->ln();
			
		$pdf->Cell( 100, 5 );
		$pdf->Cell( 21, 5, "IBAN:" );
		$pdf->Cell( 39, 5, $companyInfo[ "iban" ] );
		$pdf->ln();
			
		$pdf->Cell( 100, 5 );
		$pdf->Cell( 21, 5, "BIC:" );
		$pdf->Cell( 39, 5, $companyInfo[ "bic" ] );
		$pdf->ln();
			
		$pdf->Cell( 100, 5 );
		$pdf->Cell( 21, 5, $companyInfo[ "bank" ] );
		$pdf->Cell( 39, 5, $companyInfo[ "bankrekening" ] );
		$pdf->ln();
			
		$pdf->Cell( 104, 5 );
		$pdf->Cell( 17, 5, "t.n.v." );
		$pdf->Cell( 39, 5, $companyInfo[ "tnv" ] );
		$pdf->ln();
	
		$pdf->Cell( 104, 5 );
		$pdf->Cell( 17, 5, "te" );
		$pdf->Cell( 39, 5, $companyInfo[ "plaats" ] );
		$pdf->ln();
	
		$pdf->Cell( 100, 5 );
		$pdf->Cell( 21, 5, "KvK:" );
		$pdf->Cell( 39, 5, $companyInfo[ "kvkplaats" ] . " " . $companyInfo[ "kvknummer" ] );
		$pdf->ln();
	
	}
	

	/**
	 * Shows a balance
	 * @param IsdatPDF $pdf
	 * @param Balance $balance
	 */
	static function balance( $pdf, $balance ) {
		// Zonder bedragen ook geen balans
		if( !$balance->hasData() ) {
			return false;
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
	
	/**
	 * Shows a comparison of balances at different dates
	 * @param IsdatPDF $pdf
	 * @param Carbon $date
	 * @param Balance ...$balances
	 * @throws Exception
	 */
	static function balanceComparison($pdf, Carbon $date, Balance... $balances) {
		// Only work with balances with data
		$balances = array_filter($balances, function($balance) { return $balance->hasData(); });
		$balanceCount = count($balances);
		
		// Only show something if there is actual data
		if($balanceCount == 0)
			return;
		
		// Allow at most 3 balances
		if($balanceCount > 3) {
			throw new Exception( "At most 3 balances can be compared");
		}
		
		// Compute some measurements: each balance needs 30px width
		$totalWidth = 160;
		$balanceWidth = 30;
		$postNumberWidth = 10;
		$remainingWidth = $totalWidth - $balanceCount * $balanceWidth;
		$postNameWidth = $remainingWidth - $postNumberWidth;
		
		// Start with a title
		$pdf->h2( "Balans " . $date->format( "d-m-Y" ) );
		
		// Zet de gegevens in de PDF
		$pdf->setFontSize( 9 );
		$pdf->setNormalLine();
		
		// Show both sides
		$titles = array( "debet" => "activa", "credit" => "passiva" );
		foreach($titles as $side => $title ) {
			// Voeg wat ruimte in
			$pdf->Cell( 10, 1, "", 0, 1 );
		
			// Zet de titel neer
			$pdf->setNormalLine();
			$pdf->setFont( "Gill", "B" );
			$pdf->Cell( $remainingWidth, 6, ucfirst( $title ), "B", 0 );
		
			$pdf->setFont( "Gill" );
		
			foreach($balances as $balance) {
				$pdf->Cell( $balanceWidth, 6, $balance->getDate()->format( 'd-m-Y' ), "B", 0, "R" );
			}
			$pdf->ln();
		
			// Determine a list of posts to show for this side
			$posts = [];
			foreach($balances as $balance) {
				foreach($balance->getBalance($side) as $id => $postTotal ) {
					$posts[$id] = $postTotal->getPost();
				}
			}
			
			// Order the posts by number
			$posts = array_values($posts);
			usort($posts, function($a, $b) { 
				if( $a->nummer == $b->nummer )
					return 0;
				
				return ($a->nummer < $b->nummer) ? -1 : 1;
			});
		
			foreach( $posts as $post ) {
				// Zet het nummer grijs op het scherm
				$pdf->setTextColor( 128 );
				$pdf->Cell( $postNumberWidth, 5, $post->nummer );
		
				$pdf->setTextColor( 0 );
				$pdf->Cell( $postNameWidth, 5, $post->omschrijving );
		
				foreach( $balances as $balance ) {
					$balanceSide = $balance->getBalance($side);
					if(array_key_exists( $post->id, $balanceSide)) {
						$txt = number_format( $balanceSide[$post->id]->getAmount(), 2, ",", "." );
					} else {
						$txt = '-';
					}
					
					$pdf->Cell($balanceWidth, 5, $txt, 0, 0, 'R');
				}
				$pdf->ln();
			}
		
			// Zet ook het balanstotaal neer
			$pdf->setNormalLine();
			$pdf->Cell( $remainingWidth, 5, "Totaal " . $title, "T", 0 );
		
			foreach($balances as $balance) {
				$pdf->Cell( $balanceWidth, 5, number_format( abs( $balance->getTotals($side) ), 2, ",", "." ), "T", 0, "R" );
				
			}
			$pdf->ln();
		
			// Voeg wat ruimte in
			$pdf->Cell( 10, 4, "", 0, 1 );
		
		}
		
		return true;
		
	}
	
	/**
	 * Shows a list of statements in PDF
	 * @param IsdatPDF $pdf
	 * @param Collection $statements
	 */
	static function statements($pdf, $statements) {
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
	
	/**
	 * Shows a list of ledgers
	 * @param IsdatPDF $pdf
	 * @param Ledgers $ledgers
	 */
	static function ledgers($pdf, $ledgers) {
		$stdLH = 4;
		$deelLH = 3;
	
		$pdf->h2( "Grootboeken" );
	
		$pdf->setFontSize( 7 );
	
		foreach( $ledgers->getLedgers() as $ledger ) {
			// Als alleen de titel (of niks meer) op de pagina past, ga dan
			// eerst naar de volgende pagina
			if( $pdf->getY() + $stdLH + $stdLH > $pdf->getPageBreakTrigger() ) {
				$pdf->addPage();
			}
	
			// Toon de grootboektitel
			self::ledgerTitle( $pdf, $ledger->getPost(), $stdLH );
	
			// Zet ook de boekingdelen weer neer
			$totaal = 0;
	
			// Zet eerst het startbedrag neer (Van balans)
			if( $ledger->getInitial() != 0 ) {
				self::ledgerLine($pdf, $ledger->getPost(), null, "Van balans", $ledger->getInitial(), $stdLH, $deelLH, $color = 80 );
			}
	
			// Zet dan ook de andere boekingdelen neer
			foreach($ledger->getStatementLines() as $statementLine) {
				self::ledgerLine($pdf, $ledger->getPost(), $statementLine->Statement->datum, $statementLine->Statement->omschrijving, $statementLine->getSignedAmount(), $stdLH, $deelLH );
			}
	
			if( $pdf->getY() + $deelLH > $pdf->getPageBreakTrigger() ) {
				$pdf->addPage();
				self::ledgerTitle( $pdf, $post, $stdLH, true );
			}
	
			// Zet de totaalregel neer
			$pdf->setFont( "Gill", "B" );
			self::ledgerLine($pdf, $ledger->getPost(), null, "Totaal", $ledger->getTotal(), $stdLH, $deelLH );
			$pdf->setFont( "Gill", "" );
	
			// Maak een beetje ruimte
			$pdf->Cell(20, 3, '', 0, 1 );
		}
	}
	
	/**
	 * Shows the profit and loss statmeent
	 * @param IsdatPDF $pdf
	 * @param ProfitAndLossStatement $statement
	 */
	static function profitAndLoss($pdf, $statement) {
		$stdLH = 4;
		$deelLH = 3;
	
		$pdf->h2( "Winst en verliesrekening " . Timespan::create($statement->getStart(), $statement->getEnd()) );
	
		$pdf->setFontSize( 9 );
	
		// Zet nu de typen posten die bijdragen aan het resultaat in de PDF
		self::profitAndLossType($pdf, $statement->getResults(), $statement->getResultTotal(), "Bedrijfsresultaat");
	
		if(abs($statement->getOtherTotal()) > 0.005) {
			// Maak wat ruimte
			$pdf->Cell( 160,8, "", 0, 1 );
	
			self::profitAndLossType($pdf, $statement->getOther(), $statement->getResultTotal() + $statement->getOtherTotal(), "Wijziging eigen vermogen");
		}
	
		if(abs($statement->getLimitedTotal()) > 0.005) {
			// Maak wat ruimte
			$pdf->Cell( 160,16, "", 0, 1 );
	
			$pdf->h2( "Beperkt aftrekbare kosten" );
			$pdf->setFontSize( 9 );
			self::profitAndLossType($pdf, $statement->getLimited(), $statement->getLimitedTotal(), "Totaal beperkt aftrekbare kosten");
		}
	}
	
	/**
	 * Shows a comparison of multiple profit and loss statements
	 * @param IsdatPDF $pdf
	 * @param ProfitAndLossStatement ...$statements
	 */
	static function profitAndLossComparison($pdf, Timespan $period, ProfitAndLossStatement... $statements) {
		$pdf->h2( "Winst en verliesrekening " . $period );
		
		// Compute widths
		$statementCount = count($statements);
		$numberWidth = 20;
		$statementWidth = 2 * $numberWidth;
		$remainingWidth = 160 - $statementCount * $statementWidth;
		
		$pdf->Cell( $remainingWidth, 5, "" );
		foreach($statements as $statement) {
			$pdf->Cell( $statementWidth, 5, $statement->getPeriod(), 0, 0, "C" );
		}
		$pdf->ln();
		$pdf->setFont( "Gill", "" );		
		
		$pdf->setFontSize( 9 );
		
		// Zet nu de typen posten die bijdragen aan het resultaat in de PDF
		self::multipleProfitAndLossTypes($pdf, ProfitAndLossStatement::TYPE_RESULTS, "Bedrijfsresultaat", $statements );
	}
	
	/**
	 * Shows a list of amounts due
	 * @param IsdatPDF $pdf
	 * @param Carbon $date
	 * @param Collection $saldos
	 */
	static function amountsDue($pdf, $date, $saldos) {
		$stdLH = 4;
		$deelLH = 3;
	
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
		self::saldoTitle( $pdf, $saldo, $stdLH );
			
		$totaal = 0;
			
		foreach( $saldo->StatementLines as $statementLine) {
			$totaal += $statementLine->getSignedAmount();
				
			if( $pdf->getY() + $stdLH > $pdf->getPageBreakTrigger() ) {
				$pdf->addPage();
				self::saldoTitle( $pdf, $saldo, $stdLH, true );
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
			self::saldoTitle( $pdf, $saldo, $stdLH, true );
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
	
	protected static function ledgerLine($pdf, $post, $date, $title, $amount, $stdLH, $deelLH, $color = 0) {
		if( $pdf->getY() + $deelLH > $pdf->getPageBreakTrigger() ) {
			$pdf->addPage();
			self::ledgerTitle( $pdf,  $post, $stdLH, true );
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

	protected static function ledgerTitle( $pdf, $post, $stdLH, $continued = false ) {
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
	
	protected static function saldoTitle( $pdf, $saldo, $stdLH, $continued = false ) {
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
	
	public static function profitAndLossType($pdf, $data, $total, $totalDescription) {
		foreach( $data as $category => $posts):
			self::profitAndLossPart($pdf, $category, true, [$posts]);
		endforeach;
	
		// Zet het resultaat in de PDF
		$pdf->setFont( "Gill", "B" );
		$pdf->setMediumLine();
	
		// Maak wat ruimte
		$pdf->Cell( 160,3, "", 0, 1 );
	
		$pdf->Cell( 120, 5, $totalDescription, "T" );
		$pdf->Cell( 20, 5, number_format( -$total, 2, '.', ',' ), "T", 0, "R" );
		$pdf->Cell( 20,5, "", "T" );
	
		$pdf->setFont( "Gill", "" );
	}
	
	/**
	 * Shows multiple profit and loss statements
	 * @param unknown $pdf
	 * @param unknown $type
	 * @param unknown $totalDescription
	 * @param unknown $statements
	 */
	protected static function multipleProfitAndLossTypes($pdf, $type, $totalDescription, $statements) {
		// Combine categories of multiple statements. A category would be 'baten' or 'lasten'
		$categories = [];
		
		foreach($statements as $statement) {
			foreach($statement->getData($type) as $category => $postTotals) {
				if(!array_key_exists($category, $categories))
					$categories[$category] = [];
				
				$categories[$category][] = $postTotals;
			}
		}
		
		foreach($categories as $category => $postTotals) {
			self::profitAndLossPart($pdf, $category, false, $postTotals);
		}
		
		// Zet het resultaat in de PDF
		$pdf->setFont( "Gill", "B" );
		$pdf->setMediumLine();
		
		// Maak wat ruimte
		$pdf->Cell( 160,3, "", 0, 1 );

		// Compute widths
		$statementCount = count($statements);
		$numberWidth = 20;
		$statementWidth = 2 * $numberWidth;
		$remainingWidth = 160 - $statementCount * $statementWidth;
		
		$pdf->Cell( $remainingWidth, 5, $totalDescription, "T" );
		foreach($statements as $statement) {
			$pdf->Cell( 20, 5, number_format( -$statement->getResultTotal(), 2, '.', ',' ), "T", 0, "R" );
			$pdf->Cell( 20,5, "", "T" );
		}		
		$pdf->setFont( "Gill", "" );
		
	}
	
	protected static function profitAndLossPart($pdf, $posts, $showPercentage, $categoryData ) {
		// Zorg voor een klein beetje ruimte tussen de verschillende categorieen
		$pdf->Cell( 160,3 , "", 0, 1 );
	
		// Zet de omschrijving en het totaal neer
		$pdf->setFont( "Gill", "B" );
		$pdf->setNormalLine();
	
		// Compute widths
		$statementCount = count($categoryData);
		$numberWidth = 20;
		$statementWidth = 2 * $numberWidth;
		$remainingWidth = 160 - $statementCount * $statementWidth;
		
		// Write header
		$pdf->Cell( $remainingWidth, 5, $categoryData[0]->getLabel(), "B" );
		
		foreach( $categoryData as $data ) {
			$pdf->Cell( 20, 5, number_format( -$data->getTotal(), 2, '.', ',' ), "B", 0, "R" );
			$pdf->Cell( 20, 5, "", "B" );
		}
		
		$pdf->ln();
	
		$pdf->setFont( "Gill", "" );
		$pdf->setThinLine();
	
		// Compute a set of posts to show
		$posts = [];
		$mappedData = [];
		foreach( $categoryData as $data ) {
			// Create a list of posts
			$currentMap = [];
			foreach($data as $postTotal) {
				$posts[$postTotal->getPost()->id] = $postTotal->getPost();
				$currentMap[$postTotal->getPost()->id] = $postTotal;
			}
			
			$mappedData[] = $currentMap;
		}
		
		// Order the list of posts by number
		usort($posts, function($a, $b) {
			if( $a->nummer == $b->nummer )
				return 0;
		
				return ($a->nummer < $b->nummer) ? -1 : 1;
		});
		
		$eerste = true;
		foreach($posts as $post):
			// Dit zorgt ervoor dat de eerste geen border krijgt, en de rest wel
			$border = "B"; // ( $eerste ? "" : "T" );
		
			$pdf->Cell( 10, 5, "", $border );
		
			if( $showPercentage ) {
				$pdf->Cell( $remainingWidth - 20, 5, $post->omschrijving, $border );

				if( $post->percentage_aftrekbaar > 0 && $post->percentage_aftrekbaar < 100 ):
					$pdf->setTextColor( 128 );
					$pdf->Cell( 10, 5, $post->percentage_aftrekbaar . " %", $border, "R"  );
					$pdf->setTextColor( 0 );
				else:
					$pdf->Cell( 10, 5, "", $border  );
				endif;
			} else {
				$pdf->Cell( $remainingWidth - 10, 5, $post->omschrijving, $border );
			}
				
			foreach($mappedData as $currentMap) {
				if(array_key_exists($post->id, $currentMap)) {
					$txt = number_format( -$currentMap[$post->id]->getSignedAmount(), 2, '.', '' );
				} else {
					$txt = '-';
				}
				$pdf->Cell( $numberWidth, 5, "", $border );
				$pdf->Cell( $numberWidth, 5, $txt, $border, 0, "R" );
			}
			$pdf->ln();
		
			$eerste = false;
		endforeach;
	}
	
	
}