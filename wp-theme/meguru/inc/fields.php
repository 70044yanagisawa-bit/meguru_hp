<?php
/**
 * トップと「これまでのこと」を、管理画面から編集できるようにする入力欄。
 *
 * 固定ページ「トップページ」「これまでのこと」の編集画面に、
 * セクションごとの入力欄が出る。中身はページのカスタムフィールド（_meguru_fields）に入る。
 * 何も入れていない項目は、下の default（いまの文章）が出る。
 *
 * 入力欄の種類
 *   text     … 1行
 *   lines    … 見出し。1行ずつ下からせり上がる（改行＝行の区切り）
 *   paras    … 本文。1行＝1段落
 *   textarea … 改行がそのまま改行になる
 *   image    … 画像（メディアから選ぶ）。空なら default の絵や写真
 *   toggle   … このセクションを表示するかどうか
 *   repeater … 行を追加・削除・並べ替えできる表
 * 文章の中では <b> <i> <br> <small> <a href=""> が使える。
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* ==========================================================
   どんな入力欄があるか（いまの文章を default に入れてある）
   ========================================================== */

function meguru_illust_choices() {
	return array(
		''         => '（絵なし）',
		'cafe'     => 'カフェで打ち合わせ',
		'listen'   => '話を聞く',
		'mindmap'  => 'マインドマップ',
		'phone'    => 'スマホでLINE',
		'pc'       => 'パソコン',
		'town'     => '街',
		'line'     => 'LINE',
		'chart'    => 'グラフ',
		'train'    => 'トレーニング',
		'stairs'   => '階段',
		'protect'  => '守る（消防）',
		'sell'     => '売る（営業）',
		'sns'      => 'SNS',
	);
}

