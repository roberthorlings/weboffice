<?php

namespace Weboffice\Models;

use Baum\Node;
use Illuminate\Database\Eloquent\Model;

class Post extends Node
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
     * Column name for the right index.
     *
     * @var string
     */
    protected $rightColumn = 'rght';
    
    /**
     * Column to perform the default sorting
     *
     * @var string
     */
    protected $orderColumn = 'nummer';
    
    
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
        return $this->belongsTo('\Weboffice\Models\PostType', 'post_type_id');
    }
    
    /**
     * Checks whether the current post is the leftmost in its subtree
     * @return boolean
     */
    public function isFirstInSubtree() {
    	return $this->getLeftSibling() == null;
    }

    /**
     * Checks whether the current post is the rightmost in its subtree
     * @return boolean
     */
    public function isLastInSubtree() {
    	return $this->getRightSibling() == null;
    }
    
}
