<?php

/**
 * Your Photos
 *
 * This source file is subject to the GNU General Public License v3.0
 * that is bundled with this package in the file license.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.html
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@skyverge.com so we can send you a copy immediately.
 *
 * @author    Enis Trevisi
 * @copyright Copyright (c) 2020, Enis Trevisi
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3.0
 * @package   YourPhotos
 */

namespace YourPhotos;

use SkyVerge\WooCommerce\PluginFramework\v5_7_1 as Framework;
use YourPhotos\Psr4Test;

defined('ABSPATH') || exit;

/**
 * @since 1.0.0
 */
class Plugin extends Framework\SV_WC_Plugin
{


	/** @var Plugin */
	protected static $instance;

	/** Plugin version number */
	const VERSION = '1.0.0';

	/** Plugin id */
	const PLUGIN_ID = 'your-photos';

	/**
	 * Constructs the plugin.
	 *
	 * @since 1.0
	 */
	public function __construct()
	{

		parent::__construct(
			self::PLUGIN_ID,
			self::VERSION,
			array(
				'text_domain' => 'your-photos',
			)
		);
		add_action( 'init', array( $this, 'init' ) );
	}

	/**
	 * Initializes the plugin
	 *
	 * @since 1.0
	 *
	 * @return null
	 */
	public function init() {
	}



	/**
	 * Gets the full path and filename of the plugin file.
	 *
	 * @since 1.0.0
	 *
	 * @return string the full path and filename of the plugin file
	 */
	protected function get_file()
	{

		return __FILE__;
	}


	/**
	 * Gets the plugin full name including "WooCommerce", ie "WooCommerce X".
	 *
	 * @since 1.0.0
	 *
	 * @return string plugin name
	 */
	public function get_plugin_name()
	{
		return __('Your Photos', 'your-photos');
	}

	/**
	 * Gets the main instance of Framework Plugin instance.
	 *
	 * Ensures only one instance is/can be loaded.
	 *
	 * @since 1.0.0
	 *
	 * @return Plugin
	 */
	public static function instance()
	{

		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}
}
