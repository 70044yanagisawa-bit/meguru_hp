<?php
/**
 * めぐるHP制作 — テーマの設定
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'MEGURU_VER', '2.0.0' );

require get_template_directory() . '/inc/works.php';
require get_template_directory() . '/inc/blocks.php';
require get_template_directory() . '/inc/content.php';

/* ---------- 基本 ---------- */
add_action( 'after_setup_theme', function () {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'html5', array( 'script', 'style', 'gallery', 'caption', 'search-form', 'comment-list' ) );
	add_theme_support( 'responsive-embeds' );
	add_post_type_support( 'page', 'excerpt' ); // 抜粋＝検索結果に出る説明文

	// 編集画面でも、公開ページと同じ見た目にする
	add_theme_support( 'editor-styles' );
	add_editor_style( array(
		meguru_fonts_url(),
		'assets/css/main.css',
		'assets/css/wp.css',
		'assets/editor/editor.css',
	) );
} );

/* ---------- テーマを有効にしたとき：ページ・実績・お知らせを用意する ---------- */
add_action( 'after_switch_theme', function () {
	meguru_register_work_type();
	meguru_setup_pages();
	meguru_seed_works();
	flush_rewrite_rules();
} );

/* ---------- CSS・JS ---------- */
function meguru_asset_ver( $rel ) {
	$file = get_template_directory() . '/' . $rel;
	return file_exists( $file ) ? (string) filemtime( $file ) : MEGURU_VER;
}

function meguru_fonts_url() {
	return 'https://fonts.googleapis.com/css2?family=Fredoka:wght@500;600;700&family=Zen+Kaku+Gothic+New:wght@400;500;700&family=Zen+Maru+Gothic:wght@500;700&display=swap';
}

/** いま表示しているページに「ファーストビュー（YMの島）」ブロックがあるか */
function meguru_has_hero() {
	return is_singular() && has_block( 'meguru/hero', get_queried_object_id() );
}

add_action( 'wp_enqueue_scripts', function () {
	$uri = get_template_directory_uri();

	wp_enqueue_style( 'meguru-fonts', meguru_fonts_url(), array(), null );
	wp_enqueue_style( 'meguru', $uri . '/assets/css/main.css', array( 'meguru-fonts' ), meguru_asset_ver( 'assets/css/main.css' ) );
	wp_enqueue_style( 'meguru-wp', $uri . '/assets/css/wp.css', array( 'meguru' ), meguru_asset_ver( 'assets/css/wp.css' ) );

	$args = array( 'in_footer' => true );
	wp_enqueue_script( 'meguru-draw', $uri . '/assets/js/draw.js', array(), meguru_asset_ver( 'assets/js/draw.js' ), $args );
	$deps = array( 'meguru-draw' );
	if ( meguru_has_hero() ) {
		// 「YM」の島は、ファーストビューのブロックがあるページだけ
		wp_enqueue_script( 'meguru-scene', $uri . '/assets/js/scene.js', $deps, meguru_asset_ver( 'assets/js/scene.js' ), $args );
		$deps[] = 'meguru-scene';
	}
	wp_enqueue_script( 'meguru-illust', $uri . '/assets/js/illust.js', $deps, meguru_asset_ver( 'assets/js/illust.js' ), $args );
	wp_enqueue_script( 'meguru-main', $uri . '/assets/js/main.js', array( 'meguru-illust' ), meguru_asset_ver( 'assets/js/main.js' ), $args );
}, 20 );

add_action( 'wp_head', function () {
	echo '<link rel="preconnect" href="https://fonts.googleapis.com">' . "\n";
	echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>' . "\n";
}, 1 );

// 絵文字のスクリプトは使わない
remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'wp_print_styles', 'print_emoji_styles' );

/* ---------- タイトル・説明文・OGP ----------
   タイトルは WordPress の標準（トップ＝「サイトのタイトル | キャッチフレーズ」、ほか＝「ページ名 | サイトのタイトル」）。
   説明文は、ページの「抜粋」。SEO のプラグインを入れたときは、そちらにまかせる。 */
add_filter( 'document_title_separator', function () {
	return '|';
} );

function meguru_seo_plugin_active() {
	return defined( 'WPSEO_VERSION' ) || defined( 'SEO_SIMPLE_PACK_VERSION' ) || defined( 'RANK_MATH_VERSION' ) || defined( 'AIOSEO_VERSION' );
}

function meguru_description() {
	if ( is_singular() ) {
		$post = get_queried_object();
		if ( $post && has_excerpt( $post ) ) {
			return wp_strip_all_tags( get_the_excerpt( $post ) );
		}
		if ( $post && ! is_front_page() && 'page' !== $post->post_type ) {
			return wp_trim_words( wp_strip_all_tags( strip_shortcodes( $post->post_content ) ), 80, '…' );
		}
	}
	return get_bloginfo( 'description' );
}

