<?php
/**
 * Template Name: Contact Page
 *
 * @package Noorjahan_Movie_Theater
 */

get_header(); ?>

<main id="primary" class="site-main">

  <!-- CONTACT HERO -->
  <section class="ifs-cinema-contact-hero">
    <div class="ifs-cinema-container">
      <span class="ifs-cinema-badge ifs-cinema-badge-amber"><?php esc_html_e( '24/7 GUEST SERVICES', 'noorjahan-movie-theater' ); ?></span>
      <h1><?php esc_html_e( 'Get in Touch with Concierge', 'noorjahan-movie-theater' ); ?></h1>
      <p>
        <?php esc_html_e( 'Whether you require private auditorium bookings, corporate screening inquiries, or ticket support, our concierge desk is available to assist you.', 'noorjahan-movie-theater' ); ?>
      </p>
    </div>
  </section>

  <!-- CONTACT CARDS & FORM -->
  <section class="ifs-cinema-section-padding">
    <div class="ifs-cinema-container ifs-cinema-contact-grid">
      
      <!-- Contact Info Cards -->
      <div class="ifs-cinema-contact-cards">
        <div class="ifs-cinema-contact-info-card">
          <div class="ifs-cinema-info-icon">
            <svg viewBox="0 0 24 24" width="24" height="24" fill="currentColor">
              <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5a2.5 2.5 0 0 1 0-5 2.5 2.5 0 0 1 0 5z"/>
            </svg>
          </div>
          <div class="ifs-cinema-info-content">
            <h4><?php esc_html_e( 'Theatre Location', 'noorjahan-movie-theater' ); ?></h4>
            <p><?php esc_html_e( 'Noorjahan Grand, VIP Road, Sylhet, Bangladesh', 'noorjahan-movie-theater' ); ?></p>
            <p style="font-size:0.8rem; color:var(--ifs-cinema-text-muted); margin-top:4px;"><?php esc_html_e( 'Complimentary Valet Parking Available', 'noorjahan-movie-theater' ); ?></p>
          </div>
        </div>

        <div class="ifs-cinema-contact-info-card">
          <div class="ifs-cinema-info-icon">
            <svg viewBox="0 0 24 24" width="24" height="24" fill="currentColor">
              <path d="M6.62 10.79a15.053 15.053 0 0 0 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/>
            </svg>
          </div>
          <div class="ifs-cinema-info-content">
            <h4><?php esc_html_e( 'Telephone Concierge', 'noorjahan-movie-theater' ); ?></h4>
            <p><?php esc_html_e( 'Direct:', 'noorjahan-movie-theater' ); ?> <a href="tel:+8801700244723"><?php esc_html_e( '+880 1700-BIGSCREEN (244723)', 'noorjahan-movie-theater' ); ?></a></p>
            <p><?php esc_html_e( 'Hotel Desk: +880 1711-000000 (Ext. 402)', 'noorjahan-movie-theater' ); ?></p>
          </div>
        </div>

        <div class="ifs-cinema-contact-info-card">
          <div class="ifs-cinema-info-icon">
            <svg viewBox="0 0 24 24" width="24" height="24" fill="currentColor">
              <path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4-8 5-8-5V6l8 5 8-5v2z"/>
            </svg>
          </div>
          <div class="ifs-cinema-info-content">
            <h4><?php esc_html_e( 'Email Support', 'noorjahan-movie-theater' ); ?></h4>
            <p><?php esc_html_e( 'General:', 'noorjahan-movie-theater' ); ?> <a href="mailto:concierge@bigscreencinemas.com">concierge@bigscreencinemas.com</a></p>
            <p><?php esc_html_e( 'Corporate:', 'noorjahan-movie-theater' ); ?> <a href="mailto:events@bigscreencinemas.com">events@bigscreencinemas.com</a></p>
          </div>
        </div>

        <div class="ifs-cinema-contact-info-card">
          <div class="ifs-cinema-info-icon">
            <svg viewBox="0 0 24 24" width="24" height="24" fill="currentColor">
              <path d="M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8zm.5-13H11v6l5.25 3.15.75-1.23-4.5-2.67V7z"/>
            </svg>
          </div>
          <div class="ifs-cinema-info-content">
            <h4><?php esc_html_e( 'Operating Hours', 'noorjahan-movie-theater' ); ?></h4>
            <p><?php esc_html_e( 'Box Office Counter: 10:00 AM – 11:30 PM', 'noorjahan-movie-theater' ); ?></p>
            <p><?php esc_html_e( 'Auditorium Screenings: 11:00 AM – 02:00 AM (Daily)', 'noorjahan-movie-theater' ); ?></p>
          </div>
        </div>
      </div>

      <!-- Contact / Inquiries Form -->
      <div class="ifs-cinema-form-card">
        <div class="ifs-cinema-form-header">
          <h3><?php esc_html_e( 'Send Us a Message', 'noorjahan-movie-theater' ); ?></h3>
          <p><?php esc_html_e( 'Fill out the form below and our concierge desk will respond within 2 hours.', 'noorjahan-movie-theater' ); ?></p>
        </div>

        <form onsubmit="event.preventDefault(); alert('<?php echo esc_js( __( 'Thank you! Your message has been sent to our concierge desk.', 'noorjahan-movie-theater' ) ); ?>');">
          <div class="ifs-cinema-form-row">
            <div class="ifs-cinema-form-group">
              <label><?php esc_html_e( 'Full Name', 'noorjahan-movie-theater' ); ?></label>
              <input type="text" class="ifs-cinema-form-input" placeholder="<?php esc_attr_e( 'e.g. Tanvir Ahmed', 'noorjahan-movie-theater' ); ?>" required>
            </div>
            <div class="ifs-cinema-form-group">
              <label><?php esc_html_e( 'Phone Number', 'noorjahan-movie-theater' ); ?></label>
              <input type="tel" class="ifs-cinema-form-input" placeholder="<?php esc_attr_e( '+880 1700-000000', 'noorjahan-movie-theater' ); ?>" required>
            </div>
          </div>

          <div class="ifs-cinema-form-row">
            <div class="ifs-cinema-form-group">
              <label><?php esc_html_e( 'Email Address', 'noorjahan-movie-theater' ); ?></label>
              <input type="email" class="ifs-cinema-form-input" placeholder="<?php esc_attr_e( 'name@example.com', 'noorjahan-movie-theater' ); ?>" required>
            </div>
            <div class="ifs-cinema-form-group">
              <label><?php esc_html_e( 'Inquiry Subject', 'noorjahan-movie-theater' ); ?></label>
              <select class="ifs-cinema-form-select">
                <option><?php esc_html_e( 'Ticket Support & Cancellation', 'noorjahan-movie-theater' ); ?></option>
                <option><?php esc_html_e( 'Private Hall Hire & Corporate Screenings', 'noorjahan-movie-theater' ); ?></option>
                <option><?php esc_html_e( 'Food & Concessions Inquiries', 'noorjahan-movie-theater' ); ?></option>
                <option><?php esc_html_e( 'Membership & VIP Club', 'noorjahan-movie-theater' ); ?></option>
              </select>
            </div>
          </div>

          <div class="ifs-cinema-form-group">
            <label><?php esc_html_e( 'Your Message', 'noorjahan-movie-theater' ); ?></label>
            <textarea class="ifs-cinema-form-textarea" placeholder="<?php esc_attr_e( 'How can our concierge team assist you today?', 'noorjahan-movie-theater' ); ?>" required></textarea>
          </div>

          <button type="submit" class="ifs-cinema-btn ifs-cinema-btn-amber" style="width:100%;">
            <svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor">
              <path d="M2.01 21 23 12 2.01 3 2 10l15 2-15 2z"/>
            </svg>
            <?php esc_html_e( 'Submit Message to Concierge', 'noorjahan-movie-theater' ); ?>
          </button>
        </form>
      </div>

    </div>
  </section>

  <!-- LOCATION MAP -->
  <section class="ifs-cinema-section-padding" style="background:var(--ifs-cinema-bg-surface);">
    <div class="ifs-cinema-container">
      <div style="margin-bottom:3rem; text-align:center;">
        <span class="ifs-cinema-badge ifs-cinema-badge-amber"><?php esc_html_e( 'FIND US IN SYLHET', 'noorjahan-movie-theater' ); ?></span>
        <h2 style="font-size:2.5rem; font-weight:900; margin-top:0.8rem;"><?php esc_html_e( 'Noorjahan Grand Location', 'noorjahan-movie-theater' ); ?></h2>
      </div>

      <div class="ifs-cinema-map-card">
        <iframe 
          title="<?php esc_attr_e( 'Google Map Location', 'noorjahan-movie-theater' ); ?>"
          src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d115904.03264426514!2d91.80214878051758!3d24.898862955519343!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x375054d3d270329f%3A0xf58ef93431f67382!2sSylhet!5e0!3m2!1sen!2sbd!4v1700000000000" 
          allowfullscreen="" 
          loading="lazy">
        </iframe>
      </div>
    </div>
  </section>

</main>

<?php get_footer(); ?>