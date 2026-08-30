<?php
/**
 * Template Name: Front Page / Home
 *
 * @package Noorjahan_Movie_Theater
 */

get_header(); ?>

<main id="primary" class="site-main">

  <!-- 03. GENERAL LUXURY MULTIPLEX HERO SECTION -->
  <section class="ifs-cinema-hero-stage" id="home">
    <div class="ifs-cinema-container ifs-cinema-hero-grid">
      <div>
        <div class="ifs-cinema-hero-top-meta">
          <div class="ifs-cinema-live-pulse-badge">
            <span class="ifs-cinema-pulse-dot"></span>
            <span><?php esc_html_e( 'Now Open • Noorjahan Grand', 'noorjahan-movie-theater' ); ?></span>
          </div>
          <span class="ifs-cinema-badge ifs-cinema-badge-amber"><?php esc_html_e( 'BOUTIQUE 23-SEAT AUDITORIUMS', 'noorjahan-movie-theater' ); ?></span>
        </div>

        <h1 class="ifs-cinema-hero-title"><?php esc_html_e( 'EXPERIENCE CINEMA IN ABSOLUTE LUXURY', 'noorjahan-movie-theater' ); ?></h1>
        
        <div class="ifs-cinema-hero-specs-hud">
          <div class="ifs-cinema-hud-item highlight">
            <svg viewBox="0 0 24 24" width="14" height="14" fill="currentColor" style="display:inline-block; vertical-align:-2px; margin-right:4px;">
              <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
            </svg>
            <?php esc_html_e( '4K Dual RGB Laser', 'noorjahan-movie-theater' ); ?>
          </div>
          <div class="ifs-cinema-hud-item"><?php esc_html_e( 'Dolby Atmos® 128-Ch', 'noorjahan-movie-theater' ); ?></div>
          <div class="ifs-cinema-hud-item"><?php esc_html_e( 'VIP Motorized Recliners', 'noorjahan-movie-theater' ); ?></div>
          <div class="ifs-cinema-hud-item"><?php esc_html_e( 'Gourmet In-Seat Dining', 'noorjahan-movie-theater' ); ?></div>
        </div>

        <p class="ifs-cinema-hero-lead-desc">
          <?php esc_html_e( "Sylhet's premier luxury multiplex at Noorjahan Grand. Featuring giant 70ft silver curved screens, 128-channel spatial Dolby sound, and freshly prepared hot chef-crafted concession combos delivered right to your seat.", 'noorjahan-movie-theater' ); ?>
        </p>

        <!-- Structured Key-Value Bar -->
        <div class="ifs-cinema-hero-specs-bar">
          <div class="ifs-cinema-hero-spec-node">
            <div class="k"><?php esc_html_e( 'Location', 'noorjahan-movie-theater' ); ?></div>
            <div class="v"><?php esc_html_e( 'Noorjahan Grand, Sylhet', 'noorjahan-movie-theater' ); ?></div>
          </div>
          <div class="ifs-cinema-hero-spec-node">
            <div class="k"><?php esc_html_e( 'Screen Tech', 'noorjahan-movie-theater' ); ?></div>
            <div class="v"><?php esc_html_e( '70ft 4K Laser Curved', 'noorjahan-movie-theater' ); ?></div>
          </div>
          <div class="ifs-cinema-hero-spec-node">
            <div class="k"><?php esc_html_e( 'Seating Tiers', 'noorjahan-movie-theater' ); ?></div>
            <div class="v"><?php esc_html_e( 'VIP Recliners & Standard', 'noorjahan-movie-theater' ); ?></div>
          </div>
        </div>

        <div class="ifs-cinema-hero-cta-stack">
          <a href="#movies" class="ifs-cinema-btn ifs-cinema-btn-amber">
            <?php esc_html_e( 'Book Tickets Now', 'noorjahan-movie-theater' ); ?>
            <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor">
              <path d="M5 13h11.86l-5.43 5.43 1.42 1.42L21.14 12l-8.29-7.85-1.42 1.42L16.86 11H5v2z"/>
            </svg>
          </a>
          <a href="#schedule" class="ifs-cinema-btn ifs-cinema-btn-secondary">
            <?php esc_html_e( "View Today's Schedule", 'noorjahan-movie-theater' ); ?>
          </a>
        </div>

      </div>

      <!-- Multiplex Showcase Hero Frame -->
      <div>
        <div class="ifs-cinema-poster-frame-hero">
          <div class="ifs-cinema-floating-badge-top"><?php esc_html_e( 'PREMIER LUXURY THEATRE', 'noorjahan-movie-theater' ); ?></div>
          <img src="<?php echo esc_url( get_theme_file_uri( '/assets/img/hero-auditorium.jpg' ) ); ?>" 
               alt="<?php esc_attr_e( 'Big Screen Cinemas Luxury Auditorium', 'noorjahan-movie-theater' ); ?>"
               onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1517604931442-7e0c8ed2963c?w=900&auto=format&fit=crop&q=80';">
          <div class="ifs-cinema-floating-badge-bottom">
            <div>
              <span style="font-size:0.75rem; color:var(--ifs-cinema-text-muted); font-weight:800; text-transform:uppercase; display:block;"><?php esc_html_e( 'Daily Screenings', 'noorjahan-movie-theater' ); ?></span>
              <strong style="color:#fff; font-size:1rem; font-family:var(--ifs-cinema-font-mono);"><?php esc_html_e( '11:00 AM – 02:00 AM', 'noorjahan-movie-theater' ); ?></strong>
            </div>
            <span class="ifs-cinema-badge ifs-cinema-badge-amber"><?php esc_html_e( 'FROM BDT 400', 'noorjahan-movie-theater' ); ?></span>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- 04. NOW SHOWING (3-BOX VECTOR GRID) -->
  <section class="ifs-cinema-section-padding" id="movies">
    <div class="ifs-cinema-container">

      <div class="ifs-cinema-section-title">
        <span class="ifs-cinema-badge ifs-cinema-badge-amber"><?php esc_html_e( 'LIVE SCREENINGS', 'noorjahan-movie-theater' ); ?></span>
        <h4><?php esc_html_e( 'Now Showing in Theatres', 'noorjahan-movie-theater' ); ?></h4>
        <p><?php esc_html_e( 'Live auditorium schedules and real-time 30-seat availability.', 'noorjahan-movie-theater' ); ?></p>
      </div>

      <div class="ifs-cinema-movie-grid-3">
        <!-- Box 1 -->
        <div class="ifs-cinema-card" tabindex="0">
          <div class="ifs-cinema-poster-wrap">
            <div class="ifs-cinema-card-tag"><span class="ifs-cinema-badge ifs-cinema-badge-amber"><?php esc_html_e( 'SPECIAL COMBO', 'noorjahan-movie-theater' ); ?></span></div>
            <div class="ifs-cinema-card-rating">
              <svg viewBox="0 0 24 24" width="13" height="13" fill="currentColor" style="display:inline-block; vertical-align:-1px; margin-right:2px;">
                <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
              </svg>
              8.6
            </div>
            <img src="<?php echo esc_url( get_theme_file_uri( '/assets/img/evil-dead.jpg' ) ); ?>" 
                 alt="<?php esc_attr_e( 'Food Combo', 'noorjahan-movie-theater' ); ?>" 
                 class="ifs-cinema-poster-img"
                 onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1518709268805-4e9042af9f23?w=700&auto=format&fit=crop&q=75';">
            <div class="ifs-cinema-card-overlay">
              <div>
                <h3 style="color:#fff; font-size:1.35rem; font-weight:900; margin-bottom:1.25rem;"><?php esc_html_e( 'FOOD COMBO (EVIL DEAD BURN)', 'noorjahan-movie-theater' ); ?></h3>
                <div class="ifs-cinema-meta-row"><span><?php esc_html_e( 'Release:', 'noorjahan-movie-theater' ); ?></span><strong>July 10, 2026</strong></div>
                <div class="ifs-cinema-meta-row"><span><?php esc_html_e( 'Genre:', 'noorjahan-movie-theater' ); ?></span><strong><?php esc_html_e( 'Horror / Thriller', 'noorjahan-movie-theater' ); ?></strong></div>
                <div class="ifs-cinema-meta-row"><span><?php esc_html_e( 'Duration:', 'noorjahan-movie-theater' ); ?></span><strong>01h 50m</strong></div>
              </div>
              <button class="ifs-cinema-action-btn" onclick="ifsCinemaOpenSeatModal('FOOD COMBO (EVIL DEAD BURN)', 'Auditorium 1 (30 Seats)', 500.00)">
                <span class="ifs-cinema-circle-icon">
                  <svg viewBox="0 0 24 24" width="15" height="15" fill="currentColor">
                    <path d="M22 10V6c0-1.1-.9-2-2-2H4c-1.1 0-1.99.9-1.99 2v4c1.1 0 1.99.9 1.99 2s-.89 2-2 2v4c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2v-4c-1.1 0-2-.9-2-2s.9-2 2-2zm-9 7.5h-2v-2h2v2zm0-4.5h-2v-2h2v2zm0-4.5h-2v-2h2v2z"/>
                  </svg>
                </span>
                <?php esc_html_e( 'Reserve 30-Seat Hall', 'noorjahan-movie-theater' ); ?>
                <svg viewBox="0 0 24 24" width="14" height="14" fill="currentColor" style="margin-left:2px;">
                  <path d="M5 13h11.86l-5.43 5.43 1.42 1.42L21.14 12l-8.29-7.85-1.42 1.42L16.86 11H5v2z"/>
                </svg>
              </button>
            </div>
          </div>
          <div class="ifs-cinema-card-footer">
            <h3 class="ifs-cinema-card-title"><?php esc_html_e( 'FOOD COMBO (EVIL DEAD BURN)', 'noorjahan-movie-theater' ); ?></h3>
            <p style="font-size:0.85rem; color:var(--ifs-cinema-text-secondary);"><?php esc_html_e( 'Release: July 10, 2026', 'noorjahan-movie-theater' ); ?></p>
          </div>
        </div>

        <!-- Box 2 -->
        <div class="ifs-cinema-card" tabindex="0">
          <div class="ifs-cinema-poster-wrap">
            <div class="ifs-cinema-card-tag"><span class="ifs-cinema-badge ifs-cinema-badge-imax"><?php esc_html_e( 'IMAX LASER', 'noorjahan-movie-theater' ); ?></span></div>
            <div class="ifs-cinema-card-rating">
              <svg viewBox="0 0 24 24" width="13" height="13" fill="currentColor" style="display:inline-block; vertical-align:-1px; margin-right:2px;">
                <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
              </svg>
              8.8
            </div>
            <img src="<?php echo esc_url( get_theme_file_uri( '/assets/img/dune2.jpg' ) ); ?>" 
                 alt="<?php esc_attr_e( 'Dune', 'noorjahan-movie-theater' ); ?>" 
                 class="ifs-cinema-poster-img"
                 onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1534447677768-be436bb09401?w=700&auto=format&fit=crop&q=75';">
            <div class="ifs-cinema-card-overlay">
              <div>
                <h3 style="color:#fff; font-size:1.35rem; font-weight:900; margin-bottom:1.25rem;"><?php esc_html_e( 'DUNE: PART TWO', 'noorjahan-movie-theater' ); ?></h3>
                <div class="ifs-cinema-meta-row"><span><?php esc_html_e( 'Release:', 'noorjahan-movie-theater' ); ?></span><strong>August 14, 2026</strong></div>
                <div class="ifs-cinema-meta-row"><span><?php esc_html_e( 'Genre:', 'noorjahan-movie-theater' ); ?></span><strong><?php esc_html_e( 'Sci-Fi / Adventure', 'noorjahan-movie-theater' ); ?></strong></div>
                <div class="ifs-cinema-meta-row"><span><?php esc_html_e( 'Duration:', 'noorjahan-movie-theater' ); ?></span><strong>02h 46m</strong></div>
              </div>
              <button class="ifs-cinema-action-btn" onclick="ifsCinemaOpenSeatModal('Dune: Part Two', 'Auditorium 2 (30 Seats)', 450.00)">
                <span class="ifs-cinema-circle-icon">
                  <svg viewBox="0 0 24 24" width="15" height="15" fill="currentColor">
                    <path d="M22 10V6c0-1.1-.9-2-2-2H4c-1.1 0-1.99.9-1.99 2v4c1.1 0 1.99.9 1.99 2s-.89 2-2 2v4c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2v-4c-1.1 0-2-.9-2-2s.9-2 2-2zm-9 7.5h-2v-2h2v2zm0-4.5h-2v-2h2v2zm0-4.5h-2v-2h2v2z"/>
                  </svg>
                </span>
                <?php esc_html_e( 'Reserve 30-Seat Hall', 'noorjahan-movie-theater' ); ?>
                <svg viewBox="0 0 24 24" width="14" height="14" fill="currentColor" style="margin-left:2px;">
                  <path d="M5 13h11.86l-5.43 5.43 1.42 1.42L21.14 12l-8.29-7.85-1.42 1.42L16.86 11H5v2z"/>
                </svg>
              </button>
            </div>
          </div>
          <div class="ifs-cinema-card-footer">
            <h3 class="ifs-cinema-card-title"><?php esc_html_e( 'DUNE: PART TWO', 'noorjahan-movie-theater' ); ?></h3>
            <p style="font-size:0.85rem; color:var(--ifs-cinema-text-secondary);"><?php esc_html_e( 'Release: August 14, 2026', 'noorjahan-movie-theater' ); ?></p>
          </div>
        </div>

        <!-- Box 3 -->
        <div class="ifs-cinema-card" tabindex="0">
          <div class="ifs-cinema-poster-wrap">
            <div class="ifs-cinema-card-tag"><span class="ifs-cinema-badge ifs-cinema-badge-dolby"><?php esc_html_e( 'DOLBY ATMOS', 'noorjahan-movie-theater' ); ?></span></div>
            <div class="ifs-cinema-card-rating">
              <svg viewBox="0 0 24 24" width="13" height="13" fill="currentColor" style="display:inline-block; vertical-align:-1px; margin-right:2px;">
                <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
              </svg>
              8.9
            </div>
            <img src="<?php echo esc_url( get_theme_file_uri( '/assets/img/oppenheimer.jpg' ) ); ?>" 
                 alt="<?php esc_attr_e( 'Oppenheimer', 'noorjahan-movie-theater' ); ?>" 
                 class="ifs-cinema-poster-img"
                 onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1478760329108-5c3ed9d495a0?w=700&auto=format&fit=crop&q=75';">
            <div class="ifs-cinema-card-overlay">
              <div>
                <h3 style="color:#fff; font-size:1.35rem; font-weight:900; margin-bottom:1.25rem;"><?php esc_html_e( 'OPPENHEIMER', 'noorjahan-movie-theater' ); ?></h3>
                <div class="ifs-cinema-meta-row"><span><?php esc_html_e( 'Release:', 'noorjahan-movie-theater' ); ?></span><strong>June 20, 2026</strong></div>
                <div class="ifs-cinema-meta-row"><span><?php esc_html_e( 'Genre:', 'noorjahan-movie-theater' ); ?></span><strong><?php esc_html_e( 'Biography / Drama', 'noorjahan-movie-theater' ); ?></strong></div>
                <div class="ifs-cinema-meta-row"><span><?php esc_html_e( 'Duration:', 'noorjahan-movie-theater' ); ?></span><strong>03h 00m</strong></div>
              </div>
              <button class="ifs-cinema-action-btn" onclick="ifsCinemaOpenSeatModal('Oppenheimer', 'Auditorium 3 (30 Seats)', 400.00)">
                <span class="ifs-cinema-circle-icon">
                  <svg viewBox="0 0 24 24" width="15" height="15" fill="currentColor">
                    <path d="M22 10V6c0-1.1-.9-2-2-2H4c-1.1 0-1.99.9-1.99 2v4c1.1 0 1.99.9 1.99 2s-.89 2-2 2v4c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2v-4c-1.1 0-2-.9-2-2s.9-2 2-2zm-9 7.5h-2v-2h2v2zm0-4.5h-2v-2h2v2zm0-4.5h-2v-2h2v2z"/>
                  </svg>
                </span>
                <?php esc_html_e( 'Reserve 30-Seat Hall', 'noorjahan-movie-theater' ); ?>
                <svg viewBox="0 0 24 24" width="14" height="14" fill="currentColor" style="margin-left:2px;">
                  <path d="M5 13h11.86l-5.43 5.43 1.42 1.42L21.14 12l-8.29-7.85-1.42 1.42L16.86 11H5v2z"/>
                </svg>
              </button>
            </div>
          </div>
          <div class="ifs-cinema-card-footer">
            <h3 class="ifs-cinema-card-title"><?php esc_html_e( 'OPPENHEIMER', 'noorjahan-movie-theater' ); ?></h3>
            <p style="font-size:0.85rem; color:var(--ifs-cinema-text-secondary);"><?php esc_html_e( 'Release: June 20, 2026', 'noorjahan-movie-theater' ); ?></p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- 05. UPGRADED LUXURY SCREENING SCHEDULE -->
  <section class="ifs-cinema-section-padding" id="schedule" style="background: var(--ifs-cinema-bg-surface);">
    <div class="ifs-cinema-container">

      <div class="ifs-cinema-section-title">
        <span class="ifs-cinema-badge ifs-cinema-badge-amber"><?php esc_html_e( 'AUDITORIUM TIMETABLE', 'noorjahan-movie-theater' ); ?></span>
        <h4><?php esc_html_e( 'Daily Screening Schedule', 'noorjahan-movie-theater' ); ?></h4>
        <p><?php esc_html_e( 'Live auditorium schedules and real-time 30-seat availability.', 'noorjahan-movie-theater' ); ?></p>
      </div>

      <div class="ifs-cinema-schedule-deck-wrap">
        
        <!-- Schedule Card 1 -->
        <div class="ifs-cinema-schedule-feature-card">
          <img src="<?php echo esc_url( get_theme_file_uri( '/assets/img/evil-dead.jpg' ) ); ?>" 
               class="ifs-cinema-schedule-thumb" 
               alt="<?php esc_attr_e( 'Evil Dead Burn', 'noorjahan-movie-theater' ); ?>"
               onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1518709268805-4e9042af9f23?w=200&auto=format&fit=crop&q=75';">
          <div>
            <span class="ifs-cinema-badge ifs-cinema-badge-amber" style="margin-bottom:0.4rem;"><?php esc_html_e( 'Giant Screen 4K • Auditorium 1', 'noorjahan-movie-theater' ); ?></span>
            <h3 style="color:#fff; font-size:1.35rem; font-weight:900;"><?php esc_html_e( 'FOOD COMBO (EVIL DEAD BURN)', 'noorjahan-movie-theater' ); ?></h3>
            <p style="font-size:0.85rem; color:var(--ifs-cinema-text-muted); margin-top:2px;"><?php esc_html_e( 'Experience includes in-seat food combo + admission pass', 'noorjahan-movie-theater' ); ?></p>
          </div>
          <div>
            <div style="font-size:0.75rem; font-weight:800; color:var(--ifs-cinema-text-muted); text-transform:uppercase; margin-bottom:0.4rem;"><?php esc_html_e( 'Showtime', 'noorjahan-movie-theater' ); ?></div>
            <div>
              <button class="ifs-cinema-time-pill-btn" onclick="ifsCinemaOpenSeatModal('FOOD COMBO (EVIL DEAD BURN)', 'Auditorium 1 (2:30 PM)', 500.00)">2:30 PM</button>
              <button class="ifs-cinema-time-pill-btn" onclick="ifsCinemaOpenSeatModal('FOOD COMBO (EVIL DEAD BURN)', 'Auditorium 1 (6:00 PM)', 500.00)">6:00 PM</button>
              <button class="ifs-cinema-time-pill-btn" onclick="ifsCinemaOpenSeatModal('FOOD COMBO (EVIL DEAD BURN)', 'Auditorium 1 (9:45 PM)', 500.00)">9:45 PM</button>
            </div>
          </div>
          <div>
            <button class="ifs-cinema-btn ifs-cinema-btn-amber ifs-cinema-btn-sm" onclick="ifsCinemaOpenSeatModal('FOOD COMBO (EVIL DEAD BURN)', 'Auditorium 1 (30 Seats)', 500.00)"><?php esc_html_e( 'Book Now', 'noorjahan-movie-theater' ); ?></button>
          </div>
        </div>

        <!-- Schedule Card 2 -->
        <div class="ifs-cinema-schedule-feature-card">
          <img src="<?php echo esc_url( get_theme_file_uri( '/assets/img/dune2.jpg' ) ); ?>" 
               class="ifs-cinema-schedule-thumb" 
               alt="<?php esc_attr_e( 'Dune 2', 'noorjahan-movie-theater' ); ?>"
               onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1534447677768-be436bb09401?w=200&auto=format&fit=crop&q=75';">
          <div>
            <span class="ifs-cinema-badge ifs-cinema-badge-imax" style="margin-bottom:0.4rem;"><?php esc_html_e( 'IMAX® Laser • Auditorium 2', 'noorjahan-movie-theater' ); ?></span>
            <h3 style="color:#fff; font-size:1.35rem; font-weight:900;"><?php esc_html_e( 'DUNE: PART TWO', 'noorjahan-movie-theater' ); ?></h3>
            <p style="font-size:0.85rem; color:var(--ifs-cinema-text-muted); margin-top:2px;"><?php esc_html_e( 'Full 1.43:1 expanded aspect ratio projection', 'noorjahan-movie-theater' ); ?></p>
          </div>
          <div>
            <div style="font-size:0.75rem; font-weight:800; color:var(--ifs-cinema-text-muted); text-transform:uppercase; margin-bottom:0.4rem;"><?php esc_html_e( 'Showtime', 'noorjahan-movie-theater' ); ?></div>
            <div>
              <button class="ifs-cinema-time-pill-btn" onclick="ifsCinemaOpenSeatModal('Dune: Part Two', 'Auditorium 2 (1:30 PM)', 450.00)">1:30 PM</button>
              <button class="ifs-cinema-time-pill-btn" onclick="ifsCinemaOpenSeatModal('Dune: Part Two', 'Auditorium 2 (4:45 PM)', 450.00)">4:45 PM</button>
              <button class="ifs-cinema-time-pill-btn" onclick="ifsCinemaOpenSeatModal('Dune: Part Two', 'Auditorium 2 (8:15 PM)', 450.00)">8:15 PM</button>
            </div>
          </div>
          <div>
            <button class="ifs-cinema-btn ifs-cinema-btn-amber ifs-cinema-btn-sm" onclick="ifsCinemaOpenSeatModal('Dune: Part Two', 'Auditorium 2 (30 Seats)', 450.00)"><?php esc_html_e( 'Book Now', 'noorjahan-movie-theater' ); ?></button>
          </div>
        </div>

        <!-- Schedule Card 3 -->
        <div class="ifs-cinema-schedule-feature-card">
          <img src="<?php echo esc_url( get_theme_file_uri( '/assets/img/oppenheimer.jpg' ) ); ?>" 
               class="ifs-cinema-schedule-thumb" 
               alt="<?php esc_attr_e( 'Oppenheimer', 'noorjahan-movie-theater' ); ?>"
               onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1478760329108-5c3ed9d495a0?w=200&auto=format&fit=crop&q=75';">
          <div>
            <span class="ifs-cinema-badge ifs-cinema-badge-dolby" style="margin-bottom:0.4rem;"><?php esc_html_e( 'Dolby Atmos® • Auditorium 3', 'noorjahan-movie-theater' ); ?></span>
            <h3 style="color:#fff; font-size:1.35rem; font-weight:900;"><?php esc_html_e( 'OPPENHEIMER', 'noorjahan-movie-theater' ); ?></h3>
            <p style="font-size:0.85rem; color:var(--ifs-cinema-text-muted); margin-top:2px;"><?php esc_html_e( '128-channel spatial acoustic mastering', 'noorjahan-movie-theater' ); ?></p>
          </div>
          <div>
            <div style="font-size:0.75rem; font-weight:800; color:var(--ifs-cinema-text-muted); text-transform:uppercase; margin-bottom:0.4rem;"><?php esc_html_e( 'Showtime', 'noorjahan-movie-theater' ); ?></div>
            <div>
              <button class="ifs-cinema-time-pill-btn" onclick="ifsCinemaOpenSeatModal('Oppenheimer', 'Auditorium 3 (12:00 PM)', 400.00)">12:00 PM</button>
              <button class="ifs-cinema-time-pill-btn" onclick="ifsCinemaOpenSeatModal('Oppenheimer', 'Auditorium 3 (3:45 PM)', 400.00)">3:45 PM</button>
              <button class="ifs-cinema-time-pill-btn" onclick="ifsCinemaOpenSeatModal('Oppenheimer', 'Auditorium 3 (7:30 PM)', 400.00)">7:30 PM</button>
            </div>
          </div>
          <div>
            <button class="ifs-cinema-btn ifs-cinema-btn-amber ifs-cinema-btn-sm" onclick="ifsCinemaOpenSeatModal('Oppenheimer', 'Auditorium 3 (30 Seats)', 400.00)"><?php esc_html_e( 'Book Now', 'noorjahan-movie-theater' ); ?></button>
          </div>
        </div>

      </div>
    </div>
  </section>

  <!-- 07. GOURMET CONCESSIONS & COMBOS -->
  <section class="ifs-cinema-section-padding" id="combos" style="background: var(--ifs-cinema-bg-surface);">
    <div class="ifs-cinema-container">

      <div class="ifs-cinema-section-title">
        <span class="ifs-cinema-badge ifs-cinema-badge-amber"><?php esc_html_e( 'SIGNATURE CONCESSIONS', 'noorjahan-movie-theater' ); ?></span>
        <h4><?php esc_html_e( 'Gourmet Dine-In Combos', 'noorjahan-movie-theater' ); ?></h4>
        <p><?php esc_html_e( 'Fresh hot snacks and beverages delivered directly to your cinema recliner.', 'noorjahan-movie-theater' ); ?></p>
      </div>

      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(290px, 1fr)); gap: 2rem;">
        <div class="ifs-cinema-combo-card">
          <div>
            <div class="ifs-cinema-combo-icon-wrap">
              <svg viewBox="0 0 24 24" width="28" height="28" fill="currentColor">
                <path d="M7 2h10l-1 5H8L7 2zm1 6h8l1.5 14H6.5L8 8zm4 3a2 2 0 1 0 0 4 2 2 0 0 0 0-4z"/>
              </svg>
            </div>
            <h4 style="color: #fff; font-size: 1.25rem; margin-bottom: 0.45rem;"><?php esc_html_e( 'Standard Popcorn & Drink', 'noorjahan-movie-theater' ); ?></h4>
            <p style="font-size: 0.88rem; color: var(--ifs-cinema-text-secondary); margin-bottom: 1.25rem; line-height: 1.6;"><?php esc_html_e( 'Fresh Butter Popcorn Tub + Fountain Beverage (500ml)', 'noorjahan-movie-theater' ); ?></p>
          </div>
          <div>
            <div style="font-size: 1.5rem; font-weight: 900; color: var(--ifs-cinema-brand-amber); margin-bottom: 1.25rem; font-family: var(--ifs-cinema-font-mono);">BDT 500</div>
            <button class="ifs-cinema-btn ifs-cinema-btn-secondary ifs-cinema-btn-sm" style="width: 100%;" onclick="alert('<?php echo esc_js( __( 'Standard Popcorn & Drink Added!', 'noorjahan-movie-theater' ) ); ?>')"><?php esc_html_e( '+ Add to Order', 'noorjahan-movie-theater' ); ?></button>
          </div>
        </div>

        <div class="ifs-cinema-combo-card">
          <div>
            <div class="ifs-cinema-combo-icon-wrap">
              <svg viewBox="0 0 24 24" width="28" height="28" fill="currentColor">
                <path d="M11 9H9V2H7v7H5V2H3v7c0 2.12 1.66 3.84 3.75 3.97V22h2.5v-9.03C11.34 12.84 13 11.12 13 9V2h-2v7zm5-3v8h2.5v8H21V2c-2.76 0-5 2.24-5 4z"/>
              </svg>
            </div>
            <h4 style="color: #fff; font-size: 1.25rem; margin-bottom: 0.45rem;"><?php esc_html_e( 'Popcorn, Chicken Strips & Drink', 'noorjahan-movie-theater' ); ?></h4>
            <p style="font-size: 0.88rem; color: var(--ifs-cinema-text-secondary); margin-bottom: 1.25rem; line-height: 1.6;"><?php esc_html_e( 'Crispy Chicken Strips + Butter Popcorn + Large Beverage', 'noorjahan-movie-theater' ); ?></p>
          </div>
          <div>
            <div style="font-size: 1.5rem; font-weight: 900; color: var(--ifs-cinema-brand-amber); margin-bottom: 1.25rem; font-family: var(--ifs-cinema-font-mono);">BDT 800</div>
            <button class="ifs-cinema-btn ifs-cinema-btn-secondary ifs-cinema-btn-sm" style="width: 100%;" onclick="alert('<?php echo esc_js( __( 'Popcorn, Chicken Strips & Drink Added!', 'noorjahan-movie-theater' ) ); ?>')"><?php esc_html_e( '+ Add to Order', 'noorjahan-movie-theater' ); ?></button>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- 10. CUSTOMER FAQ ACCORDION -->
  <section class="ifs-cinema-section-padding" id="faq" style="background: var(--ifs-cinema-bg-surface);">
    <div class="ifs-cinema-container">
      <div class="ifs-cinema-section-title">
        <span class="ifs-cinema-badge ifs-cinema-badge-amber"><?php esc_html_e( 'CUSTOMER INFORMATION', 'noorjahan-movie-theater' ); ?></span>
        <h4><?php esc_html_e( 'Frequently Asked Questions', 'noorjahan-movie-theater' ); ?></h4>
        <p><?php esc_html_e( 'Everything you need to know about tickets, concessions, and admissions.', 'noorjahan-movie-theater' ); ?></p>
      </div>

      <div class="ifs-cinema-faq-container">
        <details class="ifs-cinema-faq-item">
          <summary><?php esc_html_e( 'How does the Food Combo + Free Movie Ticket work?', 'noorjahan-movie-theater' ); ?></summary>
          <div class="ifs-cinema-faq-answer">
            <?php esc_html_e( 'When you purchase a designated promotional combo (such as the Evil Dead Burn Combo for BDT 500), your seat admission ticket is included at zero extra cost. Present your digital QR pass at the entrance to redeem food and seats simultaneously.', 'noorjahan-movie-theater' ); ?>
          </div>
        </details>

        <details class="ifs-cinema-faq-item">
          <summary><?php esc_html_e( 'Can I cancel or reschedule my booked seats?', 'noorjahan-movie-theater' ); ?></summary>
          <div class="ifs-cinema-faq-answer">
            <?php esc_html_e( 'Tickets can be rescheduled or refunded up to 2 hours before the scheduled showtime by contacting our Concierge Desk via phone or online messaging.', 'noorjahan-movie-theater' ); ?>
          </div>
        </details>

        <details class="ifs-cinema-faq-item">
          <summary><?php esc_html_e( 'What are the differences between Standard and VIP Recliner seats?', 'noorjahan-movie-theater' ); ?></summary>
          <div class="ifs-cinema-faq-answer">
            <?php esc_html_e( 'VIP Recliner rows (Rows C and D) feature full motorized zero-gravity recline, personal swivel tables for dining, and built-in USB-C charging ports for maximum comfort.', 'noorjahan-movie-theater' ); ?>
          </div>
        </details>
      </div>
    </div>
  </section>

  <!-- 11. UPGRADED CINEMA GALLERY -->
  <section class="ifs-cinema-section-padding" id="gallery">
    <div class="ifs-cinema-container">
      <div class="ifs-cinema-section-title">
        <span class="ifs-cinema-badge ifs-cinema-badge-amber"><?php esc_html_e( 'INSIDE THE MULTIPLEX', 'noorjahan-movie-theater' ); ?></span>
        <h4><?php esc_html_e( 'Cinema Gallery', 'noorjahan-movie-theater' ); ?></h4>
        <p><?php esc_html_e( 'A visual look inside our laser auditoriums, luxury lounges, and dine-in experiences.', 'noorjahan-movie-theater' ); ?></p>
      </div>

      <div class="ifs-cinema-gallery-masonry-refined">
        
        <!-- Photo 1 (Featured Span 2) -->
        <div class="ifs-cinema-gallery-card-pro span-2">
          <img src="<?php echo esc_url( get_theme_file_uri( '/assets/img/gallery-1.jpg' ) ); ?>" 
               alt="<?php esc_attr_e( 'Curved Laser Giant Screen', 'noorjahan-movie-theater' ); ?>"
               onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1517604931442-7e0c8ed2963c?w=900&auto=format&fit=crop&q=80';">
          <div class="ifs-cinema-gallery-badge-overlay">
            <span class="cat"><?php esc_html_e( 'Auditorium 1', 'noorjahan-movie-theater' ); ?></span>
            <div class="title"><?php esc_html_e( '70ft Curved 4K Laser Screen', 'noorjahan-movie-theater' ); ?></div>
          </div>
        </div>

        <!-- Photo 2 -->
        <div class="ifs-cinema-gallery-card-pro">
          <img src="<?php echo esc_url( get_theme_file_uri( '/assets/img/gallery-2.jpg' ) ); ?>" 
               alt="<?php esc_attr_e( 'VIP Recliners', 'noorjahan-movie-theater' ); ?>"
               onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1489599849927-2ee91cede3ba?w=600&auto=format&fit=crop&q=70';">
          <div class="ifs-cinema-gallery-badge-overlay">
            <span class="cat"><?php esc_html_e( 'VIP Lounges', 'noorjahan-movie-theater' ); ?></span>
            <div class="title"><?php esc_html_e( 'Motorized Heated Recliners', 'noorjahan-movie-theater' ); ?></div>
          </div>
        </div>

        <!-- Photo 3 -->
        <div class="ifs-cinema-gallery-card-pro">
          <img src="<?php echo esc_url( get_theme_file_uri( '/assets/img/gallery-3.jpg' ) ); ?>" 
               alt="<?php esc_attr_e( 'Gourmet Combos', 'noorjahan-movie-theater' ); ?>"
               onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1585647347483-22b66260dfff?w=600&auto=format&fit=crop&q=70';">
          <div class="ifs-cinema-gallery-badge-overlay">
            <span class="cat"><?php esc_html_e( 'Concessions', 'noorjahan-movie-theater' ); ?></span>
            <div class="title"><?php esc_html_e( 'Fresh Butter Popcorn & Meals', 'noorjahan-movie-theater' ); ?></div>
          </div>
        </div>

        <!-- Photo 4 -->
        <div class="ifs-cinema-gallery-card-pro">
          <img src="<?php echo esc_url( get_theme_file_uri( '/assets/img/gallery-4.jpg' ) ); ?>" 
               alt="<?php esc_attr_e( 'Dolby Sound', 'noorjahan-movie-theater' ); ?>"
               onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1478760329108-5c3ed9d495a0?w=600&auto=format&fit=crop&q=70';">
          <div class="ifs-cinema-gallery-badge-overlay">
            <span class="cat"><?php esc_html_e( 'Audio Engine', 'noorjahan-movie-theater' ); ?></span>
            <div class="title"><?php esc_html_e( '128-Channel Spatial Atmos', 'noorjahan-movie-theater' ); ?></div>
          </div>
        </div>

        <!-- Photo 5 -->
        <div class="ifs-cinema-gallery-card-pro">
          <img src="<?php echo esc_url( get_theme_file_uri( '/assets/img/gallery-5.jpg' ) ); ?>" 
               alt="<?php esc_attr_e( 'Resort Lobby', 'noorjahan-movie-theater' ); ?>"
               onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1509198397868-475647b2a1e5?w=600&auto=format&fit=crop&q=70';">
          <div class="ifs-cinema-gallery-badge-overlay">
            <span class="cat"><?php esc_html_e( 'Resort Multiplex', 'noorjahan-movie-theater' ); ?></span>
            <div class="title"><?php esc_html_e( 'Concierge Reception Lobby', 'noorjahan-movie-theater' ); ?></div>
          </div>
        </div>

        <!-- Photo 6 (Featured Span 2) -->
        <div class="ifs-cinema-gallery-card-pro span-2">
          <img src="<?php echo esc_url( get_theme_file_uri( '/assets/img/gallery-6.jpg' ) ); ?>" 
               alt="<?php esc_attr_e( 'In-Seat Dining', 'noorjahan-movie-theater' ); ?>"
               onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1518709268805-4e9042af9f23?w=900&auto=format&fit=crop&q=80';">
          <div class="ifs-cinema-gallery-badge-overlay">
            <span class="cat"><?php esc_html_e( 'Dine-In Lounge', 'noorjahan-movie-theater' ); ?></span>
            <div class="title"><?php esc_html_e( 'Personal Table In-Seat Food Delivery', 'noorjahan-movie-theater' ); ?></div>
          </div>
        </div>

      </div>
    </div>
  </section>

</main>

<?php get_footer(); ?>