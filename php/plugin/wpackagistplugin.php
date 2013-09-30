<?php

namespace Tomjn\ComposerPress\Plugin;

class WPackagistPlugin extends \Tomjn\ComposerPress\Plugin\WordpressPlugin {

	public function __construct( $path, $plugin_data ) {
		parent::__construct( $path, $plugin_data );
	}

	public function get_name() {
		$name = 'wpackagist/'.sanitize_title( $this->plugin_data['Name'] );
		return $name;
	}

	public function get_version() {
		return $this->plugin_data['Version'];
	}

	public function get_vcs_type() {
		return 'composer';
	}

	public function get_url() {
		return 'http://wpackagist.org';
	}
}