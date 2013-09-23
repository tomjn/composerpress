<?php

namespace Tomjn\ComposerPress;

class ComposerPress extends \Pimple {
	public function __construct() {
		//
	}

	public function run() {
		add_action( 'admin_menu', array( $this, 'on_admin_menu' ) );
	}

	public function on_admin_menu() {
		add_submenu_page( 'tools.php', 'Composer.json', 'Composer.json', 'manage_options', 'composer-json-page', array( $this, 'options_page' ) );
	}

	function options_page() {

		echo '<div class="wrap">';
		echo '<h2>Composer.json</h2>';
		echo '<pre>';
		echo $this->get_composer_json();
		echo '</pre>';
		echo '</div>';

	}

	public function get_composer_json() {
		$plugins = get_plugins();
		$data = array();
		$data['rarst/wordpress'] = '>='.get_bloginfo( 'version' );
		$data['php'] = '>=5.2.4';

		$repositories = array();
		$repositories[] = array(
			'type' => 'vcs',
			'url' => 'http://rarst.net'
		);

		foreach ( $plugins as $key => $plugin ) {
			$path = plugin_dir_path( $key );
			$fullpath = WP_CONTENT_DIR.'/plugins/'.$path;
			$req = array();
			if ( file_exists( $fullpath.'.svn/' ) ) {
				$req = $this->handle_plugin_svn_require( $plugin, $fullpath );
			} else if ( file_exists( $fullpath.'.git/' ) ) {
				$req = $this->handle_plugin_git_require( $plugin, $fullpath );
			} else {
				$req = $this->handle_plugin_fallback_require( $plugin, $fullpath );
			}
			if ( !empty( $req ) ) {
				$data[ $req['key'] ] = $req['version'];
			}
			//wp_die( print_r( $fullpath, true ) );
		}
		$manifest = array();
		$manifest['name'] = 'wpsite/'.sanitize_title( get_bloginfo( 'name' ) );
		$manifest['description'] = get_bloginfo( 'description' );
		$manifest['homepage'] = home_url();
		$manifest['version'] = get_bloginfo( 'version' );
		$manifest['repositories'] = $repositories;
		$manifest['extra'] = array(
			'installer-paths' => array(
				'./' => array( 'rarst/wordpress' )
			)
		);
		$manifest['require'] = $data;
		$json = json_encode( $manifest, ( JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT ) );
		return $json;
	}

	public function handle_plugin_git_require( $plugin ) {
		$dependency = array();
		return $dependency;
	}
	public function handle_plugin_svn_require( $plugin ) {
		$dependency = array();
		return $dependency;
	}
	public function handle_plugin_fallback_require( $plugin ) {
		$key = 'wpackagist/'.sanitize_title( $plugin['Name'] );
		$version = $plugin['Version'];
		if ( !empty( $version ) ) {
			$dependency = array( 'key' => $key, 'version' => $version );
			return $dependency;
		}
		return array();
	}
}


