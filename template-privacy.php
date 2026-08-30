<?php
/**
 * Template Name: Privacy Policy Page
 *
 * @package Noorjahan_Movie_Theater
 */

get_header(); ?>

<main id="primary" class="site-main">

  <!-- PRIVACY POLICY HERO -->
  <section class="ifs-cinema-about-hero">
    <div class="ifs-cinema-container">
      <span class="ifs-cinema-badge ifs-cinema-badge-amber"><?php esc_html_e( 'LEGAL & COMPLIANCE', 'noorjahan-movie-theater' ); ?></span>
      <h1><?php esc_html_e( 'Privacy Policy', 'noorjahan-movie-theater' ); ?></h1>
      <p>
        <?php esc_html_e( 'Your privacy is paramount. Learn how Big Screen Cinemas at Noorjahan Grand collects, uses, protects, and handles your personal information across our website and booking platforms.', 'noorjahan-movie-theater' ); ?>
      </p>
    </div>
  </section>

  <!-- PRIVACY POLICY CONTENT -->
  <section class="ifs-cinema-container ifs-cinema-details-layout">
    
    <!-- 01. Introduction -->
    <div class="ifs-cinema-content-card">
      <h2 class="ifs-cinema-card-heading">
        <svg viewBox="0 0 24 24" width="22" height="22" fill="currentColor">
          <path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm0 10.99h7c-.53 4.12-3.28 7.79-7 8.94V12H5V6.3l7-3.11v8.8z"/>
        </svg>
        <?php esc_html_e( '1. Introduction', 'noorjahan-movie-theater' ); ?>
      </h2>
      <p style="line-height: 1.85; margin-bottom: 1rem;">
        <?php esc_html_e( 'Welcome to Big Screen Cinemas ("we," "our," or "us"). We operate the ticketing and digital concierge portal for our luxury cinema multiplex located at Noorjahan Grand, VIP Road, Sylhet, Bangladesh.', 'noorjahan-movie-theater' ); ?>
      </p>
      <p style="line-height: 1.85;">
        <?php esc_html_e( 'This Privacy Policy explains how we collect, store, utilize, and protect your personal information when you browse our website, reserve auditorium seats, buy dine-in concessions, or interact with our concierge desk.', 'noorjahan-movie-theater' ); ?>
      </p>
    </div>

    <!-- 02. Information We Collect -->
    <div class="ifs-cinema-content-card">
      <h2 class="ifs-cinema-card-heading">
        <svg viewBox="0 0 24 24" width="22" height="22" fill="currentColor">
          <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-5 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/>
        </svg>
        <?php esc_html_e( '2. Information We Collect', 'noorjahan-movie-theater' ); ?>
      </h2>
      <p style="line-height: 1.85; margin-bottom: 1.25rem;">
        <?php esc_html_e( 'We collect only the essential information needed to process your ticket reservations and provide customer support:', 'noorjahan-movie-theater' ); ?>
      </p>
      <ul style="list-style: none; display: flex; flex-direction: column; gap: 0.85rem; padding-left: 0.5rem; color: var(--ifs-cinema-text-secondary); font-size: 0.95rem;">
        <li style="display: flex; align-items: flex-start; gap: 0.75rem;">
          <svg viewBox="0 0 24 24" width="16" height="16" fill="var(--ifs-cinema-brand-amber)" style="flex-shrink: 0; margin-top: 3px;">
            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
          </svg>
          <span><strong><?php esc_html_e( 'Contact Details:', 'noorjahan-movie-theater' ); ?></strong> <?php esc_html_e( 'Full name, mobile phone number (for SMS ticket vouchers), and email address (for booking confirmations).', 'noorjahan-movie-theater' ); ?></span>
        </li>
        <li style="display: flex; align-items: flex-start; gap: 0.75rem;">
          <svg viewBox="0 0 24 24" width="16" height="16" fill="var(--ifs-cinema-brand-amber)" style="flex-shrink: 0; margin-top: 3px;">
            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
          </svg>
          <span><strong><?php esc_html_e( 'Transaction & Booking Details:', 'noorjahan-movie-theater' ); ?></strong> <?php esc_html_e( 'Movie title, auditorium, showtime, selected seat identifiers (e.g., A1-A5, B1-B6), concessions ordered, total transaction amount, and unique pass tokens.', 'noorjahan-movie-theater' ); ?></span>
        </li>
        <li style="display: flex; align-items: flex-start; gap: 0.75rem;">
          <svg viewBox="0 0 24 24" width="16" height="16" fill="var(--ifs-cinema-brand-amber)" style="flex-shrink: 0; margin-top: 3px;">
            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
          </svg>
          <span><strong><?php esc_html_e( 'Payment Information:', 'noorjahan-movie-theater' ); ?></strong> <?php esc_html_e( 'All online card, bKash, and Nagad payments are processed through secure 256-bit SSL encrypted payment gateways (e.g., SSLCommerz). We never store your full credit card numbers, CVV codes, or mobile wallet PINs on our servers.', 'noorjahan-movie-theater' ); ?></span>
        </li>
        <li style="display: flex; align-items: flex-start; gap: 0.75rem;">
          <svg viewBox="0 0 24 24" width="16" height="16" fill="var(--ifs-cinema-brand-amber)" style="flex-shrink: 0; margin-top: 3px;">
            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
          </svg>
          <span><strong><?php esc_html_e( 'Technical Logs:', 'noorjahan-movie-theater' ); ?></strong> <?php esc_html_e( 'IP address, device type, browser specifications, and cookie preferences used to improve site performance and prevent fraud.', 'noorjahan-movie-theater' ); ?></span>
        </li>
      </ul>
    </div>

    <!-- 03. How We Use Your Information -->
    <div class="ifs-cinema-content-card">
      <h2 class="ifs-cinema-card-heading">
        <svg viewBox="0 0 24 24" width="22" height="22" fill="currentColor">
          <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/>
        </svg>
        <?php esc_html_e( '3. How We Use Your Information', 'noorjahan-movie-theater' ); ?>
      </h2>
      <p style="line-height: 1.85; margin-bottom: 1.25rem;">
        <?php esc_html_e( 'Your personal data is used solely for standard business and operational purposes:', 'noorjahan-movie-theater' ); ?>
      </p>
      <ul style="list-style: none; display: flex; flex-direction: column; gap: 0.85rem; padding-left: 0.5rem; color: var(--ifs-cinema-text-secondary); font-size: 0.95rem;">
        <li style="display: flex; align-items: flex-start; gap: 0.75rem;">
          <svg viewBox="0 0 24 24" width="16" height="16" fill="var(--ifs-cinema-brand-amber)" style="flex-shrink: 0; margin-top: 3px;">
            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
          </svg>
          <span><?php esc_html_e( 'Issuing verified digital QR vouchers and seat admission passes.', 'noorjahan-movie-theater' ); ?></span>
        </li>
        <li style="display: flex; align-items: flex-start; gap: 0.75rem;">
          <svg viewBox="0 0 24 24" width="16" height="16" fill="var(--ifs-cinema-brand-amber)" style="flex-shrink: 0; margin-top: 3px;">
            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
          </svg>
          <span><?php esc_html_e( 'Sending automated booking confirmations, schedule changes, or cancellation notices via SMS and email.', 'noorjahan-movie-theater' ); ?></span>
        </li>
        <li style="display: flex; align-items: flex-start; gap: 0.75rem;">
          <svg viewBox="0 0 24 24" width="16" height="16" fill="var(--ifs-cinema-brand-amber)" style="flex-shrink: 0; margin-top: 3px;">
            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
          </svg>
          <span><?php esc_html_e( 'Delivering pre-ordered gourmet dine-in combos to your assigned cinema recliner.', 'noorjahan-movie-theater' ); ?></span>
        </li>
        <li style="display: flex; align-items: flex-start; gap: 0.75rem;">
          <svg viewBox="0 0 24 24" width="16" height="16" fill="var(--ifs-cinema-brand-amber)" style="flex-shrink: 0; margin-top: 3px;">
            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
          </svg>
          <span><?php esc_html_e( 'Providing 24/7 guest support and responding to inquiries submitted to our concierge desk.', 'noorjahan-movie-theater' ); ?></span>
        </li>
      </ul>
    </div>

    <!-- 04. Data Protection & Security -->
    <div class="ifs-cinema-content-card">
      <h2 class="ifs-cinema-card-heading">
        <svg viewBox="0 0 24 24" width="22" height="22" fill="currentColor">
          <path d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zm-6 9c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zm3.1-9H8.9V6c0-1.71 1.39-3.1 3.1-3.1 1.71 0 3.1 1.39 3.1 3.1v2z"/>
        </svg>
        <?php esc_html_e( '4. Data Security & Storage', 'noorjahan-movie-theater' ); ?>
      </h2>
      <p style="line-height: 1.85; margin-bottom: 1rem;">
        <?php esc_html_e( 'We enforce strict technical and organizational safeguards to protect your personal data against unauthorized access, loss, alteration, or disclosure. All data transmissions between your browser and our servers are encrypted via industry-standard 256-bit SSL (Secure Sockets Layer) encryption.', 'noorjahan-movie-theater' ); ?>
      </p>
      <p style="line-height: 1.85;">
        <?php esc_html_e( 'Access to booking databases and transaction records is strictly limited to authorized personnel managing the box office and concierge operations.', 'noorjahan-movie-theater' ); ?>
      </p>
    </div>

    <!-- 05. Third-Party Sharing -->
    <div class="ifs-cinema-content-card">
      <h2 class="ifs-cinema-card-heading">
        <svg viewBox="0 0 24 24" width="22" height="22" fill="currentColor">
          <path d="M18 16.08c-.76 0-1.44.3-1.96.77L8.91 12.7c.05-.23.09-.46.09-.7s-.04-.47-.09-.7l7.05-4.11c.54.5 1.25.81 2.04.81 1.66 0 3-1.34 3-3s-1.34-3-3-3-3 1.34-3 3c0 .24.04.47.09.7L8.04 9.81C7.5 9.31 6.79 9 6 9c-1.66 0-3 1.34-3 3s1.34 3 3 3c.79 0 1.5-.31 2.04-.81l7.12 4.16c-.05.21-.08.43-.08.65 0 1.61 1.31 2.92 2.92 2.92 1.61 0 2.92-1.31 2.92-2.92s-1.31-2.92-2.92-2.92z"/>
        </svg>
        <?php esc_html_e( '5. Third-Party Disclosure', 'noorjahan-movie-theater' ); ?>
      </h2>
      <p style="line-height: 1.85; margin-bottom: 1rem;">
        <?php esc_html_e( 'We do not sell, trade, or rent your personal identification information to marketing agencies or third parties. We share data only with:', 'noorjahan-movie-theater' ); ?>
      </p>
      <ul style="list-style: none; display: flex; flex-direction: column; gap: 0.85rem; padding-left: 0.5rem; color: var(--ifs-cinema-text-secondary); font-size: 0.95rem;">
        <li style="display: flex; align-items: flex-start; gap: 0.75rem;">
          <svg viewBox="0 0 24 24" width="16" height="16" fill="var(--ifs-cinema-brand-amber)" style="flex-shrink: 0; margin-top: 3px;">
            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
          </svg>
          <span><strong><?php esc_html_e( 'Payment Gateways:', 'noorjahan-movie-theater' ); ?></strong> <?php esc_html_e( 'Licensed financial aggregators and mobile banking portals (such as SSLCommerz, bKash, and Nagad) to authorize transactions.', 'noorjahan-movie-theater' ); ?></span>
        </li>
        <li style="display: flex; align-items: flex-start; gap: 0.75rem;">
          <svg viewBox="0 0 24 24" width="16" height="16" fill="var(--ifs-cinema-brand-amber)" style="flex-shrink: 0; margin-top: 3px;">
            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
          </svg>
          <span><strong><?php esc_html_e( 'SMS Gateways:', 'noorjahan-movie-theater' ); ?></strong> <?php esc_html_e( 'Telecommunications partners solely to transmit your digital ticket pass tokens and alerts.', 'noorjahan-movie-theater' ); ?></span>
        </li>
        <li style="display: flex; align-items: flex-start; gap: 0.75rem;">
          <svg viewBox="0 0 24 24" width="16" height="16" fill="var(--ifs-cinema-brand-amber)" style="flex-shrink: 0; margin-top: 3px;">
            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
          </svg>
          <span><strong><?php esc_html_e( 'Legal Authorities:', 'noorjahan-movie-theater' ); ?></strong> <?php esc_html_e( 'When required by applicable laws, court orders, or law enforcement investigations within Bangladesh.', 'noorjahan-movie-theater' ); ?></span>
        </li>
      </ul>
    </div>

    <!-- 06. Cookies Policy -->
    <div class="ifs-cinema-content-card">
      <h2 class="ifs-cinema-card-heading">
        <svg viewBox="0 0 24 24" width="22" height="22" fill="currentColor">
          <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10c0-.55-.45-1-1-1s-1 .45-1 1c0 4.41-3.59 8-8 8s-8-3.59-8-8 3.59-8 8-8c.55 0 1-.45 1-1s-.45-1-1-1zm6.5 4.5c.83 0 1.5-.67 1.5-1.5s-.67-1.5-1.5-1.5-1.5.67-1.5 1.5.67 1.5 1.5 1.5zm-3 5c.83 0 1.5-.67 1.5-1.5s-.67-1.5-1.5-1.5-1.5.67-1.5 1.5.67 1.5 1.5 1.5zm-5 5c.83 0 1.5-.67 1.5-1.5s-.67-1.5-1.5-1.5-1.5.67-1.5 1.5.67 1.5 1.5 1.5z"/>
        </svg>
        <?php esc_html_e( '6. Cookies & Local Storage', 'noorjahan-movie-theater' ); ?>
      </h2>
      <p style="line-height: 1.85;">
        <?php esc_html_e( 'Our portal uses essential cookies and browser session storage to maintain your seat selections during the reservation process, preserve your authentication status, and remember user preferences. You can disable non-essential cookies via your browser settings, though doing so may affect interactive seat selection performance.', 'noorjahan-movie-theater' ); ?>
      </p>
    </div>

    <!-- 07. Contact Us -->
    <div class="ifs-cinema-content-card">
      <h2 class="ifs-cinema-card-heading">
        <svg viewBox="0 0 24 24" width="22" height="22" fill="currentColor">
          <path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/>
        </svg>
        <?php esc_html_e( '7. Contact Concierge Regarding Privacy', 'noorjahan-movie-theater' ); ?>
      </h2>
      <p style="line-height: 1.85; margin-bottom: 1.25rem;">
        <?php esc_html_e( 'If you have questions about this Privacy Policy, wish to request the deletion of your account, or need support regarding your stored data, reach out directly to our compliance desk:', 'noorjahan-movie-theater' ); ?>
      </p>
      <div class="ifs-cinema-hud-item" style="display: inline-block; padding: 1rem 1.5rem; font-family: var(--ifs-cinema-font-main);">
        <p style="color: #fff; margin-bottom: 0.35rem;"><strong><?php esc_html_e( 'Big Screen Cinemas Concierge Desk', 'noorjahan-movie-theater' ); ?></strong></p>
        <p style="margin-bottom: 0.25rem;"><?php esc_html_e( 'Noorjahan Grand, VIP Road, Sylhet, Bangladesh', 'noorjahan-movie-theater' ); ?></p>
        <p style="margin-bottom: 0.25rem;"><?php esc_html_e( 'Helpline:', 'noorjahan-movie-theater' ); ?> <a href="tel:+8801700244723" style="color: var(--ifs-cinema-brand-amber); font-weight: 700;"><?php esc_html_e( '+880 1700-BIGSCREEN (244723)', 'noorjahan-movie-theater' ); ?></a></p>
        <p><?php esc_html_e( 'Email:', 'noorjahan-movie-theater' ); ?> <a href="mailto:concierge@bigscreencinemas.com" style="color: var(--ifs-cinema-brand-amber); font-weight: 700;">concierge@bigscreencinemas.com</a></p>
      </div>
    </div>

  </section>

</main>

<?php get_footer(); ?>