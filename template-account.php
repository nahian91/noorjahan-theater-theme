<?php
/**
 * Template Name: Account Dashboard Page
 * Description: Dynamic user account dashboard displaying live active passes with QR tokens, screening schedules, booking history, and profile management.
 *
 * @package Nahian_Movie_Theater
 */

// Auto-gate: Redirect non-logged-in visitors to the custom login page.
if ( ! is_user_logged_in() ) {
	$request_uri = isset( $_SERVER['REQUEST_URI'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '/';
	$http_host   = isset( $_SERVER['HTTP_HOST'] ) ? sanitize_text_field( wp_unslash( $_SERVER['HTTP_HOST'] ) ) : '';
	$current_url = ! empty( $http_host ) ? set_url_scheme( 'http://' . $http_host . $request_uri ) : home_url( $request_uri );
	wp_safe_redirect( add_query_arg( 'redirect_to', rawurlencode( $current_url ), home_url( '/custom-login/' ) ) );
	exit;
}

// Handle Profile & Password Update Submission.
$profile_notice = '';
if ( 'POST' === ( $_SERVER['REQUEST_METHOD'] ?? '' ) && isset( $_POST['ifs_update_profile_nonce'] ) ) {
	if ( wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['ifs_update_profile_nonce'] ) ), 'ifs_save_profile_action' ) ) {
		$current_user_id = get_current_user_id();

		$new_name  = sanitize_text_field( wp_unslash( $_POST['user_name'] ?? '' ) );
		$new_phone = sanitize_text_field( wp_unslash( $_POST['user_phone'] ?? '' ) );
		$pass_cur  = isset( $_POST['current_pass'] ) ? (string) wp_unslash( $_POST['current_pass'] ) : '';
		$pass_new  = isset( $_POST['new_pass'] ) ? (string) wp_unslash( $_POST['new_pass'] ) : '';
		$pass_conf = isset( $_POST['confirm_pass'] ) ? (string) wp_unslash( $_POST['confirm_pass'] ) : '';

		// Update Name & Phone.
		if ( ! empty( $new_name ) ) {
			wp_update_user(
				array(
					'ID'           => $current_user_id,
					'display_name' => $new_name,
					'first_name'   => $new_name,
				)
			);
		}
		if ( ! empty( $new_phone ) ) {
			update_user_meta( $current_user_id, 'phone_number', $new_phone );
			update_user_meta( $current_user_id, 'billing_phone', $new_phone );
		}

		// Handle Password Update if requested.
		if ( ! empty( $pass_new ) || ! empty( $pass_conf ) ) {
			$user_data = get_userdata( $current_user_id );
			if ( $user_data && wp_check_password( $pass_cur, $user_data->user_pass, $current_user_id ) ) {
				if ( $pass_new === $pass_conf && strlen( $pass_new ) >= 6 ) {
					wp_set_password( $pass_new, $current_user_id );
					$profile_notice = 'success_pass';
				} else {
					$profile_notice = 'error_mismatch';
				}
			} else {
				$profile_notice = 'error_wrong_pass';
			}
		} else {
			$profile_notice = 'success_profile';
		}
	}
}

get_header();

global $wpdb;
$table_bookings  = $wpdb->prefix . 'nmbp_bookings';
$table_customers = $wpdb->prefix . 'nmbp_customers';

$current_user = wp_get_current_user();
$user_email   = $current_user->user_email;
$user_name    = ! empty( $current_user->display_name ) ? $current_user->display_name : ( ! empty( $current_user->first_name ) ? $current_user->first_name : __( 'Valued Member', 'nahian-movie-theater' ) );
$user_phone   = get_user_meta( $current_user->ID, 'phone_number', true );

// Fetch Member Record from database.
$customer_record = null;
if ( $wpdb->get_var( $wpdb->prepare( 'SHOW TABLES LIKE %s', $table_customers ) ) === $table_customers ) {
	$customer_record = $wpdb->get_row(
		$wpdb->prepare(
			"SELECT * FROM {$table_customers} WHERE email = %s OR (phone != '' AND phone = %s) LIMIT 1",
			$user_email,
			$user_phone
		)
	);
}

