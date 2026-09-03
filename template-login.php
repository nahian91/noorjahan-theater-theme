<?php
/**
 * Template Name: Auth Access / VIP Portal Page
 * Description: Multi-step email OTP verification and profile creation portal with timer and resend support.
 *
 * @package Noorjahan_Movie_Theater
 */

if ( is_user_logged_in() ) {
    $redirect_target = ! empty( $_GET['redirect_to'] ) ? esc_url_raw( $_GET['redirect_to'] ) : home_url( '/account/' );
    wp_safe_redirect( $redirect_target );
    exit;
}

$auth_error    = isset( $_GET['auth_error'] ) ? sanitize_key( $_GET['auth_error'] ) : '';
$active_tab    = isset( $_GET['tab'] ) ? sanitize_key( $_GET['tab'] ) : 'signin';
$reg_step      = isset( $_GET['step'] ) ? sanitize_key( $_GET['step'] ) : 'enter_email';
$pending_email = isset( $_GET['pending_email'] ) ? sanitize_email( wp_unslash( $_GET['pending_email'] ) ) : '';
$error_message = '';

if ( 'invalid_credentials' === $auth_error ) {
    $error_message = __( 'Incorrect email or password. Please try again.', 'bcs-screen-manager' );
} elseif ( 'email_exists' === $auth_error ) {
    $error_message = __( 'This email address is already registered. Please sign in instead.', 'bcs-screen-manager' );
} elseif ( 'empty_fields' === $auth_error ) {
    $error_message = __( 'Please fill in all required fields.', 'bcs-screen-manager' );
} elseif ( 'password_mismatch' === $auth_error ) {
    $error_message = __( 'Passwords do not match. Please re-enter.', 'bcs-screen-manager' );
} elseif ( 'invalid_otp' === $auth_error ) {
    $error_message = __( 'Invalid or expired OTP code. Please try again.', 'bcs-screen-manager' );
} elseif ( 'session_expired' === $auth_error ) {
    $error_message = __( 'Verification session expired. Please start over.', 'bcs-screen-manager' );
} elseif ( 'registration_failed' === $auth_error ) {
    $error_message = __( 'Could not create account. Please try a different email.', 'bcs-screen-manager' );
}

$redirect_to_param = isset( $_GET['redirect_to'] ) ? esc_url_raw( $_GET['redirect_to'] ) : home_url( '/account/' );

get_header(); ?>

<style>
  :root {
    --auth-bg-dark: #070e11;
    --auth-card-bg: #0b171c;
    --auth-amber: #f59e0b;
    --auth-border: #1e293b;
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
    text-align: center;
    outline: none;
  }

  .ifs-auth-tab-btn.active {
    background: var(--auth-amber) !important;
    color: #000 !important;
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
  }

  .ifs-auth-submit-btn {
    width: 100%;
    padding: 0.9rem;
    border-radius: 8px;
    font-weight: 900;
    font-size: 0.95rem;
    cursor: pointer;
    border: none;
    background: var(--auth-amber);
    color: #000;
    margin-top: 0.5rem;
    transition: background 0.2s;
  }

  .ifs-auth-submit-btn:hover {
    background: #d97706;
  }
</style>

