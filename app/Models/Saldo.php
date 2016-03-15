<?php

namespace Weboffice\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Saldo extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'saldos';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['omschrijving', 'relatie_id'];

    public function Relation()
    {
        return $this->belongsTo('\Weboffice\Models\Relation', 'relatie_id');
    }
    
    /**
     * Associated statement lines
     */
    public function StatementLines() {
    	return $this->hasMany('\Weboffice\Models\StatementLine', 'saldo_id');
    }
    
    /**
     * Returns the amount that is still open for this saldo
     */
    public function getOpenAmount() {
    	$sum = 0;
    	foreach( $this->StatementLines as $line ) {
    		$sum += $line->getSignedAmount();
    	}
    	return $sum;
    }
    
    /**
     * Scope a query to only include open saldos.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOpen($query)
    {
    	return $query->where(DB::raw('0'), '!=', function($subquery) {
    		return $subquery
    			->from('boeking_delen')
    			->where( 'boeking_delen.saldo_id', '=', DB::raw('saldos.id') )
    			->select( DB::raw( 'sum(if(boeking_delen.credit = 0, boeking_delen.bedrag, -boeking_delen.bedrag)) as total' ) );
    	});
    }
    
}
