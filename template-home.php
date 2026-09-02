<?php
/**
 * Template Name: Front Page / Home
 *
 * @package Noorjahan_Movie_Theater
 */

get_header(); 

// Fetch currency and pricing settings from admin settings
$currency_symbol = get_option( 'bsc_currency_symbol', 'BDT' );
$pricing_tiers   = get_option( 'bsc_pricing_tiers', array(
    array( 'title' => 'Standard Seating', 'amount' => '400' ),
    array( 'title' => 'VIP Motorized Recliner', 'amount' => '500' )
) );
$min_price = 400.00;
if (!empty($pricing_tiers)) {
    $amounts = array_column($pricing_tiers, 'amount');
    $min_price = min(array_map('floatval', $amounts));
}

global $wpdb;
$table_movies   = $wpdb->prefix . 'bsc_control_movies';
$table_bookings = $wpdb->prefix . 'bsc_bookings';

$current_timestamp = current_time( 'timestamp' );
$today_str         = date( 'Y-m-d', $current_timestamp );

// Safely fetch only 'Now Showing' movies if the table exists
$latest_movies = array();
if ($wpdb->get_var("SHOW TABLES LIKE '{$table_movies}'") === $table_movies) {
    $latest_movies = $wpdb->get_results("SELECT * FROM {$table_movies} WHERE category = 'Now Showing' ORDER BY id DESC LIMIT 3");
}

// Safely fetch upcoming movies if the table exists
$upcoming_movies = array();
if ($wpdb->get_var("SHOW TABLES LIKE '{$table_movies}'") === $table_movies) {
    $upcoming_movies = $wpdb->get_results( "SELECT * FROM {$table_movies} WHERE category = 'Upcoming' ORDER BY id DESC LIMIT 5" );
}

