# THE STANDARD — WordPress Theme

A WordPress packaging of THE STANDARD homepage & article-page redesign prototype.

## What this is

A **static** build: the page content ships in `assets/js/data.js` and is rendered
client-side with **React** (loaded from a CDN) + **Babel Standalone**. WordPress
supplies the shell — `<head>`, enqueued fonts/CSS, and the theme/article URLs.
It does **not** yet read posts from the WordPress database.

Includes two screens:

- **Homepage** — Hero, Opinion, Popular, Latest, Video, Shorts, Events
- **Article page** — full article layout with related-news sidebar

## File structure

```
the-standard-theme/
├── style.css                     # Theme header (metadata only)
├── functions.php                 # Setup, asset enqueue, runtime <script> helper
├── header.php                    # <head> + <body> open (wp_head)
├── footer.php                    # scroll-reveal + wp_footer
├── front-page.php                # Front page  → homepage
├── index.php                     # Fallback    → homepage
├── single.php                    # Single post → article page
├── page-article.php              # Page template "THE STANDARD — Article"
├── template-parts/
│   ├── home-app.php              # Homepage React mount
│   └── article-app.php           # Article React mount (reads ?id=)
└── assets/
    ├── css/main.css              # Full stylesheet
    ├── js/data.js                # All content (ARTICLES, POPULAR, VIDEOS, …)
    ├── js/components.jsx          # React components (Header, Hero, ArticlePage, …)
    └── images/                    # logo, event poster
```

## Install

1. Zip the `the-standard-theme` folder (the zip's top-level folder must be
   `the-standard-theme` containing `style.css`).
2. WP admin → **Appearance → Themes → Add New → Upload Theme** → choose the zip → **Activate**.
   (Or copy the folder into `wp-content/themes/` directly.)

## Two one-time setup steps

The homepage renders on the site root automatically. To make the article links work:

1. **Create the article Page.** Pages → Add New → title e.g. `Article`,
   set the **slug to `article`**, and under *Page Attributes → Template* choose
   **“THE STANDARD — Article”**. Publish.
   Homepage cards link to `<article-page>?id=<article-id>`; the theme detects
   this page automatically (`ts_article_base()` in `functions.php`).
2. **(Optional) Pretty permalinks.** Settings → Permalinks → pick any option
   other than “Plain” so the `?id=` query is preserved on the article page.

> The article page also renders for any normal post via `single.php`
> (it falls back to the first article when no `?id=` is given).

## Editing content

All copy, images and links live in **`assets/js/data.js`** — edit the
`window.ARTICLES`, `POPULAR`, `VIDEOS`, `SHORT_CLIPS`, `EVENTS`, `OPINIONS`
arrays. Layout/components are in `assets/js/components.jsx`; styles in
`assets/css/main.css`. After editing, bump `TS_THEME_VERSION` in
`functions.php` to bust caches.

## Notes / limitations

- **Babel-in-browser** compiles JSX on each page load — great for a prototype,
  not ideal for production traffic. For a production rollout, precompile
  `components.jsx` and the inline apps to plain JS and drop the Babel CDN.
- **Static content.** To drive the theme from real WordPress posts, replace the
  `window.ARTICLES` lookup in `template-parts/*.php` with data emitted from the
  WP loop (e.g. `wp_localize_script`) — the components already accept a plain
  article object shape: `{ id, category, title, excerpt, image, time, author, readTime }`.
- Requires a network connection at runtime (React/Babel load from unpkg.com).
