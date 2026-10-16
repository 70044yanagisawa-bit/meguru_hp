<?php
/**
 * めぐるHP制作 — このサイト専用のブロック
 *
 * ブロックエディタの「＋」→「めぐる」に並ぶ。
 * 見た目（HTML）は PHP の render で出すので、公開ページは静的版（hp/）と同じになる。
 * 編集画面の動きは assets/editor/blocks.js。
 *
 * 文章の決まりごと
 *   見出し … 改行（Shift + Enter）したところで行が分かれ、1行ずつ下からせり上がる
 *   本文   … ふつうの段落ブロック。Enter で段落が増える
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* ==========================================================
   ブロックの一覧と、持っている値
   ========================================================== */

function meguru_img_attrs( $prefix = '', $illust = '' ) {
	return array(
		$prefix . 'imageId'  => array( 'type' => 'number', 'default' => 0 ),
		$prefix . 'imageUrl' => array( 'type' => 'string', 'default' => '' ),
		$prefix . 'illust'   => array( 'type' => 'string', 'default' => $illust ),
	);
}

function meguru_block_defs() {
	$s = function ( $default = '' ) {
		return array( 'type' => 'string', 'default' => $default );
	};
	$b = function ( $default = false ) {
		return array( 'type' => 'boolean', 'default' => $default );
	};

	return array(
		'meguru/hero'      => array(
			'title' => 'ファーストビュー（YMの島）',
			'icon'  => 'admin-site-alt3',
			'desc'  => 'いちばん上。動くイラストの上に、キャッチコピーが乗る。',
			'attrs' => array(
				'label'  => $s(),
				'title'  => $s(),
				'name'   => $s(),
				'nameEn' => $s(),
				'bg'     => $s(),
				'hint'   => $s(),
			),
		),
		'meguru/intro'     => array(
			'title' => '大きな文章',
			'icon'  => 'editor-quote',
			'desc'  => '大きな文章と、右下の小さな文章。',
			'attrs' => array(
				'text' => $s(),
				'sub'  => $s(),
			),
		),
		'meguru/photo'     => array(
			'title' => '大きな写真',
			'icon'  => 'format-image',
			'desc'  => '横いっぱいの写真（またはイラスト）。',
			'attrs' => meguru_img_attrs( '', 'cafe' ),
		),
		'meguru/pair'      => array(
			'title' => '写真2枚',
			'icon'  => 'images-alt2',
			'desc'  => '縦長と横長の写真を、ずらして並べる。',
			'attrs' => array_merge( meguru_img_attrs( 'a', 'mindmap' ), meguru_img_attrs( 'b', 'phone' ) ),
		),
		'meguru/chapter'   => array(
			'title' => '章（大きな一言＋文章）',
			'icon'  => 'text-page',
			'desc'  => '縦書きの大きな一言と、見出し・本文。写真を付けると3列になる。',
			'inner' => true,
			'attrs' => array_merge(
				array(
					'word'    => $s(),
					'title'   => $s(),
					'reverse' => $b(),
				),
				meguru_img_attrs( '', '' )
			),
		),
		'meguru/band'      => array(
			'title' => '写真の上を通る言葉',
			'icon'  => 'cover-image',
			'desc'  => '画面いっぱいの写真の上を、大きな言葉が通り過ぎる。',
			'attrs' => array_merge( array( 'text' => $s() ), meguru_img_attrs( '', 'town' ) ),
		),
		'meguru/feature'   => array(
			'title' => '囲み（特集）',
			'icon'  => 'feedback',
			'desc'  => '白い囲みの中に、見出し・写真・本文。比べる図と、緑の帯も付けられる。',
			'inner' => true,
			'attrs' => array_merge(
				array(
					'tag'          => $s(),
					'title'        => $s(),
					'swapShow'     => $b(),
					'swapFrom'     => $s(),
					'swapFromNote' => $s(),
					'swapTo'       => $s(),
					'stamp'        => $s(),
					'stampNum'     => $s(),
					'stampUnit'    => $s(),
					'note'         => $s(),
					'promiseShow'  => $b(),
					'promiseTitle' => $s(),
					'promiseBody'  => $s(),
				),
				meguru_img_attrs( '', 'line' )
			),
		),
		'meguru/works'     => array(
			'title' => '実績の一覧',
			'icon'  => 'portfolio',
			'desc'  => '左メニューの「実績」に登録したものが並ぶ。',
			'attrs' => array(
				'title' => $s(),
				'sub'   => $s(),
			),
		),
		'meguru/profile'   => array(
			'title' => 'プロフィール',
			'icon'  => 'admin-users',
			'desc'  => '縦長の写真と、名前・自己紹介。',
			'inner' => true,
			'attrs' => array_merge(
				array(
					'name'     => $s(),
					'nameEn'   => $s(),
					'moreText' => $s(),
					'moreUrl'  => $s(),
				),
				meguru_img_attrs( '', '' )
			),
		),
		'meguru/news'      => array(
			'title' => 'お知らせ（新着）',
			'icon'  => 'megaphone',
			'desc'  => '「投稿」で書いたお知らせの新しいものが並ぶ。',
			'attrs' => array(
				'title' => $s(),
				'count' => array( 'type' => 'number', 'default' => 3 ),
			),
		),
		'meguru/contact'   => array(
			'title' => 'お問い合わせ（しめくくり）',
			'icon'  => 'email',
			'desc'  => '大きな見出しと、メールアドレスへのリンク。',
			'inner' => true,
			'attrs' => array(
				'title' => $s(),
				'mail'  => $s(),
			),
		),
		'meguru/page-hero' => array(
			'title' => 'ページの最初（見出し＋写真）',
			'icon'  => 'heading',
			'desc'  => '下層ページのいちばん上。ラベル・大きな見出し・リード文・横長の写真。',
			'attrs' => array_merge(
				array(
					'tag'   => $s(),
					'title' => $s(),
					'lead'  => $s(),
				),
				meguru_img_attrs( '', 'stairs' )
			),
		),
		'meguru/story'     => array(
			'title' => '章（番号＋写真）',
			'icon'  => 'id-alt',
			'desc'  => '番号・大きな一言・ラベル・本文と、横の写真。続けて置くと左右を交互にできる。',
			'inner' => true,
			'attrs' => array_merge(
				array(
					'no'      => $s(),
					'word'    => $s(),
					'sub'     => $s(),
					'reverse' => $b(),
				),
				meguru_img_attrs( '', 'listen' )
			),
		),
		'meguru/steps'     => array(
			'title' => '年表（ステップ）',
			'icon'  => 'list-view',
			'desc'  => '緑の線の上に、段ごとに並ぶ。中の「ステップ」を足して使う。',
			'inner' => true,
			'attrs' => array( 'title' => $s() ),
		),
		'meguru/step'      => array(
			'title'  => 'ステップ',
			'icon'   => 'marker',
			'desc'   => '年表の1段。',
			'parent' => array( 'meguru/steps' ),
			'attrs'  => array(
				'step' => $s(),
				'name' => $s(),
				'text' => $s(),
				'now'  => $b(),
			),
		),
	);
}

