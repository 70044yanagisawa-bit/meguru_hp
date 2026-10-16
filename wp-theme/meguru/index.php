<?php
/**
 * お知らせの一覧（設定 → 表示設定 の「投稿ページ」）、カテゴリー・検索結果、見つからないページ
 */
get_header();

if ( is_home() ) {
	$title = get_option( 'page_for_posts' ) ? get_the_title( get_option( 'page_for_posts' ) ) : 'お知らせ';
} elseif ( is_search() ) {
	$title = '「' . get_search_query() . '」の検索結果';
} elseif ( is_archive() ) {
	$title = wp_strip_all_tags( get_the_archive_title() );
} else {
	$title = 'ページが見つかりませんでした';
}
?>
  <main class="entry entry--page">
    <header class="page-head">
      <p class="tag">News</p>
      <h1 class="page-head__title"><?php echo esc_html( $title ); ?></h1>
    </header>

<?php if ( have_posts() && ! is_404() ) : ?>
    <ul class="news__list news__list--page">
	<?php
	while ( have_posts() ) :
		the_post();
		?>
      <li><a href="<?php the_permalink(); ?>"><time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date( 'Y.m.d' ) ); ?></time><span><?php the_title(); ?></span></a></li>
	<?php endwhile; ?>
    </ul>
	<?php
	the_posts_pagination( array(
		'prev_text' => '←',
		'next_text' => '→',
	) );
	?>
<?php else : ?>
    <p>お探しのページは見つかりませんでした。</p>
    <p class="post-article__back"><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><i aria-hidden="true">←</i> トップへ戻る</a></p>
<?php endif; ?>
  </main>

<?php get_footer(); ?>
