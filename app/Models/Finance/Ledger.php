<?php
namespace Weboffice\Models\Finance;

use Carbon\Carbon;
use Weboffice\Models\PostType;
use Weboffice\Models\Post;
use AppConfig;

/**
 * General ledger data for a single post
 * @author robert
 *
 */
class Ledger {
	
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
	 * Post
	 * @var Post
	 */
	protected $post;

	/**
	 * @var array
	 */
	protected $statementLines = [];
	
	/**
	 * Initial value
	 * @var unknown $initial
	 */
	protected $initial = 0;
	
	/**
	 * Total amount 
	 * @var unknown $total
	 */
	protected $total = 0;
	
	/**
	 * Initializes the ledger
	 * @param Carbon $date
	 */
	public function __construct(Carbon $start, Carbon $end, Post $post) {
		$this->start = $start;
		$this->end = $end;
		$this->post = $post;
	}
	
	public function loadStatementLines() {
		$this->setStatementLines(
				StatementLine::with('Statement')
					->whereBetween('datum', [$this->start, $this->end])
					->where('post_id', $this->post->id)
					->get()
		);
	}
	
	public function setStatementLines(array $lines) {
		$this->statementLines = $lines;
		$this->updateTotal();
	}
	
	public function getStatementLines() {
		return $this->statementLines;
	}
	
	public function setInitial($initial) {
		$this->initial = $initial;
		$this->updateTotal();
	}
	public function getInitial() {
		return $this->initial;
	}

	public function getTotal() {
		return $this->total;
	}
	
	public function getPost() {
		return $this->post;
	}
	
	public function getStart() {
		return $this->start;
	}
	public function getEnd() {
		return $this->end;
	}
	
	public function totalSide() {
		return $this->total < 0 ? 'credit' : 'debet';
	}
	
	protected function updateTotal() {
		$total = $this->initial;
		foreach( $this->statementLines as $line ) {
			$total += $line->getSignedAmount();
		}
		
		$this->total = $total;
	}
}