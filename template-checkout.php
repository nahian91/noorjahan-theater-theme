<?php
/**
 * Template Name: Checkout Reservation Page
 *
 * @package Noorjahan_Movie_Theater
 */

get_header(); ?>

<main id="primary" class="site-main">

  <!-- CHECKOUT HERO / BREADCRUMB -->
  <section class="ifs-cinema-checkout-hero">
    <div class="ifs-cinema-container">
      <span class="ifs-cinema-badge ifs-cinema-badge-amber">
        <?php esc_html_e( 'SECURE ADMISSION PORTAL', 'noorjahan-movie-theater' ); ?>
      </span>
      <h1><?php esc_html_e( 'Finalize Your Reservation', 'noorjahan-movie-theater' ); ?></h1>
      
      <div class="ifs-cinema-checkout-steps">
        <div class="ifs-cinema-step-item">
          <span class="ifs-cinema-step-num">
            <svg viewBox="0 0 24 24" width="14" height="14" fill="currentColor">
              <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
            </svg>
          </span>
          <span><?php esc_html_e( '1. Pick Seats', 'noorjahan-movie-theater' ); ?></span>
        </div>
        <div class="ifs-cinema-step-item active">
          <span class="ifs-cinema-step-num">2</span>
          <span><?php esc_html_e( '2. Guest Details & Payment', 'noorjahan-movie-theater' ); ?></span>
        </div>
        <div class="ifs-cinema-step-item">
          <span class="ifs-cinema-step-num">3</span>
          <span><?php esc_html_e( '3. Instant Pass', 'noorjahan-movie-theater' ); ?></span>
        </div>
      </div>
    </div>
  </section>

  <!-- MAIN CHECKOUT SECTION -->
  <section class="ifs-cinema-container ifs-cinema-checkout-layout">
    <div>
      <!-- 01. GUEST INFORMATION -->
      <div class="ifs-cinema-panel-card">
        <h2 class="ifs-cinema-panel-title">
          <svg viewBox="0 0 24 24" width="22" height="22" fill="currentColor">
            <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
          </svg>
          <?php esc_html_e( 'Guest & Contact Details', 'noorjahan-movie-theater' ); ?>
        </h2>
        <div class="ifs-cinema-form-grid-2">
          <div class="ifs-cinema-form-group">
            <label><?php esc_html_e( 'Full Name', 'noorjahan-movie-theater' ); ?></label>
            <input type="text" class="ifs-cinema-input" id="guestName" placeholder="<?php esc_attr_e( 'e.g. Mahfuzur Rahman', 'noorjahan-movie-theater' ); ?>" required>
          </div>
          <div class="ifs-cinema-form-group">
            <label><?php esc_html_e( 'Phone Number (for SMS Pass)', 'noorjahan-movie-theater' ); ?></label>
            <input type="tel" class="ifs-cinema-input" id="guestPhone" placeholder="<?php esc_attr_e( '+880 1700-000000', 'noorjahan-movie-theater' ); ?>" required>
          </div>
        </div>
        <div class="ifs-cinema-form-group">
          <label><?php esc_html_e( 'Email Address (for Digital Voucher)', 'noorjahan-movie-theater' ); ?></label>
          <input type="email" class="ifs-cinema-input" id="guestEmail" placeholder="<?php esc_attr_e( 'name@domain.com', 'noorjahan-movie-theater' ); ?>" required>
        </div>
      </div>

      <!-- 02. PAYMENT METHOD SELECTION -->
      <div class="ifs-cinema-panel-card">
        <h2 class="ifs-cinema-panel-title">
          <svg viewBox="0 0 24 24" width="22" height="22" fill="currentColor">
            <path d="M20 4H4c-1.11 0-1.99.89-1.99 2L2 18c0 1.11.89 2 2 2h16c1.11 0 2-.89 2-2V6c0-1.11-.89-2-2-2zm0 14H4v-6h16v6zm0-10H4V6h16v2z"/>
          </svg>
          <?php esc_html_e( 'Select Payment Method', 'noorjahan-movie-theater' ); ?>
        </h2>
        
        <div class="ifs-cinema-payment-grid">
          <div class="ifs-cinema-payment-tile active" onclick="selectPaymentTile(this, 'bKash')">
            <div class="icon-wrap">
              <svg viewBox="0 0 24 24" width="28" height="28" fill="currentColor">
                <path d="M17 1.01L7 1c-1.1 0-2 .9-2 2v18c0 1.1.9 2 2 2h10c1.1 0 2-.9 2-2V3c0-1.1-.9-1.99-2-1.99zM17 19H7V5h10v14z"/>
              </svg>
            </div>
            <div class="name"><?php esc_html_e( 'bKash Online', 'noorjahan-movie-theater' ); ?></div>
          </div>
          <div class="ifs-cinema-payment-tile" onclick="selectPaymentTile(this, 'Nagad')">
            <div class="icon-wrap">
              <svg viewBox="0 0 24 24" width="28" height="28" fill="currentColor">
                <path d="M7 2v11h3v9l7-12h-4l4-8z"/>
              </svg>
            </div>
            <div class="name"><?php esc_html_e( 'Nagad Direct', 'noorjahan-movie-theater' ); ?></div>
          </div>
          <div class="ifs-cinema-payment-tile" onclick="selectPaymentTile(this, 'Card')">
            <div class="icon-wrap">
              <svg viewBox="0 0 24 24" width="28" height="28" fill="currentColor">
                <path d="M20 4H4c-1.11 0-1.99.89-1.99 2L2 18c0 1.11.89 2 2 2h16c1.11 0 2-.89 2-2V6c0-1.11-.89-2-2-2zm0 14H4v-6h16v6zm0-10H4V6h16v2z"/>
              </svg>
            </div>
            <div class="name"><?php esc_html_e( 'Debit / Credit Card', 'noorjahan-movie-theater' ); ?></div>
          </div>
        </div>

        <!-- Dynamic Payment Gateway Prompt -->
        <div class="ifs-cinema-payment-fields" id="paymentDetailsArea">
          <div style="font-size:0.9rem; color:#fff; font-weight:700; margin-bottom:0.5rem;" id="paymentGatewayPrompt">
            <?php esc_html_e( 'bKash Instant Gateway', 'noorjahan-movie-theater' ); ?>
          </div>
          <p style="font-size:0.85rem; color:var(--ifs-cinema-text-secondary); margin-bottom:1rem;" id="paymentGatewayDesc">
            <?php esc_html_e( 'You will be redirected to the secure bKash PGW to verify your wallet and complete payment instantly.', 'noorjahan-movie-theater' ); ?>
          </p>
          <div class="ifs-cinema-form-group" id="accountNoField">
            <label><?php esc_html_e( 'bKash Account Number', 'noorjahan-movie-theater' ); ?></label>
            <input type="text" class="ifs-cinema-input" placeholder="017XXXXXXXX">
          </div>
        </div>
      </div>
    </div>

    <!-- RIGHT STICKY ORDER SUMMARY -->
    <aside class="ifs-cinema-order-summary-card">
      <h2 class="ifs-cinema-panel-title" style="border-bottom:none; margin-bottom:1.25rem;">
        <svg viewBox="0 0 24 24" width="22" height="22" fill="currentColor">
          <path d="M22 10V6c0-1.1-.9-2-2-2H4c-1.1 0-1.99.9-1.99 2v4c1.1 0 1.99.9 1.99 2s-.89 2-2 2v4c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2v-4c-1.1 0-2-.9-2-2s.9-2 2-2zm-9 7.5h-2v-2h2v2zm0-4.5h-2v-2h2v2zm0-4.5h-2v-2h2v2z"/>
        </svg>
        <?php esc_html_e( 'Booking Overview', 'noorjahan-movie-theater' ); ?>
      </h2>

      <div class="ifs-cinema-summary-movie-head">
        <img src="<?php echo esc_url( get_theme_file_uri( '/assets/img/movie-thumb.jpg' ) ); ?>" 
             alt="<?php esc_attr_e( 'Movie Poster', 'noorjahan-movie-theater' ); ?>"
             onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1518709268805-4e9042af9f23?w=300&auto=format&fit=crop&q=75';">
        <div>
          <h3 style="font-size:1.15rem; color:#fff; font-weight:800; line-height:1.2;"><?php esc_html_e( 'FOOD COMBO (EVIL DEAD BURN)', 'noorjahan-movie-theater' ); ?></h3>
          <p style="font-size:0.8rem; color:var(--ifs-cinema-brand-amber); font-weight:700; margin-top:4px;"><?php esc_html_e( 'Auditorium 1 • IMAX Laser', 'noorjahan-movie-theater' ); ?></p>
          <p style="font-size:0.75rem; color:var(--ifs-cinema-text-muted); margin-top:2px;"><?php esc_html_e( 'Today • 06:00 PM', 'noorjahan-movie-theater' ); ?></p>
        </div>
      </div>

      <div>
        <span style="font-size:0.78rem; font-weight:800; text-transform:uppercase; color:var(--ifs-cinema-text-muted);">
          <?php esc_html_e( 'Selected Seats:', 'noorjahan-movie-theater' ); ?>
        </span>
        <div class="ifs-cinema-selected-seats-badge-wrap">
          <span class="ifs-cinema-seat-chip">F4 (VIP)</span>
          <span class="ifs-cinema-seat-chip">F5 (VIP)</span>
        </div>
      </div>

      <div class="ifs-cinema-bill-rows">
        <div class="ifs-cinema-bill-item">
          <span><?php esc_html_e( 'Tickets Subtotal (2 Seats)', 'noorjahan-movie-theater' ); ?></span>
          <span><?php esc_html_e( 'BDT 1,000.00', 'noorjahan-movie-theater' ); ?></span>
        </div>
        <div class="ifs-cinema-bill-item">
          <span><?php esc_html_e( 'Concessions Combo Package', 'noorjahan-movie-theater' ); ?></span>
          <span style="color:#10b981; font-weight:700;"><?php esc_html_e( 'INCLUDED FREE', 'noorjahan-movie-theater' ); ?></span>
        </div>
        <div class="ifs-cinema-bill-item">
          <span><?php esc_html_e( 'VAT & Entertainment Tax (5%)', 'noorjahan-movie-theater' ); ?></span>
          <span><?php esc_html_e( 'BDT 50.00', 'noorjahan-movie-theater' ); ?></span>
        </div>
        <div class="ifs-cinema-bill-item total">
          <span><?php esc_html_e( 'Total Settled Amount', 'noorjahan-movie-theater' ); ?></span>
          <span style="color:var(--ifs-cinema-brand-amber);"><?php esc_html_e( 'BDT 1,050.00', 'noorjahan-movie-theater' ); ?></span>
        </div>
      </div>

      <button class="ifs-cinema-btn ifs-cinema-btn-amber" style="width:100%; font-size:0.95rem;" onclick="processFinalPayment()">
        <?php esc_html_e( 'Complete & Issue Pass', 'noorjahan-movie-theater' ); ?>
        <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor">
          <path d="M5 13h11.86l-5.43 5.43 1.42 1.42L21.14 12l-8.29-7.85-1.42 1.42L16.86 11H5v2z"/>
        </svg>
      </button>
    </aside>
  </section>

  <!-- INSTANT PASS MODAL VOUCHER -->
  <div class="ifs-cinema-modal-overlay" id="checkoutPassModal">
    <div class="ifs-cinema-ticket-card">
      <button style="position:absolute; top:12px; right:12px; background:var(--ifs-cinema-bg-card); color:#fff; border:none; width:36px; height:36px; border-radius:50%; cursor:pointer; display:flex; align-items:center; justify-content:center;" onclick="closePassModal()" aria-label="<?php esc_attr_e( 'Close Modal', 'noorjahan-movie-theater' ); ?>">
        <svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor">
          <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/>
        </svg>
      </button>
      <div class="ifs-cinema-ticket-header">
        <h3 style="font-size:1.35rem; font-weight:900; letter-spacing:1px;"><?php esc_html_e( 'BIG SCREEN PASS', 'noorjahan-movie-theater' ); ?></h3>
        <p style="font-size:0.75rem; text-transform:uppercase; font-weight:700;"><?php esc_html_e( 'Confirmed Digital Admission', 'noorjahan-movie-theater' ); ?></p>
      </div>
      <div class="ifs-cinema-ticket-body">
        <div class="ifs-cinema-ticket-grid">
          <div>
            <div style="font-size:0.72rem; color:var(--ifs-cinema-text-muted); font-weight:700; text-transform:uppercase;"><?php esc_html_e( 'Guest', 'noorjahan-movie-theater' ); ?></div>
            <strong id="passHolderName" style="font-size:0.95rem;"><?php esc_html_e( 'Mahfuzur Rahman', 'noorjahan-movie-theater' ); ?></strong>
          </div>
          <div>
            <div style="font-size:0.72rem; color:var(--ifs-cinema-text-muted); font-weight:700; text-transform:uppercase;"><?php esc_html_e( 'Auditorium', 'noorjahan-movie-theater' ); ?></div>
            <strong style="font-size:0.95rem;"><?php esc_html_e( 'Auditorium 1', 'noorjahan-movie-theater' ); ?></strong>
          </div>
          <div>
            <div style="font-size:0.72rem; color:var(--ifs-cinema-text-muted); font-weight:700; text-transform:uppercase;"><?php esc_html_e( 'Seats', 'noorjahan-movie-theater' ); ?></div>
            <strong style="color:var(--ifs-cinema-brand-amber); font-family:var(--ifs-cinema-font-mono);">F4, F5 (VIP)</strong>
          </div>
          <div>
            <div style="font-size:0.72rem; color:var(--ifs-cinema-text-muted); font-weight:700; text-transform:uppercase;"><?php esc_html_e( 'Method & Paid', 'noorjahan-movie-theater' ); ?></div>
            <strong id="passPaymentMethod" style="font-family:var(--ifs-cinema-font-mono);"><?php esc_html_e( 'bKash • BDT 1,050.00', 'noorjahan-movie-theater' ); ?></strong>
          </div>
        </div>

        <div class="ifs-cinema-ticket-qr">
          <div class="ifs-cinema-mock-qr"></div>
          <span style="font-size:0.75rem; font-family:var(--ifs-cinema-font-mono); color:var(--ifs-cinema-brand-amber);" id="passTokenCode">TOKEN: #BSC-9482-TX</span>
        </div>
      </div>
    </div>
  </div>

</main>

<?php get_footer(); ?>