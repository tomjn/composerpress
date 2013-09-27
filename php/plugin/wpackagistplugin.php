<?php

namespace Tomjn\ComposerPress\Plugin;

class WPackagistPlugin extends \Tomjn\ComposerPress\Plugin\WordpressPlugin {

	private $path;
	private $plugin_data;

	public function __construct( $path, $plugin_data ) {
		$this->path = $path;
		$this->plugin_data = $plugin_data;
	}

	public function get_name() {
		$name = 'wpackagist/'.sanitize_title( $this->plugin_data['Name'] );
		return $name;
	}

	public function get_vcs_type() {
		return 'composer';
	}

	public function get_url() {
		return 'http://wpackagist.org';
	}
}