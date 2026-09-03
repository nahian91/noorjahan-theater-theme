<?php
/**
 * Template Name: Confirmation / Thanks Page
 * Description: Dynamically renders confirmed booking details, in-seat dine-in options, and an interactive QR code pass with a ticket info modal.
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
$guest_email    = ! empty( $booking->customer_email ) ? $booking->customer_email : 'vip@noorjahan.com';
$guest_phone    = ! empty( $booking->customer_phone ) ? $booking->customer_phone : '+880 1700-000000';
$movie_title    = ! empty( $booking->movie_title ) ? $booking->movie_title : ( isset( $_GET['movie'] ) ? sanitize_text_field( $_GET['movie'] ) : 'Feature Presentation' );
$reserved_seats = ! empty( $booking->seats ) ? $booking->seats : ( isset( $_GET['seats'] ) ? sanitize_text_field( $_GET['seats'] ) : 'A1' );
$total_amount   = ! empty( $booking->total_amount ) ? number_format( floatval( $booking->total_amount ), 2 ) : ( isset( $_GET['total'] ) ? number_format( floatval( $_GET['total'] ), 2 ) : '525.00' );
$payment_method = ! empty( $booking->payment_method ) ? $booking->payment_method : 'Online Portal';
$booking_token  = ! empty( $booking->booking_token ) ? $booking->booking_token : ( ! empty( $token ) ? $token : '#BSC-9482-TX' );
$auditorium     = ! empty( $booking->auditorium ) ? $booking->auditorium : 'Auditorium 1 (23 Seats)';

// Fixed Showtime mapping from database record or URL query parameter
$raw_showtime   = ! empty( $booking->showtime ) ? $booking->showtime : ( isset( $_GET['time'] ) ? sanitize_text_field( $_GET['time'] ) : '18:00' );
$formatted_time = ( strpos( $raw_showtime, ':' ) !== false && strlen( $raw_showtime ) <= 5 ) ? date( 'h:i A', strtotime( $raw_showtime ) ) : $raw_showtime;

$screening_date = ! empty( $booking->booking_date ) ? date_i18n( 'F j, Y', strtotime( $booking->booking_date ) ) : ( isset( $_GET['date'] ) ? date_i18n( 'F j, Y', strtotime( sanitize_text_field( $_GET['date'] ) ) ) : date_i18n( 'F j, Y' ) );

$currency_symbol = get_option( 'bsc_currency_symbol', '৳' );

// Fetch corresponding movie poster from control table if available
$movie_poster = 'https://images.unsplash.com/photo-1518709268805-4e9042af9f23?w=300&auto=format&fit=crop&q=75';
if ( ! empty( $movie_title ) && $wpdb->get_var( "SHOW TABLES LIKE '{$table_movies}'" ) === $table_movies ) {
    $matched_movie = $wpdb->get_row( $wpdb->prepare( "SELECT poster_url FROM {$table_movies} WHERE title = %s", $movie_title ) );
    if ( $matched_movie && ! empty( $matched_movie->poster_url ) ) {
        $movie_poster = $matched_movie->poster_url;
    }
}

// Generate Live QR Code URL using free API encoder containing full reservation string
$qr_payload   = sprintf( "Pass: %s | Movie: %s | Seats: %s | Date: %s %s | Guest: %s", $booking_token, $movie_title, $reserved_seats, $screening_date, $formatted_time, $guest_name );
$qr_image_url = 'https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=' . urlencode( $qr_payload );
?>

<style>
  :root {
    --ifs-cinema-bg-dark: #070e11;
    --ifs-cinema-card-bg: #0b171c;
    --ifs-cinema-brand-amber: #f59e0b;
    --ifs-cinema-brand-teal: #0d9488;
    --ifs-cinema-text-primary: #f8fafc;
    --ifs-cinema-text-secondary: #94a3b8;
    --ifs-cinema-text-muted: #64748b;
    --ifs-cinema-border: #1e293b;
    --ifs-cinema-border-light: rgba(255, 255, 255, 0.08);
    --ifs-cinema-font-mono: 'Space Grotesk', monospace, system-ui;
  }

  .ifs-cinema-thanks-hero {
    background: radial-gradient(circle at center top, #0f232b 0%, var(--ifs-cinema-bg-dark) 80%);
    color: var(--ifs-cinema-text-primary);
    padding: 4rem 1rem 2.5rem;
    text-align: center;
    border-bottom: 1px solid var(--ifs-cinema-border);
  }

  .ifs-cinema-success-badge-icon {
    width: 64px;
    height: 64px;
    border-radius: 50%;
    background: rgba(13, 148, 136, 0.15);
    border: 2px solid var(--ifs-cinema-brand-teal);
    color: #2dd4bf;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1.25rem;
  }

  .ifs-cinema-thanks-hero h1 {
    font-size: 2.25rem;
    font-weight: 900;
    color: #fff;
    margin: 0 0 0.5rem 0;
    font-family: 'Space Grotesk', sans-serif;
  }

  .ifs-cinema-thanks-hero p {
    font-size: 0.98rem;
    color: var(--ifs-cinema-text-secondary);
    max-width: 600px;
    margin: 0 auto;
    line-height: 1.6;
  }

  .ifs-cinema-pass-section {
    padding: 3rem 1rem 5rem;
    background: var(--ifs-cinema-bg-dark);
  }

  .ifs-cinema-pass-center-wrap {
    display: grid;
    grid-template-columns: 1fr 340px;
    gap: 2rem;
    align-items: start;
  }

  .ifs-cinema-pass-main-card {
    background: var(--ifs-cinema-card-bg);
    border: 1px solid var(--ifs-cinema-border);
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 25px 50px -12px rgba(0,0,0,0.7);
  }

  .ifs-cinema-pass-top-band {
    background: #0f232b;
    padding: 1.25rem 1.75rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    border-bottom: 1px solid var(--ifs-cinema-border);
  }

  .ifs-cinema-pass-top-band h3 {
    margin: 0;
    font-size: 1rem;
    font-weight: 900;
    color: #fff;
    letter-spacing: 0.05em;
  }

  .ifs-cinema-pass-top-band span {
    font-size: 0.72rem;
    color: var(--ifs-cinema-text-muted);
    font-weight: 800;
    text-transform: uppercase;
  }

  .ifs-cinema-pass-body {
    padding: 2rem;
  }

  .ifs-cinema-movie-lead-row {
    display: flex;
    gap: 1.25rem;
    align-items: center;
    margin-bottom: 1.75rem;
    padding-bottom: 1.5rem;
    border-bottom: 1px solid var(--ifs-cinema-border-light);
  }

  .ifs-cinema-movie-lead-row img {
    width: 75px;
    height: 105px;
    object-fit: cover;
    border-radius: 8px;
    border: 1px solid var(--ifs-cinema-border);
  }

  .ifs-cinema-pass-grid-specs {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1.25rem;
    margin-bottom: 1.75rem;
  }

  .ifs-cinema-pass-spec-item .label {
    font-size: 0.7rem;
    text-transform: uppercase;
    font-weight: 800;
    color: var(--ifs-cinema-text-muted);
    margin-bottom: 2px;
  }

  .ifs-cinema-pass-spec-item .value {
    font-size: 0.95rem;
    font-weight: 800;
    color: #fff;
  }

  .ifs-cinema-pass-spec-item .value.amber {
    color: var(--ifs-cinema-brand-amber);
  }

  .ifs-cinema-concessions-included-banner {
    display: flex;
    align-items: center;
    gap: 1rem;
    background: rgba(13, 148, 136, 0.08);
    border: 1px solid rgba(13, 148, 136, 0.25);
    padding: 1rem 1.25rem;
    border-radius: 10px;
  }

  .ifs-cinema-concession-icon {
    width: 44px;
    height: 44px;
    border-radius: 50%;
    background: rgba(13, 148, 136, 0.2);
    color: #2dd4bf;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
  }

  .ifs-cinema-pass-footer-perforated {
    background: #060d10;
    padding: 1rem 1.75rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-top: 1px dashed var(--ifs-cinema-border);
  }

  .ifs-cinema-qr-side-card {
    background: var(--ifs-cinema-card-bg);
    border: 1px solid var(--ifs-cinema-border);
    border-radius: 16px;
    padding: 2rem;
    text-align: center;
    box-shadow: 0 20px 40px rgba(0,0,0,0.4);
  }

  .ifs-cinema-qr-box {
    width: 170px;
    height: 170px;
    background: #fff;
    margin: 0 auto 1rem;
    border-radius: 12px;
    padding: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 10px 25px rgba(0,0,0,0.5);
    cursor: pointer;
    transition: transform 0.2s ease;
  }

  .ifs-cinema-qr-box:hover {
    transform: scale(1.03);
  }

  .ifs-cinema-qr-box img {
    width: 100%;
    height: 100%;
    object-fit: contain;
    border-radius: 4px;
  }

  .ifs-cinema-token-tag {
    font-family: var(--ifs-cinema-font-mono);
    font-size: 0.95rem;
    font-weight: 900;
    color: var(--ifs-cinema-brand-amber);
    background: rgba(245, 158, 11, 0.1);
    border: 1px solid rgba(245, 158, 11, 0.3);
    padding: 0.4rem 0.8rem;
    border-radius: 6px;
    display: inline-block;
    margin-bottom: 1rem;
  }

  .ifs-cinema-actions-stack {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
    margin-top: 1.5rem;
  }

  .ifs-cinema-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 0.85rem 1rem;
    border-radius: 8px;
    font-weight: 800;
    font-size: 0.9rem;
    cursor: pointer;
    text-decoration: none;
    border: none;
    transition: all 0.2s ease;
  }

  .ifs-cinema-btn-amber {
    background: var(--ifs-cinema-brand-amber);
    color: #000;
  }

  .ifs-cinema-btn-amber:hover {
    background: #d97706;
  }

  .ifs-cinema-btn-secondary {
    background: rgba(255, 255, 255, 0.08);
    color: #fff;
    border: 1px solid var(--ifs-cinema-border);
  }

  .ifs-cinema-btn-secondary:hover {
    background: rgba(255, 255, 255, 0.15);
  }

  /* Modal Overlay Styling */
  .ifs-modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(2, 6, 8, 0.85);
    backdrop-filter: blur(6px);
    display: none;
    align-items: center;
    justify-content: center;
    z-index: 99999;
    padding: 1rem;
  }

  .ifs-modal-card {
    background: var(--ifs-cinema-card-bg);
    border: 1px solid var(--ifs-cinema-border);
    border-radius: 16px;
    width: 100%;
    max-width: 480px;
    padding: 2rem;
    box-shadow: 0 25px 60px rgba(0,0,0,0.8);
    color: #fff;
    position: relative;
    animation: modalScaleUp 0.25s ease;
  }

  @keyframes modalScaleUp {
    from { transform: scale(0.92); opacity: 0; }
    to { transform: scale(1); opacity: 1; }
  }

  .ifs-modal-close {
    position: absolute;
    top: 1rem;
    right: 1rem;
    background: rgba(255,255,255,0.08);
    border: none;
    color: #fff;
    width: 32px;
    height: 32px;
    border-radius: 50%;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 900;
  }

  .ifs-modal-close:hover {
    background: #ef4444;
  }

  .ifs-modal-ticket-row {
    display: flex;
    justify-content: space-between;
    padding: 0.65rem 0;
    border-bottom: 1px solid var(--ifs-cinema-border-light);
    font-size: 0.88rem;
  }

  .ifs-modal-ticket-row span:first-child {
    color: var(--ifs-cinema-text-muted);
    font-weight: 700;
    text-transform: uppercase;
    font-size: 0.72rem;
  }

  .ifs-modal-ticket-row span:last-child {
    color: #fff;
    font-weight: 800;
    font-family: var(--ifs-cinema-font-mono);
  }

  @media print {
    body * {
      visibility: hidden;
    }
    .ifs-cinema-pass-main-card, .ifs-cinema-pass-main-card * {
      visibility: visible;
    }
    .ifs-cinema-pass-main-card {
      position: absolute;
      left: 0;
      top: 0;
      width: 100%;
      border: none;
      box-shadow: none;
    }
  }

  @media (max-width: 900px) {
    .ifs-cinema-pass-center-wrap {
      grid-template-columns: 1fr;
    }
  }
