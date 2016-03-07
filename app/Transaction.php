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

    public function Post()
    {
        return $this->belongsTo('\Weboffice\Account', 'rekening_id');
    }    	

}
