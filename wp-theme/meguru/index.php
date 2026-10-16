<?php
/**
 * そのほかのページ（プライバシーポリシーなど、管理画面で作った固定ページ・投稿）
 * 本文はブロックエディタで書いたものがそのまま出る。
 */
get_header();
?>
  <main class="wp-page">
<?php if ( have_posts() ) : ?>
<?php while ( have_posts() ) : the_post(); ?>
    <article <?php post_class( 'wp-page__article' ); ?>>
      <h1 class="wp-page__title"><?php the_title(); ?></h1>
      <?php if ( 'post' === get_post_type() ) : ?>
      <p class="wp-page__date"><?php echo esc_html( get_the_date() ); ?></p>
      <?php endif; ?>
      <div class="wp-page__body">
        <?php is_singular() ? the_content() : the_excerpt(); ?>
      </div>
      <?php if ( ! is_singular() ) : ?>
      <p><a class="close__mail" href="<?php the_permalink(); ?>">続きを読む</a></p>
      <?php endif; ?>
    </article>
<?php endwhile; ?>
    <?php the_posts_pagination(); ?>
<?php else : ?>
    <article class="wp-page__article">
      <h1 class="wp-page__title">ページが見つかりませんでした</h1>
      <p><a class="close__mail" href="<?php echo esc_url( home_url( '/' ) ); ?>">めぐるHP制作のトップへ戻る</a></p>
    </article>
<?php endif; ?>
  </main>

<?php get_footer(); ?>
