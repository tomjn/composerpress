<?php

namespace Tomjn\ComposerPress\Plugin;

class WPackagistPlugin extends \Tomjn\ComposerPress\Plugin\WordpressPlugin {

	public function __construct( $path, $plugin_data ) {
		parent::__construct( $path, $plugin_data );
	}

	public function get_name() {
		$reponame = 'wpackagist/'.sanitize_title( $this->plugin_data['Name'] );
		if ( $this->has_composer() ) {
			$composer = $this->get_composer();
			if ( !empty( $composer->name ) ) {
				return $composer->name;
			}
		}
		return $reponame;
	}

	public function get_version() {
		return $this->plugin_data['Version'];
	}

	public function is_packagist() {
		return true;
	}

	public function get_vcs_type() {
		return 'composer';
	}

	public function get_url() {
		return 'http://wpackagist.org';
	}
}