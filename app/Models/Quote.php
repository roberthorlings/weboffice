<?php

namespace Weboffice\Models;

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

    protected $dates = [ 'datum', 'vervaldatum' ];
    
    public function Relation()
    {
        return $this->belongsTo('\Weboffice\Models\Relation', 'relatie_id');
    }    	
    public function Project()
    {
        return $this->belongsTo('\Weboffice\Models\Project', 'project_id');
    }    	

}
