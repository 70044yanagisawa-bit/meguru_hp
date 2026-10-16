<?php
/**
 * めぐるHP制作 — テーマの設定
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'MEGURU_VER', '1.0.0' );

require get_template_directory() . '/inc/works.php';

/* ---------- 基本 ---------- */
add_action( 'after_setup_theme', function () {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'html5', array( 'script', 'style', 'gallery', 'caption' ) );
} );

/* ---------- テーマを有効にしたとき：「これまでのこと」のページと、はじめの実績2件を用意する ---------- */
add_action( 'after_switch_theme', function () {
	if ( ! get_page_by_path( 'history' ) ) {
		wp_insert_post( array(
			'post_type'   => 'page',
			'post_status' => 'publish',
			'post_title'  => 'これまでのこと',
			'post_name'   => 'history',
		) );
	}
	meguru_register_work_type();
	meguru_seed_works();
	flush_rewrite_rules();
} );

/* ---------- CSS・JS ---------- */
function meguru_asset_ver( $rel ) {
	$file = get_template_directory() . '/' . $rel;
	return file_exists( $file ) ? (string) filemtime( $file ) : MEGURU_VER;
}

add_action( 'wp_enqueue_scripts', function () {
	$uri = get_template_directory_uri();

	wp_enqueue_style( 'meguru-fonts', 'https://fonts.googleapis.com/css2?family=Fredoka:wght@500;600;700&family=Zen+Kaku+Gothic+New:wght@400;500;700&family=Zen+Maru+Gothic:wght@500;700&display=swap', array(), null );
	wp_enqueue_style( 'meguru', $uri . '/assets/css/main.css', array( 'meguru-fonts' ), meguru_asset_ver( 'assets/css/main.css' ) );
	wp_enqueue_style( 'meguru-wp', $uri . '/assets/css/wp.css', array( 'meguru' ), meguru_asset_ver( 'assets/css/wp.css' ) );

	$args = array( 'in_footer' => true );
	wp_enqueue_script( 'meguru-draw', $uri . '/assets/js/draw.js', array(), meguru_asset_ver( 'assets/js/draw.js' ), $args );
	$deps = array( 'meguru-draw' );
	if ( is_front_page() ) {
		// 「YM」の島はトップだけ
		wp_enqueue_script( 'meguru-scene', $uri . '/assets/js/scene.js', $deps, meguru_asset_ver( 'assets/js/scene.js' ), $args );
		$deps[] = 'meguru-scene';
	}
	wp_enqueue_script( 'meguru-illust', $uri . '/assets/js/illust.js', $deps, meguru_asset_ver( 'assets/js/illust.js' ), $args );
	wp_enqueue_script( 'meguru-main', $uri . '/assets/js/main.js', array( 'meguru-illust' ), meguru_asset_ver( 'assets/js/main.js' ), $args );

	// このテーマはブロックを使わないので、ブロック用の CSS は読み込まない（見た目が静的版とずれないように）
	wp_dequeue_style( 'wp-block-library' );
	wp_dequeue_style( 'wp-block-library-theme' );
	wp_dequeue_style( 'global-styles' );
	wp_dequeue_style( 'classic-theme-styles' );
}, 20 );

add_action( 'wp_head', function () {
	echo '<link rel="preconnect" href="https://fonts.googleapis.com">' . "\n";
	echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>' . "\n";
}, 1 );

// 絵文字のスクリプトは使わない
remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'wp_print_styles', 'print_emoji_styles' );

/* ---------- タイトル・説明文・OGP ---------- */
add_filter( 'pre_get_document_title', function ( $title ) {
	if ( is_front_page() ) {
		return 'めぐるHP制作 | 柳沢恵瑠';
	}
	if ( is_page( 'history' ) ) {
		return 'これまでのこと | めぐるHP制作';
	}
	return $title;
} );

add_filter( 'document_title_separator', function () {
	return '|';
} );

function meguru_meta() {
	if ( is_front_page() ) {
		return array(
			'title' => 'めぐるHP制作 | 柳沢恵瑠',
			'desc'  => '柳沢恵瑠がひとりで請けているホームページ制作。話を聞くところから、LINEとのつながり、公開後の見直しまで。',
			'og'    => '話を聞くところから、ホームページをつくっています。',
		);
	}
	if ( is_page( 'history' ) ) {
		return array(
			'title' => 'これまでのこと | めぐるHP制作',
			'desc'  => '柳沢恵瑠のこれまで。消防士、フルコミッション営業、SNSマーケティング、コーチングを経て、いまはホームページ制作とAI導入の仕事をしています。',
			'og'    => '興味を持ってくれた方へ。少しだけ、遠回りの話を。',
		);
	}
	$desc = is_singular() ? wp_strip_all_tags( get_the_excerpt() ) : get_bloginfo( 'description' );
	return array(
		'title' => wp_get_document_title(),
		'desc'  => $desc,
		'og'    => $desc,
	);
}

add_action( 'wp_head', function () {
	$m   = meguru_meta();
	$ogp = get_template_directory() . '/assets/images/ogp.jpg';
	echo '<meta name="description" content="' . esc_attr( $m['desc'] ) . '">' . "\n";
	echo '<meta property="og:title" content="' . esc_attr( $m['title'] ) . '">' . "\n";
	echo '<meta property="og:description" content="' . esc_attr( $m['og'] ) . '">' . "\n";
	echo '<meta property="og:type" content="website">' . "\n";
	echo '<meta property="og:url" content="' . esc_url( is_singular() ? get_permalink() : home_url( '/' ) ) . '">' . "\n";
	if ( file_exists( $ogp ) ) {
		echo '<meta property="og:image" content="' . esc_url( get_template_directory_uri() . '/assets/images/ogp.jpg' ) . '">' . "\n";
	}
	echo '<meta name="theme-color" content="#0d7a43">' . "\n";
	if ( ! has_site_icon() ) {
		echo '<link rel="icon" href="' . esc_url( get_template_directory_uri() . '/favicon.svg' ) . '" type="image/svg+xml">' . "\n";
	}
}, 2 );

/* ---------- カスタマイザー：メールアドレスとメインサイトのURL ---------- */
add_action( 'customize_register', function ( $wp_customize ) {
	$wp_customize->add_section( 'meguru', array(
		'title'    => 'めぐるHP制作',
		'priority' => 30,
	) );

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
		'label'   => '「柳沢恵瑠のサイト」のURL',
		'section' => 'meguru',
		'type'    => 'url',
	) );
} );

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
