<?php
/**
 * Template Name: Single Movie Details
 * Description: Dynamic Single Movie Details, Hierarchical Schedule (Days & Time Slots) with Live Seat Availability, Interactive Sidebar Dropdowns, Auto-Redirect Login Gate, and Real-Time Reactive Seating Matrix template.
 *
 * @package Nahian_Movie_Theater
 */

get_header();

global $wpdb;
$table_movies   = $wpdb->prefix . 'nmbp_control_movies';
$table_bookings = $wpdb->prefix . 'nmbp_bookings';

// Resolve targeted movie by ID query param or fallback to latest record.
$movie_id = isset( $_GET['movie_id'] ) ? absint( wp_unslash( $_GET['movie_id'] ) ) : ( isset( $_GET['id'] ) ? absint( wp_unslash( $_GET['id'] ) ) : 0 );

if ( $movie_id > 0 ) {
	$movie = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$table_movies} WHERE id = %d", $movie_id ) );
} else {
	$movie = $wpdb->get_row( "SELECT * FROM {$table_movies} ORDER BY id DESC LIMIT 1" );
}

// Fallback values if database contains no records.
$curr_movie_id     = ! empty( $movie->id ) ? absint( $movie->id ) : 1;
$title             = ! empty( $movie->title ) ? $movie->title : 'FOOD COMBO (EVIL DEAD BURN)';
$poster_url        = ! empty( $movie->poster_url ) ? esc_url( $movie->poster_url ) : 'https://images.unsplash.com/photo-1518709268805-4e9042af9f23?w=700&auto=format&fit=crop&q=75';
$trailer_url       = ! empty( $movie->trailer_url ) ? esc_url( $movie->trailer_url ) : '';
$rating            = ! empty( $movie->rating ) ? $movie->rating : '8.6';
$age_rating        = ! empty( $movie->age_rating ) ? $movie->age_rating : 'PG-13';
$duration          = ! empty( $movie->duration ) ? $movie->duration : '01 Hours 50 Minutes';
$genre             = ! empty( $movie->genre ) ? $movie->genre : 'Horror / Supernatural Thriller';
$audio_language    = ! empty( $movie->audio_language ) ? $movie->audio_language : 'English (Dolby Atmos® 128-Ch)';
$showtimes_raw     = ! empty( $movie->showtimes ) ? $movie->showtimes : '';
$badge_tag         = ! empty( $movie->badge_tag ) ? $movie->badge_tag : '4K DUAL LASER';
$director          = ! empty( $movie->director ) ? $movie->director : 'Darren Aron';
$writers           = ! empty( $movie->writers ) ? $movie->writers : 'Sam Raimi, James Wan';
$short_description = ! empty( $movie->short_description ) ? $movie->short_description : 'Experience our signature dine-in package with chef-prepared refreshments and premium seating.';
$synopsis          = ! empty( $movie->synopsis ) ? wp_kses_post( $movie->synopsis ) : 'A secluded mountain sanctuary turns into a fiery gauntlet when an ancient relic is unearthed during a solar alignment.';

// Parse official theatrical release date.
$official_release_date_str = '--';
if ( ! empty( $movie->release_date ) ) {
	$decoded_rd = json_decode( $movie->release_date, true );
	$raw_rd     = ( is_array( $decoded_rd ) && ! empty( $decoded_rd[0] ) ) ? $decoded_rd[0] : $movie->release_date;
	$ts_rd      = strtotime( $raw_rd );
	$official_release_date_str = $ts_rd ? date_i18n( 'd F Y', $ts_rd ) : $raw_rd;
}

// Current local timestamp & date.
$current_timestamp = current_time( 'timestamp' );
$today_str         = wp_date( 'Y-m-d', $current_timestamp );

// Parse hierarchical screening schedule (Days & Time slots).
$day_schedules = array();
if ( ! empty( $showtimes_raw ) ) {
	$decoded_s = json_decode( $showtimes_raw, true );
	if ( is_array( $decoded_s ) && ! empty( $decoded_s ) ) {
		if ( isset( $decoded_s[0]['date'] ) && isset( $decoded_s[0]['times'] ) ) {
			$day_schedules = $decoded_s;
		} elseif ( isset( $decoded_s[0]['date'] ) && isset( $decoded_s[0]['time'] ) ) {
			$grouped = array();
			foreach ( $decoded_s as $item ) {
				$d = $item['date'] ?? $today_str;
				$t = $item['time'] ?? '18:00';
				if ( ! isset( $grouped[ $d ] ) ) {
					$grouped[ $d ] = array(
						'date'  => $d,
						'times' => array(),
					);
				}
				$grouped[ $d ]['times'][] = $t;
			}
			$day_schedules = array_values( $grouped );
		} else {
			$day_schedules = array(
				array(
					'date'  => $today_str,
					'times' => is_array( $decoded_s ) ? $decoded_s : array( '18:00' ),
				),
			);
		}
	}
}

// Fallback to today's schedule if empty.
if ( empty( $day_schedules ) ) {
	$day_schedules = array(
		array(
			'date'  => $today_str,
			'times' => array( '18:00' ),
		),
	);
}

// Pre-calculate per-slot booked seats map & total occupied seat array.
$slot_booked_seats_map = array();
$slot_booked_counts    = array();

if ( $wpdb->get_var( $wpdb->prepare( 'SHOW TABLES LIKE %s', $table_bookings ) ) === $table_bookings ) {
	$all_movie_bookings = $wpdb->get_results(
		$wpdb->prepare(
			"SELECT booking_date, showtime, seats FROM {$table_bookings} WHERE movie_title = %s AND payment_status != 'Cancelled'",
			$title
		)
	);
	if ( ! empty( $all_movie_bookings ) && is_array( $all_movie_bookings ) ) {
		foreach ( $all_movie_bookings as $b_row ) {
			$b_date   = wp_date( 'Y-m-d', strtotime( $b_row->booking_date ) );
			$b_time   = trim( (string) $b_row->showtime );
			$b_seats  = explode( ',', (string) $b_row->seats );
			$slot_key = $b_date . '_' . $b_time;

			if ( ! isset( $slot_booked_seats_map[ $slot_key ] ) ) {
				$slot_booked_seats_map[ $slot_key ] = array();
			}

			foreach ( $b_seats as $st ) {
				$trimmed_st = strtoupper( trim( $st ) );
				if ( ! empty( $trimmed_st ) ) {
					$slot_booked_seats_map[ $slot_key ][] = $trimmed_st;
				}
			}
		}

		// Clean unique seats per slot.
		foreach ( $slot_booked_seats_map as $s_key => $s_list ) {
			$unique_seats                   = array_values( array_unique( $s_list ) );
			$slot_booked_seats_map[ $s_key ] = $unique_seats;
			$slot_booked_counts[ $s_key ]    = count( $unique_seats );
		}
	}
}

// Total capacity is 30 seats (6 rows F-A x 5 seats).
$total_capacity = 30;

// Find default active future slot.
$initial_selected_date = '';
$initial_selected_time = '';

