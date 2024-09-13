<?php
/**
 * The template for displaying all single real estate objects
 *
 * @package Understrap
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

get_header();
$container = get_theme_mod( 'understrap_container_type' );
?>

<div class="wrapper" id="single-wrapper">

	<div class="<?php echo esc_attr( $container ); ?>" id="content" tabindex="-1">

		<div class="row justify-content-center">

			<main class="site-main col-md-8" id="main">

				<?php
				while ( have_posts() ) {
					the_post();

					// Custom real estate object title
					echo '<div class="real-estate-header text-center" style="padding: 30px 0; background-color: #f7f7f7;">';
					echo '<h1 class="real-estate-title" style="font-size: 2.5em;">' . get_the_title() . '</h1>';
					echo '</div>';

					// ACF Custom Fields for Real Estate Object
					if ( function_exists('get_field') ) {
						$house_name = get_field('house_name');
						$location_coordinates = get_field('location_coordinates');
						$number_of_floors = get_field('number_of_floors');
						$type_of_building = get_field('type_of_building');
						$environmentalism = get_field('environmentalism');
						$premises = get_field('premises');

						// Display ACF fields in a structured layout
						echo '<div class="real-estate-details" style="padding: 20px;">';
						echo '<h2>Property Details</h2>';
						echo '<p><strong>House Name:</strong> ' . esc_html($house_name) . '</p>';
						echo '<p><strong>Location Coordinates:</strong> ' . esc_html($location_coordinates) . '</p>';
						echo '<p><strong>Number of Floors:</strong> ' . esc_html($number_of_floors) . '</p>';
						echo '<p><strong>Type of Building:</strong> ' . esc_html($type_of_building) . '</p>';
						echo '<p><strong>Environmentalism:</strong> ' . esc_html($environmentalism) . '</p>';
						
						// Premises Details (nested custom field group)
						if ( $premises ) {
							echo '<h3>Premises Details</h3>';
							echo '<p><strong>Area:</strong> ' . esc_html($premises['area']) . '</p>';
							echo '<p><strong>Number of Rooms:</strong> ' . esc_html($premises['number_of_rooms']) . '</p>';
							echo '<p><strong>Balcony:</strong> ' . esc_html($premises['balcony']) . '</p>';
							echo '<p><strong>Bathroom:</strong> ' . esc_html($premises['bathroom']) . '</p>';
							
							// Display premises image
							$premises_image = $premises['premises_image'];
							if ( $premises_image ) {
								echo '<img src="' . esc_url($premises_image['url']) . '" alt="' . esc_attr($premises_image['alt']) . '" style="max-width:100%; height:auto;">';
							}
						}

						echo '</div>';
					}

					// Real estate object content
					echo '<div class="real-estate-content" style="padding: 20px;">' . get_the_content() . '</div>';

					// Comments section if enabled
					if ( comments_open() || get_comments_number() ) {
						comments_template();
					}
				}
				?>

			</main><!-- #main -->

		</div><!-- .row -->

	</div><!-- #content -->

</div><!-- #single-wrapper -->

<?php get_footer(); ?>
