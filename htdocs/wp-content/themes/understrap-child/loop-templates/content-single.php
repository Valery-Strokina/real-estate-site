<?php
/**
 * Single post partial template
 *
 * @package Understrap
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;
?>

<article <?php post_class(); ?> id="post-<?php the_ID(); ?>">

	<header class="entry-header">

		<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>

		<div class="entry-meta">
			<?php understrap_posted_on(); ?>
		</div><!-- .entry-meta -->

	</header><!-- .entry-header -->

	<?php echo get_the_post_thumbnail( $post->ID, 'large' ); ?>

	<div class="entry-content">

		<?php
		the_content();

		// Display ACF fields
		if (function_exists('get_field')) {
			// House Name
			$house_name = get_field('house_name');
			if ($house_name) {
				echo '<p><strong>House Name:</strong> ' . esc_html($house_name) . '</p>';
			}

			// Location Coordinates
			$location_coordinates = get_field('location_coordinates');
			if ($location_coordinates) {
				echo '<p><strong>Location Coordinates:</strong> ' . esc_html($location_coordinates) . '</p>';
			}

			// Number of Floors
			$number_of_floors = get_field('number_of_floors');
			if ($number_of_floors) {
				echo '<p><strong>Number of Floors:</strong> ' . esc_html($number_of_floors) . '</p>';
			}

			// Type of Building
			$type_of_building = get_field('type_of_building');
			if ($type_of_building) {
				echo '<p><strong>Type of Building:</strong> ' . esc_html($type_of_building) . '</p>';
			}

			// Environmentalism
			$environmentalism = get_field('environmentalism');
			if ($environmentalism) {
				echo '<p><strong>Environmentalism:</strong> ' . esc_html($environmentalism) . '</p>';
			}

			// Images
			$images = get_field('images');
			if ($images) {
				echo '<p><strong>Images:</strong><br>';
				echo '<img src="' . esc_url($images['url']) . '" alt="' . esc_attr($images['alt']) . '" width="300" /></p>';
			}

			// Premises Details
			$premises = get_field('premises');
			if ($premises) {
				echo '<h3>Premises Details</h3>';
				echo '<p><strong>Area:</strong> ' . esc_html($premises['area']) . '</p>';
				echo '<p><strong>Number of Rooms:</strong> ' . esc_html($premises['number_of_rooms']) . '</p>';
				echo '<p><strong>Balcony:</strong> ' . esc_html($premises['balcony']) . '</p>';
				echo '<p><strong>Bathroom:</strong> ' . esc_html($premises['bathroom']) . '</p>';
				
				// Premises Image
				$premises_image = $premises['premises_image'];
				if ($premises_image) {
					echo '<p><strong>Premises Image:</strong><br>';
					echo '<img src="' . esc_url($premises_image['url']) . '" alt="' . esc_attr($premises_image['alt']) . '" width="300" /></p>';
				}
			}
		}

		understrap_link_pages();
		?>

	</div><!-- .entry-content -->

	<footer class="entry-footer">
		<?php understrap_entry_footer(); ?>
	</footer><!-- .entry-footer -->

</article><!-- #post-<?php the_ID(); ?> -->



