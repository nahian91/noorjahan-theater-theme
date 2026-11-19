<?php
/**
 * Template Name: Upcoming Movies Page
 *
 * @package Nahian_Movie_Theater
 */

get_header();

global $wpdb;
$table_movies = $wpdb->prefix . 'nmbp_control_movies';

// Fetch movies marked as 'Upcoming' from database.
$upcoming_movies = array();
if ( $wpdb->get_var( $wpdb->prepare( 'SHOW TABLES LIKE %s', $table_movies ) ) === $table_movies ) {
	$upcoming_movies = $wpdb->get_results(
		$wpdb->prepare( "SELECT * FROM {$table_movies} WHERE category = %s ORDER BY id DESC", 'Upcoming' )
	);
}
?>

<style id="nmt-upcoming-movies-css">
	.nmt-upcoming-card-default {
		cursor: default;
	}
	.nmt-upcoming-genre-label {
		font-size: 0.75rem;
		color: var(--ifs-cinema-brand-amber);
		font-weight: 800;
		text-transform: uppercase;
	}
	.nmt-upcoming-card-title {
		color: #ffffff;
		font-size: 1.25rem;
		font-weight: 800;
		margin: 0.35rem 0 0.45rem;
	}
	.nmt-upcoming-card-desc {
		font-size: 0.85rem;
		color: var(--ifs-cinema-text-secondary);
		margin: 0;
		line-height: 1.5;
	}
	.nmt-upcoming-empty-state {
		grid-column: 1 / -1;
		text-align: center;
		padding: 3rem;
		color: var(--ifs-cinema-text-muted);
	}
</style>

<main id="primary" class="site-main">

	<section class="ifs-cinema-contact-hero">
		<div class="ifs-cinema-container">
			<span class="ifs-cinema-badge ifs-cinema-badge-amber"><?php esc_html_e( 'COMING SOON', 'nahian-movie-theater' ); ?></span>
			<h1><?php esc_html_e( 'Upcoming Movie Releases & Previews', 'nahian-movie-theater' ); ?></h1>
			<p>
				<?php esc_html_e( 'Explore our upcoming blockbusters lineup at Nahian Grand and set premiere reminders for the most anticipated cinematic arrivals.', 'nahian-movie-theater' ); ?>
			</p>
		</div>
	</section>

	<section class="ifs-cinema-section-padding" id="upcoming">
		<div class="ifs-cinema-container">

			<div class="ifs-cinema-upcoming-grid-refined">

				<?php if ( ! empty( $upcoming_movies ) ) : ?>
					<?php
					foreach ( $upcoming_movies as $movie ) :
						$poster_url = ! empty( $movie->poster_url ) ? esc_url( $movie->poster_url ) : 'https://images.unsplash.com/photo-1518709268805-4e9042af9f23?w=600&auto=format&fit=crop&q=80';
						$title      = ! empty( $movie->title ) ? $movie->title : __( 'Upcoming Feature', 'nahian-movie-theater' );
						$genre      = ! empty( $movie->genre ) ? $movie->genre : __( 'Cinematic Release', 'nahian-movie-theater' );
						$short_desc = ! empty( $movie->short_description ) ? $movie->short_description : '';
						?>
						<div class="ifs-cinema-upcoming-card-pro nmt-upcoming-card-default">
							<div class="ifs-cinema-upcoming-poster-box">
								<img src="<?php echo esc_url( $poster_url ); ?>"
									data-fallback-src="https://images.unsplash.com/photo-1518709268805-4e9042af9f23?w=600&auto=format&fit=crop&q=80"
									alt="<?php echo esc_attr( $title ); ?>">
								<span class="ifs-cinema-upcoming-date-badge"><?php esc_html_e( 'COMING SOON', 'nahian-movie-theater' ); ?></span>
							</div>
							<div class="ifs-cinema-upcoming-details-box">
								<span class="nmt-upcoming-genre-label"><?php echo esc_html( $genre ); ?></span>
								<h3 class="nmt-upcoming-card-title"><?php echo esc_html( $title ); ?></h3>
								<?php if ( ! empty( $short_desc ) ) : ?>
									<p class="nmt-upcoming-card-desc"><?php echo esc_html( $short_desc ); ?></p>
								<?php endif; ?>
							</div>
						</div>
					<?php endforeach; ?>
				<?php else : ?>
					<div class="nmt-upcoming-empty-state">
						<p><?php esc_html_e( 'No upcoming movies scheduled at the moment. Please check back soon!', 'nahian-movie-theater' ); ?></p>
					</div>
				<?php endif; ?>

			</div>
		</div>
	</section>

</main>

<script id="nmt-upcoming-movies-js">
	(function() {
		document.addEventListener("DOMContentLoaded", function() {
			document.querySelectorAll("img[data-fallback-src]").forEach(function(img) {
				img.addEventListener("error", function() {
					const fallback = img.getAttribute("data-fallback-src");
					if (fallback && img.getAttribute("src") !== fallback) {
						img.setAttribute("src", fallback);
					}
				}, { once: true });
			});
		});
	})();
</script>

<?php get_footer(); ?>