function meguru_schema( $page ) {
	$img_help = '写真を選ぶと、イラストの代わりに写真が出ます。空にするとイラストに戻ります。';

	$seo = array(
		'title'  => '検索・SNSでの説明',
		'fields' => array(),
	);

	if ( 'front' === $page ) {
		$seo['fields'] = array(
			'seo_title' => array( 'label' => 'ページのタイトル（ブラウザのタブや検索結果に出る）', 'type' => 'text', 'default' => 'めぐるHP制作 | 柳沢恵瑠' ),
			'seo_desc' => array( 'label' => '検索結果に出る説明文', 'type' => 'textarea', 'default' => '柳沢恵瑠がひとりで請けているホームページ制作。話を聞くところから、LINEとのつながり、公開後の見直しまで。' ),
			'seo_og'   => array( 'label' => 'SNSでシェアされたときの説明文', 'type' => 'textarea', 'default' => '話を聞くところから、ホームページをつくっています。' ),
		);

		return array(
			'hero'  => array(
				'title'  => 'ファーストビュー（いちばん上）',
				'fields' => array(
					'hero_label' => array( 'label' => '小さな見出し', 'type' => 'text', 'default' => 'めぐるHP制作' ),
					'hero_title' => array( 'label' => '大きな見出し', 'type' => 'lines', 'default' => "話を聞くことから、\nホームページを\nつくっています。" ),
					'hero_name'  => array( 'label' => '名前', 'type' => 'text', 'default' => '柳沢恵瑠 <i>Meguru Yanagisawa</i>', 'help' => '<i>〜</i> で囲んだところは英字のフォントになります。' ),
					'hero_bg'    => array( 'label' => 'うしろの大きな英字', 'type' => 'text', 'default' => 'MEGURU' ),
					'hero_hint'  => array( 'label' => 'スクロールの案内', 'type' => 'textarea', 'default' => "スクロールすると、\nパーツが集まって「YM」になります。" ),
				),
			),
			'intro' => array(
				'title'  => 'はじめに',
				'fields' => array(
					'intro_text' => array( 'label' => '大きな文章', 'type' => 'lines', 'default' => "ホームページをつくる仕事をしています。\nといっても、最初の時間のほとんどは、\n話を聞いています。" ),
					'intro_sub'  => array( 'label' => '小さな文章', 'type' => 'textarea', 'default' => "どんな人に来てほしいのか。これまで何を大事にしてきたのか。\n言葉になる前のところまで聞けたら、つくるのはそのあとです。" ),
					'img_wide'   => array( 'label' => '下の大きな写真', 'type' => 'image', 'illust' => 'cafe', 'help' => $img_help ),
				),
			),
			'ch1'   => array(
				'title'  => '章1（聞く）',
				'fields' => array(
					'ch1_word'  => array( 'label' => '大きな一言', 'type' => 'text', 'default' => '聞く' ),
					'ch1_title' => array( 'label' => '見出し', 'type' => 'lines', 'default' => "うまく言えなくて、\n大丈夫です。" ),
					'ch1_body'  => array( 'label' => '本文', 'type' => 'paras', 'default' => "副業でコーチをしていて、年間でおよそ250人と話します。そこで知ったのは、自分のことをいちばん言葉にしにくいのは、本人だということでした。\nだから最初は、ホームページの話をあまりしません。お店や仕事を始めたきっかけ、よく来てくれるお客さんのこと、ちょっと困っていること。雑談のような時間の中に、ページに載せたい言葉はだいたい埋まっています。" ),
					'img_pair_a' => array( 'label' => '下の写真（左）', 'type' => 'image', 'illust' => 'mindmap', 'help' => $img_help ),
					'img_pair_b' => array( 'label' => '下の写真（右）', 'type' => 'image', 'illust' => 'phone', 'help' => $img_help ),
				),
			),
			'ch2'   => array(
				'title'  => '章2（つくる）',
				'fields' => array(
					'ch2_word'  => array( 'label' => '大きな一言', 'type' => 'text', 'default' => 'つくる' ),
					'ch2_title' => array( 'label' => '見出し', 'type' => 'lines', 'default' => "早くて、\n雑じゃないように。" ),
					'ch2_body'  => array( 'label' => '本文', 'type' => 'paras', 'default' => "制作の途中にAIを組み込んでいるので、ヒアリングから公開まで、早ければ1週間ほどです。そのぶん、話を聞く時間と、見た目を整える時間は削らないようにしています。\nデザインは一緒に画面を見ながら、納得いくまで直します。ドメインや公開の手続きも、こちらでまとめて引き受けます。このサイトも、自分でつくりました。" ),
				),
			),
			'band'  => array(
				'title'  => '写真の上を通る言葉',
				'fields' => array(
					'band_show' => array( 'label' => 'このセクションを表示する', 'type' => 'toggle', 'default' => '1' ),
					'band_text' => array( 'label' => '言葉', 'type' => 'lines', 'default' => "きれいに見えることより、\nその人らしさが伝わること。" ),
					'img_band'  => array( 'label' => '写真', 'type' => 'image', 'illust' => 'town', 'help' => $img_help ),
				),
			),
			'ch3'   => array(
				'title'  => '章3（つなぐ）',
				'fields' => array(
					'ch3_show'  => array( 'label' => 'このセクションを表示する', 'type' => 'toggle', 'default' => '1' ),
					'ch3_word'  => array( 'label' => '大きな一言', 'type' => 'text', 'default' => 'つなぐ' ),
					'ch3_title' => array( 'label' => '見出し', 'type' => 'lines', 'default' => "見てくれた人と、\nそのあとも。" ),
					'ch3_body'  => array( 'label' => '本文', 'type' => 'paras', 'default' => "ホームページは、見てもらって終わりではないと思っています。せっかく気になってくれた人との出会いが、一度きりで終わってしまうのはもったいない。\nだから私は、ホームページをつくったあと、LINEの構築までひと続きで引き受けています。" ),
				),
			),
			'line'  => array(
				'title'  => '緑の囲み（LINE構築）',
				'fields' => array(
					'line_show'          => array( 'label' => 'このセクションを表示する', 'type' => 'toggle', 'default' => '1' ),
					'line_tag'           => array( 'label' => 'ラベル', 'type' => 'text', 'default' => 'LINE' ),
					'line_title'         => array( 'label' => '見出し', 'type' => 'lines', 'default' => "ホームページの先に、\nLINEを。" ),
					'img_line'           => array( 'label' => '写真', 'type' => 'image', 'illust' => 'line', 'help' => $img_help ),
					'line_body'          => array( 'label' => '本文', 'type' => 'paras', 'default' => "LINEで自動の返信やステップ配信、予約の案内をしようとすると、Lステップやエルメのような有料のツールを使うのが一般的です。とても便利な一方で、月々の料金はずっとかかり続けます。\n私は、オープンソースの<b>LINE Harness（LINEハーネス）</b>を使って、同じような仕組みをつくります。友だちの管理、シナリオ配信、タグ付け、リッチメニューの切り替え。必要なものはそろっていて、ツールの月額はかかりません。\nアカウントの開設から、配信の設計、構築、実際に動かしてみての調整まで。途中で誰かに引き継ぐことなく、私が最後まで伴走します。" ),
					'line_swap_show'     => array( 'label' => '「→ 0円」の比べる図を表示する', 'type' => 'toggle', 'default' => '1' ),
					'line_from'          => array( 'label' => '比べる図：左（線で消すもの。1行に1つ）', 'type' => 'textarea', 'default' => "Lステップ\nエルメ" ),
					'line_from_note'     => array( 'label' => '比べる図：左の小さな文字', 'type' => 'text', 'default' => '月額のツール代' ),
					'line_to'            => array( 'label' => '比べる図：右', 'type' => 'text', 'default' => 'LINE Harness' ),
					'line_stamp'         => array( 'label' => '比べる図：黄色い丸（上）', 'type' => 'text', 'default' => 'ツール代' ),
					'line_stamp_num'     => array( 'label' => '比べる図：黄色い丸（大きな数字）', 'type' => 'text', 'default' => '0' ),
					'line_stamp_unit'    => array( 'label' => '比べる図：黄色い丸（単位）', 'type' => 'text', 'default' => '円' ),
					'line_note'          => array( 'label' => '注意書き', 'type' => 'textarea', 'default' => '※ LINE公式アカウントそのものの月額プランは、送るメッセージの数によって別にかかることがあります。' ),
					'line_promise_title' => array( 'label' => '下の見出し', 'type' => 'lines', 'default' => "直しは、\n納得いくまで。" ),
					'line_promise_body'  => array( 'label' => '下の本文', 'type' => 'paras', 'default' => "回数で区切ることはしません。言葉のひとつ、ボタンの色ひとつでも、気になるところがあれば言ってください。\n使いはじめてから「ここ、こうしたいな」が出てくることも、たくさんあります。そのたびに、一緒に直していきます。あなたのお店や仕事に、ちゃんと役立つものになるまで。" ),
				),
			),
			'ch4'   => array(
				'title'  => '章4（見つづける）',
				'fields' => array(
					'ch4_show'  => array( 'label' => 'このセクションを表示する', 'type' => 'toggle', 'default' => '1' ),
					'ch4_word'  => array( 'label' => '大きな一言', 'type' => 'text', 'default' => '見つづける' ),
					'ch4_title' => array( 'label' => '見出し', 'type' => 'lines', 'default' => "公開してからが、\n本番です。" ),
					'ch4_body'  => array( 'label' => '本文', 'type' => 'paras', 'default' => "公開したあとは、GA4で数字を見ます。どこから来て、どのページで読むのをやめているのか。それを見ながら、言葉や順番を少しずつ直していきます。\n数字の見方もお伝えするので、ご自身でも様子がわかるようになります。" ),
					'img_ch4'   => array( 'label' => '写真', 'type' => 'image', 'illust' => 'chart', 'help' => $img_help ),
				),
			),
			'works' => array(
				'title'  => '実績（一覧そのものは左メニューの「実績」から）',
				'fields' => array(
					'works_show'  => array( 'label' => 'このセクションを表示する', 'type' => 'toggle', 'default' => '1' ),
					'works_title' => array( 'label' => '見出し', 'type' => 'lines', 'default' => "これまでに\nつくったもの" ),
					'works_sub'   => array( 'label' => '小さな文章', 'type' => 'text', 'default' => '画像を押すと、実際のサイトが開きます。' ),
				),
			),
			'me'    => array(
				'title'  => 'プロフィール',
				'fields' => array(
					'me_show'    => array( 'label' => 'このセクションを表示する', 'type' => 'toggle', 'default' => '1' ),
					'me_photo'   => array( 'label' => '写真（縦長）', 'type' => 'image', 'file' => 'me.jpg' ),
					'me_name'    => array( 'label' => '名前', 'type' => 'text', 'default' => '柳沢恵瑠' ),
					'me_name_en' => array( 'label' => '名前（英字）', 'type' => 'text', 'default' => 'Meguru Yanagisawa' ),
					'me_body'    => array( 'label' => '本文', 'type' => 'paras', 'default' => "はじめまして、柳沢恵瑠（やなぎさわ めぐる）です。\n消防士として3年半、現場にいました。そのあと完全歩合の営業、SNSマーケティングの会社での運用と法人営業を経て、いまはAIの導入支援と、ホームページの制作をしています。\n遠回りに見えるかもしれません。でも、人の話を聞くこと、相手の立場で伝えること、仕組みで手間を減らすこと。どれも、いまの仕事でいちばん役に立っています。" ),
					'me_more'    => array( 'label' => '「これまでのこと」へのリンクの文字（空にするとリンクを出さない）', 'type' => 'text', 'default' => 'これまでのことを、もう少し詳しく' ),
				),
			),
			'close' => array(
				'title'  => 'おわりに（お問い合わせ）',
				'fields' => array(
					'close_title' => array( 'label' => '見出し', 'type' => 'lines', 'default' => "話してみたいと\n思ったら。" ),
					'close_body'  => array( 'label' => '本文', 'type' => 'paras', 'default' => '何をつくるか決まっていなくても、かまいません。「うちにホームページって要るのかな」くらいの話から、一緒に考えます。' ),
					'close_mail'  => array( 'label' => 'メールの案内（{mail} のところにメールアドレスのリンクが入る）', 'type' => 'text', 'default' => 'メールは、{mail} まで。', 'help' => 'メールアドレスは「外観 → カスタマイズ → めぐるHP制作」で変えられます。' ),
				),
			),
			'seo'   => $seo,
		);
	}

	// これまでのこと
	$seo['fields'] = array(
		'seo_title' => array( 'label' => 'ページのタイトル（ブラウザのタブや検索結果に出る）', 'type' => 'text', 'default' => 'これまでのこと | めぐるHP制作' ),
		'seo_desc' => array( 'label' => '検索結果に出る説明文', 'type' => 'textarea', 'default' => '柳沢恵瑠のこれまで。消防士、フルコミッション営業、SNSマーケティング、コーチングを経て、いまはホームページ制作とAI導入の仕事をしています。' ),
		'seo_og'   => array( 'label' => 'SNSでシェアされたときの説明文', 'type' => 'textarea', 'default' => '興味を持ってくれた方へ。少しだけ、遠回りの話を。' ),
	);

	return array(
		'h_hero'   => array(
			'title'  => 'はじまり（いちばん上）',
			'fields' => array(
				'h_tag'   => array( 'label' => 'ラベル', 'type' => 'text', 'default' => 'History' ),
				'h_title' => array( 'label' => '大きな見出し', 'type' => 'lines', 'default' => "これまでの\nこと。" ),
				'h_lead'  => array( 'label' => 'リード文', 'type' => 'lines', 'default' => "興味を持ってくれた方へ。\n少しだけ、遠回りの話を。" ),
				'h_img'   => array( 'label' => '写真', 'type' => 'image', 'illust' => 'stairs', 'help' => $img_help ),
			),
		),
		'h_chaps'  => array(
			'title'  => '章（左右交互に並ぶ）',
			'fields' => array(
				'h_chapters' => array(
					'label'   => '章',
					'type'    => 'repeater',
					'add'     => '章を追加',
					'fields'  => array(
						'no'     => array( 'label' => '番号', 'type' => 'text' ),
						'word'   => array( 'label' => '大きな一言', 'type' => 'text' ),
						'sub'    => array( 'label' => '小見出し', 'type' => 'text' ),
						'body'   => array( 'label' => '本文（1行＝1段落）', 'type' => 'paras' ),
						'illust' => array( 'label' => 'イラスト', 'type' => 'select', 'choices' => meguru_illust_choices() ),
						'image'  => array( 'label' => '写真（選ぶとイラストの代わりに出る）', 'type' => 'image', 'empty' => '（写真なし：上で選んだイラストが出ます）' ),
					),
					'default' => array(
						array( 'no' => '01', 'word' => '守る', 'sub' => '陸上自衛隊 / 消防士', 'body' => '消防士になりたくて、陸上自衛隊を経て消防へ。3年半、消火・救急・救助・予防の現場にいました。', 'illust' => 'protect' ),
						array( 'no' => '02', 'word' => '売る', 'sub' => '外資系の訪問販売会社 / フルコミッション営業', 'body' => '完全歩合の訪問販売へ。Weeklyで全国1位をとり、半年でコアメンバーに入りました。', 'illust' => 'sell' ),
						array( 'no' => '03', 'word' => '届ける', 'sub' => 'SNSマーケティングの会社', 'body' => 'SNSマーケティングの会社で、SNS運用と法人営業。幹部評価をもらい、リーダーとしてSalesforceの導入も進めました。', 'illust' => 'sns' ),
						array( 'no' => '04', 'word' => '聴く', 'sub' => 'コーチングの会社（副業）', 'body' => '副業でコーチに。年間およそ250人と話し、人が動けない理由の奥を一緒に探しています。', 'illust' => 'listen' ),
						array( 'no' => '05', 'word' => 'いま', 'sub' => 'AI導入支援 / HP制作 / LINE構築', 'body' => 'AIの導入とHP制作。遠回りだと思っていた経験が、いちばん役に立っているのがここです。', 'illust' => 'pc' ),
					),
				),
			),
		),
		'h_record' => array(
			'title'  => '経歴の要点',
			'fields' => array(
				'record_show'  => array( 'label' => 'このセクションを表示する', 'type' => 'toggle', 'default' => '1' ),
				'record_title' => array( 'label' => '見出し', 'type' => 'lines', 'default' => "ひとつずつ、\nのぼってきた。" ),
				'record_items' => array(
					'label'   => '項目',
					'type'    => 'repeater',
					'add'     => '項目を追加',
					'fields'  => array(
						'step' => array( 'label' => 'ラベル（Step 01 など）', 'type' => 'text' ),
						'name' => array( 'label' => '名前', 'type' => 'text' ),
						'text' => array( 'label' => '説明', 'type' => 'text' ),
						'now'  => array( 'label' => '「いま」として黄色で目立たせる', 'type' => 'toggle' ),
					),
					'default' => array(
						array( 'step' => 'Step 01', 'name' => '陸上自衛隊', 'text' => '初期教育を修了' ),
						array( 'step' => 'Step 02', 'name' => '消防士', 'text' => '消火・救急・救助・予防業務に3年半' ),
						array( 'step' => 'Step 03', 'name' => '外資系の訪問販売会社', 'text' => 'フルコミッション営業。Weekly全国1位。半年でコアメンバー、12人の組織づくりに関わる' ),
						array( 'step' => 'Step 04', 'name' => 'SNSマーケティングの会社', 'text' => '2023年 幹部評価1.1（社内75人中3人）。入社1年でリーダー。法人営業でSalesforceの導入を進める' ),
						array( 'step' => 'Step 05', 'name' => 'コーチングの会社', 'text' => 'コーチ（副業）。年間約250人とセッション。リブースト／セルフコア合宿のサポートコーチ' ),
						array( 'step' => 'Now', 'name' => 'AI導入・業務効率化 / HP制作・LINE構築', 'text' => 'これまでの全部を使って、いまの仕事へ。', 'now' => '1' ),
					),
				),
			),
		),
		'h_close'  => array(
			'title'  => 'おわりに',
			'fields' => array(
				'h_close_title' => array( 'label' => '見出し', 'type' => 'lines', 'default' => "そして、\nいまの仕事へ。" ),
				'h_close_link'  => array( 'label' => 'トップへ戻るリンクの文字', 'type' => 'text', 'default' => 'めぐるHP制作のトップへ戻る' ),
			),
		),
		'seo'      => $seo,
	);
}

