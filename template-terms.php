<?php
/**
 * Template Name: Terms & Conditions Page
 *
 * @package Nahian_Movie_Theater
 */

get_header(); ?>

<style id="nmt-terms-css">
	.nmt-terms-para {
		line-height: 1.85;
		margin-bottom: 1rem;
	}
	.nmt-terms-para--spaced {
		line-height: 1.85;
		margin-bottom: 1.25rem;
	}
	.nmt-terms-para--plain {
		line-height: 1.85;
	}
	.nmt-terms-list {
		list-style: none;
		display: flex;
		flex-direction: column;
		gap: 0.85rem;
		padding-left: 0.5rem;
		color: var(--ifs-cinema-text-secondary);
		font-size: 0.95rem;
	}
	.nmt-terms-list-item {
		display: flex;
		align-items: flex-start;
		gap: 0.75rem;
	}
	.nmt-terms-check-icon {
		flex-shrink: 0;
		margin-top: 3px;
	}
	.nmt-terms-contact-box {
		display: inline-block;
		padding: 1rem 1.5rem;
		font-family: var(--ifs-cinema-font-main);
	}
	.nmt-terms-org-name {
		color: #ffffff;
		margin-bottom: 0.35rem;
	}
	.nmt-terms-address-line {
		margin-bottom: 0.25rem;
	}
	.nmt-terms-link {
		color: var(--ifs-cinema-brand-amber);
		font-weight: 700;
	}
</style>

