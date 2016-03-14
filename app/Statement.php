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
    
    /**
     * Handle updating a statement line, as edited by the user. 
     * Only lines with a non-zero amount and proper postId are stored.
     * 
     * @param unknown $id		Existing ID for the statement line. If none is given, a new one will be created
     * @param boolean $credit	Should the line be credited or not. Only used for new lines, otherwise the sign of the original line is used
     * @param unknown $amount	Amount in euros to store.
     * @param unknown $postId	ID of the post to associate the statementline with.
     * @param unknown $saldoId	ID of the saldo to associate the statementline with.
     * @return StatementLine	The StatementLine object or null if no proper data was specified.
     */
    public function updateLine($id, $credit, $amount, $postId, $saldoId = null) {
    	$line = null;
    	
    	// If ID is specified, reuse existing line
    	if($id) {
    		$line = StatementLine::find($id);
    	}

    	// If no amount is specified, don't store anything (or delete existing)
    	if( !$amount || !$postId ) {
    		if($line && $line->id)
    			$line->delete();
    			 
    		return null;
    	}
    	 
    	// If no or invalid id was specified, create a new line
    	if(!$line) {
    		$line = new StatementLine();
    		$line->credit = $credit; 
    	}
    	
    	// Update line properites
    	$line->bedrag = $amount;
    	$line->post_id = $postId;
    	
    	if($saldoId) {
    		$line->saldo_id = $saldoId;
    	}
    	 
    	// Save the line itself
    	$this->StatementLines()->save($line);
    	 
    	return $line;
    }
    
    /**
     * Adds a new statement line
     * Only lines with a non-zero amount and proper postId are stored.
     *
     * @param boolean $credit	Should the line be credited or not.
     * @param unknown $amount	Amount in euros to store.
     * @param unknown $postId	ID of the post to associate the statementline with.
     * @param unknown $saldoId	ID of the saldo to associate the statementline with.
     * @return StatementLine	The StatementLine object or null if no proper data was specified.
     */
    public function addLine($credit, $amount, $postId, $saldoId = null) {
    	return $this->updateLine(null, $credit, $amount, $postId, $saldoId);
    }    

}