/* ==========================================================
   どのページが「トップ」「これまでのこと」か
   ========================================================== */

function meguru_page_kind( $post_id ) {
	$post_id = (int) $post_id;
	if ( ! $post_id ) {
		return '';
	}
	if ( 'page' === get_option( 'show_on_front' ) && (int) get_option( 'page_on_front' ) === $post_id ) {
		return 'front';
	}
	$page = get_post( $post_id );
	if ( $page && 'page' === $page->post_type && 'history' === $page->post_name ) {
		return 'history';
	}
	return '';
}

/** いま表示しているページのフィールドの持ち主（トップを固定ページにしていないときは 0 ＝ 全部 default） */
function meguru_current_id() {
	if ( is_front_page() ) {
		return 'page' === get_option( 'show_on_front' ) ? (int) get_option( 'page_on_front' ) : 0;
	}
	return (int) get_queried_object_id();
}

function meguru_field_def( $kind, $key ) {
	foreach ( meguru_schema( $kind ) as $section ) {
		if ( isset( $section['fields'][ $key ] ) ) {
			return $section['fields'][ $key ];
		}
	}
	return null;
}

/* ==========================================================
   値を取り出す（テンプレートで使う）
   ========================================================== */

function meguru_f( $key ) {
	$kind = is_front_page() ? 'front' : 'history';
	$def  = meguru_field_def( $kind, $key );
	$id   = meguru_current_id();
	$vals = $id ? get_post_meta( $id, '_meguru_fields', true ) : array();
	if ( is_array( $vals ) && array_key_exists( $key, $vals ) ) {
		return $vals[ $key ];
	}
	return isset( $def['default'] ) ? $def['default'] : '';
}

