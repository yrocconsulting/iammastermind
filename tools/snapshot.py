#!/usr/bin/env python3
"""Crawl the live iammastermind.com (EN + ES) and save raw HTML + compiled assets.

Usage:  python3 tools/snapshot.py [--base https://iammastermind.com] [--out .snapshot]

The live site is an Astro build. Language is chosen server-side via a `lang`
cookie (en|es), so every page is fetched twice.
"""
import argparse, json, os, re, subprocess, sys

UA = "Mozilla/5.0 (compatible; iam-snapshot/1.0)"
SKIP = re.compile(r"^/(_astro|dashboard|login|reset-password|api|forms|quiz)(/|$)")
ASSET_EXT = re.compile(r"\.(css|js|svg|png|jpe?g|gif|webp|xml|ico|txt|mp4|pdf)$", re.I)


def fetch(url, dest=None, cookie=None):
    cmd = ["curl", "-sSL", "-A", UA, "-w", "%{http_code}"]
    if cookie:
        cmd += ["-b", cookie]
    cmd += ["-o", dest or "/dev/null", url]
    return subprocess.run(cmd, capture_output=True, text=True).stdout.strip()


def key_for(path):
    return "index" if path in ("", "/") else path.strip("/")


def main():
    ap = argparse.ArgumentParser()
    ap.add_argument("--base", default="https://iammastermind.com")
    ap.add_argument("--out", default=".snapshot")
    a = ap.parse_args()
    base = a.base.rstrip("/")
    os.makedirs(a.out, exist_ok=True)

    sm = os.path.join(a.out, "sitemap.xml")
    fetch(base + "/sitemap.xml", sm)
    queue = [u.replace(base, "") or "/" for u in re.findall(r"<loc>([^<]+)", open(sm).read())]
    seen, pages = set(), []
    while queue:
        p = queue.pop(0).split("#")[0].split("?")[0] or "/"
        if p != "/" and p.endswith("/"):
            p = p[:-1]
        if p in seen or SKIP.match(p) or ASSET_EXT.search(p):
            continue
        seen.add(p)
        k = key_for(p)
        ok = True
        for lang in ("en", "es"):
            d = os.path.join(a.out, "pages", lang, k + ".html")
            os.makedirs(os.path.dirname(d), exist_ok=True)
            code = fetch(base + p, d, "lang=" + lang)
            print(code, lang, p, flush=True)
            ok = ok and code == "200"
        if not ok:
            continue
        pages.append(k)
        html = open(os.path.join(a.out, "pages", "en", k + ".html"), encoding="utf-8", errors="ignore").read()
        queue += re.findall(r'href="(/[^"#?]*)', html)

    # compiled Astro assets (+ their relative imports)
    adir = os.path.join(a.out, "_astro")
    os.makedirs(adir, exist_ok=True)
    want = set()
    for lang in ("en", "es"):
        for k in pages:
            h = open(os.path.join(a.out, "pages", lang, k + ".html"), encoding="utf-8").read()
            want |= set(re.findall(r"/_astro/([A-Za-z0-9_.\-]+\.(?:js|css))", h))
    done = set()
    while want - done:
        for f in sorted(want - done):
            done.add(f)
            print(fetch(base + "/_astro/" + f, os.path.join(adir, f)), "asset", f, flush=True)
            if f.endswith(".js"):
                js = open(os.path.join(adir, f), encoding="utf-8").read()
                want |= set(re.findall(r'["\']\./([A-Za-z0-9_.\-]+\.(?:js|css))["\']', js))
                want |= set(re.findall(r'["\']_astro/([A-Za-z0-9_.\-]+\.(?:js|css))["\']', js))
    json.dump(sorted(pages), open(os.path.join(a.out, "pages.json"), "w"), indent=1)
    print(len(pages), "pages,", len(done), "assets")


if __name__ == "__main__":
    sys.exit(main())
