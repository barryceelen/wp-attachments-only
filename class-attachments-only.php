<?php
/**
 * Contains the main plugin class
 *
 * @package   Attachments_Only
 * @author    Barry Ceelen <b@rryceelen.com>
 * @license   GPL-2.0+
 * @link      https://github.com/barryceelen/wp-attachments-only
 * @copyright 2015 Barry Ceelen
 */

/**
 * Main plugin class
 *
 * @since 0.0.1
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
	const VERSION = '0.0.3';

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

		$this->add_actions_and_filters();
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
		if ( null === self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Add actions and filters.
	 *
	 * @since 0.0.3
	 */
	private function add_actions_and_filters() {
		// Remove default 'Add Media' button.
		add_action( 'admin_head', array( $this, 'remove_media_button' ) );

		// Add our own button.
		add_action( 'media_buttons', array( $this, 'the_media_button' ) );

		// Rename media frame label.
		add_filter( 'media_view_strings', array( $this, 'filter_media_view_strings' ) );

		// Enqueue JavaScript.
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

		// Until this plugin replaces the default behaviour, prevent uploading files by
		// dragging them on to the editor by hiding .uploader-editor.
		add_filter( 'wp_editor_settings', array( $this, 'filter_wp_editor_settings' ), 10, 2 );
	}

	/**
	 * Remove the default 'Add Media' button.
	 *
	 * @since 0.0.1
	 */
	public function remove_media_button() {

		global $post, $pagenow;

		if ( ! in_array( $pagenow, array( 'post.php', 'post-new.php' ), true ) ) {
			return;
		}

		if ( ! $this->is_supported_post_type( $post->post_type ) ) {
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

		global $post;

		if ( $this->is_supported_post_type( $post->post_type ) ) {
			printf(
				'<a href"#" id="insert-media-button-attachments-only" class="button add_media"  title="%s"><span class="wp-media-buttons-icon"></span> %s</a>',
				esc_attr( apply_filters( 'attachments_only_media_button_label', __( 'Attachments', 'attachments-only' ) ) ),
				esc_html( apply_filters( 'attachments_only_media_button_label', __( 'Attachments', 'attachments-only' ) ) )
			);
		}
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

		if ( ! in_array( $pagenow, array( 'post.php', 'post-new.php' ), true ) ) {
			return $strings;
		}

		if ( ! $this->is_supported_post_type( $post->post_type ) ) {
			return $strings;
		}

		$strings['mediaLibraryTitle'] = esc_html( apply_filters( 'attachments_only_media_library_title', __( 'Attachments', 'attachments-only' ) ) );
		$strings['done'] = esc_html( apply_filters( 'attachments_only_media_library_button_title', __( 'Close', 'attachments-only' ) ) );

		return $strings;
	}

	/**
	 * Register and enqueue JavaScript file.
	 *
	 * @since 0.0.1
	 *
	 * @param string $hook_suffix The current admin page.
	 */
	public function enqueue_scripts( $hook_suffix ) {

		if ( ! in_array( $hook_suffix, array( 'post.php', 'post-new.php' ), true ) ) {
			return;
		}

		global $post;

		if ( ! $this->is_supported_post_type( $post->post_type ) ) {
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
	 * Filter arguments for the post editor.
	 *
	 * Prevents uploading files by dragging them on the post editor.
	 *
	 * @todo Open our own media view in stead of preventing to open the default one.
	 *
	 * @since 0.0.3
	 * @param  array  $settings  Array of editor arguments {@see _WP_Editors::parse_settings()}.
	 * @param  string $editor_id ID for the current editor instance.
	 * @return array Filtered arguments array.
	 */
	public function filter_wp_editor_settings( $settings, $editor_id ) {

		global $post, $pagenow;

		if ( ! in_array( $pagenow, array( 'post.php', 'post-new.php' ), true ) ) {
			return;
		}

		if ( ! $this->is_supported_post_type( $post->post_type ) ) {
			return;
		}

		if ( 'content' === $editor_id ) {
			$settings['drag_drop_upload'] = false;
		}

		return $settings;
	}

	/**
	 * Get post types which support the 'attachments_only' feature.
	 *
	 * Use only after or late in the 'init' action as that is where post types and their features should be registered.
	 *
	 * @return array List of supported post types.
	 */
	private function get_supported_post_types() {
		global $_wp_post_type_features;
		return array_keys( wp_filter_object_list( $_wp_post_type_features, array( 'attachments_only' => true ) ) );
	}

	/**
	 * Is the post type supported?
	 *
	 * Use only after or late in the 'init' action as that is where post types and their features should be registered.
	 *
	 * @param  string $post_type Post type.
	 * @return bool True if supported, else false.
	 */
	private function is_supported_post_type( $post_type ) {
		return in_array( $post_type, $this->get_supported_post_types(), true );
	}
}