<main id="primary" class="site-main">

	<!-- TERMS & CONDITIONS HERO -->
	<section class="ifs-cinema-about-hero">
		<div class="ifs-cinema-container">
			<span class="ifs-cinema-badge ifs-cinema-badge-amber"><?php esc_html_e( 'LEGAL & POLICIES', 'nahian-movie-theater' ); ?></span>
			<h1><?php esc_html_e( 'Terms & Conditions', 'nahian-movie-theater' ); ?></h1>
			<p>
				<?php esc_html_e( 'Please review the rules, booking regulations, admission guidelines, and ticketing terms for Big Screen Cinemas at Nahian Grand.', 'nahian-movie-theater' ); ?>
			</p>
		</div>
	</section>

	<!-- TERMS & CONDITIONS CONTENT -->
	<section class="ifs-cinema-container ifs-cinema-details-layout">

		<!-- 01. Agreement to Terms -->
		<div class="ifs-cinema-content-card">
			<h2 class="ifs-cinema-card-heading">
				<svg viewBox="0 0 24 24" width="22" height="22" fill="currentColor" aria-hidden="true">
					<path d="M14 2H6c-1.1 0-1.99.9-1.99 2L4 20c0 1.1.89 2 1.99 2H18c1.1 0 2-.9 2-2V8l-6-6zm2 16H8v-2h8v2zm0-4H8v-2h8v2zm-3-5V3.5L18.5 9H13z"/>
				</svg>
				<?php esc_html_e( '1. Agreement to Terms', 'nahian-movie-theater' ); ?>
			</h2>
			<p class="nmt-terms-para">
				<?php esc_html_e( 'By accessing our website, purchasing admission tickets, reserving auditorium seats, or buying gourmet concessions from Big Screen Cinemas ("we," "our," or "us") at Nahian Grand, VIP Road, Sylhet, Bangladesh, you agree to be bound by these Terms and Conditions.', 'nahian-movie-theater' ); ?>
			</p>
			<p class="nmt-terms-para--plain">
				<?php esc_html_e( 'If you do not agree with any part of these terms, please refrain from using our online ticketing platform or entering our multiplex facilities.', 'nahian-movie-theater' ); ?>
			</p>
		</div>

		<!-- 02. Ticket Bookings & Seat Allocations -->
		<div class="ifs-cinema-content-card">
			<h2 class="ifs-cinema-card-heading">
				<svg viewBox="0 0 24 24" width="22" height="22" fill="currentColor" aria-hidden="true">
					<path d="M22 10V6c0-1.1-.9-2-2-2H4c-1.1 0-1.99.9-1.99 2v4c1.1 0 1.99.9 1.99 2s-.89 2-2 2v4c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2v-4c-1.1 0-2-.9-2-2s.9-2 2-2zm-9 7.5h-2v-2h2v2zm0-4.5h-2v-2h2v2zm0-4.5h-2v-2h2v2z"/>
				</svg>
				<?php esc_html_e( '2. Ticketing & Seat Allocation', 'nahian-movie-theater' ); ?>
			</h2>
			<p class="nmt-terms-para--spaced">
				<?php esc_html_e( 'To ensure a premium and comfortable viewing experience in our exclusive boutique auditoriums:', 'nahian-movie-theater' ); ?>
			</p>
			<ul class="nmt-terms-list">
				<li class="nmt-terms-list-item">
					<svg viewBox="0 0 24 24" width="16" height="16" fill="var(--ifs-cinema-brand-amber)" aria-hidden="true" class="nmt-terms-check-icon">
						<path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
					</svg>
					<span><strong><?php esc_html_e( 'Seat Selection:', 'nahian-movie-theater' ); ?></strong> <?php esc_html_e( 'All tickets are sold on an allocated seating basis according to our hall layout (Row A VIP Recliners, Rows B–D Standard Seats). Guests must occupy only their assigned seats as stated on the digital voucher.', 'nahian-movie-theater' ); ?></span>
				</li>
				<li class="nmt-terms-list-item">
					<svg viewBox="0 0 24 24" width="16" height="16" fill="var(--ifs-cinema-brand-amber)" aria-hidden="true" class="nmt-terms-check-icon">
						<path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
					</svg>
					<span><strong><?php esc_html_e( 'Digital Admission Pass:', 'nahian-movie-theater' ); ?></strong> <?php esc_html_e( 'An active digital token/pass containing the official QR code must be presented at the cinema turnstile for verification before entry.', 'nahian-movie-theater' ); ?></span>
				</li>
				<li class="nmt-terms-list-item">
					<svg viewBox="0 0 24 24" width="16" height="16" fill="var(--ifs-cinema-brand-amber)" aria-hidden="true" class="nmt-terms-check-icon">
						<path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
					</svg>
					<span><strong><?php esc_html_e( 'Pricing & Taxes:', 'nahian-movie-theater' ); ?></strong> <?php esc_html_e( 'All stated prices are in Bangladeshi Taka (BDT) and are subject to mandatory government VAT and service charges where applicable.', 'nahian-movie-theater' ); ?></span>
				</li>
			</ul>
		</div>

		<!-- 03. Cancellation, Rescheduling & Refund Policy -->
		<div class="ifs-cinema-content-card">
			<h2 class="ifs-cinema-card-heading">
				<svg viewBox="0 0 24 24" width="22" height="22" fill="currentColor" aria-hidden="true">
					<path d="M12.5 8c-2.65 0-5.05.99-6.9 2.6L2 7v9h9l-3.62-3.62c1.39-1.16 3.16-1.88 5.12-1.88 3.54 0 6.55 2.31 7.6 5.5l2.37-.78C21.08 11.03 17.15 8 12.5 8z"/>
				</svg>
				<?php esc_html_e( '3. Cancellation & Refund Policy', 'nahian-movie-theater' ); ?>
			</h2>
			<ul class="nmt-terms-list">
				<li class="nmt-terms-list-item">
					<svg viewBox="0 0 24 24" width="16" height="16" fill="var(--ifs-cinema-brand-amber)" aria-hidden="true" class="nmt-terms-check-icon">
						<path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
					</svg>
					<span><strong><?php esc_html_e( 'Final Sales:', 'nahian-movie-theater' ); ?></strong> <?php esc_html_e( 'All confirmed online and counter ticket purchases are non-refundable once the transaction is completed.', 'nahian-movie-theater' ); ?></span>
				</li>
				<li class="nmt-terms-list-item">
					<svg viewBox="0 0 24 24" width="16" height="16" fill="var(--ifs-cinema-brand-amber)" aria-hidden="true" class="nmt-terms-check-icon">
						<path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
					</svg>
					<span><strong><?php esc_html_e( 'Rescheduling Requests:', 'nahian-movie-theater' ); ?></strong> <?php esc_html_e( 'Show rescheduling requests may be considered at the sole discretion of the concierge desk if submitted at least 4 hours prior to the scheduled showtime.', 'nahian-movie-theater' ); ?></span>
				</li>
				<li class="nmt-terms-list-item">
					<svg viewBox="0 0 24 24" width="16" height="16" fill="var(--ifs-cinema-brand-amber)" aria-hidden="true" class="nmt-terms-check-icon">
						<path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
					</svg>
					<span><strong><?php esc_html_e( 'Technical Disruptions:', 'nahian-movie-theater' ); ?></strong> <?php esc_html_e( 'In the rare event of a screening cancellation due to technical faults, power failure, or operational delays, guests will be issued alternative passes or a full refund.', 'nahian-movie-theater' ); ?></span>
				</li>
			</ul>
		</div>

		<!-- 04. Age Restrictions & Cinema Etiquette -->
		<div class="ifs-cinema-content-card">
			<h2 class="ifs-cinema-card-heading">
				<svg viewBox="0 0 24 24" width="22" height="22" fill="currentColor" aria-hidden="true">
					<path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/>
				</svg>
				<?php esc_html_e( '4. Age Classification & House Rules', 'nahian-movie-theater' ); ?>
			</h2>
			<ul class="nmt-terms-list">
				<li class="nmt-terms-list-item">
					<svg viewBox="0 0 24 24" width="16" height="16" fill="var(--ifs-cinema-brand-amber)" aria-hidden="true" class="nmt-terms-check-icon">
						<path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
					</svg>
					<span><strong><?php esc_html_e( 'Censor Board Ratings:', 'nahian-movie-theater' ); ?></strong> <?php esc_html_e( 'Guests must comply with Bangladesh Film Censor Board age classifications (e.g., A/18+ rated films require valid photo ID verification at check-in).', 'nahian-movie-theater' ); ?></span>
				</li>
				<li class="nmt-terms-list-item">
					<svg viewBox="0 0 24 24" width="16" height="16" fill="var(--ifs-cinema-brand-amber)" aria-hidden="true" class="nmt-terms-check-icon">
						<path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
					</svg>
					<span><strong><?php esc_html_e( 'Auditorium Etiquette:', 'nahian-movie-theater' ); ?></strong> <?php esc_html_e( 'Mobile phones must be set to silent mode during screenings. Recording with video cameras or smartphones inside the hall is strictly prohibited by copyright law.', 'nahian-movie-theater' ); ?></span>
				</li>
				<li class="nmt-terms-list-item">
					<svg viewBox="0 0 24 24" width="16" height="16" fill="var(--ifs-cinema-brand-amber)" aria-hidden="true" class="nmt-terms-check-icon">
						<path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
					</svg>
					<span><strong><?php esc_html_e( 'Right of Admission:', 'nahian-movie-theater' ); ?></strong> <?php esc_html_e( 'Management reserves the right to refuse entry or remove any individual engaging in disorderly conduct without issuing a refund.', 'nahian-movie-theater' ); ?></span>
				</li>
			</ul>
		</div>

		<!-- 05. Outside Food & Beverage Policy -->
		<div class="ifs-cinema-content-card">
			<h2 class="ifs-cinema-card-heading">
				<svg viewBox="0 0 24 24" width="22" height="22" fill="currentColor" aria-hidden="true">
					<path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.42 0-8-3.58-8-8 0-1.85.63-3.55 1.69-4.9L16.9 18.31C15.55 19.37 13.85 20 12 20zm6.31-3.1L7.1 5.69C8.45 4.63 10.15 4 12 4c4.42 0 8 3.58 8 8 0 1.85-.63 3.55-1.69 4.9z"/>
				</svg>
				<?php esc_html_e( '5. Concessions & Outside Food Policy', 'nahian-movie-theater' ); ?>
			</h2>
			<p class="nmt-terms-para">
				<?php esc_html_e( 'Outside food and beverages are strictly prohibited inside the cinema halls.', 'nahian-movie-theater' ); ?>
			</p>
			<p class="nmt-terms-para--plain">
				<?php esc_html_e( 'Guests are invited to enjoy our in-house gourmet concessions, hot fried chicken meals, artisan popcorn, and beverages prepared fresh daily in our kitchen and served directly to your cinema recliner.', 'nahian-movie-theater' ); ?>
			</p>
		</div>

		<!-- 06. Contact Information -->
		<div class="ifs-cinema-content-card">
			<h2 class="ifs-cinema-card-heading">
				<svg viewBox="0 0 24 24" width="22" height="22" fill="currentColor" aria-hidden="true">
					<path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/>
				</svg>
				<?php esc_html_e( '6. Contact Concierge', 'nahian-movie-theater' ); ?>
			</h2>
			<p class="nmt-terms-para--spaced">
				<?php esc_html_e( 'For corporate bookings, private screening hire, or questions regarding our terms, reach out to our guest management desk:', 'nahian-movie-theater' ); ?>
			</p>
			<div class="ifs-cinema-hud-item nmt-terms-contact-box">
				<p class="nmt-terms-org-name"><strong><?php esc_html_e( 'Big Screen Cinemas Concierge Desk', 'nahian-movie-theater' ); ?></strong></p>
				<p class="nmt-terms-address-line"><?php esc_html_e( 'Nahian Grand, VIP Road, Sylhet, Bangladesh', 'nahian-movie-theater' ); ?></p>
				<p class="nmt-terms-address-line"><?php esc_html_e( 'Phone:', 'nahian-movie-theater' ); ?> <a href="tel:+8801700244723" class="nmt-terms-link"><?php esc_html_e( '+880 1700-BIGSCREEN (244723)', 'nahian-movie-theater' ); ?></a></p>
				<p class="nmt-terms-para--plain"><?php esc_html_e( 'Email:', 'nahian-movie-theater' ); ?> <a href="mailto:concierge@bigscreencinemas.com" class="nmt-terms-link">concierge@bigscreencinemas.com</a></p>
			</div>
		</div>

	</section>

</main>

<?php get_footer(); ?>