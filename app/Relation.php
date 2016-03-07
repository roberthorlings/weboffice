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

    const TYPE_ACTIVE_CUSTOMER = 0;
    const TYPE_INACTIVE_CUSTOMER = 1;
    const TYPE_SUPPLIER = 2;
    const TYPE_OTHER = 3;
	
	public function projects() {
		return $this->hasMany('Weboffice\Project', 'relatie_id');
	}

}
