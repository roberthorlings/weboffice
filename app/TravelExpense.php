<?php

namespace Weboffice;

use Illuminate\Database\Eloquent\Model;

class TravelExpense extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'kilometers';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['van_naar', 'bezoekadres', 'km_begin', 'km_eind', 'afstand', 'wijze', 'werktijd_id'];

    public function WorkingHour()
    {
        return $this->belongsTo('\Weboffice\WorkingHour', 'werktijd_id');
    }    	

}
