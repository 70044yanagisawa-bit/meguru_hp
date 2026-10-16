<?php
/**
 * 「実績」（これまでにつくったもの）
 * 管理画面の「実績」から追加する。
 *   タイトル        … サイト名（例：HARBOR cafe & shisha bar）
 *   アイキャッチ画像 … サムネイル（トップ画面のスクリーンショット・横長 16:10）
 *   右の「実績の情報」… 種類（例：個人コーチングのホームページ）とサイトのURL
 *   並び順          … 「ページ属性」の順序（小さい数字が左）
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function meguru_register_work_type() {
	register_post_type( 'meguru_work', array(
		'labels'        => array(
			'name'               => '実績',
			'singular_name'      => '実績',
			'add_new'            => '新規追加',
			'add_new_item'       => '実績を追加',
			'edit_item'          => '実績を編集',
			'all_items'          => '実績の一覧',
			'not_found'          => '実績はまだありません',
			'featured_image'     => 'サムネイル（横長 16:10）',
			'set_featured_image' => 'サムネイルを設定',
		),
		'public'        => false,
		'show_ui'       => true,
		'menu_icon'     => 'dashicons-portfolio',
		'menu_position' => 20,
		'supports'      => array( 'title', 'thumbnail', 'page-attributes' ),
	) );
}
add_action( 'init', 'meguru_register_work_type' );

/* ---------- 種類・URL の入力欄 ---------- */
add_action( 'add_meta_boxes', function () {
	add_meta_box( 'meguru_work_info', '実績の情報', 'meguru_work_box', 'meguru_work', 'normal', 'high' );
} );

function meguru_work_box( $post ) {
	wp_nonce_field( 'meguru_work_save', 'meguru_work_nonce' );
	$meta = get_post_meta( $post->ID, '_meguru_meta', true );
	$url  = get_post_meta( $post->ID, '_meguru_url', true );
	?>
	<p>
		<label for="meguru_meta"><b>種類</b>（サイト名の上に小さく出ます）</label><br>
		<input type="text" id="meguru_meta" name="meguru_meta" value="<?php echo esc_attr( $meta ); ?>" class="widefat" placeholder="例：個人コーチングのホームページ">
	</p>
	<p>
		<label for="meguru_url"><b>サイトのURL</b>（画像を押すと開きます）</label><br>
		<input type="url" id="meguru_url" name="meguru_url" value="<?php echo esc_attr( $url ); ?>" class="widefat" placeholder="https://">
	</p>
	<?php
}

add_action( 'save_post_meguru_work', function ( $post_id ) {
	if ( ! isset( $_POST['meguru_work_nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['meguru_work_nonce'] ), 'meguru_work_save' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}
	if ( isset( $_POST['meguru_meta'] ) ) {
		update_post_meta( $post_id, '_meguru_meta', sanitize_text_field( wp_unslash( $_POST['meguru_meta'] ) ) );
	}
	if ( isset( $_POST['meguru_url'] ) ) {
		update_post_meta( $post_id, '_meguru_url', esc_url_raw( wp_unslash( $_POST['meguru_url'] ) ) );
	}
} );

/* ---------- 一覧に URL を表示 ---------- */
add_filter( 'manage_meguru_work_posts_columns', function ( $cols ) {
	$cols['meguru_url'] = 'URL';
	return $cols;
} );
add_action( 'manage_meguru_work_posts_custom_column', function ( $col, $post_id ) {
	if ( 'meguru_url' === $col ) {
		echo esc_html( get_post_meta( $post_id, '_meguru_url', true ) );
	}
}, 10, 2 );

/* ---------- はじめの2件 ---------- */
function meguru_default_works() {
	return array(
		array(
			'name' => 'GOAL ACHIEVEMENT PROGRAM',
			'meta' => '個人コーチングのホームページ',
			'url'  => 'https://haltakagi.com/',
			'file' => 'works/site-01.jpg',
		),
		array(
			'name' => 'HARBOR cafe & shisha bar',
			'meta' => 'シーシャカフェ＆バーのLINE公式アカウント運用',
			'url'  => 'https://harbor-shisha.com/',
			'file' => 'works/site-02.jpg',
		),
	);
}

/**
 * テーマを初めて有効にしたとき、はじめの2件を「実績」に登録する（画像もメディアに入れる）。
 * 一度登録したら、消しても作り直さない。
 */
function meguru_seed_works() {
	if ( get_option( 'meguru_works_seeded' ) ) {
		return;
	}
	update_option( 'meguru_works_seeded', 1 );

	$exists = get_posts( array( 'post_type' => 'meguru_work', 'post_status' => 'any', 'posts_per_page' => 1, 'fields' => 'ids' ) );
	if ( $exists ) {
		return;
	}

	require_once ABSPATH . 'wp-admin/includes/image.php';

	foreach ( meguru_default_works() as $i => $w ) {
		$id = wp_insert_post( array(
			'post_type'   => 'meguru_work',
			'post_status' => 'publish',
			'post_title'  => $w['name'],
			'menu_order'  => $i + 1,
		) );
		if ( ! $id || is_wp_error( $id ) ) {
			continue;
		}
		update_post_meta( $id, '_meguru_meta', $w['meta'] );
		update_post_meta( $id, '_meguru_url', $w['url'] );

		$src = get_template_directory() . '/assets/images/' . $w['file'];
		if ( ! file_exists( $src ) ) {
			continue;
		}
		$up = wp_upload_bits( basename( $src ), null, file_get_contents( $src ) );
		if ( ! empty( $up['error'] ) ) {
			continue;
		}
		$att = wp_insert_attachment( array(
			'post_mime_type' => 'image/jpeg',
			'post_title'     => $w['name'],
		), $up['file'], $id );
		if ( $att && ! is_wp_error( $att ) ) {
			wp_update_attachment_metadata( $att, wp_generate_attachment_metadata( $att, $up['file'] ) );
			update_post_meta( $att, '_wp_attachment_image_alt', $w['name'] . ' のトップページ' );
			set_post_thumbnail( $id, $att );
		}
	}
}

/**
 * トップに並べる実績。
 * 管理画面に1件もないときは、はじめの2件を出す。
 */
function meguru_works() {
	$posts = get_posts( array(
		'post_type'      => 'meguru_work',
		'post_status'    => 'publish',
		'posts_per_page' => -1,
		'orderby'        => array( 'menu_order' => 'ASC', 'date' => 'ASC' ),
	) );

	if ( ! $posts ) {
		return array_map( function ( $w ) {
			return array(
				'name' => $w['name'],
				'meta' => $w['meta'],
				'url'  => $w['url'],
				'img'  => meguru_img( $w['file'] ),
				'alt'  => $w['name'] . ' のトップページ',
			);
		}, meguru_default_works() );
	}

	return array_map( function ( $p ) {
		$name = $p->post_title;
		return array(
			'name' => $name,
			'meta' => get_post_meta( $p->ID, '_meguru_meta', true ),
			'url'  => get_post_meta( $p->ID, '_meguru_url', true ),
			'img'  => get_the_post_thumbnail_url( $p, 'large' ),
			'alt'  => wp_strip_all_tags( $name ) . ' のトップページ',
		);
	}, $posts );
}
