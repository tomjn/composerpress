<?php

namespace Tomjn\ComposerPress;

class ToolPage {

	protected $composerpress = null;
	protected $model = null;

	public function __construct( $composerpress, $model ) {
		$this->composerpress = $composerpress;
		$this->model = $model;

		add_action( 'admin_menu', array( $this, 'on_admin_menu' ) );
		add_action( 'admin_init', array( $this, 'on_admin_init' ) );
	}


	public function on_admin_menu() {
		add_submenu_page( 'tools.php', 'Composer.json', 'Composer.json', 'manage_options', 'composer-json-page', array( $this, 'options_page' ) );
	}

	public function on_admin_init() {
		// Composerpress custom settings
		$this->register_settings();
		$this->add_sections();
		$this->add_settings();
	}

	/**
	 * Register settings.
	 */
	public function register_settings() {
		register_setting( 'composerpress', 'composerpress', array( $this, 'sanitize_settings' ) );
	}

	/**
	 * Add settings sections.
	 */
	public function add_sections() {
		add_settings_section(
			'default',
			__( 'Settings', 'composerpress' ),
			'__return_null',
			'composerpress'
		);
	}

	/**
	 * Register individual settings.
	 */
	public function add_settings() {
		add_settings_field(
			'vendor',
			__( 'Fallback Vendor', 'composerpress' ),
			array( $this, 'render_field_vendor' ),
			'composerpress',
			'default'
		);
	}


	/**
	 * Display a field for defining the vendor.
	 */
	public function render_field_vendor() {
		$value = 	$this->composerpress::get_setting( 'vendor', '' );
		?>
		<p>
			<input type="text" name="composerpress[vendor]" id="composerpress-vendor" value="<?php echo esc_attr( $value ); ?>"><br>
			<span class="description">Default is <code>composerpress</code></span>
		</p>
		<?php
	}


	/**
	 * Sanitize settings.
	 */
	public function sanitize_settings( $value ) {
		if ( ! empty( $value['vendor'] ) ) {
			$value['vendor'] = sanitize_text_field( $value['vendor'] );
		}
		return $value;
	}

	/**
	 * Outputs composer.json page
	 */
	function options_page() {
		$this->composerpress->fill_model();

		echo '<div class="wrap">';
		echo '<h2>Composer.json</h2>';
		echo '<style>.composerpress_json { padding:1em; background:#fff; border: 1px solid #ddd; }</style>';

		// Readme.md link reminder
		echo '<sub><a href="https://github.com/tomjn/composerpress" target="_blank">Readme</a> for usage information</sub>';

		// Prints composer.json
		echo '<pre class="composerpress_json">';
		echo $this->model->to_json();
		echo '</pre>';

		echo '<button type="button" value="download" id="download" class="button button-secondary" style="width: 100%;">Download</button>';

		// Prints Settings Form
		echo '<form action="options.php" method="post">';
		settings_fields( 'composerpress' );
		do_settings_sections( 'composerpress' );
		submit_button();
		echo '</form>';

		echo '</div>';
		?>
		<script>
		/**
		 * Originally taken from: https://stackoverflow.com/questions/42266658/download-text-from-html-pre-tag
		 */
		function saveTextAsFile() {
			var textToWrite = document.querySelector('pre.composerpress_json').innerText;
			var textFileAsBlob = new Blob([textToWrite], {type:'text/plain'});
			var fileNameToSaveAs = "composer.json";

			var downloadLink = document.createElement("a");
			downloadLink.download = fileNameToSaveAs;
			downloadLink.innerHTML = "Download File";
			if (window.webkitURL != null) {
				// Chrome allows the link to be clicked without actually adding it to the DOM.
				downloadLink.href = window.webkitURL.createObjectURL(textFileAsBlob);
			} else {
				// Firefox requires the link to be added to the DOM before it can be clicked.
				downloadLink.href = window.URL.createObjectURL(textFileAsBlob);
				downloadLink.onclick = function(){
					document.body.removeChild(downloadLink);
				};
				downloadLink.style.display = "none";
				document.body.appendChild(downloadLink);
			}
			downloadLink.click();
		}

		var button = document.getElementById('download');
		button.addEventListener('click', saveTextAsFile);

		</script>
		<?php
	}
}
