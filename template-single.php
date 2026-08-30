<?php
/**
 * Template Name: Single Movie Details
 * Description: Dynamic Single Movie Details, Sidebar Showtime Selectors, Auto-Redirect Login Gate, and Seating Matrix template with dynamic pricing tiers.
 *
 * @package Noorjahan_Movie_Theater
 */

get_header();

global $wpdb;
$table_movies   = $wpdb->prefix . 'bsc_control_movies';
$table_bookings = $wpdb->prefix . 'bsc_bookings';

// Resolve targeted movie by ID query param or fallback to latest record
$movie_id = isset( $_GET['movie_id'] ) ? intval( $_GET['movie_id'] ) : ( isset( $_GET['id'] ) ? intval( $_GET['id'] ) : 0 );

if ( $movie_id > 0 ) {
    $movie = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$table_movies} WHERE id = %d", $movie_id ) );
} else {
    $movie = $wpdb->get_row( "SELECT * FROM {$table_movies} ORDER BY id DESC LIMIT 1" );
}

// Fallback values if database contains no records
$curr_movie_id     = ! empty( $movie->id ) ? intval( $movie->id ) : 1;
$title             = ! empty( $movie->title ) ? $movie->title : 'FOOD COMBO (EVIL DEAD BURN)';
$poster_url        = ! empty( $movie->poster_url ) ? esc_url( $movie->poster_url ) : 'https://images.unsplash.com/photo-1518709268805-4e9042af9f23?w=700&auto=format&fit=crop&q=75';
$trailer_url       = ! empty( $movie->trailer_url ) ? esc_url( $movie->trailer_url ) : '';
$rating            = ! empty( $movie->rating ) ? esc_html( $movie->rating ) : '8.6';
$age_rating        = ! empty( $movie->age_rating ) ? esc_html( $movie->age_rating ) : 'PG-13';
$duration          = ! empty( $movie->duration ) ? esc_html( $movie->duration ) : '01 Hours 50 Minutes';
$genre             = ! empty( $movie->genre ) ? esc_html( $movie->genre ) : 'Horror / Supernatural Thriller';
$audio_language    = ! empty( $movie->audio_language ) ? esc_html( $movie->audio_language ) : 'English (Dolby Atmos® 128-Ch)';
$auditorium        = ! empty( $movie->auditorium ) ? esc_html( $movie->auditorium ) : 'Auditorium 1 (23 Seats)';
$showtimes_raw     = ! empty( $movie->showtimes ) ? $movie->showtimes : '["18:00"]';
$badge_tag         = ! empty( $movie->badge_tag ) ? esc_html( $movie->badge_tag ) : '4K DUAL LASER';
$director          = ! empty( $movie->director ) ? esc_html( $movie->director ) : 'Darren Aron';
$writers           = ! empty( $movie->writers ) ? esc_html( $movie->writers ) : 'Sam Raimi, James Wan';
$short_description = ! empty( $movie->short_description ) ? esc_html( $movie->short_description ) : 'Experience our signature dine-in package with chef-prepared refreshments and premium seating.';
$synopsis          = ! empty( $movie->synopsis ) ? wp_kses_post( $movie->synopsis ) : 'A secluded mountain sanctuary turns into a fiery gauntlet when an ancient relic is unearthed during a solar alignment.';

// Parse screening dates
$release_dates_array = array( current_time( 'Y-m-d' ) );
if ( ! empty( $movie->release_date ) ) {
    $decoded_dates = json_decode( $movie->release_date, true );
    if ( is_array( $decoded_dates ) ) {
        $release_dates_array = $decoded_dates;
    } else {
        $release_dates_array = array_map( 'trim', explode( ',', $movie->release_date ) );
    }
}

// Parse showtimes into array
$showtimes_array = array( '6:00 PM' );
if ( ! empty( $showtimes_raw ) ) {
    $decoded_times = json_decode( $showtimes_raw, true );
    if ( is_array( $decoded_times ) ) {
        $showtimes_array = $decoded_times;
    } else {
        $showtimes_array = array_map( 'trim', explode( ',', $showtimes_raw ) );
    }
}

