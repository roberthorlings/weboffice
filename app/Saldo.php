<?php

namespace Weboffice;

use Illuminate\Database\Eloquent\Model;

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
        return $this->belongsTo('\Weboffice\Relation', 'relatie_id');
    }    	

}
