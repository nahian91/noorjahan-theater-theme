<?php
/**
 * Template Name: Checkout Reservation Page
 * Description: Premium cinema admission checkout portal with secure order placement, locked user details, and active screening slot integration.
 *
 * @package Noorjahan_Movie_Theater
 */

// Auto-gate: Redirect non-logged-in visitors to the custom login page with full query retention
if ( ! is_user_logged_in() ) {
    $current_url = set_url_scheme( 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] );
    $login_page  = home_url( '/custom-login/' );
    
    wp_safe_redirect( add_query_arg( 'redirect_to', urlencode( $current_url ), $login_page ) );
    exit;
}

get_header();

global $wpdb;
$table_movies   = $wpdb->prefix . 'bsc_control_movies';
$table_bookings = $wpdb->prefix . 'bsc_bookings';

// Retrieve URL parameters sent from Single Movie page
$movie_id        = isset( $_GET['movie_id'] ) ? intval( $_GET['movie_id'] ) : 1;
$seats_str       = isset( $_GET['seats'] ) ? sanitize_text_field( wp_unslash( $_GET['seats'] ) ) : 'A1';
$url_total       = isset( $_GET['total'] ) ? floatval( $_GET['total'] ) : 525.00;
$screening_date  = isset( $_GET['date'] ) ? sanitize_text_field( wp_unslash( $_GET['date'] ) ) : current_time( 'Y-m-d' );
$screening_time  = isset( $_GET['time'] ) ? sanitize_text_field( wp_unslash( $_GET['time'] ) ) : '18:00';

// Format showtime for human readability
$formatted_showtime = $screening_time;
if ( false !== strpos( $screening_time, ':' ) && strlen( $screening_time ) <= 5 ) {
    $st_parts = explode( ':', $screening_time );
    $st_hour  = (int) $st_parts[0];
    $st_ampm  = $st_hour >= 12 ? 'PM' : 'AM';
    $st_h12   = ( $st_hour % 12 === 0 ) ? 12 : ( $st_hour % 12 );
    $formatted_showtime = sprintf( '%02d:%s %s', $st_h12, $st_parts[1], $st_ampm );
}

$human_screening_date = date_i18n( 'F j, Y', strtotime( $screening_date ) );

// Fetch Movie Record from database
$movie = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$table_movies} WHERE id = %d", $movie_id ) );
if ( ! $movie ) {
    $movie = $wpdb->get_row( "SELECT * FROM {$table_movies} ORDER BY id DESC LIMIT 1" );
}

$title           = ! empty( $movie->title ) ? $movie->title : 'Feature Presentation';
$poster_url      = ! empty( $movie->poster_url ) ? esc_url( $movie->poster_url ) : 'https://images.unsplash.com/photo-1518709268805-4e9042af9f23?w=300&auto=format&fit=crop&q=75';
$auditorium      = ! empty( $movie->auditorium ) ? esc_html( $movie->auditorium ) : 'Auditorium 1 (23 Seats)';
$currency_symbol = get_option( 'bsc_currency_symbol', '৳' );
$service_tax_pct = floatval( get_option( 'bsc_service_charge_pct', 5.00 ) );
$enable_tax      = get_option( 'bsc_enable_tax', 'yes' );

// Retrieve Current Logged-In User Profile Attributes
$current_user = wp_get_current_user();
$uid          = $current_user->ID;

$auto_name = '';
$f_name    = get_user_meta( $uid, 'first_name', true );
$l_name    = get_user_meta( $uid, 'last_name', true );
if ( ! empty( $f_name ) || ! empty( $l_name ) ) {
    $auto_name = trim( $f_name . ' ' . $l_name );
}
if ( empty( $auto_name ) && ! empty( $current_user->display_name ) ) {
    $auto_name = $current_user->display_name;
}
if ( empty( $auto_name ) ) {
    $auto_name = $current_user->user_login;
}

$auto_email = ! empty( $current_user->user_email ) ? $current_user->user_email : get_user_meta( $uid, 'billing_email', true );

$auto_phone = get_user_meta( $uid, 'phone_number', true );
if ( empty( $auto_phone ) ) {
    $auto_phone = get_user_meta( $uid, 'phone', true );
}
if ( empty( $auto_phone ) ) {
    $auto_phone = get_user_meta( $uid, 'billing_phone', true );
}
if ( empty( $auto_phone ) ) {
    $auto_phone = get_user_meta( $uid, 'mobile', true );
}

