<?php
/**
 * Template Name: Confirmation / Thanks Page
 *
 * @package Noorjahan_Movie_Theater
 */

get_header(); ?>

<main id="primary" class="site-main">

  <!-- THANKS HERO BANNER -->
  <section class="ifs-cinema-thanks-hero">
    <div class="ifs-cinema-container">
      <div class="ifs-cinema-success-badge-icon">
        <svg viewBox="0 0 24 24" width="36" height="36" fill="currentColor">
          <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
        </svg>
      </div>
      <h1><?php esc_html_e( 'Booking Confirmed!', 'noorjahan-movie-theater' ); ?></h1>
      <p>
        <?php esc_html_e( 'Thank you for reserving with Big Screen Cinemas. Your digital admission voucher and in-seat gourmet dine-in pass have been issued below.', 'noorjahan-movie-theater' ); ?>
      </p>
    </div>
  </section>

  <!-- CONFIRMED DIGITAL PASS VOUCHER -->
  <section class="ifs-cinema-pass-section">
    <div class="ifs-cinema-container ifs-cinema-pass-center-wrap">
      
      <!-- Main Voucher Frame -->
      <div class="ifs-cinema-pass-main-card">
        <div class="ifs-cinema-pass-top-band">
          <div>
            <h3><?php esc_html_e( 'BIG SCREEN PASS', 'noorjahan-movie-theater' ); ?></h3>
            <span><?php esc_html_e( 'VIP DIGITAL ADMISSION TOKEN', 'noorjahan-movie-theater' ); ?></span>
          </div>
          <span style="background:rgba(0,0,0,0.25); padding:0.3rem 0.65rem; border-radius:4px; font-family:var(--ifs-cinema-font-mono);">
            <?php esc_html_e( 'STATUS: ACTIVE', 'noorjahan-movie-theater' ); ?>
          </span>
        </div>

        <div class="ifs-cinema-pass-body">
          <div class="ifs-cinema-movie-lead-row">
            <img src="<?php echo esc_url( get_theme_file_uri( '/assets/img/evil-dead.jpg' ) ); ?>" 
                 alt="<?php esc_attr_e( 'Movie Poster', 'noorjahan-movie-theater' ); ?>"
                 onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1518709268805-4e9042af9f23?w=300&auto=format&fit=crop&q=75';">
            <div>
              <h2 style="font-size:1.45rem; color:#fff; font-weight:800; line-height:1.2;"><?php esc_html_e( 'FOOD COMBO (EVIL DEAD BURN)', 'noorjahan-movie-theater' ); ?></h2>
              <p style="font-size:0.85rem; color:var(--ifs-cinema-brand-amber); font-weight:700; margin-top:4px;">
                <?php esc_html_e( 'Auditorium 1 • 70ft Curved 4K Laser Screen', 'noorjahan-movie-theater' ); ?>
              </p>
              <p style="font-size:0.82rem; color:var(--ifs-cinema-text-muted); margin-top:2px;">
                <?php esc_html_e( 'Audio: Dolby Atmos® 128-Channel Spatial Engine', 'noorjahan-movie-theater' ); ?>
              </p>
            </div>
          </div>

          <div class="ifs-cinema-pass-grid-specs">
            <div class="ifs-cinema-pass-spec-item">
              <div class="label"><?php esc_html_e( 'Date & Showtime', 'noorjahan-movie-theater' ); ?></div>
              <div class="value"><?php esc_html_e( 'Today • 06:00 PM', 'noorjahan-movie-theater' ); ?></div>
            </div>
            <div class="ifs-cinema-pass-spec-item">
              <div class="label"><?php esc_html_e( 'Reserved Seats', 'noorjahan-movie-theater' ); ?></div>
              <div class="value amber"><?php esc_html_e( 'F4, F5 (VIP Recliner)', 'noorjahan-movie-theater' ); ?></div>
            </div>
            <div class="ifs-cinema-pass-spec-item">
              <div class="label"><?php esc_html_e( 'Guest Name', 'noorjahan-movie-theater' ); ?></div>
              <div class="value"><?php esc_html_e( 'Mahfuzur Rahman', 'noorjahan-movie-theater' ); ?></div>
            </div>
            <div class="ifs-cinema-pass-spec-item">
              <div class="label"><?php esc_html_e( 'Payment Method & Total', 'noorjahan-movie-theater' ); ?></div>
              <div class="value"><?php esc_html_e( 'bKash • BDT 1,050.00', 'noorjahan-movie-theater' ); ?></div>
            </div>
          </div>

          <div class="ifs-cinema-concessions-included-banner">
            <div class="ifs-cinema-concession-icon">
              <svg viewBox="0 0 24 24" width="28" height="28" fill="currentColor">
                <path d="M7 2h10l-1 5H8L7 2zm1 6h8l1.5 14H6.5L8 8zm4 3a2 2 0 1 0 0 4 2 2 0 0 0 0-4z"/>
              </svg>
            </div>
            <div>
              <div style="color:#fff; font-weight:700; font-size:0.92rem;"><?php esc_html_e( 'In-Seat Dine-In Combo Included', 'noorjahan-movie-theater' ); ?></div>
              <div style="font-size:0.8rem; color:var(--ifs-cinema-text-secondary);">
                <?php esc_html_e( '2x Fried Chicken Portions + 1x Jumbo Butter Popcorn + 2x Soft Drinks', 'noorjahan-movie-theater' ); ?>
              </div>
            </div>
          </div>
        </div>

        <div class="ifs-cinema-pass-footer-perforated">
          <div style="font-size:0.82rem; color:var(--ifs-cinema-text-muted);">
            <?php esc_html_e( 'Location: Noorjahan Grand, VIP Road, Sylhet', 'noorjahan-movie-theater' ); ?>
          </div>
          <div style="font-family:var(--ifs-cinema-font-mono); font-size:0.85rem; color:var(--ifs-cinema-brand-amber); font-weight:800;">
            <?php echo esc_html( date_i18n( 'M Y' ) ); ?>
          </div>
        </div>
      </div>

      <!-- QR Code & Quick Action Box -->
      <aside class="ifs-cinema-qr-side-card">
        <span style="font-size:0.75rem; font-weight:800; color:var(--ifs-cinema-text-muted); text-transform:uppercase; letter-spacing:0.5px; display:block; margin-bottom:1rem;">
          <?php esc_html_e( 'Scan at Gate Turnstile', 'noorjahan-movie-theater' ); ?>
        </span>
        
        <div class="ifs-cinema-qr-box">
          <div class="ifs-cinema-qr-mock-matrix"></div>
        </div>

        <div class="ifs-cinema-token-tag">#BSC-9482-TX</div>
        <p style="font-size:0.78rem; color:var(--ifs-cinema-text-secondary); line-height:1.5;">
          <?php esc_html_e( 'An SMS confirmation has been dispatched to your mobile number. Show this QR pass at the entrance scanner.', 'noorjahan-movie-theater' ); ?>
        </p>

        <div class="ifs-cinema-actions-stack">
          <button class="ifs-cinema-btn ifs-cinema-btn-amber" onclick="window.print()">
            <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor">
              <path d="M19 8H5c-1.66 0-3 1.34-3 3v6h4v4h12v-4h4v-6c0-1.66-1.34-3-3-3zm-3 11H8v-5h8v5zm3-7c-.55 0-1-.45-1-1s.45-1 1-1 1 .45 1 1-.45 1-1 1zm-1-9H6v4h12V3z"/>
            </svg>
            <?php esc_html_e( 'Print Pass Voucher', 'noorjahan-movie-theater' ); ?>
          </button>
          <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="ifs-cinema-btn ifs-cinema-btn-secondary">
            <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor">
              <path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/>
            </svg>
            <?php esc_html_e( 'Return to Homepage', 'noorjahan-movie-theater' ); ?>
          </a>
        </div>
      </aside>

    </div>
  </section>

</main>

<?php get_footer(); ?>