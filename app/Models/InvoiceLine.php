<?php

namespace Weboffice\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceLine extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'factuur_delen';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['volgorde', 'omschrijving', 'extra', 'aantal', 'prijs', 'post_id', 'project_id'];
    
    public function Invoice()
    {
        return $this->belongsTo('\Weboffice\Models\Invoice', 'factuur_id');
    }    	
    public function Project()
    {
        return $this->belongsTo('\Weboffice\Models\Project', 'project_id');
    }
    public function Post()
    {
    	return $this->belongsTo('\Weboffice\Models\Post', 'post_id');
    }
    

    /**
     * Accessor for amount
     */
    public function getPrijsAttribute($value) {
    	return round($value) / 100;
    }
    
    /**
     * Mutator for amount
     */
    public function setPrijsAttribute($amount) {
    	$this->attributes['prijs' ] = $amount * 100;
    }
    
}
