<?php

namespace Weboffice\Models;

use Illuminate\Database\Eloquent\Model;
use Weboffice\Support\Facades\AppConfig;

class QuoteLine extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'offerte_delen';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['volgorde', 'titel', 'inhoud'];
    
    public function Quote()
    {
        return $this->belongsTo('\Weboffice\Models\Quote', 'offerte_id');
    }    	
}
