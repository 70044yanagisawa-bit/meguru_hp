# めぐるHP制作 — WordPress テーマ

`hp/`（静的版のめぐるHP制作）を WordPress のテーマにしたもの。見た目と動き（オープニング、「YM」の島、イラスト、スクロールの演出）は静的版と同じ。

## 入れ方

1. `wp-theme/meguru.zip` をダウンロードする（GitHub でファイルを開いて「Download raw file」）
2. WordPress の管理画面 → 外観 → テーマ → 新規追加 → テーマのアップロード で zip を選び、有効化する
3. 有効化すると、自動で次のものができる
   - 固定ページ「これまでのこと」（スラッグ `history`）
   - 「実績」に、はじめの2件（GOAL ACHIEVEMENT PROGRAM、HARBOR）と、そのサムネイル画像
4. 設定 → パーマリンク を「投稿名」にしておく（`/history/` のURLになる）

トップページの設定は不要（テーマの `front-page.php` が自動でトップになる）。

## 管理画面でできること

| やりたいこと | 場所 |
| --- | --- |
| 実績を追加・編集・並べ替え | 左メニューの「実績」。タイトル＝サイト名、アイキャッチ画像＝サムネイル（横長 16:10）、「実績の情報」＝種類とURL、「ページ属性」の順序＝並び順（小さい数字が左） |
| 連絡先のメールアドレス、「柳沢恵瑠のサイト」のURL | 外観 → カスタマイズ → めぐるHP制作 |
| ファビコン | 外観 → カスタマイズ → サイト基本情報 → サイトアイコン（未設定のときは緑のファビコン） |
| ほかのページ（プライバシーポリシーなど） | 固定ページを作れば、同じ緑のテイストで表示される（`index.php`） |

トップと「これまでのこと」の文章・イラストは、演出が崩れないようにテンプレートに直接書いてある。
直すときは `meguru/front-page.php`・`meguru/page-history.php` を編集する。

## ファイル

```
meguru/
  style.css          テーマの名前など（WordPress が読む）
  functions.php      CSS・JS の読み込み、タイトル・OGP、カスタマイザー
  inc/works.php      「実績」の登録と入力欄
  header.php / footer.php
  front-page.php     トップ（hp/index.html と同じ）
  page-history.php   これまでのこと（hp/history.html と同じ）
  index.php          そのほかのページ
  assets/css/main.css  ← hp/style.css のコピー
  assets/css/wp.css    WordPress 用の追加分（管理バーとの重なり、ほかのページの見た目）
  assets/js/*.js       ← hp/ の JS のコピー
  assets/images/       ← hp/images のコピー
```

## hp/ を直したとき

CSS・JS・画像は `hp/` からのコピー。`hp/` を直したら、次を実行するとテーマにも反映され、`meguru.zip` も作り直される。

```bash
./wp-theme/build.sh
```

`hp/index.html`・`hp/history.html` の文章を直したときは、`front-page.php`・`page-history.php` の同じところも直す。

※ `wp-theme/` はメインサイトの Worker では配信しない（ルートの `.assetsignore` に書いてある）。