// Calculate seat array and totals
$seats_array = array_filter( array_map( 'trim', explode( ',', $seats_str ) ) );
$seat_count  = count( $seats_array );
$subtotal    = $url_total > 0 ? $url_total / ( 1 + ( ( 'yes' === $enable_tax ? $service_tax_pct : 0 ) / 100 ) ) : 500.00;
$tax_amount  = $url_total > 0 ? ( $url_total - $subtotal ) : 0.00;
$grand_total = $url_total > 0 ? $url_total : 525.00;

// Query Top 3 Popular Movies safely
$popular_movies = $wpdb->get_results( 
    "SELECT m.*, COUNT(b.id) as booking_count 
     FROM {$table_movies} m 
     LEFT JOIN {$table_bookings} b ON m.title = b.movie_title 
     GROUP BY m.id 
     ORDER BY booking_count DESC, m.id DESC 
     LIMIT 3" 
);
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
  }

  .ifs-checkout-hero {
    background: radial-gradient(circle at center top, #0f232b 0%, var(--ifs-cinema-bg-dark) 80%);
    padding: 3.5rem 1rem 2rem;
    text-align: center;
    border-bottom: 1px solid var(--ifs-cinema-border);
  }

  .ifs-checkout-container {
    max-width: 1100px;
    margin: 0 auto;
    padding: 0 1rem;
    box-sizing: border-box;
  }

  .ifs-checkout-grid {
    display: grid;
    grid-template-columns: 1fr 400px;
    gap: 2rem;
    padding: 3rem 1rem 2rem;
    max-width: 1100px;
    margin: 0 auto;
  }

  .ifs-checkout-card {
    background: var(--ifs-cinema-card-bg);
    border: 1px solid var(--ifs-cinema-border);
    border-radius: 16px;
    padding: 2rem;
    box-shadow: 0 20px 40px rgba(0,0,0,0.4);
  }

  .ifs-checkout-card-title {
    font-size: 1.15rem;
    font-weight: 900;
    color: #fff;
    margin: 0 0 1.5rem 0;
    display: flex;
    align-items: center;
    justify-content: space-between;
    border-bottom: 1px solid var(--ifs-cinema-border-light);
    padding-bottom: 0.85rem;
    font-family: 'Space Grotesk', system-ui, sans-serif;
  }

  .ifs-verified-pill {
    font-size: 0.72rem;
    font-weight: 800;
    letter-spacing: 0.05em;
    background: rgba(16, 185, 129, 0.15);
    color: #34d399;
    border: 1px solid rgba(16, 185, 129, 0.35);
    padding: 3px 8px;
    border-radius: 9999px;
    display: inline-flex;
    align-items: center;
    gap: 4px;
    text-transform: uppercase;
  }

  .ifs-form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1.25rem;
    margin-bottom: 1.25rem;
  }

  .ifs-form-group {
    margin-bottom: 1.25rem;
  }

  .ifs-form-group.full {
    grid-column: span 2;
  }

  .ifs-form-group label {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 0.72rem;
    font-weight: 800;
    text-transform: uppercase;
    color: var(--ifs-cinema-text-muted);
    margin-bottom: 0.45rem;
    letter-spacing: 0.05em;
  }

  .ifs-form-input {
    width: 100%;
    padding: 0.85rem 1rem;
    background: #04080a;
    border: 1px solid var(--ifs-cinema-border);
    border-radius: 8px;
    color: #f1f5f9;
    font-size: 0.92rem;
    font-weight: 600;
    box-sizing: border-box;
    outline: none;
    transition: all 0.2s ease;
  }

  .ifs-form-input:read-only {
    background: #060d10;
    border-color: rgba(255, 255, 255, 0.08);
    color: #94a3b8;
    cursor: default;
  }

  .ifs-summary-sidebar {
    background: var(--ifs-cinema-card-bg);
    border: 1px solid var(--ifs-cinema-border);
    border-radius: 16px;
    padding: 2rem;
    height: fit-content;
    position: sticky;
    top: 32px;
    box-shadow: 0 20px 40px rgba(0,0,0,0.4);
  }

  .ifs-movie-preview {
    display: flex;
    gap: 1rem;
    align-items: center;
    margin-bottom: 1.5rem;
    padding-bottom: 1.25rem;
    border-bottom: 1px solid var(--ifs-cinema-border-light);
  }

  .ifs-movie-preview img {
    width: 68px;
    height: 94px;
    object-fit: cover;
    border-radius: 8px;
    border: 1px solid var(--ifs-cinema-border);
  }

  .ifs-chips-container {
    display: flex;
    flex-wrap: wrap;
    gap: 0.4rem;
    margin: 0.5rem 0 1.5rem;
  }

  .ifs-seat-chip {
    background: rgba(13, 148, 136, 0.2);
    color: #2dd4bf;
    border: 1px solid rgba(13, 148, 136, 0.4);
    font-size: 0.78rem;
    font-weight: 800;
    padding: 0.25rem 0.6rem;
    border-radius: 6px;
    font-family: 'Space Grotesk', monospace;
  }

  .ifs-bill-row {
    display: flex;
    justify-content: space-between;
    font-size: 0.88rem;
    color: var(--ifs-cinema-text-secondary);
    margin-bottom: 0.75rem;
  }

  .ifs-bill-row.total {
    font-size: 1.15rem;
    font-weight: 900;
    color: #fff;
    border-top: 1px dashed var(--ifs-cinema-border-light);
    padding-top: 1rem;
    margin-top: 0.75rem;
  }

  .ifs-submit-btn {
    width: 100%;
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    color: #000;
    font-weight: 900;
    font-size: 0.95rem;
    padding: 1.05rem;
    border-radius: 10px;
    border: none;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    transition: all 0.25s ease;
    margin-top: 1.5rem;
    box-shadow: 0 6px 18px rgba(245, 158, 11, 0.25);
  }

  .ifs-submit-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(245, 158, 11, 0.4);
  }

  .ifs-popular-section {
    max-width: 1100px;
    margin: 0 auto;
    padding: 1rem 1rem 5rem;
  }

  .ifs-popular-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1.5rem;
    margin-top: 1.25rem;
  }

  .ifs-popular-card {
    background: var(--ifs-cinema-card-bg);
    border: 1px solid var(--ifs-cinema-border);
    border-radius: 12px;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    transition: transform 0.2s ease, border-color 0.2s ease;
  }

  .ifs-popular-card:hover {
    border-color: var(--ifs-cinema-brand-amber);
    transform: translateY(-3px);
  }

  .ifs-popular-poster {
    width: 100%;
    height: 180px;
    object-fit: cover;
  }

  .ifs-popular-body {
    padding: 1.25rem;
    display: flex;
    flex-direction: column;
    flex-grow: 1;
    justify-content: space-between;
  }

  .ifs-popular-title {
    font-size: 0.98rem;
    font-weight: 900;
    color: #fff;
    margin: 0 0 0.4rem 0;
    line-height: 1.3;
  }

  .ifs-popular-meta {
    font-size: 0.75rem;
    color: var(--ifs-cinema-text-muted);
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 0.75rem;
  }

  .ifs-popular-btn {
    display: inline-block;
    background: rgba(245, 158, 11, 0.15);
    color: var(--ifs-cinema-brand-amber);
    border: 1px solid rgba(245, 158, 11, 0.3);
    text-align: center;
    padding: 0.5rem;
    border-radius: 6px;
    font-size: 0.78rem;
    font-weight: 800;
    text-decoration: none;
    margin-top: 1rem;
    transition: background 0.2s ease;
  }

  .ifs-popular-btn:hover {
    background: var(--ifs-cinema-brand-amber);
    color: #000;
  }

  @media (max-width: 900px) {
    .ifs-checkout-grid { grid-template-columns: 1fr; }
    .ifs-form-grid { grid-template-columns: 1fr; }
    .ifs-form-group.full { grid-column: span 1; }
    .ifs-popular-grid { grid-template-columns: 1fr; }
  }
