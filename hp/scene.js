/* ==========================================================
   めぐるHP制作 — ファーストビューの「M」の島
   SVGをその場で組み立てる。座標は床の上の (x, y) と高さ z。
   スクロールで車とバイクがMの上の道を進む（動きは main.js から setProgress で渡す）。
   ========================================================== */
(() => {
  const root = document.querySelector('[data-scene]');
  if (!root) return;

  const NS = 'http://www.w3.org/2000/svg';
  const S = 36;                         // 床の1マス → px
  const C = Math.cos(Math.PI / 6);
  const H = 1.6;                        // Mの厚み
  // 文字の座標 (u, v) を床の向きに回す。-30° にすると、Mが正面からほぼまっすぐ読める
  const ROT = -30 * Math.PI / 180;
  const R = [Math.cos(ROT), Math.sin(ROT)];      // 文字の「右」が床のどちら向きか
  const D = [-Math.sin(ROT), Math.cos(ROT)];     // 文字の「下」
  const F = (u, v) => [u * R[0] + v * D[0], u * R[1] + v * D[1]];
  const depthOf = (u, v) => { const [x, y] = F(u, v); return x + y; };
  const P = (u, v, z = 0) => { const [x, y] = F(u, v); return [(x - y) * C * S, (x + y) * 0.5 * S - z * S]; };
  const pts = arr => arr.map(p => p.map(n => n.toFixed(1)).join(',')).join(' ');

  const COL = {
    ink: '#10291c',
    top: '#dff3e5',
    left: '#1aa85f',
    right: '#0d7a43',
    white: '#ffffff',
    green: '#1aa85f',
    deep: '#0d7a43',
    pale: '#bfe6cc',
    pop: '#ffd23f',
    skin: '#fbe3cf'
  };

  const el = (tag, attrs = {}, parent) => {
    const n = document.createElementNS(NS, tag);
    for (const k in attrs) n.setAttribute(k, attrs[k]);
    parent && parent.appendChild(n);
    return n;
  };

  const svg = el('svg', { role: 'img', 'aria-label': 'Mの形をした島の上で、トレーニングする人、パソコンでAIを使う人、LINEのお店、ダンスする人がいて、車とバイクが走っているイラスト' });
  root.appendChild(svg);

  // 網点（参考：イラストの影の質感）
  const defs = el('defs', {}, svg);
  const pat = el('pattern', { id: 'dots', width: 7, height: 7, patternUnits: 'userSpaceOnUse' }, defs);
  el('circle', { cx: 2, cy: 2, r: 1.1, fill: COL.green, opacity: .35 }, pat);
  const patW = el('pattern', { id: 'dotsW', width: 6, height: 6, patternUnits: 'userSpaceOnUse' }, defs);
  el('circle', { cx: 2, cy: 2, r: 1, fill: '#fff', opacity: .35 }, patW);

  const world = el('g', { class: 'sc-world' }, svg);

  // 見える範囲：Mの四隅から計算して、上は看板や人のぶん余白をとる
  {
    const c = [[0, 0], [15, 0], [15, 12], [0, 12]].flatMap(([u, v]) => [P(u, v, 0), P(u, v, H)]);
    const xs = c.map(p => p[0]), ys = c.map(p => p[1]);
    const x0 = Math.min(...xs) - 40, x1 = Math.max(...xs) + 40;
    const y0 = Math.min(...ys) - 110, y1 = Math.max(...ys) + 40;
    svg.setAttribute('viewBox', `${x0.toFixed(0)} ${y0.toFixed(0)} ${(x1 - x0).toFixed(0)} ${(y1 - y0).toFixed(0)}`);
  }

  /* ---------- 立体（多角形を押し出す） ---------- */
  const prism = (poly, z0, z1, opt = {}, parent = world) => {
    const g = el('g', {}, parent);
    let area = 0;
    poly.forEach((a, i) => { const b = poly[(i + 1) % poly.length]; area += a[0] * b[1] - b[0] * a[1]; });
    const sides = [];
    poly.forEach((a, i) => {
      const b = poly[(i + 1) % poly.length];
      const dx = b[0] - a[0], dy = b[1] - a[1];
      const n = F(...(area > 0 ? [dy, -dx] : [-dy, dx]));
      if (n[0] + n[1] <= 0.001) return;               // こちらを向いていない面は描かない
      sides.push({ a, b, n, depth: depthOf((a[0] + b[0]) / 2, (a[1] + b[1]) / 2) });
    });
    sides.sort((p, q) => p.depth - q.depth);
    sides.forEach(({ a, b, n }) => {
      const fill = n[0] > n[1] ? (opt.right || COL.right) : (opt.left || COL.left);
      el('polygon', { points: pts([P(...a, z0), P(...b, z0), P(...b, z1), P(...a, z1)]), fill, stroke: COL.ink, 'stroke-width': 2, 'stroke-linejoin': 'round' }, g);
    });
    const top = pts(poly.map(p => P(p[0], p[1], z1)));
    el('polygon', { points: top, fill: opt.top || COL.top, stroke: COL.ink, 'stroke-width': 2, 'stroke-linejoin': 'round' }, g);
    if (opt.dots) el('polygon', { points: top, fill: 'url(#dots)' }, g);
    return g;
  };
  const box = (x0, y0, x1, y1, z0, z1, opt, parent) => prism([[x0, y0], [x1, y0], [x1, y1], [x0, y1]], z0, z1, opt, parent);

  /* ---------- 地面の影とMの本体 ---------- */
  const M = [[0, 0], [4, 0], [7.5, 5], [11, 0], [15, 0], [15, 12], [11, 12], [11, 5.5], [7.5, 10.5], [4, 5.5], [4, 12], [0, 12]];
  el('polygon', { points: pts(M.map(([x, y]) => P(x + .6, y + .6, -.15))), fill: COL.pale, opacity: .7 }, world);
  prism(M, 0, H, { dots: true });

  /* ---------- 道（Mの上を一筆書き） ---------- */
  const ROAD = [[2, 12], [2, 1.7], [7.5, 8.1], [13, 1.7], [13, 12]];
  const roadPts = pts(ROAD.map(([x, y]) => P(x, y, H)));
  const road = el('g', { class: 'sc-road' }, world);
  el('polyline', { points: roadPts, fill: 'none', stroke: COL.ink, 'stroke-width': 30, 'stroke-linejoin': 'round' }, road);
  el('polyline', { points: roadPts, fill: 'none', stroke: COL.white, 'stroke-width': 26, 'stroke-linejoin': 'round' }, road);
  el('polyline', { points: roadPts, fill: 'none', stroke: COL.green, 'stroke-width': 2.2, 'stroke-dasharray': '8 9', 'stroke-linejoin': 'round', class: 'sc-lane' }, road);

  /* ---------- Mの上に置くもの（奥から順に描く） ---------- */
  const items = [];
  const put = (x, y, draw) => items.push({ x, y, draw });
  const at = (x, y, z = H, cls = '') => {
    const [px, py] = P(x, y, z);
    const outer = el('g', { transform: `translate(${px.toFixed(1)} ${py.toFixed(1)})` }, world);
    return el('g', { class: cls }, outer);
  };
  const line = (g, d, w = 3, color = COL.ink) => el('path', { d, fill: 'none', stroke: color, 'stroke-width': w, 'stroke-linecap': 'round', 'stroke-linejoin': 'round' }, g);

  // 人：足元が原点。body の色と、腕の形を渡す
  const person = (g, { shirt = COL.green, arms = 'down', hair = COL.ink, legs = 'stand' } = {}) => {
    if (legs === 'stand') line(g, 'M-3 0 L-3 -11 M3 0 L3 -11');
    if (legs === 'sit') line(g, 'M-3 -10 L-9 -10 L-9 0 M3 -10 L-3 -10 L-3 0');
    if (legs === 'wide') line(g, 'M-6 0 L-3 -11 M6 0 L3 -11');
    el('rect', { x: -7, y: -25, width: 14, height: 15, rx: 5, fill: shirt, stroke: COL.ink, 'stroke-width': 2 }, g);
    const A = {
      down: 'M-7 -21 L-10 -11 M7 -21 L10 -11',
      up: 'M-7 -21 L-12 -33 M7 -21 L12 -33',
      v: 'M-7 -21 L-14 -30 M7 -21 L13 -14',
      front: 'M-6 -20 L2 -14 M6 -20 L10 -13',
      phone: 'M-7 -21 L-10 -11 M7 -21 L6 -28'
    };
    line(g, A[arms], 3);
    el('circle', { cx: 0, cy: -32, r: 7, fill: COL.skin, stroke: COL.ink, 'stroke-width': 2 }, g);
    el('path', { d: 'M-7 -33 Q-7 -41 0 -40 Q7 -41 7 -33 Q3 -36 -1 -35 Q-5 -35 -7 -33 Z', fill: hair, stroke: COL.ink, 'stroke-width': 1.5, 'stroke-linejoin': 'round' }, g);
    el('circle', { cx: -2.5, cy: -31, r: 1, fill: COL.ink }, g);
    el('circle', { cx: 2.5, cy: -31, r: 1, fill: COL.ink }, g);
    return g;
  };

  // 木
  const tree = (x, y, r = 1) => put(x, y, () => {
    const g = at(x, y, H, 'sc-tree');
    line(g, 'M0 0 L0 -16', 3);
    el('circle', { cx: 0, cy: -26 * r, r: 13 * r, fill: COL.green, stroke: COL.ink, 'stroke-width': 2 }, g);
    el('circle', { cx: 0, cy: -26 * r, r: 13 * r, fill: 'url(#dotsW)' }, g);
    el('path', { d: `M${-6 * r} ${-30 * r} q4 -5 9 -4`, fill: 'none', stroke: '#fff', 'stroke-width': 2, 'stroke-linecap': 'round' }, g);
  });
  [[0.8, 1.0], [3.3, 1.1], [11.8, 1.1], [14.2, 1.0], [0.7, 11.3, .8], [14.3, 11.3, .8], [3.4, 11.4, .7], [11.7, 11.4, .7]].forEach(([x, y, r]) => tree(x, y, r));

  // LINEのお店（左の脚）
  put(0.75, 8.6, () => {
    const g = el('g', { class: 'sc-shop' }, world);
    box(0.2, 6.8, 1.4, 10.4, H, H + 1.4, { top: COL.white, left: COL.white, right: '#eef7f1' }, g);
    box(0.05, 6.65, 1.6, 10.55, H + 1.4, H + 1.6, { top: COL.deep, left: COL.green, right: COL.deep }, g);
    // 入口と、緑のひさし（+x の面）
    el('polygon', { points: pts([P(1.4, 8.0, H), P(1.4, 9.2, H), P(1.4, 9.2, H + .9), P(1.4, 8.0, H + .9)]), fill: COL.pale, stroke: COL.ink, 'stroke-width': 2 }, g);
    for (let i = 0; i < 6; i++) {
      const y0 = 6.8 + i * 0.6;
      el('polygon', { points: pts([P(1.4, y0, H + 1.15), P(1.4, y0 + .6, H + 1.15), P(1.85, y0 + .6, H + .95), P(1.85, y0, H + .95)]), fill: i % 2 ? COL.white : COL.green, stroke: COL.ink, 'stroke-width': 1.5, 'stroke-linejoin': 'round' }, g);
    }
    // 屋根の上の吹き出し看板
    const [sx, sy] = P(0.8, 8.6, H + 1.6);
    const sign = el('g', { transform: `translate(${sx.toFixed(1)} ${sy.toFixed(1)})` }, g);
    const bob = el('g', { class: 'sc-sign' }, sign);
    line(bob, 'M0 0 L0 -14', 3);
    el('path', { d: 'M-30 -50 h60 a10 10 0 0 1 10 10 v12 a10 10 0 0 1 -10 10 h-24 l-6 7 l-6 -7 h-24 a10 10 0 0 1 -10 -10 v-12 a10 10 0 0 1 10 -10z', fill: COL.green, stroke: COL.ink, 'stroke-width': 2, 'stroke-linejoin': 'round' }, bob);
    const t = el('text', { x: 0, y: -29, 'text-anchor': 'middle', fill: '#fff', 'font-size': 15, 'font-weight': 700, 'font-family': 'Fredoka, sans-serif', 'letter-spacing': 1 }, bob);
    t.textContent = 'LINE';
    // 吹き出しから出てくるハート
    [0, 1, 2].forEach(i => {
      const h = el('path', { d: 'M0 3 C-6 -3 -4 -8 0 -5 C4 -8 6 -3 0 3Z', fill: COL.pop, stroke: COL.ink, 'stroke-width': 1.2, class: 'sc-heart', style: `--k:${i}` }, sign);
      h.setAttribute('transform', `translate(${18 + i * 6} -56)`);
    });
  });
  put(3.2, 9.6, () => person(at(3.2, 9.6, H, 'sc-wave'), { shirt: COL.white, arms: 'phone' }));
  put(3.1, 7.4, () => {
    const g = at(3.1, 7.4, H);
    person(g, { shirt: COL.pop });
    el('rect', { x: 7, y: -16, width: 8, height: 7, rx: 1.5, fill: COL.green, stroke: COL.ink, 'stroke-width': 1.5 }, g);
  });

  // パソコンとAI（左の斜め）
  put(5.0, 3.0, () => person(at(5.0, 3.0, H, 'sc-type'), { shirt: COL.deep, arms: 'front', legs: 'sit' }));
  put(5.5, 3.6, () => {
    const g = el('g', {}, world);
    box(5.1, 3.2, 6.3, 4.1, H, H + .65, { top: COL.white, left: COL.pale, right: COL.green }, g);
    const [lx, ly] = P(5.7, 3.6, H + .65);
    const lap = el('g', { transform: `translate(${lx.toFixed(1)} ${ly.toFixed(1)})` }, g);
    el('path', { d: 'M-14 0 L-10 -18 L10 -18 L14 0 Z', fill: COL.ink, stroke: COL.ink, 'stroke-width': 2, 'stroke-linejoin': 'round' }, lap);
    el('path', { d: 'M-10 -3 L-7 -15 L7 -15 L10 -3 Z', fill: COL.pale, class: 'sc-screen' }, lap);
    // AIの吹き出し
    const ai = el('g', { class: 'sc-ai', transform: 'translate(-34 -46)' }, lap);
    el('rect', { x: -18, y: -14, width: 36, height: 26, rx: 13, fill: COL.white, stroke: COL.ink, 'stroke-width': 2 }, ai);
    const t = el('text', { x: 0, y: 5, 'text-anchor': 'middle', fill: COL.deep, 'font-size': 14, 'font-weight': 700, 'font-family': 'Fredoka, sans-serif' }, ai);
    t.textContent = 'AI';
    el('path', { d: 'M22 -16 l2 6 l6 2 l-6 2 l-2 6 l-2 -6 l-6 -2 l6 -2z', fill: COL.pop, stroke: COL.ink, 'stroke-width': 1.2, class: 'sc-spark' }, ai);
  });

  // ダンス（右の斜め）
  put(9.9, 3.3, () => {
    const g = at(9.9, 3.3, H);
    el('ellipse', { cx: 0, cy: 0, rx: 16, ry: 8, fill: COL.pop, stroke: COL.ink, 'stroke-width': 2 }, g);
    person(el('g', { class: 'sc-dance' }, g), { shirt: COL.green, arms: 'v', legs: 'wide' });
    ['♪', '♫'].forEach((n, i) => {
      const t = el('text', { x: 16 + i * 10, y: -40 - i * 8, 'font-size': 16, fill: COL.deep, class: 'sc-note', style: `--k:${i}` }, g);
      t.textContent = n;
    });
  });
  put(8.6, 2.5, () => {
    const g = at(8.6, 2.5, H);
    el('rect', { x: -9, y: -18, width: 18, height: 18, rx: 3, fill: COL.ink }, g);
    el('circle', { cx: 0, cy: -9, r: 5, fill: COL.pale, class: 'sc-speaker' }, g);
  });

  // トレーニング（右の脚）
  put(14.2, 7.6, () => {
    const g = at(14.2, 7.6, H);
    el('ellipse', { cx: 0, cy: 0, rx: 20, ry: 9, fill: COL.deep, stroke: COL.ink, 'stroke-width': 2 }, g);
    const lift = el('g', { class: 'sc-squat' }, g);
    person(lift, { shirt: COL.white, arms: 'up', legs: 'wide' });
    line(lift, 'M-22 -33 L22 -33', 3);
    el('rect', { x: -26, y: -42, width: 6, height: 18, rx: 2, fill: COL.green, stroke: COL.ink, 'stroke-width': 2 }, lift);
    el('rect', { x: 20, y: -42, width: 6, height: 18, rx: 2, fill: COL.green, stroke: COL.ink, 'stroke-width': 2 }, lift);
  });
  put(11.8, 9.8, () => {
    const g = el('g', {}, world);
    box(11.3, 9.4, 12.2, 10.6, H, H + .35, { top: COL.ink, left: COL.deep, right: COL.ink }, g);
    const p = at(11.75, 10.0, H + .35, 'sc-curl');
    person(p, { shirt: COL.pop, arms: 'front', legs: 'sit' });
    el('rect', { x: 7, y: -16, width: 9, height: 5, rx: 2, fill: COL.ink }, p);
  });
  put(14.3, 4.6, () => {
    const g = at(14.3, 4.6, H);
    [0, 1, 2].forEach(i => el('rect', { x: -12 + i * 2, y: -6 - i * 6, width: 22 - i * 4, height: 6, rx: 2, fill: i % 2 ? COL.green : COL.deep, stroke: COL.ink, 'stroke-width': 1.5 }, g));
  });

  // 1つずつ <g class="sc-drop"> に包む（読み込み時に順番に降ってくる）
  items.sort((a, b) => depthOf(a.x, a.y) - depthOf(b.x, b.y)).forEach(it => {
    const before = world.childNodes.length;
    it.draw();
    const added = [...world.childNodes].slice(before);
    const drop = el('g', { class: 'sc-drop', style: `--k:${(it.x / 15 * 6 + Math.random() * 2).toFixed(2)}` }, world);
    added.forEach(n => drop.appendChild(n));
  });

  /* ---------- 車とバイク ---------- */
  const seg = [];
  let total = 0;
  for (let i = 0; i < ROAD.length - 1; i++) {
    const [a, b] = [ROAD[i], ROAD[i + 1]];
    const len = Math.hypot(b[0] - a[0], b[1] - a[1]);
    seg.push({ a, b, len, from: total });
    total += len;
  }
  const along = t => {
    const d = Math.min(total - 0.001, Math.max(0, t * total));
    const s = seg.find(s => d >= s.from && d <= s.from + s.len) || seg[seg.length - 1];
    const k = (d - s.from) / s.len;
    const x = s.a[0] + (s.b[0] - s.a[0]) * k;
    const y = s.a[1] + (s.b[1] - s.a[1]) * k;
    const [p0] = P(...s.a), [p1] = P(...s.b);
    return { x, y, flip: p1 < p0 };
  };

  const vehicle = draw => {
    const outer = el('g', {}, world);
    const flip = el('g', {}, outer);
    const body = el('g', { class: 'sc-ride' }, flip);
    draw(body);
    return { outer, flip };
  };
  const car = vehicle(g => {
    el('path', { d: 'M-24 -6 L-24 -16 Q-22 -20 -16 -20 L-10 -30 Q-8 -32 -4 -32 L10 -32 Q14 -32 16 -28 L20 -20 Q26 -19 26 -14 L26 -6 Z', fill: COL.pop, stroke: COL.ink, 'stroke-width': 2, 'stroke-linejoin': 'round' }, g);
    el('path', { d: 'M-7 -21 L-3 -28 L4 -28 L4 -21 Z M8 -21 L8 -28 L12 -28 L16 -21 Z', fill: COL.pale, stroke: COL.ink, 'stroke-width': 1.5, 'stroke-linejoin': 'round' }, g);
    [-14, 15].forEach(cx => {
      el('circle', { cx, cy: -6, r: 6, fill: COL.ink }, g);
      el('circle', { cx, cy: -6, r: 2.2, fill: '#fff' }, g);
    });
    el('circle', { cx: 23, cy: -14, r: 2, fill: '#fff', stroke: COL.ink, 'stroke-width': 1 }, g);
  });
  const bike = vehicle(g => {
    [-12, 12].forEach(cx => {
      el('circle', { cx, cy: -7, r: 7, fill: '#fff', stroke: COL.ink, 'stroke-width': 3 }, g);
    });
    line(g, 'M-12 -7 L-4 -18 L8 -18 L12 -7 M8 -18 L11 -26 L15 -26', 3);
    el('path', { d: 'M-8 -18 L8 -18 L6 -24 L-6 -24 Z', fill: COL.green, stroke: COL.ink, 'stroke-width': 2, 'stroke-linejoin': 'round' }, g);
    const rider = el('g', { transform: 'translate(0 -18) scale(.85)' }, g);
    person(rider, { shirt: COL.deep, arms: 'front', legs: 'sit' });
    el('path', { d: 'M-8 -34 Q-8 -45 0 -45 Q8 -45 8 -34 Z', fill: COL.pop, stroke: COL.ink, 'stroke-width': 2 }, rider);
  });

  const place = (v, t) => {
    const { x, y, flip } = along(t);
    const [px, py] = P(x, y, H);
    v.outer.setAttribute('transform', `translate(${px.toFixed(1)} ${py.toFixed(1)})`);
    v.flip.setAttribute('transform', flip ? 'scale(-1 1)' : '');
  };

  // 0〜1 の進み具合で動かす
  const setProgress = p => {
    place(car, 0.04 + p * 0.9);
    place(bike, 0.96 - p * 0.9);
  };
  setProgress(0);
  window.meguruScene = { setProgress };
})();
