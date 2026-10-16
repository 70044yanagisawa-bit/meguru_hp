/* ==========================================================
   めぐるHP制作 — ブロックエディタでの見た目と編集
   公開ページと同じクラス名で組んでいるので、編集画面でもほぼ同じ見た目になる。
   公開ページの HTML は inc/blocks.php の render が出す（ここの save は中の段落だけ）。
   ========================================================== */
(function (wp) {
  'use strict';

  const { createElement: h, Fragment, useEffect, useRef } = wp.element;
  const { registerBlockType } = wp.blocks;
  const {
    RichText, InnerBlocks, useBlockProps, useInnerBlocksProps,
    InspectorControls, MediaUpload, MediaUploadCheck
  } = wp.blockEditor;
  const { PanelBody, SelectControl, ToggleControl, TextControl, RangeControl, Button, Notice } = wp.components;
  const { useSelect } = wp.data;
  const { decodeEntities } = wp.htmlEntities;
  const DATA = window.meguruBlocks || { blocks: {}, illusts: {} };

  /* ---------- 小さな部品 ---------- */

  // 文字の入力欄（クリックしてそのまま書ける）
  const txt = (p, key, tagName, className, placeholder, opts) =>
    h(RichText, Object.assign({
      tagName,
      className,
      value: p.attributes[key],
      onChange: (v) => p.setAttributes({ [key]: v }),
      placeholder
    }, opts || {}));

  const one = { allowedFormats: ['core/bold', 'core/italic'], disableLineBreaks: true };
  const lineHint = '（改行で行が分かれます）';

  // イラスト（公開ページと同じ絵を描く）
  function Illust({ name }) {
    const ref = useRef();
    useEffect(() => {
      const node = ref.current;
      if (!node) return;
      node.innerHTML = '';
      if (name && window.MeguruIllust) window.MeguruIllust(node, name);
    }, [name]);
    return h('div', { className: 'illust', ref });
  }

  // 写真の枠：選んでいるときだけ「写真を選ぶ」ボタンが出る
  function Frame({ p, prefix, className, fallback }) {
    const a = p.attributes;
    const pre = prefix || '';
    const url = a[pre + 'imageUrl'];
    const illust = a[pre + 'illust'];
    const src = url || (!illust && fallback) || '';
    const inner = src ? h('img', { src, alt: '' }) : (illust ? h(Illust, { name: illust }) : null);

    return h('figure', { className: 'frame ' + className + (!src && !illust ? ' mg-empty' : '') },
      h('div', { className: 'frame__in' }, inner),
      p.isSelected && h(MediaUploadCheck, null,
        h(MediaUpload, {
          allowedTypes: ['image'],
          value: a[pre + 'imageId'],
          onSelect: (m) => p.setAttributes({
            [pre + 'imageId']: m.id,
            [pre + 'imageUrl']: (m.sizes && m.sizes.large ? m.sizes.large.url : m.url)
          }),
          render: ({ open }) => h('div', { className: 'mg-frame-tools' },
            h(Button, { variant: 'primary', onClick: open }, url ? '写真を変える' : '写真を選ぶ'),
            url && h(Button, {
              variant: 'secondary',
              onClick: () => p.setAttributes({ [pre + 'imageId']: 0, [pre + 'imageUrl']: '' })
            }, '写真を外す')
          )
        })
      )
    );
  }

  // 右のサイドバー：写真がないときのイラスト
  const illustOptions = Object.keys(DATA.illusts).map((k) => ({ value: k, label: DATA.illusts[k] }));
  const illustPanel = (p, frames) =>
    h(PanelBody, { title: '写真・イラスト', initialOpen: true },
      h('p', { className: 'mg-help' }, '写真は、ブロックの中の「写真を選ぶ」から。写真がないときは、ここで選んだイラストが出ます。'),
      frames.map(([pre, label]) => h(SelectControl, {
        key: pre || 'main',
        label: label || 'イラスト',
        value: p.attributes[(pre || '') + 'illust'],
        options: illustOptions,
        onChange: (v) => p.setAttributes({ [(pre || '') + 'illust']: v })
      }))
    );

  const paragraphs = (placeholder) => ({
    allowedBlocks: ['core/paragraph', 'core/list', 'core/buttons', 'core/image'],
    template: [['core/paragraph', { placeholder: placeholder || '本文を入力（Enter で段落が増えます）' }]]
  });

  // 中の段落を、ラッパーの div をはさまずに置く
  function Inner({ className, opts, tagName }) {
    const props = useInnerBlocksProps({ className }, opts);
    return h(tagName || 'div', props);
  }

  /* ---------- ブロック ---------- */

  const EDIT = {};
  const HAS_INNER = {};

  EDIT['meguru/hero'] = (p) => {
    const a = p.attributes;
    return h(Fragment, null,
      h(InspectorControls, null,
        h(PanelBody, { title: 'うしろの大きな英字' },
          h(TextControl, { label: '英字（空にすると出さない）', value: a.bg, onChange: (v) => p.setAttributes({ bg: v }) })
        )
      ),
      h('section', useBlockProps({ className: 'hero' }),
        h('div', { className: 'hero__sticky' },
          a.bg && h('p', { className: 'hero__bg', 'aria-hidden': true }, h('span', null, (a.bg + ' ').repeat(4))),
          h('div', { className: 'hero__scene mg-scene' }, h('span', null, '公開ページでは、ここで「YM」の島のイラストが動きます')),
          h('div', { className: 'hero__copy' },
            h('p', { className: 'hero__label' }, h('span', { className: 'mask' }, txt(p, 'label', 'span', '', 'ラベル', one))),
            txt(p, 'title', 'h1', 'hero__title', 'キャッチコピー' + lineHint),
            h('p', { className: 'hero__name' },
              txt(p, 'name', 'span', '', '名前', one), ' ',
              h('i', null, txt(p, 'nameEn', 'span', '', 'Name', one))
            )
          ),
          txt(p, 'hint', 'p', 'hero__hint mg-hint', '最初だけ出る案内（任意）')
        )
      )
    );
  };

  EDIT['meguru/intro'] = (p) =>
    h('section', useBlockProps({ className: 'intro' }),
      txt(p, 'text', 'p', 'intro__text', '大きな文章' + lineHint),
      txt(p, 'sub', 'p', 'intro__sub', '小さな文章（任意）')
    );

  EDIT['meguru/photo'] = (p) =>
    h(Fragment, null,
      h(InspectorControls, null, illustPanel(p, [['', 'イラスト']])),
      h('div', useBlockProps({ className: 'mg-photo' }), h(Frame, { p, className: 'wide' }))
    );

  EDIT['meguru/pair'] = (p) =>
    h(Fragment, null,
      h(InspectorControls, null, illustPanel(p, [['a', '左のイラスト'], ['b', '右のイラスト']])),
      h('div', useBlockProps({ className: 'pair' }),
        h(Frame, { p, prefix: 'a', className: 'pair__a' }),
        h(Frame, { p, prefix: 'b', className: 'pair__b' })
      )
    );

  HAS_INNER['meguru/chapter'] = true;
  EDIT['meguru/chapter'] = (p) => {
    const a = p.attributes;
    const photo = a.imageUrl || a.illust;
    return h(Fragment, null,
      h(InspectorControls, null,
        h(PanelBody, { title: 'レイアウト' },
          h(ToggleControl, { label: '大きな一言を右に置く', checked: a.reverse, onChange: (v) => p.setAttributes({ reverse: v }) })
        ),
        illustPanel(p, [['', 'イラスト（選ぶと横に写真の枠が出ます）']])
      ),
      h('article', useBlockProps({ className: 'chapter' + (a.reverse ? ' chapter--rev' : '') + (photo ? ' chapter--photo' : '') }),
        h('p', { className: 'chapter__word' }, h('span', { className: 'mask' }, txt(p, 'word', 'span', '', '一言', one))),
        h('div', { className: 'chapter__body' },
          txt(p, 'title', 'h2', 'chapter__title', '見出し' + lineHint),
          h(Inner, { className: 'mg-inner', opts: paragraphs() })
        ),
        photo ? h(Frame, { p, className: 'chapter__photo' }) : (p.isSelected && h(Frame, { p, className: 'chapter__photo mg-add-photo' }))
      )
    );
  };

  EDIT['meguru/band'] = (p) =>
    h(Fragment, null,
      h(InspectorControls, null, illustPanel(p, [['', 'イラスト']])),
      h('section', useBlockProps({ className: 'band' }),
        h(Frame, { p, className: 'band__photo' }),
        txt(p, 'text', 'p', 'band__text', '写真の上を通る言葉' + lineHint)
      )
    );

  HAS_INNER['meguru/feature'] = true;
  EDIT['meguru/feature'] = (p) => {
    const a = p.attributes;
    return h(Fragment, null,
      h(InspectorControls, null,
        h(PanelBody, { title: '囲みの中身' },
          h(ToggleControl, { label: '比べる図（○○ → ○○）を出す', checked: a.swapShow, onChange: (v) => p.setAttributes({ swapShow: v }) }),
          h(ToggleControl, { label: '下の緑の帯を出す', checked: a.promiseShow, onChange: (v) => p.setAttributes({ promiseShow: v }) })
        ),
        illustPanel(p, [['', 'イラスト']])
      ),
      h('section', useBlockProps({ className: 'line' }),
        h('div', { className: 'line__head' },
          txt(p, 'tag', 'p', 'tag', 'ラベル', one),
          txt(p, 'title', 'h2', 'line__title', '見出し' + lineHint)
        ),
        h(Frame, { p, className: 'line__illust' }),
        h(Inner, { className: 'line__body', opts: paragraphs() }),
        a.swapShow && h('div', { className: 'line__swap' },
          h('p', { className: 'line__from' },
            txt(p, 'swapFrom', 's', '', '比べるもの（改行で分ける）'),
            txt(p, 'swapFromNote', 'small', '', '小さな説明', one)
          ),
          h('p', { className: 'line__arrow', 'aria-hidden': true }, '→'),
          h('p', { className: 'line__to' },
            txt(p, 'swapTo', 'b', '', 'おすすめ', one),
            h('span', { className: 'line__stamp' },
              txt(p, 'stamp', 'span', '', '上', one), h('br'),
              txt(p, 'stampNum', 'em', '', '0', one),
              txt(p, 'stampUnit', 'span', '', '円', one)
            )
          )
        ),
        txt(p, 'note', 'p', 'line__note', '注意書き（任意）'),
        a.promiseShow && h('div', { className: 'line__promise' },
          txt(p, 'promiseTitle', 'h3', 'line__promise-title', '見出し' + lineHint),
          txt(p, 'promiseBody', 'div', 'line__promise-body mg-paras', '本文（改行したところで段落が分かれます）')
        )
      )
    );
  };

  function WorkCard({ w }) {
    const media = useSelect((s) => (w.featured_media ? s('core').getMedia(w.featured_media) : null), [w.featured_media]);
    const src = media && (media.media_details && media.media_details.sizes && media.media_details.sizes.large ? media.media_details.sizes.large.source_url : media.source_url);
    return h('div', { className: 'work' },
      h('figure', { className: 'work__thumb frame' }, h('div', { className: 'frame__in' }, src && h('img', { src, alt: '' }))),
      h('p', { className: 'work__meta' }, (w.meta && w.meta._meguru_meta) || ''),
      h('h3', { className: 'work__name' }, decodeEntities((w.title && w.title.raw) || ''), ' ', h('i', { 'aria-hidden': true }, '↗'))
    );
  }

  EDIT['meguru/works'] = (p) => {
    const works = useSelect((s) => s('core').getEntityRecords('postType', 'meguru_work', { per_page: 50, orderby: 'menu_order', order: 'asc', context: 'edit' }), []);
    return h('section', useBlockProps({ className: 'works mg-works' }),
      h('div', { className: 'works__sticky' },
        h('div', { className: 'works__track' },
          h('div', { className: 'works__head' },
            txt(p, 'title', 'h2', 'works__title', '見出し' + lineHint),
            txt(p, 'sub', 'p', '', '小さな文章（任意）', one),
            h('p', { className: 'mg-note' }, '実績は、左のメニューの「実績」から追加・編集します。')
          ),
          (works || []).map((w) => h(WorkCard, { key: w.id, w }))
        )
      )
    );
  };

  HAS_INNER['meguru/profile'] = true;
  EDIT['meguru/profile'] = (p) => {
    const a = p.attributes;
    return h(Fragment, null,
      h(InspectorControls, null,
        h(PanelBody, { title: '下のリンク' },
          h(TextControl, { label: 'リンク先のURL（空なら「これまでのこと」のページ）', value: a.moreUrl, onChange: (v) => p.setAttributes({ moreUrl: v }) })
        )
      ),
      h('section', useBlockProps({ className: 'me' }),
        h(Frame, { p, className: 'me__photo', fallback: DATA.mePhoto }),
        h('div', { className: 'me__body' },
          h('p', { className: 'me__name' },
            h('span', { className: 'mask' }, txt(p, 'name', 'span', '', '名前', one)),
            h('span', { className: 'mask' }, h('i', null, txt(p, 'nameEn', 'span', '', 'Name', one)))
          ),
          h(Inner, { className: 'mg-inner', opts: paragraphs('自己紹介（Enter で段落が増えます）') }),
          h('p', { className: 'me__more' }, h('a', null, txt(p, 'moreText', 'span', '', 'リンクの文字（空にすると出さない）', one), ' ', h('i', { 'aria-hidden': true }, '→')))
        )
      )
    );
  };

  EDIT['meguru/news'] = (p) => {
    const a = p.attributes;
    const posts = useSelect((s) => s('core').getEntityRecords('postType', 'post', { per_page: a.count || 3, status: 'publish' }), [a.count]);
    return h(Fragment, null,
      h(InspectorControls, null,
        h(PanelBody, { title: '表示する数' },
          h(RangeControl, { label: '件数', min: 1, max: 10, value: a.count, onChange: (v) => p.setAttributes({ count: v }) })
        )
      ),
      h('section', useBlockProps({ className: 'news' }),
        h('div', { className: 'news__head' },
          txt(p, 'title', 'h2', 'news__title', 'お知らせ', one),
          h('span', { className: 'news__all' }, '一覧を見る ', h('i', null, '→'))
        ),
        posts && posts.length
          ? h('ul', { className: 'news__list' }, posts.map((post) =>
            h('li', { key: post.id }, h('a', null,
              h('time', null, (post.date || '').slice(0, 10).replace(/-/g, '.')),
              h('span', { dangerouslySetInnerHTML: { __html: post.title.rendered } })
            ))))
          : h('p', { className: 'mg-note' }, 'お知らせがまだありません。「投稿」→「新規投稿を追加」で書くと、ここに並びます（公開ページでは、お知らせが0件のあいだは表示されません）。')
      )
    );
  };

  HAS_INNER['meguru/contact'] = true;
  EDIT['meguru/contact'] = (p) =>
    h('section', useBlockProps({ className: 'close' }),
      txt(p, 'title', 'h2', 'close__title', '見出し' + lineHint),
      h(Inner, { className: 'mg-inner', opts: paragraphs() }),
      txt(p, 'mail', 'p', 'mg-mail', '例：メールは、{mail} まで。（空にすると出さない）', one),
      p.isSelected && h('p', { className: 'mg-note' }, '{mail} のところに、メールアドレス（' + DATA.mail + '）のリンクが入ります。アドレスは「外観 → カスタマイズ → めぐるHP制作」で変えられます。')
    );

  EDIT['meguru/page-hero'] = (p) =>
    h(Fragment, null,
      h(InspectorControls, null, illustPanel(p, [['', 'イラスト']])),
      h('section', useBlockProps({ className: 'h-hero' }),
        h('p', { className: 'tag h-hero__tag' }, txt(p, 'tag', 'span', '', 'ラベル', one)),
        txt(p, 'title', 'h1', 'h-hero__title', '大きな見出し' + lineHint),
        txt(p, 'lead', 'p', 'h-hero__lead', 'リード文（任意）'),
        h(Frame, { p, className: 'h-hero__illust' })
      )
    );

  HAS_INNER['meguru/story'] = true;
  EDIT['meguru/story'] = (p) => {
    const a = p.attributes;
    return h(Fragment, null,
      h(InspectorControls, null,
        h(PanelBody, { title: 'レイアウト' },
          h(ToggleControl, { label: '写真を右に置く', checked: a.reverse, onChange: (v) => p.setAttributes({ reverse: v }) })
        ),
        illustPanel(p, [['', 'イラスト']])
      ),
      h('article', useBlockProps({ className: 'h-chap' + (a.reverse ? ' h-chap--rev' : '') }),
        h(Frame, { p, className: 'h-chap__illust' }),
        h('div', { className: 'h-chap__body' },
          txt(p, 'no', 'p', 'h-chap__no', '01', one),
          h('h2', { className: 'h-chap__word' }, h('span', { className: 'mask' }, txt(p, 'word', 'span', '', '一言', one))),
          h('div', null, txt(p, 'sub', 'p', 'h-chap__sub', 'ラベル（任意）', one)),
          h(Inner, { className: 'mg-inner', opts: paragraphs() })
        )
      )
    );
  };

  HAS_INNER['meguru/steps'] = true;
  EDIT['meguru/steps'] = (p) =>
    h('section', useBlockProps({ className: 'record' }),
      txt(p, 'title', 'h2', 'record__title', '見出し（任意）' + lineHint),
      h(Inner, {
        tagName: 'ol',
        className: 'record__list',
        opts: {
          allowedBlocks: ['meguru/step'],
          template: [['meguru/step'], ['meguru/step']],
          renderAppender: InnerBlocks.ButtonBlockAppender
        }
      })
    );

  EDIT['meguru/step'] = (p) => {
    const a = p.attributes;
    return h(Fragment, null,
      h(InspectorControls, null,
        h(PanelBody, { title: 'ステップ' },
          h(ToggleControl, { label: '「いま」として黄色で目立たせる', checked: a.now, onChange: (v) => p.setAttributes({ now: v }) })
        )
      ),
      h('li', useBlockProps({ className: a.now ? 'is-now' : '' }),
        txt(p, 'step', 'span', 'record__step', 'Step 01', one),
        txt(p, 'name', 'b', '', '名前', one),
        txt(p, 'text', 'p', '', '説明', one)
      )
    );
  };

  /* ---------- 登録 ---------- */
  Object.keys(EDIT).forEach((name) => {
    const def = DATA.blocks[name];
    if (!def) return;
    registerBlockType(name, {
      apiVersion: 3,
      title: def.title,
      description: def.desc,
      icon: def.icon,
      category: 'meguru',
      parent: def.parent,
      attributes: def.attrs,
      supports: { html: false, anchor: true, className: false, customClassName: false },
      edit: EDIT[name],
      save: HAS_INNER[name] ? () => h(InnerBlocks.Content) : () => null
    });
  });
})(window.wp);
