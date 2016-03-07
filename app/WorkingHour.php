<?php

namespace Weboffice;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class WorkingHour extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'werktijden';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['datum', 'begintijd', 'eindtijd', 'opmerkingen', 'kilometers', 'pauze', 'relatie_id', 'project_id'];
    
    /**
     * Make sure the date is returned as Carbon object
     * @var unknown
     */
    protected $dates = [ 'datum' ];

    public function relatie() {
    	return $this->belongsTo('Weboffice\Relation', 'relatie_id');
    }
    
    public function project() {
    	return $this->belongsTo('Weboffice\Project', 'project_id');
    }
    
    /**
     * Returns a description for the current working hour registration
     * @return string
     */
    public function getDescriptionAttribute()
    {	
    	return $this->relatie->bedrijfsnaam . " - " . $this->begintijd->format( "d-m-Y H:i" );
    }
    
    /**
     * Get the start time as carbon object
     *
     * @param  string  $value
     * @return Carbon
     */
    public function getBegintijdAttribute($value)
    {
    	return $this->timeAsCarbon($value);
    }    
    
    /**
     * Get the end time as carbon object
     *
     * @param  string  $value
     * @return Carbon
     */
    public function getEindtijdAttribute($value)
    {
    	return $this->timeAsCarbon($value);
    }

    /**
     * Mutator for the date itself
     *
     * @param  string  $value
     * @return Carbon
     */
    public function setDatumAttribute($value)
    {
    	$this->setAttributeFromDate($value, 'datum');
    }
    
    /**
     * Mutator for the start time
     *
     * @param  string  $value
     * @return Carbon
     */
    public function setBegintijdAttribute($value)
    {
    	$this->setAttributeFromTime($value, 'begintijd');
    }
    
    /**
     * Mutator for end time 
     *
     * @param  string  $value
     * @return Carbon
     */
    public function setEindtijdAttribute($value)
    {
    	$this->setAttributeFromTime($value, 'eindtijd');
    }    

    /**
     * Returns the duration for the current registration, excluding break time
     */
    public function getDurationAttribute() {
    	$break = $this->pauze ?: 0;
    	return $this->eindtijd->subMinutes($break)->diff($this->begintijd);
    }
    
    /**
     * Get a specific attribute for use in forms. Dates and times have to be formatted properly
     *
     * @return string
     */
    public function getFormValue($field)
    {
    	switch($field) {
    		case 'datum':
    			return $this->datum->format('dmy');
    		case 'begintijd':
    		case 'eindtijd':
    			return $this->{$field}->format('Hi');
    		case 'relation_project':
    			if( $this->relatie_id && $this->project_id ) {
    				return 'project.' . $this->relatie_id . '.' . $this->project_id;
    			} elseif( $this->relatie_id ) {
    				return 'klant.' . $this->relatie_id;
    			} else {
    				return null;
    			}
    		default:
    			return $this->{$field};
    	}
    }    

    protected function timeAsCarbon($value) {
    	$time = Carbon::createFromFormat('H:i:s', $value);
    	return (new Carbon($this->datum))->setTime($time->hour, $time->minute, $time->second);
    }

    protected function setAttributeFromDate($value, $field) {
    	// Times are set in 'dm' or 'dmy' format (e.g. 010216 or 2702), 
    	// but should be stored properly in the database
    	if(is_object($value) && $value instanceof Carbon) {
    		$this->attributes[$field] = $value->format('Y-m-d');
    	} else if(is_string($value) && strlen($value) >= 4 ) {
    		// Parse dm and dmY formats
    		$cleaned = preg_replace("/[^0-9]/", "", $value);
    		 
    		if(trim($cleaned) == "") {
    			throw new \InvalidArgumentException("Invalid argument given for date field");
    		}
    		
    		$day = substr($cleaned, 0, 2);
    		$month = substr( $cleaned, 2, 2);
    		
    		if( strlen($cleaned) > 6)
	    		$year = substr($cleaned,4);
    		elseif( strlen($cleaned) > 4)
	    		$year = '20' . substr($cleaned,4);
    		else
    			$year = 0;
   			 
   			// Create a carbon object to do proper formatting
   			$time = Carbon::now()->day($day)->month($month);
   			if($year) {
   				$time->year($year);
   			}
   			
   			$this->attributes[$field] = $time->format('Y-m-d');
    	} else {
    		throw new \InvalidArgumentException( "Invalid argument given for date field" );
    	}
    }
    
    protected function setAttributeFromTime($value, $field) {
    	// Times are set in 'Hi' format (e.g. 2100), but should be stored properly in the database
    	if(is_object($value) && $value instanceof Carbon) {
    		$this->attributes[$field] = $value->format('H:i:s');
    	} else if(is_string($value) && strlen($value) >= 3 ) {
    		// Parse Hi or H:i formats
    		$cleaned = preg_replace("/[^0-9]/", "", $value);
    	
    		if(trim($cleaned) == "") {
    			throw new \InvalidArgumentException("Invalid argument given for time field");
    		}
    	
    			// Last two numbers are minutes, the rest is hours
    			$minutes = substr($cleaned, -2);
    			$hours = substr($cleaned, 0, -2);
    	
    			// Create a time object to do proper formatting
    			$time = Carbon::now()->hour($hours)->minute($minutes)->second(0);
    			$this->attributes[$field] = $time->format('H:i:s');
    	} else {
    		throw new \InvalidArgumentException( "Invalid argument given for time field" );
    	}
    }

        
}
