<?php
/**
 * Template Name: Gallery Page
 *
 * @package Noorjahan_Movie_Theater
 */

get_header(); 

// Fetch dynamic gallery items from backend database/options
$gallery_items = get_option('bsc_gallery_images', array(
  array(
    'url'         => get_theme_file_uri('/assets/img/gallery-1.jpg'),
    'category'    => 'screens',
    'title'       => '70ft Curved Silver Screen',
    'description' => 'High-frame-rate curved giant screen with dual 4K laser projection.'
  ),
  array(
    'url'         => get_theme_file_uri('/assets/img/gallery-2.jpg'),
    'category'    => 'lounges',
    'title'       => 'Motorized Recliner Suites',
    'description' => 'Heated motorized recliners with in-seat dining swivel tables.'
  ),
  array(
    'url'         => get_theme_file_uri('/assets/img/gallery-3.jpg'),
    'category'    => 'dining',
    'title'       => 'Signature Food Combos',
    'description' => 'Chef-prepared hot combos, butter popcorn tubs, and artisan beverages.'
  ),
  array(
    'url'         => get_theme_file_uri('/assets/img/gallery-4.jpg'),
    'category'    => 'screens',
    'title'       => 'Dolby Atmos® Acoustic Space',
    'description' => '128-channel Dolby Atmos spatial surround sound architecture.'
  ),
  array(
    'url'         => get_theme_file_uri('/assets/img/gallery-5.jpg'),
    'category'    => 'lounges',
    'title'       => 'Concierge Reception Lobby',
    'description' => 'Luxury reception lobby at Grand Sylhet Hotel & Resort.'
  ),
  array(
    'url'         => get_theme_file_uri('/assets/img/gallery-6.jpg'),
    'category'    => 'dining',
    'title'       => 'In-Seat Waiter Delivery',
    'description' => 'Freshly served hot meals delivered directly to your cinema chair.'
  )
));

$category_labels = array(
  'screens' => __('Auditoriums', 'noorjahan-movie-theater'),
  'lounges' => __('VIP Lounges', 'noorjahan-movie-theater'),
  'dining'  => __('Concessions', 'noorjahan-movie-theater'),
);
?>