function meguru_allowed_html() {
	return array(
		'b'      => array(),
		'strong' => array(),
		'i'      => array(),
		'em'     => array(),
		'small'  => array(),
		's'      => array(),
		'br'     => array(),
		'a'      => array( 'href' => true, 'target' => true, 'rel' => true ),
	);
}

/** 1行の文字（<b> などは使える） */
function meguru_t( $key ) {
	return wp_kses( (string) meguru_f( $key ), meguru_allowed_html() );
}

function meguru_kses( $s ) {
	return wp_kses( (string) $s, meguru_allowed_html() );
}

function meguru_split( $s ) {
	return array_values( array_filter( array_map( 'trim', preg_split( '/\R/u', (string) $s ) ), 'strlen' ) );
}

/** 見出し：1行ずつ <span class="mask"> で包む */
function meguru_lines( $key, $indent = '          ' ) {
	$out = array();
	foreach ( meguru_split( meguru_f( $key ) ) as $line ) {
		$out[] = $indent . '<span class="mask"><span>' . meguru_kses( $line ) . '</span></span>';
	}
	return implode( "\n", $out ) . "\n";
}

/** 本文：1行＝1段落 */
function meguru_paras_html( $text, $indent = '        ' ) {
	$out = array();
	foreach ( meguru_split( $text ) as $line ) {
		$out[] = $indent . '<p data-reveal="up">' . meguru_kses( $line ) . '</p>';
	}
	return implode( "\n", $out ) . "\n";
}

