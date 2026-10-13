/* ==========================================================
   めぐるHP制作 — motion
   外部ライブラリなし。スクロール連動は1本の rAF ループで回す。
   ========================================================== */
(() => {
  const body = document.body;
  const reduce = matchMedia('(prefers-reduced-motion: reduce)').matches;
  const wide = matchMedia('(min-width: 801px)');
  const $ = (s, el = document) => el.querySelector(s);
  const $$ = (s, el = document) => [...el.querySelectorAll(s)];
  const clamp = (v, a = 0, b = 1) => Math.min(b, Math.max(a, v));

  /* ---------- 遅延（stagger）用の番号 ---------- */
  $$('[data-reveal], [data-intro], .opening').forEach(box => {
    $$('.mask', box).forEach((m, i) => m.style.setProperty('--i', i));
  });
  $$('.chapter__body, .me__body, .close, .intro').forEach(box => {
    $$(':scope > [data-reveal="up"]', box).forEach((el, i) => el.style.setProperty('--i', i));
  });

  /* ---------- オープニング（1回の訪問で最初の1度だけ） ---------- */
  const opening = $('.opening');
  let seen = false;
  try { seen = sessionStorage.getItem('hp-opened') === '1'; } catch (e) {}

  const showPage = () => body.classList.add('is-loaded');

  if (!opening || reduce || seen) {
    opening && opening.remove();
    requestAnimationFrame(() => requestAnimationFrame(showPage));
  } else {
    body.style.overflow = 'hidden';
    requestAnimationFrame(() => requestAnimationFrame(() => opening.classList.add('is-in')));
    setTimeout(() => opening.classList.add('is-out'), 1800);
    setTimeout(() => {
      opening.classList.add('is-done');
      body.style.overflow = '';
      showPage();
      try { sessionStorage.setItem('hp-opened', '1'); } catch (e) {}
    }, 2200);
    setTimeout(() => opening.remove(), 3600);
  }

  /* ---------- 画面に入ったら表示 ---------- */
  const targets = $$('[data-reveal]');
  if (reduce || !('IntersectionObserver' in window)) {
    targets.forEach(el => el.classList.add('is-in'));
  } else {
    const io = new IntersectionObserver(entries => {
      entries.forEach(e => {
        if (!e.isIntersecting) return;
        e.target.classList.add('is-in');
        io.unobserve(e.target);
      });
    }, { rootMargin: '0px 0px -12% 0px' });
    targets.forEach(el => io.observe(el));
  }

  if (reduce) return;

  /* ---------- スクロール連動（パララックス・横スクロール・進み具合） ---------- */
  const layers = $$('[data-parallax]').map(el => ({ el, speed: parseFloat(el.dataset.parallax) || 0 }));
  const pin = $('[data-pin]');
  const track = pin && $('.works__track', pin);
  const bar = $('.progress span');
  const hero = $('[data-hero]');
  const heroBg = hero && $('.hero__bg span', hero);
  const heroSvg = hero && $('.hero__scene svg', hero);

  let vh = innerHeight;
  let distance = 0;

  // 横に流す距離ぶんだけ、セクションの高さを伸ばす
  const measure = () => {
    vh = innerHeight;
    if (!pin) return;
    if (wide.matches) {
      distance = Math.max(0, track.scrollWidth - innerWidth);
      pin.style.height = `${distance + vh}px`;
    } else {
      distance = 0;
      pin.style.height = '';
      track.style.transform = '';
    }
  };

  const update = () => {
    layers.forEach(({ el, speed }) => {
      const r = el.parentElement.getBoundingClientRect();
      if (r.bottom < -100 || r.top > vh + 100) return;
      const offset = (r.top + r.height / 2 - vh / 2) * speed;
      el.style.transform = `translate3d(0, ${offset.toFixed(1)}px, 0)`;
    });

    // ファーストビュー：車とバイクが進み、島が少し近づき、後ろの文字が流れる
    if (hero) {
      const r = hero.getBoundingClientRect();
      const p = clamp(-r.top / (r.height - vh));
      window.meguruScene && window.meguruScene.setProgress(p);
      heroSvg && (heroSvg.style.transform = `translate3d(0, ${(-p * 4).toFixed(2)}%, 0) scale(${(1 + p * .06).toFixed(3)})`);
    }

    if (pin && distance) {
      const r = pin.getBoundingClientRect();
      const p = clamp(-r.top / (r.height - vh));
      track.style.transform = `translate3d(${(-p * distance).toFixed(1)}px, 0, 0)`;
    }

    const max = document.documentElement.scrollHeight - vh;
    bar.style.transform = `scaleX(${max > 0 ? clamp(scrollY / max) : 0})`;
  };

  // 後ろの「MEGURU」：ずっと流れていて、スクロールした勢いのぶん速くなる
  if (heroBg) {
    heroBg.textContent += heroBg.textContent;
    let x = 0, boost = 0, lastY = scrollY, half = heroBg.scrollWidth / 2;
    addEventListener('resize', () => { half = heroBg.scrollWidth / 2; });
    addEventListener('scroll', () => {
      boost = Math.min(18, boost + Math.abs(scrollY - lastY) * .2);
      lastY = scrollY;
    }, { passive: true });
    const loop = () => {
      x -= .6 + boost;
      boost *= .92;
      if (-x >= half) x += half;
      heroBg.style.transform = `translate3d(${x.toFixed(1)}px, 0, 0)`;
      requestAnimationFrame(loop);
    };
    requestAnimationFrame(loop);
  }

  let ticking = false;
  const onScroll = () => {
    if (ticking) return;
    ticking = true;
    requestAnimationFrame(() => { update(); ticking = false; });
  };

  measure();
  update();
  addEventListener('scroll', onScroll, { passive: true });
  addEventListener('resize', () => { measure(); update(); });
  addEventListener('load', () => { measure(); update(); });
})();
