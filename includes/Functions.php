<?php
/**
 * WooCommerce Framework Plugin
 *
 * This source file is subject to the GNU General Public License v3.0
 * that is bundled with this package in the file license.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.html
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@skyverge.com so we can send you a copy immediately.
 *
 * @author    SkyVerge
 * @copyright Copyright (c) 2014-2019, SkyVerge, Inc.
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3.0
 */

use YourPhotos\Plugin;

/**
 * @since 1.0.0
 */
function wc_framework_plugin() {

	return Plugin::instance();
}

function your_photos_get_account_action_permalink( $action, $id ) {

	if ( in_array( $action, array( 'set-profile', 'edit-photo', 'delete-photo' ), true ) ) {
		$permalink = get_permalink( get_option( 'woocommerce_myaccount_page_id' ) );
		if ( '' !== get_option( 'permalink_structure' ) ) {
			// using pretty permalinks, append to url
			return user_trailingslashit( $permalink . 'your-photos/' . $action . '/' . $id );

		} else {

			return add_query_arg(
				array(
					'your-photos' => 1,
					$action       => $id,
				),
				$permalink
			);
		}
	}
}
