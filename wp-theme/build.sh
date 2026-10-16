#!/usr/bin/env bash
# hp/ の CSS・JS・画像を WordPress テーマにコピーし、アップロード用の zip を作る。
# hp/ 側を直したら、これを実行してテーマにも反映する。
#   ./wp-theme/build.sh        → wp-theme/meguru.zip ができる
set -euo pipefail
cd "$(dirname "$0")"
SRC=../hp
THEME=meguru

cp "$SRC/style.css" "$THEME/assets/css/main.css"
for f in draw.js scene.js illust.js main.js; do cp "$SRC/$f" "$THEME/assets/js/$f"; done
mkdir -p "$THEME/assets/images/works"
cp "$SRC/images/me.jpg" "$THEME/assets/images/"
cp "$SRC"/images/works/*.jpg "$THEME/assets/images/works/"
[ -f "$SRC/images/ogp.jpg" ] && cp "$SRC/images/ogp.jpg" "$THEME/assets/images/"
cp "$SRC/favicon.svg" "$THEME/favicon.svg"

rm -f "$THEME.zip"
zip -rq "$THEME.zip" "$THEME" -x '*.DS_Store'
echo "できました：wp-theme/$THEME.zip"
