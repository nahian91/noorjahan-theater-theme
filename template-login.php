<?php
/**
 * Template Name: Auth Access / VIP Portal Page
 * Description: Simplified 2-Tab Authentication Portal (Sign In & Create Account).
 * - Sign In: Email + Password + Google Login
 * - Create Account: Name, Email + Optional Phone + Password + Google Login
 *
 * @package Noorjahan_Movie_Theater
 */

// If user is already logged in, redirect directly
if ( is_user_logged_in() ) {
    $redirect_target = ! empty( $_GET['redirect_to'] ) ? esc_url_raw( $_GET['redirect_to'] ) : home_url( '/account/' );
    wp_safe_redirect( $redirect_target );
    exit;
}

$login_error = '';
if ( isset( $_GET['login'] ) && $_GET['login'] === 'failed' ) {
    $login_error = __( 'Invalid email or password. Please check your credentials.', 'noorjahan-movie-theater' );
}

$redirect_to_param = isset( $_GET['redirect_to'] ) ? esc_url_raw( $_GET['redirect_to'] ) : home_url( '/account/' );

// Google OAuth Login URL handler route
$google_login_url = home_url( '/account/?bsc_auth=google_callback' );

get_header(); ?>

<style>
  :root {
    --auth-bg-dark: #070e11;
    --auth-card-bg: #0b171c;
    --auth-amber: #f59e0b;
    --auth-border: #1e293b;
    --auth-border-light: rgba(255, 255, 255, 0.08);
    --auth-text-muted: #64748b;
  }

  .ifs-auth-wrapper {
    min-height: 80vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 4rem 1rem;
    background: radial-gradient(circle at center top, #0f232b 0%, var(--auth-bg-dark) 75%);
  }

  .ifs-auth-card {
    width: 100%;
    max-width: 440px;
    background: var(--auth-card-bg);
    border: 1px solid var(--auth-border);
    border-radius: 20px;
    padding: 2.25rem 2rem;
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.7);
    color: #f8fafc;
    box-sizing: border-box;
  }

  .ifs-auth-tab-bar {
    display: grid;
    grid-template-columns: 1fr 1fr;
    background: #060d10;
    padding: 4px;
    border-radius: 12px;
    border: 1px solid var(--auth-border);
    margin-bottom: 1.75rem;
    gap: 4px;
  }

  .ifs-auth-tab-btn {
    padding: 10px;
    border-radius: 8px;
    background: transparent;
    border: none;
    color: var(--auth-text-muted);
    font-weight: 800;
    font-size: 0.88rem;
    cursor: pointer;
    transition: all 0.2s ease;
    text-align: center;
    outline: none;
  }

  .ifs-auth-tab-btn:hover {
    color: #fff;
  }

  .ifs-auth-tab-btn.active {
    background: var(--auth-amber) !important;
    color: #000 !important;
    box-shadow: 0 2px 10px rgba(245, 158, 11, 0.3);
  }

  .ifs-auth-field-group {
    margin-bottom: 1.15rem;
  }

  .ifs-auth-field-group label {
    display: block;
    font-size: 0.72rem;
    font-weight: 800;
    text-transform: uppercase;
    color: var(--auth-text-muted);
    margin-bottom: 0.35rem;
    letter-spacing: 0.04em;
  }

  .ifs-auth-input {
    width: 100%;
    padding: 0.8rem 1rem;
    background: #070e11;
    border: 1px solid var(--auth-border);
    border-radius: 8px;
    color: #fff;
    font-size: 0.92rem;
    box-sizing: border-box;
    outline: none;
    transition: border-color 0.2s ease;
  }

  .ifs-auth-input:focus {
    border-color: var(--auth-amber);
  }

  .ifs-auth-submit-btn {
    width: 100%;
    padding: 0.9rem;
    border-radius: 8px;
    font-weight: 900;
    font-size: 0.95rem;
    cursor: pointer;
    border: none;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    transition: all 0.2s ease;
    background: var(--auth-amber);
    color: #000;
    margin-top: 0.5rem;
  }

  .ifs-auth-submit-btn:hover {
    background: #d97706;
  }

  .ifs-auth-divider {
    position: relative;
    text-align: center;
    margin: 1.75rem 0 1.25rem;
  }

  .ifs-auth-divider::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 0;
    right: 0;
    height: 1px;
    background: var(--auth-border-light);
  }

  .ifs-auth-divider span {
    position: relative;
    background: var(--auth-card-bg);
    padding: 0 12px;
    font-size: 0.72rem;
    font-weight: 800;
    text-transform: uppercase;
    color: var(--auth-text-muted);
  }

  .ifs-google-btn {
    width: 100%;
    padding: 0.75rem 1rem;
    background: #ffffff;
    color: #0f172a;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    font-weight: 800;
    font-size: 0.9rem;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    cursor: pointer;
    transition: all 0.2s ease;
    text-decoration: none;
    box-sizing: border-box;
  }

  .ifs-google-btn:hover {
    background: #f1f5f9;
  }
