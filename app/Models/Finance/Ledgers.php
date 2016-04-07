<?php
namespace Weboffice\Models\Finance;

use Carbon\Carbon;
use Weboffice\Models\Post;
use Weboffice\Models\StatementLine;
use Weboffice\Models\PostType;

/**
 * General ledger data.
 * @author robert
 *
 */
class Ledgers {
	use FinancialStatement;
	
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
	 * Ledgers
	 * @var array $result
	 */
	protected $ledgers = [];
	
	/**
	 * List of all statementlines for the given period
	 * @var unknown
	 */
	protected $statementLines = [];
	
	/**
	 * List of posts to include
	 * @var unknown $posts
	 */
	protected $posts;

	/**
	 * Initializes the profit and loss statement by loading all statements
	 * @param Carbon $date
	 */
	public function __construct(Carbon $start, Carbon $end, $posts) {
		$this->start = $start;
		$this->end = $end;
	
		if( $posts ) {
			$this->posts = $posts->all();
		}
		
		$this->initialize();
	}

	public function getLedgers() {
		return $this->ledgers;
	}
	
	/**
	 * Returns a single ledger from this collection
	 * @param unknown $postId
	 */
	public function getLedger($postId) {
		if(array_key_exists($postId, $this->ledgers)) {
			return $this->ledgers[$postId];
		} else {
			return null;
		}
	}
	
	protected function getPostIds() {
		return array_map(function($post) { return $post->id; }, $this->posts);
	}
	
	/**
	 * Initializes the ledgers, based on the given dates
	 */
	protected function initialize() {
		// Load statistics from statement lines for all posts
		// before the start date
		$this->loadPostStatistics(null, $this->start, $this->getPostIds() );
		
		// Load statement lines for all posts in a single query
		$this->loadStatementLines();
		
		// Initialize ledgers 
		$this->createLedgers();
	}
	
	/**
	 * Consolidate merged amounts into a proper statement
	 */
	protected function loadStatementLines() {
		$statementLines = StatementLine::with(['Statement', 'Post'])
			->join('boekingen', 'boekingen.id', '=', 'boeking_delen.boeking_id')
			->join('posten', 'posten.id', '=', 'boeking_delen.post_id')
			->whereBetween('datum', [$this->start, $this->end])
			->whereIn('post_id', $this->getPostIds())
			->orderBy('nummer', 'asc')
			->orderBy('datum', 'asc')
			->get();
		
		// Group lines by post id
		$statementLineMap = [];
		foreach( $statementLines as $line ) {
			if( !array_key_exists( $line->post_id, $statementLineMap ) ) {
				$statementLineMap[$line->post_id] = [];
			}
			$statementLineMap[$line->post_id][] = $line;
		}
		
		$this->statementLines = $statementLineMap;
	}
	
	/**
	 * 
	 */
	protected function createLedgers() {
		$typesBalance = PostType::whereIn('type', ['balans', 'eigen vermogen'])->select('id')->get();
		$balanceTypeIds = array_map(function($type) { return $type->id; }, $typesBalance->all());
		
		foreach( $this->posts as $post ) {
			// Create a ledger for this post
			$ledger = new Ledger($this->start, $this->end, $post);
			
			// Add all lines for the current period
			$lines = array_key_exists($post->id, $this->statementLines) ? $this->statementLines[$post->id] : [];
			$ledger->setStatementLines($lines);
			
			// Check whether we have an initial value for this ledger.
			if( in_array($post->post_type_id, $balanceTypeIds) && array_key_exists($post->id, $this->postStatistics) ) {
				$initial = 0;
				foreach( $this->postStatistics[$post->id] as $stat ) {
					$initial += ( $stat->credit ? -1 : 1) * $stat->total;
				}
				
				$ledger->setInitial($initial);
			}
			
			// Only store ledger if there is something relevant
			if(abs($ledger->getTotal()) > 0 || count($lines) > 0) {
				$this->ledgers[$post->id] = $ledger;
			}
		}
	}
	
	
}