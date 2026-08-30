<?php
/**
 * Template Name: Auth Access / VIP Portal Page
 * Description: Integrated branded login and registration portal for Big Screen Cinemas.
 *
 * @package Noorjahan_Movie_Theater
 */

// If user is already logged in, redirect them to their account dashboard
if ( is_user_logged_in() ) {
    wp_redirect( home_url( '/account/' ) );
    exit;
}

$login_error = '';
if ( isset( $_GET['login'] ) && $_GET['login'] === 'failed' ) {
    $login_error = __( 'Invalid credentials. Please check your username/email and password.', 'noorjahan-movie-theater' );
}

get_header(); ?>

<main id="primary" class="site-main">

  <!-- AUTH WORKSPACE SECTION -->
  <section class="ifs-cinema-auth-section">
    <div class="ifs-cinema-container">
      <div class="ifs-cinema-auth-stage">
        
        <!-- Left Showcase Side -->
        <div class="ifs-cinema-auth-showcase">
          <div>
            <span class="ifs-cinema-badge ifs-cinema-badge-amber">
              <?php esc_html_e( 'MEMBERSHIP ACCESS', 'noorjahan-movie-theater' ); ?>
            </span>
            <h2 style="font-size: 2.2rem; color: #fff; margin: 1rem 0 0.5rem; line-height: 1.15;">
              <?php esc_html_e( 'Unlock Premium Privileges', 'noorjahan-movie-theater' ); ?>
            </h2>
            <p style="font-size: 0.95rem;">
              <?php esc_html_e( 'Sign in to manage your digital admission passes, bypass the concession queues, and access private midnight premiere reservations.', 'noorjahan-movie-theater' ); ?>
            </p>

            <ul class="ifs-cinema-perks-list">
              <li class="ifs-cinema-perk-item">
                <div class="ifs-cinema-perk-bullet">
                  <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor">
                    <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                  </svg>
                </div>
                <span><?php esc_html_e( 'Express turnstile entry with zero line waiting', 'noorjahan-movie-theater' ); ?></span>
              </li>
              <li class="ifs-cinema-perk-item">
                <div class="ifs-cinema-perk-bullet">
                  <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor">
                    <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                  </svg>
                </div>
                <span><?php esc_html_e( 'Complimentary concession upgrades on VIP loungers', 'noorjahan-movie-theater' ); ?></span>
              </li>
              <li class="ifs-cinema-perk-item">
                <div class="ifs-cinema-perk-bullet">
                  <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor">
                    <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                  </svg>
                </div>
                <span><?php esc_html_e( '48-hour advance booking access for global blockbusters', 'noorjahan-movie-theater' ); ?></span>
              </li>
            </ul>
          </div>

          <div style="font-size: 0.8rem; color: var(--ifs-cinema-text-muted); border-top: 1px solid var(--ifs-cinema-border-subtle); padding-top: 1.25rem;">
            <?php esc_html_e( 'Grand Sylhet Hotel & Resort Multiplex • VIP Membership Desk', 'noorjahan-movie-theater' ); ?>
          </div>
        </div>

        <!-- Right Forms Side -->
        <div class="ifs-cinema-auth-workspace">
          
          <!-- Segmented Tab Toggle -->
          <div class="ifs-cinema-tab-switcher">
            <button type="button" class="ifs-cinema-tab-btn active" id="tabLoginBtn" onclick="switchAuthTab('login')"><?php esc_html_e( 'Sign In', 'noorjahan-movie-theater' ); ?></button>
            <button type="button" class="ifs-cinema-tab-btn" id="tabRegisterBtn" onclick="switchAuthTab('register')"><?php esc_html_e( 'Create Account', 'noorjahan-movie-theater' ); ?></button>
          </div>

          <?php if ( ! empty( $login_error ) ) : ?>
            <div style="background: rgba(239, 68, 68, 0.15); border: 1px solid rgba(239, 68, 68, 0.4); color: #f87171; padding: 0.75rem 1rem; border-radius: 8px; font-size: 0.85rem; margin-bottom: 1.25rem;">
              <?php echo esc_html( $login_error ); ?>
            </div>
          <?php endif; ?>

          <!-- SIGN IN FORM -->
          <div class="ifs-cinema-form-panel active" id="loginPanel">
            <div style="margin-bottom: 1.75rem;">
              <h3 style="font-size: 1.6rem; color: #fff;"><?php esc_html_e( 'Welcome Back', 'noorjahan-movie-theater' ); ?></h3>
              <p style="font-size: 0.9rem;"><?php esc_html_e( 'Enter your member credentials to access your passes.', 'noorjahan-movie-theater' ); ?></p>
            </div>

            <form method="POST" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
              <input type="hidden" name="action" value="bsc_custom_login">
              
              <div class="ifs-cinema-input-group">
                <label><?php esc_html_e( 'Email Address or Username', 'noorjahan-movie-theater' ); ?></label>
                <input type="text" class="ifs-cinema-input-field" name="log" placeholder="<?php esc_attr_e( 'name@domain.com', 'noorjahan-movie-theater' ); ?>" required>
              </div>

              <div class="ifs-cinema-input-group">
                <label><?php esc_html_e( 'Password', 'noorjahan-movie-theater' ); ?></label>
                <input type="password" class="ifs-cinema-input-field" name="pwd" placeholder="••••••••" required>
              </div>

              <div class="ifs-cinema-meta-links">
                <label style="display:flex; align-items:center; gap:0.4rem; cursor:pointer; font-size:0.82rem;">
                  <input type="checkbox" name="rememberme" value="forever" checked style="accent-color:var(--ifs-cinema-brand-amber);"> <?php esc_html_e( 'Remember me', 'noorjahan-movie-theater' ); ?>
                </label>
                <a href="<?php echo esc_url( wp_lostpassword_url() ); ?>"><?php esc_html_e( 'Forgot Password?', 'noorjahan-movie-theater' ); ?></a>
              </div>

              <button type="submit" class="ifs-cinema-btn ifs-cinema-btn-amber" style="width:100%; font-size:0.95rem;">
                <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor">
                  <path d="M10 17v-3H3v-4h7V7l5 5-5 5zm10 4h-8v-2h8V5h-8V3h8c1.1 0 2 .9 2 2v14c0 1.1-.9 2-2 2z"/>
                </svg>
                <?php esc_html_e( 'Sign In to VIP Portal', 'noorjahan-movie-theater' ); ?>
              </button>
            </form>

            <div class="ifs-cinema-social-divider">
              <span><?php esc_html_e( 'Or Authenticate With', 'noorjahan-movie-theater' ); ?></span>
            </div>

            <div class="ifs-cinema-social-stack">
              <button type="button" class="ifs-cinema-social-btn" onclick="window.location.href='<?php echo esc_url( home_url( '/account/' ) ); ?>';">
                <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor">
                  <path d="M12.48 10.92v3.28h7.84c-.24 1.84-.853 3.187-1.787 4.133-1.147 1.147-2.933 2.4-6.053 2.4-4.827 0-8.6-3.893-8.6-8.72s3.773-8.72 8.6-8.72c2.6 0 4.507 1.027 5.907 2.347l2.307-2.307C18.747 1.44 16.053 0 12.48 0 5.867 0 .307 5.387.307 12s5.56 12 12.173 12c3.573 0 6.267-1.173 8.373-3.36 2.16-2.16 2.84-5.213 2.84-7.667 0-.76-.053-1.467-.173-2.053H12.48z"/>
                </svg>
                <span><?php esc_html_e( 'Google', 'noorjahan-movie-theater' ); ?></span>
              </button>
              <button type="button" class="ifs-cinema-social-btn" onclick="window.location.href='<?php echo esc_url( home_url( '/account/' ) ); ?>';">
                <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor">
                  <path d="M18.71 19.5c-.83 1.24-1.71 2.45-3.05 2.47-1.34.03-1.77-.79-3.29-.79-1.53 0-2 .77-3.27.82-1.31.05-2.3-1.32-3.14-2.53C4.25 17 2.94 12.45 4.7 9.39c.87-1.52 2.43-2.48 4.12-2.51 1.28-.02 2.5.87 3.29.87.78 0 2.26-1.07 3.81-.91.65.03 2.47.26 3.64 1.98-.09.06-2.17 1.28-2.15 3.81.03 3.02 2.65 4.03 2.68 4.04-.03.07-.42 1.44-1.38 2.83M15.97 6.85c.66-.82 1.11-1.96.99-3.1-.96.04-2.18.65-2.88 1.47-.63.73-1.18 1.9-1.03 3.01 1.07.08 2.22-.56 2.92-1.38z"/>
                </svg>
                <span><?php esc_html_e( 'Apple ID', 'noorjahan-movie-theater' ); ?></span>
              </button>
            </div>
          </div>

          <!-- REGISTRATION FORM -->
          <div class="ifs-cinema-form-panel" id="registerPanel">
            <div style="margin-bottom: 1.75rem;">
              <h3 style="font-size: 1.6rem; color: #fff;"><?php esc_html_e( 'Join VIP Club', 'noorjahan-movie-theater' ); ?></h3>
              <p style="font-size: 0.9rem;"><?php esc_html_e( 'Create your account for instant pass storage & exclusive rates.', 'noorjahan-movie-theater' ); ?></p>
            </div>

            <form method="POST" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
              <input type="hidden" name="action" value="bsc_custom_register">

              <div class="ifs-cinema-input-group">
                <label><?php esc_html_e( 'Full Name', 'noorjahan-movie-theater' ); ?></label>
                <input type="text" class="ifs-cinema-input-field" name="reg_name" placeholder="<?php esc_attr_e( 'e.g. Mahfuzur Rahman', 'noorjahan-movie-theater' ); ?>" required>
              </div>

              <div class="ifs-cinema-input-group">
                <label><?php esc_html_e( 'Mobile Number (for SMS Pass Validation)', 'noorjahan-movie-theater' ); ?></label>
                <input type="tel" class="ifs-cinema-input-field" name="reg_phone" placeholder="<?php esc_attr_e( '+880 1700-000000', 'noorjahan-movie-theater' ); ?>" required>
              </div>

              <div class="ifs-cinema-input-group">
                <label><?php esc_html_e( 'Email Address', 'noorjahan-movie-theater' ); ?></label>
                <input type="email" class="ifs-cinema-input-field" name="reg_email" placeholder="<?php esc_attr_e( 'name@domain.com', 'noorjahan-movie-theater' ); ?>" required>
              </div>

              <div class="ifs-cinema-input-group">
                <label><?php esc_html_e( 'Create Password', 'noorjahan-movie-theater' ); ?></label>
                <input type="password" class="ifs-cinema-input-field" name="reg_pass" placeholder="<?php esc_attr_e( 'At least 8 characters', 'noorjahan-movie-theater' ); ?>" required>
              </div>

              <div style="font-size: 0.78rem; color: var(--ifs-cinema-text-muted); margin-bottom: 1.5rem; line-height: 1.5;">
                <?php esc_html_e( "By registering, you agree to Big Screen Cinemas' Theatre Rules & Digital Ticketing Terms.", 'noorjahan-movie-theater' ); ?>
              </div>

              <button type="submit" class="ifs-cinema-btn ifs-cinema-btn-amber" style="width:100%; font-size:0.95rem;">
                <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor">
                  <path d="M15 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm-9-2V7H4v3H1v2h3v3h2v-3h3v-2H6zm9 4c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                </svg>
                <?php esc_html_e( 'Register & Activate VIP Club', 'noorjahan-movie-theater' ); ?>
              </button>
            </form>
          </div>

        </div>

      </div>
    </div>
  </section>

</main>

<script>
  function switchAuthTab(tab) {
    const loginBtn = document.getElementById('tabLoginBtn');
    const registerBtn = document.getElementById('tabRegisterBtn');
    const loginPanel = document.getElementById('loginPanel');
    const registerPanel = document.getElementById('registerPanel');

    if (tab === 'login') {
      loginBtn.classList.add('active');
      registerBtn.classList.remove('active');
      loginPanel.classList.add('active');
      registerPanel.classList.remove('active');
    } else {
      registerBtn.classList.add('active');
      loginBtn.classList.remove('active');
      registerPanel.classList.add('active');
      loginPanel.classList.remove('active');
    }
  }
</script>

<?php get_footer(); ?>