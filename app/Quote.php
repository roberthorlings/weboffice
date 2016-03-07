<?php

namespace Weboffice;

use Illuminate\Database\Eloquent\Model;

class Quote extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'offertes';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['offertenummer', 'versie', 'titel', 'totaalbedrag', 'datum', 'vervaldatum', 'definitief', 'relatie_id', 'project_id'];

    public function Relation()
    {
        return $this->belongsTo('\Weboffice\Relation', 'relatie_id');
    }    	
    public function Project()
    {
        return $this->belongsTo('\Weboffice\Project', 'project_id');
    }    	

}
