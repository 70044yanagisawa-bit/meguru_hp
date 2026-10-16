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

<?php if ( meguru_has_hero() ) : ?>
  <!-- オープニング -->
  <div class="opening" aria-hidden="true">
    <p class="opening__m"><span><?php echo esc_html( mb_substr( meguru_brand( 'meguru_footer_big' ), 0, 1 ) ); ?></span></p>
    <p class="opening__word">
      <?php foreach ( preg_split( '//u', meguru_brand( 'meguru_logo' ), -1, PREG_SPLIT_NO_EMPTY ) as $char ) : ?><span class="mask"><span><?php echo esc_html( $char ); ?></span></span><?php endforeach; ?>
    </p>
    <p class="opening__sub"><span class="mask"><span><?php echo esc_html( meguru_brand( 'meguru_logo_sub' ) ); ?></span></span></p>
  </div>
<?php endif; ?>

  <header class="header">
    <a class="header__logo" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php echo esc_html( meguru_brand( 'meguru_logo' ) ); ?><small><?php echo esc_html( meguru_brand( 'meguru_logo_sub' ) ); ?></small></a>
<?php if ( meguru_brand( 'meguru_link_text' ) ) : ?>
    <a class="header__link" href="<?php echo esc_url( meguru_main_url() ); ?>" target="_blank" rel="noopener"><?php echo esc_html( meguru_brand( 'meguru_link_text' ) ); ?></a>
<?php endif; ?>
  </header>
  <div class="progress" aria-hidden="true"><span></span></div>
