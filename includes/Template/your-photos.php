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
		<!-- <img src="<?php echo plugin_dir_url( __DIR__ ) . "Assets/empty-profile.png"; ?>"> -->
		<img src="http://lorempixel.com/320/280">
		<div class="caption-bar">
				<div class="categories">
					<div class="title">Featured product:</div>
					T-shirts
				</div>
				<div class="edit">
					<a href='#'><span class="dashicons dashicons-edit"></span></a>
					<a href='#'><span class="dashicons dashicons-trash"></span></a>
				</div>
		</div>


		<form action="" method="post" enctype="multipart/form-data">
			<h3 class="upload">Add a new picture:</h3>
			<div class="radio-container">
				<h4>What's the featured item?</h4>
				<div class="radios">
					<input type='radio' name='category' value='' > T-shirts
				</div>
				<div class="radios">
					<input type='radio' name='category' value='' > Sweaters
				</div>
				<div class="radios">
					<input type='radio' name='category' value='' > Shoes
				</div>
				<div class="radios">
					<input type='radio' name='category' value='' > Hats
				</div>
				<div class="radios">
					<input type='radio' name='category' value='' > Sox
				</div>
				<div class="radios">
					<input type='radio' name='category' value='' > Coats
				</div>
				<div class="radios">
					<input type='radio' name='category' value='' > Hoodies
				</div>
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
		<?php for ( $i = 1; $i <= rand( 0, 12 ); $i++ ) : ?>
		<div class="photo">
			<img src="<?php echo "http://lorempixel.com/320/280/people/$i"; ?>" alt="<?php //echo 'some SEO sorcery' ?>">
			<div class="caption-bar">
				<div class="categories">
					<div class="title">Featured product:</div>
						T-shirts
					</div>
				<div class="edit">
					<a href='#'><span class="dashicons dashicons-edit"></span></a>
					<a href='#'><span class="dashicons dashicons-trash"></span></a>
				</div>
			</div>
			<div class="make-profile"><a href="#">Set as profile picture</a></div>
		</div>
		<?php endfor; ?>
	</div>
</div>

<?php do_action( 'your_photos_after' ); ?>
