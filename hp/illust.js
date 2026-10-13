/* ==========================================================
   めぐるHP制作 — ページ内の小さなイラスト
   <div data-illust="名前"> の中に、ファーストビューと同じタッチの島を描く。
   名前ごとの絵は、下の SCENES にまとめてある。
   ========================================================== */
(() => {
  const D = window.MeguruDraw;
  if (!D) return;
  const { COL, el, pts, line, person, bubble, heart } = D;

  const H = 1;                          // 島の厚み

  // 1枚ぶんの描き場所をつくる。w × d の島を置いて、上に乗せるものは put で登録する
  const stage = (root, w, d, { S = 34, label = '' } = {}) => {
    const view = D.iso(S, -30);
    const { P, depthOf } = view;
    const svg = el('svg', { role: 'img', 'aria-label': label, preserveAspectRatio: 'xMidYMid meet' });
    root.appendChild(svg);
    const pat = D.patterns(svg);
    const world = el('g', {}, svg);

    const c = [[0, 0], [w, 0], [w, d], [0, d]].flatMap(([u, v]) => [P(u, v, 0), P(u, v, H)]);
    const xs = c.map(p => p[0]), ys = c.map(p => p[1]);
    const x0 = Math.min(...xs) - 16, x1 = Math.max(...xs) + 16;
    const y0 = Math.min(...ys) - 96, y1 = Math.max(...ys) + 20;
    svg.setAttribute('viewBox', `${x0.toFixed(0)} ${y0.toFixed(0)} ${(x1 - x0).toFixed(0)} ${(y1 - y0).toFixed(0)}`);

    el('polygon', { points: pts([[0, 0], [w, 0], [w, d], [0, d]].map(([u, v]) => P(u + .4, v + .4, -.1))), fill: COL.pale, opacity: .8 }, world);
    D.prism(view, [[0, 0], [w, 0], [w, d], [0, d]], 0, H, { dots: pat.dots }, world);

    const items = [];
    const put = (u, v, draw) => items.push({ u, v, draw });
    const at = (u, v, z = H, cls = '') => {
      const [px, py] = P(u, v, z);
      const outer = el('g', { transform: `translate(${px.toFixed(1)} ${py.toFixed(1)})` }, world);
      return el('g', { class: cls }, outer);
    };
    const box = (x0, y0, x1, y1, z0, z1, opt) => D.box(view, x0, y0, x1, y1, z0, z1, opt, world);
    const tree = (u, v, r = 1) => put(u, v, () => D.tree(at(u, v, H, 'sc-tree'), r, pat.dotsW));
    const draw = () => items.sort((a, b) => depthOf(a.u, a.v) - depthOf(b.u, b.v)).forEach(it => it.draw());
    return { P, at, put, box, tree, draw, world };
  };

  // テーブル（天板が白い低い箱）
  const table = (s, x0, y0, x1, y1, z = .6) => s.box(x0, y0, x1, y1, H, H + z, { top: COL.white, left: COL.pale, right: COL.green });
  // ノートパソコン：天板の上に置く
  const laptop = (s, u, v, z) => {
    const g = s.at(u, v, z);
    el('path', { d: 'M-14 0 L-10 -18 L10 -18 L14 0 Z', fill: COL.ink, stroke: COL.ink, 'stroke-width': 2, 'stroke-linejoin': 'round' }, g);
    el('path', { d: 'M-10 -3 L-7 -15 L7 -15 L10 -3 Z', fill: COL.pale, class: 'sc-screen' }, g);
    return g;
  };
  // カップ
  const cup = (s, u, v, z) => {
    const g = s.at(u, v, z);
    el('path', { d: 'M-5 0 L-6 -10 L6 -10 L5 0 Z', fill: COL.white, stroke: COL.ink, 'stroke-width': 1.5, 'stroke-linejoin': 'round' }, g);
    line(g, 'M6 -8 q4 0 3 4 q-1 2 -3 2', 1.5);
    line(g, 'M-2 -13 q-2 -3 0 -6 M2 -13 q2 -3 0 -6', 1.2, COL.green).setAttribute('class', 'sc-steam');
  };
  // 小さな吹き出しをふわふわさせる
  const floatBubble = (g, text, opt, delay = 0) => {
    const b = el('g', { class: 'sc-sign', style: `animation-delay:${delay}s` }, g);
    bubble(b, text, opt);
    return b;
  };

  const SCENES = {
    /* 打ち合わせ：テーブルをはさんで話を聞く */
    listen(root) {
      const s = stage(root, 10, 6, { label: 'テーブルをはさんで、コーヒーを飲みながら話を聞いているイラスト' });
      s.tree(0.9, 0.9); s.tree(9.1, 1.0, .85); s.tree(9.2, 5.2, .7);
      s.put(3.0, 2.6, () => person(s.at(3.0, 2.6, H, 'sc-nod'), { shirt: COL.pop, arms: 'front', legs: 'sit' }));
      s.put(4.9, 3.2, () => {
        table(s, 3.8, 2.4, 6.4, 4.0);
        cup(s, 4.4, 3.0, H + .6); cup(s, 5.8, 3.3, H + .6);
        const n = s.at(5.1, 3.4, H + .6);
        el('rect', { x: -9, y: -7, width: 18, height: 10, rx: 1.5, fill: COL.white, stroke: COL.ink, 'stroke-width': 1.5, transform: 'skewX(-20)' }, n);
      });
      s.put(7.0, 4.0, () => {
        const g = s.at(7.0, 4.0, H, 'sc-talk');
        person(g, { shirt: COL.deep, arms: 'hold', legs: 'sit' });
      });
      const [ax, ay] = s.P(3.0, 2.6, H), [bx, by] = s.P(7.0, 4.0, H);
      s.put(10, 10, () => {
        floatBubble(el('g', { transform: `translate(${ax - 6} ${ay - 46})` }, s.world), 'ふむふむ', { w: 64, size: 12 });
        floatBubble(el('g', { transform: `translate(${bx + 10} ${by - 46})` }, s.world), '…♪', { w: 40, fill: COL.pop, color: COL.ink }, .8);
      });
      s.draw();
    },

    /* パソコンとAI */
    pc(root) {
      const s = stage(root, 6, 6, { label: 'パソコンに向かって、AIと一緒に作業しているイラスト' });
      s.tree(0.8, 0.8); s.tree(5.3, 5.2, .7);
      s.put(2.6, 2.3, () => person(s.at(2.6, 2.3, H, 'sc-type'), { shirt: COL.green, arms: 'front', legs: 'sit' }));
      s.put(3.2, 3.0, () => {
        table(s, 2.7, 2.5, 4.6, 3.6, .7);
        laptop(s, 3.6, 3.0, H + .7);
        const g = s.at(4.3, 3.3, H + .7);
        el('rect', { x: -4, y: -9, width: 8, height: 9, rx: 2, fill: COL.pop, stroke: COL.ink, 'stroke-width': 1.5 }, g);
      });
      const [x, y] = s.P(3.6, 3.0, H + .7);
      s.put(10, 10, () => {
        const ai = el('g', { transform: `translate(${x + 28} ${y - 40})` }, s.world);
        floatBubble(ai, 'AI', { w: 34 });
        el('path', { d: 'M22 -40 l2 6 l6 2 l-6 2 l-2 6 l-2 -6 l-6 -2 l6 -2z', fill: COL.pop, stroke: COL.ink, 'stroke-width': 1.2, class: 'sc-spark' }, ai);
        floatBubble(el('g', { transform: `translate(${x - 40} ${y - 62})` }, s.world), '</>', { w: 40, fill: COL.green, color: '#fff', size: 13 }, .6);
      });
      s.draw();
    },

    /* トレーニング */
    train(root) {
      const s = stage(root, 9, 6, { label: 'バーベルを持ち上げたり、ベンチでダンベルを上げたりしているイラスト' });
      s.tree(0.8, 0.8, .9); s.tree(8.3, 5.3, .7);
      s.put(2.6, 3.2, () => {
        const g = s.at(2.6, 3.2, H);
        el('ellipse', { cx: 0, cy: 0, rx: 20, ry: 9, fill: COL.deep, stroke: COL.ink, 'stroke-width': 2 }, g);
        const lift = el('g', { class: 'sc-squat' }, g);
        person(lift, { shirt: COL.white, arms: 'up', legs: 'wide' });
        line(lift, 'M-22 -33 L22 -33', 3);
        el('rect', { x: -26, y: -42, width: 6, height: 18, rx: 2, fill: COL.green, stroke: COL.ink, 'stroke-width': 2 }, lift);
        el('rect', { x: 20, y: -42, width: 6, height: 18, rx: 2, fill: COL.green, stroke: COL.ink, 'stroke-width': 2 }, lift);
      });
      s.put(5.6, 3.6, () => {
        s.box(5.0, 3.0, 6.2, 4.4, H, H + .35, { top: COL.ink, left: COL.deep, right: COL.ink });
        const p = s.at(5.6, 3.7, H + .35, 'sc-curl');
        person(p, { shirt: COL.pop, arms: 'front', legs: 'sit' });
        el('rect', { x: 7, y: -16, width: 9, height: 5, rx: 2, fill: COL.ink }, p);
      });
      s.put(7.6, 1.6, () => {
        const g = s.at(7.6, 1.6, H);
        [0, 1, 2].forEach(i => el('rect', { x: -12 + i * 2, y: -6 - i * 6, width: 22 - i * 4, height: 6, rx: 2, fill: i % 2 ? COL.green : COL.deep, stroke: COL.ink, 'stroke-width': 1.5 }, g));
      });
      s.put(4.2, 1.2, () => {
        const g = s.at(4.2, 1.2, H);
        el('circle', { cx: 0, cy: -8, r: 8, fill: COL.ink }, g);
        line(g, 'M-5 -16 Q0 -24 5 -16', 3);
      });
      s.draw();
    },

    /* 街：LINEのお店と、ダンスと、スマホを見る人 */
    town(root) {
      const s = stage(root, 14, 7, { S: 36, label: 'LINEのお店の前で、ダンスをしたり、スマホでやりとりしたりしている人たちのイラスト' });
      [[0.8, 0.8], [13.2, 0.9], [13.3, 6.3, .8], [7.2, 0.8, .8]].forEach(([u, v, r]) => s.tree(u, v, r));
      s.put(2.4, 2.4, () => {
        s.box(1.2, 1.2, 3.8, 3.2, H, H + 1.4, { top: COL.white, left: COL.white, right: '#eef7f1' });
        s.box(1.05, 1.05, 3.95, 3.35, H + 1.4, H + 1.6, { top: COL.deep, left: COL.green, right: COL.deep });
        for (let i = 0; i < 5; i++) {
          const u0 = 1.2 + i * 0.52;
          el('polygon', { points: pts([s.P(u0, 3.2, H + 1.15), s.P(u0 + .52, 3.2, H + 1.15), s.P(u0 + .52, 3.65, H + .95), s.P(u0, 3.65, H + .95)]), fill: i % 2 ? COL.white : COL.green, stroke: COL.ink, 'stroke-width': 1.5, 'stroke-linejoin': 'round' }, s.world);
        }
        const [x, y] = s.P(2.5, 2.2, H + 1.6);
        const sign = el('g', { transform: `translate(${x} ${y})` }, s.world);
        const b = el('g', { class: 'sc-sign' }, sign);
        line(b, 'M0 0 L0 -10', 3);
        el('g', { transform: 'translate(-3 -6)' }, b).appendChild(bubble(el('g'), 'LINE', { w: 52, fill: COL.green, color: '#fff' }));
      });
      s.put(5.0, 4.6, () => {
        const g = s.at(5.0, 4.6, H, 'sc-wave');
        person(g, { shirt: COL.white, arms: 'phone' });
        [0, 1, 2].forEach(i => {
          const h = el('g', { class: 'sc-heart', style: `--k:${i}`, transform: `translate(${8 + i * 6} -44)` }, g);
          heart(h);
        });
      });
      s.put(8.4, 3.6, () => {
        const g = s.at(8.4, 3.6, H);
        el('ellipse', { cx: 0, cy: 0, rx: 16, ry: 8, fill: COL.pop, stroke: COL.ink, 'stroke-width': 2 }, g);
        person(el('g', { class: 'sc-dance' }, g), { shirt: COL.green, arms: 'v', legs: 'wide' });
      });
      s.put(10.4, 4.4, () => {
        const g = s.at(10.4, 4.4, H);
        el('ellipse', { cx: 0, cy: 0, rx: 16, ry: 8, fill: COL.pale, stroke: COL.ink, 'stroke-width': 2 }, g);
        person(el('g', { class: 'sc-dance', style: 'animation-delay:-.5s' }, g), { shirt: COL.pop, arms: 'up', legs: 'wide', hat: 'cap' });
        ['♪', '♫'].forEach((n, i) => {
          const t = el('text', { x: 14 + i * 10, y: -44 - i * 8, 'font-size': 16, fill: COL.deep, class: 'sc-note', style: `--k:${i}` }, g);
          t.textContent = n;
        });
      });
      s.put(11.8, 2.6, () => {
        const g = s.at(11.8, 2.6, H);
        el('rect', { x: -10, y: -22, width: 20, height: 22, rx: 3, fill: COL.ink }, g);
        el('circle', { cx: 0, cy: -11, r: 6, fill: COL.pale, class: 'sc-speaker' }, g);
      });
      s.put(6.6, 5.9, () => person(s.at(6.6, 5.9, H, 'sc-walk'), { shirt: COL.deep, legs: 'walk', arms: 'phone' }));
      s.draw();
    },

    /* 数字を見る：GA4のグラフ */
    chart(root) {
      const s = stage(root, 6, 6, { label: '大きなグラフを指さしながら、数字を確かめているイラスト' });
      s.tree(5.2, 5.2, .7);
      s.put(3.4, 1.6, () => {
        const [x, y] = s.P(3.4, 1.4, H);
        const g = el('g', { transform: `translate(${x} ${y})` }, s.world);
        line(g, 'M-34 0 L-34 -20 M34 0 L34 -20', 4);
        el('rect', { x: -48, y: -96, width: 96, height: 76, rx: 6, fill: COL.white, stroke: COL.ink, 'stroke-width': 2 }, g);
        [[-34, 18], [-18, 30], [-2, 26], [14, 42], [30, 54]].forEach(([bx, h], i) => {
          el('rect', { x: bx - 5, y: -28 - h, width: 10, height: h, rx: 2, fill: i === 4 ? COL.pop : COL.green, stroke: COL.ink, 'stroke-width': 1.5, class: 'sc-bar', style: `--k:${i}` }, g);
        });
        line(g, 'M-36 -52 L-18 -64 L-2 -60 L14 -76 L30 -88', 2.5, COL.deep);
        el('circle', { cx: 30, cy: -88, r: 3.5, fill: COL.pop, stroke: COL.ink, 'stroke-width': 1.5 }, g);
      });
      s.put(1.8, 3.6, () => person(s.at(1.8, 3.6, H, 'sc-nod'), { shirt: COL.pop, arms: 'point' }));
      s.put(4.3, 4.2, () => {
        const g = s.at(4.3, 4.2, H, 'sc-curl');
        person(g, { shirt: COL.green, arms: 'hold' });
        el('circle', { cx: 15, cy: -18, r: 6, fill: COL.white, stroke: COL.ink, 'stroke-width': 2 }, g);
        line(g, 'M19 -14 L24 -9', 3);
      });
      s.draw();
    },

    /* 経歴：のぼっていく階段 */
    stairs(root) {
      const s = stage(root, 12, 5, { label: '5段の階段を、一段ずつのぼっていく人のイラスト' });
      s.tree(0.8, 0.8, .8); s.tree(11.3, 4.4, .7);
      for (let i = 0; i < 5; i++) {
        const u0 = 1.6 + i * 1.8;
        s.put(u0 + .9, 2.6, () => {
          s.box(u0, 1.6, u0 + 1.8, 3.6, H, H + (i + 1) * .45, { top: i === 4 ? '#fff3b8' : COL.white, left: COL.pale, right: COL.green });
          const [x, y] = s.P(u0 + .9, 3.6, H + (i + 1) * .45 * .5);
          const t = el('text', { x: x - 6, y: y + 4, 'font-size': 13, 'font-weight': 700, fill: COL.deep, 'font-family': 'Fredoka, sans-serif' }, s.world);
          t.textContent = `0${i + 1}`;
        });
      }
      s.put(10.3, 2.4, () => {
        const g = s.at(10.3, 2.4, H + 5 * .45);
        line(g, 'M14 0 L14 -46', 2.5);
        el('path', { d: 'M14 -46 L34 -40 L14 -34 Z', fill: COL.pop, stroke: COL.ink, 'stroke-width': 1.5, 'stroke-linejoin': 'round', class: 'sc-flag' }, g);
        person(el('g', { class: 'sc-dance' }, g), { shirt: COL.green, arms: 'up' });
      });
      s.put(4.3, 2.6, () => person(s.at(4.3, 2.6, H + 2 * .45, 'sc-walk'), { shirt: COL.pop, legs: 'walk' }));
      s.draw();
    },

    /* 01 守る：消防 */
    protect(root) {
      const s = stage(root, 7, 6, { label: 'ヘルメットをかぶって、ホースで火を消しているイラスト' });
      s.tree(0.8, 0.8, .8);
      s.put(2.0, 3.6, () => {
        const g = s.at(2.0, 3.6, H);
        person(g, { shirt: COL.deep, arms: 'hold', hat: 'helmet' });
        line(g, 'M12 -15 Q30 -12 34 -20', 4, COL.ink);
        el('path', { d: 'M36 -22 Q70 -60 104 -24', fill: 'none', stroke: '#8fd3ff', 'stroke-width': 5, 'stroke-linecap': 'round', 'stroke-dasharray': '10 8', class: 'sc-water' }, g);
      });
      s.put(5.0, 3.0, () => {
        const g = s.at(5.0, 3.0, H);
        el('path', { d: 'M-10 0 Q-14 -14 -4 -24 Q-4 -14 2 -12 Q0 -22 8 -30 Q8 -18 14 -10 Q14 0 -10 0 Z', fill: COL.pop, stroke: COL.ink, 'stroke-width': 2, 'stroke-linejoin': 'round', class: 'sc-flame' }, g);
      });
      s.put(5.8, 4.8, () => {
        const g = s.at(5.8, 4.8, H);
        el('rect', { x: -12, y: -26, width: 24, height: 26, rx: 3, fill: COL.white, stroke: COL.ink, 'stroke-width': 2 }, g);
        el('path', { d: 'M-4 -13 h8 M0 -17 v8', stroke: COL.green, 'stroke-width': 3, 'stroke-linecap': 'round' }, g);
      });
      s.draw();
    },

    /* 02 売る：訪問と、1位 */
    sell(root) {
      const s = stage(root, 7, 6, { label: 'かばんを持って家を訪ね、表彰台の1位に立っているイラスト' });
      s.tree(6.3, 0.8, .8);
      s.put(1.6, 2.0, () => {
        s.box(0.6, 0.8, 2.6, 2.6, H, H + 1.2, { top: COL.white, left: COL.white, right: '#eef7f1' });
        const [x, y] = s.P(1.6, 1.7, H + 1.2);
        el('polygon', { points: `${x - 40},${y + 14} ${x},${y - 22} ${x + 40},${y + 14}`, fill: COL.green, stroke: COL.ink, 'stroke-width': 2, 'stroke-linejoin': 'round' }, s.world);
      });
      s.put(3.2, 3.4, () => {
        const g = s.at(3.2, 3.4, H, 'sc-walk');
        person(g, { shirt: COL.deep, arms: 'down', legs: 'walk' });
        el('rect', { x: 8, y: -14, width: 10, height: 8, rx: 1.5, fill: COL.ink }, g);
      });
      s.put(5.0, 4.0, () => {
        s.box(4.5, 3.5, 5.5, 4.5, H, H + .8, { top: '#fff3b8', left: COL.pop, right: '#e8b51f' });
        s.box(3.5, 3.5, 4.5, 4.5, H, H + .5, { top: COL.white, left: COL.pale, right: COL.green });
        s.box(5.5, 3.5, 6.5, 4.5, H, H + .35, { top: COL.white, left: COL.pale, right: COL.green });
        const [x, y] = s.P(5.0, 4.5, H + .4);
        const t = el('text', { x: x - 4, y: y + 4, 'font-size': 14, 'font-weight': 700, fill: COL.ink, 'font-family': 'Fredoka, sans-serif' }, s.world);
        t.textContent = '1';
        person(el('g', { class: 'sc-dance' }, s.at(5.0, 4.0, H + .8)), { shirt: COL.pop, arms: 'up' });
      });
      s.draw();
    },

    /* 03 届ける：SNS */
    sns(root) {
      const s = stage(root, 7, 6, { label: '大きなスマホから、ハートやいいねが飛び出しているイラスト' });
      s.tree(0.8, 0.8, .8); s.tree(6.3, 5.3, .7);
      s.put(3.6, 2.2, () => {
        const [x, y] = s.P(3.6, 2.2, H);
        const g = el('g', { transform: `translate(${x} ${y})` }, s.world);
        el('rect', { x: -26, y: -96, width: 52, height: 92, rx: 9, fill: COL.ink }, g);
        el('rect', { x: -21, y: -88, width: 42, height: 76, rx: 4, fill: COL.white }, g);
        [0, 1, 2].forEach(i => {
          el('rect', { x: -16, y: -82 + i * 24, width: 32, height: 18, rx: 3, fill: i === 1 ? COL.pale : '#f1f6f2', stroke: COL.green, 'stroke-width': 1 }, g);
        });
        [0, 1, 2, 3].forEach(i => {
          const h = el('g', { class: 'sc-heart', style: `--k:${i}`, transform: `translate(${-30 + i * 18} -100)` }, g);
          heart(h, i % 2 ? COL.pop : COL.green);
        });
      });
      s.put(1.7, 4.2, () => person(s.at(1.7, 4.2, H, 'sc-wave'), { shirt: COL.pop, arms: 'phone' }));
      s.put(5.3, 4.0, () => person(s.at(5.3, 4.0, H, 'sc-nod'), { shirt: COL.green, arms: 'v' }));
      s.draw();
    }
  };

  document.querySelectorAll('[data-illust]').forEach(root => {
    const draw = SCENES[root.dataset.illust];
    draw && draw(root);
  });
})();
