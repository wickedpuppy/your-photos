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

use YourPhotos\Classes\CustomerPicture;
use YourPhotos\Classes\ImgHandler;

/**
 * Picture Book Class
 *
 * Collects and manages customer's pictures
 */
class CustomerPictureBook {

	/**
	 * Customer pictures
	 *
	 * @var [CustomerPicture]
	 */
	private $pictures;

	/**
	 * Featured customer picture
	 *
	 * @var CustomerPicture
	 */
	private $featured;

	/**
	 * User
	 *
	 * @var User
	 */
	protected $user;

	/**
	 * Constructs the plugin.
	 *
	 * @param int $user_id user id.
	 *
	 * @since 1.0
	 */
	public function __construct( $user_id = null ) {

	}

	/**
	 * Sets the featured image
	 *
	 * @param int $image_id user id.
	 *
	 * @since 1.0
	 */
	public function set_featured( $id ) {
	}

	/**
	 * Gets the featured image
	 *
	 * @since 1.0
	 */
	public function get_featured() {
	}

	/**
	 * Gets all the pictures.
	 *
	 * @since 1.0
	 */
	public function get_pictures() {
	}

	/**
	 * Adds a piture into the picture book.
	 *
	 * @param CustomerPicture $pic
	 *
	 * @param string $category.
	 *
	 * @since 1.0
	 */
	public function add( $pic, $category ) {
	}

	/**
	 * Deletes the picture from the picture book
	 *
	 * @since 1.0
	 */
	public function delete ( $id ) {
	}
}
