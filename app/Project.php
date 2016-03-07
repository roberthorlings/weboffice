<?php

namespace Weboffice;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'projecten';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['naam', 'opmerkingen', 'status', 'uurtarief', 'relatie_id', 'post_id'];

    public function Relation()
    {
        return $this->belongsTo('\Weboffice\Relation', 'relatie_id');
    }    	
    public function Post()
    {
        return $this->belongsTo('\Weboffice\Post', 'post_id');
    }    	

}
