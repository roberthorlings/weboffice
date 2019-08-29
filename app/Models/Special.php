<?php

namespace Weboffice\Models;

use Illuminate\Database\Eloquent\Model;

class Special extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'specials';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'statement_description', 'post_id'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['created_at', 'updated_at'];

    public function Post()
    {
        return $this->belongsTo('\Weboffice\Models\Post', 'post_id');
    }

}
