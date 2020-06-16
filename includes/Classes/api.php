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
 * Takes care of the custom api endpoints
 */
class Api {

	/** GET */
	public function read( $data ) {
		global $wpdb;

		$id = $data->get_param( 'id' );

		if ( is_null( $data->get_param( 'id' ) ) ) {
			$pictures_raw = $wpdb->get_results(
				"SELECT umeta_id, meta_value
				FROM {$wpdb->prefix}usermeta
				where meta_key = 'your-photos-image'"
			);

			$pictures = array();
			foreach ( $pictures_raw as $p ) {
				$p->meta_value = unserialize( $p->meta_value );
				array_push( $pictures, $this->process( $p ) );
			}

			return new \WP_REST_Response( $pictures,200 );
		}

		$picture = get_metadata_by_mid( 'user', $id );

		return new \WP_REST_Response( $this->process( $picture ), 200 );
	}

	private function process( $picture ) {
		return array(
			'id'       => $picture->umeta_id,
			'name'     => $picture->meta_value['name'],
			'filetype' => $picture->meta_value['type'],
			'user'     => $picture->meta_value['user']->ID,
			'url'      => $picture->meta_value['url'],
		);
	}

	public function check_permissions () {
		return true;
		return is_user_logged_in();
	}
}
