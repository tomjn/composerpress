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

    // Prints composer.json
		echo '<pre class="composerpress_json">';
		echo $this->model->to_json();
		echo '</pre>';

    // Prints Settings Form
    echo '<form action="options.php" method="post">';
    settings_fields( 'composerpress' );
    do_settings_sections( 'composerpress' );
    submit_button();
    echo '</form>';

		echo '</div>';
	}
}
