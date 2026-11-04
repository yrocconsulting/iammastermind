# Deploying to staging (SiteGround)

Staging: **https://iammastermind.yroc.host**

## One-time setup

1. **WordPress.** Install WordPress on `iammastermind.yroc.host` (Site Tools → WordPress → Install).
2. **SSH key.** Site Tools → Devs → **SSH Keys Manager**. Create or import a key and note the
   host, username (`u…`) and port (**18765**).
3. **wp-config.php** (Site Tools → File Manager, or over SSH), above "That's all, stop editing":
   ```php
   define( 'WP_ENVIRONMENT_TYPE', 'staging' );   // turns off the live GA property
   ```
   Also tick *Settings → Reading → Discourage search engines* so staging isn't indexed.
4. **Deploy** from your machine:
   ```bash
   cp .env.example .env   # fill in SG_HOST / SG_USER (and SG_WP if the path differs)
   ```
   ```bash
   tools/deploy.sh --setup
   ```
   `--setup` activates the theme and runs `wp iam setup`, which:
   - creates all pages,
   - sets Home as the front page,
   - sets permalinks to `/blog/%postname%/`.

   If you'd rather not use the command line: zip `wp-content/themes/iammastermind`,
   upload it under *Appearance → Themes → Add New → Upload*, then activate it. Activation
   runs the same setup.
5. **Settings → I AM Mastermind.** Add the GHL details (see GHL-INTEGRATION.md) and click
   **Send test lead**.

## Later deploys

```bash
tools/deploy.sh
```

Or use the GitHub Action (`.github/workflows/deploy-staging.yml`). To turn it on, add:
- repository secrets `SG_HOST`, `SG_USER`, `SG_SSH_KEY` (plus `SG_SSH_PASSPHRASE` if the key has a password),
- repository variable `SG_DEPLOY_ENABLED=true`.

After that, every push to `main` that changes the theme deploys it.

## SiteGround caching and the EN/ES toggle

The original site picks the language from a `lang` cookie, and the theme does the same.
SiteGround's **Dynamic Cache** may serve a cached English page to a Spanish visitor (or the
other way round). After the first deploy, test the toggle on staging with caching on. If the
language doesn't switch:

- quick fix: Speed Optimizer → Caching → turn off Dynamic Caching for staging, or exclude the
  affected URLs;
- proper fix (roadmap): move Spanish to `/es/...` URLs, which also helps SEO.

The theme sends `Vary: Cookie` on snapshot pages.

## Plugins

None are required. If you add an SEO plugin (Yoast/RankMath), note that snapshot pages
already have their own title, description, OG tags and JSON-LD, so the plugin will add a
second set. Only enable SEO output for pages you've moved to WordPress content.