/* ==========================================================
   登録
   ========================================================== */

add_filter( 'block_categories_all', function ( $cats ) {
	array_unshift( $cats, array( 'slug' => 'meguru', 'title' => 'めぐる（このサイトのデザイン）' ) );
	return $cats;
} );

add_action( 'init', function () {
	$uri = get_template_directory_uri();

	wp_register_script( 'meguru-draw-editor', $uri . '/assets/js/draw.js', array(), meguru_asset_ver( 'assets/js/draw.js' ), true );
	wp_register_script( 'meguru-illust-editor', $uri . '/assets/js/illust.js', array( 'meguru-draw-editor' ), meguru_asset_ver( 'assets/js/illust.js' ), true );
	wp_register_script(
		'meguru-blocks',
		$uri . '/assets/editor/blocks.js',
		array( 'wp-blocks', 'wp-block-editor', 'wp-components', 'wp-element', 'wp-data', 'wp-core-data', 'wp-html-entities', 'wp-i18n', 'meguru-illust-editor' ),
		meguru_asset_ver( 'assets/editor/blocks.js' ),
		true
	);

	foreach ( meguru_block_defs() as $name => $def ) {
		$attrs           = $def['attrs'];
		$attrs['anchor'] = array( 'type' => 'string', 'default' => '' );
		register_block_type( $name, array(
			'api_version'     => 3,
			'title'           => $def['title'],
			'category'        => 'meguru',
			'attributes'      => $attrs,
			'editor_script'   => 'meguru-blocks',
			'render_callback' => 'meguru_render_' . str_replace( '-', '_', substr( $name, 7 ) ),
		) );
	}
} );

