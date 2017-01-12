<?php

namespace Weboffice\Http\Controllers;

use Weboffice\Http\Controllers\Controller;
use Weboffice\Models\Finance\Ledgers;
use Weboffice\Models\Post;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Session;

class LedgerController extends Controller {
	
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(Request $request) {
		$filter = $this->getFilterFromRequest ( $request );
		
		// Fetch posts to show ledger for
		$query = Post::orderBy ( 'nummer', 'asc' );
		
		if (array_key_exists ( 'post_id', $filter )) {
			$query->where ( 'id', $filter ['post_id'] );
		}
		
		$posts = $query->paginate ( 15 );
		
		// Generate ledgers
		$ledgers = new Ledgers ( $filter ['start'], $filter ['end'], $posts );
		
		return view ( 'ledger.index', compact ( 'posts', 'ledgers', 'filter' ) );
	}
	
	/**
	 *
	 * @param Request $request        	
	 */
	protected function getFilterFromRequest(Request $request) {
		$postId = $request->input ( 'post_id' );
		$start = new Carbon ( $request->input ( 'start', Session::get ( 'start' ) ) );
		$end = new Carbon ( $request->input ( 'end', Session::get ( 'end' ) ) );
		
		// Build filter to use
		$filter = [ 
				'start' => $start,
				'end' => $end 
		];
		
		if ($postId)
			$filter ['post_id'] = $postId;
		
		return $filter;
	}
}
