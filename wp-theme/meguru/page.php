<?php
/**
 * 固定ページ（トップページ・これまでのこと・プライバシーポリシーなど）
 * 中身はブロックエディタで作る。最初のブロックが「めぐる」のブロックのときは、ページ名の見出しを出さない。
 */
get_header();

while ( have_posts() ) :
	the_post();
	$designed = meguru_starts_with_block();
	?>
  <main class="entry<?php echo $designed ? '' : ' entry--page'; ?>">
	<?php if ( ! $designed ) : ?>
    <header class="page-head">
      <h1 class="page-head__title"><?php the_title(); ?></h1>
    </header>
	<?php endif; ?>
	<?php the_content(); ?>
  </main>
	<?php
endwhile;

get_footer();
