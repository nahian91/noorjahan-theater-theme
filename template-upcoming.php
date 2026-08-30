<?php
/**
 * Template Name: Upcoming Movies Page
 *
 * @package Noorjahan_Movie_Theater
 */

get_header(); ?>

<main id="primary" class="site-main">

  <!-- UPCOMING HERO -->
  <section class="ifs-cinema-contact-hero">
    <div class="ifs-cinema-container">
      <span class="ifs-cinema-badge ifs-cinema-badge-amber"><?php esc_html_e( 'COMING SOON', 'noorjahan-movie-theater' ); ?></span>
      <h1><?php esc_html_e( 'Upcoming Movie Releases & Previews', 'noorjahan-movie-theater' ); ?></h1>
      <p>
        <?php esc_html_e( 'Explore our upcoming blockbusters lineup at Noorjahan Grand and set premiere reminders for the most anticipated cinematic arrivals.', 'noorjahan-movie-theater' ); ?>
      </p>
    </div>
  </section>

  <!-- UPCOMING MOVIES GRID -->
  <section class="ifs-cinema-section-padding" id="upcoming">
    <div class="ifs-cinema-container">

      <div class="ifs-cinema-upcoming-grid-refined">
        
        <!-- Upcoming 1 -->
        <div class="ifs-cinema-upcoming-card-pro">
          <div class="ifs-cinema-upcoming-poster-box">
            <img src="<?php echo esc_url( get_theme_file_uri( '/assets/img/avatar.jpg' ) ); ?>" 
                 alt="<?php esc_attr_e( 'Avatar: The Seed Bearer Poster', 'noorjahan-movie-theater' ); ?>"
                 onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1534447677768-be436bb09401?w=600&auto=format&fit=crop&q=80';">
            <span class="ifs-cinema-upcoming-date-badge"><?php esc_html_e( 'OCTOBER 16, 2026', 'noorjahan-movie-theater' ); ?></span>
          </div>
          <div class="ifs-cinema-upcoming-details-box">
            <span style="font-size:0.75rem; color:var(--ifs-cinema-brand-amber); font-weight:800; text-transform:uppercase;"><?php esc_html_e( 'Sci-Fi / Adventure', 'noorjahan-movie-theater' ); ?></span>
            <h3 style="color:#fff; font-size:1.25rem; font-weight:800; margin:0.35rem 0 0.85rem;"><?php esc_html_e( 'Avatar: The Seed Bearer', 'noorjahan-movie-theater' ); ?></h3>
          </div>
        </div>

        <!-- Upcoming 2 -->
        <div class="ifs-cinema-upcoming-card-pro">
          <div class="ifs-cinema-upcoming-poster-box">
            <img src="<?php echo esc_url( get_theme_file_uri( '/assets/img/gladiator.jpg' ) ); ?>" 
                 alt="<?php esc_attr_e( 'Gladiator: Rome Reborn Poster', 'noorjahan-movie-theater' ); ?>"
                 onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1478760329108-5c3ed9d495a0?w=600&auto=format&fit=crop&q=80';">
            <span class="ifs-cinema-upcoming-date-badge"><?php esc_html_e( 'NOVEMBER 22, 2026', 'noorjahan-movie-theater' ); ?></span>
          </div>
          <div class="ifs-cinema-upcoming-details-box">
            <span style="font-size:0.75rem; color:var(--ifs-cinema-brand-amber); font-weight:800; text-transform:uppercase;"><?php esc_html_e( 'Action / Epic Drama', 'noorjahan-movie-theater' ); ?></span>
            <h3 style="color:#fff; font-size:1.25rem; font-weight:800; margin:0.35rem 0 0.85rem;"><?php esc_html_e( 'Gladiator: Rome Reborn', 'noorjahan-movie-theater' ); ?></h3>
          </div>
        </div>

        <!-- Upcoming 3 -->
        <div class="ifs-cinema-upcoming-card-pro">
          <div class="ifs-cinema-upcoming-poster-box">
            <img src="<?php echo esc_url( get_theme_file_uri( '/assets/img/quantum.jpg' ) ); ?>" 
                 alt="<?php esc_attr_e( 'Quantum Nexus Poster', 'noorjahan-movie-theater' ); ?>"
                 onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1518709268805-4e9042af9f23?w=600&auto=format&fit=crop&q=80';">
            <span class="ifs-cinema-upcoming-date-badge"><?php esc_html_e( 'DECEMBER 18, 2026', 'noorjahan-movie-theater' ); ?></span>
          </div>
          <div class="ifs-cinema-upcoming-details-box">
            <span style="font-size:0.75rem; color:var(--ifs-cinema-brand-amber); font-weight:800; text-transform:uppercase;"><?php esc_html_e( 'Superhero / Cybernetic', 'noorjahan-movie-theater' ); ?></span>
            <h3 style="color:#fff; font-size:1.25rem; font-weight:800; margin:0.35rem 0 0.85rem;"><?php esc_html_e( 'Quantum Nexus', 'noorjahan-movie-theater' ); ?></h3>
          </div>
        </div>

      </div>
    </div>
  </section>

</main>

<?php get_footer(); ?>