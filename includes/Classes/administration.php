<?php
/**
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
 * @package   Your Photos
*/

namespace YourPhotos\Classes;

use YourPhotos\Classes\CustomerPictureBook;

/**
 * Administrative tools
 *
 * Collects and manages customer's pictures
 */
class Administration {

	private $settings;

	public function __construct() {

		// "Max number of photos" tab settings.
		$this->settings = array(
			'title'       => array(
				'name' => __( 'Profile Images Settings', 'YourPhotos' ),
				'type' => 'title',
				'desc' => '',
				'id'   => 'your_photos_admin_tab_title',
			),
			'max-number'  => array(
				'name'    => __( 'Maximum number of profile images', 'YourPhotos' ),
				'type'    => 'number',
				'default' => 8,
				'desc'    => '',
				'id'      => 'your_photos_max_number',
			),
			'max-upload'  => array(
				'name'    => __( 'Maximum profile image size (in bytes)', 'YourPhotos' ),
				'type'    => 'number',
				'default' => 500000,
				'desc'    => '',
				'id'      => 'your_photos_max_upload_size',
			),
			'section_end' => array(
				'type' => 'sectionend',
				'id'   => 'your_photos_admin_tab_title_section_end',
			),
		);

		// "Max number of photos" tab in settings page.
		add_filter( 'woocommerce_settings_tabs_array', array( $this, 'register_your_photos_tab' ), 120 );
		add_action( 'woocommerce_settings_tabs_your-photos', array( $this, 'your_photos_tab' ) );
		add_action( 'woocommerce_update_options_your-photos', array( $this, 'update_picture_settings' ) );

		// extra columns in order table.
		add_filter( 'manage_edit-shop_order_columns', array( $this, 'order_table_columns' ), 20 );
		add_action( 'manage_shop_order_posts_custom_column', array( $this, 'add_picture' ) );

		// pictures on user profile
		add_filter( 'show_user_profile', array( $this, 'show_pictures' ), 20 );
		add_filter( 'edit_user_profile', array( $this, 'show_pictures' ), 20 );

	}

	/**
	 * Registers the photo tab
	 *
	 * $tab the tab
	 *
	 * @since 1.0
	 */
	public function register_your_photos_tab( $tabs ) {
		$tabs['your-photos'] = __( 'Customers Photos Settings', 'YourPhotos' );
		return $tabs;
	}

	/**
	 * The tab
	 *
	 * @since 1.0
	 */
	public function your_photos_tab() {
		woocommerce_admin_fields( $this->settings );
	}

	/**
	 * Reorders the tab columns
	 *
	 * $cols the columns
	 *
	 * @since 1.0
	 */
	public function order_table_columns( $cols ) {
		$cols['user-photo'] = __( 'User profile picture', 'YourPhotos' );
		$cols['user-photo-category-picture'] = __( 'Categories of item in pic', 'YourPhotos' );
		return $cols;
	}

	/**
	 * Adds a picture into the admin table cell
	 *
	 * $col the column
	 *
	 * @since 1.0
	 */
	public function add_picture( $col ) {
		global $post;
		$order = wc_get_order( $post->ID );

		if ( 'user-photo' === $col ) {
			$pic = $order->get_meta( 'user-picture' );

			if ( empty( $pic ) ) {
				$this->img_tag( plugin_dir_url( __DIR__ ) . 'Assets/empty-profile.png' );
				return;
			}

			$this->img_tag ( $pic );
		}

		if ( 'user-photo-category-picture' === $col ) {
			$category = $order->get_meta( 'user-photo-category-picture' );

			if ( empty( $category ) ) {
				_e( 'none', 'YourPhotos' );
				return;
			}

			// return "<img src='". plugin_dir_url( __DIR__ ) . $pic['url'] . " style='heigt: 120px; width: auto'>";
			echo \htmlentities( $category );
		}
	}

	/**
	 * Returns the user order profile image html tags
	 *
	 * @since 1.0
	 */
	public function img_tag( $url ) {
		echo "<img src='" . $url . "' style='heigt: auto; max-height; 90px; width: 90px'>";
	}

	/**
	 * Replaces the picture setting
	 *
	 * @since 1.0
	 */
	public function update_picture_settings() {
		// TODO: convert in readable format
		woocommerce_update_options( $this->settings );
	}


	/**
	 * Shows pictures in the user profile page
	 *
	 * $user the user
	 *
	 * @since 1.0
	 */
	public function show_pictures( $user ) {
		$output = '';

		$picture_book = new CustomerPictureBook( $user->ID );
		$output      = '<h2>User photos</h2>';
		$output     .= '<h3>Featured Photo</h3>';
		if (! empty( $picture_book->get_featured() )) {
			$output     .= "<img src='" . $picture_book->get_featured()->url . "' style='height: 420px; max-height: 420px; width: auto;'>";
		}
		$output     .= '<h3>Other Photos</h3>';

		if (! empty( $picture_book->get_pictures() )) {
			foreach ( $picture_book->get_pictures() as $picture ) {
				$output .= "<img src='$picture->url' style='height: 240px; max-height: 240px; width: auto; display: inline-block'>";
			}
		}

		echo $output;
	}

}
