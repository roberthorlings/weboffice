<?php
namespace Weboffice\Support;

/**
 * Kan PDFs genereren met een * isdat tintje eraan
 *
 * De maten:
 * 		Marge links en rechts: 25mm
 * 		Marge tot de lijn boven en beneden: 18mm
 * 		Marge van de lijn tot de bovenkant van de info: 8mm
 * 
 * @author 		Robert Horlings
 * @version		1.0.0
 * @package 	Isdat
 * @subpackage	Isdat.Framework
 * @see 		FPDF
 */
class IsdatPDF extends \FPDF {
	public static function getBedrijfsGegevens() {
		// Stel de standaard gegevens in
		return [
			"naam" => "* isdat softwareontwikkeling",
			"adres" => "Wegenbouw 108",
			"postcode" => "3991 NK",
			"plaats" => "Houten",
			"bank" => "Rabo",
			"bankrekening" => "14.79.36.535",
			"iban" => "NL52RABO0147936535",
			"bic" => "RABONL2U",
			"tnv" => "Isdat Softwareontwikkeling",
			"btwnummer" => "NL105334388B01",
			"kvknummer" => "30284845",
			"kvkplaats" => "Utrecht"
		];
	}
	
	var $stdFontSize = 11;
	var $stdFont = "Gill";
	var $title = "";
	var $internal = false;
	var $encoding = 'ISO-8859-1';
	
	function __construct($orientation='P',$unit='mm',$format='A4') {
		parent::__construct( $orientation, $unit, $format );

		// Voeg het font Gill Sans toe
		$this->AddFont('Gill','','gill.php');
		$this->AddFont('Gill','B','gillbold.php');
		$this->AddFont('Gill','I','gillitalic.php');
		$this->AddFont('Gill','BI','gillbolditalic.php');
		
		// Stel het font gill in als standaard
		$this->setFont( $this->stdFont, "", $this->stdFontSize );
		
		// Stel de marges in
		$this->setMargins( 25, 26 );
		$this->_setBottomMargin();

		$this->setAuthor( "* isdat softwareontwikkeling" );
		
		// Initialize default settings
		$this->setFillColor( 200 );
		$this->setNormalLine();
		$this->SetDrawColor( 0 );
		$this->encoding = 'UTF-8';
	}

	function Cell($w,$h=0,$txt='',$border=0,$ln=0,$align='',$fill=0,$link='', $convert = true) {
		if( $convert ) {
			$txt = $this->_convertText( $txt );
		}
		parent::Cell($w,$h,$txt,$border,$ln,$align,$fill,$link);
	}
	
	function Text($x,$y,$txt, $convert = true) {
		if( $convert ) {
			$txt = $this->_convertText( $txt );
		}
		parent::Text($x,$y,$txt);
	}

	function _convertText( $text ) {
		if( $this->encoding != 'ISO-8859-1' ) {
			return @iconv($this->encoding, "ISO-8859-1//IGNORE", $text);
		}
		return $text;
	}
	
	function header() {
		// Zet een nette lijn neer
		$this->setDrawColor(255, 0, 0);
		$this->setLineWidth( 0.2 );
		$this->line( 0, 18, 210, 18 );

		// Als er een titel is ingevuld, geef die dan ook weer
		if( $this->title != "" && $this->PageNo() > 1 ) {
			$this->setTextColor( 0 );
			$this->setX( 25 );
			$this->setY ( 10 );
		    $this->Cell( 0,8, $this->title );
		}

		// Zet het logo van isdat neer
		$this->setY( 9 );
		$this->setFont( $this->stdFont, "B", 12 );
		
		// Bepaal de breedte van de strings
		$isdatw = $this->getStringWidth( "* isdat" );
		$ontwerpw = $this->getStringWidth( " softwareontwikkeling" );
		
		// Zet de woorden op de juiste positie
		$this->setX( -25 - $isdatw - $ontwerpw );
		$this->setTextColor( 0 );
		$this->Cell( $isdatw, 8, "* isdat", 0, 0, 'R' );

		$this->setTextColor( 153 );
		$this->Cell( $ontwerpw, 8, " softwareontwikkeling", 0, 1, 'R' );
		
		// Voeg het adres toe op pagina 1
		if($this->PageNo()==1) {
			$this->Cell( 160, 3, "", 0, 1 );
			$this->setFont( $this->stdFont, "", 8 );
			$this->setTextColor( 0 );
			
			$bedrijfsgegevens = IsdatPdf::getBedrijfsGegevens();
			$this->Cell( 160, 4, $bedrijfsgegevens[ "adres" ], 0, 1, 'R' );
			$this->Cell( 160, 4, $bedrijfsgegevens[ "postcode" ] . " " . $bedrijfsgegevens[ "plaats" ], 0, 1, 'R' );
		}
				
		// Stel de X en Y goed in
		$this->setFont ($this->stdFont, "", $this->stdFontSize );
		$this->setTextColor( 0 );
		$this->setXY( 25, 26 );
	}
	