/* 編集画面にわたす値（ブロックの一覧、イラストの名前、メールアドレスなど） */
add_action( 'enqueue_block_editor_assets', function () {
	$defs = array();
	foreach ( meguru_block_defs() as $name => $def ) {
		$def['attrs']['anchor'] = array( 'type' => 'string', 'default' => '' );
		$defs[ $name ]          = $def;
	}
	wp_add_inline_script( 'meguru-blocks', 'window.meguruBlocks = ' . wp_json_encode( array(
		'blocks'     => $defs,
		'illusts'    => meguru_illust_choices(),
		'mePhoto'    => meguru_img( 'me.jpg' ),
		'mail'       => meguru_mail(),
		'historyUrl' => meguru_history_url(),
	) ) . ';', 'before' );
} );

function meguru_illust_choices() {
	return array(
		''        => '（イラストなし）',
		'cafe'    => 'カフェで打ち合わせ',
		'listen'  => '話を聞く',
		'mindmap' => 'マインドマップ',
		'phone'   => 'スマホでLINE',
		'pc'      => 'パソコン',
		'town'    => '街',
		'line'    => 'LINE',
		'chart'   => 'グラフ',
		'train'   => 'トレーニング',
		'stairs'  => '階段',
		'protect' => '守る（消防）',
		'sell'    => '売る（営業）',
		'sns'     => 'SNS',
	);
}

/* ==========================================================
   出力の道具
   ========================================================== */

function meguru_kses( $s ) {
	return wp_kses( (string) $s, array(
		'b'      => array(),
		'strong' => array(),
		'i'      => array(),
		'em'     => array(),
		'small'  => array(),
		's'      => array(),
		'br'     => array(),
		'sub'    => array(),
		'sup'    => array(),
		'code'   => array(),
		'mark'   => array( 'class' => true, 'style' => true ),
		'span'   => array( 'class' => true ),
		'a'      => array( 'href' => true, 'target' => true, 'rel' => true ),
	) );
}

/** 改行（<br>）で分ける */
function meguru_split( $html ) {
	$parts = preg_split( '/<br\s*\/?>|\R/iu', (string) $html );
	return array_values( array_filter( array_map( 'trim', $parts ), 'strlen' ) );
}

/** 見出し：1行ずつ <span class="mask"> で包む */
function meguru_lines( $html ) {
	$out = '';
	foreach ( meguru_split( $html ) as $line ) {
		$out .= '<span class="mask"><span>' . meguru_kses( $line ) . '</span></span>';
	}
	return $out;
}

function meguru_mask( $html ) {
	return '<span class="mask"><span>' . meguru_kses( $html ) . '</span></span>';
}

/** 改行をそのまま <br> に */
function meguru_br( $html ) {
	return implode( '<br>', array_map( 'meguru_kses', meguru_split( $html ) ) );
}

