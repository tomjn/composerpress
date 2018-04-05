<?php

namespace Tomjn\ComposerPress;

use \Tomjn\ComposerPress\Plugin\HGPlugin;
use \Tomjn\ComposerPress\Plugin\GitPlugin;
use \Tomjn\ComposerPress\Plugin\SVNPlugin;
use \Tomjn\ComposerPress\Plugin\WPackagistPlugin;

class ComposerPress {

	private $model = null;
  const DEFAULT_FALLBACK_VENDOR = 'composerpress';

	public function __construct( Model $model ) {
		$this->model = $model;
	}

	public function run() {
		//
	}

	public function fill_model() {
		$plugins = get_plugins();

		$this->model->required( 'johnpbloch/wordpress', '*@stable' );
		$this->model->required( 'php', '>=5.3.2' );

		$this->model->set_name( 'wpsite/'.sanitize_title( get_bloginfo( 'name' ) ) );
		$this->model->set_homepage( home_url() );
		$description = get_bloginfo( 'description' );
		$this->model->set_description( $description );
		$this->model->set_license( 'GPL-2.0+' );
		$this->model->set_version( get_bloginfo( 'version' ) );

		$this->model->add_repository( 'composer', 'http://wpackagist.org' );

		foreach ( $plugins as $key => $plugin_data ) {
			if ( !is_plugin_active( $key ) ) {
				continue;
			}
			$path = plugin_dir_path( $key );
			$fullpath = WP_CONTENT_DIR.'/plugins/'.$path;
			$filepath = WP_CONTENT_DIR.'/plugins/'.$key;
			$plugin = null;
			if ( file_exists( $fullpath.'.hg/' ) ) {
				$plugin = new HGPlugin( $fullpath, $filepath, $plugin_data );
			} else if ( file_exists( $fullpath.'.git/' ) ) {
				$plugin = new GitPlugin( $fullpath, $filepath, $plugin_data );
			} else if ( file_exists( $fullpath.'.svn/' ) ) {
				$plugin = new SVNPlugin( $fullpath, $filepath, $plugin_data );
			} else {
				$plugin = new WPackagistPlugin( $fullpath, $filepath, $plugin_data );
			}
			if ( $plugin != null ) {
				$this->model->add_plugin( $plugin );
			}
		}
	}

  /**
   * Retrieve a setting.
   *
   * @param string $key Setting name.
   * @param mixed $default Optional. Default setting value.
   * @return mixed
   */
  public static function get_setting( $key, $default = false ) {
    $option = get_option( 'composerpress' );
    return (isset( $option[ $key ] ) && strlen($option[ $key ]) > 0) ? $option[ $key ] : $default;
  }

}