function meguru_paras( $key, $indent = '        ' ) {
	return meguru_paras_html( meguru_f( $key ), $indent );
}

/** 改行をそのまま <br> に */
function meguru_br( $key ) {
	return implode( '<br>', array_map( 'meguru_kses', meguru_split( meguru_f( $key ) ) ) );
}

function meguru_on( $key ) {
	return '1' === (string) meguru_f( $key );
}

/** 写真の枠の中身：写真が選ばれていれば写真、なければイラスト（またはテーマの写真） */
function meguru_frame_inner( $image_id, $illust = '', $file = '', $alt = '' ) {
	$image_id = (int) $image_id;
	if ( $image_id && wp_attachment_is_image( $image_id ) ) {
		return wp_get_attachment_image( $image_id, 'large', false, array( 'alt' => $alt, 'onerror' => 'ph(this)' ) );
	}
	if ( $file ) {
		return '<img src="' . esc_url( meguru_img( $file ) ) . '" alt="' . esc_attr( $alt ) . '" onerror="ph(this)">';
	}
	if ( $illust ) {
		return '<div class="illust" data-illust="' . esc_attr( $illust ) . '"></div>';
	}
	return '';
}

function meguru_frame( $key, $alt = '' ) {
	$kind = is_front_page() ? 'front' : 'history';
	$def  = meguru_field_def( $kind, $key );
	return meguru_frame_inner(
		meguru_f( $key ),
		isset( $def['illust'] ) ? $def['illust'] : '',
		isset( $def['file'] ) ? $def['file'] : '',
		$alt
	);
}