// Base URL for the Single Movie Details page
$details_page_url = home_url( '/movie-details/' );
?>

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
            <div class="v">
              <?php 
                $tier_names = array_column($pricing_tiers, 'title');
                echo !empty($tier_names) ? esc_html(implode(' & ', $tier_names)) : 'VIP Recliners & Standard';
              ?>
            </div>
          </div>
        </div>

        <div class="ifs-cinema-hero-cta-stack">
          <a href="#movies" class="ifs-cinema-btn ifs-cinema-btn-amber">
            <?php esc_html_e( 'Book Tickets Now', 'noorjahan-movie-theater' ); ?>
            <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor">
              <path d="M5 13h11.86l-5.43 5.43 1.42 1.42L21.14 12l-8.29-7.85-1.42 1.42L16.86 11H5v2z"/>
            </svg>
          </a>
          <a href="#movies" class="ifs-cinema-btn ifs-cinema-btn-secondary">
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
            <span class="ifs-cinema-badge ifs-cinema-badge-amber">
              <?php echo sprintf( esc_html__( 'FROM %s %s', 'noorjahan-movie-theater' ), esc_html( $currency_symbol ), number_format( $min_price, 0 ) ); ?>
            </span>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- 04. NOW SHOWING (3-BOX DYNAMIC DATABASE GRID) -->
  <section class="ifs-cinema-section-padding" id="movies">
    <div class="ifs-cinema-container">

      <div class="ifs-cinema-section-title">
        <span class="ifs-cinema-badge ifs-cinema-badge-amber"><?php esc_html_e( 'LIVE SCREENINGS', 'noorjahan-movie-theater' ); ?></span>
        <h4><?php esc_html_e( 'Now Showing in Theatres', 'noorjahan-movie-theater' ); ?></h4>
        <p><?php esc_html_e( 'Live auditorium schedules and real-time seat availability.', 'noorjahan-movie-theater' ); ?></p>
      </div>

      <div class="ifs-cinema-movie-grid-3">
        <?php if ( ! empty( $latest_movies ) ) : ?>
          <?php foreach ( $latest_movies as $movie ) : 
            $poster_url       = ! empty( $movie->poster_url ) ? esc_url( $movie->poster_url ) : 'https://images.unsplash.com/photo-1518709268805-4e9042af9f23?w=700&auto=format&fit=crop&q=75';
            $rating           = ! empty( $movie->rating ) ? esc_html( $movie->rating ) : '8.5';
            $badge            = ! empty( $movie->badge_tag ) ? esc_html( $movie->badge_tag ) : '4K LASER';
            $auditorium       = ! empty( $movie->auditorium ) ? esc_html( $movie->auditorium ) : 'Auditorium 1 (23 Seats)';
            $single_movie_url = esc_url( add_query_arg( 'movie_id', $movie->id, $details_page_url ) );

            // Parse official theatrical release date string
            $release_date_str = '';
            if ( ! empty( $movie->release_date ) ) {
                $decoded_rd = json_decode( $movie->release_date, true );
                $raw_rd = ( is_array( $decoded_rd ) && ! empty( $decoded_rd[0] ) ) ? $decoded_rd[0] : $movie->release_date;
                $ts_rd  = strtotime( $raw_rd );
                $release_date_str = $ts_rd ? date_i18n( 'F j, Y', $ts_rd ) : $raw_rd;
            }

            // Parse today's showtime slots from hierarchical schedules
            $today_showtimes = array();
            if ( ! empty( $movie->showtimes ) ) {
              $decoded_sched = json_decode( $movie->showtimes, true );
              if ( is_array( $decoded_sched ) && ! empty( $decoded_sched ) ) {
                if ( isset( $decoded_sched[0]['date'] ) && isset( $decoded_sched[0]['times'] ) ) {
                  // Find today's entry
                  $found_today = false;
                  foreach ( $decoded_sched as $day_slot ) {
                    if ( $day_slot['date'] === $today_str && ! empty( $day_slot['times'] ) ) {
                      $today_showtimes = (array) $day_slot['times'];
                      $found_today = true;
                      break;
                    }
                  }
                  // Fallback to first day's times if today is not configured
                  if ( ! $found_today && ! empty( $decoded_sched[0]['times'] ) ) {
                    $today_showtimes = (array) $decoded_sched[0]['times'];
                  }
                } else {
                  // Legacy simple times array
                  $today_showtimes = $decoded_sched;
                }
              } else {
                $today_showtimes = array_map( 'trim', explode( ',', $movie->showtimes ) );
              }
            }
            if ( empty( $today_showtimes ) ) { $today_showtimes = array( '18:00' ); }

            // Smart Next Showtime Calculation
            $next_showtime_val = $today_showtimes[0]; // Default fallback
            foreach ( $today_showtimes as $time_slot ) {
                $clean_time = trim( $time_slot );
                $show_timestamp = strtotime( $today_str . ' ' . $clean_time );
                if ( $show_timestamp && $show_timestamp >= $current_timestamp ) {
                    $next_showtime_val = $clean_time;
                    break;
                }
            }
            $formatted_next_show = (strpos($next_showtime_val, ':') !== false && strlen($next_showtime_val) <= 5) ? date('h:i A', strtotime($next_showtime_val)) : $next_showtime_val;

            // Calculate remaining seats out of 23 for this movie on the active showtime
            $total_seats_capacity = 23;
            $booked_seats_count   = 0;
            if ($wpdb->get_var("SHOW TABLES LIKE '{$table_bookings}'") === $table_bookings) {
                $movie_bookings = $wpdb->get_col( $wpdb->prepare( 
                    "SELECT seats FROM {$table_bookings} WHERE movie_title = %s AND (DATE(booking_date) = %s OR booking_date LIKE %s) AND showtime = %s", 
                    $movie->title, 
                    $today_str,
                    $today_str . '%',
                    $next_showtime_val
                ) );
                
                // Fallback to total movie seats if none matched exact slot yet
                if ( empty( $movie_bookings ) ) {
                  $movie_bookings = $wpdb->get_col( $wpdb->prepare( "SELECT seats FROM {$table_bookings} WHERE movie_title = %s", $movie->title ) );
                }

                if (!empty($movie_bookings)) {
                  foreach ($movie_bookings as $b_seats) {
                    $seat_arr = explode(',', $b_seats);
                    foreach ($seat_arr as $s_item) {
                      if (trim($s_item) !== '') {
                        $booked_seats_count++;
                      }
                    }
                  }
                }
            }
            $remaining_seats = max(0, $total_seats_capacity - $booked_seats_count);
          ?>
            <div class="ifs-cinema-card" tabindex="0">
              <div class="ifs-cinema-poster-wrap">
                <div class="ifs-cinema-card-tag">
                  <span class="ifs-cinema-badge ifs-cinema-badge-amber"><?php echo $badge; ?></span>
                </div>
                <div class="ifs-cinema-card-rating">
                  <svg viewBox="0 0 24 24" width="13" height="13" fill="currentColor" style="display:inline-block; vertical-align:-1px; margin-right:2px;">
                    <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
                  </svg>
                  <?php echo $rating; ?>
                </div>

                <!-- Poster Image wrapped with Single Movie link -->
                <a href="<?php echo $single_movie_url; ?>" style="display:block; width:100%; height:100%;">
                  <img src="<?php echo $poster_url; ?>" 
                       alt="<?php echo esc_attr( $movie->title ); ?>" 
                       class="ifs-cinema-poster-img"
                       onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1518709268805-4e9042af9f23?w=700&auto=format&fit=crop&q=75';">
                </a>
                
                <div class="ifs-cinema-card-overlay">
                  <div>
                    <h3 style="color:#fff; font-size:1.35rem; font-weight:900; margin-bottom:1rem;">
                      <a href="<?php echo $single_movie_url; ?>" style="color:#fff; text-decoration:none;">
                        <?php echo esc_html( $movie->title ); ?>
                      </a>
                    </h3>

                    <?php if ( ! empty( $release_date_str ) ) : ?>
                      <div class="ifs-cinema-meta-row">
                        <span><?php esc_html_e( 'Release Date:', 'noorjahan-movie-theater' ); ?></span>
                        <strong style="color:#99f6e4; font-family:var(--ifs-cinema-font-mono);"><?php echo esc_html( $release_date_str ); ?></strong>
                      </div>
                    <?php endif; ?>

                    <?php if ( ! empty( $movie->genre ) ) : ?>
                      <div class="ifs-cinema-meta-row">
                        <span><?php esc_html_e( 'Genre:', 'noorjahan-movie-theater' ); ?></span>
                        <strong><?php echo esc_html( $movie->genre ); ?></strong>
                      </div>
                    <?php endif; ?>
                    <?php if ( ! empty( $movie->duration ) ) : ?>
                      <div class="ifs-cinema-meta-row">
                        <span><?php esc_html_e( 'Duration:', 'noorjahan-movie-theater' ); ?></span>
                        <strong><?php echo esc_html( $movie->duration ); ?></strong>
                      </div>
                    <?php endif; ?>
                  </div>

                  <!-- Single Movie details & seat selection action button -->
                  <a href="<?php echo $single_movie_url; ?>" class="ifs-cinema-action-btn" style="text-decoration:none; display:inline-flex; align-items:center; justify-content:center;">
                    <span class="ifs-cinema-circle-icon">
                      <svg viewBox="0 0 24 24" width="15" height="15" fill="currentColor">
                        <path d="M22 10V6c0-1.1-.9-2-2-2H4c-1.1 0-1.99.9-1.99 2v4c1.1 0 1.99.9 1.99 2s-.89 2-2 2v4c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2v-4c-1.1 0-2-.9-2-2s.9-2 2-2zm-9 7.5h-2v-2h2v2zm0-4.5h-2v-2h2v2zm0-4.5h-2v-2h2v2z"/>
                      </svg>
                    </span>
                    <?php esc_html_e( 'View Details & Book', 'noorjahan-movie-theater' ); ?>
                    <svg viewBox="0 0 24 24" width="14" height="14" fill="currentColor" style="margin-left:2px;">
                      <path d="M5 13h11.86l-5.43 5.43 1.42 1.42L21.14 12l-8.29-7.85-1.42 1.42L16.86 11H5v2z"/>
                    </svg>
                  </a>
                </div>
              </div>
              
              <div class="ifs-cinema-card-footer">
                <h3 class="ifs-cinema-card-title">
                  <a href="<?php echo $single_movie_url; ?>" style="color:inherit; text-decoration:none;">
                    <?php echo esc_html( $movie->title ); ?>
                  </a>
                </h3>
                <!-- Next Show Time & Ticket Remaining Display -->
                <div style="display:flex; align-items:center; justify-content:space-between; margin-top:6px; font-size:0.8rem;">
                  <span style="color:var(--ifs-cinema-brand-amber); font-weight:800; font-family:var(--ifs-cinema-font-mono);">⏰ Next: <?php echo esc_html($formatted_next_show); ?></span>
                  <span style="color:<?php echo ($remaining_seats > 5) ? '#10b981' : '#ef4444'; ?>; font-weight:800;"><?php echo intval($remaining_seats); ?> Seats Left</span>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        <?php else : ?>
          <p style="color:var(--ifs-cinema-text-secondary); grid-column: 1 / -1; text-align:center;">
            <?php esc_html_e( 'No movies scheduled right now.', 'noorjahan-movie-theater' ); ?>
          </p>
        <?php endif; ?>
      </div>

    </div>
  </section>

  <!-- 05. UPGRADED UPCOMING MOVIES SCHEDULE -->
  <section class="ifs-cinema-section-padding" id="upcoming" style="background: var(--ifs-cinema-bg-surface); padding: 5rem 0;">
    <div class="ifs-cinema-container">

      <div class="ifs-cinema-section-title" style="margin-bottom: 2.5rem;">
        <span class="ifs-cinema-badge ifs-cinema-badge-amber"><?php esc_html_e( 'COMING SOON', 'big-screen-cinemas' ); ?></span>
        <h4 style="font-size: 2rem; font-weight: 900; color: #fff; margin-top: 0.35rem;"><?php esc_html_e( 'Upcoming Feature Presentations', 'big-screen-cinemas' ); ?></h4>
        <p style="color: var(--ifs-cinema-text-secondary); font-size: 0.95rem;"><?php esc_html_e( 'Get ready for upcoming blockbusters hitting our 4K laser screens soon.', 'big-screen-cinemas' ); ?></p>
      </div>

      <div class="ifs-cinema-schedule-deck-wrap" style="display:flex; flex-direction:column; gap:1.25rem;">
        
        <?php if ( ! empty( $upcoming_movies ) ) : ?>
          <?php foreach ( $upcoming_movies as $movie ) : 
            $poster = ! empty( $movie->poster_url ) ? esc_url( $movie->poster_url ) : 'https://images.unsplash.com/photo-1518709268805-4e9042af9f23?w=200&auto=format&fit=crop&q=75';
            $title  = ! empty( $movie->title ) ? esc_html( $movie->title ) : 'Upcoming Presentation';
            
            // Parse planned showtimes (hierarchical or flat)
            $showtimes = array();
            if ( ! empty( $movie->showtimes ) ) {
              $decoded = json_decode( $movie->showtimes, true );
              if ( is_array( $decoded ) && ! empty( $decoded ) ) {
                if ( isset( $decoded[0]['times'] ) ) {
                  foreach ( $decoded as $ds ) {
                    foreach ( (array) $ds['times'] as $t ) {
                      $showtimes[] = $t;
                    }
                  }
                } else {
                  $showtimes = $decoded;
                }
              } else {
                $showtimes = array_map( 'trim', explode( ',', $movie->showtimes ) );
              }
            }
            if ( empty( $showtimes ) ) { $showtimes = array( '18:00' ); }
            $showtimes = array_unique($showtimes);

            // Parse official theatrical release date
            $first_release_date = '';
            if ( ! empty( $movie->release_date ) ) {
              $decoded_rd = json_decode( $movie->release_date, true );
              $raw_rd = ( is_array( $decoded_rd ) && ! empty( $decoded_rd[0] ) ) ? $decoded_rd[0] : $movie->release_date;
              $ts_rd  = strtotime( $raw_rd );
              $first_release_date = $ts_rd ? date_i18n( 'F j, Y', $ts_rd ) : $raw_rd;
            }

            $movie_link = esc_url( add_query_arg( 'movie_id', $movie->id, $details_page_url ) );
          ?>
            <!-- Streamlined Upcoming Feature Card with Large Thumbnail -->
            <div class="ifs-cinema-upcoming-card" style="display:flex; align-items:center; justify-content:space-between; gap:2rem; background:linear-gradient(135deg, #071115 0%, #0b171c 100%); border:1px solid rgba(13, 148, 136, 0.2); border-radius:14px; padding:1.25rem 2rem; box-shadow: 0 8px 24px rgba(0,0,0,0.2); transition: all 0.25s ease;">
              
              <!-- Left: Larger Poster & Title info -->
              <div style="display:flex; align-items:center; gap:1.75rem; flex:1.5;">
                <div style="position:relative; flex-shrink:0;">
                  <img src="<?php echo $poster; ?>" 
                       alt="<?php echo esc_attr( $title ); ?>"
                       style="width:90px; height:125px; border-radius:10px; object-fit:cover; border:1px solid rgba(255,255,255,0.15); box-shadow: 0 6px 18px rgba(0,0,0,0.4);"
                       onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1518709268805-4e9042af9f23?w=200&auto=format&fit=crop&q=75';">
                </div>

                <div>
                  <h3 style="color:#fff; font-size:1.35rem; font-weight:900; margin:0 0 0.4rem 0; letter-spacing:-0.01em;">
                    <a href="<?php echo $movie_link; ?>" style="color:#fff; text-decoration:none; transition:color 0.2s;" onmouseover="this.style.color='#5eead4'" onmouseout="this.style.color='#fff'"><?php echo $title; ?></a>
                  </h3>
                  
                  <?php if (!empty($first_release_date)): ?>
                    <div style="font-size:0.8rem; color:#99f6e4; font-family:var(--ifs-cinema-font-mono); display:inline-flex; align-items:center; gap:5px;">
                      <span>📅 Release Date:</span> <strong><?php echo esc_html(trim($first_release_date)); ?></strong>
                    </div>
                  <?php endif; ?>
                </div>
              </div>

              <!-- Center: Planned Showtimes -->
              <div style="flex:1; text-align:center; border-left:1px dashed rgba(255,255,255,0.08); border-right:1px dashed rgba(255,255,255,0.08); padding: 0 1rem;">
                <div style="font-size:0.72rem; font-weight:800; color:#94a3b8; text-transform:uppercase; letter-spacing:0.05em; margin-bottom:0.5rem;"><?php esc_html_e( 'Planned Daily Slots', 'big-screen-cinemas' ); ?></div>
                <div style="display:flex; flex-wrap:wrap; justify-content:center; gap:6px;">
                  <?php foreach ( $showtimes as $time ) : 
                    $formatted_time = (strpos($time, ':') !== false && strlen($time) <= 5) ? date('h:i A', strtotime($time)) : $time;
                  ?>
                    <span style="display:inline-block; padding:0.35rem 0.75rem; background:#111827; color:#38bdf8; border-radius:6px; font-size:0.8rem; font-weight:800; font-family:var(--ifs-cinema-font-mono); border:1px solid rgba(56,189,248,0.25);"><?php echo esc_html( $formatted_time ); ?></span>
                  <?php endforeach; ?>
                </div>
              </div>

              <!-- Right: Coming Soon Status Badge -->
              <div style="text-align:right; flex-shrink:0;">
                <span style="display:inline-block; padding:0.5rem 1.15rem; background:rgba(13,148,136,0.18); border:1px solid rgba(13,148,136,0.4); color:#5eead4; border-radius:10px; font-size:0.8rem; font-weight:900; text-transform:uppercase; letter-spacing:0.05em; box-shadow:0 0 15px rgba(13,148,136,0.15);">
                  <?php esc_html_e( 'Coming Soon', 'big-screen-cinemas' ); ?>
                </span>
              </div>

            </div>
          <?php endforeach; ?>
        <?php else : ?>
          <p style="color:var(--ifs-cinema-text-muted); text-align:center; padding:2rem; background:#0b171c; border-radius:12px; border:1px solid var(--ifs-cinema-border);"><?php esc_html_e( 'No upcoming movies scheduled at the moment.', 'big-screen-cinemas' ); ?></p>
        <?php endif; ?>

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
        <div class="ifs-cinema-combo-card" style="display: flex; flex-direction: column; justify-content: space-between;">
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
            <div style="font-size: 1.5rem; font-weight: 900; color: var(--ifs-cinema-brand-amber); font-family: var(--ifs-cinema-font-mono);">
              <?php echo esc_html( $currency_symbol ); ?> 500
            </div>
          </div>
        </div>

        <div class="ifs-cinema-combo-card" style="display: flex; flex-direction: column; justify-content: space-between;">
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
            <div style="font-size: 1.5rem; font-weight: 900; color: var(--ifs-cinema-brand-amber); font-family: var(--ifs-cinema-font-mono);">
              <?php echo esc_html( $currency_symbol ); ?> 800
            </div>
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

      <?php
      // Retrieve dynamic gallery photos from admin settings
      $home_gallery_items = get_option('bsc_gallery_images', array(
        array(
          'url'         => get_theme_file_uri('/assets/img/gallery-1.jpg'),
          'category'    => 'screens',
          'title'       => '70ft Curved 4K Laser Screen'
        ),
        array(
          'url'         => get_theme_file_uri('/assets/img/gallery-2.jpg'),
          'category'    => 'lounges',
          'title'       => 'Motorized Heated Recliners'
        ),
        array(
          'url'         => get_theme_file_uri('/assets/img/gallery-3.jpg'),
          'category'    => 'dining',
          'title'       => 'Fresh Butter Popcorn & Meals'
        ),
        array(
          'url'         => get_theme_file_uri('/assets/img/gallery-4.jpg'),
          'category'    => 'screens',
          'title'       => '128-Channel Spatial Atmos'
        ),
        array(
          'url'         => get_theme_file_uri('/assets/img/gallery-5.jpg'),
          'category'    => 'lounges',
          'title'       => 'Concierge Reception Lobby'
        ),
        array(
          'url'         => get_theme_file_uri('/assets/img/gallery-6.jpg'),
          'category'    => 'dining',
          'title'       => 'Personal Table In-Seat Food Delivery'
        )
      ));

      $category_map = array(
        'screens' => __('Auditorium 1', 'noorjahan-movie-theater'),
        'lounges' => __('VIP Lounges', 'noorjahan-movie-theater'),
        'dining'  => __('Concessions', 'noorjahan-movie-theater'),
      );
      ?>

      <div class="ifs-cinema-gallery-masonry-refined">
        <?php if (!empty($home_gallery_items)): ?>
          <?php foreach (array_slice($home_gallery_items, 0, 6) as $index => $item): 
            $img_url   = !empty($item['url']) ? esc_url($item['url']) : 'https://images.unsplash.com/photo-1517604931442-7e0c8ed2963c?w=900&auto=format&fit=crop&q=80';
            $cat_key   = $item['category'] ?? 'screens';
            $cat_label = $category_map[$cat_key] ?? __('Multiplex', 'noorjahan-movie-theater');
            $title     = !empty($item['title']) ? esc_html($item['title']) : __('Cinema Showcase', 'noorjahan-movie-theater');
            
            // Feature 1st and 6th cards as wide tiles
            $span_class = ($index === 0 || $index === 5) ? ' span-2' : '';
          ?>
            <div class="ifs-cinema-gallery-card-pro<?php echo $span_class; ?>">
              <img src="<?php echo $img_url; ?>" 
                   alt="<?php echo esc_attr($title); ?>"
                   onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1517604931442-7e0c8ed2963c?w=900&auto=format&fit=crop&q=80';">
              <div class="ifs-cinema-gallery-badge-overlay">
                <span class="cat"><?php echo esc_html($cat_label); ?></span>
                <div class="title"><?php echo $title; ?></div>
              </div>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <p style="color:var(--ifs-cinema-text-muted); text-align:center; grid-column:1/-1;">
            <?php esc_html_e('No gallery photos published yet.', 'noorjahan-movie-theater'); ?>
          </p>
        <?php endif; ?>
      </div>
    </div>
  </section>

</main>

<?php get_footer(); ?>