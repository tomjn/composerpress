<?php

namespace Tomjn\ComposerPress\Plugin;

abstract class WordpressPlugin {
	abstract public function __construct( $path, $plugin_data );

	abstract public function get_name();

	abstract public function get_vcs_type();
	abstract public function get_url();
}