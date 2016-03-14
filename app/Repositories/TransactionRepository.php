<?php
namespace Weboffice\Repositories;

use Weboffice\Post;
use Weboffice\Statement;

class TransactionRepository {

	/**
	 * Updates (or creates) the statement belonging to this transaction
	 * @param unknown $transaction
	 * @param unknown $description
	 * @return Weboffice\Statement The updated statement belonging to this transaction
	 */
	public function updateStatement($transaction, $description) {
		// If there is no statement yet, add it, otherwise reuse the existing object
		if( !$transaction->Statement ) {
			// Mark transaction as being booked
			$transaction->ingedeeld = 1;
			$transaction->save();
			 
			$statement = new Statement();
			$statement->omschrijving = $description;
			$statement->datum = $transaction->datum;
			$transaction->Statement()->save($statement);
			 
			// Make sure the add the first statement line
			// Credit if amount of transaction < 0, Debet otherwise
			$statement->addLine($transaction->isCredited(), abs($transaction->bedrag), $transaction->Account->post_id);
		} else {
			$statement = $transaction->Statement;
			$statement->omschrijving = $description;
			$statement->save();
		}
		
		return $statement;
	}
}