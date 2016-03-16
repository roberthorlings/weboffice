<?php
namespace Weboffice\Models\Finance;

class PostTotals extends \ArrayObject {
	/**
	 * 
	 * @var float
	 */
	protected $total = 0;
	
	/**
	 * Label
	 * @var string
	 */
	protected $label;
	
	/**
	 */
	public function __construct($label, $postTotals = null) {
		$this->label = $label;
		
		if($postTotals != null)
			$this->addAll($postTotals);
	}
	
	/**
	 * Adds multiple to the list
	 * @param array $totals
	 */
	public function addAll($totals) {
		foreach( $totals as $total ) {
			$this->append($total);
		}
	}
	
	/**
	 * Adds a total to this list
	 * @param unknown $total
	 */
	public function append($total) {
		parent::append($total);
		$this->total += $total->getSignedAmount();
	}
	
	/**
	 * Returns the total of all posts combined
	 */
	public function getTotal() {
		return $this->total;
	}
	
	/**
	 * Returns the label for this list
	 */
	public function getLabel() {
		return $this->label;
	}
	
	/**
	 * Returns the side this amount is to be placed on 
	 */
	public function side() {
		return $this->total < 0 ? 'credit': 'debet';
	}
}