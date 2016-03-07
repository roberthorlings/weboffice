<?php

namespace Weboffice;

use Illuminate\Database\Eloquent\Model;

class Relation extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'relaties';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['bedrijfsnaam', 'contactpersoon', 'adres', 'postcode', 'plaats', 'land', 'email', 'telefoon', 'fax', 'mobiel', 'website', 'opmerkingen', 'project_count', 'postadres', 'postpostcode', 'postplaats', 'postland', 'type', 'werktijd', 'factuuradres'];



}
