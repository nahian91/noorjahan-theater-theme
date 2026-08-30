<?php
/**
 * Template Name: About Us Page
 *
 * @package Noorjahan_Movie_Theater
 */

get_header(); ?>

<main id="primary" class="site-main">

  <!-- ABOUT HERO -->
  <section class="ifs-cinema-about-hero">
    <div class="ifs-cinema-container">
      <span class="ifs-cinema-badge ifs-cinema-badge-amber">
        <?php esc_html_e( 'THE GRAND CINEMATIC STANDARD', 'noorjahan-movie-theater' ); ?>
      </span>
      <h1><?php esc_html_e( 'Crafted For Pure Immersion', 'noorjahan-movie-theater' ); ?></h1>
      <p>
        <?php esc_html_e( 'Located at the landmark Noorjahan Grand, Big Screen Cinemas redefines multiplex entertainment with giant silver screens, dual 4K laser optics, and spatial Dolby Atmos acoustic arrays.', 'noorjahan-movie-theater' ); ?>
      </p>
    </div>
  </section>

  <!-- ARCHITECTURAL VISION -->
  <section class="ifs-cinema-section-padding">
    <div class="ifs-cinema-container ifs-cinema-split-grid">
      <div>
        <span class="ifs-cinema-badge ifs-cinema-badge-amber">
          <?php esc_html_e( 'OUR VISION', 'noorjahan-movie-theater' ); ?>
        </span>
        <h2 style="font-size:2.5rem; font-weight:900; margin:1rem 0 1.25rem;">
          <?php esc_html_e( 'Cinema Built on', 'noorjahan-movie-theater' ); ?> 
          <span style="color:var(--ifs-cinema-brand-amber);"><?php esc_html_e( 'Absolute Scale', 'noorjahan-movie-theater' ); ?></span>
        </h2>
        <p style="margin-bottom:1.5rem; line-height:1.85;">
          <?php esc_html_e( 'Big Screen Cinemas was conceived with a singular objective: to dismantle the compromises of conventional movie theaters. Every auditorium was engineered from the ground up with floating curved geometry, high-contrast laser illumination, and isolated studio sound walls.', 'noorjahan-movie-theater' ); ?>
        </p>
        <p style="line-height:1.85;">
          <?php esc_html_e( 'Paired with our bespoke hot-concession kitchen, guests enjoy chef-prepared gourmet fried chicken, artisanal popcorn, and in-seat dining while relaxing in motorized heated zero-gravity loungers.', 'noorjahan-movie-theater' ); ?>
        </p>
      </div>

      <div class="ifs-cinema-frame-wrap">
        <img src="<?php echo esc_url( get_theme_file_uri( '/assets/img/auditorium.jpg' ) ); ?>" 
             alt="<?php esc_attr_e( 'Auditorium Floating Screen', 'noorjahan-movie-theater' ); ?>" 
             onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1517604931442-7e0c8ed2963c?w=900&auto=format&fit=crop&q=80';">
      </div>
    </div>
  </section>

  <!-- METRICS ROW -->
  <section class="ifs-cinema-metrics-banner">
    <div class="ifs-cinema-container ifs-cinema-metrics-grid">
      <div>
        <div class="ifs-cinema-metric-number">70ft</div>
        <div class="ifs-cinema-metric-label"><?php esc_html_e( 'Silver Screen Aperture', 'noorjahan-movie-theater' ); ?></div>
      </div>
      <div>
        <div class="ifs-cinema-metric-number">128</div>
        <div class="ifs-cinema-metric-label"><?php esc_html_e( 'Atmos Spatial Channels', 'noorjahan-movie-theater' ); ?></div>
      </div>
      <div>
        <div class="ifs-cinema-metric-number">4K</div>
        <div class="ifs-cinema-metric-label"><?php esc_html_e( 'Dual Laser Engines', 'noorjahan-movie-theater' ); ?></div>
      </div>
      <div>
        <div class="ifs-cinema-metric-number">100%</div>
        <div class="ifs-cinema-metric-label"><?php esc_html_e( 'Zero-Gravity VIP Loungers', 'noorjahan-movie-theater' ); ?></div>
      </div>
    </div>
  </section>

  <!-- PILLARS OF EXCELLENCE -->
  <section class="ifs-cinema-section-padding">
    <div class="ifs-cinema-container">
      <div style="text-align:center; max-width:680px; margin:0 auto 3.5rem;">
        <span class="ifs-cinema-badge ifs-cinema-badge-amber">
          <?php esc_html_e( 'ENGINEERING HIGHLIGHTS', 'noorjahan-movie-theater' ); ?>
        </span>
        <h2 style="font-size:2.5rem; font-weight:900; margin-top:0.8rem;">
          <?php esc_html_e( 'The Pillars of Our Multiplex', 'noorjahan-movie-theater' ); ?>
        </h2>
      </div>

      <div class="ifs-cinema-pillars-grid">
        <div class="ifs-cinema-pillar-card">
          <div>
            <div class="ifs-cinema-pillar-icon">
              <svg viewBox="0 0 24 24" width="26" height="26" fill="currentColor">
                <path d="M19 4h-2V2h-2v2H9V2H7v2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 16H5V8h14v12zm-7-9c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/>
              </svg>
            </div>
            <h3 style="font-size:1.3rem; margin-bottom:0.75rem; color:#fff;">
              <?php esc_html_e( 'Precision Laser Engines', 'noorjahan-movie-theater' ); ?>
            </h3>
            <p style="font-size:0.92rem; line-height:1.75;">
              <?php esc_html_e( 'High-frame-rate 4K dual RGB laser engines delivering authentic deep blacks, sharp highlights, and ultra-vivid color gamuts.', 'noorjahan-movie-theater' ); ?>
            </p>
          </div>
        </div>

        <div class="ifs-cinema-pillar-card">
          <div>
            <div class="ifs-cinema-pillar-icon">
              <svg viewBox="0 0 24 24" width="26" height="26" fill="currentColor">
                <path d="M3 9v6h4l5 5V4L7 9H3zm13.5 3c0-1.77-1.02-3.29-2.5-4.03v8.05c1.48-.73 2.5-2.25 2.5-4.02zM14 3.23v2.06c2.89.86 5 3.54 5 6.71s-2.11 5.85-5 6.71v2.06c4.01-.91 7-4.49 7-8.77s-2.99-7.86-7-8.77z"/>
              </svg>
            </div>
            <h3 style="font-size:1.3rem; margin-bottom:0.75rem; color:#fff;">
              <?php esc_html_e( 'Dolby Atmos® 3D Sound', 'noorjahan-movie-theater' ); ?>
            </h3>
            <p style="font-size:0.92rem; line-height:1.75;">
              <?php esc_html_e( 'Overhead ceiling transducers and side arrays envelop the audience in three-dimensional acoustic space with pinpoint object tracking.', 'noorjahan-movie-theater' ); ?>
            </p>
          </div>
        </div>

        <div class="ifs-cinema-pillar-card">
          <div>
            <div class="ifs-cinema-pillar-icon">
              <svg viewBox="0 0 24 24" width="26" height="26" fill="currentColor">
                <path d="M11 9H9V2H7v7H5V2H3v7c0 2.12 1.66 3.84 3.75 3.97V22h2.5v-9.03C11.34 12.84 13 11.12 13 9V2h-2v7zm5-3v8h2.5v8H21V2c-2.76 0-5 2.24-5 4z"/>
              </svg>
            </div>
            <h3 style="font-size:1.3rem; margin-bottom:0.75rem; color:#fff;">
              <?php esc_html_e( 'Gourmet In-Seat Dining', 'noorjahan-movie-theater' ); ?>
            </h3>
            <p style="font-size:0.92rem; line-height:1.75;">
              <?php esc_html_e( 'Signature concession combos featuring fresh fried chicken, butter popcorn, and cold beverages delivered to your personal recliner table.', 'noorjahan-movie-theater' ); ?>
            </p>
          </div>
        </div>
      </div>
    </div>
  </section>

</main>

<?php get_footer(); ?>