</style>

<main id="primary" class="site-main">

  <section class="ifs-checkout-hero">
    <div class="ifs-checkout-container">
      <span style="font-size: 0.72rem; font-weight: 800; letter-spacing: 0.1em; color: var(--ifs-cinema-brand-amber); text-transform: uppercase; display:inline-block; margin-bottom:0.5rem;">
        <?php esc_html_e( 'Secure Admission Checkout', 'bcs-screen-manager' ); ?>
      </span>
      <h1 style="font-size: 2.2rem; font-weight: 900; color: #fff; margin: 0; letter-spacing: -0.02em;">
        <?php esc_html_e( 'Finalize Your Booking', 'bcs-screen-manager' ); ?>
      </h1>
    </div>
  </section>

  <form method="POST" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
    <?php wp_nonce_field( 'bsc_frontend_checkout_verify', 'bsc_checkout_nonce' ); ?>
    <input type="hidden" name="action" value="bsc_process_frontend_checkout">
    <input type="hidden" name="movie_title" value="<?php echo esc_attr( $title ); ?>">
    <input type="hidden" name="auditorium" value="<?php echo esc_attr( $auditorium ); ?>">
    <input type="hidden" name="seats" value="<?php echo esc_attr( $seats_str ); ?>">
    <input type="hidden" name="showtime" value="<?php echo esc_attr( $screening_time ); ?>">
    <input type="hidden" name="booking_date" value="<?php echo esc_attr( $screening_date ); ?>">
    <input type="hidden" name="total_amount" value="<?php echo esc_attr( $grand_total ); ?>">
    <input type="hidden" name="payment_method" value="Online Portal">

    <div class="ifs-checkout-grid">
      
      <!-- Left Panel: Auto-Filled Profile Details -->
      <div>
        <div class="ifs-checkout-card">
          <h2 class="ifs-checkout-card-title">
            <span style="display:flex; align-items:center; gap:0.65rem;">
              <svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
              <?php esc_html_e( 'Member Information', 'bcs-screen-manager' ); ?>
            </span>
            <span class="ifs-verified-pill">
              <svg viewBox="0 0 24 24" width="12" height="12" fill="currentColor"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
              <?php esc_html_e( 'Verified Profile', 'bcs-screen-manager' ); ?>
            </span>
          </h2>

          <div class="ifs-form-grid">
            <div class="ifs-form-group">
              <label>
                <span><?php esc_html_e( 'Full Name', 'bcs-screen-manager' ); ?></span>
                <span style="color:#0d9488; font-size:0.68rem;">(Auto-filled)</span>
              </label>
              <input type="text" class="ifs-form-input" name="customer_name" value="<?php echo esc_attr( $auto_name ); ?>" readonly required>
            </div>
            <div class="ifs-form-group">
              <label>
                <span><?php esc_html_e( 'Mobile Phone', 'bcs-screen-manager' ); ?></span>
                <?php if ( ! empty( $auto_phone ) ) : ?>
                  <span style="color:#0d9488; font-size:0.68rem;">(Auto-filled)</span>
                <?php endif; ?>
              </label>
              <input type="tel" class="ifs-form-input" name="customer_phone" value="<?php echo esc_attr( $auto_phone ); ?>" <?php echo ! empty( $auto_phone ) ? 'readonly' : ''; ?> placeholder="+880 1700-000000" required>
            </div>
          </div>

          <div class="ifs-form-group full" style="margin-bottom:0;">
            <label>
              <span><?php esc_html_e( 'Email Address', 'bcs-screen-manager' ); ?></span>
              <span style="color:#0d9488; font-size:0.68rem;">(Auto-filled)</span>
            </label>
            <input type="email" class="ifs-form-input" name="customer_email" value="<?php echo esc_attr( $auto_email ); ?>" readonly required>
          </div>
        </div>
      </div>

      <!-- Right Panel: Order Summary Sidebar -->
      <aside class="ifs-summary-sidebar">
        <h2 class="ifs-checkout-card-title" style="margin-top:0;">
          <span style="display:flex; align-items:center; gap:0.65rem;">
            <svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor"><path d="M18 4H6c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 14H6V6h12v12zM8 10h8v2H8zm0 4h6v2H8z"/></svg>
            <?php esc_html_e( 'Booking Overview', 'bcs-screen-manager' ); ?>
          </span>
        </h2>

        <div class="ifs-movie-preview">
          <img src="<?php echo $poster_url; ?>" alt="<?php echo esc_attr( $title ); ?>">
          <div>
            <h3 style="font-size: 1.05rem; font-weight: 900; color: #fff; margin: 0 0 4px; line-height: 1.25;"><?php echo esc_html( $title ); ?></h3>
            <span style="font-size: 0.75rem; font-weight: 800; color: var(--ifs-cinema-brand-amber); display: block; margin-bottom: 2px;"><?php echo esc_html( $auditorium ); ?></span>
            <span style="font-size: 0.72rem; color: var(--ifs-cinema-text-muted); display: block;"><?php echo esc_html( $human_screening_date . ' • ' . $formatted_showtime ); ?></span>
          </div>
        </div>

        <div>
          <label style="font-size: 0.72rem; font-weight: 800; text-transform: uppercase; color: var(--ifs-cinema-text-muted); display: block;"><?php esc_html_e( 'Reserved Seats:', 'bcs-screen-manager' ); ?></label>
          <div class="ifs-chips-container">
            <?php if ( ! empty( $seats_array ) ) : ?>
              <?php foreach ( $seats_array as $s ) : ?>
                <span class="ifs-seat-chip"><?php echo esc_html( $s ); ?></span>
              <?php endforeach; ?>
            <?php else : ?>
              <span class="ifs-seat-chip">A1</span>
            <?php endif; ?>
          </div>
        </div>

        <div style="border-top: 1px solid var(--ifs-cinema-border-light); padding-top: 1rem;">
          <div class="ifs-bill-row">
            <span><?php echo sprintf( esc_html__( 'Tickets (%s Seats)', 'bcs-screen-manager' ), $seat_count ); ?></span>
            <span><?php echo esc_html( $currency_symbol ); ?> <?php echo number_format( $subtotal, 2 ); ?></span>
          </div>
          <div class="ifs-bill-row">
            <span><?php echo sprintf( esc_html__( 'VAT & Service Fee (%s%%)', 'bcs-screen-manager' ), ( 'yes' === $enable_tax ? $service_tax_pct : 0 ) ); ?></span>
            <span><?php echo esc_html( $currency_symbol ); ?> <?php echo number_format( $tax_amount, 2 ); ?></span>
          </div>
          <div class="ifs-bill-row total">
            <span><?php esc_html_e( 'Total Amount', 'bcs-screen-manager' ); ?></span>
            <span style="color: var(--ifs-cinema-brand-amber);"><?php echo esc_html( $currency_symbol ); ?> <?php echo number_format( $grand_total, 2 ); ?></span>
          </div>
        </div>

        <button type="submit" class="ifs-submit-btn">
          <span><?php esc_html_e( 'Complete Reservation & Issue Pass', 'bcs-screen-manager' ); ?></span>
          <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor">
            <path d="M5 13h11.86l-5.43 5.43 1.42 1.42L21.14 12l-8.29-7.85-1.42 1.42L16.86 11H5v2z"/>
          </svg>
        </button>
      </aside>

    </div>
  </form>

  <!-- Bottom Section: Popular Multiplex Presentations (Top 3 Movies) -->
  <?php if ( ! empty( $popular_movies ) ) : ?>
    <section class="ifs-popular-section">
      <div style="border-top: 1px solid var(--ifs-cinema-border); padding-top: 2.5rem; margin-top: 1rem;">
        <span style="font-size: 0.72rem; font-weight: 800; letter-spacing: 0.1em; color: var(--ifs-cinema-brand-amber); text-transform: uppercase; display: block; margin-bottom: 0.25rem;">
          <?php esc_html_e( 'Trending Screenings', 'bcs-screen-manager' ); ?>
        </span>
        <h2 style="font-size: 1.5rem; font-weight: 900; color: #fff; margin: 0; font-family: 'Space Grotesk', system-ui, sans-serif;">
          <?php esc_html_e( 'Popular Multiplex Presentations', 'bcs-screen-manager' ); ?>
        </h2>

        <div class="ifs-popular-grid">
          <?php foreach ( $popular_movies as $pop ) : 
            $p_poster = ! empty( $pop->poster_url ) ? esc_url( $pop->poster_url ) : 'https://images.unsplash.com/photo-1518709268805-4e9042af9f23?w=300&auto=format&fit=crop&q=75';
            $p_title  = esc_html( $pop->title );
            $p_genre  = esc_html( $pop->genre ?? 'Feature Presentation' );
            $p_rating = esc_html( $pop->rating ?? '8.0' );
            $p_link   = add_query_arg( 'movie_id', intval( $pop->id ), home_url( '/movie-details/' ) );
          ?>
            <div class="ifs-popular-card">
              <img src="<?php echo $p_poster; ?>" alt="<?php echo $p_title; ?>" class="ifs-popular-poster">
              <div class="ifs-popular-body">
                <div>
                  <h3 class="ifs-popular-title"><?php echo $p_title; ?></h3>
                  <span style="font-size: 0.75rem; color: var(--ifs-cinema-text-secondary); display: block;"><?php echo $p_genre; ?></span>
                </div>
                <div>
                  <div class="ifs-popular-meta">
                    <span>⭐ <?php echo $p_rating; ?> / 10</span>
                    <span>🎬 <?php echo intval( $pop->booking_count ); ?> Bookings</span>
                  </div>
                  <a href="<?php echo esc_url( $p_link ); ?>" class="ifs-popular-btn">
                    <?php esc_html_e( 'View Showtimes & Seats →', 'bcs-screen-manager' ); ?>
                  </a>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </section>
  <?php endif; ?>

</main>

<?php get_footer(); ?>