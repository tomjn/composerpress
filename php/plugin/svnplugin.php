<?php

namespace Tomjn\ComposerPress\Plugin;

class SVNPlugin extends Tomjn\ComposerPress\Plugin\WordpressPlugin {
	public function __construct( $path, $plugin_data ) {
		//
	}

	public function get_vcs_type() {
		return 'svn';
	}

	public function get_url() {
		return '';
	}
}