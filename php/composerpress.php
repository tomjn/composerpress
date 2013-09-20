<?php

namespace Tomjn\ComposerPress;

class ComposerPress extends \Pimple {
	public function __construct() {
		//
	}

	public function run() {
		add_action( 'init', array( $this, 'on_init' ) );
	}

	public function on_init() {
		//$json = self::get_composer_json();
		//wp_die( '<pre>'.print_r( $json, true ).'</pre>' );
	}

	public static function get_composer_json() {
		$plugins = get_plugins();
		$data = array();
		$data['rarst/wordpress'] = '>='.get_bloginfo( 'version' );
		$data['php'] = '>=5.2.4';
		foreach ( $plugins as $plugin ) {
			$key = 'wpackagist/'.sanitize_title( $plugin['Name'] );
			$data[ $key ] = $plugin['Version'];
		}
		$manifest = array();
		$manifest['name'] = 'wpsite/'.sanitize_title( get_bloginfo( 'name' ) );
		$repositories = array();
		$repositories[] = array(
			'type' => 'vcs',
			'url' => 'http://rarst.net'
		);
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
}
