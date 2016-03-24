<?php

namespace Weboffice\Models;

use Illuminate\Database\Eloquent\Model;
use Weboffice\Support\Facades\AppConfig;

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
    
    /**
     * Returns the subtotal (i.e. price * amount) without VAT
     */
    public function getSubtotal() {
    	return round($this->prijs * $this->aantal, 2);
    }
    

    /**
     * Returns the VAT for this line
     */
    public function getVAT() {
   		return round($this->getSubtotal() * ( AppConfig::get('btwPercentage') / 100 ), 2);
    }
    

    /**
     * Returns the grand total for this line
     */
    public function getTotal() {
    	 return $this->getSubtotal() + $this->getVAT();
    }
}
