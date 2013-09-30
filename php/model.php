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

	public function add_plugin( \Tomjn\ComposerPress\Plugin\PluginInterface $plugin ) {
		$remote_url = $plugin->get_url();
		$reponame = $plugin->get_name();
		$version = $plugin->get_version();
		$vcstype = $plugin->get_vcs_type();

		if ( !$plugin->is_packagist() ) {
			if ( $plugin->has_composer() ) {
				$this->add_repository( $vcstype, $remote_url );
			} else {
				$package = array(
					'name' => $reponame,
					'version' => $version,
					'type' => 'wordpress-plugin',
					'source' => array(
						'url' => $remote_url,
						'type' => $vcstype
					)
				);

				$this->add_package_repository( $package );
			}
		}
		$this->required( $reponame, $version );
	}

	public function to_json() {
		$manifest = array();
		$manifest['name'] = $this->name;
		$manifest['description'] = $this->description;
		$manifest['homepage'] = $this->homepage;
		$manifest['version'] = $this->version;
		if ( !empty( $this->repos ) ) {
			$manifest['repositories'] = $this->repos;
		}
		if ( !empty( $this->extra ) ) {
			$manifest['extra'] = $this->extra;
		}
		if ( !empty( $this->required ) ) {
			$manifest['require'] = $this->required;
		}
		$json = json_encode( $manifest, ( JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT ) );
		return $json;
	}
}