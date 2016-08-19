<?php
/**
 * Sticky Header
 *
 * @package   Sticky_Dropdown
 * @author    pixelsolution4it <contact@pixelsolution4it.com>
 * @license   GPL-2.0+
 * @link      http://pixelsolution4it.com
 * @copyright 2014 pixelsolution4it
 */

/**
 * Plugin class.
 *
 * @package Sticky_Dropdown
 * @author  pixelsolution4it <contact@pixelsolution4it.com>
 */
class Sticky_Dropdown {

	/**
	 * Plugin version, used for cache-busting of style and script file references.
	 *
	 * @since   1.0.0
	 *
	 * @var     string
	 */
	const VERSION = '1.0.0';

	/**
	 * Unique identifier for your plugin.
	 *
	 * The variable name is used as the text domain when internationalizing strings of text.
	 * Its value should match the Text Domain file header in the main plugin file.
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	protected $plugin_slug = 'pixl-sticky-header';

	/**
	 * Instance of this class.
	 *
	 * @since    1.0.0
	 *
	 * @var      object
	 */
	protected static $instance = null;

	/**
	 * Slug of the plugin screen.
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	protected $plugin_screen_hook_suffix = null;

	/**
	 * Initialize the plugin by setting localization, filters, and administration functions.
	 *
	 * @since     1.0.0
	 */
	private function __construct() {

		// Load plugin text domain
		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );

		// Add an action link pointing to the options page.
		$plugin_basename = plugin_basename( plugin_dir_path( __FILE__ ) . 'sticky-header.php' );
		add_filter( 'plugin_action_links_' . $plugin_basename, array( $this, 'add_action_links' ) );

		// Load public-facing style sheet and JavaScript.
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );


		// Define custom functionality.
		add_action( 'wp_footer', array( $this, 'display' ) ); // Display Sticky Header
		add_action( 'wp_head', array( $this, 'generate_css' ) ); // Sticky Header generated CSS
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_pointer' ) ); // Admin pointer
	}

	/**
	 * Return an instance of this class.
	 *
	 * @since     1.0.0
	 *
	 * @return    object    A single instance of this class.
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
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		$domain = $this->plugin_slug;
		$locale = apply_filters( 'plugin_locale', get_locale(), $domain );

		load_textdomain( $domain, trailingslashit( WP_LANG_DIR ) . $domain . '/' . $domain . '-' . $locale . '.mo' );
		load_plugin_textdomain( $domain, FALSE, basename( dirname( __FILE__ ) ) . '/languages' );
	}

	/**
	 * Register and enqueue public-facing style sheet.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		wp_enqueue_style( $this->plugin_slug . '-plugin-styles', plugins_url( 'css/public.css', __FILE__ ), array(), self::VERSION );
	}

	/**
	 * Register and enqueues public-facing JavaScript files.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		wp_enqueue_script( $this->plugin_slug . '-plugin-script', plugins_url( 'js/public.js', __FILE__ ), array( 'jquery' ), self::VERSION );
		
		// Send plugin settings to JS file.
		$plugin_settings = get_option( 'pixl_Sticky_Dropdown' );
		$script_params = array(
			'show_at'			=> $plugin_settings['show_at'],
			'hide_if_narrower'	=> $plugin_settings['hide_if_narrower']
		);
		wp_localize_script( $this->plugin_slug . '-plugin-script', 'StickyHeaderParams', $script_params );
	}

	/**
	 * Add settings action link to Customize page.
	 *
	 * @since    1.0.0
	 */
	public function add_action_links( $links ) {
		return array_merge(
			array(
				'settings'	=> '<a href="' . admin_url( 'customize.php' ) . '">' . __( 'Settings', $this->plugin_slug ) . '</a>'
			),
			$links
		);
	}

	/**
	 * Sticky Header output
	 *
	 * @since    1.0.0
	 */
	public function display() {
		include_once( 'views/public.php' );
	}

	/**
	 * Sticky Header generated CSS
	 *
	 * @since    1.0.0
	 */
	public function generate_css() {
		$plugin_settings = pixl_Sticky_Dropdown_get_settings(); ?>
		<style type="text/css">
			#pixl-sticky-header {
				background-color: <?php echo $plugin_settings['background_color']; ?>;
			}
			#pixl-sticky-header,
			#pixl-sticky-header a {
				color: <?php echo $plugin_settings['text_color']; ?> !important;
			}
			<?php if ( isset( $plugin_settings['inner_width'] ) ) : ?>
			<?php if ( '' != $plugin_settings['inner_width'] ) : ?>
			#pixl-sticky-header-inner {
				max-width: <?php echo $plugin_settings['inner_width']; ?>px;
				margin: 0 auto;
			}
			<?php endif; ?>
			<?php endif; ?>
		</style>
	<?php }

	/**
	 * Sticky Header admin pointer
	 *
	 * @since    1.0.0
	 */
	public function admin_pointer() {
		// Assume pointer shouldn't be shown
		$enqueue_pointer_script_style = false;
	
		// First check if current user can edit theme options
		if ( user_can( get_current_user_id(), 'edit_theme_options' ) ) :
			// Get array list of dismissed pointers for current user and convert it to array
			$dismissed_pointers = explode( ',', get_user_meta( get_current_user_id(), 'dismissed_wp_pointers', true ) );
		
			// Check if our pointer is not among dismissed ones
			if( ! in_array( 'pixl_Sticky_Dropdown_pointer', $dismissed_pointers ) ) :
				$enqueue_pointer_script_style = true;
				// Add footer scripts using callback function
				add_action( 'admin_print_footer_scripts', array( $this, 'print_admin_pointer_scripts' ) );
			endif;
		
			// Enqueue pointer CSS and JS files, if needed
			if( $enqueue_pointer_script_style ) :
				wp_enqueue_style( 'wp-pointer' );
				wp_enqueue_script( 'wp-pointer' );
			endif;
		endif;
	}

	/**
	 * Print Sticky Header admin pointer scripts
	 *
	 * @since    1.0.0
	 */
	public function print_admin_pointer_scripts() {
		?>
		
		<script type="text/javascript">
		//<![CDATA[
		jQuery(document).ready( function($) {
			$('#menu-appearance').pointer({
				content:		'<?php echo $pointer_content; ?>',
				position:		{
									edge:	'left', // arrow direction
									align:	'center' // vertical alignment
								},
				pointerWidth:	400,
				close:			function() {
									$.post( ajaxurl, {
											pointer: 'pixl_Sticky_Dropdown_pointer', // pointer ID
											action: 'dismiss-wp-pointer'
									});
								}
			}).pointer('open');
		});
		//]]>
		</script>	
	<?php }

}