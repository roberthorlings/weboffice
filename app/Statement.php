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

    public function Transaction()
    {
        return $this->belongsTo('\Weboffice\Transaction', 'transactie_id');
    }    	
    public function Asset()
    {
        return $this->belongsTo('\Weboffice\Asset', 'activum_id');
    }    	

}
