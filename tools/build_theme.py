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


# Analytics/tracking removed from the clone (owner's request). Matches the GA4 gtag
# loader, its inline config block and the comment Astro emits before them.
ANALYTICS = [
    re.compile(r"<!--\s*Google tag \(gtag\.js\)\s*-->", re.I),
    re.compile(r'<script[^>]*src="https://www\.googletagmanager\.com/[^"]*"[^>]*>\s*</script>', re.I),
    re.compile(r"<script>(?:(?!</script>).)*?gtag\(\s*[\"']config[\"'].*?</script>", re.S),
]


def strip_analytics(s):
    for rx in ANALYTICS:
        s = rx.sub("", s)
    return s


# Visible-text edits agreed with the client (2026-11-25 call): "Mastermind Ascension"
# becomes Retreats. Only display text and alt/title text change; URLs and image
# filenames (hyphenated/underscored, lower case) are never matched.
CONTENT_EDITS = {
    "all": [
        (r"\| The Mastermind Ascension", "| I AM Retreats"),
        (r"MASTERMIND ASCENSION RETREAT", "I AM MASTERMIND RETREAT"),
        (r"The Ascension Journal", "The Retreat Journal"),
        (r"&copy; (\d{4}) Mastermind Ascension\.", r"&copy; \1 I AM Mastermind."),
        (r'alt="Mastermind Ascension Logo"', 'alt="I AM Retreats logo"'),
        (r"The Mastermind Ascension", "The I AM Retreat"),
        (r"(?<=entire )Mastermind Ascension", "I AM Retreat"),
        (r'leading-tight">Mastermind</span>', 'leading-tight">I AM Mastermind</span>'),
        (r'leading-tight">Ascension</span>', 'leading-tight">Retreats</span>'),
        (r"the Ascension Protocol", "the I AM Retreat protocol"),
        (r"Initiate Your Ascension", "Begin Your Application"),
        (r">Mastermind Ascension<", ">I AM Retreat<"),
        (r"El Protocolo de la Ascensión Mastermind", "El Protocolo del I AM Retreat"),
        (r"La Ascensión Mastermind", "El I AM Retreat"),
        (r"el Protocolo de Ascensión", "el protocolo del I AM Retreat"),
        (r"Ascensión Mastermind", "I AM Retreat"),
    ],
    "en": [(r">(?:The )?Ascension</a>", ">The Retreat</a>")],
    "es": [(r">(?:The )?Ascension</a>", ">El Retiro</a>")],
}


def content_edits(s, lang):
    for rx, rep in CONTENT_EDITS["all"] + CONTENT_EDITS.get(lang, []):
        s = re.sub(rx, rep, s)
    return s


def take_element(html, start):
    """Return the end index of the <div> element that opens at `start`."""
    depth, i = 0, start
    for m in re.finditer(r"<(/?)div\b", html[start:]):
        depth += -1 if m.group(1) else 1
        if depth == 0:
            return start + html[start:].index(">", m.start()) + 1
    raise ValueError("unbalanced div")


def nav_placeholders(header, lang, layout_dir):
    """Swap the site header's two hard-coded menus for theme-rendered menus
    (inc/nav.php), keeping the original Coaching Programs dropdown as the Mind menu."""
    d0 = header.find('<div class="hidden lg:flex shrink-0 justify-center')
    d1 = header.find('<div class="hidden lg:flex flex-1')
    m0 = header.find('<div class="flex flex-col gap-4 flex-1"')
    m1 = header.find('<div class="mt-auto pt-8 pb-4 space-y-6"')
    if min(d0, d1, m0, m1) < 0:
        raise SystemExit("site header markup changed: nav markers not found")
    desk, mob = header[d0:d1], header[m0:m1]
    # original programs dropdown (desktop) and accordion (mobile)
    b = desk.find("dropdown-wrapper", desk.find("dropdown-wrapper") + 1)  # 2nd dropdown = programs
    b = desk.rfind("<div", 0, b)
    prog = desk[b:take_element(desk, b)]
    a = mob.find("mobile-accordion", mob.find("mobile-accordion") + 1)
    a = mob.rfind("<div", 0, a)
    acc = mob[a:take_element(mob, a)]
    label = re.search(r'<span class="relative z-10"[^>]*>([^<]+)</span>', prog).group(1)
    prog = prog.replace(">" + label + "</span>", ">{{LABEL}}</span>", 1)
    acc = re.sub(r"(<button[^>]*>)\s*" + re.escape(label) + r"\s*<svg", r"\1 {{LABEL}} <svg", acc, 1)
    open(os.path.join(layout_dir, lang + ".nav-programs.html"), "w").write(prog.strip() + "\n")
    open(os.path.join(layout_dir, lang + ".nav-programs-mobile.html"), "w").write(acc.strip() + "\n")
    return header[:d0] + "{{IAM_NAV_DESKTOP}} " + header[d1:m0] + "{{IAM_NAV_MOBILE}} " + header[m1:]


def rewrite(s, lang=None):
    s = strip_analytics(s)
    if lang:
        s = content_edits(s, lang)
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
            header = rewrite(p["header"], lang)
            if name == "site":
                header = nav_placeholders(header, lang, d)
            open(os.path.join(d, lang + ".header.html"), "w").write(header.strip() + "\n")
            open(os.path.join(d, lang + ".footer.html"), "w").write(rewrite(p["footer"], lang).strip() + "\n")
            head = re.sub(r"<title>.*?</title>", "", p["head"], flags=re.S)
            head = re.sub(r'<meta name="description"[^>]*>', "", head)
            open(os.path.join(d, lang + ".head.html"), "w").write(rewrite(head, lang) + "\n")
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
            open(base + ".head.html", "w").write(rewrite(p["head"], lang) + "\n")
            open(base + ".main.html", "w").write(rewrite(main_html, lang).strip() + "\n")
            if p["tail"]:
                open(base + ".tail.html", "w").write(rewrite(p["tail"], lang) + "\n")
            if key == POST_SAMPLE:
                tail = main_html[main_html.rindex("</main>") + 7:]
                open(os.path.join(T, "layouts", "site", lang + ".post-scripts.html"), "w").write(rewrite(tail, lang).strip() + "\n")
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
