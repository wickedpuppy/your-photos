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

/** single instance of a customer picture */
class CustomerPicture {

	public $previous_value; // For future editing features
	public $category;
	public $data;
	public $path;
	public $name;
	public $type;
	public $user;
	public $url;
	public $id;

	public function __construct( $data, $user_id ) {
		$key = 'your-photos-image';

		add_user_meta(
			$user_id,
			$key,
			$data
		);

		$this->previous_value = $data;
		$this->category       = $data['category'];
		$this->data           = $data;
		$this->path           = $data['path'];
		$this->name           = $data['name'];
		$this->type           = $data['type'];
		$this->user           = $data['user'];
		$this->url            = $data['url'];
		$this->id             = $data['id'];

	}

	/**
	 * Deletes the picture
	 *
	 * @since 1.0
	 */
	public function delete() {

		delete_user_meta( $this->user->ID, 'your-photos-image', $this->data );

		// if we want to delete the file, too
		// unlink( $this->path );
	}

	/**
	 * Replaces the file
	 *
	 * @since 1.0
	 */
	public function replace_file( $new_file ) {
		// TODO
	}
}
