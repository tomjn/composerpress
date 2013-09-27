<?php

namespace Tomjn\ComposerPress;

use Gitonomy\Git\Repository;

class ComposerPress extends \Pimple {

	private $model = null;
	public function __construct( Model $model ) {
		$this->model = $model;
	}

	public function run() {
		add_action( 'admin_menu', array( $this, 'on_admin_menu' ) );
	}

	public function on_admin_menu() {
		add_submenu_page( 'tools.php', 'Composer.json', 'Composer.json', 'manage_options', 'composer-json-page', array( $this, 'options_page' ) );
	}

	function options_page() {
		$this->fill_model();
		echo '<div class="wrap">';
		echo '<h2>Composer.json</h2>';
		echo '<pre>';
		echo $this->model->to_json();
		echo '</pre>';
		echo '</div>';
	}

	public function fill_model() {
		$plugins = get_plugins();

		$this->model->required( 'rarst/wordpress', '>='.get_bloginfo( 'version' ) );
		$this->model->required( 'php', '>=5.2.4' );

		$this->model->set_name( 'wpsite/'.sanitize_title( get_bloginfo( 'name' ) ) );
		$this->model->set_homepage( home_url() );
		$this->model->set_description( get_bloginfo( 'description' ) );
		$this->model->set_version( get_bloginfo( 'version' ) );

		$this->model->add_repository( 'composer', 'http://wpackagist.org' );
		$this->model->add_repository( 'vcs', 'http://rarst.net' );

		$this->model->add_extra( 'installer-paths', array( 'wp' => array( 'rarst/wordpress' ) ) );

		foreach ( $plugins as $key => $plugin ) {
			$path = plugin_dir_path( $key );
			$fullpath = WP_CONTENT_DIR.'/plugins/'.$path;
			if ( file_exists( $fullpath.'.svn/' ) ) {
				$this->handle_plugin_svn_require( $plugin, $fullpath );
			} else if ( file_exists( $fullpath.'.git/' ) ) {
				$this->handle_plugin_git_require( $plugin, $fullpath );
			} else {
				$this->handle_plugin_fallback_require( $plugin, $fullpath );
			}
		}
	}

	public function handle_plugin_git_require( $plugin, $fullpath ) {
		// process our git repository
		$p = new \Tomjn\ComposerPress\Plugin\GitPlugin( $fullpath, $plugin );
		$repository = new Repository( $fullpath );

		// get the repository URL
		$remote_url = $repository->run( 'config', array(
			'--get' => 'remote.origin.url'
		) );
		$remote_url = $p->get_url();
		$reponame = 'composerpress/'.sanitize_title( $plugin['Name'] );
		$package = array(
			'name' => $reponame,
			'version' => '1.0',
			'type' => 'wordpress-plugin',
			'source' => array(
				'url' => $remote_url,
				'type' => 'git'
			)
		);

		$this->model->add_package_repository( $package );
		$this->model->required( $reponame, '1.0' );

		// @TODO: Handle repos that already have a composer.json

		return;
	}

	public function handle_plugin_svn_require( $plugin ) {
		return;
	}

	public function handle_plugin_fallback_require( $plugin ) {
		$key = 'wpackagist/'.sanitize_title( $plugin['Name'] );
		$version = $plugin['Version'];
		if ( !empty( $version ) ) {
			$this->model->required( $key, $version );
		}
	}

	public function get_composer_json( $fullpath ) {
		//
	}
}