add_action( 'wp_head', function () {
	echo '<meta name="theme-color" content="#0d7a43">' . "\n";
	if ( ! has_site_icon() ) {
		echo '<link rel="icon" href="' . esc_url( get_template_directory_uri() . '/favicon.svg' ) . '" type="image/svg+xml">' . "\n";
	}
	if ( meguru_seo_plugin_active() ) {
		return;
	}
	$desc  = meguru_description();
	$image = is_singular() && has_post_thumbnail() && ! is_front_page() ? get_the_post_thumbnail_url( null, 'large' ) : '';
	if ( ! $image && file_exists( get_template_directory() . '/assets/images/ogp.jpg' ) ) {
		$image = get_template_directory_uri() . '/assets/images/ogp.jpg';
	}
	echo '<meta name="description" content="' . esc_attr( $desc ) . '">' . "\n";
	echo '<meta property="og:title" content="' . esc_attr( wp_get_document_title() ) . '">' . "\n";
	echo '<meta property="og:description" content="' . esc_attr( $desc ) . '">' . "\n";
	echo '<meta property="og:type" content="' . ( is_single() ? 'article' : 'website' ) . '">' . "\n";
	echo '<meta property="og:url" content="' . esc_url( is_singular() ? get_permalink() : home_url( '/' ) ) . '">' . "\n";
	if ( $image ) {
		echo '<meta property="og:image" content="' . esc_url( $image ) . '">' . "\n";
	}
}, 2 );

/* ---------- カスタマイザー：ロゴ・フッター・メールアドレス ---------- */
add_action( 'customize_register', function ( $wp_customize ) {
	$wp_customize->add_section( 'meguru', array(
		'title'    => 'めぐるHP制作',
		'priority' => 30,
	) );

	foreach ( meguru_brand_defaults() as $key => $d ) {
		$wp_customize->add_setting( $key, array(
			'default'           => $d[1],
			'sanitize_callback' => 'sanitize_text_field',
		) );
		$wp_customize->add_control( $key, array(
			'label'   => $d[0],
			'section' => 'meguru',
			'type'    => 'text',
		) );
	}

	$wp_customize->add_setting( 'meguru_mail', array(
		'default'           => '70044yanagisawa@gmail.com',
		'sanitize_callback' => 'sanitize_email',
	) );
	$wp_customize->add_control( 'meguru_mail', array(
		'label'   => '連絡先のメールアドレス',
		'section' => 'meguru',
		'type'    => 'email',
	) );

	$wp_customize->add_setting( 'meguru_main_url', array(
		'default'           => 'https://meguru-hp.mgr-lab.workers.dev/',
		'sanitize_callback' => 'esc_url_raw',
	) );
	$wp_customize->add_control( 'meguru_main_url', array(
		'label'   => '右上のボタンのリンク先URL',
		'section' => 'meguru',
		'type'    => 'url',
	) );
} );

/** ロゴ・フッターの文字（外観 → カスタマイズ → めぐるHP制作） */
function meguru_brand_defaults() {
	return array(
		'meguru_logo'       => array( 'ロゴ（大きい文字）', 'めぐる' ),
		'meguru_logo_sub'   => array( 'ロゴ（小さい文字）', 'HP制作' ),
		'meguru_link_text'  => array( '右上のボタンの文字（空にすると出さない）', '柳沢恵瑠のサイト' ),
		'meguru_footer_big' => array( 'フッターの大きな英字', 'Meguru' ),
		'meguru_footer'     => array( 'フッターの名前', 'めぐるHP制作 ／ 柳沢恵瑠' ),
		'meguru_copyright'  => array( 'コピーライトの名前', 'Meguru Yanagisawa' ),
	);
}

function meguru_brand( $key ) {
	$d = meguru_brand_defaults();
	return get_theme_mod( $key, $d[ $key ][1] );
}

function meguru_mail() {
	return get_theme_mod( 'meguru_mail', '70044yanagisawa@gmail.com' );
}

function meguru_main_url() {
	return get_theme_mod( 'meguru_main_url', 'https://meguru-hp.mgr-lab.workers.dev/' );
}

/* ---------- 小さな道具 ---------- */
function meguru_img( $rel ) {
	return get_template_directory_uri() . '/assets/images/' . $rel;
}

function meguru_history_url() {
	$page = get_page_by_path( 'history' );
	return $page ? get_permalink( $page ) : home_url( '/history/' );
}

/** ページの最初のブロックが、このテーマのブロックかどうか（ページ名の見出しを出すかを決める） */
function meguru_starts_with_block( $post = null ) {
	$post = get_post( $post );
	if ( ! $post || ! has_blocks( $post->post_content ) ) {
		return false;
	}
	foreach ( parse_blocks( $post->post_content ) as $block ) {
		if ( $block['blockName'] ) {
			return 0 === strpos( $block['blockName'], 'meguru/' );
		}
	}
	return false;
}
