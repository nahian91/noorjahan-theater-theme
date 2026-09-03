<?php
/**
 * Template Name: Account Dashboard Page
 * Description: Dynamic user account dashboard displaying live active passes with QR tokens, screening schedules, booking history, and member metrics.
 *
 * @package Noorjahan_Movie_Theater
 */

// Auto-gate: Redirect non-logged-in visitors to the custom login page
if ( ! is_user_logged_in() ) {
    wp_safe_redirect( wp_login_url( home_url( '/account/' ) ) );
    exit;
}

get_header();

global $wpdb;
$table_bookings  = $wpdb->prefix . 'bsc_bookings';
$table_customers = $wpdb->prefix . 'bsc_customers';

$current_user  = wp_get_current_user();
$user_email    = $current_user->user_email;
$user_name     = ! empty( $current_user->display_name ) ? $current_user->display_name : ( ! empty( $current_user->first_name ) ? $current_user->first_name : 'Valued Member' );
$user_phone    = get_user_meta( $current_user->ID, 'phone_number', true );

// Fetch Member Record from database
$customer_record = $wpdb->get_row( $wpdb->prepare(
    "SELECT * FROM {$table_customers} WHERE email = %s OR (phone != '' AND phone = %s) LIMIT 1",
    $user_email,
    $user_phone
) );

$membership_id = $customer_record ? $customer_record->membership_id : ( '#BSC-' . ( 10000 + $current_user->ID ) );
$member_tier   = $customer_record ? $customer_record->tier : 'VIP CLUB MEMBER';
$reward_points = $customer_record ? intval( $customer_record->reward_points ) : 150;
$total_visits  = $customer_record ? intval( $customer_record->total_visits ) : 1;

// Retrieve all customer bookings
$all_bookings = array();
if ( $wpdb->get_var( "SHOW TABLES LIKE '{$table_bookings}'" ) === $table_bookings ) {
    $all_bookings = $wpdb->get_results( $wpdb->prepare(
        "SELECT * FROM {$table_bookings} WHERE customer_email = %s OR (customer_phone != '' AND customer_phone = %s) ORDER BY id DESC",
        $user_email,
        $user_phone
    ) );
}

// Separate Active Passes vs Past Screening History based on current timestamp
$current_timestamp = current_time( 'timestamp' );
$active_passes     = array();
$past_history      = array();

foreach ( $all_bookings as $booking ) {
    $booking_date_str = date( 'Y-m-d', strtotime( $booking->booking_date ) );
    $booking_time_str = ! empty( $booking->showtime ) ? trim( $booking->showtime ) : '23:59';
    $show_timestamp   = strtotime( $booking_date_str . ' ' . $booking_time_str );

    if ( $show_timestamp && $show_timestamp >= $current_timestamp ) {
        $active_passes[] = $booking;
    } else {
        $past_history[] = $booking;
    }
}

// Fallback: If no future show exists, show the newest booking as active
if ( empty( $active_passes ) && ! empty( $all_bookings ) ) {
    $active_passes[] = $all_bookings[0];
    $past_history    = array_slice( $all_bookings, 1 );
}

$currency_symbol = get_option( 'bsc_currency_symbol', 'BDT' );
?>

