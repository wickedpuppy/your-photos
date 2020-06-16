<?php
/**
 * Plugin Name: Your Photos
 * Plugin URI: http://www.woocommerce.com/products/
 * Description: plugin framework test
 * Author: Enis Trevisi
 * Author URI: http://www.woocommerce.com
 * Version: 1.0.0
 * Text Domain: your-photos
 * Domain Path: /i18n/languages/
 *
 * Copyright: (c) 2020, Enis Trevisi
 *
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 *
 * @package   YourPhotos
 * @author    Enis Trevisi
 * @copyright Copyright (c) 2020, Enis Trevisi
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3.0
 *
 * Woo: 11111:00000000000000000000000000000000
 */

namespace YourPhotos;
use YourPhotos\Classes\Psr4Test;

defined( 'ABSPATH' ) || exit;

// Required functions
if ( ! function_exists( 'woothemes_queue_update' ) ) {
	require_once( plugin_dir_path( __FILE__ ) . 'woo-includes/woo-functions.php' );
}

// Plugin updates
woothemes_queue_update( plugin_basename( __FILE__ ), '00000000000000000000000000000000', '99999' ); // TODO: updater keys

// WC active check
if ( ! is_woocommerce_active() ) {
	return;
}

/**
 * The plugin loader class.
 *
 * TODO: Rename the class, and replace all instances of SV_WC_Framework_Plugin_Loader
 * TODO: Update all version numbers and @since tags to the plugin version
 *
 * @since 1.0.0
 */
class YourPhotos {


	/** minimum PHP version required by this plugin */
	const MINIMUM_PHP_VERSION = '5.6.0';

	/** minimum WordPress version required by this plugin */
	const MINIMUM_WP_VERSION = '4.4';

	/** minimum WooCommerce version required by this plugin */
	const MINIMUM_WC_VERSION = '3.0.9';

	/** SkyVerge plugin framework version used by this plugin */
	const FRAMEWORK_VERSION = '5.7.0'; // TODO: framework version

	/** the plugin name, for displaying notices */
	const PLUGIN_NAME = 'WooCommerce Framework Plugin'; // TODO: plugin name


	/** @var YourPhotos single instance of this class // TODO: replace with loader class name */
	private static $instance;

	/** @var array the admin notices to add */
	private $notices = array();


	/**
	 * Constructs the class.
	 *
	 * @since 1.0.0
	 */
	protected function __construct() {

		register_activation_hook(
			__FILE__,
			function() {
				$this->activation_check();
				$this->activate();
			}
		);

		register_deactivation_hook( __FILE__, array( $this, 'deactivate' ) );

		add_action( 'admin_init', array( $this, 'check_environment' ) );
		add_action( 'admin_init', array( $this, 'add_plugin_notices' ) );

		add_action( 'admin_notices', array( $this, 'admin_notices' ), 15 );

		// if the environment check fails, initialize the plugin
		if ( $this->is_environment_compatible() ) {
			add_action( 'plugins_loaded', array( $this, 'init_plugin' ) );
		}
	}

	/**
	 * Triggered on activation
	 *
	 * @since 1.0.0
	 */
	public function activate() {

		add_option( 'your_photos_max_number', 12 );
		add_option( 'your_photos_max_upload_size', 500000 );

		add_rewrite_endpoint( 'your-photos', EP_ROOT | EP_PAGES );
		add_rewrite_rule( '(.?.+?)/your-photos/set-profile(/(.*))?/?$',	'index.php?pagename=$matches[1]&your-photos=1&set-profile=$matches[3]', 'top' );
		add_rewrite_rule( '(.?.+?)/your-photos/delete-photo(/(.*))?/?$', 'index.php?pagename=$matches[1]&your-photos=1&delete-photo=$matches[3]', 'top' );
		add_rewrite_rule( '(.?.+?)/your-photos/edit-photo(/(.*))?/?$', 'index.php?pagename=$matches[1]&your-photos=1&edit-photo=$matches[3]', 'top' );
		flush_rewrite_rules();
	}

	/**
	 * Triggered on deactivation
	 *
	 * @since 1.0.0
	 */
	public function deactivate() {
		flush_rewrite_rules();
	}


