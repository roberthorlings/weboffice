<?php

namespace Weboffice;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'posten';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['nummer', 'omschrijving', 'percentage_aftrekbaar', 'post_type_id'];

    public function postType()
    {
        return $this->belongsTo('\Weboffice\PostType', 'post_type_id');
    }    	

}
