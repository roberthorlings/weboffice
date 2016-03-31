<?php

namespace Weboffice\Http\Middleware;

use Closure;

class Menu
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
    	\Menu::make('MainMenu', function($menu){
    	
    		$menu->add('Working Hours', [ 'url' => 'workinghours', 'icon' => 'fa-clock-o' ])->active('workinghours/*');
    		
    		$finances = $menu->add('Finances', [ 'url' => '#', 'icon' => 'fa-eur' ]);
	    		$finances->add('Transactions', [ 'url' => 'transaction', 'icon' => 'fa-eur' ])->active('transaction/(?!import).*');
	    		$finances->add('Statements', [ 'url' => 'statement', 'icon' => 'fa-eur' ])->active('statement/(?!create/).*');
				$finances->add('Results', [ 'url' => 'results', 'icon' => 'fa-eur' ])->active('results/*');
				$finances->add('Balance', [ 'url' => 'balance', 'icon' => 'fa-eur' ])->active('balance/*');
				$finances->add('Amounts due', [ 'url' => 'saldo', 'icon' => 'fa-eur' ])->active('saldo/*');
				
				$finances_more = $finances->add('More', [ 'url' => '#' ])->active(false);
					$finances_more->add('Assets', [ 'url' => 'asset', 'icon' => 'fa-eur' ])->active('asset/*');
					$finances_more->add('Ledgers', [ 'url' => 'ledger', 'icon' => 'fa-eur' ])->active('ledger/*');
					$finances_more->add('Travel registration', [ 'url' => 'travelexpense', 'icon' => 'fa-eur' ])->active('travelexpense/*');
					$finances_more->add('Import', [ 'url' => 'transaction/import', 'icon' => 'fa-eur' ])->active('transaction/import');
			
			$documents = $menu->add('Documents', [ 'url' => '#', 'icon' => 'fa-file-text-o' ]);
				$documents->add('Invoices', [ 'url' => 'invoice', 'icon' => 'fa-file-text-o' ])->active('invoice/(?!create).*');
				$documents->add('Quotes', [ 'url' => 'quote', 'icon' => 'fa-file-text-o' ])->active('quote/(?!create).*');
				$documents->add('Create project invoice', [ 'url' => 'invoice/create/project', 'icon' => 'fa-file-text-o' ])->active('invoice/create/project');
				$documents->add('Create normal invoice', [ 'url' => 'invoice/create', 'icon' => 'fa-file-text-o' ])->active('invoice/create(?!/project)(?!/creditnote)');
				
				$documents_more = $documents->add('More', [ 'url' => '#' ] );
					$documents_more->add('Add quote', [ 'url' => 'quote/create', 'icon' => 'fa-file-text-o' ])->active('quote/create');
					$documents_more->add('Add credit note', [ 'url' => 'invoice/create/creditnote', 'icon' => 'fa-file-text-o' ])->active('invoice/create/creditnote');
					$documents_more->add('Receive invoice', [ 'route' => 'statement.incoming-invoice', 'icon' => 'fa-eur' ]);
					$documents_more->add('Report costs', [ 'route' => 'statement.cost-declaration', 'icon' => 'fa-eur' ]);
						
			$crm = $menu->add('CRM', [ 'url' => '#', 'icon' => 'fa-file-text-o' ]);
				$crm->add('Relations', [ 'url' => 'relation', 'icon' => 'fa-file-text-o' ])->active('relation');
				$crm->add('Projects', [ 'url' => 'project', 'icon' => 'fa-file-text-o' ])->active('project');
				
			$other = $menu->add('Other', [ 'url' => '#', 'icon' => 'fa-ellipsis-v' ]);
				$other->add('Export', [ 'url' => 'export', 'icon' => 'fa-file-text-o' ]);
				$other->add('Overview 2015', [ 'url' => 'export/year/2015', 'icon' => 'fa-file-text-o' ]);
				
    	});
    	
        return $next($request);
    }
}