// Parse cast and crew roster
$cast_list = array();
if ( ! empty( $movie->cast_crew ) ) {
    $cast_list = json_decode( $movie->cast_crew, true );
}
if ( empty( $cast_list ) ) {
    $cast_list = array(
        array( 'name' => 'Elena Rostova', 'role' => 'Dr. Maya Vance', 'photo' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=200&auto=format&fit=crop&q=70' ),
        array( 'name' => 'Marcus Cole', 'role' => 'Captain Gabriel', 'photo' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=200&auto=format&fit=crop&q=70' ),
    );
}

// Retrieve Dynamic Pricing Tiers from Settings Repeater
$pricing_tiers = get_option('bsc_pricing_tiers', array(
    array('title' => 'Standard Seating', 'amount' => '400', 'description' => 'Applied across standard Rows B, C, D.'),
    array('title' => 'VIP Motorized Recliner', 'amount' => '500', 'description' => 'Applied across premium Row A.')
));

// Fallback prices for JS calculations
$standard_price = 400.00;
$vip_price = 500.00;
foreach ($pricing_tiers as $tier) {
    if (stripos($tier['title'], 'vip') !== false) {
        $vip_price = floatval($tier['amount']);
    } else {
        $standard_price = floatval($tier['amount']);
    }
}

$service_tax_pct = floatval( get_option( 'bsc_service_charge_pct', 5.00 ) );
$enable_tax      = get_option( 'bsc_enable_tax', 'yes' );
$currency_symbol = get_option( 'bsc_currency_symbol', 'BDT' );
$checkout_url    = home_url( '/checkout/' );

// Current Page URL redirection support for login gate
global $wp;
$current_page_url = home_url( add_query_arg( array(), $wp->request ) );
if ( ! empty( $_SERVER['QUERY_STRING'] ) ) {
    $current_page_url .= '?' . $_SERVER['QUERY_STRING'];
}
$login_url      = wp_login_url( $current_page_url );
$is_user_logged = is_user_logged_in();

// Retrieve Occupied Seats for this movie
$occupied_seats = array();
$booked_raw = $wpdb->get_col( $wpdb->prepare(
    "SELECT seats FROM {$table_bookings} WHERE movie_title = %s",
    $title
) );
if ( ! empty( $booked_raw ) ) {
    foreach ( $booked_raw as $s_str ) {
        $parts = explode( ',', $s_str );
        foreach ( $parts as $p ) {
            $trimmed = trim( $p );
            if ( ! empty( $trimmed ) ) {
                $occupied_seats[] = $trimmed;
            }
        }
    }
}
$occupied_seats = array_unique( $occupied_seats );
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
    --ifs-cinema-border-light: rgba(255, 255, 255, 0.1);
    --ifs-cinema-border-subtle: rgba(255, 255, 255, 0.06);
    --ifs-cinema-seat-standard: #0d9488;
    --ifs-cinema-seat-vip: #b45309;
    --ifs-cinema-seat-selected: #f59e0b;
    --ifs-cinema-seat-occupied: #334155;
    --ifs-cinema-font-mono: 'Space Grotesk', monospace, system-ui;
  }

  .ifs-cinema-single-movie-hero {
    background: linear-gradient(180deg, #020608 0%, var(--ifs-cinema-bg-dark) 100%);
    color: var(--ifs-cinema-text-primary);
    padding: 4rem 1.5rem 2rem;
  }

  .ifs-cinema-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 1rem;
    box-sizing: border-box;
  }

  .ifs-cinema-movie-header-grid {
    display: grid;
    grid-template-columns: 320px 1fr;
    gap: 2.5rem;
    align-items: start;
  }

  .ifs-cinema-poster-frame-3d img {
    width: 100%;
    border-radius: 16px;
    box-shadow: 0 20px 40px rgba(0,0,0,0.6);
    border: 2px solid var(--ifs-cinema-border);
    display: block;
  }

  .ifs-cinema-badge {
    display: inline-flex;
    align-items: center;
    padding: 0.25rem 0.65rem;
    border-radius: 9999px;
    font-size: 0.72rem;
    font-weight: 800;
    letter-spacing: 0.05em;
    text-transform: uppercase;
  }

  .ifs-cinema-badge-amber {
    background: rgba(245, 158, 11, 0.15);
    color: var(--ifs-cinema-brand-amber);
    border: 1px solid rgba(245, 158, 11, 0.35);
  }

  .ifs-cinema-badge-imax {
    background: rgba(13, 148, 136, 0.15);
    color: #2dd4bf;
    border: 1px solid rgba(13, 148, 136, 0.35);
  }

  .ifs-cinema-hero-main-title {
    font-size: 2.5rem;
    font-weight: 900;
    letter-spacing: -0.02em;
    margin: 0 0 1rem 0;
    color: #fff;
    line-height: 1.15;
  }

  .ifs-cinema-specs-hud-bar {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
    margin-bottom: 1.25rem;
  }

  .ifs-cinema-hud-item {
    background: #0f232b;
    border: 1px solid var(--ifs-cinema-border);
    padding: 0.35rem 0.75rem;
    border-radius: 6px;
    font-size: 0.8rem;
    font-weight: 700;
    color: var(--ifs-cinema-text-secondary);
  }

  .ifs-cinema-hud-item.highlight {
    background: rgba(245, 158, 11, 0.12);
    color: var(--ifs-cinema-brand-amber);
    border-color: rgba(245, 158, 11, 0.3);
  }

  .ifs-cinema-movie-credits-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1rem;
    margin: 1.5rem 0 2rem;
  }

  .ifs-cinema-credit-box {
    background: rgba(255, 255, 255, 0.03);
    border: 1px solid var(--ifs-cinema-border);
    padding: 0.75rem 1rem;
    border-radius: 8px;
  }

  .ifs-cinema-credit-box .key {
    font-size: 0.72rem;
    text-transform: uppercase;
    font-weight: 800;
    color: var(--ifs-cinema-text-muted);
  }

  .ifs-cinema-credit-box .val {
    font-size: 0.95rem;
    font-weight: 800;
    color: #fff;
    margin-top: 2px;
  }

  .ifs-cinema-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    padding: 0.85rem 1.6rem;
    border-radius: 8px;
    font-weight: 800;
    font-size: 0.95rem;
    cursor: pointer;
    transition: all 0.2s ease;
    border: none;
    text-decoration: none;
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

  /* SEATING MATRIX ARENA */
  .ifs-cinema-stage-card {
    background: #060d10;
    border: 1px solid var(--ifs-cinema-border);
    border-radius: 20px;
    padding: 2rem;
    display: grid;
    grid-template-columns: 1fr 340px;
    gap: 2rem;
    box-shadow: 0 20px 40px rgba(0,0,0,0.5);
    margin-bottom: 3.5rem;
  }

  .ifs-cinema-seating-arena {
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    background: radial-gradient(circle at center top, #0e2026 0%, #060d10 80%);
    border-radius: 14px;
    padding: 2rem 1.5rem 1.5rem;
    border: 1px solid rgba(255, 255, 255, 0.05);
  }

  .ifs-cinema-map-layout-wrapper {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 1.5rem;
    overflow-x: auto;
    padding-bottom: 1.5rem;
  }

  .ifs-cinema-boundary-line {
    width: 2px;
    height: 280px;
    background: dashed rgba(255, 255, 255, 0.15);
  }

  .ifs-cinema-columns-matrix {
    display: flex;
    align-items: center;
    gap: 1.25rem;
  }

  .ifs-cinema-seat-column {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.65rem;
  }

  .ifs-cinema-column-seats {
    display: flex;
    flex-direction: column;
    gap: 0.65rem;
  }

  .ifs-cinema-col-divider-label {
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    height: 100%;
    padding: 0.5rem 0;
    color: var(--ifs-cinema-text-muted);
    font-size: 0.72rem;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.05em;
  }

  .ifs-cinema-vip-col-label {
    font-size: 0.7rem;
    font-weight: 800;
    color: var(--ifs-cinema-brand-amber);
    margin-top: 0.4rem;
    letter-spacing: 0.05em;
  }

  .ifs-cinema-seat-box {
    width: 46px;
    height: 46px;
    border-radius: 8px;
    border: 2px solid transparent;
    font-weight: 800;
    font-size: 0.85rem;
    font-family: var(--ifs-cinema-font-mono);
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s ease;
    user-select: none;
    outline: none;
  }

  .ifs-cinema-seat-box.vip {
    background: #451a03;
    border-color: #b45309;
    color: #fde68a;
  }

  .ifs-cinema-seat-box.standard {
    background: #0f2e28;
    border-color: #0d9488;
    color: #99f6e4;
  }

  .ifs-cinema-seat-box:hover:not(:disabled) {
    transform: scale(1.08);
    box-shadow: 0 4px 12px rgba(0,0,0,0.4);
  }

  .ifs-cinema-seat-box.selected {
    background: var(--ifs-cinema-brand-amber) !important;
    border-color: #fde68a !important;
    color: #000 !important;
    box-shadow: 0 0 14px rgba(245, 158, 11, 0.7);
    transform: scale(1.1);
  }

  .ifs-cinema-seat-box:disabled, .ifs-cinema-seat-box.occupied {
    background: var(--ifs-cinema-seat-occupied) !important;
    border-color: #475569 !important;
    color: #64748b !important;
    cursor: not-allowed;
    opacity: 0.35;
    transform: none !important;
    box-shadow: none !important;
  }

  .ifs-cinema-screen-target-indicator {
    display: flex;
    align-items: center;
    gap: 1rem;
  }

  .ifs-cinema-screen-pointer {
    display: flex;
    flex-direction: column;
    align-items: center;
    color: var(--ifs-cinema-brand-teal);
    font-weight: 900;
    letter-spacing: 0.1em;
  }

  .ifs-cinema-screen-pointer .title {
    writing-mode: vertical-rl;
    text-orientation: upright;
    font-size: 0.85rem;
    margin-bottom: 0.5rem;
  }

  .ifs-cinema-legend {
    display: flex;
    flex-wrap: wrap;
    gap: 1.25rem;
    justify-content: center;
    padding-top: 1.25rem;
    border-top: 1px solid rgba(255, 255, 255, 0.08);
    font-size: 0.8rem;
    color: var(--ifs-cinema-text-secondary);
  }

  .ifs-cinema-legend-item {
    display: flex;
    align-items: center;
    gap: 0.45rem;
  }

  .ifs-cinema-legend-bullet {
    width: 14px;
    height: 14px;
    border-radius: 4px;
  }

  /* Checkout Summary Sidebar */
  .ifs-cinema-checkout-sidebar {
    background: var(--ifs-cinema-card-bg);
    border: 1px solid var(--ifs-cinema-border);
    border-radius: 14px;
    padding: 1.5rem;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
  }

  .ifs-cinema-movie-meta-summary h3 {
    margin: 0 0 0.25rem 0;
    font-size: 1.15rem;
    font-weight: 900;
    color: #fff;
  }

  .ifs-cinema-hall-tag {
    font-size: 0.75rem;
    color: var(--ifs-cinema-brand-teal);
    font-weight: 700;
    display: block;
    margin-bottom: 1.25rem;
  }

  .ifs-cinema-chips-wrapper {
    display: flex;
    flex-wrap: wrap;
    gap: 0.4rem;
    min-height: 42px;
    padding: 0.5rem;
    background: rgba(0,0,0,0.3);
    border: 1px solid var(--ifs-cinema-border);
    border-radius: 6px;
    margin-bottom: 1.25rem;
    align-items: center;
  }

  .ifs-cinema-seat-chip {
    background: var(--ifs-cinema-brand-teal);
    color: #fff;
    font-size: 0.75rem;
    font-weight: 800;
    padding: 0.2rem 0.5rem;
    border-radius: 4px;
    font-family: var(--ifs-cinema-font-mono);
  }

  /* Content Cards Section */
  .ifs-cinema-details-layout {
    display: grid;
    grid-template-columns: 1fr;
    gap: 2rem;
    margin-bottom: 4rem;
  }

  .ifs-cinema-content-card {
    background: var(--ifs-cinema-card-bg);
    border: 1px solid var(--ifs-cinema-border);
    border-radius: 16px;
    padding: 2rem;
    color: var(--ifs-cinema-text-primary);
  }

  .ifs-cinema-card-heading {
    font-size: 1.35rem;
    font-weight: 900;
    margin: 0 0 1.25rem 0;
    color: #fff;
    display: flex;
    align-items: center;
    gap: 0.6rem;
  }

  .ifs-cinema-cast-deck {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 1.25rem;
  }

  .ifs-cinema-actor-card {
    background: rgba(255, 255, 255, 0.02);
    border: 1px solid var(--ifs-cinema-border);
    padding: 1rem;
    border-radius: 10px;
    text-align: center;
  }

  .ifs-cinema-actor-photo {
    width: 70px;
    height: 70px;
    border-radius: 50%;
    object-fit: cover;
    margin: 0 auto 0.75rem;
    border: 2px solid var(--ifs-cinema-brand-teal);
    display: block;
  }

  .ifs-cinema-actor-name {
    font-weight: 800;
    font-size: 0.95rem;
    color: #fff;
  }

  .ifs-cinema-actor-role {
    font-size: 0.78rem;
    color: var(--ifs-cinema-text-muted);
  }

  @media (max-width: 992px) {
    .ifs-cinema-movie-header-grid {
      grid-template-columns: 1fr;
    }
    .ifs-cinema-stage-card {
      grid-template-columns: 1fr;
    }
  }
</style>

  <!-- 01. SINGLE MOVIE IMMERSIVE BACKDROP HERO -->
  <section class="ifs-cinema-single-movie-hero">
    <div class="ifs-cinema-container ifs-cinema-movie-header-grid">
      
      <!-- Poster Frame -->
      <div class="ifs-cinema-poster-frame-3d">
        <img src="<?php echo $poster_url; ?>" alt="<?php echo esc_attr( $title ); ?>">
      </div>

      <!-- Specs & Overview -->
      <div>
        <div style="display:flex; gap:0.65rem; margin-bottom:1.25rem;">
          <span class="ifs-cinema-badge ifs-cinema-badge-amber"><?php echo strtoupper( esc_html( $auditorium ) ); ?></span>
          <span class="ifs-cinema-badge ifs-cinema-badge-imax"><?php echo esc_html( $badge_tag ); ?></span>
        </div>

        <h1 class="ifs-cinema-hero-main-title"><?php echo esc_html( $title ); ?></h1>

        <div class="ifs-cinema-specs-hud-bar">
          <div class="ifs-cinema-hud-item highlight">
            <svg viewBox="0 0 24 24" width="13" height="13" fill="currentColor" style="display:inline-block; vertical-align:-1px; margin-right:2px;">
              <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
            </svg>
            <?php echo $rating; ?> / 10 Master Rating
          </div>
          <div class="ifs-cinema-hud-item"><?php echo $duration; ?></div>
          <div class="ifs-cinema-hud-item"><?php echo $genre; ?></div>
          <div class="ifs-cinema-hud-item"><?php echo $audio_language; ?></div>
          <div class="ifs-cinema-hud-item"><?php echo $age_rating; ?></div>
        </div>

        <p style="font-size:1.15rem; line-height:1.8; color:var(--ifs-cinema-text-secondary); max-width:680px; margin-bottom:1.5rem;">
          <?php echo $short_description; ?>
        </p>

        <!-- Credit Key-Values -->
        <div class="ifs-cinema-movie-credits-grid">
          <div class="ifs-cinema-credit-box">
            <div class="key"><?php esc_html_e( 'Director', 'noorjahan-movie-theater' ); ?></div>
            <div class="val"><?php echo $director; ?></div>
          </div>
          <div class="ifs-cinema-credit-box">
            <div class="key"><?php esc_html_e( 'Writers', 'noorjahan-movie-theater' ); ?></div>
            <div class="val"><?php echo $writers; ?></div>
          </div>
          <div class="ifs-cinema-credit-box">
            <div class="key"><?php esc_html_e( 'Screening Dates', 'noorjahan-movie-theater' ); ?></div>
            <div class="val"><?php echo implode(', ', $release_dates_array); ?></div>
          </div>
        </div>

        <div style="display:flex; gap:1.25rem; align-items:center;">
          <button type="button" class="ifs-cinema-btn ifs-cinema-btn-amber" onclick="document.getElementById('seatSelectorSection').scrollIntoView({behavior:'smooth'})">
            <?php echo sprintf( esc_html__( 'Choose Seats Below (From %s %s)', 'noorjahan-movie-theater' ), esc_html( $currency_symbol ), number_format( $standard_price, 0 ) ); ?>
            <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor">
              <path d="M13 5v11.86l5.43-5.43 1.42 1.42L12 21.14l-7.85-8.29 1.42-1.42L11 16.86V5h2z"/>
            </svg>
          </button>
          <?php if ( ! empty( $trailer_url ) ) : ?>
            <a href="<?php echo $trailer_url; ?>" target="_blank" class="ifs-cinema-btn ifs-cinema-btn-secondary" style="text-decoration:none;">
              <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor">
                <path d="M8 5v14l11-7z"/>
              </svg>
              <?php esc_html_e( 'Watch 4K Trailer', 'noorjahan-movie-theater' ); ?>
            </a>
          <?php endif; ?>
        </div>
      </div>

    </div>
  </section>

  <!-- 02. EXACT CINEMA HALL SEAT MAP -->
  <section class="ifs-cinema-container ifs-cinema-inline-seat-section" id="seatSelectorSection" style="padding-top: 3.5rem;">
    
    <div style="margin-bottom: 2rem; text-align: center;">
      <span class="ifs-cinema-badge ifs-cinema-badge-amber"><?php echo strtoupper( esc_html( $auditorium ) ); ?></span>
      <h2 style="font-size: 2.2rem; font-weight: 900; margin-top: 0.5rem; color:#fff;"><?php esc_html_e( 'Cinema Hall Seat Map', 'noorjahan-movie-theater' ); ?></h2>
    </div>

    <div class="ifs-cinema-stage-card">
      
      <!-- Seating Canvas Area -->
      <div class="ifs-cinema-seating-arena">
        
        <div class="ifs-cinema-map-layout-wrapper">
          <!-- Left VIP Boundary Bar -->
          <div class="ifs-cinema-boundary-line left"></div>

          <!-- 4 Vertical Column Seating Matrix -->
          <div class="ifs-cinema-columns-matrix" id="seatingColumnsGrid" role="grid" aria-label="<?php esc_attr_e( 'Vertical Columns Seat Map', 'noorjahan-movie-theater' ); ?>">
            
            <!-- Column 1: Row A (VIP Recliner Column) -->
            <div class="ifs-cinema-seat-column vip-col">
              <div class="ifs-cinema-column-seats">
                <?php foreach ( array('A1', 'A2', 'A3', 'A4', 'A5') as $seat_id ) : 
                  $is_taken = in_array( $seat_id, $occupied_seats );
                ?>
                  <button type="button" 
                          class="ifs-cinema-seat-box vip <?php echo $is_taken ? 'occupied' : ''; ?>" 
                          id="seat-<?php echo esc_attr( $seat_id ); ?>" 
                          <?php echo $is_taken ? 'disabled' : ''; ?>
                          onclick="toggleSeat('<?php echo esc_attr( $seat_id ); ?>', this, 'vip')" 
                          aria-label="Seat <?php echo esc_attr( $seat_id ); ?> VIP <?php echo $is_taken ? 'Occupied' : ''; ?>">
                    <span class="ifs-seat-id"><?php echo esc_html( $seat_id ); ?></span>
                  </button>
                <?php endforeach; ?>
              </div>
              <div class="ifs-cinema-vip-col-label"><?php esc_html_e( 'VIP SEATING', 'noorjahan-movie-theater' ); ?></div>
            </div>

            <!-- Col Label A -->
            <div class="ifs-cinema-col-divider-label">
              <span>Row A</span>
              <span>Row A</span>
            </div>

            <!-- Column 2: Row B (Standard Column) -->
            <div class="ifs-cinema-seat-column standard-col">
              <div class="ifs-cinema-column-seats">
                <?php foreach ( array('B1', 'B2', 'B3', 'B4', 'B5', 'B6') as $seat_id ) : 
                  $is_taken = in_array( $seat_id, $occupied_seats );
                ?>
                  <button type="button" 
                          class="ifs-cinema-seat-box standard <?php echo $is_taken ? 'occupied' : ''; ?>" 
                          id="seat-<?php echo esc_attr( $seat_id ); ?>" 
                          <?php echo $is_taken ? 'disabled' : ''; ?>
                          onclick="toggleSeat('<?php echo esc_attr( $seat_id ); ?>', this, 'standard')" 
                          aria-label="Seat <?php echo esc_attr( $seat_id ); ?> Standard <?php echo $is_taken ? 'Occupied' : ''; ?>">
                    <span class="ifs-seat-id"><?php echo esc_html( $seat_id ); ?></span>
                  </button>
                <?php endforeach; ?>
              </div>
            </div>

            <!-- Col Label Row B -->
            <div class="ifs-cinema-col-divider-label">
              <span>Row B</span>
              <span>Row B</span>
            </div>

            <!-- Column 3: Row C (Standard Column) -->
            <div class="ifs-cinema-seat-column standard-col">
              <div class="ifs-cinema-column-seats">
                <?php foreach ( array('C1', 'C2', 'C3', 'C4', 'C5', 'C6') as $seat_id ) : 
                  $is_taken = in_array( $seat_id, $occupied_seats );
                ?>
                  <button type="button" 
                          class="ifs-cinema-seat-box standard <?php echo $is_taken ? 'occupied' : ''; ?>" 
                          id="seat-<?php echo esc_attr( $seat_id ); ?>" 
                          <?php echo $is_taken ? 'disabled' : ''; ?>
                          onclick="toggleSeat('<?php echo esc_attr( $seat_id ); ?>', this, 'standard')" 
                          aria-label="Seat <?php echo esc_attr( $seat_id ); ?> Standard <?php echo $is_taken ? 'Occupied' : ''; ?>">
                    <span class="ifs-seat-id"><?php echo esc_html( $seat_id ); ?></span>
                  </button>
                <?php endforeach; ?>
              </div>
            </div>

            <!-- Col Label Row C & Row D -->
            <div class="ifs-cinema-col-divider-label">
              <span>Row C</span>
              <span>Row C</span>
            </div>

            <!-- Column 4: Row D (Standard Column Nearest to Screen) -->
            <div class="ifs-cinema-seat-column standard-col">
              <div class="ifs-cinema-column-seats">
                <?php foreach ( array('D1', 'D2', 'D3', 'D4', 'D5', 'D6') as $seat_id ) : 
                  $is_taken = in_array( $seat_id, $occupied_seats );
                ?>
                  <button type="button" 
                          class="ifs-cinema-seat-box standard <?php echo $is_taken ? 'occupied' : ''; ?>" 
                          id="seat-<?php echo esc_attr( $seat_id ); ?>" 
                          <?php echo $is_taken ? 'disabled' : ''; ?>
                          onclick="toggleSeat('<?php echo esc_attr( $seat_id ); ?>', this, 'standard')" 
                          aria-label="Seat <?php echo esc_attr( $seat_id ); ?> Standard <?php echo $is_taken ? 'Occupied' : ''; ?>">
                    <span class="ifs-seat-id"><?php echo esc_html( $seat_id ); ?></span>
                  </button>
                <?php endforeach; ?>
              </div>
            </div>

          </div>

          <!-- Right Screen Indicator with Arrow -->
          <div class="ifs-cinema-screen-target-indicator">
            <div class="ifs-cinema-boundary-line right"></div>
            <div class="ifs-cinema-screen-pointer">
              <span class="title"><?php esc_html_e( 'SCREEN', 'noorjahan-movie-theater' ); ?></span>
              <svg viewBox="0 0 24 24" width="32" height="32" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M5 12h14M13 5l7 7-7 7"/>
              </svg>
            </div>
          </div>
        </div>

        <!-- Tiered Legend dynamically reflecting pricing tiers -->
        <div class="ifs-cinema-legend">
          <?php foreach ($pricing_tiers as $index => $tier): 
            $bullet_bg = ($index === 0) ? 'var(--ifs-cinema-seat-standard)' : 'var(--ifs-cinema-seat-vip)';
          ?>
            <div class="ifs-cinema-legend-item">
              <div class="ifs-cinema-legend-bullet" style="background: <?php echo $bullet_bg; ?>;"></div>
              <span><?php echo esc_html($tier['title']); ?> (<?php echo esc_html( $currency_symbol ); ?> <?php echo number_format( floatval($tier['amount']), 0 ); ?>)</span>
            </div>
          <?php endforeach; ?>
          <div class="ifs-cinema-legend-item">
            <div class="ifs-cinema-legend-bullet" style="background: var(--ifs-cinema-seat-selected);"></div>
            <span><?php esc_html_e( 'Selected', 'noorjahan-movie-theater' ); ?></span>
          </div>
          <div class="ifs-cinema-legend-item">
            <div class="ifs-cinema-legend-bullet" style="background: var(--ifs-cinema-seat-occupied); opacity: 0.35;"></div>
            <span><?php esc_html_e( 'Occupied', 'noorjahan-movie-theater' ); ?></span>
          </div>
        </div>
      </div>

      <!-- Checkout Summary Sidebar -->
      <div class="ifs-cinema-checkout-sidebar">
        <div>
          <div class="ifs-cinema-movie-meta-summary">
            <h3><?php echo esc_html( $title ); ?></h3>
            <span class="ifs-cinema-hall-tag"><?php echo esc_html( $auditorium ); ?> • 23 Seats Total</span>
          </div>

          <!-- Screening Date & Showtime Selectors inside Sidebar -->
          <div style="background: rgba(255, 255, 255, 0.02); border: 1px solid var(--ifs-cinema-border); padding: 0.85rem; border-radius: 8px; margin-bottom: 1.25rem;">
            <div style="margin-bottom: 0.75rem;">
              <label style="display: block; font-size: 0.7rem; font-weight: 800; text-transform: uppercase; color: var(--ifs-cinema-text-muted); margin-bottom: 0.25rem;"><?php esc_html_e( 'Screening Date', 'noorjahan-movie-theater' ); ?></label>
              <select id="screeningDateSelect" style="width: 100%; padding: 0.45rem 0.6rem; background: #070e11; border: 1px solid var(--ifs-cinema-border); border-radius: 6px; color: #fff; font-size: 0.85rem; outline: none;">
                <?php foreach ( $release_dates_array as $date_val ) : ?>
                  <option value="<?php echo esc_attr( $date_val ); ?>"><?php echo esc_html( $date_val ); ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div>
              <label style="display: block; font-size: 0.7rem; font-weight: 800; text-transform: uppercase; color: var(--ifs-cinema-text-muted); margin-bottom: 0.25rem;"><?php esc_html_e( 'Screening Time', 'noorjahan-movie-theater' ); ?></label>
              <select id="screeningTimeSelect" style="width: 100%; padding: 0.45rem 0.6rem; background: #070e11; border: 1px solid var(--ifs-cinema-border); border-radius: 6px; color: #fff; font-size: 0.85rem; outline: none;">
                <?php foreach ( $showtimes_array as $time_slot ) : 
                  $formatted_time = (strpos($time_slot, ':') !== false && strlen($time_slot) <= 5) ? date('h:i A', strtotime($time_slot)) : $time_slot;
                ?>
                  <option value="<?php echo esc_attr( $time_slot ); ?>"><?php echo esc_html( $formatted_time ); ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>

          <span style="font-size: 0.78rem; font-weight: 800; text-transform: uppercase; color: var(--ifs-cinema-text-muted); margin-bottom: 0.5rem; display: block;">
            <?php esc_html_e( 'Selected Seats', 'noorjahan-movie-theater' ); ?> (<span id="seatCount">0</span>)
          </span>

          <div class="ifs-cinema-chips-wrapper" id="chipsContainer">
            <span style="font-size: 0.85rem; color: var(--ifs-cinema-text-muted);"><?php esc_html_e( 'Click seats to select...', 'noorjahan-movie-theater' ); ?></span>
          </div>

          <!-- Structured Bill Breakdown Table -->
          <div class="ifs-cinema-bill-table-wrap" style="margin-bottom: 1.5rem; overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; font-size: 0.88rem; text-align: left;">
              <thead>
                <tr style="border-bottom: 1px solid var(--ifs-cinema-border-light);">
                  <th style="padding: 0.5rem 0; color: var(--ifs-cinema-text-muted); font-size: 0.72rem; text-transform: uppercase;"><?php esc_html_e( 'Description', 'noorjahan-movie-theater' ); ?></th>
                  <th style="padding: 0.5rem 0; color: var(--ifs-cinema-text-muted); font-size: 0.72rem; text-transform: uppercase; text-align: right;"><?php esc_html_e( 'Amount', 'noorjahan-movie-theater' ); ?></th>
                </tr>
              </thead>
              <tbody id="billTableBody">
                <tr style="border-bottom: 1px dashed var(--ifs-cinema-border-subtle);">
                  <td style="padding: 0.6rem 0; color: var(--ifs-cinema-text-secondary);"><?php esc_html_e( 'Tickets Base', 'noorjahan-movie-theater' ); ?></td>
                  <td style="padding: 0.6rem 0; text-align: right; font-family: var(--ifs-cinema-font-mono); color: #fff;" id="subtotalPrice"><?php echo esc_html( $currency_symbol ); ?> 0.00</td>
                </tr>
                <tr style="border-bottom: 1px dashed var(--ifs-cinema-border-subtle);">
                  <td style="padding: 0.6rem 0; color: var(--ifs-cinema-text-secondary);">
                    <?php echo sprintf( esc_html__( 'VAT & Service (%s%%)', 'noorjahan-movie-theater' ), ( $enable_tax === 'yes' ? $service_tax_pct : 0 ) ); ?>
                  </td>
                  <td style="padding: 0.6rem 0; text-align: right; font-family: var(--ifs-cinema-font-mono); color: #fff;" id="taxPrice"><?php echo esc_html( $currency_symbol ); ?> 0.00</td>
                </tr>
                <tr>
                  <td style="padding: 0.8rem 0; font-weight: 900; color: #fff;"><?php esc_html_e( 'Grand Total', 'noorjahan-movie-theater' ); ?></td>
                  <td style="padding: 0.8rem 0; text-align: right; font-weight: 900; font-family: var(--ifs-cinema-font-mono); color: var(--ifs-cinema-brand-amber); font-size: 1.15rem;" id="grandTotalPrice"><?php echo esc_html( $currency_symbol ); ?> 0.00</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <button type="button" class="ifs-cinema-btn ifs-cinema-btn-amber" style="width:100%;" onclick="proceedToCheckout()">
          <?php esc_html_e( 'Proceed to Checkout', 'noorjahan-movie-theater' ); ?>
          <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor">
            <path d="M5 13h11.86l-5.43 5.43 1.42 1.42L21.14 12l-8.29-7.85-1.42 1.42L16.86 11H5v2z"/>
          </svg>
        </button>
      </div>

    </div>
  </section>

  <!-- 03. FULL DETAILS: SYNOPSIS, CAST & TRAILER -->
  <main class="ifs-cinema-container ifs-cinema-details-layout">
    
    <!-- Storyline Card -->
    <div class="ifs-cinema-content-card">
      <h2 class="ifs-cinema-card-heading">
        <svg viewBox="0 0 24 24" width="22" height="22" fill="currentColor">
          <path d="M18 2H6c-1.1 0-2 .9-2 2v16c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zM6 4h5v8l-2.5-1.5L6 12V4z"/>
        </svg>
        <?php esc_html_e( 'Storyline & Synopsis', 'noorjahan-movie-theater' ); ?>
      </h2>
      <p style="line-height:1.85; font-size:1.02rem; margin-bottom:1.5rem; color:var(--ifs-cinema-text-secondary);">
        <?php echo nl2br( $synopsis ); ?>
      </p>
    </div>

    <!-- Lead Cast Card -->
    <div class="ifs-cinema-content-card">
      <h2 class="ifs-cinema-card-heading">
        <svg viewBox="0 0 24 24" width="22" height="22" fill="currentColor">
          <path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/>
        </svg>
        <?php esc_html_e( 'Lead Cast & Production', 'noorjahan-movie-theater' ); ?>
      </h2>
      <div class="ifs-cinema-cast-deck">
        <?php foreach ( $cast_list as $cast ) : 
          $c_photo = ! empty( $cast['photo'] ) ? esc_url( $cast['photo'] ) : 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=200&auto=format&fit=crop&q=70';
          $c_name  = ! empty( $cast['name'] ) ? esc_html( $cast['name'] ) : 'Cast Member';
          $c_role  = ! empty( $cast['role'] ) ? esc_html( $cast['role'] ) : 'Actor';
        ?>
          <div class="ifs-cinema-actor-card">
            <img src="<?php echo $c_photo; ?>" class="ifs-cinema-actor-photo" alt="<?php echo esc_attr( $c_name ); ?>">
            <div class="ifs-cinema-actor-name"><?php echo $c_name; ?></div>
            <div class="ifs-cinema-actor-role"><?php echo $c_role; ?></div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>

    <!-- 4K Trailer Box -->
    <?php if ( ! empty( $trailer_url ) ) : ?>
      <div class="ifs-cinema-content-card" id="trailerBlock">
        <h2 class="ifs-cinema-card-heading">
          <svg viewBox="0 0 24 24" width="22" height="22" fill="currentColor">
            <path d="M18 4l2 4h-3l-2-4h-2l2 4h-3l-2-4H8l2 4H7L5 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V4h-4z"/>
          </svg>
          <?php esc_html_e( 'Official 4K Trailer Preview', 'noorjahan-movie-theater' ); ?>
        </h2>
        <div style="margin-top: 1rem;">
          <video src="<?php echo $trailer_url; ?>" controls style="width: 100%; max-height: 440px; border-radius: 12px; background: #000; box-shadow: 0 10px 30px rgba(0,0,0,0.5);"></video>
        </div>
      </div>
    <?php endif; ?>

  </main>

  <script>
    let selectedSeats = [];

    const PRICES = {
      standard: <?php echo floatval( $standard_price ); ?>,
      vip: <?php echo floatval( $vip_price ); ?>
    };
    const TAX_RATE = <?php echo ( $enable_tax === 'yes' ? floatval( $service_tax_pct ) / 100 : 0 ); ?>;
    const CURRENCY = <?php echo wp_json_encode( esc_html( $currency_symbol ) ); ?>;
    const MOVIE_ID = <?php echo intval( $curr_movie_id ); ?>;
    const MOVIE_TITLE = <?php echo wp_json_encode( $title ); ?>;
    const AUDITORIUM_NAME = <?php echo wp_json_encode( $auditorium ); ?>;
    const CHECKOUT_PAGE_URL = <?php echo wp_json_encode( esc_url_raw( $checkout_url ) ); ?>;
    const IS_LOGGED_IN = <?php echo $is_user_logged ? 'true' : 'false'; ?>;
    const LOGIN_URL = <?php echo wp_json_encode( esc_url( $login_url ) ); ?>;

    function toggleSeat(seatId, el, type) {
      const price = PRICES[type];
      const index = selectedSeats.findIndex(s => s.id === seatId);

      if (index > -1) {
        selectedSeats.splice(index, 1);
        el.classList.remove('selected');
      } else {
        selectedSeats.push({ id: seatId, type: type, price: price });
        el.classList.add('selected');
      }

      updatePricing();
    }

    function updatePricing() {
      const countEl = document.getElementById('seatCount');
      const chipsEl = document.getElementById('chipsContainer');
      const subtotalEl = document.getElementById('subtotalPrice');
      const taxEl = document.getElementById('taxPrice');
      const grandEl = document.getElementById('grandTotalPrice');

      if (countEl) countEl.innerText = selectedSeats.length;

      if (chipsEl) {
        if (selectedSeats.length === 0) {
          chipsEl.innerHTML = `<span style="font-size: 0.85rem; color: var(--ifs-cinema-text-muted);">Click seats to select...</span>`;
        } else {
          chipsEl.innerHTML = selectedSeats.map(s => `<span class="ifs-cinema-seat-chip">${s.id}</span>`).join('');
        }
      }

      const subtotal = selectedSeats.reduce((acc, s) => acc + s.price, 0);
      const tax = subtotal * TAX_RATE;
      const grand = subtotal + tax;

      if (subtotalEl) subtotalEl.innerText = `${CURRENCY} ${subtotal.toFixed(2)}`;
      if (taxEl) taxEl.innerText = `${CURRENCY} ${tax.toFixed(2)}`;
      if (grandEl) grandEl.innerText = `${CURRENCY} ${grand.toFixed(2)}`;
    }

    function proceedToCheckout() {
      if (selectedSeats.length === 0) {
        alert('Please pick at least one seat to proceed to checkout.');
        return;
      }

      // Automatically redirect to Sign-In page if guest is not logged in
      if (!IS_LOGGED_IN) {
        alert('Please log in to your VIP account to complete your ticket reservation.');
        window.location.href = LOGIN_URL;
        return;
      }

      const screeningDate = document.getElementById('screeningDateSelect').value;
      const screeningTime = document.getElementById('screeningTimeSelect').value;

      const subtotal = selectedSeats.reduce((acc, s) => acc + s.price, 0);
      const tax = subtotal * TAX_RATE;
      const grand = subtotal + tax;
      const seatIds = selectedSeats.map(s => s.id).join(',');

      // Store booking parameters in browser session storage for checkout retrieval
      const bookingData = {
        movie_id: MOVIE_ID,
        movie_title: MOVIE_TITLE,
        auditorium: AUDITORIUM_NAME,
        screening_date: screeningDate,
        screening_time: screeningTime,
        seats: selectedSeats.map(s => s.id),
        subtotal: subtotal.toFixed(2),
        tax: tax.toFixed(2),
        grand_total: grand.toFixed(2),
        currency: CURRENCY
      };
      sessionStorage.setItem('bsc_current_booking', JSON.stringify(bookingData));

      // Construct redirection URL with URL query params
      const url = new URL(CHECKOUT_PAGE_URL, window.location.origin);
      url.searchParams.set('movie_id', MOVIE_ID);
      url.searchParams.set('date', screeningDate);
      url.searchParams.set('time', screeningTime);
      url.searchParams.set('seats', seatIds);
      url.searchParams.set('total', grand.toFixed(2));

      window.location.href = url.toString();
    }
  </script>

<?php get_footer(); ?>