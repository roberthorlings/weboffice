<?php

namespace Weboffice\Http\Controllers;

use Weboffice\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Weboffice\Models\Finance\VATStatement;
use Laracasts\Flash\Flash;
use Weboffice\Support\Timespan;
use Session;
use Weboffice\Models\Finance\ProfitAndLossStatement;
use Weboffice\Models\Finance\Balance;
use Weboffice\Models\Finance\Ledgers;
use Weboffice\Models\Statement;
use Weboffice\Models\Post;
use Weboffice\Models\Saldo;
use Weboffice\Models\Finance\VATOverview;
use Weboffice\Models\Asset;

class ExportController extends Controller {
	
	/**
	 * Shows the balance
	 *
	 * @return Response
	 */
	public function index(Request $request) {
		$availableTypes = $this->getAvailableTypes ();
		
		// Determine default period
		$start = Session::get ( 'start' );
		$end = Session::get ( 'end' );
		
		// Determine which years to be shown
		$startYear = Session::get ( 'first' )->year;
		$endYear = Carbon::now ()->year;
		
		return view ( 'export.index', compact ( 'availableTypes', 'start', 'end', 'startYear', 'endYear' ) );
	}
	
	/**
	 * Export data to PDF
	 * 
	 * @param Request $request        	
	 */
	public function export(Request $request) {
		$start = new Carbon ( $request->input ( 'start', Carbon::now ()->subMonth ()->firstOfQuarter () ) );
		$end = new Carbon ( $request->input ( 'end', $start->copy ()->lastOfQuarter () ) );
		
		// Add optional data
		$options = $request->input ( 'option' );
		$data = $this->getMultiTypeData ( $options, $start, $end );
		
		$filename = 'export-weboffice.pdf';
		return response ()->view ( 'export.pdf', compact ( 'data', 'filename', 'start', 'end' ) )->header ( 'Content-Type', 'application/pdf' );
	}
	
	/**
	 * Create year overview to PDF
	 * 
	 * @param Request $request        	
	 */
	public function year($year, Request $request) {
		$start = Carbon::create ( $year, 1, 1, 0, 0, 0 );
		$end = $start->copy ()->endOfYear ();
		
		// Add data for the current year
		$data = $this->getMultiTypeData ( [ 
				'balance' => 1,
				'p-and-l' => 1,
				'saldos' => 1,
				'vat' => 1,
				'assets' => 1 
		], $start, $end );
		
		// Group saldos
		$data ['grouped-saldos'] = $this->groupSaldosByPost ( $data ['saldos'] );
		
		// Add data for the previous year
		$previousYearStart = $start->copy ()->subYear ( 1 );
		$previousYearEnd = $previousYearStart->copy ()->endOfYear ();
		$previousYearProfitAndLoss = $this->getData ( 'p-and-l', $previousYearStart, $previousYearEnd );
		
		$filename = 'year-' . $year . '.pdf';
		return response ()->view ( 'export.year', compact ( 'data', 'previousYearProfitAndLoss', 'filename', 'start', 'end' ) )->header ( 'Content-Type', 'application/pdf' );
	}
	protected function getAvailableTypes() {
		return [ 
				'statements' => 'Statements',
				'ledgers' => 'Ledgers',
				'balance' => 'Balance',
				'p-and-l' => 'Profit and Loss statement',
				'saldos' => 'Amounts due' 
		];
	}
	protected function getMultiTypeData($options, Carbon $start, Carbon $end) {
		$data = [ ];
		foreach ( $options as $type => $value ) {
			$data [$type] = $this->getData ( $type, $start, $end );
		}
		
		return $data;
	}
	protected function getData($type, Carbon $start, Carbon $end) {
		switch ($type) {
			case 'statements' :
				return $this->getStatements ( $start, $end );
			case 'ledgers' :
				return $this->getLedgers ( $start, $end );
			case 'balance' :
				return $this->getBalance ( $start, $end );
			case 'p-and-l' :
				return $this->getProfitAndLoss ( $start, $end );
			case 'saldos' :
				return $this->getSaldos ( $start, $end );
			
			// Only relevant for yearly overviews
			case 'vat' :
				return $this->getVAT ( $start, $end );
			case 'assets' :
				return $this->getAssets ( $start, $end );
		}
	}
	protected function getStatements(Carbon $start, Carbon $end) {
		$query = Statement::with ( [ 
				'StatementLines' => function ($q) {
					$q->orderBy ( 'credit' );
				},
				'StatementLines.Post' 
		] )->orderBy ( 'datum', 'asc' );
		
		// Apply filtering on date
		$query->where ( 'datum', '>=', $start );
		$query->where ( 'datum', '<=', $end );
		
		return $query->get ();
	}
	protected function getLedgers(Carbon $start, Carbon $end) {
		$posts = Post::orderBy ( 'nummer' )->get ();
		return new Ledgers ( $start, $end, $posts );
	}
	protected function getBalance(Carbon $start, Carbon $end) {
		return [ 
				'start' => new Balance ( $start->copy ()->subDay () ),
				'end' => new Balance ( $end ) 
		];
	}
	protected function getProfitAndLoss(Carbon $start, Carbon $end) {
		return new ProfitAndLossStatement ( $start, $end );
	}
	protected function getSaldos(Carbon $start, Carbon $end) {
		$query = Saldo::openOn ( $end );
		$query->with ( [ 
				'StatementLines' => function ($query) use($end) {
					$query->join ( 'boekingen', 'boekingen.id', '=', 'boeking_delen.boeking_id' );
					return $query->where ( 'datum', '<=', $end );
				} 
		] );
		return $query->get ();
	}
	protected function groupSaldosByPost($saldos) {
		$grouped = [ ];
		
		foreach ( $saldos as $saldo ) {
			foreach ( $saldo->StatementLines as $line ) {
				if (! array_key_exists ( $line->post_id, $grouped ))
					$grouped [$line->post_id] = [ 
							'post' => $line->Post,
							'total' => 0,
							'saldos' => [ ] 
					];
				
				$grouped [$line->post_id] ['saldos'] [$saldo->id] = $saldo;
				$grouped [$line->post_id] ['total'] += $line->getSignedAmount ();
			}
		}
		
		return $grouped;
	}
	protected function getVAT(Carbon $start, Carbon $end) {
		// VAT is split per quarter, so compute all quarters
		$vatOverviews = [ ];
		
		$quarterStart = $start->copy ()->firstOfQuarter ();
		
		while ( $quarterStart->lte ( $end ) ) {
			$quarterEnd = $quarterStart->copy ()->lastOfQuarter ();
			$vatOverviews [] = new VATOverview ( $quarterStart, $quarterEnd );
			
			// Move to the next quarter
			$quarterStart = $quarterStart->copy ()->addMonths ( 3 );
		}
		
		return $vatOverviews;
	}
	protected function getAssets(Carbon $start, Carbon $end) {
		return Asset::relevantBetween ( $start, $end )->orderBy ( 'aanschafdatum' )->get ();
	}
}