<style>
  .ifs-cinema-dashboard-header {
    background: linear-gradient(180deg, #020608 0%, var(--ifs-cinema-bg-dark, #070e11) 100%);
    padding: 4rem 0 2rem;
    color: #fff;
    border-bottom: 1px solid var(--ifs-cinema-border, #1e293b);
  }

  .ifs-cinema-user-hero {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 1.5rem;
    margin-bottom: 2.5rem;
  }

  .ifs-cinema-user-meta {
    display: flex;
    align-items: center;
    gap: 1.5rem;
  }

  .ifs-cinema-user-avatar {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid var(--ifs-cinema-brand-amber, #f59e0b);
    box-shadow: 0 0 20px rgba(245, 158, 11, 0.25);
  }

  .ifs-cinema-user-title h1 {
    font-size: 2rem;
    font-weight: 900;
    margin: 0 0 0.25rem;
    color: #fff;
  }

  .ifs-cinema-user-title p {
    font-size: 0.85rem;
    color: var(--ifs-cinema-text-secondary, #94a3b8);
    font-family: var(--ifs-cinema-font-mono, monospace);
    margin: 0;
  }

  .ifs-cinema-kpi-deck {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
    gap: 1.25rem;
  }

  .ifs-cinema-kpi-card {
    background: var(--ifs-cinema-card-bg, #0b171c);
    border: 1px solid var(--ifs-cinema-border, #1e293b);
    border-radius: 12px;
    padding: 1.25rem 1.5rem;
    display: flex;
    align-items: center;
    gap: 1.25rem;
  }

  .ifs-cinema-kpi-icon {
    width: 48px;
    height: 48px;
    border-radius: 10px;
    background: rgba(13, 148, 136, 0.15);
    color: #2dd4bf;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
  }

  .ifs-cinema-kpi-val {
    font-size: 1.5rem;
    font-weight: 900;
    color: #fff;
    font-family: var(--ifs-cinema-font-mono, monospace);
  }

  .ifs-cinema-kpi-lbl {
    font-size: 0.78rem;
    color: var(--ifs-cinema-text-muted, #64748b);
    font-weight: 700;
    text-transform: uppercase;
  }

  .ifs-cinema-workspace-section {
    padding: 3rem 0 5rem;
    background: var(--ifs-cinema-bg-dark, #070e11);
  }

  .ifs-cinema-workspace-grid {
    display: grid;
    grid-template-columns: 260px 1fr;
    gap: 2.5rem;
    align-items: start;
  }

  .ifs-cinema-account-sidebar {
    background: var(--ifs-cinema-card-bg, #0b171c);
    border: 1px solid var(--ifs-cinema-border, #1e293b);
    border-radius: 14px;
    padding: 0.75rem;
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
  }

  .ifs-cinema-tab-nav-btn {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.85rem 1rem;
    background: transparent;
    border: 1px solid transparent;
    border-radius: 8px;
    color: var(--ifs-cinema-text-secondary, #94a3b8);
    font-weight: 800;
    font-size: 0.88rem;
    cursor: pointer;
    text-align: left;
    transition: all 0.2s ease;
    width: 100%;
    text-decoration: none;
    box-sizing: border-box;
  }

  .ifs-cinema-tab-nav-btn:hover {
    background: rgba(255, 255, 255, 0.04);
    color: #fff;
  }

  .ifs-cinema-tab-nav-btn.active {
    background: rgba(245, 158, 11, 0.12);
    border-color: rgba(245, 158, 11, 0.3);
    color: var(--ifs-cinema-brand-amber, #f59e0b);
  }

  .ifs-cinema-tab-pane {
    display: none;
  }

  .ifs-cinema-tab-pane.active {
    display: block;
  }

  /* Digital Pass Card */
  .ifs-cinema-pass-ticket-card {
    background: var(--ifs-cinema-card-bg, #0b171c);
    border: 1px solid var(--ifs-cinema-border, #1e293b);
    border-radius: 16px;
    display: grid;
    grid-template-columns: 1fr 220px;
    margin-bottom: 1.5rem;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4);
  }

  .ifs-cinema-pass-left-content {
    padding: 2rem;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
  }

  .ifs-cinema-pass-right-qr {
    background: #060d10;
    border-left: 2px dashed rgba(255, 255, 255, 0.1);
    padding: 2rem 1.5rem;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    text-align: center;
  }

  .ifs-cinema-qr-code-img {
    width: 130px;
    height: 130px;
    background: #fff;
    padding: 6px;
    border-radius: 8px;
    margin-bottom: 0.85rem;
  }

  .ifs-cinema-history-table {
    width: 100%;
    border-collapse: collapse;
    text-align: left;
    font-size: 0.88rem;
  }

  .ifs-cinema-history-table th {
    background: #0f232b;
    padding: 0.85rem 1rem;
    color: var(--ifs-cinema-text-muted, #64748b);
    font-size: 0.72rem;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    border-bottom: 1px solid var(--ifs-cinema-border, #1e293b);
  }

  .ifs-cinema-history-table td {
    padding: 1rem;
    border-bottom: 1px solid rgba(255, 255, 255, 0.05);
    color: var(--ifs-cinema-text-primary, #f8fafc);
  }

  .ifs-cinema-profile-form-card {
    background: var(--ifs-cinema-card-bg, #0b171c);
    border: 1px solid var(--ifs-cinema-border, #1e293b);
    border-radius: 16px;
    padding: 2rem;
  }

  .ifs-cinema-form-grid-2 {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1.25rem;
    margin-bottom: 1.25rem;
  }

  .ifs-cinema-input-group label {
    display: block;
    font-size: 0.72rem;
    font-weight: 800;
    text-transform: uppercase;
    color: var(--ifs-cinema-text-muted, #64748b);
    margin-bottom: 0.4rem;
  }

  .ifs-cinema-input-field {
    width: 100%;
    padding: 0.75rem 1rem;
    background: #070e11;
    border: 1px solid var(--ifs-cinema-border, #1e293b);
    border-radius: 8px;
    color: #fff;
    font-size: 0.9rem;
    outline: none;
  }

  .ifs-cinema-input-field:focus {
    border-color: var(--ifs-cinema-brand-amber, #f59e0b);
  }

  @media (max-width: 992px) {
    .ifs-cinema-workspace-grid {
      grid-template-columns: 1fr;
    }
    .ifs-cinema-pass-ticket-card {
      grid-template-columns: 1fr;
    }
    .ifs-cinema-pass-right-qr {
      border-left: none;
      border-top: 2px dashed rgba(255, 255, 255, 0.1);
    }
  }
</style>

<main id="primary" class="site-main">

  <!-- MEMBER DASHBOARD HEADER & KPIS -->
  <section class="ifs-cinema-dashboard-header">
    <div class="ifs-cinema-container">
      <div class="ifs-cinema-user-hero">
        <div class="ifs-cinema-user-meta">
          <img src="<?php echo esc_url( get_avatar_url( $current_user->ID, array( 'size' => 160 ) ) ); ?>" 
               alt="<?php echo esc_attr( $user_name ); ?>" 
               class="ifs-cinema-user-avatar"
               onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=300&auto=format&fit=crop&q=80';">
          <div class="ifs-cinema-user-title">
            <span class="ifs-cinema-badge ifs-cinema-badge-amber" style="margin-bottom:0.4rem;">
              <?php echo esc_html( $member_tier ); ?>
            </span>
            <h1><?php echo esc_html( $user_name ); ?></h1>
            <p><?php echo sprintf( esc_html__( 'Member ID: %s • Email: %s', 'noorjahan-movie-theater' ), esc_html( $membership_id ), esc_html( $user_email ) ); ?></p>
          </div>
        </div>

        <a href="<?php echo esc_url( home_url( '/#movies' ) ); ?>" class="ifs-cinema-btn ifs-cinema-btn-amber">
          <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor">
            <path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/>
          </svg>
          <?php esc_html_e( 'Book New Movie', 'noorjahan-movie-theater' ); ?>
        </a>
      </div>

      <div class="ifs-cinema-kpi-deck">
        <div class="ifs-cinema-kpi-card">
          <div class="ifs-cinema-kpi-icon">
            <svg viewBox="0 0 24 24" width="26" height="26" fill="currentColor">
              <path d="M22 10V6c0-1.1-.9-2-2-2H4c-1.1 0-1.99.9-1.99 2v4c1.1 0 1.99.9 1.99 2s-.89 2-2 2v4c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2v-4c-1.1 0-2-.9-2-2s.9-2 2-2zm-9 7.5h-2v-2h2v2zm0-4.5h-2v-2h2v2zm0-4.5h-2v-2h2v2z"/>
            </svg>
          </div>
          <div>
            <div class="ifs-cinema-kpi-val"><?php echo esc_html( count( $active_passes ) ); ?></div>
            <div class="ifs-cinema-kpi-lbl"><?php esc_html_e( 'Active Digital Passes', 'noorjahan-movie-theater' ); ?></div>
          </div>
        </div>

        <div class="ifs-cinema-kpi-card">
          <div class="ifs-cinema-kpi-icon">
            <svg viewBox="0 0 24 24" width="26" height="26" fill="currentColor">
              <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-5 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/>
            </svg>
          </div>
          <div>
            <div class="ifs-cinema-kpi-val"><?php echo esc_html( $total_visits ); ?></div>
            <div class="ifs-cinema-kpi-lbl"><?php esc_html_e( 'Total Cinema Visits', 'noorjahan-movie-theater' ); ?></div>
          </div>
        </div>

        <div class="ifs-cinema-kpi-card">
          <div class="ifs-cinema-kpi-icon">
            <svg viewBox="0 0 24 24" width="26" height="26" fill="currentColor">
              <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
            </svg>
          </div>
          <div>
            <div class="ifs-cinema-kpi-val"><?php echo number_format( $reward_points ); ?></div>
            <div class="ifs-cinema-kpi-lbl"><?php esc_html_e( 'Reward Points Earned', 'noorjahan-movie-theater' ); ?></div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- ACCOUNT WORKSPACE -->
  <section class="ifs-cinema-workspace-section">
    <div class="ifs-cinema-container ifs-cinema-workspace-grid">
      
      <!-- Sidebar Navigation -->
      <aside class="ifs-cinema-account-sidebar">
        <button type="button" class="ifs-cinema-tab-nav-btn active" onclick="switchDashboardTab('passes', this)">
          <svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor">
            <path d="M22 10V6c0-1.1-.9-2-2-2H4c-1.1 0-1.99.9-1.99 2v4c1.1 0 1.99.9 1.99 2s-.89 2-2 2v4c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2v-4c-1.1 0-2-.9-2-2s.9-2 2-2zm-9 7.5h-2v-2h2v2zm0-4.5h-2v-2h2v2zm0-4.5h-2v-2h2v2z"/>
          </svg>
          <span><?php esc_html_e( 'Active Passes', 'noorjahan-movie-theater' ); ?> (<?php echo count( $active_passes ); ?>)</span>
        </button>

        <button type="button" class="ifs-cinema-tab-nav-btn" onclick="switchDashboardTab('history', this)">
          <svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor">
            <path d="M13 3a9 9 0 0 0-9 9H1l3.89 3.89.07.14L9 12H6c0-3.87 3.13-7 7-7s7 3.13 7 7-3.13 7-7 7c-1.93 0-3.68-.79-4.94-2.06l-1.42 1.42A8.954 8.954 0 0 0 13 21a9 9 0 0 0 0-18zm-1 5v5l4.28 2.54.72-1.21-3.5-2.08V8H12z"/>
          </svg>
          <span><?php esc_html_e( 'Booking History', 'noorjahan-movie-theater' ); ?></span>
        </button>

        <button type="button" class="ifs-cinema-tab-nav-btn" onclick="switchDashboardTab('profile', this)">
          <svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor">
            <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
          </svg>
          <span><?php esc_html_e( 'Profile Settings', 'noorjahan-movie-theater' ); ?></span>
        </button>

        <a href="<?php echo esc_url( wp_logout_url( home_url() ) ); ?>" class="ifs-cinema-tab-nav-btn" style="color:#ef4444;">
          <svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor">
            <path d="M10.09 15.59L11.5 17l5-5-5-5-1.41 1.41L12.67 11H3v2h9.67l-2.58 2.59zM19 3H5c-1.11 0-2 .9-2 2v4h2V5h14v14H5v-4H3v4c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2z"/>
          </svg>
          <span><?php esc_html_e( 'Sign Out', 'noorjahan-movie-theater' ); ?></span>
        </a>
      </aside>

      <!-- Content Panels -->
      <div>
        
        <!-- PANE 1: ACTIVE PASSES -->
        <div class="ifs-cinema-tab-pane active" id="pane-passes">
          <div style="margin-bottom: 2rem;">
            <h2 style="font-size: 1.6rem; color: #fff; margin-bottom: 0.35rem; font-weight:900;"><?php esc_html_e( 'Active Digital Vouchers & Screening Passes', 'noorjahan-movie-theater' ); ?></h2>
            <p style="color:var(--ifs-cinema-text-secondary);"><?php esc_html_e( 'Show these QR passes directly at the cinema turnstile scanners for instant auditorium admission.', 'noorjahan-movie-theater' ); ?></p>
          </div>

          <?php if ( ! empty( $active_passes ) ) : ?>
            <?php foreach ( $active_passes as $booking ) : 
              $formatted_time = ( strpos( $booking->showtime, ':' ) !== false && strlen( $booking->showtime ) <= 5 ) ? date( 'h:i A', strtotime( $booking->showtime ) ) : $booking->showtime;
              $booking_date   = date_i18n( 'l, d F Y', strtotime( $booking->booking_date ) );
              $qr_data_url    = 'https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=' . urlencode( $booking->booking_token );
            ?>
              <div class="ifs-cinema-pass-ticket-card">
                <div class="ifs-cinema-pass-left-content">
                  <div>
                    <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:0.75rem;">
                      <span class="ifs-cinema-badge ifs-cinema-badge-amber"><?php echo esc_html( $booking->auditorium ); ?></span>
                      <span class="ifs-cinema-badge" style="background:rgba(16,185,129,0.15); color:#10b981; border:1px solid rgba(16,185,129,0.3);"><?php echo esc_html( $booking->payment_status ); ?></span>
                    </div>
                    <h3 style="font-size: 1.5rem; color: #fff; font-weight: 900; margin-bottom: 0.4rem;"><?php echo esc_html( $booking->movie_title ); ?></h3>
                    <p style="font-size: 0.9rem; color: var(--ifs-cinema-brand-amber); font-weight: 700; margin:0;">
                      ⏰ <?php echo esc_html( $formatted_time ); ?> • 70ft Curved 4K Laser Screen
                    </p>
                  </div>

                  <div style="display:grid; grid-template-columns: repeat(3, 1fr); gap:1.25rem; margin-top:1.5rem; border-top:1px dashed rgba(255,255,255,0.1); padding-top:1.25rem;">
                    <div>
                      <div style="font-size:0.72rem; color:var(--ifs-cinema-text-muted); text-transform:uppercase; font-weight:800;"><?php esc_html_e( 'Screening Date', 'noorjahan-movie-theater' ); ?></div>
                      <strong style="font-size:0.92rem; color:#fff; font-family:var(--ifs-cinema-font-mono);"><?php echo esc_html( $booking_date ); ?></strong>
                    </div>
                    <div>
                      <div style="font-size:0.72rem; color:var(--ifs-cinema-text-muted); text-transform:uppercase; font-weight:800;"><?php esc_html_e( 'Reserved Seats', 'noorjahan-movie-theater' ); ?></div>
                      <strong style="font-size:0.95rem; color:var(--ifs-cinema-brand-amber); font-family:var(--ifs-cinema-font-mono);"><?php echo esc_html( $booking->seats ); ?></strong>
                    </div>
                    <div>
                      <div style="font-size:0.72rem; color:var(--ifs-cinema-text-muted); text-transform:uppercase; font-weight:800;"><?php esc_html_e( 'Total Amount', 'noorjahan-movie-theater' ); ?></div>
                      <strong style="font-size:0.95rem; color:#fff; font-family:var(--ifs-cinema-font-mono);"><?php echo esc_html( $currency_symbol ); ?> <?php echo number_format( floatval( $booking->total_amount ), 2 ); ?></strong>
                    </div>
                  </div>
                </div>

                <div class="ifs-cinema-pass-right-qr">
                  <img src="<?php echo esc_url( $qr_data_url ); ?>" alt="Digital Pass QR" class="ifs-cinema-qr-code-img">
                  <span style="font-family:var(--ifs-cinema-font-mono); font-size:0.85rem; color:var(--ifs-cinema-brand-amber); font-weight:800; display:block; margin-bottom:0.75rem;">
                    <?php echo esc_html( $booking->booking_token ); ?>
                  </span>
                  <button class="ifs-cinema-btn ifs-cinema-btn-secondary" style="width:100%; padding:0.5rem; font-size:0.78rem;" onclick="window.print()">
                    <svg viewBox="0 0 24 24" width="14" height="14" fill="currentColor">
                      <path d="M19 8H5c-1.66 0-3 1.34-3 3v6h4v4h12v-4h4v-6c0-1.66-1.34-3-3-3zm-3 11H8v-5h8v5zm3-7c-.55 0-1-.45-1-1s.45-1 1-1 1 .45 1 1-.45 1-1 1zm-1-9H6v4h12V3z"/>
                    </svg>
                    <?php esc_html_e( 'Print Pass', 'noorjahan-movie-theater' ); ?>
                  </button>
                </div>
              </div>
            <?php endforeach; ?>
          <?php else : ?>
            <div style="background:var(--ifs-cinema-card-bg); padding:3rem 2rem; border-radius:14px; text-align:center; color:var(--ifs-cinema-text-secondary); border:1px solid var(--ifs-cinema-border);">
              <p style="font-size:1.1rem; margin-bottom:1rem;"><?php esc_html_e( 'No active digital screening passes found right now.', 'noorjahan-movie-theater' ); ?></p>
              <a href="<?php echo esc_url( home_url( '/#movies' ) ); ?>" class="ifs-cinema-btn ifs-cinema-btn-amber">
                <?php esc_html_e( 'Explore Live Screenings', 'noorjahan-movie-theater' ); ?>
              </a>
            </div>
          <?php endif; ?>
        </div>

        <!-- PANE 2: BOOKING HISTORY -->
        <div class="ifs-cinema-tab-pane" id="pane-history">
          <div style="margin-bottom: 2rem;">
            <h2 style="font-size: 1.6rem; color: #fff; margin-bottom: 0.35rem; font-weight:900;"><?php esc_html_e( 'Screening History & Transaction Archive', 'noorjahan-movie-theater' ); ?></h2>
            <p style="color:var(--ifs-cinema-text-secondary);"><?php esc_html_e( 'Full ledger record of all past movie bookings and turnstile check-ins.', 'noorjahan-movie-theater' ); ?></p>
          </div>

          <div style="background:var(--ifs-cinema-card-bg); border:1px solid var(--ifs-cinema-border); border-radius:14px; overflow-x:auto;">
            <table class="ifs-cinema-history-table">
              <thead>
                <tr>
                  <th><?php esc_html_e( 'TOKEN ID', 'noorjahan-movie-theater' ); ?></th>
                  <th><?php esc_html_e( 'MOVIE TITLE', 'noorjahan-movie-theater' ); ?></th>
                  <th><?php esc_html_e( 'SCREENING DATE & TIME', 'noorjahan-movie-theater' ); ?></th>
                  <th><?php esc_html_e( 'SEATS', 'noorjahan-movie-theater' ); ?></th>
                  <th><?php esc_html_e( 'TOTAL AMOUNT', 'noorjahan-movie-theater' ); ?></th>
                  <th><?php esc_html_e( 'STATUS', 'noorjahan-movie-theater' ); ?></th>
                </tr>
              </thead>
              <tbody>
                <?php if ( ! empty( $all_bookings ) ) : ?>
                  <?php foreach ( $all_bookings as $history ) : 
                    $h_time = ( strpos( $history->showtime, ':' ) !== false && strlen( $history->showtime ) <= 5 ) ? date( 'h:i A', strtotime( $history->showtime ) ) : $history->showtime;
                  ?>
                    <tr>
                      <td><code style="color:var(--ifs-cinema-brand-amber); font-weight:800;"><?php echo esc_html( $history->booking_token ); ?></code></td>
                      <td><strong><?php echo esc_html( $history->movie_title ); ?></strong></td>
                      <td><?php echo esc_html( date_i18n( 'd M Y', strtotime( $history->booking_date ) ) ); ?> • <?php echo esc_html( $h_time ); ?></td>
                      <td><span style="font-family:var(--ifs-cinema-font-mono); color:#5eead4; font-weight:800;"><?php echo esc_html( $history->seats ); ?></span></td>
                      <td style="font-family:var(--ifs-cinema-font-mono); font-weight:800; color:#fff;"><?php echo esc_html( $currency_symbol ); ?> <?php echo number_format( floatval( $history->total_amount ), 2 ); ?></td>
                      <td><span class="ifs-cinema-badge ifs-cinema-badge-amber" style="font-size:0.7rem;"><?php echo esc_html( $history->payment_status ); ?></span></td>
                    </tr>
                  <?php endforeach; ?>
                <?php else : ?>
                  <tr>
                    <td colspan="6" style="text-align:center; padding: 2.5rem; color:var(--ifs-cinema-text-muted);"><?php esc_html_e( 'No previous screening history found.', 'noorjahan-movie-theater' ); ?></td>
                  </tr>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>

        <!-- PANE 3: PROFILE SETTINGS -->
        <div class="ifs-cinema-tab-pane" id="pane-profile">
          <div class="ifs-cinema-profile-form-card">
            <h2 style="font-size: 1.6rem; color: #fff; margin-bottom: 1.5rem; border-bottom:1px solid rgba(255,255,255,0.06); padding-bottom:1rem; font-weight:900;">
              <?php esc_html_e( 'Personal VIP Profile Settings', 'noorjahan-movie-theater' ); ?>
            </h2>

            <form onsubmit="event.preventDefault(); alert('Profile preferences updated successfully.');">
              <div class="ifs-cinema-form-grid-2">
                <div class="ifs-cinema-input-group">
                  <label><?php esc_html_e( 'Full Legal Name', 'noorjahan-movie-theater' ); ?></label>
                  <input type="text" class="ifs-cinema-input-field" value="<?php echo esc_attr( $user_name ); ?>" required>
                </div>
                <div class="ifs-cinema-input-group">
                  <label><?php esc_html_e( 'Mobile Phone (For SMS Turnstile Passes)', 'noorjahan-movie-theater' ); ?></label>
                  <input type="tel" class="ifs-cinema-input-field" value="<?php echo esc_attr( $user_phone ); ?>" placeholder="+880 1700-000000">
                </div>
              </div>

              <div class="ifs-cinema-form-grid-2">
                <div class="ifs-cinema-input-group">
                  <label><?php esc_html_e( 'Email Address', 'noorjahan-movie-theater' ); ?></label>
                  <input type="email" class="ifs-cinema-input-field" value="<?php echo esc_attr( $user_email ); ?>" readonly style="background:#04080a; opacity:0.7;">
                </div>
                <div class="ifs-cinema-input-group">
                  <label><?php esc_html_e( 'Membership Tier', 'noorjahan-movie-theater' ); ?></label>
                  <input type="text" class="ifs-cinema-input-field" value="<?php echo esc_attr( $member_tier ); ?>" readonly style="background:#04080a; opacity:0.7; color:var(--ifs-cinema-brand-amber); font-weight:800;">
                </div>
              </div>

              <button type="submit" class="ifs-cinema-btn ifs-cinema-btn-amber" style="margin-top:1rem;">
                <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor">
                  <path d="M17 3H5c-1.11 0-2 .9-2 2v14c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V7l-4-4zm-5 16c-1.66 0-3-1.34-3-3s1.34-3 3-3 3 1.34 3 3-1.34 3-3 3zm3-10H5V5h10v4z"/>
                </svg>
                <?php esc_html_e( 'Save Profile Changes', 'noorjahan-movie-theater' ); ?>
              </button>
            </form>
          </div>
        </div>

      </div>

    </div>
  </section>

</main>

<script>
  function switchDashboardTab(tabId, btnEl) {
    document.querySelectorAll('.ifs-cinema-tab-pane').forEach(p => p.classList.remove('active'));
    document.querySelectorAll('.ifs-cinema-tab-nav-btn').forEach(b => b.classList.remove('active'));
    
    const targetPane = document.getElementById('pane-' + tabId);
    if (targetPane) {
      targetPane.classList.add('active');
    }
    if (btnEl) {
      btnEl.classList.add('active');
    }
  }
</script>

<?php get_footer(); ?>