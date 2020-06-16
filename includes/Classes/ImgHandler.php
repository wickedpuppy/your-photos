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

/**
 * Handles image upload
 */
class ImgHandler {

	private $uploadDir;

	public function __construct() {
		$this->uploadDir =  wp_upload_dir();
	}


	public function uploadImg( $img ) {
		if (
			in_array( $img['type'], array ('image/gif', 'image/jpeg', 'image/png' ))
			&&
			( $img['size'] <= get_option( 'your_photos_max_upload_size' ) )
		) {

			$imgName = $this->rename( $img );
			$path = $this->uploadDir['path'] . '/' . $imgName;

			if ( move_uploaded_file($img['tmp_name'], $path) ) {
				return array(
					'valid' => true,
					'path'  => str_replace( '\\', '/', $path ),
					'name'  => $imgName,
					'type'  => $img['type'],
					'url'   => $this->uploadDir['url'] . '/' . $imgName,
				);
			}
		}

		return array( 'valid' => false );
	}

	/** renames the image with an unique and sanitized name */
	private function rename( $img ) {
		return  time() . wp_unslash( $img['name'] );;
	}
}
