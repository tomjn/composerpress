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

	abstract public function is_packagist();

	public function has_composer() {
		$path = trailingslashit( $this->path ).'composer.json';
		return file_exists( $path );
	}

	public function get_composer() {
		$path = trailingslashit( $this->path ).'composer.json';
		$content = file_get_contents( $path );
		$json = json_decode( $content );
		//wp_die( print_r( $json, true ) );
		return $json;
	}

	abstract public function get_vcs_type();
	abstract public function get_url();
}