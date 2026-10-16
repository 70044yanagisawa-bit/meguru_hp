<!doctype html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo( 'charset' ); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <script>
    document.documentElement.classList.add("js");
    // 写真がまだ無いときは、仮の写真（data-fallback）→ それも無ければ枠だけ残す
    function ph(img) {
      var fb = img.getAttribute("data-fallback");
      if (fb) { img.removeAttribute("data-fallback"); img.src = fb; } else { img.remove(); }
    }
  </script>
  <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<?php if ( is_front_page() ) : ?>
  <!-- オープニング -->
  <div class="opening" aria-hidden="true">
    <p class="opening__m"><span>M</span></p>
    <p class="opening__word">
      <span class="mask"><span>め</span></span><span class="mask"><span>ぐ</span></span><span class="mask"><span>る</span></span>
    </p>
    <p class="opening__sub"><span class="mask"><span>HP制作</span></span></p>
  </div>
<?php endif; ?>

  <header class="header">
    <a class="header__logo" href="<?php echo esc_url( home_url( '/' ) ); ?>">めぐる<small>HP制作</small></a>
    <a class="header__link" href="<?php echo esc_url( meguru_main_url() ); ?>" target="_blank" rel="noopener">柳沢恵瑠のサイト</a>
  </header>
  <div class="progress" aria-hidden="true"><span></span></div>
