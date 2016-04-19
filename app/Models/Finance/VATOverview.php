<?php
namespace Weboffice\Models\Finance;

use AppConfig;
use Carbon\Carbon;
use Weboffice\Models\Post;
use Weboffice\Models\Statement;
use Weboffice\Models\StatementLine;
use Weboffice\Support\Timespan;
use Weboffice\Models\Saldo;

/**
 * VAT overview
 * @author robert
 *
 */
class VATOverview extends Ledger {
	/**
	 * Cache variable for statement lines wrt VAT declaration
	 * @var Collection $vatDeclarationStatementLines
	 */
	protected $vatDeclarationStatementLines;
	
	/**
	 * Cache variable for the revenue amount
	 * @var int
	 */
	protected $revenue;
	
	/**
	 * Initializes the VAT statement by generating ledgers
	 * @param Carbon $date
	 */
	public function __construct(Carbon $start, Carbon $end) {
		$post = Post::find(AppConfig::get( 'postAfTeDragenBTW'));
		parent::__construct( $start, $end, $post );
		$this->loadStatementLines();
	}
	
	public function getRevenue() {
		if( is_null($this->revenue) ) {
			$statement = new ProfitAndLossStatement($this->start, $this->end);
			$this->revenue = $statement->getResult('baten');
		}
		
		return $this->revenue;	
	}
	
	/**
	 * Returns the period for this VAT overview
	 */
	public function getPeriod() {
		return new Timespan($this->start, $this->end);
	}
	
	/**
	 * Returns the total amount of VAT to be payed this period
	 */
	public function getVAT() {
		// Exclude VAT declarations from the ledger and sum
		return $this->getTotal() - $this->getDeclaredVATAmount();
	}
	
	public function isPayed() {
		return abs($this->getTotal()) < 0.001; 
	}
	
	/**
	 * Returns any statement lines regarding the VAT declaration in this ledger
	 */
	public function getVATDeclarationStatementLines() {
		if( !$this->vatDeclarationStatementLines) {
			$this->vatDeclarationStatementLines = $this->statementLines->filter(function($line) {
				return preg_match( '/Aangifte/', $line->Statement->omschrijving);
			});
		}
		
		return $this->vatDeclarationStatementLines;
	}
	
	/**
	 * Returns the amount of VAT that has been declared
	 */
	public function getDeclaredVATAmount() {
		$total = 0;
		foreach( $this->getVATDeclarationStatementLines() as $line ) {
			$total += $line->getSignedAmount();
		}
		
		return $total;
	}
}