/** 1行＝1段落 */
function meguru_br_paras( $html ) {
	$out = '';
	foreach ( meguru_split( $html ) as $line ) {
		$out .= '<p data-reveal="up">' . meguru_kses( $line ) . '</p>';
	}
	return $out;
}

/** 中の段落ブロックに、下からふわっと出る印を付ける */
function meguru_reveal( $content ) {
	return trim( preg_replace( '/<p(?=[\s>])/', '<p data-reveal="up"', (string) $content ) );
}

function meguru_id( $a ) {
	return ! empty( $a['anchor'] ) ? ' id="' . esc_attr( $a['anchor'] ) . '"' : '';
}

/** 写真の枠の中身：写真 → イラスト → テーマの写真 の順 */
function meguru_frame( $a, $prefix = '', $alt = '', $file = '' ) {
	$id     = (int) ( isset( $a[ $prefix . 'imageId' ] ) ? $a[ $prefix . 'imageId' ] : 0 );
	$url    = isset( $a[ $prefix . 'imageUrl' ] ) ? $a[ $prefix . 'imageUrl' ] : '';
	$illust = isset( $a[ $prefix . 'illust' ] ) ? $a[ $prefix . 'illust' ] : '';

	if ( $id && wp_attachment_is_image( $id ) ) {
		$img_alt = $alt ? $alt : get_post_meta( $id, '_wp_attachment_image_alt', true );
		return wp_get_attachment_image( $id, 'large', false, array( 'alt' => $img_alt, 'onerror' => 'ph(this)' ) );
	}
	if ( $url ) {
		return '<img src="' . esc_url( $url ) . '" alt="' . esc_attr( $alt ) . '" onerror="ph(this)">';
	}
	if ( $illust ) {
		return '<div class="illust" data-illust="' . esc_attr( $illust ) . '"></div>';
	}
	if ( $file ) {
		return '<img src="' . esc_url( meguru_img( $file ) ) . '" alt="' . esc_attr( $alt ) . '" onerror="ph(this)">';
	}
	return '';
}

function meguru_has_photo( $a, $prefix = '' ) {
	return ! empty( $a[ $prefix . 'imageId' ] ) || ! empty( $a[ $prefix . 'imageUrl' ] ) || ! empty( $a[ $prefix . 'illust' ] );
}

/* ==========================================================
   公開ページの HTML（hp/index.html・hp/history.html と同じ形）
   ========================================================== */

function meguru_render_hero( $a ) {
	$bg = wp_strip_all_tags( $a['bg'] );
	ob_start();
	?>
<section class="hero"<?php echo meguru_id( $a ); ?> data-intro data-hero>
  <div class="hero__sticky">
    <?php if ( $bg ) : ?><p class="hero__bg" aria-hidden="true"><span><?php echo esc_html( str_repeat( $bg . "\u{00A0}", 4 ) ); ?></span></p><?php endif; ?>
    <div class="hero__scene" data-scene></div>
    <div class="hero__copy">
      <?php if ( $a['label'] ) : ?><p class="hero__label"><?php echo meguru_mask( $a['label'] ); ?></p><?php endif; ?>
      <h1 class="hero__title"><?php echo meguru_lines( $a['title'] ); ?></h1>
      <?php if ( $a['name'] || $a['nameEn'] ) : ?><p class="hero__name"><span class="mask"><span><?php echo meguru_kses( $a['name'] ); ?><?php if ( $a['nameEn'] ) : ?> <i><?php echo meguru_kses( $a['nameEn'] ); ?></i><?php endif; ?></span></span></p><?php endif; ?>
    </div>
    <?php if ( $a['hint'] ) : ?><p class="hero__hint" aria-hidden="true"><?php echo meguru_br( $a['hint'] ); ?></p><?php endif; ?>
    <p class="hero__scroll" aria-hidden="true">Scroll</p>
  </div>
</section>
	<?php
	return ob_get_clean();
}

