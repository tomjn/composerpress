<?php

namespace Tomjn\ComposerPress\Plugin;

use Gitonomy\Git\Repository;

class GitPlugin extends \Tomjn\ComposerPress\Plugin\WordpressPlugin {
	private $path;
	private $repository;
	public function __construct( $path, $plugin_data ) {
		$this->path = $path;
		$this->repository = new Repository( $this->path );
	}

	public function get_vcs_type() {
		return 'git';
	}

	public function get_url() {
		// get the repository URL
		$remote_url = $this->repository->run( 'config', array(
			'--get' => 'remote.origin.url'
		) );
		$remote_url = trim( $remote_url );
		return $remote_url;
	}
}