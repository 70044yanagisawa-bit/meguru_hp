<?php
/**
 * トップページ
 * 文章・写真は、管理画面の「固定ページ → トップページ」の入力欄から変えられる（inc/fields.php）。
 * 実績は左メニューの「実績」から。
 */
get_header();
?>
  <main>

    <!-- ファーストビュー：Mの島。スクロールで車とバイクが進む -->
    <section class="hero" data-intro data-hero>
      <div class="hero__sticky">
        <p class="hero__bg" aria-hidden="true"><span><?php echo esc_html( str_repeat( wp_strip_all_tags( meguru_f( 'hero_bg' ) ) . "\u{00A0}", 4 ) ); ?></span></p>
        <div class="hero__scene" data-scene></div>
        <div class="hero__copy">
          <p class="hero__label"><span class="mask"><span><?php echo meguru_t( 'hero_label' ); ?></span></span></p>
          <h1 class="hero__title">
<?php echo meguru_lines( 'hero_title', '            ' ); ?>
          </h1>
          <p class="hero__name"><span class="mask"><span><?php echo meguru_t( 'hero_name' ); ?></span></span></p>
        </div>
        <p class="hero__hint" aria-hidden="true"><?php echo meguru_br( 'hero_hint' ); ?></p>
        <p class="hero__scroll" aria-hidden="true">Scroll</p>
      </div>
    </section>

    <!-- はじめに -->
    <section class="intro">
      <p class="intro__text" data-reveal>
<?php echo meguru_lines( 'intro_text', '        ' ); ?>
      </p>
      <p class="intro__sub" data-reveal="up"><?php echo meguru_br( 'intro_sub' ); ?></p>
    </section>

    <!-- 大きな写真 -->
    <figure class="wide frame" data-reveal="clip">
      <div class="frame__in" data-parallax="0.18">
        <?php echo meguru_frame( 'img_wide' ); ?>
      </div>
    </figure>

    <!-- 章1 -->
    <article class="chapter">
      <p class="chapter__word" data-reveal><span class="mask"><span><?php echo meguru_t( 'ch1_word' ); ?></span></span></p>
      <div class="chapter__body">
        <h2 class="chapter__title" data-reveal>
<?php echo meguru_lines( 'ch1_title' ); ?>
        </h2>
<?php echo meguru_paras( 'ch1_body' ); ?>
      </div>
    </article>

    <div class="pair">
      <figure class="pair__a frame" data-reveal="clip">
        <div class="frame__in" data-parallax="0.1">
          <?php echo meguru_frame( 'img_pair_a' ); ?>
        </div>
      </figure>
      <figure class="pair__b frame" data-reveal="clip-side">
        <div class="frame__in" data-parallax="-0.08">
          <?php echo meguru_frame( 'img_pair_b' ); ?>
        </div>
      </figure>
    </div>

    <!-- 章2 -->
    <article class="chapter chapter--rev">
      <p class="chapter__word" data-reveal><span class="mask"><span><?php echo meguru_t( 'ch2_word' ); ?></span></span></p>
      <div class="chapter__body">
        <h2 class="chapter__title" data-reveal>
<?php echo meguru_lines( 'ch2_title' ); ?>
        </h2>
<?php echo meguru_paras( 'ch2_body' ); ?>
      </div>
    </article>

<?php if ( meguru_on( 'band_show' ) ) : ?>
    <!-- 写真の上を言葉が通り過ぎる -->
    <section class="band">
      <figure class="band__photo frame">
        <div class="frame__in" data-parallax="0.25">
          <?php echo meguru_frame( 'img_band' ); ?>
        </div>
      </figure>
      <p class="band__text" data-reveal>
<?php echo meguru_lines( 'band_text', '        ' ); ?>
      </p>
    </section>
<?php endif; ?>

<?php if ( meguru_on( 'ch3_show' ) ) : ?>
    <!-- 章3 -->
    <article class="chapter">
      <p class="chapter__word" data-reveal><span class="mask"><span><?php echo meguru_t( 'ch3_word' ); ?></span></span></p>
      <div class="chapter__body">
        <h2 class="chapter__title" data-reveal>
<?php echo meguru_lines( 'ch3_title' ); ?>
        </h2>
<?php echo meguru_paras( 'ch3_body' ); ?>
      </div>
    </article>
<?php endif; ?>

<?php if ( meguru_on( 'line_show' ) ) : ?>
    <!-- 緑の囲み -->
    <section class="line" id="line">
      <div class="line__head">
        <p class="tag" data-reveal="up"><?php echo meguru_t( 'line_tag' ); ?></p>
        <h2 class="line__title" data-reveal>
<?php echo meguru_lines( 'line_title' ); ?>
        </h2>
      </div>

      <figure class="line__illust frame" data-reveal="clip">
        <div class="frame__in" data-parallax="0.08">
          <?php echo meguru_frame( 'img_line' ); ?>
        </div>
      </figure>

      <div class="line__body">
<?php echo meguru_paras( 'line_body' ); ?>
      </div>

