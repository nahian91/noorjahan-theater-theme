<?php
/**
 * Template Name: Gallery Page
 *
 * @package Nahian_Movie_Theater
 */

get_header();

// Fetch dynamic gallery items from backend database/options.
$gallery_items = get_option(
	'nmbp_gallery_images',
	get_option(
		'bsc_gallery_images',
		array(
			array(
				'url'         => get_theme_file_uri( '/assets/img/gallery-1.jpg' ),
				'category'    => 'screens',
				'title'       => '70ft Curved Silver Screen',
				'description' => 'High-frame-rate curved giant screen with dual 4K laser projection.',
			),
			array(
				'url'         => get_theme_file_uri( '/assets/img/gallery-2.jpg' ),
				'category'    => 'lounges',
				'title'       => 'Motorized Recliner Suites',
				'description' => 'Heated motorized recliners with in-seat dining swivel tables.',
			),
			array(
				'url'         => get_theme_file_uri( '/assets/img/gallery-3.jpg' ),
				'category'    => 'dining',
				'title'       => 'Signature Food Combos',
				'description' => 'Chef-prepared hot combos, butter popcorn tubs, and artisan beverages.',
			),
			array(
				'url'         => get_theme_file_uri( '/assets/img/gallery-4.jpg' ),
				'category'    => 'screens',
				'title'       => 'Dolby Atmos® Acoustic Space',
				'description' => '128-channel Dolby Atmos spatial surround sound architecture.',
			),
			array(
				'url'         => get_theme_file_uri( '/assets/img/gallery-5.jpg' ),
				'category'    => 'lounges',
				'title'       => 'Concierge Reception Lobby',
				'description' => 'Luxury reception lobby at Nahian Grand.',
			),
			array(
				'url'         => get_theme_file_uri( '/assets/img/gallery-6.jpg' ),
				'category'    => 'dining',
				'title'       => 'In-Seat Waiter Delivery',
				'description' => 'Freshly served hot meals delivered directly to your cinema chair.',
			),
		)
	)
);

$category_labels = array(
	'screens' => __( 'Auditoriums', 'nahian-movie-theater' ),
	'lounges' => __( 'VIP Lounges', 'nahian-movie-theater' ),
	'dining'  => __( 'Concessions', 'nahian-movie-theater' ),
);
?>

<style id="nmt-gallery-css">
	.nmt-gallery-card {
		cursor: pointer;
	}
	.nmt-gallery-card--hidden {
		display: none;
	}
	.nmt-gallery-empty-msg {
		color: var(--ifs-cinema-text-muted);
		text-align: center;
		grid-column: 1 / -1;
	}
	.nmt-lightbox-title {
		color: #ffffff;
		font-size: 1.25rem;
		margin: 0;
	}
	.nmt-lightbox-desc {
		font-size: 0.88rem;
		color: var(--ifs-cinema-text-secondary);
		margin-top: 4px;
		margin-bottom: 0;
	}
</style>

