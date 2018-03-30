<?php

namespace Weboffice\Http\Controllers;

use Carbon\Carbon;
use Weboffice\Http\Controllers\Controller;
use Flash;
use Illuminate\Http\Request;
use Weboffice\Models\Asset;
use Weboffice\Models\Post;
use Weboffice\Models\Relation;
use Weboffice\Models\Statement;
use Weboffice\Models\StatementLine;

class AssetController extends Controller {
	
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index() {
		$asset = Asset::paginate ( 15 );
		
		return view ( 'asset.index', compact ( 'asset' ) );
	}
	
	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create() {
		$lists = [ ];
		$lists ["posten"] = Post::all ()->lists ( "description", "id" );
		
		return view ( 'asset.create', compact ( 'lists' ) );
	}
	
	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Request $request) {
		Asset::create ( $request->all () );
		
		Flash::message ( 'Asset added!' );
		
		return redirect ( 'asset' );
	}
	
	/**
	 * Display the specified resource.
	 *
	 * @param int $id        	
	 *
	 * @return Response
	 */
	public function show($id) {
		$asset = Asset::findOrFail ( $id );
		
		return view ( 'asset.show', compact ( 'asset' ) );
	}
	
	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param int $id        	
	 *
	 * @return Response
	 */
	public function edit($id) {
		$asset = Asset::findOrFail ( $id );
		$lists = [ ];
		$lists ["posten"] = Post::all ()->lists ( "description", "id" );
		
		return view ( 'asset.edit', compact ( 'lists', 'asset' ) );
	}
	
	/**
	 * Update the specified resource in storage.
	 *
	 * @param int $id        	
	 *
	 * @return Response
	 */
	public function update($id, Request $request) {
		$asset = Asset::findOrFail ( $id );
		$asset->update ( $request->all () );
		
		Flash::message ( 'Asset updated!' );
		
		return redirect ( 'asset' );
	}
	
	/**
	 * Remove the specified resource from storage.
	 *
	 * @param int $id        	
	 *
	 * @return Response
	 */
	public function destroy($id) {
		Asset::destroy ( $id );
		
		Flash::message ( 'Asset deleted!' );
		
		return redirect ( 'asset' );
	}

	public function finalizeForm($id) {
        $asset = Asset::findOrFail ( $id );
        return view ( 'asset.finalizeForm', compact ( 'asset') );
    }

    /**
     * Finalizes the amortization for the given asset from a specific date
     * @param $id
     */
	public function finalize($id, Request $request) {
        $asset = Asset::findOrFail ( $id );

        $date = new Carbon($request->input ( 'date' ));
        $remainder = $request->input('remainder', 0);
        $post_id = intval($request->input('post_id'));
        $description = $request->input('description');

        $asset->amortization()->finalize($date, $remainder, $post_id, $description);

        Flash::message ( 'Amortization finalized!' );

        return redirect ( 'asset' );
    }
	
	/**
	 * Show the form for booking statements
	 *
	 * @return Response
	 */
	public function statements($id) {
		$asset = Asset::findOrFail ( $id );
		$relations = Relation::all ()->lists ( "bedrijfsnaam", "id" );
		
		// Create artificial statements
		$investmentStatement = $asset->getNewInvestmentStatement ();
		$amortizationStatement = $asset->getAmortizationStatement ();
		
		return view ( 'asset.statements', compact ( 'relations', 'asset', 'investmentStatement', 'amortizationStatement' ) );
	}
	
	/**
	 * Book one or more statements
	 * 
	 * @param unknown $id        	
	 */
	public function bookStatements($id, Request $request) {
		$asset = Asset::findOrFail ( $id );
		
		// Check whether we have to book the investment
		$investmentBooked = false;
		if ($request->get ( 'book_investment' ) && ! $asset->isInvestmentBooked ()) {
			$invoiceNo = $request->get ( 'invoicenumber' );
			$sellerId = $request->get ( 'seller' );
			
			// Create the statement
			$description = $invoiceNo ? 'Factuur ' . $invoiceNo : '';
			$asset->bookInvestment ( $description );
			
			// TODO: create saldo
			$investmentBooked = true;
		}
		
		// Check whether we have to book the amortization.
		$amortizationBooked = false;
		if ($request->get ( 'book_amortization' ) && ! $asset->amortization ()->isFinished ()) {
			$asset->amortization ()->book ();
			$amortizationBooked = true;
		}
		
		Flash::message ( 'Statements booked for asset ' . $asset->omschrijving . '.' );
		return redirect ( 'asset' );
	}
}
