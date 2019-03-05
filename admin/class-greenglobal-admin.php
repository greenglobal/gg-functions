<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://greenglobal.vn
 * @since      1.0.0
 *
 * @package    Greenglobal
 * @subpackage Greenglobal/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Greenglobal
 * @subpackage Greenglobal/admin
 * @author     Vinhnv <vinhnv@greenglobal.vn>
 */
class Greenglobal_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		add_action( 'admin_init', array( $this, 'gg_settings_init' ));
	}
	public function admin_menu() {
		add_options_page(
			'Page Title',
			'GG Functions',
			'manage_options',
			'greenglobal_settings',
			array(
				$this,
				'settings_page'
			)
		);
	}

	public function gg_settings_init(  ) { 

		register_setting( 'wpFrontend', 'gg_settings' );
		register_setting( 'wpTheme', 'gg_settings' );
		// WP FRON END
		add_settings_section('gg_frontend_section', __( 'WP Backend', 'gg' ), array($this,'gg_settings_section_callback'), 'wpFrontend');
		add_settings_field( 'gg_remove_gutenberg', __( 'Disable Gutenberg', 'gg' ), array($this,'gg_checkbox_callback'),'wpFrontend', 'gg_frontend_section',array('gg_remove_gutenberg','Disables the new Gutenberg Editor'));
		add_settings_field( 'gg_mce_editor', __( 'Customize MCE editor (Ex: Justify, Font Size)', 'gg' ), array($this,'gg_textbox_callback'),'wpFrontend', 'gg_frontend_section',array('gg_mce_editor','Eg: table,alignjustify,fontselect,fontsizeselect,subscript,superscript'));
		add_settings_field( 'gg_auto_save_images', __( 'Auto Save Images', 'gg' ), array($this,'gg_checkbox_callback'),'wpFrontend', 'gg_frontend_section',
			array(
        'gg_auto_save_images'
      )  
		);
		add_settings_field( 'gg_allow_svg', __( 'Allow SVG', 'gg' ), array($this,'gg_checkbox_callback'),'wpFrontend', 'gg_frontend_section',
			array(
        'gg_allow_svg'
      )  
		);
		add_settings_field( 'gg_disable_emojis', __( 'Disable Emojis', 'gg' ), array($this,'gg_checkbox_callback'),'wpFrontend', 'gg_frontend_section',
			array(
        'gg_disable_emojis'
      )  
		);
		add_settings_field( 'gg_remove_version', __( 'Remove wordpress version number?', 'gg' ), array($this,'gg_checkbox_callback'),'wpFrontend', 'gg_frontend_section',
			array(
        'gg_remove_version'
      )  
		);
		// WP THEME
		add_settings_section('gg_theme_section', __( 'WP Themes', 'gg' ), array($this,'gg_settings_section_callback'), 'wpTheme');
		add_settings_field( 'gg_add_header', __( 'Script in Header', 'gg' ), array($this,'gg_textarea_callback'),'wpTheme', 'gg_theme_section',array('gg_add_header','To be inserted in the <head> section.
Ex: Google Analytic'));
		add_settings_field( 'gg_add_footer', __( 'Script in Footer', 'gg' ), array($this,'gg_textarea_callback'),'wpTheme', 'gg_theme_section',array('gg_add_footer','To be inserted above the </body> tag.
Ex: Javascript'));
	}

	public function gg_settings_section_callback(){

	}

	public function gg_checkbox_callback($args) {  // Textbox Callback
		$options = get_option( 'gg_settings' );
    echo '<input type="checkbox" class="widefat" id="'. $args[0] .'" '.@checked($options[$args[0]], 1 ,false).'  name="gg_settings['. $args[0] .']" value="1"/>';
	}
	public function gg_textbox_callback($args) {  // Textbox Callback
		$options = get_option( 'gg_settings' );
    echo '<input type="text" class="widefat" id="'. $args[0] .'" name="gg_settings['. $args[0] .']" value="' . $options[$args[0]]. '" /><small>'.@$args[1].'</small>';
	}
	public function gg_textarea_callback($args) {  // Textbox Callback
		$options = get_option( 'gg_settings' );
    echo '<textarea name="gg_settings['. $args[0] .']" id="'. $args[0] .'" class="widefat" rows="8">' . $options[$args[0]]. '</textarea><small>'.@$args[1].'</small>';
	}
	public function gg_select_callback($args) {  // Textbox Callback
		$options = get_option( 'gg_settings' );
    echo '<select name="gg_settings['. $args[0] .']" id="'. $args[0] .'" class="widefat">
		  <option value="">None</option>
		</select>';
	}

	public function  settings_page() {
		load_template( dirname( __FILE__ ) . '/partials/greenglobal-admin-display.php' );
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Greenglobal_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Greenglobal_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/greenglobal-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Greenglobal_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Greenglobal_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/greenglobal-admin.js', array( 'jquery' ), $this->version, false );

	}

}