foreach ( $day_schedules as $day_slot ) {
	$d_val = $day_slot['date'];

	if ( $d_val < $today_str ) {
		continue;
	}

	foreach ( (array) $day_slot['times'] as $t_val ) {
		$clean_t   = trim( (string) $t_val );
		$slot_ts   = strtotime( $d_val . ' ' . $clean_t );
		$slot_key  = $d_val . '_' . $clean_t;
		$booked_ct = $slot_booked_counts[ $slot_key ] ?? 0;

		$is_passed = ( $d_val === $today_str && $slot_ts < $current_timestamp );
		$is_full   = ( $booked_ct >= $total_capacity );

		if ( ! $is_passed && ! $is_full ) {
			$initial_selected_date = $d_val;
			$initial_selected_time = $clean_t;
			break 2;
		}
	}
}

if ( empty( $initial_selected_date ) ) {
	$initial_selected_date = $day_schedules[0]['date'] ?? $today_str;
	$initial_selected_time = $day_schedules[0]['times'][0] ?? '18:00';
}

$initial_slot_key       = $initial_selected_date . '_' . $initial_selected_time;
$initial_occupied_seats = $slot_booked_seats_map[ $initial_slot_key ] ?? array();

// Parse cast and crew roster.
$cast_list = array();
if ( ! empty( $movie->cast_crew ) ) {
	$cast_list = json_decode( $movie->cast_crew, true );
}
if ( empty( $cast_list ) || ! is_array( $cast_list ) ) {
	$cast_list = array(
		array(
			'name'  => 'Elena Rostova',
			'role'  => 'Dr. Maya Vance',
			'photo' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=200&auto=format&fit=crop&q=70',
		),
		array(
			'name'  => 'Marcus Cole',
			'role'  => 'Captain Gabriel',
			'photo' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=200&auto=format&fit=crop&q=70',
		),
	);
}

// Retrieve Direct Ticket Prices from Settings Options.
$standard_price  = (float) get_option( 'nmbp_standard_price', get_option( 'bsc_standard_price', 400.00 ) );
$vip_price       = (float) get_option( 'nmbp_vip_price', get_option( 'bsc_vip_price', 500.00 ) );
$service_tax_pct = (float) get_option( 'nmbp_service_charge_pct', get_option( 'bsc_service_charge_pct', 5.00 ) );
$enable_tax      = get_option( 'nmbp_enable_tax', get_option( 'bsc_enable_tax', 'yes' ) );
$currency_symbol = sanitize_text_field( get_option( 'nmbp_currency_symbol', get_option( 'bsc_currency_symbol', '৳' ) ) );
$checkout_url    = home_url( '/checkout/' );
$login_page_url  = home_url( '/account/' );

global $wp;
$current_page_url = home_url( add_query_arg( array(), $wp->request ) );
$query_string     = isset( $_SERVER['QUERY_STRING'] ) ? sanitize_text_field( wp_unslash( $_SERVER['QUERY_STRING'] ) ) : '';
if ( ! empty( $query_string ) ) {
	$current_page_url .= '?' . $query_string;
}
$login_url      = add_query_arg( 'redirect_to', rawurlencode( $current_page_url ), $login_page_url );
$is_user_logged = is_user_logged_in();
?>

