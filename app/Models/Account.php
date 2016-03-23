<?php

namespace Weboffice\Models;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
	const ABN 	= "ABN";
	const ING 	= "ING";
	const RABO 	= "Rabo";
	
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'rekeningen';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['rekeningnummer', 'omschrijving', 'bank', 'post_id', 'saldodatum', 'saldo'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['created_at', 'created_at', 'saldodatum'];
    

    /**
     * Returns a description for the current account
     * @return string
     */
    public function getDescriptionAttribute()
    {
    	return $this->omschrijving . " (" . $this->rekeningnummer . ")";
    }
    
    /**
     * Get the saldodatum for forms, as it is by default returned as datetime
     *
     * @return string
     */
    public function getFormValue($field)
    {
    	switch($field) {
    		case 'saldodatum':
    			return $this->saldodatum->format('Y-m-d');
    		default:
    			return $this->{$field};
    	}
    }    
    
    public function Post()
    {
        return $this->belongsTo('\Weboffice\Models\Post', 'post_id');
    }
    
    /**
     * Returns the formatted account number
     */
    public function getFormattedNumber() {
    	// Check the account number length
    	$lengte = strlen( $this->rekeningnummer );
    		
    	if( $lengte < 5 ) {
    		// No formatting
    		return $this->rekeningnummer ;
    	} elseif( $lengte < 8 ) {
    		// Postbank nummer: xx.xx.xxx
    		return substr( $this->rekeningnummer , 0, 2 ) . "." . substr( $this->rekeningnummer , 2, 2 ) . "." . substr( $this->rekeningnummer , 4 );
    	} elseif( $lengte < 10 ) {
    		// Bankrekeningnummer: xx.xx.xx.xxx
    		return substr( $this->rekeningnummer , 0, 2 ) . "." . substr( $this->rekeningnummer , 2, 2 ) . "." . substr( $this->rekeningnummer , 4, 2 ) . "." . substr( $this->rekeningnummer , 6 );
    	} elseif( $lengte < 18 ) {
    		// Bankrekeningnummer: xx.xx.xxx.xxx
    		return substr( $this->rekeningnummer , 0, 2 ) . "." . substr( $this->rekeningnummer , 2, 2 ) . "." . substr( $this->rekeningnummer , 4, 3 ) . "." . substr( $this->rekeningnummer , 7 );
    	} else {
    		// IBAN: NLxx BANK xxx.xxx.xxxx
    		return substr( $this->rekeningnummer , 0, 4 ) . " " . substr( $this->rekeningnummer , 4, 4 ) . " " . substr( $this->rekeningnummer , 8, 3 ) . "." . substr( $this->rekeningnummer , 11, 3 ) . "." . substr( $this->rekeningnummer , 14 );
    	}    	
    }
    
    /**
     * Parses an IBAN account number and returns the original 
     * @param string $iban
     */
    public static function accountNumberFromIBAN( $iban ) {
    	if( strlen( $iban ) == 18 )
    		return self::removeLeadingZeros( substr( $iban, 8 ) );
    	else
    		return $iban;
    }
    
    /**
     * Helper function to remove leading zeros
     * @see accountNumberFromIBAN()
     */
    public static function removeLeadingZeros( $number ) {
    	return preg_replace( '/^0*/', '', $number );
    }
    
}
