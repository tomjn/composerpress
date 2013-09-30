<?php

namespace Tomjn\ComposerPress\Plugin;

abstract class WordpressPlugin {
	protected $path;
	protected $plugin_data;

	public function __construct( $path, $plugin_data ){
		$this->path = $path;
		$this->plugin_data = $plugin_data;
	}

	abstract public function get_name();
	abstract public function get_version();

	public function has_composer() {
		$path = trailingslashit( $this->path ).'composer.json';
		return file_exists( $path );
	}

	abstract public function get_vcs_type();
	abstract public function get_url();
}