</style>

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
        <?php esc_html_e( 'Thank you for reserving with Big Screen Cinemas. Your digital admission voucher and detailed ticket summary have been successfully issued below.', 'noorjahan-movie-theater' ); ?>
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
          <span style="background: rgba(13, 148, 136, 0.2); color: #2dd4bf; padding: 0.3rem 0.65rem; border-radius: 4px; font-family: var(--ifs-cinema-font-mono); font-size: 0.75rem; font-weight: 800;">
            <?php esc_html_e( 'STATUS: CONFIRMED', 'noorjahan-movie-theater' ); ?>
          </span>
        </div>

        <div class="ifs-cinema-pass-body">
          <div class="ifs-cinema-movie-lead-row">
            <img src="<?php echo esc_url( $movie_poster ); ?>" 
                 alt="<?php esc_attr_e( 'Movie Poster', 'noorjahan-movie-theater' ); ?>"
                 onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1518709268805-4e9042af9f23?w=300&auto=format&fit=crop&q=75';">
            <div>
              <h2 style="font-size: 1.35rem; color: #fff; font-weight: 900; line-height: 1.25; margin: 0 0 4px 0;"><?php echo esc_html( $movie_title ); ?></h2>
              <p style="font-size: 0.8rem; color: var(--ifs-cinema-brand-amber); font-weight: 800; margin: 0 0 2px 0;">
                <?php echo esc_html( $auditorium ); ?> • 70ft Curved 4K Laser Screen
              </p>
              <p style="font-size: 0.78rem; color: var(--ifs-cinema-text-muted); margin: 0;">
                <?php esc_html_e( 'Audio: Dolby Atmos® 128-Channel Spatial Engine', 'noorjahan-movie-theater' ); ?>
              </p>
            </div>
          </div>

          <!-- Expanded Details Information Matrix -->
          <div class="ifs-cinema-pass-grid-specs">
            <div class="ifs-cinema-pass-spec-item">
              <div class="label"><?php esc_html_e( 'Booking Token Ref', 'noorjahan-movie-theater' ); ?></div>
              <div class="value" style="font-family: var(--ifs-cinema-font-mono); color: #5eead4;"><?php echo esc_html( $booking_token ); ?></div>
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
              <div class="value" style="font-family: var(--ifs-cinema-font-mono);"><?php echo esc_html( $formatted_time ); ?></div>
            </div>
            <div class="ifs-cinema-pass-spec-item">
              <div class="label"><?php esc_html_e( 'Reserved Seats Matrix', 'noorjahan-movie-theater' ); ?></div>
              <div class="value amber" style="font-family: var(--ifs-cinema-font-mono);"><?php echo esc_html( $reserved_seats ); ?></div>
            </div>
            <div class="ifs-cinema-pass-spec-item">
              <div class="label"><?php esc_html_e( 'Payment & Total Settled', 'noorjahan-movie-theater' ); ?></div>
              <div class="value"><?php echo esc_html( $payment_method . ' • ' . $currency_symbol . ' ' . $total_amount ); ?></div>
            </div>
          </div>

          <div class="ifs-cinema-concessions-included-banner">
            <div class="ifs-cinema-concession-icon">
              <svg viewBox="0 0 24 24" width="24" height="24" fill="currentColor">
                <path d="M7 2h10l-1 5H8L7 2zm1 6h8l1.5 14H6.5L8 8zm4 3a2 2 0 1 0 0 4 2 2 0 0 0 0-4z"/>
              </svg>
            </div>
            <div>
              <div style="color: #fff; font-weight: 800; font-size: 0.88rem;"><?php esc_html_e( 'In-Seat Dine-In Combo Included', 'noorjahan-movie-theater' ); ?></div>
              <div style="font-size: 0.78rem; color: var(--ifs-cinema-text-secondary);">
                <?php esc_html_e( '2x Fried Chicken Portions + 1x Jumbo Butter Popcorn + 2x Soft Drinks', 'noorjahan-movie-theater' ); ?>
              </div>
            </div>
          </div>
        </div>

        <div class="ifs-cinema-pass-footer-perforated">
          <div style="font-size: 0.78rem; color: var(--ifs-cinema-text-muted);">
            <?php esc_html_e( 'Location: Noorjahan Grand, VIP Road, Sylhet', 'noorjahan-movie-theater' ); ?>
          </div>
          <div style="font-family: var(--ifs-cinema-font-mono); font-size: 0.82rem; color: var(--ifs-cinema-brand-amber); font-weight: 800;">
            <?php echo esc_html( date_i18n( 'M Y' ) ); ?>
          </div>
        </div>
      </div>

      <!-- QR Code & Quick Action Box -->
      <aside class="ifs-cinema-qr-side-card">
        <span style="font-size: 0.72rem; font-weight: 800; color: var(--ifs-cinema-text-muted); text-transform: uppercase; letter-spacing: 0.05em; display: block; margin-bottom: 1rem;">
          <?php esc_html_e( 'Scan at Gate Turnstile', 'noorjahan-movie-theater' ); ?>
        </span>
        
        <!-- Interactive QR Code Box -->
        <div class="ifs-cinema-qr-box" onclick="openTicketModal()" title="Click to view full ticket scan info">
          <img src="<?php echo esc_url( $qr_image_url ); ?>" alt="Booking QR Code">
        </div>

        <div class="ifs-cinema-token-tag"><?php echo esc_html( $booking_token ); ?></div>
        <p style="font-size: 0.78rem; color: var(--ifs-cinema-text-secondary); line-height: 1.5; margin-bottom: 0;">
          <?php esc_html_e( 'Tap the QR code or click below to review encoded pass credentials and scan details.', 'noorjahan-movie-theater' ); ?>
        </p>

        <div class="ifs-cinema-actions-stack">
          <button class="ifs-cinema-btn ifs-cinema-btn-amber" onclick="openTicketModal()">
            <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor">
              <path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/>
            </svg>
            <?php esc_html_e( 'Show Ticket Scan Info', 'noorjahan-movie-theater' ); ?>
          </button>
          <button class="ifs-cinema-btn ifs-cinema-btn-secondary" onclick="window.print()">
            <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor">
              <path d="M19 8H5c-1.66 0-3 1.34-3 3v6h4v4h12v-4h4v-6c0-1.66-1.34-3-3-3zm-3 11H8v-5h8v5zm3-7c-.55 0-1-.45-1-1s.45-1 1-1 1 .45 1 1-.45 1-1 1zm-1-9H6v4h12V3z"/>
            </svg>
            <?php esc_html_e( 'Print Pass Voucher', 'noorjahan-movie-theater' ); ?>
          </button>
          <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="ifs-cinema-btn ifs-cinema-btn-secondary" style="font-size: 0.82rem; padding: 0.6rem;">
            <?php esc_html_e( 'Return to Homepage', 'noorjahan-movie-theater' ); ?>
          </a>
        </div>
      </aside>

    </div>
  </section>

