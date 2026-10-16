<?php
/**
 * 「これまでのこと」
 * スラッグが history の固定ページで使われる。テーマを有効にすると自動でページができる。
 * 文章・写真・章は、そのページの編集画面の入力欄から変えられる（inc/fields.php）。
 */
get_header();
?>
  <main>

    <!-- はじまり -->
    <section class="h-hero" data-intro>
      <p class="tag h-hero__tag"><span class="mask"><span><?php echo meguru_t( 'h_tag' ); ?></span></span></p>
      <h1 class="h-hero__title">
<?php echo meguru_lines( 'h_title', '        ' ); ?>
      </h1>
      <p class="h-hero__lead">
<?php echo meguru_lines( 'h_lead', '        ' ); ?>
      </p>
      <figure class="h-hero__illust frame" data-reveal="clip">
        <div class="frame__in" data-parallax="0.08">
          <?php echo meguru_frame( 'h_img' ); ?>
        </div>
      </figure>
    </section>

    <!-- 章（左右交互） -->
<?php foreach ( array_values( (array) meguru_f( 'h_chapters' ) ) as $i => $chap ) : ?>
<?php $rev = 1 === $i % 2; ?>
    <article class="h-chap<?php echo $rev ? ' h-chap--rev' : ''; ?>">
      <figure class="h-chap__illust frame" data-reveal="<?php echo $rev ? 'clip-side' : 'clip'; ?>">
        <div class="frame__in" data-parallax="0.08">
          <?php echo meguru_frame_inner( isset( $chap['image'] ) ? $chap['image'] : 0, isset( $chap['illust'] ) ? $chap['illust'] : '' ); ?>
        </div>
      </figure>
      <div class="h-chap__body">
<?php if ( ! empty( $chap['no'] ) ) : ?>
        <p class="h-chap__no" data-reveal="up"><?php echo meguru_kses( $chap['no'] ); ?></p>
<?php endif; ?>
        <h2 class="h-chap__word" data-reveal><span class="mask"><span><?php echo meguru_kses( isset( $chap['word'] ) ? $chap['word'] : '' ); ?></span></span></h2>
<?php if ( ! empty( $chap['sub'] ) ) : ?>
        <p class="h-chap__sub" data-reveal="up"><?php echo meguru_kses( $chap['sub'] ); ?></p>
<?php endif; ?>
<?php echo meguru_paras_html( isset( $chap['body'] ) ? $chap['body'] : '' ); ?>
      </div>
    </article>

<?php endforeach; ?>
<?php if ( meguru_on( 'record_show' ) ) : ?>
    <!-- 経歴の要点 -->
    <section class="record">
      <h2 class="record__title" data-reveal>
<?php echo meguru_lines( 'record_title' ); ?>
      </h2>
      <ol class="record__list">
<?php foreach ( (array) meguru_f( 'record_items' ) as $item ) : ?>
        <li<?php echo ! empty( $item['now'] ) && '1' === (string) $item['now'] ? ' class="is-now"' : ''; ?> data-reveal="up"><span class="record__step"><?php echo meguru_kses( isset( $item['step'] ) ? $item['step'] : '' ); ?></span><b><?php echo meguru_kses( isset( $item['name'] ) ? $item['name'] : '' ); ?></b><p><?php echo meguru_kses( isset( $item['text'] ) ? $item['text'] : '' ); ?></p></li>
<?php endforeach; ?>
      </ol>
    </section>
<?php endif; ?>

    <!-- 戻る -->
    <section class="close">
      <h2 class="close__title" data-reveal>
<?php echo meguru_lines( 'h_close_title' ); ?>
      </h2>
      <p data-reveal="up"><a class="close__mail" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php echo meguru_t( 'h_close_link' ); ?></a></p>
    </section>

  </main>

<?php get_footer(); ?>
