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

		$user_id = is_null( $user_id ) ? get_current_user_id() : $user_id;
		$pictures = get_user_meta( $user_id, 'your-photos-picture-book', true );

		$this->pictures = isset( $pictures['pictures'] ) ? $pictures['pictures'] : array();
		$this->featured = isset( $pictures['featured'] ) ? $pictures['featured'] : null;
		$this->upload   = new ImgHandler();
		$this->user     = get_user_by( 'id', $user_id );

	}

	/**
	 * Sets the featured image
	 *
	 * @param int $image_id user id.
	 *
	 * @since 1.0
	 */
	public function set_featured( $id ) {
		if ( ! is_null( $this->featured ) ) {
			$this->pictures[] = $this->featured;
		}

		$this->featured = $this->pop_picture( $id );
		$this->save();
	}

	/**
	 * Gets the featured image
	 *
	 * @since 1.0
	 */
	public function get_featured() {
		return $this->featured;
	}

	/**
	 * Gets all the pictures.
	 *
	 * @since 1.0
	 */
	public function get_pictures() {
		return $this->pictures;
	}

	public function pop_picture ( $needle ) {
		foreach ( $this->pictures as $key => $pic ) {
			if ( $needle === $pic->id ) {
				$newFeatured = array_splice( $this->pictures, $key, 1 );
				return $newFeatured[0];
			}
		}
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
		$pic = $this->upload->uploadImg( $pic );
		$pic['category'] = $category;
		$pic['id'] = \uniqid();
		$pic['user'] = $this->user;

		if ( $pic['valid'] ) {
			$newPic = new CustomerPicture( $pic, $this->user->ID );
			$this->pictures[] = $newPic;
			$this->set_featured( $newPic->id );
			$this->save();
		}
	}

	/**
	 * Deletes the picture from the picture book
	 *
	 * @since 1.0
	 */
	public function delete ( $id ) {
		if( !is_null($this->featured) && $this->featured->id === $id ) {
			$this->featured->delete();
			$this->featured = null;
		} else {
			$this->pop_picture( $id )->delete();
		}
		$this->save();
	}

	/**
	 * Saves the picture book
	 *
	 * @since 1.0
	 */
	private function save() {
		update_user_meta(
			$this->user->ID,
			'your-photos-picture-book',
			array(
				'pictures' => $this->pictures,
				'featured' => $this->featured,
			)
		);
	}
}
