<?php
/**
 * 最初に入っている中身（いまの「めぐるHP制作」の文章）
 *
 * テーマを有効にしたとき、トップページ・これまでのことに、この中身をブロックとして入れる。
 * 同じものを「パターン」にも登録してあるので、＋ →「パターン」→「めぐる」から何度でも入れ直せる。
 * クライアントのサイトをつくるときは、ここの文章を差し替える。
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/** ブロック1つぶんのコメント付き HTML */
function meguru_b( $name, $attrs = array(), $inner = null ) {
	$json = $attrs ? ' ' . serialize_block_attributes( $attrs ) : '';
	if ( null === $inner ) {
		return "<!-- wp:{$name}{$json} /-->\n\n";
	}
	return "<!-- wp:{$name}{$json} -->\n{$inner}<!-- /wp:{$name} -->\n\n";
}

/** 段落ブロック（1行＝1段落） */
function meguru_p( $text ) {
	$out = '';
	foreach ( preg_split( '/\R/u', trim( $text ) ) as $line ) {
		if ( '' !== trim( $line ) ) {
			$out .= "<!-- wp:paragraph -->\n<p>" . trim( $line ) . "</p>\n<!-- /wp:paragraph -->\n\n";
		}
	}
	return $out;
}

function meguru_default_content( $page ) {
	if ( 'history' === $page ) {
		$chapters = array(
			array( '01', '守る', '陸上自衛隊 / 消防士', '消防士になりたくて、陸上自衛隊を経て消防へ。3年半、消火・救急・救助・予防の現場にいました。', 'protect' ),
			array( '02', '売る', '外資系の訪問販売会社 / フルコミッション営業', '完全歩合の訪問販売へ。Weeklyで全国1位をとり、半年でコアメンバーに入りました。', 'sell' ),
			array( '03', '届ける', 'SNSマーケティングの会社', 'SNSマーケティングの会社で、SNS運用と法人営業。幹部評価をもらい、リーダーとしてSalesforceの導入も進めました。', 'sns' ),
			array( '04', '聴く', 'コーチングの会社（副業）', '副業でコーチに。年間およそ250人と話し、人が動けない理由の奥を一緒に探しています。', 'listen' ),
			array( '05', 'いま', 'AI導入支援 / HP制作 / LINE構築', 'AIの導入とHP制作。遠回りだと思っていた経験が、いちばん役に立っているのがここです。', 'pc' ),
		);
		$steps = array(
			array( 'Step 01', '陸上自衛隊', '初期教育を修了' ),
			array( 'Step 02', '消防士', '消火・救急・救助・予防業務に3年半' ),
			array( 'Step 03', '外資系の訪問販売会社', 'フルコミッション営業。Weekly全国1位。半年でコアメンバー、12人の組織づくりに関わる' ),
			array( 'Step 04', 'SNSマーケティングの会社', '2023年 幹部評価1.1（社内75人中3人）。入社1年でリーダー。法人営業でSalesforceの導入を進める' ),
			array( 'Step 05', 'コーチングの会社', 'コーチ（副業）。年間約250人とセッション。リブースト／セルフコア合宿のサポートコーチ' ),
			array( 'Now', 'AI導入・業務効率化 / HP制作・LINE構築', 'これまでの全部を使って、いまの仕事へ。', true ),
		);

		$out = meguru_b( 'meguru/page-hero', array(
			'tag'    => 'History',
			'title'  => 'これまでの<br>こと。',
			'lead'   => '興味を持ってくれた方へ。<br>少しだけ、遠回りの話を。',
			'illust' => 'stairs',
		) );
		foreach ( $chapters as $i => $c ) {
			$out .= meguru_b( 'meguru/story', array(
				'no'      => $c[0],
				'word'    => $c[1],
				'sub'     => $c[2],
				'illust'  => $c[4],
				'reverse' => 1 === $i % 2,
			), meguru_p( $c[3] ) );
		}
		$inner = '';
		foreach ( $steps as $s ) {
			$inner .= meguru_b( 'meguru/step', array_filter( array(
				'step' => $s[0],
				'name' => $s[1],
				'text' => $s[2],
				'now'  => ! empty( $s[3] ),
			) ) );
		}
		$out .= meguru_b( 'meguru/steps', array( 'title' => 'ひとつずつ、<br>のぼってきた。' ), $inner );
		$out .= meguru_b( 'meguru/contact', array( 'title' => 'そして、<br>いまの仕事へ。' ), meguru_p( '<a class="close__mail" href="' . esc_url( home_url( '/' ) ) . '">めぐるHP制作のトップへ戻る</a>' ) );
		return $out;
	}

	// トップ
	$out  = meguru_b( 'meguru/hero', array(
		'label'  => 'めぐるHP制作',
		'title'  => '話を聞くことから、<br>ホームページを<br>つくっています。',
		'name'   => '柳沢恵瑠',
		'nameEn' => 'Meguru Yanagisawa',
		'bg'     => 'MEGURU',
		'hint'   => 'スクロールすると、<br>パーツが集まって「YM」になります。',
	) );
	$out .= meguru_b( 'meguru/intro', array(
		'text' => 'ホームページをつくる仕事をしています。<br>といっても、最初の時間のほとんどは、<br>話を聞いています。',
		'sub'  => 'どんな人に来てほしいのか。これまで何を大事にしてきたのか。<br>言葉になる前のところまで聞けたら、つくるのはそのあとです。',
	) );
	$out .= meguru_b( 'meguru/photo', array( 'illust' => 'cafe' ) );
	$out .= meguru_b( 'meguru/chapter', array( 'word' => '聞く', 'title' => 'うまく言えなくて、<br>大丈夫です。' ), meguru_p( '
副業でコーチをしていて、年間でおよそ250人と話します。そこで知ったのは、自分のことをいちばん言葉にしにくいのは、本人だということでした。
だから最初は、ホームページの話をあまりしません。お店や仕事を始めたきっかけ、よく来てくれるお客さんのこと、ちょっと困っていること。雑談のような時間の中に、ページに載せたい言葉はだいたい埋まっています。' ) );
	$out .= meguru_b( 'meguru/pair', array( 'aillust' => 'mindmap', 'billust' => 'phone' ) );
	$out .= meguru_b( 'meguru/chapter', array( 'word' => 'つくる', 'title' => '早くて、<br>雑じゃないように。', 'reverse' => true ), meguru_p( '
制作の途中にAIを組み込んでいるので、ヒアリングから公開まで、早ければ1週間ほどです。そのぶん、話を聞く時間と、見た目を整える時間は削らないようにしています。
デザインは一緒に画面を見ながら、納得いくまで直します。ドメインや公開の手続きも、こちらでまとめて引き受けます。このサイトも、自分でつくりました。' ) );
	$out .= meguru_b( 'meguru/band', array( 'text' => 'きれいに見えることより、<br>その人らしさが伝わること。', 'illust' => 'town' ) );
	$out .= meguru_b( 'meguru/chapter', array( 'word' => 'つなぐ', 'title' => '見てくれた人と、<br>そのあとも。' ), meguru_p( '
ホームページは、見てもらって終わりではないと思っています。せっかく気になってくれた人との出会いが、一度きりで終わってしまうのはもったいない。
だから私は、ホームページをつくったあと、LINEの構築までひと続きで引き受けています。' ) );
	$out .= meguru_b( 'meguru/feature', array(
		'anchor'       => 'line',
		'tag'          => 'LINE',
		'title'        => 'ホームページの先に、<br>LINEを。',
		'illust'       => 'line',
		'swapShow'     => true,
		'swapFrom'     => 'Lステップ<br>エルメ',
		'swapFromNote' => '月額のツール代',
		'swapTo'       => 'LINE Harness',
		'stamp'        => 'ツール代',
		'stampNum'     => '0',
		'stampUnit'    => '円',
		'note'         => '※ LINE公式アカウントそのものの月額プランは、送るメッセージの数によって別にかかることがあります。',
		'promiseShow'  => true,
		'promiseTitle' => '直しは、<br>納得いくまで。',
		'promiseBody'  => '回数で区切ることはしません。言葉のひとつ、ボタンの色ひとつでも、気になるところがあれば言ってください。<br>使いはじめてから「ここ、こうしたいな」が出てくることも、たくさんあります。そのたびに、一緒に直していきます。あなたのお店や仕事に、ちゃんと役立つものになるまで。',
	), meguru_p( '
LINEで自動の返信やステップ配信、予約の案内をしようとすると、Lステップやエルメのような有料のツールを使うのが一般的です。とても便利な一方で、月々の料金はずっとかかり続けます。
私は、オープンソースの<strong>LINE Harness（LINEハーネス）</strong>を使って、同じような仕組みをつくります。友だちの管理、シナリオ配信、タグ付け、リッチメニューの切り替え。必要なものはそろっていて、ツールの月額はかかりません。
アカウントの開設から、配信の設計、構築、実際に動かしてみての調整まで。途中で誰かに引き継ぐことなく、私が最後まで伴走します。' ) );
	$out .= meguru_b( 'meguru/chapter', array( 'word' => '見つづける', 'title' => '公開してからが、<br>本番です。', 'reverse' => true, 'illust' => 'chart' ), meguru_p( '
公開したあとは、GA4で数字を見ます。どこから来て、どのページで読むのをやめているのか。それを見ながら、言葉や順番を少しずつ直していきます。
数字の見方もお伝えするので、ご自身でも様子がわかるようになります。' ) );
	$out .= meguru_b( 'meguru/works', array( 'title' => 'これまでに<br>つくったもの', 'sub' => '画像を押すと、実際のサイトが開きます。' ) );
	$out .= meguru_b( 'meguru/profile', array(
		'name'     => '柳沢恵瑠',
		'nameEn'   => 'Meguru Yanagisawa',
		'moreText' => 'これまでのことを、もう少し詳しく',
	), meguru_p( '
はじめまして、柳沢恵瑠（やなぎさわ めぐる）です。
消防士として3年半、現場にいました。そのあと完全歩合の営業、SNSマーケティングの会社での運用と法人営業を経て、いまはAIの導入支援と、ホームページの制作をしています。
遠回りに見えるかもしれません。でも、人の話を聞くこと、相手の立場で伝えること、仕組みで手間を減らすこと。どれも、いまの仕事でいちばん役に立っています。' ) );
	$out .= meguru_b( 'meguru/news', array( 'title' => 'お知らせ', 'count' => 3 ) );
	$out .= meguru_b( 'meguru/contact', array(
		'title' => '話してみたいと<br>思ったら。',
		'mail'  => 'メールは、{mail} まで。',
	), meguru_p( '何をつくるか決まっていなくても、かまいません。「うちにホームページって要るのかな」くらいの話から、一緒に考えます。' ) );
	return $out;
}

/* ---------- パターン（＋ →「パターン」→「めぐる」） ---------- */
add_action( 'init', function () {
	register_block_pattern_category( 'meguru', array( 'label' => 'めぐる' ) );
	register_block_pattern( 'meguru/front', array(
		'title'       => 'トップページ一式',
		'description' => 'ファーストビューからお問い合わせまで、トップページのブロックがそろったひな形。',
		'categories'  => array( 'meguru' ),
		'content'     => meguru_default_content( 'front' ),
	) );
	register_block_pattern( 'meguru/history', array(
		'title'       => '経歴ページ一式',
		'description' => '見出し・番号つきの章・年表・しめくくりのひな形。',
		'categories'  => array( 'meguru' ),
		'content'     => meguru_default_content( 'history' ),
	) );
}, 20 );

/* ---------- テーマを有効にしたとき ---------- */
function meguru_setup_pages() {
	// トップページ
	$front_id = 0;
	if ( 'page' === get_option( 'show_on_front' ) && get_post( (int) get_option( 'page_on_front' ) ) ) {
		$front_id = (int) get_option( 'page_on_front' );
	} else {
		$front    = get_page_by_path( 'top' );
		$front_id = $front ? $front->ID : wp_insert_post( array(
			'post_type'   => 'page',
			'post_status' => 'publish',
			'post_title'  => 'トップページ',
			'post_name'   => 'top',
		) );
		if ( $front_id && ! is_wp_error( $front_id ) ) {
			update_option( 'show_on_front', 'page' );
			update_option( 'page_on_front', $front_id );
		}
	}
	meguru_fill_if_empty( $front_id, 'front' );

	// これまでのこと
	$history    = get_page_by_path( 'history' );
	$history_id = $history ? $history->ID : wp_insert_post( array(
		'post_type'   => 'page',
		'post_status' => 'publish',
		'post_title'  => 'これまでのこと',
		'post_name'   => 'history',
	) );
	meguru_fill_if_empty( $history_id, 'history' );

	// お知らせ（投稿の一覧ページ）
	if ( ! get_option( 'page_for_posts' ) ) {
		$news    = get_page_by_path( 'news' );
		$news_id = $news ? $news->ID : wp_insert_post( array(
			'post_type'   => 'page',
			'post_status' => 'publish',
			'post_title'  => 'お知らせ',
			'post_name'   => 'news',
		) );
		if ( $news_id && ! is_wp_error( $news_id ) ) {
			update_option( 'page_for_posts', $news_id );
		}
	}

	// お知らせの見本を1件
	if ( ! get_option( 'meguru_news_seeded' ) ) {
		update_option( 'meguru_news_seeded', 1 );
		wp_insert_post( array(
			'post_type'    => 'post',
			'post_status'  => 'publish',
			'post_title'   => 'ホームページを新しくしました',
			'post_content' => wp_slash( meguru_p( "ホームページを新しくしました。\nこれからは、このページでお知らせを書いていきます。" ) ),
		) );
	}
}

function meguru_fill_if_empty( $post_id, $page ) {
	if ( ! $post_id || is_wp_error( $post_id ) ) {
		return;
	}
	$post = get_post( $post_id );
	if ( $post && '' === trim( $post->post_content ) ) {
		// 抜粋＝検索結果に出る説明文
		$excerpt = 'history' === $page
			? '柳沢恵瑠のこれまで。消防士、フルコミッション営業、SNSマーケティング、コーチングを経て、いまはホームページ制作とAI導入の仕事をしています。'
			: '柳沢恵瑠がひとりで請けているホームページ制作。話を聞くところから、LINEとのつながり、公開後の見直しまで。';
		wp_update_post( array(
			'ID'           => $post_id,
			'post_content' => wp_slash( meguru_default_content( $page ) ), // 保存時に \ が消えないように
			'post_excerpt' => $post->post_excerpt ? $post->post_excerpt : $excerpt,
		) );
	}
}
