<?php

namespace Weboffice\Models;

use Illuminate\Database\Eloquent\Model;
use AppConfig;
use Carbon\Carbon;

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
    public function InvoiceProjects() 
    {
    	return $this->hasMany('\Weboffice\Models\InvoiceProject', 'invoice_id');
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
    public function updateLine($id, $description, $extra, $number, $price, $postId, $projectId = null) {
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
    	$line->project_id = $projectId;
    	 
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
    public function addLine($description, $extra, $number, $price, $postId, $projectId = null) {
    	return $this->updateLine(null, $description, $extra, $number, $price, $postId, $projectId);
    }
    
    /**
     * Handle updating an invoice project, as edited by the user.
     * Only lines with a proper project id are stored.
     *
     * @param unknown $id			Existing ID for the invoice project. If none is given, a new one will be created
     * @param int	  $projectId	Project to base the invoice on
     * @param Carbon  $start 		Start date for the period to invoice
     * @param Carbon  $end			End date for the period to invoice
     * @param string  $hoursType	Type to include the workinghours
     * @return InvoiceProject		The InvoiceProject object or null if no proper data was specified.
     */
    public function updateProject($id, $projectId, $start, $end, $hoursType) {
    	$project = null;
    
    	// If ID is specified, reuse existing line
    	if($id) {
    		$project = InvoiceProject::find($id);
    	}
    
    	// If no number, price or postId is specified, don't store anything (or delete existing)
    	if( !$projectId ) {
    		if($project && $project->id)
    			$project->delete();
    
    		return null;
    	}
    
    	// If no or invalid id was specified, create a new line
    	if(!$project) {
    		$project = new InvoiceProject();
    	}
    
    	// Update line properites
    	$project->project_id = $projectId;
    	$project->start = $start;
    	$project->end = $end;
    	$project->hours_overview_type = $hoursType;
    
    	// Save the line itself
    	$this->InvoiceProjects()->save($project);
    
    	return $project;
    }
    
    /**
     * Adds a new invoice project
     * Only lines with a proper project id are stored.
     *
     * @param int	  $projectId	Project to base the invoice on
     * @param Carbon  $start 		Start date for the period to invoice
     * @param Carbon  $end			End date for the period to invoice
     * @param string  $hoursType	Type to include the workinghours
     * @return InvoiceProject		The InvoiceProject object or null if no proper data was specified.
     */
    public function addProject($projectId, $start, $end, $hoursType) {
    	return $this->updateProject(null, $projectId, $start, $end, $hoursType);
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
	
	/**
	 * Checks whether hour registration should be shown for this invoice
	 */
	public function shouldShowHours() {
		if(!$this->uurtje_factuurtje)
			return false;
		
		// Only show hours if the is a project to show data for
		foreach( $this->InvoiceProjects as $invoiceProject ) {
			if( $invoiceProject->hours_overview_type != 'none' )
				return true;
		}
		
		return false;
	}
	
	/**
	 * Creates a statement for the given invoice
	 */
	public function saveStatement() {
		$description = 'Factuur ' . $this->factuurnummer;
		
		// Create a new saldo to keep track of the payment for this invoice
		$saldo = Saldo::create(['relatie_id' => $this->relatie_id, 'omschrijving' => $description . ' - ' . $this->titel ]);
		
		// Create the statement itself
		$statement = Statement::create([
			'datum' => $this->datum, 
			'omschrijving' => $description . ' - ' . $this->Relation->bedrijfsnaam, 
			'opmerkingen' => $this->titel,
		]);
		
		/*
		 * Boeking: Factuur uitgeven
		 *     130 Debiteuren
		 * aan 181 Af te dragen BTW
		 * aan 80x Omzet ...
		 * aan 80x Omzet ...
		 */		
		$statement->StatementLines()->save(new StatementLine(['bedrag' => $this->getTotal(), 'credit' => 0, 'post_id' => AppConfig::get('postDebiteuren'), 'saldo_id' => $saldo->id ]));
		
		if($this->btw) {
			$statement->StatementLines()->save(new StatementLine(['bedrag' => $this->getVAT(), 'credit' => 1, 'post_id' => AppConfig::get('postAfTeDragenBTW') ]));
		}
		
		foreach($this->InvoiceLines as $invoiceLine) {
			$statementLine = new StatementLine(['bedrag' => $invoiceLine->getSubtotal(), 'credit' => 1, 'post_id' => $invoiceLine->post_id ]);
			$statement->StatementLines()->save($statementLine);
			
			// Associate statement line with projects
			if($invoiceLine->project_id) 
				$statementLine->associateWithProject($invoiceLine->project_id);
		}
		
		return $statement;
	}
}