</style>

<main id="primary" class="site-main ifs-auth-wrapper">
  <div class="ifs-auth-card">

    <!-- Header Banner -->
    <div style="text-align: center; margin-bottom: 1.5rem;">
      <span style="font-size: 0.72rem; font-weight: 800; letter-spacing: 0.1em; color: var(--auth-amber); text-transform: uppercase;">
        <?php esc_html_e( 'VIP Pass Portal', 'noorjahan-movie-theater' ); ?>
      </span>
      <h1 style="font-size: 1.75rem; font-weight: 900; color: #fff; margin: 0.25rem 0 0.4rem; letter-spacing: -0.02em;">
        <?php esc_html_e( 'Access Your Account', 'noorjahan-movie-theater' ); ?>
      </h1>
      <p style="font-size: 0.85rem; color: var(--auth-text-muted); margin: 0;">
        <?php esc_html_e( 'Manage tickets, concessions & digital passes.', 'noorjahan-movie-theater' ); ?>
      </p>
    </div>

    <!-- Error Alert -->
    <?php if ( ! empty( $login_error ) ) : ?>
      <div style="background: rgba(239, 68, 68, 0.15); border: 1px solid rgba(239, 68, 68, 0.4); color: #f87171; padding: 0.75rem 1rem; border-radius: 8px; font-size: 0.85rem; margin-bottom: 1.25rem; text-align: center;">
        <?php echo esc_html( $login_error ); ?>
      </div>
    <?php endif; ?>

    <!-- 2 MAIN TABS: SIGN IN & CREATE ACCOUNT -->
    <div class="ifs-auth-tab-bar">
      <button type="button" class="ifs-auth-tab-btn active" id="tab-btn-signin">
        <?php esc_html_e( 'Sign In', 'noorjahan-movie-theater' ); ?>
      </button>
      <button type="button" class="ifs-auth-tab-btn" id="tab-btn-create">
        <?php esc_html_e( 'Create Account', 'noorjahan-movie-theater' ); ?>
      </button>
    </div>

    <!-- TAB 1: SIGN IN -->
    <div class="ifs-auth-pane" id="auth-pane-signin" style="display: block;">
      <form method="POST" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
        <input type="hidden" name="action" value="bsc_custom_login">
        <input type="hidden" name="redirect_to" value="<?php echo esc_attr( $redirect_to_param ); ?>">
        
        <div class="ifs-auth-field-group">
          <label><?php esc_html_e( 'Email Address', 'noorjahan-movie-theater' ); ?> *</label>
          <input type="email" name="log" class="ifs-auth-input" placeholder="name@domain.com" required>
        </div>

        <div class="ifs-auth-field-group">
          <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:0.35rem;">
            <label style="margin:0;"><?php esc_html_e( 'Password', 'noorjahan-movie-theater' ); ?> *</label>
            <a href="<?php echo esc_url( wp_lostpassword_url() ); ?>" style="font-size:0.75rem; color:var(--auth-amber); text-decoration:none;">
              <?php esc_html_e( 'Forgot?', 'noorjahan-movie-theater' ); ?>
            </a>
          </div>
          <input type="password" name="pwd" class="ifs-auth-input" placeholder="••••••••" required>
        </div>

        <button type="submit" class="ifs-auth-submit-btn">
          <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor">
            <path d="M10 17v-3H3v-4h7V7l5 5-5 5zm10 4h-8v-2h8V5h-8V3h8c1.1 0 2 .9 2 2v14c0 1.1-.9 2-2 2z"/>
          </svg>
          <?php esc_html_e( 'Sign In', 'noorjahan-movie-theater' ); ?>
        </button>
      </form>

      <div class="ifs-auth-divider">
        <span><?php esc_html_e( 'Or Continue With', 'noorjahan-movie-theater' ); ?></span>
      </div>

      <a href="<?php echo esc_url( $google_login_url ); ?>" class="ifs-google-btn">
        <svg viewBox="0 0 24 24" width="18" height="18">
          <path fill="#EA4335" d="M12 5c1.6 0 3 .6 4.1 1.7l3.1-3.1C17.3 1.8 14.8 1 12 1 7.5 1 3.7 3.6 1.9 7.3l3.7 2.9C6.5 7.3 9 5 12 5z"/>
          <path fill="#4285F4" d="M23.5 12.3c0-.8-.1-1.7-.2-2.3H12v4.6h6.5c-.3 1.5-1.1 2.8-2.4 3.7l3.7 2.9c2.2-2 3.7-5 3.7-8.9z"/>
          <path fill="#FBBC05" d="M5.6 14.8c-.2-.7-.4-1.5-.4-2.3 0-.8.2-1.6.4-2.3L1.9 7.3C.7 9.7 0 12 0 14.5s.7 4.8 1.9 7.2l3.7-2.9z"/>
          <path fill="#34A853" d="M12 23.5c3.2 0 6-1.1 8-3l-3.7-2.9c-1.1.7-2.5 1.2-4.3 1.2-3 0-5.5-2.3-6.4-5.2L1.9 16.5C3.7 20.2 7.5 23.5 12 23.5z"/>
        </svg>
        <span><?php esc_html_e( 'Sign In with Google', 'noorjahan-movie-theater' ); ?></span>
      </a>
    </div>

    <!-- TAB 2: CREATE ACCOUNT -->
    <div class="ifs-auth-pane" id="auth-pane-create" style="display: none;">
      <form method="POST" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
        <input type="hidden" name="action" value="bsc_custom_register">
        <input type="hidden" name="redirect_to" value="<?php echo esc_attr( $redirect_to_param ); ?>">

        <div class="ifs-auth-field-group">
          <label><?php esc_html_e( 'Full Name', 'noorjahan-movie-theater' ); ?> *</label>
          <input type="text" name="reg_name" class="ifs-auth-input" placeholder="e.g. Mahfuzur Rahman" required>
        </div>

        <div class="ifs-auth-field-group">
          <label><?php esc_html_e( 'Email Address', 'noorjahan-movie-theater' ); ?> *</label>
          <input type="email" name="reg_email" class="ifs-auth-input" placeholder="name@domain.com" required>
        </div>

        <div class="ifs-auth-field-group">
          <label><?php esc_html_e( 'Mobile Phone (Optional)', 'noorjahan-movie-theater' ); ?></label>
          <input type="tel" name="reg_phone" class="ifs-auth-input" placeholder="+880 1700-000000">
        </div>

        <div class="ifs-auth-field-group">
          <label><?php esc_html_e( 'Create Password', 'noorjahan-movie-theater' ); ?> *</label>
          <input type="password" name="reg_pass" class="ifs-auth-input" placeholder="At least 6 characters" required>
        </div>

        <button type="submit" class="ifs-auth-submit-btn">
          <?php esc_html_e( 'Create Account', 'noorjahan-movie-theater' ); ?>
        </button>
      </form>

      <div class="ifs-auth-divider">
        <span><?php esc_html_e( 'Or Register With', 'noorjahan-movie-theater' ); ?></span>
      </div>

      <a href="<?php echo esc_url( $google_login_url ); ?>" class="ifs-google-btn">
        <svg viewBox="0 0 24 24" width="18" height="18">
          <path fill="#EA4335" d="M12 5c1.6 0 3 .6 4.1 1.7l3.1-3.1C17.3 1.8 14.8 1 12 1 7.5 1 3.7 3.6 1.9 7.3l3.7 2.9C6.5 7.3 9 5 12 5z"/>
          <path fill="#4285F4" d="M23.5 12.3c0-.8-.1-1.7-.2-2.3H12v4.6h6.5c-.3 1.5-1.1 2.8-2.4 3.7l3.7 2.9c2.2-2 3.7-5 3.7-8.9z"/>
          <path fill="#FBBC05" d="M5.6 14.8c-.2-.7-.4-1.5-.4-2.3 0-.8.2-1.6.4-2.3L1.9 7.3C.7 9.7 0 12 0 14.5s.7 4.8 1.9 7.2l3.7-2.9z"/>
          <path fill="#34A853" d="M12 23.5c3.2 0 6-1.1 8-3l-3.7-2.9c-1.1.7-2.5 1.2-4.3 1.2-3 0-5.5-2.3-6.4-5.2L1.9 16.5C3.7 20.2 7.5 23.5 12 23.5z"/>
        </svg>
        <span><?php esc_html_e( 'Sign Up with Google', 'noorjahan-movie-theater' ); ?></span>
      </a>
    </div>

  </div>
