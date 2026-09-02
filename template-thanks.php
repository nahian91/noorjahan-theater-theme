<?php
/**
 * Template Name: Confirmation / Thanks Page
 * Description: Dynamically renders the confirmed booking details and digital pass after checkout.
 *
 * @package Noorjahan_Movie_Theater
 */

get_header();

global $wpdb;
$table_bookings = $wpdb->prefix . 'bsc_bookings';
$table_movies   = $wpdb->prefix . 'bsc_control_movies';

// Capture parameters passed via URL query string from checkout redirection
$token = isset( $_GET['token'] ) ? sanitize_text_field( $_GET['token'] ) : '';

$booking = null;
if ( ! empty( $token ) ) {
    $booking = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$table_bookings} WHERE booking_token = %s", $token ) );
}

// Fallback to the absolute latest booking record if no valid token string is present in the URL
if ( ! $booking ) {
    $booking = $wpdb->get_row( "SELECT * FROM {$table_bookings} ORDER BY id DESC LIMIT 1" );
}

// Assign variables dynamically with safe fallback defaults
$guest_name     = ! empty( $booking->customer_name ) ? $booking->customer_name : ( isset( $_GET['name'] ) ? sanitize_text_field( $_GET['name'] ) : 'Valued Guest' );
$movie_title    = ! empty( $booking->movie_title ) ? $booking->movie_title : ( isset( $_GET['movie'] ) ? sanitize_text_field( $_GET['movie'] ) : 'Feature Presentation' );
$reserved_seats = ! empty( $booking->seats ) ? $booking->seats : ( isset( $_GET['seats'] ) ? sanitize_text_field( $_GET['seats'] ) : 'A1' );
$total_amount   = ! empty( $booking->total_amount ) ? number_format( floatval( $booking->total_amount ), 2 ) : ( isset( $_GET['total'] ) ? number_format( floatval( $_GET['total'] ), 2 ) : '525.00' );
$payment_method = ! empty( $booking->payment_method ) ? $booking->payment_method : 'bKash';
$booking_token  = ! empty( $booking->booking_token ) ? $booking->booking_token : ( ! empty( $token ) ? $token : '#BSC-9482-TX' );

// Fixed Showtime mapping from database record or URL query parameter
$raw_showtime   = ! empty( $booking->showtime ) ? $booking->showtime : ( isset( $_GET['time'] ) ? sanitize_text_field( $_GET['time'] ) : '18:00' );
$formatted_time = (strpos($raw_showtime, ':') !== false && strlen($raw_showtime) <= 5) ? date('h:i A', strtotime($raw_showtime)) : $raw_showtime;

$screening_date = ! empty( $booking->booking_date ) ? date_i18n( 'F j, Y', strtotime( $booking->booking_date ) ) : ( isset( $_GET['date'] ) ? date_i18n( 'F j, Y', strtotime( sanitize_text_field( $_GET['date'] ) ) ) : date_i18n( 'F j, Y' ) );

$currency_symbol = get_option( 'bsc_currency_symbol', 'BDT' );

// Fetch corresponding movie poster from control table if available
$movie_poster = 'https://images.unsplash.com/photo-1518709268805-4e9042af9f23?w=300&auto=format&fit=crop&q=75';
if (!empty($movie_title) && $wpdb->get_var("SHOW TABLES LIKE '{$table_movies}'") === $table_movies) {
    $matched_movie = $wpdb->get_row($wpdb->prepare("SELECT poster_url FROM {$table_movies} WHERE title = %s", $movie_title));
    if ($matched_movie && !empty($matched_movie->poster_url)) {
        $movie_poster = $matched_movie->poster_url;
    }
}
?>

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
        <?php esc_html_e( 'Thank you for reserving with Big Screen Cinemas. Your digital admission voucher and detailed ticket summary have been issued below.', 'noorjahan-movie-theater' ); ?>
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
            <img src="<?php echo esc_url( $movie_poster ); ?>" 
                 alt="<?php esc_attr_e( 'Movie Poster', 'noorjahan-movie-theater' ); ?>"
                 onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1518709268805-4e9042af9f23?w=300&auto=format&fit=crop&q=75';">
            <div>
              <h2 style="font-size:1.45rem; color:#fff; font-weight:800; line-height:1.2;"><?php echo esc_html( $movie_title ); ?></h2>
              <p style="font-size:0.85rem; color:var(--ifs-cinema-brand-amber); font-weight:700; margin-top:4px;">
                70ft Curved 4K Laser Screen
              </p>
              <p style="font-size:0.82rem; color:var(--ifs-cinema-text-muted); margin-top:2px;">
                <?php esc_html_e( 'Audio: Dolby Atmos® 128-Channel Spatial Engine', 'noorjahan-movie-theater' ); ?>
              </p>
            </div>
          </div>

          <!-- Expanded Details Information Matrix -->
          <div class="ifs-cinema-pass-grid-specs" style="grid-template-columns: repeat(2, 1fr); gap: 1.25rem;">
            <div class="ifs-cinema-pass-spec-item">
              <div class="label"><?php esc_html_e( 'Booking Token Ref', 'noorjahan-movie-theater' ); ?></div>
              <div class="value" style="font-family:var(--ifs-cinema-font-mono); color:#5eead4;"><?php echo esc_html( $booking_token ); ?></div>
            </div>
            <div class="ifs-cinema-pass-spec-item">
              <div class="label"><?php esc_html_e( 'Guest Full Name', 'noorjahan-movie-theater' ); ?></div>
              <div class="value"><?php echo esc_html( $guest_name ); ?></div>
            </div>
            <div class="ifs-cinema-pass-spec-item">
              <div class="label"><?php esc_html_e( 'Screening Date', 'noorjahan-movie-theater' ); ?></div>
              <div class="value"><?php echo esc_html( $screening_date ); ?></div>
            </div>
            <div class="ifs-cinema-pass-spec-item">
              <div class="label"><?php esc_html_e( 'Showtime Slot', 'noorjahan-movie-theater' ); ?></div>
              <div class="value" style="font-family:var(--ifs-cinema-font-mono);"><?php echo esc_html( $formatted_time ); ?></div>
            </div>
            <div class="ifs-cinema-pass-spec-item">
              <div class="label"><?php esc_html_e( 'Reserved Seats Matrix', 'noorjahan-movie-theater' ); ?></div>
              <div class="value amber" style="font-family:var(--ifs-cinema-font-mono);"><?php echo esc_html( $reserved_seats ); ?></div>
            </div>
            <div class="ifs-cinema-pass-spec-item">
              <div class="label"><?php esc_html_e( 'Payment Method & Total', 'noorjahan-movie-theater' ); ?></div>
              <div class="value"><?php echo esc_html( $payment_method . ' • ' . $currency_symbol . ' ' . $total_amount ); ?></div>
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

        <div class="ifs-cinema-token-tag"><?php echo esc_html( $booking_token ); ?></div>
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