/* ==========================================================
   管理画面：入力欄
   ========================================================== */

// この2ページはブロックエディタではなく、入力欄の画面で編集する
add_filter( 'use_block_editor_for_post', function ( $use, $post ) {
	return meguru_page_kind( $post->ID ) ? false : $use;
}, 10, 2 );

add_action( 'add_meta_boxes_page', function ( $post ) {
	$kind = meguru_page_kind( $post->ID );
	if ( ! $kind ) {
		return;
	}
	remove_post_type_support( 'page', 'editor' );

	$vals  = get_post_meta( $post->ID, '_meguru_fields', true );
	$vals  = is_array( $vals ) ? $vals : array();
	$first = true;
	foreach ( meguru_schema( $kind ) as $sid => $section ) {
		add_meta_box( 'meguru_' . $sid, $section['title'], function () use ( $section, $vals, &$first ) {
			if ( $first ) {
				wp_nonce_field( 'meguru_fields_save', 'meguru_fields_nonce' );
				$first = false;
			}
			echo '<div class="meguru-fields">';
			foreach ( $section['fields'] as $key => $def ) {
				$val = array_key_exists( $key, $vals ) ? $vals[ $key ] : ( isset( $def['default'] ) ? $def['default'] : '' );
				meguru_render_field( 'meguru[' . $key . ']', $def, $val );
			}
			echo '</div>';
		}, 'page', 'normal', 'high' );
	}
} );