</main>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Check if user has active selected booking items stored in browser session storage
    const rawBooking = sessionStorage.getItem('bsc_current_booking');
    if (rawBooking) {
      try {
        const booking = JSON.parse(rawBooking);
        if (booking && booking.seats && booking.seats.length > 0) {
          // Build checkout URL with the saved movie, date, time, seats, and total
          const checkoutUrl = new URL('<?php echo esc_url_raw( home_url( '/checkout/' ) ); ?>', window.location.origin);
          checkoutUrl.searchParams.set('movie_id', booking.movie_id);
          checkoutUrl.searchParams.set('date', booking.screening_date);
          checkoutUrl.searchParams.set('time', booking.screening_time);
          checkoutUrl.searchParams.set('seats', booking.seats.join(','));
          checkoutUrl.searchParams.set('total', booking.grand_total);

          // Update both the Sign In and Create Account form hidden redirect_to inputs dynamically
          document.querySelectorAll('input[name="redirect_to"]').forEach(input => {
            input.value = checkoutUrl.toString();
          });
        }
      } catch (e) {
        console.error('Error parsing session storage booking info', e);
      }
    }

    // Tab switching logic
    const btnSignin = document.getElementById('tab-btn-signin');
    const btnCreate = document.getElementById('tab-btn-create');
    const paneSignin = document.getElementById('auth-pane-signin');
    const paneCreate = document.getElementById('auth-pane-create');

    if (btnSignin && btnCreate && paneSignin && paneCreate) {
      btnSignin.addEventListener('click', function() {
        paneSignin.style.display = 'block';
        paneCreate.style.display = 'none';
        btnSignin.classList.add('active');
        btnCreate.classList.remove('active');
      });

      btnCreate.addEventListener('click', function() {
        paneSignin.style.display = 'none';
        paneCreate.style.display = 'block';
        btnCreate.classList.add('active');
        btnSignin.classList.remove('active');
      });
    }
  });
</script>

<?php get_footer(); ?>