function meguru_render_intro( $a ) {
	$out = '<section class="intro"' . meguru_id( $a ) . '><p class="intro__text" data-reveal>' . meguru_lines( $a['text'] ) . '</p>';
	if ( $a['sub'] ) {
		$out .= '<p class="intro__sub" data-reveal="up">' . meguru_br( $a['sub'] ) . '</p>';
	}
	return $out . '</section>';
}

function meguru_render_photo( $a ) {
	return '<figure class="wide frame" data-reveal="clip"' . meguru_id( $a ) . '><div class="frame__in" data-parallax="0.18">' . meguru_frame( $a ) . '</div></figure>';
}

function meguru_render_pair( $a ) {
	return '<div class="pair"' . meguru_id( $a ) . '>'
		. '<figure class="pair__a frame" data-reveal="clip"><div class="frame__in" data-parallax="0.1">' . meguru_frame( $a, 'a' ) . '</div></figure>'
		. '<figure class="pair__b frame" data-reveal="clip-side"><div class="frame__in" data-parallax="-0.08">' . meguru_frame( $a, 'b' ) . '</div></figure>'
		. '</div>';
}

function meguru_render_chapter( $a, $content ) {
	$photo = meguru_has_photo( $a );
	$cls   = 'chapter' . ( $a['reverse'] ? ' chapter--rev' : '' ) . ( $photo ? ' chapter--photo' : '' );
	$out   = '<article class="' . $cls . '"' . meguru_id( $a ) . '>';
	$out  .= '<p class="chapter__word" data-reveal>' . meguru_mask( $a['word'] ) . '</p>';
	$out  .= '<div class="chapter__body"><h2 class="chapter__title" data-reveal>' . meguru_lines( $a['title'] ) . '</h2>' . meguru_reveal( $content ) . '</div>';
	if ( $photo ) {
		$out .= '<figure class="chapter__photo frame" data-reveal="clip"><div class="frame__in" data-parallax="0.1">' . meguru_frame( $a ) . '</div></figure>';
	}
	return $out . '</article>';
}

function meguru_render_band( $a ) {
	return '<section class="band"' . meguru_id( $a ) . '>'
		. '<figure class="band__photo frame"><div class="frame__in" data-parallax="0.25">' . meguru_frame( $a ) . '</div></figure>'
		. '<p class="band__text" data-reveal>' . meguru_lines( $a['text'] ) . '</p>'
		. '</section>';
}

function meguru_render_feature( $a, $content ) {
	$out = '<section class="line"' . meguru_id( $a ) . '>';
	$out .= '<div class="line__head">';
	if ( $a['tag'] ) {
		$out .= '<p class="tag" data-reveal="up">' . meguru_kses( $a['tag'] ) . '</p>';
	}
	$out .= '<h2 class="line__title" data-reveal>' . meguru_lines( $a['title'] ) . '</h2></div>';
	if ( meguru_has_photo( $a ) ) {
		$out .= '<figure class="line__illust frame" data-reveal="clip"><div class="frame__in" data-parallax="0.08">' . meguru_frame( $a ) . '</div></figure>';
	}
	$out .= '<div class="line__body">' . meguru_reveal( $content ) . '</div>';

	if ( $a['swapShow'] ) {
		$from = '';
		foreach ( meguru_split( $a['swapFrom'] ) as $f ) {
			$from .= '<s>' . meguru_kses( $f ) . '</s>';
		}
		if ( $a['swapFromNote'] ) {
			$from .= '<small>' . meguru_kses( $a['swapFromNote'] ) . '</small>';
		}
		$stamp = '';
		if ( $a['stamp'] || $a['stampNum'] || $a['stampUnit'] ) {
			$stamp = '<span class="line__stamp">' . meguru_kses( $a['stamp'] ) . '<br><em>' . meguru_kses( $a['stampNum'] ) . '</em>' . meguru_kses( $a['stampUnit'] ) . '</span>';
		}
		$out .= '<div class="line__swap" data-reveal="up"><p class="line__from">' . $from . '</p><p class="line__arrow" aria-hidden="true">→</p><p class="line__to"><b>' . meguru_kses( $a['swapTo'] ) . '</b>' . $stamp . '</p></div>';
	}
	if ( $a['note'] ) {
		$out .= '<p class="line__note" data-reveal="up">' . meguru_br( $a['note'] ) . '</p>';
	}
	if ( $a['promiseShow'] ) {
		$out .= '<div class="line__promise"><h3 class="line__promise-title" data-reveal>' . meguru_lines( $a['promiseTitle'] ) . '</h3>'
			. '<div class="line__promise-body">' . meguru_br_paras( $a['promiseBody'] ) . '</div></div>';
	}
	return $out . '</section>';
}

