<?php
namespace Weboffice\Models\Finance;

use Carbon\Carbon;
use DB;
use AppConfig;
use Weboffice\Models\Post;
use Weboffice\Models\PostType;
use Weboffice\Models\StatementLine;

/**
 * Represents a balance on the end of the given date
 * @author robert
 *
 */
class Balance {
	use FinancialStatement;
	
	/**
	 * @var Carbon
	 */
	protected $date;
	
	/**
	 * Determines whether the current balance contains data (i.e. statements)
	 * @var unknown
	 */
	protected $hasData = false;
	
	/**
	 * 
	 * @var array $balance
	 */
	protected $balance = [ 'debet' => [], 'credit' => '' ];

	/**
	 * @var array $balance
	 */
	protected $totals = [ 'debet' => 0, 'credit' => 0 ];
	
	/**
	 * Post for equity
	 * @var unknown
	 */
	protected $postEquity;
	
	/**
	 * Initializes the balance by loading all statements
	 * @param Carbon $date
	 */
	public function __construct(Carbon $date) {
		$this->date = $date;
		$this->postEquity = Post::find(AppConfig::get('postEigenVermogen'));
		
		$this->initialize();
	}
	
	/**
	 * Returns true if there is data for the current balance
	 * @return \Weboffice\Models\Finance\unknown
	 */
	public function hasData() {
		return $this->hasData;
	}
	
	/**
	 * Returns a balance to be shown on the screen
	 */
	public function getBalance($type = null) {
		if($type) {
			return $this->balance[$type];
		} else {
			return $this->balance;
		}
	}
	
	/**
	 * Returns the total values on both sides of the balance
	 */
	public function getTotals($type = null) {
		if($type) {
			return $this->totals[$type];
		} else {
			return $this->totals;
		}
	}
	
	/**
	 * Returns the date for the current balance
	 * @return \Carbon\Carbon
	 */
	public function getDate() {
		return $this->date;
	}
	
	/**
	 * Returns the details on the debet side of the balance
	 */
	public function debet() {
		return $this->balance['debet'];
	}
	
	/**
	 * Returns the details on the credit side of the balance
	 */
	public function credit() {
		return $this->balance['credit'];
	}
	
	/**
	 * Returns the totals on the debet side of the balance
	 */
	public function debetTotal() {
		return $this->totals['debet'];
	}
	
	/**
	 * Returns the totals on the credit side of the balance
	 */
	public function creditTotal() {
		return $this->totals['credit'];
	}
	
	/**
	 * Returns a posttotal object for the given post or null if it is not found
	 * @param Post $post
	 * @return PostTotal
	 */
	public function getDataForPost(Post $post) {
		foreach( $this->balance as $side => $totals ) {
			if( array_key_exists( $post->id, $totals ) ) {
				return $totals[$post->id];
			}
		}
		
		return null;
	}
	
	/**
	 * Returns the posttotal object for the equity
	 * @return PostTotal
	 */
	public function getEquity() {
		return $this->getDataForPost($this->postEquity);
	}
	
	/**
	 * Initializes the balance, based on the given date
	 */
	protected function initialize() {
		// Load statistics from statement lines for all posts
		$this->loadPostStatistics(null, $this->date);
		
		// If no data could be found in the current balance, return immediately
		if(count($this->postStatistics) == 0) {
			$this->hasData = false;
			return;
		} else {
			$this->hasData = true;
		}
		
		// Get the post hierarchy. Add only information on the 
		// first level below the roots
		$this->mergeAmounts();
		
		// Posts can either show up at the balance, or contribute
		// to the results. That depends on the post type. 
		// The results will be added to the equity
		$this->consolidateBalance();
		
		// Make sure the balance is properly ordered
		$this->orderBalance();
		
		// Compute the totals on both sides
		$this->computeTotals();
	}
	
	/**
	 * Consolidate merged amounts into a proper balance
	 */
	protected function consolidateBalance() {
		// Posts can either show up at the balance, or contribute
		// to the results. That depends on the post type.
		// The results will be added to the equity
		$postEquity = $this->postEquity;
		$typeBalance = PostType::where('type', 'balans')->select('id')->first();
		$result = 0;
		
		foreach($this->mergedAmounts as $postTotal) {
			// If no total value, skip this post
			if($postTotal->isEmpty())
				continue;
			
			// Store the amount on the balance, if its type is the proper type
			if( $postTotal->getPost()->post_type_id == $typeBalance->id ) {
				$side = $postTotal->side();
				$this->balance[$side][$postTotal->getPost()->id] = $postTotal;
			} else {
				$result += $postTotal->getSignedAmount();
			}

		}
		
		// Add the total amount of equity to the balance as well
		$side = $this->side($result);
		$this->balance[$side][$postEquity->id] = new PostTotal($postEquity, $result);
	}

	/**
	 * Order both sides of the balance by number
	 */
	protected function orderBalance() {
		foreach( ['debet', 'credit'] as $side ) {
			uasort($this->balance[$side], function($a,$b) { 
				// TODO PHP7: use spaceship operator
				//return $a['post']->nummer <=> $b['post']->nummer;
				$nA = $a->getPost()->nummer;
				$nB = $b->getPost()->nummer;
				
				if($nA == $nB) return 0;
				return $nA < $nB ? -1 : 1;
			});
		}
	}
	
	/**
	 * Computes the totals on both sides of the balance
	 */
	protected function computeTotals() {
		$this->totals = [];
		foreach( $this->balance as $side => $posts ) {
			$total = 0;
			foreach( $posts as $postTotal ) {
				$total += $postTotal->getAmount();
			}
			
			$this->totals[$side] = $total;
		}
	}
}


