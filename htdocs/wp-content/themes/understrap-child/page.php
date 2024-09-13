<?php
/**
 * The template for displaying all pages
 *
 * @package Understrap
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

get_header();
$container = get_theme_mod( 'understrap_container_type' );
?>

<div class="wrapper" id="page-wrapper">

	<div class="<?php echo esc_attr( $container ); ?>" id="content" tabindex="-1">

		<div class="row justify-content-center">

			<main class="site-main col-md-8" id="main">

				<?php
				while ( have_posts() ) {
					the_post();

					// Custom page title section with a background
					echo '<div class="page-header text-center" style="padding: 50px 0; background-color: #f7f7f7;">';
					echo '<h1 class="page-title" style="font-size: 2.5em; margin: 0;">' . get_the_title() . '</h1>';
					echo '</div>';

					// Page content with shortcode processing
					echo '<div class="page-content" style="padding: 20px;">';
					the_content();
					echo '</div>';

					// Show comments section if enabled
					if ( comments_open() || get_comments_number() ) {
						comments_template();
					}
				}
				?>

			</main><!-- #main -->

		</div><!-- .row -->

	</div><!-- #content -->

</div><!-- #page-wrapper -->

<?php get_footer(); ?>
