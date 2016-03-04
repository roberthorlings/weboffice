<?php

namespace Weboffice;

use Illuminate\Database\Eloquent\Model;

class PostType extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'post_types';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['type', 'omschrijving', 'balanszijde', 'draagt_bij_aan_resultaat'];

}
