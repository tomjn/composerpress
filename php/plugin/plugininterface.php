<?php

namespace Tomjn\ComposerPress\Plugin;

interface PluginInterface {
	public function get_name();
	public function get_version();
	public function get_required_version();

	public function is_packagist();

	public function has_composer();

	public function get_composer();

	public function get_vcs_type();
	public function get_url();
	public function get_reference();
}