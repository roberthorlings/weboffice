<?php

namespace Weboffice;

use Illuminate\Database\Eloquent\Model;

class Configuration extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'configuratie';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'value', 'title', 'type', 'categorie', 'categorie_volgorde'];



}
