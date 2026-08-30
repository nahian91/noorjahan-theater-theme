<?php
/**
 * Template Name: Checkout Reservation Page
 * Description: Secure checkout and admission portal with guaranteed form redirection.
 *
 * @package Noorjahan_Movie_Theater
 */

get_header();

global $wpdb;
$table_movies = $wpdb->prefix . 'bsc_control_movies';

// Retrieve URL parameters sent from Single Movie page
$movie_id  = isset( $_GET['movie_id'] ) ? intval( $_GET['movie_id'] ) : 1;
$seats_str = isset( $_GET['seats'] ) ? sanitize_text_field( $_GET['seats'] ) : 'A1';
$url_total = isset( $_GET['total'] ) ? floatval( $_GET['total'] ) : 525.00;

// Fetch Movie Record from database
$movie = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$table_movies} WHERE id = %d", $movie_id ) );
if ( ! $movie ) {
    $movie = $wpdb->get_row( "SELECT * FROM {$table_movies} ORDER BY id DESC LIMIT 1" );
}

$title             = ! empty( $movie->title ) ? $movie->title : 'FOOD COMBO (EVIL DEAD BURN)';
$poster_url        = ! empty( $movie->poster_url ) ? esc_url( $movie->poster_url ) : 'https://images.unsplash.com/photo-1518709268805-4e9042af9f23?w=300&auto=format&fit=crop&q=75';
$auditorium        = ! empty( $movie->auditorium ) ? esc_html( $movie->auditorium ) : 'Auditorium 1 (23 Seats)';
$currency_symbol   = get_option( 'bsc_currency_symbol', 'BDT' );
$service_tax_pct   = floatval( get_option( 'bsc_service_charge_pct', 5.00 ) );
$enable_tax        = get_option( 'bsc_enable_tax', 'yes' );

// Calculate seat array and totals
$seats_array = array_filter( array_map( 'trim', explode( ',', $seats_str ) ) );
$seat_count  = count( $seats_array );
$subtotal    = $url_total > 0 ? $url_total / ( 1 + ( ( $enable_tax === 'yes' ? $service_tax_pct : 0 ) / 100 ) ) : 500.00;
$tax_amount  = $url_total > 0 ? ( $url_total - $subtotal ) : 0.00;
$grand_total = $url_total > 0 ? $url_total : 525.00;
?>