// 「編集中のページはトップページです」の案内
add_action( 'edit_form_after_title', function ( $post ) {
	$kind = meguru_page_kind( $post->ID );
	if ( ! $kind ) {
		return;
	}
	echo '<div class="notice notice-info inline" style="margin:16px 0"><p>';
	echo 'front' === $kind
		? 'このページは<b>トップページ</b>です。下の欄の文章や写真を直して「更新」を押すと、サイトに反映されます。'
		: 'このページは<b>「これまでのこと」</b>です。下の欄の文章や写真を直して「更新」を押すと、サイトに反映されます。';
	echo '<br>見出しは<b>改行したところで行が分かれ</b>、本文は<b>1行が1段落</b>になります。</p></div>';
} );

function meguru_render_field( $name, $def, $val ) {
	$type  = $def['type'];
	$id    = 'mf_' . md5( $name );
	$label = '<label class="meguru-label" for="' . esc_attr( $id ) . '">' . esc_html( $def['label'] ) . '</label>';
	$help  = ! empty( $def['help'] ) ? '<p class="description">' . wp_kses_post( $def['help'] ) . '</p>' : '';

	echo '<div class="meguru-field meguru-field--' . esc_attr( $type ) . '">';
	switch ( $type ) {
		case 'text':
			echo $label; // phpcs:ignore WordPress.Security.EscapeOutput
			echo '<input type="text" class="widefat" id="' . esc_attr( $id ) . '" name="' . esc_attr( $name ) . '" value="' . esc_attr( $val ) . '">';
			break;

		case 'lines':
		case 'paras':
		case 'textarea':
			$rows = max( 'paras' === $type ? 4 : 2, count( meguru_split( $val ) ) + ( 'paras' === $type ? 2 : 1 ) );
			echo $label; // phpcs:ignore WordPress.Security.EscapeOutput
			echo '<textarea class="widefat" rows="' . (int) $rows . '" id="' . esc_attr( $id ) . '" name="' . esc_attr( $name ) . '">' . esc_textarea( $val ) . '</textarea>';
			break;

		case 'toggle':
			echo '<input type="hidden" name="' . esc_attr( $name ) . '" value="0">';
			echo '<label class="meguru-toggle"><input type="checkbox" name="' . esc_attr( $name ) . '" value="1"' . checked( '1', (string) $val, false ) . '> ' . esc_html( $def['label'] ) . '</label>';
			break;

		case 'select':
			echo $label; // phpcs:ignore WordPress.Security.EscapeOutput
			echo '<select id="' . esc_attr( $id ) . '" name="' . esc_attr( $name ) . '">';
			foreach ( $def['choices'] as $k => $text ) {
				echo '<option value="' . esc_attr( $k ) . '"' . selected( (string) $k, (string) $val, false ) . '>' . esc_html( $text ) . '</option>';
			}
			echo '</select>';
			break;

		case 'image':
			$src = $val ? wp_get_attachment_image_url( (int) $val, 'medium' ) : '';
			$fallback = ! empty( $def['empty'] ) ? $def['empty'] : ( ! empty( $def['illust'] ) ? '（いまはイラスト）' : ( ! empty( $def['file'] ) ? '（いまは最初から入っている写真）' : '（なし）' ) );
			echo '<span class="meguru-label">' . esc_html( $def['label'] ) . '</span>';
			echo '<div class="meguru-image" data-empty="' . esc_attr( $fallback ) . '">';
			echo '<input type="hidden" name="' . esc_attr( $name ) . '" value="' . esc_attr( $val ) . '">';
			echo '<div class="meguru-image__preview">' . ( $src ? '<img src="' . esc_url( $src ) . '" alt="">' : '<span>' . esc_html( $fallback ) . '</span>' ) . '</div>';
			echo '<button type="button" class="button meguru-image__pick">写真を選ぶ</button> ';
			echo '<button type="button" class="button-link meguru-image__clear">外す</button>';
			echo '</div>';
			break;

		case 'repeater':
			$rows = is_array( $val ) ? $val : array();
			echo '<span class="meguru-label">' . esc_html( $def['label'] ) . '</span>';
			echo '<div class="meguru-rep" data-name="' . esc_attr( $name ) . '">';
			echo '<div class="meguru-rep__rows">';
			foreach ( array_values( $rows ) as $i => $row ) {
				meguru_render_row( $name, $i, $def['fields'], $row );
			}
			echo '</div>';
			echo '<script type="text/html" class="meguru-rep__tpl">';
			ob_start();
			meguru_render_row( $name, '__i__', $def['fields'], array() );
			echo str_replace( '</script', '<\/script', ob_get_clean() ); // phpcs:ignore WordPress.Security.EscapeOutput
			echo '</script>';
			echo '<p><button type="button" class="button meguru-rep__add">＋ ' . esc_html( $def['add'] ) . '</button></p>';
			echo '<input type="hidden" name="' . esc_attr( $name ) . '[__present]" value="1">';
			echo '</div>';
			break;
	}
	echo $help; // phpcs:ignore WordPress.Security.EscapeOutput
	echo '</div>';
}

