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

namespace YourPhotos\Controllers;

use YourPhotos\Classes\CustomerPictureBook;


defined( 'ABSPATH' ) || exit;

/**
 * @since 1.0.0
 */
class PictureBookController {

	/** @var CustomerPictureBook */
	private $picture_book;

	/**
	 * Initializes the plugin
	 *
	 * @since 1.0
	 *
	 * @return null
	 */
	public function __construct() {

		$this->picture_book = new CustomerPictureBook();

		// adding actions
		add_filter( 'the_title', array( $this, 'your_photos_endpoint_title' ) );
		add_filter( 'query_vars', array( $this, 'your_photos_query_vars' ), 0 );

		add_action( 'woocommerce_account_menu_items', array( $this, 'add_your_photos_menu_item' ) );
		add_action( 'woocommerce_account_your-photos_endpoint', array( $this, 'your_photos_page' ) );

		add_filter( 'woocommerce_checkout_create_order', array( $this, 'picture_on_order' ) );
		add_action( 'wp_enqueue_scripts', function () {
			wp_enqueue_style( 'dashicons' );
			wp_enqueue_style(
				'your-photos',
				plugin_dir_url( __FILE__ ) . '../Assets/your-photos.css',
				array(),
				'1.0',
				'all'
			);
		});

	}

	/**
	 * Adds picture and category on order metadata
	 *
	 * @since 1.0
	 *
	 * @return null
	 */
		public function picture_on_order( $order ) {

		if ( ! is_null( $this->picture_book->get_featured() ) ) {
			$order->add_meta_data(
				'user-picture',
				$this->picture_book->get_featured()->url
			);

			$order->add_meta_data(
				'user-photo-category-picture',
				$this->picture_book->get_featured()->category
			);
		}

	}


	/**
	 * Changes the endpoint page title accordingly to the subsections
	 *
	 * @since 1.0
	 *
	 * @return null
	 */
	public function your_photos_endpoint_title( $title ) {
		global $wp_query;

		// New page title.
		if ( $this->is_subsection( 'your-photos' ) ) {
			// New page title.
			$title = __( 'Your photos', 'your-photos' );
			remove_filter( 'the_title', 'your_photos_endpoint_title' );
		}

		return $title;
	}

	/**
	 * Checks if we are in a given subsection
	 *
	 * @since 1.0
	 *
	 * @return null
	 */
	private function is_subsection( $slug ) {

		global $wp_query;

		return isset( $wp_query->query_vars[ $slug ] )
		&&
		! is_admin()
		&&
		is_main_query()
		&&
		in_the_loop()
		&&
		is_account_page();
	}

	// query vars
	public function your_photos_query_vars( $vars )
	{
		$vars['your-photos']  = 'your-photos';
		$vars['set-profile']  = 'set-profile';
		$vars['delete-photo'] = 'delete-photo';
		$vars['edit-photo']   = 'edit-photo';
		return $vars;
	}

	/**
	 * Adds a link to the photos page in the user account page menu
	 *
	 * @since 1.0
	 *
	 * @return null
	 */

	public function add_your_photos_menu_item( $items )
	{
		$first_item = array_shift( $items );
		$items      = array_merge(
			array( 'your-photos' => __( 'Your photos', 'your-photos' ) ),
			$items
		);
		array_unshift( $items, $first_item );

		return $items;
	}


	/**
	 * Displays the "your photos" dashboard to the end user
	 *
	 * @since 1.0
	 */
	public function your_photos_page( $value )
	{
		global $wp_query;

		/** new photo */
		if (
			isset( $_POST['customer-photo'] )
			&&
			wp_verify_nonce( $_POST['customer-photo'], 'add-customer-photo' )
			&&
			isset( $_FILES['pic'] )
			&&
			$this->can_upload()
			) {
				$this->picture_book->add( $_FILES['pic'], $_POST['category'] );
		}

		/** set profile */
		if ( $this->is_subsection( 'set-profile' ) ) {
			$this->picture_book->set_featured( $wp_query->query_vars['set-profile'] );
			// $this->redirect();
		}

		/** delete photo */
		if ( $this->is_subsection( 'delete-photo' ) ) {
			$this->picture_book->delete( $wp_query->query_vars['delete-photo'] );
			// $this->redirect();
		}

		/** edit photo */
		if ( $this->is_subsection( 'edit-photo' ) ) {
			// TODO
			// $this->redirect();
		}

		$categories = array_map(
			function ( $category ) {
				return $category->name;
			},
			get_terms( 'product_cat' )
		);

		wc_get_template(
			'your-photos.php',
			array(
				'featured'   => $this->picture_book->get_featured(),
				'images'     => $this->picture_book->get_pictures(),
				'categories' => $categories,
			),
			'',
			plugin_dir_path( __FILE__ ) . '../Template/'
		);
	}

	private function can_upload() {
		$uploaded_pics_count = count( $this->picture_book->get_pictures() );
		if ( ! is_null( $this->picture_book->get_featured() ) ) {
			$uploaded_pics_count++;
		}

		return get_option( 'your_photos_max_number' ) > $uploaded_pics_count;
	}

	private function redirect() {
		\wp_safe_redirect(
			add_query_arg(
				'your-photos',
				1,
				get_permalink(
					get_option( 'woocommerce_myaccount_page_id' )
				)
			)
		);

		exit;
	}
}
