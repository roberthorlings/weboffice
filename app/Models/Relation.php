<?php

namespace Weboffice\Models;

use Illuminate\Database\Eloquent\Model;
use Weboffice\Models\Stats\RevenueAndWorkingHourStats;

class Relation extends Model
{
	use RevenueAndWorkingHourStats;
	
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
	
	public function StatementLines() {
		$relationId = $this->id;
		return StatementLine::whereHas('projects', function ($query) use($relationId) {
			$query->where('relatie_id', $relationId);
		});		
	}
	
	public function WorkingHours()
	{
		return $this->hasMany('\Weboffice\Models\WorkingHour', 'relatie_id');
	}
	
	/**
	 * Scope a query to only include active customers
	 *
	 * @return \Illuminate\Database\Eloquent\Builder
	 */
	public function scopeActive($query)
	{
		return $query->where('type', self::TYPE_ACTIVE_CUSTOMER );
	}

	/**
	 * Returns a human readable description of the relation type
	 */
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
