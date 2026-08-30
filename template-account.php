<?php
/**
 * Template Name: Account Dashboard Page
 * Description: Dynamic user account dashboard displaying live active passes, booking history, and member metrics.
 *
 * @package Noorjahan_Movie_Theater
 */

get_header();

global $wpdb;
$table_bookings = $wpdb->prefix . 'bsc_bookings';

// For demonstration, retrieve user context (or fallback to recent guest booking details)
$customer_phone = isset( $_GET['phone'] ) ? sanitize_text_field( $_GET['phone'] ) : '';
$customer_email = isset( $_GET['email'] ) ? sanitize_email( $_GET['email'] ) : '';

if ( ! empty( $customer_phone ) || ! empty( $customer_email ) ) {
    $user_bookings = $wpdb->get_results( $wpdb->prepare(
        "SELECT * FROM {$table_bookings} WHERE customer_phone = %s OR customer_email = %s ORDER BY id DESC",
        $customer_phone,
        $customer_email
    ) );
} else {
    // Fetch latest bookings for dashboard display
    $user_bookings = $wpdb->get_results( "SELECT * FROM {$table_bookings} ORDER BY id DESC LIMIT 5" );
}

$active_passes_count = count( $user_bookings );
$total_reward_points = $active_passes_count * 150 + 850;
?>

