<?php

namespace Weboffice\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
	const STATUS_NIETBEGONNEN = 0;
	const STATUS_OFFERTEVERSTUURD = 1;
	const STATUS_ACTIEF = 2;
	const STATUS_FACTUURVERSTUURD = 3;
	const STATUS_AFGEROND = 4;
	const STATUS_OFFERTEAFGEWEZEN = 5;
	
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
        return $this->belongsTo('\Weboffice\Models\Relation', 'relatie_id');
    }    	
    public function Post()
    {
        return $this->belongsTo('\Weboffice\Models\Post', 'post_id');
    }    	

}
