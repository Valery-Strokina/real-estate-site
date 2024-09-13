<?php
/**
 * The template for displaying all single posts
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

			<?php
			// Do the left sidebar check and open div#primary.
			get_template_part( 'global-templates/left-sidebar-check' );
			?>

			<main class="site-main col-md-8" id="main">

				<?php
				while ( have_posts() ) {
					the_post();

					// Custom post title and metadata
					echo '<div class="post-header text-center" style="padding: 30px 0;">';
					echo '<h1 class="post-title" style="font-size: 2.5em; margin-bottom: 10px;">' . get_the_title() . '</h1>';
					echo '<p class="post-meta" style="color: #888;">Published on ' . get_the_date() . '</p>';
					echo '</div>';

					// Display the content using the original content-single.php file
					get_template_part( 'loop-templates/content', 'single' );

					// Post navigation for next/previous posts
					understrap_post_nav();

					// Display comments section if enabled
					if ( comments_open() || get_comments_number() ) {
						comments_template();
					}
				}
				?>

			</main><!-- #main -->

			<?php
			// Do the right sidebar check and close div#primary.
			get_template_part( 'global-templates/right-sidebar-check' );
			?>

		</div><!-- .row -->

	</div><!-- #content -->

</div><!-- #single-wrapper -->

<?php
get_footer();
?>


