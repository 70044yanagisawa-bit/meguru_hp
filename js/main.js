/* ==========================================================
   Meguru Yanagisawa — motion
   外部ライブラリなし。スクロール連動はすべて1本の rAF ループで回す。
   トップ（index.html）と経歴（career.html）の両方で読み込む。
   ========================================================== */
(() => {
  const body = document.body;
  const reduce = matchMedia('(prefers-reduced-motion: reduce)').matches;
  const wide = matchMedia('(min-width: 901px)');
  const canHover = matchMedia('(hover: hover)').matches;
  const clamp = (v, a = 0, b = 1) => Math.min(b, Math.max(a, v));
  const $ = (s, el = document) => el.querySelector(s);
  const $$ = (s, el = document) => [...el.querySelectorAll(s)];

  let vw = innerWidth;
  let vh = innerHeight;

  /* ---------- 遅延（stagger）用のインデックスを振る ---------- */
  $$('[data-reveal], [data-intro], .loader__name, .loader__inner, .menu nav').forEach(box => {
    $$(':scope .mask', box).forEach((m, i) => m.style.setProperty('--i', i));
  });
  $$('.about__text, .contact__body, .teaser__body').forEach(box => {
    $$(':scope > [data-reveal="up"]', box).forEach((el, i) => el.style.setProperty('--i', i));
  });

  /* ---------- オープニング（トップのみ） ---------- */
  const loader = $('.loader');

  const finishLoading = () => {
    body.classList.add('is-loaded');
    if (!loader) return;
    loader.classList.add('is-done');
    loader.addEventListener('transitionend', () => loader.remove(), { once: true });
    setTimeout(() => loader.isConnected && loader.remove(), 2000);
  };

  if (!loader || reduce || sessionStorage.getItem('opened')) {
    requestAnimationFrame(() => requestAnimationFrame(finishLoading));
  } else {
    requestAnimationFrame(() => loader.classList.add('is-in'));
    const count = $('.loader__count span');
    const bar = $('.loader__bar span');
    const duration = 1900;
    const ease = p => (p < .5 ? 4 * p * p * p : 1 - Math.pow(-2 * p + 2, 3) / 2);
    let start;
    const tick = now => {
      start ??= now;
      const p = clamp((now - start) / duration);
      const e = ease(p);
      count.textContent = Math.round(e * 100);
      bar.style.transform = `scaleX(${e})`;
      if (p < 1) requestAnimationFrame(tick);
      else setTimeout(finishLoading, 350);
    };
    setTimeout(() => requestAnimationFrame(tick), 300);
    try { sessionStorage.setItem('opened', '1'); } catch (_) {}
  }

  /* ---------- 画面に入ったら表示 ---------- */
  const io = new IntersectionObserver(entries => {
    entries.forEach(e => {
      if (!e.isIntersecting) return;
      e.target.classList.add('is-in');
      io.unobserve(e.target);
    });
  }, { rootMargin: '0px 0px -12% 0px' });
  $$('[data-reveal]').forEach(el => io.observe(el));

  /* ---------- About：文字がスクロールに合わせて濃くなる ---------- */
  const fill = $('[data-fill]');
  const chars = [];
  if (fill) {
    const text = fill.textContent.trim();
    fill.textContent = '';
    fill.setAttribute('aria-label', text);
    for (const c of text) {
      const s = document.createElement('span');
      s.className = 'ch';
      s.setAttribute('aria-hidden', 'true');
      s.textContent = c;
      fill.appendChild(s);
      chars.push(s);
    }
  }
  let lit = -1;

  /* ---------- パララックス ----------
     data-speed：枠の中で写真だけが動く
     data-float：写真の枠ごと、固定背景の上を違う速さで流れる */
  const parallax = $$('[data-speed]').map(el => ({ el, box: el.parentElement, speed: parseFloat(el.dataset.speed) }));
  const floats = $$('[data-float]').map(el => ({ el, speed: parseFloat(el.dataset.float) }));

  /* ---------- Story：縦スクロールを横移動に変換（経歴ページ） ---------- */
  const story = $('.story');
  const track = $('.story__track');
  const storyBar = $('.story__progress span');
  let storyDist = 0;

  const layout = () => {
    vw = innerWidth;
    vh = innerHeight;
    if (!story) return;
    if (wide.matches) {
      storyDist = Math.max(0, track.scrollWidth - vw);
      story.style.height = `${storyDist + vh}px`;
    } else {
      storyDist = 0;
      story.style.height = '';
      track.style.transform = '';
    }
  };
  layout();
  addEventListener('resize', layout);
  addEventListener('load', layout);
  document.fonts?.ready.then(layout);

  /* ---------- ヘッダー・メニュー ---------- */
  const header = $('.header');
  const menu = $('.menu');
  const menuBtn = $('.header__menu');
  const badge = $('.badge');
  const contact = $('#contact') || $('.footer');
  const setMenu = open => {
    menu.classList.toggle('is-open', open);
    menu.setAttribute('aria-hidden', String(!open));
    menuBtn.setAttribute('aria-expanded', String(open));
    menuBtn.textContent = open ? 'Close' : 'Menu';
    body.style.overflow = open ? 'hidden' : '';
  };
  menuBtn.addEventListener('click', () => setMenu(!menu.classList.contains('is-open')));
  $$('a', menu).forEach(a => a.addEventListener('click', () => setMenu(false)));

  /* ---------- Service：カーソルについてくる写真 ---------- */
  const preview = $('.preview');
  const previewInner = $('.preview__inner');
  const pointer = { x: vw / 2, y: vh / 2 };
  const pv = { x: vw / 2, y: vh / 2 };
  addEventListener('pointermove', e => { pointer.x = e.clientX; pointer.y = e.clientY; }, { passive: true });

  if (canHover && preview) {
    $$('.service__row').forEach(row => {
      let url = null;
      const img = new Image();
      img.onload = () => { url = row.dataset.img; };
      img.src = row.dataset.img;
      row.addEventListener('pointerenter', () => {
        previewInner.style.backgroundImage = url ? `url("${url}")` : '';
        preview.classList.add('is-on');
      });
      row.addEventListener('pointerleave', () => preview.classList.remove('is-on'));
    });
  }

  /* ---------- Marquee ---------- */
  const marquee = $('.marquee__track');
  let mx = 0;
  let mDir = -1;

  /* ---------- ファーストビューの糸 ---------- */
  const canvas = $('.threads');
  const hero = $('.hero');
  const ctx = canvas?.getContext('2d');
  const COLORS = ['#3355ff', '#00b4d8', '#2fbf71', '#ffd23f', '#ff7a1a', '#ff2e63', '#8e44ff'];
  const LINES = 56;
  const heroPointer = { x: -9999, y: -9999, tx: -9999, ty: -9999 };
  let cw = 0;
  let ch = 0;

  const sizeCanvas = () => {
    const dpr = Math.min(devicePixelRatio || 1, 2);
    cw = hero.clientWidth;
    ch = hero.clientHeight;
    canvas.width = cw * dpr;
    canvas.height = ch * dpr;
    ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
  };
  if (canvas) {
    sizeCanvas();
    addEventListener('resize', sizeCanvas);
    hero.addEventListener('pointermove', e => {
      const r = hero.getBoundingClientRect();
      heroPointer.tx = e.clientX - r.left;
      heroPointer.ty = e.clientY - r.top;
    });
    hero.addEventListener('pointerleave', () => { heroPointer.tx = heroPointer.ty = -9999; });
  }

  const drawThreads = t => {
    ctx.clearRect(0, 0, cw, ch);
    const narrow = cw < 900;
    heroPointer.x += (heroPointer.tx - heroPointer.x) * .08;
    heroPointer.y += (heroPointer.ty - heroPointer.y) * .08;
    const step = narrow ? 14 : 18;
    ctx.lineWidth = narrow ? .8 : 1;
    ctx.globalAlpha = .9;

    for (let i = 0; i < LINES; i++) {
      const k = i / (LINES - 1) * 2 - 1;                // -1〜1：束の中での位置
      const c = (i / (LINES - 1)) * (COLORS.length - 1);
      ctx.strokeStyle = COLORS[Math.round(c)];
      ctx.beginPath();
      for (let x = -40; x <= cw + 40; x += step) {
        const u = x / cw;
        // 左下から右上へ流れる軸
        const axis = narrow ? ch * (.62 - u * .3) : ch * (.92 - u * .7);
        const wave = Math.sin(u * 4.2 + t * .35) * ch * .06 + Math.sin(u * 9 - t * .5) * ch * .02;
        // ねじれ：束が細くなったり広がったりする
        const twist = Math.sin(u * 5.5 + t * .45 + k * .6);
        const spread = ch * (narrow ? .05 : .08);
        let y = axis + wave + k * spread * (twist * .85 + .15);
        // カーソルの近くだけ少し引き寄せる
        const dx = x - heroPointer.x;
        const pull = Math.exp(-(dx * dx) / (2 * 140 * 140));
        y += (heroPointer.y - y) * .22 * pull;
        x === -40 ? ctx.moveTo(x, y) : ctx.lineTo(x, y);
      }
      ctx.stroke();
    }
  };

  /* ---------- 1本のループで全部回す ---------- */
  let lastY = scrollY;
  let velocity = 0;
  let headerY = 0;

  const frame = now => {
    const y = scrollY;
    const dy = y - lastY;
    lastY = y;
    velocity += (Math.abs(dy) - velocity) * .1;
    if (dy !== 0) mDir = dy > 0 ? -1 : 1;

    // ヘッダー：下に進むと隠れ、戻ると出る
    if (!menu.classList.contains('is-open')) {
      headerY += dy;
      if (dy < 0 || y < 120) headerY = 0;
      header.classList.toggle('is-hidden', headerY > 80);
    }

    // 丸いリンク：少しスクロールしたら出し、Contact が見えたら引っ込める
    const nearContact = contact.getBoundingClientRect().top < vh * .8;
    badge.classList.toggle('is-shown', body.classList.contains('is-loaded') && y > vh * .5 && !nearContact);

    // 糸（ファーストビューが見えている間だけ）
    if (canvas && y < ch) drawThreads(reduce ? 0 : now / 1000);

    if (!reduce) {
      parallax.forEach(({ el, box, speed }) => {
        if (!el.isConnected) return;               // 写真未設定で img が外れたとき
        const r = box.getBoundingClientRect();
        if (r.bottom < -100 || r.top > vh + 100) return;
        const limit = r.height * .1;
        const off = clamp((r.top + r.height / 2 - vh / 2) * speed, -limit, limit);
        el.style.transform = `translate3d(0,${off}px,0)`;
      });
      floats.forEach(({ el, speed }) => {
        // 自分の transform を除いた位置で計算する
        const r = el.parentElement.getBoundingClientRect();
        if (r.bottom < -200 || r.top > vh + 200) return;
        el.style.transform = `translate3d(0,${(r.top + r.height / 2 - vh / 2) * speed}px,0)`;
      });

      // Marquee：スクロールの向きと速さに反応
      if (marquee) {
        const half = marquee.scrollWidth / 2;
        mx += mDir * (0.6 + Math.min(velocity, 60) * .25);
        if (mx <= -half) mx += half;
        if (mx > 0) mx -= half;
        marquee.style.transform = `translate3d(${mx}px,0,0)`;
      }
    }

    // About の文字
    if (chars.length) {
      const r = fill.getBoundingClientRect();
      const p = clamp((vh * .82 - r.top) / (r.height + vh * .3));
      const n = reduce ? chars.length : Math.round(p * chars.length);
      if (n !== lit) {
        chars.forEach((c, i) => c.classList.toggle('on', i < n));
        lit = n;
      }
    }

    // Story の横移動
    if (storyDist) {
      const r = story.getBoundingClientRect();
      const p = clamp(-r.top / (r.height - vh));
      track.style.transform = `translate3d(${-p * storyDist}px,0,0)`;
      storyBar.style.transform = `scaleX(${p})`;
    }

    // Service のプレビュー
    if (preview) {
      pv.x += (pointer.x - pv.x) * .14;
      pv.y += (pointer.y - pv.y) * .14;
      preview.style.transform = `translate3d(${pv.x - preview.offsetWidth / 2}px,${pv.y - preview.offsetHeight / 2}px,0)`;
    }

    requestAnimationFrame(frame);
  };
  requestAnimationFrame(frame);

  $$('.year').forEach(el => { el.textContent = new Date().getFullYear(); });
})();
