<?php
/**
 * Template Name: Gallery Page
 *
 * @package Noorjahan_Movie_Theater
 */

get_header(); ?>

<main id="primary" class="site-main">

  <!-- GALLERY HERO & FILTERS -->
  <section class="ifs-cinema-gallery-hero">
    <div class="ifs-cinema-container">
      <span class="ifs-cinema-badge ifs-cinema-badge-amber"><?php esc_html_e( 'VISUAL SHOWCASE', 'noorjahan-movie-theater' ); ?></span>
      <h1><?php esc_html_e( 'Inside The Multiplex', 'noorjahan-movie-theater' ); ?></h1>
      <p>
        <?php esc_html_e( 'Explore high-resolution photography of our curved laser giant screens, motorized heated VIP recliner suites, and gourmet dine-in concession bars.', 'noorjahan-movie-theater' ); ?>
      </p>

      <div class="ifs-cinema-filter-pills">
        <button class="ifs-cinema-filter-btn active" onclick="filterGallery('all', this)"><?php esc_html_e( 'All Highlights', 'noorjahan-movie-theater' ); ?></button>
        <button class="ifs-cinema-filter-btn" onclick="filterGallery('screens', this)"><?php esc_html_e( 'Auditoriums & Screens', 'noorjahan-movie-theater' ); ?></button>
        <button class="ifs-cinema-filter-btn" onclick="filterGallery('lounges', this)"><?php esc_html_e( 'VIP Recliners & Lounges', 'noorjahan-movie-theater' ); ?></button>
        <button class="ifs-cinema-filter-btn" onclick="filterGallery('dining', this)"><?php esc_html_e( 'Gourmet Concessions', 'noorjahan-movie-theater' ); ?></button>
      </div>
    </div>
  </section>

  <!-- MASONRY GRID -->
  <section class="ifs-cinema-section-padding">
    <div class="ifs-cinema-container">
      <div class="ifs-cinema-gallery-masonry" id="galleryGrid">
        
        <!-- Photo 1 -->
        <div class="ifs-cinema-gallery-card" data-category="screens" onclick="openLightbox('https://images.unsplash.com/photo-1517604931442-7e0c8ed2963c?w=1200&auto=format&fit=crop&q=85', 'Auditorium 1 • 70ft Laser Screen', 'High-frame-rate curved giant screen with dual 4K laser projection.')">
          <img src="<?php echo esc_url( get_theme_file_uri( '/assets/img/gallery-1.jpg' ) ); ?>" 
               class="ifs-cinema-gallery-img" 
               alt="<?php esc_attr_e( 'Curved Laser Screen', 'noorjahan-movie-theater' ); ?>"
               onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1517604931442-7e0c8ed2963c?w=700&auto=format&fit=crop&q=75';">
          <div class="ifs-cinema-gallery-overlay">
            <span class="ifs-cinema-gallery-category"><?php esc_html_e( 'Auditoriums', 'noorjahan-movie-theater' ); ?></span>
            <h3 class="ifs-cinema-gallery-title"><?php esc_html_e( '70ft Curved Silver Screen', 'noorjahan-movie-theater' ); ?></h3>
          </div>
        </div>

        <!-- Photo 2 -->
        <div class="ifs-cinema-gallery-card" data-category="lounges" onclick="openLightbox('https://images.unsplash.com/photo-1489599849927-2ee91cede3ba?w=1200&auto=format&fit=crop&q=85', 'Zero-Gravity VIP Loungers', 'Heated motorized recliners with in-seat dining swivel tables.')">
          <img src="<?php echo esc_url( get_theme_file_uri( '/assets/img/gallery-2.jpg' ) ); ?>" 
               class="ifs-cinema-gallery-img" 
               alt="<?php esc_attr_e( 'VIP Recliners', 'noorjahan-movie-theater' ); ?>"
               onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1489599849927-2ee91cede3ba?w=700&auto=format&fit=crop&q=75';">
          <div class="ifs-cinema-gallery-overlay">
            <span class="ifs-cinema-gallery-category"><?php esc_html_e( 'VIP Lounges', 'noorjahan-movie-theater' ); ?></span>
            <h3 class="ifs-cinema-gallery-title"><?php esc_html_e( 'Motorized Recliner Suites', 'noorjahan-movie-theater' ); ?></h3>
          </div>
        </div>

        <!-- Photo 3 -->
        <div class="ifs-cinema-gallery-card" data-category="dining" onclick="openLightbox('https://images.unsplash.com/photo-1585647347483-22b66260dfff?w=1200&auto=format&fit=crop&q=85', 'Gourmet Concessions', 'Chef-prepared hot combos, butter popcorn tubs, and artisan beverages.')">
          <img src="<?php echo esc_url( get_theme_file_uri( '/assets/img/gallery-3.jpg' ) ); ?>" 
               class="ifs-cinema-gallery-img" 
               alt="<?php esc_attr_e( 'Gourmet Popcorn', 'noorjahan-movie-theater' ); ?>"
               onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1585647347483-22b66260dfff?w=700&auto=format&fit=crop&q=75';">
          <div class="ifs-cinema-gallery-overlay">
            <span class="ifs-cinema-gallery-category"><?php esc_html_e( 'Concessions', 'noorjahan-movie-theater' ); ?></span>
            <h3 class="ifs-cinema-gallery-title"><?php esc_html_e( 'Signature Food Combos', 'noorjahan-movie-theater' ); ?></h3>
          </div>
        </div>

        <!-- Photo 4 -->
        <div class="ifs-cinema-gallery-card" data-category="screens" onclick="openLightbox('https://images.unsplash.com/photo-1478760329108-5c3ed9d495a0?w=1200&auto=format&fit=crop&q=85', 'Auditorium 2 • Dolby Cinema', '128-channel Dolby Atmos spatial surround sound architecture.')">
          <img src="<?php echo esc_url( get_theme_file_uri( '/assets/img/gallery-4.jpg' ) ); ?>" 
               class="ifs-cinema-gallery-img" 
               alt="<?php esc_attr_e( 'Dolby Cinema', 'noorjahan-movie-theater' ); ?>"
               onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1478760329108-5c3ed9d495a0?w=700&auto=format&fit=crop&q=75';">
          <div class="ifs-cinema-gallery-overlay">
            <span class="ifs-cinema-gallery-category"><?php esc_html_e( 'Auditoriums', 'noorjahan-movie-theater' ); ?></span>
            <h3 class="ifs-cinema-gallery-title"><?php esc_html_e( 'Dolby Atmos® Acoustic Space', 'noorjahan-movie-theater' ); ?></h3>
          </div>
        </div>

        <!-- Photo 5 -->
        <div class="ifs-cinema-gallery-card" data-category="lounges" onclick="openLightbox('https://images.unsplash.com/photo-1509198397868-475647b2a1e5?w=1200&auto=format&fit=crop&q=85', 'Grand Resort Multiplex Lobby', 'Luxury reception lobby at Grand Sylhet Hotel & Resort.')">
          <img src="<?php echo esc_url( get_theme_file_uri( '/assets/img/gallery-5.jpg' ) ); ?>" 
               class="ifs-cinema-gallery-img" 
               alt="<?php esc_attr_e( 'Multiplex Lobby', 'noorjahan-movie-theater' ); ?>"
               onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1509198397868-475647b2a1e5?w=700&auto=format&fit=crop&q=75';">
          <div class="ifs-cinema-gallery-overlay">
            <span class="ifs-cinema-gallery-category"><?php esc_html_e( 'VIP Lounges', 'noorjahan-movie-theater' ); ?></span>
            <h3 class="ifs-cinema-gallery-title"><?php esc_html_e( 'Concierge Reception Lobby', 'noorjahan-movie-theater' ); ?></h3>
          </div>
        </div>

        <!-- Photo 6 -->
        <div class="ifs-cinema-gallery-card" data-category="dining" onclick="openLightbox('https://images.unsplash.com/photo-1518709268805-4e9042af9f23?w=1200&auto=format&fit=crop&q=85', 'In-Seat Dine-In Experience', 'Freshly served hot meals delivered directly to your cinema chair.')">
          <img src="<?php echo esc_url( get_theme_file_uri( '/assets/img/gallery-6.jpg' ) ); ?>" 
               class="ifs-cinema-gallery-img" 
               alt="<?php esc_attr_e( 'Dine-in Experience', 'noorjahan-movie-theater' ); ?>"
               onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1518709268805-4e9042af9f23?w=700&auto=format&fit=crop&q=75';">
          <div class="ifs-cinema-gallery-overlay">
            <span class="ifs-cinema-gallery-category"><?php esc_html_e( 'Concessions', 'noorjahan-movie-theater' ); ?></span>
            <h3 class="ifs-cinema-gallery-title"><?php esc_html_e( 'In-Seat Waiter Delivery', 'noorjahan-movie-theater' ); ?></h3>
          </div>
        </div>

      </div>
    </div>
  </section>

  <!-- LIGHTBOX MODAL -->
  <div class="ifs-cinema-lightbox-overlay" id="lightboxModal" role="dialog" aria-modal="true" onclick="closeLightbox(event)">
    <div class="ifs-cinema-lightbox-wrap" onclick="event.stopPropagation()">
      <button class="ifs-cinema-lightbox-close" onclick="closeLightboxDirect()">✕</button>
      <img id="lightboxImg" src="" alt="<?php esc_attr_e( 'Enlarged gallery view', 'noorjahan-movie-theater' ); ?>">
      <div class="ifs-cinema-lightbox-caption">
        <div>
          <h3 id="lightboxTitle" style="color:#fff; font-size:1.25rem;"><?php esc_html_e( 'Caption Title', 'noorjahan-movie-theater' ); ?></h3>
          <p id="lightboxDesc" style="font-size:0.88rem; color:var(--ifs-cinema-text-secondary); margin-top:2px;"><?php esc_html_e( 'Description', 'noorjahan-movie-theater' ); ?></p>
        </div>
        <span class="ifs-cinema-badge ifs-cinema-badge-amber"><?php esc_html_e( 'BIG SCREEN CINEMAS', 'noorjahan-movie-theater' ); ?></span>
      </div>
    </div>
  </div>

</main>

<?php get_footer(); ?>