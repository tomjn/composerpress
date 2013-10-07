<?php

namespace Tomjn\ComposerPress;

class ComposerPress {

	private $model = null;
	public function __construct( Model $model ) {
		$this->model = $model;
	}

	public function run() {
		//
	}

	public function fill_model() {
		$plugins = get_plugins();

		$this->model->required( 'johnpbloch/wordpress', '>='.get_bloginfo( 'version' ) );
		$this->model->required( 'php', '>=5.3.2' );

		$this->model->set_name( 'wpsite/'.sanitize_title( get_bloginfo( 'name' ) ) );
		$this->model->set_homepage( home_url() );
		$description = get_bloginfo( 'description' );
		$this->model->set_description( $description );
		$this->model->set_license( 'GPL-2.0+' );
		$this->model->set_version( get_bloginfo( 'version' ) );

		$this->model->add_repository( 'composer', 'http://wpackagist.org' );

		foreach ( $plugins as $key => $plugin_data ) {
			$path = plugin_dir_path( $key );
			$fullpath = WP_CONTENT_DIR.'/plugins/'.$path;
			$filepath = WP_CONTENT_DIR.'/plugins/'.$key;
			$plugin = null;
			if ( file_exists( $fullpath.'.hg/' ) ) {
				$plugin = new \Tomjn\ComposerPress\Plugin\HGPlugin( $fullpath, $filepath, $plugin_data );
			} else if ( file_exists( $fullpath.'.git/' ) ) {
				$plugin = new \Tomjn\ComposerPress\Plugin\GitPlugin( $fullpath, $filepath, $plugin_data );
			} else if ( file_exists( $fullpath.'.svn/' ) ) {
				$plugin = new \Tomjn\ComposerPress\Plugin\SVNPlugin( $fullpath, $filepath, $plugin_data );
			} else {
				$plugin = new \Tomjn\ComposerPress\Plugin\WPackagistPlugin( $fullpath, $filepath, $plugin_data );
			}
			if ( $plugin != null ) {
				$this->model->add_plugin( $plugin );
			}
		}
	}
}




