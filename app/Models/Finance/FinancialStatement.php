<?php
namespace Weboffice\Models\Finance;

use Carbon\Carbon;
use DB;
use AppConfig;
use Weboffice\Models\Post;
use Weboffice\Models\StatementLine;

/**
 * Handles common functions in a financial statement
 * @author robert
 *
 */
trait FinancialStatement {
	/**
	 * Statistics for the posts.
	 *  
	 * A map with the key being the post_id and the value
	 * being an object with properties post_id, credit and total.
	 * 
	 * The total indicates the sum of all statementlines for the given post and side
	 * 
	 * @var array $postStatistics
	 */
	protected $postStatistics = [];
	
	/**
	 * Map with the sum of all amounts for only the first level of posts below the roots
	 *  
	 * A map with the key being the post_id and the value
	 * being a map with 
	 *   'post' 	referring to the Post object for the given post
	 *   'total'	the amount that is booked onto this post or its descendants. Negative means credit, positive means debit
	 * 
	 * @var array
	 */
	protected $mergedAmounts = [];
	
	/**
	 * Loads statistics per post/side combination
	 * 
	 * The sum of amounts that is booked onto the debet or credit side of a post is returned
	 */
	protected function loadPostStatistics($start, $end) {
		$query = StatementLine::select('post_id', 'credit', DB::raw('SUM(bedrag) / 100 as total'))
			->join('boekingen', 'boekingen.id', '=', 'boeking_delen.boeking_id')
			->groupBy('post_id', 'credit')
			->orderBy('post_id');
		
		if($start != null) {
			$query->where('datum', '>=', $start);
		}
		
		if($end != null) {
			$query->where('datum', '<=', $end);
		}
		
		$statistics = $query->get();
		
		// Convert into a map
		$statisticsMap = [];
		foreach( $statistics as $statistic ) {
			if( !array_key_exists($statistic->post_id, $statisticsMap)) {
				$statisticsMap[$statistic->post_id] = [];
			}
			
			$statisticsMap[$statistic->post_id][] = $statistic;
		}
		
		return $this->postStatistics = $statisticsMap;
	}
	
	/**
	 * Merge all amounts from the posts into the categories below the roots
	 */
	protected function mergeAmounts() {
		// Get the post hierarchy. Add only information on the
		// first level below the roots
		$posts = Post::all()->toHierarchy();
		foreach( $posts as $root ) {
			// Any amounts for the root should not be merged, but added itself
			$this->addAmountToMergedSet($root, $root);
	
			foreach( $root->children as $child ) {
				// Add the total amounts for the child itself, as well as its children
				$this->mergeAmount($child, $child);
			}
		}
	}
	
	/**
	 * Merges the total sum of all activity on the given child (including its children), into the totals for post.
	 * @param unknown $post
	 * @param unknown $child
	 */
	protected function mergeAmount($post, $child) {
		$this->addAmountToMergedSet($post, $child);
		
		// Also merge in child posts
		foreach( $child->children as $grandchild ) {
			$this->mergeAmount($post, $grandchild);
		}
	}
	
	/**
	 * Add amount for a single post to the merged set
	 * @param unknown $post
	 * @param unknown $child
	 */
	protected function addAmountToMergedSet($post, $child) {
		// Check whether there has been any activity on this post in the specified period
		if( array_key_exists($child->id, $this->postStatistics) ) {
			// Make sure the entry exists in the merged amounts array
			if( !array_key_exists( $post->id, $this->mergedAmounts ) ) {
				$this->mergedAmounts[$post->id] = new PostTotal($post);
			}
			
			foreach($this->postStatistics[$child->id] as $stat) {
				$multiplier = $stat->credit ? -1 : 1;
				$this->mergedAmounts[$post->id]->add($multiplier * $stat->total);
			}
		}
		
	}
	
	/**
	 * Returns the side of the balance to put an amount on
	 * @param unknown $amount
	 */
	protected function side($amount) {
		return $amount < 0 ? 'credit': 'debet';
	}
}