function meguru_render_works( $a ) {
	$out  = '<section class="works"' . meguru_id( $a ) . ' data-pin><div class="works__sticky"><div class="works__track">';
	$out .= '<div class="works__head"><h2 class="works__title" data-reveal>' . meguru_lines( $a['title'] ) . '</h2>';
	if ( $a['sub'] ) {
		$out .= '<p data-reveal="up">' . meguru_kses( $a['sub'] ) . '</p>';
	}
	$out .= '</div>';
	foreach ( meguru_works() as $w ) {
		$out .= '<a class="work" href="' . esc_url( $w['url'] ) . '" target="_blank" rel="noopener">'
			. '<figure class="work__thumb frame" data-reveal="clip"><div class="frame__in">'
			. ( $w['img'] ? '<img src="' . esc_url( $w['img'] ) . '" alt="' . esc_attr( $w['alt'] ) . '" loading="lazy" onerror="ph(this)">' : '' )
			. '</div></figure>'
			. '<p class="work__meta">' . esc_html( $w['meta'] ) . '</p>'
			. '<h3 class="work__name">' . esc_html( $w['name'] ) . ' <i aria-hidden="true">↗</i></h3></a>';
	}
	return $out . '</div></div></section>';
}

function meguru_render_profile( $a, $content ) {
	$name = wp_strip_all_tags( $a['name'] );
	$out  = '<section class="me"' . meguru_id( $a ) . '>';
	$out .= '<figure class="me__photo frame" data-reveal="clip"><div class="frame__in" data-parallax="0.08">' . meguru_frame( $a, '', $name, 'me.jpg' ) . '</div></figure>';
	$out .= '<div class="me__body"><p class="me__name" data-reveal>' . meguru_mask( $a['name'] );
	if ( $a['nameEn'] ) {
		$out .= '<span class="mask"><span><i>' . meguru_kses( $a['nameEn'] ) . '</i></span></span>';
	}
	$out .= '</p>' . meguru_reveal( $content );
	if ( $a['moreText'] ) {
		$url  = $a['moreUrl'] ? $a['moreUrl'] : meguru_history_url();
		$out .= '<p class="me__more" data-reveal="up"><a href="' . esc_url( $url ) . '">' . meguru_kses( $a['moreText'] ) . ' <i aria-hidden="true">→</i></a></p>';
	}
	return $out . '</div></section>';
}

function meguru_render_news( $a ) {
	$posts = get_posts( array(
		'post_type'      => 'post',
		'posts_per_page' => max( 1, (int) $a['count'] ),
		'post_status'    => 'publish',
	) );
	if ( ! $posts ) {
		return '';
	}
	$all = get_option( 'page_for_posts' ) ? get_permalink( get_option( 'page_for_posts' ) ) : '';
	$out = '<section class="news"' . meguru_id( $a ) . '><div class="news__head"><h2 class="news__title" data-reveal>' . meguru_lines( $a['title'] ? $a['title'] : 'お知らせ' ) . '</h2>';
	if ( $all ) {
		$out .= '<a class="news__all" href="' . esc_url( $all ) . '">一覧を見る <i aria-hidden="true">→</i></a>';
	}
	$out .= '</div><ul class="news__list">';
	foreach ( $posts as $p ) {
		$out .= '<li data-reveal="up"><a href="' . esc_url( get_permalink( $p ) ) . '"><time datetime="' . esc_attr( get_the_date( 'c', $p ) ) . '">' . esc_html( get_the_date( 'Y.m.d', $p ) ) . '</time><span>' . esc_html( get_the_title( $p ) ) . '</span></a></li>';
	}
	return $out . '</ul></section>';
}