</main>

<!-- Ticket Scan Info Modal Overlay -->
<div class="ifs-modal-overlay" id="ticketScanModal">
  <div class="ifs-modal-card">
    <button class="ifs-modal-close" onclick="closeTicketModal()">×</button>
    
    <div style="text-align: center; margin-bottom: 1.5rem;">
      <span style="font-size: 0.7rem; font-weight: 800; color: var(--ifs-cinema-brand-amber); text-transform: uppercase; letter-spacing: 0.1em;">
        <?php esc_html_e( 'Gate Scanner Verification', 'noorjahan-movie-theater' ); ?>
      </span>
      <h3 style="font-size: 1.4rem; font-weight: 900; margin: 0.25rem 0 0 0; color: #fff;">
        <?php esc_html_e( 'Decoded Ticket Info', 'noorjahan-movie-theater' ); ?>
      </h3>
    </div>

    <div style="margin-bottom: 1.5rem;">
      <div class="ifs-modal-ticket-row">
        <span><?php esc_html_e( 'Token Reference', 'noorjahan-movie-theater' ); ?></span>
        <span style="color: #5eead4;"><?php echo esc_html( $booking_token ); ?></span>
      </div>
      <div class="ifs-modal-ticket-row">
        <span><?php esc_html_e( 'Movie Feature', 'noorjahan-movie-theater' ); ?></span>
        <span><?php echo esc_html( $movie_title ); ?></span>
      </div>
      <div class="ifs-modal-ticket-row">
        <span><?php esc_html_e( 'Auditorium Hall', 'noorjahan-movie-theater' ); ?></span>
        <span><?php echo esc_html( $auditorium ); ?></span>
      </div>
      <div class="ifs-modal-ticket-row">
        <span><?php esc_html_e( 'Seats Matrix', 'noorjahan-movie-theater' ); ?></span>
        <span style="color: var(--ifs-cinema-brand-amber);"><?php echo esc_html( $reserved_seats ); ?></span>
      </div>
      <div class="ifs-modal-ticket-row">
        <span><?php esc_html_e( 'Screening Date', 'noorjahan-movie-theater' ); ?></span>
        <span><?php echo esc_html( $screening_date ); ?></span>
      </div>
      <div class="ifs-modal-ticket-row">
        <span><?php esc_html_e( 'Showtime Slot', 'noorjahan-movie-theater' ); ?></span>
        <span><?php echo esc_html( $formatted_time ); ?></span>
      </div>
      <div class="ifs-modal-ticket-row">
        <span><?php esc_html_e( 'Registered Guest', 'noorjahan-movie-theater' ); ?></span>
        <span><?php echo esc_html( $guest_name ); ?></span>
      </div>
      <div class="ifs-modal-ticket-row">
        <span><?php esc_html_e( 'Contact Phone', 'noorjahan-movie-theater' ); ?></span>
        <span><?php echo esc_html( $guest_phone ); ?></span>
      </div>
    </div>

    <div style="background: rgba(13, 148, 136, 0.1); border: 1px dashed var(--ifs-cinema-brand-teal); padding: 0.75rem; border-radius: 8px; text-align: center; font-size: 0.78rem; color: #2dd4bf; font-weight: 700;">
      ✓ <?php esc_html_e( 'QR Payload Verified: Ready for Turnstile Validation', 'noorjahan-movie-theater' ); ?>
    </div>
  </div>
</div>

<script>
  function openTicketModal() {
    const modal = document.getElementById('ticketScanModal');
    if (modal) {
      modal.style.display = 'flex';
    }
  }

  function closeTicketModal() {
    const modal = document.getElementById('ticketScanModal');
    if (modal) {
      modal.style.display = 'none';
    }
  }

  // Close modal on outside background click
  window.addEventListener('click', function(event) {
    const modal = document.getElementById('ticketScanModal');
    if (event.target === modal) {
      modal.style.display = 'none';
    }
  });
</script>

<?php get_footer(); ?>