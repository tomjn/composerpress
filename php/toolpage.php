<?php

namespace Tomjn\ComposerPress;

class ToolPage {
	protected $composerpress = null;
	protected $model = null;
	public function __construct( $composerpress, $model ) {
		$this->composerpress = $composerpress;
		$this->model = $model;
		add_action( 'admin_menu', array( $this, 'on_admin_menu' ) );
	}

	public function on_admin_menu() {
		add_submenu_page( 'tools.php', 'Composer.json', 'Composer.json', 'manage_options', 'composer-json-page', array( $this, 'options_page' ) );
	}

	function options_page() {
		$this->composerpress->fill_model();
		echo '<div class="wrap">';
		echo '<h2>Composer.json</h2>';
		echo '<style>.composerpress_json { padding:1em; background:#fff; border: 1px solid #ddd; }</style>';
		echo '<pre class="composerpress_json">';
		echo $this->model->to_json();
		echo '</pre>';
		echo '</div>';
	}
}