<?php

namespace Weboffice\Models;

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
        return $this->belongsTo('\Weboffice\Models\Post', 'post_id');
    }    	
    
    public function Statement()
    {
        return $this->belongsTo('\Weboffice\Models\Statement', 'boeking_id');
    }
    
    public function Saldo() 
    {
    	return $this->belongsTo('\Weboffice\Models\Saldo', 'saldo_id');
    }
    
    public function Finances()
    {
    	return $this->hasMany('\Weboffice\Models\ProjectFinance', 'boeking_deel_id');
    }    

    /**
     * Returns the signed amount for this line.
     * 
     * If the line is debet, the amount will be positive.
     * @return number
     */
    public function getSignedAmount() {    	
    	return ( $this->credit ? -1 : 1 ) * $this->bedrag;
    }
    
    /**
     * Accessor for amount
     */
    public function getBedragAttribute($value) {
    	return round($value) / 100;
    }
    
    /**
     * Mutator for amount
     */
    public function setBedragAttribute($amount) {
    	$this->attributes['bedrag' ] = $amount * 100;
    }
    
    /**
     * Associate this statement line with a project
     * @param int $projectId
     */
    public function associateWithProject($projectId) {
    	$this->Finances()->save(new ProjectFinance(['boeking_deel_id' => $this->id, 'project_id' => $projectId]));
    }
}