<?php if ( meguru_on( 'line_swap_show' ) ) : ?>
      <div class="line__swap" data-reveal="up">
        <p class="line__from"><?php foreach ( meguru_split( meguru_f( 'line_from' ) ) as $from ) : ?><s><?php echo meguru_kses( $from ); ?></s><?php endforeach; ?><small><?php echo meguru_t( 'line_from_note' ); ?></small></p>
        <p class="line__arrow" aria-hidden="true">→</p>
        <p class="line__to"><b><?php echo meguru_t( 'line_to' ); ?></b><span class="line__stamp"><?php echo meguru_t( 'line_stamp' ); ?><br><em><?php echo meguru_t( 'line_stamp_num' ); ?></em><?php echo meguru_t( 'line_stamp_unit' ); ?></span></p>
      </div>
<?php endif; ?>
<?php if ( meguru_f( 'line_note' ) ) : ?>
      <p class="line__note" data-reveal="up"><?php echo meguru_br( 'line_note' ); ?></p>
<?php endif; ?>

      <div class="line__promise">
        <h3 class="line__promise-title" data-reveal>
<?php echo meguru_lines( 'line_promise_title' ); ?>
        </h3>
        <div class="line__promise-body">
<?php echo meguru_paras( 'line_promise_body', '          ' ); ?>
        </div>
      </div>
    </section>
<?php endif; ?>

<?php if ( meguru_on( 'ch4_show' ) ) : ?>
    <!-- 章4 -->
    <article class="chapter chapter--rev chapter--photo">
      <p class="chapter__word" data-reveal><span class="mask"><span><?php echo meguru_t( 'ch4_word' ); ?></span></span></p>
      <div class="chapter__body">
        <h2 class="chapter__title" data-reveal>
<?php echo meguru_lines( 'ch4_title' ); ?>
        </h2>
<?php echo meguru_paras( 'ch4_body' ); ?>
      </div>
      <figure class="chapter__photo frame" data-reveal="clip">
        <div class="frame__in" data-parallax="0.1">
          <?php echo meguru_frame( 'img_ch4' ); ?>
        </div>
      </figure>
    </article>
<?php endif; ?>

<?php if ( meguru_on( 'works_show' ) ) : ?>
    <!-- つくったもの：PCでは縦スクロールで横に流れる -->
    <section class="works" data-pin>
      <div class="works__sticky">
        <div class="works__track">
          <div class="works__head">
            <h2 class="works__title" data-reveal>
<?php echo meguru_lines( 'works_title', '              ' ); ?>
            </h2>
            <p data-reveal="up"><?php echo meguru_t( 'works_sub' ); ?></p>
          </div>

<?php foreach ( meguru_works() as $work ) : ?>
          <a class="work" href="<?php echo esc_url( $work['url'] ); ?>" target="_blank" rel="noopener">
            <figure class="work__thumb frame" data-reveal="clip">
              <div class="frame__in">
                <?php if ( $work['img'] ) : ?>
                <img src="<?php echo esc_url( $work['img'] ); ?>" alt="<?php echo esc_attr( $work['alt'] ); ?>" loading="lazy" onerror="ph(this)">
                <?php endif; ?>
              </div>
            </figure>
            <p class="work__meta"><?php echo esc_html( $work['meta'] ); ?></p>
            <h3 class="work__name"><?php echo esc_html( $work['name'] ); ?> <i aria-hidden="true">↗</i></h3>
          </a>
<?php endforeach; ?>
        </div>
      </div>
    </section>
<?php endif; ?>

<?php if ( meguru_on( 'me_show' ) ) : ?>
    <!-- プロフィール -->
    <section class="me">
      <figure class="me__photo frame" data-reveal="clip">
        <div class="frame__in" data-parallax="0.08">
          <?php echo meguru_frame( 'me_photo', wp_strip_all_tags( meguru_f( 'me_name' ) ) ); ?>
        </div>
      </figure>
      <div class="me__body">
        <p class="me__name" data-reveal>
          <span class="mask"><span><?php echo meguru_t( 'me_name' ); ?></span></span>
<?php if ( meguru_f( 'me_name_en' ) ) : ?>
          <span class="mask"><span><i><?php echo meguru_t( 'me_name_en' ); ?></i></span></span>
<?php endif; ?>
        </p>
<?php echo meguru_paras( 'me_body' ); ?>
<?php if ( meguru_f( 'me_more' ) ) : ?>
        <p class="me__more" data-reveal="up"><a href="<?php echo esc_url( meguru_history_url() ); ?>"><?php echo meguru_t( 'me_more' ); ?> <i aria-hidden="true">→</i></a></p>
<?php endif; ?>
      </div>
    </section>
<?php endif; ?>

    <!-- おわりに -->
    <section class="close">
      <h2 class="close__title" data-reveal>
<?php echo meguru_lines( 'close_title' ); ?>
      </h2>
<?php echo meguru_paras( 'close_body', '      ' ); ?>
<?php if ( meguru_f( 'close_mail' ) ) : ?>
      <p data-reveal="up"><?php
		$mail_link = '<a class="close__mail" href="' . esc_url( 'mailto:' . meguru_mail() ) . '">' . esc_html( meguru_mail() ) . '</a>';
		echo str_replace( '{mail}', $mail_link, meguru_t( 'close_mail' ) ); // phpcs:ignore WordPress.Security.EscapeOutput
		?></p>
<?php endif; ?>
    </section>

  </main>

<?php get_footer(); ?>
