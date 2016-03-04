<?php

namespace Weboffice;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'rekeningen';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['rekeningnummer', 'omschrijving', 'bank', 'post_id', 'saldodatum', 'saldo'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['created_at', 'created_at', 'saldodatum'];
    
    /**
     * Get the saldodatum for forms, as it is by default returned as datetime
     *
     * @return string
     */
    public function getFormValue($field)
    {
    	switch($field) {
    		case 'saldodatum':
    			return $this->saldodatum->format('Y-m-d');
    		default:
    			return $this->{$field};
    	}
    }    
    
    public function Post()
    {
        return $this->belongsTo('\Weboffice\Post', 'post_id');
    }    	

}
