<?php

namespace Weboffice\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
	const STATUS_NIETBEGONNEN = 0;
	const STATUS_OFFERTEVERSTUURD = 1;
	const STATUS_ACTIEF = 2;
	const STATUS_FACTUURVERSTUURD = 3;
	const STATUS_AFGEROND = 4;
	const STATUS_OFFERTEAFGEWEZEN = 5;
	
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'projecten';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['naam', 'opmerkingen', 'status', 'uurtarief', 'relatie_id', 'post_id'];

    public function Relation()
    {
        return $this->belongsTo('\Weboffice\Models\Relation', 'relatie_id');
    }    	
    public function Post()
    {
        return $this->belongsTo('\Weboffice\Models\Post', 'post_id');
    }
    public function WorkingHours() 
    {
    	return $this->hasMany('\Weboffice\Models\WorkingHour', 'project_id');
    }
	public function Finances()
	{
		return $this->hasMany('\Weboffice\Models\ProjectFinance', 'project_id');
	}

    /**
     * Accessor for amount
     */
    public function getUurtariefAttribute($value) {
    	return round($value) / 100;
    }
    
    /**
     * Mutator for amount
     */
    public function setUurtariefAttribute($amount) {
    	$this->attributes['uurtarief' ] = $amount * 100;
    }
    
    public function getStatus() {
    	switch($this->status) {
    		case self::STATUS_NIETBEGONNEN: 	return "Not started";
    		case self::STATUS_OFFERTEVERSTUURD:	return "Quote sent";
    		case self::STATUS_ACTIEF:			return "Active";
    		case self::STATUS_FACTUURVERSTUURD:	return "Invoice sent";
    		case self::STATUS_AFGEROND:			return "Finished";
    		case self::STATUS_OFFERTEAFGEWEZEN: return "Quote rejected";
    		default:							return "Unknown";
    	}
    	 
    }

}
