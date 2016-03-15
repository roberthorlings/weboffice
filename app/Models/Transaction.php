<?php

namespace Weboffice\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Transaction extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'transacties';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['omschrijving', 'bedrag', 'datum', 'tegenrekening', 'ingedeeld', 'rekening_id'];

    protected $dates = [ 'datum' ];
    
    public function Account()
    {
        return $this->belongsTo('\Weboffice\Models\Account', 'rekening_id');
    }
    
    public function Statement()
    {
    	return $this->hasOne('\Weboffice\Models\Statement', 'transactie_id');
    }

    /**
     * Get a specific attribute for use in forms. Dates and times have to be formatted properly
     *
     * @return string
     */
    public function getFormValue($field)
    {
    	switch($field) {
    		case 'datum':
    			return $this->datum->format('Y-m-d');
    		default:
    			return $this->{$field};
    	}
    }    
    
	/**
	 * Checks whether the current transaction is being split
	 * @return boolean
	 */
	public function isSplitted() {
		return $this->Statement && count($this->Statement->StatementLines) > 2;
	}
	
	/**
	 * Returns whether this transaction should be a credit booking. This is true iff the amount < 0
	 * 
	 * Deposits to a bank account (amount > 0) should appear at the debit side.
	 * Withdrawals to a bank account (amount < 0) should appear at the credit side.
	 */
	public function isCredited() {
		return $this->bedrag < 0;
	}
	
	/**
	 * Returns the post where this transaction is booked.
	 * 
	 * @returns Post if the transactions is booked onto a single post (i.e. is not splitted)
	 * @see isSplitted()
	 */
	public function getPost() {
		// If the post has not been booked, or is splitted, don't return anything
		if( !$this->ingedeeld || $this->isSplitted() ) {
			return null;
		}
		
		// If the transaction has been booked, at least two transaction lines are present
		// The first one represents the actual trasnaction (i.e. the deposit or withdrawal to the account)
		// The second line represents the post for the transaction
		return $this->Statement->StatementLines[1]->Post;
	}
	
	/**
	 * Accessor for amount
	 */
	public function getBedragAttribute($value) {
		return round($value / 100, 2);
	}
	
	/**
	 * Mutator for amount
	 */
	public function setBedragAttribute($amount) {
		$this->attributes['bedrag' ] = $amount * 100;
	}
	
	/**
	 * Scope to filter transactions to be booked on a specific post
	 * @param unknown $postId
	 */
	public function scopeBookedOnPost($query, $postId) {
		return $query->whereExists(function ($subQuery) use ($postId) {
             $subQuery->select(DB::raw(1))
             		->from('boeking_delen')
             		->join('boekingen', 'boekingen.id', '=', 'boeking_delen.boeking_id')
             		->whereRaw('boekingen.transactie_id = transacties.id')
             		->where('boeking_delen.post_id', $postId);
        });
	}
	

	/**
	 * Create a unique hash for this transaction, to prevent importing duplicates
	 */
	public function createHash() {
		return sha1( $this->rekening_id . "-" . $this->datum . $this->bedrag . $this->omschrijving );
	}
}
