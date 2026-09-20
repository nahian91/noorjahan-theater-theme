<?php
/**
 * Theme Header Template
 *
 * @package Noorjahan_Movie_Theater
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo( 'charset' ); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

  <!-- 01. TOP UTILITY BAR -->
  <aside class="ifs-cinema-topbar">
    <div class="ifs-cinema-container ifs-cinema-topbar-inner">
      <div class="ifs-cinema-topbar-info">
        <a href="tel:01930-111666" class="ifs-cinema-topbar-link">
          <svg class="ifs-cinema-svg-icon" viewBox="0 0 24 24"><path d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/></svg>
          <span><?php esc_html_e( '01930-111666', 'noorjahan-movie-theater' ); ?></span>
        </a>
        <a href="mailto:cinema@noorjahangrand.com" class="ifs-cinema-topbar-link">
          <svg class="ifs-cinema-svg-icon" viewBox="0 0 24 24"><path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/></svg>
          <span><?php esc_html_e( 'cinema@noorjahangrand.com', 'noorjahan-movie-theater' ); ?></span>
        </a>
        <div class="ifs-cinema-topbar-link">
          <svg class="ifs-cinema-svg-icon" viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>
          <span><?php esc_html_e( 'Waves 1 Dargah Gate, Sylhet 3100', 'noorjahan-movie-theater' ); ?></span>
        </div>
      </div>
    </div>
  </aside>

  <!-- 02. NAVBAR -->
  <header class="ifs-cinema-navbar">
    <div class="ifs-cinema-container" style="display:flex; align-items:center; justify-content:space-between; width:100%;">
      <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="ifs-cinema-brand-logo" rel="home">
        <?php if ( has_custom_logo() ) : ?>
          <?php 
            $custom_logo_id = get_theme_mod( 'custom_logo' );
            $logo_img = wp_get_attachment_image_src($custom_logo_id, 'full' );
          ?>
          <img src="<?php echo esc_url( $logo_img[0] ); ?>" alt="<?php bloginfo( 'name' ); ?>">
        <?php else : ?>
          <img src="<?php echo esc_url( get_theme_file_uri( '/assets/img/logo.png' ) ); ?>" alt="<?php bloginfo( 'name' ); ?>" onerror="this.style.display='none';">
        <?php endif; ?>
      </a>

      <!-- Menu with ID for Mobile Script Hook -->
      <?php
      wp_nav_menu( array(
        'theme_location' => 'primary',
        'container'      => false,
        'items_wrap'     => '<ul class="ifs-cinema-nav-menu" id="ifsCinemaNavMenu">%3$s</ul>',
        'fallback_cb'    => function() {
          ?>
          <ul class="ifs-cinema-nav-menu" id="ifsCinemaNavMenu">
            <li><a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="ifs-cinema-nav-link <?php echo is_front_page() ? 'ifs-cinema-active' : ''; ?>"><?php esc_html_e( 'Home', 'noorjahan-movie-theater' ); ?></a></li>
            <li><a href="<?php echo esc_url( home_url( '/about/' ) ); ?>" class="ifs-cinema-nav-link <?php echo is_page( 'about' ) ? 'ifs-cinema-active' : ''; ?>"><?php esc_html_e( 'About Us', 'noorjahan-movie-theater' ); ?></a></li>
            <li><a href="<?php echo esc_url( home_url( '/upcoming/' ) ); ?>" class="ifs-cinema-nav-link <?php echo is_page( 'upcoming' ) ? 'ifs-cinema-active' : ''; ?>"><?php esc_html_e( 'Upcoming Movies', 'noorjahan-movie-theater' ); ?></a></li>
            <li><a href="<?php echo esc_url( home_url( '/gallery/' ) ); ?>" class="ifs-cinema-nav-link <?php echo is_page( 'gallery' ) ? 'ifs-cinema-active' : ''; ?>"><?php esc_html_e( 'Gallery', 'noorjahan-movie-theater' ); ?></a></li>
            <li><a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="ifs-cinema-nav-link <?php echo is_page( 'contact' ) ? 'ifs-cinema-active' : ''; ?>"><?php esc_html_e( 'Contact Us', 'noorjahan-movie-theater' ); ?></a></li>
          </ul>
          <?php
        },
      ) );
      ?>

      <!-- Login / VIP Account Button & Mobile Toggle -->
      <div style="display: flex; align-items: center; gap: 1rem;">
        <?php
        $account_link = is_user_logged_in() ? home_url( '/account/' ) : home_url( '/custom-login/' );
        $button_label = is_user_logged_in() ? esc_html__( 'My Account', 'noorjahan-movie-theater' ) : esc_html__( 'Login / Account', 'noorjahan-movie-theater' );
        ?>
        <a href="<?php echo esc_url( $account_link ); ?>" class="ifs-cinema-btn ifs-cinema-btn-amber ifs-cinema-btn-sm">
          <svg class="ifs-cinema-svg-icon" style="width:16px; height:16px;" viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
          <span><?php echo $button_label; ?></span>
        </a>
        <button class="ifs-cinema-mobile-toggle" id="ifsMobileToggleBtn" aria-label="<?php esc_attr_e( 'Open Navigation', 'noorjahan-movie-theater' ); ?>">
          <span style="font-size: 22px; line-height: 1; color: var(--ifs-cinema-brand-amber);">☰</span>
        </button>
      </div>
    </div>
  </header>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const toggleBtn = document.getElementById('ifsMobileToggleBtn');
  const navMenu = document.getElementById('ifsCinemaNavMenu');

  if (toggleBtn && navMenu) {
    toggleBtn.addEventListener('click', function(e) {
      e.stopPropagation();
      navMenu.classList.toggle('ifs-mobile-open');
    });

    document.addEventListener('click', function(event) {
      if (!navMenu.contains(event.target) && !toggleBtn.contains(event.target)) {
        navMenu.classList.remove('ifs-mobile-open');
      }
    });

    const navLinks = navMenu.querySelectorAll('a');
    navLinks.forEach(link => {
      link.addEventListener('click', () => {
        navMenu.classList.remove('ifs-mobile-open');
      });
    });
  }
});
</script>