<style id="nmt-single-movie-css">
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
		--ifs-cinema-font-mono: "Space Grotesk", monospace, system-ui;
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
		box-shadow: 0 20px 40px rgba(0, 0, 0, 0.6);
		border: 2px solid var(--ifs-cinema-border);
		display: block;
	}

	.nmt-hero-badge-row {
		display: flex;
		gap: 0.65rem;
		margin-bottom: 1.25rem;
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
		color: #ffffff;
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

	.nmt-hud-star-icon {
		display: inline-block;
		vertical-align: -1px;
		margin-right: 2px;
	}

	.nmt-hero-short-desc {
		font-size: 1.15rem;
		line-height: 1.8;
		color: var(--ifs-cinema-text-secondary);
		max-width: 680px;
		margin-bottom: 1.5rem;
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
		color: #ffffff;
		margin-top: 2px;
	}

	.nmt-hero-cta-row {
		display: flex;
		gap: 1.25rem;
		align-items: center;
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
		color: #000000;
	}

	.ifs-cinema-btn-amber:hover {
		background: #d97706;
	}

	.ifs-cinema-btn-secondary {
		background: rgba(255, 255, 255, 0.08);
		color: #ffffff;
		border: 1px solid var(--ifs-cinema-border);
	}

	.ifs-cinema-btn-secondary:hover {
		background: rgba(255, 255, 255, 0.15);
	}

	.nmt-trailer-link-btn {
		text-decoration: none;
	}

	/* SEATING MATRIX ARENA */
	.ifs-cinema-inline-seat-section {
		padding-top: 3.5rem;
	}

	.nmt-seat-section-head {
		margin-bottom: 2rem;
		text-align: center;
	}

	.nmt-seat-section-title {
		font-size: 2.2rem;
		font-weight: 900;
		margin-top: 0.5rem;
		color: #ffffff;
	}

	.ifs-cinema-stage-card {
		background: #060d10;
		border: 1px solid var(--ifs-cinema-border);
		border-radius: 20px;
		padding: 2rem;
		display: grid;
		grid-template-columns: 1fr 340px;
		gap: 2rem;
		box-shadow: 0 20px 40px rgba(0, 0, 0, 0.5);
		margin-bottom: 2.5rem;
	}

	.ifs-cinema-seating-arena {
		display: flex;
		flex-direction: column;
		justify-content: space-between;
		background: #0b171c;
		border-radius: 14px;
		padding: 2rem 1.5rem 1.5rem;
		border: 1px solid rgba(255, 255, 255, 0.05);
	}

	/* EXACT DASHBOARD SEAT CANVAS */
	.bsc-monitor-matrix-canvas {
		display: flex;
		align-items: center;
		justify-content: center;
		gap: 16px;
		padding: 1.5rem 1rem;
		overflow-x: auto;
	}

	.bsc-seat-matrix-col {
		display: flex;
		flex-direction: column;
		align-items: center;
		gap: 10px;
	}

	.bsc-seat-matrix-col .col-label {
		font-size: 0.95rem;
		font-weight: 900;
		color: #94a3b8;
		user-select: none;
	}

	.nmt-col-label--bottom {
		margin-top: 8px;
	}

	/* Dashboard Seat Matrix Square / Box */
	.bsc-matrix-seat {
		position: relative;
		width: 44px;
		height: 44px;
		border-radius: 8px;
		display: flex;
		align-items: center;
		justify-content: center;
		font-size: 0.85rem;
		font-weight: 800;
		font-family: var(--ifs-cinema-font-mono);
		cursor: pointer;
		box-sizing: border-box;
		user-select: none;
		transition: transform 0.15s ease, background 0.15s ease, border-color 0.15s ease, box-shadow 0.15s ease;
	}

	/* Standard Available */
	.bsc-matrix-seat.standard {
		background: #09231f;
		border: 1.5px solid #0d9488;
		color: #99f6e4;
	}

	.bsc-matrix-seat.standard:hover {
		transform: scale(1.08);
		border-color: #2dd4bf;
		box-shadow: 0 0 10px rgba(13, 148, 136, 0.4);
	}

	/* Selected Seat */
	.bsc-matrix-seat.selected {
		background: #0284c7;
		border: 2px solid #38bdf8;
		color: #ffffff;
		box-shadow: 0 0 14px rgba(56, 189, 248, 0.6);
		transform: scale(1.08);
	}

	/* Occupied Seat */
	.bsc-matrix-seat.occupied {
		background: #201317;
		border: 1.5px solid #ef4444;
		color: #fca5a5;
		cursor: not-allowed;
		opacity: 0.65;
		pointer-events: none;
		transform: none;
		box-shadow: none;
	}

	/* Screen Beacon Right */
	.bsc-matrix-screen-beacon {
		display: flex;
		flex-direction: column;
		align-items: center;
		justify-content: center;
		margin-left: 10px;
		padding-left: 12px;
		border-left: 2px dashed rgba(255, 255, 255, 0.15);
		color: #64748b;
		font-weight: 900;
		font-size: 0.82rem;
		letter-spacing: 0.2em;
		user-select: none;
	}

	.bsc-matrix-screen-beacon span {
		margin-bottom: 2px;
	}

	.nmt-screen-arrow-icon {
		margin-top: 8px;
	}

	/* Dashboard Legend Row */
	.bsc-matrix-legend-row {
		display: flex;
		flex-wrap: wrap;
		align-items: center;
		gap: 1.25rem;
		padding-top: 1.25rem;
		border-top: 1px solid rgba(255, 255, 255, 0.08);
		font-size: 0.82rem;
		color: var(--ifs-cinema-text-secondary);
	}

	.bsc-legend-pill {
		display: inline-flex;
		align-items: center;
		gap: 0.5rem;
		font-weight: 700;
	}

	.bsc-legend-pill .dot {
		width: 13px;
		height: 13px;
		border-radius: 4px;
		flex-shrink: 0;
	}

	.nmt-legend-dot--available {
		background: #0d9488;
	}

	.nmt-legend-dot--selected {
		background: #0284c7;
	}

	.nmt-legend-dot--occupied {
		background: #ef4444;
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
		color: #ffffff;
	}

	.nmt-slot-config-box {
		background: rgba(13, 148, 136, 0.08);
		border: 1px solid rgba(13, 148, 136, 0.25);
		padding: 0.85rem;
		border-radius: 10px;
		margin-bottom: 1.25rem;
	}

	.nmt-slot-config-head {
		font-size: 0.72rem;
		font-weight: 800;
		text-transform: uppercase;
		color: var(--ifs-cinema-brand-amber);
		margin-bottom: 0.6rem;
		display: flex;
		align-items: center;
		justify-content: space-between;
	}

	.nmt-slot-config-tag {
		display: inline-flex;
		align-items: center;
		gap: 4px;
	}

	.nmt-slot-date-field {
		margin-bottom: 0.6rem;
	}

	.nmt-slot-select-label {
		display: block;
		font-size: 0.68rem;
		font-weight: 700;
		text-transform: uppercase;
		color: var(--ifs-cinema-text-muted);
		margin-bottom: 2px;
	}

	.nmt-slot-select {
		width: 100%;
		padding: 0.35rem 0.5rem;
		background: #070e11;
		border: 1px solid var(--ifs-cinema-border);
		border-radius: 6px;
		color: #5eead4;
		font-size: 0.82rem;
		font-family: var(--ifs-cinema-font-mono);
		outline: none;
	}

	.nmt-selected-seats-label {
		font-size: 0.78rem;
		font-weight: 800;
		text-transform: uppercase;
		color: var(--ifs-cinema-text-muted);
		margin-bottom: 0.5rem;
		display: block;
	}

	.ifs-cinema-chips-wrapper {
		display: flex;
		flex-wrap: wrap;
		gap: 0.4rem;
		min-height: 42px;
		padding: 0.5rem;
		background: rgba(0, 0, 0, 0.3);
		border: 1px solid var(--ifs-cinema-border);
		border-radius: 6px;
		margin-bottom: 1.25rem;
		align-items: center;
	}

	.nmt-empty-seats-hint {
		font-size: 0.85rem;
		color: var(--ifs-cinema-text-muted);
	}

	.ifs-cinema-seat-chip {
		background: #0284c7;
		color: #ffffff;
		font-size: 0.75rem;
		font-weight: 800;
		padding: 0.25rem 0.55rem;
		border-radius: 4px;
		border: 1px solid #38bdf8;
		font-family: var(--ifs-cinema-font-mono);
	}

	.ifs-cinema-bill-table-wrap {
		margin-bottom: 1.5rem;
		overflow-x: auto;
	}

	.nmt-bill-table {
		width: 100%;
		border-collapse: collapse;
		font-size: 0.88rem;
		text-align: left;
	}

	.nmt-bill-thead-row {
		border-bottom: 1px solid var(--ifs-cinema-border-light);
	}

	.nmt-bill-th {
		padding: 0.5rem 0;
		color: var(--ifs-cinema-text-muted);
		font-size: 0.72rem;
		text-transform: uppercase;
	}

	.nmt-bill-th--right {
		padding: 0.5rem 0;
		color: var(--ifs-cinema-text-muted);
		font-size: 0.72rem;
		text-transform: uppercase;
		text-align: right;
	}

	.nmt-bill-row-dashed {
		border-bottom: 1px dashed var(--ifs-cinema-border-subtle);
	}

	.nmt-bill-td-label {
		padding: 0.6rem 0;
		color: var(--ifs-cinema-text-secondary);
	}

	.nmt-bill-td-amount {
		padding: 0.6rem 0;
		text-align: right;
		font-family: var(--ifs-cinema-font-mono);
		color: #ffffff;
	}

	.nmt-bill-td-total-label {
		padding: 0.8rem 0;
		font-weight: 900;
		color: #ffffff;
	}

	.nmt-bill-td-total-val {
		padding: 0.8rem 0;
		text-align: right;
		font-weight: 900;
		font-family: var(--ifs-cinema-font-mono);
		color: var(--ifs-cinema-brand-amber);
		font-size: 1.15rem;
	}

	.nmt-checkout-cta-btn {
		width: 100%;
	}

	/* Screening Schedule Table Section */
	.ifs-cinema-schedule-section {
		background: #060d10;
		border: 1px solid var(--ifs-cinema-border);
		border-radius: 16px;
		padding: 2rem;
		margin-bottom: 4rem;
		box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);
	}

	.nmt-schedule-head {
		margin-bottom: 1.5rem;
		display: flex;
		justify-content: space-between;
		align-items: flex-end;
	}

	.nmt-schedule-heading {
		font-size: 1.65rem;
		font-weight: 900;
		margin-top: 0.35rem;
		color: #ffffff;
	}

	.nmt-schedule-live-note {
		font-size: 0.8rem;
		color: var(--ifs-cinema-text-muted);
	}

	.ifs-cinema-table-wrap {
		width: 100%;
		overflow-x: auto;
	}

	.ifs-cinema-schedule-table {
		width: 100%;
		border-collapse: collapse;
		text-align: left;
		font-size: 0.9rem;
	}

	.ifs-cinema-schedule-table th {
		background: #0f232b;
		color: var(--ifs-cinema-text-muted);
		font-size: 0.72rem;
		font-weight: 800;
		text-transform: uppercase;
		letter-spacing: 0.05em;
		padding: 0.85rem 1rem;
		border-bottom: 1px solid var(--ifs-cinema-border);
	}

	.ifs-cinema-schedule-table td {
		padding: 1rem;
		border-bottom: 1px solid var(--ifs-cinema-border-light);
		color: var(--ifs-cinema-text-primary);
		vertical-align: middle;
	}

	.ifs-cinema-schedule-table tr:last-child td {
		border-bottom: none;
	}

	.ifs-cinema-schedule-table tr:hover td {
		background: rgba(255, 255, 255, 0.02);
	}

	.nmt-sched-index-cell {
		font-weight: 800;
		color: var(--ifs-cinema-text-muted);
		width: 40px;
	}

	.nmt-sched-date-cell {
		width: 190px;
	}

	.nmt-sched-date-text {
		font-size: 0.95rem;
		color: #ffffff;
		font-family: var(--ifs-cinema-font-mono);
	}

	.nmt-sched-today-badge {
		font-size: 0.65rem;
		margin-left: 6px;
	}

	.nmt-sched-day-cell {
		width: 130px;
	}

	.nmt-sched-day-text {
		font-weight: 800;
		color: var(--ifs-cinema-brand-amber);
		font-size: 0.95rem;
		text-transform: uppercase;
		letter-spacing: 0.04em;
	}

	.nmt-sched-slots-row {
		display: flex;
		flex-wrap: wrap;
		gap: 8px;
	}

	.nmt-slot-btn {
		padding: 6px 14px;
		font-size: 0.8rem;
		font-weight: 800;
		cursor: pointer;
		display: inline-flex;
		align-items: center;
		gap: 6px;
	}

	.nmt-slot-btn--active {
		background: var(--ifs-cinema-brand-amber);
		color: #000000;
		border-color: var(--ifs-cinema-brand-amber);
	}

	.ifs-slot-btn-disabled {
		background: rgba(255, 255, 255, 0.03);
		border: 1px solid rgba(255, 255, 255, 0.05);
		color: #475569;
		cursor: not-allowed;
		opacity: 0.45;
	}

	.nmt-slot-time-inner {
		display: inline-flex;
		align-items: center;
		gap: 4px;
	}

	.nmt-slot-closed-tag {
		color: #64748b;
		font-weight: 900;
	}

	.nmt-slot-soldout-tag {
		color: #ef4444;
		font-weight: 900;
	}

	.nmt-slot-left-badge {
		background: rgba(13, 148, 136, 0.3);
		color: #99f6e4;
		font-size: 0.7rem;
		padding: 1px 6px;
		border-radius: 4px;
		font-family: var(--ifs-cinema-font-mono);
	}

	.nmt-slot-left-badge--active {
		background: #000000;
		color: #ffffff;
	}

	.nmt-slot-arrow-icon {
		color: var(--ifs-cinema-brand-amber);
		font-weight: 900;
	}

	.nmt-slot-arrow-icon--active {
		color: #000000;
		font-weight: 900;
	}

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
		color: #ffffff;
		display: flex;
		align-items: center;
		gap: 0.6rem;
	}

	.nmt-synopsis-text {
		line-height: 1.85;
		font-size: 1.02rem;
		margin-bottom: 1.5rem;
		color: var(--ifs-cinema-text-secondary);
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
		color: #ffffff;
	}

	.ifs-cinema-actor-role {
		font-size: 0.78rem;
		color: var(--ifs-cinema-text-muted);
	}

	.nmt-trailer-wrap {
		margin-top: 1rem;
	}

	.nmt-trailer-video {
		width: 100%;
		max-height: 440px;
		border-radius: 12px;
		background: #000000;
		box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
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
				<img src="<?php echo esc_url( $poster_url ); ?>" alt="<?php echo esc_attr( $title ); ?>">
			</div>

			<!-- Specs & Overview -->
			<div>
				<div class="nmt-hero-badge-row">
					<span class="ifs-cinema-badge ifs-cinema-badge-imax"><?php echo esc_html( $badge_tag ); ?></span>
				</div>

				<h1 class="ifs-cinema-hero-main-title"><?php echo esc_html( $title ); ?></h1>

				<div class="ifs-cinema-specs-hud-bar">
					<div class="ifs-cinema-hud-item highlight">
						<svg viewBox="0 0 24 24" width="13" height="13" fill="currentColor" aria-hidden="true" class="nmt-hud-star-icon">
							<path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
						</svg>
						<?php
						/* translators: %s: Movie rating */
						printf( esc_html__( '%s / 10 Master Rating', 'nahian-movie-theater' ), esc_html( $rating ) );
						?>
					</div>
					<div class="ifs-cinema-hud-item"><?php echo esc_html( $duration ); ?></div>
					<div class="ifs-cinema-hud-item"><?php echo esc_html( $genre ); ?></div>
					<div class="ifs-cinema-hud-item"><?php echo esc_html( $audio_language ); ?></div>
					<div class="ifs-cinema-hud-item"><?php echo esc_html( $age_rating ); ?></div>
				</div>

				<p class="nmt-hero-short-desc">
					<?php echo esc_html( $short_description ); ?>
				</p>

				<!-- Credit Key-Values -->
				<div class="ifs-cinema-movie-credits-grid">
					<div class="ifs-cinema-credit-box">
						<div class="key"><?php esc_html_e( 'Director', 'nahian-movie-theater' ); ?></div>
						<div class="val"><?php echo esc_html( $director ); ?></div>
					</div>
					<div class="ifs-cinema-credit-box">
						<div class="key"><?php esc_html_e( 'Writers', 'nahian-movie-theater' ); ?></div>
						<div class="val"><?php echo esc_html( $writers ); ?></div>
					</div>
					<div class="ifs-cinema-credit-box">
						<div class="key"><?php esc_html_e( 'Release Date', 'nahian-movie-theater' ); ?></div>
						<div class="val"><?php echo esc_html( $official_release_date_str ); ?></div>
					</div>
				</div>

				<div class="nmt-hero-cta-row">
					<button type="button" class="ifs-cinema-btn ifs-cinema-btn-amber" id="scrollToSeatsBtn">
						<?php
						/* translators: 1: Currency symbol, 2: Standard ticket price */
						printf( esc_html__( 'Choose Seats Below (%1$s %2$s)', 'nahian-movie-theater' ), esc_html( $currency_symbol ), esc_html( number_format_i18n( $standard_price, 0 ) ) );
						?>
						<svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor" aria-hidden="true">
							<path d="M13 5v11.86l5.43-5.43 1.42 1.42L12 21.14l-7.85-8.29 1.42-1.42L11 16.86V5h2z"/>
						</svg>
					</button>
					<?php if ( ! empty( $trailer_url ) ) : ?>
						<a href="<?php echo esc_url( $trailer_url ); ?>" target="_blank" rel="noopener noreferrer" class="ifs-cinema-btn ifs-cinema-btn-secondary nmt-trailer-link-btn">
							<svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor" aria-hidden="true">
								<path d="M8 5v14l11-7z"/>
							</svg>
							<?php esc_html_e( 'Watch 4K Trailer', 'nahian-movie-theater' ); ?>
						</a>
					<?php endif; ?>
				</div>
			</div>

		</div>
	</section>

	<!-- 02. EXACT CINEMA HALL SEAT MAP (Dashboard Design: 6 Rows x 5 Seats) -->
	<section class="ifs-cinema-container ifs-cinema-inline-seat-section" id="seatSelectorSection">

		<div class="nmt-seat-section-head">
			<span class="ifs-cinema-badge ifs-cinema-badge-amber"><?php esc_html_e( 'Interactive Hall Seating', 'nahian-movie-theater' ); ?></span>
			<h2 class="nmt-seat-section-title"><?php esc_html_e( 'Cinema Hall Seat Map', 'nahian-movie-theater' ); ?></h2>
		</div>

		<div class="ifs-cinema-stage-card">

			<!-- Seating Canvas Area -->
			<div class="ifs-cinema-seating-arena">

				<div class="bsc-monitor-matrix-canvas" id="publicSeatMatrixBoard">
					<?php
					$matrix_rows = array( 'F', 'E', 'D', 'C', 'B', 'A' );

					foreach ( $matrix_rows as $row_letter ) :
						?>
						<div class="bsc-seat-matrix-col">
							<div class="col-label"><?php echo esc_html( $row_letter ); ?></div>
							<?php
							for ( $seat_num = 1; $seat_num <= 5; $seat_num++ ) :
								$s_id     = $row_letter . $seat_num;
								$is_taken = in_array( $s_id, $initial_occupied_seats, true );
								?>
								<div class="bsc-matrix-seat <?php echo $is_taken ? 'occupied' : 'standard'; ?>"
									id="matrix-seat-<?php echo esc_attr( $s_id ); ?>"
									data-seat="<?php echo esc_attr( $s_id ); ?>"
									title="<?php echo esc_attr( 'Seat ' . $s_id . ( $is_taken ? ' [OCCUPIED]' : ' [AVAILABLE]' ) ); ?>">
									<?php echo esc_html( $s_id ); ?>
								</div>
							<?php endfor; ?>
							<div class="col-label nmt-col-label--bottom"><?php echo esc_html( $row_letter ); ?></div>
						</div>
					<?php endforeach; ?>

					<div class="bsc-matrix-screen-beacon">
						<span>S</span><span>C</span><span>R</span><span>E</span><span>E</span><span>N</span>
						<svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true" class="nmt-screen-arrow-icon">
							<path d="M5 12h14M13 5l7 7-7 7"/>
						</svg>
					</div>
				</div>

				<!-- Dashboard-Matched Legend -->
				<div class="bsc-matrix-legend-row">
					<div class="bsc-legend-pill">
						<div class="dot nmt-legend-dot--available"></div>
						<span><?php esc_html_e( 'Available', 'nahian-movie-theater' ); ?> (<?php echo esc_html( $currency_symbol . ' ' . number_format_i18n( $standard_price, 0 ) ); ?>)</span>
					</div>
					<div class="bsc-legend-pill">
						<div class="dot nmt-legend-dot--selected"></div>
						<span><?php esc_html_e( 'Selected', 'nahian-movie-theater' ); ?></span>
					</div>
					<div class="bsc-legend-pill">
						<div class="dot nmt-legend-dot--occupied"></div>
						<span><?php esc_html_e( 'Booked / Reserved for Slot', 'nahian-movie-theater' ); ?></span>
					</div>
				</div>
			</div>

			<!-- Checkout Summary Sidebar -->
			<div class="ifs-cinema-checkout-sidebar">
				<div>
					<div class="ifs-cinema-movie-meta-summary">
						<h3><?php echo esc_html( $title ); ?></h3>
					</div>

					<!-- Active Slot Selectors (Interactive Dropdowns) -->
					<div class="nmt-slot-config-box">
						<div class="nmt-slot-config-head">
							<span><?php esc_html_e( 'Active Booking Slot', 'nahian-movie-theater' ); ?></span>
							<span class="nmt-slot-config-tag">
								<svg viewBox="0 0 24 24" width="12" height="12" fill="currentColor" aria-hidden="true"><path d="M19.14 12.94c.04-.3.06-.61.06-.94 0-.32-.02-.64-.07-.94l2.03-1.58c.18-.14.23-.41.12-.61l-1.92-3.32c-.12-.22-.37-.29-.59-.22l-2.39.96c-.5-.38-1.03-.7-1.62-.94l-.36-2.54c-.04-.24-.24-.41-.48-.41h-3.84c-.24 0-.43.17-.47.41l-.36 2.54c-.59.24-1.13.56-1.62.94l-2.39-.96c-.22-.08-.47 0-.59.22L2.74 8.87c-.12.21-.08.47.12.61l2.03 1.58c-.05.3-.09.63-.09.94s.02.64.07.94l-2.03 1.58c-.18.14-.23.41-.12.61l1.92 3.32c.12.22.37.29.59.22l2.39-.96c.5.38 1.03.7 1.62.94l.36 2.54c.05.24.24.41.48.41h3.84c.24 0 .44-.17.47-.41l.36-2.54c.59-.24 1.13-.56 1.62-.94l2.39.96c.22.08.47 0 .59-.22l1.92-3.32c.12-.22.07-.47-.12-.61l-2.01-1.58zM12 15.6c-1.98 0-3.6-1.62-3.6-3.6s1.62-3.6 3.6-3.6 3.6 1.62 3.6 3.6-1.62 3.6-3.6 3.6z"/></svg>
								<?php esc_html_e( 'Config', 'nahian-movie-theater' ); ?>
							</span>
						</div>

						<div class="nmt-slot-date-field">
							<label for="selectedScreeningDate" class="nmt-slot-select-label"><?php esc_html_e( 'Date', 'nahian-movie-theater' ); ?></label>
							<select id="selectedScreeningDate" class="nmt-slot-select">
								<?php
								foreach ( $day_schedules as $sched ) :
									$clean_d = trim( (string) ( $sched['date'] ?? '' ) );
									if ( $clean_d < $today_str ) {
										continue;
									}
									$human_d = date_i18n( 'd F Y', strtotime( $clean_d ) );
									$day_d   = date_i18n( 'l', strtotime( $clean_d ) );
									?>
									<option value="<?php echo esc_attr( $clean_d ); ?>" <?php selected( $clean_d, $initial_selected_date ); ?> data-human="<?php echo esc_attr( $human_d ); ?>">
										<?php echo esc_html( $human_d . ' (' . $day_d . ')' ); ?>
									</option>
								<?php endforeach; ?>
							</select>
						</div>

						<div>
							<label for="selectedScreeningTime" class="nmt-slot-select-label"><?php esc_html_e( 'Time', 'nahian-movie-theater' ); ?></label>
							<select id="selectedScreeningTime" class="nmt-slot-select">
							</select>
						</div>
					</div>

					<span class="nmt-selected-seats-label">
						<?php esc_html_e( 'Selected Seats', 'nahian-movie-theater' ); ?> (<span id="seatCount">0</span>)
					</span>

					<div class="ifs-cinema-chips-wrapper" id="chipsContainer">
						<span class="nmt-empty-seats-hint"><?php esc_html_e( 'Click seats to select...', 'nahian-movie-theater' ); ?></span>
					</div>

					<!-- Structured Bill Breakdown Table -->
					<div class="ifs-cinema-bill-table-wrap">
						<table class="nmt-bill-table">
							<thead>
								<tr class="nmt-bill-thead-row">
									<th class="nmt-bill-th"><?php esc_html_e( 'Description', 'nahian-movie-theater' ); ?></th>
									<th class="nmt-bill-th--right"><?php esc_html_e( 'Amount', 'nahian-movie-theater' ); ?></th>
								</tr>
							</thead>
							<tbody id="billTableBody">
								<tr class="nmt-bill-row-dashed">
									<td class="nmt-bill-td-label"><?php esc_html_e( 'Tickets Base', 'nahian-movie-theater' ); ?></td>
									<td class="nmt-bill-td-amount" id="subtotalPrice"><?php echo esc_html( $currency_symbol ); ?> 0.00</td>
								</tr>
								<tr class="nmt-bill-row-dashed">
									<td class="nmt-bill-td-label">
										<?php
										$tax_pct_display = ( 'yes' === $enable_tax ) ? $service_tax_pct : 0;
										/* translators: %s: Service tax percentage */
										printf( esc_html__( 'VAT & Service (%s%%)', 'nahian-movie-theater' ), esc_html( (string) $tax_pct_display ) );
										?>
									</td>
									<td class="nmt-bill-td-amount" id="taxPrice"><?php echo esc_html( $currency_symbol ); ?> 0.00</td>
								</tr>
								<tr>
									<td class="nmt-bill-td-total-label"><?php esc_html_e( 'Grand Total', 'nahian-movie-theater' ); ?></td>
									<td class="nmt-bill-td-total-val" id="grandTotalPrice"><?php echo esc_html( $currency_symbol ); ?> 0.00</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>

				<button type="button" class="ifs-cinema-btn ifs-cinema-btn-amber nmt-checkout-cta-btn" id="proceedCheckoutBtn">
					<?php esc_html_e( 'Proceed to Checkout', 'nahian-movie-theater' ); ?>
					<svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor" aria-hidden="true">
						<path d="M5 13h11.86l-5.43 5.43 1.42 1.42L21.14 12l-8.29-7.85-1.42 1.42L16.86 11H5v2z"/>
					</svg>
				</button>
			</div>

		</div>
	</section>

	<!-- 03. SCREENING SCHEDULE TABLE SECTION (30 Seats Limit) -->
	<section class="ifs-cinema-container ifs-cinema-schedule-section">
		<div class="nmt-schedule-head">
			<div>
				<span class="ifs-cinema-badge ifs-cinema-badge-amber"><?php esc_html_e( 'TIMETABLE', 'nahian-movie-theater' ); ?></span>
				<h3 class="nmt-schedule-heading"><?php esc_html_e( 'Screening Dates & Showtimes', 'nahian-movie-theater' ); ?></h3>
			</div>
			<span class="nmt-schedule-live-note"><?php esc_html_e( 'Live seat availability updated in real-time', 'nahian-movie-theater' ); ?></span>
		</div>

		<div class="ifs-cinema-table-wrap">
			<table class="ifs-cinema-schedule-table">
				<thead>
					<tr>
						<th>#</th>
						<th><?php esc_html_e( 'Screening Date', 'nahian-movie-theater' ); ?></th>
						<th><?php esc_html_e( 'Day', 'nahian-movie-theater' ); ?></th>
						<th><?php esc_html_e( 'Available Time Slots', 'nahian-movie-theater' ); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php
					$row_index = 1;
					foreach ( $day_schedules as $day_slot ) :
						$clean_date = trim( (string) ( $day_slot['date'] ?? '' ) );

						if ( $clean_date < $today_str ) {
							continue;
						}

						$human_date = date_i18n( 'd F Y', strtotime( $clean_date ) );
						$day_name   = date_i18n( 'l', strtotime( $clean_date ) );
						$is_today   = ( $clean_date === $today_str );
						$times_arr  = (array) ( $day_slot['times'] ?? array( '18:00' ) );
						?>
						<tr>
							<td class="nmt-sched-index-cell"><?php echo esc_html( (string) $row_index++ ); ?></td>
							<td class="nmt-sched-date-cell">
								<strong class="nmt-sched-date-text"><?php echo esc_html( $human_date ); ?></strong>
								<?php if ( $is_today ) : ?>
									<span class="ifs-cinema-badge ifs-cinema-badge-amber nmt-sched-today-badge"><?php esc_html_e( 'TODAY', 'nahian-movie-theater' ); ?></span>
								<?php endif; ?>
							</td>
							<td class="nmt-sched-day-cell">
								<span class="nmt-sched-day-text">
									<?php echo esc_html( $day_name ); ?>
								</span>
							</td>
							<td>
								<div class="nmt-sched-slots-row">
									<?php
									foreach ( $times_arr as $time_item ) :
										$clean_time  = trim( (string) $time_item );
										$formatted_t = ( strpos( $clean_time, ':' ) !== false && strlen( $clean_time ) <= 5 ) ? wp_date( 'h:i A', strtotime( $clean_time ) ) : $clean_time;

										$slot_key        = $clean_date . '_' . $clean_time;
										$booked_count    = $slot_booked_counts[ $slot_key ] ?? 0;
										$available_seats = max( 0, $total_capacity - $booked_count );

										$slot_timestamp = strtotime( $clean_date . ' ' . $clean_time );
										$is_passed      = ( $is_today && $slot_timestamp < $current_timestamp );
										$is_sold_out    = ( $available_seats <= 0 );
										$is_disabled    = ( $is_passed || $is_sold_out );

										$is_next_active = ( $clean_date === $initial_selected_date && $clean_time === $initial_selected_time );
										$btn_classes    = $is_disabled
											? 'button button-small ifs-slot-btn-disabled nmt-slot-btn'
											: ( $is_next_active ? 'button button-small button-primary nmt-slot-btn nmt-slot-btn--active' : 'button button-small ifs-cinema-btn-secondary nmt-slot-btn' );
										?>
										<button type="button"
											class="<?php echo esc_attr( $btn_classes ); ?>"
											data-slot-date="<?php echo esc_attr( $clean_date ); ?>"
											data-slot-time="<?php echo esc_attr( $clean_time ); ?>"
											<?php echo $is_disabled ? 'disabled aria-disabled="true"' : ''; ?>>
											<span class="nmt-slot-time-inner">
												<svg viewBox="0 0 24 24" width="13" height="13" fill="currentColor" aria-hidden="true" class="nmt-hud-star-icon"><path d="M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8zm.5-13H11v6l5.25 3.15.75-1.23-4.5-2.67z"/></svg>
												<?php echo esc_html( $formatted_t ); ?>
											</span>

											<?php if ( $is_passed ) : ?>
												<span class="nmt-slot-closed-tag">• <?php esc_html_e( 'Closed', 'nahian-movie-theater' ); ?></span>
											<?php elseif ( $is_sold_out ) : ?>
												<span class="nmt-slot-soldout-tag">• <?php esc_html_e( 'Sold Out', 'nahian-movie-theater' ); ?></span>
											<?php else : ?>
												<span class="nmt-slot-left-badge <?php echo $is_next_active ? 'nmt-slot-left-badge--active' : ''; ?>">
													<?php
													/* translators: %s: Available seat count */
													printf( esc_html__( '%s Left', 'nahian-movie-theater' ), esc_html( number_format_i18n( $available_seats ) ) );
													?>
												</span>
												<span class="<?php echo $is_next_active ? 'nmt-slot-arrow-icon--active' : 'nmt-slot-arrow-icon'; ?>">
													<?php echo $is_next_active ? '★' : '→'; ?>
												</span>
											<?php endif; ?>
										</button>
									<?php endforeach; ?>
								</div>
							</td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	</section>

	<!-- 04. FULL DETAILS: SYNOPSIS, CAST & TRAILER -->
	<main class="ifs-cinema-container ifs-cinema-details-layout">

		<!-- Storyline Card -->
		<div class="ifs-cinema-content-card">
			<h2 class="ifs-cinema-card-heading">
				<svg viewBox="0 0 24 24" width="22" height="22" fill="currentColor" aria-hidden="true">
					<path d="M18 2H6c-1.1 0-2 .9-2 2v16c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zM6 4h5v8l-2.5-1.5L6 12V4z"/>
				</svg>
				<?php esc_html_e( 'Storyline & Synopsis', 'nahian-movie-theater' ); ?>
			</h2>
			<p class="nmt-synopsis-text">
				<?php echo wp_kses_post( nl2br( $synopsis ) ); ?>
			</p>
		</div>

		<!-- Lead Cast Card -->
		<div class="ifs-cinema-content-card">
			<h2 class="ifs-cinema-card-heading">
				<svg viewBox="0 0 24 24" width="22" height="22" fill="currentColor" aria-hidden="true">
					<path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/>
				</svg>
				<?php esc_html_e( 'Lead Cast & Production', 'nahian-movie-theater' ); ?>
			</h2>
			<div class="ifs-cinema-cast-deck">
				<?php
				foreach ( $cast_list as $cast ) :
					$c_photo = ! empty( $cast['photo'] ) ? esc_url( $cast['photo'] ) : 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=200&auto=format&fit=crop&q=70';
					$c_name  = ! empty( $cast['name'] ) ? $cast['name'] : __( 'Cast Member', 'nahian-movie-theater' );
					$c_role  = ! empty( $cast['role'] ) ? $cast['role'] : __( 'Actor', 'nahian-movie-theater' );
					?>
					<div class="ifs-cinema-actor-card">
						<img src="<?php echo esc_url( $c_photo ); ?>" class="ifs-cinema-actor-photo" alt="<?php echo esc_attr( $c_name ); ?>">
						<div class="ifs-cinema-actor-name"><?php echo esc_html( $c_name ); ?></div>
						<div class="ifs-cinema-actor-role"><?php echo esc_html( $c_role ); ?></div>
					</div>
				<?php endforeach; ?>
			</div>
		</div>

		<!-- 4K Trailer Box -->
		<?php if ( ! empty( $trailer_url ) ) : ?>
			<div class="ifs-cinema-content-card" id="trailerBlock">
				<h2 class="ifs-cinema-card-heading">
					<svg viewBox="0 0 24 24" width="22" height="22" fill="currentColor" aria-hidden="true">
						<path d="M18 4l2 4h-3l-2-4h-2l2 4h-3l-2-4H8l2 4H7L5 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V4h-4z"/>
					</svg>
					<?php esc_html_e( 'Official 4K Trailer Preview', 'nahian-movie-theater' ); ?>
				</h2>
				<div class="nmt-trailer-wrap">
					<video src="<?php echo esc_url( $trailer_url ); ?>" controls class="nmt-trailer-video"></video>
				</div>
			</div>
		<?php endif; ?>

	</main>

	<script id="nmt-single-movie-js">
		(function() {
			let selectedSeats = [];

			const TOTAL_CAPACITY        = <?php echo (int) $total_capacity; ?>;
			const PRICES                = { standard: <?php echo (float) $standard_price; ?> };
			const TAX_RATE              = <?php echo ( 'yes' === $enable_tax ? (float) $service_tax_pct / 100 : 0 ); ?>;
			const CURRENCY              = <?php echo wp_json_encode( $currency_symbol ); ?>;
			const MOVIE_ID              = <?php echo (int) $curr_movie_id; ?>;
			const MOVIE_TITLE           = <?php echo wp_json_encode( $title ); ?>;
			const CHECKOUT_PAGE_URL     = <?php echo wp_json_encode( esc_url_raw( $checkout_url ) ); ?>;
			const IS_LOGGED_IN          = <?php echo $is_user_logged ? 'true' : 'false'; ?>;
			const LOGIN_URL             = <?php echo wp_json_encode( esc_url_raw( $login_url ) ); ?>;
			const TODAY_STR             = <?php echo wp_json_encode( $today_str ); ?>;
			const DAY_SCHEDULES         = <?php echo wp_json_encode( $day_schedules ); ?>;
			const SLOT_BOOKED_SEATS_MAP = <?php echo wp_json_encode( $slot_booked_seats_map ); ?>;
			const SLOT_BOOKINGS         = <?php echo wp_json_encode( $slot_booked_counts ); ?>;
			const INITIAL_TIME          = <?php echo wp_json_encode( $initial_selected_time ); ?>;
			const PICK_SEAT_ALERT       = <?php echo wp_json_encode( __( 'Please pick at least one seat to proceed to checkout.', 'nahian-movie-theater' ) ); ?>;
			const CLICK_SEATS_HINT      = <?php echo wp_json_encode( __( 'Click seats to select...', 'nahian-movie-theater' ) ); ?>;

			function formatTime(timeStr) {
				if (!timeStr) return "06:00 PM";
				if (timeStr.includes(":") && timeStr.length <= 5) {
					const parts = timeStr.split(":");
					let h = parseInt(parts[0], 10);
					const m = parts[1];
					const ampm = h >= 12 ? "PM" : "AM";
					h = h % 12 || 12;
					return String(h).padStart(2, "0") + ":" + m + " " + ampm;
				}
				return timeStr;
			}

			function selectScreeningSlot(dateVal, timeVal) {
				const dateSelect = document.getElementById("selectedScreeningDate");
				if (dateSelect) {
					dateSelect.value = dateVal;
				}

				syncSlotDropdowns(false, timeVal);
				const seatSection = document.getElementById("seatSelectorSection");
				if (seatSection) {
					seatSection.scrollIntoView({ behavior: "smooth" });
				}
			}

			function syncSlotDropdowns(autoSelectFirst, forceTime) {
				const dateSelect = document.getElementById("selectedScreeningDate");
				const timeSelect = document.getElementById("selectedScreeningTime");
				if (!dateSelect || !timeSelect) return;

				const chosenDate = dateSelect.value;
				const isToday    = (chosenDate === TODAY_STR);
				const now        = new Date();

				const currentDaySched = DAY_SCHEDULES.find(function(d) { return d.date === chosenDate; });
				const timesArray      = currentDaySched ? currentDaySched.times : ["18:00"];

				const prevSelectedTime = forceTime || timeSelect.value || INITIAL_TIME;
				timeSelect.innerHTML = "";

				timesArray.forEach(function(tVal) {
					const cleanT  = String(tVal).trim();
					const slotKey = chosenDate + "_" + cleanT;
					const booked  = SLOT_BOOKINGS[slotKey] || 0;
					const isFull  = (booked >= TOTAL_CAPACITY);
					let isPassed  = false;

					if (isToday) {
						const parts   = cleanT.split(":");
						const optDate = new Date();
						optDate.setHours(parseInt(parts[0], 10), parseInt(parts[1], 10), 0, 0);
						if (optDate < now) {
							isPassed = true;
						}
					}

					const opt       = document.createElement("option");
					opt.value       = cleanT;
					const formatted = formatTime(cleanT);

					if (isPassed) {
						opt.disabled = true;
						opt.text     = formatted + " (Passed)";
					} else if (isFull) {
						opt.disabled = true;
						opt.text     = formatted + " (Sold Out)";
					} else {
						opt.disabled = false;
						opt.text     = formatted + " (" + (TOTAL_CAPACITY - booked) + " Left)";
					}

					timeSelect.appendChild(opt);
				});

				let targetOption = Array.from(timeSelect.options).find(function(o) { return o.value === prevSelectedTime && !o.disabled; });
				if (!targetOption) {
					targetOption = Array.from(timeSelect.options).find(function(o) { return !o.disabled; }) || timeSelect.options[0];
				}

				if (targetOption) {
					timeSelect.value = targetOption.value;
				}

				refreshSeatMap();
			}

			function refreshSeatMap() {
				const dateSelect = document.getElementById("selectedScreeningDate");
				const timeSelect = document.getElementById("selectedScreeningTime");
				if (!dateSelect || !timeSelect) return;

				const chosenDate = dateSelect.value;
				const chosenTime = timeSelect.value;
				const slotKey    = chosenDate + "_" + chosenTime;

				const occupiedSeats = SLOT_BOOKED_SEATS_MAP[slotKey] || [];
				const allSeatElements = document.querySelectorAll(".bsc-matrix-seat");

				allSeatElements.forEach(function(seatEl) {
					const seatId  = seatEl.getAttribute("data-seat");
					const isTaken = occupiedSeats.includes(seatId);

					if (isTaken) {
						seatEl.className = "bsc-matrix-seat occupied";
						seatEl.title     = "Seat " + seatId + " [OCCUPIED FOR SLOT]";

						const sIdx = selectedSeats.findIndex(function(s) { return s.id === seatId; });
						if (sIdx > -1) {
							selectedSeats.splice(sIdx, 1);
						}
					} else {
						const isSelected = selectedSeats.some(function(s) { return s.id === seatId; });
						if (isSelected) {
							seatEl.className = "bsc-matrix-seat selected";
							seatEl.title     = "Seat " + seatId + " [SELECTED]";
						} else {
							seatEl.className = "bsc-matrix-seat standard";
							seatEl.title     = "Seat " + seatId + " [AVAILABLE]";
						}
					}
				});

				updatePricing();
			}

			function toggleSeat(seatId, seatEl) {
				if (seatEl.classList.contains("occupied")) return;

				const price = PRICES.standard;
				const index = selectedSeats.findIndex(function(s) { return s.id === seatId; });

				if (index > -1) {
					selectedSeats.splice(index, 1);
					seatEl.className = "bsc-matrix-seat standard";
					seatEl.title     = "Seat " + seatId + " [AVAILABLE]";
				} else {
					selectedSeats.push({ id: seatId, price: price });
					seatEl.className = "bsc-matrix-seat selected";
					seatEl.title     = "Seat " + seatId + " [SELECTED]";
				}

				updatePricing();
			}

			function updatePricing() {
				const countEl    = document.getElementById("seatCount");
				const chipsEl    = document.getElementById("chipsContainer");
				const subtotalEl = document.getElementById("subtotalPrice");
				const taxEl      = document.getElementById("taxPrice");
				const grandEl    = document.getElementById("grandTotalPrice");

				if (countEl) countEl.textContent = selectedSeats.length;

				if (chipsEl) {
					if (selectedSeats.length === 0) {
						chipsEl.innerHTML = `<span class="nmt-empty-seats-hint">${CLICK_SEATS_HINT}</span>`;
					} else {
						chipsEl.innerHTML = selectedSeats.map(function(s) {
							return `<span class="ifs-cinema-seat-chip">${s.id}</span>`;
						}).join("");
					}
				}

				const subtotal = selectedSeats.reduce(function(acc, s) { return acc + s.price; }, 0);
				const tax      = subtotal * TAX_RATE;
				const grand    = subtotal + tax;

				if (subtotalEl) subtotalEl.textContent = `${CURRENCY} ${subtotal.toFixed(2)}`;
				if (taxEl) taxEl.textContent           = `${CURRENCY} ${tax.toFixed(2)}`;
				if (grandEl) grandEl.textContent       = `${CURRENCY} ${grand.toFixed(2)}`;
			}

			function proceedToCheckout() {
				if (selectedSeats.length === 0) {
					window.alert(PICK_SEAT_ALERT);
					return;
				}

				const screeningDate = document.getElementById("selectedScreeningDate").value;
				const screeningTime = document.getElementById("selectedScreeningTime").value;

				const subtotal = selectedSeats.reduce(function(acc, s) { return acc + s.price; }, 0);
				const tax      = subtotal * TAX_RATE;
				const grand    = subtotal + tax;
				const seatIds  = selectedSeats.map(function(s) { return s.id; }).join(",");

				const bookingData = {
					movie_id: MOVIE_ID,
					movie_title: MOVIE_TITLE,
					screening_date: screeningDate,
					screening_time: screeningTime,
					seats: selectedSeats.map(function(s) { return s.id; }),
					subtotal: subtotal.toFixed(2),
					tax: tax.toFixed(2),
					grand_total: grand.toFixed(2),
					currency: CURRENCY
				};
				sessionStorage.setItem("nmbp_current_booking", JSON.stringify(bookingData));

				if (!IS_LOGGED_IN) {
					window.location.href = LOGIN_URL;
					return;
				}

				const url = new URL(CHECKOUT_PAGE_URL, window.location.origin);
				url.searchParams.set("movie_id", MOVIE_ID);
				url.searchParams.set("date", screeningDate);
				url.searchParams.set("time", screeningTime);
				url.searchParams.set("seats", seatIds);
				url.searchParams.set("total", grand.toFixed(2));

				window.location.href = url.toString();
			}

			document.addEventListener("DOMContentLoaded", function() {
				const scrollBtn   = document.getElementById("scrollToSeatsBtn");
				const dateSelect  = document.getElementById("selectedScreeningDate");
				const timeSelect  = document.getElementById("selectedScreeningTime");
				const matrixBoard = document.getElementById("publicSeatMatrixBoard");
				const checkoutBtn = document.getElementById("proceedCheckoutBtn");

				if (scrollBtn) {
					scrollBtn.addEventListener("click", function() {
						const seatSection = document.getElementById("seatSelectorSection");
						if (seatSection) {
							seatSection.scrollIntoView({ behavior: "smooth" });
						}
					});
				}

				if (dateSelect) {
					dateSelect.addEventListener("change", function() {
						syncSlotDropdowns(true, "");
					});
				}

				if (timeSelect) {
					timeSelect.addEventListener("change", function() {
						syncSlotDropdowns(false, "");
					});
				}

				if (matrixBoard) {
					matrixBoard.addEventListener("click", function(e) {
						const seatEl = e.target.closest(".bsc-matrix-seat");
						if (!seatEl) return;
						const seatId = seatEl.getAttribute("data-seat");
						toggleSeat(seatId, seatEl);
					});
				}

				document.querySelectorAll(".nmt-slot-btn[data-slot-date]").forEach(function(slotBtn) {
					slotBtn.addEventListener("click", function() {
						if (slotBtn.disabled) return;
						const dVal = slotBtn.getAttribute("data-slot-date") || "";
						const tVal = slotBtn.getAttribute("data-slot-time") || "";
						selectScreeningSlot(dVal, tVal);
					});
				});

				if (checkoutBtn) {
					checkoutBtn.addEventListener("click", proceedToCheckout);
				}

				syncSlotDropdowns(false, INITIAL_TIME);
			});
		})();
	</script>

<?php get_footer(); ?>