<?php

namespace Tomjn\ComposerPress\Plugin;

class SVNPlugin extends \Tomjn\ComposerPress\Plugin\WordpressPlugin {
	public function __construct( $path, $plugin_data ) {
		parent::__construct( $path, $plugin_data );
	}

	public function get_name() {
		$reponame = 'composerpress/'.sanitize_title( $this->plugin_data['Name'] );
		return $reponame;
	}

	public function get_version() {
		return $this->plugin_data['Version'];
	}

	public function get_vcs_type() {
		return 'svn';
	}

	public function get_url() {
		$dbpath = trailingslashit( $this->path ).'.svn/wc.db';
		//$database = \sqlite_open( $dbpath, 0666, $error );
		$database = new \PDO( 'sqlite:'.$dbpath );
		 $sql = 'SELECT root FROM REPOSITORY ORDER BY id';
		foreach ( $database->query( $sql ) as $row ) {
			return $row['root'];
		}

		//$info = \svn_info( $this->path );
		return '';
	}
}