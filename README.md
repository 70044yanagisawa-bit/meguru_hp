# meguru_hp

柳沢恵瑠（Meguru Yanagisawa）のホームページ。ビルド不要の静的サイト（HTML / CSS / JS のみ）。

- `index.html` … トップ（About / Service＝2つのサービスの入口 / Works 制作サイト / Contact）
- `ai.html` … サービス01：AI導入・業務効率化（裏方の作業の図解あり）
- `web.html` … サービス02：HP制作・LINE構築（進め方の流れあり）
- `history.html` … 経歴（フッターとAboutからだけリンク。興味がある人向け）

## 写真の入れ方

`images/` に下のファイル名で置くだけで反映されます（未設定の枠にはファイル名が表示されます）。
JPG・横幅 2000px 前後・1枚 500KB 以下が目安。

| ファイル名 | 場所 | 向き |
| --- | --- | --- |
| hero.jpg | ファーストビュー（黒いパネルの左に重なる） | 横長 3:2 |
| about.jpg | About | 縦長 4:5 |
| wide.jpg | 「道具より先に、人の話を聞く。」の背景 | 横長 16:9 |
| works/site-01.jpg 〜 | 制作したサイトのサムネイル（トップ画面のスクリーンショット） | 横長 16:10 |
| service-ai.jpg / service-web.jpg | トップの Service と、各サービスページの最初の画像（図解イラスト。差し替え可） | 横長 16:10 |
| history-hero.jpg | 経歴ページの最初の大きな写真 | 横長 |
| story-01.jpg 〜 story-05.jpg | 経歴ページの各章（守る／売る／届ける／聴く／いま） | 縦長 |
| contact.jpg | Contact | 縦長 4:5 |
| ogp.jpg | SNSでシェアされたときの画像 | 1200×630 |

## 公開前に差し替えるもの

- Works：制作物が増えたら index.html の `<a class="site">` を複製して追加（サムネイルは images/works/）

- `index.html` の `hello@example.com`（2か所）を実際のメールアドレスに
- 独自ドメインが決まったら `og:image` を絶対URLに

## 公開（Cloudflare Workers）

公開URL：https://meguru-hp.mgr-lab.workers.dev

GitHub の `main` に push すると、Cloudflare（Workers Builds）が自動で `npx wrangler deploy` を実行して公開する。
`wrangler.jsonc` の設定で、このフォルダの静的ファイルをそのまま配信している（`.assetsignore` に書いたファイルは配信されない）。

保存と公開は、このフォルダで次を実行するだけ：

```bash
./deploy.sh "変更内容のメモ"
```