	function footer() {
		if( $this->internal ) {
			$line_top = 280;
			$text_top = -16;
		} else {
			$line_top = 273;
			$text_top = -21;
		}

		// Zet een nette lijn neer
		$this->setDrawColor(255, 0, 0);
		$this->setLineWidth( 0.2 );
		$this->line( 0, $line_top, 210, $line_top );

		// Zet de tekst onderin neer
		$bedrijfsgegevens = IsdatPdf::getBedrijfsGegevens();
		$info = 
			"KvK " . $bedrijfsgegevens[ "kvkplaats" ] . " " . $bedrijfsgegevens[ "kvknummer" ] . " - " .
			"BTW nr. " . $bedrijfsgegevens[ "btwnummer" ] . " - " . 
			"IBAN " . $bedrijfsgegevens[ "iban" ];
		
		$this->setXY( 25, $text_top );
		$this->setFontSize(8);
		$this->Cell( 160, 3, $info, 0, 1, 'C');
		$this->setXY( 25, -16 );

		if( !$this->internal ) {
			$this->setTextColor(128, 128, 128);
			$this->MultiCell( 0, 3, "Op al onze transacties en aanbiedingen zijn onze algemene voorwaarden van toepassing.  Daarvan is aan u een\nexemplaar ter beschikking gesteld. Een extra exemplaar wordt u op aanvraag kosteloos toegestuurd.", 0, 'C');
			$this->setTextColor(0, 0, 0);
		}

		// Zet het pagina nummer neer
		$this->setFontSize($this->stdFontSize);
		$this->setTextColor(0);
		$this->setXY( - 25 - 15, $text_top );
	    $this->Cell(15, 4, $this->PageNo() ,0, 0 , 'R');
	}
	
	function addTo( $klant, $postadres = true ) {
		$this->setFontSize( $this->stdFontSize );
		$this->cell( 80, 5, $klant[ "bedrijfsnaam" ], 0, 1 );
		if( $klant[ "contactpersoon" ] != "" ) {
			$this->cell( 80, 5, "t.a.v " . $klant[ "contactpersoon" ], 0, 1 );
		}
		
		if( $klant[ "postadres" ] != "" && $postadres ) {
			$adres = $klant[ "postadres" ];
			$pcplaats = $klant[ "postpostcode" ] . " " . $klant[ "postplaats" ];
		} else {
			$adres = $klant[ "adres" ];
			$pcplaats = $klant[ "postcode" ] . " " . $klant[ "plaats" ];
		}
		$this->cell( 80, 5, $adres, 0, 1 );
		$this->cell( 80, 5, $pcplaats, 0, 1 );

		$this->setY( 56 );
	}
	
	function addFactuurAdres( $klant ) {
		if( $klant[ "factuuradres" ] != "" ) {
			$this->setFontSize( $this->stdFontSize );
			foreach( preg_split( '/[\n\r]+/', $klant[ "factuuradres" ] ) as $regel ) {
				$this->cell( 80, 5, $regel, 0, 1 );
			}

			$this->setY( 56 );
		} else {
			$this->addTo( $klant, true );
		}
	}		
	
	function h1( $text ) {
		$this->setFont( $this->stdFont, "B", $this->stdFontSize + 4 );
		$this->write( 8, $text );
		$this->ln();
		$this->_resetFont();
	}

	function h2( $text ) {
		$this->setFont( $this->stdFont, "", $this->stdFontSize + 3 );
		$this->write( 8, $text );
		$this->ln();
		$this->_resetFont();
	}
	
	function h3( $text ) {
		$this->setFont( $this->stdFont, "B", $this->stdFontSize + 1 );
		$this->write( 6, $text );
		$this->ln();
		$this->_resetFont();
	}		
	
	function setFontSmaller() {
		$this->setFont( $this->stdFont, "", $this->stdFontSize -2 );
	}
			
	function _resetFont() {
		$this->setFont( $this->stdFont, "", $this->stdFontSize );
	}

	function setThickLine() {
		$this->setDrawColor( 255,0,0 );
		$this->setLineWidth( 0.4 );
	}
	function setMediumLine() {
		$this->setDrawColor( 255,0,0 );
		$this->setLineWidth( 0.3 );
	}
	function setNormalLine() {
		$this->setDrawColor( 255,0,0 );
		$this->setLineWidth( 0.2 );
	}
	function setThinLine() {
		$this->setDrawColor( 255,200,200 );
		$this->setLineWidth( 0.1 );
	}
	
	function _resetLine() {
		$this->setDrawColor( 0 );
		$this->setLineWidth( 0.2 );
	}

	function setInternal( $boolean = true ) {
		$this->internal = $boolean;
		$this->_setBottomMargin();
	}

	function getInternal() {
		return $this->internal;
	}

	function _setBottomMargin() {
		if( $this->internal ) {
			$this->SetAutoPageBreak(true, 20 );
		} else {
			$this->SetAutoPageBreak(true, 27 );
		}
	}
	
	public function getLeftMargin() {
		return $this->lMargin;
	}
	
}