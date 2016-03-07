<?php

namespace Weboffice;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'facturen';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['factuurnummer', 'versie', 'titel', 'referentie', 'totaalbedrag', 'datum', 'definitief', 'uurtje_factuurtje', 'btw', 'creditfactuur', 'oorspronkelijk_factuurnummer', 'oorspronkelijk_datum', 'relatie_id', 'project_id'];

    public function Relation()
    {
        return $this->belongsTo('\Weboffice\Relation', 'relatie_id');
    }    	
    public function Project()
    {
        return $this->belongsTo('\Weboffice\Project', 'project_id');
    }    	

}