	/**
	 * Cloning instances is forbidden due to singleton pattern.
	 *
	 * @since 1.0.0
	 */
	public function __clone() {

		_doing_it_wrong( __FUNCTION__, sprintf( 'You cannot clone instances of %s.', get_class( $this ) ), '1.0.0' );
	}


	/**
	 * Unserializing instances is forbidden due to singleton pattern.
	 *
	 * @since 1.0.0
	 */
	public function __wakeup() {

		_doing_it_wrong( __FUNCTION__, sprintf( 'You cannot unserialize instances of %s.', get_class( $this ) ), '1.0.0' );
	}


	/**
	 * Initializes the plugin.
	 *
	 * @since 1.0.0
	 */
	public function init_plugin() {

		if ( ! $this->plugins_compatible() ) {
			return;
		}

		$this->load_framework();

		/** If the plugin is structured for PSR-4, do the following: */

		// autoload plugin and vendor files
		$loader = require_once( plugin_dir_path( __FILE__ ) . 'vendor/autoload.php' );

		// register plugin namespace with autoloader
		// $loader->addPsr4( 'YourPhotos\\', __DIR__ . '/includes' );

		// depending on how the plugin is structured, you may need to manually load the file that contains the initial plugin function
		require_once( plugin_dir_path( __FILE__ ) . 'includes/Functions.php' );

		/** Otherwise, for plugins that use the traditional WordPress class-class-name.php structure, simply include the main plugin file:

		// load the main plugin class
		require_once( plugin_dir_path( __FILE__ ) . 'class-wc-framework-plugin.php' ); // TODO: main plugin class file
		*/
		// fire it up!
		wc_framework_plugin();
	}


	/**
	 * Loads the base framework classes.
	 *
	 * @since 1.0.0
	 */
	private function load_framework() {

		if ( ! class_exists( '\\SkyVerge\\WooCommerce\\PluginFramework\\' . $this->get_framework_version_namespace() . '\\SV_WC_Plugin' ) ) {
			require_once( plugin_dir_path( __FILE__ ) . 'woocommerce/class-sv-wc-plugin.php' );
		}

		// // TODO: remove this if not a payment gateway
		// if ( ! class_exists( '\\SkyVerge\\WooCommerce\\PluginFramework\\' . $this->get_framework_version_namespace() . '\\SV_WC_Payment_Gateway_Plugin' ) ) {
		// 	require_once( plugin_dir_path( __FILE__ ) . 'woocommerce/payment-gateway/class-sv-wc-payment-gateway-plugin.php' );
		// }
	}


	/**
	 * Gets the framework version in namespace form.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_framework_version_namespace() {

		return 'v' . str_replace( '.', '_', $this->get_framework_version() );
	}


	/**
	 * Gets the framework version used by this plugin.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_framework_version() {

		return self::FRAMEWORK_VERSION;
	}


	/**
	 * Checks the server environment and other factors and deactivates plugins as necessary.
	 *
	 * Based on http://wptavern.com/how-to-prevent-wordpress-plugins-from-activating-on-sites-with-incompatible-hosting-environments
	 *
	 * @internal
	 *
	 * @since 1.0.0
	 */
	public function activation_check() {

		if ( ! $this->is_environment_compatible() ) {

			$this->deactivate_plugin();

			wp_die( self::PLUGIN_NAME . ' could not be activated. ' . $this->get_environment_message() );
		}
	}


	/**
	 * Checks the environment on loading WordPress, just in case the environment changes after activation.
	 *
	 * @internal
	 *
	 * @since 1.0.0
	 */
	public function check_environment() {

		if ( ! $this->is_environment_compatible() && is_plugin_active( plugin_basename( __FILE__ ) ) ) {

			$this->deactivate_plugin();

			$this->add_admin_notice( 'bad_environment', 'error', self::PLUGIN_NAME . ' has been deactivated. ' . $this->get_environment_message() );
		}
	}


