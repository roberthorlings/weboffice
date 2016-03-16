<?php
namespace Weboffice\Models\Finance;

use Weboffice\Models\Post;

class PostTotal {
	/**
	 * @var Post
	 */
	protected $post;
	
	/**
	 * 
	 * @var float
	 */
	protected $amount;
	
	/**
	 * 
	 * @param Post $post
	 * @param unknown $amount
	 */
	public function __construct(Post $post, $amount = 0) {
		$this->post = $post;
		$this->amount = $amount;
	}
	
	/**
	 * Increments the amount of this posttotal
	 * @param unknown $amount
	 */
	public function add($amount) {
		$this->amount += $amount;
	}
	
	/**
	 * Multiplies the amount with the given multiplier
	 * @param unknown $multiplier
	 */
	public function multiply($multiplier) {
		$this->amount *= $multiplier;
	}
	
	/**
	 * Checks whether the amount in this total is effectively zero
	 */
	public function isEmpty() {
		return abs($this->amount) < 0.0005;	
	}
	
	/**
	 * Returns the post
	 */
	public function getPost() {
		return $this->post;
	}
	
	/**
	 * Returns the amount
	 */
	public function getAmount() {
		return abs($this->amount);
	}
	
	/**
	 * Returns the amount with sign (negative means credit)
	 */
	public function getSignedAmount() {
		return $this->amount;
	}
	
	
	/**
	 * Returns the side this amount is to be placed on 
	 */
	public function side() {
		return $this->amount < 0 ? 'credit': 'debet';
	}

}