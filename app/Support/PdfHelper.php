<?php
namespace Weboffice\Support;

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
	
	function werktijd( $pdf, $relatie, $werktijden, $totale_duur ) {
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
			
		foreach( $werktijden as $werktijd ) {
			// Zet eerst een lijn neer
			$pdf->Cell( 160, 1,"", "T", 2 );
	
			if( $pdf->getY() + 5 + 5 + ( is_null( $relatie ) ? 4 : 0 ) + 5 > $pdf->PageBreakTrigger ) {
				$pdf->addPage();
			}
	
			$y_regel = $pdf->getY();
	
	
			$pdf->Cell( 25, 5, $werktijd['Werktijd']['datum']->format( 'd-m' ) );
	
			// Zet eerst de omschrijving neer
			$pdf->MultiCell( 105, 5, $werktijd['Werktijd']['opmerkingen'] );
	
			// Zet de klantnaam iets kleiner en schuin neer
			if( is_null( $relatie ) ) {
				$pdf->Cell( 25, 4 );
				$pdf->setFontSize( $pdf->stdFontSize - 1 );
				$pdf->setFont( "Gill", "I" );
					
				$pdf->MultiCell( 105, 4, $werktijd[ "Relatie" ][ "bedrijfsnaam" ] );
					
				$pdf->setFont( "Gill" );
				$pdf->setFontSize( $pdf->stdFontSize );
			}
	
			$y_volgende_regel = $pdf->getY();
	
			// Zet ook de getallen neer
			$pdf->setXY( 130 + $pdf->getLeftMargin(), $y_regel );
			$pdf->Cell( 30, 5, $werktijd['Werktijd'][ 'tijdsduur' ]->format( '%H:%I' ), 0, 0, "R" );
	
			$pdf->setY( $y_volgende_regel + 2 );
		}
	
		// Zet eerst een lijn neer
		$pdf->Cell( 160, 6,"", "T", 2 );
	
		$pdf->SetDrawColor( 0 );
			
		// Toon het totaal
		$pdf->Cell( 25, 5, "", "T" );
		$pdf->Cell( 105, 5, "Totaal:", "T" );
		$pdf->Cell( 30, 5, sprintf( '%d:%02d', floor( $totale_duur / 60 ), $totale_duur % 60 ), "T", 1, "R" );
	
		// Zorg voor een klein beetje ruimte
		$pdf->setFont( "Gill" );
		$pdf->ln();
	
		return $pdf;
	}
	
	function werktijdVerkort( $pdf, $relatie, $werktijden, $totale_duur ) {
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
			
		$aantalWerktijden = count( $werktijden );
		$helftWerktijden = ceil( $aantalWerktijden / 2 );
			
		for( $i = 0; $i < $helftWerktijden; $i++ ) {
			// Zet eerst een lijn neer
			$pdf->Cell( 160, 1,"", "T", 2 );
	
			// Ga naar de volgende pagina als deze regel er niet meer op past
			if( $pdf->getY() + 5 + 5 + 5 > $pdf->PageBreakTrigger ) {
				$pdf->addPage();
			}
	
			$y_regel = $pdf->getY();
	
			// Zet de eerste werktijd neer
			$werktijd = $werktijden[ $i ];
	
			$pdf->Cell( 35, 5, $werktijd['Werktijd']['datum']->format( 'd-m' ) );
			$pdf->Cell( 35, 5, $werktijd['Werktijd'][ 'tijdsduur' ]->format( '%H:%I' ), 0, 0, "R" );
	
			// Zet ook de tweede werktijd neer
			if( $i + $helftWerktijden < $aantalWerktijden ) {
				$werktijd = $werktijden[ $i + $helftWerktijden ];
				$pdf->Cell( 20, 5, '' );
				$pdf->Cell( 35, 5, $werktijd['Werktijd']['datum']->format( 'd-m' ) );
				$pdf->Cell( 35, 5, $werktijd['Werktijd'][ 'tijdsduur' ]->format( '%H:%I' ), 0, 0, "R" );
			}
	
			$pdf->ln();
		}
	
		// Zet eerst een lijn neer
		$pdf->Cell( 160, 6,"", "T", 2 );
	
		$pdf->SetDrawColor( 0 );
			
		// Toon het totaal
		$pdf->Cell( 125, 5, "Totaal:", "T" );
		$pdf->Cell( 35, 5, sprintf( '%d:%02d', floor( $totale_duur / 60 ), $totale_duur % 60 ), "T", 1, "R" );
	
		// Zorg voor een klein beetje ruimte
		$pdf->setFont( "Gill" );
		$pdf->ln();
	
		return $pdf;
	}
	
	
	function kilometerregistratie( $pdf, $relatie, $ritten, $totale_kilometers ) {
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
			
		foreach( $ritten as $rit) {
			// Zet eerst een lijn neer
			$pdf->Cell( 160, 1,"", "T", 2 );
	
			if( $pdf->getY() + 5 + 5 > $pdf->PageBreakTrigger ) {
				$pdf->addPage();
			}
	
			$y_regel = $pdf->getY();
	
			$pdf->Cell( 25, 5, date( 'd-m-Y', strtotime( $rit['Werktijd']['datum'] ) ) );
	
			// Zet eerst de omschrijving neer
			$pdf->MultiCell( 105, 5, $rit['Kilometer']['van_naar'] );
	
			// Zet het bezoekadres iets kleiner en schuin neer
			$pdf->Cell( 25, 4 );
			$pdf->setFontSize( $pdf->stdFontSize - 2 );
			$pdf->setFont( "Gill", "I" );
	
			$pdf->MultiCell( 105, 4, $rit[ "Kilometer" ][ "bezoekadres" ] );
	
			$pdf->setFont( "Gill" );
			$pdf->setFontSize( $pdf->stdFontSize );
	
			// Ga terug naar de juiste hoogte om het aantal kilometer neer te zeten
			$y_volgende_regel = $pdf->getY();
	
			// Zet ook de getallen neer
			$pdf->setXY( 130 + $pdf->getLeftMargin(), $y_regel );
			$pdf->Cell( 30, 5, number_format( $rit['totaal' ], 0, '', '.' ) . " km", 0, 1, "R" );
	
			// Zet ook de kilometerstanden neer, in het grijs en klein
			$pdf->Cell( 130, 4, '' );
			$pdf->setFontSize( $pdf->stdFontSize - 3 );
			$pdf->setFont( "Gill", "I" );
			$pdf->setTextColor( 128 );
	
			if( $rit[ 'Kilometer' ][ 'wijze' ] == 'auto' ):
			$pdf->Cell( 30, 4, number_format( $rit[ "Kilometer" ][ "km_begin" ], 0, '', '.' ) . ' - ' . number_format( $rit[ 'Kilometer' ][ 'km_eind' ], 0, '', '.' ) , 0, 0, "R" );
			else:
			$pdf->Cell( 30, 4, $rit[ 'Kilometer' ][ 'wijze' ], 0, 0, "R" );
			endif;
	
			$pdf->setTextColor( 0 );
			$pdf->setFont( "Gill" );
			$pdf->setFontSize( $pdf->stdFontSize );
	
			$pdf->setY( $y_volgende_regel + 2 );
		}
	
		// Zet eerst een lijn neer
		$pdf->Cell( 160, 6,"", "T", 2 );
	
		$pdf->SetDrawColor( 0 );
			
		// Toon het totaal
		$pdf->Cell( 25, 5, "", "T" );
		$pdf->Cell( 105, 5, "Totaal:", "T" );
		$pdf->Cell( 30, 5, $totale_kilometers . " km", "T", 1, "R" );
	
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
	
	
}