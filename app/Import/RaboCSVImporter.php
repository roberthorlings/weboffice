<?php
namespace Weboffice\Import;

use Weboffice\Models\Account;
use Weboffice\Models\Transaction;

use Carbon\Carbon;

class RaboCSVImporter extends Importer {
    const DELIMITER = ",";

    function __construct(\SplFileInfo $file ) {
		parent::__construct( $file );
		$this->bank = Account::RABO;
	}
	
	/**
	 * Checks whether the file with the given file handle is supported
	 * @param unknown $fh
	 */
	public static function supports(\SplFileObject $file) {
		// Try Rabobank files: comma separated files with 26 fields a line.
        $expectedHeader = ["IBAN/BBAN","Munt","BIC","Volgnr","Datum","Rentedatum","Bedrag","Saldo na trn","Tegenrekening IBAN/BBAN","Naam tegenpartij","Naam uiteindelijke partij"];
		$header = $file->fgetcsv(self::DELIMITER);

		// Match the number of fields and the first few headers (the next header contains a special character, and comparison
        // may be influenced by locale settings)
		if( count( $header ) == 26 && array_slice($header, 0, count($expectedHeader)) == $expectedHeader ) {
		    $firstline = $file->fgetcsv(self::DELIMITER);

			if( Importer::accountType($firstline[ 0 ]) == Account::RABO ) {
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

		// Skip the first line
        $header = $file->fgetcsv(self::DELIMITER);

		// Read the parsed lines into memory
		while($line = $file->fgetcsv(self::DELIMITER)) {
			if( count( $line ) < 26 ) {
				continue;
			}

            $account = $this->getAccount($line[0]);
			
			if(!$account) {
			    // TODO: Add logging about skipped line
				continue;
			}
			
			/*
				# format Rabobank .csv: 
				# from account, valuta, date (ISO), Debet or Credit, amount, to account, description, interest date, type of payment (transfer, authorization, ...), ..., additional info, additional info, additional info, additional info,additional info,additional info

                // "IBAN/BBAN","Munt","BIC","Volgnr","Datum","Rentedatum","Bedrag","Saldo na trn","Tegenrekening IBAN/BBAN","Naam tegenpartij","Naam uiteindelijke partij","Naam initiërende partij","BIC tegenpartij","Code","Batch ID","Transactiereferentie","Machtigingskenmerk","Incassant ID","Betalingskenmerk","Omschrijving-1","Omschrijving-2","Omschrijving-3","Reden retour","Oorspr bedrag","Oorspr munt","Koers"
                // "NL52RABO0147936535","EUR","RABONL2U","000000000000000660","2018-08-06","2018-08-06","+12162,73","+27250,14","NL82RABO0329761234","The Hyve Products B.V.","","","RABONL2U","cb","","","","","","factuur 2018-199"," ","","","","",""

				The output format should be:
				  Array(
						"datum"			=> ,
						"tegenrekening"	=> ,
						"omschrijving"	=> ,
						"bedrag"		=>
					)
			*/

			// The date should be converted from yyyy-mm-dd format to dd-mm-yyyy. This is done
			// using the default PHP functions
            // Use either the date or the interest date
			if( $line[4] ) {
				$date = Carbon::createFromFormat( 'Y-m-d', $line[4] );
			} else {
				$date = Carbon::createFromFormat( 'Y-m-d', $line[5] );
			}
			
			// The amount uses a comma as decimal separator
            $amount = floatval(str_replace(',', '.', $line[6]));

			// De omschrijving staat in de posities 19 t/m 22
			$omschrijving = $line[ 19 ] . " " . $line[ 20 ] . " " . $line[ 21 ] . " " . $line[ 22 ];
			
			// The description should be sanatized from several ascii characters
			// Also, double spaces should be converted to one
			$omschrijving = preg_replace('/[^(\x20-\x7F)]*/','', $omschrijving );
			$omschrijving = preg_replace('/ +/', ' ', $omschrijving );
			
			// Zoek de tegenrekening op
			$tegenrekening = $line[8];
			
			// Create transaction object
			$transactions[] = new Transaction([
					"rekening_id" => $account->id,
					"datum" => $date,
					"tegenrekening" => $tegenrekening,
					"omschrijving" => trim($omschrijving),
					"bedrag" => $amount
			]);			
		}

		return $transactions;
	}
	
}
