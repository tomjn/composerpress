<?php

namespace Tomjn\ComposerPress;

class Model {

	public $required;
	public $repos;
	public $homepage;
	public $description;
	public $version;
	public $name;
	public $extra;

	public function __construct() {
		$this->required = array();
		$this->repos = array();
		$this->extra = array();
		$this->homepage = '';
		$this->description = '';
		$this->version = '';
		$this->name = '';
	}

	public function set_homepage( $homepage ) {
		$this->homepage = $homepage;
	}

	public function set_name( $name ) {
		$this->name = $name;
	}

	public function set_version( $version ) {
		$this->version = $version;
	}

	public function set_description( $description ) {
		$this->description = $description;
	}

	public function add_repository( $type, $url ) {
		$this->repos[] = array(
			'type' => $type,
			'url' => $url
		);
	}

	public function add_package_repository( $package ) {
		$this->repos[] = array(
			'type' => 'package',
			'package' => $package
		);
	}

	public function add_extra( $name, $data ) {
		$this->extra[$name] = $data;
	}

	public function required( $package, $version ) {
		$this->required[ $package ] = $version;
	}

	public function to_json() {
		$manifest = array();
		$manifest['name'] = $this->name;
		$manifest['description'] = $this->description;
		$manifest['homepage'] = $this->homepage;
		$manifest['version'] = $this->version;
		$manifest['repositories'] = $this->repos;
		$manifest['extra'] = $this->extra;
		$manifest['require'] = $this->required;
		$json = json_encode( $manifest, ( JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT ) );
		return $json;
	}
}