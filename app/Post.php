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

    /**
     * Returns a description for the current post
     * @return string
     */
    public function getDescriptionAttribute()
    {
    	return $this->nummer . " - " . $this->omschrijving;
    }    
    
    /**
     * Association to post type
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function postType()
    {
        return $this->belongsTo('\Weboffice\PostType', 'post_type_id');
    }    	

}
