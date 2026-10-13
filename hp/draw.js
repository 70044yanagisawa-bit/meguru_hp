/* ==========================================================
   めぐるHP制作 — イラストの共通部品
   ファーストビュー（scene.js）と、ページ内の小さなイラスト（illust.js）で使う。
   人・木・立体の描き方をここにまとめて、どこでも同じタッチにする。
   ========================================================== */
window.MeguruDraw = (() => {
  const NS = 'http://www.w3.org/2000/svg';

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
  const pts = arr => arr.map(p => p.map(n => n.toFixed(1)).join(',')).join(' ');
  const line = (g, d, w = 3, color = COL.ink) => el('path', { d, fill: 'none', stroke: color, 'stroke-width': w, 'stroke-linecap': 'round', 'stroke-linejoin': 'round' }, g);

  // 網点の模様。id がページ内で重ならないように、SVGごとに番号をつける
  let uid = 0;
  const patterns = svg => {
    const id = ++uid;
    const defs = el('defs', {}, svg);
    const pat = el('pattern', { id: `dots-${id}`, width: 7, height: 7, patternUnits: 'userSpaceOnUse' }, defs);
    el('circle', { cx: 2, cy: 2, r: 1.1, fill: COL.green, opacity: .35 }, pat);
    const patW = el('pattern', { id: `dotsW-${id}`, width: 6, height: 6, patternUnits: 'userSpaceOnUse' }, defs);
    el('circle', { cx: 2, cy: 2, r: 1, fill: '#fff', opacity: .35 }, patW);
    return { dots: `url(#dots-${id})`, dotsW: `url(#dotsW-${id})` };
  };

  /* ---------- 床の座標 (u, v, z) → 画面 ----------
     rot：床をどれだけ回して見るか。-30° で、正面からやや斜めに見下ろす形になる */
  const iso = (S, rotDeg = -30) => {
    const C = Math.cos(Math.PI / 6);
    const r = rotDeg * Math.PI / 180;
    const R = [Math.cos(r), Math.sin(r)];
    const D = [-Math.sin(r), Math.cos(r)];
    const F = (u, v) => [u * R[0] + v * D[0], u * R[1] + v * D[1]];
    const depthOf = (u, v) => { const [x, y] = F(u, v); return x + y; };
    const P = (u, v, z = 0) => { const [x, y] = F(u, v); return [(x - y) * C * S, (x + y) * 0.5 * S - z * S]; };
    return { F, P, depthOf };
  };

  /* ---------- 立体（多角形を押し出す） ---------- */
  const prism = (view, poly, z0, z1, opt = {}, parent) => {
    const { F, P, depthOf } = view;
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
    if (opt.dots) el('polygon', { points: top, fill: opt.dots }, g);
    return g;
  };
  const box = (view, x0, y0, x1, y1, z0, z1, opt, parent) => prism(view, [[x0, y0], [x1, y0], [x1, y1], [x0, y1]], z0, z1, opt, parent);

  /* ---------- 人：足元が原点 ----------
     shirt：服の色 / arms：腕の形 / legs：脚の形 / hat：帽子（helmet・cap） */
  const person = (g, { shirt = COL.green, arms = 'down', hair = COL.ink, legs = 'stand', hat } = {}) => {
    if (legs === 'stand') line(g, 'M-3 0 L-3 -11 M3 0 L3 -11');
    if (legs === 'sit') line(g, 'M-3 -10 L-9 -10 L-9 0 M3 -10 L-3 -10 L-3 0');
    if (legs === 'wide') line(g, 'M-6 0 L-3 -11 M6 0 L3 -11');
    if (legs === 'walk') line(g, 'M-6 0 L-2 -11 M5 -1 L2 -11');
    el('rect', { x: -7, y: -25, width: 14, height: 15, rx: 5, fill: shirt, stroke: COL.ink, 'stroke-width': 2 }, g);
    const A = {
      down: 'M-7 -21 L-10 -11 M7 -21 L10 -11',
      up: 'M-7 -21 L-12 -33 M7 -21 L12 -33',
      v: 'M-7 -21 L-14 -30 M7 -21 L13 -14',
      front: 'M-6 -20 L2 -14 M6 -20 L10 -13',
      phone: 'M-7 -21 L-10 -11 M7 -21 L6 -28',
      point: 'M-7 -21 L-10 -11 M7 -21 L18 -27',
      hold: 'M-7 -21 L4 -16 M7 -21 L12 -15'
    };
    line(g, A[arms], 3);
    el('circle', { cx: 0, cy: -32, r: 7, fill: COL.skin, stroke: COL.ink, 'stroke-width': 2 }, g);
    el('path', { d: 'M-7 -33 Q-7 -41 0 -40 Q7 -41 7 -33 Q3 -36 -1 -35 Q-5 -35 -7 -33 Z', fill: hair, stroke: COL.ink, 'stroke-width': 1.5, 'stroke-linejoin': 'round' }, g);
    if (hat === 'helmet') {
      el('path', { d: 'M-9 -34 Q-9 -44 0 -44 Q9 -44 9 -34 Z', fill: COL.pop, stroke: COL.ink, 'stroke-width': 2, 'stroke-linejoin': 'round' }, g);
      line(g, 'M-11 -34 L11 -34', 2.5);
    }
    if (hat === 'cap') el('path', { d: 'M-8 -35 Q-7 -43 0 -43 Q7 -43 8 -35 L14 -35', fill: COL.deep, stroke: COL.ink, 'stroke-width': 2, 'stroke-linejoin': 'round' }, g);
    el('circle', { cx: -2.5, cy: -31, r: 1, fill: COL.ink }, g);
    el('circle', { cx: 2.5, cy: -31, r: 1, fill: COL.ink }, g);
    return g;
  };

  /* ---------- 木：根元が原点 ---------- */
  const tree = (g, r = 1, dotsW) => {
    line(g, 'M0 0 L0 -16', 3);
    el('circle', { cx: 0, cy: -26 * r, r: 13 * r, fill: COL.green, stroke: COL.ink, 'stroke-width': 2 }, g);
    if (dotsW) el('circle', { cx: 0, cy: -26 * r, r: 13 * r, fill: dotsW }, g);
    el('path', { d: `M${-6 * r} ${-30 * r} q4 -5 9 -4`, fill: 'none', stroke: '#fff', 'stroke-width': 2, 'stroke-linecap': 'round' }, g);
    return g;
  };

  /* ---------- 吹き出し：しっぽの先が原点 ---------- */
  const bubble = (g, text, { w = 40, fill = COL.white, color = COL.deep, size = 14 } = {}) => {
    const h = 26;
    el('path', { d: `M${-w / 2} ${-h - 7} h${w} a6 6 0 0 1 6 6 v${h - 12} a6 6 0 0 1 -6 6 h${-w / 2 + 6} l-6 7 l-6 -7 h${-w / 2 + 6} a6 6 0 0 1 -6 -6 v${-h + 12} a6 6 0 0 1 6 -6z`, fill, stroke: COL.ink, 'stroke-width': 2, 'stroke-linejoin': 'round' }, g);
    const t = el('text', { x: 3, y: -15, 'text-anchor': 'middle', fill: color, 'font-size': size, 'font-weight': 700, 'font-family': 'Fredoka, "Zen Maru Gothic", sans-serif' }, g);
    t.textContent = text;
    return g;
  };

  // ハート：中心が原点
  const heart = (g, fill = COL.pop) => el('path', { d: 'M0 3 C-6 -3 -4 -8 0 -5 C4 -8 6 -3 0 3Z', fill, stroke: COL.ink, 'stroke-width': 1.2 }, g);

  return { NS, COL, el, pts, line, patterns, iso, prism, box, person, tree, bubble, heart };
})();
