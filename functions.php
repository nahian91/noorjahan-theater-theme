<?php
/**
 * Noorjahan Movie Theater functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Noorjahan_Movie_Theater
 */

if ( ! defined( '_S_VERSION' ) ) {
	// Replace the version number of the theme on each release.
	define( '_S_VERSION', '1.0.0' );
}

/**
 * Sets up theme defaults and registers support for various WordPress features.
 */
function nmt_movie_theater_setup() {
	load_theme_textdomain( 'noorjahan-movie-theater', get_template_directory() . '/languages' );

	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );

	register_nav_menus(
		array(
			'menu-1' => esc_html__( 'Primary', 'noorjahan-movie-theater' ),
		)
	);

	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
		)
	);

	add_theme_support(
		'custom-background',
		apply_filters(
			'nmt_movie_theater_custom_background_args',
			array(
				'default-color' => 'ffffff',
				'default-image' => '',
			)
		)
	);

	add_theme_support( 'customize-selective-refresh-widgets' );

	add_theme_support(
		'custom-logo',
		array(
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		)
	);
}
add_action( 'after_setup_theme', 'nmt_movie_theater_setup' );

function nmt_movie_theater_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'nmt_movie_theater_content_width', 640 );
}
add_action( 'after_setup_theme', 'nmt_movie_theater_content_width', 0 );

function nmt_movie_theater_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Sidebar', 'noorjahan-movie-theater' ),
			'id'            => 'sidebar-1',
			'description'   => esc_html__( 'Add widgets here.', 'noorjahan-movie-theater' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action( 'widgets_init', 'nmt_movie_theater_widgets_init' );

function nmt_movie_theater_scripts() {
	wp_enqueue_style(
		'nmt-google-fonts',
		'https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,400;1,700&family=Space+Grotesk:wght@500;700;800&display=swap',
		array(),
		null
	);

	wp_enqueue_style(
		'nmt-cinema-main-style',
		get_template_directory_uri() . '/assets/css/style.css',
		array( 'nmt-google-fonts' ),
		_S_VERSION
	);

	wp_enqueue_script(
		'nmt-movie-theater-navigation',
		get_template_directory_uri() . '/js/navigation.js',
		array(),
		_S_VERSION,
		true
	);

	wp_enqueue_script(
		'nmt-cinema-main-script',
		get_template_directory_uri() . '/assets/js/main.js',
		array(),
		_S_VERSION,
		true
	);

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'nmt_movie_theater_scripts' );

require get_template_directory() . '/inc/custom-header.php';
require get_template_directory() . '/inc/template-tags.php';
require get_template_directory() . '/inc/template-functions.php';
require get_template_directory() . '/inc/customizer.php';

if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/inc/jetpack.php';
}

// =============================================================================
// FORCE DEFAULT WORDPRESS LOGIN TO CUSTOM PORTAL (ADMIN SAFE)
// =============================================================================
add_action( 'login_init', 'nmt_redirect_default_wp_login_to_custom' );
function nmt_redirect_default_wp_login_to_custom() {
	if ( isset( $_GET['action'] ) && in_array( sanitize_key( wp_unslash( $_GET['action'] ) ), array( 'logout', 'lostpassword', 'rp', 'resetpass' ), true ) ) {
		return;
	}

	if ( isset( $_GET['loggedout'] ) ) {
		return;
	}

	if ( ! is_user_logged_in() ) {
		$custom_login_url = home_url( '/custom-login/' );

		if ( ! empty( $_GET['redirect_to'] ) ) {
			$custom_login_url = add_query_arg( 'redirect_to', rawurlencode( sanitize_text_field( wp_unslash( $_GET['redirect_to'] ) ) ), $custom_login_url );
		}

		wp_safe_redirect( $custom_login_url );
		exit;
	}
}

