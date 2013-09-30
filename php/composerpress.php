<?php

namespace Tomjn\ComposerPress;

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

		$this->model->required( 'johnpbloch/wordpress', '>='.get_bloginfo( 'version' ) );
		$this->model->required( 'php', '>=5.3.2' );

		$this->model->set_name( 'wpsite/'.sanitize_title( get_bloginfo( 'name' ) ) );
		$this->model->set_homepage( home_url() );
		$this->model->set_description( get_bloginfo( 'description' ) );
		$this->model->set_version( get_bloginfo( 'version' ) );

		$this->model->add_repository( 'composer', 'http://wpackagist.org' );

		$this->model->add_extra( 'installer-paths', array( 'wp' => array( 'rarst/wordpress' ) ) );

		foreach ( $plugins as $key => $plugin ) {
			$path = plugin_dir_path( $key );
			$fullpath = WP_CONTENT_DIR.'/plugins/'.$path;
			if ( file_exists( $fullpath.'.git/' ) ) {
				$this->handle_plugin_git_require( $plugin, $fullpath );
			} else if ( file_exists( $fullpath.'.svn/' ) ) {
				$this->handle_plugin_svn_require( $plugin, $fullpath );
			} else {
				$this->handle_plugin_fallback_require( $plugin, $fullpath );
			}
		}
	}

	public function handle_plugin_git_require( $plugin, $fullpath ) {
		$gitplugin = new \Tomjn\ComposerPress\Plugin\GitPlugin( $fullpath, $plugin );
		$this->handle_plugin( $gitplugin );
	}

	public function handle_plugin_svn_require( $plugin, $fullpath ) {
		$svnplugin = new \Tomjn\ComposerPress\Plugin\SVNPlugin( $fullpath, $plugin );
		$this->handle_plugin( $svnplugin );
	}

	public function handle_plugin_fallback_require( $plugin, $fullpath ) {
		$packagist = new \Tomjn\ComposerPress\Plugin\WPackagistPlugin( $fullpath, $plugin );
		$version = $plugin['Version'];
		if ( !empty( $version ) ) {
			$this->model->required( $packagist->get_name(), $version );
		}
	}

	public function handle_plugin( $plugin ) {
		$remote_url = $plugin->get_url();
		$reponame = $plugin->get_name();
		$version = $plugin->get_version();
		$vcstype = $plugin->get_vcs_type();

		if ( $plugin->has_composer() ) {
			$this->model->add_repository( $vcstype, $remote_url );
		} else {
			$package = array(
				'name' => $reponame,
				'version' => $version,
				'type' => 'wordpress-plugin',
				'source' => array(
					'url' => $remote_url,
					'type' => $vcstype
				)
			);

			$this->model->add_package_repository( $package );
		}
		$this->model->required( $reponame, $version );
	}
}