<main id="primary" class="site-main ifs-auth-wrapper">
  <div class="ifs-auth-card">

    <div style="text-align: center; margin-bottom: 1.5rem;">
      <h1 style="font-size: 1.75rem; font-weight: 900; color: #fff; margin: 0.25rem 0 0.4rem;">
        <?php 
        if ( 'create' === $active_tab && 'verify_otp' === $reg_step ) {
            esc_html_e( 'Verify Email OTP', 'bcs-screen-manager' );
        } elseif ( 'create' === $active_tab && 'complete_profile' === $reg_step ) {
            esc_html_e( 'Complete Your Profile', 'bcs-screen-manager' );
        } else {
            esc_html_e( 'Access Your Account', 'bcs-screen-manager' );
        }
        ?>
      </h1>
      <p style="font-size: 0.85rem; color: var(--auth-text-muted); margin: 0;">
        <?php 
        if ( 'create' === $active_tab && 'verify_otp' === $reg_step ) {
            printf( esc_html__( 'Enter the 6-digit code sent to %s', 'bcs-screen-manager' ), esc_html( $pending_email ) );
        } elseif ( 'create' === $active_tab && 'complete_profile' === $reg_step ) {
            esc_html_e( 'Your email is verified. Fill in your details below.', 'bcs-screen-manager' );
        } else {
            esc_html_e( 'Manage tickets, concessions & digital passes.', 'bcs-screen-manager' );
        }
        ?>
      </p>
    </div>

    <?php if ( ! empty( $error_message ) ) : ?>
      <div style="background: rgba(239, 68, 68, 0.15); border: 1px solid rgba(239, 68, 68, 0.4); color: #f87171; padding: 0.75rem 1rem; border-radius: 8px; font-size: 0.85rem; margin-bottom: 1.25rem; text-align: center; font-weight: 600;">
        <?php echo esc_html( $error_message ); ?>
      </div>
    <?php endif; ?>

    <?php if ( 'create' === $active_tab && 'verify_otp' === $reg_step ) : ?>
      <!-- STEP 2: VERIFY OTP FORM WITH COUNTDOWN TIMER & RESEND -->
      <form method="POST" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
        <input type="hidden" name="action" value="bsc_verify_email_otp">
        <input type="hidden" name="pending_email" value="<?php echo esc_attr( $pending_email ); ?>">
        <input type="hidden" name="redirect_to" value="<?php echo esc_attr( $redirect_to_param ); ?>">

        <div class="ifs-auth-field-group">
          <label><?php esc_html_e( '6-Digit Verification Code', 'bcs-screen-manager' ); ?> *</label>
          <input type="text" name="entered_otp" id="entered_otp" class="ifs-auth-input no-toggle" placeholder="123456" maxlength="6" style="text-align: center; font-size: 1.2rem; letter-spacing: 0.2em; font-family: monospace;" required>
        </div>

        <!-- Live Countdown Timer Display -->
        <div style="text-align: center; margin-bottom: 1.25rem; font-size: 0.8rem; color: var(--auth-text-muted);">
          <span id="otpTimerText"><?php esc_html_e( 'Code expires in:', 'bcs-screen-manager' ); ?> <strong id="timerDisplay" style="color: var(--auth-amber);">05:00</strong></span>
        </div>

        <button type="submit" id="verifyOtpBtn" class="ifs-auth-submit-btn">
          <?php esc_html_e( 'Verify Code', 'bcs-screen-manager' ); ?>
        </button>
      </form>

      <!-- Resend OTP Option Form -->
      <div style="text-align: center; margin-top: 1.25rem; font-size: 0.82rem; color: var(--auth-text-muted);">
        <span><?php esc_html_e( "Didn't receive the code?", 'bcs-screen-manager' ); ?></span>
        <form method="POST" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" style="display:inline; margin-left: 4px;">
          <input type="hidden" name="action" value="bsc_send_email_otp">
          <input type="hidden" name="reg_email" value="<?php echo esc_attr( $pending_email ); ?>">
          <input type="hidden" name="redirect_to" value="<?php echo esc_attr( $redirect_to_param ); ?>">
          <button type="submit" style="background:none; border:none; color:var(--auth-amber); font-weight:800; cursor:pointer; padding:0; text-decoration:underline;">
            <?php esc_html_e( 'Resend Code', 'bcs-screen-manager' ); ?>
          </button>
        </form>
      </div>

    <?php elseif ( 'create' === $active_tab && 'complete_profile' === $reg_step ) : ?>
      <!-- STEP 3: FINALIZE PROFILE FORM -->
      <form method="POST" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
        <input type="hidden" name="action" value="bsc_finalize_registration">
        <input type="hidden" name="pending_email" value="<?php echo esc_attr( $pending_email ); ?>">
        <input type="hidden" name="redirect_to" value="<?php echo esc_attr( $redirect_to_param ); ?>">

        <div class="ifs-auth-field-group">
          <label><?php esc_html_e( 'Full Name', 'bcs-screen-manager' ); ?> *</label>
          <input type="text" name="reg_name" class="ifs-auth-input no-toggle" placeholder="e.g. Mahfuzur Rahman" required>
        </div>

        <div class="ifs-auth-field-group">
          <label><?php esc_html_e( 'Mobile Phone', 'bcs-screen-manager' ); ?> *</label>
          <input type="tel" name="reg_phone" class="ifs-auth-input no-toggle" placeholder="+880 1700-000000" required>
        </div>

        <div class="ifs-auth-field-group">
          <label><?php esc_html_e( 'Create Password', 'bcs-screen-manager' ); ?> *</label>
          <div class="ifs-auth-input-wrapper">
            <input type="password" name="reg_pass" id="reg_pass" class="ifs-auth-input" placeholder="At least 6 characters" required>
            <button type="button" class="ifs-pwd-toggle-btn" data-target="reg_pass">👁</button>
          </div>
        </div>

        <div class="ifs-auth-field-group">
          <label><?php esc_html_e( 'Retype Password', 'bcs-screen-manager' ); ?> *</label>
          <div class="ifs-auth-input-wrapper">
            <input type="password" name="reg_pass_confirm" id="reg_pass_confirm" class="ifs-auth-input" placeholder="Re-enter password" required>
            <button type="button" class="ifs-pwd-toggle-btn" data-target="reg_pass_confirm">👁</button>
          </div>
        </div>

        <button type="submit" class="ifs-auth-submit-btn">
          <?php esc_html_e( 'Complete Account Creation', 'bcs-screen-manager' ); ?>
        </button>
      </form>

    <?php else : ?>

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
            <label><?php esc_html_e( 'Password', 'bcs-screen-manager' ); ?> *</label>
            <div class="ifs-auth-input-wrapper">
              <input type="password" name="pwd" id="signin_password" class="ifs-auth-input" placeholder="••••••••" required>
              <button type="button" class="ifs-pwd-toggle-btn" data-target="signin_password">👁</button>
            </div>
          </div>

          <button type="submit" class="ifs-auth-submit-btn">
            <?php esc_html_e( 'Sign In', 'bcs-screen-manager' ); ?>
          </button>
        </form>
      </div>

      <!-- TAB 2: CREATE ACCOUNT (STEP 1: EMAIL ONLY) -->
      <div class="ifs-auth-pane" id="auth-pane-create" style="display: <?php echo ( 'create' === $active_tab ) ? 'block' : 'none'; ?>;">
        <form method="POST" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
          <input type="hidden" name="action" value="bsc_send_email_otp">
          <input type="hidden" name="redirect_to" value="<?php echo esc_attr( $redirect_to_param ); ?>">

          <div class="ifs-auth-field-group">
            <label><?php esc_html_e( 'Email Address', 'bcs-screen-manager' ); ?> *</label>
            <input type="email" name="reg_email" class="ifs-auth-input no-toggle" placeholder="name@domain.com" required>
          </div>

          <button type="submit" class="ifs-auth-submit-btn">
            <?php esc_html_e( 'Send Verification Code', 'bcs-screen-manager' ); ?>
          </button>
        </form>
      </div>

    <?php endif; ?>

  </div>
