# meguru_hp

柳沢恵瑠（Meguru Yanagisawa）のホームページ。ビルド不要の静的サイト（HTML / CSS / JS のみ）。

## 写真の入れ方

`images/` に下のファイル名で置くだけで反映されます（未設定の枠にはファイル名が表示されます）。
JPG・横幅 2000px 前後・1枚 500KB 以下が目安。

| ファイル名 | 場所 | 向き |
| --- | --- | --- |
| hero.jpg | ファーストビュー | 縦長（4:5 くらい） |
| about.jpg | About | 縦長 4:5 |
| wide.jpg | 「道具より先に、人の話を聞く。」の背景 | 横長 16:9 |
| story-01.jpg 〜 story-05.jpg | Story の各章（守る／売る／届ける／聴く／いま） | 縦長 |
| work-01.jpg 〜 work-05.jpg | Work の行にカーソルを乗せたとき（PCのみ） | 縦長 3:4 |
| contact.jpg | Contact | 縦長 4:5 |
| ogp.jpg | SNSでシェアされたときの画像 | 1200×630 |

## 公開前に差し替えるもの

- `index.html` の `hello@example.com`（2か所）を実際のメールアドレスに
- 独自ドメインが決まったら `og:image` を絶対URLに

## Cloudflare Pages

- Framework preset: None
- Build command: （空欄）
- Build output directory: `/`
