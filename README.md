# meguru_hp

柳沢恵瑠（Meguru Yanagisawa）のホームページ。ビルド不要の静的サイト（HTML / CSS / JS のみ）。

- `index.html` … トップ（About / Works 導入実績 / Service / Contact）
- `career.html` … 経歴（横スクロールの5章 + 要点）

## 写真の入れ方

`images/` に下のファイル名で置くだけで反映されます（未設定の枠にはファイル名が表示されます）。
JPG・横幅 2000px 前後・1枚 500KB 以下が目安。

| ファイル名 | 場所 | 向き |
| --- | --- | --- |
| hero.jpg | ファーストビュー（黒いパネルの左に重なる） | 横長 3:2 |
| about.jpg | About | 縦長 4:5 |
| wide.jpg | 「道具より先に、人の話を聞く。」の背景 | 横長 16:9 |
| case-01.jpg / case-02.jpg | 導入実績（01 店舗のBO作業 / 02 HP制作） | 横長 3:2 |
| work-01.jpg 〜 work-05.jpg | Service の行にカーソルを乗せたとき（PCのみ） | 縦長 3:4 |
| career.jpg | トップの「経歴を読む」入口 | 縦長 4:5 |
| career-hero.jpg | 経歴ページの最初の大きな写真 | 横長 |
| story-01.jpg 〜 story-05.jpg | 経歴ページの各章（守る／売る／届ける／聴く／いま） | 縦長 |
| contact.jpg | Contact | 縦長 4:5 |
| ogp.jpg | SNSでシェアされたときの画像 | 1200×630 |

## 公開前に差し替えるもの

- 導入実績（index.html の Works）は下書き。業種・地域の出し方と内容を本人が確認する

- `index.html` の `hello@example.com`（2か所）を実際のメールアドレスに
- 独自ドメインが決まったら `og:image` を絶対URLに

## Cloudflare Pages

- Framework preset: None
- Build command: （空欄）
- Build output directory: `/`
