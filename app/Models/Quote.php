<?php

namespace Weboffice\Models;

use Illuminate\Database\Eloquent\Model;
use AppConfig;
use Illuminate\Support\Collection;

class Quote extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'offertes';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['offertenummer', 'versie', 'titel', 'totaalbedrag', 'datum', 'vervaldatum', 'definitief', 'relatie_id', 'project_id'];

    protected $dates = [ 'datum', 'vervaldatum' ];
    
    public function Relation()
    {
        return $this->belongsTo('\Weboffice\Models\Relation', 'relatie_id');
    }    	
    public function Project()
    {
        return $this->belongsTo('\Weboffice\Models\Project', 'project_id');
    }    	

    public function QuoteLines()
    {
    	return $this->hasMany('\Weboffice\Models\QuoteLine', 'offerte_id');
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
    		case 'vervaldatum':
    			return $this->{$field} ? $this->{$field}->format('Y-m-d') : null;
    		default:
    			return $this->{$field};
    	}
    }

    /**
     * Handle updating an quote line, as edited by the user.
     * Only lines with a title
     *
     * @param unknown $id			Existing ID for the invoice line. If none is given, a new one will be created
     * @param string  $title		Title for the paragraph
     * @param string  $contents		Contents of the paragraph
     * @return QuoteLine			The QuoteLine object or null if no proper data was specified.
     */
    public function updateLine($id, $title, $contents) {
    	$line = null;
    
    	// If ID is specified, reuse existing line
    	if($id) {
    		$line = QuoteLine::find($id);
    	}
    
    	// If no title is specified, don't store anything (or delete existing)
    	if( !$title || !$contents ) {
    		if($line && $line->id)
    			$line->delete();
    
    		return null;
    	}
    
    	// If no or invalid id was specified, create a new line
    	if(!$line) {
    		$line = new QuoteLine();
    	}
    
    	// Update line properites
    	$line->titel = $title;
    	$line->inhoud = $contents;
    
    	// Save the line itself
    	$this->QuoteLines()->save($line);
    
    	return $line;
    }
    
    /**
     * Adds a new quote line
     * Only lines with a title are added
     *
     * @param string  $title		Title for the paragraph
     * @param string  $contents		Contents of the paragraph
     * @return QuoteLine			The QuoteLine object or null if no proper data was specified.
     */
    public function addLine($title, $contents) {
    	return $this->updateLine(null, $title, $contents);
    }
    
    /**
     * Returns a list of all quote lines to be shown in the quote
     * 
     * This includes default lines with expiry date and applicable terms 
     */
    public function getAllLines() {
    	// Create a list of titles of explicitly added lines
    	$titles = $this->QuoteLines->map(function($line) { return $line->titel; })->all();
    	
    	// Add default lines to all quote lines but only the 
    	// lines for which we don't have an explicit line
    	return array_merge(
    		$this->QuoteLines->all(),
    	 	$this->getDefaultLines()->filter(function($line) use($titles) {
    	 		return !in_array($line->titel, $titles);
    	 	})->all()
    	 );
    }
    
    /**
     * Returns a list of default quote lines
     * @return Collection
     */
    protected function getDefaultLines() {
    	return collect([
    		new QuoteLine([
    			'titel' 	=> 'Geldigheidsduur', 
    			'inhoud' 	=> "Deze offerte is geldig tot " . $this->vervaldatum->format( 'd-m-Y' ) . 
    							". Indien u akkoord gaat met deze offerte, wil ik u verzoeken deze offerte " . 
    							"ondertekend terug te sturen naar bovenstaand adres of per e-mail naar robert@isdat.nl."
    		]),	
    		new QuoteLine([
    			'titel' 	=> 'Voorwaarden', 
    			'inhoud' 	=> "Op deze offerte zijn onze algemene voorwaarden van toepassing. Wij hebben een " . 
    							"exemplaar van de algemene voorwaarden toegevoegd."
    		]),	
    	]);
    }
    
    /**
     * Returns the next available number for an quote
     */
    public static function nextNumber() {
    	return sprintf( AppConfig::get('offerteNummerFormat'), AppConfig::get('offerteNummer') + 1 );
    }
    
    /**
     * Returns the next quote version for the given quote number
     * @param unknown $quoteNumber
     */
    public static function nextVersionNumber($quoteNumber) {
    	$current = Quote::where('offertenummer', $quoteNumber)->max('versie');
    
    	if( $current ) {
    		return $current + 1;
    	} else {
    		return 1;
    	}
    }
        
        
}
