<?php

namespace Tomjn\ComposerPress\Plugin;

class WPackagistPlugin extends Tomjn\ComposerPress\Plugin\WordpressPlugin {
	public function __construct( $path, $plugin_data ) {
		//
	}

	public function get_vcs_type() {
		return 'composer';
	}

	public function get_url() {
		return 'http://wpackagist.org';
	}
}