<main id="primary" class="site-main">

  <!-- MEMBER DASHBOARD HEADER & KPIS -->
  <section class="ifs-cinema-dashboard-header">
    <div class="ifs-cinema-container">
      <div class="ifs-cinema-user-hero">
        <div class="ifs-cinema-user-meta">
          <img src="<?php echo esc_url( get_theme_file_uri( '/assets/img/avatar.jpg' ) ); ?>" 
               alt="<?php esc_attr_e( 'Member Avatar', 'noorjahan-movie-theater' ); ?>" 
               class="ifs-cinema-user-avatar"
               onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=300&auto=format&fit=crop&q=80';">
          <div class="ifs-cinema-user-title">
            <span class="ifs-cinema-badge ifs-cinema-badge-amber" style="margin-bottom:0.4rem;">
              <?php esc_html_e( 'VIP CLUB MEMBER', 'noorjahan-movie-theater' ); ?>
            </span>
            <h1><?php esc_html_e( 'Mahfuzur Rahman', 'noorjahan-movie-theater' ); ?></h1>
            <p><?php esc_html_e( 'Member ID: #BSC-88492 • Joined June 2026', 'noorjahan-movie-theater' ); ?></p>
          </div>
        </div>

        <a href="<?php echo esc_url( home_url( '/#movies' ) ); ?>" class="ifs-cinema-btn ifs-cinema-btn-amber">
          <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor">
            <path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/>
          </svg>
          <?php esc_html_e( 'Book New Movie', 'noorjahan-movie-theater' ); ?>
        </a>
      </div>

      <div class="ifs-cinema-kpi-deck">
        <div class="ifs-cinema-kpi-card">
          <div class="ifs-cinema-kpi-icon">
            <svg viewBox="0 0 24 24" width="26" height="26" fill="currentColor">
              <path d="M22 10V6c0-1.1-.9-2-2-2H4c-1.1 0-1.99.9-1.99 2v4c1.1 0 1.99.9 1.99 2s-.89 2-2 2v4c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2v-4c-1.1 0-2-.9-2-2s.9-2 2-2zm-9 7.5h-2v-2h2v2zm0-4.5h-2v-2h2v2zm0-4.5h-2v-2h2v2z"/>
            </svg>
          </div>
          <div>
            <div class="ifs-cinema-kpi-val"><?php echo esc_html( $active_passes_count ); ?></div>
            <div class="ifs-cinema-kpi-lbl"><?php esc_html_e( 'Active Digital Passes', 'noorjahan-movie-theater' ); ?></div>
          </div>
        </div>
        <div class="ifs-cinema-kpi-card">
          <div class="ifs-cinema-kpi-icon">
            <svg viewBox="0 0 24 24" width="26" height="26" fill="currentColor">
              <path d="M7 2h10l-1 5H8L7 2zm1 6h8l1.5 14H6.5L8 8zm4 3a2 2 0 1 0 0 4 2 2 0 0 0 0-4z"/>
            </svg>
          </div>
          <div>
            <div class="ifs-cinema-kpi-val"><?php echo esc_html( $active_passes_count * 2 ); ?></div>
            <div class="ifs-cinema-kpi-lbl"><?php esc_html_e( 'Concession Combos Used', 'noorjahan-movie-theater' ); ?></div>
          </div>
        </div>
        <div class="ifs-cinema-kpi-card">
          <div class="ifs-cinema-kpi-icon">
            <svg viewBox="0 0 24 24" width="26" height="26" fill="currentColor">
              <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
            </svg>
          </div>
          <div>
            <div class="ifs-cinema-kpi-val"><?php echo number_format( $total_reward_points ); ?></div>
            <div class="ifs-cinema-kpi-lbl"><?php esc_html_e( 'Reward Points Earned', 'noorjahan-movie-theater' ); ?></div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- ACCOUNT WORKSPACE -->
  <section class="ifs-cinema-workspace-section">
    <div class="ifs-cinema-container ifs-cinema-workspace-grid">
      
      <!-- Sidebar Navigation -->
      <aside class="ifs-cinema-account-sidebar">
        <button type="button" class="ifs-cinema-tab-nav-btn active" onclick="switchDashboardTab('passes', this)">
          <svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor">
            <path d="M22 10V6c0-1.1-.9-2-2-2H4c-1.1 0-1.99.9-1.99 2v4c1.1 0 1.99.9 1.99 2s-.89 2-2 2v4c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2v-4c-1.1 0-2-.9-2-2s.9-2 2-2zm-9 7.5h-2v-2h2v2zm0-4.5h-2v-2h2v2zm0-4.5h-2v-2h2v2z"/>
          </svg>
          <span><?php esc_html_e( 'Active Passes', 'noorjahan-movie-theater' ); ?></span>
        </button>
        <button type="button" class="ifs-cinema-tab-nav-btn" onclick="switchDashboardTab('history', this)">
          <svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor">
            <path d="M13 3a9 9 0 0 0-9 9H1l3.89 3.89.07.14L9 12H6c0-3.87 3.13-7 7-7s7 3.13 7 7-3.13 7-7 7c-1.93 0-3.68-.79-4.94-2.06l-1.42 1.42A8.954 8.954 0 0 0 13 21a9 9 0 0 0 0-18zm-1 5v5l4.28 2.54.72-1.21-3.5-2.08V8H12z"/>
          </svg>
          <span><?php esc_html_e( 'Booking History', 'noorjahan-movie-theater' ); ?></span>
        </button>
        <button type="button" class="ifs-cinema-tab-nav-btn" onclick="switchDashboardTab('profile', this)">
          <svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor">
            <path d="M19.14 12.94c.04-.3.06-.61.06-.94 0-.32-.02-.64-.07-.94l2.03-1.58a.49.49 0 0 0 .12-.61l-1.92-3.32a.488.488 0 0 0-.59-.22l-2.39.96c-.5-.38-1.03-.7-1.62-.94l-.36-2.54a.484.484 0 0 0-.48-.41h-3.84c-.24 0-.43.17-.47.41l-.36 2.54c-.59.24-1.13.57-1.62.94l-2.39-.96c-.22-.08-.47 0-.59.22L2.74 8.87c-.12.21-.08.47.12.61l2.03 1.58c-.05.3-.09.63-.09.94s.02.64.07.94l-2.03 1.58a.49.49 0 0 0-.12.61l1.92 3.32c.12.22.37.29.59.22l2.39-.96c.5.38 1.03.7 1.62.94l.36 2.54c.05.24.24.41.48.41h3.84c.24 0 .44-.17.47-.41l.36-2.54c.59-.24 1.13-.56 1.62-.94l2.39.96c.22.08.47 0 .59-.22l1.92-3.32c.12-.22.07-.47-.12-.61l-2.01-1.58zM12 15.6c-1.98 0-3.6-1.62-3.6-3.6s1.62-3.6 3.6-3.6 3.6 1.62 3.6 3.6-1.62 3.6-3.6 3.6z"/>
          </svg>
          <span><?php esc_html_e( 'Profile Settings', 'noorjahan-movie-theater' ); ?></span>
        </button>
        <button type="button" class="ifs-cinema-tab-nav-btn" onclick="switchDashboardTab('payment', this)">
          <svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor">
            <path d="M20 4H4c-1.11 0-1.99.89-1.99 2L2 18c0 1.11.89 2 2 2h16c1.11 0 2-.89 2-2V6c0-1.11-.89-2-2-2zm0 14H4v-6h16v6zm0-10H4V6h16v2z"/>
          </svg>
          <span><?php esc_html_e( 'Saved Payment', 'noorjahan-movie-theater' ); ?></span>
        </button>
      </aside>

      <!-- Content Panels -->
      <div>
        
        <!-- PANE 1: ACTIVE PASSES -->
        <div class="ifs-cinema-tab-pane active" id="pane-passes">
          <div style="margin-bottom: 2rem;">
            <h2 style="font-size: 1.6rem; color: #fff; margin-bottom: 0.35rem;"><?php esc_html_e( 'Active Digital Vouchers', 'noorjahan-movie-theater' ); ?></h2>
            <p><?php esc_html_e( 'Show these QR passes directly at the cinema turnstile scanners for instant entrance.', 'noorjahan-movie-theater' ); ?></p>
          </div>

          <?php if ( ! empty( $user_bookings ) ) : ?>
            <?php foreach ( $user_bookings as $booking ) : ?>
              <div class="ifs-cinema-pass-ticket-card">
                <div class="ifs-cinema-pass-left-content">
                  <div>
                    <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:1rem;">
                      <span class="ifs-cinema-badge ifs-cinema-badge-amber"><?php echo esc_html( $booking->auditorium ); ?></span>
                      <span class="ifs-cinema-badge ifs-cinema-badge-green"><?php echo esc_html( $booking->payment_status ); ?></span>
                    </div>
                    <h3 style="font-size: 1.5rem; color: #fff; font-weight: 900; margin-bottom: 0.4rem;"><?php echo esc_html( $booking->movie_title ); ?></h3>
                    <p style="font-size: 0.9rem; color: var(--ifs-cinema-brand-amber); font-weight: 700;"><?php echo esc_html( $booking->auditorium ); ?> • 70ft Curved 4K Laser Screen</p>
                    <p style="font-size: 0.85rem; color: var(--ifs-cinema-text-muted); margin-top: 2px;"><?php esc_html_e( 'Audio: Dolby Atmos® 128-Channel Spatial Engine', 'noorjahan-movie-theater' ); ?></p>
                  </div>

                  <div style="display:grid; grid-template-columns: repeat(3, 1fr); gap:1.25rem; margin-top:2rem; border-top:1px dashed var(--ifs-cinema-border-light); padding-top:1.25rem;">
                    <div>
                      <div style="font-size:0.75rem; color:var(--ifs-cinema-text-muted); text-transform:uppercase; font-weight:800;"><?php esc_html_e( 'Booking Date', 'noorjahan-movie-theater' ); ?></div>
                      <strong style="font-size:0.95rem; color:#fff;"><?php echo esc_html( date_i18n( 'M j, Y • h:i A', strtotime( $booking->booking_date ) ) ); ?></strong>
                    </div>
                    <div>
                      <div style="font-size:0.75rem; color:var(--ifs-cinema-text-muted); text-transform:uppercase; font-weight:800;"><?php esc_html_e( 'Seats', 'noorjahan-movie-theater' ); ?></div>
                      <strong style="font-size:0.95rem; color:var(--ifs-cinema-brand-amber); font-family:var(--ifs-cinema-font-mono);"><?php echo esc_html( $booking->seats ); ?></strong>
                    </div>
                    <div>
                      <div style="font-size:0.75rem; color:var(--ifs-cinema-text-muted); text-transform:uppercase; font-weight:800;"><?php esc_html_e( 'Total Paid', 'noorjahan-movie-theater' ); ?></div>
                      <strong style="font-size:0.95rem; color:#fff;">BDT <?php echo number_format( floatval( $booking->total_amount ), 2 ); ?></strong>
                    </div>
                  </div>
                </div>

                <div class="ifs-cinema-pass-right-qr">
                  <div class="ifs-cinema-qr-mock-small"></div>
                  <span style="font-family:var(--ifs-cinema-font-mono); font-size:0.85rem; color:var(--ifs-cinema-brand-amber); font-weight:800; display:block; margin-bottom:0.75rem;">
                    <?php echo esc_html( $booking->booking_token ); ?>
                  </span>
                  <button class="ifs-cinema-btn ifs-cinema-btn-secondary ifs-cinema-btn-sm" style="width:100%;" onclick="window.print()">
                    <svg viewBox="0 0 24 24" width="14" height="14" fill="currentColor">
                      <path d="M19 8H5c-1.66 0-3 1.34-3 3v6h4v4h12v-4h4v-6c0-1.66-1.34-3-3-3zm-3 11H8v-5h8v5zm3-7c-.55 0-1-.45-1-1s.45-1 1-1 1 .45 1 1-.45 1-1 1zm-1-9H6v4h12V3z"/>
                    </svg>
                    <?php esc_html_e( 'Print Pass', 'noorjahan-movie-theater' ); ?>
                  </button>
                </div>
              </div>
            <?php endforeach; ?>
          <?php else : ?>
            <div style="background:var(--ifs-cinema-bg-surface); padding:2rem; border-radius:12px; text-align:center; color:var(--ifs-cinema-text-secondary);">
              <?php esc_html_e( 'No active digital passes found in your account ledger.', 'noorjahan-movie-theater' ); ?>
            </div>
          <?php endif; ?>
        </div>

        <!-- PANE 2: BOOKING HISTORY -->
        <div class="ifs-cinema-tab-pane" id="pane-history">
          <div style="margin-bottom: 2rem;">
            <h2 style="font-size: 1.6rem; color: #fff; margin-bottom: 0.35rem;"><?php esc_html_e( 'Past Screening History', 'noorjahan-movie-theater' ); ?></h2>
            <p><?php esc_html_e( 'Archive of all previous ticket bookings and concession transactions.', 'noorjahan-movie-theater' ); ?></p>
          </div>

          <div style="background:var(--ifs-cinema-bg-surface); border:1px solid var(--ifs-cinema-border-light); border-radius:var(--ifs-cinema-radius-md); overflow-x:auto;">
            <table class="ifs-cinema-history-table">
              <thead>
                <tr>
                  <th><?php esc_html_e( 'VOUCHER ID', 'noorjahan-movie-theater' ); ?></th>
                  <th><?php esc_html_e( 'FEATURE PRESENTATION', 'noorjahan-movie-theater' ); ?></th>
                  <th><?php esc_html_e( 'AUDITORIUM', 'noorjahan-movie-theater' ); ?></th>
                  <th><?php esc_html_e( 'SEATS', 'noorjahan-movie-theater' ); ?></th>
                  <th><?php esc_html_e( 'DATE', 'noorjahan-movie-theater' ); ?></th>
                  <th><?php esc_html_e( 'AMOUNT', 'noorjahan-movie-theater' ); ?></th>
                </tr>
              </thead>
              <tbody>
                <?php if ( ! empty( $user_bookings ) ) : ?>
                  <?php foreach ( $user_bookings as $history ) : ?>
                    <tr>
                      <td><code><?php echo esc_html( $history->booking_token ); ?></code></td>
                      <td><strong><?php echo esc_html( $history->movie_title ); ?></strong></td>
                      <td><?php echo esc_html( $history->auditorium ); ?></td>
                      <td><?php echo esc_html( $history->seats ); ?></td>
                      <td><?php echo esc_html( date_i18n( 'M j, Y', strtotime( $history->booking_date ) ) ); ?></td>
                      <td style="color:var(--ifs-cinema-brand-amber); font-weight:800;">BDT <?php echo number_format( floatval( $history->total_amount ), 2 ); ?></td>
                    </tr>
                  <?php endforeach; ?>
                <?php else : ?>
                  <tr>
                    <td colspan="6" style="text-align:center; padding: 1.5rem; color:var(--ifs-cinema-text-secondary);"><?php esc_html_e( 'No history available.', 'noorjahan-movie-theater' ); ?></td>
                  </tr>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>

        <!-- PANE 3: PROFILE SETTINGS -->
        <div class="ifs-cinema-tab-pane" id="pane-profile">
          <div class="ifs-cinema-profile-form-card">
            <h2 style="font-size: 1.6rem; color: #fff; margin-bottom: 1.5rem; border-bottom:1px solid var(--ifs-cinema-border-subtle); padding-bottom:1rem;">
              <?php esc_html_e( 'Personal VIP Profile Settings', 'noorjahan-movie-theater' ); ?>
            </h2>

            <form onsubmit="event.preventDefault(); alert('Profile details updated successfully!');">
              <div class="ifs-cinema-form-grid-2">
                <div class="ifs-cinema-input-group">
                  <label><?php esc_html_e( 'Full Legal Name', 'noorjahan-movie-theater' ); ?></label>
                  <input type="text" class="ifs-cinema-input-field" value="Mahfuzur Rahman" required>
                </div>
                <div class="ifs-cinema-input-group">
                  <label><?php esc_html_e( 'Mobile Number (For SMS Passes)', 'noorjahan-movie-theater' ); ?></label>
                  <input type="tel" class="ifs-cinema-input-field" value="+880 1700-244723" required>
                </div>
              </div>

              <div class="ifs-cinema-form-grid-2">
                <div class="ifs-cinema-input-group">
                  <label><?php esc_html_e( 'Email Address', 'noorjahan-movie-theater' ); ?></label>
                  <input type="email" class="ifs-cinema-input-field" value="mahfuzur@example.com" required>
                </div>
                <div class="ifs-cinema-input-group">
                  <label><?php esc_html_e( 'Preferred Seating Class', 'noorjahan-movie-theater' ); ?></label>
                  <select class="ifs-cinema-input-field">
                    <option selected><?php esc_html_e( 'VIP Motorized Recliner', 'noorjahan-movie-theater' ); ?></option>
                    <option><?php esc_html_e( 'Standard Rocker', 'noorjahan-movie-theater' ); ?></option>
                    <option><?php esc_html_e( 'Couple Lounger', 'noorjahan-movie-theater' ); ?></option>
                  </select>
                </div>
              </div>

              <button type="submit" class="ifs-cinema-btn ifs-cinema-btn-amber" style="margin-top:1rem;">
                <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor">
                  <path d="M17 3H5c-1.11 0-2 .9-2 2v14c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V7l-4-4zm-5 16c-1.66 0-3-1.34-3-3s1.34-3 3-3 3 1.34 3 3-1.34 3-3 3zm3-10H5V5h10v4z"/>
                </svg>
                <?php esc_html_e( 'Save Profile Changes', 'noorjahan-movie-theater' ); ?>
              </button>
            </form>
          </div>
        </div>

        <!-- PANE 4: SAVED PAYMENT -->
        <div class="ifs-cinema-tab-pane" id="pane-payment">
          <div class="ifs-cinema-profile-form-card">
            <h2 style="font-size: 1.6rem; color: #fff; margin-bottom: 1.5rem; border-bottom:1px solid var(--ifs-cinema-border-subtle); padding-bottom:1rem;">
              <?php esc_html_e( 'Saved Payment Wallets', 'noorjahan-movie-theater' ); ?>
            </h2>

            <div style="background:var(--ifs-cinema-bg-elevated); border:1px solid var(--ifs-cinema-border-light); padding:1.5rem; border-radius:var(--ifs-cinema-radius-sm); display:flex; justify-content:space-between; align-items:center; margin-bottom:1.25rem;">
              <div>
                <strong style="color:#fff; font-size:1.1rem; display:block;"><?php esc_html_e( 'bKash Registered Wallet', 'noorjahan-movie-theater' ); ?></strong>
                <span style="font-family:var(--ifs-cinema-font-mono); color:var(--ifs-cinema-text-secondary); font-size:0.9rem;">01700-XXXX23</span>
              </div>
              <span class="ifs-cinema-badge ifs-cinema-badge-green"><?php esc_html_e( 'PRIMARY WALLET', 'noorjahan-movie-theater' ); ?></span>
            </div>

            <button class="ifs-cinema-btn ifs-cinema-btn-secondary" onclick="alert('Payment tokenization prompt opened.');">
              <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor">
                <path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/>
              </svg>
              <?php esc_html_e( 'Add New Wallet / Card', 'noorjahan-movie-theater' ); ?>
            </button>
          </div>
        </div>

      </div>

    </div>
  </section>

</main>

<script>
  function switchDashboardTab(tabId, btnEl) {
    document.querySelectorAll('.ifs-cinema-tab-pane').forEach(p => p.classList.remove('active'));
    document.querySelectorAll('.ifs-cinema-tab-nav-btn').forEach(b => b.classList.remove('active'));
    
    document.getElementById('pane-' + tabId).classList.add('active');
    btnEl.classList.add('active');
  }
</script>

<?php get_footer(); ?>