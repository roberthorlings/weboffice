<?php

namespace Weboffice\Models;

use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'activa';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['omschrijving', 'aanschafdatum', 'begin_afschrijving', 'bedrag', 'restwaarde', 'afschrijvingsduur', 'afschrijvingsperiode', 'post_investering', 'post_afschrijving', 'post_kosten'];

    public function PostInvestering()
    {
        return $this->belongsTo('\Weboffice\Models\Post', 'post_investering');
    }    	
    public function PostAfschrijving()
    {
        return $this->belongsTo('\Weboffice\Models\Post', 'post_afschrijving');
    }    	
    public function PostKosten()
    {
        return $this->belongsTo('\Weboffice\Models\Post', 'post_kosten');
    }    	

}