// =============================================================================
// ROBUST CUSTOM FRONTEND LOGIN HANDLER WITH CUSTOM PLUGIN DASHBOARD REDIRECT
// =============================================================================
if ( ! function_exists( 'nmt_handle_custom_login' ) ) :
	function nmt_handle_custom_login() {
		$log      = isset( $_POST['log'] ) ? sanitize_text_field( wp_unslash( $_POST['log'] ) ) : '';
		$password = isset( $_POST['pwd'] ) ? (string) wp_unslash( $_POST['pwd'] ) : '';

		// Default redirect for regular members.
		$redirect_to = home_url( '/account/' );

		if ( empty( $log ) || empty( $password ) ) {
			wp_safe_redirect( add_query_arg( array( 'auth_error' => 'empty_fields', 'tab' => 'signin' ), home_url( '/custom-login/' ) ) );
			exit;
		}

		$user = get_user_by( 'email', $log );
		if ( ! $user ) {
			$user = get_user_by( 'login', $log );
		}

		if ( ! $user || ! wp_check_password( $password, $user->user_pass, $user->ID ) ) {
			wp_safe_redirect( add_query_arg( array( 'auth_error' => 'invalid_credentials', 'tab' => 'signin' ), home_url( '/custom-login/' ) ) );
			exit;
		}

		wp_set_current_user( $user->ID );
		wp_set_auth_cookie( $user->ID, true );

		$admin_cap = defined( 'NMBP_ADMIN_CAP' ) ? NMBP_ADMIN_CAP : 'manage_options';
		if ( user_can( $user, $admin_cap ) || in_array( 'administrator', (array) $user->roles, true ) ) {
			$redirect_to = admin_url( 'admin.php?page=nmbp-dashboard' );
		} elseif ( ! empty( $_POST['redirect_to'] ) ) {
			$redirect_to = esc_url_raw( wp_unslash( $_POST['redirect_to'] ) );
		}

		wp_safe_redirect( $redirect_to );
		exit;
	}
	add_action( 'admin_post_nopriv_nmt_custom_login', 'nmt_handle_custom_login' );
	add_action( 'admin_post_nmt_custom_login', 'nmt_handle_custom_login' );
endif;

// =============================================================================
// MULTI-STEP OTP REGISTRATION HANDLERS (WITH HTML EMAIL)
// =============================================================================

