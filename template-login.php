<?php
/**
 * Template Name: Auth Access / VIP Portal Page
 * Description: Multi-step email OTP verification and profile creation portal with timer and resend support.
 *
 * @package Nahian_Movie_Theater
 */

if ( is_user_logged_in() ) {
	$redirect_target = ! empty( $_GET['redirect_to'] ) ? esc_url_raw( wp_unslash( $_GET['redirect_to'] ) ) : home_url( '/account/' );
	wp_safe_redirect( $redirect_target );
	exit;
}

$auth_error    = isset( $_GET['auth_error'] ) ? sanitize_key( wp_unslash( $_GET['auth_error'] ) ) : '';
$active_tab    = isset( $_GET['tab'] ) ? sanitize_key( wp_unslash( $_GET['tab'] ) ) : 'signin';
$reg_step      = isset( $_GET['step'] ) ? sanitize_key( wp_unslash( $_GET['step'] ) ) : 'enter_email';
$pending_email = isset( $_GET['pending_email'] ) ? sanitize_email( wp_unslash( $_GET['pending_email'] ) ) : '';
$error_message = '';

if ( 'invalid_credentials' === $auth_error ) {
	$error_message = __( 'Incorrect email or password. Please try again.', 'nahian-movie-theater' );
} elseif ( 'email_exists' === $auth_error ) {
	$error_message = __( 'This email address is already registered. Please sign in instead.', 'nahian-movie-theater' );
} elseif ( 'empty_fields' === $auth_error ) {
	$error_message = __( 'Please fill in all required fields.', 'nahian-movie-theater' );
} elseif ( 'password_mismatch' === $auth_error ) {
	$error_message = __( 'Passwords do not match. Please re-enter.', 'nahian-movie-theater' );
} elseif ( 'invalid_otp' === $auth_error ) {
	$error_message = __( 'Invalid or expired OTP code. Please try again.', 'nahian-movie-theater' );
} elseif ( 'session_expired' === $auth_error ) {
	$error_message = __( 'Verification session expired. Please start over.', 'nahian-movie-theater' );
} elseif ( 'registration_failed' === $auth_error ) {
	$error_message = __( 'Could not create account. Please try a different email.', 'nahian-movie-theater' );
}

$redirect_to_param = isset( $_GET['redirect_to'] ) ? esc_url_raw( wp_unslash( $_GET['redirect_to'] ) ) : home_url( '/account/' );

get_header(); ?>

