<?php

namespace Weboffice\Models;

use Illuminate\Database\Eloquent\Model;
use AppConfig;

class Invoice extends Model
{

	/**
	 * Cache variable to hold the subtotal
	 * @var unknown $subTotal
	 */
	protected $subTotal = null;
	
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
    
	/**
	 * Returns the next available number for an invoice
	 */
	public static function nextNumber() {
		return sprintf( AppConfig::get('factuurNummerFormat'), AppConfig::get('factuurNummer') + 1 );
	}
	
	/**
	 * Returns the next invoice version for the given invoice number
	 * @param unknown $invoiceNumber
	 */
	public static function nextVersionNumber($invoiceNumber) {
		$current = Invoice::where('factuurnummer', $invoiceNumber)->max('versie');
		
		if( $current ) {
			return $current + 1;
		} else {
			return 1;
		}
	}
	

	/**
	 * Returns the subtotal (i.e. price * amount) without VAT
	 */
	public function getSubtotal() {
		if( is_null($this->subTotal) ) 
			$this->subTotal = $this->calculateSubTotal();
		
		return $this->subTotal;
	}
	
	/**
	 * Calculates the subtotal for the current invoice
	 */
	protected function calculateSubTotal() {
		return array_sum(
			array_map(
				function($line) { return $line->getSubtotal(); },
				$this->InvoiceLines->all()
			)
		);		
	}
	
	/**
	 * Returns the VAT percentage for this invoice
	 */
	public function getVATPercentage() {
		return $this->btw ? AppConfig::get('btwPercentage') : 0;
	}
	
	/**
	 * Returns the VAT for this line
	 */
	public function getVAT() {
		return round($this->getSubtotal() * ( $this->getVATPercentage() / 100 ), 2);
	}
	
	
	/**
	 * Returns the grand total for this line
	 */
	public function getTotal() {
		return $this->getSubtotal() + $this->getVAT();
	}	
}
