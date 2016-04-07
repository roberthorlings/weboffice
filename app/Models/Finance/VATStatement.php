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
 * VAT statement.
 * @author robert
 *
 */
class VATStatement extends Ledgers {
	protected $declarationStatement;
	protected $paymentStatement;
	
	/**
	 * Initializes the VAT statement by generating ledgers
	 * @param Carbon $date
	 */
	public function __construct(Carbon $start, Carbon $end) {
		$postIds = [AppConfig::get( 'postTeVorderenBTW' ), AppConfig::get( 'postAfTeDragenBTW')];
		$posts = Post::findMany($postIds);
		parent::__construct( $start, $end, $posts );
	}
	
	/**
	 * Returns a statement that can be used to declare the VAT
	 * @return Statement
	 */
	public function getDeclarationStatement() {
		if(!$this->declarationStatement)
			$this->generateStatements();
		
		return $this->declarationStatement;
	}
	
	/**
	 * Returns a statement that can be used to move the VAT amount to be paid (debtors or creditors)
	 * @return Statement
	 */
	public function getPaymentStatement() {
		if(!$this->paymentStatement)
			$this->generateStatements();
		
		return $this->paymentStatement;
	}
	
	public function saveStatements() {
		// Store the declaration statement
		$this->getDeclarationStatement()->saveCascaded();
		
		// The payment statement should be associated with a saldo
		$saldo = Saldo::create(['relatie_id' => AppConfig::get('relatieBelasting'), 'omschrijving' => 'Betaling BTW ' . Timespan::create($this->start, $this->end) ]);
		
		// Associate the last statementline with the saldo. We know that the payment statement
		// always has two statement lines
		$paymentStatement = $this->getPaymentStatement();
		$paymentStatement->StatementLines[1]->saldo_id = $saldo->id;
		
		// Store the payment statement as well
		$paymentStatement->saveCascaded();
	}
	
	/**
	 * Generates statements to to declare and pay the VAT for the given period
	 */
	protected function generateStatements() {
		// When declaring taxes, it is allowed to round the amounts either way
		// depending on what's best for the tax payer. This can be done by
		// using ceil to round the amounts to +infinity.
		$roundedAmounts = [];
		$roundingDifference = 0;
		$total = 0;
		foreach( $this->ledgers as $postId => $ledger ) {
			$roundedAmounts[$postId] = ceil(round($ledger->getTotal(), 2));
			$total += $roundedAmounts[$postId];
			$roundingDifference += ($ledger->getTotal() - $roundedAmounts[$postId]);
		}
		
		// Determine the remarks for the declaration, including the total amount of revenue
		$profitAndLoss = new ProfitAndLossStatement($this->start, $this->end);
		$revenue = $profitAndLoss->getResult("baten");
		$description = [ "Totaal baten: " . ($revenue ? round(abs( $revenue->getTotal() )) : '0') ];
		
		// Add information on how to declare the taxes. This is actually a mapping from
		// a post to the field in the declaration
		$mapping = [ AppConfig::get("postAfTeDragenBTW" ) => "BTW (1a)", AppConfig::get("postBTWVerlegd") => "Verlegd (4b)", AppConfig::get("postTeVorderenBTW") => "Voorbelasting (5b)" ];
		foreach( $mapping as $postId => $text ) {
			$ledger = $this->getLedger($postId);
			if($ledger && abs($ledger->getTotal()) > 0)
				$description[] = $text . ": " . abs($roundedAmounts[$postId]);
		}
		
		// Create a declaration statement
		$declarationStatement = new Statement([
				'datum' 		=> $this->end,
				'omschrijving' 	=> 'Aangifte BTW ' . Timespan::create($this->start, $this->end)->getDescription(),
				'opmerkingen' 	=> implode(", ", $description),
		]);
		
		$declarationStatement->StatementLines->add(new StatementLine(['bedrag' => abs($total), 'credit' => $total < 0, 'post_id' => AppConfig::get("postBTWOpAangifte") ]));
		$declarationStatement->StatementLines->add(new StatementLine(['bedrag' => abs($roundingDifference), 'credit' => $roundingDifference < 0, 'post_id' => AppConfig::get('postAfrondingsverschil') ]));
		
		// Add a statementline for each of the totals
		foreach( $this->ledgers as $postId => $ledger ) {
			$declarationStatement->StatementLines->add(new StatementLine(['bedrag' => abs($ledger->getTotal()), 'credit' => $ledger->getTotal() > 0, 'post_id' => $postId ]));
		}
		
		// Create a payment statement
		$side = $total < 0 ? 'crediteuren' : 'debiteuren';
		$postId = AppConfig::get('post' . ucfirst($side));
		$paymentStatement = new Statement([
				'datum' 		=> $this->end,
				'omschrijving' 	=> 'Aangifte BTW ' . Timespan::create($this->start, $this->end) . ' naar ' . $side
		]);
		
		$paymentStatement->StatementLines->add(new StatementLine(['bedrag' => abs($total), 'credit' => $total > 0, 'post_id' => AppConfig::get("postBTWOpAangifte") ]));
		$paymentStatement->StatementLines->add(new StatementLine(['bedrag' => abs($total), 'credit' => $total <= 0, 'post_id' => $postId ]));
		
		// Store statements
		$this->declarationStatement = $declarationStatement;
		$this->paymentStatement = $paymentStatement;
	}
}