function meguru_render_contact( $a, $content ) {
	$out = '<section class="close"' . meguru_id( $a ) . '><h2 class="close__title" data-reveal>' . meguru_lines( $a['title'] ) . '</h2>' . meguru_reveal( $content );
	if ( $a['mail'] ) {
		$link = '<a class="close__mail" href="' . esc_url( 'mailto:' . meguru_mail() ) . '">' . esc_html( meguru_mail() ) . '</a>';
		$out .= '<p data-reveal="up">' . str_replace( '{mail}', $link, meguru_kses( $a['mail'] ) ) . '</p>';
	}
	return $out . '</section>';
}

function meguru_render_page_hero( $a ) {
	$out = '<section class="h-hero"' . meguru_id( $a ) . ' data-intro>';
	if ( $a['tag'] ) {
		$out .= '<p class="tag h-hero__tag">' . meguru_mask( $a['tag'] ) . '</p>';
	}
	$out .= '<h1 class="h-hero__title">' . meguru_lines( $a['title'] ) . '</h1>';
	if ( $a['lead'] ) {
		$out .= '<p class="h-hero__lead">' . meguru_lines( $a['lead'] ) . '</p>';
	}
	if ( meguru_has_photo( $a ) ) {
		$out .= '<figure class="h-hero__illust frame" data-reveal="clip"><div class="frame__in" data-parallax="0.08">' . meguru_frame( $a ) . '</div></figure>';
	}
	return $out . '</section>';
}

function meguru_render_story( $a, $content ) {
	$rev = ! empty( $a['reverse'] );
	$out = '<article class="h-chap' . ( $rev ? ' h-chap--rev' : '' ) . '"' . meguru_id( $a ) . '>';
	$out .= '<figure class="h-chap__illust frame" data-reveal="' . ( $rev ? 'clip-side' : 'clip' ) . '"><div class="frame__in" data-parallax="0.08">' . meguru_frame( $a ) . '</div></figure>';
	$out .= '<div class="h-chap__body">';
	if ( $a['no'] ) {
		$out .= '<p class="h-chap__no" data-reveal="up">' . meguru_kses( $a['no'] ) . '</p>';
	}
	$out .= '<h2 class="h-chap__word" data-reveal>' . meguru_mask( $a['word'] ) . '</h2>';
	if ( $a['sub'] ) {
		$out .= '<p class="h-chap__sub" data-reveal="up">' . meguru_kses( $a['sub'] ) . '</p>';
	}
	return $out . meguru_reveal( $content ) . '</div></article>';
}

function meguru_render_steps( $a, $content ) {
	$out = '<section class="record"' . meguru_id( $a ) . '>';
	if ( $a['title'] ) {
		$out .= '<h2 class="record__title" data-reveal>' . meguru_lines( $a['title'] ) . '</h2>';
	}
	return $out . '<ol class="record__list">' . trim( $content ) . '</ol></section>';
}

function meguru_render_step( $a ) {
	return '<li' . ( $a['now'] ? ' class="is-now"' : '' ) . ' data-reveal="up"><span class="record__step">' . meguru_kses( $a['step'] ) . '</span><b>' . meguru_kses( $a['name'] ) . '</b><p>' . meguru_kses( $a['text'] ) . '</p></li>';
}
