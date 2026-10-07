#!/bin/zsh
# 変更を GitHub に保存して、Cloudflare の公開サイトに反映する。
# 使い方：  ./deploy.sh "変更内容のメモ"
set -e
cd "$(dirname "$0")"

msg="${1:-サイトを更新}"
if [[ -n "$(git status --porcelain)" ]]; then
  git add -A
  git commit -m "$msg"
fi
git push

CLOUDFLARE_ACCOUNT_ID=65716fe5d4ca4cda2e57c9b00f352744 npx -y wrangler@latest deploy
echo "\n公開しました → https://meguru-hp.mgr-lab.workers.dev"
