<?php
/**
 * 「これまでのこと」（hp/history.html と同じ内容）
 * スラッグが history の固定ページで使われる。テーマを有効にすると自動でページができる。
 */
get_header();
?>
  <main>

    <!-- はじまり -->
    <section class="h-hero" data-intro>
      <p class="tag h-hero__tag"><span class="mask"><span>History</span></span></p>
      <h1 class="h-hero__title">
        <span class="mask"><span>これまでの</span></span>
        <span class="mask"><span>こと。</span></span>
      </h1>
      <p class="h-hero__lead">
        <span class="mask"><span>興味を持ってくれた方へ。</span></span>
        <span class="mask"><span>少しだけ、遠回りの話を。</span></span>
      </p>
      <figure class="h-hero__illust frame" data-reveal="clip">
        <div class="frame__in" data-parallax="0.08">
          <div class="illust" data-illust="stairs"></div>
        </div>
      </figure>
    </section>

    <!-- 5つの章 -->
    <article class="h-chap">
      <figure class="h-chap__illust frame" data-reveal="clip">
        <div class="frame__in" data-parallax="0.08">
          <div class="illust" data-illust="protect"></div>
        </div>
      </figure>
      <div class="h-chap__body">
        <p class="h-chap__no" data-reveal="up">01</p>
        <h2 class="h-chap__word" data-reveal><span class="mask"><span>守る</span></span></h2>
        <p class="h-chap__sub" data-reveal="up">陸上自衛隊 / 消防士</p>
        <p data-reveal="up">消防士になりたくて、陸上自衛隊を経て消防へ。3年半、消火・救急・救助・予防の現場にいました。</p>
      </div>
    </article>

    <article class="h-chap h-chap--rev">
      <figure class="h-chap__illust frame" data-reveal="clip-side">
        <div class="frame__in" data-parallax="0.08">
          <div class="illust" data-illust="sell"></div>
        </div>
      </figure>
      <div class="h-chap__body">
        <p class="h-chap__no" data-reveal="up">02</p>
        <h2 class="h-chap__word" data-reveal><span class="mask"><span>売る</span></span></h2>
        <p class="h-chap__sub" data-reveal="up">外資系の訪問販売会社 / フルコミッション営業</p>
        <p data-reveal="up">完全歩合の訪問販売へ。Weeklyで全国1位をとり、半年でコアメンバーに入りました。</p>
      </div>
    </article>

    <article class="h-chap">
      <figure class="h-chap__illust frame" data-reveal="clip">
        <div class="frame__in" data-parallax="0.08">
          <div class="illust" data-illust="sns"></div>
        </div>
      </figure>
      <div class="h-chap__body">
        <p class="h-chap__no" data-reveal="up">03</p>
        <h2 class="h-chap__word" data-reveal><span class="mask"><span>届ける</span></span></h2>
        <p class="h-chap__sub" data-reveal="up">SNSマーケティングの会社</p>
        <p data-reveal="up">SNSマーケティングの会社で、SNS運用と法人営業。幹部評価をもらい、リーダーとしてSalesforceの導入も進めました。</p>
      </div>
    </article>

    <article class="h-chap h-chap--rev">
      <figure class="h-chap__illust frame" data-reveal="clip-side">
        <div class="frame__in" data-parallax="0.08">
          <div class="illust" data-illust="listen"></div>
        </div>
      </figure>
      <div class="h-chap__body">
        <p class="h-chap__no" data-reveal="up">04</p>
        <h2 class="h-chap__word" data-reveal><span class="mask"><span>聴く</span></span></h2>
        <p class="h-chap__sub" data-reveal="up">コーチングの会社（副業）</p>
        <p data-reveal="up">副業でコーチに。年間およそ250人と話し、人が動けない理由の奥を一緒に探しています。</p>
      </div>
    </article>

    <article class="h-chap">
      <figure class="h-chap__illust frame" data-reveal="clip">
        <div class="frame__in" data-parallax="0.08">
          <div class="illust" data-illust="pc"></div>
        </div>
      </figure>
      <div class="h-chap__body">
        <p class="h-chap__no" data-reveal="up">05</p>
        <h2 class="h-chap__word" data-reveal><span class="mask"><span>いま</span></span></h2>
        <p class="h-chap__sub" data-reveal="up">AI導入支援 / HP制作 / LINE構築</p>
        <p data-reveal="up">AIの導入とHP制作。遠回りだと思っていた経験が、いちばん役に立っているのがここです。</p>
      </div>
    </article>

    <!-- 経歴の要点 -->
    <section class="record">
      <h2 class="record__title" data-reveal>
        <span class="mask"><span>ひとつずつ、</span></span>
        <span class="mask"><span>のぼってきた。</span></span>
      </h2>
      <ol class="record__list">
        <li data-reveal="up"><span class="record__step">Step 01</span><b>陸上自衛隊</b><p>初期教育を修了</p></li>
        <li data-reveal="up"><span class="record__step">Step 02</span><b>消防士</b><p>消火・救急・救助・予防業務に3年半</p></li>
        <li data-reveal="up"><span class="record__step">Step 03</span><b>外資系の訪問販売会社</b><p>フルコミッション営業。Weekly全国1位。半年でコアメンバー、12人の組織づくりに関わる</p></li>
        <li data-reveal="up"><span class="record__step">Step 04</span><b>SNSマーケティングの会社</b><p>2023年 幹部評価1.1（社内75人中3人）。入社1年でリーダー。法人営業でSalesforceの導入を進める</p></li>
        <li data-reveal="up"><span class="record__step">Step 05</span><b>コーチングの会社</b><p>コーチ（副業）。年間約250人とセッション。リブースト／セルフコア合宿のサポートコーチ</p></li>
        <li class="is-now" data-reveal="up"><span class="record__step">Now</span><b>AI導入・業務効率化 / HP制作・LINE構築</b><p>これまでの全部を使って、いまの仕事へ。</p></li>
      </ol>
    </section>

    <!-- 戻る -->
    <section class="close">
      <h2 class="close__title" data-reveal>
        <span class="mask"><span>そして、</span></span>
        <span class="mask"><span>いまの仕事へ。</span></span>
      </h2>
      <p data-reveal="up"><a class="close__mail" href="<?php echo esc_url( home_url( '/' ) ); ?>">めぐるHP制作のトップへ戻る</a></p>
    </section>

  </main>

<?php get_footer(); ?>
