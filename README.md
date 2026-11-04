# I AM Mastermind — WordPress theme

Custom WordPress theme that reproduces **https://iammastermind.com** page for page
(English and Spanish), for the staging site **iammastermind.yroc.host** on SiteGround.
The membership/training area is handled by **Go High Level (GHL)**. The site sends leads
there and links members to the GHL portal.

```
wp-content/themes/iammastermind/   ← the theme (this is what gets deployed)
tools/snapshot.py                  ← crawls the live site (EN + ES) into .snapshot/
tools/build_theme.py               ← turns .snapshot/ into theme files
tools/deploy.sh                    ← rsync the theme to SiteGround over SSH
.github/workflows/deploy-staging.yml  ← optional auto-deploy on push to main
docs/GHL-INTEGRATION.md            ← what is wired to Go High Level and what else is possible
docs/DEPLOYMENT.md                 ← first-time staging setup + SiteGround notes
```

## How it works

The original site is an Astro + Tailwind build. To get pages that look exactly the same,
the theme reuses the original compiled CSS/JS and markup:

| Piece | Where |
|---|---|
| Compiled Tailwind CSS, Preact islands (theme + language toggles), page scripts | `assets/_astro/` |
| Shared header/nav and footer per layout (main site, Retreats) × language | `layouts/<site\|retreat>/<en\|es>.{header,footer,head}.html` |
| Each page's `<head>` (title, description, OG, JSON-LD) and `<main>` | `snapshots/<en\|es>/<path>.{head,main,tail}.html` |
| URL → layout/classes map | `snapshots/manifest.php` (generated) |

**Routing.** Every URL from the original site (32 URLs: home, about, programs, retreats,
events, blog articles, legal, challenge, contact…) is served from its snapshot. The router
is in `inc/snapshots.php`.

**Language.** It works the same way as the original: the EN/ES toggle sets a `lang` cookie,
and the server returns that language. `?lang=es` also works and sets the cookie.

**Editing a page in WordPress.** Theme activation creates a WordPress Page for each URL and
leaves the content empty. While the content is empty, the snapshot is shown. Add content
in the editor, or tick **"Use WordPress content instead of the snapshot"** in the sidebar
box, and that page switches to the native template (same header and footer). That way you
can rebuild pages one at a time.

**Blog.** The existing articles are snapshots. New Posts use a native `single.php` that copies
the original article design, at `/blog/<slug>/`. The `/blog` listing is still the snapshot,
so new posts don't show up there yet. Making it dynamic is the next step (see Roadmap).

**Forms.** The original JavaScript posts to `/api/newsletter`, `/api/contact` and (for the Inner
Circle application) to `/inner-circle`. The theme handles those same URLs, so the scripts run
unchanged. Each submission is:
1. saved in WP admin under **Form submissions**,
2. sent to GHL (Inbound Webhook and/or API v2),
3. optionally emailed to you.

Configure all of this under **Settings → I AM Mastermind**.

## Verified

Checked against the live site on 2026-11-04 with a local WordPress 6.9 + PHP 8.3:

- Full-page screenshots of all 32 URLs, EN at 1440px and ES at 390px: identical
  (0.00–0.32% pixel difference; the 0.32% is the About hero video frame).
- Rendered HTML matches the original on every page in both languages, apart from
  asset URLs pointing at the theme folder.
- Language toggle, light/dark toggle, Retreat page transitions, newsletter, contact,
  Inner Circle forms, honeypot and email validation all tested. No JS errors.

## Re-syncing from the live site

If the live site changes before cut-over:

```bash
python3 tools/snapshot.py          # crawl → .snapshot/
python3 tools/build_theme.py       # rebuild theme files
```

`build_theme.py` regenerates `assets/_astro`, `layouts/` and `snapshots/`. Don't hand-edit
those folders if you plan to re-sync. Once you start customizing, stop re-syncing and edit
the snapshot files (or move the page to WordPress content).

## Things the original site does that you should know about

- **Stripe payment links** on program/retreat pages point to the **live** Stripe account.
  A test click on staging takes you to a real checkout.
- **Google Analytics** (`G-ZP07G84RHF`) is turned off when `WP_ENVIRONMENT_TYPE` is not
  `production`. Set it to `staging` on the staging site (see DEPLOYMENT.md).
- `/blog` and `/privacy` overflow sideways on phones on the live site too. The clone copies
  that exactly. Worth fixing after sign-off.
- Some program pages exist twice on the live site (`/programs/business-matrix` and
  `/programs/business-matrix-6266`, etc.), and three Spanish blog URLs just show the blog
  index. Both are copied as-is.

## Roadmap (after sign-off)

1. Move editable copy (hero text, program cards, testimonials, events) into WordPress
   (block patterns or ACF fields) one page at a time.
2. Make `/blog` and `/events` listings dynamic from Posts and an Events post type.
3. Move Spanish from a cookie to `/es/...` URLs (better for SEO and caching).
4. Connect the GHL calendar / forms / chat widget where you want them (docs/GHL-INTEGRATION.md).
