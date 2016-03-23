<?php
namespace Weboffice\Import;

use Weboffice\Models\Account;
use Weboffice\Models\Transaction;
use Carbon\Carbon;

class ABNImporter extends Importer {
	// Public constructor
	function __construct(\SplFileInfo $file) {
		parent::__construct( $file );
		$this->bank = Account::ABN;
	}
	
	/**
	 * Checks whether the file with the given file handle is supported
	 * @param unknown $fh
	 */
	public static function supports(\SplFileObject $file) {
		// ABN Tab files are tab-separated text files with 8 fields a line. Give it a try
		$line = $file->fgetcsv("\t");
		if( count( $line ) == 8 && is_numeric( $line[ 0 ] ) && is_numeric( $line[ 2 ] ) ) {
			// Extra check to see if the account specific is actually known as an ABN account
			if( Importer::accountType( $line[ 0 ] ) == Account::ABN ) {
				return true;
			}
		}
		
		return false;
	}
	
	/** 
	 * Parses the uploaded file.
	 *
	 * @return	array	Array with transactions to add
	 */
	function parse() {
		$transactions = array();
		
		// Open the file for reading
		$file = $this->file->openFile();
		
		// Read the parsed lines into memory
		while($line = $file->fgetcsv("\t")) {
			/*
				Every input line contains 8 fields:
					add account       currency        date1   misc1   misc2   date2   amount  description line to inputfile
			
				The output format should be a Transaction object
				  Array(
						"datum"			=> ,
						"tegenrekening"	=> ,
						"omschrijving"	=> ,
						"bedrag"		=>
					)
			*/
			
			$accountNumber = $line[0];
			$account = $this->getAccount($accountNumber);
			if( !$account )
				continue;

			// The date should be converted from yyyymmdd format to dd-mm-yyyy. This is done
			// using the default PHP functions
			if( is_numeric( $line[ 5 ] ) ) {
				$date = Carbon::createFromFormat( 'Ymd', $line[ 5 ] );
			} else {
				$date = Carbon::createFromFormat( 'Ymd', $line[ 2 ] );
			}
			
			// The amount uses a comma as decimal separator, we shouldn't
			$bedrag = str_replace( ",", ".", $line[ 6 ] );
			
			// The description should be sanatized from several ascii characters
			// Also, double spaces should be converted to one
			$omschrijving = preg_replace('/[^(\x20-\x7F)]*/','', $line[ 7 ] );
			$omschrijving = preg_replace('/ +/', ' ', $omschrijving );
			
			// If a tegenrekening is present in the description, we should find it
			// It is found in the beginning of the description
			// It might be:
			//		xxx.xxx.xxx	
			//		GIRO xxxxxxx
			// 
			// Also, when the account number itself (the account for which the transactions
			// are imported) is found, it should not be taken as a tekenrekening.
			if( preg_match( '/^\s*(GIRO\s+)?([0-9.]+)/', $omschrijving, $match ) ) {
				if( $match[ 2 ] != $accountNumber ) {
					$tegenrekening = str_replace( ".", "", $match[ 2 ] );
					$omschrijving = str_replace( $match[ 0 ], "", $omschrijving );
				}
			} else {
				$tegenrekening = "";
			}
			
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