// Step 1: Send OTP to Email.
add_action( 'admin_post_nopriv_nmt_send_email_otp', 'nmt_handle_send_email_otp' );
add_action( 'admin_post_nmt_send_email_otp', 'nmt_handle_send_email_otp' );
function nmt_handle_send_email_otp() {
	$email       = isset( $_POST['reg_email'] ) ? sanitize_email( wp_unslash( $_POST['reg_email'] ) ) : '';
	$redirect_to = ! empty( $_POST['redirect_to'] ) ? esc_url_raw( wp_unslash( $_POST['redirect_to'] ) ) : home_url( '/account/' );

	if ( empty( $email ) || ! is_email( $email ) ) {
		wp_safe_redirect( add_query_arg( array( 'auth_error' => 'empty_fields', 'tab' => 'create' ), home_url( '/custom-login/' ) ) );
		exit;
	}

	if ( email_exists( $email ) ) {
		wp_safe_redirect( add_query_arg( array( 'auth_error' => 'email_exists', 'tab' => 'create' ), home_url( '/custom-login/' ) ) );
		exit;
	}

	$otp_code = wp_rand( 100000, 999999 );

	$temp_data = array(
		'email'       => $email,
		'otp'         => $otp_code,
		'redirect_to' => $redirect_to,
		'verified'    => false,
	);
	set_transient( 'nmt_otp_flow_' . md5( $email ), $temp_data, 300 );

	$subject = __( 'Your Verification Code - Noorjahan Movie Theater', 'noorjahan-movie-theater' );
	$year    = wp_date( 'Y' );
	$message = '<!DOCTYPE html><html><head><meta charset="UTF-8"><style id="nmt-otp-email-css">
		.nmt-mail-wrap { background-color: #070e11; padding: 40px 0; font-family: "Plus Jakarta Sans", Arial, sans-serif; color: #f8fafc; }
		.nmt-mail-card { max-width: 500px; background-color: #0b171c; border: 1px solid #1e293b; border-radius: 16px; overflow: hidden; }
		.nmt-mail-head { padding: 30px 40px; text-align: center; border-bottom: 1px solid #1e293b; }
		.nmt-mail-brand { margin: 0; color: #f59e0b; font-size: 22px; font-weight: 900; letter-spacing: 0.05em; text-transform: uppercase; }
		.nmt-mail-body { padding: 40px; }
		.nmt-mail-greeting { margin: 0 0 20px; font-size: 15px; color: #94a3b8; line-height: 1.6; }
		.nmt-mail-copy { margin: 0 0 25px; font-size: 15px; color: #f8fafc; line-height: 1.6; }
		.nmt-otp-box { background-color: #060d10; border: 2px dashed #f59e0b; border-radius: 12px; padding: 20px; text-align: center; margin-bottom: 25px; }
		.nmt-otp-code { font-size: 36px; font-weight: 900; color: #f59e0b; letter-spacing: 0.25em; font-family: monospace; display: inline-block; }
		.nmt-mail-note { margin: 0; font-size: 13px; color: #64748b; line-height: 1.5; text-align: center; }
		.nmt-mail-foot { background-color: #060d10; padding: 20px; text-align: center; border-top: 1px solid #1e293b; }
		.nmt-mail-foot-copy { margin: 0; font-size: 11px; color: #64748b; }
	</style></head><body>
	<div class="nmt-mail-wrap">
		<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" class="nmt-mail-card">
			<tr>
				<td class="nmt-mail-head">
					<h2 class="nmt-mail-brand">' . esc_html__( 'Noorjahan Cinemas', 'noorjahan-movie-theater' ) . '</h2>
				</td>
			</tr>
			<tr>
				<td class="nmt-mail-body">
					<p class="nmt-mail-greeting">' . esc_html__( 'Hello,', 'noorjahan-movie-theater' ) . '</p>
					<p class="nmt-mail-copy">' . wp_kses_post( __( 'Thank you for registering. Please use the secure verification code below to verify your email address. This code will expire in <strong>5 minutes</strong>.', 'noorjahan-movie-theater' ) ) . '</p>
					<div class="nmt-otp-box">
						<span class="nmt-otp-code">' . esc_html( (string) $otp_code ) . '</span>
					</div>
					<p class="nmt-mail-note">' . esc_html__( 'If you did not request this code, please ignore this email.', 'noorjahan-movie-theater' ) . '</p>
				</td>
			</tr>
			<tr>
				<td class="nmt-mail-foot">
					<p class="nmt-mail-foot-copy">&copy; ' . esc_html( $year ) . ' ' . esc_html__( 'Noorjahan Movie Theater. All rights reserved.', 'noorjahan-movie-theater' ) . '</p>
				</td>
			</tr>
		</table>
	</div></body></html>';

	$headers = array( 'Content-Type: text/html; charset=UTF-8' );
	wp_mail( $email, $subject, $message, $headers );

	$redirect_url = add_query_arg(
		array(
			'tab'           => 'create',
			'step'          => 'verify_otp',
			'pending_email' => rawurlencode( $email ),
			'redirect_to'   => rawurlencode( $redirect_to ),
		),
		home_url( '/custom-login/' )
	);

	wp_safe_redirect( $redirect_url );
	exit;
}

// Step 2: Verify OTP.
add_action( 'admin_post_nopriv_nmt_verify_email_otp', 'nmt_handle_verify_email_otp' );
add_action( 'admin_post_nmt_verify_email_otp', 'nmt_handle_verify_email_otp' );
function nmt_handle_verify_email_otp() {
	$email       = isset( $_POST['pending_email'] ) ? sanitize_email( wp_unslash( $_POST['pending_email'] ) ) : '';
	$user_otp    = isset( $_POST['entered_otp'] ) ? sanitize_text_field( wp_unslash( $_POST['entered_otp'] ) ) : '';
	$redirect_to = ! empty( $_POST['redirect_to'] ) ? esc_url_raw( wp_unslash( $_POST['redirect_to'] ) ) : home_url( '/account/' );

	$saved_data = get_transient( 'nmt_otp_flow_' . md5( $email ) );

	if ( ! $saved_data || (string) $saved_data['otp'] !== (string) $user_otp ) {
		wp_safe_redirect(
			add_query_arg(
				array(
					'auth_error'    => 'invalid_otp',
					'tab'           => 'create',
					'step'          => 'verify_otp',
					'pending_email' => rawurlencode( $email ),
					'redirect_to'   => rawurlencode( $redirect_to ),
				),
				home_url( '/custom-login/' )
			)
		);
		exit;
	}

	$saved_data['verified'] = true;
	set_transient( 'nmt_otp_flow_' . md5( $email ), $saved_data, 300 );

	$redirect_url = add_query_arg(
		array(
			'tab'           => 'create',
			'step'          => 'complete_profile',
			'pending_email' => rawurlencode( $email ),
			'redirect_to'   => rawurlencode( $redirect_to ),
		),
		home_url( '/custom-login/' )
	);

	wp_safe_redirect( $redirect_url );
	exit;
}

// Step 3: Finalize Account Creation.
add_action( 'admin_post_nopriv_nmt_finalize_registration', 'nmt_handle_finalize_registration' );
add_action( 'admin_post_nmt_finalize_registration', 'nmt_handle_finalize_registration' );
function nmt_handle_finalize_registration() {
	$email        = isset( $_POST['pending_email'] ) ? sanitize_email( wp_unslash( $_POST['pending_email'] ) ) : '';
	$name         = isset( $_POST['reg_name'] ) ? sanitize_text_field( wp_unslash( $_POST['reg_name'] ) ) : '';
	$phone        = isset( $_POST['reg_phone'] ) ? sanitize_text_field( wp_unslash( $_POST['reg_phone'] ) ) : '';
	$password     = isset( $_POST['reg_pass'] ) ? (string) wp_unslash( $_POST['reg_pass'] ) : '';
	$pass_confirm = isset( $_POST['reg_pass_confirm'] ) ? (string) wp_unslash( $_POST['reg_pass_confirm'] ) : '';
	$redirect_to  = ! empty( $_POST['redirect_to'] ) ? esc_url_raw( wp_unslash( $_POST['redirect_to'] ) ) : home_url( '/account/' );

	$saved_data = get_transient( 'nmt_otp_flow_' . md5( $email ) );

	if ( ! $saved_data || true !== $saved_data['verified'] ) {
		wp_safe_redirect( add_query_arg( array( 'auth_error' => 'session_expired', 'tab' => 'create' ), home_url( '/custom-login/' ) ) );
		exit;
	}

	if ( empty( $name ) || empty( $password ) || empty( $phone ) ) {
		wp_safe_redirect( add_query_arg( array( 'auth_error' => 'empty_fields', 'tab' => 'create', 'step' => 'complete_profile', 'pending_email' => rawurlencode( $email ) ), home_url( '/custom-login/' ) ) );
		exit;
	}

	if ( $password !== $pass_confirm ) {
		wp_safe_redirect( add_query_arg( array( 'auth_error' => 'password_mismatch', 'tab' => 'create', 'step' => 'complete_profile', 'pending_email' => rawurlencode( $email ) ), home_url( '/custom-login/' ) ) );
		exit;
	}

	$user_id = wp_create_user( $email, $password, $email );

	if ( is_wp_error( $user_id ) ) {
		wp_safe_redirect( add_query_arg( array( 'auth_error' => 'registration_failed', 'tab' => 'create' ), home_url( '/custom-login/' ) ) );
		exit;
	}

	wp_update_user(
		array(
			'ID'           => $user_id,
			'display_name' => $name,
			'first_name'   => $name,
		)
	);

	update_user_meta( $user_id, 'phone_number', $phone );
	update_user_meta( $user_id, 'billing_phone', $phone );

	delete_transient( 'nmt_otp_flow_' . md5( $email ) );

	wp_set_current_user( $user_id );
	wp_set_auth_cookie( $user_id, true );

	wp_safe_redirect( $redirect_to );
	exit;
}

// =============================================================================
// ROLE-BASED LOGIN REDIRECT (ADMINS -> WP-ADMIN, OTHERS -> CUSTOM ACCOUNT)
// =============================================================================
if ( ! function_exists( 'nmt_force_login_redirect' ) ) :
	function nmt_force_login_redirect( $redirect_to, $request, $user ) {
		if ( isset( $user->ID ) && is_object( $user ) ) {
			if ( in_array( 'administrator', (array) $user->roles, true ) ) {
				return admin_url();
			}
			return home_url( '/account/' );
		}
		return $redirect_to;
	}
	add_filter( 'login_redirect', 'nmt_force_login_redirect', 99, 3 );
endif;

function nmt_cinema_site_favicon() {
	$favicon_url = get_theme_file_uri( '/assets/img/favicon.png' );
	echo '<link rel="icon" href="' . esc_url( $favicon_url ) . '" sizes="32x32" />' . "\n";
	echo '<link rel="icon" href="' . esc_url( $favicon_url ) . '" sizes="192x192" />' . "\n";
	echo '<link rel="apple-touch-icon" href="' . esc_url( $favicon_url ) . '" />' . "\n";
}
add_action( 'wp_head', 'nmt_cinema_site_favicon' );

// =============================================================================
// SEQUENTIAL TICKET ID GENERATOR (NMBP-DD-MON-YY-##)
// =============================================================================
if ( ! function_exists( 'nmt_generate_cinema_ticket_id' ) ) {
	function nmt_generate_cinema_ticket_id( $prefix = 'NMBP' ) {
		global $wpdb;
		$table_bookings = $wpdb->prefix . 'nmbp_bookings';

		$today_ymd = current_time( 'Y-m-d' );
		$day_str   = strtoupper( wp_date( 'd-M-y' ) );

		$count_today = (int) $wpdb->get_var(
			$wpdb->prepare(
				"SELECT COUNT(id) FROM {$table_bookings} WHERE DATE(booking_date) = %s",
				$today_ymd
			)
		);

		$sequence_number = str_pad( (string) ( $count_today + 1 ), 2, '0', STR_PAD_LEFT );
		$clean_prefix    = strtoupper( sanitize_key( $prefix ) );
		$candidate_token = sprintf( '%s-%s-%s', $clean_prefix, $day_str, $sequence_number );

		$token_exists = (int) $wpdb->get_var(
			$wpdb->prepare(
				"SELECT COUNT(id) FROM {$table_bookings} WHERE booking_token = %s",
				$candidate_token
			)
		);

		if ( $token_exists > 0 ) {
			$extra_seq       = str_pad( (string) wp_rand( 10, 99 ), 2, '0', STR_PAD_LEFT );
			$candidate_token = sprintf( '%s-%s-%s', $clean_prefix, $day_str, $extra_seq );
		}

		return $candidate_token;
	}
}

// =============================================================================
// PROCESS FRONTEND CHECKOUT WITH SSLCOMMERZ GATEWAY INTEGRATION
// =============================================================================
add_action( 'admin_post_nmt_process_frontend_checkout', 'nmt_handle_sslcommerz_direct_checkout' );
function nmt_handle_sslcommerz_direct_checkout() {
	if ( ! is_user_logged_in() ) {
		wp_safe_redirect( home_url( '/custom-login/' ) );
		exit;
	}

	check_admin_referer( 'nmt_frontend_checkout_verify', 'nmt_checkout_nonce' );

	global $wpdb;
	$table_bookings = $wpdb->prefix . 'nmbp_bookings';

	$movie_title    = isset( $_POST['movie_title'] ) ? sanitize_text_field( wp_unslash( $_POST['movie_title'] ) ) : '';
	$auditorium     = isset( $_POST['auditorium'] ) ? sanitize_text_field( wp_unslash( $_POST['auditorium'] ) ) : 'Auditorium 1 (30 Seats)';
	$seats          = isset( $_POST['seats'] ) ? sanitize_text_field( wp_unslash( $_POST['seats'] ) ) : '';
	$showtime       = isset( $_POST['showtime'] ) ? sanitize_text_field( wp_unslash( $_POST['showtime'] ) ) : '';
	$booking_date   = isset( $_POST['booking_date'] ) ? sanitize_text_field( wp_unslash( $_POST['booking_date'] ) ) : current_time( 'Y-m-d' );
	$total_amount   = isset( $_POST['total_amount'] ) ? (float) wp_unslash( $_POST['total_amount'] ) : 0.00;
	$customer_name  = isset( $_POST['customer_name'] ) ? sanitize_text_field( wp_unslash( $_POST['customer_name'] ) ) : __( 'Guest', 'noorjahan-movie-theater' );
	$customer_phone = isset( $_POST['customer_phone'] ) ? sanitize_text_field( wp_unslash( $_POST['customer_phone'] ) ) : '';
	$customer_email = isset( $_POST['customer_email'] ) ? sanitize_email( wp_unslash( $_POST['customer_email'] ) ) : '';

	$tran_id       = 'NMBP-' . strtoupper( wp_generate_password( 8, false ) );
	$booking_token = nmt_generate_cinema_ticket_id( 'NMBP' );

	// SSLCommerz Credentials.
	$store_id     = 'testmoviex6wn';
	$store_passwd = 'eZPZ7jbP06wM';
	$api_url      = 'https://sandbox.sslcommerz.com/gwprocess/v4/api.php';

	$post_data = array(
		'store_id'         => $store_id,
		'store_passwd'     => $store_passwd,
		'total_amount'     => $total_amount,
		'currency'         => 'BDT',
		'tran_id'          => $tran_id,
		'success_url'      => add_query_arg(
			array(
				'token' => rawurlencode( $booking_token ),
				'tran'  => rawurlencode( $tran_id ),
			),
			home_url( '/wp-json/nmbp/v1/payment-success' )
		),
		'fail_url'         => add_query_arg( 'tran', rawurlencode( $tran_id ), home_url( '/wp-json/nmbp/v1/payment-fail' ) ),
		'cancel_url'       => add_query_arg( 'tran', rawurlencode( $tran_id ), home_url( '/wp-json/nmbp/v1/payment-cancel' ) ),
		'ipn_url'          => home_url( '/wp-json/nmbp/v1/payment-ipn' ),
		'product_name'     => 'Movie Ticket: ' . $movie_title,
		'product_category' => 'Entertainment',
		'product_profile'  => 'non-physical-goods',
		'cus_name'         => $customer_name,
		'cus_email'        => $customer_email,
		'cus_add1'         => 'Noorjahan Cinema Complex',
		'cus_city'         => 'Sylhet',
		'cus_postcode'     => '3100',
		'cus_country'      => 'Bangladesh',
		'cus_phone'        => $customer_phone,
	);

	$wpdb->insert(
		$table_bookings,
		array(
			'booking_token'  => $booking_token,
			'movie_title'    => $movie_title,
			'auditorium'     => $auditorium,
			'showtime'       => $showtime,
			'ticket_type'    => 'Standard',
			'seats'          => $seats,
			'customer_name'  => $customer_name,
			'customer_phone' => $customer_phone,
			'customer_email' => $customer_email,
			'total_amount'   => $total_amount,
			'payment_method' => 'SSLCommerz',
			'payment_status' => 'Pending',
			'booking_date'   => $booking_date . ' ' . current_time( 'H:i:s' ),
		),
		array( '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%f', '%s', '%s', '%s' )
	);

	$response = wp_remote_post(
		$api_url,
		array(
			'body'      => $post_data,
			'timeout'   => 30,
			'sslverify' => true,
		)
	);

	if ( is_wp_error( $response ) ) {
		wp_die(
			'<h3>' . esc_html__( 'Connection Error', 'noorjahan-movie-theater' ) . '</h3><p>' . esc_html( $response->get_error_message() ) . '</p>',
			esc_html__( 'Gateway Failure', 'noorjahan-movie-theater' ),
			array( 'back_link' => true )
		);
	}

	$result = json_decode( wp_remote_retrieve_body( $response ), true );

	if ( isset( $result['status'] ) && 'SUCCESS' === $result['status'] && ! empty( $result['GatewayPageURL'] ) ) {
		wp_redirect( esc_url_raw( $result['GatewayPageURL'] ) );
		exit;
	} else {
		$error_reason = $result['failedreason'] ?? __( 'Unknown gateway initialization error.', 'noorjahan-movie-theater' );
		wp_die(
			'<h3>' . esc_html__( 'Gateway Initialization Failed', 'noorjahan-movie-theater' ) . '</h3><p>' . esc_html( $error_reason ) . '</p>',
			esc_html__( 'Payment Error', 'noorjahan-movie-theater' ),
			array( 'back_link' => true )
		);
	}
}

// =============================================================================
// SEND ORDER CONFIRMATION EMAIL UPON SUCCESSFUL BOOKING & PAYMENT
// =============================================================================
function nmt_send_order_confirmation_email( $booking_token ) {
	global $wpdb;
	$table_bookings = $wpdb->prefix . 'nmbp_bookings';

	$booking = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$table_bookings} WHERE booking_token = %s", $booking_token ) );

	if ( ! $booking || empty( $booking->customer_email ) ) {
		return;
	}

	$currency_symbol = sanitize_text_field( get_option( 'nmbp_currency_symbol', '৳' ) );
	$year            = wp_date( 'Y' );
	$to              = sanitize_email( $booking->customer_email );
	$subject         = sprintf(
		/* translators: 1: Movie title, 2: Booking token */
		__( 'Booking Confirmed: %1$s [%2$s]', 'noorjahan-movie-theater' ),
		$booking->movie_title,
		$booking->booking_token
	);

	$message = '<!DOCTYPE html><html><head><meta charset="UTF-8"><style id="nmt-confirmation-email-css">
		.nmt-order-wrap { background-color: #070e11; padding: 40px 0; font-family: "Plus Jakarta Sans", Arial, sans-serif; color: #f8fafc; }
		.nmt-order-card { max-width: 600px; background-color: #0b171c; border: 1px solid #1e293b; border-radius: 16px; overflow: hidden; }
		.nmt-order-head { padding: 30px 40px; text-align: center; border-bottom: 1px solid #1e293b; }
		.nmt-order-brand { margin: 0; color: #f59e0b; font-size: 22px; font-weight: 900; letter-spacing: 0.05em; text-transform: uppercase; }
		.nmt-order-sub { margin: 5px 0 0; font-size: 13px; color: #0d9488; font-weight: 700; text-transform: uppercase; }
		.nmt-order-body { padding: 40px; }
		.nmt-order-greeting { margin: 0 0 15px; font-size: 15px; color: #94a3b8; }
		.nmt-order-lead { margin: 0 0 25px; font-size: 15px; color: #f8fafc; line-height: 1.6; }
		.nmt-order-box { background-color: #060d10; border: 1px solid #1e293b; border-radius: 12px; margin-bottom: 25px; }
		.nmt-order-key { font-size: 13px; color: #64748b; font-weight: 800; text-transform: uppercase; border-bottom: 1px solid #1e293b; }
		.nmt-order-key--last { font-size: 13px; color: #64748b; font-weight: 800; text-transform: uppercase; }
		.nmt-order-val-token { font-size: 15px; color: #f59e0b; font-weight: 900; font-family: monospace; text-align: right; border-bottom: 1px solid #1e293b; }
		.nmt-order-val-title { font-size: 15px; color: #ffffff; font-weight: 800; text-align: right; border-bottom: 1px solid #1e293b; }
		.nmt-order-val-slot { font-size: 14px; color: #ffffff; text-align: right; border-bottom: 1px solid #1e293b; }
		.nmt-order-slot-accent { color: #0d9488; font-weight: 700; }
		.nmt-order-val-seats { font-size: 14px; color: #f8fafc; font-weight: 800; font-family: monospace; text-align: right; border-bottom: 1px solid #1e293b; }
		.nmt-order-val-total { font-size: 16px; color: #f59e0b; font-weight: 900; font-family: monospace; text-align: right; }
		.nmt-order-note { margin: 0; font-size: 13px; color: #64748b; line-height: 1.5; text-align: center; }
		.nmt-order-foot { background-color: #060d10; padding: 20px; text-align: center; border-top: 1px solid #1e293b; }
		.nmt-order-foot-copy { margin: 0; font-size: 11px; color: #64748b; }
	</style></head><body>
	<div class="nmt-order-wrap">
		<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" class="nmt-order-card">
			<tr>
				<td class="nmt-order-head">
					<h2 class="nmt-order-brand">' . esc_html__( 'Noorjahan Movie Theater', 'noorjahan-movie-theater' ) . '</h2>
					<p class="nmt-order-sub">' . esc_html__( 'Official Booking Confirmation', 'noorjahan-movie-theater' ) . '</p>
				</td>
			</tr>
			<tr>
				<td class="nmt-order-body">
					<p class="nmt-order-greeting">' . wp_kses_post( sprintf( __( 'Dear <strong>%s</strong>,', 'noorjahan-movie-theater' ), esc_html( $booking->customer_name ) ) ) . '</p>
					<p class="nmt-order-lead">' . wp_kses_post( sprintf( __( 'Thank you for your booking! Your payment has been successfully processed via <strong>%s</strong>. Below are your ticket details:', 'noorjahan-movie-theater' ), esc_html( $booking->payment_method ) ) ) . '</p>

					<table width="100%" cellpadding="12" cellspacing="0" class="nmt-order-box">
						<tr>
							<td class="nmt-order-key">' . esc_html__( 'Ticket Reference', 'noorjahan-movie-theater' ) . '</td>
							<td class="nmt-order-val-token">' . esc_html( $booking->booking_token ) . '</td>
						</tr>
						<tr>
							<td class="nmt-order-key">' . esc_html__( 'Movie Title', 'noorjahan-movie-theater' ) . '</td>
							<td class="nmt-order-val-title">' . esc_html( $booking->movie_title ) . '</td>
						</tr>
						<tr>
							<td class="nmt-order-key">' . esc_html__( 'Auditorium & Showtime', 'noorjahan-movie-theater' ) . '</td>
							<td class="nmt-order-val-slot">' . esc_html( $booking->auditorium ) . '<br><span class="nmt-order-slot-accent">' . esc_html( $booking->showtime . ' (' . $booking->booking_date . ')' ) . '</span></td>
						</tr>
						<tr>
							<td class="nmt-order-key">' . esc_html__( 'Selected Seats', 'noorjahan-movie-theater' ) . '</td>
							<td class="nmt-order-val-seats">' . esc_html( $booking->seats ) . '</td>
						</tr>
						<tr>
							<td class="nmt-order-key--last">' . esc_html__( 'Total Paid Amount', 'noorjahan-movie-theater' ) . '</td>
							<td class="nmt-order-val-total">' . esc_html( $currency_symbol . ' ' . number_format_i18n( (float) $booking->total_amount, 2 ) ) . '</td>
						</tr>
					</table>

					<p class="nmt-order-note">' . esc_html__( 'Please show this ticket token or your account pass at the cinema entrance counter.', 'noorjahan-movie-theater' ) . '</p>
				</td>
			</tr>
			<tr>
				<td class="nmt-order-foot">
					<p class="nmt-order-foot-copy">&copy; ' . esc_html( $year ) . ' ' . esc_html__( 'Noorjahan Movie Theater. All rights reserved.', 'noorjahan-movie-theater' ) . '</p>
				</td>
			</tr>
		</table>
	</div></body></html>';

	$headers = array( 'Content-Type: text/html; charset=UTF-8' );
	wp_mail( $to, $subject, $message, $headers );
}

// =============================================================================
// REGISTER REST API ENDPOINTS FOR SSLCOMMERZ CALLBACKS
// =============================================================================
add_action(
	'rest_api_init',
	function () {
		register_rest_route(
			'nmbp/v1',
			'/payment-success',
			array(
				'methods'             => array( 'GET', 'POST' ),
				'callback'            => 'nmt_handle_sslcommerz_success',
				'permission_callback' => '__return_true',
			)
		);

		register_rest_route(
			'nmbp/v1',
			'/payment-fail',
			array(
				'methods'             => array( 'GET', 'POST' ),
				'callback'            => 'nmt_handle_sslcommerz_fail',
				'permission_callback' => '__return_true',
			)
		);

		register_rest_route(
			'nmbp/v1',
			'/payment-cancel',
			array(
				'methods'             => array( 'GET', 'POST' ),
				'callback'            => 'nmt_handle_sslcommerz_cancel',
				'permission_callback' => '__return_true',
			)
		);
	}
);

function nmt_handle_sslcommerz_success( WP_REST_Request $request ) {
	global $wpdb;
	$table_bookings = $wpdb->prefix . 'nmbp_bookings';
	$table_ledger   = $wpdb->prefix . 'nmbp_ledger';

	$booking_token = sanitize_text_field( (string) $request->get_param( 'token' ) );

	if ( ! empty( $booking_token ) ) {
		$wpdb->update(
			$table_bookings,
			array( 'payment_status' => 'Confirmed' ),
			array( 'booking_token' => $booking_token ),
			array( '%s' ),
			array( '%s' )
		);

		// Trigger confirmation email.
		nmt_send_order_confirmation_email( $booking_token );

		$booking = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$table_bookings} WHERE booking_token = %s", $booking_token ) );
		if ( $booking ) {
			$wpdb->insert(
				$table_ledger,
				array(
					'reference_no'     => $booking_token,
					'txn_type'         => 'Income',
					'particulars'      => 'SSLCommerz Ticket: ' . $booking->movie_title . ' (' . $booking->seats . ')',
					'method'           => 'SSLCommerz',
					'amount'           => $booking->total_amount,
					'transaction_date' => current_time( 'mysql' ),
				),
				array( '%s', '%s', '%s', '%s', '%f', '%s' )
			);
		}

		$thank_you_url = add_query_arg(
			array(
				'booking_status' => 'success',
				'token'          => rawurlencode( $booking_token ),
			),
			home_url( '/thank-you/' )
		);

		wp_safe_redirect( $thank_you_url );
		exit;
	}

	wp_safe_redirect( home_url( '/account/' ) );
	exit;
}

function nmt_handle_sslcommerz_fail( WP_REST_Request $request ) {
	wp_safe_redirect( add_query_arg( 'payment', 'failed', home_url( '/checkout/' ) ) );
	exit;
}

function nmt_handle_sslcommerz_cancel( WP_REST_Request $request ) {
	wp_safe_redirect( add_query_arg( 'payment', 'cancelled', home_url( '/checkout/' ) ) );
	exit;
}

// =============================================================================
// DISABLE ADMIN BAR FOR NON-ADMINISTRATORS
// =============================================================================
add_action( 'after_setup_theme', 'nmt_disable_admin_bar' );
function nmt_disable_admin_bar() {
	if ( ! current_user_can( 'administrator' ) ) {
		show_admin_bar( false );
	}
}