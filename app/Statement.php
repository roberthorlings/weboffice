<?php

namespace Weboffice;

use Illuminate\Database\Eloquent\Model;

class Statement extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'boekingen';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['datum', 'omschrijving', 'opmerkingen', 'actief', 'transactie_id', 'activum_id'];

    /**
     * Date fields
     * @var unknown
     */
    protected $dates = ['datum'];
    
    /**
     * Default values for attributes.
     * @var unknown
     */
    protected $attributes = [ 'actief' => 1 ];
    
    public function Transaction()
    {
        return $this->belongsTo('\Weboffice\Transaction', 'transactie_id');
    }    	
    
    public function Asset()
    {
        return $this->belongsTo('\Weboffice\Asset', 'activum_id');
    }
    
    public function StatementLines() 
    {
    	return $this->hasMany('\Weboffice\StatementLine', 'boeking_id');
    }
    
    /**
     * Checks whether the current statement is balanced, i.e. the sum of 
     * credited amounts equals the sum of debited amounts
     * 
     * @return boolean
     */
    public function isBalanced() {
    	$sum = ['credit' => 0, 'debet' => 0];
    	foreach( $this->StatementLines as $line ) {
    		$sum[ $line->credit ? 'credit' : 'debet' ] += $line->bedrag;
    	}
    	
    	return abs($sum['credit'] - $sum['debet']) < 0.005;
    }

}
