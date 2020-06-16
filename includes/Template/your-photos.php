<?php
/**
 * Your pictures
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
 * @package   Your Photos
 */

defined( 'ABSPATH' ) || exit;

do_action( 'your_photos_before', $featured, $images ); ?>


<div class="your_photos your_photos-MyAccount">
	<div class="featured">
		<h3>Profile picture</h3>
		<?php if ( empty( $featured ) ) : ?>
		<img src="<?php echo plugin_dir_url( __DIR__ ) . "Assets/empty-profile.png"; ?>">
		<?php else : ?>
		<img src="<?php echo $featured->url; ?>">
		<div class="caption-bar">
				<div class="categories">
					<div class="title">Featured product:</div>
					<?php echo $featured->category; ?>
				</div>
				<div class="edit">
					<a href='<?php echo your_photos_get_account_action_permalink( 'edit-photo', $featured->id ); ?>'><span class="dashicons dashicons-edit"></span></a>
					<a href='<?php echo your_photos_get_account_action_permalink( 'delete-photo', $featured->id ); ?>'><span class="dashicons dashicons-trash"></span></a>
				</div>
		</div>


		<?php endif; ?>
		<form action="<?php the_permalink(); ?>your-photos" method="post" enctype="multipart/form-data">
		<?php wp_nonce_field( 'add-customer-photo', 'customer-photo' ); ?>
			<h3 class="upload">Add a new picture:</h3>
			<div class="radio-container">
				<h4>What's the featured item?</h4>
				<?php foreach ( $categories as $category ) : ?>
				<div class="radios">
					<input type='radio' name='category' value='<?php echo htmlentities( $category ); ?>' > <?php echo htmlentities( $category ); ?>
				</div>
				<?php endforeach; ?>
			</div>
			<label for="pic">
			Chose file<span class="dashicons dashicons-camera-alt"> </span>
			<input type="file" name="pic" id="pic">
			</label>
			<input type="submit" value="Upload image" name="submit">
		</form>

	</div>
	<div class="photo-roll">

		<h3>Other uploads</h3>
		<?php foreach ( $images as $image) : ?>
		<div class="photo">
			<img src="<?php echo $image->url; ?>" alt="<?php //echo 'some SEO sorcery' ?>">
			<div class="caption-bar">
				<div class="categories">
					<div class="title">Featured product:</div>
						<?php echo $image->category; ?>
					</div>
				<div class="edit">
					<a href='<?php echo your_photos_get_account_action_permalink( 'edit-photo', $image->id ); ?>'><span class="dashicons dashicons-edit"></span></a>
					<a href='<?php echo your_photos_get_account_action_permalink( 'delete-photo', $image->id ); ?>'><span class="dashicons dashicons-trash"></span></a>
				</div>
			</div>
			<div class="make-profile"><a href="<?php echo your_photos_get_account_action_permalink( 'set-profile', $image->id ); ?>">Set as profile picture</a></div>
		</div>
		<?php endforeach; ?>
	</div>
</div>

<?php do_action( 'your_photos_after' ); ?>
