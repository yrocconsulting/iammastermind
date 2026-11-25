#!/usr/bin/env python3
"""Report classes used in theme templates that the original CSS doesn't define.

Unprefixed classes must exist in assets/_astro/global.*.css (the frozen original
build). New styles must use the tw: prefix (compiled by tools/tailwind into
assets/iam.css). Usage: python3 tools/check_classes.py [files...]
"""
import glob, re, sys

THEME = "wp-content/themes/iammastermind"
css = open(glob.glob(THEME + "/assets/_astro/global.*.css")[0], encoding="utf-8").read()


def esc(c):
    return re.sub(r"([^A-Za-z0-9_-])", r"\\\1", c)


files = sys.argv[1:] or (glob.glob(THEME + "/parts/**/*.php", recursive=True) + glob.glob(THEME + "/inc/nav.php") + glob.glob(THEME + "/*.php"))
missing = {}
for f in files:
    src = open(f, encoding="utf-8").read()
    for attr in re.findall(r"class=[\"']([^\"']*)[\"']", src) + re.findall(r"iam_icon\([^,]+,\s*'([^']+)'", src):
        if "<?" in attr or "?>" in attr:
            continue  # computed in PHP
        for c in attr.split():
            if c.startswith("tw:") or "<?" in c or "$" in c or "{" in c or "%" in c or c in ("lucide", "icon-gold", "group", "dropdown-wrapper", "dropdown-content", "gooey-container", "gooey-item", "mobile-accordion", "accordion-content", "iam-lead-form", "iam-form-msg", "share-btn") or c.startswith("lucide-"):
                continue
            if "." + esc(c) not in css:
                missing.setdefault(c, set()).add(f.replace(THEME + "/", ""))
for c, fs in sorted(missing.items()):
    print(f"{c:45s} {', '.join(sorted(fs))}")
print(f"{len(missing)} classes not in the original CSS (use tw: versions)")
sys.exit(1 if missing else 0)
