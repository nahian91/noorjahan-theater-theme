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
function noorjahan_movie_theater_setup() {
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
            'noorjahan_movie_theater_custom_background_args',
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
add_action( 'after_setup_theme', 'noorjahan_movie_theater_setup' );

function noorjahan_movie_theater_content_width() {
    $GLOBALS['content_width'] = apply_filters( 'noorjahan_movie_theater_content_width', 640 );
}
add_action( 'after_setup_theme', 'noorjahan_movie_theater_content_width', 0 );

function noorjahan_movie_theater_widgets_init() {
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
add_action( 'widgets_init', 'noorjahan_movie_theater_widgets_init' );

function noorjahan_movie_theater_scripts() {
    wp_enqueue_style( 
        'noorjahan-google-fonts', 
        'https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,400;1,700&family=Space+Grotesk:wght@500;700;800&display=swap', 
        array(), 
        null 
    );

    wp_enqueue_style( 
        'noorjahan-cinema-main-style', 
        get_template_directory_uri() . '/assets/css/style.css', 
        array( 'noorjahan-google-fonts' ), 
        _S_VERSION 
    );

    wp_enqueue_script( 
        'noorjahan-movie-theater-navigation', 
        get_template_directory_uri() . '/js/navigation.js', 
        array(), 
        _S_VERSION, 
        true 
    );

    wp_enqueue_script( 
        'noorjahan-cinema-main-script', 
        get_template_directory_uri() . '/assets/js/main.js', 
        array(), 
        _S_VERSION, 
        true 
    );

    if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
        wp_enqueue_script( 'comment-reply' );
    }
}
add_action( 'wp_enqueue_scripts', 'noorjahan_movie_theater_scripts' );

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
add_action( 'login_init', 'bsc_redirect_default_wp_login_to_custom' );
function bsc_redirect_default_wp_login_to_custom() {
    if ( isset( $_GET['action'] ) && in_array( $_GET['action'], array( 'logout', 'lostpassword', 'rp', 'resetpass' ), true ) ) {
        return;
    }

    if ( isset( $_GET['loggedout'] ) ) {
        return;
    }

    if ( ! is_user_logged_in() ) {
        $custom_login_url = home_url( '/custom-login/' );
        
        if ( ! empty( $_GET['redirect_to'] ) ) {
            $custom_login_url = add_query_arg( 'redirect_to', urlencode( $_GET['redirect_to'] ), $custom_login_url );
        }
        
        wp_safe_redirect( $custom_login_url );
        exit;
    }
}

// =============================================================================
// ROBUST CUSTOM FRONTEND LOGIN HANDLER WITH CUSTOM PLUGIN DASHBOARD REDIRECT
// =============================================================================
if ( ! function_exists( 'bsc_handle_custom_login' ) ) :
function bsc_handle_custom_login() {
    $log      = sanitize_text_field( wp_unslash( $_POST['log'] ?? '' ) );
    $password = $_POST['pwd'] ?? '';

    // Default redirect for regular members
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

    // STRICT CHECK: If the user is an administrator, send them straight to your plugin dashboard page
    if ( in_array( 'administrator', (array) $user->roles, true ) ) {
        $redirect_to = admin_url( 'admin.php?page=bsc-dashboard' );
    } elseif ( ! empty( $_POST['redirect_to'] ) ) {
        $redirect_to = esc_url_raw( wp_unslash( $_POST['redirect_to'] ) );
    }

    wp_safe_redirect( $redirect_to );
    exit;
}
add_action( 'admin_post_nopriv_bsc_custom_login', 'bsc_handle_custom_login' );
add_action( 'admin_post_bsc_custom_login', 'bsc_handle_custom_login' );
endif;

// =============================================================================
// CUSTOM FRONTEND REGISTRATION HANDLER
// =============================================================================
if ( ! function_exists( 'bsc_handle_custom_registration' ) ) :
function bsc_handle_custom_registration() {
    $name        = sanitize_text_field( wp_unslash( $_POST['reg_name'] ?? '' ) );
    $email       = sanitize_email( wp_unslash( $_POST['reg_email'] ?? '' ) );
    $phone       = sanitize_text_field( wp_unslash( $_POST['reg_phone'] ?? '' ) );
    $password    = $_POST['reg_pass'] ?? '';
    $redirect_to = ! empty( $_POST['redirect_to'] ) ? esc_url_raw( $_POST['redirect_to'] ) : home_url( '/account/' );

    if ( empty( $email ) || empty( $password ) || empty( $name ) ) {
        wp_safe_redirect( add_query_arg( array( 'auth_error' => 'empty_fields', 'tab' => 'create' ), home_url( '/custom-login/' ) ) );
        exit;
    }

    if ( email_exists( $email ) ) {
        wp_safe_redirect( add_query_arg( array( 'auth_error' => 'email_exists', 'tab' => 'create' ), home_url( '/custom-login/' ) ) );
        exit;
    }

    $user_id = wp_create_user( $email, $password, $email );

    if ( is_wp_error( $user_id ) ) {
        wp_safe_redirect( add_query_arg( array( 'auth_error' => 'registration_failed', 'tab' => 'create' ), home_url( '/custom-login/' ) ) );
        exit;
    }

    wp_update_user( array(
        'ID'           => $user_id,
        'display_name' => $name,
        'first_name'   => $name,
    ) );

    if ( ! empty( $phone ) ) {
        update_user_meta( $user_id, 'phone_number', $phone );
        update_user_meta( $user_id, 'billing_phone', $phone );
    }

    wp_set_current_user( $user_id );
    wp_set_auth_cookie( $user_id, true );

    wp_safe_redirect( $redirect_to );
    exit;
}
add_action( 'admin_post_nopriv_bsc_custom_register', 'bsc_handle_custom_registration' );
add_action( 'admin_post_bsc_custom_register', 'bsc_handle_custom_registration' );
endif;

// =============================================================================
// ROLE-BASED LOGIN REDIRECT (ADMINS -> WP-ADMIN, OTHERS -> CUSTOM ACCOUNT)
// =============================================================================
if ( ! function_exists( 'bsc_force_login_redirect' ) ) :
function bsc_force_login_redirect( $redirect_to, $request, $user ) {
    if ( isset( $user->ID ) && is_object( $user ) ) {
        if ( in_array( 'administrator', (array) $user->roles, true ) ) {
            return admin_url();
        }
        return home_url( '/account/' );
    }
    return $redirect_to;
}
add_filter( 'login_redirect', 'bsc_force_login_redirect', 99, 3 );
endif;

function ifs_cinema_site_favicon() {
    $favicon_url = get_theme_file_uri( '/assets/img/favicon.png' );
    echo '<link rel="icon" href="' . esc_url( $favicon_url ) . '" sizes="32x32" />' . "\n";
    echo '<link rel="icon" href="' . esc_url( $favicon_url ) . '" sizes="192x192" />' . "\n";
    echo '<link rel="apple-touch-icon" href="' . esc_url( $favicon_url ) . '" />' . "\n";
}
add_action( 'wp_head', 'ifs_cinema_site_favicon' );

// =============================================================================
// SEQUENTIAL TICKET ID GENERATOR (BCS-DD-MON-YY-##)
// =============================================================================
if ( ! function_exists( 'bsc_generate_cinema_ticket_id' ) ) {
    function bsc_generate_cinema_ticket_id( $prefix = 'BCS' ) {
        global $wpdb;
        $table_bookings = $wpdb->prefix . 'bsc_bookings';

        $today_ymd = current_time( 'Y-m-d' );
        $day_str   = strtoupper( current_time( 'd-M-y' ) );

        $count_today = (int) $wpdb->get_var( $wpdb->prepare(
            "SELECT COUNT(id) FROM {$table_bookings} WHERE DATE(booking_date) = %s",
            $today_ymd
        ) );

        $sequence_number = str_pad( (string) ( $count_today + 1 ), 2, '0', STR_PAD_LEFT );
        $candidate_token = sprintf( '%s-%s-%s', sanitize_key( $prefix ), $day_str, $sequence_number );

        $token_exists = (int) $wpdb->get_var( $wpdb->prepare(
            "SELECT COUNT(id) FROM {$table_bookings} WHERE booking_token = %s",
            $candidate_token
        ) );

        if ( $token_exists > 0 ) {
            $extra_seq       = str_pad( (string) wp_rand( 10, 99 ), 2, '0', STR_PAD_LEFT );
            $candidate_token = sprintf( '%s-%s-%s', sanitize_key( $prefix ), $day_str, $extra_seq );
        }

        return $candidate_token;
    }
}

// =============================================================================
// PROCESS FRONTEND CHECKOUT WITH CUSTOM FORMATTED TICKET ID
// =============================================================================
add_action( 'admin_post_bsc_process_frontend_checkout', 'bsc_handle_frontend_checkout_submission' );
function bsc_handle_frontend_checkout_submission() {
    if ( ! is_user_logged_in() ) {
        wp_safe_redirect( home_url( '/custom-login/' ) );
        exit;
    }

    check_admin_referer( 'bsc_frontend_checkout_verify', 'bsc_checkout_nonce' );

    global $wpdb;
    $table_bookings = $wpdb->prefix . 'bsc_bookings';
    $table_ledger   = $wpdb->prefix . 'bsc_ledger';

    $movie_title    = sanitize_text_field( wp_unslash( $_POST['movie_title'] ?? '' ) );
    $auditorium     = sanitize_text_field( wp_unslash( $_POST['auditorium'] ?? 'Auditorium 1 (23 Seats)' ) );
    $seats          = sanitize_text_field( wp_unslash( $_POST['seats'] ?? '' ) );
    $showtime       = sanitize_text_field( wp_unslash( $_POST['showtime'] ?? '' ) );
    $booking_date   = sanitize_text_field( wp_unslash( $_POST['booking_date'] ?? current_time( 'Y-m-d' ) ) );
    $total_amount   = floatval( $_POST['total_amount'] ?? 0.00 );
    $customer_name  = sanitize_text_field( wp_unslash( $_POST['customer_name'] ?? 'Guest' ) );
    $customer_phone = sanitize_text_field( wp_unslash( $_POST['customer_phone'] ?? '' ) );
    $customer_email = sanitize_email( wp_unslash( $_POST['customer_email'] ?? '' ) );
    $payment_method = sanitize_text_field( wp_unslash( $_POST['payment_method'] ?? 'Online Portal' ) );

    $booking_token = bsc_generate_cinema_ticket_id( 'BCS' );

    $booking_data = array(
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
        'payment_method' => $payment_method,
        'payment_status' => 'Confirmed',
        'booking_date'   => $booking_date . ' ' . current_time( 'H:i:s' ),
    );

    $booking_formats = array(
        '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%f', '%s', '%s', '%s'
    );

    $inserted = $wpdb->insert( $table_bookings, $booking_data, $booking_formats );

    if ( false !== $inserted ) {
        $wpdb->insert(
            $table_ledger,
            array(
                'reference_no'     => $booking_token,
                'txn_type'         => 'Income',
                'particulars'      => 'Online Ticket: ' . $movie_title . ' (' . $seats . ')',
                'method'           => $payment_method,
                'amount'           => $total_amount,
                'transaction_date' => current_time( 'mysql' ),
            ),
            array( '%s', '%s', '%s', '%s', '%f', '%s' )
        );

        $thank_you_url = add_query_arg(
            array(
                'booking_status' => 'success',
                'token'          => urlencode( $booking_token ),
            ),
            home_url( '/thank-you/' )
        );

        wp_safe_redirect( $thank_you_url );
        exit;
    } else {
        wp_die(
            '<h3>Booking Error</h3><p>' . esc_html( $wpdb->last_error ) . '</p>',
            'Reservation Failed',
            array( 'back_link' => true )
        );
    }
}

// =============================================================================
// DISABLE ADMIN BAR FOR NON-ADMINISTRATORS
// =============================================================================
add_action( 'after_setup_theme', 'bsc_disable_admin_bar' );
function bsc_disable_admin_bar() {
    if ( ! current_user_can( 'administrator' ) ) {
        show_admin_bar( false );
    }
}