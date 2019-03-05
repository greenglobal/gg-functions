<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://greenglobal.vn
 * @since      1.0.0
 *
 * @package    Greenglobal
 * @subpackage Greenglobal/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Greenglobal
 * @subpackage Greenglobal/public
 * @author     Vinhnv <vinhnv@greenglobal.vn>
 */
class Greenglobal_Public {

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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		add_action( 'init', array( $this, 'gg_wp_extra' ));
	}

	public function gg_wp_extra(){
		$options = get_option('gg_settings');
		if(isset($options['gg_remove_gutenberg']) && $options['gg_remove_gutenberg'] == 1){
			add_filter('use_block_editor_for_post', '__return_false');
		}
		if(isset($options['gg_mce_editor']) && $options['gg_mce_editor']){
			// var_dump($options['gg_mce_editor']);die;
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/inc/tinymce/tinymce-advanced.php';
			// if (!function_exists( 'wpvn_mce_buttons')){
			// 	function gg_mce_buttons($buttons){
			// 		array_push($buttons,
			// 			"alignjustify",
			// 			"subscript",
			// 			"fontselect",
			// 			"fontsizeselect",
			// 			"superscript",
			// 			"anchor",
			// 			"table"
			// 		);
			// 		return $buttons;
			// 	}
			// 	add_filter("mce_buttons", "gg_mce_buttons");
			// }
			// if ( ! function_exists( 'gg_mce_text_sizes' ) ) {
		 //    function gg_mce_text_sizes( $initArray ){
	  //       $initArray['fontsize_formats'] = "9px 10px 12px 13px 14px 16px 18px 21px 24px 28px 32px 36px";
	  //       return $initArray;
		 //    }
			// }
			// add_filter( 'tiny_mce_before_init', 'gg_mce_text_sizes' );
		}
		if(isset($options['gg_auto_save_images']) && $options['gg_auto_save_images'] == 1){
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/inc/auto-save-image.php';
		}
		if (isset($options['gg_allow_svg']) && $options['gg_allow_svg'] == 1) {
			if(!function_exists('gg_mime_types')){
		    function gg_mime_types($mimes) {
					$mimes['svg'] = 'image/svg+xml';
					return $mimes;
				}
				add_filter('upload_mimes', 'gg_mime_types');
			}
		}
		if(isset($options['gg_disable_emojis']) && $options['gg_disable_emojis'] == 1){
			if(!function_exists('gg_disable_emojis')){
				function gg_disable_emojis() {
					remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
					remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
					remove_action( 'wp_print_styles', 'print_emoji_styles' );
					remove_action( 'admin_print_styles', 'print_emoji_styles' );	
					remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
					remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );	
					remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
					add_filter( 'tiny_mce_plugins', 'gg_disable_emojis_tinymce' );
				}
				add_action( 'init', 'gg_disable_emojis' );
			}
			if(!function_exists('gg_disable_emojis_tinymce')){
				function gg_disable_emojis_tinymce( $plugins ) {
					if ( is_array( $plugins ) ) {
						return array_diff( $plugins, array( 'wpemoji' ) );
					} else {
						return array();
					}
				}
			}
		}
		if(isset($options['gg_remove_version']) && $options['gg_remove_version'] == 1){
			// remove version from head
			remove_action('wp_head', 'wp_generator');
			// remove version from rss
			add_filter('the_generator', '__return_empty_string');
			// remove version from scripts and styles
			if(!function_exists('gg_remove_version_scripts_styles')){
				function gg_remove_version_scripts_styles($src) {
					if (strpos($src, 'ver=')) {
						$src = remove_query_arg('ver', $src);
					}
					return $src;
				}
				add_filter('style_loader_src', 'gg_remove_version_scripts_styles', 9999);
				add_filter('script_loader_src', 'gg_remove_version_scripts_styles', 9999);
			}
		}

		if (isset($options['gg_add_header']) && $options['gg_add_header']) {
			if(!function_exists('gg_add_header_code')){
				function gg_add_header_code () {
					$options = get_option('gg_settings');
					echo stripslashes($options['gg_add_header']);
				}
				add_action('wp_head', 'gg_add_header_code');
			}
		}
		if (isset($options['gg_add_footer']) && $options['gg_add_footer']) {
			if(!function_exists('gg_add_footer_code')){
				function gg_add_footer_code () {
					$options = get_option('gg_settings');
					echo stripslashes($options['gg_add_footer']);
				}
				add_action('wp_footer', 'gg_add_footer_code');
			}
		}
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/greenglobal-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/greenglobal-public.js', array( 'jquery' ), $this->version, false );

	}

}
