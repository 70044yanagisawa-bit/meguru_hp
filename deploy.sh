#!/bin/zsh
# 変更を GitHub に保存する。Cloudflare とつないであるので、push すると自動で公開サイトに反映される（1〜2分）。
# 使い方：  ./deploy.sh "変更内容のメモ"
set -e
cd "$(dirname "$0")"

msg="${1:-サイトを更新}"
if [[ -n "$(git status --porcelain)" ]]; then
  git add -A
  git commit -m "$msg"
fi
git push
echo "\nGitHub に保存しました。1〜2分で公開サイトに反映されます → https://meguru-hp.mgr-lab.workers.dev"
