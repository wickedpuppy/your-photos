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

	public function uploadImg( $img ) {
	}

	/** renames the image with an unique and sanitized name */
	private function rename( $img ) {
		return time();
	}
}