function meguru_render_row( $name, $i, $fields, $row ) {
	echo '<div class="meguru-rep__row">';
	echo '<div class="meguru-rep__bar"><span class="meguru-rep__no"></span>';
	echo '<button type="button" class="button-link meguru-rep__up" aria-label="上へ">↑</button>';
	echo '<button type="button" class="button-link meguru-rep__down" aria-label="下へ">↓</button>';
	echo '<button type="button" class="button-link button-link-delete meguru-rep__del">削除</button></div>';
	foreach ( $fields as $k => $def ) {
		meguru_render_field( $name . '[' . $i . '][' . $k . ']', $def, isset( $row[ $k ] ) ? $row[ $k ] : '' );
	}
	echo '</div>';
}

/* ---------- 保存 ---------- */
function meguru_sanitize( $def, $val ) {
	switch ( $def['type'] ) {
		case 'image':
			return $val ? (string) absint( $val ) : '';
		case 'toggle':
			return '1' === (string) $val ? '1' : '0';
		case 'select':
			return array_key_exists( (string) $val, $def['choices'] ) ? (string) $val : '';
		case 'text':
			return trim( wp_kses( str_replace( array( "\r", "\n" ), ' ', (string) $val ), meguru_allowed_html() ) );
		case 'repeater':
			$rows = array();
			if ( is_array( $val ) ) {
				unset( $val['__present'] );
				foreach ( $val as $row ) {
					if ( ! is_array( $row ) ) {
						continue;
					}
					$clean = array();
					foreach ( $def['fields'] as $k => $sub ) {
						$clean[ $k ] = meguru_sanitize( $sub, isset( $row[ $k ] ) ? $row[ $k ] : '' );
					}
					// 全部空の行は捨てる
					if ( implode( '', array_diff_key( $clean, array( 'now' => 0 ) ) ) !== '' ) {
						$rows[] = $clean;
					}
				}
			}
			return $rows;
		default: // lines / paras / textarea
			return trim( wp_kses( str_replace( "\r", '', (string) $val ), meguru_allowed_html() ) );
	}
}

add_action( 'save_post_page', function ( $post_id ) {
	if ( ! isset( $_POST['meguru_fields_nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['meguru_fields_nonce'] ), 'meguru_fields_save' ) ) {
		return;
	}
	if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) || ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}
	$kind = meguru_page_kind( $post_id );
	if ( ! $kind || ! isset( $_POST['meguru'] ) || ! is_array( $_POST['meguru'] ) ) {
		return;
	}
	$in   = wp_unslash( $_POST['meguru'] ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput -- 下で項目ごとに整える
	$vals = array();
	foreach ( meguru_schema( $kind ) as $section ) {
		foreach ( $section['fields'] as $key => $def ) {
			if ( array_key_exists( $key, $in ) ) {
				$vals[ $key ] = meguru_sanitize( $def, $in[ $key ] );
			}
		}
	}
	update_post_meta( $post_id, '_meguru_fields', $vals );
} );

/* ---------- 入力欄の見た目と動き（写真を選ぶ・行を足す） ---------- */
add_action( 'admin_enqueue_scripts', function ( $hook ) {
	if ( ! in_array( $hook, array( 'post.php', 'post-new.php' ), true ) ) {
		return;
	}
	$post = get_post();
	if ( ! $post || ! meguru_page_kind( $post->ID ) ) {
		return;
	}
	wp_enqueue_media();
	$uri = get_template_directory_uri();
	wp_enqueue_style( 'meguru-admin', $uri . '/assets/admin/fields.css', array(), meguru_asset_ver( 'assets/admin/fields.css' ) );
	wp_enqueue_script( 'meguru-admin', $uri . '/assets/admin/fields.js', array( 'jquery' ), meguru_asset_ver( 'assets/admin/fields.js' ), true );
} );

/* ---------- 「固定ページ」一覧で、どのページか分かるように ---------- */
add_filter( 'display_post_states', function ( $states, $post ) {
	if ( 'history' === meguru_page_kind( $post->ID ) ) {
		$states['meguru_history'] = 'これまでのこと（入力欄で編集）';
	}
	return $states;
}, 10, 2 );
