<?php

namespace Weboffice;

use Illuminate\Database\Eloquent\Model;

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
        return $this->belongsTo('\Weboffice\Account', 'rekening_id');
    }
    
    public function Statement()
    {
    	return $this->hasOne('\Weboffice\Statement', 'transactie_id');
    }
    
	/**
	 * Checks whether the current transaction is being split
	 * @return boolean
	 */
	public function isSplitted() {
		return $this->Statement && count($this->Statement->StatementLines) > 2;
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
	
    
}