</main>

<script>
  document.addEventListener('DOMContentLoaded', function() {
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

    document.querySelectorAll('.ifs-pwd-toggle-btn').forEach(button => {
      button.addEventListener('click', function() {
        const targetId = this.getAttribute('data-target');
        const inputField = document.getElementById(targetId);
        if (inputField) {
          inputField.type = inputField.type === 'password' ? 'text' : 'password';
        }
      });
    });

    // Persistent Countdown Timer using localStorage (Survives Page Refreshes)
    const timerDisplay = document.getElementById('timerDisplay');
    const otpTimerText = document.getElementById('otpTimerText');
    const verifyOtpBtn = document.getElementById('verifyOtpBtn');
    const enteredOtpInput = document.getElementById('entered_otp');

    if (timerDisplay) {
      const storageKey = 'bsc_otp_expire_time_' + "<?php echo esc_js( $pending_email ); ?>";
      let expirationTime = localStorage.getItem(storageKey);
      const now = Math.floor(Date.now() / 1000);

      // If no timer exists or it has already expired, set a new 5-minute (300 seconds) target
      if (!expirationTime || now >= parseInt(expirationTime, 10)) {
        expirationTime = now + 300;
        localStorage.setItem(storageKey, expirationTime);
      }

      const countdown = setInterval(function () {
        const currentTime = Math.floor(Date.now() / 1000);
        let duration = parseInt(expirationTime, 10) - currentTime;

        if (duration <= 0) {
          clearInterval(countdown);
          localStorage.removeItem(storageKey);
          timerDisplay.textContent = "00:00";
          if (otpTimerText) {
            otpTimerText.innerHTML = '<span style="color: #ef4444; font-weight: 800;">Code has expired. Please request a new one.</span>';
          }
          if (verifyOtpBtn) {
            verifyOtpBtn.disabled = true;
            verifyOtpBtn.style.opacity = '0.5';
            verifyOtpBtn.style.cursor = 'not-allowed';
          }
          if (enteredOtpInput) {
            enteredOtpInput.disabled = true;
          }
        } else {
          let minutes = Math.floor(duration / 60);
          let seconds = duration % 60;

          minutes = minutes < 10 ? "0" + minutes : minutes;
          seconds = seconds < 10 ? "0" + seconds : seconds;

          timerDisplay.textContent = minutes + ":" + seconds;
        }
      }, 1000);
    }
  });
</script>

<?php get_footer(); ?>