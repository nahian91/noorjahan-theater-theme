<?php
/**
 * Template Name: Auth Access / VIP Portal Page
 * Description: Simplified 2-Tab Authentication Portal (Sign In & Create Account).
 *
 * @package Noorjahan_Movie_Theater
 */

if ( is_user_logged_in() ) {
    $redirect_target = ! empty( $_GET['redirect_to'] ) ? esc_url_raw( $_GET['redirect_to'] ) : home_url( '/account/' );
    wp_safe_redirect( $redirect_target );
    exit;
}

$auth_error = isset( $_GET['auth_error'] ) ? sanitize_key( $_GET['auth_error'] ) : '';
$error_message = '';

if ( 'invalid_credentials' === $auth_error ) {
    $error_message = __( 'Incorrect email or password. Please try again.', 'bcs-screen-manager' );
} elseif ( 'email_exists' === $auth_error ) {
    $error_message = __( 'This email address is already registered. Please sign in instead.', 'bcs-screen-manager' );
} elseif ( 'empty_fields' === $auth_error ) {
    $error_message = __( 'Please fill in all required fields (including matching passwords & phone).', 'bcs-screen-manager' );
} elseif ( 'password_mismatch' === $auth_error ) {
    $error_message = __( 'Passwords do not match. Please re-enter your password.', 'bcs-screen-manager' );
} elseif ( 'registration_failed' === $auth_error ) {
    $error_message = __( 'Could not create account. Please try a different email.', 'bcs-screen-manager' );
}