<style>
  :root {
    --ifs-cinema-bg-dark: #070e11;
    --ifs-cinema-card-bg: #0b171c;
    --ifs-cinema-brand-amber: #f59e0b;
    --ifs-cinema-brand-teal: #0d9488;
    --ifs-cinema-text-primary: #f8fafc;
    --ifs-cinema-text-secondary: #94a3b8;
    --ifs-cinema-text-muted: #64748b;
    --ifs-cinema-border: #1e293b;
    --ifs-cinema-border-light: rgba(255, 255, 255, 0.1);
  }

  .ifs-cinema-checkout-hero {
    background: linear-gradient(180deg, #020608 0%, var(--ifs-cinema-bg-dark) 100%);
    color: var(--ifs-cinema-text-primary);
    padding: 3rem 1.5rem 1.5rem;
    text-align: center;
  }

  .ifs-cinema-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 1rem;
    box-sizing: border-box;
  }

  .ifs-cinema-checkout-steps {
    display: flex;
    justify-content: center;
    gap: 2rem;
    margin-top: 1.5rem;
    flex-wrap: wrap;
  }

  .ifs-cinema-step-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.85rem;
    font-weight: 700;
    color: var(--ifs-cinema-text-muted);
  }

  .ifs-cinema-step-item.active {
    color: var(--ifs-cinema-brand-amber);
  }

  .ifs-cinema-step-num {
    width: 26px;
    height: 26px;
    border-radius: 50%;
    background: #1e293b;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.75rem;
    font-weight: 900;
  }

  .ifs-cinema-step-item.active .ifs-cinema-step-num {
    background: var(--ifs-cinema-brand-amber);
    color: #000;
  }

  .ifs-cinema-checkout-layout {
    display: grid;
    grid-template-columns: 1fr 380px;
    gap: 2rem;
    padding: 2.5rem 1rem 4rem;
  }

  .ifs-cinema-panel-card {
    background: var(--ifs-cinema-card-bg);
    border: 1px solid var(--ifs-cinema-border);
    border-radius: 16px;
    padding: 1.75rem;
    margin-bottom: 1.5rem;
    color: var(--ifs-cinema-text-primary);
  }

  .ifs-cinema-panel-title {
    font-size: 1.15rem;
    font-weight: 900;
    margin: 0 0 1.25rem 0;
    color: #fff;
    display: flex;
    align-items: center;
    gap: 0.6rem;
    border-bottom: 1px solid var(--ifs-cinema-border-light);
    padding-bottom: 0.75rem;
  }

  .ifs-cinema-form-grid-2 {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
  }

  .ifs-cinema-form-group {
    margin-bottom: 1rem;
  }

  .ifs-cinema-form-group label {
    display: block;
    font-size: 0.78rem;
    font-weight: 800;
    text-transform: uppercase;
    color: var(--ifs-cinema-text-secondary);
    margin-bottom: 0.35rem;
  }

  .ifs-cinema-input {
    width: 100%;
    padding: 0.7rem 0.9rem;
    background: #070e11;
    border: 1px solid var(--ifs-cinema-border);
    border-radius: 8px;
    color: #fff;
    font-size: 0.9rem;
    box-sizing: border-box;
    outline: none;
  }

  .ifs-cinema-input:focus {
    border-color: var(--ifs-cinema-brand-teal);
  }

  .ifs-cinema-payment-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1rem;
    margin-bottom: 1.5rem;
  }

  .ifs-cinema-payment-tile {
    background: rgba(255, 255, 255, 0.02);
    border: 2px solid var(--ifs-cinema-border);
    border-radius: 10px;
    padding: 1rem;
    text-align: center;
    cursor: pointer;
    transition: all 0.2s ease;
  }

  .ifs-cinema-payment-tile.active {
    border-color: var(--ifs-cinema-brand-amber);
    background: rgba(245, 158, 11, 0.08);
  }

  .ifs-cinema-payment-tile .icon-wrap {
    font-size: 1.5rem;
    margin-bottom: 0.35rem;
  }

  .ifs-cinema-payment-tile .name {
    font-size: 0.82rem;
    font-weight: 800;
    color: #fff;
  }

  .ifs-cinema-payment-fields {
    background: #070e11;
    border: 1px solid var(--ifs-cinema-border);
    border-radius: 10px;
    padding: 1.25rem;
  }

  .ifs-cinema-order-summary-card {
    background: var(--ifs-cinema-card-bg);
    border: 1px solid var(--ifs-cinema-border);
    border-radius: 16px;
    padding: 1.75rem;
    height: fit-content;
    position: sticky;
    top: 32px;
  }

  .ifs-cinema-summary-movie-head {
    display: flex;
    gap: 1rem;
    align-items: center;
    margin-bottom: 1.25rem;
    padding-bottom: 1.25rem;
    border-bottom: 1px solid var(--ifs-cinema-border-light);
  }

  .ifs-cinema-summary-movie-head img {
    width: 60px;
    height: 80px;
    object-fit: cover;
    border-radius: 6px;
    border: 1px solid var(--ifs-cinema-border);
  }

  .ifs-cinema-selected-seats-badge-wrap {
    display: flex;
    flex-wrap: wrap;
    gap: 0.35rem;
    margin: 0.5rem 0 1.25rem;
  }

  .ifs-cinema-seat-chip {
    background: var(--ifs-cinema-brand-teal);
    color: #fff;
    font-size: 0.75rem;
    font-weight: 800;
    padding: 0.2rem 0.5rem;
    border-radius: 4px;
    font-family: monospace;
  }

  .ifs-cinema-bill-rows {
    border-top: 1px solid var(--ifs-cinema-border-light);
    padding-top: 1rem;
    margin-bottom: 1.5rem;
  }

  .ifs-cinema-bill-item {
    display: flex;
    justify-content: space-between;
    font-size: 0.88rem;
    color: var(--ifs-cinema-text-secondary);
    margin-bottom: 0.6rem;
  }

  .ifs-cinema-bill-item.total {
    font-weight: 900;
    color: #fff;
    font-size: 1.1rem;
    border-top: 1px dashed var(--ifs-cinema-border-light);
    padding-top: 0.75rem;
    margin-top: 0.5rem;
  }

  .ifs-cinema-badge {
    display: inline-flex;
    align-items: center;
    padding: 0.25rem 0.65rem;
    border-radius: 9999px;
    font-size: 0.72rem;
    font-weight: 800;
    text-transform: uppercase;
  }
  .ifs-cinema-badge-amber {
    background: rgba(245, 158, 11, 0.15);
    color: var(--ifs-cinema-brand-amber);
    border: 1px solid rgba(245, 158, 11, 0.35);
  }
  .ifs-cinema-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    padding: 0.85rem 1.6rem;
    border-radius: 8px;
    font-weight: 800;
    font-size: 0.95rem;
    cursor: pointer;
    transition: all 0.2s ease;
    border: none;
    text-decoration: none;
  }
  .ifs-cinema-btn-amber {
    background: var(--ifs-cinema-brand-amber);
    color: #000;
  }
  .ifs-cinema-btn-amber:hover {
    background: #d97706;
  }

  @media (max-width: 992px) {
    .ifs-cinema-checkout-layout {
      grid-template-columns: 1fr;
    }
    .ifs-cinema-form-grid-2 {
      grid-template-columns: 1fr;
    }
  }
