<?php
namespace Weboffice\Import;

use Weboffice\Models\Account;
use Carbon\Carbon;
use Weboffice\Models\Transaction;

class RaboMutAscImporter extends Importer {
	// In the MUT.ASC format, different lines can be found
	public static $RaboMutAscFormat1 = "A10Rekeningnummer/A3Muntsoort/A5Hoofdgroep/A5GroepBoekingen/A1Recordcode/A3Batchnummer/A5UniverseleTransactiecode/A1CodeHerstelboeking/A5BankenTransactiecode/A10Tegenrekening/A24Tegenrekeninghouder/A1StatusTegenrekening/A13Bedrag/A1CodeDebetCredit/A6Boekingsdatum/A6Valutadatum/A4Rekeningafschrift/A5Postnummer/A16Informatie/A2Mediumcode/A2Reserve";
	public static $RaboMutAscFormat2 = "A10Rekeningnummer/A3Muntsoort/A5Hoofdgroep/A5GroepBoekingen/A1Recordcode/A29NavraagGegevens/A3Reserve/A64Omschrijving/A1Volgrecords/A7Reserve";
	public static $RaboMutAscFormat3 = "A10Rekeningnummer/A3Muntsoort/A5Hoofdgroep/A5GroepBoekingen/A1Recordcode/A96Omschrijving/A8Reserve";
	
	function __construct( \SplFileInfo $file ) {
		parent::__construct( $file );
		$this->bank = Account::RABO;
	}
	
	/**
	 * Checks whether the file with the given file handle is supported
	 * @param unknown $fh
	 */
	public static function supports(\SplFileObject $file) {
		// Try the MUT.ASC file
		$line = $file->fgets();
	
		if( $unpacked = @unpack( self::$RaboMutAscFormat1, $line ) ) {
			// Check several fields
			if( is_numeric( $unpacked[ "Rekeningnummer" ] ) && $unpacked[ "Hoofdgroep" ] == "99999" && $unpacked[ "GroepBoekingen" ] == "99999" && $unpacked[ "Recordcode" ] == 2 ) {
				if( Importer::accountType( intval( $unpacked[ "Rekeningnummer" ] ) ) == Rekening::RABO ) {
					return true;
				}
			}
		}
	
		return false;
	}
	
	
	/** 
	 * Parses the uploaded file.
	 *
	 * @return	array	Array with every transaction as an element. Every element should 
	 *					be an array that can be inserted into the Transacties table.
	 */
	function parse() {
		$transactions = array();
		
		// Open the file for reading
		$file = $this->file->openFile();
		
		// Loop through all lines
		// Every transaction has at least two lines: type1 and type2. Depending on the
		// number given in the type2-line, more lines could be present
		while ($line = $file->fgets(1024)) {
			$fields = array();
			
			// Unpack all fields
			$fields[0] = @unpack( self::$RaboMutAscFormat1, $line);
			
			if( !$fields[0] ) {
				continue;
			}

			if ($line = $file->fgets(1024)) {
				// Unpack fields on the next line
				$fields[1] = unpack( self::$RaboMutAscFormat2, $line);
				
				// Bepaal het aantal volgrecords
				$aantal_volgrecords = $fields[1][ "Volgrecords" ];
				
				if ( $aantal_volgrecords > 0 ) {
					$fields[2] = array();
					for( $i = 0; $i < $aantal_volgrecords; $i++ ) {
						if ( $line = $file->fgets(1024) ) {
							$fields[2][] = unpack( self::$RaboMutAscFormat3, $line );
						}
					}
				}							
			}
			
			// Zoek het nummer van deze rekening op
			$accountNumber = $this->_removeLeadingZeros( $fields[0][ "Rekeningnummer" ] );
			$account = $this->getAccount($accountNumber);
			
			if(!$account) {
				continue;
			}
				
			
			// The date should be converted from yymmdd format to dd-mm-yyyy. This is done
			// using the default PHP functions
			if( is_numeric( $fields[0][ "Boekingsdatum" ] ) ) {
				$date = Carbon::createFromFormat( 'ymd', $fields[0][ "Boekingsdatum" ] );
			} else {
				$date = Carbon::createFromFormat( 'ymd', $fields[0][ "Valutadatum" ] );
			}
			
			// The amount is already in cents
			$bedrag = intval( $fields[0][ "Bedrag" ] ) / 100;
			
			// Als het bedrag debet is, dan is het negatief in ons systeem
			$bedrag = ( ( $fields[0][ "CodeDebetCredit" ] == 'D' ) ? -1 : 1 ) * $bedrag;
			
			// De omschrijving staan op de 2e en volgende regels
			$omschrijving = $fields[ 1 ][ "Omschrijving" ];
			
			if( array_key_exists( 2, $fields ) ) {
				foreach( $fields[ 2 ] as $extra_regel ) {
					$omschrijving .= $extra_regel[ "Omschrijving" ];
				}
			}
			
			// The description should be sanatized from several ascii characters
			// Also, double spaces should be converted to one
			$omschrijving = preg_replace('/[^(\x20-\x7F)]*/','', $omschrijving );
			$omschrijving = preg_replace('/ +/', ' ', $omschrijving );
			
			// Zoek de tegenrekening op
			$tegenrekening = Account::removeLeadingZeros( $fields[0][ "Tegenrekening" ] );
			
			// Create transaction object
			$transactions[] = new Transaction([
				"rekening_id" => $account->id,
				"datum" => $date,
				"tegenrekening" => $tegenrekening,
				"omschrijving" => trim($omschrijving),
				"bedrag" => $bedrag
			]);
		}
				
		return $transactions;
	}
	
}
