<?php

namespace Tomjn\ComposerPress\Plugin;

class HGPlugin extends \Tomjn\ComposerPress\Plugin\WordpressPlugin {
	private $repository;

	public function __construct( $path, $plugin_data ) {
		parent::__construct( $path, $plugin_data );
	}

	public function get_name() {
		$reponame = 'composerpress/'.sanitize_title( $this->plugin_data['Name'] );
		if ( $this->has_composer() ) {
			$composer = $this->get_composer();
			if ( !empty( $composer->name ) ) {
				return $composer->name;
			}
		}
		return $reponame;
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

	public function is_packagist() {
		return false;
	}

	public function has_vcs() {
		return true;
	}

	public function get_vcs_type() {
		return 'hg';
	}

	public function get_url() {
		$hgrcpath = trailingslashit( $this->path ).'.hg/hgrc';
		$hgrc = parse_ini_file( $hgrcpath );
		$remote_url = $hgrc['default'];

		$remote_url = trim( $remote_url );
		return $remote_url;
	}
}