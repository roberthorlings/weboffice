<?php

namespace Weboffice\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectFinance extends Model
{
	/**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'project_financieen';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['boeking_deel_id', 'project_id'];

    public function StatementLine()
    {
        return $this->belongsTo('\Weboffice\Models\StatementLine', 'boeking_deel_id');
    }    	
    public function Project()
    {
        return $this->belongsTo('\Weboffice\Models\Project', 'project_id');
    }

}