<main id="primary" class="site-main">

	<!-- GALLERY HERO & FILTERS -->
	<section class="ifs-cinema-gallery-hero">
		<div class="ifs-cinema-container">
			<span class="ifs-cinema-badge ifs-cinema-badge-amber"><?php esc_html_e( 'VISUAL SHOWCASE', 'nahian-movie-theater' ); ?></span>
			<h1><?php esc_html_e( 'Inside The Multiplex', 'nahian-movie-theater' ); ?></h1>
			<p>
				<?php esc_html_e( 'Explore high-resolution photography of our curved laser giant screens, motorized heated VIP recliner suites, and gourmet dine-in concession bars.', 'nahian-movie-theater' ); ?>
			</p>

			<div class="ifs-cinema-filter-pills">
				<button type="button" class="ifs-cinema-filter-btn active" data-gallery-filter="all"><?php esc_html_e( 'All Highlights', 'nahian-movie-theater' ); ?></button>
				<button type="button" class="ifs-cinema-filter-btn" data-gallery-filter="screens"><?php esc_html_e( 'Auditoriums & Screens', 'nahian-movie-theater' ); ?></button>
				<button type="button" class="ifs-cinema-filter-btn" data-gallery-filter="lounges"><?php esc_html_e( 'VIP Recliners & Lounges', 'nahian-movie-theater' ); ?></button>
				<button type="button" class="ifs-cinema-filter-btn" data-gallery-filter="dining"><?php esc_html_e( 'Gourmet Concessions', 'nahian-movie-theater' ); ?></button>
			</div>
		</div>
	</section>

	<!-- MASONRY GRID -->
	<section class="ifs-cinema-section-padding">
		<div class="ifs-cinema-container">
			<div class="ifs-cinema-gallery-masonry" id="galleryGrid">

				<?php
				if ( ! empty( $gallery_items ) && is_array( $gallery_items ) ) :
					foreach ( $gallery_items as $item ) :
						$cat_key   = sanitize_key( $item['category'] ?? 'screens' );
						$cat_label = $category_labels[ $cat_key ] ?? __( 'Multiplex', 'nahian-movie-theater' );
						$img_url   = esc_url( $item['url'] ?? '' );
						$title     = ! empty( $item['title'] ) ? $item['title'] : __( 'Showcase', 'nahian-movie-theater' );
						$desc      = $item['description'] ?? '';
						?>
						<div class="ifs-cinema-gallery-card nmt-gallery-card" data-category="<?php echo esc_attr( $cat_key ); ?>" data-lightbox-src="<?php echo esc_url( $img_url ); ?>" data-lightbox-title="<?php echo esc_attr( $title ); ?>" data-lightbox-desc="<?php echo esc_attr( $desc ); ?>">
							<img src="<?php echo esc_url( $img_url ); ?>"
								data-fallback-src="https://images.unsplash.com/photo-1517604931442-7e0c8ed2963c?w=700&auto=format&fit=crop&q=75"
								class="ifs-cinema-gallery-img"
								alt="<?php echo esc_attr( $title ); ?>">
							<div class="ifs-cinema-gallery-overlay">
								<span class="ifs-cinema-gallery-category"><?php echo esc_html( $cat_label ); ?></span>
								<h3 class="ifs-cinema-gallery-title"><?php echo esc_html( $title ); ?></h3>
							</div>
						</div>
						<?php
					endforeach;
				else :
					?>
					<p class="nmt-gallery-empty-msg"><?php esc_html_e( 'No photos uploaded yet.', 'nahian-movie-theater' ); ?></p>
				<?php endif; ?>

			</div>
		</div>
	</section>

	<!-- LIGHTBOX MODAL -->
	<div class="ifs-cinema-lightbox-overlay" id="lightboxModal" role="dialog" aria-modal="true">
		<div class="ifs-cinema-lightbox-wrap">
			<button type="button" class="ifs-cinema-lightbox-close" id="lightboxCloseBtn" aria-label="<?php esc_attr_e( 'Close lightbox', 'nahian-movie-theater' ); ?>">✕</button>
			<img id="lightboxImg" src="" alt="<?php esc_attr_e( 'Enlarged gallery view', 'nahian-movie-theater' ); ?>">
			<div class="ifs-cinema-lightbox-caption">
				<div>
					<h3 id="lightboxTitle" class="nmt-lightbox-title"><?php esc_html_e( 'Caption Title', 'nahian-movie-theater' ); ?></h3>
					<p id="lightboxDesc" class="nmt-lightbox-desc"><?php esc_html_e( 'Description', 'nahian-movie-theater' ); ?></p>
				</div>
				<span class="ifs-cinema-badge ifs-cinema-badge-amber"><?php esc_html_e( 'BIG SCREEN CINEMAS', 'nahian-movie-theater' ); ?></span>
			</div>
		</div>
	</div>

</main>

<script id="nmt-gallery-js">
	(function() {
		document.addEventListener("DOMContentLoaded", function() {
			const filterButtons = document.querySelectorAll(".ifs-cinema-filter-btn[data-gallery-filter]");
			const cards         = document.querySelectorAll("#galleryGrid .ifs-cinema-gallery-card");
			const modal         = document.getElementById("lightboxModal");
			const modalImg      = document.getElementById("lightboxImg");
			const modalTitle    = document.getElementById("lightboxTitle");
			const modalDesc     = document.getElementById("lightboxDesc");
			const closeBtn      = document.getElementById("lightboxCloseBtn");

			filterButtons.forEach(function(btn) {
				btn.addEventListener("click", function() {
					const category = btn.getAttribute("data-gallery-filter");
					filterButtons.forEach(function(b) {
						b.classList.remove("active");
					});
					btn.classList.add("active");

					cards.forEach(function(card) {
						if (category === "all" || card.getAttribute("data-category") === category) {
							card.classList.remove("nmt-gallery-card--hidden");
						} else {
							card.classList.add("nmt-gallery-card--hidden");
						}
					});
				});
			});

			function openLightbox(card) {
				if (!modal) return;
				const url   = card.getAttribute("data-lightbox-src") || "";
				const title = card.getAttribute("data-lightbox-title") || "";
				const desc  = card.getAttribute("data-lightbox-desc") || "";

				if (modalImg) modalImg.setAttribute("src", url);
				if (modalTitle) modalTitle.textContent = title;
				if (modalDesc) modalDesc.textContent = desc;
				modal.classList.add("active");
			}

			function closeLightboxDirect() {
				if (modal) {
					modal.classList.remove("active");
				}
			}

			cards.forEach(function(card) {
				card.addEventListener("click", function() {
					openLightbox(card);
				});
			});

			if (closeBtn) {
				closeBtn.addEventListener("click", closeLightboxDirect);
			}

			if (modal) {
				modal.addEventListener("click", function(e) {
					if (e.target === modal) {
						closeLightboxDirect();
					}
				});
			}

			document.querySelectorAll("img[data-fallback-src]").forEach(function(img) {
				img.addEventListener("error", function() {
					const fallback = img.getAttribute("data-fallback-src");
					if (fallback && img.getAttribute("src") !== fallback) {
						img.setAttribute("src", fallback);
					}
				}, { once: true });
			});
		});
	})();
</script>

<?php get_footer(); ?>