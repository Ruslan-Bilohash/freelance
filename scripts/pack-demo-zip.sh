#!/usr/bin/env bash
# 30-day demo ZIP for GitHub Release / Packages.
set -euo pipefail
ROOT="$(cd "$(dirname "$0")/.." && pwd)"
cd "$ROOT"
VER="${1:-dev}"
OUT="dist/freelance-cms-demo-30d-${VER}.zip"
mkdir -p dist
rm -f "$OUT"
zip -rq "$OUT" . \
  -x ".git/*" \
  -x "data/settings.json" \
  -x "data/users.json" \
  -x "**/.env" \
  -x "**/.env.*" \
  -x "dist/*" \
  -x "*.zip"
echo "Created $OUT ($(du -h "$OUT" | cut -f1))"