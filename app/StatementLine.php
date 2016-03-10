<?php

namespace Weboffice;

use Illuminate\Database\Eloquent\Model;

class StatementLine extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'boeking_delen';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['bedrag', 'credit', 'post_id', 'boeking_id', 'saldo_id'];

    public function Post()
    {
        return $this->belongsTo('\Weboffice\Post', 'post_id');
    }    	
    
    public function Statement()
    {
        return $this->belongsTo('\Weboffice\Statement', 'boeking_id');
    }
    
    public function Saldo() 
    {
    	return $this->belongsTo('\Weboffice\Saldo', 'saldo_id');
    }

    /**
     * Returns the signed amount for this line.
     * 
     * If the line is debet, the amount will be negative.
     * @return number
     */
    public function getSignedAmount() {    	
    	return ( $this->credit ? 1 : -1 ) * $this->bedrag;
    }
    
}
