<?php

namespace Tomjn\ComposerPress\Plugin;

use Gitonomy\Git\Repository;

class GitPlugin extends \Tomjn\ComposerPress\Plugin\WordpressPlugin {
	private $repository;

	public function __construct( $path, $filepath, $plugin_data ) {
		parent::__construct( $path, $filepath, $plugin_data );
		$this->repository = new Repository( $this->path );
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

	public function get_required_version() {
		$version = '>='.$this->plugin_data['Version'];
		if ( $this->has_composer() ) {
			$composer = $this->get_composer();
			if ( !empty( $composer->version ) ) {
				if (  is_numeric( $composer->version ) )
					return '~'.$composer->version;
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
		return 'git';
	}

	public function get_url() {
		if ( $this->has_composer() ) {
			//wp_die( 'omg composer'.$this->get_name() );
		}
		// get the repository URL
		$remote_url = $this->repository->run( 'config', array(
			'--get' => 'remote.origin.url'
		) );
		$remote_url = trim( $remote_url );
		return $remote_url;
	}
}