$membership_id = ( $customer_record && ! empty( $customer_record->membership_id ) ) ? $customer_record->membership_id : ( '#NMBP-' . ( 10000 + $current_user->ID ) );
$member_tier   = ( $customer_record && ! empty( $customer_record->tier ) ) ? $customer_record->tier : __( 'VIP CLUB MEMBER', 'nahian-movie-theater' );
$total_visits  = ( $customer_record && isset( $customer_record->total_visits ) ) ? intval( $customer_record->total_visits ) : 1;

// Retrieve all customer bookings.
$all_bookings = array();
if ( $wpdb->get_var( $wpdb->prepare( 'SHOW TABLES LIKE %s', $table_bookings ) ) === $table_bookings ) {
	$all_bookings = $wpdb->get_results(
		$wpdb->prepare(
			"SELECT * FROM {$table_bookings} WHERE customer_email = %s OR (customer_phone != '' AND customer_phone = %s) ORDER BY id DESC",
			$user_email,
			$user_phone
		)
	);
}
if ( ! is_array( $all_bookings ) ) {
	$all_bookings = array();
}

// Separate Active Passes vs Past Screening History based on current timestamp.
$current_timestamp = current_time( 'timestamp' );
$active_passes     = array();
$past_history      = array();

foreach ( $all_bookings as $booking ) {
	$booking_date_str = wp_date( 'Y-m-d', strtotime( $booking->booking_date ) );
	$booking_time_str = ! empty( $booking->showtime ) ? trim( $booking->showtime ) : '23:59';
	$show_timestamp   = strtotime( $booking_date_str . ' ' . $booking_time_str );

	if ( $show_timestamp && $show_timestamp >= $current_timestamp ) {
		$active_passes[] = $booking;
	} else {
		$past_history[] = $booking;
	}
}

// Fallback: If no future show exists, show the newest booking as active.
if ( empty( $active_passes ) && ! empty( $all_bookings ) ) {
	$active_passes[] = $all_bookings[0];
	$past_history    = array_slice( $all_bookings, 1 );
}

$currency_symbol = sanitize_text_field( get_option( 'nmbp_currency_symbol', get_option( 'bsc_currency_symbol', 'BDT' ) ) );
?>