$active_tab = isset( $_GET['tab'] ) ? sanitize_key( $_GET['tab'] ) : 'signin';
$redirect_to_param = isset( $_GET['redirect_to'] ) ? esc_url_raw( $_GET['redirect_to'] ) : home_url( '/account/' );

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

  .ifs-auth-input-wrapper {
    position: relative;
  }

  .ifs-auth-input {
    width: 100%;
    padding: 0.8rem 2.5rem 0.8rem 1rem;
    background: #070e11;
    border: 1px solid var(--auth-border);
    border-radius: 8px;
    color: #fff;
    font-size: 0.92rem;
    box-sizing: border-box;
    outline: none;
    transition: border-color 0.2s ease;
  }

  .ifs-auth-input.no-toggle {
    padding-right: 1rem;
  }

  .ifs-auth-input:focus {
    border-color: var(--auth-amber);
  }

  .ifs-pwd-toggle-btn {
    position: absolute;
    right: 12px;
    top: 50%;
    transform: translateY(-50%);
    background: transparent;
    border: none;
    color: var(--auth-text-muted);
    cursor: pointer;
    padding: 0;
    display: flex;
    align-items: center;
  }

  .ifs-pwd-toggle-btn:hover {
    color: #fff;
  }

  /* Password Strength & Match Indicators */
  .ifs-pwd-strength-container {
    margin-top: 0.4rem;
  }

  .ifs-pwd-strength-bar {
    display: flex;
    gap: 4px;
    height: 4px;
    background: #1e293b;
    border-radius: 2px;
    overflow: hidden;
    margin-bottom: 4px;
  }

  .ifs-pwd-strength-segment {
    flex: 1;
    background: transparent;
    transition: background 0.3s ease;
  }

  .ifs-pwd-strength-text {
    font-size: 0.7rem;
    font-weight: 700;
    color: var(--auth-text-muted);
    text-transform: uppercase;
  }

  .ifs-pwd-match-hint {
    font-size: 0.7rem;
    font-weight: 700;
    margin-top: 0.3rem;
  }

  .ifs-checkbox-group {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 0.82rem;
    color: var(--auth-text-muted);
    margin-bottom: 1.25rem;
    font-weight: 600;
  }

  .ifs-checkbox-group input[type="checkbox"] {
    accent-color: var(--auth-amber);
    width: 16px;
    height: 16px;
    cursor: pointer;
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
</style>

<main id="primary" class="site-main ifs-auth-wrapper">
  <div class="ifs-auth-card">

    <div style="text-align: center; margin-bottom: 1.5rem;">
      <h1 style="font-size: 1.75rem; font-weight: 900; color: #fff; margin: 0.25rem 0 0.4rem; letter-spacing: -0.02em;">
        <?php esc_html_e( 'Access Your Account', 'bcs-screen-manager' ); ?>
      </h1>
      <p style="font-size: 0.85rem; color: var(--auth-text-muted); margin: 0;">
        <?php esc_html_e( 'Manage tickets, concessions & digital passes.', 'bcs-screen-manager' ); ?>
      </p>
    </div>

    <?php if ( ! empty( $error_message ) ) : ?>
      <div style="background: rgba(239, 68, 68, 0.15); border: 1px solid rgba(239, 68, 68, 0.4); color: #f87171; padding: 0.75rem 1rem; border-radius: 8px; font-size: 0.85rem; margin-bottom: 1.25rem; text-align: center; font-weight: 600;">
        <?php echo esc_html( $error_message ); ?>
      </div>
    <?php endif; ?>

    <div class="ifs-auth-tab-bar">
      <button type="button" class="ifs-auth-tab-btn <?php echo ( 'signin' === $active_tab ) ? 'active' : ''; ?>" id="tab-btn-signin">
        <?php esc_html_e( 'Sign In', 'bcs-screen-manager' ); ?>
      </button>
      <button type="button" class="ifs-auth-tab-btn <?php echo ( 'create' === $active_tab ) ? 'active' : ''; ?>" id="tab-btn-create">
        <?php esc_html_e( 'Create Account', 'bcs-screen-manager' ); ?>
      </button>
    </div>

    <!-- TAB 1: SIGN IN -->
    <div class="ifs-auth-pane" id="auth-pane-signin" style="display: <?php echo ( 'signin' === $active_tab ) ? 'block' : 'none'; ?>;">
      <form method="POST" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
        <input type="hidden" name="action" value="bsc_custom_login">
        <input type="hidden" name="redirect_to" value="<?php echo esc_attr( $redirect_to_param ); ?>">
        
        <div class="ifs-auth-field-group">
          <label><?php esc_html_e( 'Email Address', 'bcs-screen-manager' ); ?> *</label>
          <input type="email" name="log" class="ifs-auth-input no-toggle" placeholder="name@domain.com" required>
        </div>

        <div class="ifs-auth-field-group">
          <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:0.35rem;">
            <label style="margin:0;"><?php esc_html_e( 'Password', 'bcs-screen-manager' ); ?> *</label>
            <a href="<?php echo esc_url( wp_lostpassword_url() ); ?>" style="font-size:0.75rem; color:var(--auth-amber); text-decoration:none;">
              <?php esc_html_e( 'Forgot?', 'bcs-screen-manager' ); ?>
            </a>
          </div>
          <div class="ifs-auth-input-wrapper">
            <input type="password" name="pwd" id="signin_password" class="ifs-auth-input" placeholder="••••••••" required>
            <button type="button" class="ifs-pwd-toggle-btn" data-target="signin_password" aria-label="Toggle password visibility">
              <svg class="eye-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
            </button>
          </div>
        </div>

        <div class="ifs-checkbox-group">
          <input type="checkbox" name="rememberme" id="rememberme" value="forever">
          <label for="rememberme" style="margin:0; font-size:0.82rem; text-transform:none; color:var(--auth-text-muted); cursor:pointer;"><?php esc_html_e( 'Remember Me on this device', 'bcs-screen-manager' ); ?></label>
        </div>

        <button type="submit" class="ifs-auth-submit-btn">
          <?php esc_html_e( 'Sign In', 'bcs-screen-manager' ); ?>
        </button>
      </form>
    </div>

    <!-- TAB 2: CREATE ACCOUNT -->
    <div class="ifs-auth-pane" id="auth-pane-create" style="display: <?php echo ( 'create' === $active_tab ) ? 'block' : 'none'; ?>;">
      <form method="POST" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
        <input type="hidden" name="action" value="bsc_custom_register">
        <input type="hidden" name="redirect_to" value="<?php echo esc_attr( $redirect_to_param ); ?>">

        <div class="ifs-auth-field-group">
          <label><?php esc_html_e( 'Full Name', 'bcs-screen-manager' ); ?> *</label>
          <input type="text" name="reg_name" class="ifs-auth-input no-toggle" placeholder="e.g. Mahfuzur Rahman" required>
        </div>

        <div class="ifs-auth-field-group">
          <label><?php esc_html_e( 'Email Address', 'bcs-screen-manager' ); ?> *</label>
          <input type="email" name="reg_email" class="ifs-auth-input no-toggle" placeholder="name@domain.com" required>
        </div>

        <div class="ifs-auth-field-group">
          <label><?php esc_html_e( 'Mobile Phone', 'bcs-screen-manager' ); ?> *</label>
          <input type="tel" name="reg_phone" class="ifs-auth-input no-toggle" placeholder="+880 1700-000000" required>
        </div>

        <div class="ifs-auth-field-group">
          <label><?php esc_html_e( 'Create Password', 'bcs-screen-manager' ); ?> *</label>
          <div class="ifs-auth-input-wrapper">
            <input type="password" name="reg_pass" id="reg_pass" class="ifs-auth-input" placeholder="At least 6 characters" required>
            <button type="button" class="ifs-pwd-toggle-btn" data-target="reg_pass" aria-label="Toggle password visibility">
              <svg class="eye-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
            </button>
          </div>
          <!-- Password Strength Indicator -->
          <div class="ifs-pwd-strength-container">
            <div class="ifs-pwd-strength-bar">
              <div class="ifs-pwd-strength-segment" id="seg-1"></div>
              <div class="ifs-pwd-strength-segment" id="seg-2"></div>
              <div class="ifs-pwd-strength-segment" id="seg-3"></div>
              <div class="ifs-pwd-strength-segment" id="seg-4"></div>
            </div>
            <span class="ifs-pwd-strength-text" id="pwd-strength-label">Password Strength: Enter Password</span>
          </div>
        </div>

        <div class="ifs-auth-field-group">
          <label><?php esc_html_e( 'Retype Password', 'bcs-screen-manager' ); ?> *</label>
          <div class="ifs-auth-input-wrapper">
            <input type="password" name="reg_pass_confirm" id="reg_pass_confirm" class="ifs-auth-input" placeholder="Re-enter password" required>
            <button type="button" class="ifs-pwd-toggle-btn" data-target="reg_pass_confirm" aria-label="Toggle password visibility">
              <svg class="eye-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
            </button>
          </div>
          <div class="ifs-pwd-match-hint" id="pwd-match-label" style="display: none;"></div>
        </div>

        <button type="submit" class="ifs-auth-submit-btn" id="reg-submit-btn">
          <?php esc_html_e( 'Create Account', 'bcs-screen-manager' ); ?>
        </button>
      </form>
    </div>

  </div>
</main>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Session booking restoration
    const rawBooking = sessionStorage.getItem('bsc_current_booking');
    if (rawBooking) {
      try {
        const booking = JSON.parse(rawBooking);
        if (booking && booking.seats && booking.seats.length > 0) {
          const checkoutUrl = new URL('<?php echo esc_url_raw( home_url( '/checkout/' ) ); ?>', window.location.origin);
          checkoutUrl.searchParams.set('movie_id', booking.movie_id);
          checkoutUrl.searchParams.set('date', booking.screening_date);
          checkoutUrl.searchParams.set('time', booking.screening_time);
          checkoutUrl.searchParams.set('seats', booking.seats.join(','));
          checkoutUrl.searchParams.set('total', booking.grand_total);

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

    // Password show/hide toggle logic
    document.querySelectorAll('.ifs-pwd-toggle-btn').forEach(button => {
      button.addEventListener('click', function() {
        const targetId = this.getAttribute('data-target');
        const inputField = document.getElementById(targetId);
        if (inputField) {
          if (inputField.type === 'password') {
            inputField.type = 'text';
            this.style.color = 'var(--auth-amber)';
          } else {
            inputField.type = 'password';
            this.style.color = 'var(--auth-text-muted)';
          }
        }
      });
    });

    // Real-time password strength checker
    const regPassInput = document.getElementById('reg_pass');
    const regPassConfirm = document.getElementById('reg_pass_confirm');
    const strengthLabel = document.getElementById('pwd-strength-label');
    const matchLabel = document.getElementById('pwd-match-label');
    const segments = [
      document.getElementById('seg-1'),
      document.getElementById('seg-2'),
      document.getElementById('seg-3'),
      document.getElementById('seg-4')
    ];

    function evaluateStrength(password) {
      let score = 0;
      if (password.length >= 6) score++;
      if (password.length >= 10) score++;
      if (/[A-Z]/.test(password) && /[0-9]/.test(password)) score++;
      if (/[^A-Za-z0-9]/.test(password)) score++;
      return score;
    }

    if (regPassInput) {
      regPassInput.addEventListener('input', function() {
        const val = this.value;
        const score = evaluateStrength(val);

        // Reset segments
        segments.forEach(seg => seg.style.background = '#1e293b');

        if (val.length === 0) {
          strengthLabel.textContent = 'Password Strength: Enter Password';
          strengthLabel.style.color = 'var(--auth-text-muted)';
          return;
        }

        let colors = ['#ef4444', '#f59e0b', '#3b82f6', '#10b981'];
        let texts = ['Weak', 'Fair', 'Good', 'Strong'];

        for (let i = 0; i < score; i++) {
          segments[i].style.background = colors[score - 1];
        }
        strengthLabel.textContent = 'Password Strength: ' + texts[score - 1];
        strengthLabel.style.color = colors[score - 1];

        checkPasswordMatch();
      });
    }

    function checkPasswordMatch() {
      if (!regPassConfirm || !matchLabel) return;
      const pass1 = regPassInput.value;
      const pass2 = regPassConfirm.value;

      if (pass2.length === 0) {
        matchLabel.style.display = 'none';
        return;
      }

      matchLabel.style.display = 'block';
      if (pass1 === pass2) {
        matchLabel.textContent = '✓ Passwords match successfully';
        matchLabel.style.color = '#10b981';
      } else {
        matchLabel.textContent = '✕ Passwords do not match';
        matchLabel.style.color = '#ef4444';
      }
    }

    if (regPassConfirm) {
      regPassConfirm.addEventListener('input', checkPasswordMatch);
    }

    // Client-side form submission safeguard
    const registerForm = document.querySelector('#auth-pane-create form');
    if (registerForm) {
      registerForm.addEventListener('submit', function(e) {
        const pass = regPassInput.value;
        const confirmPass = regPassConfirm.value;
        if (pass !== confirmPass) {
          e.preventDefault();
          alert('Passwords do not match. Please re-enter.');
        }
      });
    }
  });
</script>

<?php get_footer(); ?>