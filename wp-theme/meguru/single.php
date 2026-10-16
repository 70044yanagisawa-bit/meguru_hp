<?php
/**
 * お知らせ（投稿）1件
 */
get_header();

while ( have_posts() ) :
	the_post();
	$list = get_option( 'page_for_posts' ) ? get_permalink( get_option( 'page_for_posts' ) ) : home_url( '/' );
	?>
  <main class="entry entry--page">
    <article <?php post_class( 'post-article' ); ?>>
      <header class="page-head">
        <p class="tag"><time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date( 'Y.m.d' ) ); ?></time></p>
        <h1 class="page-head__title"><?php the_title(); ?></h1>
      </header>
	<?php if ( has_post_thumbnail() ) : ?>
      <figure class="post-article__thumb"><?php the_post_thumbnail( 'large' ); ?></figure>
	<?php endif; ?>
	<?php the_content(); ?>
      <p class="post-article__back"><a href="<?php echo esc_url( $list ); ?>"><i aria-hidden="true">←</i> お知らせの一覧へ</a></p>
    </article>
  </main>
	<?php
endwhile;

get_footer();
