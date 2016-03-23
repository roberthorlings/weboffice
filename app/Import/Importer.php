<?php
namespace Weboffice\Import;

use Weboffice\Models\Account;

/**
 * Abstract importer class
 */
 
abstract class Importer {
	/**
	 * @var \SplFileInfo
	 */
	protected $file;
	
	protected $accountNumber = "";
	protected $bank = null;
	protected $type = "";
	
	/**
	 * Cache map with accounts, mapped by their account number
	 * @var array
	 */
	protected $accounts = [];
	
	public static $availableImporters = [
		RaboCSVImporter::class,
		RaboMutAscImporter::class,
		ABNImporter::class,
		INGImporter::class,
	];
	
	function __construct(\SplFileInfo $file) {
		$this->file = $file;
	}
	
	/** 
	 * Parses the uploaded file.
	 *
	 * @return	array	Array with every transaction as an element. Every element should 
	 *					be an array that can be inserted into the Transacties table.
	 */
	abstract function parse();
	
	/** 
	 * Returns an account object for the given account number.
	 *
	 * @return	Account|null Bank account when a bank account is found, null otherwise
	 */
	function getAccount($accountNumber) {
		if(!array_key_exists($accountNumber, $this->accounts)) {
			$this->accounts[$accountNumber] = Account::where('rekeningnummer', $accountNumber)->first();
		}
		return $this->accounts[$accountNumber];
	}

	/**
	 * Determines the type of the uploaded file. Possible values for ABN files are MT940 and TAB
	 */
	function getType() {
		return $this->type;
	}

	/**
	 * Determines which importer to use for the given file and returns an instance of that importer
	 * @return	Importer
	 */
	public static function getImporter(\SplFileInfo $file) {
		// Open the file for reading
		$filehandle = $file->openFile("r");
		
		foreach( self::$availableImporters as $availableImporter ) {
			// Reset file pointer
			$filehandle->rewind();
			
			if($availableImporter::supports($filehandle)) {
				return new $availableImporter($file);
			}
		}

		// If no importer supports this type of file, tell the caller
		return null;
	}
	
	/**
	 * Checks the type of the bank account
	 * @param $accountNumber
	 * @return $type
	 */
	protected static function accountType( $accountNumber ) {
		$account = Account::where('rekeningnummer', $accountNumber)->first();
		return $account ? $account->bank : null;
	}
	
}
