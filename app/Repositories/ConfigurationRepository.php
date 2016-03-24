<?php
namespace Weboffice\Repositories;

use Weboffice\Models\Configuration;

class ConfigurationRepository {
	protected $configuration = [];
	
	protected function load() {
		// If data has already been loaded, return
		if($this->isLoaded())
			return;
		
		foreach( Configuration::all() as $model ) {
			$this->configuration[$model->name] = $model->value;
		}
	}
	
	/**
	 * @return boolean
	 */
	protected function isLoaded() {
		return count($this->configuration) > 0;
	}
	
	/**
	 * Returns the value
	 * @param unknown $key
	 */
	public function get($key) {
		$this->load();
		if(array_key_exists($key, $this->configuration)) {
			return $this->configuration[$key];
		} else {
			return null;
		}
	}
	
	/**
	 * Sets a new value
	 * @param string $key
	 * @param string $value
	 */
	public function set($key, $value) {
		if( $this->isLoaded() ) {
			$this->configuration[$key] = $value;
		}
		
		// Update or insert
		$numUpdated = Configuration::where('name', $key)->update(['value' => $value]);
		
		if($numUpdated == 0) {
			Configuration::create(['name' => $key, 'value' => $value]);
		}
	}	
}