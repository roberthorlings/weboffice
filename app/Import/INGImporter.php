<?php
namespace Weboffice\Import;

use Carbon\Carbon;
use Weboffice\Models\Account;
use Weboffice\Models\Transaction;


class INGImporter extends Importer {
	function __construct( \SplFileInfo $file ) {
		parent::__construct( $file );
		$this->bank = Account::ING;
	}
	
	/**
	 * Checks whether the file with the given file handle is supported
	 * @param unknown $fh
	 */
	public static function supports(\SplFileObject $file) {
		// Try Postbank files: comma separated files with 9 fields a line. The first line is default
		//	"Datum", "Naam / Omschrijving", "Rekening", "Tegenrekening", "Code", "Af Bij", "Bedrag (EUR)", "MutatieSoort", "Mededelingen"
		$testline = array( "Datum", "Naam / Omschrijving", "Rekening", "Tegenrekening", "Code", "Af Bij", "Bedrag (EUR)", "MutatieSoort", "Mededelingen" );
		$line = $file->fgetcsv();
		if( count( $line ) == 9 ) {
			foreach( $testline as $i => $item ) {
				$is_ing = ( $line[ $i ] == $item );
				if( !$is_ing ) {
					break;
				}
			}
				
			return $is_ing;
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

		// Discard the first line as it only contains column headers
		$line = $file->fgets();
		
		// Read the parsed lines into memory
		while($line = $file->fgetcsv()) {
			/*
				Every input line contains 9 fields:
					"Datum", "Naam / Omschrijving", "Rekening", "Tegenrekening", "Code", "Af Bij", "Bedrag (EUR)", "MutatieSoort", "Mededelingen"
			
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
			$date = Carbon::createFromFormat( 'Ymd', $line[ 0 ] );
			
			// The amount uses a comma as decimal separator. We have to convert it to cents
			$bedrag = floatval( str_replace( ",", ".", $line[ 6 ] ) );
			if( $line[ 5 ] == 'Af' ) { 
				$bedrag = -$bedrag;
			}
			
			// The description should be sanatized from several ascii characters
			// Also, double spaces should be converted to one
			$omschrijving = preg_replace('/[^(\x20-\x7F)]*/','', $line[ 1 ] . " " . $line[ 8 ] );
			$omschrijving = preg_replace('/ +/', ' ', $omschrijving );
			
			// Also, when the account number itself (the account for which the transactions
			// are imported) is found, it should not be taken as a tekenrekening.
			$tegenrekening = $line[ 3 ];

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