</style>

<main id="primary" class="site-main">

  <section class="ifs-cinema-checkout-hero">
    <div class="ifs-cinema-container">
      <span class="ifs-cinema-badge ifs-cinema-badge-amber">
        <?php esc_html_e( 'SECURE ADMISSION PORTAL', 'noorjahan-movie-theater' ); ?>
      </span>
      <h1><?php esc_html_e( 'Finalize Your Reservation', 'noorjahan-movie-theater' ); ?></h1>
      
      <div class="ifs-cinema-checkout-steps">
        <div class="ifs-cinema-step-item">
          <span class="ifs-cinema-step-num">✓</span>
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

  <!-- FORM WRAPPER SUBMITTING DIRECTLY TO ADMIN-POST.PHP -->
  <form method="POST" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
    <?php wp_nonce_field( 'bsc_frontend_checkout_verify', 'bsc_checkout_nonce' ); ?>
    <input type="hidden" name="action" value="bsc_process_frontend_checkout">
    <input type="hidden" name="movie_title" value="<?php echo esc_attr( $title ); ?>">
    <input type="hidden" name="auditorium" value="<?php echo esc_attr( $auditorium ); ?>">
    <input type="hidden" name="seats" value="<?php echo esc_attr( $seats_str ); ?>">
    <input type="hidden" name="total_amount" value="<?php echo esc_attr( $grand_total ); ?>">
    <input type="hidden" name="payment_method" id="selectedPaymentMethodInput" value="bKash">

    <section class="ifs-cinema-container ifs-cinema-checkout-layout">
      <div>
        <div class="ifs-cinema-panel-card">
          <h2 class="ifs-cinema-panel-title">👤 <?php esc_html_e( 'Guest & Contact Details', 'noorjahan-movie-theater' ); ?></h2>
          <div class="ifs-cinema-form-grid-2">
            <div class="ifs-cinema-form-group">
              <label><?php esc_html_e( 'Full Name', 'noorjahan-movie-theater' ); ?> *</label>
              <input type="text" class="ifs-cinema-input" name="customer_name" id="guestName" placeholder="<?php esc_attr_e( 'e.g. Mahfuzur Rahman', 'noorjahan-movie-theater' ); ?>" required>
            </div>
            <div class="ifs-cinema-form-group">
              <label><?php esc_html_e( 'Phone Number (for SMS Pass)', 'noorjahan-movie-theater' ); ?> *</label>
              <input type="tel" class="ifs-cinema-input" name="customer_phone" id="guestPhone" placeholder="<?php esc_attr_e( '+880 1700-000000', 'noorjahan-movie-theater' ); ?>" required>
            </div>
          </div>
          <div class="ifs-cinema-form-group" style="margin-bottom:0;">
            <label><?php esc_html_e( 'Email Address (for Digital Voucher)', 'noorjahan-movie-theater' ); ?></label>
            <input type="email" class="ifs-cinema-input" name="customer_email" id="guestEmail" placeholder="<?php esc_attr_e( 'name@domain.com', 'noorjahan-movie-theater' ); ?>">
          </div>
        </div>

        <div class="ifs-cinema-panel-card">
          <h2 class="ifs-cinema-panel-title">💳 <?php esc_html_e( 'Select Payment Method', 'noorjahan-movie-theater' ); ?></h2>
          
          <div class="ifs-cinema-payment-grid">
            <div class="ifs-cinema-payment-tile active" onclick="selectPaymentTile(this, 'bKash')">
              <div class="icon-wrap">📱</div>
              <div class="name"><?php esc_html_e( 'bKash Online', 'noorjahan-movie-theater' ); ?></div>
            </div>
            <div class="ifs-cinema-payment-tile" onclick="selectPaymentTile(this, 'Nagad')">
              <div class="icon-wrap">💳</div>
              <div class="name"><?php esc_html_e('Nagad Direct', 'noorjahan-movie-theater' ); ?></div>
            </div>
            <div class="ifs-cinema-payment-tile" onclick="selectPaymentTile(this, 'Cash')">
              <div class="icon-wrap">💵</div>
              <div class="name"><?php esc_html_e( 'Counter Cash', 'noorjahan-movie-theater' ); ?></div>
            </div>
          </div>

          <div class="ifs-cinema-payment-fields" id="paymentDetailsArea">
            <div style="font-size:0.9rem; color:#fff; font-weight:700; margin-bottom:0.5rem;" id="paymentGatewayPrompt">
              <?php esc_html_e( 'bKash Instant Gateway', 'noorjahan-movie-theater' ); ?>
            </div>
            <p style="font-size:0.85rem; color:var(--ifs-cinema-text-secondary); margin-bottom:1rem;" id="paymentGatewayDesc">
              <?php esc_html_e( 'Secure merchant checkout via bKash mobile banking wallet.', 'noorjahan-movie-theater' ); ?>
            </p>
            <div class="ifs-cinema-form-group" id="accountNoField" style="margin-bottom:0;">
              <label><?php esc_html_e( 'bKash Account Number', 'noorjahan-movie-theater' ); ?></label>
              <input type="text" class="ifs-cinema-input" placeholder="017XXXXXXXX">
            </div>
          </div>
        </div>
      </div>

      <aside class="ifs-cinema-order-summary-card">
        <h2 class="ifs-cinema-panel-title">🎟️ <?php esc_html_e( 'Booking Overview', 'noorjahan-movie-theater' ); ?></h2>

        <div class="ifs-cinema-summary-movie-head">
          <img src="<?php echo $poster_url; ?>" alt="<?php echo esc_attr( $title ); ?>">
          <div>
            <h3 style="font-size:1.1rem; color:#fff; font-weight:800; line-height:1.2; margin:0 0 4px;"><?php echo esc_html( $title ); ?></h3>
            <p style="font-size:0.78rem; color:var(--ifs-cinema-brand-amber); font-weight:700; margin:0 0 2px;"><?php echo esc_html( $auditorium ); ?></p>
            <p style="font-size:0.72rem; color:var(--ifs-cinema-text-muted); margin:0;"><?php esc_html_e( 'Today • 06:00 PM', 'noorjahan-movie-theater' ); ?></p>
          </div>
        </div>

        <div style="margin-bottom:1.25rem;">
          <span style="font-size:0.75rem; font-weight:800; text-transform:uppercase; color:var(--ifs-cinema-text-muted);">
            <?php esc_html_e( 'Selected Seats:', 'noorjahan-movie-theater' ); ?>
          </span>
          <div class="ifs-cinema-selected-seats-badge-wrap">
            <?php if ( ! empty( $seats_array ) ) : ?>
              <?php foreach ( $seats_array as $s ) : ?>
                <span class="ifs-cinema-seat-chip"><?php echo esc_html( $s ); ?></span>
              <?php endforeach; ?>
            <?php else : ?>
              <span style="color:#f59e0b; font-size:0.85rem; font-weight:700;">A1</span>
            <?php endif; ?>
          </div>
        </div>

        <div class="ifs-cinema-bill-rows">
          <div class="ifs-cinema-bill-item">
            <span><?php echo sprintf( esc_html__( 'Tickets Subtotal (%s Seats)', 'noorjahan-movie-theater' ), $seat_count ); ?></span>
            <span><?php echo esc_html( $currency_symbol ); ?> <?php echo number_format( $subtotal, 2 ); ?></span>
          </div>
          <div class="ifs-cinema-bill-item">
            <span><?php echo sprintf( esc_html__( 'VAT & Service (%s%%)', 'noorjahan-movie-theater' ), ( $enable_tax === 'yes' ? $service_tax_pct : 0 ) ); ?></span>
            <span><?php echo esc_html( $currency_symbol ); ?> <?php echo number_format( $tax_amount, 2 ); ?></span>
          </div>
          <div class="ifs-cinema-bill-item total">
            <span><?php esc_html_e( 'Total Settled Amount', 'noorjahan-movie-theater' ); ?></span>
            <span style="color:var(--ifs-cinema-brand-amber);"><?php echo esc_html( $currency_symbol ); ?> <?php echo number_format( $grand_total, 2 ); ?></span>
          </div>
        </div>

        <button type="submit" id="completeBookingBtn" class="ifs-cinema-btn ifs-cinema-btn-amber" style="width:100%; font-size:0.95rem;">
          <?php esc_html_e( 'Complete & Issue Pass', 'noorjahan-movie-theater' ); ?>
          <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor" style="margin-left:4px;">
            <path d="M5 13h11.86l-5.43 5.43 1.42 1.42L21.14 12l-8.29-7.85-1.42 1.42L16.86 11H5v2z"/>
          </svg>
        </button>
      </aside>
    </section>
  </form>

</main>

<script>
  function selectPaymentTile(tileEl, method) {
    document.querySelectorAll('.ifs-cinema-payment-tile').forEach(t => t.classList.remove('active'));
    tileEl.classList.add('active');
    document.getElementById('selectedPaymentMethodInput').value = method;

    const promptEl = document.getElementById('paymentGatewayPrompt');
    const descEl = document.getElementById('paymentGatewayDesc');
    const accField = document.getElementById('accountNoField');

    if (method === 'bKash') {
      promptEl.innerText = 'bKash Instant Gateway';
      descEl.innerText = 'Secure merchant checkout via bKash mobile banking wallet.';
      accField.style.display = 'block';
    } else if (method === 'Nagad') {
      promptEl.innerText = 'Nagad Direct Pay';
      descEl.innerText = 'Enter your Nagad wallet account number for instant settlement.';
      accField.style.display = 'block';
    } else {
      promptEl.innerText = 'Counter Cash Settlement';
      descEl.innerText = 'Pay cash directly at the box office desk upon arrival.';
      accField.style.display = 'none';
    }
  }
</script>

<?php get_footer(); ?>