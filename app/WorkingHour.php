<?php

namespace Weboffice;

use Illuminate\Database\Eloquent\Model;

class WorkingHour extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'werktijden';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['datum', 'begintijd', 'eindtijd', 'opmerkingen', 'kilometers', 'pauze'];

    /**
     * Returns a description for the current working hour registration
     * @return string
     */
    public function getDescriptionAttribute()
    {
    	return $this->relatie->bedrijfsnaam . " - " . $this->datum . " " . $this->begintijd;
    }
    
    
    public function relatie() {
    	return $this->belongsTo('Weboffice\Relation', 'relatie_id');
    }
    
    public function project() {
    	return $this->belongsTo('Weboffice\Project', 'project_id');
    }
}