	/**
	 * Adds notices for out-of-date WordPress and/or WooCommerce versions.
	 *
	 * @internal
	 *
	 * @since 1.0.0
	 */
	public function add_plugin_notices() {

		if ( ! $this->is_wp_compatible() ) {

			$this->add_admin_notice( 'update_wordpress', 'error', sprintf(
				'%s requires WordPress version %s or higher. Please %supdate WordPress &raquo;%s',
				'<strong>' . self::PLUGIN_NAME . '</strong>',
				self::MINIMUM_WP_VERSION,
				'<a href="' . esc_url( admin_url( 'update-core.php' ) ) . '">', '</a>'
			) );
		}

		if ( ! $this->is_wc_compatible() ) {

			$this->add_admin_notice( 'update_woocommerce', 'error', sprintf(
				'%1$s requires WooCommerce version %2$s or higher. Please %3$supdate WooCommerce%4$s to the latest version, or %5$sdownload the minimum required version &raquo;%6$s',
				'<strong>' . self::PLUGIN_NAME . '</strong>',
				self::MINIMUM_WC_VERSION,
				'<a href="' . esc_url( admin_url( 'update-core.php' ) ) . '">', '</a>',
				'<a href="' . esc_url( 'https://downloads.wordpress.org/plugin/woocommerce.' . self::MINIMUM_WC_VERSION . '.zip' ) . '">', '</a>'
			) );
		}
	}


	/**
	 * Determines if the required plugins are compatible.
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	private function plugins_compatible() {

		return $this->is_wp_compatible() && $this->is_wc_compatible();
	}


	/**
	 * Determines if the WordPress compatible.
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	private function is_wp_compatible() {

		if ( ! self::MINIMUM_WP_VERSION ) {
			return true;
		}

		return version_compare( get_bloginfo( 'version' ), self::MINIMUM_WP_VERSION, '>=' );
	}


	/**
	 * Determines if the WooCommerce compatible.
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	private function is_wc_compatible() {

		if ( ! self::MINIMUM_WC_VERSION ) {
			return true;
		}

		return defined( 'WC_VERSION' ) && version_compare( WC_VERSION, self::MINIMUM_WC_VERSION, '>=' );
	}


	/**
	 * Deactivates the plugin.
	 *
	 * @internal
	 *
	 * @since 1.0.0
	 */
	protected function deactivate_plugin() {

		deactivate_plugins( plugin_basename( __FILE__ ) );

		if ( isset( $_GET['activate'] ) ) {
			unset( $_GET['activate'] );
		}
	}


	/**
	 * Adds an admin notice to be displayed.
	 *
	 * @since 1.0.0
	 *
	 * @param string $slug the slug for the notice
	 * @param string $class the css class for the notice
	 * @param string $message the notice message
	 */
	private function add_admin_notice( $slug, $class, $message ) {

		$this->notices[ $slug ] = array(
			'class'   => $class,
			'message' => $message
		);
	}


	/**
	 * Displays any admin notices added with \SV_WC_Framework_Plugin_Loader::add_admin_notice()
	 *
	 * @internal
	 *
	 * @since 1.0.0
	 */
	public function admin_notices() {

		foreach ( (array) $this->notices as $notice_key => $notice ) {

			?>
			<div class="<?php echo esc_attr( $notice['class'] ); ?>">
				<p><?php echo wp_kses( $notice['message'], array( 'a' => array( 'href' => array() ) ) ); ?></p>
			</div>
			<?php
		}
	}


	/**
	 * Determines if the server environment is compatible with this plugin.
	 *
	 * Override this method to add checks for more than just the PHP version.
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	private function is_environment_compatible() {

		return version_compare( PHP_VERSION, self::MINIMUM_PHP_VERSION, '>=' );
	}


	/**
	 * Gets the message for display when the environment is incompatible with this plugin.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	private function get_environment_message() {

		return sprintf( 'The minimum PHP version required for this plugin is %1$s. You are running %2$s.', self::MINIMUM_PHP_VERSION, PHP_VERSION );
	}


	/**
	 * Gets the main \SV_WC_Framework_Plugin_Loader instance.
	 *
	 * Ensures only one instance can be loaded.
	 *
	 * @since 1.0.0
	 *
	 * @return \SV_WC_Framework_Plugin_Loader
	 */
	public static function instance() {

		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}


}

// fire it up!
YourPhotos::instance();
