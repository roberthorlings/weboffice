<?php
namespace Weboffice\Repositories;

use Weboffice\Configuration;

class ConfigurationRepository {
	protected $configuration = [];
	
	protected function load() {
		// If data has already been loaded, return
		if( count($this->configuration) > 0 )
			return;
		
		foreach( Configuration::all() as $model ) {
			$this->configuration[$model->name] = $model->value;
		}
	}
	
	public function get($key) {
		$this->load();
		if(array_key_exists($key, $this->configuration)) {
			return $this->configuration[$key];
		} else {
			return null;
		}
	}
}