<style id="nmt-account-dashboard-css">
	.ifs-cinema-dashboard-header {
		background: linear-gradient(180deg, #020608 0%, var(--ifs-cinema-bg-dark, #070e11) 100%);
		padding: 4rem 0 2rem;
		color: #ffffff;
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
		color: #ffffff;
	}

	.ifs-cinema-user-title p {
		font-size: 0.85rem;
		color: var(--ifs-cinema-text-secondary, #94a3b8);
		font-family: var(--ifs-cinema-font-mono, monospace);
		margin: 0;
	}

	.nmt-tier-badge-spacing {
		margin-bottom: 0.4rem;
	}

	.ifs-cinema-kpi-deck {
		display: grid;
		grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
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
		color: #ffffff;
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
		color: #ffffff;
	}

	.ifs-cinema-tab-nav-btn.active {
		background: rgba(245, 158, 11, 0.12);
		border-color: rgba(245, 158, 11, 0.3);
		color: var(--ifs-cinema-brand-amber, #f59e0b);
	}

	.nmt-tab-nav-btn--logout {
		color: #ef4444;
	}

	.ifs-cinema-tab-pane {
		display: none;
	}

	.ifs-cinema-tab-pane.active {
		display: block;
	}

	.nmt-pane-header {
		margin-bottom: 2rem;
	}

	.nmt-pane-title {
		font-size: 1.6rem;
		color: #ffffff;
		margin-bottom: 0.35rem;
		font-weight: 900;
	}

	.nmt-pane-desc {
		color: var(--ifs-cinema-text-secondary, #94a3b8);
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

	.nmt-pass-top-row {
		display: flex;
		justify-content: space-between;
		align-items: flex-start;
		margin-bottom: 0.75rem;
	}

	.nmt-pass-status-badge {
		background: rgba(16, 185, 129, 0.15);
		color: #10b981;
		border: 1px solid rgba(16, 185, 129, 0.3);
	}

	.nmt-pass-movie-title {
		font-size: 1.5rem;
		color: #ffffff;
		font-weight: 900;
		margin-bottom: 0.4rem;
	}

	.nmt-pass-screen-meta {
		font-size: 0.9rem;
		color: var(--ifs-cinema-brand-amber, #f59e0b);
		font-weight: 700;
		margin: 0;
	}

	.nmt-pass-specs-grid {
		display: grid;
		grid-template-columns: repeat(3, 1fr);
		gap: 1.25rem;
		margin-top: 1.5rem;
		border-top: 1px dashed rgba(255, 255, 255, 0.1);
		padding-top: 1.25rem;
	}

	.nmt-pass-spec-label {
		font-size: 0.72rem;
		color: var(--ifs-cinema-text-muted, #64748b);
		text-transform: uppercase;
		font-weight: 800;
	}

	.nmt-pass-spec-date {
		font-size: 0.92rem;
		color: #ffffff;
		font-family: var(--ifs-cinema-font-mono, monospace);
	}

	.nmt-pass-spec-seats {
		font-size: 0.95rem;
		color: var(--ifs-cinema-brand-amber, #f59e0b);
		font-family: var(--ifs-cinema-font-mono, monospace);
	}

	.nmt-pass-spec-amount {
		font-size: 0.95rem;
		color: #ffffff;
		font-family: var(--ifs-cinema-font-mono, monospace);
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
		background: #ffffff;
		padding: 6px;
		border-radius: 8px;
		margin-bottom: 0.85rem;
	}

	.nmt-pass-token-text {
		font-family: var(--ifs-cinema-font-mono, monospace);
		font-size: 0.85rem;
		color: var(--ifs-cinema-brand-amber, #f59e0b);
		font-weight: 800;
		display: block;
		margin-bottom: 0.75rem;
	}

	.nmt-btn-print-pass {
		width: 100%;
		padding: 0.5rem;
		font-size: 0.78rem;
	}

	.nmt-empty-passes-box {
		background: var(--ifs-cinema-card-bg, #0b171c);
		padding: 3rem 2rem;
		border-radius: 14px;
		text-align: center;
		color: var(--ifs-cinema-text-secondary, #94a3b8);
		border: 1px solid var(--ifs-cinema-border, #1e293b);
	}

	.nmt-empty-passes-copy {
		font-size: 1.1rem;
		margin-bottom: 1rem;
	}

	.nmt-history-table-card {
		background: var(--ifs-cinema-card-bg, #0b171c);
		border: 1px solid var(--ifs-cinema-border, #1e293b);
		border-radius: 14px;
		overflow-x: auto;
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

	.nmt-history-token-code {
		color: var(--ifs-cinema-brand-amber, #f59e0b);
		font-weight: 800;
	}

	.nmt-history-seats-val {
		font-family: var(--ifs-cinema-font-mono, monospace);
		color: #5eead4;
		font-weight: 800;
	}

	.nmt-history-amount-val {
		font-family: var(--ifs-cinema-font-mono, monospace);
		font-weight: 800;
		color: #ffffff;
	}

	.nmt-history-status-pill {
		font-size: 0.7rem;
	}

	.nmt-history-empty-cell {
		text-align: center;
		padding: 2.5rem;
		color: var(--ifs-cinema-text-muted, #64748b);
	}

	.ifs-cinema-profile-form-card {
		background: var(--ifs-cinema-card-bg, #0b171c);
		border: 1px solid var(--ifs-cinema-border, #1e293b);
		border-radius: 16px;
		padding: 2rem;
	}

	.nmt-profile-heading {
		font-size: 1.6rem;
		color: #ffffff;
		margin-bottom: 1.5rem;
		border-bottom: 1px solid rgba(255, 255, 255, 0.06);
		padding-bottom: 1rem;
		font-weight: 900;
	}

	.nmt-notice-success {
		background: rgba(16, 185, 129, 0.15);
		border: 1px solid rgba(16, 185, 129, 0.4);
		color: #34d399;
		padding: 0.75rem 1rem;
		border-radius: 8px;
		margin-bottom: 1.5rem;
		font-size: 0.88rem;
		font-weight: 600;
	}

	.nmt-notice-error {
		background: rgba(239, 68, 68, 0.15);
		border: 1px solid rgba(239, 68, 68, 0.4);
		color: #f87171;
		padding: 0.75rem 1rem;
		border-radius: 8px;
		margin-bottom: 1.5rem;
		font-size: 0.88rem;
		font-weight: 600;
	}

	.ifs-cinema-form-grid-2 {
		display: grid;
		grid-template-columns: 1fr 1fr;
		gap: 1.25rem;
		margin-bottom: 1.25rem;
	}

	.nmt-form-grid-2--spaced {
		margin-bottom: 1.5rem;
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
		color: #ffffff;
		font-size: 0.9rem;
		outline: none;
		box-sizing: border-box;
	}

	.ifs-cinema-input-field:focus {
		border-color: var(--ifs-cinema-brand-amber, #f59e0b);
	}

	.nmt-input-readonly {
		background: #04080a;
		opacity: 0.7;
	}

	.nmt-input-tier-readonly {
		background: #04080a;
		opacity: 0.7;
		color: var(--ifs-cinema-brand-amber, #f59e0b);
		font-weight: 800;
	}

	.nmt-password-section {
		border-top: 1px solid rgba(255, 255, 255, 0.06);
		padding-top: 1.5rem;
		margin-top: 1.5rem;
	}

	.nmt-password-subheading {
		font-size: 1.1rem;
		color: #ffffff;
		margin-bottom: 1rem;
		font-weight: 800;
	}

	.nmt-current-pass-group {
		margin-bottom: 1.25rem;
	}

	.nmt-btn-save-profile {
		margin-top: 1.5rem;
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
		.ifs-cinema-form-grid-2 {
			grid-template-columns: 1fr;
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
						data-fallback-src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=300&auto=format&fit=crop&q=80"
						class="ifs-cinema-user-avatar">
					<div class="ifs-cinema-user-title">
						<span class="ifs-cinema-badge ifs-cinema-badge-amber nmt-tier-badge-spacing">
							<?php echo esc_html( $member_tier ); ?>
						</span>
						<h1><?php echo esc_html( $user_name ); ?></h1>
						<p>
							<?php
							/* translators: 1: Membership ID, 2: User email */
							printf( esc_html__( 'Member ID: %1$s • Email: %2$s', 'nahian-movie-theater' ), esc_html( $membership_id ), esc_html( $user_email ) );
							?>
						</p>
					</div>
				</div>

				<a href="<?php echo esc_url( home_url( '/#movies' ) ); ?>" class="ifs-cinema-btn ifs-cinema-btn-amber">
					<svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor" aria-hidden="true">
						<path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/>
					</svg>
					<?php esc_html_e( 'Book New Movie', 'nahian-movie-theater' ); ?>
				</a>
			</div>

			<div class="ifs-cinema-kpi-deck">
				<div class="ifs-cinema-kpi-card">
					<div class="ifs-cinema-kpi-icon">
						<svg viewBox="0 0 24 24" width="26" height="26" fill="currentColor" aria-hidden="true">
							<path d="M22 10V6c0-1.1-.9-2-2-2H4c-1.1 0-1.99.9-1.99 2v4c1.1 0 1.99.9 1.99 2s-.89 2-2 2v4c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2v-4c-1.1 0-2-.9-2-2s.9-2 2-2zm-9 7.5h-2v-2h2v2zm0-4.5h-2v-2h2v2zm0-4.5h-2v-2h2v2z"/>
						</svg>
					</div>
					<div>
						<div class="ifs-cinema-kpi-val"><?php echo esc_html( number_format_i18n( count( $active_passes ) ) ); ?></div>
						<div class="ifs-cinema-kpi-lbl"><?php esc_html_e( 'Active Digital Passes', 'nahian-movie-theater' ); ?></div>
					</div>
				</div>

				<div class="ifs-cinema-kpi-card">
					<div class="ifs-cinema-kpi-icon">
						<svg viewBox="0 0 24 24" width="26" height="26" fill="currentColor" aria-hidden="true">
							<path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-5 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/>
						</svg>
					</div>
					<div>
						<div class="ifs-cinema-kpi-val"><?php echo esc_html( number_format_i18n( $total_visits ) ); ?></div>
						<div class="ifs-cinema-kpi-lbl"><?php esc_html_e( 'Total Cinema Visits', 'nahian-movie-theater' ); ?></div>
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
				<button type="button" class="ifs-cinema-tab-nav-btn active" data-dashboard-tab="passes">
					<svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor" aria-hidden="true">
						<path d="M22 10V6c0-1.1-.9-2-2-2H4c-1.1 0-1.99.9-1.99 2v4c1.1 0 1.99.9 1.99 2s-.89 2-2 2v4c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2v-4c-1.1 0-2-.9-2-2s.9-2 2-2zm-9 7.5h-2v-2h2v2zm0-4.5h-2v-2h2v2zm0-4.5h-2v-2h2v2z"/>
					</svg>
					<span><?php esc_html_e( 'Active Passes', 'nahian-movie-theater' ); ?> (<?php echo esc_html( number_format_i18n( count( $active_passes ) ) ); ?>)</span>
				</button>

				<button type="button" class="ifs-cinema-tab-nav-btn" data-dashboard-tab="history">
					<svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor" aria-hidden="true">
						<path d="M13 3a9 9 0 0 0-9 9H1l3.89 3.89.07.14L9 12H6c0-3.87 3.13-7 7-7s7 3.13 7 7-3.13 7-7 7c-1.93 0-3.68-.79-4.94-2.06l-1.42 1.42A8.954 8.954 0 0 0 13 21a9 9 0 0 0 0-18zm-1 5v5l4.28 2.54.72-1.21-3.5-2.08V8H12z"/>
					</svg>
					<span><?php esc_html_e( 'Booking History', 'nahian-movie-theater' ); ?></span>
				</button>

				<button type="button" class="ifs-cinema-tab-nav-btn" data-dashboard-tab="profile">
					<svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor" aria-hidden="true">
						<path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
					</svg>
					<span><?php esc_html_e( 'Profile Settings', 'nahian-movie-theater' ); ?></span>
				</button>

				<a href="<?php echo esc_url( wp_logout_url( home_url() ) ); ?>" class="ifs-cinema-tab-nav-btn nmt-tab-nav-btn--logout">
					<svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor" aria-hidden="true">
						<path d="M10.09 15.59L11.5 17l5-5-5-5-1.41 1.41L12.67 11H3v2h9.67l-2.58 2.59zM19 3H5c-1.11 0-2 .9-2 2v4h2V5h14v14H5v-4H3v4c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2z"/>
					</svg>
					<span><?php esc_html_e( 'Sign Out', 'nahian-movie-theater' ); ?></span>
				</a>
			</aside>

			<!-- Content Panels -->
			<div>

				<!-- PANE 1: ACTIVE PASSES -->
				<div class="ifs-cinema-tab-pane active" id="pane-passes">
					<div class="nmt-pane-header">
						<h2 class="nmt-pane-title"><?php esc_html_e( 'Active Digital Vouchers & Screening Passes', 'nahian-movie-theater' ); ?></h2>
						<p class="nmt-pane-desc"><?php esc_html_e( 'Show these QR passes directly at the cinema turnstile scanners for instant auditorium admission.', 'nahian-movie-theater' ); ?></p>
					</div>

					<?php if ( ! empty( $active_passes ) ) : ?>
						<?php
						foreach ( $active_passes as $booking ) :
							$formatted_time = ( strpos( $booking->showtime, ':' ) !== false && strlen( $booking->showtime ) <= 5 ) ? wp_date( 'h:i A', strtotime( $booking->showtime ) ) : $booking->showtime;
							$booking_date   = date_i18n( 'l, d F Y', strtotime( $booking->booking_date ) );
							$qr_data_url    = add_query_arg(
								array(
									'size' => '180x180',
									'data' => rawurlencode( $booking->booking_token ),
								),
								'https://api.qrserver.com/v1/create-qr-code/'
							);
							?>
							<div class="ifs-cinema-pass-ticket-card">
								<div class="ifs-cinema-pass-left-content">
									<div>
										<div class="nmt-pass-top-row">
											<span class="ifs-cinema-badge ifs-cinema-badge-amber"><?php echo esc_html( $booking->auditorium ); ?></span>
											<span class="ifs-cinema-badge nmt-pass-status-badge"><?php echo esc_html( $booking->payment_status ); ?></span>
										</div>
										<h3 class="nmt-pass-movie-title"><?php echo esc_html( $booking->movie_title ); ?></h3>
										<p class="nmt-pass-screen-meta">
											⏰ <?php echo esc_html( $formatted_time ); ?> • <?php esc_html_e( '70ft Curved 4K Laser Screen', 'nahian-movie-theater' ); ?>
										</p>
									</div>

									<div class="nmt-pass-specs-grid">
										<div>
											<div class="nmt-pass-spec-label"><?php esc_html_e( 'Screening Date', 'nahian-movie-theater' ); ?></div>
											<strong class="nmt-pass-spec-date"><?php echo esc_html( $booking_date ); ?></strong>
										</div>
										<div>
											<div class="nmt-pass-spec-label"><?php esc_html_e( 'Reserved Seats', 'nahian-movie-theater' ); ?></div>
											<strong class="nmt-pass-spec-seats"><?php echo esc_html( $booking->seats ); ?></strong>
										</div>
										<div>
											<div class="nmt-pass-spec-label"><?php esc_html_e( 'Total Amount', 'nahian-movie-theater' ); ?></div>
											<strong class="nmt-pass-spec-amount"><?php echo esc_html( $currency_symbol . ' ' . number_format_i18n( (float) $booking->total_amount, 2 ) ); ?></strong>
										</div>
									</div>
								</div>

								<div class="ifs-cinema-pass-right-qr">
									<img src="<?php echo esc_url( $qr_data_url ); ?>" alt="<?php esc_attr_e( 'Digital Pass QR', 'nahian-movie-theater' ); ?>" class="ifs-cinema-qr-code-img">
									<span class="nmt-pass-token-text">
										<?php echo esc_html( $booking->booking_token ); ?>
									</span>
									<button type="button" class="ifs-cinema-btn ifs-cinema-btn-secondary nmt-btn-print-pass nmt-print-pass-btn">
										<svg viewBox="0 0 24 24" width="14" height="14" fill="currentColor" aria-hidden="true">
											<path d="M19 8H5c-1.66 0-3 1.34-3 3v6h4v4h12v-4h4v-6c0-1.66-1.34-3-3-3zm-3 11H8v-5h8v5zm3-7c-.55 0-1-.45-1-1s.45-1 1-1 1 .45 1 1-.45 1-1 1zm-1-9H6v4h12V3z"/>
										</svg>
										<?php esc_html_e( 'Print Pass', 'nahian-movie-theater' ); ?>
									</button>
								</div>
							</div>
						<?php endforeach; ?>
					<?php else : ?>
						<div class="nmt-empty-passes-box">
							<p class="nmt-empty-passes-copy"><?php esc_html_e( 'No active digital screening passes found right now.', 'nahian-movie-theater' ); ?></p>
							<a href="<?php echo esc_url( home_url( '/#movies' ) ); ?>" class="ifs-cinema-btn ifs-cinema-btn-amber">
								<?php esc_html_e( 'Explore Live Screenings', 'nahian-movie-theater' ); ?>
							</a>
						</div>
					<?php endif; ?>
				</div>

				<!-- PANE 2: BOOKING HISTORY -->
				<div class="ifs-cinema-tab-pane" id="pane-history">
					<div class="nmt-pane-header">
						<h2 class="nmt-pane-title"><?php esc_html_e( 'Screening History & Transaction Archive', 'nahian-movie-theater' ); ?></h2>
						<p class="nmt-pane-desc"><?php esc_html_e( 'Full ledger record of all past movie bookings and turnstile check-ins.', 'nahian-movie-theater' ); ?></p>
					</div>

					<div class="nmt-history-table-card">
						<table class="ifs-cinema-history-table">
							<thead>
								<tr>
									<th><?php esc_html_e( 'TOKEN ID', 'nahian-movie-theater' ); ?></th>
									<th><?php esc_html_e( 'MOVIE TITLE', 'nahian-movie-theater' ); ?></th>
									<th><?php esc_html_e( 'SCREENING DATE & TIME', 'nahian-movie-theater' ); ?></th>
									<th><?php esc_html_e( 'SEATS', 'nahian-movie-theater' ); ?></th>
									<th><?php esc_html_e( 'TOTAL AMOUNT', 'nahian-movie-theater' ); ?></th>
									<th><?php esc_html_e( 'STATUS', 'nahian-movie-theater' ); ?></th>
								</tr>
							</thead>
							<tbody>
								<?php if ( ! empty( $all_bookings ) ) : ?>
									<?php
									foreach ( $all_bookings as $history ) :
										$h_time = ( strpos( $history->showtime, ':' ) !== false && strlen( $history->showtime ) <= 5 ) ? wp_date( 'h:i A', strtotime( $history->showtime ) ) : $history->showtime;
										?>
										<tr>
											<td><code class="nmt-history-token-code"><?php echo esc_html( $history->booking_token ); ?></code></td>
											<td><strong><?php echo esc_html( $history->movie_title ); ?></strong></td>
											<td><?php echo esc_html( date_i18n( 'd M Y', strtotime( $history->booking_date ) ) . ' • ' . $h_time ); ?></td>
											<td><span class="nmt-history-seats-val"><?php echo esc_html( $history->seats ); ?></span></td>
											<td class="nmt-history-amount-val"><?php echo esc_html( $currency_symbol . ' ' . number_format_i18n( (float) $history->total_amount, 2 ) ); ?></td>
											<td><span class="ifs-cinema-badge ifs-cinema-badge-amber nmt-history-status-pill"><?php echo esc_html( $history->payment_status ); ?></span></td>
										</tr>
									<?php endforeach; ?>
								<?php else : ?>
									<tr>
										<td colspan="6" class="nmt-history-empty-cell"><?php esc_html_e( 'No previous screening history found.', 'nahian-movie-theater' ); ?></td>
									</tr>
								<?php endif; ?>
							</tbody>
						</table>
					</div>
				</div>

				<!-- PANE 3: PROFILE SETTINGS -->
				<div class="ifs-cinema-tab-pane" id="pane-profile">
					<div class="ifs-cinema-profile-form-card">
						<h2 class="nmt-profile-heading">
							<?php esc_html_e( 'Personal VIP Profile & Security Settings', 'nahian-movie-theater' ); ?>
						</h2>

						<?php if ( 'success_profile' === $profile_notice ) : ?>
							<div class="nmt-notice-success" role="status">
								<?php esc_html_e( 'Profile details updated successfully.', 'nahian-movie-theater' ); ?>
							</div>
						<?php elseif ( 'success_pass' === $profile_notice ) : ?>
							<div class="nmt-notice-success" role="status">
								<?php esc_html_e( 'Password and profile preferences updated successfully.', 'nahian-movie-theater' ); ?>
							</div>
						<?php elseif ( 'error_mismatch' === $profile_notice ) : ?>
							<div class="nmt-notice-error" role="alert">
								<?php esc_html_e( 'New passwords do not match or are shorter than 6 characters.', 'nahian-movie-theater' ); ?>
							</div>
						<?php elseif ( 'error_wrong_pass' === $profile_notice ) : ?>
							<div class="nmt-notice-error" role="alert">
								<?php esc_html_e( 'Incorrect current password provided.', 'nahian-movie-theater' ); ?>
							</div>
						<?php endif; ?>

						<form method="POST">
							<?php wp_nonce_field( 'ifs_save_profile_action', 'ifs_update_profile_nonce' ); ?>

							<div class="ifs-cinema-form-grid-2">
								<div class="ifs-cinema-input-group">
									<label><?php esc_html_e( 'Full Legal Name', 'nahian-movie-theater' ); ?></label>
									<input type="text" class="ifs-cinema-input-field" name="user_name" value="<?php echo esc_attr( $user_name ); ?>" required>
								</div>
								<div class="ifs-cinema-input-group">
									<label><?php esc_html_e( 'Mobile Phone', 'nahian-movie-theater' ); ?></label>
									<input type="tel" class="ifs-cinema-input-field" name="user_phone" value="<?php echo esc_attr( $user_phone ); ?>" placeholder="+880 1700-000000">
								</div>
							</div>

							<div class="ifs-cinema-form-grid-2 nmt-form-grid-2--spaced">
								<div class="ifs-cinema-input-group">
									<label><?php esc_html_e( 'Email Address', 'nahian-movie-theater' ); ?></label>
									<input type="email" class="ifs-cinema-input-field nmt-input-readonly" value="<?php echo esc_attr( $user_email ); ?>" readonly>
								</div>
								<div class="ifs-cinema-input-group">
									<label><?php esc_html_e( 'Membership Tier', 'nahian-movie-theater' ); ?></label>
									<input type="text" class="ifs-cinema-input-field nmt-input-tier-readonly" value="<?php echo esc_attr( $member_tier ); ?>" readonly>
								</div>
							</div>

							<div class="nmt-password-section">
								<h3 class="nmt-password-subheading"><?php esc_html_e( 'Change Password', 'nahian-movie-theater' ); ?></h3>

								<div class="ifs-cinema-input-group nmt-current-pass-group">
									<label><?php esc_html_e( 'Current Password (Leave blank to keep current)', 'nahian-movie-theater' ); ?></label>
									<input type="password" class="ifs-cinema-input-field" name="current_pass" placeholder="••••••••">
								</div>

								<div class="ifs-cinema-form-grid-2">
									<div class="ifs-cinema-input-group">
										<label><?php esc_html_e( 'New Password', 'nahian-movie-theater' ); ?></label>
										<input type="password" class="ifs-cinema-input-field" name="new_pass" placeholder="<?php esc_attr_e( 'At least 6 characters', 'nahian-movie-theater' ); ?>">
									</div>
									<div class="ifs-cinema-input-group">
										<label><?php esc_html_e( 'Confirm New Password', 'nahian-movie-theater' ); ?></label>
										<input type="password" class="ifs-cinema-input-field" name="confirm_pass" placeholder="<?php esc_attr_e( 'Re-enter new password', 'nahian-movie-theater' ); ?>">
									</div>
								</div>
							</div>

							<button type="submit" class="ifs-cinema-btn ifs-cinema-btn-amber nmt-btn-save-profile">
								<svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor" aria-hidden="true">
									<path d="M17 3H5c-1.11 0-2 .9-2 2v14c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V7l-4-4zm-5 16c-1.66 0-3-1.34-3-3s1.34-3 3-3 3 1.34 3 3-1.34 3-3 3zm3-10H5V5h10v4z"/>
								</svg>
								<?php esc_html_e( 'Save Profile & Password Changes', 'nahian-movie-theater' ); ?>
							</button>
						</form>
					</div>
				</div>

			</div>

		</div>
	</section>

</main>

<script id="nmt-account-dashboard-js">
	(function() {
		document.addEventListener("DOMContentLoaded", function() {
			const tabButtons = document.querySelectorAll(".ifs-cinema-tab-nav-btn[data-dashboard-tab]");
			const tabPanes   = document.querySelectorAll(".ifs-cinema-tab-pane");

			tabButtons.forEach(function(btn) {
				btn.addEventListener("click", function() {
					const tabId = btn.getAttribute("data-dashboard-tab");
					tabPanes.forEach(function(pane) {
						pane.classList.remove("active");
					});
					tabButtons.forEach(function(navBtn) {
						navBtn.classList.remove("active");
					});

					const targetPane = document.getElementById("pane-" + tabId);
					if (targetPane) {
						targetPane.classList.add("active");
					}
					btn.classList.add("active");
				});
			});

			document.querySelectorAll(".nmt-print-pass-btn").forEach(function(printBtn) {
				printBtn.addEventListener("click", function() {
					window.print();
				});
			});

			document.querySelectorAll("img[data-fallback-src]").forEach(function(img) {
				img.addEventListener("error", function() {
					const fallback = img.getAttribute("data-fallback-src");
					if (fallback && img.getAttribute("src") !== fallback) {
						img.setAttribute("src", fallback);
					}
				}, { once: true });
			});
		});
	})();
</script>

<?php get_footer(); ?>