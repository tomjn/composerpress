<?php

namespace Tomjn\ComposerPress\Plugin;

class SVNPlugin extends \Tomjn\ComposerPress\Plugin\WordpressPlugin {
	public function __construct( $path, $filepath, $plugin_data ) {
		parent::__construct( $path, $filepath, $plugin_data );
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

	public function get_vcs_type() {
		return 'svn';
	}

	public function get_url() {
		$dbpath = trailingslashit( $this->path ).'.svn/wc.db';

		$root = '';
		//$database = \sqlite_open( $dbpath, 0666, $error );
		$database = new \PDO( 'sqlite:'.$dbpath );
		$sql = 'SELECT root FROM REPOSITORY ORDER BY id';
		foreach ( $database->query( $sql ) as $row ) {
			$root = trailingslashit( $row['root'] );
			break;
		}

		//$info = \svn_info( $this->path );
		return $root;
	}

	public function get_reference() {
		$dbpath = trailingslashit( $this->path ).'.svn/wc.db';

		//$database = \sqlite_open( $dbpath, 0666, $error );
		$database = new \PDO( 'sqlite:'.$dbpath );

		$key = substr( $this->filepath, strlen( $this->path ) );

		$sql = 'SELECT * FROM NODES WHERE local_relpath = "'.$key.'" ORDER BY wc_id';
		foreach ( $database->query( $sql ) as $row ) {
			$rel = $row['repos_path'];
		}

		//$info = \svn_info( $this->path );
		return $rel;
	}
}