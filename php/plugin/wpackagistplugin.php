<?php

namespace Tomjn\ComposerPress\Plugin;

class WPackagistPlugin extends \Tomjn\ComposerPress\Plugin\WordpressPlugin {

	public function __construct( $path, $filepath, $plugin_data ) {
		parent::__construct( $path, $filepath, $plugin_data );
	}

	public function get_version() {
		$version = $this->plugin_data['Version'];
		if ( $this->has_composer() ) {
			$composer = $this->get_composer();
			if ( !empty( $composer->version ) ) {
				return $composer->version;
			}
		}
		return $version;
	}

	public function get_required_version() {
		$version = '>='.$this->plugin_data['Version'];
		if ( $this->has_composer() ) {
			$composer = $this->get_composer();
			if ( !empty( $composer->version ) ) {
				return $composer->version;
			}
		}
		return $version;
	}

	public function is_packagist() {
		return true;
	}

	public function get_vcs_type() {
		return 'composer';
	}

	public function get_url() {
		return 'https://wpackagist.org';
	}
}
