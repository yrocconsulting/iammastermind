#!/usr/bin/env python3
"""Convert a site snapshot (tools/snapshot.py) into theme files.

Usage: python3 tools/build_theme.py [--src .snapshot] [--theme wp-content/themes/iammastermind]

Writes, inside the theme:
  assets/_astro/*                       compiled CSS/JS from the original build
  layouts/<layout>/<lang>.header.html   shared site header (nav, toggles, island runtime)
  layouts/<layout>/<lang>.footer.html   shared footer
  layouts/<layout>/<lang>.head.html     default <head> for WordPress-native pages
  snapshots/<lang>/<key>.head.html      per-page <head> contents
  snapshots/<lang>/<key>.main.html      per-page <main> + page scripts
  snapshots/<lang>/<key>.tail.html      page scripts the original emitted after </html> (optional)
  snapshots/manifest.php                key => layout / html class / body class

Placeholders resolved at render time by inc/snapshots.php:
  {{IAM_ASSETS}}  theme assets URL      {{IAM_HOME}}  home_url() (no trailing slash)
"""
import argparse, hashlib, json, os, re, shutil

LANGS = ("en", "es")
LAYOUT_DEFAULT_PAGE = {"site": "blog", "retreat": "retreats"}
POST_SAMPLE = "blog/beyond-the-peak-the-neuroscience-of-augmented-human-intelligence"


def rewrite(s):
    s = re.sub(r'(["\'(])/_astro/', r"\1{{IAM_ASSETS}}/_astro/", s)
    s = re.sub(r"https?://(www\.)?iammastermind\.com", "{{IAM_HOME}}", s)
    return s


def norm(s):
    s = re.sub(r'\s(uid|data-preact-island-id)="[^"]*"', "", s)
    return hashlib.md5(re.sub(r"\s+", " ", s).strip().encode()).hexdigest()


def split(html):
    m = re.search(r"<html([^>]*)>", html)
    attrs = m.group(1)
    html_class = (re.search(r'class="([^"]*)"', attrs) or [None, ""])[1]
    html_lang = (re.search(r'lang="([^"]*)"', attrs) or [None, ""])[1]
    hs = html.index("<head>") + 6
    he = html.index("</head>")
    bm = re.search(r"<body([^>]*)>", html)
    body_class = (re.search(r'class="([^"]*)"', bm.group(1)) or [None, ""])[1]
    body = html[bm.end():html.rindex("</body>")]
    # Astro emits some page scripts after </html>; browsers run them at the end of <body>.
    tail = html[html.rindex("</body>") + 7:].replace("</html>", "").strip()
    ms = body.find("<main")
    fs = body.rfind("<footer")
    return dict(html_class=html_class, html_lang=html_lang, head=html[hs:he].strip(), body_class=body_class,
                header=body[:ms], main=body[ms:fs], footer=body[fs:], tail=tail)


def php_str(s):
    return "'" + s.replace("\\", "\\\\").replace("'", "\\'") + "'"


def main():
    ap = argparse.ArgumentParser()
    ap.add_argument("--src", default=".snapshot")
    ap.add_argument("--theme", default="wp-content/themes/iammastermind")
    a = ap.parse_args()
    pages = json.load(open(os.path.join(a.src, "pages.json")))
    T = a.theme

    # assets
    adst = os.path.join(T, "assets", "_astro")
    shutil.rmtree(adst, ignore_errors=True)
    os.makedirs(adst)
    for f in os.listdir(os.path.join(a.src, "_astro")):
        s = open(os.path.join(a.src, "_astro", f), encoding="utf-8").read()
        if f.startswith("client.") and f.endswith(".js"):
            # vite preload helper resolves "_astro/x.js" against "/" -> point it at the theme
            s = s.replace('L=function(t){return"/"+t}',
                          'L=function(t){return(window.IAM_ASSET_BASE||"/")+t}')
        open(os.path.join(adst, f), "w", encoding="utf-8").write(s)

    for d in ("snapshots", "layouts"):
        shutil.rmtree(os.path.join(T, d), ignore_errors=True)

    layouts = {}   # (layout, lang) -> {header, footer, hh, fh}
    header_layout = {}  # header hash -> layout name
    manifest = {}
    warnings = []
    # first pass: identify layouts from their representative pages
    for name, key in LAYOUT_DEFAULT_PAGE.items():
        for lang in LANGS:
            p = split(open(os.path.join(a.src, "pages", lang, key + ".html"), encoding="utf-8").read())
            layouts[(name, lang)] = dict(p, hh=norm(p["header"]), fh=norm(p["footer"]))
            header_layout[(norm(p["header"]), lang)] = name
            d = os.path.join(T, "layouts", name)
            os.makedirs(d, exist_ok=True)
            open(os.path.join(d, lang + ".header.html"), "w").write(rewrite(p["header"]).strip() + "\n")
            open(os.path.join(d, lang + ".footer.html"), "w").write(rewrite(p["footer"]).strip() + "\n")
            head = re.sub(r"<title>.*?</title>", "", p["head"], flags=re.S)
            head = re.sub(r'<meta name="description"[^>]*>', "", head)
            open(os.path.join(d, lang + ".head.html"), "w").write(rewrite(head) + "\n")
            json.dump({"html_class": p["html_class"], "body_class": p["body_class"]},
                      open(os.path.join(d, lang + ".json"), "w"))

    for key in pages:
        entry = {}
        for lang in LANGS:
            p = split(open(os.path.join(a.src, "pages", lang, key + ".html"), encoding="utf-8").read())
            hh, fh = norm(p["header"]), norm(p["footer"])
            layout = header_layout.get((hh, lang))
            main_html = p["main"]
            if layout is None or layouts[(layout, lang)]["fh"] != fh:
                # unusual page: keep its own header/footer inline
                warnings.append(f"{lang}/{key}: custom header/footer kept inline")
                layout = "none"
                main_html = p["header"] + p["main"] + p["footer"]
            entry[lang] = {"layout": layout, "html_lang": p["html_lang"] or lang, "html_class": p["html_class"], "body_class": p["body_class"]}
            base = os.path.join(T, "snapshots", lang, key)
            os.makedirs(os.path.dirname(base), exist_ok=True)
            open(base + ".head.html", "w").write(rewrite(p["head"]) + "\n")
            open(base + ".main.html", "w").write(rewrite(main_html).strip() + "\n")
            if p["tail"]:
                open(base + ".tail.html", "w").write(rewrite(p["tail"]) + "\n")
            if key == POST_SAMPLE:
                tail = main_html[main_html.rindex("</main>") + 7:]
                open(os.path.join(T, "layouts", "site", lang + ".post-scripts.html"), "w").write(rewrite(tail).strip() + "\n")
        manifest[key] = entry

    lines = ["<?php", "// Generated by tools/build_theme.py - do not edit by hand.", "return array("]
    for key, e in sorted(manifest.items()):
        inner = ", ".join(
            f"{php_str(l)} => array('layout' => {php_str(v['layout'])}, 'html_lang' => {php_str(v['html_lang'])}, 'html_class' => {php_str(v['html_class'])}, 'body_class' => {php_str(v['body_class'])})"
            for l, v in e.items())
        lines.append(f"\t{php_str(key)} => array({inner}),")
    lines.append(");")
    open(os.path.join(T, "snapshots", "manifest.php"), "w").write("\n".join(lines) + "\n")
    for w in warnings:
        print("WARN", w)
    print(f"built {len(manifest)} pages x {len(LANGS)} languages into {T}")


if __name__ == "__main__":
    main()
