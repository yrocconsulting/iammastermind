#!/usr/bin/env bash
# Deploy the theme to the SiteGround staging site over SSH.
#
# Required env (or put them in .env next to this repo's root - it is gitignored):
#   SG_HOST   e.g. ssh.iammastermind.yroc.host or the server hostname from Site Tools > Devs > SSH Keys Manager
#   SG_USER   e.g. u1234-abcdefgh
# Optional:
#   SG_PORT   default 18765 (SiteGround's SSH port)
#   SG_WP     WordPress root on the server, default ~/www/iammastermind.yroc.host/public_html
#   SG_KEY    path to the private key, default ~/.ssh/id_ed25519
#
# Usage: tools/deploy.sh [--setup]    (--setup also activates the theme and creates pages)
set -euo pipefail
cd "$(dirname "$0")/.."
[ -f .env ] && set -a && . ./.env && set +a

: "${SG_HOST:?set SG_HOST}"
: "${SG_USER:?set SG_USER}"
SG_PORT="${SG_PORT:-18765}"
SG_WP="${SG_WP:-www/iammastermind.yroc.host/public_html}"
SG_KEY="${SG_KEY:-$HOME/.ssh/id_ed25519}"
SSH="ssh -p $SG_PORT -i $SG_KEY -o StrictHostKeyChecking=accept-new"

echo "==> Syncing theme to $SG_USER@$SG_HOST:$SG_WP/wp-content/themes/iammastermind"
rsync -az --delete -e "$SSH" \
  wp-content/themes/iammastermind/ \
  "$SG_USER@$SG_HOST:$SG_WP/wp-content/themes/iammastermind/"

if [ "${1:-}" = "--setup" ]; then
  echo "==> Activating theme + creating pages"
  $SSH "$SG_USER@$SG_HOST" "cd $SG_WP && wp theme activate iammastermind && wp iam setup"
fi

echo "==> Purging SiteGround cache (ignored if Speed Optimizer is not installed)"
$SSH "$SG_USER@$SG_HOST" "cd $SG_WP && (wp sg purge || true)" || true
echo "Done."
