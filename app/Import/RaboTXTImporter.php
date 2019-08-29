<?php
namespace Weboffice\Import;

use Weboffice\Models\Account;
use Weboffice\Models\Transaction;

use Carbon\Carbon;

class RaboTXTImporter extends Importer {
	function __construct( \SplFileInfo $file ) {
		parent::__construct( $file );
		$this->bank = Account::RABO;
	}
	
	/**
	 * Checks whether the file with the given file handle is supported
	 * @param unknown $fh
	 */
	public static function supports(\SplFileObject $file) {
		// Try Rabobank files: comma separated files with 13 fields a line.
		$line = $file->fgetcsv(",");
		if( count( $line ) >= 13 && ( is_numeric( $line[ 0 ] ) || strlen( $line[ 0 ] ) == 18 ) && is_numeric( $line[ 2 ] ) && ( $line[ 3 ] == "D" || $line[ 3 ] == "C" ) ) {
			if( Importer::accountType($line[ 0 ]) == Account::RABO ) {
				return true;
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
		
		// Read the parsed lines into memory
		while($line = $file->fgetcsv(",")) {
			if( count( $line ) < 13 ) {
				continue;
			}
			
			$accountNumber = Account::removeLeadingZeros( $line[ 0 ] );
			$account = $this->getAccount($accountNumber);
			
			if(!$account) {
				continue;
			}
			
			/*
				# format Rabobank .csv: 
				# from account, valuta, date (ISO), Debet or Credit, amount, to account, description, interest date, type of payment (transfer, authorization, ...), ..., additional info, additional info, additional info, additional info,additional info,additional info
				
				"0149019637","EUR",20090226,"D",47.55,"0200013777","GELDAUTOM.BUITENLAND",20090227,"gb","","*CITY PLACE ARCADE       >BOSTON","Geldautomaat 13:38 pasnr. 001","USD 60,00 EUR = 1,26183 USD","","",""
				The output format should be:
				  Array(
						"datum"			=> ,
						"tegenrekening"	=> ,
						"omschrijving"	=> ,
						"bedrag"		=>
					)
			*/

			// The date should be converted from yyyymmdd format to dd-mm-yyyy. This is done
			// using the default PHP functions
			if( is_numeric( $line[ 2 ] ) ) {
				$date = Carbon::createFromFormat( 'Ymd', $line[ 2 ] );
			} else {
				$date = Carbon::createFromFormat( 'Ymd', $line[ 7 ] );
			}
			
			// The amount uses a dot as decimal separator, but it has to multiplied by 100
			$bedrag = floatval( $line[ 4 ] );
			
			// Als het bedrag debet is, dan is het negatief in ons systeem
			$bedrag = ( ( $line[ 3 ] == 'D' ) ? -1 : 1 ) * $bedrag;
			
			// De omschrijving staat in de posities 10 t/m 13
			$omschrijving = $line[ 10 ] . " " . $line[ 11 ] . " " . $line[ 12 ] . " " . $line[ 13 ];
			
			// The description should be sanatized from several ascii characters
			// Also, double spaces should be converted to one
			$omschrijving = preg_replace('/[^(\x20-\x7F)]*/','', $omschrijving );
			$omschrijving = preg_replace('/ +/', ' ', $omschrijving );
			
			// Zoek de tegenrekening op
			$tegenrekening = Account::removeLeadingZeros( $line[ 5 ] );
			
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
