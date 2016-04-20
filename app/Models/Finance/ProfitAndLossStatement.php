<?php
namespace Weboffice\Models\Finance;

use Carbon\Carbon;
use Weboffice\Models\PostType;
use Weboffice\Models\Post;
use AppConfig;
use Weboffice\Support\Timespan;

/**
 * Profit and loss statement.
 * @author robert
 *
 */
class ProfitAndLossStatement {
	use FinancialStatement;
	
	const TYPE_RESULTS = "results";
	const TYPE_OTHER = "other";
	const TYPE_LIMITED = "limited";
	
	/**
	 * 
	 * @var Carbon
	 */
	protected $start;
	
	/**
	 * 
	 * @var Carbon
	 */
	protected $end;
	
	/**
	 * Results that are attributed to the profit and loss statement
	 * @var array $result
	 */
	protected $results = [];
	protected $resultsTotal = 0;
	
	/**
	 * Results that influence the equity but are not attributed to the profit and loss statement
	 * e.g. private deposits or withdrawals
	 * @var array $result
	 */
	protected $other = [];
	protected $otherTotal = 0;
	
	/**
	 * Part of the results that can only be attributed to the P&L for a limited percentage
	 * e.g. diners
	 * @var array $result
	 */
	protected $limited = [];
	protected $limitedTotal = 0;
	
	/**
	 * Initializes the profit and loss statement by loading all statements
	 * @param Carbon $date
	 */
	public function __construct(Carbon $start, Carbon $end) {
		$this->start = $start;
		$this->end = $end;
	
		$this->initialize();
	}
	
	public function getStart() {
		return $this->start;
	}
	
	public function getEnd() {
		return $this->end;
	}
	
	public function getPeriod() {
		return Timespan::create($this->start, $this->end);
	}
	
	public function getResults() {
		return $this->results;
	}
	public function getResult($type) {
		if(array_key_exists($type, $this->results))
			return $this->results[$type];
		else
			return null;
	}
	
	
	public function getResultTotal() {
		return $this->resultsTotal;
	}
	
	public function getOther() {
		return $this->other;
	}
	
	public function getOtherTotal() {
		return $this->otherTotal;
	}
	
	public function getLimited() {
		return $this->limited;
	}
	
	public function getLimitedTotal() {
		return $this->limitedTotal;
	}
	
	public function getData($type) {
		switch($type) {
			case self::TYPE_RESULTS: return $this->getResults();
			case self::TYPE_OTHER: return $this->getOther();
			case self::TYPE_LIMITED: return $this->getLimited();
			default: throw new Exception( "Unsupported data type" );
		}
	}
	public function getTotal($type) {
		switch($type) {
			case self::TYPE_RESULTS: return $this->getResultTotal();
			case self::TYPE_OTHER: return $this->getOtherTotal();
			case self::TYPE_LIMITED: return $this->getLimitedTotal();
			default: throw new Exception( "Unsupported data type" );
		}
	}
	
	public function getEquityChanges() {
		$evPost = Post::find(AppConfig::get('postEigenVermogen'));
		$resultsPost = Post::find(AppConfig::get('postResultaat'));
		
		$results = new PostTotals('Results', [
			new PostTotal($resultsPost, $this->getResultTotal())
		]);
		$other = new PostTotals('Equity changes', [
				new PostTotal($evPost, $this->getOtherTotal())
		]);
		
		return [
			'results' => $results,
			'other' => $other
		];
	}
	
	public function getEquityChangesTotal() {
		return $this->getResultTotal() + $this->getOtherTotal();
	}
	
	/**
	 * Convenience method to return the turnover
	 */
	public function getTurnover() {
		$result = $this->getResult("baten");
		
		if( $result ) {
			return -$result->getTotal();
		} else {
			return 0;
		}
	}
	
	/**
	 * Convenience method to return the total revenue
	 */
	public function getRevenue() {
		return -$this->getResultTotal();
	}
	
	/**
	 * Initializes the p&l statement, based on the given dates
	 */
	protected function initialize() {
		// Load statistics from statement lines for all posts
		$this->loadPostStatistics($this->start, $this->end);
		
		// Get the post hierarchy. Add only information on the 
		// first level below the roots
		$this->mergeAmounts();
		
		// Posts can either contribute to the results, influence the 
		// equity and/or have limited deductability 
		// That depends on the post type. 
		$this->consolidateProfitAndLoss();
		
		// Make sure the statement is properly ordered
		$this->orderStatements();
		
		// Compute totals for each category
		$this->computeTotals();
	}
	
	/**
	 * Consolidate merged amounts into a proper statement
	 */
	protected function consolidateProfitAndLoss() {
		// Posts can either contribute to the results, influence the 
		// equity and/or have limited deductability
		$types = PostType::all()->getDictionary();
		$result = 0;
		
		foreach($this->mergedAmounts as $postTotal) {
			// If no total value, skip this post
			if($postTotal->isEmpty())
				continue;
			
			// Retrieve the posttype
			$post = $postTotal->getPost();
			$postType = $types[$post->post_type_id];
			
			// Check if this amount is relevant to the result
			if(!$postType->draagt_bij_aan_resultaat) {
				continue;
			}
			
			// The percentage_aftrekbaar indicates whether the post attributes to the P&L
			if($post->percentage_aftrekbaar == 0) {
				// 	If 0, it should be regarded as 'other'
				$this->addToList('other', $postType->type, $postTotal);
			} else {
				// Otherwise, it contributes to the result
				$this->addToList('results', $postType->type, $postTotal);
				
				// However, for partially deducatble amounts, we should add them to 
				// the limited list as well
				if($post->percentage_aftrekbaar < 100) {
					$limitedAmount = clone $postTotal;
					$limitedAmount->multiply((100 - $post->percentage_aftrekbaar) / 100);
					$this->addToList('limited', $postType->type, $limitedAmount);
				}
			}
		}
	}
	
	/**
	 * 
	 * @param unknown $propertyName
	 * @param unknown $key
	 * @param unknown $value
	 */
	protected function addToList($propertyName, $key, PostTotal $value) {
		if(!array_key_exists($key, $this->$propertyName)) {
			$this->{$propertyName}[$key] = new PostTotals($this->getLabel($key));
		}
		$this->{$propertyName}[$key]->append($value);
	}
	
	/**
	 * Returns a label for a specified post type
	 * @param unknown $key
	 */
	protected function getLabel($key) {
		switch($key) {
			case 'verlies en winst': return 'Resultaten';
			default:				 return ucfirst($key);
		}
	}

	/**
	 * Order both sides of the balance by number
	 */
	protected function orderStatements() {
		foreach( ['results', 'other', 'limited'] as $property) {
			ksort($this->{$property});
		}
	}
	
	/**
	 * Order both sides of the balance by number
	 */
	protected function computeTotals() {
		foreach( ['results', 'other', 'limited'] as $property) {
			$total = 0;
			
			foreach($this->{$property} as $key => $posts) {
				$total += $posts->getTotal();
			}
			
			$this->{$property . 'Total'} = $total;
		}
	}
	
	
}