<?php
/**
 * Template Name: Disclaimer Page
 *
 * @package Noorjahan_Movie_Theater
 */

get_header(); ?>

<main id="primary" class="site-main">

  <!-- DISCLAIMER HERO -->
  <section class="ifs-cinema-about-hero">
    <div class="ifs-cinema-container">
      <span class="ifs-cinema-badge ifs-cinema-badge-amber"><?php esc_html_e( 'LEGAL NOTICE', 'noorjahan-movie-theater' ); ?></span>
      <h1><?php esc_html_e( 'Disclaimer', 'noorjahan-movie-theater' ); ?></h1>
      <p>
        <?php esc_html_e( 'Please read this disclaimer carefully before using the website, ticketing engine, or services of Big Screen Cinemas at Noorjahan Grand.', 'noorjahan-movie-theater' ); ?>
      </p>
    </div>
  </section>

  <!-- DISCLAIMER CONTENT -->
  <section class="ifs-cinema-container ifs-cinema-details-layout">
    
    <!-- 01. General Website Information -->
    <div class="ifs-cinema-content-card">
      <h2 class="ifs-cinema-card-heading">
        <svg viewBox="0 0 24 24" width="22" height="22" fill="currentColor">
          <path d="M11 7h2v2h-2zm0 4h2v6h-2zm1-9C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8z"/>
        </svg>
        <?php esc_html_e( '1. General Information', 'noorjahan-movie-theater' ); ?>
      </h2>
      <p style="line-height: 1.85; margin-bottom: 1rem;">
        <?php esc_html_e( 'The information provided on this website by Big Screen Cinemas ("we," "our," or "us"), located at Noorjahan Grand, VIP Road, Sylhet, Bangladesh, is for general informational and ticket reservation purposes only.', 'noorjahan-movie-theater' ); ?>
      </p>
      <p style="line-height: 1.85;">
        <?php esc_html_e( 'While we endeavor to keep schedules, pricing, movie metadata, and seating availability up to date and accurate, we make no representations or warranties of any kind, express or implied, regarding the completeness, accuracy, reliability, or availability of the website content.', 'noorjahan-movie-theater' ); ?>
      </p>
    </div>

    <!-- 02. Showtime & Scheduling Changes -->
    <div class="ifs-cinema-content-card">
      <h2 class="ifs-cinema-card-heading">
        <svg viewBox="0 0 24 24" width="22" height="22" fill="currentColor">
          <path d="M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8zm.5-13H11v6l5.25 3.15.75-1.23-4.5-2.67V7z"/>
        </svg>
        <?php esc_html_e( '2. Showtimes & Program Changes', 'noorjahan-movie-theater' ); ?>
      </h2>
      <p style="line-height: 1.85; margin-bottom: 1.25rem;">
        <?php esc_html_e( 'Screening schedules, auditorium allocations, and movie premiere dates are subject to change without prior notice due to:', 'noorjahan-movie-theater' ); ?>
      </p>
      <ul style="list-style: none; display: flex; flex-direction: column; gap: 0.85rem; padding-left: 0.5rem; color: var(--ifs-cinema-text-secondary); font-size: 0.95rem;">
        <li style="display: flex; align-items: flex-start; gap: 0.75rem;">
          <svg viewBox="0 0 24 24" width="16" height="16" fill="var(--ifs-cinema-brand-amber)" style="flex-shrink: 0; margin-top: 3px;">
            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
          </svg>
          <span><?php esc_html_e( 'Distributor release schedule adjustments or censor board clearance delays.', 'noorjahan-movie-theater' ); ?></span>
        </li>
        <li style="display: flex; align-items: flex-start; gap: 0.75rem;">
          <svg viewBox="0 0 24 24" width="16" height="16" fill="var(--ifs-cinema-brand-amber)" style="flex-shrink: 0; margin-top: 3px;">
            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
          </svg>
          <span><?php esc_html_e( 'Unforeseen technical maintenance on 4K dual laser projectors, sound processors, or auditorium infrastructure.', 'noorjahan-movie-theater' ); ?></span>
        </li>
        <li style="display: flex; align-items: flex-start; gap: 0.75rem;">
          <svg viewBox="0 0 24 24" width="16" height="16" fill="var(--ifs-cinema-brand-amber)" style="flex-shrink: 0; margin-top: 3px;">
            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
          </svg>
          <span><?php esc_html_e( 'Force majeure events, power grid fluctuations, or government restrictions.', 'noorjahan-movie-theater' ); ?></span>
        </li>
      </ul>
      <p style="line-height: 1.85; margin-top: 1.25rem;">
        <?php esc_html_e( 'In the event of an unavoidable cancellation, our liability is strictly limited to issuing a replacement ticket pass or processing a standard refund for the cancelled showtime.', 'noorjahan-movie-theater' ); ?>
      </p>
    </div>

    <!-- 03. Third-Party Links & Media Assets -->
    <div class="ifs-cinema-content-card">
      <h2 class="ifs-cinema-card-heading">
        <svg viewBox="0 0 24 24" width="22" height="22" fill="currentColor">
          <path d="M3.9 12c0-1.71 1.39-3.1 3.1-3.1h4V7H7c-2.76 0-5 2.24-5 5s2.24 5 5 5h4v-1.9H7c-1.71 0-3.1-1.39-3.1-3.1zM8 13h8v-2H8v2zm9-6h-4v1.9h4c1.71 0 3.1 1.39 3.1 3.1s-1.39 3.1-3.1 3.1h-4V17h4c2.76 0 5-2.24 5-5s-2.24-5-5-5z"/>
        </svg>
        <?php esc_html_e( '3. Third-Party Content & Trademarks', 'noorjahan-movie-theater' ); ?>
      </h2>
      <p style="line-height: 1.85; margin-bottom: 1rem;">
        <?php esc_html_e( 'Movie titles, trailers, poster art, character names, and associated media assets displayed on this site are the copyrighted properties and trademarks of their respective film studios, producers, and distributors. They are utilized solely for promotional and identification purposes.', 'noorjahan-movie-theater' ); ?>
      </p>
      <p style="line-height: 1.85;">
        <?php esc_html_e( 'Any third-party payment gateways (such as SSLCommerz, bKash, or Nagad) or external map links accessible via this portal are managed by independent entities. We hold no responsibility for external content, transaction latency, or third-party platform policies.', 'noorjahan-movie-theater' ); ?>
      </p>
    </div>

    <!-- 04. Health, Strobe & Audio Notice -->
    <div class="ifs-cinema-content-card">
      <h2 class="ifs-cinema-card-heading">
        <svg viewBox="0 0 24 24" width="22" height="22" fill="currentColor">
          <path d="M1 21h22L12 2 1 21zm12-3h-2v-2h2v2zm0-4h-2v-4h2v4z"/>
        </svg>
        <?php esc_html_e( '4. Audio, Strobe & Health Notice', 'noorjahan-movie-theater' ); ?>
      </h2>
      <p style="line-height: 1.85; margin-bottom: 1rem;">
        <?php esc_html_e( 'Our auditoriums utilize high-intensity 4K dual laser projection and multi-channel high-decibel Dolby Atmos spatial sound systems.', 'noorjahan-movie-theater' ); ?>
      </p>
      <p style="line-height: 1.85;">
        <?php esc_html_e( 'Certain feature films contain flashing strobe effects, sudden lighting changes, and intense low-frequency audio vibrations. Guests with photosensitive epilepsy, hearing sensitivities, or heart conditions are advised to check individual movie rating advisories before booking.', 'noorjahan-movie-theater' ); ?>
      </p>
    </div>

    <!-- 05. Personal Belongings -->
    <div class="ifs-cinema-content-card">
      <h2 class="ifs-cinema-card-heading">
        <svg viewBox="0 0 24 24" width="22" height="22" fill="currentColor">
          <path d="M20 6h-4V4c0-1.11-.89-2-2-2h-4c-1.11 0-2 .89-2 2v2H4c-1.11 0-1.99.89-1.99 2L2 19c0 1.11.89 2 2 2h16c1.11 0 2-.89 2-2V8c0-1.11-.89-2-2-2zm-6 0h-4V4h4v2z"/>
        </svg>
        <?php esc_html_e( '5. Personal Belongings & Valuables', 'noorjahan-movie-theater' ); ?>
      </h2>
      <p style="line-height: 1.85;">
        <?php esc_html_e( 'Big Screen Cinemas and Noorjahan Grand management accept no liability for the loss, theft, or damage of personal items, electronic devices, or valuables brought inside the cinema halls, lounges, or waiting areas.', 'noorjahan-movie-theater' ); ?>
      </p>
    </div>

    <!-- 06. Contact Concierge -->
    <div class="ifs-cinema-content-card">
      <h2 class="ifs-cinema-card-heading">
        <svg viewBox="0 0 24 24" width="22" height="22" fill="currentColor">
          <path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/>
        </svg>
        <?php esc_html_e( '6. Questions & Clarifications', 'noorjahan-movie-theater' ); ?>
      </h2>
      <p style="line-height: 1.85; margin-bottom: 1.25rem;">
        <?php esc_html_e( 'If you have any questions regarding this disclaimer, please contact our management team:', 'noorjahan-movie-theater' ); ?>
      </p>
      <div class="ifs-cinema-hud-item" style="display: inline-block; padding: 1rem 1.5rem; font-family: var(--ifs-cinema-font-main);">
        <p style="color: #fff; margin-bottom: 0.35rem;"><strong><?php esc_html_e( 'Big Screen Cinemas Management', 'noorjahan-movie-theater' ); ?></strong></p>
        <p style="margin-bottom: 0.25rem;"><?php esc_html_e( 'Noorjahan Grand, VIP Road, Sylhet, Bangladesh', 'noorjahan-movie-theater' ); ?></p>
        <p style="margin-bottom: 0.25rem;"><?php esc_html_e( 'Phone:', 'noorjahan-movie-theater' ); ?> <a href="tel:+8801700244723" style="color: var(--ifs-cinema-brand-amber); font-weight: 700;"><?php esc_html_e( '+880 1700-BIGSCREEN (244723)', 'noorjahan-movie-theater' ); ?></a></p>
        <p><?php esc_html_e( 'Email:', 'noorjahan-movie-theater' ); ?> <a href="mailto:concierge@bigscreencinemas.com" style="color: var(--ifs-cinema-brand-amber); font-weight: 700;">concierge@bigscreencinemas.com</a></p>
      </div>
    </div>

  </section>

</main>

<?php get_footer(); ?>