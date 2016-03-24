<?php

namespace Weboffice\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'facturen';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['factuurnummer', 'versie', 'titel', 'referentie', 'totaalbedrag', 'datum', 'definitief', 'uurtje_factuurtje', 'btw', 'creditfactuur', 'oorspronkelijk_factuurnummer', 'oorspronkelijk_datum', 'relatie_id', 'project_id'];

    protected $dates = [ 'datum', 'oorspronkelijk_datum' ];
    
    public function Relation()
    {
        return $this->belongsTo('\Weboffice\Models\Relation', 'relatie_id');
    }    	
    public function Project()
    {
        return $this->belongsTo('\Weboffice\Models\Project', 'project_id');
    }    	
    public function InvoiceLines()
    {
    	return $this->hasMany('\Weboffice\Models\InvoiceLine', 'factuur_id');
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
    		case 'oorspronkelijk_datum':
    			return $this->{$field} ? $this->{$field}->format('Y-m-d') : null;
    		default:
    			return $this->{$field};
    	}
    }
    
    
    /**
     * Handle updating an invoice line, as edited by the user.
     * Only lines with a non-zero number and price and proper postId are stored.
     *
     * @param unknown $id			Existing ID for the invoice line. If none is given, a new one will be created
     * @param string  $description	Main description for the line
     * @param string  $extra 		Optional extra information for the current line
     * @param float   $number		Number of items in this line
     * @param float	  $price		Price in euros to store.
     * @param unknown $postId		ID of the post to associate the statementline with.
     * @return InvoiceLine			The InvoiceLine object or null if no proper data was specified.
     */
    public function updateLine($id, $description, $extra, $number, $price, $postId) {
    	$line = null;
    	 
    	// If ID is specified, reuse existing line
    	if($id) {
    		$line = InvoiceLine::find($id);
    	}
    
    	// If no number, price or postId is specified, don't store anything (or delete existing)
    	if( !$number || !$price || !$postId ) {
    		if($line && $line->id)
    			$line->delete();
    
    		return null;
    	}
    
    	// If no or invalid id was specified, create a new line
    	if(!$line) {
    		$line = new InvoiceLine();
    	}
    	 
    	// Update line properites
    	$line->omschrijving = $description;
    	$line->extra = $extra;
    	$line->aantal = $number;
    	$line->prijs = $price;
    	$line->post_id = $postId;
    	 
    	// Save the line itself
    	$this->InvoiceLines()->save($line);
    
    	return $line;
    }
    
    /**
     * Adds a new invoice line
     * Only lines with a non-zero amount and proper postId are stored.
     *
     * @param string  $description	Main description for the line
     * @param string  $extra 		Optional extra information for the current line
     * @param float   $number		Number of items in this line
     * @param float	  $price		Price in euros to store.
     * @param unknown $postId		ID of the post to associate the statementline with.
     * @return InvoiceLine			The InvoiceLine object or null if no proper data was specified.
     */
    public function addLine($description, $extra, $number, $price, $postId) {
    	return $this->updateLine(null, $description, $extra, $number, $price, $postId);
    }
    

}
