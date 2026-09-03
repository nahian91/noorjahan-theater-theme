<?php
/**
 * Template Name: Upcoming Movies Page
 *
 * @package Noorjahan_Movie_Theater
 */

get_header(); 

global $wpdb;
$table_movies = $wpdb->prefix . 'bsc_control_movies';

// Fetch movies marked as 'Upcoming' from database
$upcoming_movies = $wpdb->get_results( 
    $wpdb->prepare( "SELECT * FROM {$table_movies} WHERE category = %s ORDER BY id DESC", 'Upcoming' ) 
);
?>

<main id="primary" class="site-main">

  <section class="ifs-cinema-contact-hero">
    <div class="ifs-cinema-container">
      <span class="ifs-cinema-badge ifs-cinema-badge-amber"><?php esc_html_e( 'COMING SOON', 'noorjahan-movie-theater' ); ?></span>
      <h1><?php esc_html_e( 'Upcoming Movie Releases & Previews', 'noorjahan-movie-theater' ); ?></h1>
      <p>
        <?php esc_html_e( 'Explore our upcoming blockbusters lineup at Noorjahan Grand and set premiere reminders for the most anticipated cinematic arrivals.', 'noorjahan-movie-theater' ); ?>
      </p>
    </div>
  </section>

  <section class="ifs-cinema-section-padding" id="upcoming">
    <div class="ifs-cinema-container">

      <div class="ifs-cinema-upcoming-grid-refined">
        
        <?php if ( ! empty( $upcoming_movies ) ) : ?>
          <?php foreach ( $upcoming_movies as $movie ) : 
            $poster_url = ! empty( $movie->poster_url ) ? esc_url( $movie->poster_url ) : 'https://images.unsplash.com/photo-1518709268805-4e9042af9f23?w=600&auto=format&fit=crop&q=80';
            $title      = ! empty( $movie->title ) ? esc_html( $movie->title ) : 'Upcoming Feature';
            $genre      = ! empty( $movie->genre ) ? esc_html( $movie->genre ) : 'Cinematic Release';
            $short_desc = ! empty( $movie->short_description ) ? esc_html( $movie->short_description ) : '';
          ?>
            <div class="ifs-cinema-upcoming-card-pro" style="cursor: default;">
              <div class="ifs-cinema-upcoming-poster-box">
                <img src="<?php echo $poster_url; ?>" 
                     alt="<?php echo esc_attr( $title ); ?>"
                     onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1518709268805-4e9042af9f23?w=600&auto=format&fit=crop&q=80';">
                <span class="ifs-cinema-upcoming-date-badge"><?php esc_html_e( 'COMING SOON', 'noorjahan-movie-theater' ); ?></span>
              </div>
              <div class="ifs-cinema-upcoming-details-box">
                <span style="font-size:0.75rem; color:var(--ifs-cinema-brand-amber); font-weight:800; text-transform:uppercase;"><?php echo $genre; ?></span>
                <h3 style="color:#fff; font-size:1.25rem; font-weight:800; margin:0.35rem 0 0.45rem;"><?php echo $title; ?></h3>
                <?php if ( ! empty( $short_desc ) ) : ?>
                  <p style="font-size:0.85rem; color:var(--ifs-cinema-text-secondary); margin:0; line-height:1.5;"><?php echo $short_desc; ?></p>
                <?php endif; ?>
              </div>
            </div>
          <?php endforeach; ?>
        <?php else : ?>
          <div style="grid-column: 1 / -1; text-align: center; padding: 3rem; color: var(--ifs-cinema-text-muted);">
            <p><?php esc_html_e( 'No upcoming movies scheduled at the moment. Please check back soon!', 'noorjahan-movie-theater' ); ?></p>
          </div>
        <?php endif; ?>

      </div>
    </div>
  </section>

</main>

<?php get_footer(); ?>