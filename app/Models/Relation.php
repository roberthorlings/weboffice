<?php

namespace Weboffice\Models;

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
	
	public function Projects() {
		return $this->hasMany('Weboffice\Models\Project', 'relatie_id');
	}

	public function getRelationType() {
		switch($this->type) {
			case self::TYPE_ACTIVE_CUSTOMER: 	return "Customer";
			case self::TYPE_INACTIVE_CUSTOMER: 	return "Customer";
			case self::TYPE_SUPPLIER:			return "Supplier";
			case self::TYPE_OTHER:				
			default:							return "Other";
		}
	}
	
}