<style id="nmt-auth-portal-css">
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

	.nmt-auth-head {
		text-align: center;
		margin-bottom: 1.5rem;
	}

	.nmt-auth-heading {
		font-size: 1.75rem;
		font-weight: 900;
		color: #ffffff;
		margin: 0.25rem 0 0.4rem;
	}

	.nmt-auth-subheading {
		font-size: 0.85rem;
		color: var(--auth-text-muted);
		margin: 0;
	}

	.nmt-auth-error-alert {
		background: rgba(239, 68, 68, 0.15);
		border: 1px solid rgba(239, 68, 68, 0.4);
		color: #f87171;
		padding: 0.75rem 1rem;
		border-radius: 8px;
		font-size: 0.85rem;
		margin-bottom: 1.25rem;
		text-align: center;
		font-weight: 600;
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
		background: var(--auth-amber);
		color: #000000;
	}

	.ifs-auth-pane {
		display: none;
	}

	.ifs-auth-pane.active {
		display: block;
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
		color: #ffffff;
		font-size: 0.92rem;
		box-sizing: border-box;
		outline: none;
	}

	.ifs-auth-input.no-toggle {
		padding-right: 1rem;
	}

	.ifs-auth-input--otp {
		text-align: center;
		font-size: 1.2rem;
		letter-spacing: 0.2em;
		font-family: monospace;
	}

	.ifs-auth-input:focus {
		border-color: var(--auth-amber);
	}

	.ifs-auth-input:disabled {
		opacity: 0.5;
		cursor: not-allowed;
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

	.nmt-otp-timer-row {
		text-align: center;
		margin-bottom: 1.25rem;
		font-size: 0.8rem;
		color: var(--auth-text-muted);
	}

	.nmt-otp-timer-val {
		color: var(--auth-amber);
	}

	.nmt-otp-expired-msg {
		color: #ef4444;
		font-weight: 800;
	}

	.nmt-resend-wrap {
		text-align: center;
		margin-top: 1.25rem;
		font-size: 0.82rem;
		color: var(--auth-text-muted);
	}

	.nmt-resend-form {
		display: inline;
		margin-left: 4px;
	}

	.nmt-resend-btn {
		background: none;
		border: none;
		color: var(--auth-amber);
		font-weight: 800;
		cursor: pointer;
		padding: 0;
		text-decoration: underline;
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
		color: #000000;
		margin-top: 0.5rem;
		transition: background 0.2s, opacity 0.2s;
	}

	.ifs-auth-submit-btn:hover:not(:disabled) {
		background: #d97706;
	}

	.ifs-auth-submit-btn:disabled {
		opacity: 0.5;
		cursor: not-allowed;
	}
</style>

<main id="primary" class="site-main ifs-auth-wrapper">
	<div class="ifs-auth-card">

		<div class="nmt-auth-head">
			<h1 class="nmt-auth-heading">
				<?php
				if ( 'create' === $active_tab && 'verify_otp' === $reg_step ) {
					esc_html_e( 'Verify Email OTP', 'nahian-movie-theater' );
				} elseif ( 'create' === $active_tab && 'complete_profile' === $reg_step ) {
					esc_html_e( 'Complete Your Profile', 'nahian-movie-theater' );
				} else {
					esc_html_e( 'Access Your Account', 'nahian-movie-theater' );
				}
				?>
			</h1>
			<p class="nmt-auth-subheading">
				<?php
				if ( 'create' === $active_tab && 'verify_otp' === $reg_step ) {
					/* translators: %s: Pending email address */
					printf( esc_html__( 'Enter the 6-digit code sent to %s', 'nahian-movie-theater' ), esc_html( $pending_email ) );
				} elseif ( 'create' === $active_tab && 'complete_profile' === $reg_step ) {
					esc_html_e( 'Your email is verified. Fill in your details below.', 'nahian-movie-theater' );
				} else {
					esc_html_e( 'Manage tickets, concessions & digital passes.', 'nahian-movie-theater' );
				}
				?>
			</p>
		</div>

		<?php if ( ! empty( $error_message ) ) : ?>
			<div class="nmt-auth-error-alert" role="alert">
				<?php echo esc_html( $error_message ); ?>
			</div>
		<?php endif; ?>

		<?php if ( 'create' === $active_tab && 'verify_otp' === $reg_step ) : ?>
			<!-- STEP 2: VERIFY OTP FORM WITH COUNTDOWN TIMER & RESEND -->
			<form method="POST" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
				<input type="hidden" name="action" value="nmt_verify_email_otp">
				<input type="hidden" name="pending_email" value="<?php echo esc_attr( $pending_email ); ?>">
				<input type="hidden" name="redirect_to" value="<?php echo esc_attr( $redirect_to_param ); ?>">

				<div class="ifs-auth-field-group">
					<label><?php esc_html_e( '6-Digit Verification Code', 'nahian-movie-theater' ); ?> *</label>
					<input type="text" name="entered_otp" id="entered_otp" class="ifs-auth-input no-toggle ifs-auth-input--otp" placeholder="123456" maxlength="6" required>
				</div>

				<!-- Live Countdown Timer Display -->
				<div class="nmt-otp-timer-row">
					<span id="otpTimerText"><?php esc_html_e( 'Code expires in:', 'nahian-movie-theater' ); ?> <strong id="timerDisplay" class="nmt-otp-timer-val">05:00</strong></span>
				</div>

				<button type="submit" id="verifyOtpBtn" class="ifs-auth-submit-btn">
					<?php esc_html_e( 'Verify Code', 'nahian-movie-theater' ); ?>
				</button>
			</form>

			<!-- Resend OTP Option Form -->
			<div class="nmt-resend-wrap">
				<span><?php esc_html_e( "Didn't receive the code?", 'nahian-movie-theater' ); ?></span>
				<form method="POST" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" class="nmt-resend-form">
					<input type="hidden" name="action" value="nmt_send_email_otp">
					<input type="hidden" name="reg_email" value="<?php echo esc_attr( $pending_email ); ?>">
					<input type="hidden" name="redirect_to" value="<?php echo esc_attr( $redirect_to_param ); ?>">
					<button type="submit" class="nmt-resend-btn">
						<?php esc_html_e( 'Resend Code', 'nahian-movie-theater' ); ?>
					</button>
				</form>
			</div>

		<?php elseif ( 'create' === $active_tab && 'complete_profile' === $reg_step ) : ?>
			<!-- STEP 3: FINALIZE PROFILE FORM -->
			<form method="POST" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
				<input type="hidden" name="action" value="nmt_finalize_registration">
				<input type="hidden" name="pending_email" value="<?php echo esc_attr( $pending_email ); ?>">
				<input type="hidden" name="redirect_to" value="<?php echo esc_attr( $redirect_to_param ); ?>">

				<div class="ifs-auth-field-group">
					<label><?php esc_html_e( 'Full Name', 'nahian-movie-theater' ); ?> *</label>
					<input type="text" name="reg_name" class="ifs-auth-input no-toggle" placeholder="<?php esc_attr_e( 'e.g. Mahfuzur Rahman', 'nahian-movie-theater' ); ?>" required>
				</div>

				<div class="ifs-auth-field-group">
					<label><?php esc_html_e( 'Mobile Phone', 'nahian-movie-theater' ); ?> *</label>
					<input type="tel" name="reg_phone" class="ifs-auth-input no-toggle" placeholder="<?php esc_attr_e( '+880 1700-000000', 'nahian-movie-theater' ); ?>" required>
				</div>

				<div class="ifs-auth-field-group">
					<label><?php esc_html_e( 'Create Password', 'nahian-movie-theater' ); ?> *</label>
					<div class="ifs-auth-input-wrapper">
						<input type="password" name="reg_pass" id="reg_pass" class="ifs-auth-input" placeholder="<?php esc_attr_e( 'At least 6 characters', 'nahian-movie-theater' ); ?>" required>
						<button type="button" class="ifs-pwd-toggle-btn" data-target="reg_pass" aria-label="<?php esc_attr_e( 'Toggle password visibility', 'nahian-movie-theater' ); ?>">👁</button>
					</div>
				</div>

				<div class="ifs-auth-field-group">
					<label><?php esc_html_e( 'Retype Password', 'nahian-movie-theater' ); ?> *</label>
					<div class="ifs-auth-input-wrapper">
						<input type="password" name="reg_pass_confirm" id="reg_pass_confirm" class="ifs-auth-input" placeholder="<?php esc_attr_e( 'Re-enter password', 'nahian-movie-theater' ); ?>" required>
						<button type="button" class="ifs-pwd-toggle-btn" data-target="reg_pass_confirm" aria-label="<?php esc_attr_e( 'Toggle confirm password visibility', 'nahian-movie-theater' ); ?>">👁</button>
					</div>
				</div>

				<button type="submit" class="ifs-auth-submit-btn">
					<?php esc_html_e( 'Complete Account Creation', 'nahian-movie-theater' ); ?>
				</button>
			</form>

		<?php else : ?>

			<div class="ifs-auth-tab-bar">
				<button type="button" class="ifs-auth-tab-btn <?php echo ( 'signin' === $active_tab ) ? 'active' : ''; ?>" id="tab-btn-signin">
					<?php esc_html_e( 'Sign In', 'nahian-movie-theater' ); ?>
				</button>
				<button type="button" class="ifs-auth-tab-btn <?php echo ( 'create' === $active_tab ) ? 'active' : ''; ?>" id="tab-btn-create">
					<?php esc_html_e( 'Create Account', 'nahian-movie-theater' ); ?>
				</button>
			</div>

			<!-- TAB 1: SIGN IN -->
			<div class="ifs-auth-pane <?php echo ( 'signin' === $active_tab ) ? 'active' : ''; ?>" id="auth-pane-signin">
				<form method="POST" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
					<input type="hidden" name="action" value="nmt_custom_login">
					<input type="hidden" name="redirect_to" value="<?php echo esc_attr( $redirect_to_param ); ?>">

					<div class="ifs-auth-field-group">
						<label><?php esc_html_e( 'Email Address', 'nahian-movie-theater' ); ?> *</label>
						<input type="email" name="log" class="ifs-auth-input no-toggle" placeholder="<?php esc_attr_e( 'name@domain.com', 'nahian-movie-theater' ); ?>" required>
					</div>

					<div class="ifs-auth-field-group">
						<label><?php esc_html_e( 'Password', 'nahian-movie-theater' ); ?> *</label>
						<div class="ifs-auth-input-wrapper">
							<input type="password" name="pwd" id="signin_password" class="ifs-auth-input" placeholder="••••••••" required>
							<button type="button" class="ifs-pwd-toggle-btn" data-target="signin_password" aria-label="<?php esc_attr_e( 'Toggle password visibility', 'nahian-movie-theater' ); ?>">👁</button>
						</div>
					</div>

					<button type="submit" class="ifs-auth-submit-btn">
						<?php esc_html_e( 'Sign In', 'nahian-movie-theater' ); ?>
					</button>
				</form>
			</div>

			<!-- TAB 2: CREATE ACCOUNT (STEP 1: EMAIL ONLY) -->
			<div class="ifs-auth-pane <?php echo ( 'create' === $active_tab ) ? 'active' : ''; ?>" id="auth-pane-create">
				<form method="POST" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
					<input type="hidden" name="action" value="nmt_send_email_otp">
					<input type="hidden" name="redirect_to" value="<?php echo esc_attr( $redirect_to_param ); ?>">

					<div class="ifs-auth-field-group">
						<label><?php esc_html_e( 'Email Address', 'nahian-movie-theater' ); ?> *</label>
						<input type="email" name="reg_email" class="ifs-auth-input no-toggle" placeholder="<?php esc_attr_e( 'name@domain.com', 'nahian-movie-theater' ); ?>" required>
					</div>

					<button type="submit" class="ifs-auth-submit-btn">
						<?php esc_html_e( 'Send Verification Code', 'nahian-movie-theater' ); ?>
					</button>
				</form>
			</div>

		<?php endif; ?>

	</div>
</main>

<script id="nmt-auth-portal-js">
	(function() {
		document.addEventListener("DOMContentLoaded", function() {
			const btnSignin   = document.getElementById("tab-btn-signin");
			const btnCreate   = document.getElementById("tab-btn-create");
			const paneSignin  = document.getElementById("auth-pane-signin");
			const paneCreate  = document.getElementById("auth-pane-create");
			const expiredText = <?php echo wp_json_encode( __( 'Code has expired. Please request a new one.', 'nahian-movie-theater' ) ); ?>;
			const pendingMail = <?php echo wp_json_encode( $pending_email ); ?>;

			if (btnSignin && btnCreate && paneSignin && paneCreate) {
				btnSignin.addEventListener("click", function() {
					paneSignin.classList.add("active");
					paneCreate.classList.remove("active");
					btnSignin.classList.add("active");
					btnCreate.classList.remove("active");
				});

				btnCreate.addEventListener("click", function() {
					paneSignin.classList.remove("active");
					paneCreate.classList.add("active");
					btnCreate.classList.add("active");
					btnSignin.classList.remove("active");
				});
			}

			document.querySelectorAll(".ifs-pwd-toggle-btn").forEach(function(button) {
				button.addEventListener("click", function() {
					const targetId   = button.getAttribute("data-target");
					const inputField = document.getElementById(targetId);
					if (inputField) {
						inputField.type = inputField.type === "password" ? "text" : "password";
					}
				});
			});

			// Persistent Countdown Timer using localStorage (Survives Page Refreshes).
			const timerDisplay    = document.getElementById("timerDisplay");
			const otpTimerText    = document.getElementById("otpTimerText");
			const verifyOtpBtn    = document.getElementById("verifyOtpBtn");
			const enteredOtpInput = document.getElementById("entered_otp");

			if (timerDisplay) {
				const storageKey = "nmt_otp_expire_time_" + pendingMail;
				let expirationTime = localStorage.getItem(storageKey);
				const now = Math.floor(Date.now() / 1000);

				if (!expirationTime || now >= parseInt(expirationTime, 10)) {
					expirationTime = now + 300;
					localStorage.setItem(storageKey, expirationTime);
				}

				const countdown = setInterval(function() {
					const currentTime = Math.floor(Date.now() / 1000);
					const duration = parseInt(expirationTime, 10) - currentTime;

					if (duration <= 0) {
						clearInterval(countdown);
						localStorage.removeItem(storageKey);
						timerDisplay.textContent = "00:00";
						if (otpTimerText) {
							otpTimerText.textContent = "";
							const expiredSpan = document.createElement("span");
							expiredSpan.className = "nmt-otp-expired-msg";
							expiredSpan.textContent = expiredText;
							otpTimerText.appendChild(expiredSpan);
						}
						if (verifyOtpBtn) {
							verifyOtpBtn.disabled = true;
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
	})();
</script>

<?php get_footer(); ?>