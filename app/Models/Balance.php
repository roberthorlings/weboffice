<?php
namespace Weboffice\Models;

use Carbon\Carbon;
use DB;
use AppConfig;

/**
 * Represents a balance on the end of the given date
 * @author robert
 *
 */
class Balance {
	/**
	 * @var Carbon
	 */
	protected $date;
	
	/**
	 * 
	 * @var array $postStatistics
	 */
	protected $postStatistics = [];
	
	/**
	 * 
	 * @var array
	 */
	protected $mergedAmounts = [];
	
	/**
	 * 
	 * @var array $balance
	 */
	protected $balance = [ 'debet' => [], 'credit' => '' ];
	
	/**
	 * Initializes the balance by loading all statements
	 * @param Carbon $date
	 */
	public function __construct(Carbon $date) {
		$this->date = $date;
		
		$this->initialize();
	}
	
	/**
	 * Returns a balance to be shown on the screen
	 */
	public function getBalance() {
		return $this->balance();
	}
	
	/**
	 * Initializes the balance, based on the given date
	 */
	protected function initialize() {
		
		// Load statistics from statement lines for all posts
		$this->loadPostStatistics();
		
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
		
		// Posts can either show up at the balance, or contribute
		// to the results. That depends on the post type. 
		// The results will be added to the equity
		$this->consolidateBalance();
		
		// Make sure the balance is properly ordered
		$this->orderBalance();
	}
	
	/**
	 * Loads statistics per post/side combination
	 * 
	 * The sum of amounts that is booked onto the debet or credit side of a post is returned
	 */
	protected function loadPostStatistics() {
		$statistics = StatementLine::select('post_id', 'credit', DB::raw('SUM(bedrag) / 100 as total'))
			->join('boekingen', 'boekingen.id', '=', 'boeking_delen.boeking_id')
			->where('datum', '<=', $this->date)
			->groupBy('post_id', 'credit')
			->orderBy('post_id')
			->get();
		
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
				$this->mergedAmounts[$post->id] = [ 'post' => $post, 'total' => 0 ];
			}
			
			foreach($this->postStatistics[$child->id] as $stat) {
				$multiplier = $stat->credit ? -1 : 1;
				$this->mergedAmounts[$post->id]['total'] += $multiplier * $stat->total;
			}
		}
		
	}
	
	/**
	 * Consolidate merged amounts into a proper balance
	 */
	protected function consolidateBalance() {
		// Posts can either show up at the balance, or contribute
		// to the results. That depends on the post type.
		// The results will be added to the equity
		$postEquity = Post::find(AppConfig::get('postEigenVermogen'));
		$typeBalance = PostType::where('type', 'balans')->select('id')->first();
		$result = 0;
		
		foreach($this->mergedAmounts as $amount) {
			// If no total value, skip this post
			if(abs($amount['total']) < 0.0005)
				continue;
			
			// Store the amount on the balance, if its type is the proper type
			if( $amount['post']->post_type_id == $typeBalance->id ) {
				$side = $this->side($amount['total']);
				$amount[ 'total' ] = abs($amount['total']);
				$this->balance[$side][] = $amount;
			} else {
				$result += $amount['total'];
			}

		}
		
		// Add the total amount of equity to the balance as well
		$side = $this->side($result);
		$this->balance[$side][] = ['post' => $postEquity, 'total' => abs($result)];
	}

	/**
	 * Order both sides of the balance by number
	 */
	protected function orderBalance() {
		foreach( ['debet', 'credit'] as $side ) {
			usort($this->balance[$side], function($a,$b) { 
				// TODO PHP7: use spaceship operator
				//return $a['post']->nummer <=> $b['post']->nummer;
				$nA = $a['post']->nummer;
				$nB = $b['post']->nummer;
				
				if($nA == $nB) return 0;
				return $nA < $nB ? -1 : 1;
			});
		}
	}
	
	/**
	 * Returns the side of the balance to put an amount on
	 * @param unknown $amount
	 */
	protected function side($amount) {
		return $amount < 0 ? 'credit': 'debet';
	}
	
	/**
	 * Maakt een balans aan
	 * @param $datum		DateTime object (boekingen van deze datum worden ook meegenomen in de balans)
	 * @param $begindatum	DateTime object van de begindatum
	 */
	function maakBalans( $datum = null, $begindatum = null ) {
		if( $datum == null ) {
			$datum = new DateTime();
		}
			
		// Zoek de Eigen Vermogen post op
		$config = ConfigurationLib::getInstance();
		$postEV = $this->Post->findByPostTypeId( $config->postEigenVermogen );
	
		// Zoek alle bedragen op van alle 'hoofdposten'
		$hoofdposten = $this->Post->find( 'all', array( "conditions" => array( "parent_id" => 0 ), "order" => "Post.lft" ) );
			
		//		Negatieve bedragen staan credit, positieve bedragen staan debet
		$bedragen = array();
		$zijden = array();
		$resultaat = 0;
	
		// Bouw een balans op
		$typeBalans = $this->PostType->findByType( "balans" ) ;
		$balans = array( "debet" => array(), "credit" => array(), "totaal" => array( "debet" => 0, "credit" => 0 ) );
			
		foreach( $hoofdposten as $hoofdpost ) {
			$hoofd_id = $hoofdpost[ 'Post' ][ 'id' ];
			$bedragen[ $hoofd_id ] = $this->BoekingDeel->getTotaal( $hoofd_id, $begindatum, $datum );
	
			foreach( $bedragen[ $hoofd_id ][ "nodes" ] as $balanspost ) {
				// Special case: Eigen vermogen - tel het resultaat hierbij op
				if( $balanspost[ "id" ] == $postEV[ "Post" ][ "id" ] ) {
					$balanspostEV = $balanspost;
				} else {
					switch( $balanspost[ "type" ] ) {
						case $typeBalans[ "PostType" ][ "id" ]:
							$balans = $this->_voegToeAanBalans( $balans, $balanspost );
	
							// Voeg ook het eerste niveau aan subposten toe
							if( $config->toonSubPostenInBalans ) {
								// Bepaal de zijde waar de post (met subposten) moet komen
								if( $balanspost[ "totaal" ] > 0 ) {
									$kant = "debet" ;
								} elseif( $balanspost[ "totaal" ] < 0 ) {
									$kant = "credit";
								} else {
									$kant = null;
								}
	
								$aantalnodes = count( $balanspost[ "nodes" ] );
								if( $aantalnodes > 1 ) {
									for( $i = 1; $i < $aantalnodes; $i++ ) {
										$balans = $this->_voegToeAanBalans( $balans, $balanspost[ "nodes" ][ $i ], $kant, 2 );
									}
								}
							}
							break;
						default:
							$resultaat += $balanspost[ "totaal" ];
							break;
					}
				}
			}
		}
			
		// Voeg ook het EV toe aan de balans
		$balanspostEV[ "totaal" ] += $resultaat;
		$balanspostEV[ "bedrag" ] += $resultaat;
			
		$balans = $this->_voegToeAanBalans( $balans, $balanspostEV );
		return $balans;
	}
	
	/**
	 * Zet de verschillende posten op de balans per zijde op volgorde van postnummer
	 * @param $balans
	 * @return	$balans
	 */
	function orderBalans( $balans ) {
		$sort_func = create_function( '$a,$b', 'return $a[ "nummer" ] == $b[ "nummer" ] ? 0 : $a[ "nummer" ] < $b[ "nummer" ] ? -1 : 1;');
		usort( $balans[ "debet" ], $sort_func );
		usort( $balans[ "credit" ], $sort_func );
		return $balans;
	}	
}


