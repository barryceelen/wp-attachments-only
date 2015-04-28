<?php
/**
* Attachments Only.
*
* @package   Attachments_Only
* @author    Barry Ceelen <b@rryceelen.com>
* @license   GPL-2.0+
* @link      https://github.com/barryceelen/wp-attachments-only
* @copyright 2015 Barry Ceelen
*/

/**
* @package Attachments_Only
* @author  Barry Ceelen <b@rryceelen.com>
*/
class Attachments_Only {

	/**
	 * Plugin version, used for cache-busting of style
	 * and script file references.
	 *
	 * @since 0.0.1
	 *
	 * @var string
	 */
	const VERSION = '0.0.2';

	/**
	 * Instance of this class.
	 *
	 * @since 0.0.1
	 *
	 * @var object
	 */
	protected static $instance = null;

	/**
	 * Initialize the plugin.
	 *
	 * @since 0.0.1
	 */
	private function __construct() {

		$defaults = array(
			'post_types' => array( 'post', 'page' ),
			'media_button_label' => esc_html__( 'Attachments', 'attachments-only' ),
			'media_library_title' => esc_html__( 'Attachments', 'attachments-only' ),
			'media_library_tab_title' => esc_html__( 'Attachments', 'attachments-only' ),
			'media_library_button_title' => esc_html__( 'Close', 'attachments-only' ),
		);

		$this->options = apply_filters( 'attachments_only_options', $defaults );

		// Remove default 'Add Media' button.
		add_action( 'admin_head', array( $this, 'remove_media_button' ) );

		// Add our own button.
		add_action( 'media_buttons', array( $this, 'the_media_button' ) );

		// Rename media frame label.
		add_filter( 'media_view_strings', array( $this, 'filter_media_view_strings') );

		// Enqueue JavaScript.
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

		// Until this plugin replaces the default behaviour, prevent uploading files by
		// dragging them on to the editor by hiding .uploader-editor.
		add_action( 'admin_footer', array( $this, 'hide_uploader_editor' ) );
	}

	/**
	 * Return an instance of this class.
	 *
	 * @since 0.0.1
	 *
	 * @return object A single instance of this class.
	 */
	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since 0.0.1
	 */
	public static function load_plugin_textdomain() {
		load_plugin_textdomain(
			'attachments-only',
			false,
			dirname( plugin_basename( __FILE__ ) ) . '/languages'
		);
	}

	/**
	 * Remove the default 'Add Media' button.
	 *
	 * @since 0.0.1
	 *
	 * @return null
	 */
	public function remove_media_button() {
		global $post, $pagenow;
		if ( ! in_array( $pagenow, array( 'post.php','post-new.php' ) ) ) {
			return;
		}
		if ( ! in_array( $post->post_type, $this->options['post_types'] ) ) {
			return;
		}
		remove_action( 'media_buttons', 'media_buttons' );
	}

	/**
	 * Add our own 'Add Media' button to post edit screen.
	 *
	 * @since 0.0.1
	 */
	public function the_media_button() {
		printf( '<a href"#" id="insert-media-button-attachments-only" class="button add_media"  title="%s"><span class="wp-media-buttons-icon"></span> %s</a>', esc_attr__( $this->options['media_button_label'] ), esc_html__( $this->options['media_button_label'] ) );
	}

	/**
	 * Filter default media view strings.
	 *
	 * @todo Set media library tab title via JavaScript in attachments-only.js?
	 *
	 * @since 0.0.1
	 *
	 * @param  array $strings Default strings.
	 * @return array
	 */
	public function filter_media_view_strings( $strings ) {
		global $post, $pagenow;
		if ( ! in_array( $pagenow, array( 'post.php','post-new.php' ) ) ) {
			return $strings;
		}
		if ( ! in_array( $post->post_type, $this->options['post_types'] ) ) {
			return $strings;
		}
		$strings['mediaLibraryTitle'] = $this->options['media_library_title'];
		$strings['done'] = $this->options['media_library_button_title'];
		return $strings;
	}

	/**
	 * Register and enqueue JavaScript file.
	 *
	 * @since 0.0.1
	 *
	 * @return null
	 */
	public function enqueue_scripts( $hook ) {

		if ( ! in_array( $hook, array( 'post.php','post-new.php' ) ) ) {
			return;
		}

		global $post;

		if ( ! in_array( $post->post_type, $this->options['post_types'] ) ) {
			return;
		}

		wp_enqueue_media( array( 'post' => $post->ID ) );

		wp_enqueue_script(
			'attachments-only',
			plugins_url( 'js/attachments-only.js', __FILE__ ),
			array( 'jquery' ),
			self::VERSION,
			true
		);
	}

	/**
	 * Prevent uploading files by dragging them on the post editor.
	 *
	 * @todo Open our own media view in stead of preventing to open the default one.
	 *
	 * @since 0.0.2
	 * @return null
	 */
	public function hide_uploader_editor() {
		global $post, $pagenow;
		if ( ! in_array( $pagenow, array( 'post.php','post-new.php' ) ) ) {
			return;
		}
		if ( ! in_array( $post->post_type, $this->options['post_types'] ) ) {
			return;
		}
		echo '<style>.uploader-editor { display: none !important;  }</style>';
	}
}
