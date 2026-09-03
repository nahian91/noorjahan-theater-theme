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
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function noorjahan_movie_theater_setup() {
	/*
		* Make theme available for translation.
		* Translations can be filed in the /languages/ directory.
		* If you're building a theme based on Noorjahan Movie Theater, use a find and replace
		* to change 'noorjahan-movie-theater' to the name of your theme in all the template files.
		*/
	load_theme_textdomain( 'noorjahan-movie-theater', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
		* Let WordPress manage the document title.
		* By adding theme support, we declare that this theme does not use a
		* hard-coded <title> tag in the document head, and expect WordPress to
		* provide it for us.
		*/
	add_theme_support( 'title-tag' );

	/*
		* Enable support for Post Thumbnails on posts and pages.
		*
		* @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		*/
	add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus(
		array(
			'menu-1' => esc_html__( 'Primary', 'noorjahan-movie-theater' ),
		)
	);

	/*
		* Switch default core markup for search form, comment form, and comments
		* to output valid HTML5.
		*/
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

	// Set up the WordPress core custom background feature.
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

	// Add theme support for selective refresh for widgets.
	add_theme_support( 'customize-selective-refresh-widgets' );

	/**
	 * Add support for core custom logo.
	 *
	 * @link https://codex.wordpress.org/Theme_Logo
	 */
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

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function noorjahan_movie_theater_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'noorjahan_movie_theater_content_width', 640 );
}
add_action( 'after_setup_theme', 'noorjahan_movie_theater_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
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

/**
 * Enqueue scripts and styles for Noorjahan Movie Theater.
 */
function noorjahan_movie_theater_scripts() {
    // 1. Google Fonts
    wp_enqueue_style( 
        'noorjahan-google-fonts', 
        'https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,400;1,700&family=Space+Grotesk:wght@500;700;800&display=swap', 
        array(), 
        null 
    );

    // 2. Custom Stylesheet (assets/css/style.css)
    wp_enqueue_style( 
        'noorjahan-cinema-main-style', 
        get_template_directory_uri() . '/assets/css/style.css', 
        array( 'noorjahan-google-fonts' ), 
        _S_VERSION 
    );

    // 4. Navigation Script
    wp_enqueue_script( 
        'noorjahan-movie-theater-navigation', 
        get_template_directory_uri() . '/js/navigation.js', 
        array(), 
        _S_VERSION, 
        true 
    );

    // 5. Main Custom Script (assets/js/main.js)
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

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/inc/jetpack.php';
}

/**
 * Custom Backend Handlers for VIP Portal Sign-In & Registration (Safe Declarations)
 */
if ( ! function_exists( 'bsc_handle_custom_login' ) ) :
function bsc_handle_custom_login() {
    if ( ! empty( $_POST['log'] ) && ! empty( $_POST['pwd'] ) ) {
        $creds = array(
            'user_login'    => sanitize_email( $_POST['log'] ),
            'user_password' => $_POST['pwd'],
            'remember'      => true,
        );

        $user = wp_signon( $creds, is_ssl() );

        if ( ! is_wp_error( $user ) ) {
            $redirect_to = ! empty( $_POST['redirect_to'] ) ? esc_url_raw( $_POST['redirect_to'] ) : home_url( '/account/' );
            wp_redirect( $redirect_to );
            exit;
        }
    }

    $login_page = home_url( '/account/' );
    wp_redirect( add_query_arg( 'login', 'failed', $login_page ) );
    exit;
}
add_action( 'admin_post_nopriv_bsc_custom_login', 'bsc_handle_custom_login' );
add_action( 'admin_post_bsc_custom_login', 'bsc_handle_custom_login' );
endif;

if ( ! function_exists( 'bsc_handle_custom_registration' ) ) :
function bsc_handle_custom_registration() {
    if ( ! empty( $_POST['reg_email'] ) && ! empty( $_POST['reg_pass'] ) ) {
        $email       = sanitize_email( $_POST['reg_email'] );
        $password    = $_POST['reg_pass'];
        $name        = ! empty( $_POST['reg_name'] ) ? sanitize_text_field( $_POST['reg_name'] ) : 'VIP Member';
        $phone       = ! empty( $_POST['reg_phone'] ) ? sanitize_text_field( $_POST['reg_phone'] ) : '';
        $redirect_to = ! empty( $_POST['redirect_to'] ) ? esc_url_raw( $_POST['redirect_to'] ) : home_url( '/account/' );

        if ( email_exists( $email ) ) {
            $login_page = home_url( '/account/' );
            wp_redirect( add_query_arg( 'register', 'email_exists', $login_page ) );
            exit;
        }

        $user_id = wp_create_user( $email, $password, $email );

        if ( ! is_wp_error( $user_id ) ) {
            wp_update_user( array(
                'ID'           => $user_id,
                'display_name' => $name,
                'first_name'   => $name,
            ) );

            if ( ! empty( $phone ) ) {
                update_user_meta( $user_id, 'phone_number', $phone );
            }

            $creds = array(
                'user_login'    => $email,
                'user_password' => $password,
                'remember'      => true,
            );
            $user = wp_signon( $creds, is_ssl() );

            if ( ! is_wp_error( $user ) ) {
                wp_redirect( $redirect_to );
                exit;
            }
        }
    }

    $login_page = home_url( '/account/' );
    wp_redirect( add_query_arg( 'register', 'failed', $login_page ) );
    exit;
}
add_action( 'admin_post_nopriv_bsc_custom_register', 'bsc_handle_custom_registration' );
add_action( 'admin_post_bsc_custom_register', 'bsc_handle_custom_registration' );
endif;

if ( ! function_exists( 'bsc_force_login_redirect' ) ) :
function bsc_force_login_redirect( $redirect_to, $request, $user ) {
    if ( isset( $user->ID ) ) {
        if ( in_array( 'administrator', (array) $user->roles, true ) ) {
            return $redirect_to;
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