<main id="primary" class="site-main">

  <!-- GALLERY HERO & FILTERS -->
  <section class="ifs-cinema-gallery-hero">
    <div class="ifs-cinema-container">
      <span class="ifs-cinema-badge ifs-cinema-badge-amber"><?php esc_html_e( 'VISUAL SHOWCASE', 'noorjahan-movie-theater' ); ?></span>
      <h1><?php esc_html_e( 'Inside The Multiplex', 'noorjahan-movie-theater' ); ?></h1>
      <p>
        <?php esc_html_e( 'Explore high-resolution photography of our curved laser giant screens, motorized heated VIP recliner suites, and gourmet dine-in concession bars.', 'noorjahan-movie-theater' ); ?>
      </p>

      <div class="ifs-cinema-filter-pills">
        <button class="ifs-cinema-filter-btn active" onclick="filterGallery('all', this)"><?php esc_html_e( 'All Highlights', 'noorjahan-movie-theater' ); ?></button>
        <button class="ifs-cinema-filter-btn" onclick="filterGallery('screens', this)"><?php esc_html_e( 'Auditoriums & Screens', 'noorjahan-movie-theater' ); ?></button>
        <button class="ifs-cinema-filter-btn" onclick="filterGallery('lounges', this)"><?php esc_html_e( 'VIP Recliners & Lounges', 'noorjahan-movie-theater' ); ?></button>
        <button class="ifs-cinema-filter-btn" onclick="filterGallery('dining', this)"><?php esc_html_e( 'Gourmet Concessions', 'noorjahan-movie-theater' ); ?></button>
      </div>
    </div>
  </section>

  <!-- MASONRY GRID -->
  <section class="ifs-cinema-section-padding">
    <div class="ifs-cinema-container">
      <div class="ifs-cinema-gallery-masonry" id="galleryGrid">
        
        <?php if (!empty($gallery_items)): foreach ($gallery_items as $item): 
          $cat_key = esc_attr($item['category'] ?? 'screens');
          $cat_label = $category_labels[$cat_key] ?? __('Multiplex', 'noorjahan-movie-theater');
          $img_url = esc_url($item['url']);
          $title = esc_html($item['title'] ?? 'Showcase');
          $desc = esc_html($item['description'] ?? '');
        ?>
          <div class="ifs-cinema-gallery-card" data-category="<?php echo $cat_key; ?>" onclick="openLightbox('<?php echo $img_url; ?>', '<?php echo esc_js($title); ?>', '<?php echo esc_js($desc); ?>')">
            <img src="<?php echo $img_url; ?>" 
                 class="ifs-cinema-gallery-img" 
                 alt="<?php echo esc_attr($title); ?>"
                 onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1517604931442-7e0c8ed2963c?w=700&auto=format&fit=crop&q=75';">
            <div class="ifs-cinema-gallery-overlay">
              <span class="ifs-cinema-gallery-category"><?php echo esc_html($cat_label); ?></span>
              <h3 class="ifs-cinema-gallery-title"><?php echo $title; ?></h3>
            </div>
          </div>
        <?php endforeach; else: ?>
          <p style="color:var(--ifs-cinema-text-muted); text-align:center; grid-column:1/-1;"><?php esc_html_e('No photos uploaded yet.', 'noorjahan-movie-theater'); ?></p>
        <?php endif; ?>

      </div>
    </div>
  </section>

  <!-- LIGHTBOX MODAL -->
  <div class="ifs-cinema-lightbox-overlay" id="lightboxModal" role="dialog" aria-modal="true" onclick="closeLightbox(event)">
    <div class="ifs-cinema-lightbox-wrap" onclick="event.stopPropagation()">
      <button class="ifs-cinema-lightbox-close" onclick="closeLightboxDirect()">✕</button>
      <img id="lightboxImg" src="" alt="<?php esc_attr_e( 'Enlarged gallery view', 'noorjahan-movie-theater' ); ?>">
      <div class="ifs-cinema-lightbox-caption">
        <div>
          <h3 id="lightboxTitle" style="color:#fff; font-size:1.25rem; margin:0;"><?php esc_html_e( 'Caption Title', 'noorjahan-movie-theater' ); ?></h3>
          <p id="lightboxDesc" style="font-size:0.88rem; color:var(--ifs-cinema-text-secondary); margin-top:4px; margin-bottom:0;"><?php esc_html_e( 'Description', 'noorjahan-movie-theater' ); ?></p>
        </div>
        <span class="ifs-cinema-badge ifs-cinema-badge-amber"><?php esc_html_e( 'BIG SCREEN CINEMAS', 'noorjahan-movie-theater' ); ?></span>
      </div>
    </div>
  </div>

</main>

<script>
  function filterGallery(category, btn) {
    document.querySelectorAll('.ifs-cinema-filter-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');

    const cards = document.querySelectorAll('#galleryGrid .ifs-cinema-gallery-card');
    cards.forEach(card => {
      if (category === 'all' || card.getAttribute('data-category') === category) {
        card.style.display = 'block';
      } else {
        card.style.display = 'none';
      }
    });
  }

  function openLightbox(url, title, desc) {
    document.getElementById('lightboxImg').src = url;
    document.getElementById('lightboxTitle').innerText = title;
    document.getElementById('lightboxDesc').innerText = desc;
    document.getElementById('lightboxModal').classList.add('active');
  }

  function closeLightboxDirect() {
    document.getElementById('lightboxModal').classList.remove('active');
  }

  function closeLightbox(e) {
    if (e.target.id === 'lightboxModal') {
      closeLightboxDirect();
    }
  }
</script>

<?php get_footer(); ?>